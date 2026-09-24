<?php

use App\Models\SiteSetting;

if (! function_exists('site')) {
    /**
     * Read a website setting value (cached).
     */
    function site(string $key, $default = null)
    {
        return SiteSetting::get($key, $default);
    }
}

if (! function_exists('site_json')) {
    /**
     * Read a website setting value as an array (for json-type settings).
     */
    function site_json(string $key, $default = []): array
    {
        $value = SiteSetting::get($key, null);

        if (is_array($value)) {
            return $value;
        }

        if (is_string($value) && $value !== '') {
            $decoded = json_decode($value, true);

            return is_array($decoded) ? $decoded : (array) $default;
        }

        return (array) $default;
    }
}
