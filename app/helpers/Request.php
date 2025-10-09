<?php declare(strict_types=1); 

namespace app\helpers;

class Request
{
    public static function get(): string // pegar o método da requisição
    {
        return $_SERVER['REQUEST_METHOD'];
    }
}