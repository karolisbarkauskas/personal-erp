@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12 mg-t-10">
            <div class="card mg-b-10">
                <div class="card-header pd-t-20 d-sm-flex align-items-start justify-content-between bd-b-0 pd-b-0">
                    <div>
                        <h6 class="mg-b-5">Expenses</h6>
                        <p class="tx-13 tx-color-03 mg-b-0">
                            All expenses by month
                        </p>
                    </div>
                    <div class="d-flex mg-t-20 mg-sm-t-0">
                        <div class="btn-group flex-fill">
                            <a href="{{ route('clients.create') }}" class="btn btn-success btn-sm">
                                Create new
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 mg-t-10">
            <div class="card mg-b-10">
                <div class="card-header pd-t-20 d-sm-flex align-items-start justify-content-between bd-b-0 pd-b-0">
                    <div class="table-responsive">
                        <table id="client-table" class="table table-dashboard mg-b-0"></table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('javascript')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.6.0/dist/chart.min.js"></script>

    <script type="text/javascript">
        $(document).ready(function () {
            $("#client-table").DataTable({
                searchDelay: 1000,
                processing: true,
                serverSide: true,
                ordering: true,
                searching: true,
                order: [[0, "desc"]],
                stateSave: true,
                lengthMenu: [ 10, 25, 50, 75, 100 ],
                pageLength: 25,
                ajax: {
                    url: 'client-table'
                },
                dom: 'frtlip',
                columns: [
                    {
                        title: 'Client',
                        name: 'name',
                        searchable: false,
                        className: 'cell-detail',
                        render: function (data, type, full, meta) {
                            return `${full.name}`;
                        },
                    },
                    {
                        title: 'Income (curr.y)',
                        type: 'num-fmt',
                        name: 'income_this_year',
                        searchable: false,
                        orderable: false,
                        className: 'cell-detail',
                        render: function (data, type, full, meta) {
                            return `${full.income_this_year_display}`;
                        },
                    },
                    {
                        title: 'Income (all)',
                        name: 'total_income',
                        type: 'num-fmt',
                        searchable: false,
                        orderable: false,
                        className: 'cell-detail',
                        render: function (data, type, full, meta) {
                            return `${full.total_income_display}`;
                        },
                    }, {
                        title: 'Debt (all)',
                        name: 'debt',
                        type: 'num-fmt',
                        searchable: false,
                        orderable: false,
                        className: 'cell-detail',
                        render: function (data, type, full, meta) {
                            return `${full.debt_display}`;
                        },
                    },
                    {
                        title: 'Invoices',
                        name: 'invoices',
                        searchable: false,
                        orderable: false,
                        className: 'cell-detail',
                        render: function (data, type, full, meta) {
                            return `${full.invoices}`;
                        },
                    },
                    {
                        title: 'Actions',
                        searchable: false,
                        orderable: false,
                        className: 'cell-detail',
                        render: function (data, type, full, meta) {
                            return `<a href="/clients/${full.id}/edit" class="badge badge-info">Edit</a>`;
                        },
                    }
                ]
            });
        });
    </script>
@endpush
