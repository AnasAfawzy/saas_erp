<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Supplier;
use App\Services\SupplierService;
use Exception;
use Illuminate\Support\Facades\Log;

class SuppliersTable extends Component
{
    use WithPagination;

    // Modal & Form Properties
    public $showModal = false;
    public $editingSuplierId = null;

    // Supplier Form Fields
    public $code = '';
    public $name = '';
    public $supplier_type = 'individual';
    public $phone = '';
    public $email = '';
    public $national_id = '';
    public $tax_number = '';
    public $is_active = true;

    // Address Information
    public $address = '';
    public $city = '';
    public $state = '';
    public $postal_code = '';
    public $country = 'Saudi Arabia';

    // Financial Information
    public $credit_limit = 0;
    public $current_balance = 0;
    public $payment_terms = 'cash';
    public $payment_days = 0;
    public $discount_percentage = 0;

    // Contact Information
    public $contact_person = '';
    public $website = '';
    public $whatsapp = '';
    public $notes = '';

    // Rating Information
    public $rating = 0;
    public $delivery_rating = 0;
    public $quality_rating = 0;
    public $last_supply_date = '';

    // Search and Filter Properties
    public $search = '';
    public $supplierTypeFilter = '';
    public $statusFilter = '';
    public $cityFilter = '';
    public $paymentTermsFilter = '';
    public $ratingFilter = '';
    public $withBalanceFilter = false;

    // Service
    protected $supplierService;

    // Validation Rules
    protected $rules = [
        'name' => 'required|string|max:255',
        'supplier_type' => 'required|in:individual,company',
        'phone' => 'required|string|max:20',
        'email' => 'nullable|email|max:255',
        'national_id' => 'nullable|string|max:50',
        'tax_number' => 'nullable|string|max:50',
        'is_active' => 'boolean',
        'address' => 'nullable|string',
        'city' => 'nullable|string|max:100',
        'state' => 'nullable|string|max:100',
        'postal_code' => 'nullable|string|max:20',
        'country' => 'nullable|string|max:100',
        'credit_limit' => 'nullable|numeric|min:0',
        'current_balance' => 'nullable|numeric',
        'payment_terms' => 'nullable|in:cash,credit,installment',
        'payment_days' => 'nullable|integer|min:0|max:365',
        'discount_percentage' => 'nullable|numeric|min:0|max:100',
        'contact_person' => 'nullable|string|max:255',
        'website' => 'nullable|url|max:255',
        'whatsapp' => 'nullable|string|max:20',
        'notes' => 'nullable|string',
        'rating' => 'nullable|numeric|min:0|max:5',
        'delivery_rating' => 'nullable|numeric|min:0|max:5',
        'quality_rating' => 'nullable|numeric|min:0|max:5',
        'last_supply_date' => 'nullable|date'
    ];

    protected $messages = [
        'name.required' => 'app.supplier_name_required',
        'supplier_type.required' => 'app.supplier_type_required',
        'supplier_type.in' => 'app.supplier_type_in',
        'phone.required' => 'app.phone_required',
        'email.email' => 'app.email_email',
        'credit_limit.min' => 'app.credit_limit_min',
        'payment_terms.in' => 'app.payment_terms_in',
        'payment_days.integer' => 'app.payment_days_integer',
        'payment_days.min' => 'app.payment_days_min',
        'discount_percentage.numeric' => 'app.discount_percentage_numeric',
        'discount_percentage.between' => 'app.discount_percentage_between',
        'website.url' => 'app.website_url'
    ];

    public function boot()
    {
        $this->supplierService = new SupplierService();
    }

    public function mount()
    {
        $this->resetPage();
    }

    public function render()
    {
        $suppliers = $this->getFilteredSuppliers();
        $stats = $this->supplierService->getStatistics();

        return view('livewire.suppliers-table', [
            'suppliers' => $suppliers,
            'stats' => $stats
        ]);
    }

    private function getFilteredSuppliers()
    {
        $query = Supplier::query();

        // Apply search filter
        if ($this->search) {
            $query->search($this->search);
        }

        // Apply type filter
        if ($this->supplierTypeFilter) {
            $query->where('supplier_type', $this->supplierTypeFilter);
        }

        // Apply status filter
        if ($this->statusFilter !== '') {
            $query->where('is_active', (bool)$this->statusFilter);
        }

        // Apply city filter
        if ($this->cityFilter) {
            $query->byCity($this->cityFilter);
        }

        // Apply payment terms filter
        if ($this->paymentTermsFilter) {
            $query->byPaymentTerms($this->paymentTermsFilter);
        }

        // Apply rating filter
        if ($this->ratingFilter) {
            $query->byRating($this->ratingFilter);
        }

        // Apply balance filter
        if ($this->withBalanceFilter) {
            $query->withBalance();
        }

        return $query->orderBy('created_at', 'desc')->paginate(15);
    }

    public function openAddModal()
    {
        $this->resetForm();
        $this->code = $this->supplierService->generateCode();
        $this->showModal = true;
        $this->dispatch('modal-opened');
    }

