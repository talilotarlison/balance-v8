-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 15/04/2025 às 02:13
-- Versão do servidor: 10.4.28-MariaDB
-- Versão do PHP: 8.2.4
-- Pré-requisitos: o banco financeiro e as tabelas login, bancos e categorias devem existir.
-- Execute bancos.sql e categorias.sql antes deste arquivo. Este dump cria fluxocaixa.
-- Para uma tabela existente, use lancamentos_campos.sql e lancamentos_usuario.sql.
-- Este arquivo já inclui os novos campos; não execute essas migrações depois dele.

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `financeiro`
--

USE `financeiro`;

-- --------------------------------------------------------

--
-- Estrutura para tabela `fluxocaixa`
--

CREATE TABLE `fluxocaixa` (
  `id` int(11) NOT NULL,
  `tipo` varchar(255) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `preco` float NOT NULL,
  `disc` varchar(255) NOT NULL,
  `data` tinytext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

ALTER TABLE `fluxocaixa`
  ADD COLUMN `usuario_id` INT(11) DEFAULT NULL AFTER `id`,
  ADD COLUMN `banco_id` INT(11) DEFAULT NULL AFTER `data`,
  ADD COLUMN `categoria_id` INT(11) DEFAULT NULL AFTER `banco_id`,
  ADD COLUMN `data_lancamento` DATE DEFAULT NULL AFTER `categoria_id`,
  ADD KEY `idx_fluxocaixa_usuario_id` (`usuario_id`),
  ADD KEY `idx_fluxocaixa_banco` (`banco_id`),
  ADD KEY `idx_fluxocaixa_categoria` (`categoria_id`),
  ADD KEY `idx_fluxocaixa_data_lancamento` (`data_lancamento`),
  ADD CONSTRAINT `fk_fluxocaixa_usuario`
    FOREIGN KEY (`usuario_id`) REFERENCES `login` (`id`)
    ON UPDATE CASCADE
    ON DELETE SET NULL,
  ADD CONSTRAINT `fk_fluxocaixa_banco`
    FOREIGN KEY (`banco_id`) REFERENCES `bancos` (`id`)
    ON UPDATE CASCADE
    ON DELETE SET NULL,
  ADD CONSTRAINT `fk_fluxocaixa_categoria`
    FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`)
    ON UPDATE CASCADE
    ON DELETE SET NULL;

--
-- Despejando dados para a tabela `fluxocaixa`
--

INSERT INTO `fluxocaixa` (`id`, `usuario_id`, `tipo`, `nome`, `preco`, `disc`, `data`, `banco_id`, `categoria_id`, `data_lancamento`) VALUES
(149, NULL, 'Agendado', 'Talilo', 100, 'Gdgs', '19/05/2024 15:28:35', NULL, NULL, NULL),
(150, NULL, 'Saida', 'Preco produto', -24, 'Rw', '2024-12-8 1:21:20.027', NULL, NULL, NULL),
(175, NULL, 'Entrada', 'Pao', 2, 'Pao com ovo', '2024-12-8 1:21:20.027', NULL, NULL, NULL),
(176, NULL, 'Agendado', 'Biscoito', 44, 'Biscode pacote branco', '2024-12-8 1:26:25.318', NULL, NULL, NULL),
(199, NULL, 'Saida', 'Ssfs', -24, 'Ffs', '29/01/2025 00:29:10', NULL, NULL, NULL),
(201, NULL, 'Agendado', 'Dfsdsf', 232313, 'Sfagdggfsf', '07/02/2025 17:12:41', NULL, NULL, NULL),
(203, NULL, 'Entrada', 'Bobo', 32, 'Bolosa', '12/04/2025 15:02:08', NULL, NULL, NULL),
(210, NULL, 'Agendado', 'Juju', 6, 'U', '14/04/2025 11:11:02', NULL, NULL, NULL),
(214, NULL, 'Agendado', 'Ptyt', 5, 'G', '14/04/2025 16:39:18', NULL, NULL, NULL);

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `fluxocaixa`
--
ALTER TABLE `fluxocaixa`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `fluxocaixa`
--
ALTER TABLE `fluxocaixa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=215;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
