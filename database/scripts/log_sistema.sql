-- Execute uma única vez no banco financeiro.
-- Registra um evento por autenticação bem-sucedida.

USE `financeiro`;

CREATE TABLE IF NOT EXISTS `log_sistema` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `usuario_id` INT(11) NOT NULL,
  `data_hora` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_log_sistema_usuario_data` (`usuario_id`, `data_hora`),
  CONSTRAINT `fk_log_sistema_usuario`
    FOREIGN KEY (`usuario_id`) REFERENCES `login` (`id`)
    ON UPDATE CASCADE
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
