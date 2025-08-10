<!-- Bootstrap Footer -->
<footer class="footer bg-light border-top py-3 mt-auto">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <div class="d-flex align-items-center">
                    <i class="fas fa-building me-2 text-primary"></i>
                    <span class="text-muted">&copy; {{ date('Y') }} {{ __('app.business_management_system') }}.
                        {{ __('app.all_rights_reserved') }}</span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="d-flex justify-content-md-end justify-content-start align-items-center">
                    <span class="text-muted me-3">{{ __('app.version') }} 1.0</span>
                    <div class="btn-group btn-group-sm" role="group">
                        <a href="#" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-question-circle me-1"></i>
                            {{ __('app.help') }}
                        </a>
                        <a href="#" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-envelope me-1"></i>
                            {{ __('app.contact_us') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
