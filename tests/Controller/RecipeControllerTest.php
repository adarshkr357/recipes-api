<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use App\Controller\RecipeController;
use App\Utils\ResponseHelper;

class RecipeControllerTest extends TestCase
{
    public function testApiInfo()
    {
        $controller = new RecipeController();
        $request = Request::create('/');
        $response = $controller->apiInfo($request);

        $this->assertEquals(200, $response->getStatusCode());
        $data = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('routes', $data);
    }

    public function testListRecipes()
    {
        $controller = new RecipeController();
        $request = Request::create('/recipes');
        $response = $controller->listRecipes($request);

        $this->assertEquals(200, $response->getStatusCode());
        $data = json_decode($response->getContent(), true);
        $this->assertIsArray($data);
    }

    public function testGetRecipeNotFound()
    {
        $controller = new RecipeController();
        $request = Request::create('/recipes/99999');
        $response = $controller->getRecipe($request, 99999);

        $this->assertEquals(404, $response->getStatusCode());
        $data = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('error', $data);
    }

    public function testRateRecipeInvalidRating()
    {
        $controller = new RecipeController();
        $request = Request::create(
            '/recipes/1/rating',
            'POST',
            [],
            [],
            [],
            [],
            json_encode(['rating' => 10])
        );
        $response = $controller->rateRecipe($request, 1);
        $this->assertEquals(400, $response->getStatusCode());
        $data = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('error', $data);
    }
}
