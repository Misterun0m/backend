-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: jovenestramites
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

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
-- Table structure for table `modulo`
--

DROP TABLE IF EXISTS `modulo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `modulo` (
  `modulo_id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(200) NOT NULL,
  `direccion` varchar(500) NOT NULL,
  `lat` decimal(10,7) NOT NULL,
  `lng` decimal(10,7) NOT NULL,
  `horario` varchar(200) NOT NULL,
  `telefono` varchar(50) DEFAULT NULL,
  `url_cita` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`modulo_id`)
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `modulo`
--

LOCK TABLES `modulo` WRITE;
/*!40000 ALTER TABLE `modulo` DISABLE KEYS */;
INSERT INTO `modulo` VALUES (1,'Módulo INE Iztapalapa','Av. Ermita Iztapalapa 3315, Iztapalapa, CDMX',19.3571569,-99.0627234,'Lun-Vie 9:00-17:00','800 433 2000','https://inetel-citas.ine.mx/'),(2,'Módulo INE Gustavo A. Madero','Av. Insurgentes Norte 1173, GAM, CDMX',19.4803241,-99.1207683,'Lun-Vie 9:00-17:00','800 433 2000','https://inetel-citas.ine.mx/'),(3,'Módulo INE Coyoacán','Av. Universidad 1449, Coyoacán, CDMX',19.3488312,-99.1621543,'Lun-Vie 9:00-17:00','800 433 2000','https://inetel-citas.ine.mx/'),(4,'Módulo INE Tlalpan','Insurgentes Sur 4411, Tlalpan, CDMX',19.2952341,-99.1730218,'Lun-Vie 9:00-17:00','800 433 2000','https://inetel-citas.ine.mx/'),(5,'Módulo INE Centro Histórico','Av. Hidalgo 77, Centro Histórico, CDMX',19.4363824,-99.1452341,'Lun-Vie 9:00-17:00','800 433 2000','https://inetel-citas.ine.mx/'),(6,'Módulo INE Ecatepec','Av. Central 405, Ecatepec, Edomex',19.6012543,-99.0312876,'Lun-Vie 9:00-17:00','800 433 2000','https://inetel-citas.ine.mx/'),(7,'Módulo INE Tecámac','Av. Principal 123, Tecámac, Edomex',19.7026000,-98.9824000,'Lun-Vie 9:00-17:00','55-1234-5678','https://inetel-citas.ine.mx/'),(8,'Módulo INE Guadalajara','Av. Federalismo Norte 951, Guadalajara, Jalisco',20.6843217,-103.3487652,'Lun-Vie 9:00-17:00','800 433 2000','https://inetel-citas.ine.mx/'),(9,'Módulo INE Monterrey','Washington 2000 Ote, Monterrey, Nuevo León',25.6682341,-100.3101234,'Lun-Vie 9:00-17:00','800 433 2000','https://inetel-citas.ine.mx/'),(10,'Módulo INE Puebla','11 Oriente 1204, Puebla, Puebla',19.0459876,-98.1981234,'Lun-Vie 9:00-17:00','800 433 2000','https://inetel-citas.ine.mx/'),(11,'Módulo INE Tijuana','Blvd. Agua Caliente 4558, Tijuana, B.C.',32.5149219,-117.0382354,'Lun-Vie 9:00-17:00','800 433 2000','https://inetel-citas.ine.mx/'),(12,'Módulo INE León','Blvd. Francisco Villa 302, León, Guanajuato',21.1218765,-101.6823451,'Lun-Vie 9:00-17:00','800 433 2000','https://inetel-citas.ine.mx/'),(13,'Módulo INE Mérida','Calle 65 492, Centro, Mérida, Yucatán',20.9673451,-89.6231234,'Lun-Vie 9:00-17:00','800 433 2000','https://inetel-citas.ine.mx/'),(14,'SAT CDMX Centro','Av. Hidalgo 77, Centro Histórico, CDMX',19.4355341,-99.1435218,'Lun-Vie 8:30-16:30','55 8852 2222','https://citas.sat.gob.mx/'),(15,'SAT CDMX Insurgentes','Insurgentes Sur 954, Nápoles, CDMX',19.3961234,-99.1712345,'Lun-Vie 8:30-16:30','55 8852 2222','https://citas.sat.gob.mx/'),(16,'SAT Tlalnepantla','Blvd. Ávila Camacho 2566, Tlalnepantla, Edomex',19.5423451,-99.2051234,'Lun-Vie 8:30-16:30','55 8852 2222','https://citas.sat.gob.mx/'),(17,'SAT Guadalajara','Av. Américas 1211, Guadalajara, Jalisco',20.6712345,-103.3823451,'Lun-Vie 8:30-16:30','33 3678 3000','https://citas.sat.gob.mx/'),(18,'SAT Monterrey','Av. Lázaro Cárdenas 2321, Monterrey, Nuevo León',25.6512345,-100.3312345,'Lun-Vie 8:30-16:30','81 8150 5000','https://citas.sat.gob.mx/'),(19,'SAT Puebla','16 de Septiembre 1202, Puebla, Puebla',19.0423451,-98.2012345,'Lun-Vie 8:30-16:30','22 2309 3500','https://citas.sat.gob.mx/'),(20,'SAT Tijuana','Paseo de los Héroes 9415, Tijuana, B.C.',32.5251234,-117.0212345,'Lun-Vie 8:30-16:30','66 4647 4400','https://citas.sat.gob.mx/'),(21,'SAT Mérida','Calle 61 476, Centro, Mérida, Yucatán',20.9712345,-89.6212345,'Lun-Vie 8:30-16:30','99 9930 2030','https://citas.sat.gob.mx/'),(22,'IMSS CDMX Norte','Av. San Isidro 16, Azcapotzalco, CDMX',19.4912345,-99.1812345,'Lun-Vie 8:00-20:00','800 623 2323',NULL),(23,'IMSS CDMX Sur','Av. del Imán 151, Coyoacán, CDMX',19.3123451,-99.1623451,'Lun-Vie 8:00-20:00','800 623 2323',NULL),(24,'IMSS CDMX Oriente','Av. Ermita Iztapalapa 5230, Iztapalapa, CDMX',19.3412345,-99.0512345,'Lun-Vie 8:00-20:00','800 623 2323',NULL),(25,'IMSS Guadalajara','Av. Federalismo Sur 330, Guadalajara, Jalisco',20.6612345,-103.3512345,'Lun-Vie 8:00-20:00','800 623 2323',NULL),(26,'IMSS Monterrey','Av. Constitución 2000 Ote, Monterrey, Nuevo León',25.6712345,-100.3012345,'Lun-Vie 8:00-20:00','800 623 2323',NULL),(27,'IMSS Puebla','5 de Mayo 1602, Puebla, Puebla',19.0512345,-98.1912345,'Lun-Vie 8:00-20:00','800 623 2323',NULL),(28,'IMSS Tijuana','Blvd. Cuauhtémoc Sur 668, Tijuana, B.C.',32.5012345,-117.0312345,'Lun-Vie 8:00-20:00','800 623 2323',NULL),(29,'IMSS Mérida','Calle 27 No. 321, Mérida, Yucatán',20.9812345,-89.6112345,'Lun-Vie 8:00-20:00','800 623 2323',NULL),(30,'Cartilla Militar CDMX Centro','Av. Juárez 92, Centro Histórico, CDMX',19.4341234,-99.1458234,'Lun-Vie 9:00-14:00','55 5130 1300',NULL),(31,'Cartilla Militar CDMX Sur','Insurgentes Sur 4411, Tlalpan, CDMX',19.2912345,-99.1712345,'Lun-Vie 9:00-14:00','55 5130 1300',NULL),(32,'Cartilla Militar Guadalajara','Av. 16 de Septiembre 530, Guadalajara, Jalisco',20.6712345,-103.3412345,'Lun-Vie 9:00-14:00','33 3613 7700',NULL),(33,'Cartilla Militar Monterrey','Zuazua 1200 Nte, Monterrey, Nuevo León',25.6812345,-100.3112345,'Lun-Vie 9:00-14:00','81 8340 4000',NULL),(34,'Cartilla Militar Puebla','11 Norte 1104, Centro, Puebla, Puebla',19.0412345,-98.2012345,'Lun-Vie 9:00-14:00','22 2246 8500',NULL),(35,'Cartilla Militar Tijuana','Av. Obregón 1310, Centro, Tijuana, B.C.',32.5312345,-117.0212345,'Lun-Vie 9:00-14:00','66 4685 2200',NULL),(36,'Cartilla Militar Mérida','Calle 59 501, Centro, Mérida, Yucatán',20.9612345,-89.6312345,'Lun-Vie 9:00-14:00','99 9924 0500',NULL),(37,'Licencias CDMX Vallejo','Av. Insurgentes Norte 20, GAM, CDMX',19.4762345,-99.1352345,'Lun-Sáb 8:00-18:00','55 5208 9898','https://www.semovi.cdmx.gob.mx/'),(38,'Licencias CDMX Xochimilco','Periférico Oriente 9058, Xochimilco, CDMX',19.2652345,-99.1042345,'Lun-Sáb 8:00-18:00','55 5208 9898','https://www.semovi.cdmx.gob.mx/'),(39,'Licencias CDMX Cuauhtémoc','Av. Insurgentes Centro 149, CDMX',19.4272345,-99.1482345,'Lun-Sáb 8:00-18:00','55 5208 9898','https://www.semovi.cdmx.gob.mx/'),(40,'Licencias Guadalajara','Av. Mariano Otero 3507, Guadalajara, Jalisco',20.6512345,-103.3912345,'Lun-Sáb 8:00-18:00','33 3030 0660','https://semov.jalisco.gob.mx/'),(41,'Licencias Monterrey','Av. Constitución 4000 Ote, Monterrey, Nuevo León',25.6612345,-100.2912345,'Lun-Sáb 8:00-18:00','81 2020 3200',NULL),(42,'Licencias Puebla','Blvd. Norte 2902, Puebla, Puebla',19.0712345,-98.1812345,'Lun-Sáb 8:00-17:00','22 2303 2900',NULL),(43,'Licencias Tijuana','Blvd. Agua Caliente 11200, Tijuana, B.C.',32.5012345,-116.9812345,'Lun-Vie 8:00-17:00','66 4973 7500',NULL),(44,'Licencias Mérida','Calle 90 No. 500, Mérida, Yucatán',20.9712345,-89.6212345,'Lun-Vie 8:00-16:00','99 9930 3270',NULL);
/*!40000 ALTER TABLE `modulo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `modulo_tramite`
--

DROP TABLE IF EXISTS `modulo_tramite`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `modulo_tramite` (
  `modulo_id` int(11) NOT NULL,
  `tram_id` int(11) NOT NULL,
  PRIMARY KEY (`modulo_id`,`tram_id`),
  KEY `tram_id` (`tram_id`),
  CONSTRAINT `modulo_tramite_ibfk_1` FOREIGN KEY (`modulo_id`) REFERENCES `modulo` (`modulo_id`) ON DELETE CASCADE,
  CONSTRAINT `modulo_tramite_ibfk_2` FOREIGN KEY (`tram_id`) REFERENCES `tramite` (`tram_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `modulo_tramite`
--

LOCK TABLES `modulo_tramite` WRITE;
/*!40000 ALTER TABLE `modulo_tramite` DISABLE KEYS */;
INSERT INTO `modulo_tramite` VALUES (1,1),(2,1),(3,1),(4,1),(5,1),(6,1),(7,1),(8,1),(9,1),(10,1),(11,1),(12,1),(13,1),(14,2),(15,2),(16,2),(17,2),(18,2),(19,2),(20,2),(21,2),(22,3),(23,3),(24,3),(25,3),(26,3),(27,3),(28,3),(29,3),(30,4),(31,4),(32,4),(33,4),(34,4),(35,4),(36,4),(37,5),(38,5),(39,5),(40,5),(41,5),(42,5),(43,5),(44,5);
/*!40000 ALTER TABLE `modulo_tramite` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `requisito`
--

DROP TABLE IF EXISTS `requisito`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `requisito` (
  `re_id` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(500) NOT NULL,
  `tram_id` int(11) NOT NULL,
  `portal_oficial` varchar(500) NOT NULL,
  PRIMARY KEY (`re_id`),
  UNIQUE KEY `tram_id` (`tram_id`),
  CONSTRAINT `requisito_ibfk_1` FOREIGN KEY (`tram_id`) REFERENCES `tramite` (`tram_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `requisito`
--

LOCK TABLES `requisito` WRITE;
/*!40000 ALTER TABLE `requisito` DISABLE KEYS */;
INSERT INTO `requisito` VALUES (1,'•	Documento de Nacionalidad: Acta de nacimiento original (sin tachaduras ni enmendaduras).\r\n•	Identificación con Fotografía: Pasaporte, Cédula Profesional, Cartilla Militar o incluso tu credencial de la escuela (siempre que esté vigente).\r\n•	Comprobante de Domicilio: Recibo de luz, agua, teléfono o predial (no mayor a 3 meses).\r\n',1,'https://inetel-citas.ine.mx/ReservaCitas/'),(2,'•	Acta de nacimiento o CURP\r\n•	Identificación oficial con foto (INE, pasaporte o cédula profesional)\r\n•	Comprobante de domicilio (no mayor a tres meses) \r\n•	Capturar tus datos en el formulario de inscripción\r\n•	También se recomienda llevar una USB si deseas obtener la e.firma.\r\n',2,'https://taxdown.com.mx/rfc/papeles-tramitar-rfc#:~:text=Para%20tramitar%20el%20RFC%20necesitas,en%20el%20formulario%20de%20inscripci%C3%B3n'),(3,'•	CURP (Clave Única de Registro de Población).\r\n•	Correo electrónico personal y vigente.\r\n',3,'https://serviciosdigitales.imss.gob.mx/gestionAsegurados-web-externo/asignacionNSS;JSESSIONIDASEGEXTERNO=nCZLfN6qKmKkmKMSdGXlMjfR6SCv3ZYlQCTdrSIuocrO05RbTQCI!-129869531'),(4,'•	Cuatro fotografías recientes de 35×45 milímetros, de frente a color o       blanco y negro, con fondo blanco, sin retoque, en las que las facciones del interesado se distingan con claridad, sin tocado (gorra, sombrero, etc.), sin lentes, con bigote recortado, sin barba, patillas recortadas, sin aretes u otros objetos colocados en el rostro mediante perforaciones.\r\n•	Copia certificada del acta de nacimiento.\r\n•	Comprobante de domicilio (recibo reciente de luz, teléfono, predial, agua, etc.)\r\n•	',4,'https://www.elcuartopoder.mx/por-que-es-importante-la-cartilla-militar/'),(5,'•	Identificación oficial vigente (INE o pasaporte)\r\n•	CURP\r\n•	Comprobante de domicilio (no mayor a 3 meses)\r\n•	Acta de nacimiento\r\n•	Pago de derechos\r\n•	Aprobar examen teórico (reglamento de tránsito)\r\n•	Aprobar examen práctico de manejo\r\n•	En algunos estados: certificado médico\r\n',5,'https://www.gob.mx/public/componentes/busqueda/resultadoBusqueda.xhtml');
/*!40000 ALTER TABLE `requisito` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tramite`
--

DROP TABLE IF EXISTS `tramite`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tramite` (
  `tram_id` int(11) NOT NULL AUTO_INCREMENT,
  `tram_imp` varchar(500) NOT NULL,
  `tram_tip` varchar(500) NOT NULL,
  `tram_saber` varchar(1000) NOT NULL,
  PRIMARY KEY (`tram_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tramite`
--

LOCK TABLES `tramite` WRITE;
/*!40000 ALTER TABLE `tramite` DISABLE KEYS */;
INSERT INTO `tramite` VALUES (1,'Es la identificación oficial más usada en México, sirve para votar en elecciones y la solicitan en muchos trámites como en la escuela, bancos, empleos y contratos, además de que permite confirmar tu identidad.','Credencial de elector (INE)','•	El trámite es gratuito al solicitarla por primera vez.\r\n•	Debes presentar todos los documentos en original y vigentes.\r\n•	Es recomendable llegar puntual a tu cita para evitar contratiempos.\r\n•	La credencial no se entrega el mismo día; deberás acudir posteriormente a recogerla.\r\n'),(2,'Es necesaria para personas (física) y empresas (moral), que realizan actividades económicas, su importancia radica en la formalización, el cumplimiento de obligaciones fiscales y el acceso a servicios financieros.','Registro Federal de Contribuyentes (RFC)','●	Tienes 10 minutos de tolerancia.\r\n●	Si ya no necesitas la cita, puedes cancelarla hasta una hora antes para que no cuente como inasistencia.\r\n●	Si faltas a dos citas en 15 días, no podrás agendar otra durante 30 días.'),(3,'Es necesaria para personas (física) y empresas (moral), que realizan actividades económicas, su importancia radica en la formalización, el cumplimiento de obligaciones fiscales y el acceso a servicios financieros.','Número de seguro social (NSS)','•	El trámite es gratuito y se realiza de manera personal.\r\n•	Es necesario contar con CURP y un correo electrónico vigente.\r\n•	El número asignado es único y permanente.\r\n•	Se recomienda guardar o imprimir el comprobante una vez obtenido.\r\n'),(4,'Acredita el cumplimiento del Servicio Militar Nacional, una obligación cívica en México. Además, puede ser solicitada como requisito en empleos gubernamentales o trámites oficiales, y funciona como un documento que respalda la situación militar del ciudadano.','Cartilla militar','•	El registro se realiza durante el año en que cumples 18 años.\r\n•	Si no realizas el trámite en tiempo, se considera extemporáneo.\r\n•	El sorteo determina si realizarás servicio encuadrado o quedarás a disponibilidad.\r\n•	La cartilla solo tiene validez oficial cuando está liberada.'),(5,'Es un documento oficial que acredita que una persona cuenta con los conocimientos teóricos y habilidades prácticas necesarias para conducir un vehículo de manera legal y segura.','Licencia de conducir','Cuentas con 10 minutos de tolerancia para tu cita y el trámite se realiza en la Secretaría de Movilidad o dependencia estatal correspondiente.\r\n● La vigencia puede ser de 1, 3 o 5 años, según el estado, por lo que debe renovarse antes de su vencimiento.\r\n● El costo y el tipo de licencia varían según el vehículo (automovilista, motociclista o chofer).\r\n');
/*!40000 ALTER TABLE `tramite` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_tramite`
--

DROP TABLE IF EXISTS `user_tramite`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_tramite` (
  `user_idtramite` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `estado_tramite` varchar(15) NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_finalizacion` date DEFAULT NULL,
  `tram_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`user_idtramite`),
  KEY `fk_usertramite_tramite` (`tram_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `fk_usertramite_tramite` FOREIGN KEY (`tram_id`) REFERENCES `tramite` (`tram_id`)
) ENGINE=InnoDB AUTO_INCREMENT=91 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_tramite`
--

LOCK TABLES `user_tramite` WRITE;
/*!40000 ALTER TABLE `user_tramite` DISABLE KEYS */;
INSERT INTO `user_tramite` VALUES (4,12,'Pendiente','2026-03-07',NULL,1),(6,14,'Pendiente','2026-03-07',NULL,1),(8,17,'Pendiente','2026-03-07',NULL,1),(10,19,'Pendiente','2026-03-07',NULL,1),(12,21,'Pendiente','2026-03-07',NULL,1),(14,23,'Pendiente','2026-03-07',NULL,1),(16,24,'Pendiente','2026-03-07',NULL,1),(20,26,'Finalizado','2026-03-07','2026-03-11',1),(21,26,'Pendiente','2026-03-07',NULL,2),(22,26,'Finalizado','2026-03-07','2026-03-09',3),(23,26,'Pendiente','2026-03-07',NULL,4),(24,26,'Pendiente','2026-03-07',NULL,5),(25,27,'Finalizado','2026-03-07','2026-03-10',1),(26,27,'Pendiente','2026-03-07',NULL,2),(27,27,'Pendiente','2026-03-07',NULL,3),(28,27,'Pendiente','2026-03-07',NULL,4),(29,27,'Pendiente','2026-03-07',NULL,5),(30,28,'Pendiente','2026-03-09',NULL,1),(31,28,'Pendiente','2026-03-09',NULL,2),(32,28,'Pendiente','2026-03-09',NULL,3),(33,28,'Pendiente','2026-03-09',NULL,4),(34,28,'Pendiente','2026-03-09',NULL,5),(35,29,'Pendiente','2026-03-10',NULL,1),(36,30,'Pendiente','2026-03-10',NULL,1),(37,30,'Pendiente','2026-03-10',NULL,2),(38,30,'Pendiente','2026-03-10',NULL,3),(39,30,'Pendiente','2026-03-10',NULL,4),(40,30,'Pendiente','2026-03-10',NULL,5),(41,31,'Pendiente','2026-03-10',NULL,1),(42,31,'Pendiente','2026-03-10',NULL,2),(43,31,'Pendiente','2026-03-10',NULL,3),(44,31,'Pendiente','2026-03-10',NULL,4),(45,31,'Pendiente','2026-03-10',NULL,5),(46,32,'Pendiente','2026-03-10',NULL,1),(47,32,'En proceso','2026-03-10',NULL,2),(48,32,'Pendiente','2026-03-10',NULL,3),(49,32,'Pendiente','2026-03-10',NULL,4),(50,32,'Pendiente','2026-03-10',NULL,5),(76,38,'Pendiente','2026-03-12',NULL,1),(77,38,'Pendiente','2026-03-12',NULL,2),(78,38,'Pendiente','2026-03-12',NULL,3),(79,38,'Pendiente','2026-03-12',NULL,4),(80,38,'Pendiente','2026-03-12',NULL,5),(81,39,'Pendiente','2026-03-12',NULL,1),(82,39,'Pendiente','2026-03-12',NULL,2),(83,39,'Pendiente','2026-03-12',NULL,3),(84,39,'Pendiente','2026-03-12',NULL,4),(85,39,'Pendiente','2026-03-12',NULL,5),(86,40,'Pendiente','2026-03-25',NULL,1),(87,40,'Pendiente','2026-03-25',NULL,2),(88,40,'Pendiente','2026-03-25',NULL,3),(89,40,'Pendiente','2026-03-25',NULL,4),(90,40,'Pendiente','2026-03-25',NULL,5);
/*!40000 ALTER TABLE `user_tramite` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuario`
--

DROP TABLE IF EXISTS `usuario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usuario` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_nom` varchar(50) NOT NULL,
  `user_sex` varchar(9) DEFAULT NULL,
  `user_pass` varchar(255) DEFAULT NULL,
  `user_correo` varchar(150) NOT NULL,
  `google_id` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_nacimiento` date DEFAULT NULL,
  `codigo_recuperacion` int(11) NOT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `unique_email` (`user_correo`)
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario`
--

LOCK TABLES `usuario` WRITE;
/*!40000 ALTER TABLE `usuario` DISABLE KEYS */;
INSERT INTO `usuario` VALUES (6,'ivan','Masculino','123','imartinezr@n3xt.com.mx',NULL,'2026-03-06 16:42:01','2025-09-03',669770),(7,'alexis','Masculino','Vaquita1','alexisem05@gmail.com',NULL,'2026-03-06 19:45:51','2005-08-10',398048),(17,'juan','Masculino','123','caballeoruben070@gmail.com',NULL,'2026-03-08 05:21:05','2026-03-19',0),(28,'Ruben','Masculino','1234567','caballeoruben98@gmail.com',NULL,'2026-03-09 22:53:06','2026-03-11',0),(30,'Ivan Martinez Rodriguex','Masculino','123456','imartinezr@outlook.com',NULL,'2026-03-10 14:43:39','1992-08-31',0),(31,'Inicio Ciudadano','Masculino',NULL,'iniciociudadano@gmail.com','106297391841302862724','2026-03-10 14:55:04','2026-03-11',0),(32,'noe','Masculino','pollito123','neogta1@gmail.com',NULL,'2026-03-10 20:13:13','1999-10-12',0),(38,'Ruben De Jesus Elizalde sancphez','Masculino',NULL,'caballeoruben7@gmail.com','116115350824311333748','2026-03-12 15:57:07','2026-03-08',914814),(39,'ozuna ramos elioth','Masculino','elioth223','elioth.ozuna.ramos@gmail.com',NULL,'2026-03-12 16:36:56','2005-11-19',606748),(40,'Adri','Femenino','147258','adrimontalvo51@gmail.com',NULL,'2026-03-25 18:42:09','2005-06-07',840440);
/*!40000 ALTER TABLE `usuario` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-04-04 22:25:46
