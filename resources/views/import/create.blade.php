@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12 mg-t-10">
            <div class="card mg-b-10">
                <div class="card-header pd-t-20 d-sm-flex align-items-start justify-content-between bd-b-0 pd-b-0">
                    <div>
                        <h6 class="mg-b-5">Import</h6>
                    </div>
                </div>

                <div class="card-header text-lg-center">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header pd-b-0 pd-t-20 bd-b-0">
                                <h6 class="mg-b-0">
                                    Import data from bank (csv;) - SWEDBANK
                                </h6>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('import.store') }}"
                                      method="post" enctype="multipart/form-data">
                                    @csrf
                                    <input type="file" autocomplete="off"
                                           name="swedbank[]" multiple required>
                                    <button class="btn btn-success">
                                        Upload!
                                    </button>
                                </form>
                                <pre class="text-left">
                                    ATASKAITAI imant:
                                    Kasdieninės paslaugos > Sąskaitos išrašas

                                    - Išplėstas formatas
                                    - Grupiniai atskirom sumom
                                    - Banko mokesčiai kaip viena suma
                                </pre>
                            </div>
                        </div>
                    </div>
                </div>

                @if($import->isEmpty())
                    <div class="alert alert-secondary" role="alert">
                        All data is processed
                    </div>
                @else
                    <div class="table-responsive table-hover">
                        <form action="{{ route('import.store') }}" method="post">
                            <div class="text-center">
                                <button class="btn btn-info wd-900-f text-center" value="1" name="import">Import data
                                </button>
                            </div>
                            @csrf
                            <table class="table table-dashboard mg-b-0">
                                <thead>
                                <tr>
                                    <th class="text-center">Date</th>
                                    <th class="text-center">Payer</th>
                                    <th class="text-center">Sum</th>
                                    <th class="text-center">Purpose</th>
                                    <th class="text-center wd-250">Expense category</th>
                                    <th class="text-center wd-50-f">VAT size</th>
                                    <th class="text-center">Ignore?</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($import as $row)
                                    <tr class="@if($row->sum > 0) text-success @else text-danger @endif">
                                        <td class="text-center p-1">{{ $row->date->format('Y-m-d') }}</td>
                                        <td class="text-center p-1">{{ $row->payer }}</td>
                                        <td class="tx-medium text-center p-1">{{ $row->sum }}€</td>
                                        <td class="tx-medium text-center p-1">{{ $row->purpose }}</td>
                                        <td class="tx-medium text-center p-1">
                                            @if($row->sum < 0 && !$row->ignored())
                                                <select name="recurring_expense[{{ $row->id }}]"
                                                        id="recurring_expense{{ $row->id }}" autocomplete="off"
                                                        class="form-control w-full">
                                                    <option value="">
                                                        Assign recurring expense
                                                    </option>
                                                    @foreach($recurringExpenses as $category)
                                                        <option value="{{ $category->id }}"
                                                                @if ($row->recurringExpenseCategory($category) == $category->id) selected="selected" @endif>
                                                            @if ($row->recurringExpenseCategory($category) == $category->id) ✅ @endif
                                                            {{ $category->name }} | {{ \App\Label::formatPrice($category->size) }}
                                                        </option>
                                                    @endforeach
                                                </select>

                                                <select name="expense_category[{{ $row->id }}]"
                                                        id="expense_category{{ $row->id }}" autocomplete="off"
                                                        class="form-control w-full">
                                                    <option value="">
                                                        Assign expense category
                                                    </option>
                                                    @foreach($categories as $category)
                                                        <option value="{{ $category->id }}"
                                                                @if ($row->expenseCategory($category) == $category->id) selected="selected" @endif>
                                                            @if ($row->expenseCategory($category) == $category->id) ✅ @endif {{ $category->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            @endif
                                        </td>

                                        <th class="text-center wd-50-f">
                                            <input type="text" value="{{ $row->getVat() }}" name="vat[{{ $row->id }}]">
                                        </th>
                                        <td class="tx-medium text-center p-1">
                                            <div class="custom-control custom-checkbox mg-t-15">
                                                <input type="checkbox" class="custom-control-input"
                                                       id="ignore_{{ $row->id }}" name="ignore[{{ $row->id }}]"
                                                       value="1"
                                                       @if ($row->ignored()) checked @endif>
                                                <input type="hidden" name="id[{{ $row->id }}]" value="{{ $row->id }}">
                                                <label class="custom-control-label" for="ignore_{{ $row->id }}"></label>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="text-center">
                                <button class="btn btn-info wd-900-f text-center" value="1" name="import">Import data
                                </button>
                            </div>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
