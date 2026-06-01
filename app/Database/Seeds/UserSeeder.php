<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'name' => 'Admin',
                'email' => 'admin@gmail.com',
                'password' => '123456',  // plain text
                'role' => 'admin'
            ],
            [
                'name' => 'Rahul',
                'email' => 'rahul@gmail.com',
                'password' => '123456',
                'role' => 'employee'
            ],
            [
                'name' => 'Sita',
                'email' => 'sita@gmail.com',
                'password' => '123456',
                'role' => 'employee'
            ]
        ];

        // Insert into table
        $this->db->table('users')->insertBatch($data);
    }
}
