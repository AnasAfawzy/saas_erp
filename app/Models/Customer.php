<?php

namespace App\Models;

use App\Traits\HasAccount;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Customer extends Model
{
    use HasFactory, HasAccount;

    protected $fillable = [
        'code',
        'name',
        'customer_type',
        'phone',
        'email',
        'national_id',
        'tax_number',
        'address',
        'city',
        'state',
        'postal_code',
        'country',
        'credit_limit',
        'payment_terms',
        'payment_days',
        'discount_percentage',
        'current_balance',
        'total_purchases',
        'contact_person',
        'website',
        'whatsapp',
        'notes',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'credit_limit' => 'decimal:2',
        'discount_percentage' => 'decimal:2',
        'current_balance' => 'decimal:2',
        'total_purchases' => 'decimal:2',
        'payment_days' => 'integer',
    ];

    // Scopes
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeInactive(Builder $query): Builder
    {
        return $query->where('is_active', false);
    }

    public function scopeSearch(Builder $query, string $search): Builder
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
                ->orWhere('code', 'like', "%{$search}%")
                ->orWhere('phone', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%");
        });
    }

    public function scopeByType(Builder $query, string $type): Builder
    {
        return $query->where('customer_type', $type);
    }

    public function scopeWithBalance(Builder $query): Builder
    {
        return $query->where('current_balance', '>', 0);
    }

    public function scopeByCity(Builder $query, string $city): Builder
    {
        return $query->where('city', 'like', "%{$city}%");
    }

    public function scopeByPaymentTerms(Builder $query, string $terms): Builder
    {
        return $query->where('payment_terms', $terms);
    }

    // Accessors
    public function getBalanceStatusAttribute(): string
    {
        if ($this->current_balance > 0) {
            return 'positive';
        } elseif ($this->current_balance < 0) {
            return 'negative';
        }
        return 'zero';
    }

    public function getCustomerTypeNameAttribute(): string
    {
        return $this->customer_type === 'individual' ? 'فرد' : 'شركة';
    }

    public function getPaymentTermsNameAttribute(): string
    {
        return $this->payment_terms === 'cash' ? 'نقدي' : 'آجل';
    }

    public function getFullAddressAttribute(): string
    {
        $parts = array_filter([
            $this->address,
            $this->city,
            $this->state,
            $this->postal_code,
            $this->country
        ]);

        return implode(', ', $parts);
    }

    // Business Methods
    public function addPurchase(float $amount): void
    {
        $this->total_purchases += $amount;
        $this->current_balance += $amount;
        $this->save();
    }

    public function addPayment(float $amount): void
    {
        $this->current_balance -= $amount;
        $this->save();
    }

    public function calculateBalance(): float
    {
        return $this->current_balance;
    }
}
