<?php declare(strict_types=1);

use app\controller\ProdutoController;

require_once __DIR__ . '/../app/controller/ProdutoController.php';

header('Content-Type: application/json; charset=utf-8');

$produtoController = new ProdutoController();

// Pega a URI completa
$uri = $_SERVER['REQUEST_URI'];

// Remove prefixo da pasta do projeto (ex: /estoque/public)
$path = substr($uri, strlen('/estoque/public'));

// Verifica a rota e método
    if ($path === '/api/produtos' && $_SERVER['REQUEST_METHOD'] === 'GET') 
    {
        $produtoController->exibirProdutos(); // Chama método da API
    } 
        else 
        {
            http_response_code(404); // Rota não encontrada
            echo json_encode(["erro" => "Rota não encontrada"]);
        }


