# Pesquisa aplicada ao site — Braço Robótico

Revisão de conteúdo, interface e qualidade realizada em 22 de novembro de 2026.

## 1. Princípio de apresentação

O site deve documentar um protótipo real de feira tecnológica. A interface não deve simular um painel industrial, criar métricas sem fonte nem usar cartões decorativos para preencher espaço. A navegação foi organizada em quatro perguntas que um visitante provavelmente terá:

1. O que é o projeto?
2. Como o comando chega ao movimento?
3. Quais peças foram cadastradas?
4. Onde consultar o circuito e tirar dúvidas?

A linguagem visual foi alterada para uma direção editorial/industrial: fundo claro, texto grafite, azul técnico e laranja de sinalização, tipografia do sistema, alinhamento em grelha, tabelas para inventário e separadores tipográficos. Não exige bibliotecas externas, fontes remotas, frameworks ou build step.

## 2. Evidências usadas do protótipo enviado

- Foto do braço físico: estrutura articulada escura, base apoiada em placa, garra e servomotores azuis.
- Foto da eletrônica: placa Arduino Uno, protoboard, fios e potenciômetro visível.
- Captura do Tinkercad: Arduino Uno, quatro controles rotativos e quatro servomotores na simulação; também aparece uma bateria de 9 V no esquema.
- Link fornecido pelo responsável: projeto compartilhado no Tinkercad.

A captura do circuito é uma fotografia de uma tela, não o arquivo CAD nativo. Por isso o site direciona ao Tinkercad para confirmação dos pinos. O firmware original (.ino) não veio no pacote; pinos e limites físicos individuais não são declarados como fato.

## 3. Referências técnicas consultadas

### Documentação oficial

- Arduino — Servo Motor Basics: demonstra o circuito Knob, no qual um potenciômetro é lido e usado para posicionar um servo. https://docs.arduino.cc/learn/electronics/servo-motors
- Arduino — Read Analog Voltage: descreve a conversão analógica para valores digitais de 0 a 1023 em ADC de 10 bits. https://docs.arduino.cc/built-in-examples/basics/ReadAnalogVoltage
- Arduino Servo library — README e exemplos: documentação da biblioteca e cuidados de alimentação dos servomotores. https://github.com/arduino-libraries/Servo
- Arduino Servo library — exemplo Knob: https://github.com/arduino-libraries/Servo/blob/master/examples/Knob/Knob.ino

### Repositórios GitHub para comparação

- toghrul-guluzade/Arduino-4-Axis-Robotic-Arm-Control: demonstra quatro servos associados a quatro potenciômetros. https://github.com/toghrul-guluzade/Arduino-4-Axis-Robotic-Arm-Control
- hanusekk/Robot_arm_arduino_uno: exemplo de braço comandado por quatro potenciômetros, incluindo gravação e repetição de posições. https://github.com/hanusekk/Robot_arm_arduino_uno
- maker101io/roboticArmVacuum: braço com potenciômetros, servos e acessórios adicionais; útil para observar organização de conexões, mas não é uma cópia deste protótipo. https://github.com/maker101io/roboticArmVacuum

Esses repositórios são referências externas, não são o código do projeto escolar e não devem ser tratados como fonte dos pinos usados no circuito enviado.

### Vídeos YouTube

- Mert Arduino / Maker 101 — DIY Arduino Robot Arm Kit, controle 4DOF com quatro potenciômetros: https://www.youtube.com/watch?v=bYcbU8aQ2Js
- Lee Curiosity — Build a 4 DOF Robotic Arm with Arduino, quatro servos e controle por joysticks (que contêm potenciômetros): https://www.youtube.com/watch?v=o89WD4vNm5U

Os vídeos são material de estudo e comparação, não documentação do protótipo físico enviado.

## 4. Diretrizes de interface e acessibilidade

- WCAG 2.2: contraste mínimo de 4,5:1 para texto normal (critério 1.4.3); informação não depende apenas de cor. https://www.w3.org/TR/WCAG22/
- MDN — prefers-reduced-motion: reduz movimento não essencial para usuários que pedem menos animação no sistema operacional. https://developer.mozilla.org/en-US/docs/Web/CSS/Guides/Media_queries/Using_for_accessibility
- MDN — container queries: referência para componentes que respondem ao tamanho do próprio espaço disponível. https://developer.mozilla.org/en-US/docs/Web/CSS/Guides/Containment/Container_queries

Aplicado: foco de teclado visível, pular para conteúdo, rótulos explícitos, tabelas com cabeçalho, menu móvel com `aria-expanded`, redução de movimento, cores com bom contraste e filtros sem dependência de JavaScript externo.

## 5. Segurança e limites técnicos

- PHP PDO: parâmetros preparados para valores vindos do usuário. https://www.php.net/manual/en/pdo.prepare.php
- OWASP Authentication Cheat Sheet: boas práticas de autenticação e proteção da sessão. https://cheatsheetseries.owasp.org/cheatsheets/Authentication_Cheat_Sheet.html
- Servos: a biblioteca Servo alerta que servos consomem corrente significativa e que mais de um ou dois podem exigir fonte separada. A fonte deve ser dimensionada conforme os modelos utilizados e seu GND deve ser comum ao Arduino. https://github.com/arduino-libraries/Servo

O desenho do Tinkercad mostra uma bateria de 9 V. O site não recomenda ligá-la diretamente aos servos. A alimentação física precisa ser conferida com as especificações dos motores e a montagem real.

## 6. Escopo de validação

O pacote foi validado estaticamente com `php -l` e `node --check`; a integridade dos links relativos e arquivos de imagem foi conferida. Não foi possível executar testes de integração com o banco MySQL do usuário nem teste de navegação no servidor XAMPP dele neste ambiente.
