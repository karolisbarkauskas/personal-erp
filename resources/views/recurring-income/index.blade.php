@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12 mg-t-10">
            <div class="card mg-b-10">
                <div class="card-header pd-t-20 d-sm-flex align-items-start justify-content-between bd-b-0 pd-b-0">
                    <div>
                        <h6 class="mg-b-5">Recurring income</h6>
                    </div>
                    <div class="d-flex mg-t-20 mg-sm-t-0">
                        <div class="btn-group flex-fill">
                            <a href="{{ route('recurring-income.create') }}" class="btn btn-success btn-sm">
                                Create new
                            </a>
                        </div>
                    </div>
                </div>

                <div class="table-responsive table-hover table-strip">
                    <table class="table table-dashboard mg-b-0">
                        <thead>
                        <tr>
                            <th class="text-center">Client</th>
                            <th class="text-center">Service</th>
                            <th class="text-center">Amount</th>
                            <th class="text-center">Next invoice date</th>
                            <th class="text-center"></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($incomes as $income)
                            <tr>
                                <td class="tx-medium text-center">{{ $income->client->name }}</td>
                                <td class="tx-medium text-center">{{ $income->category->name }}</td>
                                <td class="tx-medium text-center">{{ \App\Label::formatPrice($income->amount) }}</td>
                                <td class="tx-medium text-center">{!! $income->next_invoice_date !!}</td>
                                <td class="tx-medium text-center">
                                    <a href="{{ route('recurring-income.edit', $income) }}" class="btn btn-info btn-xs">
                                        Edit
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
