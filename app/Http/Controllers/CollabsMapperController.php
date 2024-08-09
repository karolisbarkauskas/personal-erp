<?php

namespace App\Http\Controllers;

use App\Client;
use App\CollabMaps;
use App\Http\Requests\MapStoreRequest;
use App\Onesoft\Project;

class CollabsMapperController extends Controller
{
    public function edit(Client $client)
    {
        $onesoftProjects = Project::active()->get();
        $prestaProCollabs = \App\Prestapro\Project::active()->get();
        $innerCollabProjects = \App\Inner\Project::active()->get();

        return view('map.index',
            compact('client', 'onesoftProjects', 'prestaProCollabs', 'innerCollabProjects')
        );
    }

    public function store(MapStoreRequest $request, Client $client)
    {
        $client->collabs()->create([
            'onesoft_collab_id' => $request->get('onesoft_collab'),
            'prestapro_collab_id' => $request->get('prestapro_collab'),
            'inner_collab_id' => $request->get('inner_collab')
        ]);

        return redirect()->back()->with('success', 'ok');
    }

    public function destroy(CollabMaps $map)
    {
        $client = $map->client->id;
        $map->delete();

        return redirect()->route('collabs-map', $client)->with('success', 'ok');
    }
}
