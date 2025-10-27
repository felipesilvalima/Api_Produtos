<?php declare(strict_types=1); 

namespace app\model;

use app\model\Conexao;
use ErrorException;
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
//git commit -m "retornando o relacionamento entre as tabelas"
            $sql = "SELECT P.*, P.id AS p_id, C.*, C.id AS c_id,  C.descricao AS c_desc, F.*, F.id As f_id  
            FROM Produtos AS P INNER JOIN categoria AS C ON P.categoria_id = C.id  INNER JOIN fornecedor AS F ON P.fornecedor_id = F.id"; // SQL para listar todos os produtos
            $stm = self::$conexao->Conexao()->prepare($sql);  // Prepara a query
            $stm->execute();                            // Executa a query

            $listarProdutos = $stm->fetchAll(PDO::FETCH_OBJ); // Pega todos os resultados como objetos

            if(!$listarProdutos) // se não existir a linha retorne array vazio
            {
                return [];
            }

            foreach ($listarProdutos as $line) 
            {
                $datas[] = [
                    'id' => $line->p_id,
                    'categoria_id' => $line->categoria_id,
                    'fornecedor_id' => $line->fornecedor_id,
                    'produto' => $line->produto,
                    'preco' => $line->preco,
                    'quantidade' => $line->quantidade_max,
                    'quantidade_min' => $line->quantidade_min,
                    'descricao' => $line->descricao,
                    'unidade_medida' => $line->unidade_medida,
                    'categoria' => [
                        'id' => $line->c_id,
                        'categoria' => $line->categoria,
                        'descricao' => $line->c_desc,
                    ],
                    'fornecedor' => [
                        'id' => $line->f_id,
                        'fornecedor' => $line->fornecedor,
                        'cpf' => $line->cpf,
                        'telefone' => $line->telefone,
                        'endereco' => $line->endereco,
                    ]
                 ];
            }

            return $datas; // Retorna o array de objetos
        
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
            $sql = "SELECT P.*, P.id AS p_id, C.*, C.id AS c_id,  C.descricao AS c_desc, F.*, F.id As f_id  
            FROM Produtos AS P INNER JOIN categoria AS C ON P.categoria_id = C.id  INNER JOIN fornecedor AS F ON P.fornecedor_id = F.id WHERE P.id = :id"; // SQL para listar todos os produtos
            $stm = self::$conexao->Conexao()->prepare($sql);  // Prepara a query
            $stm->bindParam(':id', $id, PDO::PARAM_INT); // passando o parâmetro
            $stm->execute(); // Executa a query

            $line = $stm->fetch(PDO::FETCH_OBJ); // Pega o resultados como objetos

            if(!$line) // se não existir a linha retorne array vazio
            {
                return [];
            }

                $data = [
                    'id' => $line->p_id,
                    'categoria_id' => $line->categoria_id,
                    'fornecedor_id' => $line->fornecedor_id,
                    'produto' => $line->produto,
                    'preco' => $line->preco,
                    'quantidade' => $line->quantidade_max,
                    'quantidade_min' => $line->quantidade_min,
                    'descricao' => $line->descricao,
                    'unidade_medida' => $line->unidade_medida,
                    'categoria' => [
                        'id' => $line->c_id,
                        'categoria' => $line->categoria,
                        'descricao' => $line->c_desc,
                    ],
                    'fornecedor' => [
                        'id' => $line->f_id,
                        'fornecedor' => $line->fornecedor,
                        'cpf' => $line->cpf,
                        'telefone' => $line->telefone,
                        'endereco' => $line->endereco,
                    ]
                 ];
            
            
            return $data;  // Retorna o array de objeto
        
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
            $this->produto = $request['produto'] ?? null;
            $this->descricao = $request['descricao'] ?? null;
            $this->preco = $request['preco'] ?? 0;
            $this->quantidade = $request['quantidade'] ?? 0;
            $this->quantidade_min = $request['quantidade_min'] ?? 0;
            $this->unidade_medida = $request['unidade_medida'] ?? null;
            $this->categoria_id = $request['categoria_id'] ?? 0;
            $this->fornecedor_id = $request['fornecedor_id'] ?? 0;

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
            $this->produto = $request['produto'] ?? null;
            $this->descricao = $request['descricao'] ?? null;
            $this->preco = $request['preco'] ?? 0.00;
            $this->quantidade = $request['quantidade'] ?? 0;
            $this->quantidade_min = $request['quantidade_min'] ?? 0;
            $this->unidade_medida = $request['unidade_medida'] ?? null;
            $this->categoria_id = $request['categoria_id'] ?? 0;
            $this->fornecedor_id = $request['fornecedor_id'] ?? 0;

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

     public function UpdateParcialProdutos(array $request, int $id)
    {
         try 
        {
            $values = $this->exibirProdutosId($id); // pegar os dados do id


            $this->id = $id;
            $this->produto = isset($request['produto']) && !empty($request['produto']) ? $request['produto'] : $values['produto'];
            $this->descricao = isset($request['descricao']) && !empty($request['descricao']) ? $request['descricao'] : $values['descricao'];
            $this->preco =  isset($request['preco']) && !empty($request['preco']) ? $request['preco'] : $values['preco'];
            $this->quantidade = isset($request['quantidade']) && !empty($request['quantidade']) ? $request['quantidade'] : $values['quantidade'];
            $this->quantidade_min = isset($request['quantidade_min']) && !empty($request['quantidade_min']) ? $request['quantidade_min'] : $values['quantidade_min'];
            $this->unidade_medida =  isset($request['unidade_medida']) && !empty($request['unidade_medida']) ? $request['unidade_medida'] : $values['unidade_medida'];
            $this->categoria_id =  isset($request['categoria_id']) && !empty($request['categoria_id']) ? $request['categoria_id'] : $values['categoria_id'];
            $this->fornecedor_id = isset($request['categofornecedor_idria_id']) && !empty($request['fornecedor_id']) ? $request['fornecedor_id'] : $values['fornecedor_id'];

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

    public function deleteProduto(int $id)
    {
        try
        {

            $sql = "DELETE FROM produtos WHERE id = :id"; // SQL para remover uma listar de produto
            $stm = self::$conexao->Conexao()->prepare($sql); // Prepara a query
            $stm->bindParam(':id', $id, PDO::PARAM_INT); // passando o parâmetro
            $stm->execute(); // Executa a query
    
            if($stm)
            {
                return true;
            }
    
            return false;
        }
            catch(PDOException $e)
            {
                throw new Exception("error no banco de dados" . $e->getMessage());  // Lança exceção em caso de erro
            }
    }

    public static function isExistProduto(string $produto ,int $id)
    {
        try 
        {
            $sql = "SELECT produto FROM produtos WHERE produto = :produto AND id != :id"; // SQL para verificar produto
            $stm = self::$conexao->Conexao()->prepare($sql);  // Prepara a query
            $stm->bindParam(':produto', $produto, PDO::PARAM_STR); // passando o parâmetro
            $stm->bindParam(':id', $id, PDO::PARAM_INT); // passando o parâmetro
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
            $sql = "SELECT id FROM categoria WHERE EXISTS(SELECT id FROM categoria WHERE id = :categoria_id)";
            $stm = self::$conexao->Conexao()->prepare($sql);
            $stm->bindParam(':categoria_id', $categoria_id, PDO::PARAM_INT);
            $stm->execute();

            $verificarCategoria = $stm->fetch(PDO::FETCH_OBJ);
    
            if(empty($verificarCategoria))
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
 
            $sql = "SELECT id FROM fornecedor WHERE EXISTS(SELECT id FROM fornecedor WHERE id = :fornecedor_id)";
            $stm = self::$conexao->Conexao()->prepare($sql);
            $stm->bindParam(':fornecedor_id', $fornecedor_id, PDO::PARAM_INT);
            $stm->execute();
            
            $verificarFornecedor = $stm->fetch(PDO::FETCH_OBJ);

                if(empty($verificarFornecedor))
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

            $sql = "SELECT id FROM produtos WHERE EXISTS(SELECT id FROM produtos WHERE id = :id)";
    
            $stm = self::$conexao->Conexao()->prepare($sql);
            $stm->bindParam(':id', $id, PDO::PARAM_INT);
            $stm->execute();

            $verificarID = $stm->fetch(PDO::FETCH_OBJ);
            
            if(empty($verificarID))
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
