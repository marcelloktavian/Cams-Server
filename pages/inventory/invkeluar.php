<?php require_once '../../include/config.php' ?>
<?php
$group_acess = unserialize(file_get_contents("../../GROUP_ACCESS_CACHE".$_SESSION['user']['group_id']));
$allow_add = is_show_menu(ADD_POLICY, MUTASIKELUAR, $group_acess);
$allow_edit = is_show_menu(EDIT_POLICY, MUTASIKELUAR, $group_acess);
$allow_delete = is_show_menu(DELETE_POLICY, MUTASIKELUAR, $group_acess);
$allow_post = is_show_menu(POST_POLICY, MUTASIKELUAR, $group_acess);

if(isset($_GET['action']) && strtolower($_GET['action']) == 'json') {
	$page  = $_GET['page'];
	$limit = $_GET['rows'];
	$sidx  = $_GET['sidx'];
	$sord  = $_GET['sord'];
	
	$startdate = isset($_GET['startdate_invkeluar'])?$_GET['startdate_invkeluar']:date('Y-m-d');
	$enddate = isset($_GET['enddate_invkeluar'])?$_GET['enddate_invkeluar']:date('Y-m-d'); 
	$filter=$_GET['filter'];
	
        $page = isset($_GET['page'])?$_GET['page']:1; // get the requested page
        $limit = isset($_GET['rows'])?$_GET['rows']:10; // get how many rows we want to have into the grid
        $sidx = isset($_GET['sidx'])?$_GET['sidx']:'tgl_trans'; // get index row - i.e. user click to sort
        $sord = isset($_GET['sord'])?$_GET['sord']:''; 
        
        //p.state = '0' artinya belum posting";
        //p.state = '1' artinya sudah posting gudang";
        $where = "WHERE TRUE AND  p.deleted=0";
		//filter _tanggalnya berdasarkan tanggal kirim lastmodified
        if(($startdate != null) && ($filter != null)) {
        	$where .= " AND DATE(p.lastmodified) BETWEEN STR_TO_DATE('$startdate','%d/%m/%Y') AND STR_TO_DATE('$enddate','%d/%m/%Y') AND ((p.catatan like '%$filter%') or (p.kode like '%$filter%') or (p.totalqty like '%$filter%'))";	
        }	
        else
        {
        	$where .=" AND DATE(p.lastmodified) BETWEEN STR_TO_DATE('$startdate','%d/%m/%Y') AND STR_TO_DATE('$enddate','%d/%m/%Y')";
        }
		//((p.ref_code like '%$filter%') or (j.nama like '%$filter%') or (p.nama like '%$filter%') or (e.nama like '%$filter%') or (p.exp_code like '%$filter%'))
        $sql = "SELECT p.*,i.nama as sumber FROM `invkeluar` p LEFT JOIN mst_inventory i on p.id_inventory=i.id ".$where;
        //var_dump($sql);die;
        $q = $db->query($sql);
        $count = $q->rowCount();
        
        $count > 0 ? $total_pages = ceil($count/$limit) : $total_pages = 0;
        if ($page > $total_pages) $page=$total_pages;
        $start = $limit*$page - $limit;
        if($start <0) $start = 0;

        $q = $db->query($sql."
        	ORDER BY `".$sidx."` ".$sord."
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
        $grand_qty=0;$grand_faktur=0;$grand_totalfaktur=0;$grand_piutang=0;$grand_tunai=0;$grand_transfer=0;$grand_biaya=0 ;
        foreach($data1 as $line) {
        	
        	// $allowEdit = array(1,2,3);
        	// $allowPost = array(1,2,3);
        	// $allowDelete = array(1,2,3);
          if ($statusToko == 'Tutup') {
                $edit = '<a onclick="javascript:custom_alert(\'Maaf, Toko Sudah Tutup\')" href="javascript:;">Edit</a>';
                $posting = '<a onclick="javascript:custom_alert(\'Maaf, Toko Sudah Tutup\')" href="javascript:;">POST</a>';
                $delete = '<a onclick="javascript:custom_alert(\'Maaf, Toko Sudah Tutup\')" href="javascript:;">Delete</a>';
            } else {
			   //edit
        	if($allow_edit)
        	{
        		if($line['state']=='0'){
        			$edit = '<a onclick="window.open(\''.BASE_URL.'pages/inventory/invkeluar_detail_edit.php?ids='.$line['id_trans'].'\',\'table_invkeluar\')" href="javascript:;">Edit</a>';
        		}
        		else
        		{
        			$edit = '<a onclick="javascript:custom_alert(\'Tidak Boleh Edit\')" href="javascript:;">POSTED</a>'; 	
        		}
        	}
        	else
        		$edit = '<a onclick="javascript:custom_alert(\'Tidak Boleh Edit\')" href="javascript:;">POSTED</a>';
        	
			//posting
        	if($allow_post)
        	{
				//belum posting ke gudang
        		if($line['state']=='0'){
        			$posting = '<a onclick="javascript:link_ajax(\''.BASE_URL.'pages/inventory/invkeluar.php?action=post&id='.$line['id_trans'].'\',\'table_invkeluar\')" href="javascript:;">POST</a>';
        		}
				//sudah posting ke gudang
        		else
        		{
        			$posting = '<a onclick="javascript:custom_alert(\'Tidak Bisa Posting ke gudang, karena data sudah posted ke gudang\')" href="javascript:;">POSTED</a>';
        		}
        	}
        	else
        		$posting = '<a onclick="javascript:custom_alert(\'Tidak Boleh Posting\')" href="javascript:;">Post</a>';
        	
			//delete
        	if($allow_delete)
        		$delete = '<a onclick="javascript:link_ajax(\''.BASE_URL.'pages/inventory/invkeluar.php?action=delete&id='.$line['id_trans'].'\',\'table_invkeluar\')" href="javascript:;">Delete</a>';
        	else
        		$delete = '<a onclick="javascript:custom_alert(\'Tidak Boleh dibatalkan\')" href="javascript:;">Delete</a>';
        	
        	$select = '<input type="checkbox" class="chkPrint" name="select"  value='.$line['id_trans'].'>';
        	}
        	$responce['rows'][$i]['id']   = $line['id_trans'];
        	$responce['rows'][$i]['cell'] = array(
        		$line['id'],
        		$line['id_trans'],
        		$line['kode'],                
        		$line['sumber'],
        		$line['tgl_trans'],
        		$line['lastmodified'],
        		$line['catatan'],
        		number_format($line['totalqty'],0),
        		$edit,
        		$posting,
        		$delete,
        	);
        	$grand_qty+=$line['totalqty'];
        	$i++;
        }
        
        $responce['userdata']['totalqty'] 		= number_format($grand_qty,0);
        
        echo json_encode($responce);
        
        exit;
      }
      elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'post') {
        $sql = $db->query("SELECT state FROM `invkeluar` where id_trans='".$_GET['id']."'");
        $data = $sql->fetchAll(PDO::FETCH_ASSOC);
        foreach($data as $d){
          if ($d['state']=='0') {
             //update state posting = 1
           $stmt = $db->prepare("UPDATE invkeluar SET state='1' WHERE id_trans=?");
           $stmt->execute(array($_GET['id']));
           
             //insert inventory
           $stmt = $db->prepare("INSERT INTO inventory (id_trans,id_product,namabrg,size,qty,lastmodified)
            SELECT id_trans,id_product,namabrg,size,-jumlah_beli,NOW() FROM invkeluar_detail WHERE id_trans=?"); 
           $stmt->execute(array($_GET['id']));


           $q = $db->query("SELECT ikdet.id_product FROM `invkeluar_detail` ikdet where ikdet.id_trans='".$_GET['id']."'");
           $data1 = $q->fetchAll(PDO::FETCH_ASSOC);
           foreach($data1 as $line){

                //update incentory balance
            $stmt = $db->query("UPDATE inventory_balance ib SET ib.stok=IFNULL(ib.stok,0)+(SELECT SUM(i.qty) FROM inventory i  WHERE i.id_product='". $line['id_product']."' and `update`=0),ib.lastmodified=NOW() where ib.id='". $line['id_product']."'");

                //update inventory
            $stmt = $db->query("UPDATE inventory set `update`=1 where id_product='". $line['id_product']."'");  
          }

          $affected_rows = $stmt->rowCount();
          if($affected_rows > 0) {
            $r['stat'] = 1;
            $r['message'] = 'Success';
          }
          else {
            $r['stat'] = 0;
            $r['message'] = 'Failed';
          }
        }else{
         $r['stat'] = 0;
         $r['message'] = 'Data Sudah di Posting';
       }
     }  	
     echo json_encode($r);
     exit;
   }
   elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'delete') {
		//delete stok yang ada di inventory
     $stmt = $db->prepare("delete from inventory WHERE id_trans=?");
     $stmt->execute(array($_GET['id']));

		//update invkeluar agar deleted jadi 1
     $stmt = $db->prepare("update invkeluar set deleted=1 WHERE id_trans=?");
     $stmt->execute(array($_GET['id']));

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
  elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'json_sub') {

   $id = $_GET['id'];
		//$id = $line['id_trans'];
   $where = "WHERE pd.id_trans = '".$id."' ";
   $q = $db->query("SELECT pd.* FROM `invkeluar_detail` pd ".$where);

   $count = $q->rowCount();

   $data1 = $q->fetchAll(PDO::FETCH_ASSOC);

   $i=0;
   $responce = '';
   foreach($data1 as $line){
    $responce->rows[$i]['id']   = $line['id_inv_d'];
    $responce->rows[$i]['cell'] = array(
     $i+1,
     $line['id_product'],
     $line['namabrg'],
     $line['size'],
     number_format($line['jumlah_beli'],0),                
     number_format($line['subtotal'],0)                
   );
    $i++;
  }
  echo json_encode($responce);
  exit;
}


