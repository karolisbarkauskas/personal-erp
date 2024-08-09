<?php

namespace App\Http\Controllers;

use App\Client;
use App\Employee;
use App\Http\Requests\CreateProjectRequest;
use App\Project;

class ProjectsController extends Controller
{
    public function index()
    {

    }

    public function edit(Project $project)
    {
        $clients = Client::all();

        return view('projects.edit', compact('clients', 'project'));
    }

    public function store(CreateProjectRequest $request)
    {
        /** @var Project $project */
        $project = Project::create($request->validated());

        $project->resources()->createMany(
            Employee::all()->map(function (Employee $employee) {
                return [
                    'employee_id' => $employee->id
                ];
            })
        );

        return redirect()->route('projects.edit', $project);
    }

    public function create()
    {
        $clients = Client::all();

        return view('projects.create', compact('clients'));
    }

    public function update(CreateProjectRequest $request, Project $project)
    {
        $project->update($request->validated());

        return redirect()->route('projects.edit', $project);
    }
}
