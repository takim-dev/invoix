<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Migration_Make_company_code_globally_unique extends Migration
{
    protected $DBGroup = 'default';

    public function up()
    {
        // Company codes are now globally unique (not just per-user) so that
        // an invoice number prefix cannot collide across users or companies.
        $this->db->query('ALTER TABLE `companies` DROP INDEX `companies_user_code_unique`');
        $this->db->query('ALTER TABLE `companies` ADD UNIQUE KEY `companies_code_unique` (`code`)');
    }

    public function down()
    {
        $this->db->query('ALTER TABLE `companies` DROP INDEX `companies_code_unique`');
        $this->db->query('ALTER TABLE `companies` ADD UNIQUE KEY `companies_user_code_unique` (`user_id`, `code`)');
    }
}
