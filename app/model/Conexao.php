<?php declare(strict_types=1); 

namespace app\model;

use Exception;
use PDO;
use PDOException;

class Conexao
{
    private static $conexao = null;

    public function Conexao()
    { 
        try 
        {
            if(!self::$conexao || self::$conexao == null)
            {
                $conexao = new PDO(
                    "mysql:host=" . $_ENV['DB_HOST'] . ";dbname=" . $_ENV['DB_NAME'],
                    $_ENV['DB_USER'],      // usuário do banco
                    $_ENV['DB_PASS']   // senha do banco
                ); // Cria a conexão PDO
    
                $conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Configura para lançar exceções
            }

            return $conexao; // Retorna a conexão
        } 
            catch (PDOException $e) 
            {
                throw new Exception("error na Conexão " . $e->getMessage()); // Lança exceção em caso de erro
            }
    }

}