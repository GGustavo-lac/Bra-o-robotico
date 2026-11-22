<?php
require __DIR__ . '/../../src/bootstrap.php';
exigir_admin();

use App\Services\ComponenteService;
$service = new ComponenteService();
$acao = (string) ($_GET['acao'] ?? 'listar');
$id = (int) ($_GET['id'] ?? 0);
$valores = ['nome' => '', 'categoria_id' => '', 'quantidade' => 1, 'descricao' => '', 'especificacao' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_validar();
    $idPost = (int) ($_POST['id'] ?? 0);
    try {
        if (($_POST['acao'] ?? '') === 'salvar') {
            if ($idPost) {
                $service->atualizar($idPost, $_POST);
                flash('ok', 'Componente atualizado.');
            } else {
                $service->criar($_POST);
                flash('ok', 'Componente cadastrado.');
            }
        } elseif (($_POST['acao'] ?? '') === 'excluir') {
            $service->excluir($idPost);
            flash('ok', 'Componente excluído.');
        }
        redirecionar('componentes.php');
    } catch (InvalidArgumentException $e) {
        flash('erro', $e->getMessage());
        $valores = array_merge($valores, $_POST);
        $acao = $idPost ? 'editar' : 'novo';
        $id = $idPost;
    }
} elseif ($acao === 'editar') {
    $valores = $service->buscar($id) ?? redirecionar('componentes.php');
}

cabecalho('Catálogo administrativo', 'componentes', true);
if ($acao === 'novo' || $acao === 'editar'):
?>
<header class="admin-heading"><div><span class="eyebrow">Catálogo</span><h1><?= $acao === 'editar' ? 'Editar componente' : 'Adicionar componente' ?></h1><p>Essas informações aparecem na área pública do projeto.</p></div></header>
<form method="post" action="componentes.php" class="form-card admin-form">
  <?= csrf_campo() ?><input type="hidden" name="acao" value="salvar"><input type="hidden" name="id" value="<?= $id ?>">
  <div class="field"><label for="nome">Nome</label><input id="nome" name="nome" maxlength="80" required value="<?= e($valores['nome']) ?>"></div>
  <div class="field"><label for="categoria_id">Categoria</label><select id="categoria_id" name="categoria_id" required><option value="">Selecione</option><?php foreach ($service->categorias() as $c): ?><option value="<?= (int) $c['id'] ?>" <?= (int) $valores['categoria_id'] === (int) $c['id'] ? 'selected' : '' ?>><?= e($c['nome']) ?></option><?php endforeach; ?></select></div>
  <div class="field"><label for="quantidade">Quantidade</label><input type="number" id="quantidade" name="quantidade" min="1" max="99" required value="<?= (int) $valores['quantidade'] ?>"></div>
  <div class="field"><label for="descricao">Função no projeto</label><textarea id="descricao" name="descricao" maxlength="500" required><?= e($valores['descricao']) ?></textarea></div>
  <div class="field"><label for="especificacao">Especificação complementar (opcional)</label><input id="especificacao" name="especificacao" maxlength="120" value="<?= e($valores['especificacao'] ?? '') ?>"></div>
  <div class="admin-actions"><button class="button button-dark" type="submit">Salvar componente</button><a class="button button-quiet" href="componentes.php">Cancelar</a></div>
</form>
<?php else: $lista = $service->listar(); ?>
<header class="admin-heading"><div><span class="eyebrow">Catálogo público</span><h1>Componentes</h1><p>Adicione, edite ou remova peças cadastradas no projeto.</p></div><a class="button button-dark" href="componentes.php?acao=novo">+ Adicionar peça</a></header>
<div class="panel-card table-wrap">
  <?php if (!$lista): ?><div class="empty-state">Nenhum componente cadastrado.</div><?php else: ?>
    <table><thead><tr><th>Componente</th><th>Categoria</th><th>Qtd.</th><th>Ações</th></tr></thead><tbody>
      <?php foreach ($lista as $c): ?><tr><td><strong><?= e($c['nome']) ?></strong><br><small><?= e($c['descricao']) ?></small></td><td><?= e($c['categoria']) ?></td><td><?= (int) $c['quantidade'] ?></td><td><div class="admin-actions"><a class="button button-quiet button-small" href="componentes.php?acao=editar&id=<?= (int) $c['id'] ?>">Editar</a><form method="post" action="componentes.php" data-confirm="Excluir este componente? Articulações e perguntas relacionadas permanecerão, mas sem vínculo com ele."><?= csrf_campo() ?><input type="hidden" name="acao" value="excluir"><input type="hidden" name="id" value="<?= (int) $c['id'] ?>"><button class="button button-danger button-small" type="submit">Excluir</button></form></div></td></tr><?php endforeach; ?>
    </tbody></table>
  <?php endif; ?>
</div>
<?php endif; ?>
<?php rodape(true); ?>
