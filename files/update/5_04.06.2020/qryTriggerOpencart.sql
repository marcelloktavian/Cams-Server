

SET @`outfull` := CONCAT('D:/bcs/data',CONCAT(DATE_FORMAT(NOW(), '%d%m%Y%i%s')), '_EMAIL.csv');    
SET @`qry` := CONCAT('SELECT o.order_id,o.firstname,o.lastname,o.payment_address_1,o.payment_address_2,o.payment_city,o.payment_postcode,o.payment_country,o.payment_zone,o.payment_method,o.payment_code,o.`shipping_firstname`,o.`shipping_address_1`,o.`shipping_city`,o.`shipping_postcode`,o.`shipping_country`,o.`shipping_method`,o.`shipping_code`,o.`comment`,o.`order_status_id`,p.`product_id`,p.`name`,p.quantity,p.`price`,o.`total`,s.`name`
INTO OUTFILE \'', @`outfull`, '\' 
FIELDS TERMINATED BY \';\' 
ENCLOSED BY \'"\' 
LINES TERMINATED BY \'\n\' 
FROM oc_order o INNER JOIN oc_order_product p ON o.`order_id`=p.`order_id` LEFT JOIN oc_order_status s ON o.`order_status_id`=s.`order_status_id`
');    
PREPARE `stmt` FROM @`qry`;
SET @`qry` := NULL;
EXECUTE `stmt`;
DEALLOCATE PREPARE `stmt`;