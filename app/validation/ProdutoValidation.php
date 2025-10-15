<?php declare(strict_types=1); 

namespace app\validation;

class ProdutoValidation
{

    public static function validationAllData(array $produto)
    {
        $messages = [];
        
        if(
            !empty(self::Produto($produto['produto'])) ||
            !empty(self::preco($produto['preco'])) || 
            !empty(self::quantidade($produto['quantidade'])) || 
            !empty(self::quantidade_min($produto['quantidade_min'])) ||
            !empty(self::categoria_id($produto['categoria_id'])) ||
            !empty(self::fornecedor_id($produto['fornecedor_id']))  || 
            !empty(self::descricao($produto['descricao']))   
            )
        {
            $messages[] = self::Produto($produto['produto']);
            $messages[] = self::preco($produto['preco']);
            $messages[] = self::quantidade($produto['quantidade']);
            $messages[] = self::quantidade_min($produto['quantidade_min']);
            $messages[] = self::categoria_id($produto['categoria_id']);
            $messages[] = self::fornecedor_id($produto['fornecedor_id']);
            $messages[] = self::descricao($produto['descricao']);

            return $messages;
        }

        return null;
        
    }



    public static function Produto($produto)
    {
        $messages = [];

       if(empty($produto))
       {
          $messages[] = "Precisar prencheer o campo Produto!!";
       }
            elseif(strlen($produto) > 30)
            {
                $messages[] = "O campo produto deve ter até 30 caracteres";
            }
        
       return $messages;
    }

    public static function preco($preco)
    {
        $messages = [];

        if(empty($preco))
        {
            $messages[] = "Precisar prencheer o campo Preço!!";
        }
            elseif(!is_numeric($preco))
            {
                $messages[] = "O campo Preço precisar ser do tipo númerico";
            }

       return $messages;
    }

    public static function quantidade($quantidade)
    {
        $messages = [];

        if(empty($quantidade))
        {
             $messages[] = "Precisar prencheer o campo quantidade!!";
        }
            elseif(!is_numeric($quantidade))
            {
                $messages[] = "O campo Quantidade precisar ser do tipo númerico";
            }

       return $messages;

    }

    public static function quantidade_min($quantidade_min)
    {
        $messages = [];
        
        if(empty($quantidade_min))
        {
            $messages[] = "Precisar prencheer o campo quantidade minima!!";
        }
            elseif(!is_numeric($quantidade_min))
            {
                $messages[] = "O campo Quantidade minima precisar ser do tipo númerico";
            }

       return $messages;


    }

    public static function descricao($descricao)
    {
        $messages = [];

        if(strlen($descricao) > 100)
        {
            $messages[] = "O campo descricação deve ter até 100 caracteres";
        }
        
       return $messages;
    }

    public static function unidade_medida($unidade_medida)
    {

    }

    public static function categoria_id($categoria_id)
    {
        $messages = [];
        if(empty($categoria_id))
        {
            $messages[] = "Precisar prencheer o campo categoria!!";
        }
             elseif(!is_numeric($categoria_id))
            {
                $messages[] = "O campo categoria precisar ser do tipo númerico";
            }

       return $messages;

    }

    public static function fornecedor_id($fornecedor_id)
    {
        $messages = [];

        if(empty($fornecedor_id))
        {
            $messages[] = "Precisar prencheer o campo fornecedor!!";
        }
             elseif(!is_numeric($fornecedor_id))
            {
                $messages[] = "O campo fornecedor precisar ser do tipo númerico";
            }

       return $messages;
     
    }

  
}