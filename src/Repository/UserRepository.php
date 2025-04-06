<?php

namespace App\Repository;

use App\Model\User;
use App\Utils\Database;
use PDO;

class UserRepository
{
    protected $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function createUser(array $data): ?array
    {
        $hashedPassword = password_hash($data['password'], PASSWORD_BCRYPT);
        $stmt = $this->db->prepare(
            "INSERT INTO users (username, password) VALUES (?, ?) RETURNING id"
        );
        $stmt->execute([$data['username'], $hashedPassword]);
        $id = $stmt->fetchColumn();
        return $this->findById($id);
    }

    public function findById($id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $row : null;
    }

    public function findByUsername($username): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $row : null;
    }
}
