@extends('layouts.app')

@section('content')
    <div class="container pd-x-0 pd-lg-x-10 pd-xl-x-0">

        <h4 id="section3" class="mg-b-10">
            {{ $employee->full_name }}
        </h4>
        <div data-label="Example" class="df-example demo-forms">

            <form method="post" action="{{ route('employees.update', $employee) }}">
                @csrf
                @method('PATCH')
                <div class="form-row">
                    <div class="form-group col-md-2">
                        <label for="full_name">Full name *</label>
                        <input type="text" class="form-control" id="full_name" name="full_name"
                               value="{{ old('full_name', $employee->full_name) }}">
                    </div>
                    <div class="form-group col-md-2">
                        <label for="salary_with_vat">Salary with taxes</label>
                        <input type="text" class="form-control" id="salary_with_vat" name="salary_with_vat"
                               value="{{ old('salary_with_vat', $employee->salary_with_vat) }}">
                    </div>
                    <div class="form-group col-md-2">
                        <label for="salary_to_hands">Salary to pay</label>
                        <input type="text" class="form-control" id="salary_to_hands" name="salary_to_hands"
                               value="{{ old('salary_to_hands', $employee->salary_to_hands) }}">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="salary_to_cover">Salary to be covered by other employees</label>
                        <input type="text" class="form-control" id="salary_to_cover" name="salary_to_cover"
                               value="{{ old('salary_to_cover', $employee->salary_to_cover) }}">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="return">Return from LT (UŽT)</label>
                        <input type="text" class="form-control" id="return" name="return"
                               value="{{ old('return', $employee->return) }}">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label for="hourly_rate_without_markup">Hourly rate without markup</label>
                        <input type="text" class="form-control" id="hourly_rate_without_markup" name="hourly_rate_without_markup"
                               readonly
                               value="{{ old('hourly_rate_without_markup', $employee->hourly_rate_without_markup) }}">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="hourly_rate_with_markup">Hourly rate with markup</label>
                        <input type="text" class="form-control" id="hourly_rate_with_markup" name="hourly_rate_with_markup"
                               readonly
                               value="{{ old('hourly_rate_with_markup', $employee->hourly_rate_with_markup) }}">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="hourly_rate_sellable">Current sellable hourly rate</label>
                        <input type="text" class="form-control" id="hourly_rate_sellable" name="hourly_rate_sellable"
                               value="{{ old('hourly_rate_sellable', $employee->hourly_rate_sellable) }}">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label for="employment_start">Employment start</label>
                        <input type="text" class="form-control datepicker" id="employment_start" name="employment_start"
                               value="{{ old('employment_start', $employee->employment_start) }}">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="employment_end">Employment end</label>
                        <input type="text" class="form-control datepicker" id="employment_end" name="employment_end"
                               value="{{ old('employment_end', $employee->employment_end) }}">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="sellable_hours_per_day">Sellable hours per day</label>
                        <input type="text" class="form-control" id="sellable_hours_per_day" name="sellable_hours_per_day"
                               value="{{ old('sellable_hours_per_day', $employee->sellable_hours_per_day) }}">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="markup">Markup</label>
                        <input type="text" class="form-control" id="markup" name="markup"
                               value="{{ old('markup', $employee->markup) }}">
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Update information</button>

                @if(!$employee->isProfitable())
                    <div class="alert alert-danger mt-3">
                        Employee is not profitable!
                    </div>
                @endif
            </form>
        </div>

    </div>
@endsection
