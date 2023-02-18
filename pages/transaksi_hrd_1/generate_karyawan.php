<?php
include"../../include/koneksi.php";

$id = $_GET['id'];
$status = $_GET['status'];

if($id == '0'){
    $sql_note = "SELECT a.id_karyawan, a.nama_karyawan, c.nama_dept, IFNULL(a.total_$status,0) as total FROM hrd_karyawan a LEFT JOIN hrd_karyawandet d ON d.id_karyawan=a.id_karyawan LEFT JOIN hrd_pendapatan_potongan e ON e.id_penpot=d.id_penpot AND e.type='$status' LEFT JOIN hrd_jabatan b ON b.id_jabatan=a.id_jabatan LEFT JOIN hrd_departemen c ON c.id_dept=b.id_dept WHERE a.deleted=0 GROUP BY a.id_karyawan";
}else{
    $sql_note = "SELECT a.id_karyawan, a.nama_karyawan, c.nama_dept, IFNULL(a.total_$status,0) as total FROM hrd_karyawan a LEFT JOIN hrd_karyawandet d ON d.id_karyawan=a.id_karyawan LEFT JOIN hrd_pendapatan_potongan e ON e.id_penpot=d.id_penpot AND e.type='$status' LEFT JOIN hrd_jabatan b ON b.id_jabatan=a.id_jabatan LEFT JOIN hrd_departemen c ON c.id_dept=b.id_dept WHERE a.periode='$id' AND a.deleted=0 GROUP BY a.id_karyawan"; 
}

// var_dump($sql_note);die; 
$sql = mysql_query($sql_note);
$results = array();
while($row = mysql_fetch_array($sql))
{
   $results[] = array(
      'id_karyawan' => $row['id_karyawan'],
      'nama_karyawan' => $row['nama_karyawan'],
      'nama_dept' => $row['nama_dept'],
      'total' => $row['total'],
   );
}
echo json_encode($results);


?>