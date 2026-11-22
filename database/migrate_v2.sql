-- MIGRAÇÃO DO MODELO ANTIGO PARA O MODELO DA FEIRA v2.
-- Faça um backup antes. Esta migração preserva componentes e textos das dúvidas,
-- mas remove nomes/e-mails de visitantes e a antiga tabela de cadastro pessoal.
-- Execute uma única vez em um banco que já tenha o schema antigo.
USE braco_robotico;
SET NAMES utf8mb4;

ALTER TABLE componentes
  ADD COLUMN especificacao VARCHAR(120) NULL;

CREATE TABLE IF NOT EXISTS articulacoes (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  nome VARCHAR(40) NOT NULL,
  descricao VARCHAR(250) NOT NULL,
  ordem TINYINT UNSIGNED NOT NULL,
  servo_componente_id INT UNSIGNED NULL,
  entrada_componente_id INT UNSIGNED NULL,
  PRIMARY KEY (id), UNIQUE KEY uq_articulacoes_nome (nome), UNIQUE KEY uq_articulacoes_ordem (ordem),
  CONSTRAINT fk_articulacoes_servo FOREIGN KEY (servo_componente_id) REFERENCES componentes(id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT fk_articulacoes_entrada FOREIGN KEY (entrada_componente_id) REFERENCES componentes(id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS perguntas (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  componente_id INT UNSIGNED NULL,
  pergunta VARCHAR(500) NOT NULL,
  resposta VARCHAR(500) NULL,
  status ENUM('pendente', 'publicada') NOT NULL DEFAULT 'pendente',
  origem_legada_id INT UNSIGNED NULL,
  criado_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  respondida_em TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (id), UNIQUE KEY uq_pergunta_legada (origem_legada_id),
  KEY idx_perguntas_status_data (status, criado_em),
  CONSTRAINT fk_perguntas_componente FOREIGN KEY (componente_id) REFERENCES componentes(id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT ck_pergunta_texto CHECK (CHAR_LENGTH(pergunta) BETWEEN 5 AND 500)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS avaliacoes (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  nota TINYINT UNSIGNED NOT NULL,
  comentario VARCHAR(500) NULL,
  criado_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id), KEY idx_avaliacoes_data (criado_em),
  CONSTRAINT ck_avaliacao_nota CHECK (nota BETWEEN 1 AND 5)
) ENGINE=InnoDB;

-- Copia as perguntas antigas sem copiar nome, e-mail ou instituição do visitante.
-- origem_legada_id torna a cópia repetível sem duplicar os registros.
INSERT INTO perguntas (componente_id, pergunta, resposta, status, origem_legada_id, criado_em, respondida_em)
SELECT d.componente_id, d.pergunta, d.resposta,
       CASE WHEN d.resposta IS NOT NULL AND TRIM(d.resposta) <> '' THEN 'publicada' ELSE 'pendente' END,
       d.id, d.criado_em,
       CASE WHEN d.resposta IS NOT NULL AND TRIM(d.resposta) <> '' THEN d.criado_em ELSE NULL END
FROM duvidas d
ON DUPLICATE KEY UPDATE
  pergunta = VALUES(pergunta), resposta = VALUES(resposta), status = VALUES(status);

-- As perguntas já foram preservadas; os dados pessoais deixam de ser necessários.
DROP TABLE IF EXISTS duvidas;
DROP TABLE IF EXISTS visitantes;
