<div class="card mg-b-10">
    <div class="card-header pd-t-20 d-sm-flex justify-content-between bd-b-0 pd-b-0">
        <div>
            <p class="tx-color-03 mg-b-6 text-center text-success">
                Current bank balance in all company accounts
            </p>
            <h3><strong>{{ \App\Label::formatPrice($bankBalance) }}</strong></h3>
        </div>


        <div class="form-group col-md-6">
            <label for="size">Current bank balance in all company accounts *</label>
            <input type="number" class="form-control" id="size" name="size" wire:model="bankBalance"
                   value="{{ old('size') }}">
        </div>

    </div>
</div>
