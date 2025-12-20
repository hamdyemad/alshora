<?php

return [
    'store_orders' => [
        'new',
        'inprogress',
        'delivered',
        'canceled',
        'returned',
    ],

    'styles' => [
        'new' => 'primary',
        'inprogress' => 'info',
        'delivered' => 'success',
        'canceled' => 'danger',
        'returned' => 'warning',
    ],

    'icons' => [
        'new' => 'clock',
        'inprogress' => 'sync',
        'delivered' => 'check-circle',
        'canceled' => 'times-circle',
        'returned' => 'exclamation-triangle',
    ],
];
