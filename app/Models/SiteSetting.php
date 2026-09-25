<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SiteSetting extends Model
{
    use Auditable;

    protected $fillable = ['key', 'value', 'group', 'type', 'label'];

    public const CACHE_KEY = 'site_settings';

    public static function flush(): void
    {
        Cache::forget(self::CACHE_KEY);
    }

    public static function all_settings(): array
    {
        return Cache::rememberForever(self::CACHE_KEY, function () {
            return static::all()->pluck('value', 'key')->toArray();
        });
    }

    public static function get($key, $default = null)
    {
        $settings = static::all_settings();

        return array_key_exists($key, $settings) ? $settings[$key] : $default;
    }

    public static function set($key, $value, $group = 'general', $type = 'text', $label = null): void
    {
        static::updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'group' => $group, 'type' => $type, 'label' => $label ?? $key]
        );
        static::flush();
    }
}
