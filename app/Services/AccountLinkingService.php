<?php

namespace App\Services;

use App\Models\Account;
use App\Models\AccountableAccount;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class AccountLinkingService
{
    protected array $entityConfigs = [];

    public function __construct()
    {
        $this->loadEntityConfigs();
    }

    /**
     * تسجيل إعدادات كيان جديد
     */
    public function registerEntity(string $entityClass, array $config): void
    {
        $this->entityConfigs[$entityClass] = array_merge([
            'enabled' => true,
            'auto_create' => true,
            'auto_sync' => true,
            'parent_account_id' => null,
            'account_type' => 'assets',
            'account_nature' => 'debit',
            'account_level_type' => 'sub_account',
            'code_prefix' => 'ACC',
            'sync_fields' => ['name', 'is_active'],
            'balance_field' => null,
        ], $config);
    }

    /**
     * إنشاء حساب تلقائي لكيان
     */
    public function createAccountForEntity(Model $entity, array $overrides = []): ?Account
    {
        $entityClass = get_class($entity);

        if (!$this->isEntitySupported($entityClass)) {
            Log::warning("Entity {$entityClass} is not supported for account linking");
            return null;
        }

        $config = $this->entityConfigs[$entityClass];

        if (!$config['enabled'] || !$config['auto_create']) {
            return null;
        }

        DB::beginTransaction();
        try {
            // إنشاء الحساب
            $account = $this->createAccount($entity, $config, $overrides);

            // ربط الحساب بالكيان
            $this->linkAccountToEntity($account, $entity);

            // مزامنة البيانات الأولية
            $this->syncEntityWithAccount($entity, $account);

            DB::commit();

            Log::info("Account created and linked for {$entityClass} ID: {$entity->id}");
            return $account;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error("Failed to create account for {$entityClass} ID: {$entity->id}. Error: {$e->getMessage()}");
            throw $e;
        }
    }

    /**
     * مزامنة بيانات الكيان مع حسابه
     */
    public function syncEntityWithAccount(Model $entity, Account $account = null): bool
    {
        if (!$account) {
            $account = $this->getEntityAccount($entity);
        }

        if (!$account) {
            return false;
        }

        $entityClass = get_class($entity);
        $config = $this->entityConfigs[$entityClass];

        if (!$config['auto_sync']) {
            return false;
        }

        try {
            $updateData = [];

            // مزامنة الحقول المحددة
            foreach ($config['sync_fields'] as $field) {
                if ($entity->hasAttribute($field)) {
                    $updateData[$field] = $entity->{$field};
                }
            }

            // مزامنة الرصيد إذا كان محدداً
            if ($config['balance_field'] && $entity->hasAttribute($config['balance_field'])) {
                $updateData['balance'] = $entity->{$config['balance_field']};
            }

            if (!empty($updateData)) {
                $account->update($updateData);

                // تحديث وقت آخر مزامنة
                $this->updateLastSyncTime($entity);
            }

            Log::info("Synced {$entityClass} ID: {$entity->id} with account ID: {$account->id}");
            return true;
        } catch (Exception $e) {
            Log::error("Failed to sync {$entityClass} ID: {$entity->id} with account. Error: {$e->getMessage()}");
            return false;
        }
    }

    /**
     * الحصول على حساب الكيان
     */
    public function getEntityAccount(Model $entity): ?Account
    {
        $linking = AccountableAccount::where([
            'accountable_type' => get_class($entity),
            'accountable_id' => $entity->id
        ])->first();

        return $linking ? $linking->account : null;
    }

    /**
     * إلغاء ربط الكيان من حسابه
     */
    public function unlinkEntityFromAccount(Model $entity, bool $deleteAccount = false): bool
    {
        try {
            $account = $this->getEntityAccount($entity);

            if (!$account) {
                return false;
            }

            // حذف الربط
            AccountableAccount::where([
                'accountable_type' => get_class($entity),
                'accountable_id' => $entity->id
            ])->delete();

            // حذف الحساب إذا كان مطلوباً
            if ($deleteAccount) {
                $account->delete();
            }

            Log::info("Unlinked " . get_class($entity) . " ID: {$entity->id} from account");
            return true;
        } catch (Exception $e) {
            Log::error("Failed to unlink " . get_class($entity) . " ID: {$entity->id} from account. Error: {$e->getMessage()}");
            return false;
        }
    }

    /**
     * إنشاء حسابات متعددة دفعة واحدة
     */
    public function bulkCreateAccounts(string $entityClass, array $entityIds = []): array
    {
        $results = [
            'success' => [],
            'failed' => [],
            'skipped' => []
        ];

        $query = $entityClass::query();

        if (!empty($entityIds)) {
            $query->whereIn('id', $entityIds);
        }

        // استبعاد الكيانات التي لها حسابات بالفعل
        $query->whereDoesntHave('accountableAccount');

        $entities = $query->get();

        foreach ($entities as $entity) {
            try {
                $account = $this->createAccountForEntity($entity);

                if ($account) {
                    $results['success'][] = $entity->id;
                } else {
                    $results['skipped'][] = $entity->id;
                }
            } catch (Exception $e) {
                $results['failed'][] = [
                    'id' => $entity->id,
                    'error' => $e->getMessage()
                ];
            }
        }

        return $results;
    }

    /**
     * إنشاء الحساب الفعلي
     */
    protected function createAccount(Model $entity, array $config, array $overrides = []): Account
    {
        $parentAccount = $this->determineParentAccount($entity, $config);

        return Account::create(array_merge([
            'code' => $this->generateAccountCode($entity, $config),
            'name' => $this->getEntityDisplayName($entity),
            'name_en' => $this->getEntityDisplayName($entity, 'en'),
            'parent_id' => $parentAccount?->id,
            'account_type' => $config['account_type'],
            'account_nature' => $config['account_nature'],
            'account_level_type' => $config['account_level_type'],
            'level' => $parentAccount ? $parentAccount->level + 1 : 1,
            'is_active' => $entity->is_active ?? true,
            'balance' => $config['balance_field'] ? ($entity->{$config['balance_field']} ?? 0) : 0,
            'description' => $this->generateAccountDescription($entity),
        ], $overrides));
    }

    /**
     * ربط الحساب بالكيان
     */
    protected function linkAccountToEntity(Account $account, Model $entity): void
    {
        AccountableAccount::create([
            'account_id' => $account->id,
            'accountable_type' => get_class($entity),
            'accountable_id' => $entity->id,
            'auto_created' => true,
            'last_sync_at' => now(),
        ]);
    }

    /**
     * تحديد الحساب الأب
     */
    protected function determineParentAccount(Model $entity, array $config): ?Account
    {
        // فحص parent_accounts أولاً (للكيانات التي لها شروط متعددة)
        if (isset($config['parent_accounts']) && is_array($config['parent_accounts'])) {
            foreach ($config['parent_accounts'] as $condition => $parentId) {
                if ($this->checkCondition($entity, $condition)) {
                    return Account::find($parentId);
                }
            }
            return null;
        }

        // فحص parent_account_id (للكيانات البسيطة)
        if (isset($config['parent_account_id'])) {
            if (is_array($config['parent_account_id'])) {
                // إذا كان هناك حسابات أب متعددة بناءً على شروط
                foreach ($config['parent_account_id'] as $condition => $parentId) {
                    if ($this->checkCondition($entity, $condition)) {
                        return Account::find($parentId);
                    }
                }
                return null;
            }

            return Account::find($config['parent_account_id']);
        }

        return null;
    }

    /**
     * توليد كود الحساب
     */
    protected function generateAccountCode(Model $entity, array $config): string
    {
        $prefix = $config['code_prefix'];
        $entityCode = $entity->code ?? $entity->id;

        return "{$prefix}-{$entityCode}";
    }

    /**
     * الحصول على اسم الكيان للعرض
     */
    protected function getEntityDisplayName(Model $entity, string $lang = 'ar'): string
    {
        $nameField = $lang === 'en' ? 'name_en' : 'name';
        return $entity->{$nameField} ?? $entity->name ?? "Entity #{$entity->id}";
    }

    /**
     * توليد وصف الحساب
     */
    protected function generateAccountDescription(Model $entity): string
    {
        $entityName = class_basename(get_class($entity));
        return "حساب {$entityName}: {$entity->name} - كود: " . ($entity->code ?? $entity->id);
    }

    /**
     * فحص شرط معين على الكيان
     */
    protected function checkCondition(Model $entity, string $condition): bool
    {
        // يمكن توسيع هذه الدالة لدعم شروط أكثر تعقيداً
        if (str_contains($condition, '=')) {
            [$field, $value] = explode('=', $condition, 2);
            return $entity->{$field} === $value;
        }

        return false;
    }

    /**
     * تحديث وقت آخر مزامنة
     */
    protected function updateLastSyncTime(Model $entity): void
    {
        AccountableAccount::where([
            'accountable_type' => get_class($entity),
            'accountable_id' => $entity->id
        ])->update(['last_sync_at' => now()]);
    }

    /**
     * فحص ما إذا كان الكيان مدعوماً
     */
    protected function isEntitySupported(string $entityClass): bool
    {
        return isset($this->entityConfigs[$entityClass]);
    }

    /**
     * تحميل إعدادات الكيانات
     */
    protected function loadEntityConfigs(): void
    {
        // يمكن تحميل الإعدادات من ملف config أو قاعدة البيانات
        $this->entityConfigs = config('account_linking.entities', []);
    }

    /**
     * الحصول على إحصائيات الربط
     */
    public function getLinkingStatistics(): array
    {
        $stats = [];

        foreach ($this->entityConfigs as $entityClass => $config) {
            // تخطي الكيانات المعطلة
            if (!$config['enabled']) {
                continue;
            }

            // التحقق من وجود الكيان
            if (!class_exists($entityClass)) {
                Log::warning("Entity class {$entityClass} does not exist");
                continue;
            }

            try {
                $totalEntities = $entityClass::count();
                $linkedEntities = AccountableAccount::where('accountable_type', $entityClass)->count();

                $stats[$entityClass] = [
                    'total' => $totalEntities,
                    'linked' => $linkedEntities,
                    'unlinked' => $totalEntities - $linkedEntities,
                    'percentage' => $totalEntities > 0 ? round(($linkedEntities / $totalEntities) * 100, 2) : 0,
                ];
            } catch (\Exception $e) {
                Log::error("Error getting statistics for {$entityClass}: " . $e->getMessage());
                $stats[$entityClass] = [
                    'total' => 0,
                    'linked' => 0,
                    'unlinked' => 0,
                    'percentage' => 0,
                    'error' => $e->getMessage()
                ];
            }
        }

        return $stats;
    }
}
