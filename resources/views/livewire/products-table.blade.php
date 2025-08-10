<div>
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h2 class="h3 mb-1">
                        <i class="fas fa-boxes me-2 text-primary"></i>
                        {{ __('app.products') }}
                    </h2>
                    <p class="text-muted mb-0">{{ __('app.manage_products_description') }}</p>
                </div>
                <div>
                    <button class="btn btn-primary" wire:click="openAddModal">
                        <i class="fas fa-plus me-2"></i>
                        {{ __('app.add_product') }}
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
                            <h6 class="card-subtitle mb-0 opacity-75 small">{{ __('app.total_products') }}</h6>
                            <h4 class="card-title mb-0">{{ $stats['total'] }}</h4>
                        </div>
                        <div class="ms-2">
                            <i class="fas fa-boxes fa-lg opacity-75"></i>
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
                            <h6 class="card-subtitle mb-0 opacity-75 small">{{ __('app.active_products') }}</h6>
                            <h4 class="card-title mb-0">{{ $stats['active'] }}</h4>
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
                            <h6 class="card-subtitle mb-0 opacity-75 small">{{ __('app.inactive_products') }}</h6>
                            <h4 class="card-title mb-0">{{ $stats['inactive'] }}</h4>
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
                            <h6 class="card-subtitle mb-0 opacity-75 small">{{ __('app.services_only') }}</h6>
                            <h4 class="card-title mb-0">{{ $stats['services'] }}</h4>
                        </div>
                        <div class="ms-2">
                            <i class="fas fa-cogs fa-lg opacity-75"></i>
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
                            <h6 class="card-subtitle mb-0 opacity-75 small">{{ __('app.products_only') }}</h6>
                            <h4 class="card-title mb-0">{{ $stats['products'] }}</h4>
                        </div>
                        <div class="ms-2">
                            <i class="fas fa-box fa-lg opacity-75"></i>
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
                            <h6 class="card-subtitle mb-0 opacity-75 small">{{ __('app.low_stock') }}</h6>
                            <h4 class="card-title mb-0">{{ $stats['low_stock'] }}</h4>
                        </div>
                        <div class="ms-2">
                            <i class="fas fa-exclamation-triangle fa-lg opacity-75"></i>
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
                            placeholder="{{ __('app.search_products') }}">
                    </div>
                </div>
                <div class="col-md-2">
                    <label class="form-label small text-muted">{{ __('app.category') }}</label>
                    <select class="form-select" wire:model.live="categoryFilter">
                        <option value="">{{ __('app.all_categories') }}</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small text-muted">{{ __('app.brand') }}</label>
                    <select class="form-select" wire:model.live="brandFilter">
                        <option value="">{{ __('app.all_brands') }}</option>
                        @foreach ($brands as $brand)
                            <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small text-muted">{{ __('app.product_type') }}</label>
                    <select class="form-select" wire:model.live="typeFilter">
                        <option value="">{{ __('app.all_types') }}</option>
                        <option value="0">{{ __('app.products_only') }}</option>
                        <option value="1">{{ __('app.services_only') }}</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small text-muted">{{ __('app.status') }}</label>
                    <select class="form-select" wire:model.live="statusFilter">
                        <option value="">{{ __('app.all') }}</option>
                        <option value="1">{{ __('app.active') }}</option>
                        <option value="0">{{ __('app.inactive') }}</option>
                    </select>
                </div>
                <div class="col-md-1">
                    <label class="form-label small text-muted">&nbsp;</label>
                    <button class="btn btn-outline-secondary w-100" wire:click="clearSearch">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Products Table -->
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>{{ __('app.product_code') }}</th>
                            <th>{{ __('app.product_name') }}</th>
                            <th>{{ __('app.category') }}</th>
                            <th>{{ __('app.brand') }}</th>
                            <th>{{ __('app.product_type') }}</th>
                            <th>{{ __('app.selling_price') }}</th>
                            <th>{{ __('app.current_stock') }}</th>
                            <th>{{ __('app.status') }}</th>
                            <th>{{ __('app.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if ($product->image)
                                            <img src="{{ Storage::url($product->image) }}"
                                                alt="{{ $product->name }}" class="product-image me-2">
                                        @else
                                            <div
                                                class="product-image me-2 bg-light d-flex align-items-center justify-content-center">
                                                <i class="fas fa-box text-muted"></i>
                                            </div>
                                        @endif
                                        <strong>{{ $product->code }}</strong>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <div class="fw-semibold">{{ $product->name }}</div>
                                        @if ($product->description)
                                            <small
                                                class="text-muted">{{ Str::limit($product->description, 50) }}</small>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-secondary">{{ $product->category->name ?? '-' }}</span>
                                </td>
                                <td>{{ $product->brand->name ?? '-' }}</td>
                                <td>
                                    @if ($product->is_service)
                                        <span class="badge bg-info">{{ __('app.service') }}</span>
                                    @else
                                        <span class="badge bg-primary">{{ __('app.product') }}</span>
                                    @endif
                                </td>
                                <td>{{ number_format($product->selling_price, 2) }}</td>
                                <td>
                                    @if ($product->track_inventory)
                                        @php
                                            $stockStatus = $product->stock_status;
                                        @endphp
                                        <span class="stock-status stock-{{ $stockStatus }}">
                                            {{ $product->current_stock }}
                                        </span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox"
                                            {{ $product->is_active ? 'checked' : '' }}
                                            wire:click="toggleStatus({{ $product->id }})">
                                    </div>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-sm btn-link text-primary"
                                            wire:click="openEditModal({{ $product->id }})"
                                            title="{{ __('app.edit') }}">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-link text-danger"
                                            onclick="confirmDelete({{ $product->id }})"
                                            title="{{ __('app.delete') }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-5">
                                    <i class="fas fa-boxes fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">{{ __('app.no_products_found') }}</h5>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if ($products->hasPages())
            <div class="card-footer">
                {{ $products->links() }}
            </div>
        @endif
    </div>

    <!-- Modal -->
    @if ($showModal)
        <div class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5)">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            @if ($editingProductId)
                                <i class="fas fa-edit me-2"></i>
                                {{ __('app.edit_product') }}
                            @else
                                <i class="fas fa-plus me-2"></i>
                                {{ __('app.add_product') }}
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
                                        {{ __('app.basic_information') }}
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" data-bs-toggle="tab" href="#pricing-tab" role="tab">
                                        {{ __('app.price') }}
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" data-bs-toggle="tab" href="#inventory-tab" role="tab">
                                        {{ __('app.stock') }}
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" data-bs-toggle="tab" href="#additional-tab" role="tab">
                                        {{ __('app.additional_info') }}
                                    </a>
                                </li>
                            </ul>

                            <!-- Tab Content -->
                            <div class="tab-content">
                                <!-- Basic Information Tab -->
                                <div class="tab-pane fade show active" id="basic-info-tab" role="tabpanel">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="code" class="form-label">{{ __('app.product_code') }}
                                                    *</label>
                                                <input type="text"
                                                    class="form-control @error('code') is-invalid @enderror"
                                                    id="code" wire:model="code"
                                                    placeholder="{{ __('app.product_code') }}">
                                                @error('code')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="name" class="form-label">{{ __('app.product_name') }}
                                                    *</label>
                                                <input type="text"
                                                    class="form-control @error('name') is-invalid @enderror"
                                                    id="name" wire:model="name"
                                                    placeholder="{{ __('app.product_name') }}">
                                                @error('name')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="category_id" class="form-label">{{ __('app.category') }}
                                                    *</label>
                                                <select class="form-select @error('category_id') is-invalid @enderror"
                                                    id="category_id" wire:model="category_id">
                                                    <option value="">{{ __('app.select_category') }}</option>
                                                    @foreach ($categories as $category)
                                                        <option value="{{ $category->id }}">{{ $category->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('category_id')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="brand_id"
                                                    class="form-label">{{ __('app.brand') }}</label>
                                                <select class="form-select @error('brand_id') is-invalid @enderror"
                                                    id="brand_id" wire:model="brand_id">
                                                    <option value="">{{ __('app.select_brand') }}</option>
                                                    @foreach ($brands as $brand)
                                                        <option value="{{ $brand->id }}">{{ $brand->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('brand_id')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="unit_id" class="form-label">{{ __('app.unit') }}
                                                    *</label>
                                                <select class="form-select @error('unit_id') is-invalid @enderror"
                                                    id="unit_id" wire:model="unit_id">
                                                    <option value="">{{ __('app.select_unit') }}</option>
                                                    @foreach ($units as $unit)
                                                        <option value="{{ $unit->id }}">{{ $unit->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('unit_id')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label for="description"
                                                    class="form-label">{{ __('app.description') }}</label>
                                                <textarea class="form-control @error('description') is-invalid @enderror" id="description" rows="3"
                                                    wire:model="description" placeholder="{{ __('app.product_description') }}"></textarea>
                                                @error('description')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" id="is_service"
                                                        wire:model.live="is_service">
                                                    <label class="form-check-label" for="is_service">
                                                        {{ __('app.is_service') }}
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
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

                                <!-- Pricing Tab -->
                                <div class="tab-pane fade" id="pricing-tab" role="tabpanel">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="purchase_price"
                                                    class="form-label">{{ __('app.purchase_price') }} *</label>
                                                <input type="number" step="0.01"
                                                    class="form-control @error('purchase_price') is-invalid @enderror"
                                                    id="purchase_price" wire:model="purchase_price"
                                                    placeholder="0.00">
                                                @error('purchase_price')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="selling_price"
                                                    class="form-label">{{ __('app.selling_price') }} *</label>
                                                <input type="number" step="0.01"
                                                    class="form-control @error('selling_price') is-invalid @enderror"
                                                    id="selling_price" wire:model="selling_price" placeholder="0.00">
                                                @error('selling_price')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="wholesale_price"
                                                    class="form-label">{{ __('app.wholesale_price') }}</label>
                                                <input type="number" step="0.01"
                                                    class="form-control @error('wholesale_price') is-invalid @enderror"
                                                    id="wholesale_price" wire:model="wholesale_price"
                                                    placeholder="0.00">
                                                @error('wholesale_price')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="tax_rate" class="form-label">{{ __('app.tax_rate') }}
                                                    (%)</label>
                                                <input type="number" step="0.01"
                                                    class="form-control @error('tax_rate') is-invalid @enderror"
                                                    id="tax_rate" wire:model="tax_rate" placeholder="0.00">
                                                @error('tax_rate')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Inventory Tab -->
                                <div class="tab-pane fade" id="inventory-tab" role="tabpanel">
                                    @if (!$is_service)
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox"
                                                            id="track_inventory" wire:model.live="track_inventory">
                                                        <label class="form-check-label" for="track_inventory">
                                                            {{ __('app.track_inventory') }}
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        @if ($track_inventory)
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="mb-3">
                                                        <label for="min_stock_level"
                                                            class="form-label">{{ __('app.min_stock_level') }}</label>
                                                        <input type="number"
                                                            class="form-control @error('min_stock_level') is-invalid @enderror"
                                                            id="min_stock_level" wire:model="min_stock_level"
                                                            placeholder="0">
                                                        @error('min_stock_level')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="mb-3">
                                                        <label for="max_stock_level"
                                                            class="form-label">{{ __('app.max_stock_level') }}</label>
                                                        <input type="number"
                                                            class="form-control @error('max_stock_level') is-invalid @enderror"
                                                            id="max_stock_level" wire:model="max_stock_level"
                                                            placeholder="0">
                                                        @error('max_stock_level')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="mb-3">
                                                        <label for="reorder_point"
                                                            class="form-label">{{ __('app.reorder_point') }}</label>
                                                        <input type="number"
                                                            class="form-control @error('reorder_point') is-invalid @enderror"
                                                            id="reorder_point" wire:model="reorder_point"
                                                            placeholder="0">
                                                        @error('reorder_point')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @else
                                        <div class="alert alert-info">
                                            <i class="fas fa-info-circle me-2"></i>
                                            {{ __('app.services_no_inventory') }}
                                        </div>
                                    @endif
                                </div>

                                <!-- Additional Information Tab -->
                                <div class="tab-pane fade" id="additional-tab" role="tabpanel">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="barcode"
                                                    class="form-label">{{ __('app.barcode') }}</label>
                                                <input type="text"
                                                    class="form-control @error('barcode') is-invalid @enderror"
                                                    id="barcode" wire:model="barcode"
                                                    placeholder="{{ __('app.barcode') }}">
                                                @error('barcode')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="sku" class="form-label">{{ __('app.sku') }}</label>
                                                <input type="text"
                                                    class="form-control @error('sku') is-invalid @enderror"
                                                    id="sku" wire:model="sku"
                                                    placeholder="{{ __('app.sku') }}">
                                                @error('sku')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="weight" class="form-label">{{ __('app.weight') }}
                                                    (kg)</label>
                                                <input type="number" step="0.001"
                                                    class="form-control @error('weight') is-invalid @enderror"
                                                    id="weight" wire:model="weight" placeholder="0.000">
                                                @error('weight')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="image"
                                                    class="form-label">{{ __('app.product_image') }}</label>
                                                <input type="file"
                                                    class="form-control @error('image') is-invalid @enderror"
                                                    id="image" wire:model="image" accept="image/*">
                                                @error('image')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label for="notes"
                                                    class="form-label">{{ __('app.notes') }}</label>
                                                <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" rows="3"
                                                    wire:model="notes" placeholder="{{ __('app.notes') }}"></textarea>
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
</div>

@push('scripts')
    <script>
        function confirmDelete(productId) {
            Swal.fire({
                title: '{{ __('app.are_you_sure') }}',
                text: '{{ __('app.confirm_delete_product') }}',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '{{ __('app.delete') }}',
                cancelButtonText: '{{ __('app.cancel') }}'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.delete(productId);
                }
            });
        }
    </script>

    <script>
        // Handle product events
        document.addEventListener('livewire:init', () => {
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

            // Product saved notification
            Livewire.on('product-saved', (event) => {
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

            // Product deleted notification
            Livewire.on('product-deleted', (event) => {
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
        });
    </script>
@endpush
