<?php

if (!function_exists('format_currency')) {
    /**
     * Format currency using company settings
     */
    function format_currency($amount, bool $showCurrency = true): string
    {
        return \App\Helpers\CurrencyHelper::formatCurrency($amount, $showCurrency);
    }
}

if (!function_exists('format_balance')) {
    /**
     * Format balance using company settings
     */
    function format_balance($balance, bool $showCurrency = false): string
    {
        return \App\Helpers\CurrencyHelper::formatBalance($balance, $showCurrency);
    }
}

if (!function_exists('format_number')) {
    /**
     * Format number using company decimal places
     */
    function format_number($number, ?int $decimals = null): string
    {
        return \App\Helpers\CurrencyHelper::formatNumber($number, $decimals);
    }
}

if (!function_exists('format_customer_balance')) {
    /**
     * Format customer balance with proper sign and color
     */
    function format_customer_balance($balance, bool $returnArray = false)
    {
        return \App\Helpers\CurrencyHelper::formatCustomerBalance($balance, $returnArray);
    }
}

if (!function_exists('format_supplier_balance')) {
    /**
     * Format supplier balance with proper sign and color
     */
    function format_supplier_balance($balance, bool $returnArray = false)
    {
        return \App\Helpers\CurrencyHelper::formatSupplierBalance($balance, $returnArray);
    }
}

if (!function_exists('format_account_balance')) {
    /**
     * Format account balance with proper accounting format
     */
    function format_account_balance($balance, string $accountType = 'asset', bool $returnArray = false)
    {
        return \App\Helpers\CurrencyHelper::formatAccountBalance($balance, $accountType, $returnArray);
    }
}
