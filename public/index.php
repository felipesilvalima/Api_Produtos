<?php declare(strict_types=1);

use app\helpers\Request;
use app\helpers\Uri;
use app\routes\Router;

require_once __DIR__. '/../vendor/autoload.php';

var_dump(Uri::get('path'));
var_dump(Request::get());
var_dump(Router::Routes());
var_dump(Router::load('ProdutoController', 'exibirProdutos'));
