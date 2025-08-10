<?php

namespace App\Services;

use App\Models\Branch;
use Illuminate\Support\Facades\Validator;

class BranchService extends BaseService
{
    public function __construct()
    {
        parent::__construct(new Branch());
    }

    /**
     * Get all branches with optional pagination
     */
    public function getAllBranches($paginate = false, $perPage = 15): array
    {
        try {
            $query = Branch::query()->orderBy('name');

            if ($paginate) {
                $records = $query->paginate($perPage);
                return $this->successResponse('Branches retrieved successfully', $records);
            }

            $records = $query->get();
            return $this->successResponse('Branches retrieved successfully', $records);
        } catch (\Exception $e) {
            return $this->errorResponse('Error retrieving branches: ' . $e->getMessage());
        }
    }

    /**
     * Search branches by query
     */
    public function searchBranches(string $query, bool $paginate = false, int $perPage = 10): array
    {
        try {
            $searchQuery = Branch::query()
                ->where('name', 'like', '%' . $query . '%')
                ->orWhere('location', 'like', '%' . $query . '%')
                ->orderBy('name');

            if ($paginate) {
                $records = $searchQuery->paginate($perPage);
                return $this->successResponse('Branches searched successfully', $records);
            }

            $records = $searchQuery->get();
            return $this->successResponse('Branches searched successfully', $records);
        } catch (\Exception $e) {
            return $this->errorResponse('Error searching branches: ' . $e->getMessage());
        }
    }

    /**
     * Get branches statistics
     */
    public function getBranchStatistics(): array
    {
        return $this->getStatistics();
    }

    /**
     * Toggle branch status
     */
    public function toggleBranchStatus(int $id): array
    {
        return $this->toggleStatus($id);
    }

    /**
     * Get active branches list
     */
    public function getActiveBranches(): array
    {
        return $this->getActive();
    }

    /**
     * Validate branch data
     */
    protected function validateData(array $data, ?int $id = null): array
    {
        $rules = [
            'name' => 'required|string|max:255|unique:branches,name' . ($id ? ",$id" : ''),
            'location' => 'required|string|max:500',
            'is_active' => 'sometimes|boolean',
        ];

        $messages = [
            'name.required' => __('app.branch_name') . ' ' . __('validation.required'),
            'name.unique' => __('app.branch_name') . ' ' . __('validation.unique'),
            'name.max' => __('app.branch_name') . ' ' . __('validation.max.string', ['max' => 255]),
            'location.required' => __('app.branch_location') . ' ' . __('validation.required'),
            'location.max' => __('app.branch_location') . ' ' . __('validation.max.string', ['max' => 500]),
        ];

        $validator = Validator::make($data, $rules, $messages);

        if ($validator->fails()) {
            return $this->errorResponse(
                __('validation.validation_failed'),
                $validator->errors()->toArray(),
                422
            );
        }

        return $this->successResponse('Validation passed');
    }
}
