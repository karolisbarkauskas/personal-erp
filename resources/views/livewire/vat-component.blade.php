<div>
    <strong>
        {{ \App\Label::formatPrice($vatToPay) }}
        <button class="badge badge-info border w-10" wire:click="calculate()">
            Calculate VAT
        </button>
    </strong>
    <hr>
    <div>
        <label for="name">Assign paid VAT</label>
        <input class="form-control w-100" wire:model="reduce"/>
        <button class="badge badge-warning border w-100" wire:click="save()">
            Assign payment
        </button>
    </div>
</div>
