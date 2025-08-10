<?php

namespace App\Livewire;

use App\Services\UnitService;
use Livewire\Component;
use Livewire\WithPagination;

class UnitsTable extends Component
{
    use WithPagination;

    // Properties
    public $search = '';
    public $showModal = false;
    public $editingUnitId = null;

    // Form fields
    public $name = '';
    public $symbol = '';
    public $description = '';
    public $is_active = true;

    // Pagination
    protected $paginationTheme = 'bootstrap';

    // Service getter method
    protected function getUnitService()
    {
        return app(UnitService::class);
    }

    public function mount()
    {
        $this->resetPage();
    }

    // Computed properties
    public function getUnitsProperty()
    {
        return $this->getUnitService()->getAllUnits($this->search, 10);
    }

    public function getStatsProperty()
    {
        return $this->getUnitService()->getStats();
    }

    // Search functionality
    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function clearSearch()
    {
        $this->search = '';
        $this->resetPage();
    }

    // Modal methods
    public function openAddModal()
    {
        $this->resetForm();
        $this->editingUnitId = null;
        $this->showModal = true;
    }

    public function openEditModal($unitId)
    {
        $unit = $this->getUnitService()->findUnit($unitId);

        $this->editingUnitId = $unitId;
        $this->name = $unit->name;
        $this->symbol = $unit->symbol ?? '';
        $this->description = $unit->description ?? '';
        $this->is_active = $unit->is_active;

        $this->showModal = true;
    }
    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
        $this->resetValidation();
    }

    // Form methods
    private function resetForm()
    {
        $this->name = '';
        $this->symbol = '';
        $this->description = '';
        $this->is_active = true;
    }

    public function save()
    {
        $data = [
            'name' => $this->name,
            'symbol' => $this->symbol,
            'description' => $this->description,
            'is_active' => $this->is_active,
        ];

        $validationResult = $this->getUnitService()->validateData($data, $this->editingUnitId);

        if (!$validationResult['success']) {
            $this->setErrorBag($validationResult['errors']);
            return;
        }

        try {
            if ($this->editingUnitId) {
                $result = $this->getUnitService()->update($this->editingUnitId, $data);
                $message = __('app.unit_updated_successfully');
            } else {
                $result = $this->getUnitService()->create($data);
                $message = __('app.unit_created_successfully');
            }

            if ($result['success'] ?? true) {
                $this->closeModal();
                session()->flash('success', $message);
            } else {
                session()->flash('error', $result['message'] ?? __('app.error_saving_unit'));
            }
        } catch (\Exception $e) {
            session()->flash('error', __('app.error_saving_unit'));
        }
    }

    public function delete($unitId)
    {
        try {
            $result = $this->getUnitService()->delete($unitId);

            if ($result['success']) {
                session()->flash('success', __('app.unit_deleted_successfully'));
            } else {
                session()->flash('error', $result['message'] ?? __('app.error_deleting_unit'));
            }
        } catch (\Exception $e) {
            session()->flash('error', __('app.error_deleting_unit'));
        }
    }

    public function toggleStatus($unitId)
    {
        $result = $this->getUnitService()->toggleUnitStatus($unitId);

        $this->dispatch('status-toggled', $result);
    }
    public function render()
    {
        return view('livewire.units-table', [
            'units' => $this->units,
            'stats' => $this->stats,
        ]);
    }
}
