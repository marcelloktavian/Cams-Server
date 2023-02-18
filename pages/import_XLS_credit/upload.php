<!DOCTYPE html>
<html>
<head>
	<title>IMPORT XLS CREDIT MARKETPLACE</title>
</head>
<body>
	<style type="text/css">
		body{
			background-color: #3e94ec;
			font-family: "Roboto", helvetica, arial, sans-serif;
			font-size: 12px;
			font-weight: 400;
			text-rendering: optimizeLegibility;
		}

		p{
			color: green;
		}
	</style>
	<h2>IMPORT XLS CREDIT MARKETPLACE</h2>


	<a href="importcamoucredit.php">Kembali</a>
	<br/><br/>
	<?php 
	include("../../include/koneksi.php");
	?>

	<form method="post" enctype="multipart/form-data" action="upload_aksi.php">
		Pilih File: 
		<input name="filecamou" type="file" required="required"> 
		<input name="upload" type="submit" value="Import">
	</form>

	<br/><br/>

</body>
</html>