<?php

namespace App\Http\Livewire;

use App\Settings;
use Livewire\Component;

class BankBalance extends Component
{

    public $bankBalance = 0;

    public function mount()
    {
        $this->bankBalance = Settings::getBankBalance();
    }

    public function updatedBankBalance()
    {
        Settings::setBankBalance($this->bankBalance);
    }

    public function render()
    {
        return view('livewire.bank-balance');
    }
}
