@component('mail::layout')
    {{-- Header --}}
    @slot('header')
        @component('mail::header', ['url' => config('app.url')])
        @endcomponent
    @endslot

    {{-- Body --}}
    <h1>{{__('letter.subscription-prolong.text_4')}}</h1>
    <div class="center_wrapper">
        <p class="margin_remove">{{__('letter.subscription-prolong.text_1', ['sum' => $sum])}}
            <span class="bold">{{$user->fullName}}</span> {{__('letter.subscription-prolong.text_2')}} <span class="bold">{{$card->last_four}}</span>.
            {{__('letter.subscription-prolong.text_3')}}</p>
        <div class="button_wrapper_center">
            <div class="button large primary">
                <a href="{{env('APP_URL')}}/settings/finance" class="button_link">{{__('letter.subscription-prolong.button_methods')}}</a>
            </div>
        </div>
        {!!str_replace('$number', App\Models\Subscription::UI_ATTEMPTS - $attempts, getLetterText($texts, 'body')) !!}
        <p>{{__('letter.subscription-prolong.thank')}}<br>TheFans team.</p>
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
            <table class="row_wrapper">
                <tr>
                    <td>
                        {!! getLetterText($texts, 'footer') !!}
                    </td>
                    <td class="footer_right">
                        <div class="table_inner">
                            <div>
                                <a href="{{env('APP_URL')}}/support" target="_blank" rel="noopener">{{__('letter.subscription-prolong.button_help')}}</a>
                            </div>
                        </div>
                    </td>
                </tr>
            </table>
        @endcomponent
    @endslot
@endcomponent