    public function openEditModal($supplierId)
    {
        try {
            $supplier = Supplier::findOrFail($supplierId);

            $this->editingSuplierId = $supplierId;
            $this->code = $supplier->code;
            $this->name = $supplier->name;
            $this->supplier_type = $supplier->supplier_type;
            $this->phone = $supplier->phone;
            $this->email = $supplier->email ?? '';
            $this->national_id = $supplier->national_id ?? '';
            $this->tax_number = $supplier->tax_number ?? '';
            $this->is_active = $supplier->is_active;

            // Address
            $this->address = $supplier->address ?? '';
            $this->city = $supplier->city ?? '';
            $this->state = $supplier->state ?? '';
            $this->postal_code = $supplier->postal_code ?? '';
            $this->country = $supplier->country ?? 'Saudi Arabia';

            // Financial
            $this->credit_limit = $supplier->credit_limit;
            $this->current_balance = $supplier->current_balance;
            $this->payment_terms = $supplier->payment_terms;
            $this->payment_days = $supplier->payment_days;
            $this->discount_percentage = $supplier->discount_percentage;

            // Contact
            $this->contact_person = $supplier->contact_person ?? '';
            $this->website = $supplier->website ?? '';
            $this->whatsapp = $supplier->whatsapp ?? '';
            $this->notes = $supplier->notes ?? '';

            // Rating
            $this->rating = $supplier->rating;
            $this->delivery_rating = $supplier->delivery_rating;
            $this->quality_rating = $supplier->quality_rating;
            $this->last_supply_date = $supplier->last_supply_date ? $supplier->last_supply_date->format('Y-m-d') : '';

            $this->showModal = true;
            $this->dispatch('modal-opened');
        } catch (Exception $e) {
            $this->dispatch('error-occurred', [
                'message' => __('app.error_loading_supplier')
            ]);
            Log::error('Error loading supplier: ' . $e->getMessage());
        }
    }

    public function save()
    {
        try {
            $this->validate();

            $data = [
                'code' => $this->code,
                'name' => $this->name,
                'supplier_type' => $this->supplier_type,
                'phone' => $this->phone,
                'email' => $this->email ?: null,
                'national_id' => $this->national_id ?: null,
                'tax_number' => $this->tax_number ?: null,
                'is_active' => $this->is_active,
                'address' => $this->address ?: null,
                'city' => $this->city ?: null,
                'state' => $this->state ?: null,
                'postal_code' => $this->postal_code ?: null,
                'country' => $this->country ?: 'Saudi Arabia',
                'credit_limit' => $this->credit_limit ?: 0,
                'current_balance' => $this->current_balance ?: 0,
                'payment_terms' => $this->payment_terms,
                'payment_days' => $this->payment_days ?: 0,
                'discount_percentage' => $this->discount_percentage ?: 0,
                'contact_person' => $this->contact_person ?: null,
                'website' => $this->website ?: null,
                'whatsapp' => $this->whatsapp ?: null,
                'notes' => $this->notes ?: null,
                'rating' => $this->rating ?: 0,
                'delivery_rating' => $this->delivery_rating ?: 0,
                'quality_rating' => $this->quality_rating ?: 0,
                'last_supply_date' => $this->last_supply_date ?: null
            ];

            if ($this->editingSuplierId) {
                $supplier = $this->supplierService->update($this->editingSuplierId, $data);
                $message = __('app.supplier_updated_successfully');
            } else {
                $supplier = $this->supplierService->create($data);
                $message = __('app.supplier_added_successfully');
            }

            $this->dispatch('supplier-saved', [
                'message' => $message
            ]);

            $this->closeModal();
        } catch (Exception $e) {
            $this->dispatch('error-occurred', [
                'message' => $e->getMessage()
            ]);
            Log::error('Error saving supplier: ' . $e->getMessage());
        }
    }

    public function delete($supplierId)
    {
        try {
            $this->supplierService->delete($supplierId);

            $this->dispatch('supplier-deleted', [
                'message' => __('app.supplier_deleted_successfully')
            ]);
        } catch (Exception $e) {
            $this->dispatch('error-occurred', [
                'message' => __('app.error_deleting_supplier')
            ]);
            Log::error('Error deleting supplier: ' . $e->getMessage());
        }
    }

    public function toggleStatus($supplierId)
    {
        try {
            $supplier = $this->supplierService->toggleStatus($supplierId);

            $message = $supplier->is_active
                ? __('app.supplier_activated_successfully')
                : __('app.supplier_deactivated_successfully');

            $this->dispatch('status-toggled', [
                'success' => true,
                'message' => $message
            ]);
        } catch (Exception $e) {
            $this->dispatch('status-toggled', [
                'success' => false,
                'message' => __('app.error_changing_supplier_status')
            ]);
            Log::error('Error toggling supplier status: ' . $e->getMessage());
        }
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
        $this->resetValidation();
    }

    public function clearSearch()
    {
        $this->reset([
            'search',
            'supplierTypeFilter',
            'statusFilter',
            'cityFilter',
            'paymentTermsFilter',
            'ratingFilter',
            'withBalanceFilter'
        ]);
        $this->resetPage();
    }

    private function resetForm()
    {
        $this->reset([
            'editingSuplierId',
            'code',
            'name',
            'supplier_type',
            'phone',
            'email',
            'national_id',
            'tax_number',
            'is_active',
            'address',
            'city',
            'state',
            'postal_code',
            'country',
            'credit_limit',
            'current_balance',
            'payment_terms',
            'payment_days',
            'discount_percentage',
            'contact_person',
            'website',
            'whatsapp',
            'notes',
            'rating',
            'delivery_rating',
            'quality_rating',
            'last_supply_date'
        ]);

        // Set defaults
        $this->supplier_type = 'individual';
        $this->is_active = true;
        $this->country = 'Saudi Arabia';
        $this->payment_terms = 'cash';
    }

    // Listen for search updates to reset pagination
    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedSupplierTypeFilter()
    {
        $this->resetPage();
    }

    public function updatedStatusFilter()
    {
        $this->resetPage();
    }

    public function updatedCityFilter()
    {
        $this->resetPage();
    }

    public function updatedPaymentTermsFilter()
    {
        $this->resetPage();
    }

    public function updatedRatingFilter()
    {
        $this->resetPage();
    }

    public function updatedWithBalanceFilter()
    {
        $this->resetPage();
    }
}
