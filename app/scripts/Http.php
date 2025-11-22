<?php declare(strict_types=1); 

namespace app\scripts;

class Http
{
  
    public static function http_get_json(string $url)
    {
        $ch = curl_init(); // inicializando o curl
        
        curl_setopt_array($ch, [ // configurações do curl
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_CONNECTTIMEOUT => 5,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer ", 
                "Content-Type: application/json"
            ]
        ]);

            $error = curl_error($ch); // error do curl
            $status = curl_getinfo($ch, CURLINFO_HTTP_CODE); // status da resposta
            $response = json_decode(curl_exec($ch)); // resposta do curl junto com 
            curl_close($ch);

            if($response === false) // conexão com curl for falsa
            {
                return ['error' => 'Falha ao conectar: ' . $error, 'status' => 0];
            }
                if($response === null) // curl retorna null 
                {
                    return ['error' => 'Resposta não é JSON válido', 'status' => $status];
                }

                return $response; //retorno das informações da api
    }
}