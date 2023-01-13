<?php

namespace App;

class Util
{
    static function tryGet(array $array, string $key, $default = null) {
        if(array_key_exists($key, $array)) {
            return $array[$key] ?? $default;
        }else return $default;
    }

    static function generateToken(int $length = 40): string
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}