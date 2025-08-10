<?php

namespace App\Services;

use App\Models\Category;
use Illuminate\Pagination\LengthAwarePaginator;

class CategoryService extends BaseService
{
    public function __construct(Category $model)
    {
        parent::__construct($model);
    }

    /**
     * Get all categories with optional pagination
     */
    public function getAllCategories($paginate = true, $perPage = 10)
    {
        try {
            $query = $this->model->orderBy('created_at', 'desc');

            if ($paginate) {
                $categories = $query->paginate($perPage);
            } else {
                $categories = $query->get();
            }

            return [
                'success' => true,
                'data' => $categories,
                'message' => __('app.categories_retrieved_successfully')
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => __('app.error_retrieving_categories'),
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Search categories
     */
    public function searchCategories($search, $paginate = true, $perPage = 10)
    {
        try {
            $query = $this->model->search($search)->orderBy('created_at', 'desc');

            if ($paginate) {
                $categories = $query->paginate($perPage);
            } else {
                $categories = $query->get();
            }

            return [
                'success' => true,
                'data' => $categories,
                'message' => __('app.categories_retrieved_successfully')
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => __('app.error_searching_categories'),
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Get category statistics
     */
    public function getCategoryStatistics()
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
     * Toggle category status
     */
    public function toggleCategoryStatus($id)
    {
        try {
            $category = $this->findById($id);

            if (!$category['success']) {
                return $category;
            }

            $categoryModel = $category['data'];
            $categoryModel->is_active = !$categoryModel->is_active;
            $categoryModel->save();

            $status = $categoryModel->is_active ? __('app.active') : __('app.inactive');

            return [
                'success' => true,
                'data' => $categoryModel,
                'message' => __('app.category_status_changed_to', ['status' => $status])
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => __('app.error_changing_category_status'),
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Find category by ID
     */
    public function findCategory($id)
    {
        return $this->findById($id);
    }

    /**
     * Validate category data
     */
    protected function validateData(array $data, $id = null)
    {
        $rules = [
            'name' => 'required|string|max:255|unique:categories,name' . ($id ? ',' . $id : ''),
            'description' => 'nullable|string|max:1000',
            'is_active' => 'boolean'
        ];

        $messages = [
            'name.required' => __('app.category_name_required'),
            'name.unique' => __('app.category_name_exists'),
            'name.max' => __('app.category_name_max_length'),
            'description.max' => __('app.category_description_max_length'),
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
