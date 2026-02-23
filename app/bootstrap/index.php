<?php declare(strict_types=1);

use app\controller\AuthController;
use app\controller\ProdutoController;
use app\model\AuthModel;
use app\config\Database;
use app\model\ProdutoModel;

require_once __DIR__ . '/../../config/env.php';

//injeção de dependencias
$db = Database::Conexao();
$Authmodel = new AuthModel($db);
$AuthController = new AuthController($Authmodel);

$produtoModel = new ProdutoModel($db);
$produtoController = new ProdutoController($produtoModel);