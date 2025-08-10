<?php

namespace App\Services;

use App\Models\Unit;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Validation\Rule;

class UnitService extends BaseService
{
    public function __construct(Unit $unit)
    {
        parent::__construct($unit);
    }

    /**
     * Get all units with pagination and search
     */
    public function getAllUnits($search = null, $perPage = 10)
    {
        $query = $this->model->query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('symbol', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $query->orderBy('created_at', 'desc');

        return $query->paginate($perPage);
    }

    /**
     * Search units with specific criteria
     */
    public function searchUnits($criteria)
    {
        $query = $this->model->query();

        if (isset($criteria['name'])) {
            $query->where('name', 'like', "%{$criteria['name']}%");
        }

        if (isset($criteria['symbol'])) {
            $query->where('symbol', 'like', "%{$criteria['symbol']}%");
        }

        if (isset($criteria['is_active'])) {
            $query->where('is_active', $criteria['is_active']);
        }

        return $query->get();
    }

    /**
     * Get unit statistics
     */
    public function getStats()
    {
        return [
            'total' => $this->model->count(),
            'active' => $this->model->active()->count(),
            'inactive' => $this->model->inactive()->count(),
        ];
    }

    /**
     * Validate unit data
     */
    public function validateData(array $data, $unitId = null)
    {
        $rules = [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('units', 'name')->ignore($unitId)
            ],
            'symbol' => [
                'nullable',
                'string',
                'max:10',
                Rule::unique('units', 'symbol')->ignore($unitId)
            ],
            'description' => 'nullable|string|max:1000',
            'is_active' => 'boolean'
        ];

        $messages = [
            'name.required' => __('validation.required', ['attribute' => __('app.unit_name')]),
            'name.unique' => __('validation.unique', ['attribute' => __('app.unit_name')]),
            'name.max' => __('validation.max.string', ['attribute' => __('app.unit_name'), 'max' => 255]),
            'symbol.unique' => __('validation.unique', ['attribute' => __('app.unit_symbol')]),
            'symbol.max' => __('validation.max.string', ['attribute' => __('app.unit_symbol'), 'max' => 10]),
            'description.max' => __('validation.max.string', ['attribute' => __('app.description'), 'max' => 1000]),
        ];

        $validator = validator($data, $rules, $messages);

        if ($validator->fails()) {
            return [
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ];
        }

        return [
            'success' => true,
            'message' => 'Validation passed'
        ];
    }

    /**
     * Toggle unit status
     */
    public function toggleUnitStatus($unitId)
    {
        try {
            $unit = $this->findUnit($unitId);
            $unit->is_active = !$unit->is_active;
            $unit->save();

            $status = $unit->is_active ? __('app.activated') : __('app.deactivated');

            return [
                'success' => true,
                'message' => __('app.unit_status_updated', ['status' => $status])
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => __('app.error_updating_unit_status')
            ];
        }
    }

    /**
     * Get active units for dropdowns
     */
    public function getActiveUnits()
    {
        return $this->model->active()
            ->orderBy('name')
            ->get(['id', 'name', 'symbol']);
    }

    /**
     * Find unit by ID
     */
    public function findUnit($unitId)
    {
        return $this->model->findOrFail($unitId);
    }
}
