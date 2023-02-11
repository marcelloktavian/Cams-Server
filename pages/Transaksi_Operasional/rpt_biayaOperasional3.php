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
}.style99 {font-size: 10pt; font-family:Tahoma}
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
.style19b { color: #000000;
  font-size: 11pt;
  font-weight: bold;
  font-family: Tahoma;
}
.style_title {  color: #000000;
  font-size: 7pt; 
  font-family: Tahoma;
  border-top: 1px solid black;
  border-bottom: 1px solid black;
  border-right: 1px solid black;
  
  
  padding: 3px;
}
.style_title_left { color: #000000;
  font-size: 7pt; 
  font-family: Tahoma;
  border-top: 1px solid black;
  border-bottom: 1px solid black;
  border-right: 1px solid black;
  border-left: 1px solid black;
  
  padding: 3px;
}
.style_detail { color: #000000;
  font-size: 7pt; 
  font-family: Tahoma;
  border-right: 1px solid black;
  padding: 3px;
}
.style_detail2 { color: #000000;
  font-size: 6pt; 
  font-family: Tahoma;
  border-right: 1px solid black;
}
.style_detail_left {  color: #000000;
  font-size: 7pt; 
  font-family: Tahoma;
  border-left: 1px solid black;
  border-right: 1px solid black;
  padding: 3px;
}
.style_detail_up {  color: #000000;
  font-size: 7pt; 
  font-family: Tahoma;
  border-top: 1px solid black;
  padding: 3px;
}
#container{width:100%;}
#timestamp{font-size: 9pt;  
  font-family: Tahoma;}
#left{float:left;width:30%;}
#right{float:right;width:70%;}
#left2{float:left;width:55%;}
#right2{float:right;width:45%;}
#left3{float:left;width:93%;}
#right3{float:right;width:7%;}
@page {
        size: A4;
        margin: 15px;
    }
</style>

<!-- php disini -->
<?php
  error_reporting(0);

  function rupiah($angka){
  $hasil_rupiah = number_format($angka,0,'.',',');
  return $hasil_rupiah;
 
}

// koneksi dengan database
  require "../../include/koneksi.php";
  $startdate = $_GET['start'];
  $enddate = $_GET['end'];
  $filter = $_GET['filter'];
// 
  $where = '';
 if ($filter == '') {
    $where = "WHERE mstop.deleted=0 AND mstop.tanggal BETWEEN STR_TO_DATE('".$startdate."','%d/%m/%Y') AND STR_TO_DATE('".$enddate."','%d/%m/%Y')";
 } else {
    $where = "WHERE mstop.deleted=0 AND mstop.tanggal BETWEEN STR_TO_DATE('".$startdate."','%d/%m/%Y') AND STR_TO_DATE('".$enddate."','%d/%m/%Y')";
 }
 
  $sql="SELECT kat.nama_kategori, mstbiaya.nama_jenis, detbiaya.nama_biaya ,CONCAT(round(SUM(detop.qty)),' ',detop.satuan) AS jml_satuan, 
  detop.harga_satuan, SUM(detop.subtotal) AS subtotal FROM biayaoperasional mstop
  LEFT JOIN biayaoperasional_det detop ON mstop.id=detop.id_parent
  LEFT JOIN det_jenisbiaya detbiaya ON detbiaya.id=detop.id_det_jenisbiaya
  LEFT JOIN mst_jenisbiaya mstbiaya ON mstbiaya.id=detbiaya.id_parent
  LEFT JOIN mst_kategori_biaya kat ON kat.id=mstbiaya.id_kategori
  ".$where." AND kat.id<>9 AND mstbiaya.id<>27 AND (detbiaya.id <> 163 AND detbiaya.id <> 164 AND detbiaya.id <> 186 AND detbiaya.id <> 187 AND detbiaya.id <> 269)
  GROUP BY detbiaya.id
  ORDER BY kat.id ASC, mstbiaya.id ASC, detbiaya.nama_biaya ASC ";
  // ,detop.harga_satuan
  // detop.harga_satuan
    // var_dump($sql);die;
    $result= mysql_query($sql);
