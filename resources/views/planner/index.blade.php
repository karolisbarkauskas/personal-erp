@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12 mg-t-10">
            <div class="card mg-b-10">
                <div class="card-header pd-t-20 d-sm-flex align-items-start justify-content-between bd-b-0 pd-b-0">
                    <div>
                        <h4 class="mg-b-5">
                            Income status by booked time
                        </h4>

                    </div>
                    <div class="d-flex mg-t-20 mg-sm-t-0">
                        <div class="btn-group flex-fill">
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <div class="mg-b-5 pd-10 pd-t-20 d-sm-flex align-items-start justify-content-between bd-b-0 pd-b-0">
                        Income required: {{ $incomeRequired }}€ <br>

                        <form action="{{ route('planner.index') }}" method="get">
                            From: <br>
                            <input type="date" class="form-control" id="active_from" name="active_from"
                                   value="">
                            To: <br>
                            <input type="date" class="form-control" id="active_to" name="active_to"
                                   value="">
                            <button class="btn btn-info w-100">Filter</button>
                        </form>
                    </div>
                    <table class="table table-dashboard mg-b-0 table-hover">
                        <thead>
                        <tr>
                            <th class="wd-90">Project</th>
                            <th class="text-center">Total time booked</th>
                            <th class="text-center">Total non-billable time</th>
                            <th class="text-center">Total billable time</th>
                            <th class="text-center">Total € (billable)</th>
                            <th class="text-center">Total € (NOT - billable)</th>
                            <th class="text-center">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($timeBooked as $projectName => $times)
                            <tr>
                                <td>
                                    <em>{{ $projectName }}</em>
                                </td>
                                <td class="text-center">
                                    {{ $times->decimalToTime($times->getBookedTime()) }}
                                </td>
                                <td class="text-center">
                                    {{ $times->decimalToTime($times->getNotBillableTime()) }}
                                </td>
                                <td class="text-center">
                                    {{ $times->decimalToTime($times->getBillableTime()) }}
                                </td>
                                <td class="text-center text-success">
                                    {{ $times->getTotalBillable($times) }}€
                                </td>
                                <td class="text-center text-danger">
                                    {{ $times->getTotalNotBillable($times) }}€
                                </td>
                                <td class="text-center text-success">
                                    <a href="{{ route('planner.show', [$times->first()->task->project->id, 'from' => $from, 'to' => $to]) }}">
                                        View
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
