<?php

namespace App\Http\Livewire;

use App\Sale;
use App\YoutrackRecord;
use Livewire\Component;

class YoutrackMap extends Component
{

    /**
     * @var YoutrackRecord
     */
    public $record;
    /**
     * @var Sale[]|\Illuminate\Database\Eloquent\Collection
     */
    public $sales;

    public $sale;

    protected $listeners = ['refreshComponent' => '$refresh'];

    public function mount(YoutrackRecord $record)
    {
        $this->record = $record;
        $this->sale = $this->record->sale;
        $this->sales = Sale::all();
    }

    public function render()
    {
        return view('livewire.youtrack-map');
    }

    public function updateRow()
    {
        $this->record->update([
            'sale' => $this->sale
        ]);

        $this->record = $this->record->fresh();

        $this->emitSelf('refreshComponent');
    }
}
