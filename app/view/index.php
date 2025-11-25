<?php  declare(strict_types=1);

use app\scripts\Http;
require_once __DIR__.'/../../config/url_base_api.php';
require __DIR__.'/../../vendor/autoload.php';

$response_login = Http::http_post_json("http://localhost:8000/login", "", "");

echo  $response_login->mensagem . "<br>" ?? null;

if(isset($response_login->token))
{

    $responses = Http::http_get_json(BASE_URL_API, $response_login->token);
    
    echo $responses->mensagem ?? null;
    
    if(isset($responses->datas))
    {
    
        foreach($responses->datas as $res)
        {
            echo $res->produto . "<br>";
                
        }
    }
    
}



?>