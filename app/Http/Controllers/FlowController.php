<?php

namespace App\Http\Controllers;

use App\CollabUsers;
use App\GlobalLabel;
use App\Project;

class FlowController extends Controller
{
    public function index()
    {
        $projects = Project::whereHas('tasks')->filtered()->active()->byName()->get();
        $employees = CollabUsers::active()->byName()->get();
        $allProjects = Project::whereHas('tasks')->excludedProjects()->active()->byName()->get();
        $teams = GlobalLabel::teamLabels()->orderBy('name')->get();

        return view('flow.index', compact('projects', 'employees', 'allProjects', 'teams'));
    }
}
