<?php

namespace App\Services;

use App\Models\Account;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as SupportCollection;

class AccountService extends BaseService
{
    public function __construct()
    {
        parent::__construct(new Account());
    }

    /**
     * احصل على قائمة الحسابات المؤهلة لتكون حسابات أب
     */
    public function getAccountsForSelect($accountType = null, $excludeId = null): SupportCollection
    {
        $query = Account::query()
            ->where('is_active', true)
            ->where(function ($q) {
                // فقط العناوين والحسابات الفرعية يمكن أن تكون أباً - ليس الحسابات التشغيلية
                $q->where('account_level_type', 'title')
                    ->orWhere('account_level_type', 'sub_account');
            })
            ->orderBy('code');

        // إزالة تقييد نوع الحساب لأن الحسابات ستنشأ يدوياً
        // if ($accountType) {
        //     $query->where('account_type', $accountType);
        // }

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->get()->map(function ($account) {
            return [
                'id' => $account->id,
                'code' => $account->code,
                'name' => $account->name,
                'full_name' => $account->code . ' - ' . $account->name,
                'level' => $account->level,
                'account_type' => $account->account_type,
                'account_level_type' => $account->account_level_type
            ];
        });
    }

    /**
     * احصل على شجرة الحسابات للعرض
     */
    public function getAccountsTree(array $filters = [])
    {
        $includeInactive = isset($filters['is_active']) && $filters['is_active'] === null;
        return Account::getAccountTree(null, 0, $includeInactive);
    }

