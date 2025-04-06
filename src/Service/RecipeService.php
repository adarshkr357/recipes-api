<?php

namespace App\Service;

use App\Repository\RecipeRepository;
use App\Repository\RatingRepository;

class RecipeService
{
    private $recipeRepository;
    private $ratingRepository;

    public function __construct()
    {
        $this->recipeRepository = new RecipeRepository();
        // If there are no recipes in the DB, load from TheMealDB.
        if ($this->recipeRepository->count() === 0) {
            $this->recipeRepository->loadFromTheMealDB();
        }
        $this->ratingRepository = new RatingRepository();
    }

    public function listRecipes(): array
    {
        return $this->recipeRepository->findAll();
    }

    public function getRecipe($id): ?array
    {
        return $this->recipeRepository->findById($id);
    }

    public function searchRecipes($query): array
    {
        return $this->recipeRepository->search($query);
    }

    public function createRecipe(array $data): ?array
    {
        return $this->recipeRepository->create($data);
    }

    public function updateRecipe($id, array $data): ?array
    {
        return $this->recipeRepository->update($id, $data);
    }

    public function deleteRecipe($id): bool
    {
        return $this->recipeRepository->delete($id);
    }

    public function rateRecipe($recipeId, $rating): array
    {
        $this->ratingRepository->addRating($recipeId, $rating);
        $average = $this->ratingRepository->calculateAverageRating($recipeId);
        $count = $this->ratingRepository->getRatingsCount($recipeId);
        return [
            'message'         => "Recipe {$recipeId} rated successfully.",
            'average_rating'  => $average,
            'ratings_count'   => $count,
        ];
    }
}
