<?php declare(strict_types=1);

use app\controller\ProdutoController;
use app\helpers\Request;
use app\helpers\Uri;
use app\routes\Router;

require_once __DIR__. '/../vendor/autoload.php';

header('Content-Type: application/json; charset=utf-8'); // configurando a página para retornar json

Router::execute();

