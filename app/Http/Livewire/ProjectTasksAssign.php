<?php

namespace App\Http\Livewire;

use App\Project;
use Livewire\Component;

class ProjectTasksAssign extends Component
{
    public Project $project;

    public $task;

    protected $listeners = ['refreshComponent' => '$refresh'];

    public function mount(Project $project)
    {
        $this->project = $project;
    }

    public function render()
    {
        return view('livewire.project-tasks-assign');
    }

    public function assign()
    {
        if (preg_match('/^([^-\s]+-[^-\s]+)/', $this->task, $matches)) {
            $parentTask = \App\Task::firstOrCreate([
                'code' => $matches[0]
            ]);
            $this->project->tasks()->attach($parentTask);
            $this->emitSelf('refreshComponent');
        }
    }
}
