<?php
require __DIR__ . '/../../src/bootstrap.php';
exigir_admin();

use App\Services\PerguntaService;
$service = new PerguntaService();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_validar();
    $id = (int) ($_POST['id'] ?? 0);
    try {
        if (($_POST['acao'] ?? '') === 'responder') {
            $service->responder($id, (string) ($_POST['resposta'] ?? ''), isset($_POST['publicar']));
            flash('ok', 'Pergunta atualizada.');
        } elseif (($_POST['acao'] ?? '') === 'excluir') {
            $service->excluir($id);
            flash('ok', 'Pergunta excluída.');
        }
        redirecionar('perguntas.php');
    } catch (InvalidArgumentException $e) {
        flash('erro', $e->getMessage());
        redirecionar('perguntas.php');
    }
}

$perguntas = $service->listarAdmin();
cabecalho('Gerenciar perguntas', 'perguntas', true);
?>
<header class="admin-heading"><div><span class="eyebrow">Moderação</span><h1>Perguntas recebidas</h1><p>Responda e escolha o que será publicado no site.</p></div></header>
<?php if (!$perguntas): ?><div class="empty-state">Nenhuma pergunta cadastrada até agora.</div><?php else: ?>
  <div class="admin-card">
    <?php foreach ($perguntas as $p): ?>
      <article class="admin-card">
        <div class="component-top"><span class="status status-<?= e($p['status']) ?>"><?= $p['status'] === 'pendente' ? 'Pendente' : 'Publicada' ?></span><span class="category-label"><?= e($p['componente'] ?: 'Pergunta geral') ?></span></div>
        <h3><?= e($p['pergunta']) ?></h3>
        <p>Recebida em <?= e(date('d/m/Y H:i', strtotime($p['criado_em']))) ?></p>
        <form method="post" action="perguntas.php" class="admin-form">
          <?= csrf_campo() ?><input type="hidden" name="acao" value="responder"><input type="hidden" name="id" value="<?= (int) $p['id'] ?>">
          <div class="field"><label for="resposta-<?= (int) $p['id'] ?>">Resposta da equipe</label><textarea id="resposta-<?= (int) $p['id'] ?>" name="resposta" maxlength="500" placeholder="Escreva uma resposta objetiva..."><?= e($p['resposta']) ?></textarea></div>
          <div class="admin-actions"><label class="field-label"><input type="checkbox" name="publicar" value="1" <?= $p['status'] === 'publicada' ? 'checked' : '' ?>> Publicar no site</label><button class="button button-dark button-small" type="submit">Salvar resposta</button></div>
        </form>
        <form method="post" action="perguntas.php" data-confirm="Excluir esta pergunta permanentemente?" style="margin-top:10px">
          <?= csrf_campo() ?><input type="hidden" name="acao" value="excluir"><input type="hidden" name="id" value="<?= (int) $p['id'] ?>"><button class="button button-danger button-small" type="submit">Excluir pergunta</button>
        </form>
      </article>
    <?php endforeach; ?>
  </div>
<?php endif; ?>
<?php rodape(true); ?>
