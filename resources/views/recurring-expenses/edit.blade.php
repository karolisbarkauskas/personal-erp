@extends('layouts.app')

@section('content')
    <div class="container pd-x-0 pd-lg-x-10 pd-xl-x-0">

        <h4 id="section3" class="mg-b-10">
            Recurring expense - {{ $recurring_expense->name }}
        </h4>

        <div data-label="Example" class="df-example demo-forms">
            <form method="post" action="{{ route('recurring-expenses.update', $recurring_expense) }}">
                @csrf
                @method('PATCH')
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="name">Expense name *</label>
                        <input type="text" class="form-control" id="name" name="name" autocomplete="off"
                               value="{{ old('name', $recurring_expense->name) }}">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="size">Size (without vat) *</label>
                        <input type="text" class="form-control" id="size" name="size"
                               value="{{ old('size', $recurring_expense->size) }}">
                        Last payment size(without vat): {{ $lastPaymentSize }}€
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="category">Expenses category *</label>
                        <select name="category" id="category" class="form-control">
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}"
                                        @if(old('category', $recurring_expense->category) == $category->id) selected="selected" @endif>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-5">
                        <label for="installment">
                            Payment installment sizes (will mark as dept / loan and will deduce from size field)
                        </label>
                        <input type="text" class="form-control" id="installment" name="installment"
                               value="{{ old('installment', $recurring_expense->installment) }}"/>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="priority">Priority *</label>
                        <select name="priority" id="priority" class="form-control">
                            <option value="1"
                                    @if(old('priority', $recurring_expense->priority) == 1) selected="selected" @endif>
                                Essential (Critical for operations)
                            </option>
                            <option value="2"
                                    @if(old('priority', $recurring_expense->priority) == 2) selected="selected" @endif>
                                Secondary (High priority, but ok if paid late)
                            </option>
                            <option value="3"
                                    @if(old('priority', $recurring_expense->priority) == 2) selected="selected" @endif>
                                Non-essential
                            </option>
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="payment_date">
                            Payment day
                        </label>
                        <input type="text" class="form-control" id="payment_date" name="payment_date"
                               value="{{ old('payment_date', $recurring_expense->payment_date) }}"/>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="expense_payment_name">
                           Name for bank statement
                        </label>
                        <input type="text" class="form-control" id="expense_payment_name" name="expense_payment_name"
                               value="{{ old('expense_payment_name', $recurring_expense->expense_payment_name) }}"/>
                    </div>
                </div>

                <div class="form-group">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input"
                               @if($recurring_expense->vatable) checked @endif
                               id="vatable" name="vatable" value="1">
                        <label class="custom-control-label" for="vatable">
                            Vatable?
                        </label>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Update</button>
            </form>

            <div style="height: 640px;" id="chart"></div>

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

            const dataSource = [
                @foreach($recurringExpensesList as $expense)
                {
                    current: {{ $expense['size'] }},
                    date: "{{ $expense['expense_date'] }}",
                },
                @endforeach
            ];

            $('#chart').dxChart({
                dataSource,
                commonSeriesSettings: {
                    argumentField: 'date',
                    type: 'line',
                    point: {
                        hoverMode: 'allArgumentPoints',
                    },
                },
                argumentAxis: {
                    valueMarginsEnabled: false,
                    discreteAxisDivisionMode: 'crossLabels',
                    grid: {
                        visible: true,
                    },
                },
                crosshair: {
                    enabled: true,
                    color: '#949494',
                    width: 3,
                    dashStyle: 'dot',
                    label: {
                        visible: true,
                        backgroundColor: '#949494',
                        font: {
                            color: '#fff',
                            size: 12,
                        },
                    },
                },
                series: [
                    {valueField: 'current', name: 'Payment changes', color: '#2E3192'},
                ],
                legend: {
                    verticalAlignment: 'bottom',
                    horizontalAlignment: 'center',
                    itemTextPosition: 'bottom',
                    equalColumnWidth: true,
                },
                title: {
                    text: 'Payment changes for expense: {{ $recurring_expense->name }}',
                },
                export: {
                    enabled: true,
                },
                tooltip: {
                    enabled: true,
                },
            });
        });
    </script>
@endpush
