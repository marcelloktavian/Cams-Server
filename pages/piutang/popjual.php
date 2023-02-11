<style type="text/css">
.style9 {font-size: 10pt}
</style>
</head>
<style type="text/css">
<!--
.head_tbl {
	font-size: 14px;
	font-weight: bold;
	font-family:Verdana, Arial, Helvetica, sans-serif;
	text-transform: uppercase;
	color: #FFFFFF;
	background-color: #FF0000;
}

</style>
<script type="text/javascript">

var b = <?php echo $_GET['row'];?>;
function ambil(a){
	window.opener.document.getElementById("BARCODE["+b+"]").value = document.getElementById("Id_Jasa"+a+"").innerHTML;
	window.opener.document.getElementById("Tgl["+b+"]").value = document.getElementById("Tanggal"+a+"").innerHTML;
	window.opener.document.getElementById("Invoice["+b+"]").value = document.getElementById("Total"+a+"").innerHTML;
	window.opener.document.getElementById("Piutang["+b+"]").value = document.getElementById("Piutang"+a+"").innerHTML;
	window.opener.document.getElementById("Bayar["+b+"]").focus();	
	window.close();	
};

</script>

<body>
<table width="530" border="0">
  <tr>
    <td width="524"><form id="form1" name="form1" method="post" action="" >
      <label>Kode Transaksi
      <input type="text" name="cari" id="cari" value="" />
      </label>
      <label>
      <input type="submit" name="button" id="button" value="cari" onClick="pencarian()"/>
      </label>
    </form></td>
  </tr>
  <tr>
    <td>
    <table border="1" width="523" align="left">
      <tr class="head_tbl">
        <td width="80"><div align="center">ID Transaksi</div></td>
        <td width="80"><div align="center">Tgl.Transaksi</div></td>
        <td width="100"><div align="center">Total Faktur</div></td>
        <td width="100"><div align="center">Piutang</div></td>
        </tr>
      <?php
	  error_reporting(0);
	include("../../include/koneksi.php");
	//include("koneksi/koneksi.php");
		$hal = $_GET[hal];
if(!isset($_GET['hal'])){ 
    $page = 1; 
} else { 
    $page = $_GET['hal']; 
}
$jmlperhalaman = 10;  // jumlah record per halaman
$offset = (($page * $jmlperhalaman) - $jmlperhalaman);  

	
if($_GET['flag']==1)
{
	$cari=$_GET['cari'];
	/*
	$sql=mysql_query("Select sb.id_barang,b.nm_barang,b.hrg_jual,sum(sb.stok) as stok from stok_barang sb left join barang b on sb.id_barang = b.id_barang where b.id_barang LIKE '%".$cari."%' group by sb.id_barang order by b.nm_barang asc limit $offset, $jmlperhalaman") or die (mysql_error());
	*/
	$sql=mysql_query("select * from trjual where piutang > 0 and kode LIKE '%".$cari."%' limit $offset, $jmlperhalaman") or die (mysql_error());

}
else
{
	$sql=mysql_query("select * from trjual where piutang > 0 order by id_trans asc limit $offset, $jmlperhalaman") or die (mysql_error());

	/*
	$sql=mysql_query("Select sb.id_barang,b.nm_barang,b.hrg_jual,sum(sb.stok) as stok from stok_barang sb left join barang b on sb.id_barang = b.id_barang group by sb.id_barang order by b.nm_barang asc limit $offset, $jmlperhalaman") or die (mysql_error());
	*/
}

$i=1;
while($rs=mysql_fetch_array($sql))
{
	if($i%2==0)
	{
		echo("<tr onclick=\"ambil('$rs[0]')\" bgcolor=\"#FFEEEE\">");
	}
	else
	{
		echo("<tr onclick=\"ambil('$rs[0]')\">");
	}
	
?>
        <td width="80" id="Id_Jasa<?=$rs[0];?>"><?=$rs[id_trans];?></td>
        <td width="80" id="Tanggal<?=$rs[0];?>"><?=$rs[tgl_trans];?></td>
        <td width="100" id="Total<?=$rs[0];?>" align=right><?=$rs[totalfaktur];?>
        </td> 
        <td width="100" id="Piutang<?=$rs[0];?>" align=right><?=$rs[piutang];?>
        </td> 
        </tr>
      <?php
	$i++;
}
?>
    </table></td>
  </tr>
