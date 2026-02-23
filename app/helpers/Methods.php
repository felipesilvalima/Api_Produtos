<?php declare(strict_types=1); 

namespace app\helpers;

trait Methods
{
    public static function requestPut()
    {
        $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
        $rawData = file_get_contents('php://input');

        $data = [];

            if (stripos($contentType, 'multipart/form-data') !== false) 
            {

                // Extrai o boundary
                preg_match('/boundary=(.*)$/', $contentType, $matches);
                $boundary = $matches[1] ?? '';

                // Divide as partes do multipart
                $parts = array_slice(explode('--' . $boundary, $rawData), 1, -1);

                foreach ($parts as $part) 
                {
                    if (empty(trim($part))) continue;

                    // Separa cabeçalhos do conteúdo
                    list($rawHeaders, $body) = explode("\r\n\r\n", $part, 2);
                    $body = trim($body);

                    // Encontra o nome do campo
                    if (preg_match('/name="([^"]+)"/', $rawHeaders, $nameMatch)) 
                    {
                        $name = $nameMatch[1];
                        $data[$name] = $body;
                    }
                }
            } 
                else 
                {
                    // Fallback: tenta JSON ou URL-encoded
                    $data = json_decode($rawData, true) ?? [];
                        if (empty($data)) 
                        {
                            parse_str($rawData, $data);
                        }
                }
                
                return $data;
    }
}