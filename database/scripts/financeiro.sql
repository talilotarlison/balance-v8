-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 13/04/2025 às 02:32
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

--
-- Despejando dados para a tabela `fluxocaixa`
--

INSERT INTO `fluxocaixa` (`id`, `tipo`, `nome`, `preco`, `disc`, `data`) VALUES
(149, 'Agendado', 'Talilo', 100, 'Gdgs', '19/05/2024 15:28:35'),
(150, 'Saida', 'Preco produto', -24, 'Rw', '2024-12-8 1:21:20.027'),
(175, 'Entrada', 'Pao', 2, 'Pao com ovo', '2024-12-8 1:21:20.027'),
(176, 'Agendado', 'Biscoito', 44, 'Biscode pacote branco', '2024-12-8 1:26:25.318'),
(199, 'Saida', 'Ssfs', -24, 'Ffs', '29/01/2025 00:29:10'),
(201, 'Agendado', 'Dfsdsf', 232313, 'Sfagdggfsf', '07/02/2025 17:12:41'),
(203, 'Entrada', 'Bobo', 32, 'Bolosa', '12/04/2025 15:02:08'),
(207, 'Agendado', 'Fd', 23532, 'Fdf', '12/04/2025 21:29:38');

-- --------------------------------------------------------

--
-- Estrutura para tabela `links`
--

CREATE TABLE `links` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `visto` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Despejando dados para a tabela `links`
--

INSERT INTO `links` (`id`, `nome`, `url`, `visto`) VALUES
(32, 'valorfaf2', 'valsafor3', 'talilo'),
(33, 'valorfaf2', 'valsafor3', '12'),
(34, 'valorfaf2', 'valsafor3', 'true'),
(35, 'valorfaf2', 'valsafor3', 'true'),
(36, 'valorfaf2', 'valsafor3', '1'),
(37, 'valorfaf2', 'valsafor3', 'true');

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
(3, 'tasdgsa', 'asg@gsgasg', 'd41d8cd98f00b204e9800998ecf8427e', '06/04/2024 21:59:16', 0),
(4, 'BRUNO', 'SSAGDSA@FGSDG', 'd41d8cd98f00b204e9800998ecf8427e', '06/04/2024 22:03:57', 0),
(5, 'BRUNO00000000', 'SSAGDSA@FGSDG', 'd41d8cd98f00b204e9800998ecf8427e', '06/04/2024 22:10:51', 0),
(6, 'jose', 'jos@afsafj', '827ccb0eea8a706c4c34a16891f84e7b', '06/04/2024 22:16:53', 1),
(7, 'jose', 'jos@afsafj', '827ccb0eea8a706c4c34a16891f84e7b', '06/04/2024 22:17:49', 0),
(8, 'Talilo', 'talilotarlison@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', '06/04/2024 23:53:01', 0),
(9, 'talilo', 'rstas@gsdg', 'e10adc3949ba59abbe56e057f20f883e', '07/04/2024 00:15:53', 0),
(10, 'sgsdg', 'dgas@dgsg', '81dc9bdb52d04dc20036dbd8313ed055', '07/04/2024 00:16:27', 0),
(11, 'talilo', 'ydh@dgsg', '5a4bdf6e5f547b9481af07367e1bff94', '19/05/2024 11:44:10', 0),
(12, 'Talilo', 'talilo@gmail.com', '5a4bdf6e5f547b9481af07367e1bff94', '19/05/2024 13:10:30', 0),
(13, 'ta', 'ta@ta', '81dc9bdb52d04dc20036dbd8313ed055', '03/07/2024 23:44:47', 0),
(14, 'talilo', 'tt@fd', '5a4bdf6e5f547b9481af07367e1bff94', '08/08/2024 19:16:50', 0),
(15, 'Talilo Tarlison', 'ttarlison@gmail.com', '5a4bdf6e5f547b9481af07367e1bff94', '09/10/2024 23:32:54', 0),
(16, 'sdf', 'ssfa@sg', '202cb962ac59075b964b07152d234b70', '14/12/2024 14:08:16', 0),
(17, 'Talilo Tarlison', 'abc@abc.com', '7fda41a732efa12ff8b8cddda0efd44a', '29/01/2025 00:28:20', 0),
(18, 'tad', 'tt@tt', '81dc9bdb52d04dc20036dbd8313ed055', '07/02/2025 23:17:53', 0),
(19, 'jesus', 'jesus@jesus', '202cb962ac59075b964b07152d234b70', '12/04/2025 14:43:24', 0),
(20, 'talilo', 'ttarlison@gmail.com', '827ccb0eea8a706c4c34a16891f84e7b', '12/04/2025 14:48:04', 0),
(21, 'gg', 'gg@gg', '73c18c59a39b18382081ec00bb456d43', '12/04/2025 15:31:21', 1);

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `fluxocaixa`
--
ALTER TABLE `fluxocaixa`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `links`
--
ALTER TABLE `links`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `login`
--
ALTER TABLE `login`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `fluxocaixa`
--
ALTER TABLE `fluxocaixa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=208;

--
-- AUTO_INCREMENT de tabela `links`
--
ALTER TABLE `links`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT de tabela `login`
--
ALTER TABLE `login`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
