@extends('layouts.app')

@section('content')
    <div class="container pd-x-0 pd-lg-x-10 pd-xl-x-0">
        <h4 id="section3" class="mg-b-10">
            New recurring income
        </h4>

        <div data-label="Example" class="df-example demo-forms">

            <form method="post" action="{{ route('recurring-income.store') }}">
                @csrf
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="client_id">Client *</label>
                        <select name="client_id" id="client_id" class="form-control">
                            <option value="">-- SELECT a client --</option>
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}"
                                        @if(old('client_id') == $client->id) selected="selected" @endif>
                                    {{ $client->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="service_id">Service *</label>
                        <select name="service_id" id="service_id" class="form-control">
                            <option value="">-- SELECT a service --</option>
                            @foreach($services as $service)
                                <option value="{{ $service->id }}"
                                        @if(old('service_id') == $service->id) selected="selected" @endif>
                                    {{ $service->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="date">Invoice generation day *</label>
                        <input type="text" class="form-control" id="date" name="date"
                               value="{{ old('date') }}">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="amount">Size (without VAT) *</label>
                        <input type="text" class="form-control" id="amount" name="amount"
                               value="{{ old('amount') }}">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="vat_size">VAT size *</label>
                        <input type="text" class="form-control" id="vat_size" name="vat_size"
                               value="{{ old('vat_size') }}">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="service_line">Service line in invoice (date will be appended) *</label>
                        <input type="text" class="form-control" id="service_line" name="service_line"
                               value="{{ old('service_line') }}" />
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="period">Service purchase period in months *</label>
                        <input type="text" class="form-control" id="period" name="period"
                               value="{{ old('period') }}">
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Create</button>
            </form>
        </div>
    </div>
@endsection
