<?php declare(strict_types=1);

use app\controller\ProdutoController;

require_once __DIR__ . '/../app/controller/ProdutoController.php';

header('Content-Type: application/json; charset=utf-8');

$produtoController = new ProdutoController();

// URI completa
$uri = $_SERVER['REQUEST_URI'];

// Remove o prefixo da URL correspondente à pasta do projeto
$path = substr($uri, strlen('/estoque/public'));

    // Agora a rota
    if ($path === '/api/produtos' && $_SERVER['REQUEST_METHOD'] === 'GET') 
    {
        $produtoController->exibirProdutos();
    } 
        else 
        {
            http_response_code(404);
            echo json_encode(["erro" => "Rota não encontrada"]);
        }


