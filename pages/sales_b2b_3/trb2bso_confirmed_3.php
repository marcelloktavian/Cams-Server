<?php require_once '../../include/config.php' ?>
<?php
$group_acess = unserialize(file_get_contents("../../GROUP_ACCESS_CACHE".$_SESSION['user']['group_id']));
$allow_post = is_show_menu(POST_POLICY, ConfirmedSales, $group_acess);
$allow_delete = is_show_menu(DELETE_POLICY, ConfirmedSales, $group_acess);

if(isset($_GET['action']) && strtolower($_GET['action']) == 'json') {
  $page  = $_GET['page'];
  $limit = $_GET['rows'];
  $sidx  = $_GET['sidx'];
  $sord  = $_GET['sord'];

  if(!$sidx) $sidx=1;
  if ($_REQUEST["_search"] == "false") {
       //all transaction kecuali yang batal
    $where = "WHERE TRUE AND p.state='1' AND (p.deleted=0) AND (p.totalkirim-p.totalqty <> 0)";
} else {
 $operations = array(
        'eq' => "= '%s'",            // Equal
        'ne' => "<> '%s'",           // Not equal
        'lt' => "< '%s'",            // Less than
        'le' => "<= '%s'",           // Less than or equal
        'gt' => "> '%s'",            // Greater than
        'ge' => ">= '%s'",           // Greater or equal
        'bw' => "like '%s%%'",       // Begins With
        'bn' => "not like '%s%%'",   // Does not begin with
        'in' => "in ('%s')",         // In
        'ni' => "not in ('%s')",     // Not in
        'ew' => "like '%%%s'",       // Ends with
        'en' => "not like '%%%s'",   // Does not end with
        'cn' => "like '%%%s%%'",     // Contains
        'nc' => "not like '%%%s%%'", // Does not contain
        'nu' => "is null",           // Is null
        'nn' => "is not null"        // Is not null
    ); 

 $value = $_REQUEST["searchString"];
 $where = sprintf(" where TRUE AND (p.state='1') AND (p.deleted = 0) AND (p.totalkirim-p.totalqty <> 0) AND %s ".$operations[$_REQUEST["searchOper"]], $_REQUEST["searchField"], $value);
     //echo"<script>alert('where=$where')</script>";
}
$sql_unpaid = "SELECT p.*,k.nama as kategori,j.nama as customer,s.nama as salesman,e.nama as expedition FROM `b2bso` p Left Join `mst_b2bcategory_sale` k on (p.id_kategori=k.id) Left Join `mst_b2bsalesman` s on (p.id_salesman=s.id) Left Join `mst_b2bcustomer` j on (p.id_customer=j.id) Left Join `mst_b2bexpedition` e on (p.id_expedition=e.id) ".$where;

        // var_dump($sql_unpaid);die;
$q = $db->query($sql_unpaid);
$count = $q->rowCount();

$count > 0 ? $total_pages = ceil($count/$limit) : $total_pages = 0;
if ($page > $total_pages) $page=$total_pages;
$start = $limit*$page - $limit;
if($start <0) $start = 0;

$q = $db->query($sql_unpaid." ORDER BY `".$sidx."` ".$sord."
  LIMIT ".$start.", ".$limit);
$data1 = $q->fetchAll(PDO::FETCH_ASSOC);

$statusToko = '';
$getStat = $db->prepare("SELECT * FROM tbl_status LIMIT 1");
$getStat->execute();
$stat = $getStat->fetchAll();
foreach ($stat as $stats) {
            // $id = $stats['id'];
    $statusToko = $stats['status'];
}

$responce['page'] = $page;
$responce['total'] = $total_pages;
$responce['records'] = $count;
$i=0;
foreach($data1 as $line) {

 // $allowEdit = array(1,2,3);
 // $allowDelete = array(1,2,3);
    if ($statusToko == 'Tutup') {
       $detail = '<a onclick="javascript:custom_alert(\'Maaf, Toko Sudah Tutup\')" href="javascript:;">Detail</a>';
       $send = '<a onclick="javascript:custom_alert(\'Maaf, Toko Sudah Tutup\')" href="javascript:;">Send</a>';
       $delete = '<a onclick="javascript:custom_alert(\'Maaf, Toko Sudah Tutup\')" href="javascript:;">Cancel</a>';
   } else {
     if($allow_post)
     {
        $detail = '<a onclick="window.open(\''.BASE_URL.'pages/sales_b2b/trb2bso_confirmed_detail.php?ids='.$line['id_trans'].'\',\'table_b2bso_confirmed\')" href="javascript:;">Detail</a>';
        $send = '<a onclick="window.open(\''.BASE_URL.'pages/sales_b2b/trb2bso_confirmed_send.php?ids='.$line['id_trans'].'\',\'table_b2bso_confirmed\')" href="javascript:;">Send</a>';
    } 
    else
    {
        $detail = '<a onclick="javascript:custom_alert(\'Tidak Bisa UNPosting\')" href="javascript:;">POSTED</a>';
        $send = '<a onclick="javascript:custom_alert(\'Tidak Bisa Dibatalkan \')" href="javascript:;">Send</a>';
    }

    if($allow_delete)
    {
        if($line['tgl_trans'] == date('Y-m-d')){
      //tgl sama
          $delete = '<a onclick="javascript:link_ajax(\''.BASE_URL.'pages/sales_b2b/trb2bso_confirmed.php?action=delete&qty='.$line['totalkirim'].'&id='.$line['id_trans'].'\',\'table_b2bso_confirmed\')" href="javascript:;">Cancel</a>';
      }else{
      //tgl beda
          $delete = '<a onclick="javascript:popup_form(\''.BASE_URL.'pages/sales_b2b/trb2bso_confirmed.php?action=pass&qty='.$line['totalkirim'].'&id='.$line['id_trans'].'\',\'table_b2bso_confirmed\')" href="javascript:;">Cancel</a>';
      }
  } 
  else
  {
     $delete = '<a onclick="javascript:custom_alert(\'Tidak Bisa Cancel\')" href="javascript:;">Cancel</a>';
 }

}
            /*
            if(in_array($_SESSION['user']['access'], $allowDelete))
                $delete = '<a onclick="javascript:link_ajax(\''.BASE_URL.'pages/sales_online/troln_unservice.php?action=delete&id='.$line['id_trans'].'\',\'table_jualarchive\')" href="javascript:;">Cancel</a>';
            else
                $delete = '<a onclick="javascript:custom_alert(\'Tidak Boleh dibatalkan\')" href="javascript:;">Delete</a>';
            */
                $responce['rows'][$i]['id']   = $line['id_trans'];
                $responce['rows'][$i]['cell'] = array(
                    $line['id_trans'],
                    $line['ref_kode'],                
                    $line['customer'],                
                    $line['tgl_trans'],
                    $line['salesman'],
                    $line['alamat'],
        // number_format($line['exp_fee'],0),
        // $line['expedition'],
                    $line['kategori'],
                    number_format($line['totalqty'],0),
                    number_format($line['totalkirim'],0),
                    $detail,
                    $send,
                    $delete,
                );
                $i++;
            }
            echo json_encode($responce);
            exit;
        }
        elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'posting') {
        //update olnso agar jadi 1 krn siap kirim,tapi statenya dikasih string='1' krn tipe datanya enum
            $stmt = $db->prepare("Update olnso set state='1' WHERE id_trans=?");
            $stmt->execute(array($_GET['id']));
        //var_dump($stmt);

            $affected_rows = $stmt->rowCount();
            if($affected_rows > 0) {
               $r['stat'] = 1;
               $r['message'] = 'Success';
           }
           else {
               $r['stat'] = 0;
               $r['message'] = 'Failed';
           }
           echo json_encode($r);
           exit;
       }
       elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'pass') {
          //tgl beda
          include 'trb2bso_pass_form.php';exit();
          exit;
      }
      elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'process_pass') {
          //cek apakah pass sama atau tidak
          $stmt = $db->prepare("SELECT * FROM `user` WHERE deleted=0 AND `password`=MD5('".$_POST['pass']."') AND (user_id=1 OR user_id=3 OR user_id=6 OR user_id=10 OR user_id=13)");
          $stmt->execute();
          
          $affected_rows = $stmt->rowCount();
          if($affected_rows > 0) {
    //insert backdatecancel
    // $stmt = $db->prepare("INSERT INTO tbl_backdatecancel SELECT `id`, `id_trans`, `kode`, `tgl_trans`, `ref_kode`, `nama`, `alamat`, `telp`, `tunai`, `transfer`, `deposit`, `faktur`, `discount_faktur`, `total`, `piutang`, `simpan_deposit`, `totalqty`, `pelunasan`, `id_dropshipper`, `id_address`, `id_expedition`, `exp_code`, `exp_note`, `exp_fee`, `note`, `no_telp`, `hp`, `pic`, `tglrequest`, `address`, `tglinvoice`, `tgljatuhtempo`, `discount`, `totalreal`, `progress`, `type_payment`, `type`, `aktif`, `state`, '".$_SESSION['user']['username']."' as `user`, `stkirim`, `pending_kirim`, `deleted`, NOW() as `lastmodified`, `lastmodified` as `tgl_ship` FROM `olnso` WHERE id_trans=?;");
    // $stmt->execute(array($_POST['id']));
    // $stmt = $db->prepare("INSERT INTO tbl_backdatecanceldetail SELECT * FROM olnsodetail WHERE id_trans=?;");
    // $stmt->execute(array($_POST['id']));

    // $stmt = $db->prepare("update trpacking_detail set deleted=1 WHERE id_oln=?");
    // $stmt->execute(array($_POST['id']));
              
    // // update trolnso agar state jadi nol dan dikembalikan ke sales_order dan stkirim jadi 0 lagi
    // $stmt = $db->prepare("update olnso set state='0',stkirim=0 WHERE id_trans=?");
    // $stmt->execute(array($_POST['id']));
        if ($_POST['qty'] > 0) {
            $r['stat'] = 0;
            $r['message'] = 'Sudah ada barang terkirim';
        } else {
                $stmt = $db->prepare("Update b2bso set state='0',lastmodified=now() WHERE id_trans=?");
                $stmt->execute(array($_POST['id']));

                $r['stat'] = 1;
                $r['message'] = 'Success';
            }
        }
        else {
            $r['stat'] = 0;
            $r['message'] = 'Failed';
        }
        echo json_encode($r);
        exit;
    }
    elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'delete') {
     if ($_GET['qty'] > 0) {
        $r['stat'] = 0;
        $r['message'] = 'Sudah ada barang terkirim';
    } else {
      $affected_rows = $stmt->rowCount();
      if($affected_rows > 0) {
            $stmt = $db->prepare("Update b2bso set state='0',lastmodified=now() WHERE id_trans=?");
            $stmt->execute(array($_GET['id']));
            $r['stat'] = 1;
            $r['message'] = 'Success';
      }
      else {
          $r['stat'] = 0;
          $r['message'] = 'Failed';
      }
  }
  
        //delete olndeposit krn void invoice        
  // $stmt = $db->prepare("delete from olndeposit WHERE id_trans=?");
  // $stmt->execute(array($_GET['id']));

    //  //update trjual agar jadi nol krn void invoice
  // $stmt = $db->prepare("Update olnso set total=0,exp_fee=0,faktur=0,totalqty=0,tunai=0,transfer=0,deposit=0,piutang=0,pelunasan=0,deleted=1 WHERE id_trans=?");
  // $stmt->execute(array($_GET['id']));
    //  //var_dump($stmt);die;
    //  //update trjual_detail agar jadi nol krn void invoice
  // $stmt = $db->prepare("update olnsodetail set jumlah_beli=0,harga_satuan=0,subtotal=0 WHERE id_trans=?");
  // $stmt->execute(array($_GET['id']));
        //var_dump($stmt);die;


  echo json_encode($r);
  exit;
}
elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'json_sub') {
    
  $id = $_GET['id'];

  $where = "WHERE do.id_transb2bso = '".$id."' AND totalkirim <> 0 AND totalfaktur <> 0";
  $q = $db->query("SELECT do.*,date_format(do.tgl_trans,'%d-%m-%Y') as tanggal,e.nama as expedition FROM `b2bdo` do left join mst_b2bexpedition e on do.id_expedition=e.id ".$where. " order by id desc");
        // var_dump($q); die;
  $count = $q->rowCount();
  $data1 = $q->fetchAll(PDO::FETCH_ASSOC);

  $i=0;
  $responce = '';
  foreach($data1 as $line){
    $responce->rows[$i]['id']   = $line['id_trans'];
    $responce->rows[$i]['cell'] = array(
      $i+1,
      $line['id_trans'],
      $line['tanggal'],
      $line['expedition'],
      number_format($line['totalkirim'],0),
      number_format($line['faktur'],0),                
      number_format($line['exp_fee'],0),                
      number_format($line['totalfaktur'],0),                
  );
    $i++;
}
echo json_encode($responce);
exit;
}

