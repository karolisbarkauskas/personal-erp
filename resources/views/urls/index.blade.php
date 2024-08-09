@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12 mg-t-10">
            <div data-label="Example" class="df-example demo-forms">
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <h5 class="text-center">All urls</h5>
                        <div class="products-container">
                            <div id="urlList"></div>
                        </div>
                    </div>
                </div>
                <hr>
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

            function displayUrls() {
                axios({
                    url: '/urlTable',
                    method: 'get',
                    timeout: 30000,
                    headers: {
                        'Content-Type': 'application/json',
                    }
                })
                    .then(res => {
                        displayProductsList(res.data)
                    })
                    .catch(
                        err => console.error(err)
                    );

                function displayProductsList(tabledata) {
                    $("#urlList").dxTreeList({
                        dataSource: tabledata.urls,
                        keyExpr: "id",
                        parentIdExpr: "parent",
                        columnAutoWidth: true,
                        wordWrapEnabled: true,
                        showBorders: true,
                        showRowLines: true,
                        allowColumnReordering: true,
                        allowColumnResizing: true,
                        scrolling: {
                            mode: "standard"
                        },
                        paging: {
                            enabled: true,
                            pageSize: 20
                        },
                        pager: {
                            showPageSizeSelector: true,
                            allowedPageSizes: [5, 10, 20, 50, 100, 250, 500],
                            showInfo: true
                        },
                        editing: {
                            mode: "cell",
                            allowUpdating: true,
                        },
                        expandedRowKeys: tabledata.expandedRowKeys,
                        selectedRowKeys: tabledata.selectedRowKeys,
                        loadPanel: {
                            enabled: true
                        },
                        filterRow: {
                            visible: true
                        },
                        searchPanel: {
                            visible: false
                        },
                        headerFilter: {
                            visible: false
                        },
                        selection: {
                            mode: "single"
                        },
                        columnChooser: {
                            enabled: false
                        },
                        columns: [
                            {
                                dataField: "id",
                                allowEditing: false,
                                allowHiding: false,
                            },
                            {
                                dataField: "url",
                                allowEditing: false,
                                allowHiding: false,
                                width: 200,
                            },
                            {
                                dataField: "ssl",
                                allowEditing: false,
                                allowHiding: true,
                            },
                            {
                                dataField: "php",
                                allowEditing: false,
                                allowHiding: true,
                            },
                            {
                                dataField: "country_code",
                                allowEditing: false,
                                allowHiding: true,
                            },
                            {
                                dataField: "sitemap_check",
                                allowEditing: false,
                                allowHiding: true,
                            }, {
                                dataField: "sitemap_status",
                                allowEditing: false,
                                allowHiding: true,
                            }, {
                                dataField: "sitemap_size",
                                allowEditing: false,
                                allowHiding: true,
                            },{
                                dataField: "google_speed_count",
                                allowEditing: false,
                                allowHiding: true,
                            },{
                                dataField: "google_speed_check",
                                allowEditing: false,
                                allowHiding: true,
                            },
                            {
                                dataField: "google_speed_status",
                                allowEditing: false,
                                allowHiding: true,
                            },
                            {
                                dataField: "gtmetrix_check",
                                allowEditing: false,
                                allowHiding: true,
                            },
                            {
                                dataField: "gtmetrix_status",
                                allowEditing: false,
                                allowHiding: true,
                            },
                            {
                                name: "command-editing",
                                caption: "Action",
                                allowFiltering: false,
                                allowSorting: false,
                                cellTemplate: function (cellElement, cellInfo) {
                                    cellElement.append("<a class='badge badge-success' href='./url/" + cellInfo.data.id + "/edit'>Show More</a>");
                                }
                            }
                        ],
                        remoteOperations: {
                            filtering: true,
                            sorting: true,
                            grouping: true
                        }
                    });
                }
            };
            if ($("#urlList").length) {
                displayUrls();
            }
        });
    </script>
@endpush
