<?php require_once '../../include/config.php' ?>
<?php
	if(isset($_GET['action']) && strtolower($_GET['action']) == 'json') {
		$page  = $_GET['page'];
        $limit = $_GET['rows'];
        $sidx  = $_GET['sidx'];
        $sord  = $_GET['sord'];
		
	   if(!$sidx) $sidx=1;
               if ($_REQUEST["_search"] == "false") {
       //all transaction kecuali yang batal
	   $where = "WHERE TRUE AND p.state='0' AND (p.totalqty <> 0) AND (p.piutang= 0) and (p.deleted=0) ";
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
		$where = sprintf(" where TRUE AND (p.totalqty <> 0) AND (p.state ='0') AND (p.piutang= 0) and (p.deleted=0) AND %s ".$operations[$_REQUEST["searchOper"]], $_REQUEST["searchField"], $value);
	 //echo"<script>alert('where=$where')</script>";		
		}			
		
		//0= SALES,1=DO,3=ARCHIVE_DO
		//MENAMPILKAN PENJUALAN YANG BARU INPUT STATE=0 DAN TOTALQTY<>0 KRN BUKAN TRANSAKSI CANCEL dan TRANSAKSI YANG SUDAH LUNAS /Cash(PIUTANG=0)
        
		$sql = "SELECT p.*,j.nama as dropshipper,e.nama as expedition FROM `olnso` p Left Join `mst_dropshipper` j on (p.id_dropshipper=j.id) Left Join `mst_expedition` e on (p.id_expedition=e.id) ".$where;
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
        	
			$allowEdit = array(1,2,3);
			$allowDelete = array(1,2,3);
		    if(in_array($_SESSION['user']['access'], $allowEdit)){
			$edit = '<a onclick="javascript:link_ajax(\''.BASE_URL.'pages/sales_online/trolnso.php?action=posting&id='.$line['id_trans'].'\',\'table_jual\')" href="javascript:;">Posting</a>';
			}
			else
				$edit = '<a onclick="javascript:custom_alert(\'Tidak Boleh Posting Data\')" href="javascript:;">Posting</a>';
			
			if(in_array($_SESSION['user']['access'], $allowDelete))
				$delete = '<a onclick="javascript:link_ajax(\''.BASE_URL.'pages/sales_online/trolnso.php?action=delete&id='.$line['id_trans'].'\',\'table_jual\')" href="javascript:;">Cancel</a>';
			else
				$delete = '<a onclick="javascript:custom_alert(\'Tidak Boleh dibatalkan\')" href="javascript:;">Delete</a>';
			
			    //$select = '<input type="checkbox" class="chkPrint" name="select"  value='.$line['id_trans'].'>';
			
        	$responce['rows'][$i]['id']   = $line['id_trans'];
            $responce['rows'][$i]['cell'] = array(
                $line['id_trans'],
                $line['ref_kode'],                
                $line['dropshipper'],                
                $line['tgl_trans'],
                $line['nama'],
                $line['alamat'],
                number_format($line['exp_fee'],0),
                $line['expedition'],
                $line['exp_code'],
				number_format($line['totalqty'],0),
				$edit,
				$delete,
			//	$select,
            );
			$grand_qty+=$line['totalqty'];
			$grand_faktur+=$line['faktur'];
			$grand_totalfaktur+=$line['total'];
			$grand_piutang+=$line['piutang'];
			$grand_tunai+=$line['tunai'];
			$grand_transfer+=$line['transfer'];
			$grand_biaya+=$line['exp_fee'];
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
		$stmt = $db->prepare("Update olnso set state='1',lastmodified=now() WHERE id_trans=?");
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
		//delete olndeposit krn void invoice		
		$stmt = $db->prepare("delete from olndeposit WHERE id_trans=?");
		$stmt->execute(array($_GET['id']));
		
		//update troln agar jadi nol krn void invoice dan dimasukan ke archive state=3
		$stmt = $db->prepare("Update olnso set total=0,exp_fee=0,faktur=0,totalqty=0,tunai=0,transfer=0,deposit=0,piutang=0,pelunasan=0,deleted=1,ref_kode='',exp_code='',state='0' WHERE id_trans=?");
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
		//$id = $line['id_trans'];
		$where = "WHERE pd.id_trans = '".$id."' ";
        $q = $db->query("SELECT pd.* FROM `olnsodetail` pd ".$where);
		
		$count = $q->rowCount();
		
		//$q = $db->query("SELECT pd.id_detail,pd.id_barang,b.nm_barang,b.kode_brg,pd.id_trans,pd.qty,pd.harga,(pd.qty * pd.harga) as subtotal FROM `trjual_detail` pd INNER JOIN `barang` b ON (pd.kode_brg=b.kode_brg) ".$where);
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

<div class="btn_box">
 <a href="javascript: void(0)" 
   onclick="window.open('pages/sales_online/trolnso_detail.php');">
   <button class="btn btn-success">Add</button></a>   
</div>
 
<table id="table_jual"></table>
<div id="pager_table_jual"></div>

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
        $("#table_jual").jqGrid({
            url:'<?php echo BASE_URL.'pages/sales_online/trolnso.php?action=json'; ?>',
            datatype: "json",
            colNames:['ID','ID_web','Dropshipper','Date','Receiver','Address','Exp.Fee','Expedition','Exp.Code','Qty','Posting','Cancel'],
            colModel:[
                {name:'id_trans',index:'id_trans', width:40, search:true, stype:'text', searchoptions:{sopt:['cn']}},
                {name:'ref_kode',index:'ref_kode', width:25, search:true, stype:'text', searchoptions:{sopt:['cn']}},
                {name:'j.nama',index:'j.nama', width:40, searchoptions: {sopt:['cn']}},                
                {name:'tgl_trans',index:'tgl_trans', width:35, searchoptions: {sopt:['cn']},formatter:"date", formatoptions:{srcformat:"Y-m-d", newformat:"d/m/Y"}, align:'center'},
                {name:'p.nama',index:'p.nama', align:'left', width:60, searchoptions: {sopt:['cn']}},
                {name:'alamat',index:'alamat', align:'left', width:100, searchoptions: {sopt:['cn']}},
                {name:'exp_fee',index:'exp_fee', align:'right', width:20, searchoptions: {sopt:['cn']}},
				{name:'e.nama',index:'e.nama', align:'left', width:35, searchoptions: {sopt:['cn']}},
				{name:'exp_code',index:'exp.code', align:'left', width:35, searchoptions: {sopt:['cn']}},
                {name:'totalqty',index:'totalqty', align:'right', width:20, searchoptions: {sopt:['cn']}},
                {name:'edit',index:'edit', align:'center', width:25, sortable: false, search: false},
                {name:'delete',index:'delete', align:'center', width:25, sortable: false, search: false},
            ],
            rowNum:20,
            rowList:[10,20,30],
            pager: '#pager_table_jual',
            sortname: 'id_trans',
            autowidth: true,
	        height: '300',
            viewrecords: true,
            rownumbers: true,
            sortorder: "desc",
            caption:"Data Penjualan Online",
            ondblClickRow: function(rowid) {
                alert(rowid);
            },
			footerrow : true,
			userDataOnFooter : true,
            subGrid : true,
            subGridUrl : '<?php echo BASE_URL.'pages/sales_online/trolnso.php?action=json_sub'; ?>',
            subGridModel: [
			            	{ 
			            		name : ['No','Kode','Barang','Size','Harga','Qty(pcs)','Subtotal'], 
			            		width : [40,40,300,30,50,50,50],
			            		align : ['right','center','left','center','right','right','right'],
			            	} 
			            ],
						
            
        });
        $("#table_jual").jqGrid('navGrid','#pager_table_jual',{edit:false,add:false,del:false,search:true});
		

		
		// $("#checkAll").click(function () {
			// $(".chkPrint").prop('checked', $(this).prop('checked'));
		// });
    })
</script>