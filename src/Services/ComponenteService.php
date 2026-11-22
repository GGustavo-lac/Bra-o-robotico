<?php
declare(strict_types=1);

namespace App\Services;

use App\Database;
use InvalidArgumentException;
use PDO;
use PDOException;

final class ComponenteService
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::conectar();
    }

    public function categorias(): array
    {
        return $this->db->query('SELECT id, nome FROM categorias ORDER BY nome')->fetchAll();
    }

    public function listar(): array
    {
        return $this->db->query(
            'SELECT c.id, c.nome, c.descricao, c.quantidade, c.especificacao, g.nome AS categoria
             FROM componentes c JOIN categorias g ON g.id = c.categoria_id
             ORDER BY g.nome, c.nome'
        )->fetchAll();
    }

    public function buscar(int $id): ?array
    {
        $st = $this->db->prepare('SELECT * FROM componentes WHERE id = ?');
        $st->execute([$id]);
        return $st->fetch() ?: null;
    }

    public function criar(array $dados): int
    {
        $d = $this->validar($dados);
        try {
            $st = $this->db->prepare(
                'INSERT INTO componentes (categoria_id, nome, descricao, quantidade, especificacao)
                 VALUES (?, ?, ?, ?, ?)'
            );
            $st->execute([$d['categoria_id'], $d['nome'], $d['descricao'], $d['quantidade'], $d['especificacao']]);
        } catch (PDOException $e) {
            $this->tratarDuplicidade($e);
        }
        return (int) $this->db->lastInsertId();
    }

    public function atualizar(int $id, array $dados): void
    {
        if (!$this->buscar($id)) {
            throw new InvalidArgumentException('Esse componente não existe mais.');
        }
        $d = $this->validar($dados);
        try {
            $st = $this->db->prepare(
                'UPDATE componentes SET categoria_id = ?, nome = ?, descricao = ?, quantidade = ?, especificacao = ? WHERE id = ?'
            );
            $st->execute([$d['categoria_id'], $d['nome'], $d['descricao'], $d['quantidade'], $d['especificacao'], $id]);
        } catch (PDOException $e) {
            $this->tratarDuplicidade($e);
        }
    }

    public function excluir(int $id): void
    {
        $st = $this->db->prepare('DELETE FROM componentes WHERE id = ?');
        $st->execute([$id]);
    }

    private function validar(array $dados): array
    {
        $nome = trim((string) ($dados['nome'] ?? ''));
        $descricao = trim((string) ($dados['descricao'] ?? ''));
        $categoria = filter_var($dados['categoria_id'] ?? null, FILTER_VALIDATE_INT);
        $quantidade = filter_var($dados['quantidade'] ?? null, FILTER_VALIDATE_INT);
        $especificacao = trim((string) ($dados['especificacao'] ?? ''));

        if ($nome === '' || tam($nome) > 80) {
            throw new InvalidArgumentException('Informe um nome de até 80 caracteres.');
        }
        if ($descricao === '' || tam($descricao) > 500) {
            throw new InvalidArgumentException('A descrição deve ter entre 1 e 500 caracteres.');
        }
        if ($quantidade === false || $quantidade < 1 || $quantidade > 99) {
            throw new InvalidArgumentException('A quantidade deve estar entre 1 e 99.');
        }
        if ($especificacao !== '' && tam($especificacao) > 120) {
            throw new InvalidArgumentException('A especificação pode ter até 120 caracteres.');
        }
        $st = $this->db->prepare('SELECT 1 FROM categorias WHERE id = ?');
        $st->execute([(int) $categoria]);
        if (!$categoria || !$st->fetchColumn()) {
            throw new InvalidArgumentException('Escolha uma categoria válida.');
        }
        return [
            'categoria_id' => (int) $categoria,
            'nome' => $nome,
            'descricao' => $descricao,
            'quantidade' => (int) $quantidade,
            'especificacao' => $especificacao !== '' ? $especificacao : null,
        ];
    }

    private function tratarDuplicidade(PDOException $e): never
    {
        if ($e->getCode() === '23000') {
            throw new InvalidArgumentException('Já existe um componente com esse nome.');
        }
        throw $e;
    }
}
