@if($project->testing->isNotEmpty())
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
                {{ $project->testing->count() }}
            </a>
        </td>
    </tr>
@endif
