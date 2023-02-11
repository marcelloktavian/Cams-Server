SELECT p.deposit,SUM(p.harga_satuan) AS harga_satuan,SUM(p.tax) AS tax,p.total AS grandtotal,p.subtotal,p.oln_order_id,SUM(p.jumlah_beli) AS qty, SUM(p.subtotal) AS total,SUM(p.deposit) AS grand_deposit,p.oln_note,p.oln_expnote,d.disc AS discdp FROM `olnpreso` p LEFT JOIN mst_dropshipper d ON p.id_dropshipper=d.id  WHERE p.oln_order_id ='130688' GROUP BY p.oln_order_id 
UNION ALL
SELECT p.deposit,SUM(p.harga_satuan) AS harga_satuan,SUM(p.tax) AS tax,p.total AS grandtotal,p.subtotal,p.oln_order_id,SUM(p.jumlah_beli) AS qty, SUM(p.subtotal) AS total,SUM(p.deposit) AS grand_deposit,p.oln_note,p.oln_expnote,d.disc AS discdp FROM `olnpreso` p LEFT JOIN mst_dropshipper d ON p.id_dropshipper=d.id
WHERE p.oln_order_id ='132811' GROUP BY p.oln_order_id 
UNION ALL
SELECT p.deposit,SUM(p.harga_satuan) AS harga_satuan,SUM(p.tax) AS tax,p.total AS grandtotal,p.subtotal,p.oln_order_id,SUM(p.jumlah_beli) AS qty, SUM(p.subtotal) AS total,SUM(p.deposit) AS grand_deposit,p.oln_note,p.oln_expnote,d.disc AS discdp FROM `olnpreso` p LEFT JOIN mst_dropshipper d ON p.id_dropshipper=d.id WHERE p.oln_order_id ='132814' GROUP BY p.oln_order_id 

SELECT p.harga_satuan,p.tax,p.total AS grandtotal,p.subtotal,p.oln_order_id,p.jumlah_beli AS qty, p.subtotal,p.deposit AS grand_deposit,p.oln_note,p.oln_expnote,d.disc AS discdp FROM `olnpreso` p LEFT JOIN mst_dropshipper d ON p.id_dropshipper=d.id WHERE p.oln_order_id ='132814' GROUP BY p.oln_order_id 
