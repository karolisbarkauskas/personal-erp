@extends('layouts.app')

@section('content')

    <div class="row">

        @if(auth()->user()->isRoot() || auth()->user()->projectManager())
            <div class="col-md-6">
                <h4 class="mg-10"><em>Todays time booking sheet</em></h4>
                <table class="table table-striped table-hover">
                    <thead>
                    <tr>
                        <th scope="col" style="width: 25%">Name</th>
                        <th scope="col" style="width: 25%" class="text-center">Time since last time submitted (when
                            submitted)
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($employees as $employee)
                        <tr>
                            <td>{{ $employee->getFullName() }}</td>
                            <td class="@if(!$employee->isOoo()) tx-{{ $employee->getClassForTimeBooked() }} @endif text-center">
                                @if($employee->isOoo())
                                    Out Of Office - {{ $employee->getOooReason() }}
                                @else
                                    @if($employee->getTimeSinceLastSubmitting())
                                        {{ $employee->getTimeSinceLastSubmitting()->updated_on->diffForHumans() }}
                                        <small>
                                            ({{ $employee->getTimeSinceLastSubmitting()->updated_on->format('H:i') }})
                                        </small>
                                    @endif
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="col-md-6">
                <div id="accordion3">
                    @if($ppTasksCount->sum('total') > 0)
                        <h6>
                            Deployment to LIVE
                            <span class="badge badge-success">{!! $ppTasksCount->sum('completed') !!}</span> of
                            <span class="badge badge-danger">{!! $ppTasksCount->sum('total') !!}</span>
                            <span class="float-right">{!! round(($ppTasksCount->sum('completed') * 100) / $ppTasksCount->sum('total')) !!}%</span>
                        </h6>
                        <div>
                            <table class="table table-striped table-hover" style="height: 200px !important; overflow: scroll">
                                <thead>
                                <tr>
                                    <th scope="col" style="width: 25%">
                                        Team
                                    </th>
                                    <th scope="col" style="width: 25%">
                                        Project
                                    </th>
                                    <th scope="col" style="width: 25%" class="text-center">
                                        Tasks count
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($deploymentsNeededPP as $project)
                                    @include('to-deploy')
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                    @if($OSTasksCount->sum('total') > 0)
                        <h6>
                            Deployment to LIVE
                            <span class="badge badge-success">{!! $OSTasksCount->sum('completed') !!}</span> of
                            <span class="badge badge-danger"> {!! $OSTasksCount->sum('total') !!}</span>
                            <span class="float-right">{!! round(($OSTasksCount->sum('completed') * 100) / $OSTasksCount->sum('total')) !!}%</span>
                        </h6>
                        <div>
                            <table class="table table-striped table-hover" style="height: 200px !important; overflow: scroll">
                                <thead>
                                <tr>
                                    <th scope="col" style="width: 25%">
                                        Team
                                    </th>
                                    <th scope="col" style="width: 25%">
                                        Project
                                    </th>
                                    <th scope="col" style="width: 25%" class="text-center">
                                        Tasks count
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($deploymentsNeededOS as $project)
                                    @include('to-deploy')
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif

                    @if($ppTasksCountTesting->sum('total') > 0)
                        <h6>
                            TESTING stage
                            <span class="badge badge-success">{!! $ppTasksCountTesting->sum('total') !!}</span>
                        </h6>
                        <div>
                            <table class="table table-striped table-hover" style="height: 200px !important; overflow: scroll">
                                <thead>
                                <tr>
                                    <th scope="col" style="width: 25%">
                                        Team
                                    </th>
                                    <th scope="col" style="width: 25%">
                                        Project
                                    </th>
                                    <th scope="col" style="width: 25%" class="text-center">
                                        Tasks count
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach($testingNeededPP as $project)
                                        @include('to-test')
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                    @if($OSTasksCountTesting->sum('total') > 0)
                        <h6>
                            TESTING stage
                            <span class="badge badge-success">{!! $OSTasksCountTesting->sum('total') !!}</span>
                        </h6>
                        <div>
                            <table class="table table-striped table-hover" style="height: 200px !important; overflow: scroll">
                                <thead>
                                <tr>
                                    <th scope="col" style="width: 25%">
                                        Team
                                    </th>
                                    <th scope="col" style="width: 25%">
                                        Project
                                    </th>
                                    <th scope="col" style="width: 25%" class="text-center">
                                        Tasks count
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach($testingNeededOS as $project)
                                        @include('to-test')
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
            <hr>
        @endif

        <div class="col-md-12 mg-b-6">
            <form action="{{ route('update-view') }}" method="get">
                @csrf
                @if(auth()->user()->isRoot())
                    <button class="btn btn-{{ auth()->user()->private_tasks_view ? 'info' : 'success' }}">
                        @if (auth()->user()->private_tasks_view)
                            Switch to all employees tasks
                        @else
                            Switch to my only tasks
                        @endif
                    </button>
                @endif
            </form>
        </div>
        <div class="col-md-6 mg-b-6">

            @if(auth()->user()->isRoot() || auth()->user()->projectManager())
                <form action="{{ route('home') }}" method="get">
                    <div class="form-group col-md-6">
                        <label for="employee">View selected Employee tasks *</label>
                        <select name="employee" id="employee" class="form-control w-35" required>
                            <option value="" selected>-- Select --</option>
                            @foreach($employees as $employee)
                                <option value="{{ $employee->id }}"
                                        @if(request()->get('employee') == $employee->id) selected @endif>{{ $employee->getFullName() }}</option>
                            @endforeach
                        </select>
                        <button class="btn btn-info">
                            View
                        </button>
                    </div>
                </form>
            @endif
        </div>
    </div>

    <h1 class="mg-10"><em>{{ date('Y-m-d') }}</em></h1>
    <div class="row">
        @foreach($tasks as $employee)
            <div
                class="@if((auth()->user()->isRoot() || auth()->user()->projectManager())) col-lg-8 @else col-lg-12 @endif mg-b-10">
                <div class="card">
                    <div class="card-header d-sm-flex align-items-start justify-content-between">
                        <h6 class="lh-5 mg-b-0">
                            {{ $employee['name'] }} (Total: {{ $employee['work_day'] }})
                        </h6>
                        @if(auth()->user()->isRoot() || auth()->user()->projectManager())
                            <a href="" class="tx-13 link-03">
                                {{ $employee['date'] }}
                                <span class="badge badge-{{ $employee['employment']['badge'] }}">
                                    {{ $employee['employment']['text'] }}
                                </span>
                            </a>
                        @endif
                    </div>
                    <div class="card-body pd-y-15 pd-x-10">
                        <div class="table-responsive">
                            @foreach($employee['tasks'] as $projectName => $priorities)
                                <div class="divider-text tx-black tx-2"><strong>{{ $projectName }}</strong></div>
                                <table class="table table-borderless table-sm tx-13 tx-nowrap mg-b-0 table-hover">
                                    <thead>
                                    <tr class="tx-10 tx-spacing-1 tx-color-03 tx-uppercase">
                                        <th class="text-center wd-150-f">Labels</th>
                                        <th class="text-center wd-800-f">Task</th>
                                        <th class="text-center wd-100-f">Estimated time</th>
                                        <th class="text-center wd-100-f">Actual time</th>
                                        @if(auth()->user()->isRoot() || auth()->user()->projectManager())
                                            <th class="text-center">Due on</th>
                                            <th class="text-center"></th>
                                        @endif
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($priorities as $priority)
                                        @foreach($priority as $task)
                                            <tr>
                                                <td class="tx-medium text-center wd-150-f">
                                                    @if(!$task->labels->containsPriorityLabels())
                                                        <span class="badge badge-warning">
                                                            No priority assigned
                                                        </span>
                                                    @else
                                                        @foreach($task->labels as $label)
                                                            @if ($label->showPriority())
                                                                <span class="badge"
                                                                      style="background: {{ $label->globalLabel->color }}">
                                                                {{ $label->globalLabel->name }}
                                                            </span>
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                </td>
                                                <td class="tx-medium wd-800-f">
                                                    @if($task->isTaskCompleted())
                                                        <span class="badge badge-success">Task completed</span>
                                                    @endif
                                                    <a class="@if($task->isTaskLate() && !$task->isTaskCompleted()) tx-danger @endif"
                                                       href="https://tasks.onesoft.io/projects/{{ $task->project_id }}?modal=Task-{!! $task->id !!}-{{ $task->project_id }}"
                                                       target="_blank">
                                                        #{!! $task->task_number !!} - {!! $task->name !!}
                                                    </a>
                                                </td>
                                                <td class="text-center @if($task->isTaskLate() && !$task->isTaskCompleted()) tx-danger @endif">@if($task->isTrackingEnabled()) {{ $task->estimate }} @else
                                                        - @endif</td>
                                                <td class="text-center @if($task->isTaskLate() && !$task->isTaskCompleted()) tx-danger @endif">@if($task->isTrackingEnabled()) {{ $task->getTimeBookedByEmployee()->sum('value') }} @else
                                                        - @endif</td>
                                                @if(auth()->user()->isRoot() || auth()->user()->projectManager())
                                                    <td class="text-center wd-400-f @if($task->isTaskLate() && !$task->isTaskCompleted()) tx-danger @endif">
                                                        {{ $task->due_on }}
                                                        <span class="badge badge-{{ $task->getTaskClass() }}">
                                                            {{ $task->getOverTime() }}
                                                        </span>
                                                    </td>
                                                    <td class="text-center @if($task->isTaskLate() && !$task->isTaskCompleted()) tx-danger @endif">
                                                        <span class="badge badge-{{ $task->getTaskBadge() }}">
                                                            {{ $task->getTaskText() }}
                                                        </span>
                                                    </td>
                                                @endif
                                            </tr>
                                        @endforeach
                                    @endforeach
                                    </tbody>
                                </table>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            @if((auth()->user()->isRoot() || auth()->user()->projectManager()))
                <div class="col-lg-4 mg-b-10">
                    <div class="card">
                        <div class="card-header d-sm-flex align-items-start justify-content-between">
                            <h6 class="lh-5 mg-b-0">
                                {{ $employee['name'] }} booked time today
                                <em>{{ $employee['booked_times']->decimalToTime($employee['booked_times']->getBillableTime()) }}
                                    h
                                    of {{ $employee['booked_times']->decimalToTime((float)$employee['expected_work_time']) }}
                                    h</em>
                            </h6>
                        </div>
                        <div class="card-body pd-y-15 pd-x-10">
                            <div class="table-responsive">
                                <table
                                    class="table table-borderless table-sm tx-13 tx-nowrap mg-b-0 table-hover">
                                    <thead>
                                    <tr class="tx-10 tx-spacing-1 tx-color-03 tx-uppercase">
                                        <th class="text-center">Project</th>
                                        <th class="tx-medium">Task and comments</th>
                                        <th class="text-center">Booked time</th>
                                        <th class="text-center"></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($employee['booked_times'] as $time)
                                        <tr>
                                            <td class="text-center">
                                                {!! $time->task->project->name !!}
                                            </td>
                                            <td class="tx-medium">
                                                <a href="https://tasks.onesoft.io/projects/{{ $time->task->project->id }}?modal=Task-{!! $time->parent_id !!}-{{ $time->task->project->id }}"
                                                   target="_blank">
                                                    {{ $time->task->name }} <br>
                                                </a>
                                                <p>
                                                    <pre>{{ $time->summary }}</pre>
                                                </p>
                                            </td>
                                            <td class="text-center">
                                                @if($time->billable_status)
                                                    <span class="badge badge-success">
                                                        {!! $time->decimalToTime($time->value) !!}
                                                    </span>
                                                @else
                                                    <span class="badge badge-warning">
                                                        {!! $time->decimalToTime($time->value) !!}
                                                    </span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
    </div>

    <div id="accordion1" class="accordion accordion-style1 mg-b-10">
        @include('tasks', ['data' => $currentWeekTasks])
    </div>

@endsection

@section('javascript')
    <script src="{{ asset('lib/jqueryui/jquery-ui.min.js') }}"></script>

    <script>
        $('#accordion1,#accordion2, #accordion3').accordion({
            collapsible: false,
            active: false,
            heightStyle: "content"
        });
    </script>
@endsection

