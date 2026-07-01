<?php
function currency_symbol($code) {
    $symbols = [
        'USD' => '$',
        'IDR' => 'Rp',
        'MYR' => 'RM',
        'CNY' => '¥',
        'INR' => '₹',
        'EUR' => '€',
        'SAR' => '﷼',
        'VND' => '₫',
    ];
    return $symbols[$code] ?? '$';
}

function format_currency($amount, $currency = 'USD') {
    return currency_symbol($currency) . number_format($amount, 2);
}
