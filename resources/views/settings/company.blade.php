@extends('layouts.main')

@section('title', __('app.company_settings'))

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="row mb-4">
            <div class="col">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h2 class="h3 mb-1">
                            <i class="fas fa-building me-2 text-primary"></i>
                            {{ __('app.company_settings') }}
                        </h2>
                        <p class="text-muted mb-0">{{ __('app.manage_company_info') }}</p>
                    </div>
                    <div>
                        <button class="btn btn-success" type="button" onclick="saveCompanySettings()">
                            <i class="fas fa-save me-2"></i>
                            {{ __('app.save_changes') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Company Information -->
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-info-circle me-2"></i>
                            {{ __('app.basic_information') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        @if ($errors->has('error'))
                            <div class="alert alert-danger">
                                {{ $errors->first('error') }}
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif

                        <form id="companyForm">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="companyName" class="form-label">{{ __('app.company_name') }} <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="companyName" name="company_name"
                                        value="{{ $settings->company_name ?? '' }}" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="companyNameEn" class="form-label">{{ __('app.company_name_en') }}</label>
                                    <input type="text" class="form-control" id="companyNameEn" name="company_name_en"
                                        value="{{ $settings->company_name_en ?? '' }}">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="commercialNumber"
                                        class="form-label">{{ __('app.commercial_number') }}</label>
                                    <input type="text" class="form-control" id="commercialNumber"
                                        name="commercial_number" value="{{ $settings->commercial_number ?? '' }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="taxNumber" class="form-label">{{ __('app.tax_number') }}</label>
                                    <input type="text" class="form-control" id="taxNumber" name="tax_number"
                                        value="{{ $settings->tax_number ?? '' }}">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">{{ __('app.email') }}</label>
                                    <input type="email" class="form-control" id="email" name="email"
                                        value="{{ $settings->email ?? '' }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="phone" class="form-label">{{ __('app.phone') }}</label>
                                    <input type="text" class="form-control" id="phone" name="phone"
                                        value="{{ $settings->phone ?? '' }}">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="website" class="form-label">{{ __('app.website') }}</label>
                                    <input type="url" class="form-control" id="website" name="website"
                                        value="{{ $settings->website ?? '' }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="currency" class="form-label">{{ __('app.currency') }}</label>
                                    <select class="form-select" id="currency" name="currency">
                                        <option value="SAR"
                                            {{ ($settings->currency ?? 'SAR') == 'SAR' ? 'selected' : '' }}>
                                            {{ __('app.saudi_riyal') }} (SAR)</option>
                                        <option value="USD"
                                            {{ ($settings->currency ?? 'SAR') == 'USD' ? 'selected' : '' }}>
                                            {{ __('app.us_dollar') }} (USD)</option>
                                        <option value="EUR"
                                            {{ ($settings->currency ?? 'SAR') == 'EUR' ? 'selected' : '' }}>
                                            {{ __('app.euro') }} (EUR)</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="address" class="form-label">{{ __('app.address') }}</label>
                                <textarea class="form-control" id="address" name="address" rows="3">{{ $settings->address ?? '' }}</textarea>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- System Settings -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-cogs me-2"></i>
                            {{ __('app.system_settings') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="dateFormat" class="form-label">{{ __('app.date_format') }}</label>
                                <select class="form-select" id="dateFormat" name="date_format">
                                    <option value="d/m/Y"
                                        {{ ($settings->date_format ?? 'd/m/Y') == 'd/m/Y' ? 'selected' : '' }}>DD/MM/YYYY
                                    </option>
                                    <option value="m/d/Y"
                                        {{ ($settings->date_format ?? 'd/m/Y') == 'm/d/Y' ? 'selected' : '' }}>MM/DD/YYYY
                                    </option>
                                    <option value="Y-m-d"
                                        {{ ($settings->date_format ?? 'd/m/Y') == 'Y-m-d' ? 'selected' : '' }}>YYYY-MM-DD
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="decimalPlaces" class="form-label">{{ __('app.decimal_places') }} <span
                                        class="text-danger">*</span></label>
                                <select class="form-select" id="decimalPlaces" name="decimal_places">
                                    <option value="0" {{ ($settings->decimal_places ?? 2) == 0 ? 'selected' : '' }}>0
                                    </option>
                                    <option value="1" {{ ($settings->decimal_places ?? 2) == 1 ? 'selected' : '' }}>1
                                    </option>
                                    <option value="2" {{ ($settings->decimal_places ?? 2) == 2 ? 'selected' : '' }}>2
                                    </option>
                                    <option value="3" {{ ($settings->decimal_places ?? 2) == 3 ? 'selected' : '' }}>3
                                    </option>
                                    <option value="4" {{ ($settings->decimal_places ?? 2) == 4 ? 'selected' : '' }}>4
                                    </option>
                                </select>
                                <div class="form-text">{{ __('app.decimal_places_help') }}</div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="enableNotifications"
                                        name="enable_notifications"
                                        {{ $settings->enable_notifications ?? true ? 'checked' : '' }}>
                                    <label class="form-check-label" for="enableNotifications">
                                        {{ __('app.enable_notifications') }}
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Company Logo and Status -->
            <div class="col-lg-4">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-image me-2"></i>
                            {{ __('app.company_logo') }}
                        </h5>
                    </div>
                    <div class="card-body text-center">
                        <div class="mb-3">
                            @if ($settings && $settings->logo_path)
                                <div class="avatar avatar-xl mx-auto mb-3">
                                    <img src="{{ asset('storage/' . $settings->logo_path) }}" alt="Company Logo"
                                        class="img-fluid rounded">
                                </div>
                            @else
                                <div class="avatar avatar-xl mx-auto mb-3 bg-primary">
                                    <i class="fas fa-building text-white" style="font-size: 2rem;"></i>
                                </div>
                            @endif
                            <p class="text-muted small">{{ __('app.logo_requirements') }}</p>
                        </div>
                        <input type="file" id="logoInput" accept="image/jpeg,image/png,image/jpg"
                            style="display: none;" onchange="uploadLogo()">
                        <button class="btn btn-outline-primary btn-sm"
                            onclick="document.getElementById('logoInput').click()">
                            <i class="fas fa-upload me-2"></i>
                            {{ __('app.upload_logo') }}
                        </button>
                        @if ($settings && $settings->logo_path)
                            <button class="btn btn-outline-danger btn-sm ms-2" onclick="deleteLogo()">
                                <i class="fas fa-trash me-2"></i>
                                {{ __('app.delete_logo') }}
                            </button>
                        @endif
                    </div>
                </div>

                <!-- Company Status -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-chart-line me-2"></i>
                            {{ __('app.company_status') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="text-muted">{{ __('app.status') }}</span>
                            <span class="badge bg-success">{{ __('app.active') }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="text-muted">{{ __('app.created_at') }}</span>
                            <span>{{ __('app.january') }} 2024</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="text-muted">{{ __('app.employees') }}</span>
                            <span>50</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted">{{ __('app.branches_count') }}</span>
                            <span>3</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- App JavaScript -->
    <script src="{{ asset('js/app.js') }}"></script>

    <!-- Pass data to JavaScript -->
    <script>
        // Global variables for JavaScript
        window.companyApiUrl = "{{ route('company.settings.update') }}";
        window.logoUploadUrl = "{{ route('company.settings.upload-logo') }}";
        window.logoDeleteUrl = "{{ route('company.settings.delete-logo') }}";
        window.csrfToken = "{{ csrf_token() }}";

        window.translations = {
            saving: '{{ __('app.saving') }}',
            success: '{{ __('app.success') }}',
            error: '{{ __('app.error') }}',
            ok: '{{ __('app.ok') }}',
            error_occurred: '{{ __('app.error_occurred') }}',
            invalid_file_type: '{{ __('app.invalid_file_type') }}',
            file_too_large: '{{ __('app.file_too_large') }}',
            uploading: '{{ __('app.uploading') }}',
            please_wait: '{{ __('app.please_wait') }}',
            are_you_sure: '{{ __('app.are_you_sure') }}',
            delete_logo_confirmation: '{{ __('app.delete_logo_confirmation') }}',
            delete: '{{ __('app.delete') }}',
            cancel: '{{ __('app.cancel') }}',
            deleting: '{{ __('app.deleting') }}',
            save_success: '{{ __('app.settings_saved_successfully') }}',
            save_error: '{{ __('app.save_error') }}',
            validation_error: '{{ __('app.validation_error') }}',
            connection_error: '{{ __('app.connection_error') }}',
            logo_upload_success: '{{ __('app.logo_uploaded_successfully') }}',
            logo_upload_error: '{{ __('app.logo_upload_error') }}',
            logo_delete_success: '{{ __('app.logo_deleted_successfully') }}',
            logo_delete_error: '{{ __('app.logo_delete_error') }}',
            confirm_delete_logo: '{{ __('app.confirm_delete_logo') }}',
            cannot_restore_logo: '{{ __('app.cannot_restore_logo') }}',
            yes_delete: '{{ __('app.yes_delete') }}'
        };
    </script>

    <!-- Company Settings JavaScript -->
    <script src="{{ asset('js/company-settings.js') }}"></script>
@endpush
