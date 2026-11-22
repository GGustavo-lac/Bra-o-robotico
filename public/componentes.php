<?php
require __DIR__ . '/../src/bootstrap.php';

use App\Services\ComponenteService;

$lista = (new ComponenteService())->listar();
$grupos = [];
foreach ($lista as $item) $grupos[$item['categoria']][] = $item;
$totalUnidades = array_sum(array_map(static fn(array $item): int => (int) $item['quantidade'], $lista));

cabecalho('Componentes', 'componentes');
?>
<header class="page-heading">
  <span class="eyebrow">03 / Inventário técnico</span>
  <h1 class="page-title">O que compõe o protótipo.</h1>
  <p>Peças cadastradas, quantidades informadas e função de cada item no sistema. O catálogo é alimentado pelo banco de dados e pode ser atualizado pela equipe.</p>
</header>
<div class="catalog-toolbar">
  <p><strong><?= count($lista) ?></strong> tipos de componente · <strong><?= $totalUnidades ?></strong> unidades cadastradas</p>
  <label class="search-field"><span class="category-label">Filtrar</span><input type="search" id="component-search" placeholder="Buscar peça ou função…" autocomplete="off" aria-label="Buscar componente por nome, categoria ou função"></label>
</div>
<?php if (!$grupos): ?>
  <div class="empty-state">O catálogo ainda não tem componentes cadastrados.</div>
<?php else: ?>
  <?php foreach ($grupos as $categoria => $itens): ?>
    <section class="component-group" data-component-group>
      <h2><?= e($categoria) ?></h2>
      <div class="table-wrap">
        <table>
          <thead><tr><th scope="col">Componente</th><th scope="col">Função no projeto</th><th scope="col">Quantidade</th><th scope="col">Especificação</th></tr></thead>
          <tbody>
          <?php foreach ($itens as $item): ?>
            <tr data-component-row data-search="<?= e(strtolower($categoria . ' ' . $item['nome'] . ' ' . $item['descricao'] . ' ' . ($item['especificacao'] ?? ''))) ?>">
              <td><strong><?= e($item['nome']) ?></strong></td>
              <td><?= e($item['descricao']) ?></td>
              <td><span class="quantity">× <?= (int) $item['quantidade'] ?></span></td>
              <td><?= $item['especificacao'] ? e($item['especificacao']) : '<span class="caption">Não informada</span>' ?></td>
            </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </section>
  <?php endforeach; ?>
  <p class="empty-state" id="component-empty" hidden>Nenhum componente corresponde à busca.</p>
<?php endif; ?>

<section class="section notice">
  <strong>Sobre os dados:</strong> quantidades e descrições refletem o cadastro atual. O diagrama de simulação mostra uma bateria de 9 V, mas a alimentação da montagem física deve ser confirmada à parte. Para quatro servos, verifique a corrente exigida e utilize alimentação regulada apropriada, com GND comum ao Arduino.
</section>
<?php rodape(); ?>
