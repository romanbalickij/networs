@component('mail::layout')
    {{-- Header --}}
    @slot('header')
        @component('mail::header', ['url' => config('app.url')])
        @endcomponent
    @endslot

    {{-- Body --}}
        <div class="post_preview">
            <div class="post_images_wrapper">
                @foreach($message->images as $image)
                    <div class="parent">
                        <div class="cell">
                            <img src="{{letterRenderImage($image->url)}}"/>
                        </div>
                @endforeach
            </div>
            <div class="post_text">
                {{$message->text}}
            </div>
        </div>
        <div class="center_wrapper">
            <div class="button_wrapper_center">
                <div class="button large primary">
                    <a href="{{env('APP_URL')}}" class="button_link">{{__('letter.promotion.button_thefans')}}</a>
                </div>
            </div>
        </div>
    <p>{{__('letter.promotion.thank')}}<br>TheFans team.</p>
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
                                        <a href="{{env('APP_URL')}}/settings" target="_blank" rel="noopener">{{__('letter.promotion.button_settings')}}</a>
                                        <a href="{{env('APP_URL')}}/support" target="_blank" rel="noopener">{{__('letter.promotion.button_help')}}</a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </table>
        @endcomponent
    @endslot
@endcomponent

