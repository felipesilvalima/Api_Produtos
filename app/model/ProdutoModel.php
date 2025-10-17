<?php declare(strict_types=1); 

namespace app\model;

use Exception;
use PDO;
use PDOException;
class ProdutoModel
{

    private $id;
    private $produto;
    private $preco;
    private $quantidade;
    private $descricao;
    private $quantidade_min;
    private $unidade_medida;
    private $categoria_id;
    private $fornecedor_id;
    private $usuario_id;
    

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

    public function inserirProdutos(array $produto)
    {
        try 
        {
            $this->produto = $produto['produto'];
            $this->descricao = $produto['descricao'];
            $this->preco = $produto['preco'];
            $this->quantidade = $produto['quantidade'];
            $this->quantidade_min = $produto['quantidade_min'];
            $this->unidade_medida = $produto['unidade_medida'];
            $this->categoria_id = $produto['categoria_id'];
            $this->fornecedor_id = $produto['fornecedor_id'];

            $sql = "INSERT INTO 
            produtos(produto,descricao,preco,quantidade_max,quantidade_min,unidade_medida,categoria_id,fornecedor_id,usuario_id)
            VALUES(:produto, :descricao, :preco, :quantidade, :quantidade_min, :unidade_medida, :categoria_id, :fornecedor_id, :usuario_id)"; // SQL para listar todos os produtos
            $stm = Conexao::Conexao()->prepare($sql);  // Prepara a query
            $stm->bindParam(':produto', $this->produto, PDO::PARAM_STR); // passando o parâmetro
            $stm->bindParam(':descricao', $this->descricao, PDO::PARAM_STR); // passando o parâmetro
            $stm->bindParam(':preco', $this->preco, PDO::PARAM_STR); // passando o parâmetro
            $stm->bindParam(':quantidade', $this->quantidade, PDO::PARAM_INT); // passando o parâmetro
            $stm->bindParam(':quantidade_min', $this->quantidade_min, PDO::PARAM_INT); // passando o parâmetro
            $stm->bindParam(':unidade_medida', $this->unidade_medida, PDO::PARAM_STR); // passando o parâmetro
            $stm->bindParam(':categoria_id', $this->categoria_id, PDO::PARAM_INT); // passando o parâmetro
            $stm->bindParam(':fornecedor_id', $this->fornecedor_id, PDO::PARAM_INT); // passando o parâmetro
            $stm->bindValue(':usuario_id', 2, PDO::PARAM_INT); // passando o parâmetro
            $stm->execute(); // Executa a query

            if($stm)
            {
                return true;
            }

            return false;
        
        } 
            catch (PDOException $e) 
            {
                throw new Exception("error" . $e->getMessage()); // Lança exceção em caso de erro
            }
    }


    public static function isExistProduto(string $produto)
    {
        try 
        {
            $sql = "SELECT * FROM Produtos WHERE produto = :produto"; // SQL para listar todos os produtos
            $stm = Conexao::Conexao()->prepare($sql);  // Prepara a query
            $stm->bindParam(':produto', $produto, PDO::PARAM_STR); // passando o parâmetro
            $stm->execute(); // Executa a query

            if($stm->rowCount() > 0)
            {
                return false;
            }

            return true;
        
        } 
            catch (PDOException $e) 
            {
                throw new Exception("error" . $e->getMessage()); // Lança exceção em caso de erro
            }
    }

    public static function isExistCategoria(int $categoria_id)
    {
        $sql = "SELECT id FROM categoria WHERE NOT EXISTS(SELECT id FROM categoria WHERE id = :categoria_id)";

        $stm = Conexao::Conexao()->prepare($sql);
        $stm->bindParam(':categoria_id', $categoria_id, PDO::PARAM_INT);
        $stm->execute();

        if($stm->rowCount() > 0)
        {
            return false;
        }

        return true;

    }

    public static function isExistFornecedor(int $fornecedor_id)
    {
        $sql = "SELECT id FROM fornecedor WHERE NOT EXISTS(SELECT id FROM fornecedor WHERE id = :fornecedor_id)";
        $stm = Conexao::Conexao()->prepare($sql);
        $stm->bindParam(':fornecedor_id', $fornecedor_id, PDO::PARAM_INT);
        $stm->execute();

        if($stm->rowCount() > 0)
        {
            return false;
        }

        return true;
    }

}