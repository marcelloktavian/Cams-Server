<?php
error_reporting(0);
include("../../include/koneksi.php");
include("../../include/config.php");
$tglstart = $_GET['start'];
$tglend = $_GET['end'];
$type = $_GET['type'];

$query = "SELECT
d.nama AS dropshipper,
	COUNT(IF(m.piutang = 0,m.id_trans,NULL)) as order_cash,
	SUM(IF(m.piutang = 0,m.totalqty,0)) as qty_cash,
	SUM(IF(m.piutang = 0,m.faktur,0)) as f_cash,
	COUNT(IF(m.piutang > 0,m.id_trans,NULL)) as order_cr,
	SUM(IF(m.piutang > 0,m.totalqty,0)) as qty_cr,
	SUM(IF(m.piutang > 0,m.faktur,0)) as f_cr,
	COUNT(m.id_trans) as order_all,
	SUM(m.totalqty) as qty_all,
	SUM(m.faktur) as f_all,
	ROUND(SUM(m.faktur) / 1.11) AS dpp_all,
	ROUND((SUM(m.faktur) / 1.11) * 0.11) as ppn_all,
	SUM(exp_fee) as ongkir
FROM olnso m LEFT JOIN mst_dropshipper d ON m.id_dropshipper = d.id 
WHERE m.deleted = 0 AND m.state = '1' AND DATE( m.lastmodified ) 
BETWEEN STR_TO_DATE( '$tglstart', '%d/%m/%Y' ) AND STR_TO_DATE( '$tglend', '%d/%m/%Y' ) 
GROUP BY m.id_dropshipper ORDER BY d.nama ASC";
$data = $db->query($query)->fetchAll(PDO::FETCH_ASSOC);

$no = 1;

$order_cash = 0;
$qty_cash = 0;
$f_cash = 0;
$order_cr = 0;
$qty_cr = 0;
$f_cr = 0;
$order_all = 0;
$qty_all = 0;
$f_all = 0;
$dpp_all = 0;
$ppn_all = 0;
$ongkir = 0;

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OLN LAPORAN PENJUALAN CASH + CREDIT</title>
    <script src="../../assets/js/jsbilangan.js" type="text/javascript"></script>
    <script type="text/javascript" src="../../assets/js/jquery-1.4.js"></script>
    <style type="text/css">
        .title {
            font-size: large;
            font-weight: bold;
        }

        .title_dir {
            display: flex;
            flex-direction: row;
            justify-content: space-between;
        }

        @page {
            size: A4;
            margin: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid black;
            padding: 4px;
            text-align: center;
        }

        .data {
            border: 1px solid black;
            padding: 8px;
            text-align: left;

        }

        tbody {
            font-size: 14px;
        }

        .zero {
            color: #e0e0e0;
        }

        .bold {
            font-weight: bold;
            font-size: large;
        }
    </style>
</head>

<body>
    <div class="title_dir">
        <span class="title">
            OLN LAPORAN PENJUALAN CASH + CREDIT
        </span>
        <span id="timestamp"><?php date_default_timezone_set('Asia/Jakarta');
                                echo $timestamp = date('d/m/Y H:i:s'); ?>
        </span>
    </div>
    <div style="margin-bottom: 20px;">
        <span><?php echo "" . $tglstart; ?>&nbsp;-&nbsp;<?php echo "" . $tglend; ?></span>
    </div>

    <table>
        <thead>
            <tr>
                <th rowspan="2">NO</th>
                <th rowspan="2">Nama Customer</th>
                <th colspan="3">Penjualan Cash</th>
                <th colspan="3">Penjualan Credit</th>
                <th colspan="5">Penjualan OLN + Credit</th>
                <th rowspan="2">Ongkos Kirim</th>
            </tr>
            <tr>
                <th>Total QTY</th>
                <th>Total Order</th>
                <th>Penjualan Bruto</th>
                <th>Total QTY</th>
                <th>Total Order</th>
                <th>Penjualan Bruto</th>
                <th>Total QTY</th>
                <th>Total Order</th>
                <th>Penjualan Bruto</th>
                <th>DPP</th>
                <th>PPN</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($data as $d) : ?>
                <tr>
                    <td><?= $no ?></td>
                    <td class="data"><?= $d['dropshipper'] ?></td>
                    <td class="<?= $d['qty_cash'] == 0 ? 'zero'  : '' ?>"><?= number_format($d['qty_cash']) ?></td>
                    <td class="<?= $d['order_cash'] == 0 ? 'zero'  : '' ?>"><?= number_format($d['order_cash']) ?></td>
                    <td class="<?= $d['f_cash'] == 0 ? 'zero'  : '' ?>"><?= number_format($d['f_cash']) ?></td>
                    <td class="<?= $d['qty_cr'] == 0 ? 'zero'  : '' ?>"><?= number_format($d['qty_cr']) ?></td>
                    <td class="<?= $d['order_cr'] == 0 ? 'zero'  : '' ?>"><?= number_format($d['order_cr']) ?></td>
                    <td class="<?= $d['f_cr'] == 0 ? 'zero'  : '' ?>"><?= number_format($d['f_cr']) ?></td>
                    <td class="<?= $d['qty_all'] == 0 ? 'zero'  : '' ?>"><?= number_format($d['qty_all']) ?></td>
                    <td class="<?= $d['order_all'] == 0 ? 'zero'  : '' ?>"><?= number_format($d['order_all']) ?></td>
                    <td class="<?= $d['f_all'] == 0 ? 'zero'  : '' ?>"><?= number_format($d['f_all']) ?></td>
                    <td class="<?= $d['dpp_all'] == 0 ? 'zero'  : '' ?>"><?= number_format($d['dpp_all']) ?></td>
                    <td class="<?= $d['ppn_all'] == 0 ? 'zero'  : '' ?>"><?= number_format($d['ppn_all']) ?></td>
                    <td class="<?= $d['ongkir'] == 0 ? 'zero'  : '' ?>"><?= number_format($d['ongkir']) ?></td>
                </tr>
            <?php $no++;
                $order_cash += $d['order_cash'];
                $qty_cash += $d['qty_cash'];
                $f_cash += $d['f_cash'];
                $order_cr += $d['order_cr'];
                $qty_cr += $d['qty_cr'];
                $f_cr += $d['f_cr'];
                $order_all += $d['order_all'];
                $qty_all += $d['qty_all'];
                $f_all += $d['f_all'];
                $dpp_all += $d['dpp_all'];
                $ppn_all += $d['ppn_all'];
                $ongkir += $d['ongkir'];
            endforeach; ?>
            <tr class="bold">
                <td colspan="2">Total</td>
                <td><?= number_format($order_cash) ?></td>
                <td><?= number_format($qty_cash) ?></td>
                <td><?= number_format($f_cash) ?></td>
                <td><?= number_format($order_cr) ?></td>
                <td><?= number_format($qty_cr) ?></td>
                <td><?= number_format($f_cr) ?></td>
                <td><?= number_format($order_all) ?></td>
                <td><?= number_format($qty_all) ?></td>
                <td><?= number_format($f_all) ?></td>
                <td><?= number_format($dpp_all) ?></td>
                <td><?= number_format($ppn_all) ?></td>
                <td><?= number_format($ongkir) ?></td>
            </tr>
        </tbody>
    </table>



    <script language="javascript">
        $(document).ready(function() {
            setInterval(timestamp, 1000);
        });

        function timestamp() {
            $.ajax({
                url: '../timestamp.php',
                success: function(data) {
                    $('#timestamp').html(data);
                },
            });
        }

        window.print();
    </script>
</body>

</html>