<?php
namespace App\Models;
use CodeIgniter\Model;

class ItemModel extends Model {
    protected $table = 'items';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useTimestamps = false;
    protected $allowedFields = ['user_id','category_id','name','description','unit_price','currency','unit'];

    public function getByUser($userId) {
        return $this->select('items.*, item_categories.name as category_name')
            ->join('item_categories', 'item_categories.id = items.category_id', 'left')
            ->where('items.user_id', $userId)
            ->orderBy('items.name', 'ASC')
            ->findAll();
    }

    public function getWithCategory($id) {
        return $this->select('items.*, item_categories.name as category_name')
            ->join('item_categories', 'item_categories.id = items.category_id', 'left')
            ->where('items.id', $id)
            ->first();
    }

    public function countByUser($userId) {
        return $this->where('user_id', $userId)->countAllResults();
    }

    public function datatablesBuilder(int $userId, string $search) {
        $builder = $this->builder();
        $builder->select('items.*, item_categories.name as category_name')
            ->join('item_categories', 'item_categories.id = items.category_id', 'left')
            ->where('items.user_id', $userId);

        if ($search !== '') {
            $builder->groupStart()
                ->like('items.name', $search)
                ->orLike('items.description', $search)
                ->orLike('items.unit', $search)
                ->orLike('item_categories.name', $search)
                ->groupEnd();
        }
        return $builder;
    }
}