</table>

<?
if($_GET['flag']==1)
{
/*
Select sb.id_barang,b.nm_barang,b.hrg_jual,sum(sb.stok) from stok_barang sb left join barang b on sb.id_barang = b.id_barang group by sb.id_barang order by b.nm_barang asc
*/
	$cari=$_GET['cari'];
	/*
	$total_record = mysql_result(mysql_query("SELECT COUNT(*) as Num from stok_barang sb left join barang b on sb.id_barang = b.id_barang
    where b.nm_barang LIKE '%".$cari."%' group by sb.id_barang order by b.id_barang asc "),0);
	*/
	
	$total_record = mysql_result(mysql_query("SELECT COUNT(*) as Num FROM trjual where piutang > 0 and kode LIKE '%".$cari."%'"),0);

}
else
{
	/*
	$total_record = mysql_result(mysql_query("SELECT COUNT(*) as Num FROM stok_barang sb left join barang b on sb.id_barang = b.id_barang group by sb.id_barang order by b.nm_barang asc"),0);
	*/
	$total_record = mysql_result(mysql_query("SELECT COUNT(*) as Num FROM trjual where piutang > 0"),0);
}


$total_halaman = ceil($total_record / $jmlperhalaman);
echo "<center>"; 
$perhal=4;
if($hal > 1){ 
    $prev = ($page - 1); 
    if($_GET['flag']==1)
	{
		echo "<a href=$_SERVER[PHP_SELF]?hal=$prev&row=$_GET[row]&cari=$_GET[cari]&flag=1&id_kategori=$id_kategori> << </a> "; 
	}
	else
	{
		echo "<a href=$_SERVER[PHP_SELF]?hal=$prev&row=$_GET[row]&id_kategori=$id_kategori> << </a> "; 
	} 
}
if($total_halaman<=10){
$hal1=1;
$hal2=$total_halaman;
}else{
$hal1=$hal-$perhal;
$hal2=$hal+$perhal;
}
if($hal<=5){
$hal1=1;
}
if($hal<$total_halaman){
$hal2=$hal+$perhal;
}else{
$hal2=$hal;
}
for($i = $hal1; $i <= $hal2; $i++){ 
    if(($hal) == $i){ 
        echo "[<b>$i</b>] "; 
        } else { 
    if($i<=$total_halaman){
        if($_GET['flag']==1)
		{
			echo "<a href=$_SERVER[PHP_SELF]?hal=$i&row=$_GET[row]&cari=$_GET[cari]&flag=1&id_kategori=$id_kategori>$i</a> "; 
		}
		else
		{
			echo "<a href=$_SERVER[PHP_SELF]?hal=$i&row=$_GET[row]&id_kategori=$id_kategori>$i</a> "; 
		} 
    }
    } 
}
if($hal < $total_halaman){ 
    $next = ($page + 1); 
    if($_GET['flag']==1)
	{
		echo "<a href=$_SERVER[PHP_SELF]?hal=$next&row=$_GET[row]&cari=$_GET[cari]&flag=1&id_kategori=$id_kategori> >> </a>"; 
	}
	else
	{
		echo "<a href=$_SERVER[PHP_SELF]?hal=$next&row=$_GET[row]&id_kategori=$id_kategori> >> </a>"; 
	}
} 
echo "</center>";  
?>
<p>&nbsp;</p>
<script type="text/javascript">
function pencarian()
{  	var cari=document.getElementById('cari').value;
	var id_kategori='<?=$_GET['id_kategori'];?>';
	document.form1.action="popjual.php?flag=1&row="+b+"&id_kategori="+id_kategori+"&cari="+cari;
	document.form1.submit();
}	
</script>
