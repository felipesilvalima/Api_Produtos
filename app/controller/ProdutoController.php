<?php declare(strict_types=1); 

namespace app\controller;

use app\model\ProdutoModel;

require_once __DIR__.'/../model/ProdutoModel.php';

class ProdutoController
{
    public function exibirProdutos()
    {
        header('Content-Type: application/json; charset=utf-8');
        
        $Produtos = new ProdutoModel();

        $listaProdutos = $Produtos->exibirTodosProdutos();
       
        if(empty($listaProdutos))
        {
            http_response_code(404);
           echo json_encode(["mensagem" => "Nenhum produto encontrado!!"]);
        }
            else
            {
                 http_response_code(200);
                echo json_encode($listaProdutos);
            }         
    }

}