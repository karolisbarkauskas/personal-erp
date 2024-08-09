@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-7">
            <div class="card card-body">
                <h6 class="tx-uppercase tx-11 tx-spacing-1 tx-color-02 tx-semibold mg-b-8">
                    Upcoming payments
                </h6>
                <livewire:expenses.upcoming.list-component />
            </div>
        </div>
        <div class="col-lg-3">
            <div class="card card-body">
                <h6 class="tx-uppercase tx-11 tx-spacing-1 tx-color-02 tx-semibold mg-b-8">
                    Current depts
                </h6>
                <livewire:expenses.depts.list-component />
            </div>
            <div class="card card-body mt-2 col-lg-12">
                <h6 class="tx-uppercase tx-11 tx-spacing-1 tx-color-02 tx-semibold mg-b-8">
                    VAT to pay
                </h6>
                <livewire:vat-component />
            </div>
        </div>
        <div class="col-lg-2">
            <div class="card card-body">
                <h6 class="tx-uppercase tx-11 tx-spacing-1 tx-color-02 tx-semibold mg-b-8">
                    Upcoming income from clients
                </h6>
                <table class="table table-striped table-hover">
                    <tbody>
                        @foreach($upcoming as $income)
                            <tr>
                                <td><a href="{{ route('income.edit', $income) }}">{{ $income->incomeClient->name }}</a></td>
                                <td>{{ \App\Label::formatPrice($income->total) }}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td><strong><em>Total</em></strong></td>
                            <td>
                                <strong>
                                    <em>{{ \App\Label::formatPrice($upcoming->sum('total')) }}</em>
                                </strong>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="card card-body mt-3">
                <h6 class="tx-uppercase tx-11 tx-spacing-1 tx-color-02 tx-semibold mg-b-8">
                    Upcoming payments with actual invoices sent
                </h6>
                <table class="table table-striped table-hover">
                    <tbody>
                        @foreach($incomes as $income)
                            <tr>
                                <td @if($income->isDept()) class="tx-danger" @endif>
                                    <a @if($income->isDept()) class="tx-danger" @endif href="{{ route('income.edit', $income) }}">
                                        {{ $income->incomeClient->name }}
                                    </a>
                                </td>
                                <td @if($income->isDept()) class="tx-danger" @endif>{{ \App\Label::formatPrice(abs($income->unpaid())) }}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td class="tx-success">
                                <strong>Total income to be received</strong>
                            </td>
                            <td>
                                <h5 class="tx-success">
                                    <em>{{ \App\Label::formatPrice(abs($totalIncomeToCome)) }}</em>
                                </h5>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
