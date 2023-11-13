<?php 
require_once '../../include/config.php';
$q = $db->query(
    " SELECT a.nama AS nama_barang,
  b.jumlah_kirim AS total_qty,
  b.harga_satuan AS harga_faktur,
  a.harga AS harga_resale,
  b.harga_satuan / 1.".VALUE_PPN." AS harga_pajak,
  (b.harga_satuan / 1.".VALUE_PPN.") * 0.01 * (100-disc) AS harga_asli,
  b.disc AS disc,
  b.jumlah_kirim * a.harga AS total_murni,
  b.jumlah_kirim * (
   (b.harga_satuan / 1.".VALUE_PPN.") * 0.01 * (100-disc)
  ) AS total_resale,
  b.jumlah_kirim * (
    (b.harga_satuan / 1.".VALUE_PPN.") * 0.01 * (100-disc) - a.harga
  ) AS komisi FROM mst_b2bproductsgrp a LEFT JOIN b2bdo_detail b ON a.id=b.id_product LEFT JOIN b2bdo c ON c.id_trans=b.id_trans WHERE a.deleted=0 AND c.deleted=0 AND c.`no_faktur`='".$_GET['no_faktur']."'"
);

$data = $q->fetchAll(PDO::FETCH_ASSOC);
$i = 0;
$total_resale = 0;
$total_harga = 0;
$total_komisi = 0;

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    <script src="../../assets/js/jsbilangan.js" type="text/javascript"></script>
    <script type="text/javascript" src="../../assets/js/jquery-1.4.js"></script>
    <style type="text/css">
        .style9 {
        font-size: 9pt; 
        font-family:Tahoma;
        }
        .style9b {color: #000000;
            font-size: 9pt;
            font-weight: bold;
            font-family: Tahoma;
        }.style99 {font-size: 13pt; font-family:Tahoma}
        .style10 {font-size: 10pt; font-family:Tahoma; text-align:right}
        .style19 {font-size: 10pt; font-weight: bold; font-family:Tahoma; font-style:italic}
        .style11 {
            color: #000000;
            font-size: 8pt;
            font-weight: normal;
            font-family: MS Reference Sans Serif;
            
        }
        .style20b {font-size: 8pt;font-weight: bold; font-family:Tahoma}
        .style20 {font-size: 8pt; font-family:Tahoma}
        .style16 {font-size: 9pt; font-family:Tahoma}
        .style21 {color: #000000;
            font-size: 10pt;
            font-weight: bold;
            font-family: Tahoma;
        }
        .style18 {color: #000000;
            font-size: 9pt;
            font-weight: normal;
            font-family: Tahoma;
        }
        .style6 {color: #000000;
            font-size: 9pt;
            font-weight: bold;
            font-family: Tahoma;
        }
        .style19b {	color: #000000;
            font-size: 11pt;
            font-weight: bold;
            font-family: Tahoma;
        }
        .style_title {	color: #000000;
            font-size: 9pt;	
            font-family: Tahoma;
            border-top: 1px solid black;
            border-bottom: 1px solid black;
            border-right: 1px solid black;
            
            
            padding: 3px;
        }
        .style_title_left {	color: #000000;
            font-size: 9pt;	
            font-family: Tahoma;
            border-top: 1px solid black;
            border-bottom: 1px solid black;
            border-right: 1px solid black;
            border-left: 1px solid black;
            
            padding: 3px;
        }
        .style_detail {	color: #000000;
            font-size: 9pt;	
            font-family: Tahoma;
            border-bottom: 1px dashed black;
            border-right: 1px solid black;
            padding: 3px;
        }
        .style_detail_left {	color: #000000;
            font-size: 9pt;	
            font-family: Tahoma;
            border-bottom: 1px dashed black;
            border-left: 1px solid black;
            border-right: 1px solid black;
            padding: 3px;
        }
        @page {
                size: A4;
                margin: 15px;
            }
    </style>
