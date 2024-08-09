<form method="post" id="rateHistoryForm" class="col-md-12" action="{{ route('rateHistory.update', $rateHistory) }}">
    <div class="form-row">
        @csrf
        @method('PATCH')
        <div class="form-group col-md-4">
            <label data-toggle="tooltip" title="(Bruto - with taxes; Neto - without taxes)" for="salary_bruto">Monthly salary (bruto)*</label>
            <input type="text" class="form-control" id="salary_bruto" name="salary_bruto"
                   value="{{ old('cost', $rateHistory->salary_bruto) }}">
        </div>
        <div class="form-group col-md-4">
            <label for="rate">Hourly rate</label>
            <input type="text" class="form-control" id="rate" name="rate"
                   value="{{ old('rate', $rateHistory->rate) }}">
        </div>
        <div class="form-group col-md-4">
            <label for="from">Date *</label>
            <input type="date" class="form-control" id="from" name="from"
                   value="{{ old('date', \Carbon\Carbon::createFromTimeString($rateHistory->from)->format('Y-m-d')) }}">
        </div>
        <div class="form-group col-md-12">
            <button type="submit" class="btn btn-primary mt-2 w-100">Update</button>
        </div>
    </div>
</form>
