@extends('layouts.app')

@section('content')
    <div class="container pd-x-0 pd-lg-x-10 pd-xl-x-0">

        <h4 id="section3" class="mg-b-10">
            EDIT service: {{ $service->name }}
        </h4>

        <div data-label="Example" class="df-example demo-forms">

            <form method="post" action="{{ route('service.update', $service) }}">
                @csrf
                @method('PATCH')
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="name">Service name *</label>
                        <input type="text" class="form-control" id="name" name="name"
                               value="{{ old('name', $service->name) }}">
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">UPDATE</button>
            </form>
        </div>

        <div class="mt-5">
            Total recurring income average per month <strong>{{ \App\Label::formatPrice($recurringIncome) }}</strong>
        </div>

    </div>
@endsection
