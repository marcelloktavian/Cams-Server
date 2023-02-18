-- cek -- 
SHOW PROCESSLIST
-- set event
SET GLOBAL event_scheduler = ON;

CREATE EVENT update_inventory
ON SCHEDULE EVERY 60 SECOND
STARTS CURRENT_TIMESTAMP
-- ENDS CURRENT_TIMESTAMP + INTERVAL 1 HOUR
DO
   UPDATE inventory_balance ib SET ib.stok=ib.stok+(SELECT SUM(i.qty) FROM inventory i  WHERE i.id_product=ib.id ),ib.lastmodified=NOW();

-- drop event   
DROP EVENT update_inventory 

-- untuk update stok_balance inventory per tanggal
UPDATE inventory_balance ib SET ib.stok=(SELECT SUM(i.qty) FROM inventory i  WHERE i.id_product=ib.id AND DATE(i.lastmodified) <= ('$startdate') ),ib.lastmodified=NOW();

-- RESET STOK 10000
UPDATE inventory_balance ib SET ib.stok=10000,ib.lastmodified=NOW();

