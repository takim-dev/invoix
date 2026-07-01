<?php

namespace App\Models;

use CodeIgniter\Model;

class EmailVerificationModel extends Model
{
    protected $table = 'email_verifications';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useTimestamps = false;
    protected $allowedFields = ['user_id', 'token', 'expires_at', 'verified_at', 'created_at'];

    private function hashToken(string $token): string
    {
        return hash('sha256', $token);
    }

    public function createToken(int $userId, string $token): bool
    {
        $expiresAt = date('Y-m-d H:i:s', strtotime('+24 hours'));

        return (bool) $this->insert([
            'user_id' => $userId,
            'token' => $this->hashToken($token),
            'expires_at' => $expiresAt,
            'verified_at' => null,
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public function getByToken(string $token): ?array
    {
        return $this->where('token', $this->hashToken($token))->first();
    }

    public function verifyToken(string $token): bool
    {
        return (bool) $this->where('token', $this->hashToken($token))->update(null, [
            'verified_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public function deleteByUserId(int $userId): bool
    {
        return $this->where('user_id', $userId)->delete();
    }

    public function deleteExpired(): bool
    {
        return $this->where('expires_at <', date('Y-m-d H:i:s'))->delete();
    }
}
