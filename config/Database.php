<?php declare(strict_types=1); 

namespace app\config;
use Exception;
use PDO;
use PDOException;

class Database
{
   private static $conexao = null;

    public static function Conexao()
    { 
        try 
        {
            if(!self::$conexao || self::$conexao === null)
            {
                self::$conexao = new PDO(
                    "mysql:host=" . getenv('DB_HOST'). ";dbname=" . getenv('DB_NAME'),
                    getenv('DB_USER'),      // usuário do banco
                    getenv('DB_PASS')  // senha do banco
                ); // Cria a conexão PDO
    
                self::$conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Configura para lançar exceções
            }

            return self::$conexao; // Retorna a conexão
        } 
            catch (PDOException $e) 
            {
                throw new Exception("error na Conexão " . $e->getMessage()); // Lança exceção em caso de erro
            }
    }

    public static function closeConexao()
    {
        if(self::$conexao && !self::$conexao == null)
        {
            self::$conexao = null; 
        }
    } 
}