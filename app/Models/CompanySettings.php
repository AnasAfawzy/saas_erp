<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CompanySettings extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_name',
        'company_name_en',
        'commercial_number',
        'tax_number',
        'email',
        'phone',
        'website',
        'address',
        'currency',
        'date_format',
        'decimal_places',
        'enable_notifications',
        'logo_path',
        'is_active',
    ];

    protected $casts = [
        'enable_notifications' => 'boolean',
        'is_active' => 'boolean',
        'decimal_places' => 'integer',
    ];

    /**
     * Get the company settings instance (singleton pattern)
     */
    public static function getSettings()
    {
        return static::first() ?? static::create([
            'company_name' => '',
            'currency' => 'SAR',
            'date_format' => 'd/m/Y',
            'decimal_places' => 2,
            'enable_notifications' => true,
            'is_active' => true,
        ]);
    }

    /**
     * Update or create company settings
     */
    public static function updateSettings(array $data)
    {
        $settings = static::first();

        if ($settings) {
            $settings->update($data);
        } else {
            $settings = static::create($data);
        }

        return $settings;
    }
}
