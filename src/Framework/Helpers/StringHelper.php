<?php

declare(strict_types=1);

namespace Cheremhovo1990\Framework\Helpers;

class StringHelper
{
    public static function replace(array|string $search, array|string $replace, array|string $subject, &$count = 0): string
    {
        return str_replace($search, $replace, $subject, $count);
    }


    public static function replaceEnd(string $search,string $replace, string $subject): string
    {
        $position = strrpos($subject, $search);
        if ($position !== false) {
            return substr_replace($subject, $replace, $position, strlen($search));
        }
        return $subject;
    }

    public static function replaceStart(string $search,string $replace, string $subject): string
    {
        $position = strpos($subject, $search);
        if ($position === 0) {
            return substr_replace($subject, $replace, 0, strlen($search));
        }
        return $subject;
    }

    public static function equalEnd($haystack, $needle): bool
    {
        return str_ends_with($haystack, $needle);
    }

    public static function camelCaseToId(string $controller): string
    {
        $pattern = '~(\p{Ll}{1,1})(\p{Lu})~';
        return strtolower(preg_replace($pattern, '$1-$2', $controller));
    }
}