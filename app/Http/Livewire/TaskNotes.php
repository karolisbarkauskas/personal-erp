<?php

namespace App\Http\Livewire;

use App\PersonalNotes;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class TaskNotes extends Component
{
    public $notes;

    public function mount()
    {
        $note = PersonalNotes::where('user_id', Auth::user()->id)->first();
        $this->notes = $note ? $note->notes : '';
    }

    public function save()
    {
        PersonalNotes::updateOrCreate([
            'user_id' => Auth::id(),
        ], [
            'notes' => $this->notes,
        ]);
    }

    public function render()
    {
        return view('livewire.task-notes');
    }
}
