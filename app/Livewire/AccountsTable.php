<?php

namespace App\Livewire;

use App\Models\Account;
use App\Models\Branch;
use App\Services\AccountService;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Computed;

class AccountsTable extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    // خصائص البحث والتصفية
    public $search = '';
    public $accountType = '';
    public $isActive = '';
    public $level = '';
    public $branchFilter = '';

    // خصائص النموذج
    public $showModal = false;
    public $modalMode = 'create'; // create, edit, view
    public $accountId = null;

    // بيانات النموذج
    public $name = '';
    public $nameEn = '';
    public $parentId = null;
    public $branchId = null;
    public $accountLevelType = '';
    public $accountNature = '';
    public $description = '';
    public $isActiveForm = true;

    // خصائص أخرى
    public $parentAccounts = [];
    public $branches = [];

    public $accountLevelTypesOptions = [
        'title' => 'عنوان',
        'account' => 'حساب',
        'sub_account' => 'حساب فرعي'
    ];

    public $accountNatureOptions = [
        'debit' => 'مدين',
        'credit' => 'دائن',
        'both' => 'مدين / دائن'
    ];

    protected AccountService $accountService;

    public function boot(AccountService $accountService)
    {
        $this->accountService = $accountService;
    }

    protected function rules()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'nameEn' => 'nullable|string|max:255',
            'parentId' => 'nullable|exists:accounts,id',
            'branchId' => 'nullable|exists:branches,id',
            'accountLevelType' => 'required|string|in:title,account,sub_account',
            'description' => 'nullable|string',
            'isActiveForm' => 'boolean'
        ];

        // طبيعة الحساب مطلوبة فقط للحسابات التشغيلية
        if ($this->accountLevelType === 'account') {
            $rules['accountNature'] = 'required|string|in:debit,credit,both';
        } else {
            $rules['accountNature'] = 'nullable|string|in:debit,credit,both';
        }

        return $rules;
    }

    protected function validationAttributes()
    {
        return [
            'name' => 'اسم الحساب',
            'nameEn' => 'الاسم بالإنجليزية',
            'parentId' => 'الحساب الأب',
            'branchId' => 'الفرع',
            'accountLevelType' => 'نوع مستوى الحساب',
            'accountNature' => 'طبيعة الحساب',
            'description' => 'الوصف',
            'isActiveForm' => 'الحالة'
        ];
    }

    public function mount()
    {
        $this->loadParentAccounts();
        $this->loadBranches();
    }

    public function render()
    {
        // العرض المسطح مع الصفحات
        $filters = [
            'search' => $this->search,
            'account_type' => $this->accountType,
            'is_active' => $this->isActive !== '' ? (bool) $this->isActive : null,
            'level' => $this->level,
            'branch_id' => $this->branchFilter,
            'perPage' => 15
        ];

        $result = $this->accountService->getAllAccountsFlat($filters);

        return view('livewire.accounts-table', [
            'accounts' => $result['accounts'],
            'statistics' => $result['statistics'],
            'branches' => $this->branches
        ]);
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedAccountType()
    {
        $this->resetPage();
    }

    public function updatedIsActive()
    {
        $this->resetPage();
    }

    public function updatedLevel()
    {
        $this->resetPage();
    }

    public function updatedBranchFilter()
    {
        $this->resetPage();
    }

    public function updatedAccountLevelType()
    {
        // تنظيف طبيعة الحساب إذا لم تعد مطلوبة
        if ($this->accountLevelType !== 'account') {
            $this->accountNature = null;
        }
    }

    public function openCreateModal()
    {
        $this->resetForm();
        $this->modalMode = 'create';
        // ترك نوع مستوى الحساب فارغاً للاختيار اليدوي
        $this->accountLevelType = ''; // فارغ للاختيار اليدوي
        $this->accountNature = null; // فارغ حتى يتم تحديد النوع
        $this->showModal = true;
        $this->dispatch('modal-opened');
    }

    public function openEditModal($id)
    {
        try {
            $this->resetForm();
            $this->modalMode = 'edit';
            $this->accountId = $id;

            $result = $this->accountService->getAccountDetails($id);
            if ($result['success']) {
                $account = $result['data'];
                $this->name = $account->name;
                $this->nameEn = $account->name_en ?? '';
                $this->parentId = $account->parent_id;
                $this->branchId = $account->branch_id;
                $this->accountLevelType = $account->account_level_type;
                $this->accountNature = $account->account_nature;
                $this->description = $account->description ?? '';
                $this->isActiveForm = $account->is_active;

                $this->showModal = true;
                $this->dispatch('modal-opened');
            } else {
                $this->dispatch('error-occurred', [
                    'success' => false,
                    'message' => $result['message']
                ]);
            }
        } catch (\Exception $e) {
            $this->dispatch('error-occurred', [
                'success' => false,
                'message' => __('app.error_loading_account')
            ]);
        }
    }

    public function openViewModal($id)
    {
        $this->openEditModal($id);
        $this->modalMode = 'view';
        $this->dispatch('modal-opened');
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
        $this->resetValidation();
    }

    public function save()
    {
        try {
            $this->validate();

            $data = [
                'name' => $this->name,
                'name_en' => $this->nameEn ?: null,
                'parent_id' => $this->parentId ?: null,
                'branch_id' => $this->branchId ?: null,
                'account_level_type' => $this->accountLevelType,
                'account_nature' => $this->accountNature,
                'description' => $this->description ?: null,
                'is_active' => $this->isActiveForm
            ];

            if ($this->modalMode === 'create') {
                $result = $this->accountService->createAccount($data);
                $message = __('app.account_added_successfully');
            } else {
                $result = $this->accountService->updateAccount($this->accountId, $data);
                $message = __('app.account_updated_successfully');
            }

            if ($result['success']) {
                $this->closeModal();
                $this->loadParentAccounts();
                $this->dispatch('account-saved', [
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
                'message' => __('app.error_saving_account')
            ]);
        }
    }

    public function delete($id)
    {
        try {
            $result = $this->accountService->deleteAccount($id);

            if ($result['success']) {
                $this->loadParentAccounts();
                $this->dispatch('account-deleted', [
                    'success' => true,
                    'message' => __('app.account_deleted_successfully')
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
                'message' => __('app.error_deleting_account')
            ]);
        }
    }

    public function toggleStatus($id)
    {
        try {
            // تحقق إضافي من وجود أطفال
            $account = Account::find($id);
            if ($account && $account->has_children) {
                $this->dispatch('error-occurred', [
                    'success' => false,
                    'message' => 'لا يمكن تغيير حالة الحساب لأنه يحتوي على حسابات فرعية'
                ]);
                return;
            }

            $result = $this->accountService->toggleAccountStatus($id);

            $this->dispatch('status-toggled', [
                'success' => $result['success'],
                'message' => $result['message']
            ]);
        } catch (\Exception $e) {
            $this->dispatch('error-occurred', [
                'success' => false,
                'message' => __('app.error_changing_account_status')
            ]);
        }
    }

    public function resetFilters()
    {
        $this->reset(['search', 'accountType', 'isActive', 'level', 'branchFilter']);
        $this->resetPage();
    }

    private function resetForm()
    {
        $this->reset([
            'accountId',
            'name',
            'nameEn',
            'parentId',
            'branchId',
            'accountLevelType',
            'accountNature',
            'description',
            'isActiveForm'
        ]);
        $this->isActiveForm = true;
    }

    private function loadParentAccounts()
    {
        $parentAccounts = $this->accountService->getAccountsForSelect(
            null, // لن نقيد بنوع الحساب بعد الآن
            $this->accountId
        );

        $this->parentAccounts = $parentAccounts->toArray();
    }

    private function loadBranches()
    {
        $this->branches = Branch::active()
            ->select(['id', 'name'])
            ->get()
            ->toArray();
    }

    public function updatedParentId()
    {
        if ($this->parentId) {
            $parent = Account::find($this->parentId);
            if ($parent) {
                // وراثة طبيعة الحساب فقط للحسابات التشغيلية وإذا كان النوع محدد مسبقاً
                if ($this->accountLevelType === 'account' && $parent->account_nature) {
                    $this->accountNature = $parent->account_nature;
                } else if ($this->accountLevelType !== 'account') {
                    $this->accountNature = null; // العناوين والحسابات الفرعية لا تحتاج لطبيعة حساب
                }

                // الكود سيتم توليده تلقائياً في الخدمة
            }
        } else {
            // حساب رئيسي - إزالة طبيعة الحساب إذا كان النوع غير تشغيلي
            if ($this->accountLevelType !== 'account') {
                $this->accountNature = null;
            }

            // الكود سيتم توليده تلقائياً في الخدمة
        }
    }
}
