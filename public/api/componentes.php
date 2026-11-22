<?php
require __DIR__ . '/../../src/bootstrap.php';

use App\Services\ComponenteService;

header('Content-Type: application/json; charset=utf-8');
header('X-Content-Type-Options: nosniff');
header('Cache-Control: public, max-age=60');

function responder_json(int $status, mixed $body = null): never
{
    http_response_code($status);
    if ($body !== null) {
        echo json_encode($body, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR);
    }
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    header('Allow: GET');
    responder_json(405, ['erro' => 'A API pública permite apenas consulta (GET).']);
}

try {
    $service = new ComponenteService();
    $id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);
    if (isset($_GET['id']) && (!$id || $id < 1)) {
        responder_json(400, ['erro' => 'Parâmetro id inválido.']);
    }
    if ($id) {
        $item = $service->buscar((int) $id);
        if (!$item) responder_json(404, ['erro' => 'Componente não encontrado.']);
        responder_json(200, $item);
    }
    responder_json(200, $service->listar());
} catch (Throwable $e) {
    error_log('[API componentes] ' . $e->getMessage());
    responder_json(503, ['erro' => 'Serviço temporariamente indisponível.']);
}
