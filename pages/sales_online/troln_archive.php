<?php require_once '../../include/config.php' ?>
<?php
$group_acess = unserialize(file_get_contents("../../GROUP_ACCESS_CACHE".$_SESSION['user']['group_id']));
$allow_post = is_show_menu(POST_POLICY, ArchiveOrder, $group_acess);
$allow_delete = is_show_menu(DELETE_POLICY, ArchiveOrder, $group_acess);

if(isset($_GET['action']) && strtolower($_GET['action']) == 'json') {
	$page  = $_GET['page'];
	$limit = $_GET['rows'];
	$sidx  = $_GET['sidx'];
	$sord  = $_GET['sord'];

	if(!$sidx) $sidx=1;
	if ($_REQUEST["_search"] == "false") {
       //all transaction kecuali yang batal
		$where = "WHERE TRUE AND (p.totalqty <> 0)";
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
		$where = sprintf(" where TRUE AND (p.totalqty <> 0) AND %s ".$operations[$_REQUEST["searchOper"]], $_REQUEST["searchField"], $value);
	 //echo"<script>alert('where=$where')</script>";
	}
	$sql_unpaid = "SELECT p.*,j.nama as dropshipper FROM `olnso` p Left Join `mst_dropshipper` j on (p.id_dropshipper=j.id) Left Join `mst_expedition` e on (p.id_expedition=e.id) ".$where;
        //var_dump($sql_unpaid);die;
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

			//if(in_array($_SESSION['user']['access'], $allowEdit))
			//{

		if ($statusToko == 'Tutup') {
                $edit = '<a onclick="javascript:custom_alert(\'Maaf, Toko Sudah Tutup\')" href="javascript:;">POSTED</a>';
                $delete = '<a onclick="javascript:custom_alert(\'Maaf, Toko Sudah Tutup\')" href="javascript:;">SENT</a>';
                $return = '<a onclick="javascript:custom_alert(\'Maaf, Toko Sudah Tutup\')" href="javascript:;">RETURN</a>';
            } else {
		if ($line['state']=='0')
		{
			//posting
			if ($allow_post) {
				$edit = '<a onclick="javascript:link_ajax(\''.BASE_URL.'pages/sales_online/troln_archive.php?action=posting&id='.$line['id_trans'].'\',\'table_jualarchive\')" href="javascript:;">Posting</a>';
			} else {
				$edit = '<a onclick="javascript:custom_alert(\'Tidak Bisa Posting\')" href="javascript:;">POSTED</a>';
			}

			//delete
			if ($allow_delete) {
				$delete = '<a onclick="javascript:link_ajax(\''.BASE_URL.'pages/sales_online/troln_archive.php?action=delete&id='.$line['id_trans'].'\',\'table_jualarchive\')" href="javascript:;">Cancel</a>';
			} else {
				$delete = '<a onclick="javascript:custom_alert(\'Tidak Bisa Dibatalkan \')" href="javascript:;">SENT</a>';
			}

			//return
			$return = '<a onclick="javascript:custom_alert(\'Tidak Bisa DiRETUR \')" href="javascript:;">Unreturnable</a>';
			/*}
				else if ($line['state']=='"0"'){
				$edit='Posted';
				$delete='Sent';
				*/
			} 
			else
			{
				$edit = '<a onclick="javascript:custom_alert(\'Tidak Bisa Posting\')" href="javascript:;">POSTED</a>';
				$delete = '<a onclick="javascript:custom_alert(\'Tidak Bisa Dibatalkan \')" href="javascript:;">SENT</a>';
				//return
				if ($allow_post) {
					$return = '<a onclick="window.open(\''.BASE_URL.'pages/sales_online/trolnreturn_detail_edit.php?ids='.$line['id'].'\',\'table_jualarchive\')" href="javascript:;">RETURN</a>';
				} else {
					$return = '<a onclick="javascript:custom_alert(\'Tidak Bisa DiRETUR \')" href="javascript:;">Unreturnable</a>';
				}
				
				
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
					$line['dropshipper'],                
					$line['tgl_trans'],
					number_format($line['totalqty'],0),
					number_format($line['faktur'],0),
					number_format($line['exp_fee'],0),
					number_format($line['total'],0),
					number_format($line['tunai'],0),
					number_format($line['transfer'],0),
					number_format(($line['piutang']-$line['pelunasan']),0),
					$edit,
					$delete,
					$return,
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
		elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'delete') {
		//delete olndeposit krn void invoice		
			$stmt = $db->prepare("delete from olndeposit WHERE id_trans=?");
			$stmt->execute(array($_GET['id']));

		//update trjual agar jadi nol krn void invoice
			$stmt = $db->prepare("Update olnso set total=0,exp_fee=0,faktur=0,totalqty=0,tunai=0,transfer=0,deposit=0,piutang=0,pelunasan=0,deleted=1 WHERE id_trans=?");
			$stmt->execute(array($_GET['id']));
		//var_dump($stmt);die;
		//update trjual_detail agar jadi nol krn void invoice
			$stmt = $db->prepare("update olnsodetail set jumlah_beli=0,harga_satuan=0,subtotal=0 WHERE id_trans=?");
			$stmt->execute(array($_GET['id']));
		//var_dump($stmt);die;

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

			$where = "WHERE pd.id_trans = '".$id."' ";
			$q = $db->query("SELECT pd.* FROM `olnsodetail` pd ".$where);
		//var_dump($q); die;
			$count = $q->rowCount();
			$data1 = $q->fetchAll(PDO::FETCH_ASSOC);

			$i=0;
			$responce = '';
			foreach($data1 as $line){
				$responce->rows[$i]['id']   = $line['id_so_d'];
				$responce->rows[$i]['cell'] = array(
					$i+1,
					$line['id_product'],
					$line['namabrg'],
					$line['size'],
					number_format($line['harga_satuan'],0),
					number_format($line['jumlah_beli'],0),                
					number_format($line['subtotal'],0),                
				);
				$i++;
			}
			echo json_encode($responce);
			exit;
		}

		?>
		<table id="table_jualarchive"></table>
		<div id="pager_table_jualarchive"></div>
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
        echo '<button type="button" onclick="javascript:custom_alert(\'Maaf, Toko Sudah Tutup\')" class="btn">Cetak</button>';
    }else{
    ?>
			<a href="javascript: void(0)" 
			onclick="window.open('pages/summary_online/oln_unpaidrpt.php')">
			<button class="btn btn-success">Cetak</button></a>  
	<?php } ?>
		</br>
	</div>

	<script type="text/javascript">
		$(document).ready(function(){

			$("#table_jualarchive").jqGrid({
				url:'<?php echo BASE_URL.'pages/sales_online/troln_archive.php?action=json'; ?>',
				datatype: "json",
				colNames:['ID','Dropshipper','Tanggal Transaksi','Qty','Faktur','Ongkir','Total Faktur','Tunai','Transfer','Piutang','State','Delete','Return'],
				colModel:[
				{name:'id_trans',index:'id_trans', width:80, search:true, stype:'text', searchoptions:{sopt:['cn']}},
				{name:'dropshipper',index:'j.nama', width:100, searchoptions: {sopt:['cn']}},                
				{name:'tgl_trans',index:'tgl_trans', width:80, searchoptions: {sopt:['cn']},formatter:"date", formatoptions:{srcformat:"Y-m-d", newformat:"d/m/Y"}, align:'center'},
				{name:'totalqty',index:'totalqty', align:'right', width:40, searchoptions: {sopt:['cn']}},
				{name:'faktur',index:'faktur', align:'right', width:80, searchoptions: {sopt:['cn']}},
				{name:'biaya',index:'biaya', align:'right', width:60, searchoptions: {sopt:['cn']}},
				{name:'totalfaktur',index:'totalfaktur', align:'right', width:80, searchoptions: {sopt:['cn']}},
				{name:'tunai',index:'tunai', align:'right', width:80, searchoptions: {sopt:['cn']}},
				{name:'transfer',index:'transfer', align:'right', width:80, searchoptions: {sopt:['cn']}},
				{name:'piutang',index:'piutang', align:'right', width:80, searchoptions: {sopt:['cn']}},
				{name:'edit',index:'edit', align:'center', width:50, sortable: false, search: false},
				{name:'delete',index:'delete', align:'center', width:50, sortable: false, search: false},
				{name:'return',index:'return', align:'center', width:50, sortable: false, search: false},
				],
				rowNum:20,
				rowList:[10,20,30],
				pager: '#pager_table_jualarchive',
				sortname: 'id_trans',
				autowidth: true,
				height: '400',
				viewrecords: true,
				rownumbers: true,
				sortorder: "desc",
				caption:"Archive Order",
				ondblClickRow: function(rowid) {
					alert(rowid);
				},
				subGrid : true,
				subGridUrl : '<?php echo BASE_URL.'pages/sales_online/troln_archive.php?action=json_sub'; ?>',
				subGridModel: [
				{ 
					name : ['No','Kode','Barang','Size','Harga','Qty(pcs)','Subtotal'], 
					width : [40,40,300,50,30,50,50],
					align : ['right','center','left','center','right','right','right'],
				} 
				],


			});
			$("#table_jualarchive").jqGrid('navGrid','#pager_table_jualarchive',{edit:false,add:false,del:false});
		})
	</script>