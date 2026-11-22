-- Esquema inicial limpo para MySQL 8 / MariaDB 10.4+.
-- Este arquivo NÃO apaga tabelas existentes. Em instalações antigas, faça backup
-- e execute database/migrate_v2.sql antes de importar database/seed.sql.
CREATE DATABASE IF NOT EXISTS braco_robotico
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE braco_robotico;
SET NAMES utf8mb4;

CREATE TABLE IF NOT EXISTS categorias (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  nome VARCHAR(40) NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY uq_categorias_nome (nome)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS componentes (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  categoria_id INT UNSIGNED NOT NULL,
  nome VARCHAR(80) NOT NULL,
  descricao VARCHAR(500) NOT NULL,
  quantidade TINYINT UNSIGNED NOT NULL DEFAULT 1,
  especificacao VARCHAR(120) NULL,
  criado_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  atualizado_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_componentes_nome (nome),
  KEY idx_componentes_categoria (categoria_id),
  CONSTRAINT ck_componentes_qtd CHECK (quantidade BETWEEN 1 AND 99),
  CONSTRAINT fk_componentes_categoria FOREIGN KEY (categoria_id)
    REFERENCES categorias (id) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB;

-- Cada articulação liga um movimento do braço às peças que o comandam.
CREATE TABLE IF NOT EXISTS articulacoes (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  nome VARCHAR(40) NOT NULL,
  descricao VARCHAR(250) NOT NULL,
  ordem TINYINT UNSIGNED NOT NULL,
  servo_componente_id INT UNSIGNED NULL,
  entrada_componente_id INT UNSIGNED NULL,
  PRIMARY KEY (id),
  UNIQUE KEY uq_articulacoes_nome (nome),
  UNIQUE KEY uq_articulacoes_ordem (ordem),
  CONSTRAINT fk_articulacoes_servo FOREIGN KEY (servo_componente_id)
    REFERENCES componentes (id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT fk_articulacoes_entrada FOREIGN KEY (entrada_componente_id)
    REFERENCES componentes (id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB;

-- Perguntas chegam sem exigir nome, e-mail ou cadastro do visitante.
-- Só respostas aprovadas pela equipe aparecem publicamente.
CREATE TABLE IF NOT EXISTS perguntas (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  componente_id INT UNSIGNED NULL,
  pergunta VARCHAR(500) NOT NULL,
  resposta VARCHAR(500) NULL,
  status ENUM('pendente', 'publicada') NOT NULL DEFAULT 'pendente',
  origem_legada_id INT UNSIGNED NULL,
  criado_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  respondida_em TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY uq_pergunta_legada (origem_legada_id),
  KEY idx_perguntas_status_data (status, criado_em),
  CONSTRAINT fk_perguntas_componente FOREIGN KEY (componente_id)
    REFERENCES componentes (id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT ck_pergunta_texto CHECK (CHAR_LENGTH(pergunta) BETWEEN 5 AND 500)
) ENGINE=InnoDB;

-- Avaliação anônima do estande: não armazena nome, e-mail ou IP.
CREATE TABLE IF NOT EXISTS avaliacoes (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  nota TINYINT UNSIGNED NOT NULL,
  comentario VARCHAR(500) NULL,
  criado_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY idx_avaliacoes_data (criado_em),
  CONSTRAINT ck_avaliacao_nota CHECK (nota BETWEEN 1 AND 5)
) ENGINE=InnoDB;
