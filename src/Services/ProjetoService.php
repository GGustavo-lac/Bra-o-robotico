<?php
declare(strict_types=1);

namespace App\Services;

use App\Database;
use PDO;

final class ProjetoService
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::conectar();
    }

    public function articulacoes(): array
    {
        return $this->db->query(
            'SELECT a.id, a.nome, a.descricao, a.ordem,
                    servo.nome AS servo, entrada.nome AS entrada
             FROM articulacoes a
             LEFT JOIN componentes servo ON servo.id = a.servo_componente_id
             LEFT JOIN componentes entrada ON entrada.id = a.entrada_componente_id
             ORDER BY a.ordem'
        )->fetchAll();
    }

    public function resumoPublico(): array
    {
        $sql = "SELECT
            (SELECT COUNT(*) FROM componentes) AS componentes,
            (SELECT COUNT(*) FROM articulacoes) AS articulacoes,
            (SELECT COUNT(*) FROM perguntas WHERE status = 'publicada' AND resposta IS NOT NULL AND resposta <> '') AS perguntas";
        return $this->db->query($sql)->fetch() ?: ['componentes' => 0, 'articulacoes' => 0, 'perguntas' => 0];
    }
}
