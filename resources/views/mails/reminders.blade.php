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

    .btn.tx-normal.btn-success {
        color: green;
    }

    .btn.tx-normal.btn-warning {
        color: orange;
    }

    .btn.tx-normal.btn-danger {
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
                                        There are some unanswered or QA needed tasks
                                        <br><br>
                                    </div>
                                </div>
                                <div class="card-body pd-y-15 pd-x-10">
                                    <div class="table-responsive table-striped">
                                        <table class="wrapper" width="100%" cellpadding="0" cellspacing="0"
                                               style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; background-color: #f5f8fa; margin: 0; padding: 0; width: 100%; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 100%;">
                                            <thead>
                                            <tr class="tx-10 tx-spacing-1 tx-color-03 tx-uppercase">
                                                <th class="text-center">Type</th>
                                                <th class="text-center">Task</th>
                                                <th class="text-center">Responsible</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @if(isset($report['needsAnswers']))
                                                @foreach($report['needsAnswers'] as $task)
                                                    <tr>
                                                        <td class="text-center"><span class="badge badge-info">Need information</span>
                                                        </td>
                                                        <td class="text-center">
                                                            <a href="https://tasks.onesoft.io/projects/{{ $task->project_id }}?modal=Task-{!! $task->id !!}-{{ $task->project_id }}"
                                                               target="_blank">
                                                                #{!! $task->task_number !!} - {!! $task->name !!}
                                                            </a>
                                                        </td>
                                                        <td class="text-center">{{ $task->responsible ? $task->responsible->getFullName() : 'No assignee' }}</td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                            @if(isset($report['needsQA']))
                                                @foreach($report['needsQA'] as $task)
                                                    <tr>
                                                        <td class="text-center"><span
                                                                class="badge badge-info">Needs QA</span>
                                                        </td>
                                                        <td class="text-center">
                                                            <a href="https://tasks.onesoft.io/projects/{{ $task->project_id }}?modal=Task-{!! $task->id !!}-{{ $task->project_id }}"
                                                               target="_blank">
                                                                #{!! $task->task_number !!} - {!! $task->name !!}
                                                            </a>
                                                        </td>
                                                        <td class="text-center">{{ $task->responsible ? $task->responsible->getFullName() : 'No assignee' }}</td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <hr>
                        </div>

                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>


