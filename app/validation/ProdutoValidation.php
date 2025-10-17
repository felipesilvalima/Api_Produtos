<?php declare(strict_types=1); 

namespace app\validation;

use app\model\ProdutoModel;

class ProdutoValidation
{

    public static function validationAllData(array $produto)
    {
        $messages = [];
        
            $messages[] = self::Produto($produto['produto']);
            $messages[] = self::preco($produto['preco']);
            $messages[] = self::quantidade($produto['quantidade']);
            $messages[] = self::quantidade_min($produto);
            $messages[] = self::categoria_id($produto['categoria_id']);
            $messages[] = self::fornecedor_id($produto['fornecedor_id']);
            $messages[] = self::descricao($produto['descricao']);

            $responses = [];

            for($i = 0; $i < count($messages); $i++)
            {
                if(!empty($messages[$i]))
                {
                    $responses[] = $messages[$i];
                }
            }

            if(!empty($responses))
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

       if(empty($produto))
       {
          $messages = "O campo Produto precisar ser preenchido";
       }
            elseif(strlen($produto) > 30)
            {
                $messages = "O campo produto deve ter até 30 caracteres";
            }
                elseif(!ProdutoModel::isExistProduto($produto))
                {
                    $messages = "O campo produto já foi Cadastrado";
                }
        
       return !empty($messages) && isset($messages) ? $messages: null;
    }

    public static function preco($preco)
    {

        if(empty($preco))
        {
            $messages = "O campo Preço precisar ser preenchido";
        }
            elseif($preco <= 0)
            {
                $messages = "O campo Preço tem que ser maior que 0"; 
            }
                elseif(!preg_match('/^\d{1,3}(?:\.\d{3})*,\d{2}$/', $preco))
                {   
                    $messages = "O campo Preço tem que ser tipo númerico no formato EX:(00,00, 0,00, 0.000,00)";
                }

       return !empty($messages) && isset($messages) ? $messages: null;
    }

    public static function quantidade($quantidade)
    {

        if(empty($quantidade))
        {
             $messages = "O campo Quantidade precisar ser preenchido";
        }
            elseif(!is_numeric($quantidade))
            {
                $messages = "O campo Quantidade precisar ser do tipo númerico";
            }
                elseif($quantidade <= 0)
                {
                  $messages = "O campo Quantidade tem que ser maior que 0"; 
                }

      return !empty($messages) && isset($messages) ? $messages: null;

    }

    public static function quantidade_min(array $produto)
    {

        $quantidade_min = $produto['quantidade_min'];
        $quantidade = $produto['quantidade'];
        
        if(empty($quantidade_min))
        {
            $messages = "O campo Quantidade Minima precisar ser preenchido";
        }
            elseif(!is_numeric($quantidade_min))
            {
                $messages = "O campo Quantidade minima precisar ser do tipo númerico";
            }
                elseif($quantidade_min >=  $quantidade)
                {
                  $messages = "O campo Quantidade minima tem que ser menor que quantidade"; 
                }
                    elseif($quantidade_min <= 0)
                    {
                        $messages = "O campo Quantidade minima tem que ser maior que 0"; 
                    }

       return !empty($messages) && isset($messages) ? $messages: null;


    }

    public static function descricao($descricao)
    {

        if(strlen($descricao) > 100)
        {
            $messages = "O campo descricação deve ter até 100 caracteres";
        }
        
       return !empty($messages) && isset($messages) ? $messages: null;
    }

    public static function unidade_medida($unidade_medida)
    {

    }

    public static function categoria_id($categoria_id)
    {
        if(empty($categoria_id))
        {
            $messages = "O campo Categoria precisar ser preenchido";
        }
             elseif(!is_numeric($categoria_id))
            {
                $messages = "O campo categoria precisar ser do tipo númerico";
            }
                    elseif($categoria_id <= 0)
                    {
                        $messages = "O campo Categoria tem que ser maior que 0"; 
                    }
                        elseif(!ProdutoModel::isExistCategoria((int)$categoria_id))
                        {
                            $messages = "O campo Categoria não existe"; 
                        }

       return !empty($messages) && isset($messages) ? $messages: null;

    }

    public static function fornecedor_id($fornecedor_id)
    {
        
        if(empty($fornecedor_id))
        {
            $messages = "O campo Fornecedor precisar ser preenchido";
        }
             elseif(!is_numeric($fornecedor_id))
            {
                $messages = "O campo fornecedor precisar ser do tipo númerico";
            }
                    elseif($fornecedor_id <= 0)
                    {
                        $messages = "O campo Fornecedor tem que ser maior que 0"; 
                    }
                        elseif(!ProdutoModel::isExistFornecedor((int)$fornecedor_id))
                        {
                            $messages = "O campo Fornecedor não existe"; 
                        }

       return !empty($messages) && isset($messages) ? $messages: null;
     
    }

  
}