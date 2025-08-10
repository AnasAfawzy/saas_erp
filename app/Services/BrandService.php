<?php

namespace App\Services;

use App\Models\Brand;
use Illuminate\Pagination\LengthAwarePaginator;

class BrandService extends BaseService
{
    public function __construct(Brand $model)
    {
        parent::__construct($model);
    }

    /**
     * Get all brands with optional pagination
     */
    public function getAllBrands($paginate = true, $perPage = 10)
    {
        try {
            $query = $this->model->orderBy('created_at', 'desc');

            if ($paginate) {
                $brands = $query->paginate($perPage);
            } else {
                $brands = $query->get();
            }

            return [
                'success' => true,
                'data' => $brands,
                'message' => __('app.brands_retrieved_successfully')
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => __('app.error_retrieving_brands'),
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Search brands
     */
    public function searchBrands($search, $paginate = true, $perPage = 10)
    {
        try {
            $query = $this->model->search($search)->orderBy('created_at', 'desc');

            if ($paginate) {
                $brands = $query->paginate($perPage);
            } else {
                $brands = $query->get();
            }

            return [
                'success' => true,
                'data' => $brands,
                'message' => __('app.brands_retrieved_successfully')
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => __('app.error_searching_brands'),
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Get brand statistics
     */
    public function getBrandStatistics()
    {
        try {
            $total = $this->model->count();
            $active = $this->model->active()->count();
            $inactive = $this->model->inactive()->count();

            return [
                'success' => true,
                'data' => [
                    'total' => $total,
                    'active' => $active,
                    'inactive' => $inactive
                ],
                'message' => __('app.statistics_retrieved_successfully')
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => __('app.error_retrieving_statistics'),
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Toggle brand status
     */
    public function toggleBrandStatus($id)
    {
        try {
            $brand = $this->findById($id);

            if (!$brand['success']) {
                return $brand;
            }

            $brandModel = $brand['data'];
            $brandModel->is_active = !$brandModel->is_active;
            $brandModel->save();

            $status = $brandModel->is_active ? __('app.active') : __('app.inactive');

            return [
                'success' => true,
                'data' => $brandModel,
                'message' => __('app.brand_status_changed_to', ['status' => $status])
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => __('app.error_changing_brand_status'),
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Find brand by ID
     */
    public function findBrand($id)
    {
        return $this->findById($id);
    }

    /**
     * Validate brand data
     */
    protected function validateData(array $data, $id = null)
    {
        $rules = [
            'name' => 'required|string|max:255|unique:brands,name' . ($id ? ',' . $id : ''),
            'description' => 'nullable|string|max:1000',
            'logo' => 'nullable|string|max:500',
            'is_active' => 'boolean'
        ];

        $messages = [
            'name.required' => __('app.brand_name_required'),
            'name.unique' => __('app.brand_name_exists'),
            'name.max' => __('app.brand_name_max_length'),
            'description.max' => __('app.brand_description_max_length'),
            'logo.max' => __('app.brand_logo_max_length'),
        ];

        $validator = validator($data, $rules, $messages);

        if ($validator->fails()) {
            return [
                'success' => false,
                'data' => $validator->errors()->toArray(),
                'message' => __('app.validation_failed')
            ];
        }

        return [
            'success' => true,
            'data' => $data
        ];
    }
}
