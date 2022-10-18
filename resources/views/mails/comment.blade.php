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
                        <img class="img_avatar cover" src="{{letterRenderAvatar($comment->user->profilePhotoUrl)}}" alt="">
                    </div>
                    <div class="user_right_wrapper">
                        <div class="name_notify_wrapper">
                            <div class="name_wrapper">
                                <div class="title_wrap">
                                    <span class="user_title">{{$comment->user->fullname}}</span>
                                    @if($comment->user->isAccountVerified())
                                        <img src="{{letterRenderImage('img/Verif_gray.svg')}}" alt="">
                                    @endif
                                </div>
                                <p>{{$comment->user->nickname}}</p>
                            </div>
                        </div>
                        <p class="small_text margin_top">{{__('letter.notify-comment.text_1')}}</p>
                    </div>
                </div>
            </div>
        </div>
        <p class="margin_remove">{{__('letter.notify-comment.text_2', ['count' => $user->unreadInformings->count() ])}}</p>
        <div class="button_wrapper half">
            <div class="button_item">
                <div class="button_item_table">
                    <div class="button border">
                        <a href="{{env('APP_URL')}}/settings/statistics" class="button_link">{{__('letter.notify-comment.button_statistics')}}</a>
                    </div>
                </div>
            </div>
            <div class="button_item">
                <div class=button_item_table">
                    <div class="button primary">
                        <a href="{{env('APP_URL')}}/notifications" class="button_link">{{__('letter.notify-comment.button_notification')}}</a>
                    </div>
                </div>
            </div>
        </div>
        <p>{{__('letter.notify-comment.thank')}}<br>TheFans team.</p>
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
                                <a href="{{env('APP_URL')}}/settings" target="_blank" rel="noopener">{{__('letter.notify-comment.button_settings')}}</a>
                                <a href="{{env('APP_URL')}}/support" target="_blank" rel="noopener">{{__('letter.notify-comment.button_help')}}</a>
                            </div>
                        </div>
                    </td>
                </tr>
            </table>
        @endcomponent
    @endslot
@endcomponent
