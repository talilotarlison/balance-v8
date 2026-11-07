-- Execute uma única vez no banco financeiro após criar a tabela login.
-- Faça backup antes. Lançamentos existentes ficam com usuario_id NULL porque
-- não há uma atribuição confiável do proprietário nos dados legados.

USE `financeiro`;

ALTER TABLE `fluxocaixa`
  ADD COLUMN `usuario_id` INT(11) DEFAULT NULL AFTER `id`,
  ADD KEY `idx_fluxocaixa_usuario_id` (`usuario_id`),
  ADD CONSTRAINT `fk_fluxocaixa_usuario`
    FOREIGN KEY (`usuario_id`) REFERENCES `login` (`id`)
    ON UPDATE CASCADE
    ON DELETE SET NULL;
