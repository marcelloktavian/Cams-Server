<?php

require_once '../../include/config.php';
include "../../include/koneksi.php";

if(isset($_GET['action']) && strtolower($_GET['action'])=='json'){
  $page   = $_GET['page'];
  $limit  = $_GET['rows'];
  $sidx   = $_GET['sidx'];
  $sord   = $_GET['sord'];

  $page = isset($_GET['page'])?$_GET['page']:1; // get the requested page
  $limit = isset($_GET['rows'])?$_GET['rows']:20; // get how many rows we want to have into the grid
  $sidx = isset($_GET['sidx'])?$_GET['sidx']:'id'; // get index row - i.e. user click to sort
  $sord = isset($_GET['sord'])?$_GET['sord']:'';

  if(!$sidx) $sidx = 1;

  $sql_yec = "SELECT a.*, DAY(a.lastmodified) AS hari, MONTHNAME(a.lastmodified) AS bulan, YEAR(a.lastmodified) AS tahun, TIME(a.lastmodified) AS waktu, b.nama AS nama_pic FROM `tbl_logyec` a LEFT JOIN `user` b ON a.pic=b.user_id";
  $q = $db->query($sql_yec);

  $count = $q->rowCount();
  $count > 0 ? $total_pages = ceil($count/$limit) : $total_pages = 0;
  if ($page > $total_pages) $page=$total_pages;
  $start = $limit*$page - $limit;
  if($start <0) $start = 0;

  $q = $db->query($sql_yec." 
    ORDER BY `".$sidx."` ".$sord." 
    LIMIT ".$start.", ".$limit
  );

  $data1 = $q->fetchAll(PDO::FETCH_ASSOC);

  $responce['page'] = $page;
  $responce['total'] = $total_pages;
  $responce['records'] = $count;

  // $responce = array();
  $i=0;

  foreach($data1 as $line){
    $detail = '<a onclick="javascript:window.open(\''.BASE_URL.'pages/Transaksi_acc/tutupbuku_excel.php?id='.$line['id'].'\')" href="javascript:void(0)">Detail</a>';

    $responce['rows'][$i]['id']   = $line['id'];
    $responce['rows'][$i]['cell'] = array(
      date('F', mktime(0, 0, 0, $line['month'], 1)).' '.$line['year'],
      $line['nama_pic'],
      $line['hari'].' '.$line['bulan'].' '.$line['tahun'].' '.$line['waktu'],
      $detail,
    );
    $i++;
  }
  if(!isset($responce)){
    $responce = [];
  }
  echo json_encode($responce);
  exit;
}
elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'process_close') {
  $stmt = $db->prepare("SELECT * FROM `user` WHERE deleted=0 AND `password`=MD5('".$_POST['pass_yec']."') AND (user_id=17 OR user_id=3 OR user_id=13 OR user_id=10)");
  $stmt->execute();

  $affected_rows = $stmt->rowCount();
  if($affected_rows > 0){
    $stmt = $db->prepare("SELECT * FROM `tbl_logyec` WHERE closed = 1");
    $stmt->execute();
    $monthbaru = explode('/',$_POST['date_yec'])[0].'/'.explode('/',$_POST['date_yec'])[1];
    $affected_rows = $stmt->rowCount();
    if($affected_rows == 0){
      $q_to = date('Y-m-t', strtotime(explode('/',$_POST['date_yec'])[1].'-'.explode('/',$_POST['date_yec'])[0]));

      $q_move = mysql_query("INSERT INTO `jurnal_archive` (`id_ori`,`no_jurnal`,`tgl`,`keterangan`,`total_debet`,`total_kredit`,`deleted`,`user`,`lastmodified`,`status`,`id_logyec`) SELECT `id`,`no_jurnal`,`tgl`,`keterangan`,`total_debet`,`total_kredit`,`deleted`,`user`,`lastmodified`,`status`,'$monthbaru' FROM `jurnal` where date(tgl) <= date('$q_to')");

      $gt_min_id = mysql_query("SELECT MIN(`id_ori`) as start_id FROM `jurnal_archive`");
      $gt_min_id = mysql_fetch_array($gt_min_id);
      $min_id = $gt_min_id['start_id'];

      $gt_max_id = mysql_query("SELECT MAX(`id_ori`) as end_id FROM `jurnal_archive`");
      $gt_max_id = mysql_fetch_array($gt_max_id);
      $max_id = $gt_max_id['end_id'];

      $q_detmove = mysql_query("INSERT INTO `jurnal_detail_archive` (`id_parent`,`id_akun`,`no_akun`,`nama_akun`,`status`,`debet`,`kredit`,`deleted`,`user`,`lastmodified`,`id_logyec`) SELECT `id_parent`,`id_akun`,`no_akun`,`nama_akun`,`status`,`debet`,`kredit`,`deleted`,`user`,`lastmodified`,'$monthbaru' FROM `jurnal_detail` WHERE jurnal_detail.id_parent BETWEEN $min_id AND $max_id");
    }
    else{
      $q_from = mysql_query("SELECT CONCAT(CASE WHEN `month` = 12 THEN CAST(`year` AS UNSIGNED) + 1 ELSE CAST(`year` AS UNSIGNED) END, '-', CASE WHEN `month` = 12 THEN '01' ELSE LPAD(`month` + 1, 2, '0') END, '-01') AS `start_date` FROM `tbl_logyec` WHERE closed = 1");
      $q_form = mysql_fetch_array($q_from);
      $q_from = $q_form['start_date'];

      $q_to = date('Y-m-t', strtotime(explode('/',$_POST['date_yec'])[1].'-'.explode('/',$_POST['date_yec'])[0]));
      
      $q_move = mysql_query("INSERT INTO `jurnal_archive` (`id_ori`,`no_jurnal`,`tgl`,`keterangan`,`total_debet`,`total_kredit`,`deleted`,`user`,`lastmodified`,`status`,`id_logyec`) SELECT `id`,`no_jurnal`,`tgl`,`keterangan`,`total_debet`,`total_kredit`,`deleted`,`user`,`lastmodified`,`status`,'$monthbaru' FROM `jurnal` WHERE jurnal.tgl BETWEEN '$q_from' AND '$q_to'");

      $gt_min_id = mysql_query("SELECT MIN(`id_ori`) as start_id FROM `jurnal_archive`");
      $gt_min_id = mysql_fetch_array($gt_min_id);
      $min_id = $gt_min_id['start_id'];

      $gt_max_id = mysql_query("SELECT MAX(`id_ori`) as end_id FROM `jurnal_archive`");
      $gt_max_id = mysql_fetch_array($gt_max_id);
      $max_id = $gt_max_id['end_id'];

      $q_detmove = mysql_query("INSERT INTO `jurnal_detail_archive` (`id_parent`,`id_akun`,`no_akun`,`nama_akun`,`status`,`debet`,`kredit`,`deleted`,`user`,`lastmodified`,`id_logyec`) SELECT `id_parent`,`id_akun`,`no_akun`,`nama_akun`,`status`,`debet`,`kredit`,`deleted`,`user`,`lastmodified`,'$monthbaru' FROM `jurnal_detail` WHERE jurnal_detail.id_parent BETWEEN $min_id AND $max_id");
    }

    $user = $_SESSION['user']['user_id'];
    $month = date('n', strtotime(explode('/',$_POST['date_yec'])[1].'/'.explode('/',$_POST['date_yec'])[0].'/01'));
    $year = date('Y', strtotime(explode('/',$_POST['date_yec'])[1].'/'.explode('/',$_POST['date_yec'])[0].'/01'));

    $q_reset  = $db->prepare("UPDATE `tbl_logyec` SET `closed`=0");
    $q_reset->execute();

    $q_post   = $db->prepare("INSERT `tbl_logyec` (`pic`,`month`,`year`,`lastmodified`,`closed`) VALUES (?, ?, ?, ?, ?)");
    $q_post->execute(array($user, $month, $year, date('Y-m-d H:i:s'), 1));  

    $affected_rows = $q_post->rowCount();

    if($affected_rows > 0){
      $r['stat'] = 1; $r['message'] = 'Succes';
    }
    else{
      $r['stat'] = 0; $r['message'] = 'Failed';
    }
  }
  else{
    $r['stat'] = 0; $r['message'] = 'Failed';
  }
  echo json_encode($r);
  exit;
}
elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'json_excel') {
  $id = $_GET['id'];

  $data_master = 'SELECT a.*, DAY(a.lastmodified) AS hari, MONTHNAME(a.lastmodified) AS bulan, YEAR(a.lastmodified) AS tahun, TIME(a.lastmodified) AS waktu, b.nama AS nama_pic FROM `tbl_logyec` a LEFT JOIN `user` b ON a.pic=b.user_id AND a.id='.$id.'';
  $q = $db->query($data_master );

  $data1 = $q->fetchAll(PDO::FETCH_ASSOC);

  $i = 0;
  foreach($data1 as $line){
    $responce['rows'][$i]['id']   = $line['id'];
    $responce['rows'][$i]['cell'] = array(
      date('F', mktime(0, 0, 0, $line['month'], 1)).' '.$line['year'],
      $line['nama_pic'],
      $line['hari'].' '.$line['bulan'].' '.$line['tahun'].' '.$line['waktu'],
    );
    $i++;
  }
  if(!isset($responce)){
    $responce = [];
  }
  echo json_encode($responce);
  exit;
}
elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'json_sub') {
  error_reporting(0);
  $id = $_GET["id"];

  $sql_products ="SELECT a.* FROM `mst_coa` a ";
  $query = '';
  $countnya = 0;
  $q = $db->query($sql_products.' where a.deleted=0 ORDER BY noakun ASC');
  $data1 = $q->fetchAll(PDO::FETCH_ASSOC);
  foreach($data1 as $line) {
    if ($countnya == 0) {
      $query .= "(select id, noakun, nama, jenis from mst_coa where id='".$line['id']."'  ORDER BY noakun ASC)";
    } else {
      $query .= " UNION ALL (select id, noakun, nama, jenis from mst_coa  where id='".$line['id']."'  ORDER BY noakun ASC) ";
    }
    $countnya++;
    $q2 = $db->query("SELECT * FROM det_coa WHERE id_parent='".$line['id']."' ORDER by noakun ASC");
    $data2 = $q2->fetchAll(PDO::FETCH_ASSOC);
    foreach($data2 as $line2) {
      $query .= " UNION ALL (select '' as id, noakun, nama, '' as jenis from det_coa where id='".$line2['id']."' ORDER BY noakun ASC) ";
    }
  }
  $i = 0;
  $p = $db->query($query);
  $rows = $p->fetchAll(PDO::FETCH_ASSOC);
  $responce = '';
  foreach($rows as $lines){
    $month = '';
    $qmonth = "SELECT *, IF(length(month)=1,concat('0',month),month) as bulannya FROM tbl_logyec WHERE id=".$id;
    $pmonth = $db->query($qmonth);
    $rowsmonth = $pmonth->fetchAll(PDO::FETCH_ASSOC);
    foreach($rowsmonth as $r){
      $month = $r['bulannya'].'/'.$r['year'];
    }

    $qsaldo = "SELECT SUM(debet) AS db, SUM(kredit) AS cr FROM jurnal_detail_archive WHERE no_akun='".$lines['noakun']."' AND id_logyec='$month' ";
    $debet = 0;
    $kredit = 0;
    $psaldo = $db->query($qsaldo);
    $rowssalso = $psaldo->fetchAll(PDO::FETCH_ASSOC);
    foreach($rowssalso as $rs){
      $debet = $rs['db'];
      $kredit = $rs['cr'];
    }
    
    $responce->rows[$i]['id']   = $lines['id'];
    $responce->rows[$i]['cell'] = array(
      $i+1,
      $lines['noakun'],
      $lines['nama'],
      number_format($debet),
      number_format($kredit),
    );
    $i++;
  }
  echo json_encode($responce);
  exit;
}
?>