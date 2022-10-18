@component('mail::layout')
    {{-- Header --}}
    @slot('header')
        @component('mail::header', ['url' => config('app.url')])
        @endcomponent
    @endslot

    {{-- Body --}}
    You code  1234
    <p>{{__('letter.notify-comment-moderated.thank')}}<br>TheFans team.</p>
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
            <table class="row_wrapper">
                <tr>
                    <td>
                        _test
                    </td>
                    <td class="footer_right">
                        <div class="table_inner">
                            <div>
                                <a href="{{env('APP_URL')}}/settings" target="_blank" rel="noopener">_test</a>
                                <a href="{{env('APP_URL')}}/messages?chat=support" target="_blank" rel="noopener">_test</a>
                            </div>
                        </div>
                    </td>
                </tr>
            </table>
        @endcomponent
    @endslot
@endcomponent

