<?php

namespace App\Livewire;

use App\Enums\UnitStatus;
use App\Models\Unit;
use Livewire\Component;
use Livewire\WithPagination;

class UnitList extends Component
{
    use WithPagination;

    public string $search = '';

    public string $status = '';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedStatus(): void
    {
        $this->resetPage();
    }

    public function clearFilters(): void
    {
        $this->reset([
            'search',
            'status',
        ]);

        $this->resetPage();
    }

    public function render()
    {
        $units = Unit::query()
            ->with('brand')
            ->when(
                $this->search !== '',
                function ($query) {
                    $search = trim(
                        $this->search
                    );

                    $query->where(function ($query) use ($search) {
                        $query
                            ->where(
                                'vin',
                                'like',
                                "%{$search}%"
                            )
                            ->orWhere(
                                'model',
                                'like',
                                "%{$search}%"
                            )
                            ->orWhereHas(
                                'brand',
                                fn($brandQuery) =>
                                $brandQuery->where(
                                    'name',
                                    'like',
                                    "%{$search}%"
                                )
                            );
                    });
                }
            )
            ->when(
                $this->status !== '',
                fn($query) =>
                $query->where(
                    'status',
                    $this->status
                )
            )
            ->latest()
            ->paginate(15);

        return view(
            'livewire.unit-list',
            [
                'units' => $units,
                'statuses' => UnitStatus::cases(),
            ]
        );
    }
}
