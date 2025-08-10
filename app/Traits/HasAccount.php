<?php

namespace App\Traits;

use App\Models\Account;
use App\Models\AccountableAccount;
use App\Services\AccountLinkingService;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\Relations\MorphOne;

trait HasAccount
{
    /**
     * العلاقة المباشرة مع جدول الربط
     */
    public function accountableAccount(): MorphOne
    {
        return $this->morphOne(AccountableAccount::class, 'accountable');
    }

    /**
     * العلاقة مع الحساب المحاسبي عبر جدول الربط
     */
    public function account(): HasOneThrough
    {
        return $this->hasOneThrough(
            Account::class,
            AccountableAccount::class,
            'accountable_id',
            'id',
            'id',
            'account_id'
        )->where('accountable_accounts.accountable_type', static::class);
    }

    /**
     * الحصول على الحساب المحاسبي كـ attribute
     */
    public function getAccountAttribute(): ?Account
    {
        return $this->account()->first();
    }

    /**
     * الحصول على كود الحساب المحاسبي
     */
    public function getAccountCodeAttribute(): ?string
    {
        return $this->account?->code;
    }

    /**
     * الحصول على رصيد الحساب المحاسبي
     */
    public function getAccountBalanceAttribute(): float
    {
        return $this->account?->balance ?? 0.0;
    }

    /**
     * فحص ما إذا كان للكيان حساب محاسبي
     */
    public function hasAccount(): bool
    {
        return $this->accountableAccount()->exists();
    }

    /**
     * إنشاء حساب محاسبي للكيان
     */
    public function createAccount(array $overrides = []): ?Account
    {
        if ($this->hasAccount()) {
            return $this->account;
        }

        $service = app(AccountLinkingService::class);
        return $service->createAccountForEntity($this, $overrides);
    }

    /**
     * مزامنة بيانات الكيان مع حسابه المحاسبي
     */
    public function syncWithAccount(): bool
    {
        if (!$this->hasAccount()) {
            return false;
        }

        $service = app(AccountLinkingService::class);
        return $service->syncEntityWithAccount($this);
    }

    /**
     * إلغاء ربط الكيان من حسابه المحاسبي
     */
    public function unlinkAccount(bool $deleteAccount = false): bool
    {
        if (!$this->hasAccount()) {
            return false;
        }

        $service = app(AccountLinkingService::class);
        return $service->unlinkEntityFromAccount($this, $deleteAccount);
    }

    /**
     * الحصول على معلومات حالة الربط
     */
    public function getAccountLinkingStatus(): array
    {
        $accountableAccount = $this->accountableAccount;

        return [
            'has_account' => $this->hasAccount(),
            'account_id' => $accountableAccount?->account_id,
            'account_code' => $this->account_code,
            'account_balance' => $this->account_balance,
            'auto_created' => $accountableAccount?->auto_created ?? false,
            'last_sync' => $accountableAccount?->formatted_last_sync,
            'is_active' => $accountableAccount?->isActive() ?? false,
        ];
    }

    /**
     * Boot method للـ trait
     */
    protected static function bootHasAccount(): void
    {
        // عند إنشاء كيان جديد
        static::created(function ($model) {
            if (config("account_linking.entities." . static::class . ".auto_create", false)) {
                $model->createAccount();
            }
        });

        // عند تحديث الكيان
        static::updated(function ($model) {
            if (
                $model->hasAccount() &&
                config("account_linking.entities." . static::class . ".auto_sync", false)
            ) {
                $model->syncWithAccount();
            }
        });

        // عند حذف الكيان
        static::deleting(function ($model) {
            $deleteAccount = config("account_linking.entities." . static::class . ".delete_account_on_entity_delete", false);
            $model->unlinkAccount($deleteAccount);
        });
    }

    /**
     * Scope للكيانات التي لها حسابات
     */
    public function scopeWithAccounts($query)
    {
        return $query->has('accountableAccount');
    }

    /**
     * Scope للكيانات التي ليس لها حسابات
     */
    public function scopeWithoutAccounts($query)
    {
        return $query->doesntHave('accountableAccount');
    }

    /**
     * Scope للكيانات المرتبطة بحسابات نشطة
     */
    public function scopeWithActiveAccounts($query)
    {
        return $query->whereHas('account', function ($q) {
            $q->where('is_active', true);
        });
    }
}
