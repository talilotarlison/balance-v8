-- Execute uma única vez, após bancos.sql e categorias.sql, no banco financeiro.
-- Use este arquivo para atualizar uma tabela fluxocaixa existente.
-- Os campos novos aceitam NULL para preservar os lançamentos já existentes.

USE `financeiro`;

ALTER TABLE `fluxocaixa`
  ADD COLUMN `banco_id` INT(11) DEFAULT NULL AFTER `data`,
  ADD COLUMN `categoria_id` INT(11) DEFAULT NULL AFTER `banco_id`,
  ADD COLUMN `data_lancamento` DATE DEFAULT NULL AFTER `categoria_id`,
  ADD KEY `idx_fluxocaixa_banco` (`banco_id`),
  ADD KEY `idx_fluxocaixa_categoria` (`categoria_id`),
  ADD KEY `idx_fluxocaixa_data_lancamento` (`data_lancamento`),
  ADD CONSTRAINT `fk_fluxocaixa_banco`
    FOREIGN KEY (`banco_id`) REFERENCES `bancos` (`id`)
    ON UPDATE CASCADE
    ON DELETE SET NULL,
  ADD CONSTRAINT `fk_fluxocaixa_categoria`
    FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`)
    ON UPDATE CASCADE
    ON DELETE SET NULL;
