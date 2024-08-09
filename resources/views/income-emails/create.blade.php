@extends('layouts.app')

@section('content')
    <div class="container pd-x-0 pd-lg-x-10 pd-xl-x-0">

        <h4 id="section3" class="mg-b-10">
            Sent invoice to the client
        </h4>

        <div data-label="Example" class="df-example demo-forms">

            <form method="post" action="{{ route('income-mail.store', ['income' => $income->id]) }}">
                @csrf
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="subject">Subject *</label>
                        <input type="text" class="form-control" id="subject" name="subject"
                               value="{{ old('subject', "Sąskaita faktūra $income->invoice_no išrašyta $income->issue_date") }}"/>
                    </div>
                    <div class="form-group col-md-6">
                        <h6 class="text-center">
                            RECEIVERS:
                        </h6>
                        <table class="table table-dashboard mg-b-0 table-hover">
                            <thead>
                            <tr>
                                <th class="text-left">Name</th>
                                <th class="text-left">Email</th>
                                <th class="text-left">Send to?</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($income->incomeClient->contacts as $contact)
                                <tr>
                                    <td class="text-left">{{ $contact->full_name }}</td>
                                    <td class="text-left"><a
                                            href="mailto:{{ $contact->email }}">{{ $contact->email }}</a></td>
                                    <td>
                                        <input type="checkbox" name="sendto[{{ $contact->id }}]"
                                               @if(old('sendto.'.$contact->id, true)) checked @endif
                                               value="{{ $contact->id }}">
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                        <div class="form-group mg-t-20">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input"
                                       @if(old('attach-debts', true)) checked @endif
                                       id="attach-debts" name="attach-debts" value="1">
                                <label class="custom-control-label" for="attach-debts">
                                    Attach dept invoices if they have them?
                                </label>
                            </div>
                        </div>

                        <div class="form-group mg-t-20">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input"
                                       @if(old('attach-report', true)) checked @endif
                                       id="attach-report" name="attach-report" value="1">
                                <label class="custom-control-label" for="attach-report">
                                    Attach tasks report?
                                </label>
                            </div>
                        </div>

                        <div class="mb-2">
                            <label for="language">Invoice language</label>
                            <select name="language" id="language" class="form-control" required>
                                <option value="lt">LT</option>
                                <option value="en">EN</option>
                            </select>
                        </div>

                        <div class="form-group mg-t-20">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input"
                                       id="no_scheduling" name="no_scheduling" value="1" />
                                <label class="custom-control-label" for="no_scheduling">
                                    Send invoice NOW (no scheduling)
                                </label>
                            </div>
                        </div>

                        <div class="mb-2">
                            <label for="language">Schedule invoice email:</label>

                            <input type="datetime-local" class="form-control" id="schedule_time" name="schedule_time"
                                   value="{{ old('schedule_time', now()->addMinute()->format('Y-m-d\TH:i:00')) }}" />
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <label for="content">Email body *</label>
                        <textarea name="content" id="content" class="form-control" cols="30" rows="40">
                            @if(old('content'))
                                {{ old('content') }}
                            @else
                                {!! $income->getEmailContent() !!}
                            @endif
                        </textarea>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100">
                    SEND email
                </button>
            </form>

            @if($income->emails->isNotEmpty())
                <div class="form-group col-md-12 mg-t-60">
                    <label for="description">
                        EMAIL LOG
                    </label>
                    <table class="table table-dashboard mg-b-0">
                        <thead>
                        <tr>
                            <th>Information</th>
                            <th>Email body</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($income->emails()->orderBy('created_at', 'desc')->get() as $email)
                            <tr>
                                <td class="tx-normal">
                                    Subject: <strong>{{ $email->subject }}</strong> <br>
                                    Sent at: <strong>{!! $email->created_at !!}</strong> <br>
                                    Has depts attached? <strong>{!! $email->attach_debts ? 'Yes' : 'No' !!}</strong>
                                    <br>
                                    <br>
                                    Receivers: <br>
                                    @foreach($email->getReceivers() as $receiver)
                                        <strong>{!! $receiver->full_name !!} - {!! $receiver->email !!} <br></strong>
                                    @endforeach
                                </td>
                                <td>
                                    {!! $email->content !!}
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

            @endif
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .cke_notification.cke_notification_warning {
            display: none !important;
        }
    </style>
@endpush

@push('javascript')
    <script src="https://cdn.ckeditor.com/4.20.1/standard/ckeditor.js"></script>

    <script>
        CKEDITOR.replace('content');
    </script>
@endpush
