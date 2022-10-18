@component('mail::layout')
    {{-- Header --}}
    @slot('header')
        @component('mail::header', ['url' => config('app.url')])
        @endcomponent
    @endslot

    {{-- Body --}}
    <h1>{{__('letter.newInvoiceNotify.text_1')}} TheFans!</h1>
    <p class="bold">{{__('letter.newInvoiceNotify.text_2', ['direction' => $invoice->direction, 'sum' =>$invoice->sum])}}</p>
    <div class="center_wrapper">
        <div class="button_wrapper_center">
            <div class="button large primary">
                <a href="{{$downloadUrl}}" class="button_link" target="_blank">{{__('letter.newInvoiceNotify.button_download')}}</a>
            </div>
        </div>
    </div>
    <p class="margin_remove">{!! getLetterText($texts, 'header') !!}</p>
    <div class="center_wrapper">
        <div class="button_wrapper half">
            <div class="button_item">
                <div class="button_item_table">
                    <div class="button border">
                        <a href="{{env('APP_URL')}}/support" class="button_link">{{__('letter.newInvoiceNotify.button_support')}}</a>
                    </div>
                </div>
            </div>
            <div class="button_item">
                <div class="button_item_table">
                    <div class="button border">
                        <a href="{{env('APP_URL')}}/settings/invoices" class="button_link">{{__('letter.newInvoiceNotify.button_invoices')}}</a>
                    </div>
                </div>
            </div>
        </div>
        <p>{{__('letter.newInvoiceNotify.thank')}}<br>TheFans team.</p>
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
                                    <a href="{{env('APP_URL')}}/settings" target="_blank" rel="noopener">{{__('letter.newInvoiceNotify.button_settings')}}</a>
                                    <a href="{{env('APP_URL')}}/support" target="_blank" rel="noopener">{{__('letter.newInvoiceNotify.button_help')}}</a>
                                </div>
                            </div>
                        </td>
                    </tr>
                </table>
        @endcomponent
    @endslot
@endcomponent
