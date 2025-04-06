<?php

namespace App\Repository;

use App\Model\Recipe;
use App\Utils\Database;
use PDO;
use GuzzleHttp\Client;

class RecipeRepository
{
    protected $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function count(): int
    {
        $stmt = $this->db->query("SELECT COUNT(*) FROM recipes");
        return (int)$stmt->fetchColumn();
    }

    public function loadFromTheMealDB(): void
    {
        $client = new Client([
            'base_uri' => 'https://www.themealdb.com/api/json/v1/1/',
            'timeout'  => 10.0,
        ]);
        // The API returns all meals when search is empty.
        $response = $client->get('search.php', [
            'query' => ['s' => '']
        ]);
        $data = json_decode($response->getBody(), true);
        if (!empty($data['meals'])) {
            foreach ($data['meals'] as $meal) {
                $recipeData = [
                    'name'         => $meal['strMeal'] ?? null,
                    'category'     => $meal['strCategory'] ?? null,
                    'area'         => $meal['strArea'] ?? null,
                    'instructions' => $meal['strInstructions'] ?? null,
                    'image'        => $meal['strMealThumb'] ?? null,
                ];
                // Insert each recipe into the DB.
                $this->create($recipeData);
            }
        }
    }

    public function findAll(): array
    {
        $stmt = $this->db->query("SELECT * FROM recipes ORDER BY id");
        $results = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $results[] = $row;
        }
        return $results;
    }

    public function findById($id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM recipes WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $row : null;
    }

    public function search($query): array
    {
        $stmt = $this->db->prepare("SELECT * FROM recipes WHERE name ILIKE ? ORDER BY id");
        $stmt->execute(["%{$query}%"]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create(array $data): array
    {
        $stmt = $this->db->prepare(
            "INSERT INTO recipes (name, category, area, instructions, image) VALUES (?, ?, ?, ?, ?) RETURNING id"
        );
        $stmt->execute([
            $data['name'] ?? null,
            $data['category'] ?? null,
            $data['area'] ?? null,
            $data['instructions'] ?? null,
            $data['image'] ?? null
        ]);
        $id = $stmt->fetchColumn();
        return $this->findById($id);
    }

    public function update($id, array $data): ?array
    {
        $stmt = $this->db->prepare(
            "UPDATE recipes SET name = ?, category = ?, area = ?, instructions = ?, image = ? WHERE id = ?"
        );
        $stmt->execute([
            $data['name'] ?? null,
            $data['category'] ?? null,
            $data['area'] ?? null,
            $data['instructions'] ?? null,
            $data['image'] ?? null,
            $id
        ]);
        return $this->findById($id);
    }

    public function delete($id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM recipes WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
