1. Penambahan table group
CREATE TABLE `group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(25) NOT NULL,
  `desc` text,
  `deleted` char(1) NOT NULL,
  PRIMARY KEY (`id`)
);

2. Penambahan table menu
CREATE TABLE `menu` (
  `menu_id` int(11) NOT NULL AUTO_INCREMENT,
  `menu_group` varchar(20) NOT NULL,
  `menu_name` varchar(30) NOT NULL,
  `menu_parent` varchar(20) DEFAULT NULL,
  `url` varchar(50) NOT NULL,
  `hide` smallint(6) NOT NULL,
  `policy` varchar(30) NOT NULL,
  `span_id` varchar(20) DEFAULT NULL,
  `icon` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`menu_id`)
);

3. Penambahan table group_access
CREATE TABLE `group_access` (
  `menu_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `policy` varchar(30) NOT NULL,
  PRIMARY KEY (`menu_id`,`group_id`)
);

4. Penambahan field group_id di table user
ALTER TABLE `user` ADD `group_id` INT(11) NOT NULL AFTER `deleted`;

5. Insert table group
insert  into `group`(`id`,`nama`,`desc`,`deleted`) values 
(1,'Superadmin','Superadmin','0'),
(2,'Supervisor','Supervisor','0'),
(3,'Operator','Operator','0');

