-- Execute no banco financeiro (DB_NAME configurado no .env).
-- Este script cria somente a tabela de categorias; não altera tabelas existentes.

USE `financeiro`;

CREATE TABLE IF NOT EXISTS `categorias` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `usuario_id` INT(11) NOT NULL,
  `nome` VARCHAR(120) NOT NULL,
  `tipo` ENUM('Entrada', 'Saida', 'Agendado') NOT NULL,
  `cor` CHAR(7) NOT NULL,
  `criado_em` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_categorias_usuario_tipo` (`usuario_id`, `tipo`),
  CONSTRAINT `fk_categorias_usuario`
    FOREIGN KEY (`usuario_id`) REFERENCES `login` (`id`)
    ON UPDATE CASCADE
    ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
