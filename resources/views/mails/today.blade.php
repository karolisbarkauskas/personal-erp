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
                            <div class="card">
                                <div
                                    style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: #000000; font-size: 19px; font-weight: bold; text-decoration: none; text-shadow: 0 1px 0 #ffffff;text-align: center">
                                    <br> {!! $day !!} daily report <br><br>
                                </div>
                                <div class="card-body pd-y-15 pd-x-10">
                                    <div class="table-responsive pd-b-10">
                                        <table class="wrapper table-striped" width="100%" cellpadding="0" cellspacing="0"
                                               style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; background-color: #f5f8fa; margin: 0; padding: 0; width: 100%; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 100%;">
                                            <thead>
                                            <tr class="tx-10 tx-spacing-1 tx-color-03 tx-uppercase">
                                                <th class="text-center">Employee</th>
                                                <th class="text-center">Time booked</th>
                                                <th class="text-center">Billable</th>
                                                <th class="text-center">Non Billable</th>
                                                <th class="text-center">Time to be booked</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($timeToday as $name => $timeRecords)
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
                                                            <strong>{!! $timeRecords->getEmployeeDailyWorkHours() !!}</strong>
                                                        </td>
                                                        @foreach($timeRecords as $record)
                                                            <tr @if($loop->iteration % 2 != 0) style="background: #d3d3d3" @endif>
                                                                <td class="text-center"
                                                                    style="font-family:  Avenir, Helvetica, sans-serif; box-sizing: border-box; text-align: left;width: 400px !important;padding: 1px">

                                                                    @if($record->parent_type == 'Task')
                                                                        <a href="https://tasks.onesoft.io/projects/{{ $record->task->project_id }}?modal=Task-{!! $record->task->id !!}-{{ $record->task->project_id }}"
                                                                           target="_blank">

                                                                            {!! $record->task->project->name !!} #{!! $record->task->task_number !!} - {!! $record->task->name !!}
                                                                        </a>
                                                                    @else
                                                                        <strong>{!! $record->project->name !!}</strong> PROJECT level entry
                                                                    @endif
                                                                </td>
                                                                <td class="text-center"
                                                                    style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; text-align: center;">
                                                                    {!! $timeRecords->decimalToTime($record->value) !!}
                                                                </td>
                                                                <td class="text-center" colspan="3"
                                                                    style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; text-align: left;">
                                                                    {!! $record->summary !!}
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                        <tr>
                                                            <td colspan="8">
                                                                <hr>
                                                            </td>
                                                        </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        <hr>
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


