<?php

namespace App\Helpers;

use App\Models\CompanySettings;

class CurrencyHelper
{
    /**
     * Get decimal places from company settings
     */
    public static function getDecimalPlaces(): int
    {
        static $decimalPlaces = null;

        if ($decimalPlaces === null) {
            $settings = CompanySettings::getSettings();
            $decimalPlaces = $settings->decimal_places ?? 2;
        }

        return $decimalPlaces;
    }

    /**
     * Get currency from company settings
     */
    public static function getCurrency(): string
    {
        static $currency = null;

        if ($currency === null) {
            $settings = CompanySettings::getSettings();
            $currency = $settings->currency ?? 'SAR';
        }

        return $currency;
    }

    /**
     * Format number with company decimal places
     */
    public static function formatNumber($number, ?int $decimals = null): string
    {
        $decimals = $decimals ?? self::getDecimalPlaces();
        return number_format((float) $number, $decimals);
    }

    /**
     * Format currency with company decimal places and currency symbol
     */
    public static function formatCurrency($amount, bool $showCurrency = true): string
    {
        $formattedAmount = self::formatNumber($amount);

        if ($showCurrency) {
            $currency = self::getCurrency();
            return $formattedAmount . ' ' . $currency;
        }

        return $formattedAmount;
    }

    /**
     * Format account balance
     */
    public static function formatBalance($balance, bool $showCurrency = false): string
    {
        $formatted = self::formatNumber($balance);

        if ($showCurrency) {
            $currency = self::getCurrency();
            $formatted .= ' ' . $currency;
        }

        return $formatted;
    }

    /**
     * Format customer balance with proper sign and color class
     */
    public static function formatCustomerBalance($balance, bool $returnArray = false)
    {
        $formatted = self::formatNumber(abs($balance));
        $currency = self::getCurrency();

        if ($balance > 0) {
            // العميل له رصيد (مدين للعميل)
            $text = $formatted . ' ' . $currency;
            $class = 'text-success';
            $label = __('app.credit_balance'); // له
        } elseif ($balance < 0) {
            // العميل عليه رصيد (دائن للشركة)
            $text = $formatted . ' ' . $currency;
            $class = 'text-danger';
            $label = __('app.debit_balance'); // عليه
        } else {
            $text = '0.00 ' . $currency;
            $class = 'text-muted';
            $label = __('app.no_balance');
        }

        if ($returnArray) {
            return [
                'text' => $text,
                'class' => $class,
                'label' => $label,
                'amount' => $formatted
            ];
        }

        return $text;
    }

    /**
     * Format supplier balance with proper sign and color class
     */
    public static function formatSupplierBalance($balance, bool $returnArray = false)
    {
        $formatted = self::formatNumber(abs($balance));
        $currency = self::getCurrency();

        if ($balance > 0) {
            // المورد له رصيد (مدين للمورد)
            $text = $formatted . ' ' . $currency;
            $class = 'text-danger';
            $label = __('app.payable_balance'); // مستحق للمورد
        } elseif ($balance < 0) {
            // المورد عليه رصيد (دائن للشركة)
            $text = $formatted . ' ' . $currency;
            $class = 'text-success';
            $label = __('app.receivable_balance'); // مستحق من المورد
        } else {
            $text = '0.00 ' . $currency;
            $class = 'text-muted';
            $label = __('app.no_balance');
        }

        if ($returnArray) {
            return [
                'text' => $text,
                'class' => $class,
                'label' => $label,
                'amount' => $formatted
            ];
        }

        return $text;
    }

    /**
     * Format account balance with proper accounting format
     */
    public static function formatAccountBalance($balance, string $accountType = 'asset', bool $returnArray = false)
    {
        $formatted = self::formatNumber(abs($balance));
        $currency = self::getCurrency();

        // تحديد اللون والعلامة حسب نوع الحساب
        $class = 'text-dark';
        $sign = '';

        if ($balance != 0) {
            switch ($accountType) {
                case 'asset':
                case 'expense':
                    $class = $balance >= 0 ? 'text-success' : 'text-danger';
                    $sign = $balance < 0 ? '-' : '';
                    break;

                case 'liability':
                case 'equity':
                case 'revenue':
                    $class = $balance <= 0 ? 'text-success' : 'text-danger';
                    $sign = $balance < 0 ? '-' : '';
                    break;

                default:
                    $class = $balance >= 0 ? 'text-success' : 'text-danger';
                    $sign = $balance < 0 ? '-' : '';
            }
        } else {
            $class = 'text-muted';
        }

        $text = $sign . $formatted;

        if ($returnArray) {
            return [
                'text' => $text,
                'class' => $class,
                'amount' => $formatted,
                'sign' => $sign
            ];
        }

        return $text;
    }

    /**
     * Parse formatted number back to float
     */
    public static function parseNumber(string $formattedNumber): float
    {
        // إزالة الفواصل والمسافات والعملة
        $cleaned = preg_replace('/[^\d.-]/', '', $formattedNumber);
        return (float) $cleaned;
    }

    /**
     * Validate if number has correct decimal places
     */
    public static function validateDecimalPlaces($number): bool
    {
        $decimals = self::getDecimalPlaces();
        $numberStr = (string) $number;

        if (strpos($numberStr, '.') === false) {
            return true; // Whole numbers are always valid
        }

        $decimalPart = substr($numberStr, strpos($numberStr, '.') + 1);
        return strlen($decimalPart) <= $decimals;
    }
}
