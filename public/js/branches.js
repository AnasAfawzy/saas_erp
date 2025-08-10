// Branches Management JavaScript
class BranchManager {
    constructor() {
        this.routes = window.branchRoutes;
        this.translations = window.branchTranslations || {};
        this.currentBranchId = null;
        this.modal = null;
        this.form = null;
        this.initializeComponents();
        this.setupEventListeners();
    }

    initializeComponents() {
        this.modal = new bootstrap.Modal(document.getElementById('branchModal'));
        this.form = document.getElementById('branchForm');
    }

    setupEventListeners() {
        // Form submission
        if (this.form) {
            this.form.addEventListener('submit', (e) => {
                e.preventDefault();
                this.saveBranch();
            });
        }

        // Search functionality
        const searchInput = document.getElementById('searchInput');
        if (searchInput) {
            let searchTimeout;
            searchInput.addEventListener('input', (e) => {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    this.searchBranches(e.target.value);
                }, 300);
            });
        }

        // Modal events
        const modalElement = document.getElementById('branchModal');
        if (modalElement) {
            modalElement.addEventListener('hidden.bs.modal', () => {
                this.resetForm();
            });
        }
    }

    // Open modal for adding new branch
    openAddModal() {
        this.currentBranchId = null;
        this.resetForm();
        document.getElementById('branchModalLabel').textContent = this.translations.add_branch || 'إضافة فرع جديد';
        this.modal.show();
    }

    // Open modal for editing branch
    async editBranch(id) {
        try {
            this.showLoading(true);

            const response = await fetch(this.routes.show.replace(':id', id), {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const result = await response.json();

            if (result.success) {
                this.currentBranchId = id;
                this.populateForm(result.data);
                document.getElementById('branchModalLabel').textContent = this.translations.edit_branch || 'تعديل الفرع';
                this.modal.show();
            } else {
                this.showError(result.message || 'فشل في جلب بيانات الفرع');
            }

        } catch (error) {
            console.error('Error fetching branch:', error);
            this.showError('حدث خطأ أثناء جلب بيانات الفرع');
        } finally {
            this.showLoading(false);
        }
    }

    // Save branch (add or update)
    async saveBranch() {
        try {
            this.clearValidationErrors();
            this.showLoading(true);

            const formData = new FormData(this.form);
            const data = Object.fromEntries(formData.entries());

            // Handle checkbox
            data.is_active = document.getElementById('branchIsActive').checked ? 1 : 0;

            const isUpdate = this.currentBranchId !== null;
            const url = isUpdate
                ? this.routes.update.replace(':id', this.currentBranchId)
                : this.routes.store;

            const method = isUpdate ? 'PUT' : 'POST';

            const response = await fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify(data)
            });

            const result = await response.json();

            if (result.success) {
                const message = isUpdate
                    ? (this.translations.branch_updated_successfully || 'تم تحديث الفرع بنجاح')
                    : (this.translations.branch_added_successfully || 'تم إضافة الفرع بنجاح');

                this.showSuccess(message);
                this.modal.hide();
                this.refreshPage();
            } else {
                if (result.errors) {
                    this.showValidationErrors(result.errors);
                } else {
                    this.showError(result.message || 'حدث خطأ أثناء حفظ البيانات');
                }
            }

        } catch (error) {
            console.error('Error saving branch:', error);
            this.showError('حدث خطأ في الاتصال');
        } finally {
            this.showLoading(false);
        }
    }

    // Delete branch
    async deleteBranch(id) {
        try {
            const confirmTitle = this.translations.confirm_delete || 'تأكيد الحذف';
            const confirmText = this.translations.confirm_delete_branch || 'هل تريد حذف هذا الفرع؟';
            const confirmButton = this.translations.yes_delete || 'نعم، احذف';
            const cancelButton = this.translations.cancel || 'إلغاء';

            const result = await Swal.fire({
                title: confirmTitle,
                text: confirmText,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: confirmButton,
                cancelButtonText: cancelButton
            });

            if (result.isConfirmed) {
                this.showLoading(true);

                const response = await fetch(this.routes.destroy.replace(':id', id), {
                    method: 'DELETE',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const deleteResult = await response.json();

                if (deleteResult.success) {
                    this.showSuccess(deleteResult.message || this.translations.branch_deleted_successfully || 'تم حذف الفرع بنجاح');
                    this.refreshPage();
                } else {
                    this.showError(deleteResult.message || 'فشل في حذف الفرع');
                }
            }

        } catch (error) {
            console.error('Error deleting branch:', error);
            this.showError('حدث خطأ أثناء حذف الفرع');
        } finally {
            this.showLoading(false);
        }
    }

    // Toggle branch status
    async toggleStatus(id) {
        try {
            this.showLoading(true);

            // Find the switch element
            const switchElement = document.getElementById(`statusSwitch${id}`);
            const originalState = switchElement ? switchElement.checked : false;

            const response = await fetch(this.routes.toggleStatus.replace(':id', id), {
                method: 'PATCH',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const result = await response.json();

            if (result.success) {
                // Update the UI immediately
                const row = document.querySelector(`tr[data-id="${id}"]`);
                if (row) {
                    const statusSwitch = row.querySelector(`#statusSwitch${id}`);

                    if (result.data && result.data.is_active !== undefined) {
                        // Update switch only
                        statusSwitch.checked = result.data.is_active;
                        statusSwitch.title = result.data.is_active ?
                            (this.translations.deactivate || 'إلغاء التفعيل') :
                            (this.translations.activate || 'تفعيل');
                    }
                }

                this.showSuccess(result.message || this.translations.branch_status_changed || 'تم تغيير حالة الفرع');
            } else {
                // Revert switch state on error
                if (switchElement) {
                    switchElement.checked = originalState;
                }
                this.showError(result.message || 'فشل في تغيير حالة الفرع');
            }

        } catch (error) {
            console.error('Error toggling status:', error);
            // Revert switch state on error
            const switchElement = document.getElementById(`statusSwitch${id}`);
            if (switchElement) {
                switchElement.checked = !switchElement.checked;
            }
            this.showError('حدث خطأ أثناء تغيير حالة الفرع');
        } finally {
            this.showLoading(false);
        }
    }

    // Search branches
    async searchBranches(query) {
        try {
            const url = new URL(this.routes.data, window.location.origin);
            if (query) {
                url.searchParams.append('search', query);
            }

            const response = await fetch(url, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const result = await response.json();

            if (result.success) {
                this.updateTable(result.data);
            }

        } catch (error) {
            console.error('Error searching branches:', error);
        }
    }

    // Clear search
    clearSearch() {
        document.getElementById('searchInput').value = '';
        this.searchBranches('');
    }

    // Utility methods
    populateForm(branch) {
        document.getElementById('branchName').value = branch.name || '';
        document.getElementById('branchLocation').value = branch.location || '';
        document.getElementById('branchIsActive').checked = branch.is_active;
    }

    resetForm() {
        this.form.reset();
        this.clearValidationErrors();
        document.getElementById('branchIsActive').checked = true;
    }

    showValidationErrors(errors) {
        Object.keys(errors).forEach(field => {
            const input = document.getElementById('branch' + field.charAt(0).toUpperCase() + field.slice(1));
            if (input) {
                input.classList.add('is-invalid');
                const feedback = input.parentNode.querySelector('.invalid-feedback');
                if (feedback) {
                    feedback.textContent = errors[field][0];
                }
            }
        });
    }

    clearValidationErrors() {
        document.querySelectorAll('.is-invalid').forEach(el => {
            el.classList.remove('is-invalid');
        });
        document.querySelectorAll('.invalid-feedback').forEach(el => {
            el.textContent = '';
        });
    }

    updateTable(branches) {
        const tbody = document.getElementById('branchesTableBody');
        if (!tbody) return;

        if (branches.length === 0) {
            tbody.innerHTML = '<tr><td colspan="6" class="text-center">' + (this.translations.no_branches_found || 'لا توجد فروع') + '</td></tr>';
            return;
        }

        tbody.innerHTML = branches.map(branch => `
            <tr data-id="${branch.id}">
                <td>${branch.id}</td>
                <td>${branch.name}</td>
                <td>${branch.location}</td>
                <td>
                    <div class="form-check form-switch mb-0">
                        <input class="form-check-input"
                               type="checkbox"
                               id="statusSwitch${branch.id}"
                               ${branch.is_active ? 'checked' : ''}
                               onchange="toggleStatus(${branch.id})"
                               title="${branch.is_active ? (this.translations.deactivate || 'إلغاء التفعيل') : (this.translations.activate || 'تفعيل')}">
                        <label class="form-check-label" for="statusSwitch${branch.id}"></label>
                    </div>
                </td>
                <td>${new Date(branch.created_at).toLocaleDateString()}</td>
                <td>
                    <div class="btn-group btn-group-sm" role="group">
                        <button type="button" class="btn btn-link text-primary p-1"
                                onclick="branchManager.editBranch(${branch.id})" title="${this.translations.edit || 'تعديل'}">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button type="button" class="btn btn-link text-danger p-1"
                                onclick="branchManager.deleteBranch(${branch.id})" title="${this.translations.delete || 'حذف'}">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `).join('');
    }

    refreshPage() {
        window.location.reload();
    }

    showLoading(show) {
        // Add loading indicator if needed
    }

    showSuccess(message) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'success',
                title: this.translations.success || 'نجح',
                text: message,
                showConfirmButton: false,
                timer: 2000,
                toast: true,
                position: 'top-end'
            });
        }
    }

    showError(message) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'error',
                title: this.translations.error || 'خطأ',
                text: message
            });
        }
    }
}

// Initialize when DOM is loaded
let branchManager;
document.addEventListener('DOMContentLoaded', function() {
    branchManager = new BranchManager();
    console.log('Branch Manager initialized');
});

// Global functions for backward compatibility
window.openAddModal = function() {
    if (branchManager) {
        branchManager.openAddModal();
    }
};

window.editBranch = function(id) {
    if (branchManager) {
        branchManager.editBranch(id);
    }
};

window.deleteBranch = function(id) {
    if (branchManager) {
        branchManager.deleteBranch(id);
    }
};

window.toggleStatus = function(id) {
    if (branchManager) {
        branchManager.toggleStatus(id);
    }
};

window.clearSearch = function() {
    if (branchManager) {
        branchManager.clearSearch();
    }
};
