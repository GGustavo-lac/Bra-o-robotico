<?php
require __DIR__ . '/../src/bootstrap.php';

use App\Services\AvaliacaoService;

$service = new AvaliacaoService();
$notaSelecionada = '';
$comentarioDigitado = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_validar();
    try {
        if (trim((string) ($_POST['website'] ?? '')) === '') {
            $nota = filter_var($_POST['nota'] ?? null, FILTER_VALIDATE_INT);
            if ($nota === false) $nota = 0;
            $service->criar((int) $nota, (string) ($_POST['comentario'] ?? ''));
        }
        flash('ok', 'Obrigado! Sua avaliação anônima foi registrada.');
        redirecionar('index.php');
    } catch (InvalidArgumentException $e) {
        flash('erro', $e->getMessage());
        $notaSelecionada = (string) ($_POST['nota'] ?? '');
        $comentarioDigitado = (string) ($_POST['comentario'] ?? '');
    }
}

cabecalho('Avaliar apresentação');
?>
<div class="login-wrap">
  <div class="login-brand"><span class="eyebrow">Retorno da feira</span></div>
  <section class="login-card">
    <h1>Como foi a demonstração?</h1>
    <p>Sua avaliação ajuda a equipe a entender se a explicação do protótipo ficou clara.</p>
    <form method="post" action="avaliar.php">
      <?= csrf_campo() ?>
      <div class="honeypot" aria-hidden="true"><label>Não preencha este campo<input name="website" tabindex="-1" autocomplete="off"></label></div>
      <fieldset class="field" style="border:0;padding:0;margin:22px 0">
        <legend class="field-label">Avalie de 1 a 5</legend>
        <div class="rating-options">
          <?php foreach ([5 => 'Muito clara', 4 => 'Clara', 3 => 'Razoável', 2 => 'Pouco clara', 1 => 'Não ficou clara'] as $value => $label): ?>
            <label class="rating-option"><input type="radio" name="nota" value="<?= $value ?>" required <?= $notaSelecionada === (string) $value ? 'checked' : '' ?>><span><strong><?= $value ?>/5</strong><small><?= e($label) ?></small></span></label>
          <?php endforeach; ?>
        </div>
      </fieldset>
      <div class="field"><label for="comentario">Comentário (opcional)</label><textarea id="comentario" name="comentario" maxlength="500" placeholder="O que ficou claro? O que poderia ser explicado melhor?"><?= e($comentarioDigitado) ?></textarea></div>
      <button class="button button-dark" type="submit">Enviar avaliação</button>
      <p class="form-note">O formulário não pede identificação. O banco guarda apenas a nota, o comentário e a data.</p>
    </form>
  </section>
</div>
<?php rodape(); ?>
