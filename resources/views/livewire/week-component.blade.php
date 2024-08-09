<td>
    @foreach($week->getTasks($employee) as $task)
        <livewire:week.task :task="$task" :wire:key="$task->id"/>
    @endforeach
</td>
