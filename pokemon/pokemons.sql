-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 29-Jul-2025 às 13:32
-- Versão do servidor: 10.4.27-MariaDB
-- versão do PHP: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `pokemon_db`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `pokemons`
--

CREATE TABLE `pokemons` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `tipo` varchar(50) DEFAULT NULL,
  `localizacao` varchar(100) DEFAULT NULL,
  `data_registro` date DEFAULT NULL,
  `hp` int(11) DEFAULT NULL,
  `ataque` int(11) DEFAULT NULL,
  `defesa` int(11) DEFAULT NULL,
  `observacoes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `pokemons`
--

INSERT INTO `pokemons` (`id`, `nome`, `tipo`, `localizacao`, `data_registro`, `hp`, `ataque`, `defesa`, `observacoes`) VALUES
(1, 'Pikachu', 'Elétrico', 'Campo', '2025-07-29', 30, 50, 30, 'Pikachu é um Pokémon do tipo Elétrico, conhecido por sua aparência fofa e poderes elétricos. Ele evolui de Pichu e pode evoluir para Raichu.'),
(2, 'Charmander', 'Fogo', 'Cidade quente', '2025-07-29', 39, 52, 43, 'Muito agitado, solta pequenas chamas da cauda');

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `pokemons`
--
ALTER TABLE `pokemons`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `pokemons`
--
ALTER TABLE `pokemons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
