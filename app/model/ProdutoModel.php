<?php declare(strict_types=1); 

namespace app\model;

use Exception;
use model\Conexao\Conexao;
use PDO;
use PDOException;

require_once __DIR__.'/Conexao.php';

class ProdutoModel
{
    
    public function exibirTodosProdutos()
    {
        try 
        {
            $sql = "SELECT * FROM Produtos ORDER BY id";
            $stm = Conexao::Conexao()->prepare($sql);
            $stm->execute();

                $listarProdutos = $stm->fetchAll(PDO::FETCH_OBJ);

                 return $listarProdutos;
                
        } 
            catch (PDOException $e) 
            {
                 throw new Exception("error". $e->getMessage());
            }
    }

   


}