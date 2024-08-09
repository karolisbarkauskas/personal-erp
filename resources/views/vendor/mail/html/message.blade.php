@component('mail::layout')
{{-- Header --}}
@slot('header')
@component('mail::header')
@isset($brandLink)
<a href="{{ $brandLink }}" style="display: inline-block;">
<img src="{!! $brandLogo !!}" alt="{{ $brand }}" height="50">
</a>
@endisset
@endcomponent
@endslot
@isset($content)
{{-- Body --}}
{!! $content !!}

{{-- Subcopy --}}
@endisset
@isset($subcopy)
@slot('subcopy')
@component('mail::subcopy')
{{ $subcopy }}
@endcomponent
@endslot
@endisset

{{-- Footer --}}
@slot('footer')
@component('mail::footer')
@isset($brand)
© {{ date('Y') }} {{ $brand }}. @lang('All rights reserved.')
@endisset
@endcomponent
@endslot
@endcomponent
