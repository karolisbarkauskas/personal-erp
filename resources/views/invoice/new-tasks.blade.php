<h3>THERE ARE SOME NEW TASKS ON SOME PROJECTS</h3>

@foreach($tasks as $project => $availableTasks)
<h4>{!! $project !!}</h4>
@foreach($availableTasks as $task)
<span>
    <a href="{!! $projectLink !!}/{{ $task->project_id }}?modal=Task-{!! $task->id !!}-{{ $task->project_id }}">{!! ucfirst($task->name) !!}</a> by {!! strtoupper($task->created_by_name) !!}
</span>
@endforeach
<hr>
@endforeach
