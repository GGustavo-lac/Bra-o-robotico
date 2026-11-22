<?php
declare(strict_types=1);

namespace App\Services;

use App\Database;
use InvalidArgumentException;
use PDO;

final class AvaliacaoService
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::conectar();
    }

    public function criar(int $nota, string $comentario): void
    {
        $comentario = trim($comentario);
        if ($nota < 1 || $nota > 5) {
            throw new InvalidArgumentException('Escolha uma nota de 1 a 5.');
        }
        if (tam($comentario) > 500) {
            throw new InvalidArgumentException('O comentário pode ter até 500 caracteres.');
        }
        $st = $this->db->prepare('INSERT INTO avaliacoes (nota, comentario) VALUES (?, ?)');
        $st->execute([$nota, $comentario !== '' ? $comentario : null]);
    }

    public function resumo(): array
    {
        $sql = 'SELECT COUNT(*) AS total, COALESCE(ROUND(AVG(nota), 1), 0) AS media FROM avaliacoes';
        $resumo = $this->db->query($sql)->fetch() ?: ['total' => 0, 'media' => 0];
        $resumo['recentes'] = $this->db->query(
            'SELECT nota, comentario, criado_em FROM avaliacoes WHERE comentario IS NOT NULL AND comentario <> \'\' ORDER BY criado_em DESC LIMIT 8'
        )->fetchAll();
        return $resumo;
    }
}
