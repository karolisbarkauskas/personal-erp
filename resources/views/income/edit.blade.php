@extends('layouts.app')

@section('content')
    <div class="pd-x-0 pd-lg-x-10 pd-xl-x-0">

        <div class="mg-b-10 clearfix">
            <h4 class="float-left">Income edit for <strong>{{ $income->incomeClient->name }}</strong></h4>
            <div class="float-right">
                @if($income->isDataReady())
                    <a href="{{ route('download-invoice', [$income, 'lt']) }}" class="badge badge-info" target="_blank">
                        DOWNLOAD INVOICE [LT]
                    </a>
                    <a href="{{ route('download-invoice', [$income, 'en']) }}" class="badge badge-info" target="_blank">
                        DOWNLOAD INVOICE [EN]
                    </a>

                    <a href="{{ route('income-mail.create', ['income' => $income]) }}" class="btn btn-success"
                       target="_blank">
                        SEND TO CLIENT
                    </a>
                @else
                    <span class="badge badge-danger">
                        Invoice is not ready to be created
                    </span>
                @endif
            </div>
        </div>

        <div data-label="Example" class="df-example demo-forms">
            <form method="post" action="{{ route('income.update', $income) }}">
                @method('PATCH')
                @csrf
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="company_id"> COMPANY</label>
                        <select name="company_id" id="company_id" class="form-control">
                            @foreach($companies as $company)
                                <option value="{{ $company->id }}"
                                        @if(old('company_id', $income->company_id) == $company->id) selected="selected" @endif>
                                    {{ $company->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="name">
                            <a href="{{ route('clients.edit', $income->client) }}" target="_blank">
                                Client *
                            </a>
                        </label>
                        <select name="client" id="client" class="form-control">
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}"
                                        @if(old('client', $income->client) == $client->id) selected="selected" @endif>
                                    {{ $client->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="name">Status *</label>
                        <select name="status" id="status" class="form-control">
                            <option value="0">-- NO status --</option>
                            @foreach($statuses as $status)
                                <option value="{{ $status->id }}"
                                        @if(old('status', $income->status) == $status->id) selected="selected" @endif>
                                    {{ $status->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                @if($income->isIncomeNotFinished())
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="issue_date">Invoice issue date *</label>

                            <input type="date" class="form-control" id="issue_date" name="issue_date"
                                   value="{{ old('issue_date', $income->issue_date) }}">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="income_date">Income period (month)</label>

                            <input type="date" class="form-control" id="income_date" name="income_date"
                                   value="{{ old('income_date', $income->income_date) }}">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="send_date">ACTUAL invoice sent date</label>

                            <input type="date" class="form-control" id="income_date" name="send_date"
                                   value="{{ old('send_date', optional($income->send_date)->format('Y-m-d')) }}">
                            <span>From this date deadline will be calculated</span>
                        </div>
                    </div>
                    <hr>
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="invoice_no">
                                {!! ucfirst($income->getInvoiceTitle()) !!} number:
                            </label>

                            <input type="text" class="form-control" id="invoice_no" name="invoice_no"
                                   value="{{ old('invoice_no', $income->invoice_no) }}">
                            Next invoice <strong>{{ $nextInvoice }}</strong> <br>
                            Next CREDIT <strong>{{ $nextInvoiceCredit }}</strong> <br>
                            Next PROFORMA <strong>{{ $nextInvoiceProforma }}</strong> <br>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="amount">Amount *</label>

                            <input type="text" class="form-control" id="amount" name="amount" readonly
                                   value="{{ old('amount', $income->amount) }}">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="vat_size">VAT size % *</label>

                            <input type="text" class="form-control" id="vat_size" name="vat_size"
                                   value="{{ old('vat_size', $income->vat_size) }}">
                        </div>
                    </div>
                    <hr>
                @endif
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="description">Description*</label>

                        <textarea name="description" id="description" cols="30" class="form-control"
                                  rows="10">{{ old('description', $income->description) }}</textarea>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="description">
                            Income information
                        </label>
                        <table class="table table-dashboard mg-b-0">
                            <thead>
                            <tr>
                                <th>Service name</th>
                                <th class="text-center">Amount</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach($services as $service)
                                    <tr>
                                        <td class="tx-color-03 tx-normal">{{ $service->name }}</td>
                                        <td class="tx-medium text-center">
                                            <input type="text"
                                                   @if(!$income->isIncomeNotFinished()) disabled @endif
                                                   value="{{ optional($income->getServiceValue($service))->amount }}"
                                                   class="form-control" name="service[{{ $service->id }}]">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="form-group col-md-4">
                        <div class="mb-2">
                            <label for="name">Display for client</label>
                            <select name="display_style" id="display_style" class="form-control" required>
                                <option value="">-- Select --</option>
                                <option value="1" @if($income->display_style == 1) selected @endif >FULL info</option>
                                <option value="2" @if($income->display_style == 2) selected @endif>Short info</option>
                            </select>
                        </div>

                        <div class="mb-2">
                            <label for="name">Service line for short information</label>
                            <input type="text" class="form-control" id="invoice_no" name="short_service"
                                   value="{{ old('short_service', $income->short_service) }}">
                        </div>
                    </div>

                </div>

                <hr>
                @if($income->isIncomeNotFinished())
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="discount">
                                Discount size
                            </label>

                            <input type="text" class="form-control" id="discount" name="discount"
                                   value="{{ old('discount', $income->discount) }}">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="type">Discount type</label>

                            <select name="type" id="type" class="form-control">
                                <option value="0"
                                        @if(old('status', $income->type) == 0) selected="selected" @endif>
                                    Percentage
                                </option>
                                <option value="1"
                                        @if(old('status', $income->type) == 1) selected="selected" @endif>
                                    Amount
                                </option>
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="discount_reason">Discount reason</label>

                            <input type="text" class="form-control" id="discount_reason" name="discount_reason"
                                   value="{{ old('discount_reason', $income->discount_reason) }}">
                        </div>
                    </div>
                @endif

                <button type="submit" class="btn btn-primary w-100">Update income</button>
            </form>

            <hr>
            @livewire('income-report', [
            'income' => $income
            ])

            <a href="{{ route('download-report', [$income, 'lt']) }}" class="btn btn-info" target="_blank">
                DOWNLOAD TASKS report
            </a>
        </div>
        <div class="row mg-t-10">
            <div class="col-lg-4">
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
            <div class="col-lg-4">
                <div class="card mb-2">
                    <div class="card-header">
                        <h6 class="mg-b-0">Invoice total WITH VAT/Paid</h6>
                    </div>
                    <div class="card-body tx-center">
                        <h4 class="tx-normal tx-rubik tx-40 tx-spacing--1 mg-b-0">
                            {{ $income->total }}€ / {{ $income->payment()->sum('amount') }}€
                        </h4>

                        @if($income->invoice_no)
                            @if($income->isPaid())
                                <span class="badge badge-success">
                                    PAID
                                </span>
                            @else
                                <span class="badge badge-danger">
                                    DEPT <strong>{{ $income->getDept() }}€</strong>
                                </span>
                            @endif
                        @endif
                    </div>
                </div>

                <div class="card mg-b-10">
                    <div class="card-header">
                        <h6 class="mg-b-0">Payment assign</h6>
                    </div>
                    <div class="form-row card-body">
                        <div class="form-group col-md-12">
                            <form method="post" action="{{ route('payment.store', $income) }}">
                                @csrf
                                <label for="name">Payment size (with VAT) *</label>
                                <input type="text" class="form-control" id="amount" name="amount"
                                       value="{{ old('amount') }}">
                        </div>
                        <div class="form-group col-md-12">
                            <label for="name">Extra comments</label>

                            <textarea name="comment" id="comment" cols="30" class="form-control"
                                      rows="10">{{ old('comment') }}</textarea>

                            <button type="submit" class="btn btn-primary mt-2">Add payment</button>
                            </form>
                        </div>


                        <div class="table-responsive table-hover">
                            <table class="table table-dashboard mg-b-0">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th class="text-center">Size</th>
                                    <th class="text-center">Comment</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($income->payment as $row)
                                    <tr>
                                        <td class="tx-color-03 tx-normal">{{ $row->id }}</td>
                                        <td class="tx-medium text-center">{{ $row->amount }}€</td>
                                        <td class="tx-medium text-center">{{ $row->comment }}</td>
                                        <td class="tx-medium text-center">
                                            <form action="{{ route('payment.destroy', [$income, $row]) }}"
                                                  class="aligned-right"
                                                  method="post">
                                                @method('DELETE')
                                                @csrf
                                                <button href="" class="badge badge-danger">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
            <div class="col-lg-4">
                @if($income->incomeClient->unpaidIncomes->isNotEmpty())
                    <div class="card mb-2">
                        <div class="card-header">
                            <h6 class="mg-b-0">THIS CLIENT HAS OUTSTANDING INVOICES</h6>
                        </div>
                        <div class="card-body tx-left">
                            <table class="table table-dashboard mg-b-0">
                                @foreach($income->incomeClient->unpaidIncomes as $invoice)
                                    <tr>
                                        <td>
                                            <a href="{{ route('income.edit', $invoice) }}" class="badge badge-danger">
                                                {{ $invoice->invoice_no }}
                                            </a>
                                        </td>
                                        <td>
                                            <a href="{{ route('income.edit', $invoice) }}" target="_blank">
                                                {{ $invoice->amount }} € + PVM
                                            </a>
                                        </td>
                                        <td>
                                            <span>
                                                <a href="{{ route('income.edit', $invoice) }}"
                                                   class="badge badge-danger"
                                                   target="_blank">
                                                    -{{ $invoice->getOutstandingDays() }} d.
                                                </a>
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                    </div>
                @else
                    <div class="badge badge-success pd-10 w-100 mb-1">
                        This client has no outstanding invoices
                    </div>
                @endif
                <div class="card mb-2">
                    <div class="card-header">
                        <h6 class="mg-b-0">Client information</h6>
                    </div>
                    <div class="card-body tx-left">
                        <table class="table table-dashboard mg-b-0">
                            <tr>
                                <td>
                                    Total income this year:
                                </td>
                                <td>
                                    <strong>{{ \App\Label::formatPrice($income->incomeClient->getTotalIncomeThisYear()) }}</strong>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Total income from this client:
                                </td>
                                <td>
                                    <strong>{{ \App\Label::formatPrice($income->incomeClient->getTotalIncome()) }}</strong>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    AVERAGE invoice:
                                </td>
                                <td>
                                    <strong>{{ \App\Label::formatPrice($income->incomeClient->getAverageInvoice()) }}</strong>
                                </td>
                            </tr>
                        </table>

                    </div>
                </div>

            </div>

        </div>

    </div>
@endsection
