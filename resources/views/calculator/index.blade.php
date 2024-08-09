@extends('layouts.app')

@section('content')

    <form action="{{ route('calculator') }}" method="get">
        <div class="row">
            <div class="col-md-10">
                @csrf
                <table class="table table-borderless table-sm tx-13 tx-nowrap mg-b-0 table-hover">
                    <thead>
                    <tr class="tx-10 tx-spacing-1 tx-color-03 tx-uppercase">
                        <th class="text-center">Employee</th>
                        <th class="text-center">Time needed</th>
                        <th class="text-center">Hourly rate</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($employees as $employee)
                        <tr>
                            <td class="text-center">
                                {{ $employee->getFullName() }}
                            </td>
                            <td class="text-center">
                                <input type="text" class="form-control" name="emp[{{ $employee->id }}][time]" value="{{ request()->get('emp')[$employee->id]['time'] ?? '' }}">
                            </td>
                            <td class="text-center">
                                <input type="hidden" class="form-control" name="emp[{{ $employee->id }}][rate]"
                                       value="{{ $employee->getHourlyRate() }}">
                                {{ $employee->getHourlyRate() }}€
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

                <div class="form-group">
                    <label class="d-block">Buffer (%)</label>
                    <input type="text" class="form-control" name="buffer" id="buffer"
                           value="{{ request()->get('buffer', 15) }}">
                </div>

                <button class="btn btn-info mg-t-10" value="1" name="calculator">Calculate</button>
            </div>

            @if($total > 0)
                <div class="col-lg-12 mg-t-10">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mg-b-0">Total estimated price</h6>
                        </div>
                        <div class="card-body tx-center">
                            <h4 class="tx-normal tx-rubik tx-40 tx-spacing--1 mg-b-0">
                                {{ $total }}€
                            </h4>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </form>

@endsection
