<?php
require __DIR__ . '/../src/bootstrap.php';

use App\Services\ComponenteService;
use App\Services\PerguntaService;

$perguntaService = new PerguntaService();
$perguntaDigitada = '';
$componenteSelecionado = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_validar();
    try {
        if (trim((string) ($_POST['website'] ?? '')) === '') {
            $componenteRaw = trim((string) ($_POST['componente_id'] ?? ''));
            $componenteId = $componenteRaw === '' ? null : filter_var($componenteRaw, FILTER_VALIDATE_INT);
            if ($componenteRaw !== '' && !$componenteId) {
                throw new InvalidArgumentException('Selecione um componente válido.');
            }
            $perguntaService->criar((string) ($_POST['pergunta'] ?? ''), $componenteId ?: null);
        }
        flash('ok', 'Pergunta enviada. A equipe vai revisar antes de publicar a resposta.');
        redirecionar('duvidas.php');
    } catch (InvalidArgumentException $e) {
        flash('erro', $e->getMessage());
        $perguntaDigitada = (string) ($_POST['pergunta'] ?? '');
        $componenteSelecionado = (string) ($_POST['componente_id'] ?? '');
    }
}

$componentes = (new ComponenteService())->listar();
$perguntas = $perguntaService->publicadas();
cabecalho('Perguntas', 'duvidas');
?>
<header class="page-heading">
  <span class="eyebrow">04 / Perguntas e respostas</span>
  <h1 class="page-title">Dúvidas sobre a construção.</h1>
  <p>Consulte as respostas publicadas pela equipe ou envie uma pergunta sobre a eletrônica, a programação e a estrutura mecânica.</p>
</header>
<div class="qa-layout">
  <section class="qa-list" aria-label="Perguntas respondidas">
    <div class="component-top" style="padding:0 0 15px"><span class="category-label">Base de conhecimento</span><span class="caption"><?= count($perguntas) ?> respostas publicadas</span></div>
    <?php if ($perguntas): ?>
      <?php foreach ($perguntas as $item): ?>
        <article class="question-card">
          <span class="category-label"><?= e($item['componente'] ?: 'Projeto') ?></span>
          <h2><?= e($item['pergunta']) ?></h2>
          <span class="answer-label">Resposta da equipe</span>
          <p><?= e($item['resposta']) ?></p>
        </article>
      <?php endforeach; ?>
    <?php else: ?>
      <div class="empty-state">Ainda não há respostas publicadas. Envie sua dúvida pelo formulário; a equipe poderá revisá-la no painel.</div>
    <?php endif; ?>
  </section>
  <aside class="qa-form-column">
    <form class="form-card" method="post" action="duvidas.php">
      <span class="eyebrow">Fale com a equipe</span>
      <h2>Enviar uma pergunta</h2>
      <p>As mensagens passam por revisão antes de aparecer publicamente.</p>
      <?= csrf_campo() ?>
      <div class="honeypot" aria-hidden="true"><label>Não preencha este campo<input name="website" tabindex="-1" autocomplete="off"></label></div>
      <div class="field"><label for="componente_id">Assunto relacionado</label>
        <select id="componente_id" name="componente_id"><option value="">Projeto em geral</option>
          <?php foreach ($componentes as $c): ?><option value="<?= (int) $c['id'] ?>" <?= $componenteSelecionado === (string) $c['id'] ? 'selected' : '' ?>><?= e($c['nome']) ?></option><?php endforeach; ?>
        </select>
      </div>
      <div class="field"><label for="pergunta">Sua pergunta</label><textarea id="pergunta" name="pergunta" maxlength="500" minlength="8" required placeholder="Ex.: como o Arduino transforma a leitura do potenciômetro em movimento?"><?= e($perguntaDigitada) ?></textarea><small>De 8 a 500 caracteres.</small></div>
      <button class="button button-dark" type="submit">Enviar para revisão <span aria-hidden="true">→</span></button>
      <p class="form-note">Não pedimos nome ou e-mail. Evite incluir dados pessoais no texto.</p>
    </form>
  </aside>
</div>
<?php rodape(); ?>
