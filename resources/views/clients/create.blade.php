@extends('layouts.app')

@section('content')
    <div class="container pd-x-0 pd-lg-x-10 pd-xl-x-0">

        <h4 id="section3" class="mg-b-10">
            New client
        </h4>

        <div data-label="Example" class="df-example demo-forms">

            <form method="post" action="{{ route('clients.store') }}">
                @csrf
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="name">Company name *</label>
                        <input type="text" class="form-control" id="name" name="name"
                               value="{{ old('name') }}">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="code">Company code *</label>
                        <input type="text" class="form-control" id="code" name="code"
                               value="{{ old('code') }}">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="vat_code">Company VAT code</label>
                        <input type="text" class="form-control" id="vat_code" name="vat_code"
                               value="{{ old('vat_code') }}">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label for="address">Company address *</label>
                        <input type="text" class="form-control" id="address" name="address"
                               value="{{ old('address') }}">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="payment_delay">Payment delay</label>
                        <input type="text" class="form-control" id="payment_delay" name="payment_delay"
                               value="{{ old('payment_delay') }}">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="rate">Hourly rate</label>
                        <input type="text" class="form-control" id="rate" name="rate"
                               value="{{ old('rate') }}">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="vat_size">VAT size *</label>
                        <input type="text" class="form-control" id="vat_size" name="vat_size"
                               value="{{ old('vat_size') }}">
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Create</button>
            </form>
        </div>
    </div>
@endsection
