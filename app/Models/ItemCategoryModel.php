<?php
namespace App\Models;
use CodeIgniter\Model;

class ItemCategoryModel extends Model {
    protected $table = 'item_categories';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useTimestamps = false;
    protected $allowedFields = ['user_id','name'];

    public function getByUser($userId) {
        return $this->where('user_id', $userId)->orderBy('name', 'ASC')->findAll();
    }
}
