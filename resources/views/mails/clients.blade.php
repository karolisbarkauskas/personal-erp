<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
</head>
<body
    style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; background-color: #f5f8fa; color: #74787e; height: 100%; hyphens: auto; line-height: 1.4; margin: 0; -moz-hyphens: auto; -ms-word-break: break-all; width: 100% !important; -webkit-hyphens: auto; -webkit-text-size-adjust: none; word-break: break-word;">
<style>
    table tr:nth-child(even) {
        background: #eeeeee;
    }
</style>
<table class="wrapper" width="100%" cellpadding="0" cellspacing="0"
       style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; background-color: #f5f8fa; margin: 0; padding: 0; width: 100%; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 100%;">
    <tr>
        <td align="center" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box;">
            @if(count($debtors))
                <table class="content" width="100%" cellpadding="0" cellspacing="0"
                       style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; margin: 0; padding: 0; width: 100%; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 100%;">
                    <tr>
                        <td>
                            <div class="col-lg-6 mg-b-10">
                                @foreach($debtors as $brand => $info)
                                    <div class="card mg-t-10">
                                        <div class="card-header d-sm-flex align-items-start justify-content-between">
                                            <div
                                                style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: #000000; font-size: 19px; font-weight: bold; text-decoration: none; text-shadow: 0 1px 0 #ffffff;text-align: center">
                                                <strong>{!! $brand !!}</strong> customers debts information
                                            </div>
                                        </div>
                                        <div class="card-body pd-y-15 pd-x-10">
                                            <div class="table-responsive">
                                                <table class="wrapper" width="100%" cellpadding="0" cellspacing="0"
                                                       style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; background-color: #f5f8fa; margin: 0; padding: 0; width: 100%; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 100%;">
                                                    <thead>
                                                    <tr class="tx-10 tx-spacing-1 tx-color-03 tx-uppercase">
                                                        <th class="text-center" style="width: 400px">Customer</th>
                                                        <th class="text-center">Debt size</th>
                                                        <th class="text-center">Overdue time</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($info as $client => $dept)
                                                        <tr>
                                                            <td style="text-align: center;padding: 3px">
                                                                {!! $client !!} <br>
                                                            </td>
                                                            <td style="text-align: center">
                                                                {!! \App\Label::formatPrice($dept['amount']) !!}
                                                            </td>
                                                            <td style="text-align: center">
                                                                <strong>{!! $dept['overdue'] !!} days.</strong>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                <hr>
                            </div>
                        </td>
                    </tr>
                </table>
            @endif

            @if(count($planned))
                <table class="content" width="100%" cellpadding="0" cellspacing="0"
                       style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; margin: 0; padding: 0; width: 100%; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 100%;">
                    <tr>
                        <td>
                            <div class="col-lg-6 mg-b-10">
                                @foreach($planned as $brand => $info)
                                    <div class="card mg-t-10">
                                        <div class="card-header d-sm-flex align-items-start justify-content-between">
                                            <div
                                                style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: #000000; font-size: 19px; font-weight: bold; text-decoration: none; text-shadow: 0 1px 0 #ffffff;text-align: center">
                                                <strong>{!! $brand !!}</strong> UNFINISHED PLANNED INVOICES
                                            </div>
                                        </div>
                                        <div class="card-body pd-y-15 pd-x-10">
                                            <div class="table-responsive">
                                                <table class="wrapper" width="100%" cellpadding="0" cellspacing="0"
                                                       style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; background-color: #f5f8fa; margin: 0; padding: 0; width: 100%; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 100%;">
                                                    <thead>
                                                    <tr class="tx-10 tx-spacing-1 tx-color-03 tx-uppercase">
                                                        <th class="text-center" style="width: 400px">Customer</th>
                                                        <th class="text-center">Debt size</th>
                                                        <th class="text-center">Overdue time</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($info as $client => $dept)
                                                        <tr>
                                                            <td style="text-align: center;padding: 3px">
                                                                {!! $client !!} <br>
                                                            </td>
                                                            <td style="text-align: center">
                                                                {!! \App\Label::formatPrice($dept['amount']) !!}
                                                            </td>
                                                            <td style="text-align: center">
                                                                <strong>{!! $dept['overdue'] !!} days.</strong>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                <hr>
                            </div>
                        </td>
                    </tr>
                </table>
            @endif
        </td>
    </tr>
</table>
</body>
</html>
