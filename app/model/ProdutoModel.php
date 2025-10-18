<?php declare(strict_types=1); 

namespace app\model;

use app\model\Conexao;
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
    
    private static $conexao;

    public function __construct()
    {
        self::$conexao = new Conexao();
    }

    public function exibirTodosProdutos()
    {
        try 
        {
            $sql = "SELECT * FROM Produtos ORDER BY id"; // SQL para listar todos os produtos
            $stm = self::$conexao->Conexao()->prepare($sql);  // Prepara a query
            $stm->execute();                            // Executa a query

            $listarProdutos = $stm->fetchAll(PDO::FETCH_OBJ); // Pega todos os resultados como objetos
            return $listarProdutos;                             // Retorna o array de objetos
        
        } 
            catch (PDOException $e) 
            {
               throw new Exception("error no banco de dados" . $e->getMessage()); // Lança exceção em caso de erro
            }
    }

    public function exibirProdutosId(int $id)
    {
        try 
        {
            $sql = "SELECT * FROM Produtos WHERE id = :id"; // SQL para listar todos os produtos
            $stm = self::$conexao->Conexao()->prepare($sql);  // Prepara a query
            $stm->bindParam(':id', $id, PDO::PARAM_INT); // passando o parâmetro
            $stm->execute(); // Executa a query

            $listarProdutos = $stm->fetch(PDO::FETCH_OBJ); // Pega o resultados como objetos
            return $listarProdutos;  // Retorna o array de objeto
        
        } 
            catch (PDOException $e) 
            {
                throw new Exception("error no banco de dados" . $e->getMessage()); // Lança exceção em caso de erro
            }
    }

    public function inserirProdutos(array $request)
    {
        try 
        {
            $this->produto = $request['produto'];
            $this->descricao = $request['descricao'];
            $this->preco = $request['preco'];
            $this->quantidade = $request['quantidade'];
            $this->quantidade_min = $request['quantidade_min'];
            $this->unidade_medida = $request['unidade_medida'];
            $this->categoria_id = $request['categoria_id'];
            $this->fornecedor_id = $request['fornecedor_id'];

            $sql = "INSERT INTO 
            produtos(produto,descricao,preco,quantidade_max,quantidade_min,unidade_medida,categoria_id,fornecedor_id,usuario_id)
            VALUES(:produto, :descricao, :preco, :quantidade, :quantidade_min, :unidade_medida, :categoria_id, :fornecedor_id, :usuario_id)"; // SQL para listar todos os produtos
            $stm = self::$conexao->Conexao()->prepare($sql);  // Prepara a query
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
               throw new Exception("error no banco de dados" . $e->getMessage()); // Lança exceção em caso de erro
            }
    }

    public function UpdateProdutos(array $request, int $id)
    {
         try 
        {
            $this->id = $id;
            $this->produto = $request['produto'];
            $this->descricao = $request['descricao'];
            $this->preco = $request['preco'];
            $this->quantidade = $request['quantidade'];
            $this->quantidade_min = $request['quantidade_min'];
            $this->unidade_medida = $request['unidade_medida'];
            $this->categoria_id = $request['categoria_id'];
            $this->fornecedor_id = $request['fornecedor_id'];

            $sql = "UPDATE produtos
            SET produto=:produto, descricao=:descricao, preco=:preco, quantidade_max=:quantidade, quantidade_min=:quantidade_min, unidade_medida=:unidade_medida, categoria_id=:categoria_id, fornecedor_id=:fornecedor_id, usuario_id=:usuario_id WHERE id = :id"; // SQL para listar todos os produtos
            $stm = self::$conexao->Conexao()->prepare($sql);  // Prepara a query
            $stm->bindParam(':id', $this->id, PDO::PARAM_INT); // passando o parâmetro
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
               throw new Exception("error no banco de dados" . $e->getMessage()); // Lança exceção em caso de erro
            }
    }


    public static function isExistProduto(string $produto)
    {
        try 
        {
            $sql = "SELECT * FROM Produtos WHERE produto = :produto"; // SQL para listar todos os produtos
            $stm = self::$conexao->Conexao()->prepare($sql);  // Prepara a query
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
                throw new Exception("error no banco de dados" . $e->getMessage()); // Lança exceção em caso de erro
            }
    }

    public static function isExistCategoria(int $categoria_id)
    {
        try
        {

            $sql = "SELECT id FROM categoria WHERE NOT EXISTS(SELECT id FROM categoria WHERE id = :categoria_id)";
    
            $stm = self::$conexao->Conexao()->prepare($sql);
            $stm->bindParam(':categoria_id', $categoria_id, PDO::PARAM_INT);
            $stm->execute();
    
            if($stm->rowCount() > 0)
            {
                return false;
            }
    
            return true;
        } 
            catch(PDOException $e)
            {
                throw new Exception("error no banco de dados" . $e->getMessage());
            }
        

    }
    
    public static function isExistFornecedor(int $fornecedor_id)
    {
        try
        {
            
            $sql = "SELECT id FROM fornecedor WHERE NOT EXISTS(SELECT id FROM fornecedor WHERE id = :fornecedor_id)";
            $stm = self::$conexao->Conexao()->prepare($sql);
            $stm->bindParam(':fornecedor_id', $fornecedor_id, PDO::PARAM_INT);
            $stm->execute();
            
                if($stm->rowCount() > 0)
                {
                    return false;
                }
                
                return true;
        }
            catch(PDOException $e)
            {
               throw new Exception("error no banco de dados" . $e->getMessage());
            }
    }
        
    public static function isExistID(int $id)
    {
        try
        {

            $sql = "SELECT id FROM produtos WHERE NOT EXISTS(SELECT id FROM produtos WHERE id = :id)";
    
            $stm = self::$conexao->Conexao()->prepare($sql);
            $stm->bindParam(':id', $id, PDO::PARAM_INT);
            $stm->execute();
    
            if($stm->rowCount() > 0)
            {
                return false;
            }
    
            return true;
        } 
            catch(PDOException $e)
            {
                throw new Exception("error no banco de dados" . $e->getMessage());
            }
        

    }
}
