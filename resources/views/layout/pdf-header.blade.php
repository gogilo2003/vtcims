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
                <address class="text-sm font-extralight">
                    {{ $postal_address }} <br>
                    @if ($phone)
                        <span class="btn-address btn-phone"><i class="fa fa-phone"></i>
                            {{ $phone }}</span> |
                    @endif
                    @if ($email)
                        <span class="btn-address btn-email"><i class="fa fa-envelope"></i> {{ $email }}</span> |
                    @endif
                    @if ($website)
                        <span class="btn-address btn-website"><i class="fa fa-globe"></i>
                            www.{{ $website }}</span>
                    @endif
                    @if (($phone || $email || $website) && ($facebook || $twitter || $youtube || $linkedin))
                        <hr class="my-2">
                    @endif
                    @if ($facebook)
                        <span class="btn-social btn-facebook"><i class="fa fa-facebook"></i> {{ $facebook }}</span>
                    @endif
                    @if ($twitter)
                        <span class="btn-social btn-twitter"><i class="fa fa-twitter"></i> {{ $twitter }}</span>
                    @endif
                    @if ($youtube)
                        <span class="btn-social btn-youtube"><i class="fa fa-youtube"></i> {{ $youtube }}</span>
                    @endif
                    @if ($linkedin)
                        <span class="btn-social btn-linkedin"><i class="fa fa-linkedin"></i> {{ $linkedin }}</span>
                    @endif
                    @if (($phone || $email || $website || $facebook || $twitter || $youtube || $linkedin) && $physical_address)
                        <hr class="my-2">
                    @endif
                    @if ($physical_address)
                        <span class="btn-social btn-website"><i class="fa fa-map"></i>
                            {{ $physical_address }}</span>
                    @endif
                </address>
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
