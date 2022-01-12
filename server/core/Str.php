<?php

namespace core;

class Str
{
    public static function toPascalCase(string $str): string
    {
        $str = str_replace('-', ' ', $str);
        $str = ucwords($str);
        $str = str_replace(' ', '', $str);

        return $str;
    }

    public static function toCamelCase(string $str): string
    {
        return lcfirst(self::toPascalCase($str));
    }

    public static function getControllerName(string $str): string
    {
        $str = strrchr($str, '\\');
        $str = substr($str, 1);
        $str = lcfirst($str);
        $str = stristr($str, 'controller', true);

        return $str;
    }
}
