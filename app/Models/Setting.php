<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    public static function getValue($key, $default = null)
    {
        $setting = Cache::rememberForever('setting.' . $key, function () use ($key) {
            return self::where('key', $key)->first();
        });

        return $setting ? $setting->value : $default;
    }

    public static function setValue($key, $value)
    {
        $setting = self::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );

        Cache::forget('setting.' . $key);
        return $setting;
    }

    protected static function boot()
    {
        parent::boot();

        static::updated(function ($setting) {
            Cache::forget('setting.' . $setting->key);
        });

        static::deleted(function ($setting) {
            Cache::forget('setting.' . $setting->key);
        });
    }
}
