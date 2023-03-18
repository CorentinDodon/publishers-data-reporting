<?php

return [
    'exchangerate_host' => [
        'base_url' => 'https://api.exchangerate.host/latest?base=USD',
        'method' => 'exchangerateHostDataTransformer',
        'requireKey' => false
    ],
    'currencylayer' => [
        'base_url' => 'https://cdn.jsdelivr.net/gh/fawazahmed0/currency-api@1/latest/currencies/usd.json',
        'method' => 'currencylayerDataTransformer',
        'requireKey' => false
    ],
    'fixerio' => [
        'base_url' => 'https://data.fixer.io/api/latest?access_key=' . env('FIXERIO_API_KEY', 'xxx'),
        'method' => 'fixerioDataTransformer',
        'requireKey' => true,
    ],
];
