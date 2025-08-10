<div>
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h2 class="h3 mb-1">
                        <i class="fas fa-code-branch me-2 text-primary"></i>
                        {{ __('app.branches') }}
                    </h2>
                    <p class="text-muted mb-0">{{ __('app.manage_branches_description') }}</p>
                </div>
                <div>
                    <button class="btn btn-primary" wire:click="openAddModal">
                        <i class="fas fa-plus me-2"></i>
                        {{ __('app.add_branch') }}
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
                            <h6 class="card-subtitle mb-0 opacity-75">{{ __('app.total_branches') }}</h6>
                            <h2 class="card-title mb-0">{{ $stats['total'] }}</h2>
                        </div>
                        <div class="ms-3">
                            <i class="fas fa-code-branch fa-2x opacity-75"></i>
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
                            <h6 class="card-subtitle mb-0 opacity-75">{{ __('app.active_branches') }}</h6>
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
            <div class="card bg-secondary text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="card-subtitle mb-0 opacity-75">{{ __('app.inactive_branches') }}</h6>
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
                    placeholder="{{ __('app.search_branches') }}">
            </div>
        </div>
        <div class="col-md-6">
            <button class="btn btn-outline-secondary" wire:click="clearSearch">
                <i class="fas fa-times me-2"></i>
                {{ __('app.clear_search') }}
            </button>
        </div>
    </div>

    <!-- Flash Messages -->
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Branches Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="fas fa-list me-2"></i>
                {{ __('app.branches_list') }}
            </h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>{{ __('app.id') }}</th>
                            <th>{{ __('app.name') }}</th>
                            <th>{{ __('app.location') }}</th>
                            <th>{{ __('app.status') }}</th>
                            <th>{{ __('app.created_at') }}</th>
                            <th>{{ __('app.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($branches as $branch)
                            <tr>
                                <td>{{ $branch->id }}</td>
                                <td>{{ $branch->name }}</td>
                                <td>{{ $branch->location }}</td>
                                <td>
                                    <div class="form-check form-switch mb-0">
                                        <input class="form-check-input" type="checkbox"
                                            id="statusSwitch{{ $branch->id }}"
                                            {{ $branch->is_active ? 'checked' : '' }}
                                            wire:click="toggleStatus({{ $branch->id }})"
                                            title="{{ $branch->is_active ? __('app.deactivate') : __('app.activate') }}">
                                        <label class="form-check-label" for="statusSwitch{{ $branch->id }}"></label>
                                    </div>
                                </td>
                                <td>{{ $branch->created_at->format('Y-m-d') }}</td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <button type="button" class="btn btn-link text-primary p-1"
                                            wire:click="openEditModal({{ $branch->id }})"
                                            title="{{ __('app.edit') }}">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-link text-danger p-1"
                                            wire:click="delete({{ $branch->id }})"
                                            wire:confirm="{{ __('app.confirm_delete_branch') }}"
                                            title="{{ __('app.delete') }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">{{ __('app.no_branches_found') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-3">
                {{ $branches->links() }}
            </div>
        </div>
    </div>

    <!-- Branch Modal -->
    @if ($showModal)
        <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5);">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            {{ $editingBranchId ? __('app.edit_branch') : __('app.add_branch') }}
                        </h5>
                        <button type="button" class="btn-close" wire:click="closeModal"></button>
                    </div>
                    <form wire:submit="save">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="branchName" class="form-label">{{ __('app.branch_name') }} <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    id="branchName" wire:model="name" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="branchLocation" class="form-label">{{ __('app.branch_location') }} <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('location') is-invalid @enderror"
                                    id="branchLocation" wire:model="location" required>
                                @error('location')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="branchIsActive"
                                        wire:model="is_active">
                                    <label class="form-check-label" for="branchIsActive">
                                        {{ __('app.branch_active') }}
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" wire:click="closeModal">
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
    @endif

    <!-- Wire Loading Indicator -->
    <div wire:loading class="position-fixed top-50 start-50 translate-middle">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
</div>

@push('styles')
    <style>
        .btn-link {
            text-decoration: none !important;
            border: none !important;
            background: none !important;
            box-shadow: none !important;
        }

        .btn-link:hover {
            text-decoration: none !important;
            opacity: 0.7;
            transform: scale(1.1);
            transition: all 0.2s ease;
        }

        .btn-link:focus {
            box-shadow: none !important;
            outline: none !important;
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('livewire:init', () => {
            // Status toggle notifications
            Livewire.on('status-toggled', (data) => {
                if (data[0].success) {
                    Swal.fire({
                        icon: 'success',
                        title: '{{ __('app.success') }}',
                        text: data[0].message,
                        showConfirmButton: false,
                        timer: 2000
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: '{{ __('app.error') }}',
                        text: data[0].message,
                        confirmButtonText: '{{ __('app.ok') }}'
                    });
                }
            });

            // Branch saved notification
            Livewire.on('branch-saved', (data) => {
                Swal.fire({
                    icon: 'success',
                    title: '{{ __('app.success') }}',
                    text: data[0].message,
                    showConfirmButton: false,
                    timer: 2000
                });
            });

            // Branch deleted notification
            Livewire.on('branch-deleted', (data) => {
                Swal.fire({
                    icon: 'success',
                    title: '{{ __('app.success') }}',
                    text: data[0].message,
                    showConfirmButton: false,
                    timer: 2000
                });
            });

            // Error notification
            Livewire.on('error-occurred', (data) => {
                Swal.fire({
                    icon: 'error',
                    title: '{{ __('app.error') }}',
                    text: data[0].message,
                    confirmButtonText: '{{ __('app.ok') }}'
                });
            });

            // Modal close on successful save
            Livewire.on('modal-closed', () => {
                // Optional: Additional actions when modal is closed
            });
        });
    </script>
@endpush
