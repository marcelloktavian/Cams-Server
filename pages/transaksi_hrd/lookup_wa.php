<?php
    error_reporting(0);
    session_start();
    $user=$_SESSION['user']['username'];
    include("../../include/koneksi.php");

    $id = $_GET['id'];
    $id_penggajian = $_GET['penggajian'];
    $id_karyawan =  $_GET['idKaryawan'];
    $wa =  $_GET['wa'];
    
    $query = "UPDATE `hrd_penggajiandet` SET wa='Y' WHERE id_karyawan='$id_karyawan' AND id_penggajian='$id_penggajian' ";
    $hasil = mysql_query($query) or die ("error".mysql_error());

    //
    $sql_title = "SELECT a.id_karyawan, a.nama_karyawan, c.nama_dept FROM hrd_karyawan a LEFT JOIN hrd_jabatan b ON b.id_jabatan=a.id_jabatan LEFT JOIN hrd_departemen c ON c.id_dept=b.id_dept WHERE a.deleted=0 AND a.id_karyawan = '$id_karyawan' ";
    $data_title=mysql_query($sql_title);
    $rs_title = mysql_fetch_array($data_title); 

    $namakaryawan = $rs_title['nama_karyawan'];
    $dept = $rs_title['nama_dept'];

    //
    $sql_title2 = "SELECT nama_periode,date_format(tgl_upah_start,'%d %b %Y') as awal,date_format(tgl_upah_end,'%d %b %Y') as akhir,date_format(last_day(tgl_upah_end),'%d %M %Y') as lastday,date_format(last_day(tgl_upah_end),'%d %M %Y') as lastday,date_format(tgl_upah_end,'%M %Y') as monthnow,jml_periode   FROM hrd_penggajian a WHERE penggajian_id = '$id_penggajian' ";

    $data_title2=mysql_query($sql_title2);
    $rs_title2 = mysql_fetch_array($data_title2); 
    
    $namaperiode = str_replace(" ","%20",STRTOUPPER($rs_title2['nama_periode']));
    $Month = str_replace(" ","%20",STRTOUPPER($rs_title2['monthnow']));
    $Monthawal = str_replace(" ","%20",STRTOUPPER($rs_title2['awal']));
    $Monthakhir = str_replace(" ","%20",STRTOUPPER($rs_title2['akhir']));
    $periode = $rs_title2['jml_periode'];

    //
    $sql_title3 = "SELECT IFNULL(jml_kehadiran,0) as kehadiran FROM hrd_penggajiandet WHERE id_penggajian=$id_penggajian AND id_karyawan=$id_karyawan AND `status`='pendapatan' ";

    $data_title3=mysql_query($sql_title3);
    $rs_title3 = mysql_fetch_array($data_title3); 
    
    $kehadiran = 0;
    $kehadiran = $rs_title3['kehadiran'];

    //
    $totalpendapatan=0;
    $totalpotongan=0;

    $tglpembayaran = '';
    $kehadiran = '';
    $sql_mst = "SELECT DATE_FORMAT(tgl_pembayaran,'%d %b %Y') AS tglpembayaran, jml_kehadiran  FROM hrd_penggajian a LEFT JOIN hrd_penggajiandet b ON b.id_penggajian=a.penggajian_id WHERE a.penggajian_id='$id_penggajian' AND id_karyawan='$id_karyawan' LIMIT 1";
    $sqmst = mysql_query($sql_mst);
    while($rsmst=mysql_fetch_array($sqmst))
    {
        $tglpembayaran = strtoupper($rsmst['tglpembayaran']);
        $kehadiran = strtoupper($rsmst['jml_kehadiran']);
    }

    $wa = "https://api.whatsapp.com/send/?phone=".$wa."&text=*PT%20AGUNG%20KEMUNINGWIJAYA*%0a*Slip%20Gaji%20".$namaperiode."*"."%0a%0a*DATA%20KARYAWAN*%0aNama%20%20:%20".$namakaryawan."%0aDivisi%20%20%20:%20".$dept."%0aKehadiran%20:%20".$kehadiran."%20dari%20".$periode."%20hari%20kerja%0a%0a*PENDAPATAN*%0a";

    $i = 1;
    $sql_detail1 = "SELECT * FROM hrd_penggajiandet a 
    LEFT JOIN hrd_pendapatan_potongan b ON b.id_penpot=a.id_penpot
    WHERE `status` = 'pendapatan' AND a.id_penggajian='$id_penggajian' AND a.id_karyawan='$id_karyawan' AND b.total_pendapatan=1 ";
    $sq1 = mysql_query($sql_detail1);
    while($rs1=mysql_fetch_array($sq1))
    {
        $totala = 0;
        if($rs1['metode_pethitungan']=='Manual Input'){
            $totala = $rs1['subtotal_variabel'];
        }else{
            $totala = $rs1['subtotal'];
        }
        if($totala != '0'){
            $wa .= $i.".%20%20".str_replace(" ","%20",$rs1['nama_penpot'])."%20:%20".number_format($totala,0,',','.').'%0a';
            $i++;
            $totalpendapatan += $totala;
        }
    }
    $wa .= "-----------------------------------%0a";
    $wa .= "*Total%20Pendapatan%20(A):%20".number_format($totalpendapatan,0,',','.')."*%0a";

    $wa .= "%0a*POTONGAN*%0a";

    $i = 1;
    $sql_detail1 = "SELECT * FROM hrd_penggajiandet a 
    LEFT JOIN hrd_pendapatan_potongan b ON b.id_penpot=a.id_penpot
    WHERE `status` = 'potongan' AND a.id_penggajian='$id_penggajian' AND a.id_karyawan='$id_karyawan' AND b.total_pendapatan=1";
    $sq1 = mysql_query($sql_detail1);
    while($rs1=mysql_fetch_array($sq1))
    {
        $totalb = 0;
        if($rs1['metode_pethitungan']=='Manual Input'){
            $totalb = $rs1['subtotal_variabel'];
        }else{
            $totalb = $rs1['subtotal'];
        }
        if($totalb != '0'){
            $wa .= $i.".%20%20".str_replace(" ","%20",$rs1['nama_penpot'])."%20:%20".number_format($totalb,0,',','.').'%0a';
            $i++;
            $totalpotongan += $totalb;
        }
    }
    
    $wa .= "-----------------------------------%0a";
    $wa .= "*Total%20Potongan%20(B)%20%20%20%20:%20".number_format($totalpotongan,0,',','.')."*%0a%0a";

    $wa .= "*GAJI%20BERSIH%20(A-B)%20%20%20%20:%20".number_format($totalpendapatan-$totalpotongan,0,',','.')."*%0a";
    $wa .= "_Pembayaran%20dilakukan%20via%20Transfer%20ke%20rekening%20Bank%20Karyawan%20yang%20terdaftar%20pada%20".$tglpembayaran."._";

    // $wa .= "%0aTotal%20Pendapatan%20:%20".number_format($totalpendapatan,0)."%0aTotal%20Potongan%20%20%20%20:%20".number_format($totalpotongan,0)."%0aGaji%20Bersih%20%20%20%20%20%20%20%20%20%20%20:%20".number_format(($totalpendapatan-$totalpotongan),0);

    echo $wa;
?>