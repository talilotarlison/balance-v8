-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 07/11/2026 às 23:43
-- Versão do servidor: 10.4.28-MariaDB
-- Versão do PHP: 8.2.4

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

-- --------------------------------------------------------

--
-- Estrutura para tabela `bancos`
--

CREATE TABLE `bancos` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `nome` varchar(120) NOT NULL,
  `tipo` varchar(40) NOT NULL,
  `cor` char(7) NOT NULL DEFAULT '#246BFE',
  `descricao` varchar(1000) DEFAULT NULL,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `bancos`
--

INSERT INTO `bancos` (`id`, `usuario_id`, `nome`, `tipo`, `cor`, `descricao`, `criado_em`) VALUES
(1, 25, 'Nubank', 'Conta corrente', '#fa2afe', NULL, '2026-11-07 11:19:38'),
(2, 25, 'Dinheiro', 'Dinheiro', '#509568', NULL, '2026-11-07 11:22:04'),
(3, 25, 'Caixa', 'Poupança', '#246bfe', NULL, '2026-11-07 11:22:14');

-- --------------------------------------------------------

--
-- Estrutura para tabela `categorias`
--

CREATE TABLE `categorias` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `nome` varchar(120) NOT NULL,
  `tipo` enum('Entrada','Saida','Agendado') NOT NULL,
  `cor` char(7) NOT NULL,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `categorias`
--

INSERT INTO `categorias` (`id`, `usuario_id`, `nome`, `tipo`, `cor`, `criado_em`) VALUES
(1, 25, 'Salario', 'Entrada', '#138A64', '2026-11-07 11:40:26'),
(3, 25, 'Saúde', 'Saida', '#D94F61', '2026-11-07 11:40:51'),
(4, 25, 'Investimentos', 'Entrada', '#138A64', '2026-11-07 11:41:06'),
(5, 25, 'Juros', 'Entrada', '#138A64', '2026-11-07 11:41:10'),
(6, 25, 'Estudos', 'Saida', '#D94F61', '2026-11-07 19:29:27'),
(7, 25, 'Contas', 'Agendado', '#E8B84F', '2026-11-07 19:36:34'),
(8, 25, 'Juros', 'Agendado', '#E8B84F', '2026-11-07 19:37:24'),
(9, 25, 'Aluguel', 'Agendado', '#E8B84F', '2026-11-07 19:44:20'),
(10, 25, 'Lanche', 'Saida', '#D94F61', '2026-11-07 20:31:16');

-- --------------------------------------------------------

--
-- Estrutura para tabela `fluxocaixa`
--

CREATE TABLE `fluxocaixa` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `tipo` varchar(255) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `preco` float NOT NULL,
  `disc` varchar(255) NOT NULL,
  `data` tinytext DEFAULT NULL,
  `banco_id` int(11) DEFAULT NULL,
  `categoria_id` int(11) DEFAULT NULL,
  `data_lancamento` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Despejando dados para a tabela `fluxocaixa`
--

INSERT INTO `fluxocaixa` (`id`, `usuario_id`, `tipo`, `nome`, `preco`, `disc`, `data`, `banco_id`, `categoria_id`, `data_lancamento`) VALUES
(149, 25, 'Agendado', 'Mensalidade Faculdade', 100, 'Mensalidade Faculdade', '19/05/2024 15:28:35', 2, 7, '2026-11-10'),
(150, 25, 'Saida', 'Preco produto', -24.6, 'Supermecado', '2024-12-8 1:21:20.027', 3, 3, '2026-11-01'),
(175, 25, 'Saida', 'Pao', -2, 'Pao com ovo', '2024-12-8 1:21:20.027', 2, 10, '2026-11-14'),
(176, 25, 'Saida', 'Biscoito', -44, 'Biscode pacote', '2024-12-8 1:26:25.318', 2, 10, '2026-11-04'),
(199, 25, 'Saida', 'Todinho', -28, 'Compra', '29/01/2025 00:29:10', 2, 3, '2026-11-03'),
(201, 25, 'Agendado', 'Dfsdsf', 25, 'Sfagdggfsf', '07/02/2025 17:12:41', 2, 9, '2026-11-03'),
(203, 25, 'Entrada', 'Venda Bolo', 32.9, 'Bolo chocolate', '12/04/2025 15:02:08', 3, 5, '2026-11-11'),
(210, 25, 'Agendado', 'Joana Conta', 6, 'Recebiemento', '14/04/2025 11:11:02', 2, 9, '2026-11-06'),
(216, 25, 'Entrada', 'Caixa', 33, 'Caixa Inicial', '04/04/2026 11:23:06', 3, 5, '2026-11-06'),
(217, 25, 'Entrada', 'Salario farmacia', 800, 'Pagamento finalizado', '07/11/2026 09:04:08', 1, 1, '2026-11-07'),
(218, 25, 'Entrada', 'Juros cdi', 478, 'Juros acumuluado', '07/11/2026 18:37:05', 1, 5, '2026-11-07');

-- --------------------------------------------------------

--
-- Estrutura para tabela `links`
--

CREATE TABLE `links` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `nome` varchar(255) NOT NULL,
  `descricao` text DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `telefone` varchar(30) DEFAULT NULL,
  `data_prevista` date DEFAULT NULL,
  `url` varchar(255) NOT NULL,
  `visto` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Despejando dados para a tabela `links`
--