    /**
     * احصل على جميع الحسابات مع التصفية
     */
    public function getAllAccountsFlat(array $filters = []): array
    {
        $query = Account::query()
            ->with(['parent', 'children', 'branch']);

        // تطبيق الفلاتر
        if (!empty($filters['search'])) {
            $searchTerm = $filters['search'];
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                    ->orWhere('code', 'like', "%{$searchTerm}%")
                    ->orWhere('name_en', 'like', "%{$searchTerm}%");
            });
        }

        if (!empty($filters['account_type'])) {
            $query->where('account_type', $filters['account_type']);
        }

        if (isset($filters['is_active']) && $filters['is_active'] !== null) {
            $query->where('is_active', $filters['is_active']);
        }

        if (!empty($filters['level'])) {
            $query->where('level', $filters['level']);
        }

        if (!empty($filters['branch_id'])) {
            $query->where('branch_id', $filters['branch_id']);
        }

        // إضافة التصفح
        $perPage = $filters['perPage'] ?? 15;
        $accounts = $query->orderBy('code')->paginate($perPage);

        return [
            'accounts' => $accounts,
            'total' => $accounts->total(),
            'statistics' => $this->getSimpleStatistics()
        ];
    }

    /**
     * احصل على إحصائيات بسيطة للحسابات
     */
    public function getSimpleStatistics(): array
    {
        return [
            'total_accounts' => Account::count(),
            'active_accounts' => Account::where('is_active', true)->count(),
            'main_accounts' => Account::whereNull('parent_id')->count(),
            'sub_accounts' => Account::whereNotNull('parent_id')->count(),
            'leaf_accounts' => Account::where('has_children', false)->count(),
            'assets' => Account::where('account_type', 'assets')->count(),
            'liabilities' => Account::where('account_type', 'liabilities')->count(),
            'equity' => Account::where('account_type', 'equity')->count(),
            'revenue' => Account::where('account_type', 'revenue')->count(),
            'expenses' => Account::where('account_type', 'expenses')->count(),
        ];
    }

    /**
     * إنشاء حساب جديد بنفس منطق النظام السابق
     * مثال على توليد الأكواد:
     * - حساب أصول رئيسي: 101 (النوع=1 + الترتيب=01)
     * - حساب فرعي أول: 10101 (الأب=101 + الترتيب=01)
     * - حساب فرعي ثاني: 10102 (الأب=101 + الترتيب=02)
     * - حساب فرعي للفرعي الأول: 1010101 (الأب=10101 + الترتيب=01)
     * المستوى = طول الكود ÷ 2 (101 = مستوى 1.5 ≈ 2, 10101 = مستوى 2.5 ≈ 3)
     */
    public function createAccount(array $data): array
    {
        try {
            // منطق توليد الكود والمستوى مثل GenIndexController
            $parent_code = '';
            $position = 0;

            if (!empty($data['parent_id'])) {
                // حساب فرعي - جلب بيانات الحساب الأب
                $parent = Account::find($data['parent_id']);
                if (!$parent) {
                    return $this->errorResponse('الحساب الأب غير موجود');
                }
                $parent_code = $parent->code;

                // حساب الترتيب الصحيح للابن الجديد - نفس منطق GenIndexController
                $maxPosition = Account::where('parent_id', $data['parent_id'])->max('sort_order');
                $position = is_null($maxPosition) ? 1 : $maxPosition + 1;
            } else {
                // حساب رئيسي - حساب الترتيب الصحيح للجذر - نفس منطق GenIndexController
                $maxPosition = Account::whereNull('parent_id')->max('sort_order');
                $position = is_null($maxPosition) ? 1 : $maxPosition + 1;
                $parent_code = '';
            }

            // توليد الكود الشجري الصحيح والمتسلسل - نفس منطق GenIndexController
            $code = str_pad($position, 2, '0', STR_PAD_LEFT);
            $code = $parent_code . $code;
            $level = strlen($code) / 2;

            $data['position'] = $position;
            $data['code'] = $code;
            $data['level'] = (int)$level;
            $data['sort_order'] = $position;

            // تحديد المستوى والطبيعة ونوع المستوى
            if (!empty($data['parent_id'])) {
                $parent = Account::find($data['parent_id']);
                if (!$parent) {
                    return $this->errorResponse('الحساب الأب غير موجود');
                }

                // وراثة نوع الحساب من الأب (إن وجد)
                if ($parent->account_type) {
                    $data['account_type'] = $parent->account_type;
                }

                // التحقق من صحة نوع مستوى الحساب بناءً على الأب
                if ($parent->account_level_type === 'account') {
                    return $this->errorResponse('لا يمكن إضافة حساب فرعي لحساب تشغيلي');
                }

                // العناوين والحسابات الفرعية لا تحتاج لطبيعة حساب
                if ($data['account_level_type'] === 'title' || $data['account_level_type'] === 'sub_account') {
                    $data['account_nature'] = null;
                } else {
                    // فقط الحسابات التشغيلية تحتاج لطبيعة حساب
                    if (empty($data['account_nature']) && $parent->account_nature) {
                        $data['account_nature'] = $parent->account_nature;
                    }
                }
            } else {
                // المستوى محسوب بالفعل من طول الكود
                $data['account_level_type'] = $data['account_level_type'] ?? 'title'; // الحسابات الرئيسية تكون عناوين بشكل افتراضي

                // إذا لم يتم تحديد نوع الحساب، يمكن تركه فارغاً لأنه سيحدد يدوياً
                $data['account_type'] = $data['account_type'] ?? null;

                // العناوين لا تحتاج لطبيعة حساب
                if ($data['account_level_type'] === 'title') {
                    $data['account_nature'] = null;
                }
            }
            $account = Account::create($data);
            $account->updateParentChildrenCount();

            return $this->successResponse(__('app.account_added_successfully'), $account);
        } catch (\Exception $e) {
            return $this->errorResponse(__('app.error_saving_account'));
        }
    }

    /**
     * تحديث حساب موجود
     */
    public function updateAccount(int $id, array $data): array
    {
        try {
            $account = Account::find($id);
            if (!$account) {
                return $this->errorResponse('الحساب غير موجود');
            }

            // التحقق من تغيير الحساب الأب
            if (isset($data['parent_id']) && $data['parent_id'] != $account->parent_id) {
                // التحقق من عدم جعل الحساب ابناً لنفسه أو لأحد أطفاله
                if ($data['parent_id'] == $account->id) {
                    return $this->errorResponse('لا يمكن جعل الحساب ابناً لنفسه');
                }

                if ($data['parent_id']) {
                    $parent = Account::find($data['parent_id']);
                    if (!$parent) {
                        return $this->errorResponse('الحساب الأب غير موجود');
                    }

                    // تحقق من عدم جعل الأب أحد أطفال الحساب الحالي
                    if ($account->isAncestorOf($parent)) {
                        return $this->errorResponse('لا يمكن جعل الحساب الفرعي أباً للحساب الأصلي');
                    }
                }
            }

            $account->update($data);
            $account->updateParentChildrenCount();

            return $this->successResponse(__('app.account_updated_successfully'), $account);
        } catch (\Exception $e) {
            return $this->errorResponse(__('app.error_saving_account'));
        }
    }

    /**
     * حذف حساب
     */
    public function deleteAccount(int $id): array
    {
        try {
            $account = Account::find($id);
            if (!$account) {
                return $this->errorResponse(__('app.account_not_found'));
            }

            // التحقق من إمكانية الحذف
            if ($account->children()->count() > 0) {
                return $this->errorResponse(__('app.cannot_delete_account_has_children'));
            }

            if ($account->balance != 0) {
                return $this->errorResponse(__('app.cannot_delete_account_non_zero_balance'));
            }

            // تحديث الحساب الأب
            $parent = $account->parent;
            $account->delete();

            if ($parent) {
                $parent->updateChildrenCount();
            }

            return $this->successResponse(__('app.account_deleted_successfully'));
        } catch (\Exception $e) {
            return $this->errorResponse(__('app.error_deleting_account'));
        }
    }

    /**
     * تغيير حالة الحساب
     */
    public function toggleAccountStatus(int $id): array
    {
        try {
            $account = Account::find($id);
            if (!$account) {
                return $this->errorResponse('الحساب غير موجود');
            }

            // التحقق من وجود حسابات فرعية
            if ($account->has_children) {
                return $this->errorResponse('لا يمكن تغيير حالة الحساب لأنه يحتوي على حسابات فرعية');
            }

            $account->is_active = !$account->is_active;
            $account->save();

            $message = $account->is_active ?
                __('app.account_activated_successfully') :
                __('app.account_deactivated_successfully');

            return $this->successResponse($message, $account);
        } catch (\Exception $e) {
            return $this->errorResponse(__('app.error_changing_account_status'));
        }
    }

    /**
     * البحث في الحسابات
     */
    public function searchAccounts(string $term): Collection
    {
        return Account::where('name', 'like', "%{$term}%")
            ->orWhere('code', 'like', "%{$term}%")
            ->orWhere('name_en', 'like', "%{$term}%")
            ->where('is_active', true)
            ->orderBy('code')
            ->limit(20)
            ->get();
    }

    /**
     * الحصول على تفاصيل حساب معين
     */
    public function getAccountDetails(int $id): array
    {
        try {
            $account = Account::with(['parent', 'children', 'branch'])
                ->find($id);

            if (!$account) {
                return $this->errorResponse('الحساب غير موجود');
            }

            return $this->successResponse('تفاصيل الحساب', $account);
        } catch (\Exception $e) {
            return $this->errorResponse(__('app.error_loading_account'));
        }
    }

    /**
     * التحقق من صحة الكود
     */
    public function validateAccountCode(string $code, int $excludeId = null): bool
    {
        $query = Account::where('code', $code);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->doesntExist();
    }

    /**
     * احصل على الحسابات بشكل شجري للعرض
     */
    public function getAccountsTreeView($accountType = null, array $filters = []): SupportCollection
    {
        $query = Account::query()
            ->with(['parent', 'children', 'branch'])
            ->orderBy('code');

        if ($accountType) {
            $query->where('account_type', $accountType);
        }

        // فلترة الحالة
        if (isset($filters['is_active']) && $filters['is_active'] !== null) {
            $query->where('is_active', $filters['is_active']);
        }

        $accounts = $query->get();

        return $this->buildTreeStructure($accounts);
    }

    /**
     * بناء البنية الشجرية للحسابات
     */
    private function buildTreeStructure(Collection $accounts, $parentId = null, $level = 0): SupportCollection
    {
        $tree = collect();

        foreach ($accounts->where('parent_id', $parentId) as $account) {
            $account->level_display = $level;
            $account->indent = str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $level);
            $tree->push($account);

            // إضافة الأطفال
            $children = $this->buildTreeStructure($accounts, $account->id, $level + 1);
            $tree = $tree->merge($children);
        }

        return $tree;
    }

    /**
     * احصل على إحصائيات تفصيلية
     */
    public function getDetailedStatistics(): array
    {
        $stats = $this->getSimpleStatistics();

        // إضافة إحصائيات تفصيلية
        $stats['by_level'] = [];
        for ($i = 1; $i <= 5; $i++) {
            $stats['by_level'][$i] = Account::where('level', $i)->count();
        }

        $stats['by_nature'] = [
            'debit' => Account::where('account_nature', 'debit')->count(),
            'credit' => Account::where('account_nature', 'credit')->count(),
            'both' => Account::where('account_nature', 'both')->count(),
        ];

        $stats['by_level_type'] = [
            'title' => Account::where('account_level_type', 'title')->count(),
            'account' => Account::where('account_level_type', 'account')->count(),
            'sub_account' => Account::where('account_level_type', 'sub_account')->count(),
        ];

        return $stats;
    }

    /**
     * إعادة حساب جميع أكواد الحسابات بناءً على النظام الجديد
     * مفيد في حالة الترحيل من النظام القديم
     */
    public function recalculateAllCodes(): array
    {
        try {
            $this->recalculateCodesRecursive();
            return $this->successResponse('تم إعادة حساب جميع أكواد الحسابات بنجاح');
        } catch (\Exception $e) {
            return $this->errorResponse('فشل في إعادة حساب الأكواد: ' . $e->getMessage());
        }
    }

    /**
     * إعادة حساب الأكواد بشكل متداخل
     */
    private function recalculateCodesRecursive($parentId = null, $parentCode = '', $accountType = null)
    {
        $query = Account::where('parent_id', $parentId)->orderBy('sort_order');

        if ($parentId === null) {
            // الحسابات الرئيسية - نحتاج لتجميعها بحسب النوع
            $accountTypes = ['assets', 'liabilities', 'equity', 'revenue', 'expenses'];
            $typeMapping = [
                'assets' => '1',
                'liabilities' => '2',
                'equity' => '3',
                'revenue' => '4',
                'expenses' => '5'
            ];

            foreach ($accountTypes as $type) {
                $accounts = Account::whereNull('parent_id')
                    ->where('account_type', $type)
                    ->orderBy('sort_order')
                    ->get();

                $position = 1;
                foreach ($accounts as $account) {
                    $newCode = $typeMapping[$type] . str_pad($position, 2, '0', STR_PAD_LEFT);
                    $account->update([
                        'code' => $newCode,
                        'level' => strlen($newCode) / 2
                    ]);

                    // إعادة حساب أكواد الأطفال
                    $this->recalculateCodesRecursive($account->id, $newCode);
                    $position++;
                }
            }
        } else {
            $accounts = $query->get();
            $position = 1;

            foreach ($accounts as $account) {
                $newCode = $parentCode . str_pad($position, 2, '0', STR_PAD_LEFT);
                $account->update([
                    'code' => $newCode,
                    'level' => strlen($newCode) / 2
                ]);

                // إعادة حساب أكواد الأطفال
                $this->recalculateCodesRecursive($account->id, $newCode);
                $position++;
            }
        }
    }
}
