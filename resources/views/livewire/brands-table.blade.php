<div>
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h2 class="h3 mb-1">
                        <i class="fas fa-award me-2 text-primary"></i>
                        {{ __('app.brands') }}
                    </h2>
                    <p class="text-muted mb-0">{{ __('app.manage_brands_description') }}</p>
                </div>
                <div>
                    <button class="btn btn-primary" wire:click="openAddModal">
                        <i class="fas fa-plus me-2"></i>
                        {{ __('app.add_brand') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="card-subtitle mb-0 opacity-75">{{ __('app.total_brands') }}</h6>
                            <h2 class="card-title mb-0">{{ $stats['total'] }}</h2>
                        </div>
                        <div class="ms-3">
                            <i class="fas fa-award fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="card-subtitle mb-0 opacity-75">{{ __('app.active_brands') }}</h6>
                            <h2 class="card-title mb-0">{{ $stats['active'] }}</h2>
                        </div>
                        <div class="ms-3">
                            <i class="fas fa-check-circle fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="card-subtitle mb-0 opacity-75">{{ __('app.inactive_brands') }}</h6>
                            <h2 class="card-title mb-0">{{ $stats['inactive'] }}</h2>
                        </div>
                        <div class="ms-3">
                            <i class="fas fa-times-circle fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="input-group">
                <span class="input-group-text">
                    <i class="fas fa-search"></i>
                </span>
                <input type="text" class="form-control" wire:model.live="search"
                    placeholder="{{ __('app.search_brands') }}">
            </div>
        </div>
        <div class="col-md-6">
            <button class="btn btn-outline-secondary" wire:click="clearSearch">
                <i class="fas fa-times me-2"></i>
                {{ __('app.clear_search') }}
            </button>
        </div>
    </div>

    <!-- Brands Table -->
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>{{ __('app.id') }}</th>
                            <th>{{ __('app.name') }}</th>
                            <th>{{ __('app.description') }}</th>
                            <th>{{ __('app.logo') }}</th>
                            <th>{{ __('app.status') }}</th>
                            <th>{{ __('app.created_at') }}</th>
                            <th>{{ __('app.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($brands as $brand)
                            <tr>
                                <td>{{ $brand->id }}</td>
                                <td>{{ $brand->name }}</td>
                                <td>{{ Str::limit($brand->description, 50) ?? '-' }}</td>
                                <td>
                                    @if ($brand->logo)
                                        <span class="badge bg-info">{{ __('app.has_logo') }}</span>
                                    @else
                                        <span class="badge bg-secondary">{{ __('app.no_logo') }}</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="form-check form-switch mb-0">
                                        <input class="form-check-input" type="checkbox"
                                            id="statusSwitch{{ $brand->id }}"
                                            {{ $brand->is_active ? 'checked' : '' }}
                                            wire:click="toggleStatus({{ $brand->id }})"
                                            title="{{ $brand->is_active ? __('app.deactivate') : __('app.activate') }}">
                                        <label class="form-check-label" for="statusSwitch{{ $brand->id }}"></label>
                                    </div>
                                </td>
                                <td>{{ $brand->created_at->format('Y-m-d') }}</td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <button type="button" class="btn btn-link text-primary p-1"
                                            wire:click="openEditModal({{ $brand->id }})"
                                            title="{{ __('app.edit') }}">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-link text-danger p-1"
                                            wire:click="delete({{ $brand->id }})"
                                            wire:confirm="{{ __('app.confirm_delete_brand') }}"
                                            title="{{ __('app.delete') }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <i class="fas fa-award fa-3x text-muted mb-3"></i>
                                    <p class="text-muted mb-0">{{ __('app.no_brands_found') }}</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    @if ($brands->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $brands->links() }}
        </div>
    @endif

    <!-- Add/Edit Modal -->
    @if ($showModal)
        <div class="modal fade show" style="display: block;" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            @if ($editingBrandId)
                                <i class="fas fa-edit me-2"></i>
                                {{ __('app.edit_brand') }}
                            @else
                                <i class="fas fa-plus me-2"></i>
                                {{ __('app.add_brand') }}
                            @endif
                        </h5>
                        <button type="button" class="btn-close" wire:click="closeModal"></button>
                    </div>
                    <div class="modal-body">
                        <form wire:submit.prevent="save">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">{{ __('app.brand_name') }}</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                            id="name" wire:model="name"
                                            placeholder="{{ __('app.brand_name') }}">
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="logo" class="form-label">{{ __('app.brand_logo') }}</label>
                                        <input type="url" class="form-control @error('logo') is-invalid @enderror"
                                            id="logo" wire:model="logo"
                                            placeholder="{{ __('app.brand_logo_url') }}">
                                        @error('logo')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
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
                            <div class="mb-3">
                                <label for="description" class="form-label">{{ __('app.description') }}</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" id="description" wire:model="description"
                                    rows="3" placeholder="{{ __('app.brand_description') }}"></textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="closeModal">
                            {{ __('app.cancel') }}
                        </button>
                        <button type="button" class="btn btn-primary" wire:click="save">
                            <i class="fas fa-save me-2"></i>
                            {{ __('app.save') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
    @endif
</div>

<script>
    // Handle brand events
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

        // Brand saved notification
        Livewire.on('brand-saved', (event) => {
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

        // Brand deleted notification
        Livewire.on('brand-deleted', (event) => {
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
