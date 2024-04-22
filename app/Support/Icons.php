<?php
namespace App\Support;

use Illuminate\Support\Str;

final class Icons
{
    public static function phone($attributes): string
    {
        return Str::replace(
            "</template>",
            "",
            Str::replace(
                "<template>",
                '<span class="w-5 h-5 object-contain">',
                Str::replace(
                    '<svg',
                    '<svg ' . $attributes,
                    file_get_contents(resource_path('js/Components/Icons/Phone.vue'))
                )
            )
        );
    }
    public static function email($attributes): string
    {
        return Str::replace(
            "</template>",
            "",
            Str::replace(
                "<template>",
                '<span class="w-5 h-5 object-contain">',
                Str::replace(
                    '<svg',
                    '<svg ' . $attributes,
                    file_get_contents(resource_path('js/Components/Icons/Email.vue'))
                )
            )
        );
    }
    public static function website($attributes): string
    {
        return Str::replace(
            "</template>",
            "",
            Str::replace(
                "<template>",
                '<span class="w-5 h-5 object-contain">',
                Str::replace(
                    '<svg',
                    '<svg ' . $attributes,
                    file_get_contents(resource_path('js/Components/Icons/Website.vue'))
                )
            )
        );
    }
    public static function facebook($attributes): string
    {
        return Str::replace(
            "</template>",
            "",
            Str::replace(
                "<template>",
                '<span class="w-5 h-5 object-contain">',
                Str::replace(
                    '<svg',
                    '<svg ' . $attributes,
                    file_get_contents(resource_path('js/Components/Icons/Facebook.vue'))
                )
            )
        );
    }
    public static function twitter($attributes): string
    {
        return Str::replace(
            "</template>",
            "",
            Str::replace(
                "<template>",
                '<span class="w-5 h-5 object-contain">',
                Str::replace(
                    '<svg',
                    '<svg ' . $attributes,
                    file_get_contents(resource_path('js/Components/Icons/Twitter.vue'))
                )
            )
        );
    }
    public static function youtube($attributes): string
    {
        return Str::replace(
            "</template>",
            "",
            Str::replace(
                "<template>",
                '<span class="w-5 h-5 object-contain">',
                Str::replace(
                    '<svg',
                    '<svg ' . $attributes,
                    file_get_contents(resource_path('js/Components/Icons/Youtube.vue'))
                )
            )
        );
    }
    public static function linkedin($attributes): string
    {
        return Str::replace(
            "</template>",
            "",
            Str::replace(
                "<template>",
                '<span class="w-5 h-5 object-contain">',
                Str::replace(
                    '<svg',
                    '<svg ' . $attributes,
                    file_get_contents(resource_path('js/Components/Icons/Linkedin.vue'))
                )
            )
        );
    }
    public static function location($attributes): string
    {
        return Str::replace(
            "</template>",
            "",
            Str::replace(
                "<template>",
                '<span class="w-5 h-5 object-contain">',
                Str::replace(
                    '<svg',
                    '<svg ' . $attributes,
                    file_get_contents(resource_path('js/Components/Icons/Location.vue'))
                )
            )
        );
    }
}
