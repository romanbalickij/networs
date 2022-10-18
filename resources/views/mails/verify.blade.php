@component('mail::layout')
    {{-- Header --}}
    @slot('header')
        @component('mail::header', ['url' => config('app.url')])
        @endcomponent
    @endslot

    {{-- Body --}}
    <div class="center_wrapper">
        <h1>Hello!</h1>
        <p class="margin_remove ">{{getLetterText($texts, 'header')}}</p>
        <div class="button_wrapper_center">
            <div class="button primary">
                <a href="{{$token}}" class="button_link">{{$page->title ?? null}}</a>
            </div>
        </div>
          {!! getLetterText($texts, 'body') !!}
    </div>
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
