<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'description',
        'category_id',
        'brand_id',
        'unit_id',
        'purchase_price',
        'selling_price',
        'wholesale_price',
        'tax_rate',
        'barcode',
        'sku',
        'weight',
        'dimensions',
        'image',
        'notes',
        'is_service',
        'track_inventory',
        'min_stock_level',
        'max_stock_level',
        'reorder_point',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_service' => 'boolean',
        'track_inventory' => 'boolean',
        'purchase_price' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'wholesale_price' => 'decimal:2',
        'tax_rate' => 'decimal:2',
        'weight' => 'decimal:3',
        'min_stock_level' => 'integer',
        'max_stock_level' => 'integer',
        'reorder_point' => 'integer',
        'dimensions' => 'array'
    ];

    // Relationships
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }

    public function scopeServices($query)
    {
        return $query->where('is_service', true);
    }

    public function scopeProducts($query)
    {
        return $query->where('is_service', false);
    }

    public function scopeLowStock($query)
    {
        return $query->where('track_inventory', true)
            ->whereColumn('current_stock', '<=', 'min_stock_level');
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($query) use ($search) {
            $query->where('name', 'like', '%' . $search . '%')
                ->orWhere('code', 'like', '%' . $search . '%')
                ->orWhere('barcode', 'like', '%' . $search . '%')
                ->orWhere('sku', 'like', '%' . $search . '%');
        });
    }

    // Accessors
    public function getCurrentStockAttribute()
    {
        // This would be calculated from inventory movements
        // For now, returning a placeholder
        return 0;
    }

    public function getStockStatusAttribute()
    {
        if (!$this->track_inventory) {
            return 'not_tracked';
        }

        $currentStock = $this->current_stock;

        if ($currentStock <= $this->min_stock_level) {
            return 'low';
        } elseif ($currentStock <= $this->reorder_point) {
            return 'reorder';
        } elseif ($currentStock >= $this->max_stock_level) {
            return 'high';
        }

        return 'normal';
    }

    public function getTypeTextAttribute()
    {
        return $this->is_service ? __('app.service') : __('app.product');
    }
}
