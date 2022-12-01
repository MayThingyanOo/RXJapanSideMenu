<?php

namespace App\Lib\CpsBlade;

use Illuminate\Support\Str;

class Directive
{
    public static function css($file)
    {
        $path = static::getCssPath($file);
        return '<link href="' . auto_version($path) . '" rel="stylesheet">';
    }

    public static function cssIf($file)
    {
        $path = static::getCssPath($file);
        if (file_exists($_SERVER['DOCUMENT_ROOT'] . $path)) {
            return static::css($path);
        }
    }

    protected static function getCssPath($file)
    {
        $path = $file;
        if (!Str::startsWith($path, '/')) {
            $path = '/stylesheets/' . str_replace('.', '/', $path);
        }
        if (!Str::endsWith($path, '.css')) {
            $path .= '.css';
        }
        return $path;
    }
}
