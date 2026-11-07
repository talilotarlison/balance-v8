-- Migração única da tabela links existente para o módulo de lembretes.
-- Faça backup antes de executar. Bancos legados precisam associar registros
-- com usuario_id NULL manualmente, após confirmar a quem pertencem.

USE `financeiro`;

-- Garante a chave primária necessária para gerar IDs automaticamente,
-- sem tentar criar uma segunda chave se o dump já a tiver.
SET @links_tem_chave_primaria := (
  SELECT COUNT(*)
  FROM information_schema.TABLE_CONSTRAINTS
  WHERE CONSTRAINT_SCHEMA = DATABASE()
    AND TABLE_NAME = 'links'
    AND CONSTRAINT_TYPE = 'PRIMARY KEY'
);
SET @links_sql_chave := IF(
  @links_tem_chave_primaria = 0,
  'ALTER TABLE `links` ADD PRIMARY KEY (`id`)',
  'SELECT 1'
);
PREPARE links_stmt_chave FROM @links_sql_chave;
EXECUTE links_stmt_chave;
DEALLOCATE PREPARE links_stmt_chave;

-- Converte os valores textuais legados para o estado de conclusão binário.
UPDATE `links`
SET `visto` = CASE
  WHEN LOWER(TRIM(`visto`)) IN ('1', 'true', 'sim', 'concluido', 'concluído') THEN '1'
  ELSE '0'
END;

ALTER TABLE `links`
  MODIFY COLUMN `id` INT(11) NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `visto` TINYINT(1) NOT NULL DEFAULT 0,
  ADD COLUMN `usuario_id` INT(11) DEFAULT NULL AFTER `id`,
  ADD COLUMN `email` VARCHAR(255) DEFAULT NULL AFTER `nome`,
  ADD COLUMN `telefone` VARCHAR(30) DEFAULT NULL AFTER `email`,
  ADD COLUMN `data_prevista` DATE DEFAULT NULL AFTER `telefone`,
  ADD COLUMN `descricao` TEXT DEFAULT NULL AFTER `nome`,
  ADD KEY `idx_links_usuario_estado_data` (`usuario_id`, `visto`, `data_prevista`),
  ADD CONSTRAINT `fk_links_usuario`
    FOREIGN KEY (`usuario_id`) REFERENCES `login` (`id`)
    ON UPDATE CASCADE
    ON DELETE CASCADE;

-- Para instalações que já executaram esta migração anteriormente, execute
-- somente esta instrução uma vez (não rode novamente o ALTER TABLE acima):
-- ALTER TABLE `links` ADD COLUMN `descricao` TEXT DEFAULT NULL AFTER `nome`;

ALTER TABLE `links`
  ADD COLUMN `descricao` TEXT DEFAULT NULL AFTER `nome`;