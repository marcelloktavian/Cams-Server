ALTER TABLE `cams_db2020`.`olnso`
  CHANGE `state` `state` ENUM ('0', '1', '2') CHARSET latin1 COLLATE latin1_swedish_ci NOT NULL;
 
ALTER TABLE `cams_db2020`.`mst_expedition`
  ADD COLUMN `id_oln` INT NULL AFTER `kode`;

ALTER TABLE `cams_db2020`.`mst_expedition` ADD COLUMN `logo` VARCHAR(50) NULL AFTER `kode_warna`; 

-- daftar update file  
-- 1.trolndo,trolnso_notabd,trolnso_3nota_bd,rpt_printorder idx,rpt_printorder_abs,rpt_printorder_exp
-- 2.master_online/expedition,expedition_form
-- report online harap diubah expedisinya agar yang ditampilkan sesuai dengan transaksi
-- label diperbaiki(dipepetkan) dengan ditambah experiment label ekspedisi dan logonya
-- expedisi di camou.co.id dijodohkan dengan expedisi di cams..agar otomatis
