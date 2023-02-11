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
<?php
error_reporting(0);
	include("../../include/koneksi.php");
	$tglstart=$_GET['start'];
    $tglend=$_GET['end'];
	$filter=$_GET['filter'];	
	
	$where_title=" WHERE p.deleted=0  ";	
	if($filter != null)
	{
	$where_title .= " AND DATE(p.tgl_trans) BETWEEN STR_TO_DATE('$tglstart','%d/%m/%Y')  AND STR_TO_DATE('$tglend','%d/%m/%Y') AND ((p.ref_kode like '%".$filter."%' OR j.nama like '%".$filter."%' OR s.nama like '%".$filter."%'))";
	}
	else
	{
	$where_title .= " AND DATE(p.tgl_trans) BETWEEN STR_TO_DATE('$tglstart','%d/%m/%Y')  AND STR_TO_DATE('$tglend','%d/%m/%Y')";
	}
	
	$sql_title ="SELECT p.*,k.nama as kategori,j.nama as customer,s.nama as salesman,e.nama as expedition, (SELECT p.id_trans  FROM `b2bso` p Left Join `mst_b2bcategory_sale` k on (p.id_kategori=k.id) Left Join `mst_b2bsalesman` s on (p.id_salesman=s.id) Left Join `mst_b2bcustomer` j on (p.id_customer=j.id) Left Join `mst_b2bexpedition` e on (p.id_expedition=e.id) ".$where_title." ORDER BY p.id_trans ASC LIMIT 1) as first_order, (SELECT p.id_trans  FROM `b2bso` p Left Join `mst_b2bcategory_sale` k on (p.id_kategori=k.id) Left Join `mst_b2bsalesman` s on (p.id_salesman=s.id) Left Join `mst_b2bcustomer` j on (p.id_customer=j.id) Left Join `mst_b2bexpedition` e on (p.id_expedition=e.id) ".$where_title." ORDER BY p.id_trans DESC LIMIT 1) as last_order, (SELECT COUNT(p.id_trans)  FROM `b2bso` p Left Join `mst_b2bcategory_sale` k on (p.id_kategori=k.id) Left Join `mst_b2bsalesman` s on (p.id_salesman=s.id) Left Join `mst_b2bcustomer` j on (p.id_customer=j.id) Left Join `mst_b2bexpedition` e on (p.id_expedition=e.id) ".$where_title.") as jumlah_order FROM `b2bso` p Left Join `mst_b2bcategory_sale` k on (p.id_kategori=k.id) Left Join `mst_b2bsalesman` s on (p.id_salesman=s.id) Left Join `mst_b2bcustomer` j on (p.id_customer=j.id) Left Join `mst_b2bexpedition` e on (p.id_expedition=e.id) ".$where_title;
	
	// var_dump($sql_title);die;
	$data_title=mysql_query($sql_title);
	$rs_title = mysql_fetch_array($data_title); 
	
	
?>


