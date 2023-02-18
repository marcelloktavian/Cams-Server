1. OLN191102741 Darminto WR COFFEE/CAPUCINO 42 (VALUE pada online summary 0, selisih 60.720)
2. OLN191104487 Pa Gunawan OWEN OXFORD 40, OWEN OXFORD 41, CHELSEA CHIA 36 (Disc manual, selisih 9.382)
3. OLN191104879 Novieca Shop OWEN ORCA 43 (total pada VALUE lebih 103.840)

SELECT * FROM olnso WHERE id_trans='OLN191102741'
SELECT * FROM olnsodetail WHERE id_trans='OLN191102741'
UPDATE olnso SET total='60720',faktur='60720' WHERE id_trans='OLN191102741'
UPDATE olnsodetail SET subtotal='75900' WHERE id_trans='OLN191102741'


SELECT * FROM olnso WHERE id_trans='OLN191104487'
SELECT * FROM olnsodetail WHERE id_trans='OLN191104487'
SELECT * FROM olnsodetail WHERE id_trans='OLN191104487'
UPDATE olnso SET total='60720',faktur='60720' WHERE id_trans='OLN191104487'

SELECT * FROM olnso WHERE id_trans='OLN191104879'
SELECT * FROM olnsodetail WHERE id_trans='OLN191104879'
UPDATE olnso SET total='103200',faktur='79200',transfer='103200' WHERE id_trans='OLN191104879'

SELECT do.*,so.nama,so.alamat,c.nama AS customer,e.nama AS expedition,s.nama AS salesman,DATE_FORMAT(do.tgl_trans,'%d/%m/%Y')  FROM `b2bdo` DO LEFT JOIN `b2bso` so ON do.id_transb2bso=so.id_trans LEFT JOIN `mst_b2bcustomer` c ON (do.id_customer=c.id) LEFT JOIN `mst_expedition` e ON (do.id_expedition=e.id) LEFT JOIN `mst_b2bsalesman` s ON (do.id_salesman=s.id) WHERE do.id_trans='BDO19110001'

SELECT d.namabrg,d.jumlah_beli,d.harga_satuan,d.disc,d.size, d.subtotal FROM b2bdo_detail d WHERE d.id_trans ='BDO19110001';
	    
SELECT do.*,(so.ref_kode) AS no_po,DATE_FORMAT(so.tgl_trans,'%d/%m/%Y')AS tglsales,so.nama,so.alamat,c.nama AS customer,e.nama AS expedition,s.nama AS salesman,DATE_FORMAT(do.tgl_trans,'%d/%m/%Y')AS tgl FROM `b2bdo` DO LEFT JOIN `b2bso` so ON do.id_transb2bso=so.id_trans LEFT JOIN `mst_b2bcustomer` c ON (do.id_customer=c.id) LEFT JOIN `mst_expedition` e ON (do.id_expedition=e.id) LEFT JOIN `mst_b2bsalesman` s ON (do.id_salesman=s.id) WHERE do.id_trans='BDO20010001'

SELECT dt.id_product,dt.harga_satuan,dt.subtotal,dt.disc,dt.namabrg,SUM(dt.jumlah_beli) AS totalqty ,SUM(IF((dt.size) = '36', dt.jumlah_beli, 0) ) AS s36 ,SUM(IF((dt.size) = '37', dt.jumlah_beli, 0) ) AS s37 ,SUM(IF((dt.size) = '38', dt.jumlah_beli, 0) ) AS s38 ,SUM(IF((dt.size) = '39', dt.jumlah_beli, 0) ) AS s39 ,SUM(IF((dt.size) = '40', dt.jumlah_beli, 0) ) AS s40 ,SUM(IF((dt.size) = '41', dt.jumlah_beli, 0) ) AS s41 ,SUM(IF((dt.size) = '42', dt.jumlah_beli, 0) ) AS s42 ,SUM(IF((dt.size) = '43', dt.jumlah_beli, 0) ) AS s43 ,SUM(IF((dt.size) = '44', dt.jumlah_beli, 0) ) AS s44 FROM b2bdo_detail dt WHERE dt.id_trans ='BDO19110001' GROUP BY dt.id_product ASC