/* SQL Manager for MySQL                              5.6.2.48160 */
/* -------------------------------------------------------------- */
/* Host     : localhost                                           */
/* Port     : 3306                                                */
/* Database : bit                                                 */


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES 'utf8' */;

SET FOREIGN_KEY_CHECKS=0;

CREATE DATABASE `bit`
    CHARACTER SET 'utf8'
    COLLATE 'utf8_general_ci';

USE `bit`;

/* Structure for the `accounts` table : */

CREATE TABLE `accounts` (
  `id` VARCHAR(20) COLLATE utf8_general_ci NOT NULL,
  `balance` DOUBLE NOT NULL DEFAULT 0,
  PRIMARY KEY USING BTREE (`id`)
) ENGINE=InnoDB
CHARACTER SET 'utf8' COLLATE 'utf8_general_ci'
;

/* Structure for the `operations` table : */

CREATE TABLE `operations` (
  `operationId` INTEGER(11) NOT NULL AUTO_INCREMENT,
  `debId` VARCHAR(20) COLLATE utf8_general_ci NOT NULL,
  `credId` VARCHAR(20) COLLATE utf8_general_ci NOT NULL,
  `amount` DOUBLE NOT NULL,
  `dateOperation` INTEGER(11) NOT NULL,
  `description` VARCHAR(200) COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY USING BTREE (`operationId`)
) ENGINE=InnoDB
AUTO_INCREMENT=32 CHARACTER SET 'utf8' COLLATE 'utf8_general_ci'
;

/* Structure for the `users` table : */

CREATE TABLE `users` (
  `id` INTEGER(11) NOT NULL AUTO_INCREMENT,
  `login` VARCHAR(64) COLLATE utf8_general_ci NOT NULL,
  `password` VARCHAR(64) COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY USING BTREE (`id`)
) ENGINE=InnoDB
AUTO_INCREMENT=2 CHARACTER SET 'utf8' COLLATE 'utf8_general_ci'
;

/* Data for the `accounts` table  (LIMIT 0,500) */

INSERT INTO `accounts` (`id`, `balance`) VALUES
  ('f1',16.93),
  ('r1',-1681),
  ('r2',-11),
  ('r3',-1),
  ('u1',0),
  ('w1',1346.4),
  ('w2',329.67),
  ('w3',0);
COMMIT;

/* Data for the `operations` table  (LIMIT 0,500) */

INSERT INTO `operations` (`operationId`, `debId`, `credId`, `amount`, `dateOperation`, `description`) VALUES
  (11,'u1','r1',1000,1490712473,'Пополение счета пользователя rootroot через r1'),
  (12,'w1','u1',891,1490712572,'Вывод средств со счета пользователя rootroot на w1'),
  (13,'f1','u1',9,1490712572,'Коммисия за вывод средств со счета пользователя rootroot на w1. Референс операции REF12'),
  (14,'u1','r2',11,1490713709,'Пополение счета пользователя rootroot через r2'),
  (15,'w2','u1',0.99,1490713721,'Вывод средств со счета пользователя rootroot на w2'),
  (16,'f1','u1',0.01,1490713721,'Коммисия за вывод средств со счета пользователя rootroot на w2. Референс операции REF15'),
  (17,'u1','r1',5,1490714923,'Пополение счета пользователя rootroot через r1'),
  (18,'w1','u1',4.95,1490714932,'Вывод средств со счета пользователя rootroot на w1'),
  (19,'f1','u1',0.05,1490714932,'Коммисия за вывод средств со счета пользователя rootroot на w1. Референс операции REF18'),
  (20,'u1','r1',222,1490716176,'Пополение счета пользователя rootroot через r1'),
  (21,'w2','u1',328.68,1490716183,'Вывод средств со счета пользователя rootroot на w2'),
  (22,'f1','u1',3.32,1490716183,'Коммисия за вывод средств со счета пользователя rootroot на w2. Референс операции REF21'),
  (23,'u1','r1',10,1490717928,'Пополение счета пользователя rootroot через r1'),
  (24,'w1','u1',9.9,1490717939,'Вывод средств со счета пользователя rootroot на w1'),
  (25,'f1','u1',0.1,1490717939,'Коммисия за вывод средств со счета пользователя rootroot на w1. Референс операции REF24'),
  (26,'u1','r3',1,1490717975,'Пополение счета пользователя rootroot через r3'),
  (27,'u1','r1',444,1490796274,'Пополение счета пользователя rootroot через r1'),
  (28,'w1','u1',435.6,1490796456,'Вывод средств со счета пользователя rootroot на w1'),
  (29,'f1','u1',4.4,1490796456,'Коммисия за вывод средств со счета пользователя rootroot на w1. Референс операции REF28'),
  (30,'w1','u1',4.95,1490796558,'Вывод средств со счета пользователя rootroot на w1'),
  (31,'f1','u1',0.05,1490796558,'Коммисия за вывод средств со счета пользователя rootroot на w1. Референс операции REF30');
COMMIT;

/* Data for the `users` table  (LIMIT 0,500) */

INSERT INTO `users` (`id`, `login`, `password`) VALUES
  (1,'rootroot','$2y$10$IlPWDSQI./a5n0npSntLqeWAR283Z82i.GM69Hy0xDebiZH1tjFS.');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;