?>
<form id="form" name="form" action="" method="post"  onSubmit="return validasi(this)">
  <?php
  $no=1;
  $bagian = '';
  $jenis = '';
  $sub=0;
  $total=0;
  $jumlah = 0;
  while ($data = mysql_fetch_array($result)):
    if(fmod($jumlah,39)==0){
    ?>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td height="123" valign="top"><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
          
          <tr>
            <td width="100%" class="style99" colspan="7"><strong>
      PT. AGUNG KEMUNINGWIJAYA - BANDUNG </strong></td>
     
            
          </tr>
          <tr>
            <td width="100%" class="style9b" colspan="7"> DATA PEMBELIAN & BIAYA KAS PERIODE :
            <?php echo"".$startdate;?>
            &nbsp;-&nbsp;<?php echo"".$enddate;?> </td>  
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
            <td colspan="10" class="style9"><hr /></td>
          </tr>
      <tr>
    <tr>
    <!-- 1 -->
   
            <td><div class="style_title_left" align='left'>NO</div></td>
        <td><div class="style_title" align='center'>NAMA BAGIAN</div></td>
        <td colspan='3'><div class="style_title" align='center'>JENIS PENGELUARAN</div></td>
        <td><div class="style_title" align='center'>JML<font style="color:white;">_</font>SATUAN</div></td>
        <td><div class="style_title" align='center'>HRG<font style="color:white;">_</font>SATUAN</div></td>
        <td><div class="style_title" align='center'>JUMLAH</div></td>
        <td><div class="style_title" align='center'>KEETRANGAN</div></td>
          <?php
      }
    ?>
        
    </tr>
    <tr>
    <?php
    
      if ($no<>1 && $jenis != $data['nama_jenis']) {
            ?>
              <tr>
                <td><div class='style_detail_left'>&nbsp;</div></td>
                <td><div class='style_detail'>&nbsp;</div></td>
                <td><div class='style_detail'>&nbsp;</div></td>
                <td colspan="2"><div class='style_detail'>&nbsp;</div></td>
                <td><div class='style_detail'>&nbsp;</div></td>
                <td><div class='style_detail'>&nbsp;</div></td>
                <td><div class='style_detail'>&nbsp;</div></td>
                <td><div class='style_detail'>&nbsp;</div></td>
              </tr>
            <?php
          }
          $jumlah++;
        if($bagian != $data['nama_kategori']){
            ?>
                 <td><div class='style_detail_left' align='right'><?= $no ?> </div></td>
                 <td class='style_detail'>
        <div id="container" >
  <div id="left3"><?= $data['nama_kategori'] ?></div>
  <div id="right3" align='right'><&nbsp;</div>
</div>
    
    </td>

            <?php
            
            $no++;
        }else{
            ?>
                <td><div class='style_detail_left'>&nbsp;</div></td>
                <td> <div class='style_detail' id="right3" align='right'><&nbsp;</div> </td>
                
            <?php
            
        }

        if ($jenis != $data['nama_jenis']) {
            ?>
                <td colspan='1'><div class='style_detail'><?= $data['nama_jenis'] ?> </div></td>
            <?php
            $sub=0;
        } else {
            ?>
              <td colspan='1'><div class='style_detail'>&nbsp;</div></td>
            <?php
            
        }
        
    ?>
        <td colspan='2'><div class='style_detail'><?= $data['nama_biaya'] ?>   <?=$jumlah?> </div></td>
        <td><div class='style_detail' align='right'><?= $data['jml_satuan'] ?> </div></td>
        <td class='style_detail'>
        <div id="container" >
  <div id="left">RP. </div>
  <div id="right" align='right'><?= rupiah($data['harga_satuan']) ?></div>
