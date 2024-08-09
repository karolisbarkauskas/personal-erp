@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12 mg-t-10">
            <div class="card mg-b-10">
                <div class="card-header pd-t-20 d-sm-flex align-items-start justify-content-between bd-b-0 pd-b-0">
                    <div>
                        <h6 class="mg-b-5">Recurring expenses</h6>
                        <p class="tx-13 tx-color-03 mg-b-0">
                            Recurring expenses list
                        </p>
                    </div>
                    <div class="d-flex mg-t-20 mg-sm-t-0">
                        <div class="btn-group flex-fill">
                            <a href="{{ route('recurring-expenses.create') }}" class="btn btn-success btn-sm">Create new</a>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-dashboard mg-b-0">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th class="text-center">Name</th>
                            <th class="text-center">Category</th>
                            <th class="text-center">Size</th>
                            <th class="text-center">Increase / Decrease</th>
                            <th class="text-center"></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($expenses as $expense)
                            <tr>
                                <td class="tx-color-03 tx-normal">{{ $expense->id }}</td>
                                <td class="tx-medium text-center">{{ $expense->name }}</td>
                                <td class="tx-medium text-center">{{ $expense->expenseCategory->name }}</td>
                                <td class="tx-medium text-center">{{ $expense->size }}€</td>
                                <td class="tx-medium text-center">{!! $expense->getSizeChanges() !!}</td>
                                <td class="tx-medium text-center">
                                    <a href="{{ route('recurring-expenses.edit', $expense) }}" class="btn btn-info btn-xs">
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
