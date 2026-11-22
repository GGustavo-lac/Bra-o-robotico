<?php
require __DIR__ . '/../src/bootstrap.php';

use App\Services\ComponenteService;
use App\Services\ProjetoService;
use App\Services\PerguntaService;

$projeto = new ProjetoService();
$componentes = (new ComponenteService())->listar();
$articulacoes = $projeto->articulacoes();
$resumo = $projeto->resumoPublico();
$perguntas = array_slice((new PerguntaService())->publicadas(), 0, 3);
$servoQtd = 0;
$potQtd = 0;
foreach ($componentes as $componente) {
    if (stripos($componente['nome'], 'servomotor') !== false) $servoQtd = (int) $componente['quantidade'];
    if (stripos($componente['nome'], 'potenci') !== false) $potQtd = (int) $componente['quantidade'];
}

cabecalho('Visão geral', 'inicio');
?>
<section class="hero" aria-labelledby="hero-title">
  <div class="hero-copy">
    <span class="eyebrow">Engenharia aplicada · Protótipo didático</span>
    <h1 id="hero-title">BRAÇO <em>ROBÓTICO.</em></h1>
    <p>Um sistema de quatro movimentos comandado por controles analógicos. Os potenciômetros definem posições; o Arduino interpreta os sinais e aciona os servomotores.</p>
    <div class="hero-actions">
      <a class="button" href="como-funciona.php">Explorar o funcionamento <span aria-hidden="true">→</span></a>
      <a class="button button-outline" href="https://www.tinkercad.com/things/5DKR8zgIWQp-braco?sharecode=Ym4qbowYRLuL_HKy80PfK6PDOhIyJoJqjXYvjDnkGhg" target="_blank" rel="noopener noreferrer">Abrir circuito Tinkercad <span aria-hidden="true">↗</span></a>
    </div>
  </div>
  <div class="hero-visual">
    <img src="img/braco-robotico.jpeg" alt="Protótipo físico de braço robótico com estrutura articulada preta, servomotores azuis e garra mecânica" fetchpriority="high">
    <span class="photo-tag">Registro do protótipo físico</span>
  </div>
</section>

<section class="metrics" aria-label="Dados principais do protótipo">
  <div class="metric"><strong><?= count($articulacoes) ? str_pad((string) count($articulacoes), 2, '0', STR_PAD_LEFT) : '—' ?></strong><span>articulações cadastradas</span></div>
  <div class="metric"><strong><?= $potQtd ? str_pad((string) $potQtd, 2, '0', STR_PAD_LEFT) : '—' ?></strong><span>controles por potenciômetro</span></div>
  <div class="metric"><strong><?= $servoQtd ? str_pad((string) $servoQtd, 2, '0', STR_PAD_LEFT) : '—' ?></strong><span>servomotores no catálogo</span></div>
</section>

<section class="section intro-split">
  <div><span class="intro-index">01 / OBJETIVO</span></div>
  <div>
    <h2>Transformar uma entrada manual em movimento mecânico.</h2>
    <p>O projeto demonstra uma cadeia simples de automação: uma pessoa gira um potenciômetro, a placa lê a variação elétrica e o programa converte essa leitura em uma posição para o servo correspondente.</p>
    <p>O foco é tornar visível a relação entre eletrônica, programação e mecânica — da leitura analógica ao movimento da garra.</p>
    <a class="text-link" href="como-funciona.php">Ver a sequência de controle <span aria-hidden="true">→</span></a>
  </div>
</section>

<section class="section" aria-labelledby="process-title">
  <div class="section-heading">
    <div><span class="eyebrow">Lógica do sistema</span><h2 id="process-title">Quatro etapas. Um movimento.</h2></div>
    <p>O comando percorre o circuito eletrônico até chegar ao atuador mecânico. Cada parte tem uma responsabilidade definida.</p>
  </div>
  <div class="steps-grid">
    <article class="step-card"><span class="step-no">ETAPA 01</span><h3>Entrada</h3><p>O potenciômetro altera a tensão no pino central conforme é girado.</p></article>
    <article class="step-card"><span class="step-no">ETAPA 02</span><h3>Leitura</h3><p>O conversor analógico-digital do Arduino transforma a tensão em um valor numérico.</p></article>
    <article class="step-card"><span class="step-no">ETAPA 03</span><h3>Interpretação</h3><p>O código relaciona o valor lido a uma posição dentro dos limites definidos para o servo.</p></article>
    <article class="step-card"><span class="step-no">ETAPA 04</span><h3>Movimento</h3><p>O servomotor posiciona a articulação correspondente do braço.</p></article>
  </div>
