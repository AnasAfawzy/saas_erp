<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;

class ProductService extends BaseService
{
    public function __construct(Product $model)
    {
        parent::__construct($model);
    }

    /**
     * Get all products with optional pagination
     */
    public function getAllProducts($paginate = true, $perPage = 10)
    {
        try {
            $query = $this->model->with(['category', 'brand', 'unit'])
                ->orderBy('created_at', 'desc');

            if ($paginate) {
                $products = $query->paginate($perPage);
            } else {
                $products = $query->get();
            }

            return [
                'success' => true,
                'data' => $products,
                'message' => __('app.products_retrieved_successfully')
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => __('app.error_retrieving_products'),
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Search products
     */
    public function searchProducts($search, $filters = [], $paginate = true, $perPage = 10)
    {
        try {
            $query = $this->model->with(['category', 'brand', 'unit']);

            if (!empty($search)) {
                $query->search($search);
            }

            // Apply filters
            if (!empty($filters['category_id'])) {
                $query->where('category_id', $filters['category_id']);
            }

            if (!empty($filters['brand_id'])) {
                $query->where('brand_id', $filters['brand_id']);
            }

            if (isset($filters['is_service'])) {
                $query->where('is_service', $filters['is_service']);
            }

            if (isset($filters['is_active'])) {
                $query->where('is_active', $filters['is_active']);
            }

            if (!empty($filters['stock_status'])) {
                switch ($filters['stock_status']) {
                    case 'low':
                        $query->lowStock();
                        break;
                    case 'normal':
                        $query->where('track_inventory', true)
                            ->whereColumn('current_stock', '>', 'min_stock_level');
                        break;
                }
            }

            $query->orderBy('created_at', 'desc');

            if ($paginate) {
                $products = $query->paginate($perPage);
            } else {
                $products = $query->get();
            }

            return [
                'success' => true,
                'data' => $products,
                'message' => __('app.products_retrieved_successfully')
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => __('app.error_searching_products'),
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Get product statistics
     */
    public function getProductStatistics()
    {
        try {
            $total = $this->model->count();
            $active = $this->model->active()->count();
            $inactive = $this->model->inactive()->count();
            $services = $this->model->services()->count();
            $products = $this->model->products()->count();
            $lowStock = $this->model->lowStock()->count();

            return [
                'success' => true,
                'data' => [
                    'total' => $total,
                    'active' => $active,
                    'inactive' => $inactive,
                    'services' => $services,
                    'products' => $products,
                    'low_stock' => $lowStock
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
     * Generate unique product code
     */
    public function generateProductCode($prefix = 'PRD')
    {
        $lastProduct = $this->model->where('code', 'like', $prefix . '%')
            ->orderBy('code', 'desc')
            ->first();

        if (!$lastProduct) {
            return $prefix . '0001';
        }

        $lastNumber = intval(substr($lastProduct->code, strlen($prefix)));
        $newNumber = $lastNumber + 1;

        return $prefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Toggle product status
     */
    public function toggleProductStatus($id)
    {
        try {
            $product = $this->findById($id);

            if (!$product['success']) {
                return $product;
            }

            $productModel = $product['data'];
            $productModel->is_active = !$productModel->is_active;
            $productModel->save();

            $status = $productModel->is_active ? __('app.active') : __('app.inactive');

            return [
                'success' => true,
                'data' => $productModel,
                'message' => __('app.product_status_changed_to', ['status' => $status])
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => __('app.error_changing_product_status'),
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Upload product image
     */
    public function uploadImage($image, $productId)
    {
        try {
            $path = $image->store('products', 'public');

            $product = $this->findById($productId);
            if ($product['success']) {
                $productModel = $product['data'];

                // Delete old image if exists
                if ($productModel->image) {
                    Storage::disk('public')->delete($productModel->image);
                }

                $productModel->image = $path;
                $productModel->save();
            }

            return [
                'success' => true,
                'data' => $path,
                'message' => __('app.image_uploaded_successfully')
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => __('app.error_uploading_image'),
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Delete product image
     */
    public function deleteImage($productId)
    {
        try {
            $product = $this->findById($productId);

            if (!$product['success']) {
                return $product;
            }

            $productModel = $product['data'];

            if ($productModel->image) {
                Storage::disk('public')->delete($productModel->image);
                $productModel->image = null;
                $productModel->save();
            }

            return [
                'success' => true,
                'message' => __('app.image_deleted_successfully')
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => __('app.error_deleting_image'),
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Validate product data
     */
    protected function validateData(array $data, $id = null)
    {
        $rules = [
            'code' => 'required|string|max:50|unique:products,code' . ($id ? ',' . $id : ''),
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'unit_id' => 'required|exists:units,id',
            'purchase_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'wholesale_price' => 'nullable|numeric|min:0',
            'tax_rate' => 'nullable|numeric|min:0|max:100',
            'barcode' => 'nullable|string|max:100|unique:products,barcode' . ($id ? ',' . $id : ''),
            'sku' => 'nullable|string|max:100|unique:products,sku' . ($id ? ',' . $id : ''),
            'weight' => 'nullable|numeric|min:0',
            'min_stock_level' => 'nullable|integer|min:0',
            'max_stock_level' => 'nullable|integer|min:0',
            'reorder_point' => 'nullable|integer|min:0',
            'is_service' => 'boolean',
            'track_inventory' => 'boolean',
            'is_active' => 'boolean'
        ];

        $messages = [
            'code.required' => __('app.product_code_required'),
            'code.unique' => __('app.product_code_exists'),
            'name.required' => __('app.product_name_required'),
            'category_id.required' => __('app.category_required'),
            'category_id.exists' => __('app.category_not_found'),
            'unit_id.required' => __('app.unit_required'),
            'unit_id.exists' => __('app.unit_not_found'),
            'purchase_price.required' => __('app.purchase_price_required'),
            'selling_price.required' => __('app.selling_price_required'),
            'barcode.unique' => __('app.barcode_exists'),
            'sku.unique' => __('app.sku_exists')
        ];

        $validator = validator($data, $rules, $messages);

        if ($validator->fails()) {
            return [
                'success' => false,
                'message' => __('app.validation_error'),
                'errors' => $validator->errors()
            ];
        }

        return [
            'success' => true,
            'message' => __('app.validation_passed')
        ];
    }

    /**
     * Create product with validation
     */
    public function create(array $data): array
    {
        // Generate code if not provided
        if (empty($data['code'])) {
            $data['code'] = $this->generateProductCode();
        }

        $validation = $this->validateData($data);
        if (!$validation['success']) {
            return $validation;
        }

        try {
            $product = $this->model->create($data);

            return [
                'success' => true,
                'data' => $product->load(['category', 'brand', 'unit']),
                'message' => __('app.product_added_successfully')
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => __('app.product_add_failed'),
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Update product with validation
     */
    public function update(int $id, array $data): array
    {
        $validation = $this->validateData($data, $id);
        if (!$validation['success']) {
            return $validation;
        }

        try {
            $result = parent::update($id, $data);

            if ($result['success']) {
                $result['data'] = $result['data']->load(['category', 'brand', 'unit']);
                $result['message'] = __('app.product_updated_successfully');
            }

            return $result;
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => __('app.product_update_failed'),
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Delete product
     */
    public function delete(int $id): array
    {
        try {
            $product = $this->findById($id);

            if (!$product['success']) {
                return $product;
            }

            $productModel = $product['data'];

            // Delete image if exists
            if ($productModel->image) {
                Storage::disk('public')->delete($productModel->image);
            }

            $productModel->delete();

            return [
                'success' => true,
                'message' => __('app.product_deleted_successfully')
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => __('app.product_delete_failed'),
                'error' => $e->getMessage()
            ];
        }
    }
}
