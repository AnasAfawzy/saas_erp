<!-- Bootstrap 5 Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
    <div class="container" style="margin-right:0px">
        <!-- Brand/Logo -->
        <a class="navbar-brand fw-bold" href="{{ route('dashboard') }}">
            <i class="fas fa-building me-2 text-primary"></i>
            {{ __('app.business_management_system') }}
        </a>

        <!-- Mobile Menu Toggle -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navigation Menu -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <!-- Main Navigation -->
            <ul class="navbar-nav me-auto">

                <!-- Master Data Dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="masterDataDropdown" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-database me-1"></i>
                        {{ __('app.master_data') }}
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="masterDataDropdown">
                        <li>
                            <h6 class="dropdown-header">{{ __('app.master_data') }}</h6>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('master-data.branches.index') }}">
                                <i class="fas fa-code-branch me-2"></i>
                                {{ __('app.branches') }}
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('master-data.units') }}">
                                <i class="fas fa-ruler me-2"></i>
                                {{ __('app.units') }}
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('master-data.categories') }}">
                                <i class="fas fa-tags me-2"></i>
                                {{ __('app.categories') }}
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('master-data.brands') }}">
                                <i class="fas fa-award me-2"></i>
                                {{ __('app.brands') }}
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('master-data.products') }}">
                                <i class="fas fa-box me-2"></i>
                                {{ __('app.products') }}
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('master-data.customers') }}">
                                <i class="fas fa-user-tie me-2"></i>
                                {{ __('app.customers') }}
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('master-data.suppliers') }}">
                                <i class="fas fa-truck me-2"></i>
                                {{ __('app.suppliers') }}
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('master-data.warehouses') }}">
                                <i class="fas fa-warehouse me-2"></i>
                                {{ __('app.warehouses') }}
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <a class="dropdown-item" href="#" onclick="alert('سيتم إنشاؤها لاحقاً')">
                                <i class="fas fa-users me-2"></i>
                                {{ __('app.users') }}
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Accounting & Finance Dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="accountingDropdown" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-calculator me-1"></i>
                        {{ __('app.accounting_finance') }}
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="accountingDropdown">
                        <li>
                            <h6 class="dropdown-header">{{ __('app.accounts') }}</h6>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('accounting.accounts') }}">
                                <i class="fas fa-sitemap me-2 text-primary"></i>
                                {{ __('app.chart_of_accounts') }}
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('accounting.account-linking') }}">
                                <i class="fas fa-link me-2 text-warning"></i>
                                إدارة ربط الحسابات
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="#" onclick="alert('سيتم إنشاؤها لاحقاً')">
                                <i class="fas fa-university me-2 text-info"></i>
                                {{ __('app.bank_accounts') }}
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="#" onclick="alert('سيتم إنشاؤها لاحقاً')">
                                <i class="fas fa-cash-register me-2 text-success"></i>
                                {{ __('app.cash_boxes') }}
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <h6 class="dropdown-header">{{ __('app.vouchers') }}</h6>
                        </li>
                        <li>
                            <a class="dropdown-item" href="#" onclick="alert('سيتم إنشاؤها لاحقاً')">
                                <i class="fas fa-receipt me-2 text-success"></i>
                                {{ __('app.receipt_voucher') }}
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="#" onclick="alert('سيتم إنشاؤها لاحقاً')">
                                <i class="fas fa-money-bill-wave me-2 text-danger"></i>
                                {{ __('app.payment_voucher') }}
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="#" onclick="alert('سيتم إنشاؤها لاحقاً')">
                                <i class="fas fa-book me-2 text-secondary"></i>
                                {{ __('app.journal_entry') }}
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <a class="dropdown-item" href="#" onclick="alert('سيتم إنشاؤها لاحقاً')">
                                <i class="fas fa-balance-scale me-2 text-warning"></i>
                                {{ __('app.bank_reconciliation') }}
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Inventory Management Dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="inventoryDropdown" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-boxes me-1"></i>
                        {{ __('app.inventory_management') }}
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="inventoryDropdown">
                        <li>
                            <a class="dropdown-item" href="#" onclick="alert('سيتم إنشاؤها لاحقاً')">
                                <i class="fas fa-clipboard-check me-2 text-primary"></i>
                                {{ __('app.physical_inventory') }}
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="#" onclick="alert('سيتم إنشاؤها لاحقاً')">
                                <i class="fas fa-exchange-alt me-2 text-info"></i>
                                {{ __('app.stock_movements') }}
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="#" onclick="alert('سيتم إنشاؤها لاحقاً')">
                                <i class="fas fa-barcode me-2 text-success"></i>
                                {{ __('app.barcode_management') }}
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="#" onclick="alert('سيتم إنشاؤها لاحقاً')">
                                <i class="fas fa-exclamation-triangle me-2 text-warning"></i>
                                {{ __('app.low_stock_alerts') }}
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Warehouse Permissions Dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="warehousePermissionsDropdown"
                        role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-clipboard-list me-1"></i>
                        {{ __('app.warehouse_permissions') }}
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="warehousePermissionsDropdown">
                        <li>
                            <a class="dropdown-item" href="#" onclick="alert('سيتم إنشاؤها لاحقاً')">
                                <i class="fas fa-exchange-alt me-2 text-primary"></i>
                                {{ __('app.receive_issue_permission') }}
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="#" onclick="alert('سيتم إنشاؤها لاحقاً')">
                                <i class="fas fa-arrows-alt me-2 text-info"></i>
                                {{ __('app.warehouse_transfer_permission') }}
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="#" onclick="alert('سيتم إنشاؤها لاحقاً')">
                                <i class="fas fa-balance-scale me-2 text-secondary"></i>
                                {{ __('app.inventory_adjustment_permission') }}
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Invoices Dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="invoicesDropdown" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-file-invoice me-1"></i>
                        {{ __('app.invoices') }}
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="invoicesDropdown">
                        <li>
                            <a class="dropdown-item" href="#" onclick="alert('سيتم إنشاؤها لاحقاً')">
                                <i class="fas fa-cash-register me-2 text-success"></i>
                                {{ __('app.sales_invoice') }}
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="#" onclick="alert('سيتم إنشاؤها لاحقاً')">
                                <i class="fas fa-shopping-cart me-2 text-info"></i>
                                {{ __('app.purchase_invoice') }}
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="#" onclick="alert('سيتم إنشاؤها لاحقاً')">
                                <i class="fas fa-concierge-bell me-2 text-warning"></i>
                                {{ __('app.service_invoice') }}
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- HR Management Dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="hrDropdown" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-user-friends me-1"></i>
                        {{ __('app.hr_management') }}
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="hrDropdown">
                        <li>
                            <a class="dropdown-item" href="#" onclick="alert('سيتم إنشاؤها لاحقاً')">
                                <i class="fas fa-id-card me-2 text-primary"></i>
                                {{ __('app.employees') }}
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="#" onclick="alert('سيتم إنشاؤها لاحقاً')">
                                <i class="fas fa-money-check-alt me-2 text-success"></i>
                                {{ __('app.payroll') }}
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="#" onclick="alert('سيتم إنشاؤها لاحقاً')">
                                <i class="fas fa-calendar-times me-2 text-info"></i>
                                {{ __('app.leaves') }}
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="#" onclick="alert('سيتم إنشاؤها لاحقاً')">
                                <i class="fas fa-clock me-2 text-warning"></i>
                                {{ __('app.attendance') }}
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Point of Sale Dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="posDropdown" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-cash-register me-1"></i>
                        {{ __('app.point_of_sale') }}
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="posDropdown">
                        <li>
                            <a class="dropdown-item" href="#" onclick="alert('سيتم إنشاؤها لاحقاً')">
                                <i class="fas fa-desktop me-2 text-primary"></i>
                                {{ __('app.pos_terminal') }}
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="#" onclick="alert('سيتم إنشاؤها لاحقاً')">
                                <i class="fas fa-user-tag me-2 text-info"></i>
                                {{ __('app.cashiers') }}
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="#" onclick="alert('سيتم إنشاؤها لاحقاً')">
                                <i class="fas fa-percentage me-2 text-success"></i>
                                {{ __('app.discounts_offers') }}
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="#" onclick="alert('سيتم إنشاؤها لاحقاً')">
                                <i class="fas fa-cash-register me-2 text-warning"></i>
                                {{ __('app.daily_closing') }}
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- System Management Dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="systemDropdown" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-tools me-1"></i>
                        {{ __('app.system_management') }}
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="systemDropdown">
                        <li>
                            <a class="dropdown-item" href="#" onclick="alert('سيتم إنشاؤها لاحقاً')">
                                <i class="fas fa-shield-alt me-2 text-primary"></i>
                                {{ __('app.roles_permissions') }}
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="#" onclick="alert('سيتم إنشاؤها لاحقاً')">
                                <i class="fas fa-database me-2 text-info"></i>
                                {{ __('app.backup_restore') }}
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="#" onclick="alert('سيتم إنشاؤها لاحقاً')">
                                <i class="fas fa-history me-2 text-secondary"></i>
                                {{ __('app.audit_trail') }}
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="#" onclick="alert('سيتم إنشاؤها لاحقاً')">
                                <i class="fas fa-coins me-2 text-warning"></i>
                                {{ __('app.currencies') }}
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="#" onclick="alert('سيتم إنشاؤها لاحقاً')">
                                <i class="fas fa-percentage me-2 text-success"></i>
                                {{ __('app.taxes') }}
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>

            <!-- Right Side Navigation -->
            <ul class="navbar-nav">
                <!-- Language Dropdown -->
                <li class="nav-item dropdown me-2">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#"
                        id="languageDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-globe me-1"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="languageDropdown">
                        <li>
                            <h6 class="dropdown-header">{{ __('app.switch_language') }}</h6>
                        </li>
                        <li>
                            <form method="POST" action="{{ route('language.switch') }}" class="d-inline">
                                @csrf
                                <input type="hidden" name="language" value="ar">
                                <button type="submit"
                                    class="dropdown-item {{ app()->getLocale() == 'ar' ? 'active' : '' }}">
                                    <i
                                        class="fas fa-check me-2 {{ app()->getLocale() == 'ar' ? '' : 'invisible' }}"></i>
                                    {{ __('app.arabic') }}
                                </button>
                            </form>
                        </li>
                        <li>
                            <form method="POST" action="{{ route('language.switch') }}" class="d-inline">
                                @csrf
                                <input type="hidden" name="language" value="en">
                                <button type="submit"
                                    class="dropdown-item {{ app()->getLocale() == 'en' ? 'active' : '' }}">
                                    <i
                                        class="fas fa-check me-2 {{ app()->getLocale() == 'en' ? '' : 'invisible' }}"></i>
                                    {{ __('app.english') }}
                                </button>
                            </form>
                        </li>
                    </ul>
                </li>


                <!-- User Dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown"
                        role="button" data-bs-toggle="dropdown" aria-expanded="false">

                        <span class="d-none d-lg-inline">
                            <div class="small">{{ Auth::user()->name }}</div>
                        </span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                        <li>
                            <a class="dropdown-item" href="#">
                                <i class="fas fa-user me-2"></i>
                                {{ __('app.profile') }}
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="#">
                                <i class="fas fa-cog me-2"></i>
                                {{ __('app.settings') }}
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('company.settings') }}">
                                <i class="fas fa-building me-2"></i>
                                {{ __('app.company_settings') }}
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="fas fa-sign-out-alt me-2"></i>
                                    {{ __('app.logout') }}
                                </button>
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
