-- --------------------------------------------------------
-- Servidor:                     127.0.0.1
-- Versão do servidor:           8.0.30 - MySQL Community Server - GPL
-- OS do Servidor:               Win64
-- HeidiSQL Versão:              12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Copiando estrutura do banco de dados para loja_santilac
CREATE DATABASE IF NOT EXISTS `loja_santilac` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `loja_santilac`;

-- Copiando estrutura para tabela loja_santilac.entrega
CREATE TABLE IF NOT EXISTS `entrega` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome_motorista` varchar(100) DEFAULT NULL,
  `placa` int NOT NULL DEFAULT '0',
  `origem` varchar(150) DEFAULT NULL,
  `destino` varchar(150) DEFAULT NULL,
  `data_inicio` date DEFAULT NULL,
  `data-fim` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Copiando dados para a tabela loja_santilac.entrega: ~0 rows (aproximadamente)

-- Copiando estrutura para tabela loja_santilac.funcionario
CREATE TABLE IF NOT EXISTS `funcionario` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) DEFAULT NULL,
  `cargo` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Copiando dados para a tabela loja_santilac.funcionario: ~0 rows (aproximadamente)

-- Copiando estrutura para tabela loja_santilac.itens_venda
CREATE TABLE IF NOT EXISTS `itens_venda` (
  `id` int NOT NULL AUTO_INCREMENT,
  `venda_id` int NOT NULL DEFAULT '0',
  `produto_id` int NOT NULL DEFAULT '0',
  `qtd` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `FK_itens_venda_venda` (`venda_id`),
  KEY `FK_itens_venda_produto` (`produto_id`),
  CONSTRAINT `FK_itens_venda_produto` FOREIGN KEY (`produto_id`) REFERENCES `produto` (`id`),
  CONSTRAINT `FK_itens_venda_venda` FOREIGN KEY (`venda_id`) REFERENCES `venda` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Copiando dados para a tabela loja_santilac.itens_venda: ~0 rows (aproximadamente)

-- Copiando estrutura para tabela loja_santilac.produto
CREATE TABLE IF NOT EXISTS `produto` (
  `id` int NOT NULL AUTO_INCREMENT,
  `tipo` varchar(50) DEFAULT NULL,
  `peso` decimal(20,0) DEFAULT NULL,
  `qtd` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Copiando dados para a tabela loja_santilac.produto: ~0 rows (aproximadamente)

-- Copiando estrutura para tabela loja_santilac.usuario
CREATE TABLE IF NOT EXISTS `usuario` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `cpf` varchar(16) DEFAULT NULL,
  `telefone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `login` varchar(50) DEFAULT NULL,
  `senha` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Copiando dados para a tabela loja_santilac.usuario: ~0 rows (aproximadamente)
INSERT INTO `usuario` (`id`, `nome`, `email`, `cpf`, `telefone`, `login`, `senha`) VALUES
	(1, 'helo', 'helo@gamil', '12234444', '23432123', 'heloisapacazzat@gmail.', '$2y$10$n0GU/v3mBoSHkcKRbfpbzufASWHRasuzSmaRup17rkC7I.bdPjKa.');

-- Copiando estrutura para tabela loja_santilac.venda
CREATE TABLE IF NOT EXISTS `venda` (
  `id` int NOT NULL AUTO_INCREMENT,
  `data_venda` datetime DEFAULT NULL,
  `funcionario_id` int NOT NULL DEFAULT '0',
  `valor_total` decimal(20,0) NOT NULL DEFAULT '0.000000',
  PRIMARY KEY (`id`),
  KEY `FK_venda_funcionario` (`funcionario_id`),
  CONSTRAINT `FK_venda_funcionario` FOREIGN KEY (`funcionario_id`) REFERENCES `funcionario` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Copiando dados para a tabela loja_santilac.venda: ~0 rows (aproximadamente)

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
