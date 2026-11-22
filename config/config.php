<?php
/** Configuração: variáveis de ambiente têm prioridade sobre config.local.php. */
$local = is_file(__DIR__ . '/config.local.php') ? require __DIR__ . '/config.local.php' : [];
$localDb = $local['db'] ?? [];
$localAdmin = $local['admin'] ?? [];
$get = static function (string $key, mixed $fallback): mixed {
    $value = getenv($key);
    return $value !== false && $value !== '' ? $value : $fallback;
};

return [
    'db' => [
        'host' => $get('DB_HOST', $localDb['host'] ?? '127.0.0.1'),
        'port' => $get('DB_PORT', $localDb['port'] ?? '3307'),
        'name' => $get('DB_NAME', $localDb['name'] ?? 'braco_robotico'),
        'user' => $get('DB_USER', $localDb['user'] ?? 'root'),
        'pass' => $get('DB_PASS', $localDb['pass'] ?? ''),
        'charset' => 'utf8mb4',
    ],
    'admin' => [
        'user' => $get('ADMIN_USER', $localAdmin['user'] ?? ''),
        'password_hash' => $get('ADMIN_PASSWORD_HASH', $localAdmin['password_hash'] ?? ''),
    ],
];
