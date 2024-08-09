@extends('layouts.app')

@section('content')
    <form action="{{ route('flow.index') }}" method="get">
        <div class="row">
            <div class="col-lg-2">
                <select name="team" id="team" class="select2 form-control">
                    <option value="">-- Team --</option>
                    @foreach($teams as $team)
                        <option value="{{ $team->id }}"
                                @if(request()->get('team') == $team->id) selected @endif>
                            {{ $team->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-2">
                <select name="employee" id="employee" class="select2 form-control">
                    <option value="">-- Team member --</option>
                    @foreach($employees as $employee)
                        <option value="{{ $employee->id }}"
                                @if(request()->get('employee') == $employee->id) selected @endif>
                            {{ $employee->first_name }} {{ $employee->last_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-2">
                <select name="project" id="project" class="select2 form-control">
                    <option value="">-- Select project --</option>
                    @foreach($allProjects as $project)
                        <option value="{{ $project->id }}"
                                @if(request()->get('project') == $project->id) selected @endif>
                            {{ $project->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-2">
                <button class="btn btn-warning">
                    Filter
                </button>
            </div>
        </div>
    </form>
    <div class="row">
        <div class="col-lg-3 mg-t-10">
            <div class="card ht-md-100p">
                <div class="card-header d-flex justify-content-between">
                    <h6 class="lh-5 mg-b-0">TASKS BACKLOG</h6>
                </div>
                <div class="card-body pd-0">
                    @foreach($projects as $project)
                        @if($project->tasksBackLog->isNotEmpty())
                            <ul class="list-unstyled mg-b-0">
                                <li class="list-label tx-black">{{ $project->name }}</li>
                                @foreach($project->tasksBackLog as $task)
                                    @include('flow._partials.task', ['task' => $task])
                                @endforeach
                            </ul>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
        <div class="col-lg-3 mg-t-10">
            <div class="card ht-md-100p">
                <div class="card-header d-flex justify-content-between">
                    <h6 class="lh-5 mg-b-0">TO DO</h6>
                </div>
                <div class="card-body pd-0">
                    @foreach($projects as $project)
                        @if($project->toTo->isNotEmpty())
                            <ul class="list-unstyled mg-b-0">
                                <li class="list-label tx-black">{{ $project->name }}</li>
                                @foreach($project->toTo as $task)
                                    @include('flow._partials.task', ['task' => $task])
                                @endforeach
                            </ul>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
        <div class="col-lg-2 mg-t-10">
            <div class="card ht-md-100p">
                <div class="card-header d-flex justify-content-between">
                    <h6 class="lh-5 mg-b-0 tx-black">IN PROGRESS</h6>
                </div>
                <div class="card-body pd-0">
                    @foreach($projects as $project)
                        @if($project->inProgress->isNotEmpty())
                            <ul class="list-unstyled mg-b-0">
                                <li class="list-label tx-black">{{ $project->name }}</li>
                                @foreach($project->inProgress as $task)
                                    @include('flow._partials.task', ['task' => $task])
                                @endforeach
                            </ul>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
        <div class="col-lg-2 mg-t-10">
            <div class="card ht-md-100p">
                <div class="card-header d-flex justify-content-between">
                    <h6 class="lh-5 mg-b-0">TESTING</h6>
                </div>
                <div class="card-body pd-0">
                    @foreach($projects as $project)
                        @if($project->testing->isNotEmpty())
                            <ul class="list-unstyled mg-b-0">
                                <li class="list-label tx-black">{{ $project->name }}</li>
                                @foreach($project->testing as $task)
                                    @include('flow._partials.task', ['task' => $task])
                                @endforeach
                            </ul>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
        <div class="col-lg-2 mg-t-10">
            <div class="card ht-md-100p">
                <div class="card-header d-flex justify-content-between">
                    <h6 class="lh-5 mg-b-0">DONE (deploy to LIVE)</h6>
                </div>
                <div class="card-body pd-0">
                    @foreach($projects as $project)
                        @if($project->done->isNotEmpty())
                            <ul class="list-unstyled mg-b-0">
                                <li class="list-label tx-black">{{ $project->name }}</li>
                                @foreach($project->done as $task)
                                    @include('flow._partials.task', ['task' => $task])
                                @endforeach
                            </ul>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
