<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>SAVE Not Allowed (GAGAL SIMPAN)</title>
  <link rel="stylesheet" href="/error_docs/styles.css">
<?php 
  include("../../include/koneksi.php");
  $id_trans=$_GET['id_trans'];
  $sql = mysql_query("SELECT * FROM olnso so WHERE so.id_trans= '".$_GET['id_trans']."'")or die (mysql_error());
  $rs = mysql_fetch_array($sql);

?>

 </head>
<body>
<div class="page">
  <div class="main">
    <h1>Server Error/</h1>
    <div class="error-code">405</div>
    <h2>Save Not Allowed (Simpan Data Tidak Diperbolehkan)</h2>
    <p class="lead">There are duplicate WEB KODE (ID WEB)/ Kode Web atau Kode Expedisi sudah dipakai  di transaksi terakhir <?php echo"".$id_trans; ?></p>
    <hr/>
    <p>That's what you can do</p>
    <div class="help-actions">
      <!--<a href="javascript:location.reload();">Reload Page</a>
      <a href="/">Home Page</a>
	  -->
	  <a href="javascript:history.back();">Back to Previous Page</a>
      
    </div>
  </div>
</div>
</body>
</html>

