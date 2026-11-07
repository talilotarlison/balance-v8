-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 15/04/2025 às 02:14
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
-- Banco de dados: `cadastros`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `cursos`
--

CREATE TABLE `cursos` (
  `idCurso` int(11) NOT NULL,
  `nome` varchar(30) NOT NULL,
  `descricao` text DEFAULT NULL,
  `carga` int(10) UNSIGNED DEFAULT NULL,
  `totalAulas` int(10) UNSIGNED DEFAULT NULL,
  `ano` year(4) DEFAULT 2016
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Despejando dados para a tabela `cursos`
--

INSERT INTO `cursos` (`idCurso`, `nome`, `descricao`, `carga`, `totalAulas`, `ano`) VALUES
(1, 'HTML5', 'Curso de HTML5', 40, 37, '2014'),
(2, 'Algoritimos', 'Logica de Programação', 20, 8, '2014'),
(3, 'Photoshop', 'Aulas de Photoshop CC', 9, 20, '2014'),
(4, 'PHP', 'PHP para Iniciantes', 33, 40, '2015'),
(5, 'Java', 'Intro ao Java', 40, 10, '2015'),
(6, 'MySQL', 'Curso MySQL', 21, 15, '2016'),
(7, 'World', 'Word Completo', 40, 30, '2018'),
(8, 'Sapateado', 'Dança Rítimica', 14, 30, '2018'),
(9, 'Cozinha Árabe', 'Aprenda a fazer Kibe', 40, 30, '2018');

-- --------------------------------------------------------

--
-- Estrutura para tabela `gafanhotos`
--

CREATE TABLE `gafanhotos` (
  `codigo` int(10) DEFAULT NULL,
  `id` int(11) NOT NULL,
  `nome` varchar(30) NOT NULL,
  `profissao` varchar(20) NOT NULL DEFAULT '',
  `cpf` varchar(11) NOT NULL,
  `nascimento` date DEFAULT NULL,
  `sexo` enum('M','F') DEFAULT NULL,
  `peso` decimal(5,2) DEFAULT NULL,
  `altura` decimal(3,2) DEFAULT NULL,
  `nacionalidade` varchar(20) DEFAULT 'Brasil'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Despejando dados para a tabela `gafanhotos`
--

INSERT INTO `gafanhotos` (`codigo`, `id`, `nome`, `profissao`, `cpf`, `nascimento`, `sexo`, `peso`, `altura`, `nacionalidade`) VALUES
(NULL, 1, 'Maria Joana', '', '2147483647', '1980-01-01', 'F', 65.40, 1.60, 'Braileira'),
(NULL, 2, 'Maria Joana', '', '1424242001', '1980-01-01', 'F', 65.40, 1.60, 'Braileira'),
(NULL, 3, 'Glodofredo', '', '1234567891', '1984-01-01', 'M', 65.40, 1.60, 'Braileira'),
(NULL, 4, 'Jose ', '', '2147483647', '1994-01-01', 'M', 64.40, 1.80, 'Braileiro'),
(NULL, 5, 'Jose filho', '', '2147483647', '1994-01-01', 'M', 64.40, 1.80, 'Braileiro'),
(NULL, 6, 'Jose filho', '', '2147483647', '1994-01-01', 'M', 64.40, 1.80, 'Braileiro'),
(NULL, 7, 'Jose filho', '', '2147483647', '1994-01-01', 'M', 64.40, 1.80, 'Braileiro'),
(NULL, 8, 'Jose filho', '', '76841419828', '1994-01-01', 'M', 64.40, 1.80, 'Braileiro'),
(NULL, 9, 'Antonio', '', '7612319828', '1990-01-01', 'M', 64.40, 1.20, 'Brasil'),
(NULL, 10, 'Marino', '', '76142319828', '1999-01-01', 'M', 61.40, 1.20, 'Brasil'),
(NULL, 11, 'Jao Ricardo', '', '7612319828', '1990-02-01', 'M', 84.40, 1.20, 'Brasil');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `cursos`
--
ALTER TABLE `cursos`
  ADD PRIMARY KEY (`idCurso`),
  ADD UNIQUE KEY `nome` (`nome`);

--
-- Índices de tabela `gafanhotos`
--
ALTER TABLE `gafanhotos`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `gafanhotos`
--
ALTER TABLE `gafanhotos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
