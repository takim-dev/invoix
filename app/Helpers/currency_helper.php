<?php
function currency_symbol($code) {
    $symbols = [
        'USD' => '$',
        'IDR' => 'Rp',
        'SGD' => 'S$',
        'JPY' => '¥',
        'CNY' => '¥',
        'MYR' => 'RM',
    ];
    return $symbols[$code] ?? '$';
}

function format_currency($amount, $currency = 'USD') {
    return currency_symbol($currency) . number_format($amount, 2);
}
