<?php require_once '../../include/config.php' ?>
<?php
$group_acess = unserialize(file_get_contents("../../GROUP_ACCESS_CACHE".$_SESSION['user']['group_id']));
// $allow_post = is_show_menu(POST_POLICY, ArchiveOrder, $group_acess);
// $allow_delete = is_show_menu(DELETE_POLICY, ArchiveOrder, $group_acess);

if(isset($_GET['action']) && strtolower($_GET['action']) == 'json') {
	$page  = $_GET['page'];
	$limit = $_GET['rows'];
	$sidx  = $_GET['sidx'];
	$sord  = $_GET['sord'];

	if(!$sidx) $sidx=1;
	if ($_REQUEST["_search"] == "false") {
       //all transaction kecuali yang batal
		$where = " WHERE TRUE AND p.state='1' AND (p.deleted=0) ";
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
		$where = sprintf(" WHERE TRUE AND p.state='1' AND (p.deleted=0)  AND %s ".$operations[$_REQUEST["searchOper"]], $_REQUEST["searchField"], $value);
	 //echo"<script>alert('where=$where')</script>";
	}
	$sql_unpaid = "SELECT p.*,k.nama as kategori,j.nama as customer,s.nama as salesman,e.nama as expedition FROM `b2bso` p Left Join `mst_b2bcategory_sale` k on (p.id_kategori=k.id) Left Join `mst_b2bsalesman` s on (p.id_salesman=s.id) Left Join `mst_b2bcustomer` j on (p.id_customer=j.id) Left Join `mst_b2bexpedition` e on (p.id_expedition=e.id) ".$where;
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
        if ($statusToko == 'Tutup') {
            $detail = '<a onclick="javascript:custom_alert(\'Maaf, Toko Sudah Tutup\')" href="javascript:;">Detail</a>';
        }else{
            $detail = '<a onclick="window.open(\''.BASE_URL.'pages/sales_b2b/trb2bso_confirmed_detail.php?ids='.$line['id_trans'].'\',\'table_b2bso_confirmed\')" href="javascript:;">Detail</a>';
        }
                $responce['rows'][$i]['id']   = $line['id_trans'];
                $responce['rows'][$i]['cell'] = array(
                    $line['id_trans'],
                    $line['ref_kode'],                
                    $line['customer'],                
                    $line['tgl_trans'],
                    $line['salesman'],
                    $line['alamat'],
                    $line['kategori'],
                    number_format($line['totalqty'],0),
                    number_format($line['totalkirim'],0),
                    $detail,
				);
				$i++;
			}
			echo json_encode($responce);
			exit;
		}
		elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'json_sub') {

			$id = $_GET['id'];

			$where = "WHERE do.id_transb2bso = '".$id."' AND totalkirim <> 0 AND totalfaktur <> 0 ";
			$q = $db->query("SELECT do.*,date_format(do.tgl_trans,'%d-%m-%Y') as tanggal,e.nama as expedition FROM `b2bdo` do left join mst_b2bexpedition e on do.id_expedition=e.id ".$where. " order by id desc");
		//var_dump($q); die;
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
		<table id="table_b2barchive"></table>
		<div id="pager_table_b2barchive"></div>
		<div class="btn_box">
			    <?php
    $statusToko = '';
    $getStat = $db->prepare("SELECT * FROM tbl_status LIMIT 1");
    $getStat->execute();
    $stat = $getStat->fetchAll();
    foreach ($stat as $stats) {
        $statusToko = $stats['status'];
    }

    ?>
		</br>
	</div>

	<script type="text/javascript">
		$(document).ready(function(){

			$("#table_b2barchive").jqGrid({
				url:'<?php echo BASE_URL.'pages/sales_b2b/trb2barchive.php?action=json'; ?>',
				datatype: "json",
				colNames:['ID','Code','Customer','Date','Salesman','Address','Category','Qty','Sent','Detail'],
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
				],
				rowNum:20,
				rowList:[10,20,30],
				pager: '#pager_table_b2barchive',
				sortname: 'id_trans',
				autowidth: true,
				height: '400',
				viewrecords: true,
				rownumbers: true,
				sortorder: "desc",
				caption:"Archive Order B2B",
				ondblClickRow: function(rowid) {
					alert(rowid);
				},
				subGrid : true,
				subGridUrl : '<?php echo BASE_URL.'pages/sales_b2b/trb2barchive.php?action=json_sub'; ?>',
				subGridModel: [
				{ 
					name : ['No','Kode','Tanggal','Expedition','Qty Kirim','Faktur','Exp.Fee','Totalfaktur'], 
                    width : [40,80,70,100,50,80,80,80],
                    align : ['right','center','center','center','right','right','right','right'],
				} 
				],


			});
			$("#table_b2barchive").jqGrid('navGrid','#pager_table_b2barchive',{edit:false,add:false,del:false});
		})
	</script>