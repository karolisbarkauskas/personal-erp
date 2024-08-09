<?php

namespace App\Http\Livewire;

use App\Settings;
use Livewire\Component;

class VatComponent extends Component
{
    public $vatToPay;
    public $reduce;
    protected $listeners = ['refresh' => '$refresh'];

    public function mount()
    {
        $this->vatToPay = Settings::getVatToPay();
    }

    public function save()
    {
        if ($this->reduce > 0) {
            Settings::where('name', 'PAID_VAT')->increment('value', $this->reduce);
            Settings::calculatePayableVat();
            $this->vatToPay = Settings::getVatToPay();
            $this->emitSelf('refresh');
        }
    }

    public function calculate()
    {
        Settings::calculatePayableVat();
        $this->vatToPay = Settings::getVatToPay();
        $this->emitSelf('refresh');
    }

    public function render()
    {
        return view('livewire.vat-component');
    }
}
