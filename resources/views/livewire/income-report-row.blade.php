<tr class="@if($hours <= 0) bg-info @endif">
    <td>
        <input type="text" class="form-control" wire:model="taskId"
               id="taskId" name="text"/>
        @error('taskId') <span class="error">{{ $message }}</span> @enderror
    </td>
    <td>
        <textarea rows="5" class="form-control" wire:model="name"
                  id="name" name="text" /></textarea>
        <input type="text" class="form-control" wire:model="taskLink"
               id="taskLink" name="text" placeholder="Task link (asana/helpdesk/etc)"/>
        @error('name') <span class="error">{{ $message }}</span> @enderror
    </td>
    <td>
        <input type="text" class="form-control" wire:model="hours"
               id="hours" name="text" />
        @error('hours') <span class="error">{{ $message }}</span> @enderror
    </td>
    <td>
        <input type="text" class="form-control" wire:model="hourly_rate"
               id="hourly_rate" name="text" />
        @error('hourly_rate') <span class="error">{{ $message }}</span> @enderror
    </td>
    <td>
        <input type="text" class="form-control" wire:model="total_row"
               id="total_row" name="text" />
        @error('total_row') <span class="error">{{ $message }}</span> @enderror
    </td>
    <td class="text-center">
        <input type="checkbox" wire:model="done"
               id="done" name="text" />
        @error('done') <span class="error">{{ $message }}</span> @enderror
    </td>
    <td class="text-center">
        <input type="checkbox" wire:model="include"
               id="include" name="text" />
        @error('include') <span class="error">{{ $message }}</span> @enderror
    </td>
    <td>
        @if($taskId && $display)
            Profit:
            @if($report->profit > 0)
                <span class="badge badge-success">{{ \App\Label::formatPrice($report->profit) }} <strong>({{ $report->getPercentage() }})%</strong></span>
            @else
                <span class="badge badge-danger">{{ \App\Label::formatPrice($report->profit) }} <strong>({{ $report->getPercentage() }})%</strong></span>
            @endif
            @if($task)
                <div>
                    @foreach($task->weeklyTasks as $weekTask)
                        @if(!$weekTask->isInvoiced())
                            <a href="{{ route('week.edit', $weekTask->task_list_id) }}"
                               class="badge badge-warning" target="_blank">
                                Not completed task in: {{ $weekTask->list->year }}/{{ $weekTask->list->week }}
                            </a>
                        @endif
                    @endforeach
                </div>
            @endif
        @endif
    </td>
    <td>
        <button type="submit" class="badge badge-danger" wire:click="delete()">
            Delete
        </button>
    </td>
</tr>
