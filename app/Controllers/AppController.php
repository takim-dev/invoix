<?php
namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\Database\BaseConnection;
use CodeIgniter\Model;
use Config\Services;

class AppController extends Controller {

    public const ROLE_ADMIN = 'admin';
    public const ROLE_USER  = 'user';

    public const STATUS_ACTIVE  = 'active';
    public const STATUS_PENDING = 'pending';
    public const STATUS_BLOCKED = 'blocked';

    public const DEFAULT_CURRENCY = 'USD';

    public const ALLOWED_STATUSES = [self::STATUS_ACTIVE, self::STATUS_PENDING, self::STATUS_BLOCKED];

    protected $userModel;
    protected $companyModel;
    protected $invoiceModel;
    protected $invoiceItemModel;
    protected $itemModel;
    protected $catModel;

    protected function requireLogin() {
        if (!session()->get('user_id')) {
            return redirect()->to('/login')->with('error', 'Please login first.');
        }
    }

    protected function requireAdmin() {
        $this->requireLogin();
        if (session()->get('role') !== self::ROLE_ADMIN) {
            return redirect()->to('/dashboard')->with('error', 'Access denied.');
        }
    }

    protected function getUser() {
        return [
            'id' => session()->get('user_id'),
            'name' => session()->get('user_name'),
            'email' => session()->get('user_email'),
            'role' => session()->get('role'),
            'max_companies' => session()->get('max_companies'),
            'max_invoices' => session()->get('max_invoices'),
        ];
    }

    protected function userInfo() {
        return $this->getUser();
    }

    protected function currentUserId(): int {
        return (int) session()->get('user_id');
    }

    protected function isAdmin(): bool {
        return session()->get('role') === self::ROLE_ADMIN;
    }

    /**
     * Fetch an entity and verify it belongs to the current user.
     * On failure, sets a redirect flash and returns null.
     */
    protected function getOwnedEntity(Model $model, $id, string $redirect = '/'): ?array {
        $entity = $model->find($id);
        if (!$entity || ($entity['user_id'] ?? null) !== session()->get('user_id')) {
            session()->setFlashdata('error', 'Not found.');
            return null;
        }
        return $entity;
    }

    /**
     * Fetch an entity by primary key, or redirect with "Not found" flash.
     * Use this for admin-scoped resources where ownership is not a concern.
     */
    protected function getOrAbort(Model $model, $id, string $redirect = '/'): ?array {
        $entity = $model->find($id);
        if (!$entity) {
            session()->setFlashdata('error', 'Not found.');
            return null;
        }
        return $entity;
    }

    /**
     * Standard redirect for validation failures.
     */
    protected function failValidation(?string $redirect = null) {
        $r = $redirect ? redirect()->to($redirect) : redirect()->back();
        return $r->withInput()->with('error', $this->validator->listErrors());
    }

    /**
     * Enforce a per-user limit. If exceeded, redirect with message.
     * Returns null if OK to proceed, or a RedirectResponse if blocked.
     */
    protected function enforceLimit(int $current, int $max, string $redirect, string $message) {
        if ($current >= $max) {
            return redirect()->to($redirect)->with('error', $message);
        }
        return null;
    }

    /**
     * Map stored email_config keys to CodeIgniter Email class property names.
     */
    protected function buildEmailConfig(array $raw): array {
        $encryption = $raw['encryption'] ?? 'tls';
        return [
            'protocol'    => $raw['protocol'] ?? 'smtp',
            'SMTPHost'    => $raw['host'] ?? '',
            'SMTPPort'    => (int) ($raw['port'] ?? 587),
            'SMTPUser'    => $raw['user'] ?? '',
            'SMTPPass'    => $raw['pass'] ?? '',
            'SMTPCrypto'  => $encryption === 'none' ? '' : $encryption,
            'SMTPTimeout' => 30,
            'mailType'    => $raw['mail_type'] ?? 'html',
            'charset'     => $raw['charset'] ?? 'UTF-8',
            'newline'     => $raw['newline'] ?? "\r\n",
            'CRLF'        => $raw['newline'] ?? "\r\n",
        ];
    }

    /**
     * Parse DataTables jQuery GET params into a normalized pagination+order array.
     *
     * @param array  $columns      Indexed column map (position => DB column).
     * @param string $defaultColumn  Fallback DB column if order column is invalid.
     */
    protected function datatablePagination(array $columns, string $defaultColumn): array {
        $draw   = (int) ($this->request->getGet('draw') ?? 1);
        $start  = max(0, (int) ($this->request->getGet('start') ?? 0));
        $length = (int) ($this->request->getGet('length') ?? 10);
        $length = $length > 0 ? min($length, 100) : 10;

        $searchParam = $this->request->getGet('search');
        $search = trim((string) (is_array($searchParam) ? ($searchParam['value'] ?? '') : ''));

        $orderParam = $this->request->getGet('order');
        $order = is_array($orderParam) ? ($orderParam[0] ?? []) : [];

        $orderColumn = $columns[(int) ($order['column'] ?? -1)] ?? $defaultColumn;
        $orderDir = strtolower($order['dir'] ?? 'desc') === 'asc' ? 'ASC' : 'DESC';

        return [
            'draw'         => $draw,
            'start'        => $start,
            'length'       => $length,
            'search'       => $search,
            'orderColumn'  => $orderColumn,
            'orderDir'     => $orderDir,
        ];
    }
}
