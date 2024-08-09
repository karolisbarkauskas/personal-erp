<div>
    <div class="row">
        <div class="col-md-12">
            <div class="card mg-b-10">
                <div class="card-body">

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                <div class="col-md-2 text-left">
                                    <h5>{{ __('Project') }}</h5>
                                </div>
                                <div class="col-md-10 text-left">
                                    <a target="_blank"
                                       href="https://tasks.onesoft.io/projects/{{ $task->project->id }}">{{ $task->project->name }}</a>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-2 text-left">
                                    <h5>{{ __('Task') }}</h5>
                                </div>
                                <div class="col-md-10 text-left">
                                    @if($task->collabTask)
                                        <a href="https://tasks.onesoft.io/projects/{{ $task->project->id }}?modal=Task-{{ $task->collabTask->id }}-{{ $task->project->id }}"
                                           target="_blank">
                                            {!! $task->collabTask->name !!}
                                        </a>
                                    @else
                                       <span class="badge badge-info">
                                           PROJECT LEVEL ENTRIES
                                       </span>
                                    @endif
                                </div>
                            </div>
                            @if($task->collabTask)
                                <div class="form-group row">
                                    <div class="col-md-2 text-left">
                                        <h5>{{ __('Task Started') }}</h5>
                                    </div>
                                    <div class="col-md-10 text-left">
                                        {!! $task->collabTask->created_on !!}
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-2 text-left">
                                        <h5>{{ __('Task competed') }}</h5>
                                    </div>
                                    <div class="col-md-10 text-left">
                                        {!! $task->collabTask->completed_on !!}
                                    </div>
                                </div>
                            @endif
                            <div class="form-group row">
                                <div class="col-md-2 text-left">
                                    <h5>{{ __('Task Hours used') }}</h5>
                                </div>
                                <div class="col-md-10 text-left">
                                    {!! $task->total_hours !!}
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-2 text-left">
                                    <h5>{{ __('Task Price') }}</h5>
                                </div>
                                <div class="col-md-4 text-left">
                                    <input type="text" value="{!! $task->total_eur !!}" wire:model="price"
                                           class="form-control d-inline">
                                    <button type="submit" class="btn btn-primary d-inline" wire:click="updateTotal()">OK</button>

                                    @if($showMessage)
                                        <span class="badge badge-warning pd-10">
                                            Price saved.
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-primary d-inline" wire:click="snoozeTask()">
                                SNOOZE / UNSNOOZE this task until next time
                            </button>
                            <br> <br>
                            @if($task->snoozed)
                                <span class="badge badge-warning pd-10">TASK IS SNOOZED UNTIL NEXT MONTH and will not be included in invoice NOW</span>
                            @endif
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-danger d-inline" wire:click="deleteTask()">
                                DELETE TASK?!
                            </button>
                            <br> <br>
                        </div>
                    </div>

                    <table class="table table-dashboard mg-b-0 table-hover table-striped">
                        <thead>
                        <tr>
                            <th>Booked by</th>
                            <th class="text-center">Booked time</th>
                            <th class="text-center">Billable?</th>
                            <th class="text-center">Total €</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($task->entries as $row)
                            <tr>
                                <td>
                                    {!! $row->employee->getFullName() !!}
                                </td>
                                <td class="text-center">
                                    {!! $row->decimalToTime($row->time) !!} <em>({!! $row->time !!})</em>
                                    on {!! $row->entry_time->format('Y-m-d') !!}
                                </td>
                                <td class="text-center">
                                    @if($row->billable)
                                        <span class="badge badge-success">
                                                YES
                                            </span>
                                    @else
                                        <span class="badge badge-warning">
                                                No
                                            </span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    {!! \App\Label::formatPrice($row->getEntryCharge()) !!}
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
