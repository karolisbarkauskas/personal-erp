<tr>
    <td>
        {{ $resource->employee->full_name }}
        @if($resource->getTotalHoursUsed() > $resource->employee_time_available)
            <span class="badge badge-danger tx-white p-2">‼️ OVERAGE ‼️</span>
        @endif
    </td>
    <td>
        <input type="text" class="form-control w-25 mx-auto" wire:model="resource.client_time_sold"/>
    </td>
    <td class="text-center">
        {{ $resource->employee_time_available }}h
    </td>
    <td class="text-center">
        {{ $resource->getTotalHoursUsed() }}h
    </td>
    <td>
        <button class="btn btn-dark" wire:click="update()">Update</button>
    </td>
</tr>
