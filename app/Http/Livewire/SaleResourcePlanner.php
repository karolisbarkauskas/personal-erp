<?php

namespace App\Http\Livewire;

use App\Sale;
use Livewire\Component;

class SaleResourcePlanner extends Component
{
    public $total;
    public $minTotal;
    /** @var Sale */
    public $sale;

    protected $listeners = ['refreshComponent' => '$refresh'];

    public function mount(Sale $sale)
    {
        $this->sale = $sale;
    }

    public function render()
    {
        $this->total = $this->sale->estimate->sum('total');
        $this->minTotal = $this->sale->getMinimalCostCollection();

        return view('livewire.sale-resource-planner');
    }
}
