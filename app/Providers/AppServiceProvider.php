<?php

namespace App\Providers;

use App\Models\Product;
use App\Models\Customer;
use App\Services\CompanySettingsService;
use App\Services\BranchService;
use App\Services\ProductService;
use App\Services\CustomerService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(CompanySettingsService::class, function ($app) {
            return new CompanySettingsService();
        });

        $this->app->singleton(BranchService::class, function ($app) {
            return new BranchService();
        });

        $this->app->singleton(ProductService::class, function ($app) {
            return new ProductService(new Product());
        });

        $this->app->singleton(CustomerService::class, function ($app) {
            return new CustomerService(new Customer());
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