?>
<table id="table_b2bso_confirmed"></table>
<div id="pager_table_b2bso_confirmed"></div>
<div class="btn_box">
  <?php
  $statusToko = '';
  $getStat = $db->prepare("SELECT * FROM tbl_status LIMIT 1");
  $getStat->execute();
  $stat = $getStat->fetchAll();
  foreach ($stat as $stats) {
    $statusToko = $stats['status'];
}

// if ($statusToko == 'Tutup') {
    // echo '<button type="button" onclick="javascript:custom_alert(\'Maaf, Toko Sudah Tutup\')" class="btn">Cetak</button>';
// }else{
   ?>
  <!-- <a href="javascript: void(0)" 
  onclick="window.open('pages/summary_online/oln_unpaidrpt.php')">
  <button class="btn btn-success">Cetak</button></a> -->
<?php //} ?>
</br>
</div>

<script type="text/javascript">
  $(document).ready(function(){

    $("#table_b2bso_confirmed").jqGrid({
      url:'<?php echo BASE_URL.'pages/sales_b2b/trb2bso_confirmed.php?action=json'; ?>',
      datatype: "json",
      colNames:['ID','Code','Customer','Date','Salesman','Address','Category','Qty','Sent','Detail','Send','Cancel'],
      colModel:[
      {name:'id_trans',index:'id_trans', width:30, search:true, stype:'text', searchoptions:{sopt:['cn']}},
      {name:'ref_kode',index:'ref_kode', width:25, search:true, stype:'text', searchoptions:{sopt:['cn']}},
      {name:'customer',index:'customer', width:40, searchoptions: {sopt:['cn']}},                
      {name:'tgl_trans',index:'tgl_trans', width:30, searchoptions: {sopt:['cn']},formatter:"date", formatoptions:{srcformat:"Y-m-d", newformat:"d/m/Y"}, align:'center'},
      {name:'salesman',index:'salesman', align:'left', width:60, searchoptions: {sopt:['cn']}},
      {name:'alamat',index:'alamat', align:'left', width:100, searchoptions: {sopt:['cn']}},
      // {name:'exp_fee',index:'exp_fee', align:'right', width:20, searchoptions: {sopt:['cn']}},
      // {name:'expedition',index:'expedition', align:'left', width:35, searchoptions: {sopt:['cn']}},
      {name:'kategori',index:'kategori', align:'left', width:35, searchoptions: {sopt:['cn']}},
      {name:'totalqty',index:'totalqty', align:'right', width:20, searchoptions: {sopt:['cn']}},
      {name:'pelunasan',index:'pelunasan', align:'right', width:20, searchoptions: {sopt:['cn']}},
      {name:'edit',index:'edit', align:'center', width:25, sortable: false, search: false},
      {name:'send',index:'send', align:'center', width:25, sortable: false, search: false},
      {name:'delete',index:'delete', align:'center', width:25, sortable: false, search: false},
              //  {name:'select',index:'select', align:'center', width:30, sortable: false, search: false},
              ],
              rowNum:20,
              rowList:[10,20,30],
              pager: '#pager_table_b2bso_confirmed',
              sortname: 'id_trans',
              autowidth: true,
              height: '400',
              viewrecords: true,
              rownumbers: true,
              sortorder: "desc",
              caption:"SALES B2B CONFIRMED",
              ondblClickRow: function(rowid) {
                alert(rowid);
            },
            subGrid : true,
            subGridUrl : '<?php echo BASE_URL.'pages/sales_b2b/trb2bso_confirmed.php?action=json_sub'; ?>',
            subGridModel: [
            { 
             name : ['No','Kode','Tanggal','Expedition','Qty Kirim','Faktur','Exp.Fee','Totalfaktur'], 
             width : [40,80,70,100,50,80,80,80],
             align : ['right','center','center','center','right','right','right','right'],

         } 
         ],


     });
    $("#table_b2bso_confirmed").jqGrid('navGrid','#pager_table_b2bso_confirmed',{edit:false,add:false,del:false});
})
</script>