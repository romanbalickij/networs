@component('mail::layout')
    {{-- Header --}}
    @slot('header')
        @component('mail::header', ['url' => config('app.url')])

        @endcomponent
    @endslot

    {{-- Body --}}
    <div class="center_wrapper">
        <div class="notification_tooltip">
            <div class="tooltip_content">
                <div class="user_wrapper">
                    <div class="user_avatar">
                        <img class="img_avatar cover" src="{{letterRenderAvatar($subscribed->user->profilePhotoUrl)}}" alt="">
                    </div>
                    <div class="user_right_wrapper">
                        <div class="name_notify_wrapper">
                            <div class="name_wrapper">
                                <div class="title_wrap">
                                    <span class="user_title">{{$subscribed->user->fullname}}</span>
                                    @if($subscribed->user->isAccountVerified())
                                        <img src="{{letterRenderImage('img/Verif_gray.svg')}}" alt="">
                                    @endif
                                </div>
                                <p>{{$subscribed->user->nickname}}</p>
                            </div>
                        </div>
                        <p class="small_text margin_top">{{__('letter.notify-subscription.text_1')}}</p>
                    </div>
                </div>
            </div>
        </div>
        <p class="margin_remove">{{__('letter.notify-subscription.text_2', ['count' => $subscribed->owner->unreadInformings->count()])}}</p>
        <div class="button_wrapper half">
            <div class="button_item">
                <div class="button_item_table">
                    <div class="button border">
                        <a href="{{env('APP_URL')}}/settings/statistics" class="button_link">{{__('letter.notify-subscription.button_statistics')}}</a>
                    </div>
                </div>
            </div>
            <div class="button_item">
                <div class=button_item_table">
                    <div class="button primary">
                        <a href="{{env('APP_URL')}}/notifications" class="button_link">{{__('letter.notify-subscription.button_notification')}}</a>
                    </div>
                </div>
            </div>
        </div>
        <p>{{__('letter.notify-subscription.thank')}}<br>TheFans team.</p>
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
                                <a href="{{env('APP_URL')}}/settings" target="_blank" rel="noopener">{{__('letter.notify-subscription.button_settings')}}</a>
                                <a href="{{env('APP_URL')}}/support" target="_blank" rel="noopener">{{__('letter.notify-subscription.button_help')}}</a>
                            </div>
                        </div>
                    </td>
                </tr>
            </table>
        @endcomponent
    @endslot
@endcomponent
