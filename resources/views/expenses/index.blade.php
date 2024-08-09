@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12 mg-t-10">
            <div class="card mg-b-10">
                <form method="get" action="{{ route('expenses.index') }}">
                    @csrf
                    @method('GET')
                    <div class="form-group col-md-6">
                        <label for="name">Filter by category *</label>
                        <select name="category_id[]" id="category_id" class="form-control" multiple>
                            <option value="">-- Select --</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}"
                                        @if(in_array($category->id, request()->get('category_id', []))) selected="selected" @endif>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-primary mt-2">Filter</button>
                    </div>
                </form>

                <div class="card-header pd-t-20 d-sm-flex align-items-start justify-content-between bd-b-0 pd-b-0">
                    <div>
                        <h6 class="mg-b-5">Expenses</h6>
                        <p class="tx-13 tx-color-03 mg-b-0">
                            All expenses by month
                        </p>
                    </div>
                    <div class="d-flex mg-t-20 mg-sm-t-0">
                        <div class="btn-group flex-fill">
                            <a href="{{ route('expenses.create') }}" class="btn btn-success btn-sm">
                                Create new
                            </a>
                            <a href="{{ route('import.index') }}" class="btn btn-info btn-sm">
                                Import
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div style="height: 640px;" id="expenses-graph"></div>
        </div>
    </div>
    <div class="col-lg-12 mg-t-10">
        <div class="card mg-b-10">
            <div class="card-header pd-t-20 d-sm-flex align-items-start justify-content-between bd-b-0 pd-b-0">
                <div class="table-responsive">
                    <table id="expenses-table" class="table table-dashboard mg-b-0"></table>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('styles')
    <link rel="stylesheet" href="{{ asset('lib/devextreme/dist/css/dx.common.css') }}">
    <link rel="stylesheet" href="{{ asset('lib/devextreme/dist/css/dx.light.css') }}">
@endpush

@push('javascript')
    <script src="{{ asset('lib/devextreme/dist/js/dx.all.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            function products() {
                axios({
                    url: '/expenses/report',
                    method: 'get',
                    timeout: 30000,
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    @if(request()->get('category_id'))
                    params: {
                        filters: '{{ implode(',', request()->get('category_id', [])) }}'
                    }
                    @endif
                })
                    .then(res => {
                        displayChart(res.data)
                    })
                    .catch(
                        err => console.error(err)
                    );

                function displayChart(dataSource) {
                    $("#expenses-graph").dxChart({
                        dataSource: dataSource.data,
                        commonSeriesSettings: {
                            type: 'spline',
                            argumentField: "month"
                        },
                        series: dataSource.series,
                        legend: {
                            verticalAlignment: "bottom", // or "bottom"
                            horizontalAlignment: "center",
                            position: "outside",

                            border: {visible: true},
                            customizeItems: function (items) {
                                var sortedItems = [];

                                items.forEach(function (item) {
                                    var startIndex = item.series.stack === "male" ? 0 : 3;
                                    sortedItems.splice(startIndex, 0, item);
                                });
                                return sortedItems;
                            }
                        },
                        valueAxis: {
                            title: {
                                text: "Euros"
                            },
                        },
                        title: "Company expenses",
                        "export": {
                            enabled: true
                        },
                        tooltip: {
                            enabled: true,
                            shared: false
                        },
                        onLegendClick: function (e) {
                            var series = e.target;
                            if (series.isVisible()) {
                                series.hide();
                            } else {
                                series.show();
                            }
                        }
                    });
                }
            }

            products();
            $("#expenses-table").DataTable({
                searchDelay: 1000,
                processing: true,
                serverSide: true,
                ordering: true,
                searching: false,
                lengthMenu: [ 10, 25, 50, 75, 100 ],
                pageLength: 25,
                stateSave: true,
                ajax: {
                    url: '/expenses-table'
                },
                order: [[ 0, "desc" ]],
                searchBuilder: {
                    columns: [1,2,3,4,5,6,7],
                    depthLimit: 1
                },
                dom: 'Qfrtlip',
                columns: [
                    {
                        title: 'ID',
                        name: 'id',
                        data: 'expenses.id',
                        className: 'cell-detail',
                        render: function (data, type, full, meta) {
                            return `${full.id}`;
                        },
                    },
                    {
                        title: 'Name',
                        name: 'name',
                        data: 'expenses.name',
                        type: 'string',
                        className: 'cell-detail',
                        render: function (data, type, full, meta) {
                            return `${full.name}`;
                        },
                    },
                    {
                        title: 'File uploaded',
                        data: 'uploaded',
                        className: 'cell-detail',
                        render: function (data, type, full, meta) {
                            return `${full.uploaded ? "<span  class=\"badge badge-success\">Yes</span>" : "<span class=\"badge badge-danger\">No</span>"}`;
                        },
                    },
                    {
                        title: 'Category',
                        name: 'category',
                        data: 'expenses_categories.name',
                        className: 'cell-detail',
                        render: function (data, type, full, meta) {
                            return `${full.category}`;
                        },
                    },
                    {
                        title: 'Payment date',
                        name: 'expense_date',
                        data: 'expenses.expense_date',
                        type: 'date',
                        render: function (data, type, full, meta) {
                            return `${full.expense_date}`;
                        },
                    },
                    {
                        title: 'Amount',
                        type: 'num-fmt',
                        name: 'size',
                        data: 'expenses.size',
                        className: 'cell-detail',
                        render: function (data, type, full, meta) {
                            return `${full.formated_amount}`;
                        },
                    },
                    {
                        title: 'Description',
                        name: 'description',
                        data: 'expenses.description',
                        searchable: false,
                        orderable: false,
                        className: 'cell-detail',
                        render: function (data, type, full, meta) {
                            return `${full.description}`;
                        },
                    },
                    {
                        title: 'Actions',
                        searchable: false,
                        orderable: false,
                        className: 'cell-detail',
                        render: function (data, type, full, meta) {
                            return `<a href="/expenses/${full.id}/edit" class="badge badge-info">Edit</a>`;
                        },
                    }
                ]
            });
        });
    </script>
@endpush
