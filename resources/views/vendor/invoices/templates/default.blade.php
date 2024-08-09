<!DOCTYPE html>
<html lang="en">
<head>
    <title>{{ $invoice->name }}</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@1,500&display=swap" rel="stylesheet">
    <style type="text/css" media="screen">
        html {
            font-family: "Roboto", 'sans-serif';
            line-height: 1.15;
            margin: 0;
        }


        body {
            font-family: "Roboto", 'sans-serif';
            font-weight: 400;
            line-height: 1.5;
            color: #212529;
            text-align: left;
            background-color: #fff;
            font-size: 10px;
            margin: 36pt;
        }

        h4 {
            margin-top: 0;
            margin-bottom: 0.5rem;
        }

        p {
            margin-top: 0;
            margin-bottom: 1rem;
        }

        strong {
            font-weight: bolder;
            font-size: 10px;
        }

        img {
            vertical-align: middle;
            border-style: none;
        }

        table {
            border-collapse: collapse;
        }

        th {
            text-align: inherit;
        }

        h4, .h4 {
            margin-bottom: 0.5rem;
            font-weight: 500;
            line-height: 1.2;
        }

        h4, .h4 {
            font-size: 1.5rem;
        }

        .table {
            width: 100%;
            margin-bottom: 1rem;
            color: #212529;
        }

        .table th,
        .table td {
            padding: 0.75rem;
            vertical-align: top;
            border-top: 1px solid #dee2e6;
        }

        .table thead th {
            vertical-align: bottom;
            border-bottom: 2px solid #dee2e6;
        }

        .table tbody + tbody {
            border-top: 2px solid #dee2e6;
        }

        .mt-5 {
            margin-top: 3rem !important;
        }

        .pr-0,
        .px-0 {
            padding-right: 0 !important;
        }

        .pl-0,
        .px-0 {
            padding-left: 0 !important;
        }

        .text-right {
            text-align: right !important;
        }

        .text-center {
            text-align: center !important;
        }

        .text-uppercase {
            text-transform: uppercase !important;
        }

        * {
            font-family: "DejaVu Sans", 'sans-serif';
            font-size: 14px;
        }

        body, h1, h2, h3, h4, h5, h6, table, th, tr, td, p, div {
            line-height: 1.1;
        }

        .party-header {
            font-size: 1rem !important;
            font-weight: bold;
            color: #434343 !important;
        }

        .total_amount {
            color: #000000FF;
            font-size: 16px;
            font-weight: bold;
        }

        .total-amount {
            font-size: 16px;
            font-weight: 700;
        }

        .border-0 {
            border: none !important;
        }

        .blue-header {
            color: #000000;
            font-size: 18px !important;
            margin-bottom: 5px !important;
            margin-top: 0 !important;
        }

        .blue-header strong {
            font-size: 12px !important;
        }

        .vat-invoice-title {
            font-size: 16px !important;
            color: #000000FF;
            margin-bottom: 5px !important;
            margin-top: 0;
        }


        .vat-invoice-title strong {
            font-size: 13px !important;
        }

        .vat-invoice-date {
            font-size: 13px !important;
            color: #000000FF;
            margin-top: 0 !important;
        }

        .seller-info {
            font-size: 12px !important;
        }
    </style>
</head>

<body>

<table class="table">
    <tbody>
    <tr>
        <td class="border-0 pl-0 seller-info" width="50%">
            <h3 class="blue-header"><strong>{{ $invoice->seller->name }}</strong></h3>
            <strong>A.k.:</strong> {{ $invoice->seller->code }} <br>
            <strong>Individualios veiklos pažymos nr.:</strong> 588648 <br>
            @if($invoice->seller->vat)
                <strong>{{ __('invoices.vat') }}</strong> {{ $invoice->seller->vat }} <br>
            @endif
            <strong>Adresas:</strong> {{ $invoice->seller->address }} <br>
            @foreach($invoice->seller->custom_fields as $key => $value)
                <strong>{{ ucfirst($key) }}</strong>: {{ $value }} <br>
            @endforeach
        </td>
        <td class="border-0 pl-0 seller-info text-center">
            <div class="vat-invoice-title">
                <strong>{{ $invoice->name }}: {{ $invoice->getSerialNumber() }}</strong>
            </div>
            <div class="vat-invoice-date">
                <p><strong>{{ __('invoices.date') }}: {{ $invoice->getDate() }}</strong></p>
            </div>
        </td>
    </tr>
    </tbody>
</table>

