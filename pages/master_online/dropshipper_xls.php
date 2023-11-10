<?php 
include '../../include/config.php';

$q = $db->query("SELECT m.oln_customer_id as oln_id ,m.nama,m.alamat,m.hp,m.disc,m.type FROM mst_dropshipper m ORDER BY m.nama");
$data = $q->fetchAll(PDO::FETCH_ASSOC);

$date = $db->query("SELECT DATE_FORMAT(CURRENT_TIMESTAMP,'%d/%m/%Y') as now")->fetchAll(PDO::FETCH_ASSOC);


header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=dropshipper_data.xls");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
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
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td height="123" valign="top"><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">   
        <tr>
            <td width="100%" class="style99" colspan="7"><strong>DROPSHIPPER DATA</strong></td>
			<td style="text-align:right">
                <div id="timestamp">
                <?php
                    date_default_timezone_set('Asia/Jakarta');
                    echo $timestamp = date('d/m/Y H:i:s');
                ?>
                </div>  
            </td>
        </tr>
    </table>
    <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
            <td colspan="11" class="style9"><hr /></td>
        </tr>
    <tr>
        <!-- <th width="8%" class="style_title"><div align="center">Salesman</div></td> -->
        <th width="10%" class="style_title"><div align="center">OLN ID</div></td>
        <th width="5%" class="style_title"><div align="center">Name</div></td>
        <th width="5%" class="style_title"><div align="center">Address</div></td>
        <th width="10%" class="style_title"><div align="center">Phone</div></td>
        <th width="10%" class="style_title"><div align="center">Disc</div></td>
        <th width="10%" class="style_title"><div align="center">Type</div></td>
    </tr>
    <?php foreach($data as $d) : ?>
        <tr>
            <td class="style_detail"><div align="center"><?= $d['oln_id']?></div></td>
            <td class="style_detail"><div align="center"><?= $d['nama']?></div></td>
            <td class="style_detail"><div align="center"><?= $d['alamat']?></div></td>
            <td class="style_detail"><div align="center"><?= $d['hp']?></div></td>
            <td class="style_detail"><div align="center"><?= $d['disc']?></div></td>
            <td class="style_detail"><div align="center"><?= $d['type']?></div></td>
        </tr>
    <?php endforeach; ?>
        </table>

</body>
</html>