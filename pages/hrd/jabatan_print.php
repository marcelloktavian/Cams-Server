<style>
    table, th, td {
        border: 1px solid black;
        border-collapse: collapse;
    }
    .title{
        font-family: "Roboto";
        font-size: 14px;
        font-weight: bold;
        padding: 15px;
    }
    .detail{
        font-family: "Roboto";
        font-size: 10px;
        font-weight: bold;
        padding: 5px;
        vertical-align:top;
    }
    .detail2{
        font-family: "Roboto";
        font-size: 10px;
        padding: 5px;
    }
    .detail3{
        font-family: "Roboto";
        font-size: 10px;
        padding-left:10px;
        padding-top:5px;
    }
    @media screen {
    div.divFooter {
        display: none;
    }
    }
    @media print {
    div.divFooter {
        position: fixed;
        bottom: 0;
        font-family: "Arial";
        font-size: 11px;
    }
    }
    #container{width:100%;}
    #left{float:left;width:95%;}
    #right{float:right;width:5%;}
</style>
<?php
    error_reporting(0);
    include("../../include/koneksi.php");
    $id=$_GET['id'];
    //$id_faktur=TSO18020021;
    $sql="SELECT c.*, d.nama_dept  FROM `hrd_jabatan` c LEFT JOIN hrd_departemen d ON d.id_dept=c.id_dept WHERE c.id_jabatan=$id AND c.deleted=0";
    $sq = mysql_query($sql);
    $rs = mysql_fetch_array($sq);
 
    $jabatan = $rs['nama_jabatan'];
    $departemen = $rs['nama_dept'];
    $lokasi = $rs['lokasi_kerja'];
    $melapor = $rs['melapor_ke'];
    $ringkasan = $rs['ringkasan'];
    $kualifikasi = $rs['kualifikasi'];
    $tanggungjawab = $rs['tanggung_jawab'];
    $kondisi = $rs['kondisi_pekerjaan'];
?>
<table width='100%' align="center" cellpadding="0" cellspacing="0">
    <tr>
        <td class='title' align='center' colspan='2'>PT. AGUNG KEMUNINGWIJAYA<br>
        Job Description</td>
    </tr>
    <tr>
        <td class='detail' width="20%">
            <div id="container" >
                <div id="left">NAMA JABATAN</div>
                <div id="right" align='right'>:</div>
            </div>
        </td>
        <td class='detail2' width="80%"><?=$jabatan?></td>
    </tr>
    <tr>
        <td class='detail' width="20%">
            <div id="container" >
                <div id="left">DEPARTEMEN</div>
                <div id="right" align='right'>:</div>
            </div>
        </td>
        <td class='detail2' width="80%"><?=$departemen?></td>
    </tr>
    <tr>
        <td class='detail' width="20%">
            <div id="container" >
                <div id="left">LOKASI KERJA</div>
                <div id="right" align='right'>:</div>
            </div>
        </td>
        <td class='detail2' width="80%"><?=nl2br($lokasi)?></td>
    </tr>
    <tr>
        <td class='detail' width="20%">
            <div id="container" >
                <div id="left">MELAPOR KE</div>
                <div id="right" align='right'>:</div>
            </div>
        </td>
        <td class='detail2' width="80%"><?=$melapor?></td>
    </tr>
    <tr>
        <td colspan='2' class='detail'>RINGKASAN JABATAN</td>
    </tr>
    <tr>
        <td colspan='2' class='detail2'><?=$ringkasan?></td>
    </tr>
    <tr>
        <td colspan='2' class='detail'>KUALIFIKASI</td>
    </tr>
    <tr>
        <td colspan='2' class='detail3'><?=$kualifikasi?></td>
    </tr>
    <tr>
        <td colspan='2' class='detail'>TANGGUNG JAWAB</td>
    </tr>
    <tr>
        <td colspan='2' class='detail3'><?=$tanggungjawab?></td>
    </tr>
    <tr>
        <td colspan='2' class='detail'>KONDISI PEKERJAAN</td>
    </tr>
    <tr>
        <td colspan='2' class='detail2'><?=nl2br($kondisi)?></td>
    </tr>
</table>
<div class='detail'>Detail mengenai Key Performance Indicator (KPI) dan kompensasi lengkap akan dilampirkan pada dokumen Job Offering</div>
<div class="divFooter">JOB DESCRIPTION  - <?=$jabatan?></div>
<script>
    window.print();
</script>