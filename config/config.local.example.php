<?php
// Configuração local do XAMPP (MySQL na porta 3307, root sem senha).
// Para o painel administrativo, configure usuário e hash de senha abaixo.
// Gere o hash com: php -r "echo password_hash('SUA_SENHA', PASSWORD_DEFAULT), PHP_EOL;"
return [
    'db' => [
        'host' => '127.0.0.1',
        'port' => '3307',
        'name' => 'braco_robotico',
        'user' => 'root',
        'pass' => '',
    ],
    'admin' => [
        'user' => 'equipe',
        'password_hash' => 'COLE_AQUI_O_HASH_GERADO',
    ],
];
