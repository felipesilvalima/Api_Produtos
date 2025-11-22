<?php declare(strict_types=1); 

namespace app\helpers;

class Attributes
{
    public static function QueryFilter(string $atributos): string
    {
        $verifyRelacionamento = explode(",", $atributos);

                $categoria_id = array_filter($verifyRelacionamento, fn($value) =>  $value == "categoria_id");
                $fornecedor_id = array_filter($verifyRelacionamento, fn($value) =>  $value == "fornecedor_id");
                $param_relacionamento =
                !empty($categoria_id) && !empty($fornecedor_id) 
                    ? "relacionamentoAll" 
                    : (empty($categoria_id) && !empty($fornecedor_id)
                            ? 'fornecedor_id' 
                            : (empty($fornecedor_id) && !empty($categoria_id)
                                    ? 'categoria_id' 
                                    : null));
            
                
                $sql = match($param_relacionamento) 
                {
                    "categoria_id" => "SELECT $atributos,
                    C.id AS c_id,C.categoria,C.descricao AS c_desc
                    FROM Produtos P INNER JOIN categoria C ON P.categoria_id = C.id",

                    'fornecedor_id' => "SELECT $atributos,
                        F.id AS for_id, F.fornecedor, F.cpf,F.telefone, F.endereco 
                        FROM Produtos P INNER JOIN fornecedor F ON P.fornecedor_id = F.id",

                    'relacionamentoAll' => "SELECT $atributos,
                            C.id AS c_id,C.categoria,C.descricao AS c_desc,
                            F.id AS for_id, F.fornecedor, F.cpf,F.telefone, F.endereco 
                            FROM Produtos P INNER JOIN categoria C ON P.categoria_id = C.id INNER JOIN fornecedor F ON P.fornecedor_id = F.id",

                    default => "SELECT $atributos FROM Produtos"
                };

                return $sql;
    }
}