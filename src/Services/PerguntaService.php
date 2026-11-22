<?php
declare(strict_types=1);

namespace App\Services;

use App\Database;
use InvalidArgumentException;
use PDO;

final class PerguntaService
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::conectar();
    }

    public function publicadas(): array
    {
        return $this->db->query(
            "SELECT p.id, p.pergunta, p.resposta, p.criado_em, c.nome AS componente
             FROM perguntas p LEFT JOIN componentes c ON c.id = p.componente_id
             WHERE p.status = 'publicada' AND p.resposta IS NOT NULL AND p.resposta <> ''
             ORDER BY p.respondida_em DESC, p.id DESC"
        )->fetchAll();
    }

    public function listarAdmin(): array
    {
        return $this->db->query(
            'SELECT p.id, p.pergunta, p.resposta, p.status, p.criado_em, p.respondida_em,
                    c.nome AS componente
             FROM perguntas p LEFT JOIN componentes c ON c.id = p.componente_id
             ORDER BY (p.status = \'pendente\') DESC, p.criado_em DESC, p.id DESC'
        )->fetchAll();
    }

    public function criar(string $pergunta, ?int $componenteId = null): void
    {
        $pergunta = trim($pergunta);
        if (tam($pergunta) < 8 || tam($pergunta) > 500) {
            throw new InvalidArgumentException('Escreva uma pergunta entre 8 e 500 caracteres.');
        }
        if ($componenteId !== null) {
            $st = $this->db->prepare('SELECT 1 FROM componentes WHERE id = ?');
            $st->execute([$componenteId]);
            if (!$st->fetchColumn()) {
                throw new InvalidArgumentException('O componente selecionado não existe.');
            }
        }
        $st = $this->db->prepare(
            "INSERT INTO perguntas (componente_id, pergunta, status) VALUES (?, ?, 'pendente')"
        );
        $st->execute([$componenteId, $pergunta]);
    }

    public function responder(int $id, string $resposta, bool $publicar): void
    {
        $resposta = trim($resposta);
        if ($resposta !== '' && tam($resposta) > 500) {
            throw new InvalidArgumentException('A resposta pode ter até 500 caracteres.');
        }
        if ($publicar && $resposta === '') {
            throw new InvalidArgumentException('Escreva uma resposta antes de publicar.');
        }
        $status = ($publicar && $resposta !== '') ? 'publicada' : 'pendente';
        $st = $this->db->prepare(
            'UPDATE perguntas SET resposta = ?, status = ?, respondida_em = CASE WHEN ? = \'publicada\' THEN NOW() ELSE NULL END WHERE id = ?'
        );
        $st->execute([$resposta !== '' ? $resposta : null, $status, $status, $id]);
        if ($st->rowCount() === 0) {
            $check = $this->db->prepare('SELECT 1 FROM perguntas WHERE id = ?');
            $check->execute([$id]);
            if (!$check->fetchColumn()) {
                throw new InvalidArgumentException('Essa pergunta não existe mais.');
            }
        }
    }

    public function excluir(int $id): void
    {
        $st = $this->db->prepare('DELETE FROM perguntas WHERE id = ?');
        $st->execute([$id]);
    }
}
