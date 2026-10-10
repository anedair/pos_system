<?php

namespace App\Models;

use CodeIgniter\Model;

class SaleModel extends Model
{
    protected $table = 'sales';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = [
        'product_id',
        'customer_id',
        'user_id',
        'quantity',
        'unit_price',
        'created_at',
    ];
}