?>
<div class="ui-widget ui-form" style="margin-bottom:5px">
 <div class="ui-widget-header ui-corner-top padding5">
  Filter Data
</div>
<div class="ui-widget-content ui-corner-bottom">
  <form id="report_project_form" method="" action="" class="ui-helper-clearfix">
   <label for="tglposting" class="ui-helper-reset label-control">Posting Date</label>
   <div class="ui-corner-all form-control">
    <table>
     <tr>
      <td>
       <input value="" type="text" class="required datepicker"   id="startdate_invkeluar" name="startdate_invkeluar">
     </td>
     <td> s.d.  
       <input value="" type="text" class="required datepicker"  id="enddate_invkeluar" name="enddate_invkeluar">
     </td>
     <td> Filter
       <input value="" type="text" id="filter_invkeluar" name="filter_invkeluar">(Kode,Catatan,totalqty)
     </td>
   </tr>
 </table>
</div>
<label for="" class="ui-helper-reset label-control">&nbsp;</label>
<div class="ui-corner-all form-control">
  <button onclick="gridReload()" class="btn" type="button">Cari</button>
</div>
</form>
</div>
</div>
<div class="btn_box">	
 <?php
    $statusToko = '';
    $getStat = $db->prepare("SELECT * FROM tbl_status LIMIT 1");
    $getStat->execute();
    $stat = $getStat->fetchAll();
    foreach ($stat as $stats) {
        $statusToko = $stats['status'];
    }
    
    if ($statusToko == 'Tutup') {
        echo '<button type="button" onclick="javascript:custom_alert(\'Maaf, Toko Sudah Tutup\')" class="btn">Tambah</button>';
    }else{
 if ($allow_add) {
  ?>
  <a href="javascript: void(0)" onclick="window.open('pages/inventory/invkeluar_detail.php');">
   <button class="btn btn-success">Tambah</button></a> 
   <?php
 }}
 ?>
