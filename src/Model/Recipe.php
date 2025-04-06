<?php

namespace App\Model;

class Recipe
{
    public $id;
    public $name;
    public $category;
    public $area;
    public $instructions;
    public $image;

    public function __construct(array $data)
    {
        $this->id           = $data['id'] ?? null;
        $this->name         = $data['name'] ?? '';
        $this->category     = $data['category'] ?? '';
        $this->area         = $data['area'] ?? '';
        $this->instructions = $data['instructions'] ?? '';
        $this->image        = $data['image'] ?? '';
    }

    public function toArray(): array
    {
        return [
            'id'           => $this->id,
            'name'         => $this->name,
            'category'     => $this->category,
            'area'         => $this->area,
            'instructions' => $this->instructions,
            'image'        => $this->image,
        ];
    }
}
