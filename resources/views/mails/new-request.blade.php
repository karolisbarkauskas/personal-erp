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

                        <div class="card">
                            <div
                                style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: #000000; font-size: 19px; font-weight: bold; text-decoration: none; text-shadow: 0 1px 0 #ffffff;text-align: center">
                                <br> There is new request from potential client <br><br>
                            </div>
                            <div class="card-body pd-y-15 pd-x-10">
                                <div class="table-responsive">
                                    <table class="wrapper" width="100%" cellpadding="0" cellspacing="0"
                                           style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; background-color: #f5f8fa; margin: 0; padding: 0; width: 100%; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 100%;">
                                        <thead>
                                            <tr class="tx-10 tx-spacing-1 tx-color-03 tx-uppercase">
                                                <th class="text-center">Task ID</th>
                                                <th class="text-center">Task name</th>
                                                <th class="text-center">Task link</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr class="tx-10 tx-spacing-1 tx-color-03 tx-uppercase">
                                                <td style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; padding: 10px 0; text-align: center;">#{{ $task->task_number }}</td>
                                                <td style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; padding: 10px 0; text-align: center;">#{{ $task->name }}</td>
                                                <td style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; padding: 10px 0; text-align: center;">
                                                    <a href="https://tasks.onesoft.io/projects/73?modal=Task-{{ $task->id }}-73">
                                                        View task in Active Collab
                                                    </a>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <br><br><br>
                                    <div style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; padding: 50px;">
                                        {!! $task->body !!}
                                    </div>

                                    <h3>
                                        Remember to leave a comment in Collab!
                                    </h3>
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