</div>
    
    </td>
    <td class='style_detail'>
        <div id="container" >
  <div id="left">RP. </div>
  <div id="right" align='right'><?= rupiah($data['subtotal']) ?></div>
</div>
    
    </td>
        <!-- <td><div class='style_detail' align='right'> </div></td> -->
        <?php
        if ($jenis == $data['nama_jenis']) {
            echo "</tr>";
        }
        
        ?>
    
    <?php
      $sql2="SELECT kat.nama_kategori, mstbiaya.nama_jenis, detbiaya.nama_biaya ,CONCAT(ROUND(SUM(detop.qty)),' ',detop.satuan) AS jml_satuan, 
      detop.harga_satuan, SUM(detop.subtotal) AS subtotal FROM biayaoperasional mstop
      LEFT JOIN biayaoperasional_det detop ON mstop.id=detop.id_parent
      LEFT JOIN det_jenisbiaya detbiaya ON detbiaya.id=detop.id_det_jenisbiaya
      LEFT JOIN mst_jenisbiaya mstbiaya ON mstbiaya.id=detbiaya.id_parent
      LEFT JOIN mst_kategori_biaya kat ON kat.id=mstbiaya.id_kategori
      ".$where." AND  mstbiaya.nama_jenis ='".$data['nama_jenis']."' AND (detbiaya.id <> 163 AND detbiaya.id <> 164 AND detbiaya.id <> 186 AND detbiaya.id <> 187 AND detbiaya.id <> 269)
      GROUP BY detbiaya.id
      ORDER BY kat.id ASC, mstbiaya.id ASC, detbiaya.nama_biaya ASC ";
      // detop.harga_satuan
    $num_rows = mysql_num_rows(mysql_query($sql2));

    $sub=$sub+$data['subtotal'];
    $total=$total+$data['subtotal'];
    if ($jenis != $data['nama_jenis']) {
        ?>
            <td rowspan='<?= $num_rows ?>' class='style_detail2' style="vertical-align:bottom;" >
            <div id='subtotal_<?= $data['nama_jenis'] ?>'></div></td>
            </tr>
        <?php
    }
    ?>
    <script>document.getElementById("subtotal_<?= $data['nama_jenis'] ?>").innerHTML = '<div id="container"><u><?= $data['nama_jenis'] ?></u><br><div id="left2">SUBTOTAL : RP.</div><div id="right2" align="right"><?= rupiah($sub) ?></div></div>';</script>
    <?php
    
    if(fmod($jumlah,39)==0){
       ?>
        <tr>
                <td><div class='style_detail_up'>&nbsp;</div></td>
                <td><div class='style_detail_up'>&nbsp;</div></td>
                <td><div class='style_detail_up'>&nbsp;</div></td>
                <td colspan="2"><div class='style_detail_up'>&nbsp;</div></td>
                <td><div class='style_detail_up'>&nbsp;</div></td>
                <td><div class='style_detail_up'>&nbsp;</div></td>
                <td><div class='style_detail_up'>&nbsp;</div></td>
                <td><div class='style_detail_up'>&nbsp;</div></td>
        </tr>
        <tr>
          <td>
          <br><br><br><br><br><br><br><br><br><br><br><br><br><br>
          </td>
        </tr>
       <?php
    }

    $bagian = $data['nama_kategori'];
    $jenis = $data['nama_jenis'];
    endwhile;

    $sqlppn = "SELECT SUM(detop.ppn) AS ppn FROM biayaoperasional mstop
      LEFT JOIN biayaoperasional_det detop ON mstop.id=detop.id_parent
      LEFT JOIN det_jenisbiaya detbiaya ON detbiaya.id=detop.id_det_jenisbiaya
      LEFT JOIN mst_jenisbiaya mstbiaya ON mstbiaya.id=detbiaya.id_parent
      LEFT JOIN mst_kategori_biaya kat ON kat.id=mstbiaya.id_kategori
      ".$where." AND (detbiaya.id <> 163 AND detbiaya.id <> 164 AND detbiaya.id <> 186 AND detbiaya.id <> 187 AND detbiaya.id <> 269)
      ";
      $result2= mysql_query($sqlppn);
      $ppn = 0;
      while ($data2 = mysql_fetch_array($result2)):
        $ppn = $data2['ppn'];
      endwhile;

      if($ppn != 0){
    ?>
    <tr>
      <td><div class='style_detail_left' align='right'>&nbsp;</div></td>
      <td><div class='style_detail' align='right'>&nbsp;</div></td>
      <td><div class='style_detail' align='right'>&nbsp;</div></td>
      <td><div class='style_detail' align='right'>&nbsp;</div></td>
      <td></td>
      <td><div class='style_detail' align='right'>&nbsp;</div></td>
      <td><div class='style_detail' align='right'>&nbsp;</div></td>
      <td><div class='style_detail' align='right'>&nbsp;</div></td>
      <td><div class='style_detail' align='right'>&nbsp;</div></td>
      
    </tr>
    <tr>
      <td><div class='style_detail_left' align='right'><?= $no ?> </div></td>
      <td class='style_detail'>
        <div id="container" >
        <div id="left3">PPN MASUKAN ATAS PEMBELIAN

</div>
        <div id="right3" align='right'><&nbsp;</div>
      </div>
    </td>
    <td colspan='1'><div class='style_detail'>PPN MASUKAN ATAS PEMBELIAN</div></td>
    <td colspan='2'><div class='style_detail'>BAHAN PRODUKSI</div></td>
    <td><div class='style_detail' align='right'>1</div></td>
    <td class='style_detail'><div id="container" >
      <div id="left">RP. </div>
      <div id="right" align='right'><?= rupiah($ppn) ?></div>
      </div>
    </td>
    <td class='style_detail'><div id="container" >
      <div id="left">RP. </div>
      <div id="right" align='right'><?= rupiah($ppn) ?></div>
      </div>
    </td>
    <td class='style_detail2'> <div id="container"><u>PPN MASUKAN ATAS PEMBELIAN</u><br><div id="left2">SUBTOTAL : RP.</div><div id="right2" align="right"><?= rupiah($ppn) ?></div></div></td>
    </tr>
  <?php } ?>
    <tr>
    <td colspan='7'><div class='style_title_left' style="border-right: 0px solid black;" align='right'><b>JUMLAH</sb></div></td>
    <td class='style_title' style="border-right: 0px solid black;" colspan='1'>
        <div id="container" >
  <div id="left">RP. </div>
  <div id="right" align='right'><b><?= rupiah($total+$ppn) ?></b></div>
