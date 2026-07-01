<?php
namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPublicShareToInvoices extends Migration {

    public function up() {
        $this->forge->addColumn('invoices', [
            'is_public' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
                'after'      => 'status',
            ],
            'public_token' => [
                'type'       => 'VARCHAR',
                'constraint' => 64,
                'null'       => true,
                'unique'     => true,
                'after'      => 'is_public',
            ],
        ]);
    }

    public function down() {
        $this->forge->dropColumn('invoices', 'public_token');
        $this->forge->dropColumn('invoices', 'is_public');
    }
}
