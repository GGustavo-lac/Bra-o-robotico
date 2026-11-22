<?php
declare(strict_types=1);

namespace App\Services;

use App\Database;
use PDO;

final class PainelService
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::conectar();
    }

    public function resumo(): array
    {
        return $this->db->query(
            "SELECT
                (SELECT COUNT(*) FROM componentes) AS componentes,
                (SELECT COUNT(*) FROM articulacoes) AS articulacoes,
                (SELECT COUNT(*) FROM perguntas) AS perguntas,
                (SELECT COUNT(*) FROM perguntas WHERE status = 'pendente') AS pendentes,
                (SELECT COUNT(*) FROM perguntas WHERE status = 'publicada') AS publicadas,
                (SELECT COUNT(*) FROM avaliacoes) AS avaliacoes,
                (SELECT COALESCE(ROUND(AVG(nota), 1), 0) FROM avaliacoes) AS media_nota"
        )->fetch();
    }
}
