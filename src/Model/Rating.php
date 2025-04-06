<?php

namespace App\Model;

class Rating
{
    public $id;
    public $recipeId;
    public $rating;
    public $createdAt;

    public function __construct(array $data)
    {
        $this->id       = $data['id'] ?? null;
        $this->recipeId = $data['recipe_id'] ?? null;
        $this->rating   = $data['rating'] ?? null;
        $this->createdAt = $data['created_at'] ?? null;
    }

    public function toArray(): array
    {
        return [
            'id'       => $this->id,
            'recipeId' => $this->recipeId,
            'rating'   => $this->rating,
            'createdAt' => $this->createdAt,
        ];
    }
}