</div>
    
    </td>
    
    <td colspan='1'><div class='style_title' >&nbsp;</div></td>

    </tr>
    </table>
</form>

<br><br><br><br><br><br><br><br><br><br><br>

<!-- Tabel Persediaan Bahan Baku -->
<?php
  $where = '';
 if ($filter == '') {
    $where = "WHERE mstop.deleted=0 AND mstop.tanggal BETWEEN STR_TO_DATE('".$startdate."','%d/%m/%Y') AND STR_TO_DATE('".$enddate."','%d/%m/%Y')";
 } else {
    $where = "WHERE mstop.deleted=0 AND mstop.tanggal BETWEEN STR_TO_DATE('".$startdate."','%d/%m/%Y') AND STR_TO_DATE('".$enddate."','%d/%m/%Y')";
 }
 
  $sql="SELECT kat.nama_kategori, mstbiaya.nama_jenis, detbiaya.nama_biaya ,CONCAT(round(SUM(detop.qty)),' ',detop.satuan) AS jml_satuan, 
  detop.harga_satuan, SUM(detop.subtotal) AS subtotal FROM biayaoperasional mstop
  LEFT JOIN biayaoperasional_det detop ON mstop.id=detop.id_parent
  LEFT JOIN det_jenisbiaya detbiaya ON detbiaya.id=detop.id_det_jenisbiaya
  LEFT JOIN mst_jenisbiaya mstbiaya ON mstbiaya.id=detbiaya.id_parent
  LEFT JOIN mst_kategori_biaya kat ON kat.id=mstbiaya.id_kategori
  ".$where." AND (kat.id=9 OR (kat.id=3 AND (detbiaya.id = 163 OR detbiaya.id = 164 OR detbiaya.id =  186 OR detbiaya.id = 187 OR detbiaya.id = 269)) OR (kat.id=7 AND mstbiaya.id=27))
  GROUP BY detbiaya.id
  ORDER BY kat.lastmodified ASC, kat.id ASC, mstbiaya.id ASC, detbiaya.nama_biaya ASC ";
  // ,detop.harga_satuan
  // detop.harga_satuan
    // var_dump($sql);die;
    $result= mysql_query($sql);
