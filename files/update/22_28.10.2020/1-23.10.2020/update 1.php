1. Alter table b2bso_detail (penambahan id46, qty46, kirim46)
ALTER TABLE `b2bso_detail` ADD `id46` INT(11) NULL DEFAULT NULL AFTER `kirim45`, ADD `qty46` FLOAT NULL DEFAULT NULL AFTER `id46`, ADD `kirim46` FLOAT NOT NULL DEFAULT '0' AFTER `qty46`;

2. Alter table b2bdo_detail (penambahan id46, qty46)
ALTER TABLE `b2bdo_detail` ADD `id46` INT(11) NULL AFTER `qty45`, ADD `qty46` FLOAT NULL AFTER `id46`;

2. Update trb2bsogrp_detail.php, trb2bsogrp_detail_edit.php, trb2bso_confirmed_send.php, trb2bso_confirmed_detail.php (penambahan kolom size 46)

3. Penambahan query untuk size 46 di trb2bso_save 

4. Perbaikan expedition pada bagian detail confirmed sales

5. Penghilangan region pada trb2bsogrp_detail_edit.php, trb2bso_confirmed_detail.php

6. Penambahan kolom 46 pada invoice Delivery Order B2B

7. Filter pada inventory

8. Update di inventory

9. Penambahan field update pada table inventory
ALTER TABLE `inventory` ADD `update` INT(11) NOT NULL DEFAULT '0' AFTER `lastmodified`;