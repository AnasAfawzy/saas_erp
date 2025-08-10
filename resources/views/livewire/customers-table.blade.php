<div>
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h2 class="h3 mb-1">
                        <i class="fas fa-users me-2 text-primary"></i>
                        {{ __('app.customers') }}
                    </h2>
                    <p class="text-muted mb-0">{{ __('app.manage_customers_description') }}</p>
                </div>
                <div>
                    <button class="btn btn-primary" wire:click="openAddModal">
                        <i class="fas fa-plus me-2"></i>
                        {{ __('app.add_customer') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-2">
            <div class="card bg-primary text-white">
                <div class="card-body py-3">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="card-subtitle mb-0 opacity-75 small">{{ __('app.total_customers') }}</h6>
                            <h4 class="card-title mb-0">{{ $stats['total'] ?? 0 }}</h4>
                        </div>
                        <div class="ms-2">
                            <i class="fas fa-users fa-lg opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card bg-success text-white">
                <div class="card-body py-3">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="card-subtitle mb-0 opacity-75 small">{{ __('app.active_customers') }}</h6>
                            <h4 class="card-title mb-0">{{ $stats['active'] ?? 0 }}</h4>
                        </div>
                        <div class="ms-2">
                            <i class="fas fa-check-circle fa-lg opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card bg-warning text-white">
                <div class="card-body py-3">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="card-subtitle mb-0 opacity-75 small">{{ __('app.inactive_customers') }}</h6>
                            <h4 class="card-title mb-0">{{ ($stats['total'] ?? 0) - ($stats['active'] ?? 0) }}</h4>
                        </div>
                        <div class="ms-2">
                            <i class="fas fa-times-circle fa-lg opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card bg-info text-white">
                <div class="card-body py-3">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="card-subtitle mb-0 opacity-75 small">{{ __('app.individual_customers') }}</h6>
                            <h4 class="card-title mb-0">{{ $stats['individual'] ?? 0 }}</h4>
                        </div>
                        <div class="ms-2">
                            <i class="fas fa-user fa-lg opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card bg-secondary text-white">
                <div class="card-body py-3">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="card-subtitle mb-0 opacity-75 small">{{ __('app.company_customers') }}</h6>
                            <h4 class="card-title mb-0">{{ $stats['company'] ?? 0 }}</h4>
                        </div>
                        <div class="ms-2">
                            <i class="fas fa-building fa-lg opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card bg-danger text-white">
                <div class="card-body py-3">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="card-subtitle mb-0 opacity-75 small">{{ __('app.customers_with_balance') }}</h6>
                            <h4 class="card-title mb-0">{{ $stats['with_balance'] ?? 0 }}</h4>
                        </div>
                        <div class="ms-2">
                            <i class="fas fa-dollar-sign fa-lg opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label small text-muted">{{ __('app.search') }}</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-search"></i>
                        </span>
                        <input type="text" class="form-control" wire:model.live="search"
                            placeholder="{{ __('app.search_customers_placeholder') }}">
                    </div>
                </div>
                <div class="col-md-2">
                    <label class="form-label small text-muted">{{ __('app.customer_type') }}</label>
                    <select class="form-select" wire:model.live="customerTypeFilter">
                        <option value="">{{ __('app.all_types') }}</option>
                        <option value="individual">{{ __('app.individual') }}</option>
                        <option value="company">{{ __('app.company') }}</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small text-muted">{{ __('app.status') }}</label>
                    <select class="form-select" wire:model.live="statusFilter">
                        <option value="">{{ __('app.all_statuses') }}</option>
                        <option value="1">{{ __('app.active') }}</option>
                        <option value="0">{{ __('app.inactive') }}</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small text-muted">{{ __('app.city') }}</label>
                    <input type="text" class="form-control" wire:model.live="cityFilter"
                        placeholder="{{ __('app.all') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label small text-muted">{{ __('app.payment_terms') }}</label>
                    <select class="form-select" wire:model.live="paymentTermsFilter">
                        <option value="">{{ __('app.all_terms') }}</option>
                        <option value="cash">{{ __('app.cash') }}</option>
                        <option value="credit">{{ __('app.credit') }}</option>
                        <option value="installment">{{ __('app.installment') }}</option>
                    </select>
                </div>
                <div class="col-md-1">
                    <label class="form-label small text-muted">&nbsp;</label>
                    <button type="button" class="btn btn-outline-secondary w-100" wire:click="clearSearch">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-md-4">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" wire:model.live="withBalanceFilter"
                            id="withBalanceFilter" {{ $withBalanceFilter ? 'checked' : '' }}>
                        <label class="form-check-label small text-muted" for="withBalanceFilter">
                            {{ __('app.customers_with_balance_only') }}
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Customers Table -->
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-vcenter">
                    <thead>
                        <tr>
                            <th class="text-center">{{ __('app.customer_code') }}</th>
                            <th class="text-center">{{ __('app.customer_name') }}</th>
                            <th class="text-center">{{ __('app.customer_type') }}</th>
                            <th class="text-center">{{ __('app.phone') }}</th>
                            <th class="text-center">{{ __('app.city') }}</th>
                            <th class="text-center">{{ __('app.current_balance') }}</th>
                            <th class="text-center">{{ __('app.payment_terms') }}</th>
                            <th class="text-center">{{ __('app.status') }}</th>
                            <th class="w-1 text-center">{{ __('app.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($customers as $customer)
                            <tr>
                                <td class="text-center">
                                    <span class="text-secondary">{{ $customer->code }}</span>
                                </td>
                                <td class="text-center">
                                    <div class="font-weight-medium">{{ $customer->name }}</div>
                                    @if ($customer->email)
                                        <div class="text-secondary small">{{ $customer->email }}</div>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <span
                                        class="badge bg-{{ $customer->customer_type === 'individual' ? 'orange' : 'purple' }}-lt">
                                        {{ $customer->customer_type === 'individual' ? __('app.individual') : __('app.company') }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="text-secondary">{{ $customer->phone }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="text-secondary">{{ $customer->city ?? '-' }}</span>
                                </td>
                                <td class="text-center">
                                    @if ($customer->current_balance > 0)
                                        <span class="text-red">{{ number_format($customer->current_balance, 2) }}
                                            {{ __('app.currency') }}</span>
                                    @elseif($customer->current_balance < 0)
                                        <span
                                            class="text-green">{{ number_format(abs($customer->current_balance), 2) }}
                                            {{ __('app.currency') }}</span>
                                    @else
                                        <span class="text-muted">0.00 {{ __('app.currency') }}</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if ($customer->payment_terms === 'cash')
                                        <span class="badge bg-success d-inline-flex align-items-center">
                                            <i class="fas fa-money-bill-wave me-1" style="font-size: 0.75rem;"></i>
                                            {{ __('app.cash') }}
                                        </span>
                                    @elseif($customer->payment_terms === 'credit')
                                        <span class="badge bg-info d-inline-flex align-items-center">
                                            <i class="fas fa-credit-card me-1" style="font-size: 0.75rem;"></i>
                                            {{ __('app.credit') }}
                                            <span class="badge bg-white text-info ms-1 px-1"
                                                style="font-size: 0.7rem;">
                                                {{ $customer->payment_days ?? 0 }} {{ __('app.days') }}
                                            </span>
                                        </span>
                                    @elseif($customer->payment_terms === 'installment')
                                        <span class="badge bg-warning d-inline-flex align-items-center">
                                            <i class="fas fa-calendar-alt me-1" style="font-size: 0.75rem;"></i>
                                            {{ __('app.installment') }}
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">-</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="form-check form-switch mb-0 d-flex justify-content-center">
                                        <input class="form-check-input" type="checkbox"
                                            {{ $customer->is_active ? 'checked' : '' }}
                                            wire:click="toggleStatus({{ $customer->id }})">
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-sm btn-link text-primary"
                                            wire:click="openEditModal({{ $customer->id }})"
                                            title="{{ __('app.edit') }}">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-link text-danger"
                                            onclick="confirmDeleteCustomer({{ $customer->id }})"
                                            title="{{ __('app.delete') }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-5">
                                    <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">{{ __('app.no_customers_found') }}</h5>
                                    <p class="text-muted mb-0">{{ __('app.try_adjusting_search_filters') }}</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if ($customers->hasPages())
            <div class="card-footer">
                {{ $customers->links() }}
            </div>
        @endif
    </div>

    <!-- Customer Modal -->
    @if ($showModal)
        <div class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5)">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            @if ($editingCustomerId)
                                <i class="fas fa-edit me-2"></i>
                                {{ __('app.edit_customer') }}
                            @else
                                <i class="fas fa-plus me-2"></i>
                                {{ __('app.add_customer') }}
                            @endif
                        </h5>
                        <button type="button" class="btn-close" wire:click="closeModal"></button>
                    </div>
                    <div class="modal-body">
                        <form wire:submit.prevent="save">
                            <!-- Tab Navigation -->
                            <ul class="nav nav-tabs form-tabs mb-4" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link active" data-bs-toggle="tab" href="#basic-info-tab"
                                        role="tab">
                                        {{ __('app.basic_info') }}
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" data-bs-toggle="tab" href="#address-info-tab"
                                        role="tab">
                                        {{ __('app.address_info') }}
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" data-bs-toggle="tab" href="#financial-info-tab"
                                        role="tab">
                                        {{ __('app.financial_info') }}
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" data-bs-toggle="tab" href="#contact-info-tab"
                                        role="tab">
                                        {{ __('app.contact_info') }}
                                    </a>
                                </li>
                            </ul>

                            <!-- Tab Content -->
                            <div class="tab-content">
                                <!-- Basic Info Tab -->
                                <div class="tab-pane fade show active" id="basic-info-tab" role="tabpanel">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">{{ __('app.customer_code') }} *</label>
                                                <input type="text"
                                                    class="form-control @error('code') is-invalid @enderror"
                                                    wire:model="code" readonly>
                                                @error('code')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">{{ __('app.customer_name') }} *</label>
                                                <input type="text"
                                                    class="form-control @error('name') is-invalid @enderror"
                                                    wire:model="name" required>
                                                @error('name')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label">{{ __('app.customer_type') }} *</label>
                                                <select
                                                    class="form-select @error('customer_type') is-invalid @enderror"
                                                    wire:model="customer_type" required>
                                                    <option value="individual">{{ __('app.individual') }}</option>
                                                    <option value="company">{{ __('app.company') }}</option>
                                                </select>
                                                @error('customer_type')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label">{{ __('app.phone') }} *</label>
                                                <input type="text"
                                                    class="form-control @error('phone') is-invalid @enderror"
                                                    wire:model="phone" required>
                                                @error('phone')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label">{{ __('app.email') }}</label>
                                                <input type="email"
                                                    class="form-control @error('email') is-invalid @enderror"
                                                    wire:model="email">
                                                @error('email')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">{{ __('app.national_id') }}</label>
                                                <input type="text"
                                                    class="form-control @error('national_id') is-invalid @enderror"
                                                    wire:model="national_id">
                                                @error('national_id')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">{{ __('app.tax_number') }}</label>
                                                <input type="text"
                                                    class="form-control @error('tax_number') is-invalid @enderror"
                                                    wire:model="tax_number">
                                                @error('tax_number')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" id="is_active"
                                                        wire:model="is_active">
                                                    <label class="form-check-label" for="is_active">
                                                        {{ __('app.active') }}
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Address Info Tab -->
                                <div class="tab-pane fade" id="address-info-tab" role="tabpanel">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label class="form-label">{{ __('app.address') }}</label>
                                                <textarea class="form-control @error('address') is-invalid @enderror" wire:model="address" rows="3"></textarea>
                                                @error('address')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">{{ __('app.city') }}</label>
                                                <input type="text"
                                                    class="form-control @error('city') is-invalid @enderror"
                                                    wire:model="city">
                                                @error('city')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">{{ __('app.state') }}</label>
                                                <input type="text"
                                                    class="form-control @error('state') is-invalid @enderror"
                                                    wire:model="state">
                                                @error('state')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">{{ __('app.postal_code') }}</label>
                                                <input type="text"
                                                    class="form-control @error('postal_code') is-invalid @enderror"
                                                    wire:model="postal_code">
                                                @error('postal_code')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">{{ __('app.country') }}</label>
                                                <input type="text"
                                                    class="form-control @error('country') is-invalid @enderror"
                                                    wire:model="country">
                                                @error('country')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Financial Info Tab -->
                                <div class="tab-pane fade" id="financial-info-tab" role="tabpanel">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label">{{ __('app.credit_limit') }}</label>
                                                <input type="number" step="0.01"
                                                    class="form-control @error('credit_limit') is-invalid @enderror"
                                                    wire:model="credit_limit" min="0" placeholder="0.00">
                                                @error('credit_limit')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label">{{ __('app.payment_terms') }}</label>
                                                <select
                                                    class="form-select @error('payment_terms') is-invalid @enderror"
                                                    wire:model="payment_terms">
                                                    <option value="cash">{{ __('app.cash') }}</option>
                                                    <option value="credit">{{ __('app.credit') }}</option>
                                                    <option value="installment">{{ __('app.installment') }}</option>
                                                </select>
                                                @error('payment_terms')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label">{{ __('app.payment_days') }}</label>
                                                <input type="number"
                                                    class="form-control @error('payment_days') is-invalid @enderror"
                                                    wire:model="payment_days" min="0" max="365"
                                                    placeholder="0">
                                                @error('payment_days')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">{{ __('app.discount_percentage') }}</label>
                                                <input type="number" step="0.01"
                                                    class="form-control @error('discount_percentage') is-invalid @enderror"
                                                    wire:model="discount_percentage" min="0" max="100"
                                                    placeholder="0.00">
                                                @error('discount_percentage')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Contact Info Tab -->
                                <div class="tab-pane fade" id="contact-info-tab" role="tabpanel">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">{{ __('app.contact_person') }}</label>
                                                <input type="text"
                                                    class="form-control @error('contact_person') is-invalid @enderror"
                                                    wire:model="contact_person">
                                                @error('contact_person')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">{{ __('app.website') }}</label>
                                                <input type="url"
                                                    class="form-control @error('website') is-invalid @enderror"
                                                    wire:model="website">
                                                @error('website')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">{{ __('app.whatsapp') }}</label>
                                                <input type="text"
                                                    class="form-control @error('whatsapp') is-invalid @enderror"
                                                    wire:model="whatsapp">
                                                @error('whatsapp')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">{{ __('app.notes') }}</label>
                                                <textarea class="form-control @error('notes') is-invalid @enderror" wire:model="notes" rows="3"></textarea>
                                                @error('notes')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end mt-4">
                                <button type="button" class="btn btn-secondary me-2" wire:click="closeModal">
                                    {{ __('app.cancel') }}
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>
                                    {{ __('app.save') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @push('styles')
        <style>
            .form-tabs .nav-link {
                border: none;
                border-bottom: 2px solid transparent;
                color: #666;
                padding: 0.75rem 1rem;
                transition: all 0.3s ease;
            }

            .form-tabs .nav-link:hover {
                border-bottom: 2px solid #6e7687;
                color: #6e7687;
                background: none;
            }

            .form-tabs .nav-link.active {
                border-bottom: 2px solid #467fcf;
                color: #467fcf;
                background: none;
                font-weight: 600;
            }

            .form-tabs .nav-link:focus {
                box-shadow: none;
                outline: none;
            }

            /* تحسين مظهر الـ checkbox */
            .form-check-input:checked {
                background-color: #467fcf;
                border-color: #467fcf;
            }

            .form-check-input:focus {
                border-color: #6e7687;
                box-shadow: 0 0 0 0.25rem rgba(70, 127, 207, 0.25);
            }

            .form-check-input:checked:focus {
                border-color: #467fcf;
                box-shadow: 0 0 0 0.25rem rgba(70, 127, 207, 0.25);
            }

            /* تحسين مظهر السويتش لتحديث الحالة - مثل الأصناف تماماً */
            .form-check-input:checked {
                background-color: #467fcf !important;
                border-color: #467fcf !important;
            }

            .form-check-input:not(:checked) {
                background-color: #e9ecef;
                border-color: #dee2e6;
            }

            .form-check-input:focus {
                border-color: #6e7687;
                box-shadow: 0 0 0 0.25rem rgba(70, 127, 207, 0.25);
            }

            .form-switch .form-check-input {
                background-color: #e9ecef;
                border-color: #dee2e6;
                transition: all 0.3s ease;
            }

            .form-switch .form-check-input:checked {
                background-color: #467fcf !important;
                border-color: #467fcf !important;
            }

            /* تحسين شارات شروط الدفع */
            .badge {
                font-size: 0.75rem;
                font-weight: 500;
                padding: 0.375em 0.75em;
            }

            .badge i {
                opacity: 0.8;
            }

            /* تحسين مظهر الأزرار */
            .btn-group .btn {
                transition: all 0.2s ease;
            }

            .btn-group .btn:hover {
                transform: scale(1.1);
            }
        </style>
    @endpush
</div>

@push('scripts')
    <script>
        document.addEventListener('livewire:init', function() {
            // Status toggle notifications
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

            // Customer saved notification
            Livewire.on('customer-saved', (event) => {
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

            // Customer deleted notification
            Livewire.on('customer-deleted', (event) => {
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

            // Reinitialize tabs when modal opens
            Livewire.on('modal-opened', function() {
                setTimeout(function() {
                    // Reset tab state when modal opens
                    const firstTab = document.querySelector('.nav-tabs .nav-link:first-child');
                    const firstPane = document.querySelector('.tab-pane:first-child');

                    if (firstTab && firstPane) {
                        // Remove all active states
                        document.querySelectorAll('.nav-tabs .nav-link').forEach(tab =>
                            tab.classList.remove('active'));
                        document.querySelectorAll('.tab-pane').forEach(pane =>
                            pane.classList.remove('show', 'active'));

                        // Set first tab as active
                        firstTab.classList.add('active');
                        firstPane.classList.add('show', 'active');
                    }
                }, 100);
            });
        });

        // Delete confirmation function
        function confirmDeleteCustomer(customerId) {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: '{{ __('app.are_you_sure') }}',
                    text: '{{ __('app.confirm_delete_customer') }}',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: '{{ __('app.delete') }}',
                    cancelButtonText: '{{ __('app.cancel') }}'
                }).then((result) => {
                    if (result.isConfirmed) {
                        @this.delete(customerId);
                    }
                });
            }
        }

        // Initialize Bootstrap tabs
        document.addEventListener('DOMContentLoaded', function() {
            // Enable all tab triggers
            const tabLinks = document.querySelectorAll('[data-bs-toggle="tab"]');
            tabLinks.forEach(function(tabLink) {
                tabLink.addEventListener('click', function(e) {
                    e.preventDefault();

                    // Remove active class from all tabs
                    const allTabs = document.querySelectorAll('.nav-tabs .nav-link');
                    const allPanes = document.querySelectorAll('.tab-pane');

                    allTabs.forEach(tab => tab.classList.remove('active'));
                    allPanes.forEach(pane => {
                        pane.classList.remove('show', 'active');
                    });

                    // Add active class to clicked tab
                    this.classList.add('active');

                    // Show corresponding tab pane
                    const targetId = this.getAttribute('href').substring(1);
                    const targetPane = document.getElementById(targetId);
                    if (targetPane) {
                        targetPane.classList.add('show', 'active');
                    }
                });
            });
        });
    </script>
@endpush
