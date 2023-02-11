<?php require_once '../../include/config.php' ?>
<?php
$group_acess = unserialize(file_get_contents("../../GROUP_ACCESS_CACHE".$_SESSION['user']['group_id']));
$allow_post = is_show_menu(POST_POLICY, PreSALESCredit, $group_acess);
$allow_delete = is_show_menu(DELETE_POLICY, PreSALESCredit, $group_acess);

	if(isset($_GET['action']) && strtolower($_GET['action']) == 'json') {
		$page  = $_GET['page'];
        $limit = $_GET['rows'];
        $sidx  = $_GET['sidx'];
        $sord  = $_GET['sord'];
		
	   if(!$sidx) $sidx=1;
               if ($_REQUEST["_search"] == "false") {
       //all transaction kecuali yang batal
	   //$where = "WHERE TRUE AND p.state='0' AND (p.totalqty <> 0) AND (p.piutang= 0) and (p.deleted=0) ";
	   $where = "WHERE TRUE AND (p.state=0)  ";
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
		$where = sprintf(" where TRUE AND (p.state=0) %s ".$operations[$_REQUEST["searchOper"]], $_REQUEST["searchField"], $value);
	 	}			
				
		$sql = "SELECT p.total as grandtotal,sum(p.deposit) as deposit,p.oln_order_id,p.oln_customer,p.oln_tgl,p.oln_penerima,p.oln_address,sum(p.jumlah_beli) as qty, sum(p.subtotal) as total,p.oln_note,p.oln_expnote FROM `olnpreso_cr` p ".$where. " GROUP by p.oln_order_id";
        //var_dump($sql);die;
		$q = $db->query($sql);
		$count = $q->rowCount();
        //var_dump($sql);
        $count > 0 ? $total_pages = ceil($count/$limit) : $total_pages = 0;
        if ($page > $total_pages) $page=$total_pages;
        $start = $limit*$page - $limit;
        if($start <0) $start = 0;

        $q = $db->query($sql."
							 ORDER BY `".$sidx."` ".$sord."
							 LIMIT ".$start.", ".$limit);
		$data1 = $q->fetchAll(PDO::FETCH_ASSOC);
        $responce['page'] = $page;
        $responce['total'] = $total_pages;
        $responce['records'] = $count;
        $i=0;
		$grand_qty=0;$grand_faktur=0;$grand_totalfaktur=0;$grand_piutang=0;$grand_tunai=0;$grand_transfer=0;$grand_biaya=0 ;
        foreach($data1 as $line) {
        	
			// $allowEdit = array(1,2,3);
			// $allowDelete = array(1,2,3);

		    if($allow_post){
			$edit = '<a onclick="window.open(\''.BASE_URL.'pages/import_XLS_credit/olnpresocr_detail_edit.php?ids='.$line['oln_order_id'].'\',\'table_olnpresocr\')" href="javascript:;">Posting</a>';
			}
			else
				$edit = '<a onclick="javascript:custom_alert(\'Tidak Boleh Posting Data\')" href="javascript:;">Posting</a>';
			
			if($allow_delete)
				$delete = '<a onclick="javascript:link_ajax(\''.BASE_URL.'pages/import_XLS_credit/olnpresocr.php?action=delete&id='.$line['oln_order_id'].'\',\'table_olnpresocr\')" href="javascript:;">Cancel</a>';
			else
				$delete = '<a onclick="javascript:custom_alert(\'Tidak Boleh dibatalkan\')" href="javascript:;">Delete</a>';
			
			    //$select = '<input type="checkbox" class="chkPrint" name="select"  value='.$line['id_trans'].'>';
			
        	$responce['rows'][$i]['id']   = $line['oln_order_id'];
            $responce['rows'][$i]['cell'] = array(
                $line['oln_order_id'],
                $line['oln_customer'],                
                $line['oln_tgl'],
                $line['oln_penerima'],
                $line['oln_address'],
                number_format($line['qty'],0),
				number_format($line['grandtotal'],0),
				number_format($line['deposit'],0),
                $line['oln_note'],
                $line['oln_expnote'],
				$edit,
				$delete,
			//	$select,
            );
			//$grand_qty+=$line['totalqty'];
			//$grand_faktur+=$line['faktur'];
			//$grand_totalfaktur+=$line['total'];
			//$grand_piutang+=$line['piutang'];
			//$grand_tunai+=$line['tunai'];
			//$grand_transfer+=$line['transfer'];
			//$grand_biaya+=$line['exp_fee'];
            $i++;
        }
		/*
		$responce['userdata']['totalqty'] 		= number_format($grand_qty,0);
		$responce['userdata']['faktur'] 		= number_format($grand_faktur,0);
		$responce['userdata']['totalfaktur'] 	= number_format( $grand_totalfaktur,0);
		$responce['userdata']['piutang'] 		= number_format($grand_piutang,0);
		$responce['userdata']['tunai'] 			= number_format($grand_tunai,0);
		$responce['userdata']['transfer']		= number_format($grand_transfer,0);
		$responce['userdata']['exp_fee'] 			= number_format($grand_biaya,0);
        */
		echo json_encode($responce);
		
		exit;
	}
	elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'posting') {
		//posting data untuk oln_id
		$stmt = $db->prepare("INSERT INTO olnso_id(`nomor`,`id_trans`,`user_id`,`lastmodified`) SELECT IFNULL((MAX(nomor)+1),0),?,?,NOW() FROM olnso_id WHERE DATE(lastmodified)=DATE(NOW())"); 
		$stmt->execute(array($_GET['id'],$_SESSION['user']['user_id']));
		
		//$sql_posting="INSERT INTO olnso_id(`nomor`,`id_trans`,`user`) SELECT MAX(nomor) + 1,'".$_GET['id']."','".$_SESSION['user']['user_id']."' FROM olnso_id WHERE DATE(lastmodified)=DATE(NOW())";
		//var_dump($sql_posting);
		
		//$stmt = $db->query($sql_posting);
		
		
		
		//update olnso agar jadi 1 krn siap kirim,tapi statenya dikasih string='1' krn tipe datanya enum
		$stmt = $db->prepare("Update olnpreso_cr set state='1',lastmodified=now() WHERE oln_order_id=?");
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
	elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'delete') {
		//delete olndeposit krn void invoice,diganti dengan nol		
		//$stmt = $db->prepare("delete from olndeposit WHERE id_trans=?");
		
		//update troln agar jadi nol krn void invoice dan dimasukan ke archive state=3
		$stmt = $db->prepare("DELETE FROM olnpreso_cr WHERE oln_order_id=?");
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
		//$id = $line['id_trans'];
		$where = "WHERE pd.oln_order_id = '".$id."' ";
        $q = $db->query("SELECT pd.* FROM `olnpreso_cr` pd ".$where);
		
		$count = $q->rowCount();
		
		//$q = $db->query("SELECT pd.id_detail,pd.id_barang,b.nm_barang,b.kode_brg,pd.id_trans,pd.qty,pd.harga,(pd.qty * pd.harga) as subtotal FROM `trjual_detail` pd INNER JOIN `barang` b ON (pd.kode_brg=b.kode_brg) ".$where);
		$data1 = $q->fetchAll(PDO::FETCH_ASSOC);
		
        $i=0;
        $responce = '';
        foreach($data1 as $line){
            $responce->rows[$i]['id']   = $line['id'];
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
<!--
<div class="btn_box">
 <a href="javascript: void(0)" 
   onclick="window.open('pages/sales_online/trolnso_detail.php');">
   <button class="btn btn-success">Add</button></a>   
</div>
--> 
<table id="table_olnpresocr"></table>
<div id="pager_table_olnpresocr"></div>

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
	
    $(document).ready(function(){
			
		// d = $("#startdate_jual").datepicker("getDate");
		// $("#startdate_jual").datepicker("setDate", new Date(d.getFullYear()+1,d.getMonth(),d.getDate()));
		//alert('kdie');
        $("#table_olnpresocr").jqGrid({
            url:'<?php echo BASE_URL.'pages/import_XLS_credit/olnpresocr.php?action=json'; ?>',
            datatype: "json",
            colNames:['orderID','Dropshipper','Date','Receiver','Address','Qty','Total','Deposit','Note','Exp.Code','Posting','Cancel'],
            colModel:[
                {name:'oln_order_id',index:'oln_order_id', width:10, search:true, stype:'text', searchoptions:{sopt:['cn']}},
                {name:'dropshipper',index:'dropshipper', width:35, search:true, stype:'text', searchoptions:{sopt:['cn']}},
                {name:'oln_tgl',index:'oln_tgl', width:20, searchoptions: {sopt:['cn']},formatter:"date", formatoptions:{srcformat:"Y-m-d", newformat:"d/m/Y"}, align:'center'},
                {name:'oln_penerima',index:'oln_penerima', align:'left', width:30, searchoptions: {sopt:['cn']}},
                {name:'oln_address',index:'oln_address', align:'left', width:100, searchoptions: {sopt:['cn']}},
                {name:'qty',index:'qty', align:'right', width:8, searchoptions: {sopt:['cn']}},
				{name:'grandtotal',index:'grandtotal', align:'right', width:15, searchoptions: {sopt:['cn']}},
				{name:'deposit',index:'deposit', align:'right', width:15, searchoptions: {sopt:['cn']}},
				{name:'oln_note',index:'oln_note', align:'left', width:35, searchoptions: {sopt:['cn']}},
                {name:'oln_expnote',index:'oln_expnote', align:'right', width:35, searchoptions: {sopt:['cn']}},
                {name:'edit',index:'edit', align:'center', width:15, sortable: false, search: false},
                {name:'delete',index:'delete', align:'center', width:15, sortable: false, search: false},
            ],
            rowNum:2000,
            rowList:[1000,2000,3000],
            pager: '#pager_table_olnpresocr',
            sortname: 'oln_order_id',
            autowidth: true,
	        height: '500',
            viewrecords: true,
            rownumbers: true,
            sortorder: "asc",
            caption:"Data PRE SALES Credit",
            ondblClickRow: function(rowid) {
                alert(rowid);
            },
			footerrow : true,
			userDataOnFooter : true,
            subGrid : true,
            subGridUrl : '<?php echo BASE_URL.'pages/import_XLS_credit/olnpresocr.php?action=json_sub'; ?>',
            subGridModel: [
			            	{ 
			            		name : ['No','Kode','Barang','Size','Harga','Qty(pcs)','Subtotal'], 
			            		width : [40,40,300,30,50,50,50],
			            		align : ['right','center','left','center','right','right','right'],
			            	} 
			            ],
						
            
        });
        $("#table_olnpresocr").jqGrid('navGrid','#pager_table_olnpresocr',{edit:false,add:false,del:false,search:true});
		

		
		// $("#checkAll").click(function () {
			// $(".chkPrint").prop('checked', $(this).prop('checked'));
		// });
    })
</script>