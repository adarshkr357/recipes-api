<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use App\Service\RecipeService;

class RecipeServiceTest extends TestCase
{
    private $service;

    protected function setUp(): void
    {
        $this->service = new RecipeService();
    }

    public function testListRecipesReturnsArray()
    {
        $recipes = $this->service->listRecipes();
        $this->assertIsArray($recipes);
    }

    public function testCreateAndGetRecipe()
    {
        // Dummy recipe data.
        $data = [
            'name' => 'Test Recipe',
            'category' => 'Test Category',
            'area' => 'Test Area',
            'instructions' => 'Test instructions',
            'image' => 'http://example.com/image.jpg'
        ];
        $created = $this->service->createRecipe($data);
        $this->assertArrayHasKey('id', $created);

        $id = $created['id'];
        $retrieved = $this->service->getRecipe($id);
        $this->assertEquals($created['name'], $retrieved['name']);

        // Clean up
        $deleted = $this->service->deleteRecipe($id);
        $this->assertTrue($deleted);
    }

    public function testRatingRecipe()
    {
        // First, create a recipe to rate.
        $data = [
            'name' => 'Rating Test Recipe',
            'category' => 'Test',
            'area' => 'Test',
            'instructions' => 'Instructions',
            'image' => 'http://example.com/test.jpg'
        ];
        $created = $this->service->createRecipe($data);
        $this->assertNotNull($created);

        $id = $created['id'];
        // Rate the recipe twice.
        $result1 = $this->service->rateRecipe($id, 4);
        $result2 = $this->service->rateRecipe($id, 5);
        $this->assertEquals(2, $result2['ratings_count']);
        $this->assertGreaterThanOrEqual(4, $result2['average_rating']);

        // Clean up
        $this->service->deleteRecipe($id);
    }
}
