<?php declare(strict_types=1); 

namespace app\model;

use Exception;
use PDO;
use PDOException;
class ProdutoModel
{
    
    public function exibirTodosProdutos()
    {
        try 
        {
            $sql = "SELECT * FROM Produtos ORDER BY id"; // SQL para listar todos os produtos
            $stm = Conexao::Conexao()->prepare($sql);  // Prepara a query
            $stm->execute();                            // Executa a query

            $listarProdutos = $stm->fetchAll(PDO::FETCH_OBJ); // Pega todos os resultados como objetos
            return $listarProdutos;                             // Retorna o array de objetos
        
        } 
            catch (PDOException $e) 
            {
                throw new Exception("error" . $e->getMessage()); // Lança exceção em caso de erro
            }
    }

    public function exibirProdutosId(int $id)
    {
        try 
        {
            $sql = "SELECT * FROM Produtos WHERE id = :id"; // SQL para listar todos os produtos
            $stm = Conexao::Conexao()->prepare($sql);  // Prepara a query
            $stm->bindParam(':id', $id, PDO::PARAM_INT); // passando o parâmetro
            $stm->execute(); // Executa a query

            $listarProdutos = $stm->fetch(PDO::FETCH_OBJ); // Pega o resultados como objetos
            return $listarProdutos;  // Retorna o array de objeto
        
        } 
            catch (PDOException $e) 
            {
                throw new Exception("error" . $e->getMessage()); // Lança exceção em caso de erro
            }
    }


}