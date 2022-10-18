@component('mail::layout')
    {{-- Header --}}
    @slot('header')
        @component('mail::header', ['url' => config('app.url')])
        @endcomponent
    @endslot

    {{-- Body --}}
    <div class="center_wrapper">
        <p class="bold padding_y">{!! getLetterText($texts, 'header') !!}</p>
        @foreach($messages as $message)
            @if ($loop->iteration -1 < 5)
              <div class="notification_tooltip message margin_top">
                <div class="tooltip_content">
                    <div class="user_wrapper">
                        <div class="user_avatar">
                            <img class="img_avatar cover" src="{{letterRenderAvatar($message->owner->profilePhotoUrl)}}" alt="">
                        </div>
                        <div class="user_right_wrapper">
                            <div class="name_notify_wrapper">
                                <div class="name_wrapper">
                                    <div class="title_wrap">
                                        <span class="user_title">{{$message->owner->fullname}}</span>
                                        @if($message->owner->isAccountVerified())
                                          <img src="{{letterRenderImage('img/Verif_gray.svg')}}" alt="">
                                        @endif
                                    </div>
                                </div>
                                <div class="time_wrapper">
                                    <p class="time">{{$message->created_at->format('h:i')}}</p>
                                </div>
                            </div>
                            <p class="margin_top short ">{{$message->text}}</p>
                        </div>
                    </div>
                </div>
              </div>
            @endif
        @php $message->sendNotificationToEmail() @endphp
        @endforeach
        <p class="margin_remove">{{__('letter.notify-unread-message.text_1', ['count' =>$messages->count() ])}}</p>
        <div class="button_wrapper_center">
            <div class="button large primary">
                <a href="{{env('APP_URL')}}/messages" class="button_link">{{__('letter.notify-unread-message.button_messages')}}</a>
            </div>
        </div>
        <p>{{__('letter.notify-unread-message.thank')}}<br>TheFans team.</p>
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
                                <a href="{{env('APP_URL')}}/settings" target="_blank" rel="noopener">{{__('letter.notify-unread-message.button_settings')}}</a>
                                <a href="{{env('APP_URL')}}/support" target="_blank" rel="noopener">{{__('letter.notify-unread-message.button_help')}}</a>
                            </div>
                        </div>
                    </td>
                </tr>
            </table>
        @endcomponent
    @endslot
@endcomponent
