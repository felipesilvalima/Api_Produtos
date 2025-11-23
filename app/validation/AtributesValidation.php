<?php declare(strict_types=1); 

namespace app\validation;

class AtributesValidation
{
    public static function AttributesValidation(string $atributos, string $atributos_categoria, string $atributos_fornecedor)
    {
        $atributos = explode(",", $atributos);
        $atributos_categoria = explode(",", $atributos_categoria);
        $atributos_fornecedor = explode(",", $atributos_fornecedor);

        $produto_atributos = ["id","produto", "descricao", "preco", "quantidade_max","quantidade_min","unidade_medida","categoria_id","fornecedor_id","usuario_id"];
        $categoria_atributos = ["id", "descricao", "categoria"];
        $fornecedor_atributos = ["id", "fornecedor", "cpf", "endereco","telefone"];

            $response["produto"] =  self::ResponseAttributes($produto_atributos,$atributos,"produto");
            $response["categoria"] =  self::ResponseAttributes($categoria_atributos,$atributos_categoria,"categoria");
            $response["fornecedor"] =  self::ResponseAttributes($fornecedor_atributos,$atributos_fornecedor,"fornecedor");
            $response = array_filter($response, fn($r) => !is_null($r));
            
                return $response;

    }


    protected static function ResponseAttributes(array $atributos, array $atributos_param, string $tabela)
    {
        $response = null;
        $verify_atributo = array_diff($atributos_param, $atributos);
        $verify_empty = in_array("", $verify_atributo);
        
           if(!$verify_empty && !empty($verify_atributo))
            {
               
                foreach($verify_atributo as $atributos)
                {
                    $response[] = "O atributo ". $atributos . " não existe na tabela {$tabela}";
                }
            }
             
            return !empty($response) && isset($response) ? $response : null;
        
    }
}