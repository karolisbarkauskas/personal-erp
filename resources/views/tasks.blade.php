@foreach($data as $date => $employees)
    <h6 class="accordion-title @if(isset($employees[auth()->user()->collab_user]) && $employees[auth()->user()->collab_user]['is_day_off']) bg-info text-white @endif "  >
        {{ $date }}
        @if(isset($employees[auth()->user()->collab_user]) && ((auth()->user()->isRoot() || auth()->user()->projectManager()) && !$employees[auth()->user()->collab_user]['is_day_off']))
            ({!! $employees[auth()->user()->collab_user]["work_day"] !!} of {!! $employees[auth()->user()->collab_user]["expected_work_time"] !!})
        @endif

        @if(request()->get('employee'))
            ({!! $employees[request()->get('employee')]["work_day"] !!} of {!! $employees[request()->get('employee')]["expected_work_time"] !!})
        @endif
    </h6>
    <div class="accordion-body">
        @foreach($employees as $employee)
            <div class="row">
                <div class="col-lg-12 mg-b-10">
                    <div class="card">
                        <div class="card-header d-sm-flex align-items-start justify-content-between">
                            <h6 class="lh-5 mg-b-0">
                                {{ $employee['name'] }} (Total: {{ $employee['work_day'] }})
                            </h6>
                            <a href="" class="tx-13 link-03">
                                    <span class="badge badge-{{ $employee['employment']['badge'] }}">
                                        {{ $employee['employment']['text'] }}
                                    </span>
                            </a>
                        </div>
                        <div class="card-body pd-y-15 pd-x-10">
                            <div class="table-responsive">
                                @foreach($employee['tasks'] as $projectName => $priorities)
                                    <div class="divider-text tx-black tx-2"><strong>{{ $projectName }}</strong></div>
                                    <table class="table table-borderless table-sm tx-13 tx-nowrap mg-b-0 table-hover">
                                        <thead>
                                        <tr class="tx-10 tx-spacing-1 tx-color-03 tx-uppercase">
                                            <th class="text-center wd-200-f">Labels</th>
                                            <th class="text-left wd-500-f">Task</th>
                                            <th class="text-center wd-200-f">Estimated time</th>
                                            <th class="text-center wd-200-f">Actual time</th>
                                            @if(auth()->user()->isRoot() || auth()->user()->projectManager())
                                                <th class="text-center wd-100-f">Due date</th>
                                                <th class="text-center wd-100-f"></th>
                                            @endif
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($priorities as $priority)
                                            @foreach($priority as $task)
                                                <tr>
                                                    <td class="tx-medium text-center wd-200-f">
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
                                                    <td class="tx-medium wd-500-f">
                                                        @if($task->isTaskCompleted())
                                                            <span class="badge badge-success">Task completed</span>
                                                        @endif
                                                        <a href="https://tasks.onesoft.io/projects/{{ $task->project_id }}?modal=Task-{!! $task->id !!}-{{ $task->project_id }}"
                                                           target="_blank">
                                                            #{!! $task->task_number !!} - {!! $task->name !!}
                                                        </a>
                                                    </td>
                                                    <td class="text-center wd-200-f">@if($task->isTrackingEnabled()) {{ $task->estimate }} @else
                                                            - @endif</td>
                                                    <td class="text-center wd-200-f">@if($task->isTrackingEnabled()) {{ $task->getTimeBookedByEmployee()->sum('value') }} @else
                                                            - @endif</td>
                                                    @if(auth()->user()->isRoot() || auth()->user()->projectManager())
                                                        <td class="text-center wd-200-f @if($task->isTaskLate() && !$task->isTaskCompleted()) tx-danger @endif">
                                                            {{ $task->due_on }}
                                                            <span class="badge badge-{{ $task->getTaskClass(\Carbon\Carbon::createFromFormat('Y-m-d', $date)->startOfDay()) }}">
                                                                {{ $task->getOverTime(\Carbon\Carbon::createFromFormat('Y-m-d', $date)->startOfDay()) }}
                                                            </span>
                                                        </td>
                                                        <td class="text-center wd-200-f">
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
            </div>
        @endforeach
    </div>
@endforeach
