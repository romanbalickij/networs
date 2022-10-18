<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
{{ $slot }}
    @php $url =  fileUrl('img/letter.jpeg') @endphp

    <img class="img_avatar cover" src="{{ env('FILE_DISK') == 'public' ? env('APP_URL').$url : $url}}" alt="">
</a>
</td>
</tr>
