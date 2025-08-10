<?php

namespace App\Livewire;

use App\Models\Brand;
use App\Services\BrandService;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Pagination\LengthAwarePaginator;

class BrandsTable extends Component
{
    use WithPagination;

    public $search = '';
    public $showModal = false;
    public $editingBrandId = null;
    public $name = '';
    public $description = '';
    public $logo = '';
    public $is_active = true;

    protected $brandService;
    protected $paginationTheme = 'bootstrap';

    public function mount()
    {
        $this->brandService = app(BrandService::class);
    }

    /**
     * Get brand service instance
     */
    protected function getBrandService()
    {
        if (!$this->brandService) {
            $this->brandService = app(BrandService::class);
        }
        return $this->brandService;
    }

    public function render()
    {
        // إنشاء paginator فارغ كحالة افتراضية
        $brands = new LengthAwarePaginator(
            collect([]), // العناصر الفارغة
            0, // المجموع الكلي
            10, // عدد العناصر لكل صفحة
            1, // الصفحة الحالية
            ['path' => request()->url(), 'pageName' => 'page'] // خيارات إضافية
        );

        $stats = ['total' => 0, 'active' => 0, 'inactive' => 0];

        try {
            $service = $this->getBrandService();

            // استخدام Service للحصول على البيانات
            if (!empty($this->search)) {
                $searchResult = $service->searchBrands($this->search, true, 10);
                if ($searchResult['success']) {
                    $brands = $searchResult['data'];
                }
            } else {
                $allBrandsResult = $service->getAllBrands(true, 10);
                if ($allBrandsResult['success']) {
                    $brands = $allBrandsResult['data'];
                }
            }

            // استخدام Service للإحصائيات
            $statsResult = $service->getBrandStatistics();
            if ($statsResult['success']) {
                $stats = $statsResult['data'];
            }
        } catch (\Exception $e) {
            session()->flash('error', __('app.error_loading_data') . ': ' . $e->getMessage());
        }

        return view('livewire.brands-table', [
            'brands' => $brands,
            'stats' => $stats
        ]);
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function clearSearch()
    {
        $this->search = '';
        $this->resetPage();
    }

    // Modal methods
    public function openAddModal()
    {
        $this->resetForm();
        $this->editingBrandId = null;
        $this->showModal = true;
    }

    public function openEditModal($brandId)
    {
        try {
            $service = $this->getBrandService();
            $result = $service->findBrand($brandId);

            if ($result['success']) {
                $brand = $result['data'];
                $this->editingBrandId = $brandId;
                $this->name = $brand->name;
                $this->description = $brand->description;
                $this->logo = $brand->logo;
                $this->is_active = $brand->is_active;
                $this->showModal = true;
            } else {
                $this->dispatch('error-occurred', [
                    'success' => false,
                    'message' => $result['message'] ?? __('app.failed_to_fetch_brand_data')
                ]);
            }
        } catch (\Exception $e) {
            $this->dispatch('error-occurred', [
                'success' => false,
                'message' => __('app.error_fetching_brand_data') . ': ' . $e->getMessage()
            ]);
        }
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    /**
     * إعادة تحميل بيانات العلامات التجارية
     */
    protected function loadBrands()
    {
        // هذا Method يعيد تحديث الكومبوننت
        // Livewire سيستدعي render() تلقائياً
        $this->resetPage();
    }

    public function save()
    {
        try {
            $service = $this->getBrandService();

            $data = [
                'name' => trim($this->name),
                'description' => trim($this->description),
                'logo' => trim($this->logo),
                'is_active' => $this->is_active,
            ];

            if ($this->editingBrandId) {
                // تحديث العلامة التجارية الموجودة
                $result = $service->update($this->editingBrandId, $data);
                $successMessage = __('app.brand_updated_successfully');
                $errorMessage = __('app.brand_update_failed');
            } else {
                // إنشاء علامة تجارية جديدة
                $result = $service->create($data);
                $successMessage = __('app.brand_added_successfully');
                $errorMessage = __('app.brand_add_failed');
            }

            if ($result['success']) {
                $this->loadBrands(); // تحديث البيانات
                $this->closeModal();
                $this->dispatch('brand-saved', [
                    'success' => true,
                    'message' => $successMessage
                ]);
            } else {
                // معالجة أخطاء التحقق من صحة البيانات
                if (isset($result['data']) && is_array($result['data'])) {
                    foreach ($result['data'] as $field => $errors) {
                        $errorMessage = is_array($errors) ? $errors[0] : $errors;
                        $this->addError($field, $errorMessage);
                    }
                } else {
                    $this->dispatch('error-occurred', [
                        'success' => false,
                        'message' => $result['message'] ?? $errorMessage
                    ]);
                }
            }
        } catch (\Exception $e) {
            $this->dispatch('error-occurred', [
                'success' => false,
                'message' => __('app.unexpected_error') . ': ' . $e->getMessage()
            ]);
        }
    }

    public function delete($id)
    {
        try {
            $service = $this->getBrandService();
            $result = $service->delete($id);

            if ($result['success']) {
                $this->loadBrands(); // تحديث البيانات
                $this->dispatch('brand-deleted', [
                    'success' => true,
                    'message' => __('app.brand_deleted_successfully')
                ]);
            } else {
                $this->dispatch('error-occurred', [
                    'success' => false,
                    'message' => $result['message'] ?? __('app.brand_delete_failed')
                ]);
            }
        } catch (\Exception $e) {
            $this->dispatch('error-occurred', [
                'success' => false,
                'message' => __('app.error_deleting_brand') . ': ' . $e->getMessage()
            ]);
        }
    }

    public function toggleStatus($id)
    {
        try {
            $service = $this->getBrandService();
            $result = $service->toggleBrandStatus($id);

            if ($result['success']) {
                $this->loadBrands(); // تحديث البيانات
                $this->dispatch('status-toggled', [
                    'success' => true,
                    'message' => $result['message'] ?? __('app.brand_status_changed_successfully')
                ]);
            } else {
                $this->dispatch('error-occurred', [
                    'success' => false,
                    'message' => $result['message'] ?? __('app.brand_status_change_failed')
                ]);
            }
        } catch (\Exception $e) {
            $this->dispatch('error-occurred', [
                'success' => false,
                'message' => __('app.error_changing_status') . ': ' . $e->getMessage()
            ]);
        }
    }

    protected function resetForm()
    {
        $this->editingBrandId = null;
        $this->name = '';
        $this->description = '';
        $this->logo = '';
        $this->is_active = true;
        $this->resetValidation();
    }
}
