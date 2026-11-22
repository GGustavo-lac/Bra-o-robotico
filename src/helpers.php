<?php
declare(strict_types=1);

function e(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function redirecionar(string $url): never
{
    header('Location: ' . $url, true, 303);
    exit;
}

function flash(string $tipo, string $mensagem): void
{
    $_SESSION['flash'] = ['tipo' => $tipo, 'mensagem' => $mensagem];
}

function csrf_token(): string
{
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return (string) $_SESSION['csrf'];
}

function csrf_campo(): string
{
    return '<input type="hidden" name="csrf" value="' . e(csrf_token()) . '">';
}

function csrf_validar(): void
{
    $esperado = (string) ($_SESSION['csrf'] ?? '');
    $recebido = (string) ($_POST['csrf'] ?? '');
    if ($esperado === '' || $recebido === '' || !hash_equals($esperado, $recebido)) {
        http_response_code(419);
        exit('Sua sessão expirou. Atualize a página e tente novamente.');
    }
}

function exigir_admin(): void
{
    header('Cache-Control: no-store');
    if (empty($_SESSION['admin_autenticado'])) {
        flash('erro', 'Entre na área administrativa para continuar.');
        redirecionar('login.php');
    }
}

function mostrar_flash(): void
{
    if (empty($_SESSION['flash'])) {
        return;
    }
    $f = $_SESSION['flash'];
    unset($_SESSION['flash']);
    $tipo = in_array($f['tipo'] ?? '', ['ok', 'erro'], true) ? $f['tipo'] : 'ok';
    echo '<div class="alert alert-' . e($tipo) . '" role="status">' . e($f['mensagem'] ?? '') . '</div>';
}

function cabecalho(string $titulo, string $ativo = '', bool $admin = false, string $base = ''): void
{
    $itens = [
        'inicio' => ['index.php', 'Visão geral'],
        'como' => ['como-funciona.php', 'Funcionamento'],
        'componentes' => ['componentes.php', 'Componentes'],
        'duvidas' => ['duvidas.php', 'Perguntas'],
    ];

    echo '<!doctype html><html lang="pt-BR"><head><meta charset="utf-8">';
    echo '<meta name="viewport" content="width=device-width, initial-scale=1">';
    echo '<meta name="theme-color" content="#111a23">';
    echo '<meta name="description" content="Documentação do protótipo de braço robótico com Arduino Uno, quatro potenciômetros e quatro servomotores.">';
    echo '<meta name="color-scheme" content="light">';
    echo '<title>' . e($titulo) . ' · Braço Robótico</title>';
    $basePath = $admin ? '../' : $base;
    echo '<link rel="stylesheet" href="' . e($basePath) . 'css/style.css?v=3">';
    echo '</head><body>';
    echo '<a class="skip-link" href="#conteudo">Pular para o conteúdo</a>';
    echo '<header class="site-header"><div class="header-inner">';
    echo '<a class="brand" href="' . e($basePath . 'index.php') . '" aria-label="Braço Robótico — página inicial">';
    echo '<span class="brand-mark" aria-hidden="true">BR.</span><span>BRAÇO<span class="brand-light">/</span>ROBÓTICO</span></a>';
    echo '<nav class="main-nav" aria-label="Navegação principal">';
    if ($admin) {
        $adminItens = ['painel' => ['index.php', 'Painel'], 'perguntas' => ['perguntas.php', 'Perguntas'], 'componentes' => ['componentes.php', 'Catálogo']];
        foreach ($adminItens as $key => [$url, $label]) {
            echo '<a href="' . e($url) . '" class="' . ($ativo === $key ? 'is-active' : '') . '">' . e($label) . '</a>';
        }
        echo '<form method="post" action="logout.php" class="nav-form">' . csrf_campo() . '<button class="nav-link" type="submit">Sair</button></form>';
    } else {
        foreach ($itens as $key => [$url, $label]) {
            echo '<a href="' . e($basePath . $url) . '" class="' . ($ativo === $key ? 'is-active' : '') . '">' . e($label) . '</a>';
        }
        echo '<a class="nav-admin" href="' . e($basePath . 'admin/login.php') . '">Área da equipe ↗</a>';
    }
    echo '</nav><button class="menu-toggle" type="button" aria-expanded="false" aria-controls="mobile-nav">Menu</button>';
    echo '</div><div id="mobile-nav" class="mobile-nav" hidden>';
    if ($admin) {
        echo '<a href="index.php">Painel</a><a href="perguntas.php">Perguntas</a><a href="componentes.php">Catálogo</a>';
        echo '<form method="post" action="logout.php">' . csrf_campo() . '<button type="submit">Sair</button></form>';
    } else {
        foreach ($itens as [$url, $label]) {
            echo '<a href="' . e($basePath . $url) . '">' . e($label) . '</a>';
        }
        echo '<a href="' . e($basePath . 'admin/login.php') . '">Área da equipe</a>';
    }
    echo '</div></header><main id="conteudo" class="page-shell">';
    mostrar_flash();
}

function rodape(bool $admin = false, string $base = ''): void
{
    echo '</main><footer class="site-footer"><div class="footer-inner">';
    echo '<span class="footer-brand">BRAÇO / ROBÓTICO</span>';
    echo '<p>Protótipo didático · Arduino Uno · Feira tecnológica</p>';
    echo '<span class="footer-note">Conteúdo informativo — não controla o hardware.</span>';
    echo '</div></footer>';
    $basePath = $admin ? '../' : $base;
    echo '<script src="' . e($basePath) . 'js/site.js?v=3" defer></script></body></html>';
}

function tam(string $texto): int
{
    return function_exists('mb_strlen') ? mb_strlen($texto, 'UTF-8') : strlen($texto);
}
