@component('mail::layout')
    {{-- Header --}}
    @slot('header')
        @component('mail::header', ['url' => config('app.url')])
        @endcomponent
    @endslot

    {{-- Body --}}
    <p class="bold margin_top">{!! getLetterText($texts, 'header') !!}</p>
    <div class="center_wrapper">
        <div class="button_wrapper_center">
            <div class="button large primary">
                <a href="{{env('APP_URL')}}" class="button_link">{{__('letter.account.text_1')}} thefans</a>
            </div>
        </div>
        <p class="margin_bottom">{{__('letter.account.text_2', ['count' => $user->unreadInformings->count()])}}</p>
        <div class="button_wrapper half">
            <div class="button_item">
                <div class="button_item_table">
                    <div class="button border">
                        <a href="{{env('APP_URL')}}/settings/statistics" class="button_link">{{__('letter.account.text_button_1')}}</a>
                    </div>
                </div>
            </div>
            <div class="button_item">
                <div class=button_item_table">
                    <div class="button border">
                        <a href="{{env('APP_URL')}}/notifications" class="button_link">{{__('letter.account.text_button_2')}} </a>
                    </div>
                </div>
            </div>
        </div>
        <p>{{__('letter.account.thank')}}<br>TheFans team.</p>
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
                                <a href="{{env('APP_URL')}}/settings" target="_blank" rel="noopener">{{__('letter.account.text_button_3')}}</a>
                                <a href="{{env('APP_URL')}}/support" target="_blank" rel="noopener">{{__('letter.account.text_button_4')}}</a>
                            </div>
                        </div>
                    </td>
                </tr>
            </table>
        @endcomponent
    @endslot
@endcomponent
