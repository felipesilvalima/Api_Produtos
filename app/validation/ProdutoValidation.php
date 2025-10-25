<?php declare(strict_types=1); 

namespace app\validation;

use app\model\ProdutoModel;

class ProdutoValidation
{

    public static function validationAllData(array $request)
    {
        $messages = [];
          
            $messages["produto"] = self::Produto($request['produto'] ?? null, $request['id'] ?? 0); // recebendo o retorno das menssagens
            $messages["preco"] = self::preco($request['preco'] ?? 0);
            $messages["quantidade"] = self::quantidade($request['quantidade'] ?? 0);
            $messages["quantiade_minima"] = self::quantidade_min($request);
            $messages["categoria_id"] = self::categoria_id($request['categoria_id'] ?? 0);
            $messages["fornecedor_id"] = self::fornecedor_id($request['fornecedor_id'] ?? 0);
            $messages["descricao"] = self::descricao($request['descricao'] ?? null);
            $messages["unidade_medida"] = self::unidade_medida($request['unidade_medida'] ?? null);

            $responses = [];

            $responses = array_filter($messages, fn($v) => !is_null($v)); // return apenas arrays que não são nulos
            
            if(!empty($responses)) // se as resposta não for vázia, retornar resposta, se não retornar null
            {
                return $responses;
            }
                else
                {
                    return null;
                }

        
    }



    public static function Produto($produto, $id)
    {

       if(empty($produto)) // validação de presença
       {
          $messages[] = "O campo Produto precisar ser preenchido";
       }
            elseif(strlen($produto) > 30) // validação de tamanho
            {
                $messages[] = "O campo produto deve ter até 30 caracteres";
            }
                elseif(!ProdutoModel::isExistProduto($produto, (int)$id)) // validação de Existência
                {
                    $messages[] = "Esse produto já foi cadastrado";
                }
        
                    return !empty($messages) && isset($messages) ? $messages: null; // se não for vazio e existir retornar menssagem se não null
    }

    public static function preco($preco)
    {

        if(empty($preco)) // validação de presença
        {
            $messages[] = "O campo Preço precisar ser preenchido";
        }
            elseif($preco <= 0) // validação de intervalo
            {
                $messages[] = "O campo Preço tem que ser maior que 0"; 
            }
                elseif(!preg_match('/^\d{1,3}(?:\.\d{3})*,\d{2}$/', $preco)) // validação de tipo e formato
                {   
                    $messages[] = "O campo Preço tem que ser tipo númerico no formato EX:(00,00, 0,00, 0.000,00)";
                }

                    return !empty($messages) && isset($messages) ? $messages: null; // se não for vazio e existir retornar menssagem se não null
    }

    public static function quantidade($quantidade)
    {
        
        if(empty($quantidade)) // validação de presença
        {
             $messages[] = "O campo Quantidade precisar ser preenchido";
        }
            elseif(!is_numeric($quantidade)) // validação de tipo
            {
                $messages[] = "O campo Quantidade precisar ser do tipo númerico";
            }
                elseif($quantidade <= 0) // validação de intervalo
                {
                  $messages[] = "O campo Quantidade tem que ser maior que 0"; 
                }

                    return !empty($messages) && isset($messages) ? $messages: null; // se não for vazio e existir retornar menssagem se não null

    }

    public static function quantidade_min(array $produto)
    {

        $quantidade_min = $produto['quantidade_min'] ?? 0;
        $quantidade = $produto['quantidade'] ?? 0;
        
        if(empty($quantidade_min)) // validação de presença
        {
            $messages[] = "O campo Quantidade Minima precisar ser preenchido";
        }
            elseif(!is_numeric($quantidade_min)) // validação de tipo
            {
                $messages[] = "O campo Quantidade minima precisar ser do tipo númerico";
            }
                elseif($quantidade_min >=  $quantidade) // validação de lógica
                {
                  $messages[] = "O campo Quantidade minima tem que ser menor que quantidade"; 
                }
                    elseif($quantidade_min <= 0) // validação de intervalo
                    {
                        $messages[] = "O campo Quantidade minima tem que ser maior que 0"; 
                    }

                        return !empty($messages) && isset($messages) ? $messages: null; // se não for vazio e existir retornar menssagem se não null 

    }

    public static function descricao($descricao)
    {

        if(!empty($descricao) && strlen($descricao) > 100)
        {
            $messages[] = "O campo descricação deve ter até 100 caracteres"; // validação de tamanho 
        }
            elseif(!empty($descricao)  && is_numeric($descricao))
            {
                $messages[] = "A descrição tem que ser um texto"; // validação de tipo 
            }
        
            return !empty($messages) && isset($messages) ? $messages: null; // se não for vazio e existir retornar menssagem se não null 
    }

    public static function unidade_medida($unidade_medida)
    {
        if(!empty($unidade_medida) && !preg_match('/^[+-]?\d+(?:[.,]\d+)?\s?(?:mg|g|kg|lb|oz|ml|l|cl|dl|gal|fl\s?oz|mm|cm|m|km|in|ft|cm²|m²|ft²|s|min|h|d|w|kw|wh|kwh|v|a|mah|°c|°f|k|un|pct|cx|dz|par|x)$/i
', $unidade_medida)) // validação de tipo de formato
        {
            $messages[] = "O campo unidade de médida tem que ser no formato Ex(2,3cm, 3m, 20kg, 2l etc..)";
        }

            return !empty($messages) && isset($messages) ? $messages: null; // se não for vazio e existir retornar menssagem se não null  
    }

    public static function categoria_id($categoria_id)
    {
        if(empty($categoria_id)) // validação de presença
        {
            $messages[] = "O campo Categoria precisar ser preenchido";
        }
             elseif(!is_numeric($categoria_id)) // validação de tipo
            {
                $messages[] = "O campo categoria precisar ser do tipo númerico";
            }
                    elseif($categoria_id <= 0) // validação de intervalo
                    {
                        $messages[] = "O campo Categoria tem que ser maior que 0"; 
                    }
                        elseif(!ProdutoModel::isExistCategoria((int)$categoria_id)) // validação de Existência
                        {
                            $messages[] = "Essa Categoria não existe"; 
                        }

                            return !empty($messages) && isset($messages) ? $messages: null; // se não for vazio e existir retornar menssagem se não null

    }

    public static function fornecedor_id($fornecedor_id)
    {
        
        if(empty($fornecedor_id)) // validação de presencia
        {
            $messages[] = "O campo Fornecedor precisar ser preenchido";
        }
             elseif(!is_numeric($fornecedor_id)) // validação de tipo
            {
                $messages[] = "O campo fornecedor precisar ser do tipo númerico";
            }
                    elseif($fornecedor_id <= 0) // validação de intervalo
                    {
                        $messages[] = "O campo Fornecedor tem que ser maior que 0"; 
                    }
                        elseif(!ProdutoModel::isExistFornecedor((int)$fornecedor_id)) // validação de Existência
                        {
                            $messages[] = "Esse Fornecedor não existe"; 
                        }

                            return !empty($messages) && isset($messages) ? $messages: null; // se não for vazio e existir retornar menssagem se não null
     
    }

  
}