<?php

namespace App\Http\Controllers;

use App\Client;
use App\Http\Requests\CreateClient;
use App\Income;
use Spatie\Activitylog\Models\Activity;

class ClientsController extends Controller
{
    public function index()
    {
        return view('clients.index');
    }

    public function edit(Client $client)
    {
        $activity = Activity::inLog('client')->where('subject_id', $client->id)->latest()->get();

        $dept = Income::where('client', $client->id)->where('status', '!=', Income::DEBT)->get();
        return view('clients.edit', compact('client', 'activity', 'dept'));
    }

    public function store(CreateClient $client)
    {
        $client = Client::create($client->all());

        return redirect()->route('clients.edit', $client)->with('success', 'Client created');
    }

    public function create()
    {
        return view('clients.create');
    }

    public function update(CreateClient $clientRequest, Client $client)
    {
        $client->update($clientRequest->all());

        activity('client')
            ->performedOn($client)
            ->withProperties($client->getChanges())
            ->log('Updated information');

        return redirect()->back()->with('success', 'Information Updated!');
    }
}
