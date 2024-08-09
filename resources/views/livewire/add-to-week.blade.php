<div>
    @if($week->getTasks($employee)->sum('employee_hours') >= ($week->getWeeklyWorkHours($employee)))
        <div class="text-success">{{ $employee->full_name }}</div>
    @else
        <div class="text-danger">{{ $employee->full_name }}</div>
    @endif

        <span title="Used employee time">{{ $week->getTasks($employee)->sum('employee_hours') }}h</span> /
        <span title="Available hours">{{ $week->getWeeklyWorkHours($employee) }}h</span> /
        <span title="SOLD time to clients">{{ $week->getSoldTimePercentage($employee) }}%</span>
        <span class="text-info" style="cursor: pointer" wire:click="toggleVisibility">E</span>
        @if($isVisible)
            <input type="text" class="w-25" wire:model="hours" />
        @endif
        <br>

        <span class="text-info" style="cursor: pointer" wire:click="addToWeek()">+</span>
</div>
