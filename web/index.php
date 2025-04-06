<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use App\Route\Router;

// Load configuration, etc...
$config = require_once __DIR__ . '/../config/config.php';
$request = Request::createFromGlobals();

$router = new Router();

// Endpoints, etc.
$router->add('GET', '/', 'App\Controller\RecipeController::apiInfo');
$router->add('GET', '/recipes', 'App\Controller\RecipeController::listRecipes');
$router->add('GET', '/recipes/(\d+)', 'App\Controller\RecipeController::getRecipe');
$router->add('POST', '/recipes', 'App\Controller\RecipeController::createRecipe');
$router->add('PUT', '/recipes/(\d+)', 'App\Controller\RecipeController::updateRecipe');
$router->add('PATCH', '/recipes/(\d+)', 'App\Controller\RecipeController::updateRecipe');
$router->add('DELETE', '/recipes/(\d+)', 'App\Controller\RecipeController::deleteRecipe');
$router->add('GET', '/recipes/search', 'App\Controller\RecipeController::searchRecipes');
$router->add('POST', '/recipes/(\d+)/rating', 'App\Controller\RecipeController::rateRecipe');

// Special endpoints for jwt authentication
$router->add('GET', '/register', 'App\Controller\AuthController::register');
$router->add('GET', '/login', 'App\Controller\AuthController::login');

$response = $router->dispatch($request);
$response->send();
