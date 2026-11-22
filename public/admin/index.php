<?php
require __DIR__ . '/../../src/bootstrap.php';
exigir_admin();

use App\Services\PainelService;
use App\Services\PerguntaService;
use App\Services\AvaliacaoService;

$resumo = (new PainelService())->resumo();
$perguntas = array_slice((new PerguntaService())->listarAdmin(), 0, 5);
$avaliacoes = (new AvaliacaoService())->resumo();

cabecalho('Painel da equipe', 'painel', true);
?>
<header class="admin-heading">
  <div><span class="eyebrow">Visão geral</span><h1>Painel da equipe</h1><p>Dados do projeto e atividade enviada pelos visitantes.</p></div>
  <a class="button button-dark" href="../index.php" target="_blank" rel="noopener noreferrer">Ver site público ↗</a>
</header>
<section class="admin-metrics" aria-label="Indicadores do projeto">
  <div class="admin-metric"><strong><?= (int) $resumo['componentes'] ?></strong><span>Componentes cadastrados</span></div>
  <div class="admin-metric"><strong><?= (int) $resumo['articulacoes'] ?></strong><span>Articulações</span></div>
  <div class="admin-metric"><strong><?= (int) $resumo['pendentes'] ?></strong><span>Perguntas para revisar</span></div>
  <div class="admin-metric"><strong><?= e((string) $resumo['media_nota']) ?>/5</strong><span>Média de <?= (int) $resumo['avaliacoes'] ?> avaliações</span></div>
</section>

<div class="admin-columns">
  <section class="panel-card">
    <h2>Perguntas recentes</h2>
    <?php if (!$perguntas): ?><div class="empty-state">Ainda não chegaram perguntas.</div><?php else: ?>
      <?php foreach ($perguntas as $p): ?>
        <article class="admin-card">
          <div class="component-top"><span class="status status-<?= e($p['status']) ?>"><?= $p['status'] === 'pendente' ? 'Pendente' : 'Publicada' ?></span><span class="category-label"><?= e($p['componente'] ?: 'Geral') ?></span></div>
          <h3><?= e($p['pergunta']) ?></h3>
          <?php if ($p['resposta']): ?><p><?= e($p['resposta']) ?></p><?php endif; ?>
          <a class="text-link" href="perguntas.php">Revisar pergunta →</a>
        </article>
      <?php endforeach; ?>
    <?php endif; ?>
    <a class="button button-quiet" href="perguntas.php">Gerenciar todas as perguntas</a>
  </section>
  <section class="panel-card">
    <h2>Retorno do público</h2>
    <p class="section-intro compact-intro">As avaliações não pedem identificação. O banco guarda nota, comentário e data.</p>
    <?php if (!$avaliacoes['recentes']): ?><div class="empty-state">As avaliações com comentário aparecerão aqui.</div><?php else: ?>
      <?php foreach ($avaliacoes['recentes'] as $a): ?>
        <article class="admin-card"><div class="component-top"><strong><?= (int) $a['nota'] ?>/5</strong><small class="admin-date"><?= e(date('d/m/Y H:i', strtotime($a['criado_em']))) ?></small></div><p class="admin-comment"><?= e($a['comentario']) ?></p></article>
      <?php endforeach; ?>
    <?php endif; ?>
  </section>
</div>
<?php rodape(true); ?>