{{-- Seller - Buyer --}}
<table class="table" style="margin: 50px 0">
    <thead>
    <tr>
        <th class="border-0 pl-0 party-header" width="55%">
            {{ __('invoices.buyer') }}
        </th>
        <th class="border-0 pl-0 party-header text-center">
            {{ trans('invoices.pay_until') }}
        </th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td class="px-0 seller-info">
            <h3 class="blue-header"><strong>{{ $invoice->buyer->name }}</strong></h3>
            @if($invoice->buyer->code)
                {{ __('invoices.code') }}: {{ $invoice->buyer->code }} <br>
            @endif
            @if($invoice->buyer->vat)
                {{ __('invoices.vat') }}: {{ $invoice->buyer->vat }} <br>
            @endif
            @if($invoice->buyer->address)
                {{ $invoice->buyer->address }} <br>
            @endif

            @foreach($invoice->buyer->custom_fields as $key => $value)
                <p class="buyer-custom-field">
                    {{ ucfirst($key) }}: {{ $value }}
                </p>
            @endforeach
        </td>
        <td class="tx-0 text-center">
            {{ $invoice->getPayUntilDate() }}
        </td>
    </tr>
    </tbody>
</table>

{{-- Table --}}
<table class="table">
    <thead>
    <tr>
        <th scope="col" class="border-0 pl-0">{{ __('invoices.description') }}</th>
        <th scope="col" class="text-center border-0">{{ __('invoices.quantity') }}</th>
        <th scope="col" class="text-right border-0">{{ __('invoices.price') }}</th>
        @if($invoice->hasItemDiscount)
            <th scope="col" class="text-right border-0">{{ __('invoices.discount') }}</th>
        @endif
        @if($invoice->hasItemTax)
            <th scope="col" class="text-right border-0">{{ __('invoices.tax') }}</th>
        @endif
        <th scope="col" class="text-right border-0 pr-0">{{ __('invoices.sub_total') }}</th>
    </tr>
    </thead>
    <tbody>
    {{-- Items --}}
    @foreach($invoice->items as $item)
        <tr>
            <td class="pl-0" style="width: 200px">{{ $item->title }}</td>
            <td class="text-center">{{ $item->quantity }}</td>
            <td class="text-right">
                {{ $invoice->formatCurrency($item->price_per_unit) }}
            </td>
            @if($invoice->hasItemDiscount)
                <td class="text-right">
                    {{ $invoice->formatCurrency($item->discount) }}
                </td>
            @endif
            @if($invoice->hasItemTax)
                <td class="text-right">
                    {{ $invoice->formatCurrency($item->tax) }}
                </td>
            @endif

            <td class="text-right pr-0">
                {{ $invoice->formatCurrency($item->sub_total_price) }}
            </td>
        </tr>
    @endforeach
    {{-- Summary --}}
    @if($invoice->hasItemOrInvoiceDiscount())
        <tr>
            <td colspan="{{ $invoice->table_columns - 2 }}" class="border-0"></td>
            <td class="text-right pl-0">{{ __('invoices.total_discount') }}</td>
            <td class="text-right pr-0">
                {{ $invoice->formatCurrency($invoice->total_discount) }}
            </td>
        </tr>
    @endif
    @if($invoice->taxable_amount)
        <tr>
            <td colspan="{{ $invoice->table_columns - 2 }}" class="border-0"></td>
            <td class="text-right pl-0">{!! __('invoices.taxable_amount', ['size' => $invoice->tax_rate]) !!}</td>
            <td class="text-right pr-0">
                {{ $invoice->formatCurrency($invoice->taxable_amount) }}
            </td>
        </tr>
    @endif
    @if($invoice->tax_rate)
        <tr>
            <td colspan="{{ $invoice->table_columns - 2 }}" class="border-0"></td>
            <td class="text-right pl-0">{!! __('invoices.tax_rate', ['size' => $invoice->tax_rate]) !!}</td>
            <td class="text-right pr-0">
                {{ $invoice->formatCurrency($invoice->total_taxes) }}
            </td>
        </tr>
    @endif
    @if($invoice->shipping_amount)
        <tr>
            <td colspan="{{ $invoice->table_columns - 2 }}" class="border-0"></td>
            <td class="text-right pl-0">{{ __('invoices.shipping') }}</td>
            <td class="text-right pr-0">
                {{ $invoice->formatCurrency($invoice->shipping_amount) }}
            </td>
        </tr>
    @endif
    <tr>
        <td colspan="{{ $invoice->table_columns - 2 }}" class="border-0"></td>
        <td class="text-right pl-0 total_amount">{{ __('invoices.total_amount') }}</td>
        <td class="text-right pr-0 total-amount">
            {{ $invoice->formatCurrency($invoice->total_amount) }}
        </td>
    </tr>
    </tbody>
</table>

<p class="seller-info">
    {{ trans('invoices.amount_in_words') }}: {{ $invoice->getAmountInWords($invoice->total_amount) }} <br>
    {!! $invoice->notes !!}
</p>

</body>
</html>
