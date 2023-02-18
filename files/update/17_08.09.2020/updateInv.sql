-- 1.Create table inventory
CREATE TABLE `invmasuk` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `id_trans` VARCHAR(15) DEFAULT NULL,
  `id_inventory` INT(11) DEFAULT NULL,
  `kode` VARCHAR(10) NOT NULL,
  `tgl_trans` DATETIME DEFAULT NULL,
  `nama` VARCHAR(50) NOT NULL,
  `catatan` TEXT,
  `totalqty` DOUBLE DEFAULT '0',
  `state` ENUM('0','1','2') NOT NULL DEFAULT '0',
  `user` VARCHAR(50) DEFAULT NULL,
  `deleted` INT(1) DEFAULT '0',
  `lastmodified` DATETIME DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_trans` (`id_trans`)
) ENGINE=INNODB DEFAULT CHARSET=latin1
-- 2 Create tbl mst inventory

CREATE TABLE `mst_inventory` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `nama` VARCHAR(200) NOT NULL,
  `catatan` TEXT,
  `aktif` ENUM('Y','N') DEFAULT 'Y',
  `deleted` INT(11) DEFAULT '0',
  `user` VARCHAR(50) DEFAULT NULL,
  `lastmodified` DATETIME DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MYISAM DEFAULT CHARSET=latin1
-- 3. Create tbl invmasuk_detail
CREATE TABLE `invmasuk_detail` (
  `id_inv_d` INT(11) NOT NULL AUTO_INCREMENT,
  `id_trans` VARCHAR(15) NOT NULL,
  `id_product` INT(11) NOT NULL,
  `namabrg` VARCHAR(50) DEFAULT NULL,
  `size` VARCHAR(10) DEFAULT NULL,
  `jumlah_beli` FLOAT NOT NULL,
  `subtotal` DECIMAL(10,0) NOT NULL,
  `id_oln_auto` INT(11) DEFAULT NULL,
  PRIMARY KEY (`id_inv_d`)
) ENGINE=INNODB DEFAULT CHARSET=latin1
-- 4. Create table inventory
CREATE TABLE `inventory` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `id_trans` VARCHAR(15) NOT NULL,
  `id_product` INT(11) NOT NULL,
  `namabrg` VARCHAR(50) DEFAULT NULL,
  `size` VARCHAR(10) DEFAULT NULL,
  `qty` FLOAT NOT NULL,
  `lastmodified` DATETIME NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=INNODB DEFAULT CHARSET=latin1
-- 5. Create table invkeluar
CREATE TABLE `invkeluar` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `id_trans` VARCHAR(15) DEFAULT NULL,
  `id_inventory` INT(11) DEFAULT NULL,
  `kode` VARCHAR(10) NOT NULL,
  `tgl_trans` DATETIME DEFAULT NULL,
  `nama` VARCHAR(50) NOT NULL,
  `catatan` TEXT,
  `totalqty` DOUBLE DEFAULT '0',
  `state` ENUM('0','1','2') NOT NULL DEFAULT '0',
  `user` VARCHAR(50) DEFAULT NULL,
  `deleted` INT(1) DEFAULT '0',
  `lastmodified` DATETIME DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_trans` (`id_trans`)
) ENGINE=INNODB DEFAULT CHARSET=latin1
-- 6. Create tbl invkeluar_detail
CREATE TABLE `invkeluar_detail` (
  `id_inv_d` INT(11) NOT NULL AUTO_INCREMENT,
  `id_trans` VARCHAR(15) NOT NULL,
  `id_product` INT(11) NOT NULL,
  `namabrg` VARCHAR(50) DEFAULT NULL,
  `size` VARCHAR(10) DEFAULT NULL,
  `jumlah_beli` FLOAT NOT NULL,
  `subtotal` DECIMAL(10,0) NOT NULL,
  `id_oln_auto` INT(11) DEFAULT NULL,
  PRIMARY KEY (`id_inv_d`)
) ENGINE=INNODB DEFAULT CHARSET=latin1

CREATE TABLE `inventory_balance` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `oln_product_id` INT(11) DEFAULT NULL,
  `kode` VARCHAR(10) DEFAULT NULL,
  `nama` VARCHAR(50) NOT NULL,
  `harga` DOUBLE DEFAULT NULL,
  `type` VARCHAR(5) DEFAULT NULL,
  `size` VARCHAR(10) DEFAULT NULL,
  `id_category` INT(11) DEFAULT NULL,
  `stok` INT(11) DEFAULT '0',
  `deleted` INT(11) DEFAULT '0',
  `user` VARCHAR(50) DEFAULT NULL,
  `lastmodified` DATETIME DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MYISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1



