<?php

namespace App\Livewire;

use App\Models\Category;
use App\Services\CategoryService;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Pagination\LengthAwarePaginator;

class CategoriesTable extends Component
{
    use WithPagination;

    public $search = '';
    public $showModal = false;
    public $editingCategoryId = null;
    public $name = '';
    public $description = '';
    public $is_active = true;

    protected $categoryService;
    protected $paginationTheme = 'bootstrap';

    public function mount()
    {
        $this->categoryService = app(CategoryService::class);
    }

    /**
     * Get category service instance
     */
    protected function getCategoryService()
    {
        if (!$this->categoryService) {
            $this->categoryService = app(CategoryService::class);
        }
        return $this->categoryService;
    }

    public function render()
    {
        // إنشاء paginator فارغ كحالة افتراضية
        $categories = new LengthAwarePaginator(
            collect([]), // العناصر الفارغة
            0, // المجموع الكلي
            10, // عدد العناصر لكل صفحة
            1, // الصفحة الحالية
            ['path' => request()->url(), 'pageName' => 'page'] // خيارات إضافية
        );

        $stats = ['total' => 0, 'active' => 0, 'inactive' => 0];

        try {
            $service = $this->getCategoryService();

            // استخدام Service للحصول على البيانات
            if (!empty($this->search)) {
                $searchResult = $service->searchCategories($this->search, true, 10);
                if ($searchResult['success']) {
                    $categories = $searchResult['data'];
                }
            } else {
                $allCategoriesResult = $service->getAllCategories(true, 10);
                if ($allCategoriesResult['success']) {
                    $categories = $allCategoriesResult['data'];
                }
            }

            // استخدام Service للإحصائيات
            $statsResult = $service->getCategoryStatistics();
            if ($statsResult['success']) {
                $stats = $statsResult['data'];
            }
        } catch (\Exception $e) {
            session()->flash('error', __('app.error_loading_data') . ': ' . $e->getMessage());
        }

        return view('livewire.categories-table', [
            'categories' => $categories,
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
        $this->editingCategoryId = null;
        $this->showModal = true;
    }

    public function openEditModal($categoryId)
    {
        try {
            $service = $this->getCategoryService();
            $result = $service->findCategory($categoryId);

            if ($result['success']) {
                $category = $result['data'];
                $this->editingCategoryId = $categoryId;
                $this->name = $category->name;
                $this->description = $category->description;
                $this->is_active = $category->is_active;
                $this->showModal = true;
            } else {
                $this->dispatch('error-occurred', [
                    'success' => false,
                    'message' => $result['message'] ?? __('app.failed_to_fetch_category_data')
                ]);
            }
        } catch (\Exception $e) {
            $this->dispatch('error-occurred', [
                'success' => false,
                'message' => __('app.error_fetching_category_data') . ': ' . $e->getMessage()
            ]);
        }
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    /**
     * إعادة تحميل بيانات الفئات
     */
    protected function loadCategories()
    {
        // هذا Method يعيد تحديث الكومبوننت
        // Livewire سيستدعي render() تلقائياً
        $this->resetPage();
    }

    public function save()
    {
        try {
            $service = $this->getCategoryService();

            $data = [
                'name' => trim($this->name),
                'description' => trim($this->description),
                'is_active' => $this->is_active,
            ];

            if ($this->editingCategoryId) {
                // تحديث الفئة الموجودة
                $result = $service->update($this->editingCategoryId, $data);
                $successMessage = __('app.category_updated_successfully');
                $errorMessage = __('app.category_update_failed');
            } else {
                // إنشاء فئة جديدة
                $result = $service->create($data);
                $successMessage = __('app.category_added_successfully');
                $errorMessage = __('app.category_add_failed');
            }

            if ($result['success']) {
                $this->loadCategories(); // تحديث البيانات
                $this->closeModal();
                $this->dispatch('category-saved', [
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
            $service = $this->getCategoryService();
            $result = $service->delete($id);

            if ($result['success']) {
                $this->loadCategories(); // تحديث البيانات
                $this->dispatch('category-deleted', [
                    'success' => true,
                    'message' => __('app.category_deleted_successfully')
                ]);
            } else {
                $this->dispatch('error-occurred', [
                    'success' => false,
                    'message' => $result['message'] ?? __('app.category_delete_failed')
                ]);
            }
        } catch (\Exception $e) {
            $this->dispatch('error-occurred', [
                'success' => false,
                'message' => __('app.error_deleting_category') . ': ' . $e->getMessage()
            ]);
        }
    }

    public function toggleStatus($id)
    {
        try {
            $service = $this->getCategoryService();
            $result = $service->toggleCategoryStatus($id);

            if ($result['success']) {
                $this->loadCategories(); // تحديث البيانات
                $this->dispatch('status-toggled', [
                    'success' => true,
                    'message' => $result['message'] ?? __('app.category_status_changed_successfully')
                ]);
            } else {
                $this->dispatch('error-occurred', [
                    'success' => false,
                    'message' => $result['message'] ?? __('app.category_status_change_failed')
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
        $this->editingCategoryId = null;
        $this->name = '';
        $this->description = '';
        $this->is_active = true;
        $this->resetValidation();
    }
}
