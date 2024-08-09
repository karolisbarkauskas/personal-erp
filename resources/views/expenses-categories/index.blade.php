@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12 mg-t-10">
            <div class="card mg-b-10">
                <div class="card-header pd-t-20 d-sm-flex align-items-start justify-content-between bd-b-0 pd-b-0">
                    <div>
                        <h6 class="mg-b-5">All expenses categories</h6>
                        <p class="tx-13 tx-color-03 mg-b-0">
                            Expenses categories
                        </p>
                    </div>
                    <div class="d-flex mg-t-20 mg-sm-t-0">
                        <div class="btn-group flex-fill">
                            <a href="{{ route('expenses-categories.create') }}" class="btn btn-success btn-sm">Create new</a>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-dashboard mg-b-0">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th class="text-center">Name</th>
                            <th class="text-center">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($categories as $category)
                            <tr>
                                <td class="tx-color-03 tx-normal">{{ $category->id }}</td>
                                <td class="tx-medium text-center">{{ $category->name }}</td>
                                <td class="tx-medium text-center">
                                    <a href="{{ route('expenses-categories.edit', $category) }}" class="btn btn-info btn-xs">Edit</a>
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
