<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePosTables extends Migration
{
    public function up()
    {
        // =====================================
        // 1. USERS TABLE
        // =====================================
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'username' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'full_name' => [
                'type' => 'VARCHAR',
                'constraint' => 150,
            ],
            'password' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'avatar' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->addUniqueKey('username');
        $this->forge->createTable('users');

        // =====================================
        // 2. CUSTOMERS TABLE
        // =====================================
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'full_name' => [
                'type' => 'VARCHAR',
                'constraint' => 150,
            ],
            'email' => [
                'type' => 'VARCHAR',
                'constraint' => 150,
                'null' => true,
            ],
            'phone' => [
                'type' => 'VARCHAR',
                'constraint' => 30,
                'null' => true,
            ],
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('customers');

        // =====================================
        // 3. PRODUCTS TABLE
        // =====================================
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => 150,
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'price' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
            ],
            'stock' => [
                'type' => 'INT',
                'unsigned' => true,
                'default' => 0,
            ],
            'image' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'is_active' => [
                'type' => 'BOOLEAN',
                'default' => 1,
            ],
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('products');

        // =====================================
        // 4. SALES TABLE
        // =====================================
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'product_id' => [
                'type' => 'INT',
                'unsigned' => true,
            ],
            'customer_id' => [
                'type' => 'INT',
                'unsigned' => true,
            ],
            'user_id' => [
                'type' => 'INT',
                'unsigned' => true,
            ],
            'quantity' => [
                'type' => 'INT',
                'unsigned' => true,
            ],
            'unit_price' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addPrimaryKey('id');

        $this->forge->addForeignKey(
            'product_id',
            'products',
            'id'
        );

        $this->forge->addForeignKey(
            'customer_id',
            'customers',
            'id'
        );

        $this->forge->addForeignKey(
            'user_id',
            'users',
            'id'
        );

        $this->forge->createTable('sales');
    }

    public function down()
    {
        $this->forge->dropTable('sales', true);
        $this->forge->dropTable('products', true);
        $this->forge->dropTable('customers', true);
        $this->forge->dropTable('users', true);
    }
}
