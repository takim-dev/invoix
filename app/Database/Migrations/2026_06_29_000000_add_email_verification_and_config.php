<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Migration_Add_email_verification_and_config extends Migration
{
    protected $DBGroup = 'default';

    public function up()
    {
        // Create email_verifications table
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'user_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'token' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'expires_at' => [
                'type' => 'DATETIME',
            ],
            'verified_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('user_id');
        $this->forge->addKey('token');
        $this->forge->createTable('email_verifications');
    }

    public function down()
    {
        $this->forge->dropTable('email_verifications');
    }
}
