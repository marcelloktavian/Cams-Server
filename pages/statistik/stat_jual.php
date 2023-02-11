<!DOCTYPE html>
<html>
<?php
error_reporting(0);
$tglstart=$_GET['start'];
$tglstart= substr_replace($tglstart,'01-',0,0);
$tglend=$_GET['end'];
$tglend= substr_replace($tglend,'01-',0,0);
?>
<head>
	<title>Chart Penjualan TR</title>
        <!-- Meng-embed Google API -->
        <script type="text/javascript" src="https://www.google.com/jsapi"></script>
        <!-- Mengembed Jquery -->
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
	<script type="text/javascript">
	// Meload paket API dari Google Chart
		google.load('visualization', '1', {'packages':['corechart']});
		// Membuat Callback yang meload API visualisasi Google Chart
		google.setOnLoadCallback(drawChart);
			function drawChart() {
				var json = $.ajax({
					url: 'data_jual.php?start=<?php echo"".$tglstart;?>&end=<?php echo"".$tglend;?>', // file json hasil query database
					dataType: 'json',
					async: false
				}).responseText;
				
				// Mengambil nilai JSON
				var data = new google.visualization.DataTable(json);
				var options = {
					title: 'Rata2 Penjualan TR<?php echo" dari ".$_GET['start']." sd ".$_GET['end'] ?>',
					colors: ['#e6693e'],
					width: 1000,
					height: 4000
				};
				// API Chart yang akan menampilkan ke dalam div id
				var chart = new google.visualization.BarChart(document.getElementById('tampil_chart'));
				chart.draw(data, options);
			}
		</script>  
</head>
<body>  
	<!-- Menampilkan dalam bentuk chart dengan ukuran yang telah disesuaikan -->
	<div id="tampil_chart" style="width: 1000px; height: 4000px;"></div>
</body>
</html>