</section>

<section class="section split-panel">
  <img src="img/arduino-protoboard.jpeg" alt="Registro da montagem eletrônica: placa Arduino Uno, protoboard, potenciômetro e cabos de conexão" loading="lazy">
  <div class="split-copy">
    <span class="eyebrow">Eletrônica de controle</span>
    <h2>O Arduino interpreta. O conjunto mecânico executa.</h2>
    <p>Na montagem fotografada, é possível identificar a placa Arduino Uno, uma protoboard, controles rotativos e jumpers. A placa funciona como unidade de leitura e controle; os servos produzem o movimento.</p>
    <a class="text-link" href="componentes.php">Consultar o inventário técnico <span aria-hidden="true">→</span></a>
  </div>
</section>

<section class="section" aria-labelledby="axes-title">
  <div class="section-heading">
    <div><span class="eyebrow">Mecânica</span><h2 id="axes-title">Os movimentos do braço.</h2></div>
    <p>Os movimentos cadastrados no projeto descrevem a função de cada junta e sua relação com o conjunto de controle.</p>
  </div>
  <div class="axis-grid">
    <?php foreach ($articulacoes as $a): ?>
      <article class="axis-card">
        <div class="axis-icon"><?= str_pad((string) $a['ordem'], 2, '0', STR_PAD_LEFT) ?></div>
        <div><h3><?= e($a['nome']) ?></h3><p><?= e($a['descricao']) ?></p></div>
        <div class="axis-meta"><span><?= e($a['servo'] ?: 'Servomotor') ?></span><span><?= e($a['entrada'] ?: 'Potenciômetro') ?></span></div>
      </article>
    <?php endforeach; ?>
  </div>
</section>

<section class="section circuit-panel">
  <div class="circuit-image"><img src="img/esquema-tinkercad.jpeg" alt="Fotografia do esquema simulado no Tinkercad com Arduino Uno, quatro potenciômetros, quatro servomotores e protoboard" loading="lazy"></div>
  <div class="circuit-copy">
    <span class="eyebrow">Circuito simulado</span>
    <h2>Do diagrama à montagem.</h2>
    <p>O esquema permite observar a organização geral dos controles e dos servomotores. A imagem é uma fotografia da tela; para verificar as ligações com mais precisão, abra o projeto no Tinkercad.</p>
    <a class="button" href="https://www.tinkercad.com/things/5DKR8zgIWQp-braco?sharecode=Ym4qbowYRLuL_HKy80PfK6PDOhIyJoJqjXYvjDnkGhg" target="_blank" rel="noopener noreferrer">Abrir simulação original <span aria-hidden="true">↗</span></a>
    <span class="caption">A simulação é uma referência de circuito; confira os componentes e a alimentação da montagem física separadamente.</span>
  </div>
</section>

<section class="section notice">
  <strong>Limite e segurança.</strong> Esta página é informativa: não envia comandos ao Arduino. Na montagem física, confirme a tensão e a corrente exigidas pelos servomotores. A documentação da biblioteca Servo alerta que vários servos podem exigir alimentação externa adequada e terra comum com o Arduino; não use a bateria de 9 V mostrada no diagrama como ligação direta aos servos sem um regulador e dimensionamento corretos.
</section>

<?php if ($perguntas): ?>
<section class="section">
  <div class="section-heading"><div><span class="eyebrow">Conhecimento compartilhado</span><h2>Perguntas já respondidas.</h2></div><a class="text-link" href="duvidas.php">Ver todas <span aria-hidden="true">→</span></a></div>
  <div class="faq-list">
    <?php foreach ($perguntas as $pergunta): ?>
      <details><summary><?= e($pergunta['pergunta']) ?></summary><p><?= e($pergunta['resposta']) ?></p></details>
    <?php endforeach; ?>
  </div>
</section>
<?php endif; ?>

<section class="section feedback-band">
  <div><h2>Ajude a melhorar a demonstração.</h2><p>Registre uma avaliação anônima da apresentação técnica.</p></div>
  <a class="button" href="avaliar.php">Avaliar o projeto <span aria-hidden="true">→</span></a>
</section>
<?php rodape(); ?>
