<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Migration_Add_currency_to_items extends Migration
{
    protected $DBGroup = 'default';

    public function up()
    {
        $this->forge->addColumn('items', [
            'currency' => [
                'type'       => 'VARCHAR',
                'constraint' => 10,
                'null'       => false,
                'default'    => 'USD',
                'after'      => 'unit_price',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('items', 'currency');
    }
}
