<?php

if (!function_exists('asset_url')) {
    function asset_url($path) {
        $path = ltrim($path, '/');
        if (app()->environment('local')) {
            return asset("public/{$path}");
        }
        return asset($path);
    }
}