6. Insert table menu
insert  into `menu`(`menu_id`,`menu_group`,`menu_name`,`menu_parent`,`url`,`hide`,`policy`,`span_id`,`icon`) values 
(1,'Online Database','Online Database',NULL,'#',0,'VIEW;',NULL,NULL),
(2,'Online Database','Address (Alamat)','1','pages/master_online/alamat.php',0,'VIEW;ADD;EDIT;DELETE;','address',NULL),
(3,'Online Database','Category','1','pages/master_online/category.php',0,'VIEW;ADD;EDIT;DELETE;','category',NULL),
(4,'Online Database','Colour (Warna)','1','pages/master_online/colour.php',0,'VIEW;ADD;EDIT;DELETE;','mstcolour',NULL),
(5,'Online Database','Dropshipper','1','pages/master_online/dropshipper.php',0,'VIEW;ADD;EDIT;DELETE;','dropshipper',NULL),
(6,'Online Database','Expedition Category','1','pages/master_online/expeditioncat.php',0,'VIEW;ADD;EDIT;DELETE;','expeditioncat',NULL),
(7,'Online Database','Expedition','1','pages/master_online/expedition.php',0,'VIEW;ADD;EDIT;DELETE;','expedition',NULL),
(8,'Online Database','Products','1','pages/master_online/products.php',0,'VIEW;ADD;EDIT;DELETE;','products',NULL),
(9,'Online Import','Online Import',NULL,'#',0,'VIEW;',NULL,NULL),
(10,'Online Import','Import CAMOU','9','pages/import_XLS/importcamou.php',0,'VIEW;','IMPORT',NULL),
(11,'Online Import','Pre SALES','9','pages/import_XLS/olnpreso.php',0,'VIEW;DELETE;POST;','presales',NULL),
(12,'Online Import','Import Credit','9','pages/import_XLS_credit/importcamoucredit.php',0,'VIEW;','IMPORT',NULL),
(13,'Online Import','Pre SALES Credit','9','pages/import_XLS_credit/olnpreso.php',0,'VIEW;','presalescr',NULL),
(14,'Online Transaction','Online Transaction',NULL,'#',0,'VIEW;',NULL,NULL),
(15,'Online Transaction','Dropshipper Deposit','14','pages/sales_online/deposit_dropshipper.php',0,'VIEW;','depositpelanggan',NULL),
(16,'Online Transaction','Deposit Transaction','14','pages/sales_online/trolndeposit.php',0,'VIEW;ADD;EDIT;DELETE;','trdeposittoko',NULL),
(17,'Online Transaction','Online Sales','14','pages/sales_online/trolnso.php',0,'VIEW;ADD;DELETE;POST;','onlinesales',NULL),
(18,'Online Transaction','Online Credit','14','pages/sales_online/trolnsocr.php',0,'VIEW;ADD;DELETE;POST;','onlinesalescr',NULL),
(19,'Online Transaction','Pending Order','14','pages/sales_online/troln_unservice.php',0,'VIEW;DELETE;POST;PRINT;','onlineunservice',NULL),
(20,'Online Transaction','Cancel Order (Online)','14','pages/sales_online/troln_cancel.php',0,'VIEW;PRINT;','onlinecancel',NULL),
(21,'Online Transaction','Online Delivery','14','pages/sales_online/trolndo.php',0,'VIEW;DELETE;PRINT;','onlinedo',NULL),
(22,'Online Transaction','Archive Order','14','pages/sales_online/troln_archive.php',0,'VIEW;DELETE;POST;PRINT;RETURN;','onlinearchive',NULL),
(23,'Online Transaction','Online Return','14','pages/sales_online/troln_return.php',0,'VIEW;DELETE;POST;PRINT;','onlinereturn',NULL),
(24,'Online Transaction','Return Confirmed','14','pages/sales_online/troln_return_confirmed.php',0,'VIEW;DELETE;POST;PRINT;','olnreturn_cf',NULL),
(25,'Online Summary','Online Summary',NULL,'#',0,'VIEW;',NULL,NULL),
(26,'Online Summary','Online Summary','25','pages/summary_online/trolnso_sum.php',0,'VIEW;PRINT;','smoln',NULL),
(27,'Online Summary','Summary Cash','25','pages/summary_online/trolnso_sumcash.php',0,'VIEW;PRINT;','smolncash',NULL),
(28,'Online Summary','Summary Credit','25','pages/summary_online/trolnso_sumcr.php',0,'VIEW;PRINT;','smolncr',NULL),
(29,'Online Summary','Dropshipper Statistik','25','pages/summary_online/dp_sumidx.php',0,'VIEW;PRINT;','dp_oln',NULL),
(30,'Online Summary','Sales Online Dropshipper','25','pages/summary_online/oln_dropshipperidx.php',0,'VIEW;PRINT;','olndropshipper',NULL),
(31,'Online Summary','Unpaid Online','25','pages/summary_online/oln_unpaid.php',0,'VIEW;PRINT;POST;','smunpaid',NULL),
(32,'Online Summary','Bill','25','pages/summary_online/piutang.php',0,'VIEW;PRINT;','smbill',NULL),
(33,'Report Online','Report Online',NULL,'#',0,'VIEW;',NULL,NULL),
(34,'Report Online','Expedition','33','pages/report_online/rpt_expedtionidx.php',0,'VIEW;PRINT;','rptexpedition',NULL),
(35,'Report Online','Print Order','33','pages/report_online/rpt_printorderidx.php',0,'VIEW;PRINT;','rptprint',NULL),
(36,'Report Online','Product Sold','33','pages/report_online/rpt_productidx.php',0,'VIEW;PRINT;','productsold',NULL),
(37,'Report Online','Trouble Order','33','pages/report_online/rpt_trouble.php',0,'VIEW;DELETE;','rpttrouble',NULL),
(38,'B2B databases','B2B databases',NULL,'#',0,'VIEW;',NULL,NULL),
(39,'B2B databases','1_Composition Products','38','pages/master_b2b/composition.php',0,'VIEW;ADD;EDIT;DELETE;','composition',NULL),
(40,'B2B databases','2_B2B Products','38','pages/master_b2b/b2bproducts.php',0,'VIEW;ADD;EDIT;DELETE;','b2bproducts',NULL),
(41,'B2B databases','3_B2B Products Group','38','pages/master_b2b/b2bproductsgrp.php',0,'VIEW;ADD;EDIT;DELETE;','b2bproductsgrp',NULL),
(42,'B2B databases','4_B2B Customer','38','pages/master_b2b/b2bcustomer.php',0,'VIEW;ADD;EDIT;DELETE;','b2bcustomer',NULL),
(43,'B2B databases','5_B2B Expedition','38','pages/master_b2b/b2bexpedition.php',0,'VIEW;ADD;EDIT;DELETE;','b2bexpedition',NULL),
(44,'B2B databases','6_B2B Salesman','38','pages/master_b2b/b2bsalesman.php',0,'VIEW;ADD;EDIT;DELETE;','b2bsalesman',NULL),
(45,'B2B Transactions','B2B Transactions',NULL,'#',0,'VIEW;',NULL,NULL),
(46,'B2B Transactions','Add Sales B2B','45','pages/sales_b2b/trb2bso_add.php',0,'VIEW;ADD;','trb2bso_add',NULL),
(47,'B2B Transactions','Sales B2B','45','pages/sales_b2b/trb2bso.php',0,'VIEW;EDIT;DELETE;POST;','trb2bso',NULL),
(48,'B2B Transactions','Confirmed Sales','45','pages/sales_b2b/trb2bso_confirmed.php',0,'VIEW;POST;PRINT;','trb2bso_confirmed',NULL),
(49,'B2B Transactions','Delivery Order B2B','45','pages/sales_b2b/trb2bdo.php',0,'VIEW;DELETE;PRINT;','trb2bdo',NULL),
(50,'B2B Transactions','Product Sold Compositions','45','pages/sales_b2b/trb2bso_composition.php',0,'VIEW;','trb2bcomp',NULL),
(51,'B2B Summary','B2B Summary',NULL,'#',0,'VIEW;',NULL,NULL),
(52,'B2B Summary','Summary Delivery B2B','51','pages/summary_b2b/trb2bdo_idx.php',0,'VIEW;PRINT;','do_b2b',NULL),
(53,'INVENTORY','INVENTORY',NULL,'#',0,'VIEW;',NULL,NULL),
(54,'INVENTORY','MUTASI MASUK GUDANG','53','pages/inventory/invmasuk.php',0,'VIEW;ADD;EDIT;DELETE;POST;','mutasi_masuk',NULL),
(55,'INVENTORY','MUTASI KELUAR GUDANG','53','pages/inventory/invkeluar.php',0,'VIEW;ADD;EDIT;DELETE;POST;','mutasi_keluar',NULL),
(56,'INVENTORY','INVENTORY','53','pages/inventory/invstok.php',0,'VIEW;POST;','inventory',NULL),
(57,'Setting','Setting',NULL,'#',0,'VIEW;',NULL,NULL),
(58,'Setting','Data Pengguna','57','pages/setting/dataUser.php',0,'VIEW;ADD;EDIT;DELETE;','dataPengguna',NULL),
(59,'Setting','User Group','57','pages/setting/userGroup.php',0,'VIEW;ADD;EDIT;DELETE;','userGroup',NULL),
(60,'Setting','Group Akses','57','pages/setting/groupAkses.php',0,'VIEW;ADD;','groupAkses',NULL);

7. Pembuatan Form User Group

8. Pembuatan Form Data Pengguna