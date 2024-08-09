<?php

namespace App\Http\Livewire;

use App\TaskList;
use Livewire\Component;

class WeekSums extends Component
{
    public TaskList $list;

    protected $listeners = ['refreshComponent' => '$refresh'];

    public function mount(TaskList $list)
    {
        $this->list = $list;
    }

    public function render()
    {
        return view('livewire.week-sums');
    }
}
