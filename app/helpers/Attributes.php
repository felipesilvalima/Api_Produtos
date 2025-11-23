<?php declare(strict_types=1); 

namespace app\helpers;

class Attributes
{
    public static function QueryFilter(string $atributos, string $atributos_categoria, string $atributos_fornecedor, string $filtro): string
    {

        $verifyRelacionamento = explode(",", $atributos);
        $filtro = explode(":", $filtro);
        $condicao = isset($filtro[0], $filtro[1]) ?  "WHERE " . $filtro[0]  . $filtro[1] : '';
        $condicao = $condicao === "WHERE " ? '' : $condicao;
        $valor = $filtro[2] ?? '';
        
                $categoria_id = in_array( "categoria_id",$verifyRelacionamento) ? 'categoria_id' : null;
                $fornecedor_id =   in_array( "fornecedor_id",$verifyRelacionamento) ? 'fornecedor_id' : null;
                
                $param_relacionamento =
                !empty($categoria_id) && !empty($fornecedor_id) 
                    ? "relacionamentoAll" 
                    : (empty($categoria_id) && !empty($fornecedor_id)
                            ? $fornecedor_id 
                            : (empty($fornecedor_id) && !empty($categoria_id)
                                    ?  $categoria_id
                                    : "produtos"));


                $verifyAtributos = explode(",", $atributos);

                        if(in_array("id", $verifyAtributos))
                        {
                            foreach($verifyAtributos as $p)
                            {
                                if($p === "id")
                                {
                                    $id_Produto =  ",P.". $p. " AS prod_id," ?? ''; 
                                }
                            }
                        }
                        
                        $atributos =  trim(preg_replace('/\bid,\b/i', '', $atributos ?? ''));
            
                if(!empty($param_relacionamento) && empty($atributos_categoria))
                {
                     
                    $sql = match($param_relacionamento) 
                    {
                        "categoria_id" => "SELECT P.$atributos $id_Produto C.categoria,C.descricao AS c_desc
                        FROM Produtos P INNER JOIN categoria C ON P.categoria_id = C.id $condicao '$valor'",
    
                        'fornecedor_id' => "SELECT P.$atributos $id_Produto
                            F.id AS for_id, F.fornecedor, F.cpf,F.telefone, F.endereco 
                            FROM Produtos P INNER JOIN fornecedor F ON P.fornecedor_id = F.id $condicao '$valor'",
    
                        'relacionamentoAll' => "SELECT P.$atributos $id_Produto
                                C.id AS c_id,C.categoria,C.descricao AS c_desc,
                                F.id AS for_id, F.fornecedor, F.cpf,F.telefone, F.endereco 
                                FROM Produtos P INNER JOIN categoria C ON P.categoria_id = C.id INNER JOIN fornecedor F ON P.fornecedor_id = F.id $condicao '$valor'",
    
                        'produtos' => "SELECT $atributos FROM Produtos $condicao '$valor'"
                    };
                   

                }
                    elseif(!empty($atributos) && !empty($atributos_categoria) || !empty($atributos_fornecedor))
                    {
                        $verifyCategoria = explode(",", $atributos_categoria);
                        $verifyFornecedor = explode(",", $atributos_fornecedor);

                        $id_Categoria = '';
                        $id_Fornecedor = '';
                        $descricao = '';

                        if(in_array("descricao", $verifyCategoria) || in_array("id", $verifyCategoria))
                        {
                            foreach ($verifyCategoria as $c) 
                            {
                               if($c === "descricao" )
                                {
                                   $descricao = ",C.". $c. " AS c_desc" ?? '';
                                }
                                    elseif($c === "id")
                                    {
                                        $id_Categoria = ",C.". $c. " AS c_id" ?? ''; 
                                    }
                            }
                           
                           
                           $atributos_categoria =  trim(preg_replace('/,descricao|,id/i', '', $atributos_categoria ?? ''));
                            
                        }

                        if(in_array("id", $verifyFornecedor))
                        {
                            foreach($verifyFornecedor as $f)
                            {
                                if($f === "id")
                                {
                                    $id_Fornecedor =  ",F.". $f. " AS for_id," ?? ''; 
                                }
                            }
                        }
                        
                        $atributos_fornecedor =  trim(preg_replace('/\bid,\b/i', '', $atributos_fornecedor ?? ''));

                        $atributos_fornecedor = ",F.". $atributos_fornecedor;
                        $atributos_fornecedor = $atributos_fornecedor === ",F." ? '' : $atributos_fornecedor;
                        
                        
                        $sql = "SELECT P.$atributos $id_Produto C.$atributos_categoria $descricao $id_Categoria $id_Fornecedor $atributos_fornecedor
                        FROM Produtos P 
                        INNER JOIN categoria C ON P.categoria_id = C.id
                        LEFT JOIN fornecedor F ON P.fornecedor_id = F.id
                        $condicao $valor";
  
                    }
                   
                        return $sql;
    }
}