@extends('layouts.app')

@section('content')
    <div class="container pd-x-0 pd-lg-x-10 pd-xl-x-0">

        <h4 id="section3" class="mg-b-10">
            Add new income for <strong><em>{{ auth()->user()->company->name }}</em></strong>
        </h4>

        <div data-label="Example" class="df-example demo-forms">

            <form method="post" action="{{ route('income.store') }}">
                @csrf
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="name">Client *</label>
                        <select name="client" id="client" class="form-control">
                            <option value="">-- Select --</option>
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}"
                                        @if(old('client') == $client->id) selected="selected" @endif>
                                    {{ $client->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @if(!$short)
                        <div class="form-group col-md-4">
                            <label for="name">Status *</label>
                            <select name="status" id="status" class="form-control">
                                <option value="">-- Select --</option>
                                @foreach($statuses as $status)
                                    <option value="{{ $status->id }}"
                                            @if(old('status') == $status->id) selected="selected" @endif>
                                        {{ $status->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                </div>
                <hr>
                @if(!$short)
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="issue_date">Invoice issue date *</label>

                            <input type="date" class="form-control" id="issue_date" name="issue_date"
                                   value="{{ old('issue_date') }}">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="income_date">Income period (month)</label>

                            <input type="date" class="form-control" id="income_date" name="income_date"
                                   value="{{ old('income_date') }}">
                        </div>
                    </div>
                    <hr>
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="vat_size">VAT size % *</label>

                            <input type="text" class="form-control" id="vat_size" name="vat_size"
                                   value="{{ old('vat_size','21') }}">
                        </div>
                    </div>
                    <hr>
                @endif

                <button type="submit" class="btn btn-primary">Create</button>
            </form>
        </div>
    </div>
@endsection
@push('javascript')
    <script type="text/javascript">
        $(document).ready(function () {
            let clientSelector = $('select[name="client"]');
            clientSelector.on("change", function () {
                let clientId = parseInt($(this).val());
                axios({
                    url: '/sales/client/' + clientId,
                    method: 'get',
                    timeout: 30000,
                    headers: {
                        'Content-Type': 'application/json',
                    }
                })
                    .then(res => {
                        $('#saleBlock').replaceWith(res.data)
                    })
                    .catch(
                        err => console.error(err)
                    );
            });
        });
    </script>
@endpush
