<?php

namespace App\Helpers;

class TextHelper
{
    public static function linkify($text)
    {
        $pattern = '/(https?:\/\/[^\s]+)/';
        $replacement = '<a href="$1" target="_blank">$1</a>';
        return preg_replace($pattern, $replacement, $text);
    }
}
