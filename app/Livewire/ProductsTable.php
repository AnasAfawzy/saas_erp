<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Unit;
use App\Services\ProductService;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductsTable extends Component
{
    use WithPagination, WithFileUploads;

    // Search and Filters
    public $search = '';
    public $categoryFilter = '';
    public $brandFilter = '';
    public $typeFilter = '';
    public $statusFilter = '';

    // Modal
    public $showModal = false;
    public $editingProductId = null;

    // Form Fields
    public $code = '';
    public $name = '';
    public $description = '';
    public $category_id = '';
    public $brand_id = '';
    public $unit_id = '';
    public $purchase_price = 0;
    public $selling_price = 0;
    public $wholesale_price = '';
    public $tax_rate = 0;
    public $barcode = '';
    public $sku = '';
    public $weight = '';
    public $notes = '';
    public $is_service = false;
    public $track_inventory = true;
    public $min_stock_level = 0;
    public $max_stock_level = '';
    public $reorder_point = 0;
    public $is_active = true;
    public $image;

    protected $productService;
    protected $paginationTheme = 'bootstrap';

    protected $listeners = [
        'productSaved' => '$refresh',
        'productDeleted' => '$refresh'
    ];

    public function mount()
    {
        $this->productService = app(ProductService::class);
    }

    /**
     * Get product service instance
     */
    protected function getProductService()
    {
        if (!$this->productService) {
            $this->productService = app(ProductService::class);
        }
        return $this->productService;
    }

    public function render()
    {
        // Create empty paginator as default
        $products = new LengthAwarePaginator(
            collect([]),
            0,
            10,
            1,
            ['path' => request()->url(), 'pageName' => 'page']
        );

        $stats = ['total' => 0, 'active' => 0, 'inactive' => 0, 'services' => 0, 'products' => 0, 'low_stock' => 0];

        try {
            $service = $this->getProductService();

            // Build filters
            $filters = [];
            if (!empty($this->categoryFilter)) {
                $filters['category_id'] = $this->categoryFilter;
            }
            if (!empty($this->brandFilter)) {
                $filters['brand_id'] = $this->brandFilter;
            }
            if ($this->typeFilter !== '') {
                $filters['is_service'] = $this->typeFilter == '1';
            }
            if ($this->statusFilter !== '') {
                $filters['is_active'] = $this->statusFilter == '1';
            }

            // Get data using Service
            if (!empty($this->search) || !empty($filters)) {
                $searchResult = $service->searchProducts($this->search, $filters, true, 10);
                if ($searchResult['success']) {
                    $products = $searchResult['data'];
                }
            } else {
                $productsResult = $service->getAllProducts(true, 10);
                if ($productsResult['success']) {
                    $products = $productsResult['data'];
                }
            }

            // Get statistics
            $statsResult = $service->getProductStatistics();
            if ($statsResult['success']) {
                $stats = $statsResult['data'];
            }
        } catch (\Exception $e) {
            $this->dispatch('swal:fire', [
                'icon' => 'error',
                'title' => __('app.error'),
                'text' => __('app.error_loading_data')
            ]);
        }

        return view('livewire.products-table', [
            'products' => $products,
            'stats' => $stats,
            'categories' => Category::active()->orderBy('name')->get(),
            'brands' => Brand::active()->orderBy('name')->get(),
            'units' => Unit::active()->orderBy('name')->get()
        ]);
    }

    // Search and Filter Methods
    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedCategoryFilter()
    {
        $this->resetPage();
    }

    public function updatedBrandFilter()
    {
        $this->resetPage();
    }

    public function updatedTypeFilter()
    {
        $this->resetPage();
    }

    public function updatedStatusFilter()
    {
        $this->resetPage();
    }

    public function clearSearch()
    {
        $this->search = '';
        $this->categoryFilter = '';
        $this->brandFilter = '';
        $this->typeFilter = '';
        $this->statusFilter = '';
        $this->resetPage();
    }

    // Modal Methods
    public function openAddModal()
    {
        $this->resetForm();
        $this->editingProductId = null;
        $this->code = $this->getProductService()->generateProductCode();
        $this->showModal = true;
    }

    public function openEditModal($productId)
    {
        try {
            $result = $this->getProductService()->findById($productId);
            if (!$result['success']) {
                $this->dispatch('error-occurred', [
                    'success' => false,
                    'message' => $result['message']
                ]);
                return;
            }

            $product = $result['data'];
            $this->editingProductId = $productId;
            $this->code = $product->code;
            $this->name = $product->name;
            $this->description = $product->description ?? '';
            $this->category_id = $product->category_id;
            $this->brand_id = $product->brand_id;
            $this->unit_id = $product->unit_id;
            $this->purchase_price = $product->purchase_price;
            $this->selling_price = $product->selling_price;
            $this->wholesale_price = $product->wholesale_price ?? '';
            $this->tax_rate = $product->tax_rate;
            $this->barcode = $product->barcode ?? '';
            $this->sku = $product->sku ?? '';
            $this->weight = $product->weight ?? '';
            $this->notes = $product->notes ?? '';
            $this->is_service = $product->is_service;
            $this->track_inventory = $product->track_inventory;
            $this->min_stock_level = $product->min_stock_level;
            $this->max_stock_level = $product->max_stock_level ?? '';
            $this->reorder_point = $product->reorder_point;
            $this->is_active = $product->is_active;

            $this->showModal = true;
        } catch (\Exception $e) {
            $this->dispatch('swal:fire', [
                'icon' => 'error',
                'title' => __('app.error'),
                'text' => __('app.error_fetching_product_data')
            ]);
        }
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
        $this->resetValidation();
        $this->image = null;
    }

    // Form Methods
    private function resetForm()
    {
        $this->code = '';
        $this->name = '';
        $this->description = '';
        $this->category_id = '';
        $this->brand_id = '';
        $this->unit_id = '';
        $this->purchase_price = 0;
        $this->selling_price = 0;
        $this->wholesale_price = '';
        $this->tax_rate = 0;
        $this->barcode = '';
        $this->sku = '';
        $this->weight = '';
        $this->notes = '';
        $this->is_service = false;
        $this->track_inventory = true;
        $this->min_stock_level = 0;
        $this->max_stock_level = '';
        $this->reorder_point = 0;
        $this->is_active = true;
        $this->image = null;
    }

    public function save()
    {
        $data = [
            'code' => $this->code,
            'name' => $this->name,
            'description' => $this->description,
            'category_id' => $this->category_id,
            'brand_id' => $this->brand_id ?: null,
            'unit_id' => $this->unit_id,
            'purchase_price' => $this->purchase_price,
            'selling_price' => $this->selling_price,
            'wholesale_price' => $this->wholesale_price ?: null,
            'tax_rate' => $this->tax_rate,
            'barcode' => $this->barcode ?: null,
            'sku' => $this->sku ?: null,
            'weight' => $this->weight ?: null,
            'notes' => $this->notes ?: null,
            'is_service' => $this->is_service,
            'track_inventory' => $this->track_inventory,
            'min_stock_level' => $this->min_stock_level,
            'max_stock_level' => $this->max_stock_level ?: null,
            'reorder_point' => $this->reorder_point,
            'is_active' => $this->is_active,
        ];

        try {
            if ($this->editingProductId) {
                $result = $this->getProductService()->update($this->editingProductId, $data);
                $message = __('app.product_updated_successfully');
            } else {
                $result = $this->getProductService()->create($data);
                $message = __('app.product_added_successfully');
            }

            if ($result['success']) {
                // Handle image upload if provided
                if ($this->image) {
                    $this->getProductService()->uploadImage($this->image, $result['data']->id);
                }

                $this->closeModal();
                $this->dispatch('product-saved', [
                    'success' => true,
                    'message' => $message
                ]);
            } else {
                if (isset($result['errors'])) {
                    $this->setErrorBag($result['errors']);
                } else {
                    $this->dispatch('error-occurred', [
                        'success' => false,
                        'message' => $result['message']
                    ]);
                }
            }
        } catch (\Exception $e) {
            $this->dispatch('error-occurred', [
                'success' => false,
                'message' => __('app.error_saving_product')
            ]);
        }
    }

    public function delete($productId)
    {
        try {
            $result = $this->getProductService()->delete($productId);

            if ($result['success']) {
                $this->dispatch('product-deleted', [
                    'success' => true,
                    'message' => __('app.product_deleted_successfully')
                ]);
            } else {
                $this->dispatch('error-occurred', [
                    'success' => false,
                    'message' => $result['message']
                ]);
            }
        } catch (\Exception $e) {
            $this->dispatch('error-occurred', [
                'success' => false,
                'message' => __('app.error_deleting_product')
            ]);
        }
    }

    public function toggleStatus($productId)
    {
        try {
            $result = $this->getProductService()->toggleProductStatus($productId);

            $this->dispatch('status-toggled', [
                'success' => $result['success'],
                'message' => $result['message']
            ]);
        } catch (\Exception $e) {
            $this->dispatch('error-occurred', [
                'success' => false,
                'message' => __('app.error_changing_product_status')
            ]);
        }
    }
}
