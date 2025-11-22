<?php  declare(strict_types=1);

use app\scripts\Http;
require_once __DIR__.'/../../config/url_base_api.php';
require __DIR__.'/../../vendor/autoload.php';

$responses = Http::http_get_json(BASE_URL_API);

echo $responses->mensagem ?? null;

if(isset($responses->datas))
{

    foreach($responses->datas as $res)
    {
        echo $res->produto . "<br>";
            
    }
}


?>