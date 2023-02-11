<?php
error_reporting(0);
session_start();
$user=$_SESSION['user']['username'];
include("../../include/koneksi.php");

$id = $_GET['id'];
$row = $_GET['jum'];
$action = $_GET['action'];

if($action == 'list'){
    $row = $_GET['row'];
    $id_penggajian = $_GET['penggajian'];
    $id_karyawan =  $_POST['idKaryawan'];

    $totalpendapatan = 0;
    $totalpendapatanvar = 0;
    $totalpotongan = 0;
    $totalpotonganvar = 0;
    
    for ($i=1; $i<=$row; $i++)
	{
        if(!isset($_POST['Id'.$i])){
		}
		else
		{
            if($_POST['Id'.$i] !== ''){
                $id_detail = $_POST['Id'.$i];

                $subtotal = $_POST['Subtotal'.$i];
                // $keterangan = $_POST['Keterangan'.$i];

                $querydet = "UPDATE `hrd_karyawandet` SET subtotal='$subtotal' WHERE id_det_karyawan='$id_detail' ";
                $hasildet = mysql_query($querydet) or die ("error".mysql_error());

                if($_POST['Tipe'.$i] == 'Pendapatan'){
                    if($_POST['Keterangan'.$i] == 'Manual Input'){
                        $totalpendapatanvar += $subtotal;
                    }else{
                        $totalpendapatan += $subtotal;
                    }
                }else{
                    if($_POST['Keterangan'.$i] == 'Manual Input'){
                        $totalpotonganvar += $subtotal;
                    }else{
                        $totalpotongan += $subtotal;
                    }
                }
            }
        }
    }

    $total = $totalpendapatan - $totalpotongan;

    $query = "UPDATE `hrd_karyawan` SET total_pendapatan='$totalpendapatan',total_potongan='$totalpotongan',total='$total'  WHERE id_karyawan='$id_karyawan' ";
    $hasil = mysql_query($query) or die ("error".mysql_error());

    $query = "UPDATE `hrd_penggajiandet` SET subtotal=(SELECT SUM(subtotal) FROM hrd_karyawandet a 
    LEFT JOIN `hrd_pendapatan_potongan` b ON b.`id_penpot`=a.`id_penpot`
    WHERE a.id_karyawan=$id_karyawan AND b.type='pendapatan'), subtotal_variabel='$totalpendapatanvar' WHERE id_karyawan='$id_karyawan' AND id_penggajian='$id_penggajian'  AND `status` ='pendapatan' ";
    $hasil = mysql_query($query) or die ("error".mysql_error());

    $query = "UPDATE `hrd_penggajiandet` SET subtotal=(SELECT SUM(subtotal) FROM hrd_karyawandet a 
        LEFT JOIN `hrd_pendapatan_potongan` b ON b.`id_penpot`=a.`id_penpot`
        WHERE a.id_karyawan=$id_karyawan AND b.type='potongan'), subtotal_variabel='$totalpotonganvar' WHERE id_karyawan='$id_karyawan' AND id_penggajian='$id_penggajian' AND `status` ='potongan' ";
    $hasil = mysql_query($query) or die ("error".mysql_error());
}else if($action == 'wa'){
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
    $sql_title2 = "SELECT date_format(last_day(tgl_upah_end),'%d %M %Y') as lastday,date_format(tgl_upah_end,'%M %Y') as monthnow  FROM hrd_penggajian a WHERE penggajian_id = '$id' ";
    $data_title2=mysql_query($sql_title2);
    $rs_title2 = mysql_fetch_array($data_title2); 
    
    $Month = str_replace(" ","%20",STRTOUPPER($rs_title2['monthnow']));
    $lastDayThisMonth = str_replace(" ","%20",STRTOUPPER($rs_title2['lastday']));

    $totalpendapatan=0;
    $totalpotongan=0;

    $wa = "https://api.whatsapp.com/send/?phone=".$wa."&text=PT%20AGUNG%20KEMUNINGWIJAYA%20-%20Slip%20Gaji%20".$Month."%0a%0aNama%20%20%20%20%20%20%20%20%20:%20".$namakaryawan."%0aKode%20Divisi%20:%20".$dept."%0a%0aPendapatan%20:%0a";

    $i = 1;
	$sql_detail1 = "SELECT e.nama_penpot as pendapatan, IFNULL(d.subtotal,0) as totalpendapatan FROM hrd_karyawan a LEFT JOIN hrd_karyawandet d ON d.id_karyawan=a.id_karyawan RIGHT JOIN hrd_pendapatan_potongan e ON e.id_penpot=d.id_penpot LEFT JOIN hrd_jabatan b ON b.id_jabatan=a.id_jabatan LEFT JOIN hrd_departemen c ON c.id_dept=b.id_dept WHERE a.deleted=0 AND a.id_karyawan=$id_karyawan AND e.type='pendapatan'";
	$sq1 = mysql_query($sql_detail1);
	while($rs1=mysql_fetch_array($sq1))
	{
        if($rs1['totalpendapatan'] != '0'){
            $wa .= $i.".%20%20".str_replace(" ","%20",$rs1['pendapatan'])."%20%20%20%20%20".number_format($rs1['totalpendapatan'],0).'%0a';
            $i++;
            $totalpendapatan += $rs1['totalpendapatan'];
        }
    }

    $wa .= "%0aPotongan%20:%0a";

    $i = 1;
	$sql_detail1 = "SELECT e.nama_penpot as potongan, IFNULL(d.subtotal,0) as totalpotongan FROM hrd_karyawan a LEFT JOIN hrd_karyawandet d ON d.id_karyawan=a.id_karyawan RIGHT JOIN hrd_pendapatan_potongan e ON e.id_penpot=d.id_penpot LEFT JOIN hrd_jabatan b ON b.id_jabatan=a.id_jabatan LEFT JOIN hrd_departemen c ON c.id_dept=b.id_dept WHERE a.deleted=0 AND a.id_karyawan=$id_karyawan AND e.type='potongan'";
	$sq1 = mysql_query($sql_detail1);
	while($rs1=mysql_fetch_array($sq1))
	{
        if($rs1['totalpotongan'] != '0'){
            $wa .= $i.".%20%20".str_replace(" ","%20",$rs1['potongan'])."%20%20%20%20%20".number_format($rs1['totalpotongan'],0).'%0a';
            $i++;
            $totalpotongan += $rs1['totalpotongan'];
        }
    }

    $wa .= "%0aTotal%20Pendapatan%20:%20".number_format($totalpendapatan,0)."%0aTotal%20Potongan%20%20%20%20:%20".number_format($totalpotongan,0)."%0aGaji%20Bersih%20%20%20%20%20%20%20%20%20%20%20:%20".number_format(($totalpendapatan-$totalpotongan),0);

    echo $wa;
}else{
    for ($i=1; $i<=$row; $i++)
	{
        $delete = $_POST['delete1'.$i];
		$id_detail = $_POST['Id'.$i];
        $id_karyawan = $_POST['IdKaryawan'.$i];
        $subtotal = $_POST['Total'.$i];
        $totalpendapatan = $_POST['TotalPendapatan'.$i];
        $totalpotongan = $_POST['TotalPotongan'.$i];

        if($id_karyawan=='' && $id_detail=='' && $delete==''){
        }
        else
        {

            if($delete=='' && $id_detail==''){
                //insert
                $sql_insert="INSERT INTO `hrd_penggajiandet` (`id_penggajian`,`id_karyawan`,`subtotal`,`status`) VALUES('".$id."','".$id_karyawan."','".$totalpendapatan."','pendapatan')" ;
                mysql_query($sql_insert) ;

                $sql_insert="INSERT INTO `hrd_penggajiandet` (`id_penggajian`,`id_karyawan`,`subtotal`,`status`) VALUES('".$id."','".$id_karyawan."','".$totalpotongan."','potongan')" ;
                mysql_query($sql_insert) ;

            }else if($delete=='' && $id_detail!=''){
                //update
                $sql_update="UPDATE `hrd_penggajiandet` SET `subtotal`='$totalpendapatan' WHERE id_penggajian = '$id' AND id_karyawan = '$id_karyawan' AND `status` = 'pendapatan' ";
                mysql_query($sql_update);

                $sql_update="UPDATE `hrd_penggajiandet` SET `subtotal`='$totalpotongan' WHERE id_penggajian = '$id' AND id_karyawan = '$id_karyawan' AND `status` = 'potongan' ";
                mysql_query($sql_update);
            }else if($delete!='' && $id_detail==''){
                //delete
                $sql_delete="delete from hrd_penggajiandet WHERE id_penggajian = '$id' AND id_karyawan = '$delete' ";
                mysql_query($sql_delete);
            }
        }
    }

    $totalpe = $_POST['totalpendapatanhidden'];
    $totalpo = $_POST['totalpotonganhidden'];

    $query = "UPDATE `hrd_penggajian` SET total_pendapatan='$totalpe',total_potongan='$totalpo',total_pendapatan_variabel=(select sum(subtotal_variabel) from hrd_penggajiandet where id_penggajian=$id and status='pendapatan'),total_potongan_variabel=(select sum(subtotal_variabel) from hrd_penggajiandet where id_penggajian=$id and status='potongan') WHERE penggajian_id='$id' ";
    $hasil = mysql_query($query) or die ("error".mysql_error());
}
?>
<script>
    window.close();
</script>