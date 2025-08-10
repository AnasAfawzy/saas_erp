<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Exception;

abstract class BaseService
{
    protected $model;

    /**
     * Constructor
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * Get all records
     */
    public function all(): array
    {
        try {
            $records = $this->model->all();
            return $this->successResponse('Records retrieved successfully', $records);
        } catch (Exception $e) {
            return $this->errorResponse('Error retrieving records: ' . $e->getMessage());
        }
    }

    /**
     * Find a record by ID
     */
    public function find(int $id): array
    {
        try {
            $record = $this->model->find($id);
            if (!$record) {
                return $this->errorResponse('Record not found', [], 404);
            }
            return $this->successResponse('Record found successfully', $record);
        } catch (Exception $e) {
            return $this->errorResponse('Error finding record: ' . $e->getMessage());
        }
    }

    /**
     * Find a record by ID (alias for find)
     */
    public function findById(int $id): array
    {
        return $this->find($id);
    }

    /**
     * Find a record by ID or fail
     */
    public function findOrFail(int $id): array
    {
        try {
            $record = $this->model->findOrFail($id);
            return $this->successResponse('Record found successfully', $record);
        } catch (Exception $e) {
            return $this->errorResponse('Record not found: ' . $e->getMessage(), [], 404);
        }
    }

    /**
     * Perform validation if validation method exists
     */
    protected function performValidation(array $data, ?int $id = null): array
    {
        if (method_exists($this, 'validateData')) {
            return call_user_func([$this, 'validateData'], $data, $id);
        }

        // Return success if no validation method is defined
        return $this->successResponse('No validation required');
    }

    /**
     * Create a new record
     */
    public function create(array $data): array
    {
        try {
            // Validate data if validation method exists in child service
            $validationResult = $this->performValidation($data);
            if (!$validationResult['success']) {
                return $validationResult;
            }

            $record = $this->model->create($data);
            return $this->successResponse('Record created successfully', $record, 201);
        } catch (Exception $e) {
            return $this->errorResponse('Error creating record: ' . $e->getMessage());
        }
    }

    /**
     * Update a record
     */
    public function update(int $id, array $data): array
    {
        try {
            // Validate data if validation method exists in child service
            $validationResult = $this->performValidation($data, $id);
            if (!$validationResult['success']) {
                return $validationResult;
            }

            $findResult = $this->findOrFail($id);
            if (!$findResult['success']) {
                return $findResult;
            }

            $record = $findResult['data'];
            $record->update($data);
            $record->refresh();

            return $this->successResponse('Record updated successfully', $record);
        } catch (Exception $e) {
            return $this->errorResponse('Error updating record: ' . $e->getMessage());
        }
    }

    /**
     * Delete a record
     */
    public function delete(int $id): array
    {
        try {
            $findResult = $this->findOrFail($id);
            if (!$findResult['success']) {
                return $findResult;
            }

            $record = $findResult['data'];
            $deleted = $record->delete();

            if ($deleted) {
                return $this->successResponse('Record deleted successfully');
            } else {
                return $this->errorResponse('Failed to delete record');
            }
        } catch (Exception $e) {
            return $this->errorResponse('Error deleting record: ' . $e->getMessage());
        }
    }

    /**
     * Get paginated records
     */
    public function paginate(int $perPage = 15): array
    {
        try {
            $records = $this->model->paginate($perPage);
            return $this->successResponse('Records paginated successfully', $records);
        } catch (Exception $e) {
            return $this->errorResponse('Error paginating records: ' . $e->getMessage());
        }
    }

    /**
     * Count total records
     */
    public function count(): array
    {
        try {
            $count = $this->model->count();
            return $this->successResponse('Records counted successfully', ['count' => $count]);
        } catch (Exception $e) {
            return $this->errorResponse('Error counting records: ' . $e->getMessage());
        }
    }

    /**
     * Check if record exists
     */
    public function exists(int $id): array
    {
        try {
            $exists = $this->model->where('id', $id)->exists();
            return $this->successResponse('Existence check completed', ['exists' => $exists]);
        } catch (Exception $e) {
            return $this->errorResponse('Error checking record existence: ' . $e->getMessage());
        }
    }

    /**
     * Search records
     */
    public function search(string $query, array $fields = ['name']): array
    {
        try {
            $queryBuilder = $this->model->newQuery();

            foreach ($fields as $field) {
                $queryBuilder->orWhere($field, 'LIKE', "%{$query}%");
            }

            $records = $queryBuilder->get();
            return $this->successResponse('Search completed successfully', $records);
        } catch (Exception $e) {
            return $this->errorResponse('Error searching records: ' . $e->getMessage());
        }
    }

    /**
     * Get statistics
     */
    public function getStatistics(): array
    {
        try {
            $stats = [
                'total' => $this->model->count(),
            ];

            // Add active/inactive counts if model has is_active field
            if ($this->model->getConnection()->getSchemaBuilder()->hasColumn($this->model->getTable(), 'is_active')) {
                $stats['active'] = $this->model->where('is_active', true)->count();
                $stats['inactive'] = $this->model->where('is_active', false)->count();
            }

            return $this->successResponse('Statistics retrieved successfully', $stats);
        } catch (Exception $e) {
            return $this->errorResponse('Error retrieving statistics: ' . $e->getMessage());
        }
    }

