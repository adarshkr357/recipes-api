<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Service\RecipeService;
use App\Utils\ResponseHelper;

class RecipeController
{
    private $service;

    public function __construct()
    {
        $this->service = new RecipeService();
    }

    // GET / - API Documentation (lists all available endpoints)
    public function apiInfo(Request $request): Response
    {
        $data = [
            'api_info' => 'This API provides endpoints for managing recipes, ratings, and user authentication.',
            'routes'   => [
                [
                    'method'      => 'GET',
                    'path'        => '/',
                    'description' => 'API documentation and list of available routes.'
                ],
                [
                    'method'      => 'GET',
                    'path'        => '/recipes',
                    'description' => 'List all recipes. Returns an array of recipes stored in the database.'
                ],
                [
                    'method'      => 'GET',
                    'path'        => '/recipes/{id}',
                    'description' => 'Retrieve a specific recipe by ID.'
                ],
                [
                    'method'      => 'GET',
                    'path'        => '/recipes/search?q=QUERY',
                    'description' => 'Search for recipes by name or keyword using the "q" query parameter.'
                ],
                [
                    'method'      => 'POST',
                    'path'        => '/recipes',
                    'description' => 'Create a new recipe (protected endpoint).'
                ],
                [
                    'method'      => 'PUT/PATCH',
                    'path'        => '/recipes/{id}',
                    'description' => 'Update an existing recipe (protected endpoint).'
                ],
                [
                    'method'      => 'DELETE',
                    'path'        => '/recipes/{id}',
                    'description' => 'Delete a recipe (protected endpoint).'
                ],
                [
                    'method'      => 'POST',
                    'path'        => '/recipes/{id}/rating',
                    'description' => 'Submit a recipe rating (value between 1-5).'
                ],
                [
                    'method'      => 'GET',
                    'path'        => '/auth/register?username=...&password=...',
                    'description' => 'Register a new user using query parameters. Requires "username" and "password".'
                ],
                [
                    'method'      => 'GET',
                    'path'        => '/auth/login?username=...&password=...',
                    'description' => 'Log in with provided credentials and receive a JWT token upon success.'
                ]
            ]
        ];
        return ResponseHelper::json($data);
    }

    // GET /recipes - List all recipes
    public function listRecipes(Request $request): Response
    {
        $recipes = $this->service->listRecipes();
        return ResponseHelper::json($recipes);
    }

    // GET /recipes/{id} - Get a recipe by ID
    public function getRecipe(Request $request, $id): Response
    {
        $recipe = $this->service->getRecipe($id);
        if (!$recipe) {
            return ResponseHelper::json(['error' => 'Recipe not found'], 404);
        }
        return ResponseHelper::json($recipe);
    }

    // POST /recipes - Create a new recipe (protected endpoint)
    public function createRecipe(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        $created = $this->service->createRecipe($data);
        if ($created) {
            return ResponseHelper::json($created, 201);
        }
        return ResponseHelper::json(['error' => 'Creation failed'], 500);
    }

    // PUT/PATCH /recipes/{id} - Update an existing recipe (protected endpoint)
    public function updateRecipe(Request $request, $id): Response
    {
        $data = json_decode($request->getContent(), true);
        $updated = $this->service->updateRecipe($id, $data);
        if ($updated) {
            return ResponseHelper::json($updated, 200);
        }
        return ResponseHelper::json(['error' => "Recipe {$id} not found or update failed"], 404);
    }

    // DELETE /recipes/{id} - Delete a recipe (protected endpoint)
    public function deleteRecipe(Request $request, $id): Response
    {
        $deleted = $this->service->deleteRecipe($id);
        if ($deleted) {
            return ResponseHelper::json(['message' => "Recipe {$id} deleted"], 200);
        }
        return ResponseHelper::json(['error' => "Recipe {$id} not found"], 404);
    }

    // GET /recipes/search?q=... - Search recipes by query string
    public function searchRecipes(Request $request): Response
    {
        $query = $request->query->get('q', '');
        $results = $this->service->searchRecipes($query);
        return ResponseHelper::json($results);
    }

    // POST /recipes/{id}/rating - Rate a recipe
    public function rateRecipe(Request $request, $id): Response
    {
        $data = json_decode($request->getContent(), true);
        if (!isset($data['rating']) || $data['rating'] < 1 || $data['rating'] > 5) {
            return ResponseHelper::json(['error' => 'Invalid rating value.'], 400);
        }
        $result = $this->service->rateRecipe($id, $data['rating']);
        return ResponseHelper::json($result);
    }
}
