-- Dados técnicos reais descritos no README e nas fotos do projeto.
-- A carga é idempotente: pode ser executada novamente sem criar duplicatas.
USE braco_robotico;
SET NAMES utf8mb4;

INSERT IGNORE INTO categorias (nome) VALUES
  ('Controle'), ('Entrada'), ('Movimento'), ('Alimentação'), ('Conexão'), ('Estrutura');

INSERT INTO componentes (categoria_id, nome, descricao, quantidade, especificacao)
SELECT c.id, dados.nome, dados.descricao, dados.quantidade, dados.especificacao
FROM (
  SELECT 'Controle' categoria, 'Arduino Uno' nome,
    'Placa que lê os potenciômetros e envia os comandos aos servomotores.' descricao,
    1 quantidade, 'Placa microcontroladora' especificacao
  UNION ALL SELECT 'Movimento', 'Servomotor SG90',
    'Atuador que posiciona cada articulação do braço conforme o sinal recebido.',
    4, 'Faixa de posição indicada no projeto: 0° a 180°'
  UNION ALL SELECT 'Entrada', 'Potenciômetro',
    'Controle giratório que varia a tensão lida pelo Arduino para comandar uma articulação.',
    4, 'Entrada analógica'
  UNION ALL SELECT 'Conexão', 'Protoboard',
    'Placa de montagem usada para distribuir conexões sem solda.',
    1, NULL
  UNION ALL SELECT 'Conexão', 'Kit de jumpers',
    'Conjunto de fios usados nas ligações entre a placa, os controles e os servomotores.',
    1, NULL
  UNION ALL SELECT 'Alimentação', 'Bateria 9V',
    'Fonte de alimentação representada no esquema de simulação do circuito.',
    1, 'Componente representado na simulação do Tinkercad'
  UNION ALL SELECT 'Alimentação', 'Capacitor eletrolítico',
    'Componente presente no esquema para ajudar na estabilização da alimentação.',
    1, NULL
  UNION ALL SELECT 'Estrutura', 'Estrutura do braço',
    'Peças mecânicas que formam a base, os segmentos e a garra do protótipo.',
    1, 'Base fixada em placa de madeira'
) AS dados
JOIN categorias c ON c.nome = dados.categoria
ON DUPLICATE KEY UPDATE
  categoria_id = VALUES(categoria_id), descricao = VALUES(descricao),
  quantidade = VALUES(quantidade), especificacao = VALUES(especificacao);

INSERT INTO articulacoes (nome, descricao, ordem, servo_componente_id, entrada_componente_id)
SELECT dados.nome, dados.descricao, dados.ordem, servo.id, entrada.id
FROM (
  SELECT 'Base' nome, 'Gira o conjunto do braço para a esquerda e para a direita.' descricao, 1 ordem
  UNION ALL SELECT 'Ombro', 'Levanta e abaixa o primeiro segmento do braço.', 2
  UNION ALL SELECT 'Cotovelo', 'Dobra e estende o segmento intermediário.', 3
  UNION ALL SELECT 'Garra', 'Abre e fecha para segurar ou soltar um objeto.', 4
) dados
JOIN componentes servo ON servo.nome = 'Servomotor SG90'
JOIN componentes entrada ON entrada.nome = 'Potenciômetro'
ON DUPLICATE KEY UPDATE
  descricao = VALUES(descricao), servo_componente_id = VALUES(servo_componente_id),
  entrada_componente_id = VALUES(entrada_componente_id);

INSERT INTO perguntas (componente_id, pergunta, resposta, status)
SELECT c.id, 'O site controla o braço robótico?',
  'Não. O site apresenta o projeto e reúne informações. O protótipo físico é comandado pelos potenciômetros ligados ao Arduino.',
  'publicada'
FROM componentes c WHERE c.nome = 'Arduino Uno'
  AND NOT EXISTS (SELECT 1 FROM perguntas p WHERE p.pergunta = 'O site controla o braço robótico?')
LIMIT 1;

INSERT INTO perguntas (componente_id, pergunta, resposta, status)
SELECT c.id, 'Por que o servomotor não dá uma volta completa?',
  'O servomotor usado no projeto trabalha com posições limitadas, indicadas no material como uma faixa de 0° a 180°. Isso permite posicionar as articulações.',
  'publicada'
FROM componentes c WHERE c.nome = 'Servomotor SG90'
  AND NOT EXISTS (SELECT 1 FROM perguntas p WHERE p.pergunta = 'Por que o servomotor não dá uma volta completa?')
LIMIT 1;

INSERT INTO perguntas (componente_id, pergunta, resposta, status)
SELECT c.id, 'Para que servem os quatro potenciômetros?',
  'Cada potenciômetro funciona como um controle manual. A posição dele altera a leitura analógica do Arduino e comanda uma das quatro articulações.',
  'publicada'
FROM componentes c WHERE c.nome = 'Potenciômetro'
  AND NOT EXISTS (SELECT 1 FROM perguntas p WHERE p.pergunta = 'Para que servem os quatro potenciômetros?')
LIMIT 1;
