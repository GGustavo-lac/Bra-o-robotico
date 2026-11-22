<?php
require __DIR__ . '/../../src/bootstrap.php';

$config = (require __DIR__ . '/../../config/config.php')['admin'];
$configurado = $config['user'] !== '' && $config['password_hash'] !== '';
$bloqueadoAte = (int) ($_SESSION['login_bloqueado_ate'] ?? 0);
$erro = '';

if (!empty($_SESSION['admin_autenticado'])) {
    redirecionar('index.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_validar();
    if (time() < $bloqueadoAte) {
        $erro = 'Muitas tentativas. Aguarde alguns minutos antes de tentar novamente.';
    } elseif (!$configurado) {
        $erro = 'A área administrativa ainda não foi configurada no servidor.';
    } else {
        $usuario = trim((string) ($_POST['usuario'] ?? ''));
        $senha = (string) ($_POST['senha'] ?? '');
        if (hash_equals($config['user'], $usuario) && password_verify($senha, $config['password_hash'])) {
            session_regenerate_id(true);
            $_SESSION['admin_autenticado'] = true;
            $_SESSION['csrf'] = bin2hex(random_bytes(32));
            unset($_SESSION['login_tentativas'], $_SESSION['login_bloqueado_ate']);
            redirecionar('index.php');
        }
        $tentativas = (int) ($_SESSION['login_tentativas'] ?? 0) + 1;
        $_SESSION['login_tentativas'] = $tentativas;
        if ($tentativas >= 5) {
            $_SESSION['login_tentativas'] = 0;
            $_SESSION['login_bloqueado_ate'] = time() + 300;
        }
        $erro = 'Usuário ou senha inválidos.';
    }
}

cabecalho('Área da equipe', '', false, '../');
?>
<div class="login-wrap">
  <div class="login-brand"><span class="eyebrow">Acesso restrito</span></div>
  <section class="login-card">
    <h1>Área da equipe.</h1>
    <p>Entre para organizar o catálogo e revisar as perguntas enviadas pelos visitantes.</p>
    <?php if ($erro !== ''): ?><div class="alert alert-erro" role="alert"><?= e($erro) ?></div><?php endif; ?>
    <?php if (!$configurado): ?>
      <div class="notice">Configure <code>ADMIN_USER</code> e <code>ADMIN_PASSWORD_HASH</code> nas variáveis de ambiente do servidor. O painel permanece bloqueado até isso ser feito.</div>
    <?php endif; ?>
    <form method="post" action="login.php">
      <?= csrf_campo() ?>
      <div class="field"><label for="usuario">Usuário</label><input id="usuario" name="usuario" autocomplete="username" maxlength="80" required <?= !$configurado ? 'disabled' : '' ?>></div>
      <div class="field"><label for="senha">Senha</label><input id="senha" name="senha" type="password" autocomplete="current-password" maxlength="1024" required <?= !$configurado ? 'disabled' : '' ?>></div>
      <button class="button button-dark" type="submit" <?= !$configurado ? 'disabled' : '' ?>>Entrar no painel</button>
    </form>
  </section>
</div>
<?php rodape(false, '../'); ?>
