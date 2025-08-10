<?php

namespace App\Services;

use App\Models\Warehouse;
use Exception;

class WarehouseService extends BaseService
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct(new Warehouse());
    }

    /**
     * Validate warehouse data
     */
    protected function validateData(array $data, ?int $id = null): array
    {
        try {
            // Check for unique code if provided
            if (!empty($data['code'])) {
                $query = Warehouse::where('code', $data['code']);
                if ($id) {
                    $query->where('id', '!=', $id);
                }
                if ($query->exists()) {
                    return $this->errorResponse(__('app.warehouse_code_already_exists'), [
                        'code' => [__('app.warehouse_code_already_exists')]
                    ], 422);
                }
            }

            // Basic required fields validation
            if (empty($data['name'])) {
                return $this->errorResponse(__('app.warehouse_name_required'), [
                    'name' => [__('app.warehouse_name_required')]
                ], 422);
            }

            if (empty($data['type']) || !in_array($data['type'], ['main', 'branch', 'virtual'])) {
                return $this->errorResponse(__('app.warehouse_type_required'), [
                    'type' => [__('app.warehouse_type_required')]
                ], 422);
            }

            return $this->successResponse('Validation passed');
        } catch (Exception $e) {
            return $this->errorResponse('Validation error: ' . $e->getMessage());
        }
    }

    /**
     * Get all warehouses with search and filters
     */
    public function getAllWarehouses($search = null, $perPage = 15, $filters = [])
    {
        try {
            $query = $this->model->newQuery();

            // Apply search if provided
            if ($search) {
                $query->search($search);
            }

            // Apply filters
            if (isset($filters['type']) && $filters['type'] !== '') {
                $query->byType($filters['type']);
            }

            if (isset($filters['status']) && $filters['status'] !== '') {
                $query->where('is_active', (bool)$filters['status']);
            }

            if (isset($filters['city']) && $filters['city'] !== '') {
                $query->byCity($filters['city']);
            }

            $warehouses = $query->orderBy('created_at', 'desc')->paginate($perPage);

            return $warehouses; // Return paginated data directly for Livewire
        } catch (Exception $e) {
            throw $e; // Let Livewire handle the exception
        }
    }

    /**
     * Get enhanced statistics for warehouses
     */
    public function getStatistics(): array
    {
        try {
            // Get base statistics from parent
            $baseStats = parent::getStatistics();

            if (!$baseStats['success']) {
                return $baseStats;
            }

            // Add warehouse-specific statistics
            $byType = $this->model->selectRaw('type, COUNT(*) as count')
                ->groupBy('type')
                ->pluck('count', 'type')
                ->toArray();

            $enhancedStats = array_merge($baseStats['data'], [
                'main' => $byType['main'] ?? 0,
                'branch' => $byType['branch'] ?? 0,
                'virtual' => $byType['virtual'] ?? 0,
            ]);

            return $this->successResponse('Enhanced warehouse statistics retrieved successfully', $enhancedStats);
        } catch (Exception $e) {
            return $this->errorResponse('Error retrieving warehouse statistics: ' . $e->getMessage());
        }
    }

    /**
     * Get simple statistics for Livewire component
     */
    public function getSimpleStatistics(): array
    {
        try {
            $total = $this->model->count();
            $active = $this->model->active()->count();
            $inactive = $total - $active;

            $byType = $this->model->selectRaw('type, COUNT(*) as count')
                ->groupBy('type')
                ->pluck('count', 'type')
                ->toArray();

            return [
                'total' => $total,
                'active' => $active,
                'inactive' => $inactive,
                'main' => $byType['main'] ?? 0,
                'branch' => $byType['branch'] ?? 0,
                'virtual' => $byType['virtual'] ?? 0,
            ];
        } catch (Exception $e) {
            return [
                'total' => 0,
                'active' => 0,
                'inactive' => 0,
                'main' => 0,
                'branch' => 0,
                'virtual' => 0,
            ];
        }
    }

    /**
     * Create warehouse with code generation
     */
    public function create(array $data): array
    {
        // Generate code if not provided
        if (empty($data['code'])) {
            $data['code'] = $this->generateCode();
        }

        // Set default values
        $data['is_active'] = $data['is_active'] ?? true;
        $data['country'] = $data['country'] ?? 'Saudi Arabia';

        return parent::create($data);
    }

    /**
     * Update warehouse
     */
    public function update(int $id, array $data): array
    {
        // Don't allow code to be empty, generate new one if needed
        if (empty($data['code'])) {
            $data['code'] = $this->generateCode();
        }

        return parent::update($id, $data);
    }

    /**
     * Generate unique warehouse code
     */
    public function generateCode(): string
    {
        $maxAttempts = 100; // Prevent infinite loop
        $attempts = 0;

        do {
            $code = 'WH-' . str_pad(random_int(1, 99999), 5, '0', STR_PAD_LEFT);
            $attempts++;
        } while ($this->model->where('code', $code)->exists() && $attempts < $maxAttempts);

        if ($attempts >= $maxAttempts) {
            // Fallback: use timestamp
            $code = 'WH-' . time();
        }

        return $code;
    }

    /**
     * Get active warehouses only
     */
    public function getActiveWarehouses(): array
    {
        try {
            $warehouses = $this->model->active()->orderBy('name')->get();
            return $this->successResponse('Active warehouses retrieved successfully', $warehouses);
        } catch (Exception $e) {
            return $this->errorResponse('Error retrieving active warehouses: ' . $e->getMessage());
        }
    }

    /**
     * Get warehouses by type
     */
    public function getWarehousesByType(string $type): array
    {
        try {
            if (!in_array($type, ['main', 'branch', 'virtual'])) {
                return $this->errorResponse('Invalid warehouse type', [], 422);
            }

            $warehouses = $this->model->byType($type)->active()->orderBy('name')->get();
            return $this->successResponse("Warehouses of type '{$type}' retrieved successfully", $warehouses);
        } catch (Exception $e) {
            return $this->errorResponse('Error retrieving warehouses by type: ' . $e->getMessage());
        }
    }

    /**
     * Find warehouse by code
     */
    public function findByCode(string $code): array
    {
        try {
            $warehouse = $this->model->where('code', $code)->first();
            if (!$warehouse) {
                return $this->errorResponse('Warehouse not found with this code', [], 404);
            }
            return $this->successResponse('Warehouse found successfully', $warehouse);
        } catch (Exception $e) {
            return $this->errorResponse('Error finding warehouse by code: ' . $e->getMessage());
        }
    }

    /**
     * Get warehouse statistics by city
     */
    public function getStatisticsByCity(): array
    {
        try {
            $byCity = $this->model->selectRaw('city, COUNT(*) as count, SUM(CASE WHEN is_active = 1 THEN 1 ELSE 0 END) as active_count')
                ->whereNotNull('city')
                ->where('city', '!=', '')
                ->groupBy('city')
                ->orderBy('count', 'desc')
                ->get()
                ->toArray();

            return $this->successResponse('City statistics retrieved successfully', $byCity);
        } catch (Exception $e) {
            return $this->errorResponse('Error retrieving city statistics: ' . $e->getMessage());
        }
    }

    /**
     * Search warehouses with advanced filtering
     */
    public function searchWarehouses(string $search, array $filters = []): array
    {
        try {
            $query = $this->model->newQuery();

            // Apply search
            if ($search) {
                $query->search($search);
            }

            // Apply filters
            foreach ($filters as $key => $value) {
                if ($value !== null && $value !== '') {
                    switch ($key) {
                        case 'type':
                            $query->byType($value);
                            break;
                        case 'city':
                            $query->byCity($value);
                            break;
                        case 'status':
                            $query->where('is_active', (bool)$value);
                            break;
                        case 'manager':
                            $query->where('manager_name', 'like', "%{$value}%");
                            break;
                    }
                }
            }

            $warehouses = $query->orderBy('created_at', 'desc')->paginate(15);
            return $this->successResponse('Search completed successfully', $warehouses);
        } catch (Exception $e) {
            return $this->errorResponse('Error searching warehouses: ' . $e->getMessage());
        }
    }
}
