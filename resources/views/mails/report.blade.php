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
    @media only screen and (max-width: 600px) {
        .inner-body {
            width: 100% !important;
        }

        .footer {
            width: 100% !important;
        }
    }

    @media only screen and (max-width: 500px) {
        .button {
            width: 100% !important;
        }
    }

    .tx-success {
        color: green;
    }

    .tx-danger {
        color: red;
    }
</style>
<table class="wrapper" width="100%" cellpadding="0" cellspacing="0"
       style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; background-color: #f5f8fa; margin: 0; padding: 0; width: 100%; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 100%;">
    <tr>
        <td align="center" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box;">
            <table class="content" width="100%" cellpadding="0" cellspacing="0"
                   style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; margin: 0; padding: 0; width: 100%; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 100%;">
                <tr>
                    <td>

                        <div class="col-lg-6 mg-b-10">

                            <div class="card mg-t-10">
                                <div class="card-header d-sm-flex align-items-start justify-content-between">
                                    <div
                                        style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: #000000; font-size: 19px; font-weight: bold; text-decoration: none; text-shadow: 0 1px 0 #ffffff;text-align: center">
                                        <br>
                                        Total time booked this week
                                        ({{ Carbon\Carbon::now()->startofWeek()->format('Y-m-d') }}
                                        - {{ Carbon\Carbon::now()->endOfWeek()->format('Y-m-d') }})
                                        <br><br>
                                    </div>
                                </div>
                                <div class="card-body pd-y-15 pd-x-10">
                                    <div class="table-responsive">
                                        <table class="wrapper" width="100%" cellpadding="0" cellspacing="0"
                                               style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; background-color: #f5f8fa; margin: 0; padding: 0; width: 100%; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 100%;">
                                            <thead>
                                            <tr class="tx-10 tx-spacing-1 tx-color-03 tx-uppercase">
                                                <th class="text-center">Employee</th>
                                                <th class="text-center">Time booked</th>
                                                <th class="text-center">Profitable time</th>
                                                <th class="text-center">Non profitable time</th>
                                                <th class="text-center">Time to be booked</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($timeThisWeek as $name => $timeRecords)
                                                <tr class="@if(($timeRecords->getExpectedWorkTimeWithDaysOff(5, $offDaysInWeek) - $timeRecords->getBillableTime()) < $timeRecords->getExpectedWorkTime()) tx-success @else tx-danger @endif" >
                                                    <td class="text-center"
                                                        style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; padding: 10px 0; text-align: center;">
                                                        {!! $name !!}
                                                    </td>
                                                    <td class="text-center"
                                                        style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; padding: 10px 0; text-align: center;">
                                                        {!! $timeRecords->decimalToTime($timeRecords->getBookedTime()) !!}
                                                    </td>
                                                    <td class="text-center"
                                                        style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; padding: 10px 0; text-align: center;">
                                                        {!! $timeRecords->decimalToTime($timeRecords->getBillableTime()) !!}
                                                    </td>
                                                    <td class="text-center"
                                                        style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; padding: 10px 0; text-align: center;">
                                                        {!! $timeRecords->decimalToTime($timeRecords->getNotBillableTime()) !!}
                                                    </td>
                                                    <td class="text-center"
                                                        style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; padding: 10px 0; text-align: center;">
                                                        {!! $timeRecords->getExpectedWorkTimeWithDaysOff(5, $offDaysInWeek) !!} |
                                                        <span class="badge @if(($timeRecords->getExpectedWorkTimeWithDaysOff(5, $offDaysInWeek) - $timeRecords->getBillableTime()) < 0) tx-success @else tx-danger @endif">
                                                            {!! $timeRecords->first()->decimalToTime( abs(($timeRecords->getExpectedWorkTimeWithDaysOff(5, $offDaysInWeek)) - $timeRecords->getBillableTime()) ) !!}
                                                        </span>

                                                        @if(($timeRecords->getExpectedWorkTimeWithDaysOff(5, $offDaysInWeek) - $timeRecords->getBillableTime()) > $timeRecords->getExpectedWorkTime())
                                                            <br> Is entire day behind schedule
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="card">
                                <div
                                    style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: #000000; font-size: 19px; font-weight: bold; text-decoration: none; text-shadow: 0 1px 0 #ffffff;text-align: center">
                                    <br> Total booked time this month <br><br>
                                </div>
                                <div class="card-body pd-y-15 pd-x-10">
                                    <div class="table-responsive">
                                        <table class="wrapper" width="100%" cellpadding="0" cellspacing="0"
                                               style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; background-color: #f5f8fa; margin: 0; padding: 0; width: 100%; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 100%;">
                                            <thead>
                                            <tr class="tx-10 tx-spacing-1 tx-color-03 tx-uppercase">
                                                <th class="text-center">Employee</th>
                                                <th class="text-center">Time booked</th>
                                                <th class="text-center">Profitable time</th>
                                                <th class="text-center">Non profitable time</th>
                                                <th class="text-center">Time to be booked</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($timeThisMonth as $name => $timeRecords)
                                                <tr>
                                                    <td class="text-center"
                                                        style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; padding: 10px 0; text-align: center;">
                                                        {!! $name !!}
                                                    </td>
                                                    <td class="text-center"
                                                        style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; padding: 10px 0; text-align: center;">
                                                        {!! $timeRecords->decimalToTime($timeRecords->getBookedTime()) !!}
                                                    </td>
                                                    <td class="text-center"
                                                        style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; padding: 10px 0; text-align: center;">
                                                        {!! $timeRecords->decimalToTime($timeRecords->getBillableTime()) !!}
                                                    </td>
                                                    <td class="text-center"
                                                        style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; padding: 10px 0; text-align: center;">
                                                        {!! $timeRecords->decimalToTime($timeRecords->getNotBillableTime()) !!}
                                                    </td>
                                                    <td class="text-center"
                                                        style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; padding: 10px 0; text-align: center;">
                                                        {!! $timeRecords->getExpectedWorkTimeWithDaysOff(\App\CollabUsers::WORKDAYS_PER_MONTH, $offDaysInMonth) !!} |
                                                        <span class="badge @if(($timeRecords->getExpectedWorkTimeWithDaysOff(\App\CollabUsers::WORKDAYS_PER_MONTH, $offDaysInMonth) - $timeRecords->getBillableTime()) < 0) tx-success @else tx-danger @endif">
                                                            {!! $timeRecords->first()->decimalToTime( abs(($timeRecords->getExpectedWorkTimeWithDaysOff(\App\CollabUsers::WORKDAYS_PER_MONTH, $offDaysInMonth)) - $timeRecords->getBillableTime()) ) !!}
                                                        </span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>


