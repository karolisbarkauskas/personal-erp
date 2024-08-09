<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
</head>
<body>
<style>

    body {
        background: rgba(253, 253, 253, 0.85);
    }

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

    .content-cell span {
        display: block;
        padding: 2px 0;
        color: #0c0e13;
    }

    .content-cell span a {
        color: green;
    }
</style>

<table class="wrapper" width="100%" cellpadding="0" cellspacing="0" role="presentation">
    <tr>
        <td align="center">
            <table class="content" width="100%" cellpadding="0" cellspacing="0" role="presentation">
                <tr>
                    <td style="text-align: center;padding: 10px">
                        <a href="{{ $brandLink }}" style="display: inline-block;">
                            <img src="{!! $brandLogo !!}" alt="{{ $brand }}" height="50">
                        </a>
                    </td>
                </tr>
                <!-- Email Body -->
                <tr>
                    <td class="body" width="100%" cellpadding="0" cellspacing="0">
                        <table class="inner-body" align="center" width="570" cellpadding="0" cellspacing="0"
                               role="presentation">
                            <!-- Body content -->
                            <tr>
                                <td class="content-cell" style="border: 10px solid red;padding: 10px">
                                    {!! $content !!}
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

            </table>
        </td>
    </tr>
</table>
</body>
</html>

{{--@component('mail::layout')--}}
{{-- Header --}}
{{--@slot('header')--}}
{{--@component('mail::header')--}}
{{--@isset($brandLink)--}}

{{--@endisset--}}
{{--@endcomponent--}}
{{--@endslot--}}
{{--@isset($content)--}}
{{-- Body --}}

{{-- Subcopy --}}
{{--@endisset--}}
{{--@isset($subcopy)--}}
{{--@slot('subcopy')--}}
{{--@component('mail::subcopy')--}}
{{--{{ $subcopy }}--}}
{{--@endcomponent--}}
{{--@endslot--}}
{{--@endisset--}}

{{-- Footer --}}
{{--@slot('footer')--}}
{{--@component('mail::footer')--}}
{{--@isset($brand)--}}
{{--© {{ date('Y') }} {{ $brand }}. @lang('All rights reserved.')--}}
{{--@endisset--}}
{{--@endcomponent--}}
{{--@endslot--}}
{{--@endcomponent--}}