</head>
<body>
<form id="form2" name="form2" action="" method="post"  onSubmit="return validasi(this)">
  <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td height="123" valign="top"><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">   
        <tr>
            <td width="100%" class="style99" colspan="7"><strong>
			SALES COMMISSION REPORT </strong></td>
			<td style="text-align:right">
                <div id="timestamp">
                <?php
                    date_default_timezone_set('Asia/Jakarta');
                    echo $timestamp = date('d/m/Y H:i:s');
                ?>
                </div>  
            </td>
        </tr>
        <tr>
            <td width="100%" class="style9b" colspan="7">
                Nomor DO: <?= $_GET['id_trans'] ?> &nbsp;&nbsp; Nomor Faktur: <?= $_GET['no_faktur'] ?>
            </td>
        </tr>
        <tr>
            <td width="100%" class="style9b" colspan="7">
                  Pada: <?= date_format(date_create($_GET['tgl']),'d/m/Y'); ?> 
            </td>           
        </tr> 
    </table>

    <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
            <td colspan="11" class="style9"><hr /></td>
        </tr>
    <tr>
        <!-- <th width="8%" class="style_title"><div align="center">Salesman</div></td> -->
        <th width="10%" class="style_title"><div align="center">Nama Barang</div></td>
        <th width="5%" class="style_title"><div align="center">QTY</div></td>
        <th width="5%" class="style_title"><div align="center">Discount</div></td>
        <th width="10%" class="style_title"><div align="center">Harga Faktur</div></td>
        <th width="10%" class="style_title"><div align="center">Harga Resale</div></td>
        <th width="10%" class="style_title"><div align="center">Subtotal faktur(-PPN) </div></td>
 	    <th width="10%" class="style_title"><div align="center">Subtotal Resale</div></td>
 	    <th width="10%" class="style_title"><div align="center">Komisi</div></td>
    </tr>
    <?php foreach($data as $d) : ?>
        <tr>
            <!-- <td class="style_detail"><div align="center"><?= $i+1 ?></div></td> -->
            <!-- <td class="style_detail"><div align="center"><?= $d['nama_salesman']?></div></td> -->
            <td class="style_detail"><div align="center"><?= $d['nama_barang']?></div></td>
            <td class="style_detail"><div align="center"><?= number_format($d['total_qty'])?></div></td>
            <td class="style_detail"><div align="center"><?= number_format($d['disc'])?></div></td>
            <td class="style_detail"><div align="center"><?= number_format($d['harga_faktur'])?></div></td>
            <td class="style_detail"><div align="center"><?= number_format($d['harga_resale'])?></div></td>
            <td class="style_detail"><div align="center"><?= number_format($d['total_resale'])?></div></td>
            <td class="style_detail"><div align="center"><?= number_format($d['total_murni'])?></div></td>
            <td class="style_detail"><div align="center"><?= number_format($d['komisi'])?></div></td>
        </tr>
        <?php 
            $i++;
            $total_resale += $d['total_resale'];
            $total_harga += $d['harga_resale'];
            $total_komisi += $d['komisi'];
        ?>
    <?php endforeach; ?>


    <tr>
        <td colspan="5" class="style_title_left"><div align="right">Total Faktur:</div></td>
        <td class="style_title" colspan="3"><div align="center"><?=number_format($total_resale);?></div></td>
    </tr>
    <tr>
        <td colspan="5" class="style_title_left"><div align="right">Total Resale:</div></td>
        <td class="style_title" colspan="3"><div align="center"><?=number_format($total_harga);?></div></td>
    </tr>
    <tr>
        <td colspan="5" class="style_title_left"><div align="right">Total Komisi:</div></td>
        <td class="style_title" colspan="3"><div align="center"><?=number_format($total_komisi);?></div></td>
    </tr>
    <!-- <tr>
        <td colspan="3" class="style_title_left"><div align="right">DPP:</div></td>
        <td class="style_title"><div align="center">&nbsp;</div></td>
        <td colspan="4" class="style_title"><div align="right"><?=number_format($totaldpp);?></div></td>
        <td class="style_title"><div align="right">&nbsp;</div></td>
    </tr>
    <tr>
        <td colspan="3" class="style_title_left"><div align="right">PPN:</div></td>
        <td class="style_title"><div align="center">&nbsp;</div></td>
        <td colspan="4" class="style_title"><div align="right"><?=number_format($totalppn);?></div></td>
        <td class="style_title"><div align="right">&nbsp;</div></td>
    </tr> -->
        </table>
    </form>
    <script>
        $(document).ready(function () {
            window.print()
        })        
    </script>
</body>
</html>