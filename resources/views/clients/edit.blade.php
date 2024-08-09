@extends('layouts.app')

@section('content')
    <div class="container pd-x-0 pd-lg-x-10 pd-xl-x-0">

        <h4 id="section3" class="mg-b-10">
            {{ $client->name }}
        </h4>
        <div class="row">
            <div data-label="Example" class="df-example demo-forms col-md-6">
                <form method="post" action="{{ route('clients.update', $client) }}">
                    @csrf
                    @method('PATCH')

                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="name">Company name *</label>
                            <input type="text" class="form-control" id="name" name="name"
                                   value="{{ old('name', $client->name) }}">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="code">Company code *</label>
                            <input type="text" class="form-control" id="code" name="code"
                                   value="{{ old('code', $client->code) }}">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="vat_code">Company VAT code</label>
                            <input type="text" class="form-control" id="vat_code" name="vat_code"
                                   value="{{ old('vat_code', $client->vat_code) }}">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="address">Company address *</label>
                            <input type="text" class="form-control" id="address" name="address"
                                   value="{{ old('address', $client->address) }}">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="project">Project name *</label>
                            <input type="text" class="form-control" id="project" name="project"
                                   value="{{ old('project', $client->project) }}">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="payment_delay">Payment delay</label>
                            <input type="text" class="form-control" id="payment_delay" name="payment_delay"
                                   value="{{ old('payment_delay', $client->payment_delay) }}">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="rate">Hourly rate</label>
                            <input type="text" class="form-control" id="rate" name="rate"
                                   value="{{ old('rate', $client->rate) }}">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="vat_size">VAT size *</label>
                            <input type="text" class="form-control" id="vat_size" name="vat_size"
                                   value="{{ old('vat_size', $client->vat_size) }}">
                        </div>
                        <div class="form-group col-md-5">
                            <label for="hourly_diff_reset">
                                Hours reset value for sold Time.
                            </label>
                            <input type="text" class="form-control" disabled
                                   id="hourly_diff_reset" name="hourly_diff_reset"
                                   value="{{ old('hourly_diff_reset', $client->hourly_diff_reset) }}">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="hourly_diff_reset">
                               Client credit
                            </label>
                            <input type="text" class="form-control"
                                   id="credit" name="credit"
                                   value="{{ old('credit', $client->credit) }}">
                        </div>
                        <div class="form-group col-md-12">
                            <label for="comment">Comment *</label>
                            <textarea name="comment" id="comment"
                                      class="form-control"
                                      cols="30" rows="10">{{ old('comment', $client->comment) }}</textarea>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        Update client main information
                    </button>
                </form>

                <h5 id="section3" class="mg-b-10 mg-t-15">
                    Client contact information for emails sending
                </h5>
                <div class="table-responsive">
                    <table class="table table-dashboard mg-b-0 table-hover">
                        <thead>
                        <tr>
                            <th class="text-center">Name</th>
                            <th class="text-center">Email</th>
                            <th class="text-center">Phone</th>
                            <th class="text-center"></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($client->contacts as $contact)
                            <tr>
                                <td class="text-center">{{ $contact->full_name }}</td>
                                <td class="text-center">
                                    <a href="mailto:{{ $contact->email }}">{{ $contact->email }}</a>
                                </td>
                                <td class="text-center">
                                    {{ $contact->phone ?? 'N/A' }}
                                </td>
                                <td>
                                    <form action="{{ route('client-contact.destroy', $contact) }}" method="post">
                                        @method('DELETE')
                                        @csrf

                                        <button type="submit" class="badge badge-danger w-100">
                                            DELETE
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                <form action="{{ route('client-contact.store') }}" method="post">
                    @csrf
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="full_name">Full name</label>
                            <input type="text" class="form-control" id="full_name" name="full_name" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="phone">Phone</label>
                            <input type="text" class="form-control" id="phone" name="phone" required>
                        </div>
                        <div class="form-group col-md-12">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                            <input type="hidden" class="form-control" id="client_id" name="client_id"
                                   value="{{ $client->id }}">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        Add new contact
                    </button>
                </form>
                <div class="card">
                    <div class="card-header pd-b-0 pd-x-20 bd-b-0">
                        <div class="d-sm-flex align-items-center justify-content-between">
                            <h6 class="mg-b-0">Recent Activities</h6>
                        </div>
                    </div><!-- card-header -->
                    <div class="card-body pd-20">
                        <ul class="activity tx-13">
                            @foreach($activity as $act)
                                <li class="activity-item">
                                    <div class="activity-icon bg-primary-light tx-primary">
                                        <i data-feather="clock"></i>
                                    </div>
                                    <div class="activity-body">
                                        <p class="mg-b-2">
                                            <strong>{{ $act->causer->name }}</strong> {{ $act->description }}
                                        </p>
                                        @foreach($act->properties as $key => $prop)
                                            <a href="" class="link-02">
                                                {{ $key }} - <strong>{{ $prop }}</strong> <br>
                                            </a>
                                        @endforeach
                                        <small class="tx-color-03">{{ $act->created_at->diffForHumans() }}</small>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            <div data-label="Example" class="df-example demo-forms col-md-6">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card mt-2">
                            <div class="card-header">
                                <h6 id="section3" class="mg-b-10">Server service income</h6>
                            </div>
                            <div class="card-body tx-center mg-t-0">
                                <table class="table table-dashboard mg-b-0 table-hover">
                                    <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th class="text-center">Date</th>
                                        <th class="text-center">Number</th>
                                        <th class="text-center">Income</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center"></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($dept as $invoice)
                                        <tr>
                                            <td class="tx-color-03 tx-normal">{{ $invoice->id }}</td>
                                            <td class="tx-medium text-center">{{ $invoice->issue_date }}</td>
                                            <td class="tx-medium text-center">{{ $invoice->invoice_no }}</td>
                                            <td class="tx-medium text-center">{{ \App\Label::formatPrice($invoice->amount) }}</td>
                                            <td class="tx-medium text-center">
                                                    <span class="badge badge-{{ $invoice->getStatusClass() }}">
                                                        {{ optional($invoice->incomeStatus)->name }}
                                                    </span>
                                            </td>
                                            <td class="tx-medium text-center">
                                                <a href="{{ route('income.edit', $invoice) }}"
                                                   class="btn btn-info btn-xs">
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
                    <div class="col-lg-12">
                        <div class="card mt-2">
                            <div class="card-header">
                                <h6 id="section3" class="mg-b-10">Unpaid invoices</h6>
                            </div>
                            <div class="card-body tx-center mg-t-0">
                                <table class="table table-dashboard mg-b-0 table-hover">
                                    <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th class="text-center">Date</th>
                                        <th class="text-center">Number</th>
                                        <th class="text-center">Income</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center"></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($dept as $invoice)
                                        <tr>
                                            <td class="tx-color-03 tx-normal">{{ $invoice->id }}</td>
                                            <td class="tx-medium text-center">{{ $invoice->issue_date }}</td>
                                            <td class="tx-medium text-center">{{ $invoice->invoice_no }}</td>
                                            <td class="tx-medium text-center">{{ \App\Label::formatPrice($invoice->amount) }}</td>
                                            <td class="tx-medium text-center">
                                                    <span class="badge badge-{{ $invoice->getStatusClass() }}">
                                                        {{ optional($invoice->incomeStatus)->name }}
                                                    </span>
                                            </td>
                                            <td class="tx-medium text-center">
                                                <a href="{{ route('income.edit', $invoice) }}"
                                                   class="btn btn-info btn-xs">
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
                    <div class="col-lg-12">
                        <div class="card mt-2">
                            <div class="card-header">
                                <h6 class="mg-b-0">Income in {{ date('Y') }}</h6>
                            </div>
                            <div class="card-body tx-center">
                                <h4 class="tx-normal tx-rubik tx-40 tx-spacing--1 mg-b-0">
                                    {{ \App\Label::formatPrice($client->getTotalIncomeThisYear()) }}
                                </h4>
                                <p class="tx-12 tx-uppercase tx-semibold tx-spacing-1 tx-color-02">
                                    without VAT
                                </p>
                            </div>
                        </div>
                        <div class="card mt-2">
                            <div class="card-header">
                                <h6 class="mg-b-0">Total income</h6>
                            </div>
                            <div class="card-body tx-center">
                                <h4 class="tx-normal tx-rubik tx-40 tx-spacing--1 mg-b-0">
                                    {{ \App\Label::formatPrice($client->getTotalIncome()) }}
                                </h4>
                                <p class="tx-12 tx-uppercase tx-semibold tx-spacing-1 tx-color-02">
                                    without VAT
                                </p>
                            </div>
                        </div>
                        <div class="card mt-2">
                            <div class="card-header">
                                <h6 class="mg-b-0">Last 12 invoices</h6>
                            </div>
                            <div class="card-body tx-center mg-t-0">
                                <table class="table table-dashboard mg-b-0 table-hover">
                                    <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th class="text-center">Date</th>
                                        <th class="text-center">Number</th>
                                        <th class="text-center">Income</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center"></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($client->getLastInvoices(12) as $invoice)
                                        <tr>
                                            <td class="tx-color-03 tx-normal">{{ $invoice->id }}</td>
                                            <td class="tx-medium text-center">{{ $invoice->issue_date }}</td>
                                            <td class="tx-medium text-center">{{ $invoice->invoice_no }}</td>
                                            <td class="tx-medium text-center">{{ \App\Label::formatPrice($invoice->amount) }}</td>
                                            <td class="tx-medium text-center">
                                                <span class="badge badge-{{ $invoice->getStatusClass() }}">
                                                    {{ optional($invoice->incomeStatus)->name }}
                                                </span>
                                            </td>
                                            <td class="tx-medium text-center">
                                                <a href="{{ route('income.edit', $invoice) }}"
                                                   class="btn btn-info btn-xs">
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
            let clientId = $('#clientid').val()

            function chart() {
                axios({
                    url: '/report/client/' + clientId + '/income',
                    method: 'get',
                    timeout: 30000,
                    headers: {
                        'Content-Type': 'application/json',
                    }
                })
                    .then(res => {
                        displayIncomeChart(res.data)
                    })
                    .catch(
                        err => console.error(err)
                    );
                axios({
                    url: '/report/client/' + clientId + '/task',
                    method: 'get',
                    timeout: 30000,
                    headers: {
                        'Content-Type': 'application/json',
                    }
                })
                    .then(res => {
                        displayTaskChart(res.data)
                    })
                    .catch(
                        err => console.error(err)
                    );
            }
            function displayTaskChart(dataSource) {
                const lineStyles = ['waved', 'straight'];

                const breaksCount = [1, 2, 3, 4];
                $("#task-performance").dxChart({
                    palette: "SoftBlue",
                    dataSource: dataSource.data,
                    commonSeriesSettings: {
                        argumentField: "month",
                        type: "spline"
                    },
                    argumentAxis: {
                        label: {
                            overlappingBehavior: "stagger"
                        }
                    },
                    margin: {
                        bottom: 20
                    },
                    valueAxis: [{
                        grid: {
                            visible: true
                        },
                        title: {
                            text: "Tasks"
                        }
                    }, {
                        name: "total",
                        position: "right",
                        autoBreaksEnabled: true,
                        maxAutoBreakCount: breaksCount[2],
                        breakStyle: {
                            line: lineStyles[0],
                        },
                        grid: {
                            visible: true
                        },
                        title: {
                            text: "Task duration"
                        }
                    }],
                    series: [
                        {
                            type: "bar",
                            valueField: "created",
                            name: "New",
                            color: "#afad00"
                        },
                        {
                            type: "bar",
                            valueField: "closed",
                            name: "Closed",
                            color: "#10b759"

                        },    {
                            type: "bar",
                            valueField: "active",
                            name: "Active",
                            color: "#af002f",

                        },
                        {
                            axis: "total",
                            valueField: "avg",
                            color: "#0d00af",
                            name: "Duration",
                            valueErrorBar: {
                                lowValueField: 'avgMin',
                                highValueField: 'avgMax',
                                lineWidth: 2
                            },
                        }
                    ],
                    tooltip: {
                        enabled: true,
                        customizeTooltip(arg) {
                            if (arg.seriesName == 'Duration') {
                                return {
                                    text: `${arg.seriesName}: ${arg.value} days ( range: ${arg.lowErrorValue} - ${arg.highErrorValue})`,
                                };
                            }
                        },
                    },
                    legend: {
                        verticalAlignment: "bottom",
                        horizontalAlignment: "center"
                    },
                    "export": {
                        enabled: true
                    },

                    title: "Task performance"
                }).dxChart("instance");
            }

            function displayIncomeChart(dataSource) {
                $("#hours-income-graph").dxChart({
                    palette: "SoftBlue",
                    dataSource: dataSource.data,
                    commonSeriesSettings: {
                        argumentField: "year",
                        type: "spline"
                    },
                    argumentAxis: {
                        label: {
                            overlappingBehavior: "stagger"
                        }
                    },
                    margin: {
                        bottom: 20
                    },
                    valueAxis: [{
                        grid: {
                            visible: true
                        },
                        title: {
                            text: "Booked hours"
                        }
                    }, {
                        name: "total",
                        position: "right",
                        grid: {
                            visible: true
                        },
                        title: {
                            text: "Income, Euro"
                        }
                    }],
                    series: [
                        {
                            axis: "total",
                            type: "bar",
                            valueField: "income",
                            name: "Income",
                            color: "#10b759"
                        },
                        {
                            valueField: "hours",
                            color: "#ecd372",
                            name: "Hours booked"
                        }, {
                            valueField: "hours_adj",
                            dashStyle: "longDash",
                            color: "#af002f",
                            name: "Hours adj"
                        }
                    ],
                    tooltip: {
                        enabled: true,
                        customizeTooltip: function (arg) {
                            return {
                                text: arg.argument + " (" + arg.value + ") "
                            };
                        }
                    },
                    legend: {
                        verticalAlignment: "bottom",
                        horizontalAlignment: "center"
                    },
                    "export": {
                        enabled: true
                    },

                    title: "Hours vs Income"
                }).dxChart("instance");
            }
            chart();
        });
    </script>
@endpush
