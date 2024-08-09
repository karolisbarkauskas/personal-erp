<?php

namespace App\Http\Controllers;

use App\Agreement;
use App\AgreementGroup;
use App\CollabUsers;
use App\GlobalLabel;
use App\Sale;
use Carbon\Carbon;

class SalesGanttController extends Controller
{
    public function index()
    {
        $result = [];
        /** @var Sale $sale */
        $minDate = Carbon::now();
        $maxDate = Carbon::now();

        $result['resources'] = $this->getResources();

        $res = 0;


            foreach (AgreementGroup::getActiveAgreementGroups() as $group) {
                $result['tasks'][] =
                    [
                        'id' => 'ag-' . $group->id,
                        'parentId' => 0,
                        'category' => $group->sale->project->getCategory(),
                        'title' => '('.$group->sale->name.') -> ' . $group->name ,
                        'start' => Carbon::createFromDate($group->start)->startOfDay()->format('Y-m-d H:i:s'),
                        'end' => Carbon::createFromDate($group->deadline)->endOfDay()->format('Y-m-d H:i:s'),
                        'progress' => $group->getProgress(),
                        'color' => 'grey',
                    ];
                $result['resourceAssignments'][] = [
                    'id' => $res++,
                    'taskId' => 'ag-' . $group->id,
                    'resourceId' => 'p-' . $group->sale->project->label_id
                ];

                if ($minDate > $group->start) {
                    $minDate = $group->start;
                }
                if ($maxDate < $group->deadline) {
                    $maxDate = $group->deadline;
                }
                /** @var Agreement $agreement */
                foreach ($group->agreements as $agreement) {
                    if ($agreement->task->completed_on || $agreement->is_trashed) {
                        continue;
                    }
                    if ($agreement->task->responsible) {
                        $result['resourceAssignments'][] = [
                            'id' => $res++,
                            'taskId' => 'a-' . $agreement->id,
                            'resourceId' => 'empl-' . $agreement->task->responsible->id
                        ];
//                        $result['resourceAssignments'][] = [
//                            'id' => $res++,
//                            'taskId' => 'ag-' . $group->id,
//                            'resourceId' => 'empl-' . $agreement->task->responsible->id
//                        ];
                    }
                    $result['tasks'][] = [
                        'id' => 'a-' . $agreement->id,
                        'parentId' => 'ag-' . $group->id,
                        'title' => $agreement->name,
                        'start' => Carbon::createFromDate($agreement->from)->startOfDay()->format('Y-m-d H:i:s'),
                        'end' => Carbon::createFromDate($agreement->to)->endOfDay()->format('Y-m-d H:i:s'),
                        'progress' => $agreement->getProgress(),
                        'color' => $agreement->getProgressColor(),
                    ];
                }
            }


        $result['startDateRange'] = Carbon::createFromDate($minDate)->startOfDay()->format('Y-m-d H:i:s');
        $result['endDateRange'] = Carbon::createFromDate($maxDate)->endOfDay()->format('Y-m-d H:i:s');
        $result['now'] = Carbon::now()->format('Y/m/d');
        $result['stripLines'] = [
            [
                'title' => 'Start',
                'start' => Carbon::now()->startOfDay(),
            ], [
                'title' => 'Final Phase',
                'start' => Carbon::now()->startOfDay(),
                'end' => Carbon::now()->endOfDay(),
            ], [
                'title' => 'Current Time',
                'start' => Carbon::now(),
                'cssClass' => 'current-time',
            ]
        ];


        return response()->json($result);

//                const dependencies = [
//                    {
//                        id: 1,
//                        predecessorId: 3,
//                        successorId: 4,
//                        type: 0,
//                    }
//                ];

    }

    public function getResources()
    {
        $resources = [
            [
                'id' => 'p-' . GlobalLabel::ONESOFT,
                'text' => 'OneSoft',
            ],
            [
                'id' => 'p-' . GlobalLabel::PRESTAPRO,
                'text' => 'PRESTAPRO',
            ]
        ];
        foreach (CollabUsers::all() as $user){
            $resources[] =   [
                'id' => 'empl-' . $user->id,
                'text' => $user->getFullName(),
            ];
        }
        return $resources;
    }
}
