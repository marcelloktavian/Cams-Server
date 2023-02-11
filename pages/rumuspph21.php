<?php
	include("../include/koneksi.php");
	?>
<b>Kalkulator PPH 21</b><br><br>
<form>
    Objek Pajak : <input type="text" name="op" id="op"><br>
    PTKP : <select name="ptkp" id="ptkp">
        <?php
            $data = mysql_query("SELECT * FROM hrd_ptkp where deleted=0 order by `group` asc, nama_ptkp asc");
			while($d = mysql_fetch_array($data)){
                ?><option value="<?= $d['value'] ?>"><?= $d['nama_ptkp'] ?></option><?php
            }
        ?>
    </select><br>
    Biaya Jabatan :  <?php
            $data = mysql_query("SELECT * FROM hrd_biayajabatan");
			while($d = mysql_fetch_array($data)){
                echo $d['persentase']. '% &nbsp;'.number_format($d['maxbiaya'],0);
                ?>
                    <input type="hidden" name="jabatan" id="jabatan" value="<?=$d['persentase']?>|<?=$d['maxbiaya']?>">
                <?php
            }
           
        ?>
        <br><button type="submit">Hitung</button>
</form>
<?php
if(isset($_GET['op'])){
    $op = $_GET['op'];
    $ptkp = $_GET['ptkp'];

    $opx12 = $op * 12;

    $ex = explode("|",$_GET['jabatan']);
    $persenjabatan = $ex[0];
    $biayajabatan = $ex[1];

    $jabatan = ($persenjabatan/100) * $opx12;
    if($jabatan > $biayajabatan){
        $jabatan = $biayajabatan;
    }

    echo "Objek Pajak = ".number_format($op);
    echo "<br>";
    echo "Objek Pajak x 12 = ".number_format($opx12);
    echo "<br>";
    echo "Biaya PTKP = ".number_format($ptkp);
    echo "<br>";
    echo "Biaya Jabatan = ".number_format($jabatan);
    echo "<br>";

    $pkp = $opx12 - $ptkp - $jabatan;
    echo "PKP = ".number_format($pkp);

    echo "<br>";
    echo "<br>";
    $pph21 = 0;
    // echo "SELECT * FROM hrd_pkp WHERE $pkp>(minvalue*1000000) AND $pkp<(maksvalue*1000000)";

    $data = mysql_query("SELECT * FROM hrd_pkp WHERE $pkp>(minvalue*1000000) AND $pkp<=(maksvalue*1000000)");
	while($d = mysql_fetch_array($data)){
        if($d['id']==1){
            //< 60 jt
            echo $d['persen1'].'% x '.number_format($pkp).' = '.number_format($pkp * ($d['persen1']/100));
            echo "<br>";
            echo "<br>";

            $pph21 = $pkp * ($d['persen1']/100);
            // echo "1";
        }else if($d['id']==2){
            //60 jt - 250 jt
            echo $d['persen1'].'% x '.number_format($d['maksvalue1'] * 1000000).' = '.number_format(($d['maksvalue1'] * 1000000) * ($d['persen1']/100));
            echo "<br>";
            echo $d['persen2'].'% x '.number_format(($pkp-($d['maksvalue1'] * 1000000))).' = '.number_format(($pkp-($d['maksvalue1'] * 1000000)) * ($d['persen2']/100));
            echo "<br>";
            echo "<br>";

            $pph21 = ($d['maksvalue1'] * 1000000) * ($d['persen1']/100) + ($pkp-($d['maksvalue1'] * 1000000)) * ($d['persen2']/100);
            // echo "2";
        }else if($d['id']==3){
            //250 jt - 500 jt
            echo $d['persen1'].'% x '.number_format($d['maksvalue1'] * 1000000).' = '.number_format(($d['maksvalue1'] * 1000000) * ($d['persen1']/100));
            echo "<br>";
            echo $d['persen2'].'% x '.number_format((($d['maksvalue2']-$d['maksvalue1']) * 1000000)).' = '.number_format((($d['maksvalue2']-$d['maksvalue1']) * 1000000) * ($d['persen2']/100));
            echo "<br>";
            echo $d['persen3'].'% x '.number_format(($pkp-($d['maksvalue2'] * 1000000))).' = '.number_format(($pkp-($d['maksvalue2'] * 1000000)) * ($d['persen3']/100));
            echo "<br>";
            echo "<br>";

            $pph21 = ($d['maksvalue1'] * 1000000) * ($d['persen1']/100) + (($d['maksvalue2']-$d['maksvalue1']) * 1000000) * ($d['persen2']/100) + ($pkp-($d['maksvalue2'] * 1000000)) * ($d['persen3']/100);
            // echo "3";
        }else if($d['id']==4){
            //500 jt - 5 M
            echo $d['persen1'].'% x '.number_format($d['maksvalue1'] * 1000000).' = '.number_format(($d['maksvalue1'] * 1000000) * ($d['persen1']/100));
            echo "<br>";
            echo $d['persen2'].'% x '.number_format((($d['maksvalue2']-$d['maksvalue1']) * 1000000)).' = '.number_format((($d['maksvalue2']-$d['maksvalue1']) * 1000000) * ($d['persen2']/100));
            echo "<br>";
            echo $d['persen3'].'% x '.number_format((($d['maksvalue3']-$d['maksvalue2']) * 1000000)).' = '.number_format((($d['maksvalue3']-$d['maksvalue2']) * 1000000) * ($d['persen3']/100));
            echo "<br>";
            echo $d['persen4'].'% x '.number_format(($pkp-($d['maksvalue3'] * 1000000))).' = '.number_format(($pkp-($d['maksvalue3'] * 1000000)) * ($d['persen4']/100));
            echo "<br>";
            echo "<br>";

            $pph21 = ($d['maksvalue1'] * 1000000) * ($d['persen1']/100) + (($d['maksvalue2']-$d['maksvalue1']) * 1000000) * ($d['persen2']/100) + (($d['maksvalue3']-$d['maksvalue2']) * 1000000) * ($d['persen3']/100) + ($pkp-($d['maksvalue3'] * 1000000)) * ($d['persen4']/100);
            // echo "4";
        }else{
            //> 5 M
            echo $d['persen1'].'% x '.number_format($d['maksvalue1'] * 1000000).' = '.number_format(($d['maksvalue1'] * 1000000) * ($d['persen1']/100));
            echo "<br>";
            echo $d['persen2'].'% x '.number_format((($d['maksvalue2']-$d['maksvalue1']) * 1000000)).' = '.number_format((($d['maksvalue2']-$d['maksvalue1']) * 1000000) * ($d['persen2']/100));
            echo "<br>";
            echo $d['persen3'].'% x '.number_format((($d['maksvalue3']-$d['maksvalue2']) * 1000000)).' = '.number_format((($d['maksvalue3']-$d['maksvalue2']) * 1000000) * ($d['persen3']/100));
            echo "<br>";
            echo $d['persen4'].'% x '.number_format((($d['maksvalue4']-$d['maksvalue3']) * 1000000)).' = '.number_format((($d['maksvalue4']-$d['maksvalue3']) * 1000000) * ($d['persen4']/100));
            echo "<br>";
            echo $d['persen5'].'% x '.number_format(($pkp-($d['maksvalue4'] * 1000000))).' = '.number_format(($pkp-($d['maksvalue4'] * 1000000)) * ($d['persen5']/100));
            echo "<br>";
            echo "<br>";

            $pph21 = ($d['maksvalue1'] * 1000000) * ($d['persen1']/100) + (($d['maksvalue2']-$d['maksvalue1']) * 1000000) * ($d['persen2']/100) + (($d['maksvalue3']-$d['maksvalue2']) * 1000000) * ($d['persen3']/100) + (($d['maksvalue4']-$d['maksvalue3']) * 1000000) * ($d['persen4']/100) + ($pkp-($d['maksvalue4'] * 1000000)) * ($d['persen5']/100);
            // echo "5";
        }
    }

    echo "Tarif PPH21 = ".number_format($pph21);
    echo "<br>";
    $pph21bulan = $pph21/12;
    echo "Tarif PPH21 Bulanan = ".number_format($pph21bulan);
}
?>