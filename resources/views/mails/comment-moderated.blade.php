@component('mail::layout')
    {{-- Header --}}
    @slot('header')
        @component('mail::header', ['url' => config('app.url')])
        @endcomponent
    @endslot

    {{-- Body --}}
    <div class="center_wrapper">
        <p class="bold padding_y">   {!! getLetterText($texts, 'header') !!}</p>
        <div class="notification_tooltip moderated margin_remove">
            <div class="tooltip_content">
                <div class="user_wrapper">
                        <div class="user_avatar small">
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
                                <div class="time_wrapper">
                                    <p class="time">{{$comment->updated_at->format('d M')}}</p>
                                    <p class="time"><span class="warning">{{__('letter.notify-comment-moderated.text_1')}}</span> • {{$comment->updated_at->format('h:i')}}</p>
                                </div>
                            </div>
                            <p class="margin_top">{{$comment->content}}</p>
                        </div>
                    </div>
            </div>
        </div>
        <div class="button_wrapper_center">
            <div class="button large primary">
                <a href="{{env('APP_URL')}}/support" class="button_link">{{__('letter.notify-comment-moderated.button_support')}}</a>
            </div>
        </div>
        <p>{{__('letter.notify-comment-moderated.text_2', ['count' => $comment->user->unreadInformings->count()])}}</p>
    </div>
    <div class="button_wrapper tree">
        <div class="button_item">
            <div class="button_item_table">
                <div class="button border">
                    <a href="{{env('APP_URL')}}/settings/statistics" class="button_link">{{__('letter.notify-comment-moderated.button_statistics')}}</a>
                </div>
            </div>
        </div>
        <div class="button_item">
            <div class="button_item_table">
                <div class="button border">
                    <a href="{{env('APP_URL')}}/notifications" class="button_link">{{__('letter.notify-comment-moderated.button_notification')}}</a>
                </div>
            </div>
        </div>
        <div class="button_item">
            <div class="button_item_table">
                <div class="button border">
                    <a href="{{env('APP_URL')}}/page/terms_of_use" class="button_link">{{__('letter.notify-comment-moderated.button_terms')}}</a>
                </div>
            </div>
        </div>
    </div>
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
                        {!! getLetterText($texts, 'footer') !!}
                    </td>
                    <td class="footer_right">
                        <div class="table_inner">
                            <div>
                                <a href="{{env('APP_URL')}}/settings" target="_blank" rel="noopener">{{__('letter.notify-comment-moderated.button_settings')}}</a>
                                <a href="{{env('APP_URL')}}/support" target="_blank" rel="noopener">{{__('letter.notify-comment-moderated.button_help')}}</a>
                            </div>
                        </div>
                    </td>
                </tr>
            </table>
        @endcomponent
    @endslot
@endcomponent

