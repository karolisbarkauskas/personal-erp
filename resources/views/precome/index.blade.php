@extends('layouts.app')

@section('content')

    <div class="row">
        <div class="col-md-5">
            <div class="card mg-b-10">
                <div class="card-body pd-y-30">
                    <div class="d-sm-flex">
                        <h5>
                            Report is generated every months first day 9:00 for last month period. <br>
                            Total Hours booked: <strong>{!! $precome->sum('total_hours') !!}h</strong> <br>
                            Total Preliminary income:
                            <strong>{!! \App\Label::formatPrice($precome->sum('total_eur')) !!}</strong> <br>
                        </h5>
                    </div>
                </div><!-- card-body -->
            </div><!-- card -->

        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 mg-t-10">
            <div class="card mg-b-10">
                <div class="card-header pd-t-20 d-sm-flex align-items-start justify-content-between bd-b-0 pd-b-0">
                    <div>
                        <h6 class="mg-b-5">Pre incomes for last month</h6>
                    </div>
                </div>

                <div class="table-responsive table-hover">
                    <table class="table table-dashboard mg-b-0">
                        <thead>
                        <tr>
                            <th class="text-center">Client</th>
                            <th class="text-center">Team</th>
                            <th class="text-center">Project</th>
                            <th class="text-center">Only closed</th>
                            <th class="text-center">Hours spent last month</th>
                            <th class="text-center">Preliminary income</th>
                            <th class="text-center">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach($precome as $income)
                                <tr>
                                    <td class="text-center">
                                        {!! $income->client->name !!}
                                    </td>
                                    <td class="text-center">
                                        {!! $income->client->getCategoryName() !!}
                                    </td>
                                    <td class="text-center">
                                        @if ($income->project_id)
                                            {!! $income->project->name !!}
                                        @else
                                            ---
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if ($income->client->only_solved)
                                            <span class="badge badge-success">Yes</span>
                                        @else
                                            <span class="badge badge-danger">No</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        {!! $income->total_hours !!}h
                                    </td>
                                    <td class="text-center">
                                        {!! \App\Label::formatPrice($income->total_eur) !!}
                                    </td>
                                    <td class="text-center">
                                        <a href="{!! route('precome.edit', $income) !!}" class="btn btn-info btn-xs">
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
