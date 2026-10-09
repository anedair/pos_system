<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductModel extends Model
{
    protected $table = 'products';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = [
        'name',
        'description',
        'price',
        'stock',
        'image',
        'is_active',
    ];

    protected $validationRules = [
        'name' => 'required|min_length[2]|max_length[150]',
        'price' => 'required|decimal|greater_than_equal_to[0]',
        'stock' => 'required|is_natural',
    ];

    protected $validationMessages = [
        'name' => [
            'required' => 'Product name is required.',
        ],
        'price' => [
            'required' => 'Product price is required.',
        ],
        'stock' => [
            'required' => 'Product stock is required.',
        ],
    ];

    public function getActiveProducts()
    {
        return $this->where('is_active', 1)->findAll();
    }
}
