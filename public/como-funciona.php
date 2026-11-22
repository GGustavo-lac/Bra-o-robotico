<?php
require __DIR__ . '/../src/bootstrap.php';

use App\Services\ProjetoService;

$articulacoes = (new ProjetoService())->articulacoes();
cabecalho('Funcionamento', 'como');
?>
<header class="page-heading">
  <span class="eyebrow">02 / Arquitetura do sistema</span>
  <h1 class="page-title">Como o sinal vira movimento.</h1>
  <p>O controle é feito localmente: potenciômetros fornecem entradas analógicas, o Arduino processa as leituras e os servomotores posicionam as partes móveis do braço.</p>
</header>

<section class="section intro-split" style="margin-top:0">
  <div><span class="intro-index">FLUXO DE SINAL</span></div>
  <div>
    <h2>Entrada analógica → processamento → atuação.</h2>
    <p>O pino central de cada potenciômetro entrega uma tensão variável. No Arduino Uno, a função <code>analogRead()</code> representa essa tensão por uma leitura de 10 bits, normalmente entre 0 e 1023.</p>
    <p>O programa converte essa leitura para uma posição adequada ao servo. O exemplo oficial Arduino “Knob” demonstra esse princípio com um potenciômetro e um servomotor; o protótipo amplia a mesma lógica para quatro canais.</p>
    <a class="text-link" href="https://docs.arduino.cc/learn/electronics/servo-motors" target="_blank" rel="noopener noreferrer">Consultar o tutorial oficial de servos <span aria-hidden="true">↗</span></a>
  </div>
</section>

<section class="section" aria-labelledby="sequence-title">
  <div class="section-heading"><div><span class="eyebrow">Sequência operacional</span><h2 id="sequence-title">O que acontece em cada etapa.</h2></div><p>A sequência descreve o princípio de funcionamento. Os pinos exatos devem ser conferidos no código carregado e no esquema editável do projeto.</p></div>
  <div class="steps-grid">
    <article class="step-card"><span class="step-no">01 / SENSOR</span><h3>Girar o potenciômetro</h3><p>O eixo altera a resistência entre seus terminais externos e o terminal central, mudando a tensão de saída.</p></article>
    <article class="step-card"><span class="step-no">02 / ADC</span><h3>Converter a leitura</h3><p>O Arduino Uno converte a tensão de entrada em um número de 0 a 1023 no padrão de 10 bits.</p></article>
    <article class="step-card"><span class="step-no">03 / CÓDIGO</span><h3>Mapear a posição</h3><p>O programa associa a leitura analógica ao intervalo de movimento permitido pelo servo, com limites definidos pela montagem.</p></article>
    <article class="step-card"><span class="step-no">04 / ATUADOR</span><h3>Posicionar o eixo</h3><p>A biblioteca Servo envia o sinal de controle e o servomotor tenta alcançar a posição solicitada.</p></article>
  </div>
</section>

<section class="section" aria-labelledby="joint-title">
  <div class="section-heading"><div><span class="eyebrow">04 movimentos</span><h2 id="joint-title">Articulações cadastradas.</h2></div><p>A lista é alimentada pelo banco de dados. A relação de pinos e a faixa mecânica de cada servo não são presumidas aqui.</p></div>
  <div class="axis-grid">
    <?php foreach ($articulacoes as $a): ?>
      <article class="axis-card">
        <div class="axis-icon"><?= str_pad((string) $a['ordem'], 2, '0', STR_PAD_LEFT) ?></div>
        <div><h3><?= e($a['nome']) ?></h3><p><?= e($a['descricao']) ?></p></div>
        <div class="axis-meta"><span><?= e($a['servo'] ?: 'Servomotor não associado') ?></span><span><?= e($a['entrada'] ?: 'Entrada não associada') ?></span></div>
      </article>
    <?php endforeach; ?>
  </div>
</section>

