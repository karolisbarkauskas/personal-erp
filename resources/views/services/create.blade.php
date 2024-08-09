@extends('layouts.app')

@section('content')
    <div class="container pd-x-0 pd-lg-x-10 pd-xl-x-0">

        <h4 id="section3" class="mg-b-10">
            New service
        </h4>

        <div data-label="Example" class="df-example demo-forms">

            <form method="post" action="{{ route('service.store') }}">
                @csrf
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="name">Name *</label>
                        <input type="text" class="form-control" id="name" name="name" required
                               value="{{ old('name') }}">
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Create</button>
            </form>
        </div>
    </div>
@endsection
