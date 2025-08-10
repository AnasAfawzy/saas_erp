<?php

namespace App\Services;

use App\Models\Account;
use App\Models\AccountableAccount;
use App\Models\Customer;
use App\Models\Supplier;
use App\Models\Warehouse;
use App\Models\Bank;
use App\Models\Branch;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use Exception;

/**
 * خدمة إدارة هيكل الحسابات الأساسي
 * إنشاء الحسابات الأب المطلوبة لنظام الربط
 */
class AccountStructureService
{
    /**
     * إنشاء هيكل الحسابات الأساسي
     */
    public function createBasicAccountStructure(): array
    {
        $results = [
            'created' => [],
            'existing' => [],
            'failed' => []
        ];

        DB::beginTransaction();
        try {
            $structure = Config::get('account_linking.parent_accounts_structure', []);

            foreach ($structure as $key => $accountData) {
                $result = $this->createAccountWithChildren($accountData);
                $results['created'][] = $result;
            }

            DB::commit();
            Log::info('Basic account structure created successfully');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Failed to create basic account structure: ' . $e->getMessage());
            throw $e;
        }

        return $results;
    }

    /**
     * إنشاء حساب مع حساباته الفرعية
     */
    private function createAccountWithChildren(array $accountData, ?Account $parent = null): Account
    {
        // فحص ما إذا كان الحساب موجوداً
        $existingAccount = Account::where('code', $accountData['code'])->first();

        if ($existingAccount) {
            Log::info("Account {$accountData['code']} already exists, skipping creation");
            return $existingAccount;
        }

        // إنشاء الحساب الأساسي
        $account = Account::create([
            'code' => $accountData['code'],
            'name' => $accountData['name'],
            'name_en' => $accountData['name_en'] ?? null,
            'parent_id' => $parent?->id,
            'account_type' => $accountData['account_type'],
            'account_nature' => $accountData['account_nature'],
            'account_level_type' => $accountData['account_level_type'],
            'level' => $parent ? $parent->level + 1 : 1,
            'is_active' => true,
            'has_children' => isset($accountData['children']),
            'balance' => 0,
            'description' => "حساب أساسي - تم إنشاؤه تلقائياً بواسطة نظام الربط"
        ]);

        Log::info("Created account: {$account->code} - {$account->name}");

        // إنشاء الحسابات الفرعية
        if (isset($accountData['children'])) {
            foreach ($accountData['children'] as $childData) {
                $childData = array_merge($childData, [
                    'account_type' => $accountData['account_type'],
                    'account_nature' => $accountData['account_nature'],
                ]);

                $this->createAccountWithChildren($childData, $account);
            }
        }

        return $account;
    }

    /**
     * تحديث إعدادات parent_account_id في ملف التكوين
     */
    public function updateEntityConfigs(): void
    {
        try {
            // الحصول على IDs الحسابات الأب
            $customersIndividualAccount = Account::where('code', 'CUS-IND')->first();
            $customersCompanyAccount = Account::where('code', 'CUS-COM')->first();
            $suppliersIndividualAccount = Account::where('code', 'SUP-IND')->first();
            $suppliersCompanyAccount = Account::where('code', 'SUP-COM')->first();
            $warehousesAccount = Account::where('code', 'WHE-MAIN')->first();
            $banksAccount = Account::where('code', 'BNK-MAIN')->first();
            $branchesAccount = Account::where('code', 'BRN-MAIN')->first();

            // تحديث التكوين (هذا مثال - في الواقع قد تحتاج لتحديث قاعدة البيانات أو cache)
            $updatedConfigs = [
                'App\Models\Customer' => [
                    'parent_accounts' => [
                        'customer_type=individual' => $customersIndividualAccount?->id,
                        'customer_type=company' => $customersCompanyAccount?->id,
                    ]
                ],
                'App\Models\Supplier' => [
                    'parent_accounts' => [
                        'supplier_type=individual' => $suppliersIndividualAccount?->id,
                        'supplier_type=company' => $suppliersCompanyAccount?->id,
                    ]
                ],
                'App\Models\Warehouse' => [
                    'parent_account_id' => $warehousesAccount?->id,
                ],
                'App\Models\Bank' => [
                    'parent_account_id' => $banksAccount?->id,
                ],
                'App\Models\Branch' => [
                    'parent_account_id' => $branchesAccount?->id,
                ],
            ];

            Log::info('Entity configurations updated with parent account IDs');
        } catch (Exception $e) {
            Log::error('Failed to update entity configurations: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * التحقق من وجود الهيكل الأساسي
     */
    public function checkBasicStructureExists(): array
    {
        $structure = Config::get('account_linking.parent_accounts_structure', []);
        $status = [];

        foreach ($structure as $key => $accountData) {
            $account = Account::where('code', $accountData['code'])->first();
            $status[$key] = [
                'exists' => (bool) $account,
                'account_id' => $account?->id,
                'code' => $accountData['code'],
                'name' => $accountData['name']
            ];

            // فحص الحسابات الفرعية
            if (isset($accountData['children'])) {
                $status[$key]['children'] = [];
                foreach ($accountData['children'] as $childKey => $childData) {
                    $childAccount = Account::where('code', $childData['code'])->first();
                    $status[$key]['children'][$childKey] = [
                        'exists' => (bool) $childAccount,
                        'account_id' => $childAccount?->id,
                        'code' => $childData['code'],
                        'name' => $childData['name']
                    ];
                }
            }
        }

        return $status;
    }

    /**
     * الحصول على إحصائيات الهيكل
     */
    public function getStructureStatistics(): array
    {
        $structure = $this->checkBasicStructureExists();
        $totalAccounts = 0;
        $existingAccounts = 0;

        foreach ($structure as $account) {
            $totalAccounts++;
            if ($account['exists']) {
                $existingAccounts++;
            }

            if (isset($account['children'])) {
                foreach ($account['children'] as $child) {
                    $totalAccounts++;
                    if ($child['exists']) {
                        $existingAccounts++;
                    }
                }
            }
        }

        return [
            'total_accounts' => $totalAccounts,
            'existing_accounts' => $existingAccounts,
            'missing_accounts' => $totalAccounts - $existingAccounts,
            'completion_percentage' => $totalAccounts > 0 ? round(($existingAccounts / $totalAccounts) * 100, 2) : 0,
            'is_complete' => $existingAccounts === $totalAccounts,
        ];
    }
}
