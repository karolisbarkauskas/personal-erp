@extends('layouts.app')

@section('content')
    <div class="container pd-x-0 pd-lg-x-10 pd-xl-x-0">

        <h4 id="section3" class="mg-b-10">
            New employee
        </h4>

        <div data-label="Example" class="df-example demo-forms">

            <form method="post" action="{{ route('employees.store') }}">
                @csrf
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="full_name">Full name *</label>
                        <input type="text" class="form-control" id="full_name" name="full_name"
                               value="{{ old('full_name') }}">
                    </div>
                </div>


                <button type="submit" class="btn btn-primary">Create</button>
            </form>
        </div>
    </div>
@endsection
