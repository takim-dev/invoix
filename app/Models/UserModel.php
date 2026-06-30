<?php
namespace App\Models;
use CodeIgniter\Model;

class UserModel extends Model {
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $allowedFields = ['name','code','email','password','role','status','max_companies','max_invoices'];

    public function findByEmail($email) {
        return $this->where('email', $email)->first();
    }

    public function register($data) {
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        return $this->insert($data);
    }

    public function verifyPassword($email, $password) {
        $user = $this->findByEmail($email);
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }

    public function getAdminCount() {
        return $this->where('role', 'admin')->countAllResults();
    }

    public function getActiveAdminCount() {
        return $this->where('role', 'admin')->where('status', 'active')->countAllResults();
    }

    public function datatablesBuilder(string $search) {
        $builder = $this->builder();
        $builder->select('id, name, email, role, status, max_companies, max_invoices, created_at, updated_at');

        if ($search !== '') {
            $builder->groupStart()
                ->like('name', $search)
                ->orLike('email', $search)
                ->orLike('role', $search)
                ->orLike('status', $search)
                ->groupEnd();
        }
        return $builder;
    }
}