INSERT INTO `links` (`id`, `usuario_id`, `nome`, `descricao`, `email`, `telefone`, `data_prevista`, `url`, `visto`) VALUES
(32, 25, 'Negociar Casa', NULL, 'contato@contato.com', NULL, '2026-12-28', 'https://www.honda.com.br/', 1),
(33, 25, 'Compra Escritorio', NULL, 'contato@contato.com', NULL, '2026-12-30', 'https://www.honda.com.br/', 1),
(34, 25, 'Reuniao de Concelho', NULL, 'contato@contato.com', NULL, '2026-12-04', 'https://www.honda.com.br/', 1),
(35, 25, 'Gerar Relatorio', NULL, 'contato@contato.com', NULL, '2026-12-04', 'https://www.honda.com.br/', 0),
(37, 25, 'Reuniao Time de vendas', 'Via Meet', 'contato@contato.com', NULL, '2026-12-04', 'https://www.honda.com.br/', 0),
(38, 25, 'Licenciamento Honda', 'Assinar documentação', 'honda@gmail.com', '8691345785', '2026-12-04', 'https://www.honda.com.br/', 0);

-- --------------------------------------------------------

--
-- Estrutura para tabela `login`
--

CREATE TABLE `login` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `data` tinytext NOT NULL,
  `tema` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Despejando dados para a tabela `login`
--

INSERT INTO `login` (`id`, `nome`, `email`, `senha`, `data`, `tema`) VALUES
(1, 'Talilo', 'meu@meu.com', 'd41d8cd98f00b204e9800998ecf8427e', '06/04/2024 21:53:30', 0),
(2, 'Talilo', 'meu@meu.com', 'd41d8cd98f00b204e9800998ecf8427e', '06/04/2024 21:56:30', 0),
(8, 'Talilo', 'talilotarlison@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', '06/04/2024 23:53:01', 0),
(12, 'Talilo', 'talilo@gmail.com', '5a4bdf6e5f547b9481af07367e1bff94', '19/05/2024 13:10:30', 0),
(15, 'Talilo Tarlison', 'ttarlison@gmail.com', '5a4bdf6e5f547b9481af07367e1bff94', '09/10/2024 23:32:54', 0),
(17, 'Talilo Tarlison', 'abc@abc.com', '7fda41a732efa12ff8b8cddda0efd44a', '29/01/2025 00:28:20', 0),
(25, 'Maria Araujo', 'maria@gmail.com', 'b88272fa0273e3d3df3db24bfe5abc61', '07/11/2026 02:04:38', 1),
(26, 'Marjory', 'meu@meu.com', 'f971cf7003c7dabf018b03a713229109', '07/11/2026 08:05:52', 0);

-- --------------------------------------------------------

--
-- Estrutura para tabela `log_sistema`
--

CREATE TABLE `log_sistema` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `data_hora` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `log_sistema`
--

INSERT INTO `log_sistema` (`id`, `usuario_id`, `data_hora`) VALUES
(3, 15, '2026-11-07 19:08:27'),
(1, 25, '2026-11-07 18:51:14'),
(2, 25, '2026-11-07 18:53:54'),
(4, 25, '2026-11-07 19:12:02');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `bancos`
--
ALTER TABLE `bancos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_bancos_usuario` (`usuario_id`);

--
-- Índices de tabela `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_categorias_usuario_tipo` (`usuario_id`,`tipo`);

--
-- Índices de tabela `fluxocaixa`
--
ALTER TABLE `fluxocaixa`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_fluxocaixa_banco` (`banco_id`),
  ADD KEY `idx_fluxocaixa_categoria` (`categoria_id`),
  ADD KEY `idx_fluxocaixa_data_lancamento` (`data_lancamento`),
  ADD KEY `idx_fluxocaixa_usuario_id` (`usuario_id`);

--
-- Índices de tabela `links`
--
ALTER TABLE `links`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_links_usuario_estado_data` (`usuario_id`,`visto`,`data_prevista`);

--
-- Índices de tabela `login`
--
ALTER TABLE `login`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `log_sistema`
--
ALTER TABLE `log_sistema`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_log_sistema_usuario_data` (`usuario_id`,`data_hora`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `bancos`
--
ALTER TABLE `bancos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de tabela `fluxocaixa`
--
ALTER TABLE `fluxocaixa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=219;

--
-- AUTO_INCREMENT de tabela `links`
--
ALTER TABLE `links`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT de tabela `login`
--
ALTER TABLE `login`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT de tabela `log_sistema`
--
ALTER TABLE `log_sistema`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `bancos`
--
ALTER TABLE `bancos`
  ADD CONSTRAINT `fk_bancos_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `login` (`id`) ON UPDATE CASCADE;

--
-- Restrições para tabelas `categorias`
--
ALTER TABLE `categorias`
  ADD CONSTRAINT `fk_categorias_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `login` (`id`) ON UPDATE CASCADE;

--
-- Restrições para tabelas `fluxocaixa`
--
ALTER TABLE `fluxocaixa`
  ADD CONSTRAINT `fk_fluxocaixa_banco` FOREIGN KEY (`banco_id`) REFERENCES `bancos` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_fluxocaixa_categoria` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_fluxocaixa_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `login` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Restrições para tabelas `links`
--
ALTER TABLE `links`
  ADD CONSTRAINT `fk_links_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `login` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para tabelas `log_sistema`
--
ALTER TABLE `log_sistema`
  ADD CONSTRAINT `fk_log_sistema_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `login` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