    /**
     * Toggle status (for models with is_active field)
     */
    public function toggleStatus(int $id): array
    {
        try {
            $findResult = $this->findOrFail($id);
            if (!$findResult['success']) {
                return $findResult;
            }

            $record = $findResult['data'];

            if (!$this->model->getConnection()->getSchemaBuilder()->hasColumn($this->model->getTable(), 'is_active')) {
                return $this->errorResponse('This model does not support status toggling');
            }

            $wasActive = $record->is_active;
            $record->is_active = !$record->is_active;
            $record->save();

            // Get model class name for specific messages
            $modelClass = class_basename($this->model);
            $modelName = strtolower($modelClass);

            // Generate specific message based on model and action
            $messageKey = $record->is_active ? "{$modelName}_activated" : "{$modelName}_deactivated";

            // Fallback to generic message if specific one doesn't exist
            $message = __("app.{$messageKey}");
            if ($message === "app.{$messageKey}") {
                $statusText = $record->is_active ? __('app.activated') : __('app.deactivated');
                $message = $modelClass . ' ' . $statusText . ' ' . __('app.successfully');
            }

            return $this->successResponse($message, $record);
        } catch (Exception $e) {
            return $this->errorResponse('Error toggling status: ' . $e->getMessage());
        }
    }

    /**
     * Get active records only
     */
    public function getActive(): array
    {
        try {
            if (!$this->model->getConnection()->getSchemaBuilder()->hasColumn($this->model->getTable(), 'is_active')) {
                return $this->all();
            }

            $records = $this->model->where('is_active', true)->get();
            return $this->successResponse('Active records retrieved successfully', $records);
        } catch (Exception $e) {
            return $this->errorResponse('Error retrieving active records: ' . $e->getMessage());
        }
    }

    /**
     * Get inactive records only
     */
    public function getInactive(): array
    {
        try {
            if (!$this->model->getConnection()->getSchemaBuilder()->hasColumn($this->model->getTable(), 'is_active')) {
                return $this->errorResponse('This model does not support status filtering');
            }

            $records = $this->model->where('is_active', false)->get();
            return $this->successResponse('Inactive records retrieved successfully', $records);
        } catch (Exception $e) {
            return $this->errorResponse('Error retrieving inactive records: ' . $e->getMessage());
        }
    }

    /**
     * Bulk operations - Create multiple records
     */
    public function createMany(array $dataArray): array
    {
        try {
            $created = [];
            $errors = [];

            foreach ($dataArray as $index => $data) {
                $result = $this->create($data);
                if ($result['success']) {
                    $created[] = $result['data'];
                } else {
                    $errors["item_{$index}"] = $result['message'];
                }
            }

            if (empty($errors)) {
                return $this->successResponse('All records created successfully', $created, 201);
            } else if (empty($created)) {
                return $this->errorResponse('Failed to create any records', $errors);
            } else {
                return $this->successResponse(
                    'Some records created successfully',
                    ['created' => $created, 'errors' => $errors],
                    207 // Multi-Status
                );
            }
        } catch (Exception $e) {
            return $this->errorResponse('Error in bulk creation: ' . $e->getMessage());
        }
    }

    /**
     * Bulk delete by IDs
     */
    public function deleteMany(array $ids): array
    {
        try {
            $deleted = 0;
            $errors = [];

            foreach ($ids as $id) {
                $result = $this->delete($id);
                if ($result['success']) {
                    $deleted++;
                } else {
                    $errors["id_{$id}"] = $result['message'];
                }
            }

            if (empty($errors)) {
                return $this->successResponse("Successfully deleted {$deleted} records", ['deleted_count' => $deleted]);
            } else {
                return $this->successResponse(
                    "Deleted {$deleted} records with some errors",
                    ['deleted_count' => $deleted, 'errors' => $errors],
                    207 // Multi-Status
                );
            }
        } catch (Exception $e) {
            return $this->errorResponse('Error in bulk deletion: ' . $e->getMessage());
        }
    }

    /**
     * Check if any data exists in the model
     */
    public function hasData(): array
    {
        try {
            $hasData = $this->model->exists();
            return $this->successResponse('Data existence check completed', ['has_data' => $hasData]);
        } catch (Exception $e) {
            return $this->errorResponse('Error checking data existence: ' . $e->getMessage());
        }
    }

    /**
     * Get latest records
     */
    public function getLatest(int $limit = 10): array
    {
        try {
            $records = $this->model->latest()->limit($limit)->get();
            return $this->successResponse('Latest records retrieved successfully', $records);
        } catch (Exception $e) {
            return $this->errorResponse('Error retrieving latest records: ' . $e->getMessage());
        }
    }

    /**
     * Success response format
     */
    protected function successResponse(string $message, $data = null, int $status = 200): array
    {
        $response = [
            'success' => true,
            'message' => $message,
            'status' => $status
        ];

        if ($data !== null) {
            $response['data'] = $data;
        }

        return $response;
    }

    /**
     * Error response format
     */
    protected function errorResponse(string $message, array $errors = [], int $status = 500): array
    {
        return [
            'success' => false,
            'message' => $message,
            'status' => $status,
            'errors' => $errors
        ];
    }

    /**
     * Get the model instance
     */
    public function getModel(): Model
    {
        return $this->model;
    }
}
