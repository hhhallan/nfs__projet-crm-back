<?php

namespace App;

class Util
{
    static function tryGet(array $array, string $key, $default = null) {
        if(array_key_exists($key, $array)) {
            return $array[$key] ?? $default;
        }else return $default;
    }
}