<tr>
    <td>
        <a href="https://invoyer.youtrack.cloud/issue/{{ $task->code }}">
            {{ $task->code }}
        </a>
    </td>
    <td class="text-center @if($task->client_time_sold < $task->client_time_used) tx-danger @endif">
        {{ $task->client_time_sold }}h
    </td>
    <td class="text-center @if($task->client_time_sold < $task->client_time_used) tx-danger @endif">
        {{ $task->client_time_used }}h
    </td>
    <td class="text-center">
        <button class="badge badge-danger" wire:click="delete()">DELETE</button>
    </td>
</tr>