</br>
</div>

<table id="table_invkeluar"></table>
<div id="pager_table_invkeluar"></div>

<!--
<?php
	/*
	$allow = array(1,2,3);
	if(in_array($_SESSION['user']['access'], $allow)) {
		echo '<button type="button" onclick="javascript:popup_form(\''.BASE_URL.'pages/beli.php?action=add\',\'table_beli\')" class="btn">Tambah</button>';		
	}	
	*/
?>
-->

<script type="text/javascript">
	
	$('#startdate_invkeluar').datepicker({
		dateFormat: "dd/mm/yy"
	});
	$('#enddate_invkeluar').datepicker({
		dateFormat: "dd/mm/yy"
	});
	$( "#startdate_invkeluar" ).datepicker( 'setDate', '<?php echo date('d/m/Y')?>' );
	$( "#enddate_invkeluar" ).datepicker( 'setDate', '<?php echo date('d/m/Y')?>' );
	
	
	function gridReload(){
		var startdate = $("#startdate_invkeluar").val();
		var enddate = $("#enddate_invkeluar").val();
		var filter = $("#filter_invkeluar").val();
		var v_url ='<?php echo BASE_URL?>pages/inventory/invkeluar.php?action=json&startdate_invkeluar='+startdate+'&enddate_invkeluar='+enddate+'&filter='+filter;
		jQuery("#table_invkeluar").setGridParam({url:v_url,page:1}).trigger("reloadGrid");
	}
	
	$("#btn-xlsdo").on('click',function(){
		var ids = getSelectedRows();
		if (ids!=='')
			window.open('<?php echo BASE_URL?>pages/sales_online/trolndo_xls.php?ids='+ids,'_blank');
	});
	
	$("#btn-print").on('click',function(){
		var ids = getSelectedRows();
		if (ids!=='')
			window.open('<?php echo BASE_URL?>pages/sales_online/trolnso_3nota_new.php?ids='+ids,'_blank');
	});
	
	$("#btn-barcode").on('click',function(){
		var ids = getSelectedRows();
		if (ids!=='')
			window.open('<?php echo BASE_URL?>pages/sales_online/trolnso_3nota_bd.php?ids='+ids,'_blank');
	});
	
	function getSelectedRows() {
		var grid = $("#table_jualdo");
		var rowKey = grid.getGridParam("selrow");

		if (!rowKey){
			alert("No rows are selected");
			return '';
		}
		else {
			var selectedIDs = grid.getGridParam("selarrrow");
			var result = "";
			for (var i = 0; i < selectedIDs.length; i++) {
				result += "'"+selectedIDs[i]+"'" + ",";
			}

			return result;
		}                
	}
	

	$(document).ready(function(){
		
		$("#table_invkeluar").jqGrid({
			url:'<?php echo BASE_URL.'pages/inventory/invkeluar.php?action=json'; ?>',
			datatype: "json",
			colNames:['ID','ID_trans','Kode','Sumber','Input.Date','Post.Date','Note','Qty','Edit','Posting','Cancel'],
			colModel:[
			{name:'id',index:'id', align:'right', width:20, search:true, stype:'text', searchoptions:{sopt:['cn']}},
			{name:'id_trans',index:'id_trans', width:40, search:true, stype:'text', searchoptions:{sopt:['cn']}},
			{name:'kode',index:'kode', align:'left', width:30, searchoptions: {sopt:['cn']}},
			{name:'sumber',index:'sumber', align:'left', width:50, searchoptions: {sopt:['cn']}},
			{name:'tgl_trans',index:'tgl_trans', width:35, searchoptions: {sopt:['cn']},formatter:"date", formatoptions:{srcformat:"Y-m-d", newformat:"d/m/Y"}, align:'center'},
			{name:'lastmodified',index:'lastmodified', width:35, searchoptions: {sopt:['cn']},formatter:"date", formatoptions:{srcformat:"Y-m-d", newformat:"d/m/Y"}, align:'center'},
			{name:'catatan',index:'catatan', align:'left', width:80, searchoptions: {sopt:['cn']}},
			{name:'totalqty',index:'totalqty', align:'right', width:18, searchoptions: {sopt:['cn']}},
			{name:'edit',index:'edit', align:'center', width:25, sortable: false, search: false},
			{name:'posting',index:'posting', align:'center', width:25, sortable: false, search: false},
			{name:'delete',index:'delete', align:'center', width:25, sortable: false, search: false},
			
			],
			rowNum:1000,
			rowList:[10,20,30,100,1000,10000],
			pager: '#pager_table_invkeluar',
			sortname: 'id_trans',
			autowidth: true,
			multiselect:true,
			height: '300',
			viewrecords: true,
			rownumbers: true,
			sortorder: "desc",
			caption:"Mutasi Keluar Barang",
			ondblClickRow: function(rowid) {
				alert(rowid);
			},
			footerrow : true,
			userDataOnFooter : true,
			subGrid : true,
			subGridUrl : '<?php echo BASE_URL.'pages/inventory/invkeluar.php?action=json_sub'; ?>',
			subGridModel: [
			{ 
				name : ['No','Kode','Barang','Size','Qty(pcs)','Subtotal'], 
				width : [40,40,300,50,50,50],
				align : ['right','center','left','center','right','right','right'],
			} 
			],
			
			
		});
		$("#table_invkeluar").jqGrid('navGrid','#pager_table_invkeluar',{edit:false,add:false,del:false,search:false});
		

		
		// $("#checkAll").click(function () {
			// $(".chkPrint").prop('checked', $(this).prop('checked'));
		// });
	})
</script>