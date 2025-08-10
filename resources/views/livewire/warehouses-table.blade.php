<div>
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h2 class="h3 mb-1">
                        <i class="fas fa-warehouse me-2 text-primary"></i>
                        {{ __('app.warehouses') }}
                    </h2>
                    <p class="text-muted mb-0">{{ __('app.manage_warehouses_description') }}</p>
                </div>
                <div>
                    <button class="btn btn-primary" wire:click="openAddModal">
                        <i class="fas fa-plus me-2"></i>
                        {{ __('app.add_warehouse') }}
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
                            <h6 class="card-subtitle mb-0 opacity-75 small">{{ __('app.total_warehouses') }}</h6>
                            <h4 class="card-title mb-0">{{ $stats['total'] ?? 0 }}</h4>
                        </div>
                        <div class="ms-2">
                            <i class="fas fa-warehouse fa-lg opacity-75"></i>
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
                            <h6 class="card-subtitle mb-0 opacity-75 small">{{ __('app.active_warehouses') }}</h6>
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
                            <h6 class="card-subtitle mb-0 opacity-75 small">{{ __('app.inactive_warehouses') }}</h6>
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
                            <h6 class="card-subtitle mb-0 opacity-75 small">{{ __('app.main_warehouses') }}</h6>
                            <h4 class="card-title mb-0">{{ $stats['main'] ?? 0 }}</h4>
                        </div>
                        <div class="ms-2">
                            <i class="fas fa-home fa-lg opacity-75"></i>
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
                            <h6 class="card-subtitle mb-0 opacity-75 small">{{ __('app.branch_warehouses') }}</h6>
                            <h4 class="card-title mb-0">{{ $stats['branch'] ?? 0 }}</h4>
                        </div>
                        <div class="ms-2">
                            <i class="fas fa-code-branch fa-lg opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card bg-dark text-white">
                <div class="card-body py-3">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="card-subtitle mb-0 opacity-75 small">{{ __('app.virtual_warehouses') }}</h6>
                            <h4 class="card-title mb-0">{{ $stats['virtual'] ?? 0 }}</h4>
                        </div>
                        <div class="ms-2">
                            <i class="fas fa-cloud fa-lg opacity-75"></i>
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
                <div class="col-md-4">
                    <label class="form-label small text-muted">{{ __('app.search') }}</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-search"></i>
                        </span>
                        <input type="text" class="form-control" wire:model.live="search"
                            placeholder="{{ __('app.search_warehouses_placeholder') }}">
                    </div>
                </div>
                <div class="col-md-2">
                    <label class="form-label small text-muted">{{ __('app.warehouse_type') }}</label>
                    <select class="form-select" wire:model.live="typeFilter">
                        <option value="">{{ __('app.all_types') }}</option>
                        <option value="main">{{ __('app.main') }}</option>
                        <option value="branch">{{ __('app.branch') }}</option>
                        <option value="virtual">{{ __('app.virtual') }}</option>
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
                <div class="col-md-3">
                    <label class="form-label small text-muted">{{ __('app.city') }}</label>
                    <input type="text" class="form-control" wire:model.live="cityFilter"
                        placeholder="{{ __('app.all') }}">
                </div>
                <div class="col-md-1">
                    <label class="form-label small text-muted">&nbsp;</label>
                    <button type="button" class="btn btn-outline-secondary w-100" wire:click="clearSearch">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Warehouses Table -->
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-vcenter">
                    <thead>
                        <tr>
                            <th class="text-center">{{ __('app.warehouse_code') }}</th>
                            <th class="text-center">{{ __('app.warehouse_name') }}</th>
                            <th class="text-center">{{ __('app.warehouse_type') }}</th>
                            <th class="text-center">{{ __('app.city') }}</th>
                            <th class="text-center">{{ __('app.manager_name') }}</th>
                            <th class="text-center">{{ __('app.phone') }}</th>
                            <th class="text-center">{{ __('app.status') }}</th>
                            <th class="w-1 text-center">{{ __('app.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($warehouses as $warehouse)
                            <tr>
                                <td class="text-center">
                                    <span class="text-secondary">{{ $warehouse->code }}</span>
                                </td>
                                <td class="text-center">
                                    <div class="font-weight-medium">{{ $warehouse->name }}</div>
                                    @if ($warehouse->description)
                                        <div class="text-secondary small">
                                            {{ Str::limit($warehouse->description, 50) }}</div>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <span
                                        class="badge {{ $warehouse->type === 'main' ? 'bg-primary' : ($warehouse->type === 'branch' ? 'bg-info' : 'bg-secondary') }}">
                                        <i
                                            class="fas {{ $warehouse->type === 'main' ? 'fa-home' : ($warehouse->type === 'branch' ? 'fa-code-branch' : 'fa-cloud') }} me-1"></i>
                                        {{ __('app.' . $warehouse->type) }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="text-secondary">{{ $warehouse->city ?? '-' }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="text-secondary">{{ $warehouse->manager_name ?? '-' }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="text-secondary">{{ $warehouse->phone ?? '-' }}</span>
                                    @if ($warehouse->manager_phone && $warehouse->phone !== $warehouse->manager_phone)
                                        <br><span class="text-muted small">{{ $warehouse->manager_phone }}</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="form-check form-switch mb-0 d-flex justify-content-center">
                                        <input class="form-check-input" type="checkbox" role="switch"
                                            {{ $warehouse->is_active ? 'checked' : '' }}
                                            wire:click="toggleStatus({{ $warehouse->id }})">
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-sm btn-link text-primary"
                                            wire:click="openEditModal({{ $warehouse->id }})"
                                            title="{{ __('app.edit') }}">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-link text-danger"
                                            onclick="confirmDeleteWarehouse({{ $warehouse->id }})"
                                            title="{{ __('app.delete') }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <i class="fas fa-warehouse fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">{{ __('app.no_warehouses_found') }}</h5>
                                    <p class="text-muted mb-0">{{ __('app.try_adjusting_search_filters') }}</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if ($warehouses->hasPages())
            <div class="card-footer">
                {{ $warehouses->links() }}
            </div>
        @endif
    </div>

    <!-- Warehouse Modal -->
    @if ($showModal)
        <div class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5)">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            @if ($editingWarehouseId)
                                <i class="fas fa-edit me-2"></i>
                                {{ __('app.edit_warehouse') }}
                            @else
                                <i class="fas fa-plus me-2"></i>
                                {{ __('app.add_warehouse') }}
                            @endif
                        </h5>
                        <button type="button" class="btn-close" wire:click="closeModal"></button>
                    </div>
                    <div class="modal-body">
                        <form wire:submit.prevent="save">
                            <!-- Tab Navigation -->
                            <ul class="nav nav-tabs form-tabs mb-4" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link active" data-bs-toggle="tab" href="#warehouse-info-tab"
                                        role="tab" aria-controls="warehouse-info-tab" aria-selected="true">
                                        <i class="fas fa-info-circle me-1"></i>
                                        {{ __('app.warehouse_info') }}
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" data-bs-toggle="tab" href="#location-management-tab"
                                        role="tab" aria-controls="location-management-tab" aria-selected="false">
                                        <i class="fas fa-map-marker-alt me-1"></i>
                                        {{ __('app.location_management') }}
                                    </a>
                                </li>
                            </ul>

                            <!-- Tab Content -->
                            <div class="tab-content">
                                <!-- Warehouse Info Tab -->
                                <div class="tab-pane fade show active" id="warehouse-info-tab" role="tabpanel">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label
                                                    class="form-label required">{{ __('app.warehouse_name') }}</label>
                                                <input type="text"
                                                    class="form-control @error('name') is-invalid @enderror"
                                                    wire:model="name"
                                                    placeholder="{{ __('app.enter_warehouse_name') }}">
                                                @error('name')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">{{ __('app.warehouse_code') }}</label>
                                                <input type="text"
                                                    class="form-control @error('code') is-invalid @enderror"
                                                    wire:model="code"
                                                    placeholder="{{ __('app.enter_warehouse_code') }}">
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
                                                    class="form-label required">{{ __('app.warehouse_type') }}</label>
                                                <select class="form-select @error('type') is-invalid @enderror"
                                                    wire:model="type">
                                                    <option value="main">{{ __('app.main') }}</option>
                                                    <option value="branch">{{ __('app.branch') }}</option>
                                                    <option value="virtual">{{ __('app.virtual') }}</option>
                                                </select>
                                                @error('type')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">{{ __('app.phone') }}</label>
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
                                        <div class="col-12">
                                            <div class="mb-3">
                                                <label class="form-label">{{ __('app.description') }}</label>
                                                <textarea class="form-control @error('description') is-invalid @enderror" rows="3" wire:model="description"
                                                    placeholder="{{ __('app.enter_description') }}"></textarea>
                                                @error('description')
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
                                                        {{ __('app.active_warehouse') }}
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Location & Management Tab -->
                                <div class="tab-pane fade" id="location-management-tab" role="tabpanel">
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
                                        <div class="col-md-4">
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
                                        <div class="col-md-4">
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
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label">{{ __('app.country') }}</label>
                                                <input type="text"
                                                    class="form-control @error('country') is-invalid @enderror"
                                                    wire:model="country" placeholder="{{ __('app.enter_country') }}">
                                                @error('country')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">{{ __('app.manager_name') }}</label>
                                                <input type="text"
                                                    class="form-control @error('manager_name') is-invalid @enderror"
                                                    wire:model="manager_name"
                                                    placeholder="{{ __('app.enter_manager_name') }}">
                                                @error('manager_name')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">{{ __('app.manager_phone') }}</label>
                                                <input type="text"
                                                    class="form-control @error('manager_phone') is-invalid @enderror"
                                                    wire:model="manager_phone"
                                                    placeholder="{{ __('app.enter_manager_phone') }}">
                                                @error('manager_phone')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">{{ __('app.manager_email') }}</label>
                                                <input type="email"
                                                    class="form-control @error('manager_email') is-invalid @enderror"
                                                    wire:model="manager_email"
                                                    placeholder="{{ __('app.enter_manager_email') }}">
                                                @error('manager_email')
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
                                    {{ $editingWarehouseId ? __('app.update') : __('app.save') }}
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

            /* تحسين مظهر السويتش لتحديث الحالة */
            .form-switch .form-check-input {
                background-color: #e9ecef;
                border-color: #dee2e6;
                transition: all 0.3s ease;
            }

            .form-switch .form-check-input:checked {
                background-color: #467fcf !important;
                border-color: #467fcf !important;
            }

            /* تحسين شارات أنواع المخازن */
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

            // Warehouse saved notification
            Livewire.on('warehouse-saved', (event) => {
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

            // Warehouse deleted notification
            Livewire.on('warehouse-deleted', (event) => {
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
        function confirmDeleteWarehouse(warehouseId) {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: '{{ __('app.are_you_sure') }}',
                    text: '{{ __('app.confirm_delete_warehouse') }}',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: '{{ __('app.delete') }}',
                    cancelButtonText: '{{ __('app.cancel') }}'
                }).then((result) => {
                    if (result.isConfirmed) {
                        @this.delete(warehouseId);
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