?>
<form id="form" name="form" action="" method="post"  onSubmit="return validasi(this)">
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td height="123" valign="top"><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
          
          <tr>
            <td width="100%" class="style99" colspan="7"><strong>
      PT. AGUNG KEMUNINGWIJAYA - BANDUNG </strong></td>
      <td style="text-align:right">
            </td>
            
          </tr>
          <tr>
            <td width="100%" class="style9b" colspan="7"> DATA PEMBELIAN BAHAN BAKU & LAIN2 PERIODE :
            <?php echo"".$startdate;?>
            &nbsp;-&nbsp;<?php echo"".$enddate;?> </td>           
      </tr>
  </table>  

    <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
            <td colspan="10" class="style9"><hr /></td>
          </tr>
      <tr>
    <tr>
    <!-- 1 -->
        <td><div class="style_title_left" align='left'>NO</div></td>
        <td><div class="style_title" align='center'>NAMA BAGIAN</div></td>
        <td colspan='3'><div class="style_title" align='center'>JENIS PENGELUARAN</div></td>
        <td><div class="style_title" align='center'>JML<font style="color:white;">_</font>SATUAN</div></td>
        <td><div class="style_title" align='center'>HRG<font style="color:white;">_</font>SATUAN</div></td>
        <td><div class="style_title" align='center'>JUMLAH</div></td>
        <td><div class="style_title" align='center'>KEETRANGAN</div></td>
    </tr>
    <tr>
    <?php
    $no=1;
    $bagian = '';
    $jenis = '';
    $sub=0;
    $total=0;
    while ($data = mysql_fetch_array($result)):
      if ($no<>1 && $jenis != $data['nama_jenis']) {
            ?>
              <tr>
                <td><div class='style_detail_left'>&nbsp;</div></td>
                <td><div class='style_detail'>&nbsp;</div></td>
                <td><div class='style_detail'>&nbsp;</div></td>
                <td colspan="2"><div class='style_detail'>&nbsp;</div></td>
                <td><div class='style_detail'>&nbsp;</div></td>
                <td><div class='style_detail'>&nbsp;</div></td>
                <td><div class='style_detail'>&nbsp;</div></td>
                <td><div class='style_detail'>&nbsp;</div></td>
              </tr>
            <?php
          }
        if($bagian != $data['nama_kategori']){
            ?>
                 <td><div class='style_detail_left' align='right'><?= $no ?> </div></td>
                 <td class='style_detail'>
        <div id="container" >
  <div id="left3"><?= $data['nama_kategori'] ?></div>
  <div id="right3" align='right'><&nbsp;</div>
</div>
    
    </td>

            <?php
            $no++;
        }else{
            ?>
                <td><div class='style_detail_left'>&nbsp;</div></td>
                <td> <div class='style_detail' id="right3" align='right'><&nbsp;</div> </td>
            <?php
        }

        if ($jenis != $data['nama_jenis']) {
            ?>
                <td colspan='1'><div class='style_detail'><?= $data['nama_jenis'] ?> </div></td>
            <?php
            $sub=0;
        } else {
            ?>
              <td colspan='1'><div class='style_detail'>&nbsp;</div></td>
            <?php
        }
        
    ?>
        <td colspan='2'><div class='style_detail'><?= $data['nama_biaya'] ?> </div></td>
        <td><div class='style_detail' align='right'><?= $data['jml_satuan'] ?> </div></td>
        <td class='style_detail'>
        <div id="container" >
  <div id="left">RP. </div>
  <div id="right" align='right'><?= rupiah($data['harga_satuan']) ?></div>
