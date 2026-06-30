<?php
namespace App\Models;

use CodeIgniter\Model;

class SettingModel extends Model {
    protected $table = 'app_settings';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $allowedFields = ['setting_key', 'setting_value'];

    public function getSetting($key, $default = null) {
        $row = $this->where('setting_key', $key)->first();
        return $row ? $row['setting_value'] : $default;
    }

    public function saveSetting($key, $value) {
        $existing = $this->where('setting_key', $key)->first();
        if ($existing) {
            return $this->update($existing['id'], ['setting_value' => $value]);
        }
        return $this->insert(['setting_key' => $key, 'setting_value' => $value]);
    }

    public function getAllSettings() {
        $settings = $this->findAll();
        $result = [];
        foreach ($settings as $s) {
            $result[$s['setting_key']] = $s['setting_value'];
        }
        return $result;
    }
}
