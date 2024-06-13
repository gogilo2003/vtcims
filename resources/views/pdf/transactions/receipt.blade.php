<!DOCTYPE html>
<html>

<head>
    <title>Receipt</title>
    <style>
        {!! file_get_contents(public_path('css/pdf.css')) !!}
    </style>
</head>

<body>
    <div class="w-[calc(54mm_-_2rem)] mx-4 font-sans antialiased border-b-[1px] border-dashed [border-color:#000]">
        <div class="h-24 w-full pb-2">
            <img class="w-auto h-full object-contain mx-auto" src="{{ $logo }}" alt="Logo">
        </div>
        <div class="text-center text-xl font-bold">{{ config('app.name') }}</div>
        <div class="text-xs font-medium">
            <div class="text-center">
                {{ sprintf('P.O. Box %s%s, %s', config('eschool.box_no'), config('eschool.post_code') ? ' - ' . config('eschool.post_code') : '', config('eschool.town')) }}
            </div>
            <div class="text-center">
                {{ config('eschool.phone') }} | {{ config('eschool.email') }}
            </div>
        </div>
        <div class="text-center uppercase font-semibold my-4 border-b border-t [border-color#000] py-2">Receipt</div>
        <div class="text-center uppercase font-medium mb-4">{{ $number }}</div>
        <div class="text-sm text-center mb-16">
            @isset($txDate)
                <p class="mb-2"><span class="font-semibold">Date: </span>{{ $txDate }}</p>
            @endisset
            @isset($amount)
                <p class="text-2xl font-medium px-4 py-6 bg-primary-600 text-gray-50 mb-3">{{ $amount }}</p>
            @endisset
            @isset($description)
                <p>{{ $description }}</p>
            @endisset
        </div>
        <!-- Add more details as needed -->
    </div>
</body>

</html>
