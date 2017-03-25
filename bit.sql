/* SQL Manager for MySQL                              5.6.2.48160 */
/* -------------------------------------------------------------- */
/* Host     : localhost                                           */
/* Port     : 3306                                                */
/* Database : bit                                                 */


/* Structure for the `operations` table : */

CREATE TABLE `operations` (
  `operationId` INTEGER(11) NOT NULL AUTO_INCREMENT,
  `debId` VARCHAR(20) COLLATE utf8_general_ci NOT NULL,
  `credId` VARCHAR(20) COLLATE utf8_general_ci NOT NULL,
  `amount` DOUBLE NOT NULL,
  `dateOperation` DATETIME NOT NULL,
  `description` VARCHAR(200) COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY USING BTREE (`operationId`)
) ENGINE=InnoDB
AUTO_INCREMENT=4 CHARACTER SET 'utf8' COLLATE 'utf8_general_ci'
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

/* Data for the `operations` table  (LIMIT 0,500) */

INSERT INTO `operations` (`operationId`, `debId`, `credId`, `amount`, `dateOperation`, `description`) VALUES
  (1,'u1','r2',111,'2017-03-24 15:15:35','Пополение счета пользователя rootroot через r2'),
  (2,'w1','u1',99,'2017-03-24 15:15:48','Вывод средств со счета пользователя rootroot на w1'),
  (3,'f1','u1',1,'2017-03-24 15:15:48','Коммисия за вывод средств со счета пользователя rootroot на w1. Референц операции REF2');
COMMIT;

/* Data for the `users` table  (LIMIT 0,500) */

INSERT INTO `users` (`id`, `login`, `password`) VALUES
  (1,'rootroot','$2y$10$IlPWDSQI./a5n0npSntLqeWAR283Z82i.GM69Hy0xDebiZH1tjFS.');
COMMIT;