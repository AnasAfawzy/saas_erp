<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'type',
        'address',
        'city',
        'state',
        'country',
        'phone',
        'manager_name',
        'manager_phone',
        'manager_email',
        'description',
        'notes',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Scopes للبحث والتصفية
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
                ->orWhere('code', 'like', "%{$search}%")
                ->orWhere('manager_name', 'like', "%{$search}%")
                ->orWhere('city', 'like', "%{$search}%")
                ->orWhere('address', 'like', "%{$search}%");
        });
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeByCity($query, $city)
    {
        return $query->where('city', 'like', "%{$city}%");
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // خاصية نوع المخزن
    public function getTypeDisplayAttribute()
    {
        $types = [
            'main' => 'رئيسي',
            'branch' => 'فرعي',
            'virtual' => 'افتراضي'
        ];

        return $types[$this->type] ?? $this->type;
    }
}