<section class="section split-panel">
  <img src="img/esquema-tinkercad.jpeg" alt="Diagrama do circuito simulado no Tinkercad com Arduino Uno e quatro conjuntos de potenciômetro e servomotor" loading="lazy">
  <div class="split-copy">
    <span class="eyebrow">Esquema eletrônico</span>
    <h2>Consulte as ligações no arquivo original.</h2>
    <p>A fotografia permite reconhecer quatro potenciômetros, quatro servomotores, Arduino Uno, protoboard e alimentação representada no simulador. Para confirmar os pinos, amplie o circuito diretamente no Tinkercad.</p>
    <a class="button" href="https://www.tinkercad.com/things/5DKR8zgIWQp-braco?sharecode=Ym4qbowYRLuL_HKy80PfK6PDOhIyJoJqjXYvjDnkGhg" target="_blank" rel="noopener noreferrer">Abrir circuito Tinkercad ↗</a>
  </div>
</section>

<section class="section notice">
  <strong>Alimentação dos servos:</strong> quatro servomotores podem demandar corrente maior do que a placa consegue fornecer pelo pino de 5 V. Use uma fonte regulada dimensionada para os motores e mantenha o GND dessa fonte em comum com o GND do Arduino. Confira tensão, corrente de pico e polaridade na ficha dos servos antes de energizar. A bateria de 9 V desenhada na simulação não deve ser conectada diretamente aos servos.
</section>

<section class="section" aria-labelledby="refs-title">
  <div class="section-heading"><div><span class="eyebrow">Pesquisa e documentação</span><h2 id="refs-title">Referências para aprofundar.</h2></div><p>Materiais externos consultados para validar o princípio de leitura analógica, controle de servos e arquitetura de braços com quatro eixos. Eles não são o código original deste protótipo.</p></div>
  <div class="reference-list">
    <article class="reference-item"><span class="reference-number">01</span><div><h3>Arduino — Servo Motor Basics</h3><p>Documentação oficial com circuito “Knob”, leitura do potenciômetro e posicionamento de servo.</p></div><a href="https://docs.arduino.cc/learn/electronics/servo-motors" target="_blank" rel="noopener noreferrer">Documentação ↗</a></article>
    <article class="reference-item"><span class="reference-number">02</span><div><h3>Arduino Servo Library — exemplo Knob</h3><p>Código de referência com <code>analogRead()</code>, <code>map()</code> e <code>Servo.write()</code>.</p></div><a href="https://github.com/arduino-libraries/Servo/blob/master/examples/Knob/Knob.ino" target="_blank" rel="noopener noreferrer">Código no GitHub ↗</a></article>
    <article class="reference-item"><span class="reference-number">03</span><div><h3>4-Axis Robotic Arm Control — GitHub</h3><p>Repositório de exemplo com quatro servos e quatro potenciômetros; útil para comparar a arquitetura de controle.</p></div><a href="https://github.com/toghrul-guluzade/Arduino-4-Axis-Robotic-Arm-Control" target="_blank" rel="noopener noreferrer">Repositório ↗</a></article>
    <article class="reference-item"><span class="reference-number">04</span><div><h3>Robot_arm_arduino_uno — GitHub</h3><p>Outro exemplo de braço controlado por quatro potenciômetros; inclui armazenamento e repetição de posições.</p></div><a href="https://github.com/hanusekk/Robot_arm_arduino_uno" target="_blank" rel="noopener noreferrer">Repositório ↗</a></article>
    <article class="reference-item"><span class="reference-number">05</span><div><h3>Mert Arduino — 4DOF com quatro potenciômetros</h3><p>Vídeo de montagem e controle de um braço de quatro eixos com Arduino Uno e quatro potenciômetros.</p></div><a href="https://www.youtube.com/watch?v=bYcbU8aQ2Js" target="_blank" rel="noopener noreferrer">Assistir no YouTube ↗</a></article>
    <article class="reference-item"><span class="reference-number">06</span><div><h3>Lee Curiosity — braço 4 DOF com servos</h3><p>Vídeo de 2024 sobre quatro servomotores e entradas analógicas por joysticks, que contêm potenciômetros.</p></div><a href="https://www.youtube.com/watch?v=o89WD4vNm5U" target="_blank" rel="noopener noreferrer">Assistir no YouTube ↗</a></article>
  </div>
  <p class="caption">Os repositórios e vídeos são referências de estudo independentes. Não copie pinos, alimentação ou código sem comparar com o circuito e o firmware efetivamente usados neste projeto.</p>
</section>
<?php rodape(); ?>
