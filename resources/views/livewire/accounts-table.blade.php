<div class="container-fluid px-4">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h2 class="h3 mb-1">
                        <i class="fas fa-sitemap me-2 text-primary"></i>
                        {{ __('app.chart_of_accounts') }}
                    </h2>
                    <p class="text-muted mb-0">{{ __('app.manage_chart_of_accounts') }}</p>
                </div>
                <div class="d-flex gap-2">
                    <button wire:click="openCreateModal" class="btn btn-success">
                        <i class="fas fa-plus me-1"></i>
                        {{ __('app.add_account') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card bg-primary text-white shadow">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">
                                {{ __('app.total_accounts') }}
                            </div>
                            <div class="h5 mb-0 font-weight-bold">
                                {{ number_format($statistics['total_accounts']) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-sitemap fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card bg-success text-white shadow">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">
                                {{ __('app.active_accounts') }}
                            </div>
                            <div class="h5 mb-0 font-weight-bold">
                                {{ number_format($statistics['active_accounts']) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card bg-info text-white shadow">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">
                                {{ __('app.main_accounts') }}
                            </div>
                            <div class="h5 mb-0 font-weight-bold">
                                {{ number_format($statistics['main_accounts']) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-layer-group fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card bg-warning text-white shadow">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">
                                {{ __('app.sub_accounts') }}
                            </div>
                            <div class="h5 mb-0 font-weight-bold">
                                {{ number_format($statistics['sub_accounts']) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-project-diagram fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body py-3">
            <div class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label small text-muted">{{ __('app.search') }}</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-search"></i>
                        </span>
                        <input type="text" class="form-control" wire:model.live="search"
                            placeholder="{{ __('app.search_accounts') }}">
                    </div>
                </div>
                <div class="col-md-2">
                    <label class="form-label small text-muted">{{ __('app.account_type') }}</label>
                    <select class="form-select" wire:model.live="accountType">
                        <option value="">{{ __('app.all') }}</option>
                        <option value="assets">{{ __('app.assets') }}</option>
                        <option value="liabilities">{{ __('app.liabilities') }}</option>
                        <option value="equity">{{ __('app.equity') }}</option>
                        <option value="revenue">{{ __('app.revenue') }}</option>
                        <option value="expenses">{{ __('app.expenses') }}</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small text-muted">{{ __('app.status') }}</label>
                    <select class="form-select" wire:model.live="isActive">
                        <option value="">{{ __('app.all') }}</option>
                        <option value="1">{{ __('app.active') }}</option>
                        <option value="0">{{ __('app.inactive') }}</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small text-muted">{{ __('app.level') }}</label>
                    <select class="form-select" wire:model.live="level">
                        <option value="">{{ __('app.all_levels') }}</option>
                        @for ($i = 1; $i <= 5; $i++)
                            <option value="{{ $i }}">{{ __('app.level') }} {{ $i }}</option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small text-muted">{{ __('app.branch') }}</label>
                    <select class="form-select" wire:model.live="branchFilter">
                        <option value="">{{ __('app.all_branches') }}</option>
                        @foreach ($branches as $branch)
                            <option value="{{ $branch['id'] }}">{{ $branch['name'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-1">
                    <label class="form-label small text-muted">&nbsp;</label>
                    <button type="button" class="btn btn-outline-secondary w-100" wire:click="resetFilters">
                        <i class="fas fa-undo"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Accounts Table -->
    <div class="card">
        <div class="card-header bg-white border-bottom">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="fas fa-table me-2 text-primary"></i>
                    {{ __('app.accounts_list') }}
                </h5>
                <!-- مفتاح التمييز البصري -->
                <div class="d-flex align-items-center gap-3">
                    <small class="text-muted">
                        <i class="fas fa-circle text-primary me-1"></i>
                        {{ __('app.regular_accounts') }}
                    </small>
                    <small class="text-danger">
                        <i class="fas fa-star me-1"></i>
                        {{ __('app.sub_accounts') }}
                    </small>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <!-- Table View -->
            @if ($accounts->count() > 0)
                <div class="table-responsive">
                    <table class="table table-vcenter">
                        <thead>
                            <tr>
                                <th class="text-center">{{ __('app.code') }}</th>
                                <th class="text-center">{{ __('app.account_name') }}</th>
                                <th class="text-center">{{ __('app.account_level_type') }}</th>
                                <th class="text-center">{{ __('app.account_nature') }}</th>
                                <th class="text-center">{{ __('app.parent_account') }}</th>
                                <th class="text-center">{{ __('app.level') }}</th>
                                <th class="text-center">{{ __('app.branch') }}</th>
                                <th class="text-center">{{ __('app.balance') }}</th>
                                <th class="text-center">{{ __('app.status') }}</th>
                                <th class="w-1 text-center">{{ __('app.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($accounts as $account)
                                <tr
                                    class="{{ $account->account_level_type === 'sub_account' ? 'sub-account-row' : '' }}">
                                    <td>
                                        <span
                                            class="fw-bold {{ $account->account_level_type === 'sub_account' ? 'text-danger' : 'text-primary' }}">
                                            {{ $account->code }}
                                            @if ($account->account_level_type === 'sub_account')
                                                <i class="fas fa-star sub-account-star ms-1"
                                                    style="font-size: 0.8em;"></i>
                                            @endif
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if ($account->level > 1)
                                                <span class="text-muted me-1">
                                                    {!! str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $account->level - 1) !!}
                                                    <i class="fas fa-long-arrow-alt-right text-secondary"></i>
                                                </span>
                                            @endif
                                            <span
                                                class="fw-medium {{ $account->account_level_type === 'sub_account' ? 'sub-account-name' : '' }}">{{ $account->name }}</span>
                                            @if ($account->account_level_type === 'sub_account')
                                                <i class="fas fa-star sub-account-star ms-1"
                                                    title="{{ __('app.sub_account') }}"></i>
                                            @endif
                                            @if ($account->has_children)
                                                <i class="fas fa-folder text-warning ms-1"
                                                    title="{{ __('app.has_children') }}"></i>
                                            @endif
                                        </div>
                                        @if ($account->name_en)
                                            <small class="text-muted d-block">{{ $account->name_en }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <span
                                            class="badge {{ $account->account_level_type === 'sub_account' ? 'bg-danger' : 'bg-info' }} text-white">
                                            {{ $account->account_level_type_name }}
                                        </span>
                                    </td>
                                    <td>
                                        @if ($account->account_nature)
                                            <span
                                                class="badge bg-{{ $account->account_nature === 'debit' ? 'warning' : ($account->account_nature === 'credit' ? 'primary' : 'secondary') }} text-white">
                                                {{ $account->account_nature_name }}
                                            </span>
                                        @else
                                            <span class="badge bg-light text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($account->parent)
                                            <span
                                                class="text-sm {{ $account->parent->account_level_type === 'sub_account' ? 'sub-account-name' : '' }}">
                                                {{ $account->parent->name }}
                                                @if ($account->parent->account_level_type === 'sub_account')
                                                    <i class="fas fa-star sub-account-star ms-1"
                                                        style="font-size: 0.7em;"></i>
                                                @endif
                                            </span>
                                        @else
                                            <span class="text-muted">{{ __('app.main_account') }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span
                                            class="badge {{ $account->account_level_type === 'sub_account' ? 'bg-danger text-white' : 'bg-light text-dark' }}">
                                            {{ $account->level }}
                                            @if ($account->account_level_type === 'sub_account')
                                                <i class="fas fa-star ms-1" style="font-size: 0.7em;"></i>
                                            @endif
                                        </span>
                                    </td>
                                    <td>
                                        @if ($account->branch)
                                            <span class="text-sm">{{ $account->branch->name }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @php
                                                $balanceData = format_account_balance(
                                                    $account->balance,
                                                    $account->account_nature ?? 'asset',
                                                    true,
                                                );
                                            @endphp
                                            <span class="fw-bold {{ $balanceData['class'] }}">
                                                {{ $balanceData['text'] }}
                                            </span>
                                            @if ($account->account_level_type === 'sub_account')
                                                <i class="fas fa-star sub-account-star ms-1"
                                                    style="font-size: 0.7em;"></i>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check form-switch d-flex justify-content-center">
                                            <input class="form-check-input" type="checkbox" role="switch"
                                                {{ $account->is_active ? 'checked' : '' }}
                                                {{ $account->has_children ? 'disabled' : '' }}
                                                wire:click="toggleStatus({{ $account->id }})"
                                                id="switch{{ $account->id }}">
                                            <label class="form-check-label" for="switch{{ $account->id }}"></label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-center gap-1">
                                            <button wire:click="openViewModal({{ $account->id }})"
                                                class="btn btn-sm p-1"
                                                style="border: none !important; background: none !important;"
                                                title="{{ __('app.view') }}">
                                                <i class="fas fa-eye text-info"></i>
                                            </button>
                                            <button wire:click="openEditModal({{ $account->id }})"
                                                class="btn btn-sm p-1"
                                                style="border: none !important; background: none !important;"
                                                title="{{ __('app.edit') }}">
                                                <i class="fas fa-edit text-primary"></i>
                                            </button>
                                            @if (!$account->has_children)
                                                <button onclick="confirmDeleteAccount({{ $account->id }})"
                                                    class="btn btn-sm p-1"
                                                    style="border: none !important; background: none !important;"
                                                    title="{{ __('app.delete') }}">
                                                    <i class="fas fa-trash text-danger"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if (method_exists($accounts, 'links'))
                    <div class="d-flex justify-content-center mt-3">
                        {{ $accounts->links() }}
                    </div>
                @endif
            @else
                <div class="text-center py-5">
                    <i class="fas fa-sitemap fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">{{ __('app.no_accounts_found') }}</h5>
                    <p class="text-muted">{{ __('app.add_first_account') }}</p>
                    <button wire:click="openCreateModal" class="btn btn-success">
                        <i class="fas fa-plus me-1"></i>
                        {{ __('app.add_account') }}
                    </button>
                </div>
            @endif
        </div>
    </div>

    <!-- Account Modal -->
    @if ($showModal)
        <div class="modal show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5);">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            @if ($modalMode === 'create')
                                <i class="fas fa-plus me-2"></i>
                                {{ __('app.add_account') }}
                            @elseif ($modalMode === 'edit')
                                <i class="fas fa-edit me-2"></i>
                                {{ __('app.edit_account') }}
                            @else
                                <i class="fas fa-eye me-2"></i>
                                {{ __('app.view_account') }}
                            @endif
                        </h5>
                        <button type="button" class="btn-close" wire:click="closeModal"></button>
                    </div>

                    <div class="modal-body">
                        <div class="row">
                            <!-- اسم الحساب -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">{{ __('app.account_name') }} *</label>
                                <input type="text" wire:model="name"
                                    class="form-control @error('name') is-invalid @enderror"
                                    {{ $modalMode === 'view' ? 'readonly' : '' }}>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- اسم الحساب بالإنجليزية -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">{{ __('app.account_name_en') }}</label>
                                <input type="text" wire:model="nameEn"
                                    class="form-control @error('nameEn') is-invalid @enderror"
                                    {{ $modalMode === 'view' ? 'readonly' : '' }}>
                                @error('nameEn')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <!-- الحساب الأب -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">{{ __('app.parent_account') }}</label>
                                <div class="position-relative">
                                    <input type="text" id="parentAccountSearch"
                                        class="form-control @error('parentId') is-invalid @enderror"
                                        placeholder="{{ __('app.search_parent_account') }}" autocomplete="off"
                                        {{ $modalMode === 'view' ? 'readonly' : '' }}>
                                    <div class="dropdown-menu w-100" id="parentAccountsList"
                                        style="max-height: 300px; overflow-y: auto;">
                                        <div class="dropdown-item" data-value=""
                                            data-name="{{ __('app.main_account') }}">
                                            <i class="fas fa-home text-primary me-2"></i>
                                            {{ __('app.main_account') }}
                                        </div>
                                        @foreach ($parentAccounts as $parent)
                                            @if ($parent['id'] != $accountId)
                                                <div class="dropdown-item" data-value="{{ $parent['id'] }}"
                                                    data-name="{{ $parent['code'] }} - {{ $parent['name'] }}">
                                                    @if ($parent['account_level_type'] === 'title')
                                                        <i class="fas fa-folder text-warning me-2"></i>
                                                    @else
                                                        <i class="fas fa-star text-danger me-2"></i>
                                                    @endif
                                                    <span
                                                        class="{{ $parent['account_level_type'] === 'title' ? 'text-primary fw-bold' : 'text-danger' }}">
                                                        {{ $parent['code'] }} - {{ $parent['name'] }}
                                                    </span>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                                <input type="hidden" wire:model="parentId" id="selectedParentId">
                                @error('parentId')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- الفرع -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">الفرع</label>
                                <select wire:model="branchId"
                                    class="form-select @error('branchId') is-invalid @enderror"
                                    {{ $modalMode === 'view' ? 'disabled' : '' }}>
                                    <option value="">اختر الفرع</option>
                                    @foreach ($branches as $branch)
                                        <option value="{{ $branch['id'] }}">{{ $branch['name'] }}</option>
                                    @endforeach
                                </select>
                                @error('branchId')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <!-- نوع مستوى الحساب -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">{{ __('app.account_level_type') }} *</label>
                                <select wire:model.live="accountLevelType"
                                    class="form-select @error('accountLevelType') is-invalid @enderror"
                                    {{ $modalMode === 'view' ? 'disabled' : '' }}>
                                    <option value="">{{ __('app.select_account_level_type') }}</option>
                                    @foreach ($accountLevelTypesOptions as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                                @error('accountLevelType')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- طبيعة الحساب - تظهر فقط للحسابات التشغيلية -->
                            @if ($accountLevelType === 'account')
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">{{ __('app.account_nature') }} *</label>
                                    <select wire:model.live="accountNature"
                                        class="form-select @error('accountNature') is-invalid @enderror"
                                        {{ $modalMode === 'view' ? 'disabled' : '' }}>
                                        <option value="">{{ __('app.select_account_nature') }}</option>
                                        @foreach ($accountNatureOptions as $key => $value)
                                            <option value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                    @error('accountNature')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            @endif
                        </div>

                        <!-- الوصف -->
                        <div class="mb-3">
                            <label class="form-label">{{ __('app.description') }}</label>
                            <textarea wire:model="description" class="form-control @error('description') is-invalid @enderror" rows="3"
                                {{ $modalMode === 'view' ? 'readonly' : '' }}></textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- الحالة -->
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" role="switch" wire:model="isActiveForm"
                                id="isActiveSwitch" {{ $modalMode === 'view' ? 'disabled' : '' }}>
                            <label class="form-check-label" for="isActiveSwitch">
                                {{ __('app.active') }}
                            </label>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="closeModal">
                            {{ __('app.close') }}
                        </button>
                        @if ($modalMode !== 'view')
                            <button type="button" class="btn btn-success" wire:click="save">
                                <i class="fas fa-save me-1"></i>
                                {{ __('app.save') }}
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

@script
    <script>
        // Status toggle notification
        Livewire.on('status-toggled', (event) => {
            const data = event[0];
            if (data.success) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'success',
                        title: '{{ __('app.success') }}',
                        text: data.message,
                        showConfirmButton: false,
                        timer: 2000
                    });
                }
            } else {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: '{{ __('app.error') }}',
                        text: data.message,
                        confirmButtonText: '{{ __('app.ok') }}'
                    });
                }
            }
        });

        // Account saved notification
        Livewire.on('account-saved', (event) => {
            const data = event[0];
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'success',
                    title: '{{ __('app.success') }}',
                    text: data.message,
                    showConfirmButton: false,
                    timer: 2000
                });
            }
        });

        // Account deleted notification
        Livewire.on('account-deleted', (event) => {
            const data = event[0];
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'success',
                    title: '{{ __('app.success') }}',
                    text: data.message,
                    showConfirmButton: false,
                    timer: 2000
                });
            }
        });

        // Error notification
        Livewire.on('error-occurred', (event) => {
            const data = event[0];
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'error',
                    title: '{{ __('app.error') }}',
                    text: data.message,
                    confirmButtonText: '{{ __('app.ok') }}'
                });
            }
        });

        // Search functionality for parent account dropdown
        function initializeParentAccountSearch() {
            // Don't initialize if modal is not shown
            if (!document.querySelector('.modal.show')) {
                console.log('Modal not visible, skipping search initialization');
                return;
            }

            const searchInput = document.getElementById('parentAccountSearch');
            const dropdownItems = document.querySelectorAll('#parentAccountsList .dropdown-item');
            const hiddenInput = document.getElementById('selectedParentId');
            const dropdownMenu = document.getElementById('parentAccountsList');

            if (searchInput && dropdownItems.length > 0 && dropdownMenu) {
                console.log('Initializing parent account search with', dropdownItems.length, 'items');

                // Clear previous event listeners by cloning
                const newSearchInput = searchInput.cloneNode(true);
                searchInput.parentNode.replaceChild(newSearchInput, searchInput);

                // Get updated reference
                const input = document.getElementById('parentAccountSearch');

                // Filter dropdown items based on search
                function handleSearch() {
                    const searchTerm = input.value.toLowerCase();
                    let hasVisibleItems = false;

                    dropdownItems.forEach(function(item) {
                        const text = item.textContent.toLowerCase();

                        if (text.includes(searchTerm)) {
                            item.style.display = '';
                            hasVisibleItems = true;
                        } else {
                            item.style.display = 'none';
                        }
                    });

                    // Show/hide dropdown
                    if (hasVisibleItems && (searchTerm.length > 0 || document.activeElement === input)) {
                        showDropdown();
                    } else {
                        hideDropdown();
                    }
                }

                function showDropdown() {
                    dropdownMenu.classList.add('show');
                    dropdownMenu.style.position = 'absolute';
                    dropdownMenu.style.top = '100%';
                    dropdownMenu.style.left = '0';
                    dropdownMenu.style.right = '0';
                    dropdownMenu.style.zIndex = '1050';
                }

                function hideDropdown() {
                    dropdownMenu.classList.remove('show');
                }

                // Event listeners
                input.addEventListener('input', handleSearch);
                input.addEventListener('focus', showDropdown);
                input.addEventListener('blur', function() {
                    setTimeout(hideDropdown, 150);
                });

                // Handle item selection
                dropdownItems.forEach(function(item) {
                    const newItem = item.cloneNode(true);
                    item.parentNode.replaceChild(newItem, item);

                    newItem.addEventListener('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation();

                        const value = this.getAttribute('data-value');
                        const name = this.getAttribute('data-name');

                        input.value = name;
                        if (hiddenInput) {
                            hiddenInput.value = value;
                        }

                        // Update Livewire
                        try {
                            window.Livewire.find('{{ $_instance->getId() }}').set('parentId', value);
                            console.log('Selected parent account:', value, name);
                        } catch (error) {
                            console.error('Error updating Livewire:', error);
                        }

                        hideDropdown();
                    });
                });

                // Close dropdown when clicking outside
                document.addEventListener('click', function(e) {
                    if (!input.contains(e.target) && !dropdownMenu.contains(e.target)) {
                        hideDropdown();
                    }
                });

                console.log('Parent account search initialized successfully');
            } else {
                console.log('Parent account search elements not found:', {
                    searchInput: !!searchInput,
                    dropdownItems: dropdownItems.length,
                    dropdownMenu: !!dropdownMenu
                });
            }
        }
        // Don't initialize on page load, only when needed
        // document.addEventListener('DOMContentLoaded', function() {
        //     initializeParentAccountSearch();
        // });

        // Re-initialize search when modal opens
        Livewire.on('modal-opened', function() {
            console.log('Modal opened event received');
            setTimeout(initializeParentAccountSearch, 800);
        });

        // Listen for modal show events using MutationObserver
        document.addEventListener('DOMContentLoaded', function() {
            const observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    if (mutation.type === 'childList') {
                        const modal = document.querySelector('.modal.show');
                        if (modal && modal.querySelector('#parentAccountSearch')) {
                            console.log('Modal detected, initializing search...');
                            setTimeout(initializeParentAccountSearch, 500);
                        }
                    }
                });
            });

            observer.observe(document.body, {
                childList: true,
                subtree: true
            });
        });
    </script>
