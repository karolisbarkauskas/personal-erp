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
                    <div class="mg-b-5 pd-10 pd-t-20">
                        @foreach($timeBooked as $projectName => $times)
                            <em><strong>{{ $projectName }} <br></strong></em>
                            <hr>
                            @if($times->getBillableTime() > 0)
                                <span class="text-success">
                                    Profitable time {{ $times->getTotalBillable($times) }}€ <br>
                                </span>
                            @endif
                            @if($times->getNotBillableTime() > 0)
                                <span class="text-danger">
                                    Non-profitable time {{ $times->getTotalNotBillable($times) }}€ <br>
                                </span>
                            @endif
                            <hr>
                            Total hours worked:
                            <span class="text-info">
                                {{ $times->decimalToTime($times->getBookedTime()) }}
                            </span>
                        @endforeach
                    </div>
                    <table class="table table-dashboard mg-b-0 table-hover">
                        <thead>
                        <tr>
                            <th class="wd-90">Time booked by</th>
                            <th class="wd-90">Time booked</th>
                            <th class="wd-500 text-center">Task</th>
                            <th class="wd-90">Entry comment</th>
                            <th class="text-center">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($timeBooked as $projectName => $times)
                            @foreach($times as $time)
                                <tr class="@if($time->billable_status) text-success @else text-danger @endif">
                                    <td class="p-0 px-3">
                                        <em>{{ $time->created_by_name }}</em>
                                    </td>
                                    <td class="p-0 px-3">
                                        {{ $times->decimalToTime($time->value) }}
                                    </td>
                                    <td class="text-center wd-80-f">
                                        {{ $time->task->name }}
                                    </td>
                                    <td class="text-center wd-80-f">
                                        {{ $time->summary }}
                                    </td>
                                    <td class="text-center">
                                        <form action="{{ route('planner.update', $time) }}" method="post">
                                            @csrf
                                            @method('PATCH')
                                            @if($time->billable_status)
                                                <input type="hidden" name="billable" value="0">
                                                <button class="text-info">Mark time as non-billable</button>
                                            @else
                                                <input type="hidden" name="billable" value="1">
                                                <button class="text-info">Mark time as billable</button>
                                            @endif
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
