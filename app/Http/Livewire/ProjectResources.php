<?php

namespace App\Http\Livewire;

use App\Project;
use Livewire\Component;

class ProjectResources extends Component
{

    public Project $project;

    public function mount(Project $project)
    {
        $this->project = $project;
    }

    public function render()
    {
        return view('livewire.project-resources');
    }
}
