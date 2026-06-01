<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePostsTable extends Migration
{
    public function up()
    {
        $this->dbforge->add_field(array(
            'Migrations_id' => array(
                'type'           => 'INT',
                'constraint'     => 5,
                'unsigned'       => true,
                'auto_increment' => true,
            ),
            'Migrations_title' => array(
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ),
            'Migrations_description' => array(
                'type' => 'TEXT',
                'null' => true,
            ),
        ));
        $this->dbforge->add_key('Migrations_id', true);
        $this->dbforge->create_table('Migrations');
    }
    

    public function down()
    {
            $this->dbforge->drop_table('Migrations');

    }
}
