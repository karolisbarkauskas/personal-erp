<?php

namespace App\Http\Livewire;

use App\ProjectResource;
use Livewire\Component;

class ResourceLine extends Component
{
    /** @var ProjectResource */
    public $resource;

    protected $rules = [
        'resource.client_time_sold' => 'nullable|numeric'
    ];

    public function mount($resource)
    {
        $this->resource = $resource;
    }

    public function update()
    {
        $this->resource->employee_time_available = $this->resource->employee->calculateDelay(
            $this->resource->project->client->rate,
            $this->resource->client_time_sold
        );

        $this->resource->save();
    }

    public function render()
    {
        return view('livewire.resource-line');
    }
}
