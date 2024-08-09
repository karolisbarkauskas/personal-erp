@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12 mg-t-10">
            <div class="card mg-b-10">
                <div class="card-header pd-t-20 d-sm-flex align-items-start justify-content-between bd-b-0 pd-b-0">
                    <div>
                        <h6 class="mg-b-5">Employees</h6>
                    </div>
                    <div class="d-flex mg-t-20 mg-sm-t-0">
                        <div class="btn-group flex-fill">
                            <a href="{{ route('employees.create') }}" class="btn btn-success btn-sm">
                                Create new
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container pd-x-0 pd-lg-x-10 pd-xl-x-0">
        <div class="row row-xs mg-b-25">
            @foreach($employees as $employee)
                <div class="col-sm-4 col-md-3 col-lg-4 col-xl-3 mg-t-10">
                    <div class="card card-profile">
                        <div class="card-body tx-13 @if(!$employee->isProfitable()) border-danger @endif">
                            <div>
                                <a href="">
                                    <div class="avatar avatar-lg">

                                    </div>
                                </a>
                                <h5>
                                    <a href="{{ route('employees.edit', $employee->id) }}">
                                        {{ $employee->full_name }}
                                    </a>
                                    <hr>
                                    Sellable rate: <strong>{!! \App\Label::formatPrice($employee->hourly_rate_sellable) !!}</strong> <br>
                                    <small>
                                        Minimal rate:  <strong>{!! \App\Label::formatPrice($employee->hourly_rate_with_markup) !!}</strong> <br>
                                    </small>


                                    <hr>
                                    <a class="btn btn-block btn-white" href="{{ route('employees.edit', $employee->id) }}">
                                        Edit information
                                    </a>
                                </h5>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
