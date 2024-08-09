<?php

namespace App\Http\Controllers;

use App\Agreement;
use App\Http\Requests\CreateAgreementRequest;
use App\Task;
use Cassandra\Aggregate;
use Illuminate\Http\Request;

class AgreementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CreateAgreementRequest $request)
    {
        Agreement::create($this->prepareData($request));
        return redirect()->back()->with('success', 'ok');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Agreement  $agreement
     * @return \Illuminate\Http\Response
     */
    public function show(Agreement $agreement)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Agreement  $agreement
     * @return \Illuminate\Http\Response
     */
    public function edit(Agreement $agreement)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Agreement  $agreement
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Agreement $agreement)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Agreement  $agreement
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Agreement $agreement)
    {
        $agreement->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Agreement successfully deleted!',
            'text' => $agreement->name,
        ]);
    }

    public function prepareData(CreateAgreementRequest $request): array
    {
        $data = $request->all();
        $task = Task::find($data['task_id']);

        if ($task && empty($data['name'])){
            $data['name'] = $task->name;
        }
        if ($task && empty($data['budget']) && !empty($data['estimate'])){
            $data['budget'] = $task->project->client->rate * $data['estimate'];
        }
        if ($task && !empty($data['budget']) && empty($data['estimate'])){
            $data['estimate'] = $data['budget'] /$task->project->client->rate;
        }
        return $data;
    }
}
