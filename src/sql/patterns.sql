--
-- Table structure for table `tcneo_patterns`
--

DROP TABLE IF EXISTS `tcneo_patterns`;
CREATE TABLE IF NOT EXISTS `tcneo_patterns`
(
  `id`          int(11)                                                 NOT NULL AUTO_INCREMENT,
  `name`        varchar(40) CHARACTER SET utf8 COLLATE utf8_general_ci           DEFAULT NULL,
  `description` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `abs1`        int(11)                                                          DEFAULT NULL,
  `abs2`        int(11)                                                          DEFAULT NULL,
  `abs3`        int(11)                                                          DEFAULT NULL,
  `abs4`        int(11)                                                          DEFAULT NULL,
  `abs5`        int(11)                                                          DEFAULT NULL,
  `abs6`        int(11)                                                          DEFAULT NULL,
  `abs7`        int(11)                                                          DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE = MyISAM
  AUTO_INCREMENT = 1
  DEFAULT CHARSET = utf8
  COLLATE = utf8_bin;

ALTER TABLE `tcneo_patterns`
  ADD CONSTRAINT `fk_absence1` FOREIGN KEY (`abs1`) REFERENCES `tcneo_absences` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_absence2` FOREIGN KEY (`abs2`) REFERENCES `tcneo_absences` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_absence3` FOREIGN KEY (`abs3`) REFERENCES `tcneo_absences` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_absence4` FOREIGN KEY (`abs4`) REFERENCES `tcneo_absences` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_absence5` FOREIGN KEY (`abs5`) REFERENCES `tcneo_absences` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_absence6` FOREIGN KEY (`abs6`) REFERENCES `tcneo_absences` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_absence7` FOREIGN KEY (`abs7`) REFERENCES `tcneo_absences` (`id`) ON DELETE SET NULL;
