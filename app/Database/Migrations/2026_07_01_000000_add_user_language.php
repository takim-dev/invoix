<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Migration_Add_user_language extends Migration
{
    protected $DBGroup = 'default';

    public function up()
    {
        $this->forge->addColumn('users', [
            'language' => [
                'type'       => 'VARCHAR',
                'constraint' => 10,
                'null'       => true,
                'after'      => 'code',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('users', 'language');
    }
}
