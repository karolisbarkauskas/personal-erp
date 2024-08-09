<tr>
    <td class="w-25">{{ $dept->name }}</td>
    <td class="w-25">{{ \App\Label::formatPrice($dept->size) }}</td>
    <td class="w-25">
        <input class="form-control" wire:model="reduce" />
        <button class="badge badge-warning border" wire:click="save()">
            Assign payment
        </button>
    </td>
</tr>