</div>
    
    </td>
    <td class='style_detail'>
        <div id="container" >
  <div id="left">RP. </div>
  <div id="right" align='right'><?= rupiah($data['subtotal']) ?></div>
</div>
    
    </td>
        <!-- <td><div class='style_detail' align='right'> </div></td> -->
        <?php
        if ($jenis == $data['nama_jenis']) {
            echo "</tr>";
        }
       
        ?>
    
    <?php
    if ($data['nama_jenis'] == 'ANGKUTAN') {
      $where2 = $where." AND (detbiaya.id = 163 OR detbiaya.id = 164 OR detbiaya.id = 186 OR detbiaya.id = 187 OR detbiaya.id = 269) ";
    }else{
      $where2 = $where;
    }
      $sql2="SELECT kat.nama_kategori, mstbiaya.nama_jenis, detbiaya.nama_biaya ,CONCAT(ROUND(SUM(detop.qty)),' ',detop.satuan) AS jml_satuan, 
      detop.harga_satuan, SUM(detop.subtotal) AS subtotal FROM biayaoperasional mstop
      LEFT JOIN biayaoperasional_det detop ON mstop.id=detop.id_parent
      LEFT JOIN det_jenisbiaya detbiaya ON detbiaya.id=detop.id_det_jenisbiaya
      LEFT JOIN mst_jenisbiaya mstbiaya ON mstbiaya.id=detbiaya.id_parent
      LEFT JOIN mst_kategori_biaya kat ON kat.id=mstbiaya.id_kategori
      ".$where2." AND  mstbiaya.nama_jenis ='".$data['nama_jenis']."' 
      GROUP BY detbiaya.id
      ORDER BY kat.id ASC, mstbiaya.id ASC, detbiaya.nama_biaya ASC ";
      // detop.harga_satuan
    $num_rows = mysql_num_rows(mysql_query($sql2));

    $sub=$sub+$data['subtotal'];
    $total=$total+$data['subtotal'];
    if ($jenis != $data['nama_jenis']) {
        ?>
            <td rowspan='<?= $num_rows ?>' class='style_detail2' style="vertical-align:bottom;" >
            <div id='subtotal2_<?= $data['nama_jenis'] ?>'></div></td>
            </tr>
        <?php
    }
    ?>
    <script>document.getElementById("subtotal2_<?= $data['nama_jenis'] ?>").innerHTML = '<div id="container"><u><?= $data['nama_jenis'] ?></u><br><div id="left2">SUBTOTAL : RP.</div><div id="right2" align="right"><?= rupiah($sub) ?></div></div>';</script>
    <?php
    
    $bagian = $data['nama_kategori'];
    $jenis = $data['nama_jenis'];
    
    

    endwhile;
    ?>
    <tr>
    <td colspan='7'><div class='style_title_left' style="border-right: 0px solid black;" align='right'><b>JUMLAH</sb></div></td>
    <td class='style_title' style="border-right: 0px solid black;" colspan='1'>
        <div id="container" >
  <div id="left">RP. </div>
  <div id="right" align='right'><b><?= rupiah($total) ?></b></div>
</div>
    
    </td>
    
    <td colspan='1'><div class='style_title' >&nbsp;</div></td>
    </tr>
    </table>
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
