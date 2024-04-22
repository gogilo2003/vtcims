@php
    $logo1 = config('eschool.logo.logo1');
    $logo2 = config('eschool.logo.logo2');
    $postal_address = '';
    $box_no = config('eschool.box_no');
    $post_code = config('eschool.post_code');
    $town = config('eschool.town');

    $postal_address = $box_no ? 'P.O. Box ' . $box_no . ($post_code ? ' - ' . $post_code . ', ' : ', ') . $town : '';
    $phone = config('eschool.phone');
    $email = config('eschool.email');
    $website = config('eschool.website');
    $facebook = config('eschool.facebook');
    $twitter = config('eschool.twitter');
    $youtube = config('eschool.youtube');
    $linkedin = config('eschool.linkedin');
    $physical_address = config('eschool.physical_address');
    // dump($logo);
@endphp
<div class="w-full mb-4">
    <table class="mb-0 pb-0 mx-auto w-fit">
        <tr>
            <td colspan="3">&nbsp;</td>
        </tr>
        <tr>
            <td class="left-logo pr-4">
                @if ($logo1)
                    @php
                        $image = public_path($logo1);
                        // Read image path, convert to base64 encoding
                        $imageData = base64_encode(file_get_contents($image));

                        // Format the image SRC:  data:{mime};base64,{data};
                        $src = 'data: ' . mime_content_type($image) . ';base64,' . $imageData;

                        // Echo out a sample image
                        // echo '<img src="' . $src . '">';

                    @endphp
                    <img src="{{ $src }}" alt="" class="h-28 w-auto">
                @endif
            </td>
            <td class="title text-center">
                <div class="uppercase" style="font-size:32px">{{ config('eschool.name') }}</div>
                <div class="text-sm font-light">
                    {{ $postal_address }} <br>
                    @if ($phone)
                        <span class="inline-block whitespace-nowrap">
                            {!! \App\Support\Icons::phone('class="w-4 h-4 object-contain inline"') !!}
                            {{ $phone }}
                        </span> |
                    @endif
                    @if ($email)
                        <span class="btn-address btn-email">
                            {!! \App\Support\Icons::email('class="w-4 h-4 object-contain inline"') !!}
                            {{ $email }}
                        </span> |
                    @endif
                    @if ($website)
                        <span class="btn-address btn-website">
                            {!! \App\Support\Icons::website('class="w-4 h-4 object-contain inline"') !!}
                            www.{{ $website }}</span>
                    @endif
                    @if (($phone || $email || $website) && ($facebook || $twitter || $youtube || $linkedin))
                        <hr class="my-2">
                    @endif
                    @if ($facebook)
                        <span class="btn-social btn-facebook">{!! \App\Support\Icons::facebook('class="w-4 h-4 object-contain inline"') !!} {{ $facebook }}</span>
                    @endif
                    @if ($twitter)
                        <span class="btn-social btn-twitter">{!! \App\Support\Icons::twitter('class="w-4 h-4 object-contain inline"') !!} {{ $twitter }}</span>
                    @endif
                    @if ($youtube)
                        <span class="btn-social btn-youtube">{!! \App\Support\Icons::youtube('class="w-4 h-4 object-contain inline"') !!} {{ $youtube }}</span>
                    @endif
                    @if ($linkedin)
                        <span class="btn-social btn-linkedin">{!! \App\Support\Icons::linkedin('class="w-4 h-4 object-contain inline"') !!} {{ $linkedin }}</span>
                    @endif
                    @if (($phone || $email || $website || $facebook || $twitter || $youtube || $linkedin) && $physical_address)
                        <hr class="my-2">
                    @endif
                    @if ($physical_address)
                        <span class="btn-social btn-website">{!! \App\Support\Icons::location('class="w-4 h-4 object-contain inline"') !!}
                            {{ $physical_address }}</span>
                    @endif
                </div>
            </td>
            <td class="right-logo pl-4">
                @if ($logo2)
                    @php
                        $image = public_path($logo2);
                        // Read image path, convert to base64 encoding
                        $imageData = base64_encode(file_get_contents($image));

                        // Format the image SRC:  data:{mime};base64,{data};
                        $src = 'data: ' . mime_content_type($image) . ';base64,' . $imageData;

                        // Echo out a sample image
                        // echo '<img src="' . $src . '">';

                    @endphp
                    <img src="{{ $src }}" alt="" class="h-28 w-auto">
                @endif
            </td>
        </tr>
    </table>
</div>
