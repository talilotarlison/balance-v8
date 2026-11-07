-- Execute uma única vez no banco financeiro para habilitar categorias de agendados.
-- Faça backup antes de executar em uma base com dados.

USE `financeiro`;

ALTER TABLE `categorias`
  MODIFY COLUMN `tipo` ENUM('Entrada', 'Saida', 'Agendado') NOT NULL;

-- Em modo SQL não estrito, tentativas anteriores de salvar Agendado com o ENUM
-- antigo podem ter sido gravadas como string vazia. Após confirmar que esses
-- registros são as categorias agendadas tentadas, recupere-os:
UPDATE `categorias`
  SET `tipo` = 'Agendado'
  WHERE `tipo` = '';
