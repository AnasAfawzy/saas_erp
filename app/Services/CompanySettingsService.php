<?php

namespace App\Services;

use App\Models\CompanySettings;
use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class CompanySettingsService extends BaseService
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct(new CompanySettings());
    }

    /**
     * Get company settings (singleton pattern)
     */
    public function getSettings(): CompanySettings
    {
        try {
            return CompanySettings::getSettings();
        } catch (Exception $e) {
            throw new Exception('Error retrieving company settings: ' . $e->getMessage());
        }
    }

    /**
     * Update company settings
     */
    public function updateSettings(array $data): CompanySettings
    {
        try {
            // Validate decimal places
            if (isset($data['decimal_places'])) {
                $data['decimal_places'] = (int) $data['decimal_places'];
                if ($data['decimal_places'] < 0 || $data['decimal_places'] > 4) {
                    throw new Exception('Decimal places must be between 0 and 4');
                }
            }

            // Validate currency
            if (isset($data['currency'])) {
                $allowedCurrencies = ['SAR', 'USD', 'EUR'];
                if (!in_array($data['currency'], $allowedCurrencies)) {
                    throw new Exception('Invalid currency selected');
                }
            }

            // Validate date format
            if (isset($data['date_format'])) {
                $allowedFormats = ['d/m/Y', 'm/d/Y', 'Y-m-d'];
                if (!in_array($data['date_format'], $allowedFormats)) {
                    throw new Exception('Invalid date format selected');
                }
            }

            // Handle boolean values
            if (isset($data['enable_notifications'])) {
                $data['enable_notifications'] = filter_var($data['enable_notifications'], FILTER_VALIDATE_BOOLEAN);
            }

            if (isset($data['is_active'])) {
                $data['is_active'] = filter_var($data['is_active'], FILTER_VALIDATE_BOOLEAN);
            }

            return CompanySettings::updateSettings($data);
        } catch (Exception $e) {
            throw new Exception('Error updating company settings: ' . $e->getMessage());
        }
    }

    /**
     * Upload company logo
     */
    public function uploadLogo(UploadedFile $file): string
    {
        try {
            // Validate file
            if (!$file->isValid()) {
                throw new Exception('Invalid file uploaded');
            }

            // Check file type
            $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
            if (!in_array($file->getMimeType(), $allowedTypes)) {
                throw new Exception('Only PNG and JPG images are allowed');
            }

            // Check file size (max 2MB)
            if ($file->getSize() > 2048 * 1024) {
                throw new Exception('File size must be less than 2MB');
            }

            // Delete old logo if exists
            $settings = $this->getSettings();
            if ($settings->logo_path && Storage::disk('public')->exists($settings->logo_path)) {
                Storage::disk('public')->delete($settings->logo_path);
            }

            // Store new logo
            $path = $file->store('company-logos', 'public');

            // Update settings with new logo path
            $this->updateSettings(['logo_path' => $path]);

            return $path;
        } catch (Exception $e) {
            throw new Exception('Error uploading logo: ' . $e->getMessage());
        }
    }

    /**
     * Delete company logo
     */
    public function deleteLogo(): bool
    {
        try {
            $settings = $this->getSettings();

            if ($settings->logo_path && Storage::disk('public')->exists($settings->logo_path)) {
                Storage::disk('public')->delete($settings->logo_path);
                $this->updateSettings(['logo_path' => null]);
                return true;
            }

            return false;
        } catch (Exception $e) {
            throw new Exception('Error deleting logo: ' . $e->getMessage());
        }
    }

    /**
     * Get formatted decimal places for display
     */
    public function formatNumber(float $number, ?int $decimalPlaces = null): string
    {
        try {
            $settings = $this->getSettings();
            $decimals = $decimalPlaces ?? $settings->decimal_places;

            return number_format($number, $decimals);
        } catch (Exception $e) {
            throw new Exception('Error formatting number: ' . $e->getMessage());
        }
    }

    /**
     * Get formatted currency value
     */
    public function formatCurrency(float $amount, ?int $decimalPlaces = null): string
    {
        try {
            $settings = $this->getSettings();
            $decimals = $decimalPlaces ?? $settings->decimal_places;
            $formattedAmount = number_format($amount, $decimals);

            $currencySymbols = [
                'SAR' => 'ر.س',
                'USD' => '$',
                'EUR' => '€'
            ];

            $symbol = $currencySymbols[$settings->currency] ?? $settings->currency;

            return $formattedAmount . ' ' . $symbol;
        } catch (Exception $e) {
            throw new Exception('Error formatting currency: ' . $e->getMessage());
        }
    }

    /**
     * Get formatted date
     */
    public function formatDate(\DateTime $date): string
    {
        try {
            $settings = $this->getSettings();
            return $date->format($settings->date_format);
        } catch (Exception $e) {
            throw new Exception('Error formatting date: ' . $e->getMessage());
        }
    }

    /**
     * Reset settings to default values
     */
    public function resetToDefaults(): CompanySettings
    {
        try {
            $defaultData = [
                'company_name' => '',
                'currency' => 'SAR',
                'date_format' => 'd/m/Y',
                'decimal_places' => 2,
                'enable_notifications' => true,
                'is_active' => true,
            ];

            return $this->updateSettings($defaultData);
        } catch (Exception $e) {
            throw new Exception('Error resetting settings: ' . $e->getMessage());
        }
    }
}
