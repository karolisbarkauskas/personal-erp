<?php

namespace App\Http\Livewire;

use App\TaskList;
use Livewire\Component;

class WeekLocker extends Component
{
    public TaskList $list;

    public function mount(TaskList $list)
    {
        $this->list = $list;
    }

    public function update()
    {
        $locked = !$this->list->is_locked;
        $this->list->update([
            'is_locked' => $locked
        ]);
        $this->list->updateTotals();
    }

    public function updateFinish()
    {
        $locked = !$this->list->is_finished;
        $this->list->update([
            'is_finished' => $locked
        ]);
        $this->list->updateTotals();
    }
    public function render()
    {
        return view('livewire.week-locker');
    }
}
