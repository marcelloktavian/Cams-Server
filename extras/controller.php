<?php
include "./conf.php";

// echo json_encode($_GET);
// die;

$method = isset($_GET['method']) ? $_GET['method'] : '';

if ($method != '') {
    if ($method == 'index') {
        $data = $db->query("SELECT COUNT(id_trans) as jumlah_baris,
        'Transaksi Selisih' as event,
        'running' as status,
        DATE_FORMAT(NOW(),'%d %M %Y') as last
        
        FROM (
            SELECT
                o.id_trans,
                o.total - o.exp_fee as value,
                (-1 * d.deposit) as deposit,
                j.total_debet,
                j.debet,
                o.lastmodified
            FROM
                `olnso` o
                JOIN olndeposit d ON o.id_trans = d.id_trans
                JOIN (		
                    SELECT
                        SUBSTRING_INDEX(j.keterangan,' - ',-1) as id,
                        j.total_debet,
                        SUM(jd.debet) as debet
                    FROM
                        jurnal j
                        JOIN jurnal_detail jd ON j.id = jd.id_parent AND j.deleted = 0 AND j.`status` = 'OLN' AND j.keterangan LIKE '%OLN%'
                    GROUP BY SUBSTRING_INDEX(j.keterangan,' - ',-1)
                    HAVING id LIKE 'OLN%' AND total_debet <> debet
                ) j ON d.id_trans = j.id
            WHERE
                o.deleted = 0 
                AND o.state = '1'
                AND YEAR(o.lastmodified) >= 2024
                HAVING (value <> deposit OR value <> debet OR deposit <> total_debet OR debet <> total_debet)
        ) AS subquery;
        ")->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($data);
        die;
    } else if ($method == 'detail') {
        $data = $db->query("SELECT
        o.id_trans,
        o.total - o.exp_fee as value,
        (-1 * d.deposit) as deposit,
        j.total_debet,
        j.debet,
        o.lastmodified
    FROM
        `olnso` o
        JOIN olndeposit d ON o.id_trans = d.id_trans
        JOIN (		
            SELECT
                SUBSTRING_INDEX(j.keterangan,' - ',-1) as id,
                j.total_debet,
                SUM(jd.debet) as debet
            FROM
                jurnal j
                JOIN jurnal_detail jd ON j.id = jd.id_parent AND j.deleted = 0 AND j.`status` = 'OLN' AND j.keterangan LIKE '%OLN%'
            GROUP BY SUBSTRING_INDEX(j.keterangan,' - ',-1)
            HAVING id LIKE 'OLN%' AND total_debet <> debet
        ) j ON d.id_trans = j.id
    WHERE
        o.deleted = 0 
        AND o.state = '1'
        AND YEAR(o.lastmodified) >= 2024
        HAVING (value <> deposit OR value <> debet OR deposit <> total_debet OR debet <> total_debet)")->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($data);
        die;
    }
} else {
    echo json_encode(['msg' => 'unknown controller']);
}
