<?php

namespace App\Enums;

enum Admins:string
{
    case Ahmad = "damsostore@gmail.com";
    case assistant= 'mawaniangdiop@gmail.com';

    public static function values():array
    {
        return array_map(
            fn(self $provider)=>$provider->value,
            self::cases()
        );
    }
}
