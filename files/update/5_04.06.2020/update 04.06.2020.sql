1a. Aplikasi lama dicopy dan direname jadi cams_2019, lalu aplikasi jadi cams_2020
1. UPDATE trolnso_detail penambahan fungsi css untuk expcode...done AND tested
2. pengosongan id_shipping dan pemasangan INDEX di olnso agar lebih cepat.. done AND teste
-- caranya dbase lama dibackup dulu dan create dbase baru cams_db2020,lalu dbasetl olnso_idnya dibackup dulu lalu ditruncate
-- setelah itu dibuat index di dbase barunya
ALTER TABLE `cams_db`.`olnso_id`
  ADD UNIQUE INDEX `id_trans` (`id_trans`);
 
EXPLAIN EXTENDED
SELECT p.*,j.nama AS dropshipper,e.nama AS expedition FROM `olnso` p LEFT JOIN `mst_dropshipper` j ON (p.id_dropshipper=j.id) LEFT JOIN `mst_expedition` e ON (p.id_expedition=e.id)
WHERE TRUE AND p.state='0' AND (p.totalqty <> 0) AND (p.piutang= 0) AND (p.deleted=0)

EXPLAIN EXTENDED
SELECT p.*,j.nama AS dropshipper,e.nama AS expedition
,i.id AS id_kirim 
FROM `olnso` p LEFT JOIN `mst_dropshipper` j ON (p.id_dropshipper=j.id) 
LEFT JOIN `mst_expedition` e ON (p.id_expedition=e.id) 
LEFT JOIN `olnso_id` i ON (p.id_trans=i.id_trans) 
WHERE TRUE AND p.state = '1' AND DATE(p.lastmodified) BETWEEN STR_TO_DATE('18/05/2020','%d/%m/%Y') AND STR_TO_DATE('18/05/2020','%d/%m/%Y')


3. riset IMPORT FROM xls....done
4. UPDATE mst_products ada oln_product_id
ALTER TABLE `cams_db`.`mst_products`
  ADD COLUMN `oln_product_id` INT NULL AFTER `id`;

5. UPDATE mst_dropshipper ada oln_customer_id
ALTER TABLE `cams_db`.`mst_dropshipper`
  CHANGE `id_jabatan` `oln_customer_id` INT (11) NULL;

