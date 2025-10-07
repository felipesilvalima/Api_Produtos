<?php declare(strict_types=1); 

namespace model\Conexao;

use Exception;
use PDO;
use PDOException;

class Conexao
{
    public static function Conexao()
    { 
        try 
        {
            $conexao = new PDO(
            "mysql: host=localhost; dbname=controler_de_estoque", 
            "root", 
            ""
            ); // Cria a conexão PDO

            $conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Configura para lançar exceções

            return $conexao; // Retorna a conexão
        } 
            catch (PDOException $e) 
            {
                throw new Exception("error" . $e->getMessage()); // Lança exceção em caso de erro
            }
}

}