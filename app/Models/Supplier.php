<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'supplier_type',
        'phone',
        'email',
        'national_id',
        'tax_number',
        'is_active',
        'address',
        'city',
        'state',
        'postal_code',
        'country',
        'credit_limit',
        'current_balance',
        'payment_terms',
        'payment_days',
        'discount_percentage',
        'contact_person',
        'website',
        'whatsapp',
        'notes',
        'rating',
        'delivery_rating',
        'quality_rating',
        'last_supply_date'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'credit_limit' => 'decimal:2',
        'current_balance' => 'decimal:2',
        'discount_percentage' => 'decimal:2',
        'rating' => 'decimal:1',
        'delivery_rating' => 'decimal:1',
        'quality_rating' => 'decimal:1',
        'last_supply_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * الحصول على النوع مترجماً
     */
    public function getTypeNameAttribute(): string
    {
        return $this->supplier_type === 'individual'
            ? __('app.individual')
            : __('app.company');
    }

    /**
     * الحصول على شروط الدفع مترجمة
     */
    public function getPaymentTermsNameAttribute(): string
    {
        return match ($this->payment_terms) {
            'cash' => __('app.cash'),
            'credit' => __('app.credit'),
            'installment' => __('app.installment'),
            default => '-'
        };
    }

    /**
     * الحصول على الحالة مترجمة
     */
    public function getStatusNameAttribute(): string
    {
        return $this->is_active ? __('app.active') : __('app.inactive');
    }

    /**
     * الحصول على متوسط التقييم
     */
    public function getAverageRatingAttribute(): float
    {
        $ratings = collect([
            $this->rating,
            $this->delivery_rating,
            $this->quality_rating
        ])->filter(fn($rating) => $rating > 0);

        return $ratings->isEmpty() ? 0 : round($ratings->average(), 1);
    }

    /**
     * Scopes for filtering
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }

    public function scopeIndividual($query)
    {
        return $query->where('supplier_type', 'individual');
    }

    public function scopeCompany($query)
    {
        return $query->where('supplier_type', 'company');
    }

    public function scopeWithBalance($query)
    {
        return $query->where('current_balance', '!=', 0);
    }

    public function scopeByPaymentTerms($query, $terms)
    {
        return $query->where('payment_terms', $terms);
    }

    public function scopeByCity($query, $city)
    {
        return $query->where('city', 'like', "%{$city}%");
    }

    public function scopeByRating($query, $rating)
    {
        return $query->where('rating', '>=', $rating);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
                ->orWhere('code', 'like', "%{$search}%")
                ->orWhere('phone', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%");
        });
    }
}
