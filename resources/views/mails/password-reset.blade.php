@component('mail::layout')
    {{-- Header --}}
    @slot('header')
        @component('mail::header', ['url' => config('app.url')])
        @endcomponent
    @endslot

    {{-- Body --}}
    <h1>{{__('letter.password-reset.hello')}}</h1>
    <p class="half margin_remove">{{getLetterText($texts, 'header')}}</p>
    <div class="center_wrapper">
        <div class="button_wrapper_center">
            <div class="button primary">
                <a href="{{$token}}" class="button_link">{{$page->title}}</a>
            </div>
        </div>
    </div>
     {!! getLetterText($texts, 'body') !!}
    {{-- Subcopy --}}
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
            {!! getLetterText($texts, 'footer') !!}
            <a href="{{$token}}" target="_blank" rel="noopener">{{$token}}</a>
        @endcomponent
    @endslot
@endcomponent
