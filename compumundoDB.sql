CREATE DATABASE  IF NOT EXISTS `compumundo_db` /*!40100 DEFAULT CHARACTER SET utf8mb4 */;
USE `compumundo_db`;
-- MySQL dump 10.13  Distrib 5.6.17, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: compumundo_db
-- ------------------------------------------------------
-- Server version	5.5.5-10.4.8-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `preguntas`
--

DROP TABLE IF EXISTS `preguntas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `preguntas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pregunta` varchar(1000) DEFAULT NULL,
  `respuesta` varchar(1000) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `preguntas`
--

LOCK TABLES `preguntas` WRITE;
/*!40000 ALTER TABLE `preguntas` DISABLE KEYS */;
INSERT INTO `preguntas` VALUES (1,'hola','¡Hola! ¿Cómo estás?'),(2,'Quiero conocer sus productos','¡Muy bien! Nuestras categorías son: <br>\nNotebooks y PC<br>\nCelulares y Tablets<br>\nSmart TV y Audio<br>\nGaming<br>\nTech Home<br>\n¿Estás interesad@ en alguna en particular? <br> ¡Solo escríbela!'),(3,'apellidos','Álvarez<br>\n Carretero<br>\n Orlandi<br> \n Yudchak<br>'),(4,'Necesito ayuda con mi compra','¡Muy bien! Te explico como puedo ayudarte: <br>\nSolo dime a qué categoría pertenece y qué características te interesan de tu próximo producto y yo buscaré tres opciones ideales para tí. <br> \n¿Precio? <br> \n¿Potencia? <br> \n¿Rendimiento?<br>\n¿Pantalla grande?<br>'),(5,'gracias','¿De nada!¡Estoy aquí para ayudarte!'),(6,'como estas?','¡Bien! ¿Y vos?'),(7,'Bien','¡Me alegro! ¿Quieres que te ayude a conseguir próximo producto?'),(8,'si','¡Muy bien! Te explico cómo puedo ayudarte: <br>\nSolo dime a qué categoría pertenece y qué características te interesan de tu próximo producto y yo buscaré tres opciones ideales para tí. <br>\n¿Precio? <br> \n¿Potencia? <br> \n¿Rendimiento?<br>\n¿Pantalla grande?<br>\nLo que creas que necesita tu producto');
/*!40000 ALTER TABLE `preguntas` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-12-10 11:34:36
