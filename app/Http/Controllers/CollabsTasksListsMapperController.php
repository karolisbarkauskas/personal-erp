<?php

namespace App\Http\Controllers;

use App\CollabMaps;
use App\CollabTasksMap;
use App\Http\Requests\MapTasksListsRequest;
use Illuminate\Http\Request;

class CollabsTasksListsMapperController extends Controller
{
    public function edit(CollabMaps $map)
    {
        $client = $map->client;
        $oneSoftTasksLists = optional($map->onesoftCollab)->taskLists;
        $prestaProTaskLists = optional($map->prestaProCollab)->taskLists;
        $innerCollabTaskLists = optional($map->innerCollab)->taskLists;

        return view('map.map-task-lists',
            compact(
                'map',
                'client',
                'oneSoftTasksLists',
                'prestaProTaskLists',
                'innerCollabTaskLists'
            )
        );
    }

    public function store(MapTasksListsRequest $request, CollabMaps $map)
    {
        if ($request->has('prestapro_collab')) {
            foreach ($request->get('prestapro_collab') as $value) {
                $map->taskListsMap()->create([
                    'map_id' => $map->id,
                    'prestapro_list_id' => $value,
                    'inner_clist_id' => $request->get('inner_collab')
                ]);
            }
        }

        if ($request->has('onesoft_task_list')) {
            foreach ($request->get('onesoft_task_list') as $value) {
                $map->taskListsMap()->create([
                    'map_id' => $map->id,
                    'onesoft_list_id' => $value,
                    'inner_clist_id' => $request->get('inner_collab')
                ]);
            }
        }

        return redirect()->back()->with('success', 'ok');
    }

    public function destroy(Request $request, CollabTasksMap $map)
    {
        $map->delete();

        return redirect()->back()->with('success', 'ok');
    }
}
