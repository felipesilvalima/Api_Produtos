<?php declare(strict_types=1); 

namespace app\validation;

use app\model\ProdutoModel;

class ProdutoValidation
{

    public static function validationAllData(array $produto)
    {
        $messages = [];
        
            $messages[] = self::Produto($produto['produto']); // recebendo o retorno das menssagens
            $messages[] = self::preco($produto['preco']);
            $messages[] = self::quantidade($produto['quantidade']);
            $messages[] = self::quantidade_min($produto);
            $messages[] = self::categoria_id($produto['categoria_id']);
            $messages[] = self::fornecedor_id($produto['fornecedor_id']);
            $messages[] = self::descricao($produto['descricao']);
            $messages[] = self::unidade_medida($produto['unidade_medida']);

            $responses = [];

            for($i = 0; $i < count($messages); $i++)
            {
                if(!empty($messages[$i])) // se menssagens não for vázio
                {
                    $responses[] = $messages[$i]; // responses vai receber todas as menssagens
                }
            }

            if(!empty($responses)) // se as resposta não for vázia, retornar resposta, se não retornar null
            {
                return $responses;
            }
                else
                {
                    return null;
                }

        
    }



    public static function Produto($produto)
    {

       if(empty($produto)) // validação de presença
       {
          $messages['produto'] = "O campo Produto precisar ser preenchido";
       }
            elseif(strlen($produto) > 30) // validação de tamanho
            {
                $messages['produto'] = "O campo produto deve ter até 30 caracteres";
            }
                elseif(!ProdutoModel::isExistProduto($produto)) // validação de Existência
                {
                    $messages['produto'] = "O nome do Produto já existe";
                }
        
                    return !empty($messages) && isset($messages) ? $messages: null; // se não for vazio e existir retornar menssagem se não null
    }

    public static function preco($preco)
    {

        if(empty($preco)) // validação de presença
        {
            $messages['preco'] = "O campo Preço precisar ser preenchido";
        }
            elseif($preco <= 0) // validação de intervalo
            {
                $messages['preco'] = "O campo Preço tem que ser maior que 0"; 
            }
                elseif(!preg_match('/^\d{1,3}(?:\.\d{3})*,\d{2}$/', $preco)) // validação de tipo e formato
                {   
                    $messages['preco'] = "O campo Preço tem que ser tipo númerico no formato EX:(00,00, 0,00, 0.000,00)";
                }

                    return !empty($messages) && isset($messages) ? $messages: null; // se não for vazio e existir retornar menssagem se não null
    }

    public static function quantidade($quantidade)
    {

        if(empty($quantidade)) // validação de presença
        {
             $messages['quantidade'] = "O campo Quantidade precisar ser preenchido";
        }
            elseif(!is_numeric($quantidade)) // validação de tipo
            {
                $messages['quantidade'] = "O campo Quantidade precisar ser do tipo númerico";
            }
                elseif($quantidade <= 0) // validação de intervalo
                {
                  $messages['quantidade'] = "O campo Quantidade tem que ser maior que 0"; 
                }

                    return !empty($messages) && isset($messages) ? $messages: null; // se não for vazio e existir retornar menssagem se não null

    }

    public static function quantidade_min(array $produto)
    {

        $quantidade_min = $produto['quantidade_min'];
        $quantidade = $produto['quantidade'];
        
        if(empty($quantidade_min)) // validação de presença
        {
            $messages['quantidade_minima'] = "O campo Quantidade Minima precisar ser preenchido";
        }
            elseif(!is_numeric($quantidade_min)) // validação de tipo
            {
                $messages['quantidade_minima'] = "O campo Quantidade minima precisar ser do tipo númerico";
            }
                elseif($quantidade_min >=  $quantidade) // validação de lógica
                {
                  $messages['quantidade_minima'] = "O campo Quantidade minima tem que ser menor que quantidade"; 
                }
                    elseif($quantidade_min <= 0) // validação de intervalo
                    {
                        $messages['quantidade_minima'] = "O campo Quantidade minima tem que ser maior que 0"; 
                    }

                        return !empty($messages) && isset($messages) ? $messages: null; // se não for vazio e existir retornar menssagem se não null 

    }

    public static function descricao($descricao)
    {

        if(strlen($descricao) > 100)
        {
            $messages['descricao'] = "O campo descricação deve ter até 100 caracteres"; // validação de tamanho 
        }
        
            return !empty($messages) && isset($messages) ? $messages: null; // se não for vazio e existir retornar menssagem se não null 
    }

    public static function unidade_medida($unidade_medida)
    {
        if(!empty($unidade_medida) && !preg_match('/^[+-]?\d+(?:[.,]\d+)?\s?(?:mg|g|kg|lb|oz|ml|l|cl|dl|gal|fl\s?oz|mm|cm|m|km|in|ft|cm²|m²|ft²|s|min|h|d|w|kw|wh|kwh|v|a|mah|°c|°f|k|un|pct|cx|dz|par|x)$/i
', $unidade_medida)) // validação de tipo de formato
        {
            $messages['unidade_medida'] = "O campo unidade de médida tem que ser no formato Ex(2,3cm, 3m, 20kg, 2l etc..)";
        }

            return !empty($messages) && isset($messages) ? $messages: null; // se não for vazio e existir retornar menssagem se não null  
    }

    public static function categoria_id($categoria_id)
    {
        if(empty($categoria_id)) // validação de presença
        {
            $messages['categoria'] = "O campo Categoria precisar ser preenchido";
        }
             elseif(!is_numeric($categoria_id)) // validação de tipo
            {
                $messages['categoria'] = "O campo categoria precisar ser do tipo númerico";
            }
                    elseif($categoria_id <= 0) // validação de intervalo
                    {
                        $messages['categoria'] = "O campo Categoria tem que ser maior que 0"; 
                    }
                        elseif(!ProdutoModel::isExistCategoria((int)$categoria_id)) // validação de Existência
                        {
                            $messages['categoria'] = "O campo Categoria não existe"; 
                        }

                            return !empty($messages) && isset($messages) ? $messages: null; // se não for vazio e existir retornar menssagem se não null

    }

    public static function fornecedor_id($fornecedor_id)
    {
        
        if(empty($fornecedor_id)) // validação de presencia
        {
            $messages['fornecedor'] = "O campo Fornecedor precisar ser preenchido";
        }
             elseif(!is_numeric($fornecedor_id)) // validação de tipo
            {
                $messages['fornecedor'] = "O campo fornecedor precisar ser do tipo númerico";
            }
                    elseif($fornecedor_id <= 0) // validação de intervalo
                    {
                        $messages['fornecedor'] = "O campo Fornecedor tem que ser maior que 0"; 
                    }
                        elseif(!ProdutoModel::isExistFornecedor((int)$fornecedor_id)) // validação de Existência
                        {
                            $messages['fornecedor'] = "O campo Fornecedor não existe"; 
                        }

                            return !empty($messages) && isset($messages) ? $messages: null; // se não for vazio e existir retornar menssagem se não null
     
    }

  
}