<?php

namespace App\Livewire;

use App\Models\Account;
use App\Models\AccountableAccount;
use App\Models\Customer;
use App\Models\Supplier;
use App\Models\Warehouse;
use App\Services\AccountLinkingService;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Computed;

class AccountLinkingManager extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    // خصائص التصفية والبحث
    public $selectedEntityType = 'Customer';
    public $search = '';
    public $linkingStatus = 'all'; // all, linked, unlinked
    public $selectedAccountType = 'all'; // all, account, sub_account

    // خصائص النموذج
    public $showLinkingModal = false;
    public $selectedEntityId = null;
    public $selectedAccountId = null;
    public $linkingNotes = '';

    // خصائص العمليات المجمعة
    public $selectedEntities = [];
    public $selectAll = false;
    public $showBulkModal = false;
    public $bulkAccountId = null;

    protected $accountLinkingService;

    public function boot(AccountLinkingService $accountLinkingService)
    {
        $this->accountLinkingService = $accountLinkingService;
    }

    protected function getAccountLinkingService(): AccountLinkingService
    {
        return $this->accountLinkingService ?: app(AccountLinkingService::class);
    }

    public function mount()
    {
        $this->resetPage();
    }

    /**
     * الحصول على الكيانات المتاحة
     */
    #[Computed]
    public function entityTypes()
    {
        return [
            'Customer' => [
                'label' => 'العملاء',
                'model' => Customer::class,
                'icon' => '👥'
            ],
            'Supplier' => [
                'label' => 'الموردين',
                'model' => Supplier::class,
                'icon' => '🚚'
            ],
            'Warehouse' => [
                'label' => 'المخازن',
                'model' => Warehouse::class,
                'icon' => '🏪'
            ]
        ];
    }

    /**
     * الحصول على الكيانات مع حالة الربط
     */
    #[Computed]
    public function entities()
    {
        $entityType = $this->entityTypes[$this->selectedEntityType] ?? null;

        if (!$entityType || !class_exists($entityType['model'])) {
            return collect();
        }

        $query = $entityType['model']::query()
            ->with(['accountableAccount.account']);

        // البحث
        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%')
                ->orWhere('code', 'like', '%' . $this->search . '%');
        }

        // تصفية حسب حالة الربط
        if ($this->linkingStatus === 'linked') {
            $query->has('accountableAccount');
        } elseif ($this->linkingStatus === 'unlinked') {
            $query->doesntHave('accountableAccount');
        }

        return $query->paginate(15);
    }

    /**
     * الحصول على الحسابات المتاحة للربط
     */
    #[Computed]
    public function availableAccounts()
    {
        $query = Account::query()
            ->where('is_active', true)
            ->whereIn('account_level_type', ['account', 'sub_account'])
            ->with('parent')
            ->orderBy('code');

        // تصفية حسب نوع الحساب
        if ($this->selectedAccountType !== 'all') {
            $query->where('account_level_type', $this->selectedAccountType);
        }

        return $query->get();
    }

    /**
     * الحصول على إحصائيات الربط
     */
    #[Computed]
    public function linkingStatistics()
    {
        $entityType = $this->entityTypes[$this->selectedEntityType] ?? null;

        if (!$entityType || !class_exists($entityType['model'])) {
            return [
                'total' => 0,
                'linked' => 0,
                'unlinked' => 0,
                'percentage' => 0
            ];
        }

        $total = $entityType['model']::count();
        $linked = AccountableAccount::where('accountable_type', $entityType['model'])->count();
        $unlinked = $total - $linked;

        return [
            'total' => $total,
            'linked' => $linked,
            'unlinked' => $unlinked,
            'percentage' => $total > 0 ? round(($linked / $total) * 100, 2) : 0
        ];
    }

    /**
     * فتح نموذج الربط
     */
    public function openLinkingModal($entityId)
    {
        $this->selectedEntityId = $entityId;
        $this->selectedAccountId = null;
        $this->linkingNotes = '';
        $this->showLinkingModal = true;
    }

    /**
     * إغلاق نموذج الربط
     */
    public function closeLinkingModal()
    {
        $this->showLinkingModal = false;
        $this->selectedEntityId = null;
        $this->selectedAccountId = null;
        $this->linkingNotes = '';
    }

    /**
     * تنفيذ عملية الربط
     */
    public function performLinking()
    {
        $this->validate([
            'selectedEntityId' => 'required',
            'selectedAccountId' => 'required|exists:accounts,id'
        ]);

        try {
            $entityType = $this->entityTypes[$this->selectedEntityType];
            $entity = $entityType['model']::find($this->selectedEntityId);
            $account = Account::find($this->selectedAccountId);

            if (!$entity || !$account) {
                $this->dispatch('swal:fire', [
                    'icon' => 'error',
                    'title' => 'خطأ',
                    'text' => 'الكيان أو الحساب غير موجود'
                ]);
                return;
            }

            // فحص ما إذا كان الكيان مرتبط بالفعل
            if ($entity->hasAccount()) {
                // إلغاء الربط السابق
                $entity->unlinkAccount();
            }

            // إنشاء ربط جديد
            AccountableAccount::create([
                'account_id' => $this->selectedAccountId,
                'accountable_type' => $entityType['model'],
                'accountable_id' => $this->selectedEntityId,
                'auto_created' => false,
                'sync_settings' => [
                    'notes' => $this->linkingNotes,
                    'linked_by_user' => true,
                    'linked_at' => now()
                ],
                'last_sync_at' => now(),
            ]);

            // مزامنة البيانات
            $this->getAccountLinkingService()->syncEntityWithAccount($entity, $account);

            $this->dispatch('swal:fire', [
                'icon' => 'success',
                'title' => 'تم بنجاح',
                'text' => "تم ربط {$entity->name} بالحساب {$account->name}",
                'timer' => 3000
            ]);

            $this->closeLinkingModal();
            $this->resetPage();
        } catch (\Exception $e) {
            $this->dispatch('swal:fire', [
                'icon' => 'error',
                'title' => 'خطأ في الربط',
                'text' => $e->getMessage()
            ]);
        }
    }

    /**
     * إلغاء الربط
     */
    public function unlinkEntity($entityId)
    {
        try {
            $entityType = $this->entityTypes[$this->selectedEntityType];
            $entity = $entityType['model']::find($entityId);

            if ($entity && $entity->hasAccount()) {
                $entity->unlinkAccount();

                $this->dispatch('swal:fire', [
                    'icon' => 'success',
                    'title' => 'تم إلغاء الربط',
                    'text' => "تم إلغاء ربط {$entity->name} من حسابه",
                    'timer' => 3000
                ]);

                $this->resetPage();
            }
        } catch (\Exception $e) {
            $this->dispatch('swal:fire', [
                'icon' => 'error',
                'title' => 'خطأ',
                'text' => 'فشل في إلغاء الربط: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * تحديد/إلغاء تحديد جميع العناصر
     */
    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selectedEntities = $this->entities->pluck('id')->toArray();
        } else {
            $this->selectedEntities = [];
        }
    }

    /**
     * فتح نموذج العمليات المجمعة
     */
    public function openBulkModal()
    {
        if (empty($this->selectedEntities)) {
            $this->dispatch('swal:fire', [
                'icon' => 'warning',
                'title' => 'تحذير',
                'text' => 'يرجى تحديد عناصر أولاً'
            ]);
            return;
        }

        $this->bulkAccountId = null;
        $this->showBulkModal = true;
    }

    /**
     * إغلاق نموذج العمليات المجمعة
     */
    public function closeBulkModal()
    {
        $this->showBulkModal = false;
        $this->bulkAccountId = null;
    }

    /**
     * تنفيذ عملية ربط مجمعة
     */
    public function performBulkLinking()
    {
        $this->validate([
            'bulkAccountId' => 'required|exists:accounts,id',
            'selectedEntities' => 'required|array|min:1'
        ]);

        try {
            $entityType = $this->entityTypes[$this->selectedEntityType];
            $account = Account::find($this->bulkAccountId);
            $successCount = 0;
            $errorCount = 0;

            foreach ($this->selectedEntities as $entityId) {
                try {
                    $entity = $entityType['model']::find($entityId);

                    if ($entity) {
                        // إلغاء الربط السابق إذا وُجد
                        if ($entity->hasAccount()) {
                            $entity->unlinkAccount();
                        }

                        // إنشاء ربط جديد
                        AccountableAccount::create([
                            'account_id' => $this->bulkAccountId,
                            'accountable_type' => $entityType['model'],
                            'accountable_id' => $entityId,
                            'auto_created' => false,
                            'sync_settings' => [
                                'bulk_linked' => true,
                                'linked_by_user' => true,
                                'linked_at' => now()
                            ],
                            'last_sync_at' => now(),
                        ]);

                        // مزامنة البيانات
                        $this->getAccountLinkingService()->syncEntityWithAccount($entity, $account);
                        $successCount++;
                    }
                } catch (\Exception $e) {
                    $errorCount++;
                }
            }

            $this->dispatch('swal:fire', [
                'icon' => $errorCount > 0 ? 'warning' : 'success',
                'title' => 'انتهت العملية',
                'text' => "تم ربط {$successCount} عنصر بنجاح" . ($errorCount > 0 ? " وفشل {$errorCount}" : ""),
                'timer' => 3000
            ]);

            $this->closeBulkModal();
            $this->selectedEntities = [];
            $this->selectAll = false;
            $this->resetPage();
        } catch (\Exception $e) {
            $this->dispatch('swal:fire', [
                'icon' => 'error',
                'title' => 'خطأ',
                'text' => 'فشل في العملية المجمعة: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * إعادة تعيين التصفية
     */
    public function resetFilters()
    {
        $this->search = '';
        $this->linkingStatus = 'all';
        $this->selectedAccountType = 'all';
        $this->resetPage();
    }

    /**
     * تحديث عند تغيير نوع الكيان
     */
    public function updatedSelectedEntityType()
    {
        $this->resetPage();
        $this->selectedEntities = [];
        $this->selectAll = false;
    }

    /**
     * تحديث عند تغيير البحث
     */
    public function updatedSearch()
    {
        $this->resetPage();
    }

    /**
     * تحديث عند تغيير حالة الربط
     */
    public function updatedLinkingStatus()
    {
        $this->resetPage();
    }

    public function render()
    {
        return view('livewire.account-linking-manager', [
            'entities' => $this->entities,
            'availableAccounts' => $this->availableAccounts,
            'linkingStatistics' => $this->linkingStatistics,
            'entityTypes' => $this->entityTypes,
        ]);
    }
}
