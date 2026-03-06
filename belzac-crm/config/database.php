<?php
return [
    'host' => 'localhost',
    'database' => 'belzac_crm',
    'username' => 'belzac_user',
    'password' => 'BelzacCRM2026!SecurePass',
    'charset' => 'utf8mb4',
    'options' => [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]
];
