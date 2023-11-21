<?php


namespace App\Enums;

enum Provider:string
{
    case Google = "google";

    public static function values():array
    {
        return array_map(
            fn(self $provider)=>$provider->value,
            self::cases()
        );
    }
}
