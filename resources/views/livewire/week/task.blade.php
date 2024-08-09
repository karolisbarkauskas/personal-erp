<div class="text-left">
    @if($task->automatic)
        <span class="badge badge-success float-right ml-1">imported</span>
    @endif
    @if($task->is_open)
            @if($task->getCurrentProfit() != 0 && !$task->isProject())
                <span class="badge badge-{{ $task->getBadgeClass() }} float-right">{{ $task->getCurrentProfit() }}€ @if($task->sold_to_client > 0)
                        / {{ $task->getProfitPercentage() }}%
                    @endif</span>
        @endif
        <a wire:click="setState(0)" style="cursor: pointer">[hide]</a> <small>({{ $task->employee_hours ?? '-' }}h)</small> <br>

        <input type="text" class="form-control w-100 mb-1" wire:model="task.name"
               placeholder="Youtrack ID and task name"/>
        <textarea wire:model="task.remarks"
                  cols="30" rows="2" class="form-control mb-2" placeholder="Remarks"></textarea>

        <select class="form-control w-75 float-left" wire:model="task.client_id">
            <option value="">-- Assign client --</option>
            @foreach($clients as $client)
                <option value="{{ $client->id }}" @if($task->client_id == $client->id) @endif>
                    {{ $client->project ? $client->project.' -> ' : '' }}{{ $client->name }} ({{ $client->hourly_diff }}h)
                </option>
            @endforeach
        </select>
        <button class="form-control w-25 float-left btn-dark text-white" wire:click="calculate()">
            <small>Calculate</small>
        </button>

        @if(!$task->isProject())
            <input type="text" class="form-control w-50 p-0 tx-center float-left" wire:model="task.sold_to_client"
                   placeholder="H sold to client"/>
        @endif
        <input type="text" class="form-control w-50 p-0 tx-center float-left" wire:model="task.employee_hours"
               placeholder="Employee hours"/>

        <select class="form-control w-50 float-left" wire:model="task.status">
            <option value="">-- Assign state --</option>
            <option value="{{ \App\WeekTask::RESOURCE }}" @if($task->isResource()) selected="selected" @endif>
                Resource plan
            </option>
            <option value="{{ \App\WeekTask::TODO }}" @if($task->isToDo()) selected="selected" @endif>
                TO DO
            </option>
            <option value="{{ \App\WeekTask::IN_PROGRESS }}" @if($task->isInProgress()) selected="selected" @endif>
                In progress
            </option>
            <option value="{{ \App\WeekTask::COMPLETED }}" @if($task->isCompleted()) selected="selected" @endif>
                Completed
            </option>
            <option value="{{ \App\WeekTask::INVOICED }}" @if($task->isInvoiced()) selected="selected" @endif>
                Invoiced
            </option>
            <option value="{{ \App\WeekTask::PROJECT_RESOURCE }}" @if($task->isProject()) selected="selected" @endif>
                Project task
            </option>
        </select>

        @if($task->isProject() && $task->task)
            <select class="form-control w-100 float-left" wire:model="project">
                <option value="">-- Project --</option>
                @foreach($projects as $sProject)
                    <option value="{{ $sProject->id }}">
                        {{ $sProject->name }} {{ $task->task->id }}
                    </option>
                @endforeach
            </select>
        @endif

        <button type="submit" class="btn btn-primary w-100 mb-3" wire:click="update()">Update task information</button>

        <button type="submit" class="badge badge-danger w-25" wire:click="deleteTask()">Delete</button>
    @else
        <a wire:click="setState(1)" style="cursor: pointer">[show]</a>
        <small>({{ $task->sold_to_client ?? '-' }}/{{ $task->employee_hours ?? '-' }})h
            - {{ optional($task->client)->project ?: optional($task->client)->name  }}</small> <br>
        <h5 class="lh-5 mg-b-0 p-2" style="{{ $task->getStyleAttributes() }}">
            @if($task->getCurrentProfit() != 0 && !$task->isProject())
                <span class="badge badge-{{ $task->getBadgeClass() }} float-right">{{ $task->getCurrentProfit() }}€ @if($task->sold_to_client > 0)
                        / {{ $task->getProfitPercentage() }}%
                    @endif</span>
            @endif
            @if($task->link)
                <a href="{{ $task->link }}" target="_blank">{{ \Illuminate\Support\Str::words($task->name, 7) ?? 'No task name set' }}</a>
            @else
                @if($task->isResource())
                    <div class="text-center" wire:poll.keep-alive>
                        <small>
                            Resource for {{ optional($task->client)->project ?: optional($task->client)->name  }} <br>
                            <strong>{{ $task->sold_to_client ?? '-' }}h / {{ $task->employee_hours ?? '-' }}h</strong>
                        </small>
                    </div>
                @else
                    <a wire:click="setState(1)" style="cursor: pointer">
                        {{ \Illuminate\Support\Str::words($task->name, 7) ?? 'No task name set' }}
                    </a>
                @endif
            @endif
            @if($task->task && !$task->isProject())
                <div @if($task->task->client_time_sold < $task->task->client_time_used) class="tx-danger" @endif>
                    <small title="Client hours total sold">
                        <strong>{{ $task->task->client_time_sold }}h</strong>
                    </small> / <small title="Total employee hours used (converted)">
                        <strong>{{ $task->task->client_time_used }}h</strong>
                    </small>
                </div>
            @endif
        </h5>
    @endif
    <hr>
</div>
