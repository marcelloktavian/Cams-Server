
CREATE TABLE `mst_expeditioncat` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `nama` VARCHAR(50) NOT NULL,
  `aktif` ENUM('Y','N') DEFAULT 'Y',
  `deleted` INT(11) DEFAULT '0',
  `user` VARCHAR(50) DEFAULT NULL,
  `lastmodified` DATETIME DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MYISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1

ALTER TABLE `cams_db2020`.`mst_expedition`
  ADD COLUMN `id_expeditioncat` INT NULL AFTER `kode_warna`;
0. Menu.php
1. expeditioncat.php
2. expeditioncat_form.php
3. expedition.php
4. expedition_form.php
5. rptprintorderdx
6. rptprintorder_exp
