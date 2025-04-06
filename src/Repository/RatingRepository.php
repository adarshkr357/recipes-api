<?php

namespace App\Repository;

use App\Utils\Database;
use PDO;

class RatingRepository
{
    protected $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function addRating($recipeId, $rating): void
    {
        $stmt = $this->db->prepare("INSERT INTO ratings (recipe_id, rating, created_at) VALUES (?, ?, NOW())");
        $stmt->execute([$recipeId, $rating]);
    }

    public function calculateAverageRating($recipeId): ?float
    {
        $stmt = $this->db->prepare("SELECT AVG(rating) AS avg_rating FROM ratings WHERE recipe_id = ?");
        $stmt->execute([$recipeId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result && $result['avg_rating'] ? round((float)$result['avg_rating'], 2) : null;
    }

    public function getRatingsCount($recipeId): int
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) AS count FROM ratings WHERE recipe_id = ?");
        $stmt->execute([$recipeId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? (int)$result['count'] : 0;
    }
}
