<?php

namespace App\Models;

use CodeIgniter\Model;

class PasswordResetModel extends Model
{
    protected $table = 'password_resets';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useTimestamps = false;
    protected $allowedFields = ['email', 'token', 'expires_at', 'created_at'];

    private function hashToken(string $token): string
    {
        return hash('sha256', $token);
    }

    public function createToken(string $email, string $token): bool
    {
        // Remove any existing reset token for this email
        $this->deleteByEmail($email);

        return (bool) $this->insert([
            'email'      => $email,
            'token'      => $this->hashToken($token),
            'expires_at' => date('Y-m-d H:i:s', strtotime('+1 hour')),
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public function getByToken(string $token): ?array
    {
        return $this->where('token', $this->hashToken($token))->first();
    }

    public function deleteByEmail(string $email): bool
    {
        return $this->where('email', $email)->delete();
    }

    public function deleteExpired(): bool
    {
        return $this->where('expires_at <', date('Y-m-d H:i:s'))->delete();
    }
}
