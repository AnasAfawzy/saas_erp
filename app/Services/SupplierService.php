<?php

namespace App\Services;

use App\Models\Supplier;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SupplierService
{
    /**
     * Generate unique supplier code
     */
    public function generateCode(): string
    {
        $latestSupplier = Supplier::latest('id')->first();
        $nextNumber = $latestSupplier ? ($latestSupplier->id + 1) : 1;

        return 'SUP-' . str_pad($nextNumber, 6, '0', STR_PAD_LEFT);
    }

    /**
     * Get suppliers statistics
     */
    public function getStatistics(): array
    {
        try {
            $total = Supplier::count();
            $active = Supplier::active()->count();
            $individual = Supplier::individual()->count();
            $company = Supplier::company()->count();
            $withBalance = Supplier::withBalance()->count();

            return [
                'total' => $total,
                'active' => $active,
                'inactive' => $total - $active,
                'individual' => $individual,
                'companies' => $company,
                'with_balance' => $withBalance,
                'avg_rating' => Supplier::where('rating', '>', 0)->avg('rating') ?: 0
            ];
        } catch (Exception $e) {
            Log::error('Error getting supplier statistics: ' . $e->getMessage());
            return [
                'total' => 0,
                'active' => 0,
                'inactive' => 0,
                'individual' => 0,
                'companies' => 0,
                'with_balance' => 0,
                'avg_rating' => 0
            ];
        }
    }

    /**
     * Create new supplier
     */
    public function create(array $data)
    {
        try {
            DB::beginTransaction();

            // Generate code if not provided
            if (!isset($data['code']) || empty($data['code'])) {
                $data['code'] = $this->generateCode();
            }

            // Validate required fields
            $this->validateSupplierData($data);

            $supplier = Supplier::create($data);

            DB::commit();

            Log::info('Supplier created successfully', ['supplier_id' => $supplier->id]);
            return $supplier;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error creating supplier: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Update supplier
     */
    public function update($id, array $data)
    {
        try {
            DB::beginTransaction();

            $supplier = Supplier::findOrFail($id);

            // Validate data
            $this->validateSupplierData($data, $id);

            $supplier->update($data);

            DB::commit();

            Log::info('Supplier updated successfully', ['supplier_id' => $id]);
            return $supplier->fresh();
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error updating supplier: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Delete supplier
     */
    public function delete($id): bool
    {
        try {
            DB::beginTransaction();

            $supplier = Supplier::findOrFail($id);

            // Check if supplier has transactions (you can add this check later)
            // if ($this->hasTransactions($supplier)) {
            //     throw new Exception('Cannot delete supplier with existing transactions');
            // }

            $deleted = $supplier->delete();

            DB::commit();

            Log::info('Supplier deleted successfully', ['supplier_id' => $id]);
            return $deleted;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error deleting supplier: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Toggle supplier status
     */
    public function toggleStatus($id)
    {
        try {
            $supplier = Supplier::findOrFail($id);
            $supplier->is_active = !$supplier->is_active;
            $supplier->save();

            $status = $supplier->is_active ? 'activated' : 'deactivated';
            Log::info("Supplier {$status} successfully", ['supplier_id' => $id]);

            return $supplier;
        } catch (Exception $e) {
            Log::error('Error toggling supplier status: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Update supplier rating
     */
    public function updateRating(int $id, array $ratings): Supplier
    {
        try {
            $supplier = Supplier::findOrFail($id);

            if (isset($ratings['overall'])) {
                $supplier->rating = $ratings['overall'];
            }

            if (isset($ratings['delivery'])) {
                $supplier->delivery_rating = $ratings['delivery'];
            }

            if (isset($ratings['quality'])) {
                $supplier->quality_rating = $ratings['quality'];
            }

            $supplier->save();

            Log::info('Supplier rating updated successfully', ['supplier_id' => $id]);
            return $supplier;
        } catch (Exception $e) {
            Log::error('Error updating supplier rating: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Get active suppliers for dropdowns
     */
    public function getActiveSuppliers()
    {
        try {
            return Supplier::active()
                ->select('id', 'name', 'code')
                ->orderBy('name')
                ->get();
        } catch (Exception $e) {
            Log::error('Error getting active suppliers: ' . $e->getMessage());
            return collect();
        }
    }

    /**
     * Get suppliers with balance
     */
    public function getSuppliersWithBalance()
    {
        try {
            return Supplier::withBalance()
                ->with([])
                ->orderBy('current_balance', 'desc')
                ->get();
        } catch (Exception $e) {
            Log::error('Error getting suppliers with balance: ' . $e->getMessage());
            return collect();
        }
    }

    /**
     * Validate supplier data
     */
    private function validateSupplierData(array $data, ?int $excludeId = null): void
    {
        // Check unique code
        if (isset($data['code'])) {
            $query = Supplier::where('code', $data['code']);
            if ($excludeId) {
                $query->where('id', '!=', $excludeId);
            }
            if ($query->exists()) {
                throw new Exception(__('app.supplier_code_unique'));
            }
        }

        // Check unique phone
        if (isset($data['phone'])) {
            $query = Supplier::where('phone', $data['phone']);
            if ($excludeId) {
                $query->where('id', '!=', $excludeId);
            }
            if ($query->exists()) {
                throw new Exception(__('app.phone_unique'));
            }
        }

        // Check unique email if provided
        if (!empty($data['email'])) {
            $query = Supplier::where('email', $data['email']);
            if ($excludeId) {
                $query->where('id', '!=', $excludeId);
            }
            if ($query->exists()) {
                throw new Exception(__('app.email_unique'));
            }
        }

        // Check unique national_id if provided
        if (!empty($data['national_id'])) {
            $query = Supplier::where('national_id', $data['national_id']);
            if ($excludeId) {
                $query->where('id', '!=', $excludeId);
            }
            if ($query->exists()) {
                throw new Exception(__('app.national_id_unique'));
            }
        }

        // Check unique tax_number if provided
        if (!empty($data['tax_number'])) {
            $query = Supplier::where('tax_number', $data['tax_number']);
            if ($excludeId) {
                $query->where('id', '!=', $excludeId);
            }
            if ($query->exists()) {
                throw new Exception(__('app.tax_number_unique'));
            }
        }
    }

    /**
     * Search suppliers with filters
     */
    public function searchSuppliers(array $filters = [], int $perPage = 15)
    {
        try {
            $query = Supplier::query();

            // Apply search filter
            if (!empty($filters['search'])) {
                $query->search($filters['search']);
            }

            // Apply type filter
            if (!empty($filters['supplier_type'])) {
                $query->where('supplier_type', $filters['supplier_type']);
            }

            // Apply status filter
            if (isset($filters['status']) && $filters['status'] !== '') {
                $query->where('is_active', (bool)$filters['status']);
            }

            // Apply city filter
            if (!empty($filters['city'])) {
                $query->byCity($filters['city']);
            }

            // Apply payment terms filter
            if (!empty($filters['payment_terms'])) {
                $query->byPaymentTerms($filters['payment_terms']);
            }

            // Apply rating filter
            if (!empty($filters['rating'])) {
                $query->byRating($filters['rating']);
            }

            // Apply balance filter
            if (!empty($filters['with_balance'])) {
                $query->withBalance();
            }

            return $query->orderBy('created_at', 'desc')
                ->paginate($perPage);
        } catch (Exception $e) {
            Log::error('Error searching suppliers: ' . $e->getMessage());
            return Supplier::paginate($perPage);
        }
    }
}
