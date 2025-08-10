<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class AccountableAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'account_id',
        'accountable_type',
        'accountable_id',
        'auto_created',
        'sync_settings',
        'last_sync_at',
    ];

    protected $casts = [
        'auto_created' => 'boolean',
        'sync_settings' => 'array',
        'last_sync_at' => 'datetime',
    ];

    /**
     * العلاقة مع جدول الحسابات
     */
    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    /**
     * العلاقة Polymorphic مع الكيانات المختلفة
     */
    public function accountable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Scope للحصول على الحسابات المنشأة تلقائياً
     */
    public function scopeAutoCreated($query)
    {
        return $query->where('auto_created', true);
    }

    /**
     * Scope للحصول على الحسابات المنشأة يدوياً
     */
    public function scopeManuallyCreated($query)
    {
        return $query->where('auto_created', false);
    }

    /**
     * Scope للبحث بنوع الكيان
     */
    public function scopeByEntityType($query, string $entityType)
    {
        return $query->where('accountable_type', $entityType);
    }

    /**
     * الحصول على اسم نوع الكيان مترجماً
     */
    public function getEntityTypeNameAttribute(): string
    {
        return match ($this->accountable_type) {
            'App\Models\Customer' => __('app.customer'),
            'App\Models\Supplier' => __('app.supplier'),
            'App\Models\Warehouse' => __('app.warehouse'),
            'App\Models\Bank' => __('app.bank'),
            'App\Models\Branch' => __('app.branch'),
            default => class_basename($this->accountable_type)
        };
    }

    /**
     * فحص ما إذا كان الربط نشطاً
     */
    public function isActive(): bool
    {
        return $this->account && $this->account->is_active &&
            $this->accountable && ($this->accountable->is_active ?? true);
    }

    /**
     * الحصول على تاريخ آخر مزامنة منسقاً
     */
    public function getFormattedLastSyncAttribute(): ?string
    {
        return $this->last_sync_at?->diffForHumans();
    }
}
