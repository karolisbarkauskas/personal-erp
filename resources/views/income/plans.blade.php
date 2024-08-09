@extends('layouts.app')

@push('styles')
    <style>
        progress.danger::-moz-progress-bar {
            background: #dc3545;
        }

        progress.danger::-webkit-progress-value {
            background: #dc3545;
        }

        progress.danger {
            color: #dc3545;
        }

        progress.success::-moz-progress-bar {
            background: #10b759;
        }

        progress.success::-webkit-progress-value {
            background: #10b759;
        }

        progress.success {
            color: #10b759;
        }
    </style>
@endpush

@section('content')
    <div class="row">
        <div class="col-lg-12 mg-t-10">
            <div class="card mg-b-10">
                <div class="card-header pd-t-20 d-sm-flex align-items-start justify-content-between bd-b-0 pd-b-0">
                    <div>
                        @foreach($companies as $company)
                            <a href="{{ route('income.company', $company) }}"
                               class="btn btn-brand-02 btn-sm mb-3">
                                {{ $company->name }}
                            </a>
                        @endforeach
                    </div>
                    <div class="d-flex mg-t-20 mg-sm-t-0">
                        <div class="btn-group flex-fill">
                            <a href="{{ route('income.create', ['short' => 1]) }}" class="btn btn-success btn-sm">
                                Create new
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-12 mg-t-10">
            <div class="card mg-b-10">
                <div class="card-header pd-t-20 d-sm-flex align-items-start justify-content-between bd-b-0 pd-b-0">
                    <div class="table-responsive">
                        <table id="income-table" class="table table-dashboard mg-b-0">
                            <thead>

                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('javascript')
    <script type="text/javascript">
        $(document).ready(function () {
            $("#income-table").DataTable({
                searchDelay: 1000,
                processing: true,
                serverSide: true,
                ordering: true,
                searching: false,
                lengthMenu: [10, 25, 50, 75, 100],
                pageLength: 25,
                stateSave: true,
                ajax: {
                    url: '/pre-income-table'
                },
                order: [[0, "desc"]],
                drawCallback: function (settings) {
                    $('#total_order').html(settings.json.total_sum);
                },
                dom: 'Qfrtlip',
                columns: [
                    {
                        title: 'Client',
                        name: 'client_name',
                        data: '{{ env('DB_DATABASE_COLLAB').'.companies.name' }}',
                        orderable: false,
                        className: 'cell-detail',
                        render: function (data, type, full, meta) {
                            return `${full.client}`;
                        },
                    },
                    {
                        title: 'Amount',
                        type: 'num-fmt',
                        name: 'amount',
                        data: 'incomes.amount',
                        className: 'cell-detail',
                        render: function (data, type, full, meta) {
                            return `${full.formated_amount}`;
                        },
                    },
                    {
                        title: 'Actions',
                        searchable: false,
                        orderable: false,
                        className: 'cell-detail',
                        render: function (data, type, full, meta) {
                            return `<a href="/income/${full.id}/edit" class="badge badge-info">Edit</a> <a id="deleteBundleButton-${full.id}" href="#"
                            data-delete-button="/income/${full.id}"
                            class="badge badge-danger">Remove</a>`;
                        },
                    }
                ]
            });
            var table = $('#income-table').DataTable();

            table.on('draw', function () {
                handleDeleteButton();
            });
        });

        function handleDeleteButton() {
            var table = $('#income-table').DataTable();
            $("[id^='deleteBundleButton']").on('click', function (e) {

                e.preventDefault();
                Swal.fire({
                    title: 'Are you sure?',
                    text: "This action is not reversible!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes'
                }).then((result) => {
                    if (result.value) {
                        let url = $(this).data('delete-button');
                        $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            method: 'DELETE',
                            url: url,
                            success: function (response) {
                                if (response) {
                                    table.draw();
                                }

                            }
                        });
                    }
                });
            });
        }
    </script>
@endpush
