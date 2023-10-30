@php
$logo = config('eschool.logo');
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
<div class="letter-head">
    <table class="table table-no-border mb-0 pb-0">
        <tr>
            <td colspan="3">&nbsp;</td>
        </tr>
        <tr>
            <td class="left-logo">
                @if (isset($logo[0]) && file_exists(public_path(config('admin.path_prefix') . $logo[0])))
                    @php
                        $image = is_array($logo) ? public_path(config('admin.path_prefix') . $logo[0]) : public_path(config('admin.path_prefix') . $logo);
                        // Read image path, convert to base64 encoding
                        $imageData = base64_encode(file_get_contents($image));
                        
                        // Format the image SRC:  data:{mime};base64,{data};
                        $src = 'data: ' . mime_content_type($image) . ';base64,' . $imageData;
                        
                        // Echo out a sample image
                        // echo '<img src="' . $src . '">';
                        
                    @endphp
                    <img src="{{ $src }}" alt="" class="img-fluid logo">
                @endif
            </td>
            <td class="title text-center">
                <div class="text-uppercase" style="font-size:32px">{{ config('eschool.name') }}</div>
                <address>
                    {{ $postal_address }} <br>
                    @if ($phone)
                        <span class="btn-address btn-phone"><i class="fa fa-phone"></i>
                            {{ clean_isdn($phone) }}</span> |
                    @endif
                    @if ($email)
                        <span class="btn-address btn-email"><i class="fa fa-envelope"></i> {{ $email }}</span> |
                    @endif
                    @if ($website)
                        <span class="btn-address btn-website"><i class="fa fa-globe"></i>
                            www.{{ $website }}</span>
                    @endif
                    @if (($phone || $email || $website) && ($facebook || $twitter || $youtube || $linkedin))
                        <hr>
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
                        <hr>
                    @endif
                    @if ($physical_address)
                        <span class="btn-social btn-website"><i class="fa fa-map"></i>
                            {{ $physical_address }}</span>
                    @endif
                </address>
            </td>
            <td class="right-logo">
                @php
                    $image = is_array($logo) ? public_path(config('admin.path_prefix') . $logo[1]) : public_path(config('admin.path_prefix') . $logo);
                    // Read image path, convert to base64 encoding
                    $imageData = base64_encode(file_get_contents($image));
                    
                    // Format the image SRC:  data:{mime};base64,{data};
                    $src = 'data: ' . mime_content_type($image) . ';base64,' . $imageData;
                    
                    // Echo out a sample image
                    // echo '<img src="' . $src . '">';
                    
                @endphp
                <img src="{{ $src }}" alt="" class="img-fluid logo">
            </td>
        </tr>
    </table>
</div>
