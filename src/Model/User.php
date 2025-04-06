<?php

namespace App\Model;

class User
{
    public $id;
    public $username;
    public $password;
    public $createdAt;

    public function __construct(array $data)
    {
        $this->id        = $data['id'] ?? null;
        $this->username  = $data['username'] ?? '';
        $this->password  = $data['password'] ?? '';
        $this->createdAt = $data['created_at'] ?? null;
    }

    public function toArray(): array
    {
        return [
            'id'       => $this->id,
            'username' => $this->username,
            'created_at' => $this->createdAt,
        ];
    }
}
