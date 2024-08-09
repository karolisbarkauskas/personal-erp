<div>
    <table class="table table-striped table-hover">
        <tbody>
            @foreach($payables as $payable)
                <livewire:expenses.payable :payable="$payable" :wire:key="'gg-' .$payable->id" >
            @endforeach
            <tr>
                <td>
                    <input class="form-control w-100" placeholder="Name" wire:model.defer="name" />
                </td>
                <td>
                    <input type="date" class="form-control w-100" wire:model.defer="date" />
                </td>
                <td>
                    <input class="form-control w-100" placeholder="Sum" wire:model.defer="sum" />
                </td>
                <td>
                    <button class="badge badge-info border w-50" wire:click="save()">Create</button>
                </td>
            </tr>
            <tr>
                <td class="text-right" colspan="2"><em>Total:</em></td>
                <td class="text-center"><strong>{{ \App\Label::formatPrice($payables->sum('amount')) }}</strong></td>
            </tr>
        </tbody>
    </table>
</div>
