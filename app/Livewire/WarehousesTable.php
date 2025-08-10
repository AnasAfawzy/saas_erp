<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Warehouse;
use App\Services\WarehouseService;
use Exception;
use Illuminate\Support\Facades\Log;

class WarehousesTable extends Component
{
    use WithPagination;

    // Modal & Form Properties
    public $showModal = false;
    public $editingWarehouseId = null;

    // Form Fields - بسيطة جداً
    public $code = '';
    public $name = '';
    public $type = 'main';
    public $address = '';
    public $city = '';
    public $state = '';
    public $country = 'Saudi Arabia';
    public $phone = '';
    public $manager_name = '';
    public $manager_phone = '';
    public $manager_email = '';
    public $description = '';
    public $notes = '';
    public $is_active = true;

    // Search and Filters - بسيطة جداً
    public $search = '';
    public $typeFilter = '';
    public $statusFilter = '';
    public $cityFilter = '';

    // Service
    protected $warehouseService;

    // Validation Rules
    protected $rules = [
        'name' => 'required|string|max:255',
        'type' => 'required|in:main,branch,virtual',
        'phone' => 'nullable|string|max:20',
        'manager_phone' => 'nullable|string|max:20',
        'manager_email' => 'nullable|email|max:255',
        'city' => 'nullable|string|max:100',
        'state' => 'nullable|string|max:100',
        'country' => 'nullable|string|max:100',
        'address' => 'nullable|string|max:500',
        'description' => 'nullable|string|max:1000',
        'notes' => 'nullable|string|max:1000',
    ];

    protected $messages = [
        'name.required' => 'app.warehouse_name_required',
        'type.required' => 'app.warehouse_type_required',
        'type.in' => 'app.warehouse_type_in',
        'manager_email.email' => 'app.email_email',
    ];

    public function boot()
    {
        $this->warehouseService = app(WarehouseService::class);
    }

    public function mount()
    {
        $this->resetPage();
    }

    public function render()
    {
        $filters = [
            'type' => $this->typeFilter,
            'status' => $this->statusFilter,
            'city' => $this->cityFilter,
        ];

        $warehouses = $this->warehouseService->getAllWarehouses($this->search, 15, $filters);
        $stats = $this->warehouseService->getSimpleStatistics(); // Use simple statistics for Livewire

        return view('livewire.warehouses-table', [
            'warehouses' => $warehouses,
            'stats' => $stats
        ]);
    }
    public function openAddModal()
    {
        $this->resetForm();
        $this->code = $this->warehouseService->generateCode();
        $this->showModal = true;
        $this->dispatch('modal-opened');
    }

    public function openEditModal($warehouseId)
    {
        try {
            $warehouse = Warehouse::findOrFail($warehouseId);

            $this->editingWarehouseId = $warehouseId;
            $this->code = $warehouse->code;
            $this->name = $warehouse->name;
            $this->type = $warehouse->type;
            $this->address = $warehouse->address ?? '';
            $this->city = $warehouse->city ?? '';
            $this->state = $warehouse->state ?? '';
            $this->country = $warehouse->country ?? 'Saudi Arabia';
            $this->phone = $warehouse->phone ?? '';
            $this->manager_name = $warehouse->manager_name ?? '';
            $this->manager_phone = $warehouse->manager_phone ?? '';
            $this->manager_email = $warehouse->manager_email ?? '';
            $this->description = $warehouse->description ?? '';
            $this->notes = $warehouse->notes ?? '';
            $this->is_active = $warehouse->is_active;

            $this->showModal = true;
            $this->dispatch('modal-opened');
        } catch (Exception $e) {
            $this->dispatch('error-occurred', [
                'message' => __('app.error_loading_warehouse')
            ]);
            Log::error('Error loading warehouse: ' . $e->getMessage());
        }
    }

    public function save()
    {
        try {
            $this->validate();

            $data = [
                'code' => $this->code,
                'name' => $this->name,
                'type' => $this->type,
                'address' => $this->address ?: null,
                'city' => $this->city ?: null,
                'state' => $this->state ?: null,
                'country' => $this->country ?: 'Saudi Arabia',
                'phone' => $this->phone ?: null,
                'manager_name' => $this->manager_name ?: null,
                'manager_phone' => $this->manager_phone ?: null,
                'manager_email' => $this->manager_email ?: null,
                'description' => $this->description ?: null,
                'notes' => $this->notes ?: null,
                'is_active' => $this->is_active
            ];

            if ($this->editingWarehouseId) {
                $result = $this->warehouseService->update($this->editingWarehouseId, $data);
                $message = $result['success']
                    ? __('app.warehouse_updated_successfully')
                    : $result['message'];
            } else {
                $result = $this->warehouseService->create($data);
                $message = $result['success']
                    ? __('app.warehouse_added_successfully')
                    : $result['message'];
            }

            if ($result['success']) {
                $this->dispatch('warehouse-saved', [
                    'message' => $message
                ]);
                $this->closeModal();
            } else {
                $this->dispatch('error-occurred', [
                    'message' => $message
                ]);
            }
        } catch (Exception $e) {
            $this->dispatch('error-occurred', [
                'message' => $e->getMessage()
            ]);
            Log::error('Error saving warehouse: ' . $e->getMessage());
        }
    }

    public function delete($warehouseId)
    {
        try {
            $result = $this->warehouseService->delete($warehouseId);

            if ($result['success']) {
                $this->dispatch('warehouse-deleted', [
                    'message' => __('app.warehouse_deleted_successfully')
                ]);
            } else {
                $this->dispatch('error-occurred', [
                    'message' => $result['message']
                ]);
            }
        } catch (Exception $e) {
            $this->dispatch('error-occurred', [
                'message' => __('app.error_deleting_warehouse')
            ]);
            Log::error('Error deleting warehouse: ' . $e->getMessage());
        }
    }

    public function toggleStatus($warehouseId)
    {
        try {
            $result = $this->warehouseService->toggleStatus($warehouseId);

            if ($result['success']) {
                $this->dispatch('status-toggled', [
                    'success' => true,
                    'message' => $result['message']
                ]);
            } else {
                $this->dispatch('status-toggled', [
                    'success' => false,
                    'message' => $result['message']
                ]);
            }
        } catch (Exception $e) {
            $this->dispatch('status-toggled', [
                'success' => false,
                'message' => __('app.error_changing_warehouse_status')
            ]);
            Log::error('Error toggling warehouse status: ' . $e->getMessage());
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
            'typeFilter',
            'statusFilter',
            'cityFilter'
        ]);
        $this->resetPage();
    }

    private function resetForm()
    {
        $this->reset([
            'editingWarehouseId',
            'code',
            'name',
            'type',
            'address',
            'city',
            'state',
            'country',
            'phone',
            'manager_name',
            'manager_phone',
            'manager_email',
            'description',
            'notes',
            'is_active'
        ]);

        // Set defaults
        $this->type = 'main';
        $this->is_active = true;
        $this->country = 'Saudi Arabia';
    }

    // Listen for search updates to reset pagination
    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedTypeFilter()
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
}
