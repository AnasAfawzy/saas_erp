// Company Settings JavaScript Functions
class CompanySettings {
    constructor() {
        this.form = document.getElementById('companyForm');
        this.apiUrl = window.companyApiUrl || '/settings/company/update';
        this.logoUploadUrl = window.logoUploadUrl || '/settings/company/logo/upload';
        this.logoDeleteUrl = window.logoDeleteUrl || '/settings/company/logo/delete';
        this.initializeEventListeners();
    }

    initializeEventListeners() {
        // Form submission
        if (this.form) {
            this.form.addEventListener('submit', (e) => {
                e.preventDefault();
                this.saveSettings();
            });
        }

        // Logo upload - استخدم اسم الحقل الصحيح
        const logoUpload = document.getElementById('logoInput');
        if (logoUpload) {
            logoUpload.addEventListener('change', (e) => {
                if (e.target.files[0]) {
                    this.uploadLogo(e.target.files[0]);
                }
            });
        }
    }

    async saveSettings() {
        try {
            // Clear previous errors
            if (window.App && App.validation) {
                App.validation.clearErrors(this.form);
            } else {
                document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
                document.querySelectorAll('.invalid-feedback').forEach(el => el.remove());
            }

            // Show loading with translation
            let loadingAlert;
            const loadingText = window.translations?.saving || 'جارِ حفظ البيانات...';
            const loadingTitle = window.translations?.please_wait || 'حفظ البيانات';

            if (window.App && App.alert) {
                loadingAlert = App.alert.loading(loadingText, loadingTitle);
            } else {
                loadingAlert = Swal.fire({
                    title: loadingTitle,
                    text: loadingText,
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showConfirmButton: false,
                    didOpen: () => Swal.showLoading()
                });
            }

            // Collect form data manually to ensure all fields are included
            const formData = this.collectFormData();

            console.log('Sending form data:', Object.fromEntries(formData.entries()));

            // Make API request
            const response = await fetch(this.apiUrl, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const result = await response.json();
            console.log('Server response:', result);

            // Close loading
            if (loadingAlert) {
                Swal.close();
            }

            if (response.ok && result.success) {
                // Success response with translation
                const successMessage = result.message || window.translations?.save_success || 'تم حفظ البيانات بنجاح';
                const successTitle = window.translations?.success || 'نجح الحفظ';

                if (window.App && App.alert) {
                    App.alert.success(successMessage);
                } else {
                    Swal.fire({
                        icon: 'success',
                        title: successTitle,
                        text: successMessage,
                        showConfirmButton: false,
                        timer: 2000,
                        timerProgressBar: true,
                        toast: true,
                        position: 'top-end'
                    });
                }
            } else {
                // Handle validation errors
                if (result.errors) {
                    console.log('Validation errors:', result.errors);
                    if (window.App && App.validation) {
                        App.validation.showErrors(result.errors, this.form);
                    } else {
                        this.showValidationErrors(result.errors);
                    }

                    const firstError = Object.values(result.errors)[0][0];
                    const errorTitle = window.translations?.validation_error || 'خطأ في البيانات';

                    if (window.App && App.alert) {
                        App.alert.error(firstError, errorTitle);
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: errorTitle,
                            text: firstError
                        });
                    }
                } else {
                    // General error
                    const errorMessage = result.message || window.translations?.save_error || 'حدث خطأ أثناء حفظ البيانات';
                    const errorTitle = window.translations?.error || 'خطأ';

                    if (window.App && App.alert) {
                        App.alert.error(errorMessage);
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: errorTitle,
                            text: errorMessage
                        });
                    }
                }
            }

        } catch (error) {
            console.error('Error saving company settings:', error);

            // Close loading if it's still open
            Swal.close();

            const connectionError = window.translations?.connection_error || 'حدث خطأ في الاتصال. يرجى المحاولة مرة أخرى.';
            const connectionErrorTitle = window.translations?.error || 'خطأ في الاتصال';

            if (window.App && App.alert) {
                App.alert.error(connectionError);
            } else {
                Swal.fire({
                    icon: 'error',
                    title: connectionErrorTitle,
                    text: connectionError
                });
            }
        }
    }

    collectFormData() {
        const formData = new FormData();

        // Text inputs - استخدم أسماء الحقول كما هي في النموذج
        const textFields = [
            'company_name', 'company_name_en', 'email',
            'phone', 'website', 'address',
            'commercial_number', 'tax_number'
        ];

        // Map field IDs to their actual names
        const fieldMapping = {
            'companyName': 'company_name',
            'companyNameEn': 'company_name_en',
            'email': 'email',
            'phone': 'phone',
            'website': 'website',
            'address': 'address',
            'commercialNumber': 'commercial_number',
            'taxNumber': 'tax_number'
        };

        // Process mapped fields
        Object.keys(fieldMapping).forEach(fieldId => {
            const element = document.getElementById(fieldId);
            if (element && element.value) {
                formData.append(fieldMapping[fieldId], element.value.trim());
            }
        });

        // Select fields - استخدم أسماء الحقول الصحيحة
        const selectMapping = {
            'currency': 'currency',
            'dateFormat': 'date_format',
            'decimalPlaces': 'decimal_places'
        };

        Object.keys(selectMapping).forEach(fieldId => {
            const element = document.getElementById(fieldId);
            if (element) {
                // Always append the value, even if it's empty or the default option
                formData.append(selectMapping[fieldId], element.value || '');
            }
        });

        // Checkbox fields
        const checkboxMapping = {
            'enableNotifications': 'enable_notifications'
        };

        Object.keys(checkboxMapping).forEach(fieldId => {
            const element = document.getElementById(fieldId);
            if (element) {
                formData.append(checkboxMapping[fieldId], element.checked ? '1' : '0');
            }
        });

        // Add CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (csrfToken) {
            formData.append('_token', csrfToken.getAttribute('content'));
        }

        return formData;
    }

    showValidationErrors(errors) {
        Object.keys(errors).forEach(field => {
            let input = document.getElementById(field) ||
                       document.querySelector(`[name="${field}"]`);

            if (input) {
                input.classList.add('is-invalid');
                const feedback = document.createElement('div');
                feedback.className = 'invalid-feedback';
                feedback.textContent = errors[field][0];
                input.parentNode.appendChild(feedback);
            }
        });
    }

    async uploadLogo(file) {
        try {
            const uploadingTitle = window.translations?.uploading || 'رفع الشعار';
            const uploadingText = window.translations?.please_wait || 'جارِ رفع الشعار...';

            const loadingAlert = Swal.fire({
                title: uploadingTitle,
                text: uploadingText,
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                didOpen: () => Swal.showLoading()
            });

            const formData = new FormData();
            formData.append('logo', file);
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

            const response = await fetch(this.logoUploadUrl, {
                method: 'POST',
                body: formData,
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const result = await response.json();
            Swal.close();

            if (response.ok && result.success) {
                // Update logo preview
                const logoImg = document.getElementById('logoPreview');
                if (logoImg && result.logo_url) {
                    logoImg.src = result.logo_url + '?t=' + Date.now();
                }

                // Show delete button
                const deleteBtn = document.getElementById('deleteLogo');
                if (deleteBtn) {
                    deleteBtn.style.display = 'inline-block';
                }

                const successMessage = result.message || window.translations?.logo_upload_success || 'تم رفع الشعار بنجاح';
                const successTitle = window.translations?.success || 'تم الرفع';

                Swal.fire({
                    icon: 'success',
                    title: successTitle,
                    text: successMessage,
                    showConfirmButton: false,
                    timer: 2000
                });
            } else {
                const errorMessage = result.message || window.translations?.logo_upload_error || 'فشل في رفع الشعار';
                const errorTitle = window.translations?.error || 'فشل الرفع';

                Swal.fire({
                    icon: 'error',
                    title: errorTitle,
                    text: errorMessage
                });
            }

        } catch (error) {
            console.error('Error uploading logo:', error);
            Swal.close();

            const uploadErrorText = window.translations?.logo_upload_error || 'حدث خطأ أثناء رفع الشعار';
            const uploadErrorTitle = window.translations?.error || 'خطأ في الرفع';

            Swal.fire({
                icon: 'error',
                title: uploadErrorTitle,
                text: uploadErrorText
            });
        }
    }

    async deleteLogo() {
        try {
            const confirmTitle = window.translations?.confirm_delete_logo || 'هل تريد حذف الشعار؟';
            const confirmText = window.translations?.cannot_restore_logo || 'لن تتمكن من استعادة الشعار بعد حذفه';
            const confirmButtonText = window.translations?.yes_delete || 'نعم، احذف';
            const cancelButtonText = window.translations?.cancel || 'إلغاء';

            const result = await Swal.fire({
                title: confirmTitle,
                text: confirmText,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: confirmButtonText,
                cancelButtonText: cancelButtonText
            });

            if (result.isConfirmed) {
                const deletingTitle = window.translations?.deleting || 'حذف الشعار';
                const deletingText = window.translations?.please_wait || 'جارِ حذف الشعار...';

                const loadingAlert = Swal.fire({
                    title: deletingTitle,
                    text: deletingText,
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showConfirmButton: false,
                    didOpen: () => Swal.showLoading()
                });

                const response = await fetch(this.logoDeleteUrl, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const deleteResult = await response.json();
                Swal.close();

                if (response.ok && deleteResult.success) {
                    // Update logo preview to default
                    const logoImg = document.getElementById('logoPreview');
                    if (logoImg) {
                        logoImg.src = '/images/default-logo.png';
                    }

                    // Hide delete button
                    const deleteBtn = document.getElementById('deleteLogo');
                    if (deleteBtn) {
                        deleteBtn.style.display = 'none';
                    }

                    const successMessage = deleteResult.message || window.translations?.logo_delete_success || 'تم حذف الشعار بنجاح';
                    const successTitle = window.translations?.success || 'تم الحذف';

                    Swal.fire({
                        icon: 'success',
                        title: successTitle,
                        text: successMessage,
                        showConfirmButton: false,
                        timer: 2000
                    });
                } else {
                    const errorMessage = deleteResult.message || window.translations?.logo_delete_error || 'فشل في حذف الشعار';
                    const errorTitle = window.translations?.error || 'فشل الحذف';

                    Swal.fire({
                        icon: 'error',
                        title: errorTitle,
                        text: errorMessage
                    });
                }
            }

        } catch (error) {
            console.error('Error deleting logo:', error);
            Swal.close();

            const deleteErrorText = window.translations?.logo_delete_error || 'حدث خطأ أثناء حذف الشعار';
            const deleteErrorTitle = window.translations?.error || 'خطأ في الحذف';

            Swal.fire({
                icon: 'error',
                title: deleteErrorTitle,
                text: deleteErrorText
            });
        }
    }
}

// Initialize when DOM is loaded
let companySettingsInstance;
document.addEventListener('DOMContentLoaded', function() {
    companySettingsInstance = new CompanySettings();
    console.log('CompanySettings initialized');
});

// Global functions for backward compatibility
window.saveCompanySettings = function() {
    if (companySettingsInstance) {
        companySettingsInstance.saveSettings();
    }
};

window.uploadLogo = function() {
    const fileInput = document.getElementById('logoInput');
    if (fileInput && fileInput.files[0] && companySettingsInstance) {
        companySettingsInstance.uploadLogo(fileInput.files[0]);
    }
};

window.deleteLogo = function() {
    if (companySettingsInstance) {
        companySettingsInstance.deleteLogo();
    }
};
