<?php

namespace App\Services;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Exception;

class CustomerService extends BaseService
{
    protected Customer $customer;

    public function __construct(Customer $customer)
    {
        $this->customer = $customer;
    }

    public function create(array $data): array
    {
        try {
            DB::beginTransaction();

            // Generate customer code if not provided
            if (empty($data['code'])) {
                $data['code'] = $this->generateCustomerCode();
            }

            $validationResult = $this->validateData($data);
            if (!$validationResult['success']) {
                DB::rollBack();
                return $validationResult;
            }

            $customer = $this->customer->create($data);

            DB::commit();

            return [
                'success' => true,
                'data' => $customer,
                'message' => __('app.customer_added_successfully')
            ];
        } catch (Exception $e) {
            DB::rollBack();
            return [
                'success' => false,
                'message' => __('app.error_saving_customer'),
                'error' => $e->getMessage()
            ];
        }
    }

    public function update(int $id, array $data): array
    {
        try {
            DB::beginTransaction();

            $customer = $this->customer->find($id);
            if (!$customer) {
                DB::rollBack();
                return [
                    'success' => false,
                    'message' => __('app.customer_not_found')
                ];
            }

            $validationResult = $this->validateData($data, $id);
            if (!$validationResult['success']) {
                DB::rollBack();
                return $validationResult;
            }

            $customer->update($data);

            DB::commit();

            return [
                'success' => true,
                'data' => $customer,
                'message' => __('app.customer_updated_successfully')
            ];
        } catch (Exception $e) {
            DB::rollBack();
            return [
                'success' => false,
                'message' => __('app.error_updating_customer'),
                'error' => $e->getMessage()
            ];
        }
    }

    public function delete(int $id): array
    {
        try {
            DB::beginTransaction();

            $customer = $this->customer->find($id);
            if (!$customer) {
                DB::rollBack();
                return [
                    'success' => false,
                    'message' => __('app.customer_not_found')
                ];
            }

            // Check if customer has orders or invoices (future implementation)
            // if ($customer->orders()->exists() || $customer->invoices()->exists()) {
            //     return [
            //         'success' => false,
            //         'message' => __('app.cannot_delete_customer_with_transactions')
            //     ];
            // }

            $customer->delete();

            DB::commit();

            return [
                'success' => true,
                'message' => __('app.customer_deleted_successfully')
            ];
        } catch (Exception $e) {
            DB::rollBack();
            return [
                'success' => false,
                'message' => __('app.error_deleting_customer'),
                'error' => $e->getMessage()
            ];
        }
    }

    public function findById(int $id): array
    {
        try {
            $customer = $this->customer->find($id);

            if (!$customer) {
                return [
                    'success' => false,
                    'message' => __('app.customer_not_found')
                ];
            }

            return [
                'success' => true,
                'data' => $customer
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => __('app.error_finding_customer'),
                'error' => $e->getMessage()
            ];
        }
    }

    public function generateCustomerCode(): string
    {
        $lastCustomer = $this->customer->orderBy('id', 'desc')->first();
        $nextId = $lastCustomer ? $lastCustomer->id + 1 : 1;
        return 'CUS-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);
    }

    public function toggleCustomerStatus(int $id): array
    {
        try {
            DB::beginTransaction();

            $customer = $this->customer->find($id);
            if (!$customer) {
                DB::rollBack();
                return [
                    'success' => false,
                    'message' => __('app.customer_not_found')
                ];
            }

            $customer->is_active = !$customer->is_active;
            $customer->save();

            DB::commit();

            $message = $customer->is_active
                ? __('app.customer_activated_successfully')
                : __('app.customer_deactivated_successfully');

            return [
                'success' => true,
                'data' => $customer,
                'message' => $message
            ];
        } catch (Exception $e) {
            DB::rollBack();
            return [
                'success' => false,
                'message' => __('app.error_changing_customer_status'),
                'error' => $e->getMessage()
            ];
        }
    }

    public function getCustomerStatistics(): array
    {
        try {
            $total = $this->customer->count();
            $active = $this->customer->active()->count();
            $inactive = $this->customer->inactive()->count();
            $individuals = $this->customer->byType('individual')->count();
            $companies = $this->customer->byType('company')->count();
            $withBalance = $this->customer->withBalance()->count();

            return [
                'total' => $total,
                'active' => $active,
                'inactive' => $inactive,
                'individuals' => $individuals,
                'companies' => $companies,
                'with_balance' => $withBalance,
            ];
        } catch (Exception $e) {
            return [
                'total' => 0,
                'active' => 0,
                'inactive' => 0,
                'individuals' => 0,
                'companies' => 0,
                'with_balance' => 0,
            ];
        }
    }

    public function getActiveCustomers(): Collection
    {
        return $this->customer->active()->orderBy('name')->get();
    }

    public function searchCustomers(string $query, array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $queryBuilder = $this->customer->query();

        // Apply search
        if (!empty($query)) {
            $queryBuilder->search($query);
        }

        // Apply filters
        if (!empty($filters['customer_type'])) {
            $queryBuilder->byType($filters['customer_type']);
        }

        if (isset($filters['is_active']) && $filters['is_active'] !== '') {
            if ($filters['is_active']) {
                $queryBuilder->active();
            } else {
                $queryBuilder->inactive();
            }
        }

        if (!empty($filters['city'])) {
            $queryBuilder->byCity($filters['city']);
        }

        if (!empty($filters['payment_terms'])) {
            $queryBuilder->byPaymentTerms($filters['payment_terms']);
        }

        if (!empty($filters['with_balance'])) {
            $queryBuilder->withBalance();
        }

        return $queryBuilder->orderBy('created_at', 'desc')->paginate($perPage);
    }

    public function validateData(array $data, int $excludeId = null): array
    {
        $rules = [
            'name' => 'required|string|max:255',
            'customer_type' => 'required|in:individual,company',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'national_id' => 'nullable|string|max:50',
            'tax_number' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:1000',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
            'credit_limit' => 'nullable|numeric|min:0',
            'payment_terms' => 'required|in:cash,credit',
            'payment_days' => 'nullable|integer|min:0',
            'discount_percentage' => 'nullable|numeric|min:0|max:100',
            'contact_person' => 'nullable|string|max:255',
            'website' => 'nullable|url|max:255',
            'whatsapp' => 'nullable|string|max:20',
            'notes' => 'nullable|string|max:1000',
            'is_active' => 'boolean',
        ];

        // Add unique validation for phone and email
        if ($excludeId) {
            $rules['phone'] .= '|unique:customers,phone,' . $excludeId;
            if (!empty($data['email'])) {
                $rules['email'] .= '|unique:customers,email,' . $excludeId;
            }
        } else {
            $rules['phone'] .= '|unique:customers,phone';
            if (!empty($data['email'])) {
                $rules['email'] .= '|unique:customers,email';
            }
        }

        $messages = [
            'name.required' => __('app.customer_name_required'),
            'customer_type.required' => __('app.customer_type_required'),
            'phone.required' => __('app.phone_required'),
            'phone.unique' => __('app.phone_exists'),
            'email.email' => __('app.invalid_email'),
            'email.unique' => __('app.email_exists'),
            'payment_terms.required' => __('app.payment_terms_required'),
            'website.url' => __('app.invalid_website'),
        ];

        $validator = validator($data, $rules, $messages);

        if ($validator->fails()) {
            return [
                'success' => false,
                'message' => __('app.validation_error'),
                'errors' => $validator->errors()
            ];
        }

        return [
            'success' => true,
            'message' => __('app.validation_passed')
        ];
    }
}
