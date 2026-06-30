<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Migration_Add_user_company_codes extends Migration
{
    protected $DBGroup = 'default';

    public function up()
    {
        // Add code column to users (globally unique identifier for the user)
        $this->forge->addColumn('users', [
            'code' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => true,
                'after'      => 'name',
            ],
        ]);
        // MySQL allows multiple NULLs in a UNIQUE column, so existing rows stay valid.
        $this->db->query('ALTER TABLE `users` ADD UNIQUE KEY `users_code_unique` (`code`)');

        // Add code column to companies (unique per user — two users may reuse the same code)
        $this->forge->addColumn('companies', [
            'code' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => true,
                'after'      => 'name',
            ],
        ]);
        $this->db->query('ALTER TABLE `companies` ADD UNIQUE KEY `companies_user_code_unique` (`user_id`, `code`)');
    }

    public function down()
    {
        $this->db->query('DROP INDEX `companies_user_code_unique` ON `companies`');
        $this->forge->dropColumn('companies', 'code');

        $this->db->query('DROP INDEX `users_code_unique` ON `users`');
        $this->forge->dropColumn('users', 'code');
    }
}
