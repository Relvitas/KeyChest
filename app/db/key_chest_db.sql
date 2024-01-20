-- MariaDB dump 10.19  Distrib 10.6.12-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: baul_claves
-- ------------------------------------------------------
-- Server version	10.6.12-MariaDB-0ubuntu0.22.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Current Database: `baul_claves`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `baul_claves` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci */;

USE `baul_claves`;

--
-- Table structure for table `correo_registro`
--

DROP TABLE IF EXISTS `correo_registro`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `correo_registro` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `correo` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `correo` (`correo`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `dato_registro`
--

DROP TABLE IF EXISTS `dato_registro`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dato_registro` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11) DEFAULT NULL,
  `id_portal_registro` int(11) DEFAULT NULL,
  `id_correo_registro` int(11) DEFAULT NULL,
  `id_nombre_registro` int(11) DEFAULT NULL,
  `id_telefono_registro` int(11) DEFAULT NULL,
  `contrasenia` varchar(255) NOT NULL,
  `clave_recuperacion` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_datoregistro_usuario` (`id_usuario`),
  KEY `fk_datoregistro_portalregistro` (`id_portal_registro`),
  KEY `fk_datoregistro_correoregistro` (`id_correo_registro`),
  KEY `fk_datoregistro_nombreregistro` (`id_nombre_registro`),
  KEY `fk_datoregistro_telefonoregistro` (`id_telefono_registro`),
  CONSTRAINT `fk_datoregistro_correoregistro` FOREIGN KEY (`id_correo_registro`) REFERENCES `correo_registro` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_datoregistro_nombreregistro` FOREIGN KEY (`id_nombre_registro`) REFERENCES `nombre_registro` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_datoregistro_portalregistro` FOREIGN KEY (`id_portal_registro`) REFERENCES `portal_registro` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_datoregistro_telefonoregistro` FOREIGN KEY (`id_telefono_registro`) REFERENCES `telefono_registro` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_datoregistro_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=71 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `nombre_registro`
--

DROP TABLE IF EXISTS `nombre_registro`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `nombre_registro` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombre` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `portal_registro`
--

DROP TABLE IF EXISTS `portal_registro`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `portal_registro` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombre` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=67 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `solicitud_restablecer_contrasenia`
--

DROP TABLE IF EXISTS `solicitud_restablecer_contrasenia`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `solicitud_restablecer_contrasenia` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11) NOT NULL,
  `token` varchar(60) NOT NULL,
  `fecha_expiracion` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `token` (`token`),
  KEY `fk_solicitudrestablecercontrasenia_usuario_id` (`id_usuario`),
  CONSTRAINT `fk_solicitudrestablecercontrasenia_usuario_id` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `telefono_registro`
--

DROP TABLE IF EXISTS `telefono_registro`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `telefono_registro` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `telefono` varchar(25) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `telefono` (`telefono`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `usuario`
--

DROP TABLE IF EXISTS `usuario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usuario` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `correo` varchar(50) NOT NULL,
  `contrasenia` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombre` (`nombre`),
  UNIQUE KEY `correo` (`correo`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping routines for database 'baul_claves'
--
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `buscar_registro` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`relvitas`@`localhost` PROCEDURE `buscar_registro`( in id_usuario smallint, in dato varchar(255))
begin
    select
        dato_registro.id as id_registro,
        portal_registro.nombre as sitio_web,
        correo_registro.correo,
        nombre_registro.nombre as nombre_usuario,
        telefono_registro.telefono,
        dato_registro.contrasenia,
        dato_registro.clave_recuperacion 
    from
        dato_registro 
        inner join
            portal_registro 
            on dato_registro.id_portal_registro = portal_registro.id 
        left join
            correo_registro 
            on dato_registro.id_correo_registro = correo_registro.id 
        left join
            nombre_registro 
            on dato_registro.id_nombre_registro = nombre_registro.id 
        left join
            telefono_registro 
            on dato_registro.id_telefono_registro = telefono_registro.id 
    where
        portal_registro.nombre like concat('%', dato, '%') 
        or correo_registro.correo like concat('%', dato, '%') 
        or nombre_registro.nombre like concat('%', dato, '%') 
        or telefono_registro.telefono like concat('%', dato, '%') 
        or dato_registro.contrasenia like concat('%', dato, '%') 
        or dato_registro.clave_recuperacion like concat('%', dato, '%') 
        and dato_registro.id_usuario = id_usuario;
end ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `buscar_registro_paginado` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`relvitas`@`localhost` PROCEDURE `buscar_registro_paginado`( in id_usuario smallint, in dato varchar(255), in comienza smallint, in limite smallint)
begin
    select
        dato_registro.id as id_registro,
        portal_registro.nombre as sitio_web,
        correo_registro.correo,
        nombre_registro.nombre as nombre_usuario,
        telefono_registro.telefono,
        dato_registro.contrasenia,
        dato_registro.clave_recuperacion 
    from
        dato_registro 
        inner join
            portal_registro 
            on dato_registro.id_portal_registro = portal_registro.id 
        left join
            correo_registro 
            on dato_registro.id_correo_registro = correo_registro.id 
        left join
            nombre_registro 
            on dato_registro.id_nombre_registro = nombre_registro.id 
        left join
            telefono_registro 
            on dato_registro.id_telefono_registro = telefono_registro.id 
    where
        portal_registro.nombre like concat('%', dato, '%') 
        or correo_registro.correo like concat('%', dato, '%') 
        or nombre_registro.nombre like concat('%', dato, '%') 
        or telefono_registro.telefono like concat('%', dato, '%') 
        or dato_registro.contrasenia like concat('%', dato, '%') 
        or dato_registro.clave_recuperacion like concat('%', dato, '%') 
        and dato_registro.id_usuario = id_usuario limit comienza, limite;
end ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-01-20 14:08:56