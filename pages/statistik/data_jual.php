<?php
error_reporting(0);
session_start();
  
$id_user=$_SESSION['id_user'];
include("../../include/koneksi.php");
$blnstart=$_GET['start'];
$blnend=$_GET['end'];
/*
$host="localhost";
$user="root";
$password="";	
$koneksi=mysql_connect($host,$user,$password) or die("Gagal Koneksi Database");
mysql_select_db("kuesioner");
*/
// write your SQL query here (you may use parameters from $_GET or $_POST if you need them)
   //- menghitung selisih bulan--------------------------
   //$date = date("Y-m-d");
   
   $awal = date("Y-m-d", strtotime($blnstart));
   $akhir = date("Y-m-d", strtotime($blnend));

   $timeStart = strtotime("$blnstart");
   $timeEnd = strtotime("$blnend");
   // Menambah bulan ini + semua bulan pada tahun sebelumnya
   $numBulan = 1 + (date("Y",strtotime($blnend))-date("Y",strtotime($blnstart)));
   $numBulan += date("m",strtotime($blnend))-date("m",strtotime($blnstart));
   //echo date("Y", strtotime($blnend));die;

   //echo $numBulan;die;
   //-----------------------------------------------------
   
    $where = "";
	$filtertgl = "";
	$filtertgl ="STR_TO_DATE('$blnstart','%d-%m-%Y') AND STR_TO_DATE('$blnend','%d-%m-%Y')";
	//$filtertgl = "AND DATE(tgl_trans) <= STR_TO_DATE('$tglend','%d/%m/%Y')";
	//var_dump($filtertgl); die; 
	if($id_pelanggan != null){
	$where .= $filtertgl." AND b.id=$id_pelanggan";
	}
	else
	{
	$where .= $filtertgl;
	}
	$sql_data="Select tr.kode_brg,tr.id_barang,(sum(tr.totalqty*30)/$numBulan) as qty_terjual from (
	Select brg.kode_brg,brg.id_barang,sum(ifnull(bd.qty,0)) as totalqty from trbeli_detail bd left join barang brg on bd.id_barang=brg.id_barang left join trbeli bl on bd.id_trans=bl.id_trans where DATE(bl.tgl_trans) BETWEEN $where group by brg.kode_brg 
	union all
	Select brg.kode_brg,brg.id_barang,(sum(jd.qty*30)/$numBulan) as totalqty from trjual_detail jd left join barang brg on jd.kode_brg=brg.kode_brg left join trjual j on jd.id_trans=j.id_trans where DATE(j.tgl_trans) BETWEEN $where group by brg.kode_brg) as tr group by tr.kode_brg order by tr.kode_brg + 0 asc";

//var_dump($sql_data); die; 
//echo $blnstart;die;
//$originalDate = "2010-03-21";
//$awal =date_create(date("Y-m-d", strtotime($blnstart)));
//echo date("Y", strtotime($blnend));die;
//echo $awal;die;
//echo $numBulan;die;
$query = mysql_query($sql_data);
 
$table = array();
$table['cols'] = array(
/* Disini kita mendefinisikan data pada tabel database
* masing-masing kolom akan kita ubah menjadi array
* Kolom tersebut adalah parameter (string) dan nilai (integer/number)
* Pada bagian ini kita juga memberi penamaan pada hasil chart nanti
*/
array('label' => 'id_barang', 'type' => 'string'),
array('label' => 'Rata rata', 'type' => 'number')
);
// melakukan query yang akan menampilkan array data
$rows = array();
while($r = mysql_fetch_assoc($query)) {
$temp = array();
// masing-masing kolom kita masukkan sebagai array sementara
$temp[] = array('v' => $r['id_barang']);
$temp[] = array('v' => (int) $r['qty_terjual']);
$rows[] = array('c' => $temp);
}
// mempopulasi row tabel
$table['rows'] = $rows;
// encode tabel ke bentuk json
$jsonTable = json_encode($table);
//var_dump($jsonTable); 
// set up header untuk JSON, wajib.
header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Content-type: application/json');
 
// menampilkan data hasil query ke bentuk json
echo $jsonTable;
?>