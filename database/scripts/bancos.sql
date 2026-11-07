-- Execute no banco financeiro (DB_NAME configurado no .env).
-- Este script cria somente a tabela de bancos; ele não altera tabelas existentes.

USE `financeiro`;

CREATE TABLE IF NOT EXISTS `bancos` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `usuario_id` INT(11) NOT NULL,
  `nome` VARCHAR(120) NOT NULL,
  `tipo` VARCHAR(40) NOT NULL,
  `cor` CHAR(7) NOT NULL DEFAULT '#246BFE',
  `descricao` VARCHAR(1000) DEFAULT NULL,
  `criado_em` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_bancos_usuario` (`usuario_id`),
  CONSTRAINT `fk_bancos_usuario`
    FOREIGN KEY (`usuario_id`) REFERENCES `login` (`id`)
    ON UPDATE CASCADE
    ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
