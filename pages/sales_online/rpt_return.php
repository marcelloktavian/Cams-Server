<?php
error_reporting(0);
include("../../include/koneksi.php");
$tglstart = $_GET['start'];
$tglend = $_GET['end'];
$filter = $_GET['filter_rn'];
$query  = "SELECT d.nama,SUM(totalqty) as qty,SUM(faktur + penalty) as bruto,SUM(penalty) as penalty,SUM(total) as total
FROM olnsoreturn r LEFT JOIN mst_dropshipper d ON r.id_dropshipper = d.id
WHERE r.deleted = 0 AND DATE( r.lastmodified ) BETWEEN STR_TO_DATE( '$tglstart', '%d/%m/%Y' ) AND STR_TO_DATE( '$tglend', '%d/%m/%Y' )
GROUP BY d.id";
$data = mysql_query($query);
$num = 1;

$qty = 0;
$bruto = 0;
$penalty = 0;
$total = 0;

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OLN LAPORAN RETUR</title>
    <style>
        :root {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        @page {
            size: A4;
            margin: 15px;
        }

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

        .red {
            color: red;
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
            OLN LAPORAN RETUR
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
                <th class="">NO.</th>
                <th class="">Nama Customer</th>
                <th class="">Total QTY</th>
                <th class="red">Bruto Retur</th>
                <th class="">Tanggungan Customer</th>
                <th class="red">Retur Netto (inc PPN)</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($d = mysql_fetch_array($data)) : ?>
                <tr>
                    <td class=""><?= $num; ?></td>
                    <td class="data"><?= $d['nama'];  ?></td>
                    <td class=""><?= number_format($d['qty']);  ?></td>
                    <td class="red"><?= number_format($d['bruto']);  ?></td>
                    <td class=""><?= number_format($d['penalty']);  ?></td>
                    <td class="red"><?= number_format($d['total']);  ?></td>
                </tr>
            <?php $num++;
                $qty += $d['qty'];
                $bruto += $d['bruto'];
                $penalty += $d['penalty'];
                $total += $d['total'];
            endwhile; ?>

            <tr class="bold">
                <td colspan="2">Total</td>
                <td><?= number_format($qty); ?></td>
                <td class="red"><?= number_format($bruto); ?></td>
                <td><?= number_format($penalty); ?></td>
                <td class="red"><?= number_format($total); ?></td>
            </tr>
        </tbody>
    </table>
</body>
<script>
    function displayLiveTime() {
        var currentDate = new Date();

        var day = currentDate.getDate();
        var month = currentDate.getMonth() + 1;
        var year = currentDate.getFullYear();
        var hours = currentDate.getHours();
        var minutes = currentDate.getMinutes();
        var seconds = currentDate.getSeconds();

        var formattedTime = day.toString().padStart(2, 0) + '/' + month.toString().padStart(2, 0) + '/' + year + ' ' + hours.toString().padStart(2, 0) + ':' + minutes.toString().padStart(2, 0) + ':' + seconds.toString().padStart(2, 0);

        document.getElementById("timestamp").innerHTML = formattedTime;
    }


    displayLiveTime();

    setInterval(displayLiveTime, 1000);

    window.print()
</script>

</html>