<tr>
    <td class="w-25">
        {{ $payable->name }}
    </td>
    <td class="w-25 @if($payable->isOverDue()) tx-danger @else tx-info @endif">
        {{ $payable->deadline->format('Y-m-d') }}
    </td>
    <td class="w-25">
        <input class="form-control w-50 p-0 px-2 float-left" style="height: 20px" wire:model="payable.amount" />
        <button class="badge badge-info border" wire:click="save()">UPD</button>
    </td>
    <td class="w-25">
        <input class="form-control w-50 p-0 px-2 float-left" style="height: 20px" wire:model="payable.amount_paid" />
        <button class="badge badge-info border" wire:click="save()">UPD</button>
    </td>
    <td class="w-25 form-group col-md-6">
        <button class="badge badge-danger border" wire:click="destroy()">X</button>
    </td>
</tr>
