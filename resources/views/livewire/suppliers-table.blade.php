<div>
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h2 class="h3 mb-1">
                        <i class="fas fa-truck me-2 text-primary"></i>
                        {{ __('app.suppliers') }}
                    </h2>
                    <p class="text-muted mb-0">{{ __('app.manage_suppliers_description') }}</p>
                </div>
                <div>
                    <button class="btn btn-primary" wire:click="openAddModal">
                        <i class="fas fa-plus me-2"></i>
                        {{ __('app.add_supplier') }}
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
                            <h6 class="card-subtitle mb-0 opacity-75 small">{{ __('app.total_suppliers') }}</h6>
                            <h4 class="card-title mb-0">{{ $stats['total'] ?? 0 }}</h4>
                        </div>
                        <div class="ms-2">
                            <i class="fas fa-truck fa-lg opacity-75"></i>
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
                            <h6 class="card-subtitle mb-0 opacity-75 small">{{ __('app.active_suppliers') }}</h6>
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
                            <h6 class="card-subtitle mb-0 opacity-75 small">{{ __('app.inactive_suppliers') }}</h6>
                            <h4 class="card-title mb-0">{{ $stats['inactive'] ?? 0 }}</h4>
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
                            <h6 class="card-subtitle mb-0 opacity-75 small">{{ __('app.individual_suppliers') }}</h6>
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
                            <h6 class="card-subtitle mb-0 opacity-75 small">{{ __('app.company_suppliers') }}</h6>
                            <h4 class="card-title mb-0">{{ $stats['companies'] ?? 0 }}</h4>
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
                            <h6 class="card-subtitle mb-0 opacity-75 small">{{ __('app.suppliers_with_balance') }}</h6>
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
                            placeholder="{{ __('app.search_suppliers_placeholder') }}">
                    </div>
                </div>
                <div class="col-md-2">
                    <label class="form-label small text-muted">{{ __('app.supplier_type') }}</label>
                    <select class="form-select" wire:model.live="supplierTypeFilter">
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
                            {{ __('app.suppliers_with_balance_only') }}
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Suppliers Table -->
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-vcenter">
                    <thead>
                        <tr>
                            <th class="text-center">{{ __('app.supplier_code') }}</th>
                            <th class="text-center">{{ __('app.supplier_name') }}</th>
                            <th class="text-center">{{ __('app.supplier_type') }}</th>
                            <th class="text-center">{{ __('app.phone') }}</th>
                            <th class="text-center">{{ __('app.city') }}</th>
                            <th class="text-center">{{ __('app.current_balance') }}</th>
                            <th class="text-center">{{ __('app.payment_terms') }}</th>
                            <th class="text-center">{{ __('app.status') }}</th>
                            <th class="w-1 text-center">{{ __('app.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($suppliers as $supplier)
                            <tr>
                                <td class="text-center">
                                    <span class="text-secondary">{{ $supplier->code }}</span>
                                </td>
                                <td class="text-center">
                                    <div class="font-weight-medium">{{ $supplier->name }}</div>
                                    @if ($supplier->contact_person)
                                        <div class="text-secondary small">{{ $supplier->contact_person }}</div>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <span
                                        class="badge {{ $supplier->supplier_type === 'company' ? 'bg-secondary' : 'bg-info' }}">
                                        <i
                                            class="fas {{ $supplier->supplier_type === 'company' ? 'fa-building' : 'fa-user' }} me-1"></i>
                                        {{ __('app.' . $supplier->supplier_type) }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="text-secondary">{{ $supplier->phone }}</span>
                                    @if ($supplier->whatsapp)
                                        <br><a href="https://wa.me/{{ $supplier->whatsapp }}" target="_blank"
                                            class="text-success text-decoration-none">
                                            <i class="fab fa-whatsapp"></i> WhatsApp
                                        </a>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <span class="text-secondary">{{ $supplier->city ?? '-' }}</span>
                                </td>
                                <td class="text-center">
                                    @if ($supplier->current_balance > 0)
                                        <span class="badge bg-success">
                                            <i class="fas fa-arrow-up me-1"></i>
                                            {{ number_format($supplier->current_balance, 2) }}
                                            {{ __('app.currency') }}
                                        </span>
                                    @elseif($supplier->current_balance < 0)
                                        <span class="badge bg-danger">
                                            <i class="fas fa-arrow-down me-1"></i>
                                            {{ number_format(abs($supplier->current_balance), 2) }}
                                            {{ __('app.currency') }}
                                        </span>
                                    @else
                                        <span class="text-muted">0.00 {{ __('app.currency') }}</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if ($supplier->payment_terms === 'cash')
                                        <span class="badge bg-success">
                                            <i class="fas fa-money-bill-wave me-1"></i>
                                            {{ __('app.cash') }}
                                        </span>
                                    @elseif($supplier->payment_terms === 'credit')
                                        <span class="badge bg-warning">
                                            <i class="fas fa-credit-card me-1"></i>
                                            {{ __('app.credit') }}
                                            @if ($supplier->payment_days > 0)
                                                ({{ $supplier->payment_days }} {{ __('app.days') }})
                                            @endif
                                        </span>
                                    @elseif($supplier->payment_terms === 'installment')
                                        <span class="badge bg-info">
                                            <i class="fas fa-calendar-alt me-1"></i>
                                            {{ __('app.installment') }}
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">-</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="form-check form-switch mb-0 d-flex justify-content-center">
                                        <input class="form-check-input" type="checkbox" role="switch"
                                            {{ $supplier->is_active ? 'checked' : '' }}
                                            wire:click="toggleStatus({{ $supplier->id }})">
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-sm btn-link text-primary"
                                            wire:click="openEditModal({{ $supplier->id }})"
                                            title="{{ __('app.edit') }}">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-link text-danger"
                                            onclick="confirmDeleteSupplier({{ $supplier->id }})"
                                            title="{{ __('app.delete') }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-5">
                                    <i class="fas fa-truck fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">{{ __('app.no_suppliers_found') }}</h5>
                                    <p class="text-muted mb-0">{{ __('app.try_adjusting_search_filters') }}</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if ($suppliers->hasPages())
            <div class="card-footer">
                {{ $suppliers->links() }}
            </div>
        @endif
    </div>

    <!-- Supplier Modal -->
    @if ($showModal)
        <div class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5)">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            @if ($editingSuplierId)
                                <i class="fas fa-edit me-2"></i>
                                {{ __('app.edit_supplier') }}
                            @else
                                <i class="fas fa-plus me-2"></i>
                                {{ __('app.add_supplier') }}
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
                                        role="tab" aria-controls="basic-info-tab" aria-selected="true">
                                        <i class="fas fa-info-circle me-1"></i>
                                        {{ __('app.basic_info') }}
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" data-bs-toggle="tab" href="#address-info-tab" role="tab"
                                        aria-controls="address-info-tab" aria-selected="false">
                                        <i class="fas fa-map-marker-alt me-1"></i>
                                        {{ __('app.address_info') }}
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" data-bs-toggle="tab" href="#financial-info-tab"
                                        role="tab" aria-controls="financial-info-tab" aria-selected="false">
                                        <i class="fas fa-money-bill-alt me-1"></i>
                                        {{ __('app.financial_info') }}
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" data-bs-toggle="tab" href="#contact-info-tab" role="tab"
                                        aria-controls="contact-info-tab" aria-selected="false">
                                        <i class="fas fa-address-book me-1"></i>
                                        {{ __('app.additional_info') }}
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
                                                <label
                                                    class="form-label required">{{ __('app.supplier_name') }}</label>
                                                <input type="text"
                                                    class="form-control @error('name') is-invalid @enderror"
                                                    wire:model="name"
                                                    placeholder="{{ __('app.enter_supplier_name') }}">
                                                @error('name')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">{{ __('app.supplier_code') }}</label>
                                                <input type="text"
                                                    class="form-control @error('code') is-invalid @enderror"
                                                    wire:model="code"
                                                    placeholder="{{ __('app.enter_supplier_code') }}">
                                                @error('code')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label
                                                    class="form-label required">{{ __('app.supplier_type') }}</label>
                                                <select
                                                    class="form-select @error('supplier_type') is-invalid @enderror"
                                                    wire:model="supplier_type">
                                                    <option value="individual">{{ __('app.individual') }}</option>
                                                    <option value="company">{{ __('app.company') }}</option>
                                                </select>
                                                @error('supplier_type')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label required">{{ __('app.phone') }}</label>
                                                <input type="text"
                                                    class="form-control @error('phone') is-invalid @enderror"
                                                    wire:model="phone" placeholder="{{ __('app.enter_phone') }}">
                                                @error('phone')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">{{ __('app.email') }}</label>
                                                <input type="email"
                                                    class="form-control @error('email') is-invalid @enderror"
                                                    wire:model="email" placeholder="{{ __('app.enter_email') }}">
                                                @error('email')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">{{ __('app.whatsapp') }}</label>
                                                <input type="text"
                                                    class="form-control @error('whatsapp') is-invalid @enderror"
                                                    wire:model="whatsapp"
                                                    placeholder="{{ __('app.enter_whatsapp') }}">
                                                @error('whatsapp')
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
                                                    wire:model="national_id"
                                                    placeholder="{{ __('app.enter_national_id') }}">
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
                                                    wire:model="tax_number"
                                                    placeholder="{{ __('app.enter_tax_number') }}">
                                                @error('tax_number')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox"
                                                        wire:model="is_active" id="is_active">
                                                    <label class="form-check-label" for="is_active">
                                                        {{ __('app.active_supplier') }}
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Address Info Tab -->
                                <div class="tab-pane fade" id="address-info-tab" role="tabpanel">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="mb-3">
                                                <label class="form-label">{{ __('app.address') }}</label>
                                                <textarea class="form-control @error('address') is-invalid @enderror" rows="3" wire:model="address"
                                                    placeholder="{{ __('app.enter_address') }}"></textarea>
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
                                                    wire:model="city" placeholder="{{ __('app.enter_city') }}">
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
                                                    wire:model="state" placeholder="{{ __('app.enter_state') }}">
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
                                                    wire:model="postal_code"
                                                    placeholder="{{ __('app.enter_postal_code') }}">
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
                                                    wire:model="country"
                                                    placeholder="{{ __('app.enter_country') }}">
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
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">{{ __('app.credit_limit') }}</label>
                                                <div class="input-group">
                                                    <input type="number"
                                                        class="form-control @error('credit_limit') is-invalid @enderror"
                                                        wire:model="credit_limit" step="0.01"
                                                        placeholder="{{ __('app.enter_credit_limit') }}">
                                                    <span class="input-group-text">{{ __('app.currency') }}</span>
                                                </div>
                                                @error('credit_limit')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">{{ __('app.current_balance') }}</label>
                                                <div class="input-group">
                                                    <input type="number"
                                                        class="form-control @error('current_balance') is-invalid @enderror"
                                                        wire:model="current_balance" step="0.01"
                                                        placeholder="{{ __('app.enter_current_balance') }}">
                                                    <span class="input-group-text">{{ __('app.currency') }}</span>
                                                </div>
                                                @error('current_balance')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
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
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">{{ __('app.payment_days') }}</label>
                                                <div class="input-group">
                                                    <input type="number"
                                                        class="form-control @error('payment_days') is-invalid @enderror"
                                                        wire:model="payment_days" min="0" max="365"
                                                        placeholder="{{ __('app.enter_payment_days') }}">
                                                    <span class="input-group-text">{{ __('app.days') }}</span>
                                                </div>
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
                                                <div class="input-group">
                                                    <input type="number"
                                                        class="form-control @error('discount_percentage') is-invalid @enderror"
                                                        wire:model="discount_percentage" step="0.01"
                                                        min="0" max="100"
                                                        placeholder="{{ __('app.enter_discount_percentage') }}">
                                                    <span class="input-group-text">%</span>
                                                </div>
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
                                                    wire:model="contact_person"
                                                    placeholder="{{ __('app.enter_contact_person') }}">
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
                                                    wire:model="website"
                                                    placeholder="{{ __('app.enter_website') }}">
                                                @error('website')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Rating Section -->
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label">{{ __('app.overall_rating') }}</label>
                                                <select class="form-select @error('rating') is-invalid @enderror"
                                                    wire:model="rating">
                                                    <option value="0">{{ __('app.no_rating') }}</option>
                                                    <option value="1">1 - {{ __('app.poor') }}</option>
                                                    <option value="2">2 - {{ __('app.fair') }}</option>
                                                    <option value="3">3 - {{ __('app.good') }}</option>
                                                    <option value="4">4 - {{ __('app.very_good') }}</option>
                                                    <option value="5">5 - {{ __('app.excellent') }}</option>
                                                </select>
                                                @error('rating')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label">{{ __('app.delivery_rating') }}</label>
                                                <select
                                                    class="form-select @error('delivery_rating') is-invalid @enderror"
                                                    wire:model="delivery_rating">
                                                    <option value="0">{{ __('app.no_rating') }}</option>
                                                    <option value="1">1 - {{ __('app.poor') }}</option>
                                                    <option value="2">2 - {{ __('app.fair') }}</option>
                                                    <option value="3">3 - {{ __('app.good') }}</option>
                                                    <option value="4">4 - {{ __('app.very_good') }}</option>
                                                    <option value="5">5 - {{ __('app.excellent') }}</option>
                                                </select>
                                                @error('delivery_rating')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label">{{ __('app.quality_rating') }}</label>
                                                <select
                                                    class="form-select @error('quality_rating') is-invalid @enderror"
                                                    wire:model="quality_rating">
                                                    <option value="0">{{ __('app.no_rating') }}</option>
                                                    <option value="1">1 - {{ __('app.poor') }}</option>
                                                    <option value="2">2 - {{ __('app.fair') }}</option>
                                                    <option value="3">3 - {{ __('app.good') }}</option>
                                                    <option value="4">4 - {{ __('app.very_good') }}</option>
                                                    <option value="5">5 - {{ __('app.excellent') }}</option>
                                                </select>
                                                @error('quality_rating')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">{{ __('app.last_supply_date') }}</label>
                                                <input type="date"
                                                    class="form-control @error('last_supply_date') is-invalid @enderror"
                                                    wire:model="last_supply_date">
                                                @error('last_supply_date')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-12">
                                            <div class="mb-3">
                                                <label class="form-label">{{ __('app.notes') }}</label>
                                                <textarea class="form-control @error('notes') is-invalid @enderror" rows="4" wire:model="notes"
                                                    placeholder="{{ __('app.enter_notes') }}"></textarea>
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
                                    {{ $editingSuplierId ? __('app.update') : __('app.save') }}
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

            // Supplier saved notification
            Livewire.on('supplier-saved', (event) => {
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

            // Supplier deleted notification
            Livewire.on('supplier-deleted', (event) => {
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
        function confirmDeleteSupplier(supplierId) {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: '{{ __('app.are_you_sure') }}',
                    text: '{{ __('app.confirm_delete_supplier') }}',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: '{{ __('app.delete') }}',
                    cancelButtonText: '{{ __('app.cancel') }}'
                }).then((result) => {
                    if (result.isConfirmed) {
                        @this.delete(supplierId);
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
