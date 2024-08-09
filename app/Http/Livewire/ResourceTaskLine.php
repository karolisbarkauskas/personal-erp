<?php

namespace App\Http\Livewire;

use App\Project;
use App\Task;
use Livewire\Component;

class ResourceTaskLine extends Component
{

    public Task $task;
    public Project $project;

    protected $listeners = ['refreshComponent' => '$refresh'];

    public function mount(Task $task, Project $project)
    {
        $this->task = $task;
        $this->project = $project;
    }

    public function delete()
    {
        $this->project->tasks()->detach($this->task->id);

        $this->emitTo(ProjectTasksAssign::class, 'refreshComponent');
    }

    public function render()
    {
        return view('livewire.resource-task-line');
    }
}
