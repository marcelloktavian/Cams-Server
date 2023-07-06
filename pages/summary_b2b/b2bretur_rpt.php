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
// error_reporting(0);
	include("../../include/koneksi.php");
	$tglstart=$_GET['start'];
    $tglend=$_GET['end'];
	$filter=$_GET['filter'];	
	
	$where_title=" p.deleted=0 AND p.post='1' ";	
	if($filter != null)
	{
	$where_title .= " AND DATE(p.tgl_return) BETWEEN STR_TO_DATE('$tglstart','%d/%m/%Y')  AND STR_TO_DATE('$tglend','%d/%m/%Y') AND ((p.b2breturn_num like '%$filter%') or (c.nama like '%$filter%') or (k.nama like '%$filter%'))";
	}
	else
	{
	$where_title .= " AND DATE(p.tgl_return) BETWEEN STR_TO_DATE('$tglstart','%d/%m/%Y')  AND STR_TO_DATE('$tglend','%d/%m/%Y')";
	}
	
	$sql_title ="SELECT (SELECT p.b2breturn_num FROM `b2breturn` p Left Join `mst_b2bcustomer` c on (c.id=p.b2bcust_id) Left Join `mst_b2bcategory_sale` k on (p.id_kategori=k.id) WHERE $where_title ORDER BY p.b2breturn_num ASC LIMIT 1) AS first_order,
    (SELECT p.b2breturn_num FROM `b2breturn` p Left Join `mst_b2bcustomer` c on (c.id=p.b2bcust_id) Left Join `mst_b2bcategory_sale` k on (p.id_kategori=k.id) WHERE  $where_title ORDER BY p.b2breturn_num DESC LIMIT 1) AS last_order ,
    (SELECT COUNT(p.b2breturn_num) FROM `b2breturn` p Left Join `mst_b2bcustomer` c on (c.id=p.b2bcust_id) Left Join `mst_b2bcategory_sale` k on (p.id_kategori=k.id) WHERE  $where_title) AS jumlah_order";
	
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
			B2B RETURN REPORT </strong></td>
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
            <td colspan="11" class="style9"><hr /></td>
        </tr>
      <tr>
      <th width="5%" class="style_title_left"><div align="center">No.trans</div></td>
      <th width="25%" class="style_title"><div align="center">Customer</div></td>
      <th width="15%" class="style_title"><div align="center">Item</div></td>
      <th width="15%" class="style_title"><div align="center">Size</div></td>
      <th width="5%" class="style_title"><div align="center">Qty</div></td>
      <th width="10%" class="style_title"><div align="center">Price</div></td>
 	  <th width="10%" class="style_title"><div align="center">Subtotal</div></td>
    </tr>
    <?php
        $grand_qty = 0;
        $grand_total = 0;

        $where = "WHERE p.post=1 AND p.deleted=0 ";
        if(($tglstart != null) && ($filter != null)) {
			$where .= " AND DATE(p.tgl_return) BETWEEN STR_TO_DATE('$tglstart','%d/%m/%Y') AND STR_TO_DATE('$tglend','%d/%m/%Y') AND ((p.b2breturn_num like '%$filter%') or (c.nama like '%$filter%') or (k.nama like '%$filter%'))";	
		}	
		else
		{
		    $where .=" AND DATE(p.tgl_return) BETWEEN STR_TO_DATE('$tglstart','%d/%m/%Y') AND STR_TO_DATE('$tglend','%d/%m/%Y')";
		}
        $query_detail = "SELECT p.*,dt.*,c.nama as customer,k.nama as kategori,DATE_FORMAT(p.tgl_return,'%d/%m/%Y') as tglreturn FROM `b2breturn` p Left Join `mst_b2bcustomer` c on (c.id=p.b2bcust_id) Left Join `mst_b2bcategory_sale` k on (p.id_kategori=k.id) left join b2breturn_detail dt ON dt.id_parent=p.id ".$where;
        $data_detail=mysql_query($query_detail);

        $i=0;
        $barangnya='';
        while($line=mysql_fetch_array($data_detail)){
            $sizenew='';
            $count=1;
            
            if ($barangnya != $line['id_product']) {
            $count=1;
            }

            if ($line['qty31'] != '0') {
            if ($count == 1) {
                $sizenew = '31'.'('.$line['qty31'].')';
            }else{					
                $sizenew = $sizenew.', 31('.$line['qty31'].')';
            }
            $count++;
            }

            if ($line['qty32'] != '0') {
            if ($count == 1) {
                $sizenew = '32'.'('.$line['qty32'].')';
            }else{
                $sizenew = $sizenew.', 32('.$line['qty32'].')';
            }
            $count++;
            }

            if ($line['qty33'] != '0') {
            if ($count == 1) {
                $sizenew = '33'.'('.$line['qty33'].')';
            }else{
                $sizenew = $sizenew.', 33('.$line['qty33'].')';
            }
            $count++;
            }

            if ($line['qty34'] != '0') {
            if ($count == 1) {
                $sizenew = '34'.'('.$line['qty34'].')';
            }else{
                $sizenew = $sizenew.', 34('.$line['qty34'].')';
            }
            $count++;
            }

            if ($line['qty35'] != '0') {
            if ($count == 1) {
                $sizenew = '35'.'('.$line['qty35'].')';
            }else{
                $sizenew = $sizenew.', 35('.$line['qty35'].')';
            }
            $count++;
            }

            if ($line['qty36'] != '0') {
            if ($count == 1) {
                $sizenew = '36'.'('.$line['qty36'].')';
            }else{
                $sizenew = $sizenew.', 36('.$line['qty36'].')';
            }
            $count++;
            }

            if ($line['qty37'] != '0') {
            if ($count == 1) {
                $sizenew = '37'.'('.$line['qty37'].')';
            }else{
                $sizenew = $sizenew.', 37('.$line['qty37'].')';
            }
            $count++;
            }

            if ($line['qty38'] != '0') {
            if ($count == 1) {
                $sizenew = '38'.'('.$line['qty38'].')';
            }else{
                $sizenew = $sizenew.', 38('.$line['qty38'].')';
            }
            $count++;
            }

            if ($line['qty39'] != '0') {
            if ($count == 1) {
                $sizenew = '39'.'('.$line['qty39'].')';
            }else{
                $sizenew = $sizenew.', 39('.$line['qty39'].')';
            }
            $count++;
            }

            if ($line['qty40'] != '0') {
            if ($count == 1) {
                $sizenew = '40'.'('.$line['qty40'].')';
            }else{
                $sizenew = $sizenew.', 40('.$line['qty40'].')';
            }
            $count++;
            }

            if ($line['qty41'] != '0') {
            if ($count == 1) {
                $sizenew = '41'.'('.$line['qty41'].')';
            }else{
                $sizenew = $sizenew.', 41('.$line['qty41'].')';
            }
            $count++;
            }

            if ($line['qty42'] != '0') {
            if ($count == 1) {
                $sizenew = '42'.'('.$line['qty42'].')';
            }else{
                $sizenew = $sizenew.', 42('.$line['qty42'].')';
            }
            $count++;
            }

            if ($line['qty43'] != '0') {
            if ($count == 1) {
                $sizenew = '43'.'('.$line['qty43'].')';
            }else{
                $sizenew = $sizenew.', 43('.$line['qty43'].')';
            }
            $count++;
            }

            if ($line['qty44'] != '0') {
            if ($count == 1) {
                $sizenew = '44'.'('.$line['qty44'].')';
            }else{
                $sizenew = $sizenew.', 44('.$line['qty44'].')';
            }
            $count++;
            }

            if ($line['qty45'] != '0') {
            if ($count == 1) {
                $sizenew = '45'.'('.$line['qty45'].')';
            }else{
                $sizenew = $sizenew.', 45('.$line['qty45'].')';
            }
            $count++;
            }

            if ($line['qty46'] != '0') {
            if ($count == 1) {
                $sizenew = '46'.'('.$line['qty46'].')';
            }else{
                $sizenew = $sizenew.', 46('.$line['qty46'].')';
            }
            $count++;
            }

            $totalqty = $line['qty31'] + $line['qty32'] + $line['qty33'] + $line['qty34'] + $line['qty35'] + $line['qty36'] + $line['qty37'] + $line['qty38'] + $line['qty39'] + $line['qty40'] + $line['qty41'] + $line['qty42'] + $line['qty43'] + $line['qty44'] + $line['qty45'] + $line['qty46'];

            echo"<tr><td class='style_detail_left'><div align='center'>".$line['b2breturn_num']."</br>(".$line['tglreturn'].")</div></td>";
            echo"<td class='style_detail'><div align='center'>".$line['customer']."</div></td>";
            echo"<td class='style_detail'><div align='center'>".$line['namabrg']."</div></td>";
            echo"<td class='style_detail'><div align='center'>".$sizenew."</div></td>";
            echo"<td class='style_detail'><div align='right'>".number_format($totalqty)."</div></td>";
            echo"<td class='style_detail'><div align='right'>".number_format($line['harga_satuan'])."</div></td>";
            echo"<td class='style_detail'><div align='right'>".number_format($line['subtotal'])."</div></td>";
            $barangnya = $line['id_product'];
            $grand_qty += $totalqty;
            $grand_total += $line['subtotal'];
            $i++;
        }
	
    ?>
       <tr>
            <td colspan="4" class="style_title_left"><div align="right">Total:</div></td>
            <td class="style_title"><div align="right"><?=$grand_qty;?></div></td>
            <td colspan="2" class="style_title"><div align="right"><?=number_format($grand_total);?></div></td>
        </tr>
	
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
