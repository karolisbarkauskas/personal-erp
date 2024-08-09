@if($project->done_count > 0)
    <tr>
        <td>
            {!! $project->getStyledBrand() !!}
        </td>
        <td>
            <a href="https://tasks.onesoft.io/projects/{{ $project->id }}"
               target="_blank">
                {{ $project->name }}
            </a>
        </td>
        <td class="text-center">
            <a href="https://tasks.onesoft.io/projects/{{ $project->id }}"
               target="_blank">
                {{ $project->deployedLive() }} out of {{ $project->done_count }}
            </a>
        </td>
    </tr>
@endif
