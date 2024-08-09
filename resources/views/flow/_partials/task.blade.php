<li class="list-item">
    <div class="media align-items-center">
        <div class="media-body mg-sm-l-15">
            <p class="tx-medium mg-b-0">
                <a href="https://tasks.onesoft.io/projects/{{ $task->project_id }}?modal=Task-{!! $task->id !!}-{{ $task->project_id }}"
                   target="_blank">
                    #{!! $task->task_number !!} - {!! $task->name !!}
                </a>
            </p>

            <span class="tx-12 mg-b-0 tx-black">
                {{ $task->responsible ? $task->responsible->getFullName() : 'No assignee' }}
            </span>
            <span class="badge badge-{{ $task->getTaskClass() }}">
                {{ $task->getOverTime() }}
            </span>
        </div>
    </div>
</li>
