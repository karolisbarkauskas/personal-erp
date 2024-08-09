<?php

namespace App\Http\Controllers;

use App\Client;
use App\CollabUsers;
use App\Project;
use App\Task;

class TaskStatusController extends Controller
{
    public function index()
    {
        return view('tasks.index');
    }

    public function show()
    {
        $projects = Project::whereHas('tasks')->get();
        $result = [];
        foreach ($projects as $project){
            $company = Client::where('id', $project->company_id)->first();
            $result['projects'][] = [
                'name' => $project->name,
                'project_id' => $project->id,
            ];
            foreach ($project->tasksLists as $taskList){
                $result['taskLists'][] = [
                    'name' => $taskList->name,
                    'task_list_id' => $taskList->id,
                ];
                /** @var Task $task */
                foreach ($taskList->tasks as $task){
                    $data = [];
                    $data['id'] = $task->id;
                    $data['parent'] = 0;
                    $data['start_on'] = $task->start_on;
                    $data['due_on'] = $task->due_on;
                    $data['priority'] = $company->getPriority() + $task->getPriority();
                    $data['task_list_name'] = $taskList->id;
                    $data['project_name'] = $project->id;
                    $data['estimate'] = $task->estimate;
                    $data['overtime'] =  $task->getOverTime();
                    $data['QA'] =  $task->needQa();
                    $data['assignee_id'] = $task->assignee_id;
                    $data['responsible'] = $task->assignee_id;
                    $data['task_name'] = $task->name;
                    $data['created_on'] = $task->created_on;
//                    $data['labels'] = implode(", ",$task->allLabels()->get()->pluck('globalLabel.name')->toArray());
                    $result['products'][]      = $data;
                }
            }
        }
        foreach (CollabUsers::all() as $user){
            $result['employes'][] = [
                'name' => $user->first_name . " ". $user->last_name,
                'user_id' => $user->id,
            ];
        }
        $result['expandedRowKeys'] = [];
        $result['selectedRowKeys'] = [];

        return response()->json($result);
    }
}
