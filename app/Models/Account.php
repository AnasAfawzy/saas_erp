<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Account extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'name_en',
        'parent_id',
        'branch_id',
        'account_type',
        'account_nature',
        'account_level_type',
        'level',
        'is_active',
        'has_children',
        'description',
        'balance',
        'sort_order'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'has_children' => 'boolean',
        'balance' => 'decimal:2',
        'level' => 'integer',
        'sort_order' => 'integer'
    ];

    // العلاقات
    public function parent()
    {
        return $this->belongsTo(Account::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Account::class, 'parent_id')->orderBy('sort_order')->orderBy('code');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function allChildren(): HasMany
    {
        return $this->children()->with('allChildren');
    }

    // النطاقات (Scopes)
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('account_type', $type);
    }

    public function scopeMainAccounts($query)
    {
        return $query->whereNull('parent_id');
    }

    public function scopeWithChildren($query)
    {
        return $query->where('has_children', true);
    }

    public function scopeLeafAccounts($query)
    {
        return $query->where('has_children', false);
    }

    public function scopeTitles($query)
    {
        return $query->where('account_level_type', 'title');
    }

    public function scopeAccounts($query)
    {
        return $query->where('account_level_type', 'account');
    }

    public function scopeSubAccounts($query)
    {
        return $query->where('account_level_type', 'sub_account');
    }

    public function scopeCanHaveChildren($query)
    {
        return $query->whereIn('account_level_type', ['title', 'account']);
    }

    // الخصائص المحسوبة
    public function getAccountTypeNameAttribute(): string
    {
        return match ($this->account_type) {
            'assets' => 'الأصول',
            'liabilities' => 'الخصوم',
            'equity' => 'حقوق الملكية',
            'revenue' => 'الإيرادات',
            'expenses' => 'المصروفات',
            default => $this->account_type ?? 'غير محدد'
        };
    }

    public function getAccountNatureNameAttribute(): string
    {
        return match ($this->account_nature) {
            'debit' => 'مدين',
            'credit' => 'دائن',
            'both' => 'مدين ودائن',
            default => $this->account_nature ?? 'غير محدد'
        };
    }

    public function getAccountLevelTypeNameAttribute(): string
    {
        return match ($this->account_level_type) {
            'title' => 'عنوان',
            'account' => 'حساب',
            'sub_account' => 'حساب فرعي',
            default => $this->account_level_type
        };
    }

    public function getFullCodeAttribute(): string
    {
        $path = collect();
        $account = $this;

        while ($account) {
            $path->prepend($account->code);
            $account = $account->parent;
        }

        return $path->implode('-');
    }

    public function getFullNameAttribute(): string
    {
        $path = collect();
        $account = $this;

        while ($account) {
            $path->prepend($account->name);
            $account = $account->parent;
        }

        return $path->implode(' > ');
    }

    public function getIndentedNameAttribute(): string
    {
        return str_repeat('── ', $this->level - 1) . $this->name;
    }

    // الدوال المساعدة
    public function updateChildrenCount(): void
    {
        $this->has_children = $this->children()->count() > 0;
        $this->save();
    }

    public function updateParentChildrenCount(): void
    {
        if ($this->parent) {
            $this->parent->updateChildrenCount();
        }
    }

    public function generateNextChildCode(): string
    {
        // حساب أعلى position للأطفال في نفس المستوى
        $maxPosition = self::where('parent_id', $this->id)->max('sort_order') ?? 0;
        $position = $maxPosition + 1;

        // توليد الكود الشجري مع padding
        $positionCode = str_pad($position, 2, '0', STR_PAD_LEFT);

        // دمج كود الأب مع position الجديد
        return $this->code . $positionCode;
    }

    public static function generateMainAccountCode($accountType): string
    {
        $typeMapping = [
            'assets' => '1',
            'liabilities' => '2',
            'equity' => '3',
            'revenue' => '4',
            'expenses' => '5'
        ];

        $prefix = $typeMapping[$accountType] ?? '9';

        // حساب أعلى position للحسابات الرئيسية من نفس النوع
        $maxPosition = self::where('account_type', $accountType)
            ->whereNull('parent_id')
            ->max('sort_order') ?? 0;

        $position = $maxPosition + 1;

        // توليد الكود بنفس طريقة النظام السابق
        $positionCode = str_pad($position, 2, '0', STR_PAD_LEFT);

        return $prefix . $positionCode;
    }

    // وظائف إضافية مستوحاة من النظام السابق

    /**
     * احصل على الحسابات المؤهلة لتكون حسابات أب
     */
    public static function getParentCandidates($accountType = null, $excludeId = null)
    {
        $query = self::where(function ($q) {
            // الحسابات التي يمكن أن تكون أب: العناوين والحسابات الرئيسية
            $q->where('account_level_type', 'title')
                ->orWhere('account_level_type', 'account');
        })->where('is_active', true);

        if ($accountType) {
            $query->where('account_type', $accountType);
        }

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->orderBy('code')->get();
    }

    /**
     * احصل على الحسابات التي لديها حسابات فرعية أو قابلة للحركات المالية
     */
    public static function getActiveWithChildren()
    {
        return self::where('is_active', true)
            ->where(function ($q) {
                $q->where('has_children', true)
                    ->orWhere('account_level_type', 'sub_account');
            })
            ->orderBy('code')
            ->get();
    }

    /**
     * احصل على شجرة الحسابات مع العمق
     */
    public static function getAccountTree($parentId = null, $depth = 0, $includeInactive = false)
    {
        $query = self::where('parent_id', $parentId);

        if (!$includeInactive) {
            $query->where('is_active', true);
        }

        $accounts = $query->orderBy('sort_order')
            ->orderBy('code')
            ->get();

        $tree = collect();

        foreach ($accounts as $account) {
            $account->depth = $depth;
            $account->indent = str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $depth);
            $tree->push($account);

            // إضافة الأطفال بشكل متداخل
            $children = self::getAccountTree($account->id, $depth + 1, $includeInactive);
            $tree = $tree->concat($children);
        }

        return $tree;
    }

    /**
     * تحقق من إمكانية حذف الحساب
     */
    public function canBeDeleted(): bool
    {
        // لا يمكن حذف الحساب إذا كان له أطفال أو رصيد غير صفر
        return !$this->has_children && $this->balance == 0;
    }

    /**
     * احصل على مسار الحساب الكامل
     */
    public function getAccountPath(): string
    {
        $path = collect();
        $account = $this;

        while ($account) {
            $path->prepend($account->name);
            $account = $account->parent;
        }

        return $path->implode(' > ');
    }
}
