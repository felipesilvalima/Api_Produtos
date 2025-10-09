<?php declare(strict_types=1); 

namespace app\helpers;

class Uri
{
    public static function get($type): string // pegar a uri da requisição do tipo path/queryString
    {
        return parse_url($_SERVER['REQUEST_URI'])[$type];
    }
}