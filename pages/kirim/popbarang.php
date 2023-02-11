<style type="text/css">
.style9 {font-size: 10pt}
</style>
</head>
<style type="text/css">

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
	window.opener.document.getElementById("Nama["+b+"]").value = document.getElementById("Nama_Jasa"+a+"").innerHTML;
	window.opener.document.getElementById("Harga["+b+"]").value = document.getElementById("Harga"+a+"").innerHTML;
	window.opener.document.getElementById("Idkategori["+b+"]").value = 1;
	//window.opener.addkategori("Idkategori["+b+"]");
	window.opener.document.getElementById("Qty["+b+"]").focus();
	
	window.close();
	
};

</script>

<body>
<table width="530" border="0">
  <tr>
    <td width="524"><form id="form1" name="form1" method="post" action="" >
      <label>Kode Barang
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
        <td width="76"><div align="center">Kode</div></td>
        <td width="336"><div align="center">NAMA BARANG</div></td>
         <td width="89"><div align="center">Harga</div></td>
        </tr>
      <?php
	  error_reporting(0);
	include("../../include/koneksi.php");
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
	$sql=mysql_query("select * from barang where id_barang LIKE '%".$cari."%' limit $offset, $jmlperhalaman") or die (mysql_error());

}
else
{

	$sql=mysql_query("select * from barang limit $offset, $jmlperhalaman") or die (mysql_error());

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
        <td width="76" id="Id_Jasa<?=$rs[0];?>"><?=$rs[id_barang];?></td>
        <td width="336" id="Nama_Jasa<?=$rs[0];?>"><?=$rs[nm_barang];?></td>
         <td width="89" id="Harga<?=$rs[0];?>"><?=$rs[hrg_beli];?>
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
	$cari=$_GET['cari'];
	$total_record = mysql_result(mysql_query("SELECT COUNT(*) as Num FROM barang where nm_barang LIKE '%".$cari."%'"),0);

}
else
{
	$total_record = mysql_result(mysql_query("SELECT COUNT(*) as Num FROM barang"),0);
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
	document.form1.action="popbarang.php?flag=1&row="+b+"&id_kategori="+id_kategori+"&cari="+cari;
	document.form1.submit();
}	
</script>
