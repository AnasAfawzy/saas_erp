<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Services\CustomerService;
use Illuminate\Contracts\View\View;

class CustomersTable extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $search = '';
    public $customerTypeFilter = '';
    public $statusFilter = '';
    public $cityFilter = '';
    public $paymentTermsFilter = '';
    public $withBalanceFilter = false;

    // Modal and form properties
    public $showModal = false;
    public $editingCustomerId = null;

    // Form fields - Basic Info
    public $code = '';
    public $name = '';
    public $customer_type = 'individual';
    public $phone = '';
    public $email = '';
    public $national_id = '';
    public $tax_number = '';
    public $is_active = true;

    // Form fields - Address
    public $address = '';
    public $city = '';
    public $state = '';
    public $postal_code = '';
    public $country = 'Saudi Arabia';

    // Form fields - Financial
    public $credit_limit = 0;
    public $payment_terms = 'cash';
    public $payment_days = 0;
    public $discount_percentage = 0;

    // Form fields - Contact
    public $contact_person = '';
    public $website = '';
    public $whatsapp = '';
    public $notes = '';

    protected $customerService;

    public function boot(CustomerService $customerService)
    {
        $this->customerService = $customerService;
    }

    protected function getCustomerService(): CustomerService
    {
        return $this->customerService ?: app(CustomerService::class);
    }

    public function mount()
    {
        $this->resetPage();
    }

    public function render(): View
    {
        $customers = $this->getCustomerService()->searchCustomers($this->search, [
            'customer_type' => $this->customerTypeFilter,
            'is_active' => $this->statusFilter !== '' ? (bool) $this->statusFilter : null,
            'city' => $this->cityFilter,
            'payment_terms' => $this->paymentTermsFilter,
            'with_balance' => $this->withBalanceFilter,
        ], 15);

        $stats = $this->getCustomerService()->getCustomerStatistics();

        return view('livewire.customers-table', [
            'customers' => $customers,
            'stats' => $stats,
        ]);
    }

    // Search and filter methods
    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedCustomerTypeFilter()
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

    public function updatedWithBalanceFilter()
    {
        $this->resetPage();
    }

    public function clearSearch()
    {
        $this->search = '';
        $this->customerTypeFilter = '';
        $this->statusFilter = '';
        $this->cityFilter = '';
        $this->paymentTermsFilter = '';
        $this->withBalanceFilter = false;
        $this->resetPage();
    }

    // Modal methods
    public function openAddModal()
    {
        $this->resetForm();
        $this->editingCustomerId = null;
        $this->code = $this->getCustomerService()->generateCustomerCode();
        $this->showModal = true;
    }

    public function openEditModal($customerId)
    {
        try {
            $result = $this->getCustomerService()->findById($customerId);
            if (!$result['success']) {
                $this->dispatch('error-occurred', [
                    'success' => false,
                    'message' => $result['message']
                ]);
                return;
            }

            $customer = $result['data'];
            $this->editingCustomerId = $customerId;

            // Basic Info
            $this->code = $customer->code;
            $this->name = $customer->name;
            $this->customer_type = $customer->customer_type;
            $this->phone = $customer->phone;
            $this->email = $customer->email ?? '';
            $this->national_id = $customer->national_id ?? '';
            $this->tax_number = $customer->tax_number ?? '';
            $this->is_active = $customer->is_active;

            // Address
            $this->address = $customer->address ?? '';
            $this->city = $customer->city ?? '';
            $this->state = $customer->state ?? '';
            $this->postal_code = $customer->postal_code ?? '';
            $this->country = $customer->country ?? 'Saudi Arabia';

            // Financial
            $this->credit_limit = $customer->credit_limit ?? 0;
            $this->payment_terms = $customer->payment_terms;
            $this->payment_days = $customer->payment_days ?? 0;
            $this->discount_percentage = $customer->discount_percentage ?? 0;

            // Contact
            $this->contact_person = $customer->contact_person ?? '';
            $this->website = $customer->website ?? '';
            $this->whatsapp = $customer->whatsapp ?? '';
            $this->notes = $customer->notes ?? '';

            $this->showModal = true;
        } catch (\Exception $e) {
            $this->dispatch('error-occurred', [
                'success' => false,
                'message' => __('app.error_loading_customer')
            ]);
        }
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
        $this->resetValidation();
    }

    private function resetForm()
    {
        // Basic Info
        $this->code = '';
        $this->name = '';
        $this->customer_type = 'individual';
        $this->phone = '';
        $this->email = '';
        $this->national_id = '';
        $this->tax_number = '';
        $this->is_active = true;

        // Address
        $this->address = '';
        $this->city = '';
        $this->state = '';
        $this->postal_code = '';
        $this->country = 'Saudi Arabia';

        // Financial
        $this->credit_limit = 0;
        $this->payment_terms = 'cash';
        $this->payment_days = 0;
        $this->discount_percentage = 0;

        // Contact
        $this->contact_person = '';
        $this->website = '';
        $this->whatsapp = '';
        $this->notes = '';
    }

    public function save()
    {
        $data = [
            'code' => $this->code,
            'name' => $this->name,
            'customer_type' => $this->customer_type,
            'phone' => $this->phone,
            'email' => $this->email,
            'national_id' => $this->national_id,
            'tax_number' => $this->tax_number,
            'address' => $this->address,
            'city' => $this->city,
            'state' => $this->state,
            'postal_code' => $this->postal_code,
            'country' => $this->country,
            'credit_limit' => $this->credit_limit,
            'payment_terms' => $this->payment_terms,
            'payment_days' => $this->payment_days,
            'discount_percentage' => $this->discount_percentage,
            'contact_person' => $this->contact_person,
            'website' => $this->website,
            'whatsapp' => $this->whatsapp,
            'notes' => $this->notes,
            'is_active' => $this->is_active,
        ];

        try {
            if ($this->editingCustomerId) {
                $result = $this->getCustomerService()->update($this->editingCustomerId, $data);
                $message = __('app.customer_updated_successfully');
            } else {
                $result = $this->getCustomerService()->create($data);
                $message = __('app.customer_added_successfully');
            }

            if ($result['success']) {
                $this->closeModal();
                $this->dispatch('customer-saved', [
                    'success' => true,
                    'message' => $message
                ]);
            } else {
                if (isset($result['errors'])) {
                    $this->setErrorBag($result['errors']);
                } else {
                    $this->dispatch('error-occurred', [
                        'success' => false,
                        'message' => $result['message']
                    ]);
                }
            }
        } catch (\Exception $e) {
            $this->dispatch('error-occurred', [
                'success' => false,
                'message' => __('app.error_saving_customer')
            ]);
        }
    }

    public function delete($customerId)
    {
        try {
            $result = $this->getCustomerService()->delete($customerId);

            if ($result['success']) {
                $this->dispatch('customer-deleted', [
                    'success' => true,
                    'message' => __('app.customer_deleted_successfully')
                ]);
            } else {
                $this->dispatch('error-occurred', [
                    'success' => false,
                    'message' => $result['message']
                ]);
            }
        } catch (\Exception $e) {
            $this->dispatch('error-occurred', [
                'success' => false,
                'message' => __('app.error_deleting_customer')
            ]);
        }
    }

    public function toggleStatus($customerId)
    {
        try {
            $result = $this->getCustomerService()->toggleCustomerStatus($customerId);

            $this->dispatch('status-toggled', [
                'success' => $result['success'],
                'message' => $result['message']
            ]);
        } catch (\Exception $e) {
            $this->dispatch('error-occurred', [
                'success' => false,
                'message' => __('app.error_changing_customer_status')
            ]);
        }
    }
}
