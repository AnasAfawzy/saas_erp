<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use App\Helpers\CurrencyHelper;

class BladeServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Currency formatting directives
        Blade::directive('currency', function ($expression) {
            return "<?php echo format_currency($expression); ?>";
        });

        Blade::directive('balance', function ($expression) {
            return "<?php echo format_balance($expression); ?>";
        });

        Blade::directive('number', function ($expression) {
            return "<?php echo format_number($expression); ?>";
        });

        // Balance with styling directive
        Blade::directive('accountBalance', function ($expression) {
            return "<?php
                \$data = format_account_balance($expression, 'asset', true);
                echo '<span class=\"' . \$data['class'] . '\">' . \$data['text'] . '</span>';
            ?>";
        });

        Blade::directive('customerBalance', function ($expression) {
            return "<?php
                \$data = format_customer_balance($expression, true);
                echo '<span class=\"' . \$data['class'] . '\" title=\"' . \$data['label'] . '\">' . \$data['text'] . '</span>';
            ?>";
        });

        Blade::directive('supplierBalance', function ($expression) {
            return "<?php
                \$data = format_supplier_balance($expression, true);
                echo '<span class=\"' . \$data['class'] . '\" title=\"' . \$data['label'] . '\">' . \$data['text'] . '</span>';
            ?>";
        });
    }
}