@endscript

@once
    @push('scripts')
        <!-- Load jQuery for dropdown search functionality -->
        <script src="https://code.jquery.com/jquery-3.7.1.min.js" crossorigin="anonymous"></script>
    @endpush
@endonce
@push('styles')
    <style>
        .sub-account-star {
            color: #dc3545 !important;
            text-shadow: 0 0 3px rgba(220, 53, 69, 0.5);
        }

        .sub-account-name {
            color: #dc3545 !important;
            font-weight: 600;
        }

        /* تمييز صفوف الحسابات الفرعية */
        .sub-account-row {
            background: linear-gradient(90deg, rgba(220, 53, 69, 0.03) 0%, rgba(255, 255, 255, 0) 100%);
            border-left: 3px solid #dc3545;
        }

        .sub-account-row:hover {
            background: linear-gradient(90deg, rgba(220, 53, 69, 0.08) 0%, rgba(255, 255, 255, 0.5) 100%);
        }

        /* Search dropdown styles */
        .dropdown-menu {
            max-height: 300px;
            overflow-y: auto;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            border: 1px solid rgba(0, 0, 0, 0.15);
        }

        .dropdown-item {
            padding: 8px 16px;
            font-size: 14px;
        }

        .dropdown-item:hover,
        .dropdown-item:focus {
            background-color: #f8f9fa;
            color: #1e2125;
        }

        .dropdown-item i {
            width: 20px;
            text-align: center;
        }
    </style>
@endpush

<script>
    // Delete confirmation function - Global function
    function confirmDeleteAccount(accountId) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: '{{ __('app.are_you_sure') }}',
                text: '{{ __('app.confirm_delete_account') }}',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '{{ __('app.delete') }}',
                cancelButtonText: '{{ __('app.cancel') }}'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.Livewire.find('{{ $_instance->getId() }}').delete(accountId);
                }
            });
        } else {
            if (confirm('{{ __('app.confirm_delete_account') }}')) {
                window.Livewire.find('{{ $_instance->getId() }}').delete(accountId);
            }
        }
    }
</script>
