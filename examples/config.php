<?php
return [
    'database' => [
        'host' => 'localhost',
        'username' => 'admin',
        'password' => '12qweasdZ123',
        'database' => 'profpaul_corsi'
    ],
    'urls' => [
        'home' => 'http://localhost',
        'login' => 'http://localhost/login.php'
    ],
    'forms' => [
        'login' => [
            'url' => 'http://localhost/login.php',
            'fields' => [
                'username' => 'test',
                'password' => 'test123'
            ],
            'success' => 'Login successful',
            'error' => 'Invalid credentials'
        ]
    ]
];