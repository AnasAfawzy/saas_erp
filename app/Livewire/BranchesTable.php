<?php

namespace App\Livewire;

use App\Models\Branch;
use App\Services\BranchService;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Pagination\LengthAwarePaginator;

class BranchesTable extends Component
{
    use WithPagination;

    public $search = '';
    public $showModal = false;
    public $editingBranchId = null;
    public $name = '';
    public $location = '';
    public $is_active = true;

    protected $branchService;
    protected $paginationTheme = 'bootstrap';

    protected $rules = [
        'name' => 'required|string|max:255',
        'location' => 'required|string|max:500',
        'is_active' => 'boolean',
    ];

    protected $messages = [
        'name.required' => 'اسم الفرع مطلوب',
        'name.max' => 'اسم الفرع لا يجب أن يتجاوز 255 حرف',
        'location.required' => 'موقع الفرع مطلوب',
        'location.max' => 'موقع الفرع لا يجب أن يتجاوز 500 حرف',
    ];

    public function mount()
    {
        $this->branchService = app(BranchService::class);
    }

    /**
     * Get branch service instance
     */
    protected function getBranchService()
    {
        if (!$this->branchService) {
            $this->branchService = app(BranchService::class);
        }
        return $this->branchService;
    }

    public function render()
    {
        // إنشاء paginator فارغ كحالة افتراضية
        $branches = new LengthAwarePaginator(
            collect([]), // العناصر الفارغة
            0, // المجموع الكلي
            10, // عدد العناصر لكل صفحة
            1, // الصفحة الحالية
            ['path' => request()->url(), 'pageName' => 'page'] // خيارات إضافية
        );

        $stats = ['total' => 0, 'active' => 0, 'inactive' => 0];

        try {
            $service = $this->getBranchService();

            // استخدام Service للحصول على البيانات
            if (!empty($this->search)) {
                $searchResult = $service->searchBranches($this->search, true, 10);
                if ($searchResult['success']) {
                    $branches = $searchResult['data'];
                }
            } else {
                $allBranchesResult = $service->getAllBranches(true, 10);
                if ($allBranchesResult['success']) {
                    $branches = $allBranchesResult['data'];
                }
            }

            // استخدام Service للإحصائيات
            $statsResult = $service->getBranchStatistics();
            if ($statsResult['success']) {
                $stats = $statsResult['data'];
            }
        } catch (\Exception $e) {
            session()->flash('error', __('app.error_loading_data') . ': ' . $e->getMessage());
        }

        return view('livewire.branches-table', [
            'branches' => $branches,
            'stats' => $stats
        ]);
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function openAddModal()
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function openEditModal($id)
    {
        try {
            $service = $this->getBranchService();
            $result = $service->findById($id);

            if ($result['success']) {
                $branch = $result['data'];
                $this->editingBranchId = $id;
                $this->name = $branch->name;
                $this->location = $branch->location;
                $this->is_active = $branch->is_active;
                $this->showModal = true;
            } else {
                $this->dispatch('error-occurred', [
                    'success' => false,
                    'message' => $result['message'] ?? __('app.failed_to_fetch_branch_data')
                ]);
            }
        } catch (\Exception $e) {
            $this->dispatch('error-occurred', [
                'success' => false,
                'message' => __('app.error_fetching_branch_data') . ': ' . $e->getMessage()
            ]);
        }
    }

    public function save()
    {
        // التحقق من صحة البيانات أولاً
        $this->validate();

        try {
            $service = $this->getBranchService();

            $data = [
                'name' => trim($this->name),
                'location' => trim($this->location),
                'is_active' => $this->is_active,
            ];

            if ($this->editingBranchId) {
                // تحديث الفرع الموجود
                $result = $service->update($this->editingBranchId, $data);
                $successMessage = __('app.branch_updated_successfully');
                $errorMessage = __('app.branch_update_failed');
            } else {
                // إنشاء فرع جديد
                $result = $service->create($data);
                $successMessage = __('app.branch_added_successfully');
                $errorMessage = __('app.branch_add_failed');
            }

            if ($result['success']) {
                $this->loadBranches(); // تحديث البيانات
                $this->closeModal();
                $this->dispatch('branch-saved', [
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
            $service = $this->getBranchService();
            $result = $service->delete($id);

            if ($result['success']) {
                $this->loadBranches(); // تحديث البيانات
                $this->dispatch('branch-deleted', [
                    'success' => true,
                    'message' => __('app.branch_deleted_successfully')
                ]);
            } else {
                $this->dispatch('error-occurred', [
                    'success' => false,
                    'message' => $result['message'] ?? __('app.branch_delete_failed')
                ]);
            }
        } catch (\Exception $e) {
            $this->dispatch('error-occurred', [
                'success' => false,
                'message' => __('app.error_deleting_branch') . ': ' . $e->getMessage()
            ]);
        }
    }

    /**
     * إعادة تحميل بيانات الفروع
     */
    protected function loadBranches()
    {
        // هذا Method يعيد تحديث الكومبوننت
        // Livewire سيستدعي render() تلقائياً
        $this->resetPage();
    }

    public function toggleStatus($id)
    {
        try {
            $service = $this->getBranchService();
            $result = $service->toggleBranchStatus($id);

            if ($result['success']) {
                $this->loadBranches(); // تحديث البيانات
                $this->dispatch('status-toggled', [
                    'success' => true,
                    'message' => $result['message'] ?? __('app.branch_status_changed_successfully')
                ]);
            } else {
                $this->dispatch('error-occurred', [
                    'success' => false,
                    'message' => $result['message'] ?? __('app.branch_status_change_failed')
                ]);
            }
        } catch (\Exception $e) {
            $this->dispatch('error-occurred', [
                'success' => false,
                'message' => __('app.error_changing_status') . ': ' . $e->getMessage()
            ]);
        }
    }

    public function clearSearch()
    {
        $this->search = '';
        $this->resetPage();
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    protected function resetForm()
    {
        $this->editingBranchId = null;
        $this->name = '';
        $this->location = '';
        $this->is_active = true;
        $this->resetValidation();
    }
}
