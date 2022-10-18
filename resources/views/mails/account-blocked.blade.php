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
                <a href="{{env('APP_URL')}}/support" class="button_link">{{__("letter.notify-account-blocked.button_support")}}</a>
            </div>
        </div>
        <p>{{__("letter.notify-account-blocked.text_1", ['count' => $user->unreadInformings->count()])}}</p>
    </div>
    <div class="button_wrapper tree">
        <div class="button_item">
            <div class="button_item_table">
                <div class="button border">
                    <a href="{{env('APP_URL')}}/settings/statistics" class="button_link">{{__("letter.notify-account-blocked.button_statistics")}}</a>
                </div>
            </div>
        </div>
        <div class="button_item">
                <div class="button_item_table">
                    <div class="button border">
                        <a href="{{env('APP_URL')}}/notifications" class="button_link">{{__("letter.notify-account-blocked.button_notification")}}</a>
                    </div>
                </div>
        </div>
        <div class="button_item">
            <div class="button_item_table">
                <div class="button border">
                    <a href="{{env('APP_URL')}}/page/terms_of_use" class="button_link">{{__("letter.notify-account-blocked.button_terms")}}</a>
                </div>
            </div>
        </div>
    </div>
    <p>{{__("letter.notify-account-blocked.thank")}}<br>TheFans team.</p>
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
                                <a href="{{env('APP_URL')}}/settings" target="_blank" rel="noopener">{{__("letter.notify-account-blocked.button_settings")}}</a>
                                <a href="{{env('APP_URL')}}/support" target="_blank" rel="noopener">{{__("letter.notify-account-blocked.button_help")}}</a>
                            </div>
                        </div>
                    </td>
                </tr>
            </table>
        @endcomponent
    @endslot
@endcomponent