<form id="form2" name="form2" action="" method="post"  onSubmit="return validasi(this)">
  <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td height="123" valign="top"><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
          
          <tr>
            <td width="100%" class="style99" colspan="7"><strong>
			B2B ORDER INPUT REPORT </strong></td>
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
            <td width="100%" class="style9b" colspan="7">Dari:
            <?php echo"".$tglstart;?>
            &nbsp;-&nbsp;<?php echo"".$tglend;?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Jumlah Order:&nbsp;<?php echo"".$rs_title['jumlah_order'];?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Nomor_awal:&nbsp;<?php echo"".$rs_title['first_order'];?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Nomor_akhir:&nbsp;<?php echo"".$rs_title['last_order'];?></td>           
		  </tr>
          		  
  </table>  
    
    
  <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  
        <tr>
            <td colspan="13" class="style9"><hr /></td>
        </tr>
      <tr>
      <th width="5%" class="style_title_left"><div align="center">No.trans</div></td>
      <th width="15%" class="style_title"><div align="center">Customer</div></td>
      <th width="15%" class="style_title"><div align="center">Salesman</div></td>
      <th width="15%" class="style_title"><div align="center">Item</div></td>
      <th width="15%" class="style_title"><div align="center">UK</div></td>
      <th width="5%" class="style_title"><div align="center">Qty Order</div></td>
      <th width="7%" class="style_title"><div align="center">Qty Kirim</div></td>
      <th width="7%" class="style_title"><div align="center">Price</div></td>
 	  <th width="7%" class="style_title"><div align="center">Subtotal</div></td>
 	  <th width="7%" class="style_title"><div align="center">Disc</div></td>
 	  <th width="7%" class="style_title"><div align="center">Total</div></td>
 	  <th width="7%" class="style_title"><div align="center">Status</div></td>
    </tr>
    <?
	
	$where = "";
    $where=" WHERE p.deleted=0  ";	
	if($filter != null)
	{
	$where .= " AND DATE(p.tgl_trans) BETWEEN STR_TO_DATE('$tglstart','%d/%m/%Y')  AND STR_TO_DATE('$tglend','%d/%m/%Y') AND ((p.ref_kode like '%".$filter."%' OR j.nama like '%".$filter."%' OR s.nama like '%".$filter."%'))";
	}
	else
	{
	$where .= " AND DATE(p.tgl_trans) BETWEEN STR_TO_DATE('$tglstart','%d/%m/%Y')  AND STR_TO_DATE('$tglend','%d/%m/%Y')";
	}
    
	$sql_detail = "SELECT pd.*,DATE_FORMAT(p.tgl_trans,'%d/%m/%Y') AS tgltrans,j.nama as customer,s.nama as salesman, p.total as total  FROM `b2bso_detail` pd LEFT JOIN b2bso p ON p.id_trans=pd.id_trans Left Join `mst_b2bcategory_sale` k on (p.id_kategori=k.id) Left Join `mst_b2bsalesman` s on (p.id_salesman=s.id) Left Join `mst_b2bcustomer` j on (p.id_customer=j.id) Left Join `mst_b2bexpedition` e on (p.id_expedition=e.id) ".$where." order by p.id_trans asc";
    // var_dump($sql_detail);die;
	$sq2 = mysql_query($sql_detail);
	$i=1;
	$nomer=0;
	$grand_subtotal=0;
	$grand_qty=0;
	$grand_qtykirim=0;
    $grand_total=0;
	// $totaldpp=0;
	// $totalppn=0;
	// $grand_ongkir=0;
	$kode=""; 
	while($rs2=mysql_fetch_array($sq2))
	{ 	
  ?>
    <tr>
    <?php
      $sqluk = "";
      $sqluk = "SELECT pd.* FROM `b2bso_detail` pd WHERE b2bso_id='".$rs2['b2bso_id']."' ";
      $squk = mysql_query($sqluk);

      $sizenew='';
	  $count=1;
	  $barangnya='';
      while($rsuk=mysql_fetch_array($squk))
	  {
        if ($barangnya != $rsuk['id_product']) {
            $count=1;
        }
            if ($rsuk['qty31'] != '0') {
                if ($count == 1) {
                    $sizenew = '31'.'('.$rsuk['qty31'].')';
                }else{					
                    $sizenew = $sizenew.', 31('.$rsuk['qty31'].')';
                }
                $count++;
            }

            if ($rsuk['qty32'] != '0') {
                if ($count == 1) {
                    $sizenew = '32'.'('.$rsuk['qty32'].')';
                }else{
                    $sizenew = $sizenew.', 32('.$rsuk['qty32'].')';
                }
                $count++;
            }

            if ($rsuk['qty33'] != '0') {
                if ($count == 1) {
                    $sizenew = '33'.'('.$rsuk['qty33'].')';
                }else{
                    $sizenew = $sizenew.', 33('.$rsuk['qty33'].')';
                }
                $count++;
            }

            if ($rsuk['qty34'] != '0') {
                if ($count == 1) {
                    $sizenew = '34'.'('.$rsuk['qty34'].')';
                }else{
                    $sizenew = $sizenew.', 34('.$rsuk['qty34'].')';
                }
                $count++;
            }

            if ($rsuk['qty35'] != '0') {
                if ($count == 1) {
                    $sizenew = '35'.'('.$rsuk['qty35'].')';
                }else{
                    $sizenew = $sizenew.', 35('.$rsuk['qty35'].')';
                }
                $count++;
            }

            if ($rsuk['qty36'] != '0') {
                if ($count == 1) {
                    $sizenew = '36'.'('.$rsuk['qty36'].')';
                }else{
                    $sizenew = $sizenew.', 36('.$rsuk['qty36'].')';
                }
                $count++;
            }

            if ($rsuk['qty37'] != '0') {
                if ($count == 1) {
                    $sizenew = '37'.'('.$rsuk['qty37'].')';
                }else{
                    $sizenew = $sizenew.', 37('.$rsuk['qty37'].')';
                }
                $count++;
            }

            if ($rsuk['qty38'] != '0') {
                if ($count == 1) {
                    $sizenew = '38'.'('.$rsuk['qty38'].')';
                }else{
                    $sizenew = $sizenew.', 38('.$rsuk['qty38'].')';
                }
                $count++;
            }

            if ($rsuk['qty39'] != '0') {
                if ($count == 1) {
                    $sizenew = '39'.'('.$rsuk['qty39'].')';
                }else{
                    $sizenew = $sizenew.', 39('.$rsuk['qty39'].')';
                }
                $count++;
            }

            if ($rsuk['qty40'] != '0') {
                if ($count == 1) {
                    $sizenew = '40'.'('.$rsuk['qty40'].')';
                }else{
                    $sizenew = $sizenew.', 40('.$rsuk['qty40'].')';
                }
                $count++;
            }

            if ($rsuk['qty41'] != '0') {
                if ($count == 1) {
                    $sizenew = '41'.'('.$rsuk['qty41'].')';
                }else{
                    $sizenew = $sizenew.', 41('.$rsuk['qty41'].')';
                }
                $count++;
            }

            if ($rsuk['qty42'] != '0') {
                if ($count == 1) {
                    $sizenew = '42'.'('.$rsuk['qty42'].')';
                }else{
                    $sizenew = $sizenew.', 42('.$rsuk['qty42'].')';
                }
                $count++;
            }

            if ($rsuk['qty43'] != '0') {
                if ($count == 1) {
                    $sizenew = '43'.'('.$rsuk['qty43'].')';
                }else{
                    $sizenew = $sizenew.', 43('.$rsuk['qty43'].')';
                }
                $count++;
            }

            if ($rsuk['qty44'] != '0') {
                if ($count == 1) {
                    $sizenew = '44'.'('.$rsuk['qty44'].')';
                }else{
                    $sizenew = $sizenew.', 44('.$rsuk['qty44'].')';
                }
                $count++;
            }

            if ($rsuk['qty45'] != '0') {
                if ($count == 1) {
                    $sizenew = '45'.'('.$rsuk['qty45'].')';
                }else{
                    $sizenew = $sizenew.', 45('.$rsuk['qty45'].')';
                }
                $count++;
            }

            if ($rsuk['qty46'] != '0') {
                if ($count == 1) {
                    $sizenew = '46'.'('.$rsuk['qty46'].')';
                }else{
                    $sizenew = $sizenew.', 46('.$rsuk['qty46'].')';
                }
                $count++;
            }
            $barangnya = $rsuk['id_product'];
      }  

	  $nomer++;
	  //bikin master_ongkir
	  if ($kode!=$rs2['id_trans'])
	  {
		echo"<td class='style_detail_left'><div align='center'>".$rs2['id_trans']."</br>(".$rs2['tgltrans'].")</div></td>";
		echo"<td class='style_detail'><div align='center'>".$rs2['customer']."</div></td>";
		echo"<td class='style_detail'><div align='center'>".$rs2['salesman']."</div></td>";
		echo"<td class='style_detail'><div align='center'>".$rs2['namabrg']."</div></td>";
		echo"<td class='style_detail'><div align='left'>".$sizenew."</div></td>";
		echo"<td class='style_detail'><div align='center'>".$rs2['jumlah_beli']."</div></td>";
		echo"<td class='style_detail'><div align='center'>".$rs2['jumlah_kirim']."</div></td>";
		echo"<td class='style_detail'><div align='right'>".number_format($rs2['harga_satuan'])."</div></td>";
		echo"<td class='style_detail'><div align='right'>".number_format($rs2['subtotal'])."</div></td>";
		echo"<td class='style_detail'><div align='right'>".number_format($rs2['disc'])."</div></td>";
		echo"<td class='style_detail'><div align='right'>".number_format($rs2['total'])."</div></td>";

        $sql = "select SUM(jumlah_beli) as totbeli,SUM(jumlah_kirim) as totkirim FROM b2bso_detail WHERE id_trans='".$rs2['id_trans']."' ";
        $totkirim=0;
        $totbeli=0;
        $sq3 = mysql_query($sql);
        while($rs3=mysql_fetch_array($sq3))
        {   
            $totkirim = $rs3['totkirim'];
            $totbeli = $rs3['totbeli'];
        }
        if($totkirim == $totbeli){
            echo"<td class='style_detail'><div align='center'>Selesai</div></td>";
        }else{
            echo"<td class='style_detail'><div align='center'>Belum Selesai</div></td>";

        }

        $kode=$rs2['id_trans'];
        $grand_total+=$rs2['total'];
	  }
      else if($kode=$rs2['id_trans'])
	  {
		echo"<td class='style_detail_left'><div align='center'></div></td>";
		echo"<td class='style_detail'><div align='center'></div></td>";
		echo"<td class='style_detail'><div align='center'></div></td>";
		echo"<td class='style_detail'><div align='center'>".$rs2['namabrg']."</div></td>";
		echo"<td class='style_detail'><div align='left'>".$sizenew."</div></td>";
		echo"<td class='style_detail'><div align='center'>".$rs2['jumlah_beli']."</div></td>";
		echo"<td class='style_detail'><div align='center'>".$rs2['jumlah_kirim']."</div></td>";
		echo"<td class='style_detail'><div align='right'>".number_format($rs2['harga_satuan'])."</div></td>";
		echo"<td class='style_detail'><div align='right'>".number_format($rs2['subtotal'])."</div></td>";
		echo"<td class='style_detail'><div align='right'></div></td>";
		echo"<td class='style_detail'><div align='center'></div></td>";
	    echo"<td class='style_detail'><div align='right'></div></td>";
	  
	  }
	?>
    </tr>  
	<?
	$grand_qty+=$rs2['jumlah_beli'];
	$grand_qtykirim+=$rs2['jumlah_kirim'];
	//$grand_subtotal+=$rs2['subtotal'];
	$grand_subtotal+=$rs2['subtotal'];
	$grand_disc+=$rs2['disc'];
	
	//$totaldpp =($grand_subtotal/1.11);
	//totaldpp didapat dari grand faktur(grandtotal)/1.11
	// $totaldpp =($grand_total/1.11);
	// $totalppn= ($totaldpp*0.11);	
  }
  ?>
       <tr>
            <td colspan="5" class="style_title_left"><div align="right">Total:</div></td>
            <td class="style_title"><div align="center"><?=number_format($grand_qty);?></div></td>
            <td class="style_title"><div align="center"><?=number_format($grand_qtykirim);?></div></td>
            <td colspan="2" class="style_title"><div align="right"><?=number_format($grand_subtotal);?></div></td>
            <td class="style_title"><div align="right"><?=number_format($grand_disc);?></div></td>
            <td class="style_title"><div align="right"><?=number_format($grand_total);?></div></td>
            <td class="style_title"><div align="right"></div></td>
       </tr>
        <!-- <tr>
                <td colspan="6" class="style_title_left"><div align="right">DPP:</div></td>
                <td class="style_title"><div align="center">&nbsp;</div></td>
                <td colspan="4" class="style_title"><div align="right"><?=number_format($totaldpp);?></div></td>
        </tr>
        <tr>
                <td colspan="6" class="style_title_left"><div align="right">PPN:</div></td>
                <td class="style_title"><div align="center">&nbsp;</div></td>
                <td colspan="4" class="style_title"><div align="right"><?=number_format($totalppn);?></div></td>
        </tr> -->
	
  </table>
   
  
   
  
  <div align="center"></div>
</form>

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
  <div align="center"><span class="style20">
   
  </span> </div>
