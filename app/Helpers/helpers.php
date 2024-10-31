<?php

if (!function_exists('setting')) {
    function setting($key, $default = null)
    {
        return \App\Models\Setting::getValue($key, $default);
    }
}

if (!function_exists('format_money')) {
    function format_money($amount)
    {
        return number_format($amount, 2, '.', ',');
    }
}

if (!function_exists('get_store_timezone')) {
    function get_store_timezone()
    {
        return auth()->user()->store->timezone ?? config('app.timezone');
    }
} 
