<?php declare(strict_types=1); 

namespace app\validation;

class ValidationProduto
{
    public static function validation(array $produto)
    {
        if(
            empty($produto['produto']) ||
            empty($produto['preco']) ||
            empty($produto['quantidade']) ||
            empty($produto['quantidade_min']) ||
            empty($produto['descricao']) ||
            empty($produto['unidade_medida']) ||
            empty($produto['categoria_id']) ||
            empty($produto['fornecedor_id']) ||
            empty($produto['usuario_id'])
        )
        {
            return false;
        }

        return true; 
    } 
}