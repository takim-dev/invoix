<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Validation\StrictRules\CreditCardRules;
use CodeIgniter\Validation\StrictRules\FileRules;
use CodeIgniter\Validation\StrictRules\FormatRules;
use CodeIgniter\Validation\StrictRules\Rules;

class Validation extends BaseConfig
{
    public array $ruleSets = [
        Rules::class,
        FormatRules::class,
        FileRules::class,
        CreditCardRules::class,
    ];

    public array $templates = [
        'list'   => 'CodeIgniter\Validation\Views\list',
        'single' => 'CodeIgniter\Validation\Views\single',
    ];

    public static array $invoice = [
        'company_id'  => 'required|integer',
        'client_name' => 'required|max_length[200]',
        'invoice_date'=> 'required|valid_date',
        'due_date'    => 'required|valid_date',
        'currency'    => 'in_list[USD,IDR,SGD,JPY,CNY,MYR]',
        'status'      => 'in_list[draft,sent,paid,overdue,cancelled]',
        'tax_rate'    => 'permit_empty|decimal|greater_than_equal_to[0]|less_than_equal_to[100]',
    ];

    public static array $company = [
        'name'        => 'required|max_length[200]',
        'code'        => 'permit_empty|max_length[20]|regex_match[/^[A-Z0-9\-_]*$/]',
        'email'       => 'permit_empty|valid_email|max_length[200]',
        'phone'       => 'permit_empty|max_length[50]',
    ];

    public static array $userCode = [
        'code' => 'permit_empty|max_length[20]|regex_match[/^[A-Z0-9\-_]*$/]',
    ];

    public static array $item = [
        'name'        => 'required|max_length[200]',
        'unit_price'  => 'required|decimal|greater_than_equal_to[0]',
        'unit'        => 'permit_empty|max_length[20]',
        'currency'    => 'permit_empty|in_list[USD,IDR,SGD,JPY,CNY,MYR]',
    ];

    public static array $itemCategory = [
        'name' => 'required|max_length[100]',
    ];

    public static array $authSettings = [
        'enable_register'      => 'in_list[0,1]',
        'default_user_status'  => 'in_list[active,pending]',
        'verification_method'  => 'in_list[none,email,admin]',
    ];

    public static array $invoiceConfig = [
        'default_max_companies' => 'required|integer|greater_than_equal_to[0]',
        'default_max_invoices'  => 'required|integer|greater_than_equal_to[0]',
    ];

    public static array $login = [
        'email'    => 'required|valid_email',
        'password' => 'required|min_length[6]',
    ];

    public static array $register = [
        'name'            => 'required|min_length[3]|max_length[100]',
        'email'           => 'required|valid_email|max_length[200]|is_unique[users.email]',
        'password'        => 'required|min_length[6]',
        'confirm_password'=> 'required|matches[password]',
    ];

    public static array $passwordChange = [
        'current_password' => 'required',
        'password'         => 'required|min_length[6]',
        'confirm_password' => 'required|matches[password]',
    ];

    public static array $forgotPassword = [
        'email' => 'required|valid_email',
    ];

    public static array $resetPassword = [
        'password'         => 'required|min_length[6]',
        'confirm_password' => 'required|matches[password]',
    ];
}
