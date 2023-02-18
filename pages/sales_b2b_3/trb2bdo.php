<?php require_once '../../include/config.php' ?>
<?php
$group_acess = unserialize(file_get_contents("../../GROUP_ACCESS_CACHE".$_SESSION['user']['group_id']));
$allow_delete = is_show_menu(DELETE_POLICY, DeliveryOrderB2B, $group_acess);


	if(isset($_GET['action']) && strtolower($_GET['action']) == 'json') {
		$page  = $_GET['page'];
        $limit = $_GET['rows'];
        $sidx  = $_GET['sidx'];
        $sord  = $_GET['sord'];
		
		$startdate = isset($_GET['startdate_b2bdo'])?$_GET['startdate_b2bdo']:date('Y-m-d');
		$enddate = isset($_GET['enddate_b2bdo'])?$_GET['enddate_b2bdo']:date('Y-m-d'); 
        $filter=$_GET['filter'];
		
        $page = isset($_GET['page'])?$_GET['page']:1; // get the requested page
        $limit = isset($_GET['rows'])?$_GET['rows']:10; // get how many rows we want to have into the grid
        $sidx = isset($_GET['sidx'])?$_GET['sidx']:'tgl_trans'; // get index row - i.e. user click to sort
        $sord = isset($_GET['sord'])?$_GET['sord']:''; 
	
        //p.state = '1' artinya siap kirim";
        $where = "WHERE TRUE AND do.state = '0' AND do.deleted=0";
		
		if(($startdate != null) && ($filter != null)) {
			$where .= " AND DATE(do.tgl_trans) BETWEEN STR_TO_DATE('$startdate','%d/%m/%Y') AND STR_TO_DATE('$enddate','%d/%m/%Y') AND ((c.nama like '%$filter%') or (e.nama like '%$filter%') or (s.nama like '%$filter%') or (do.exp_code like '%$filter%'))";	
		}	
		else
		{
		$where .=" AND DATE(do.tgl_trans) BETWEEN STR_TO_DATE('$startdate','%d/%m/%Y') AND STR_TO_DATE('$enddate','%d/%m/%Y')";
		}
		$sql = "SELECT do.*,so.nama,so.alamat,c.nama AS customer,e.nama AS expedition,s.nama AS salesman FROM `b2bdo` do LEFT JOIN `b2bso` so on do.id_transb2bso=so.id_trans LEFT JOIN `mst_b2bcustomer` c ON (do.id_customer=c.id) LEFT JOIN `mst_b2bexpedition` e ON (do.id_expedition=e.id) LEFT JOIN `mst_b2bsalesman` s ON (do.id_salesman=s.id)".$where;
        //var_dump($sql);
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
        	if ($statusToko == 'Tutup') {
                $edit = '<a onclick="javascript:custom_alert(\'Maaf, Toko Sudah Tutup\')" href="javascript:;">Edit Edit</a>';
                $invoice = '<a onclick="javascript:custom_alert(\'Maaf, Toko Sudah Tutup\')" href="javascript:;">Invoice</a>';
                $delete = '<a onclick="javascript:custom_alert(\'Maaf, Toko Sudah Tutup\')" href="javascript:;">Cancel</a>';
            } else {
			// $allowInvoice = array(1,2,3);
			// $allowEdit = array(1,2,3);
			// $allowDelete = array(1,2,3);
			
			
			$edit = '<a onclick="javascript:popup_form(\''.BASE_URL.'pages/sales_b2b/trb2bdo.php?action=edit&id='.$line['id_trans'].'\',\'table_b2bdo\')" href="javascript:;">Edit</a>';
		    // if($allow_print){
			//$invoice = '<a onclick="javascript:custom_alert(\'Under Construction\')" href="javascript:;">Invoice</a>';
			$invoice = '<a onclick="window.open(\''.BASE_URL.'pages/sales_b2b/trb2bdo_invoice.php?id_trans='.$line['id_trans'].'\',\'table_b2bdo\')" href="javascript:;">Invoice</a>';
			// }
			// else
			// 	$invoice = '<a onclick="javascript:custom_alert(\'Tidak Boleh Print Nota\')" href="javascript:;">Edit</a>';
			
			// if(in_array($_SESSION['user']['access'], $allowEdit)){
			// $edit = '<a onclick="javascript:custom_alert(\'Under Construction\')" href="javascript:;">Label</a>';
			// $edit = '<a onclick="window.open(\''.BASE_URL.'pages/sales_online/trolnso_nota.php?id_trans='.$line['id_trans'].'\',\'table_jualdo\')" href="javascript:;">Label</a>';
			// }
			// else
			// 	$edit = '<a onclick="javascript:custom_alert(\'Tidak Boleh Print Invoice\')" href="javascript:;">Edit</a>';
			
			if($allow_delete)
				$delete = '<a onclick="javascript:link_ajax(\''.BASE_URL.'pages/sales_b2b/trb2bdo.php?action=delete&id='.$line['id_trans'].'\',\'table_b2bdo\')" href="javascript:;">Cancel</a>';
			
			else
				$delete = '<a onclick="javascript:custom_alert(\'Tidak Boleh dibatalkan\')" href="javascript:;">Cancel</a>';
			
			//     $select = '<input type="checkbox" class="chkPrint" name="select"  value='.$line['id_trans'].'>';
			}
        	$responce['rows'][$i]['id']   = $line['id_trans'];
            $responce['rows'][$i]['cell'] = array(
                $line['id_trans'],
                $line['customer'],                
                $line['salesman'],                
                $line['tgl_trans'],
                $line['alamat'],
                $line['expedition'],
                $line['exp_code'],
                number_format($line['totalkirim'],0),
                number_format($line['exp_fee'],0),
                number_format($line['faktur'],0),
                number_format($line['totalfaktur'],0),
				$edit,
				$invoice,
				$delete,
			);
			
			$grand_qty+=$line['totalkirim'];
			$grand_faktur+=$line['faktur'];
			$grand_totalfaktur+=$line['totalfaktur'];
			/*
			$grand_piutang+=$line['piutang'];
			$grand_tunai+=$line['tunai'];
			$grand_transfer+=$line['transfer'];
			$grand_biaya+=$line['exp_fee'];
			*/
            $i++;
        }
		
		$responce['userdata']['totalkirim'] 	= number_format($grand_qty,0);
		$responce['userdata']['faktur'] 		= number_format($grand_faktur,0);
		$responce['userdata']['totalfaktur'] 	= number_format( $grand_totalfaktur,0);
		/*
		$responce['userdata']['piutang'] 		= number_format($grand_piutang,0);
		$responce['userdata']['tunai'] 			= number_format($grand_tunai,0);
		$responce['userdata']['transfer']		= number_format($grand_transfer,0);
		$responce['userdata']['exp_fee'] 			= number_format($grand_biaya,0);
        */
		echo json_encode($responce);
		
		exit;
	}
	elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'edit') {
		include 'trb2bdo_form.php';exit();
		exit;
	}
	elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'delete') {
		//get id b2bso
		$idb2bso='';
		$totalfaktur = 0;
		$totalkirim = 0;
		$qry = $db->prepare("SELECT id_transb2bso,totalfaktur,totalkirim FROM `b2bdo` where id_trans='".$_GET['id']."' ");
        $qry->execute();
        $id = $qry->fetchAll();
        foreach ($id as $ids) {
            $idb2bso = $ids['id_transb2bso'];
            $totalfaktur = $ids['totalfaktur'];
            $totalkirim = $ids['totalkirim'];
        }

        //update b2bso
		$stmt = $db->prepare("Update b2bso set totalkirim=totalkirim-".$totalkirim.", lastmodified=NOW() WHERE id_trans=?");
		$stmt->execute(array($idb2bso));
		
		//update b2bso detail
		$qry = $db->prepare("SELECT * FROM `b2bdo_detail` where id_trans='".$_GET['id']."' ");
        $qry->execute();
        $id = $qry->fetchAll();
        foreach ($id as $ids) {
		   $totalqty = 0;
		   $id_product =  $ids['id_product'];
           $qty31 = $ids['qty31'];
           $qty32 = $ids['qty32'];
           $qty33 = $ids['qty33'];
           $qty34 = $ids['qty34'];
           $qty35 = $ids['qty35'];
           $qty36 = $ids['qty36'];
           $qty37 = $ids['qty37'];
           $qty38 = $ids['qty38'];
           $qty39 = $ids['qty39'];
           $qty40 = $ids['qty40'];
           $qty41 = $ids['qty41'];
           $qty42 = $ids['qty42'];
           $qty43 = $ids['qty43'];
           $qty44 = $ids['qty44'];
           $qty45 = $ids['qty45'];
           $qty46 = $ids['qty46'];

		   $totalqty = $qty31 + $qty32 + $qty33 + $qty34 + $qty35 + $qty36 + $qty37 + $qty38 + $qty39 + $qty40 + $qty41 + $qty41 + $qty42 + $qty43 + $qty44 + $qty45 + $qty46;
		   
		   $stmt = $db->prepare("UPDATE `b2bso_detail` SET `kirim31`=kirim31-".$qty31.",`kirim32`=kirim32-".$qty32.",`kirim33`=kirim33-".$qty33.",`kirim34`=kirim34-".$qty34.",`kirim35`=kirim35-".$qty35.",`kirim36`=kirim36-".$qty36.",`kirim37`=kirim37-".$qty37.",`kirim38`=kirim38-".$qty38.",`kirim39`=kirim39-".$qty39.",`kirim40`=kirim40-".$qty40.",`kirim41`=kirim41-".$qty41.",`kirim42`=kirim42-".$qty42.",`kirim43`=kirim43-".$qty43.",`kirim44`=kirim44-".$qty44.",`kirim45`=kirim45-".$qty45.",`kirim46`=kirim46-".$qty46.",`jumlah_kirim`=jumlah_kirim-".$totalqty." WHERE `id_product`=? AND `id_trans`=?");

			$stmt->execute(array($id_product, $idb2bso));
        }

		//update b2bdo agar jadi nol krn void invoice
		$stmt = $db->prepare("Update b2bdo set tunai=0,transfer=0,giro=0,faktur=0,totalfaktur=0,piutang=0,totalkirim=0,exp_fee=0, deleted=1 WHERE id_trans=?");
		$stmt->execute(array($_GET['id']));

		//update b2bdo_detail agar jadi nol krn void invoice
		$stmt = $db->prepare("update b2bdo_detail set jumlah_beli=0,jumlah_kirim=0,harga_satuan=0,subtotal=0,qty36=0,qty37=0,qty38=0,qty39=0,qty40=0,qty41=0,qty42=0,qty43=0,qty44=0,qty45=0,qty46=0 WHERE id_trans=?");
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
	elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'process') {
		if(isset($_POST['id'])) {
            $stmt = $db->prepare("UPDATE b2bdo SET `note`=?,user=?, lastmodified = NOW() WHERE id_trans=?");
            $stmt->execute(array($_POST['keterangan'], $_SESSION['user']['username'], $_POST['id']));
                
            $affected_rows = $stmt->rowCount();
            if($affected_rows > 0) {
                $r['stat'] = 1;
                $r['message'] = 'Success';
            }
            else {
                $r['stat'] = 0;
                $r['message'] = 'Failed';
            
			}
		}
		echo json_encode($r);
		exit;
	}
	elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'json_sub') {
	
		$id = $_GET['id'];
		//$id = $line['id_trans'];
		$where = "WHERE pd.id_trans = '".$id."' ";
        $q = $db->query("SELECT pd.* FROM `b2bdo_detail` pd ".$where);
		
		$count = $q->rowCount();
		
		$data1 = $q->fetchAll(PDO::FETCH_ASSOC);
		
        $i=0;
        $responce = '';
        $sizenew='';
		$count=1;
		$barangnya='';
        foreach($data1 as $line){
        				if ($barangnya != $line['id_product']) {
				$count=1;
			}
				if ($line['qty31'] != '0') {
					if ($count == 1) {
						$sizenew = '31'.'('.$line['qty31'].')';
					}else{					
						$sizenew = $sizenew.', 31('.$line['qty31'].')';
					}
					$count++;
				}

				if ($line['qty32'] != '0') {
					if ($count == 1) {
						$sizenew = '32'.'('.$line['qty32'].')';
					}else{
						$sizenew = $sizenew.', 32('.$line['qty32'].')';
					}
					$count++;
				}

				if ($line['qty33'] != '0') {
					if ($count == 1) {
						$sizenew = '33'.'('.$line['qty33'].')';
					}else{
						$sizenew = $sizenew.', 33('.$line['qty33'].')';
					}
					$count++;
				}

				if ($line['qty34'] != '0') {
					if ($count == 1) {
						$sizenew = '34'.'('.$line['qty34'].')';
					}else{
						$sizenew = $sizenew.', 34('.$line['qty34'].')';
					}
					$count++;
				}

				if ($line['qty35'] != '0') {
					if ($count == 1) {
						$sizenew = '35'.'('.$line['qty35'].')';
					}else{
						$sizenew = $sizenew.', 35('.$line['qty35'].')';
					}
					$count++;
				}

				if ($line['qty36'] != '0') {
					if ($count == 1) {
						$sizenew = '36'.'('.$line['qty36'].')';
					}else{
						$sizenew = $sizenew.', 36('.$line['qty36'].')';
					}
					$count++;
				}

				if ($line['qty37'] != '0') {
					if ($count == 1) {
						$sizenew = '37'.'('.$line['qty37'].')';
					}else{
						$sizenew = $sizenew.', 37('.$line['qty37'].')';
					}
					$count++;
				}

				if ($line['qty38'] != '0') {
					if ($count == 1) {
						$sizenew = '38'.'('.$line['qty38'].')';
					}else{
						$sizenew = $sizenew.', 38('.$line['qty38'].')';
					}
					$count++;
				}

				if ($line['qty39'] != '0') {
					if ($count == 1) {
						$sizenew = '39'.'('.$line['qty39'].')';
					}else{
						$sizenew = $sizenew.', 39('.$line['qty39'].')';
					}
					$count++;
				}

				if ($line['qty40'] != '0') {
					if ($count == 1) {
						$sizenew = '40'.'('.$line['qty40'].')';
					}else{
						$sizenew = $sizenew.', 40('.$line['qty40'].')';
					}
					$count++;
				}

				if ($line['qty41'] != '0') {
					if ($count == 1) {
						$sizenew = '41'.'('.$line['qty41'].')';
					}else{
						$sizenew = $sizenew.', 41('.$line['qty41'].')';
					}
					$count++;
				}

				if ($line['qty42'] != '0') {
					if ($count == 1) {
						$sizenew = '42'.'('.$line['qty42'].')';
					}else{
						$sizenew = $sizenew.', 42('.$line['qty42'].')';
					}
					$count++;
				}

				if ($line['qty43'] != '0') {
					if ($count == 1) {
						$sizenew = '43'.'('.$line['qty43'].')';
					}else{
						$sizenew = $sizenew.', 43('.$line['qty43'].')';
					}
					$count++;
				}

				if ($line['qty44'] != '0') {
					if ($count == 1) {
						$sizenew = '44'.'('.$line['qty44'].')';
					}else{
						$sizenew = $sizenew.', 44('.$line['qty44'].')';
					}
					$count++;
				}

				if ($line['qty45'] != '0') {
					if ($count == 1) {
						$sizenew = '45'.'('.$line['qty45'].')';
					}else{
						$sizenew = $sizenew.', 45('.$line['qty45'].')';
					}
					$count++;
				}

				if ($line['qty46'] != '0') {
					if ($count == 1) {
						$sizenew = '46'.'('.$line['qty46'].')';
					}else{
						$sizenew = $sizenew.', 46('.$line['qty46'].')';
					}
					$count++;
				}

            $responce->rows[$i]['id']   = $line['b2bdo_id'];
            $responce->rows[$i]['cell'] = array(
                $i+1,
                $line['id_product'],
                $line['namabrg'],
                $sizenew,
                // number_format($line['qty31'],0),
                // number_format($line['qty32'],0),
                // number_format($line['qty33'],0),
                // number_format($line['qty34'],0),
                // number_format($line['qty35'],0),
                // number_format($line['qty36'],0),
                // number_format($line['qty37'],0),
                // number_format($line['qty38'],0),
                // number_format($line['qty39'],0),
                // number_format($line['qty40'],0),
                // number_format($line['qty41'],0),
                // number_format($line['qty42'],0),
                // number_format($line['qty43'],0),
                // number_format($line['qty44'],0),
                // number_format($line['qty45'],0),
                // number_format($line['qty46'],0),
                 number_format($line['harga_satuan'],0),
                 number_format($line['jumlah_kirim'],0),                
                 number_format(($line['harga_satuan']*$line['jumlah_kirim']),0),                
            );
            $barangnya = $line['id_product'];
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
        	<label for="project_id" class="ui-helper-reset label-control">Tanggal</label>
            <div class="ui-corner-all form-control">
            	<table>
				<tr>
				<td>
				 <input value="" type="text" class="required datepicker"   id="startdate_b2bdo" name="startdate_b2bdo">
				</td>
				<td> s.d.  
				<input value="" type="text" class="required datepicker"  id="enddate_b2bdo" name="enddate_b2bdo">
				</td>
				<td> Filter
				 <input value="" type="text" id="filterb2bdo" name="filterb2bdo">(Customer,Salesman,Expedition,Exp_Code)
				</td>
				</tr>
				</table>
            </div>
            <label for="" class="ui-helper-reset label-control">&nbsp;</label>
            <div class="ui-corner-all form-control">
            	<button onclick="gridReloadB2BDO()" class="btn" type="button">Cari</button>
				
            </div>
       	</form>
   	</div>
</div>
	<div class="btn_box">
	<!--
 <a href="javascript: void(0)" 
   onclick="window.open('pages/sales_online/trolnso_detail.php');">
   <button class="btn btn-success">Add</button></a>   
   -->
 <!-- <span class="file btn btn-success" id="add_trolnso" rel="<php echo BASE_URL ?>pages/sales_online/trolnso_detail_new.php"> Add Online Sales</span>
<button id="btn-xlsdo"  class="btn btn-success">XLS Selected</button>
<button id="btn-print"  class="btn btn-success">Print Selected Label</button>
 -->
</div>
 
<table id="table_b2bdo"></table>
<div id="pager_table_b2bdo"></div>

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
	 
	$('#startdate_b2bdo').datepicker({
		dateFormat: "dd/mm/yy"
	});
	$('#enddate_b2bdo').datepicker({
		dateFormat: "dd/mm/yy"
	});
	$( "#startdate_b2bdo" ).datepicker( 'setDate', '<?php echo date('d/m/Y')?>' );
	$( "#enddate_b2bdo" ).datepicker( 'setDate', '<?php echo date('d/m/Y')?>' );
	
	
	function gridReloadB2BDO(){
		var startdate_b2bdo = $("#startdate_b2bdo").val();
		var enddate_b2bdo = $("#enddate_b2bdo").val();
		var filter_b2bdo = $("#filterb2bdo").val();
		var v_url ='<?php echo BASE_URL?>pages/sales_b2b/trb2bdo.php?action=json&startdate_b2bdo='+startdate_b2bdo+'&enddate_b2bdo='+enddate_b2bdo+'&filter='+filter_b2bdo;
		jQuery("#table_b2bdo").setGridParam({url:v_url,page:1}).trigger("reloadGrid");
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
			
		// d = $("#startdate_jual").datepicker("getDate");
		// $("#startdate_jual").datepicker("setDate", new Date(d.getFullYear()+1,d.getMonth(),d.getDate()));
		//alert('kdie');
        $("#table_b2bdo").jqGrid({
            url:'<?php echo BASE_URL.'pages/sales_b2b/trb2bdo.php?action=json'; ?>',
            /*postData: {
                'title': function() {return $('#sJudul').val(); },
                'sales_id': function() {return $('#sSales_id').val(); },
                'Name': function() {return $('#sCustomFer').val(); },
                'summary_status': function() {return $('#sStatus').val(); },
            },*/
            datatype: "json",
            //colNames:['ID','Customer','Tanggal Transaksi','Qty','Faktur','Ongkos Kuli','Total Faktur','Tunai','Bank','View','Delete'],
            colNames:['ID','Customer','Salesman','Date','Address','Expedition','Exp.Code','Qty','Exp_fee','Faktur','Total','Exp. Note','Invoice','Cancel'],
            colModel:[
                {name:'id_trans',index:'id_trans', width:20, search:true, stype:'text', searchoptions:{sopt:['cn']}},
                {name:'customer',index:'customer', align:'left', width:60, search:true, stype:'text', searchoptions:{sopt:['cn']}},
                {name:'salesman',index:'salesman', width:40, searchoptions: {sopt:['cn']}},                
                {name:'tgl_trans',index:'tgl_trans', width:35, searchoptions: {sopt:['cn']},formatter:"date", formatoptions:{srcformat:"Y-m-d", newformat:"d/m/Y"}, align:'center'},
                {name:'alamat',index:'alamat', align:'left', width:80, searchoptions: {sopt:['cn']}},
                {name:'expedition',index:'expedition', align:'left', width:50, searchoptions: {sopt:['cn']}},
                {name:'exp_code',index:'exp_code', align:'left', width:40, searchoptions: {sopt:['cn']}},
                {name:'totalkirim',index:'totalkirim', align:'right', width:18, searchoptions: {sopt:['cn']}},
                {name:'exp_fee',index:'exp_fee', align:'right', width:25, searchoptions: {sopt:['cn']}},
                {name:'faktur',index:'faktur', align:'right', width:30, searchoptions: {sopt:['cn']}},
                {name:'totalfaktur',index:'totalfaktur', align:'right', width:30, searchoptions: {sopt:['cn']}},
                {name:'edit',index:'edit', align:'center', width:25, sortable: false, search: false},
                {name:'invoice',index:'invoice', align:'center', width:25, sortable: false, search: false},
				{name:'delete',index:'delete', align:'center', width:25, sortable: false, search: false},
                
            ],
            rowNum:1000,
            rowList:[10,20,30,100,1000,10000],
            pager: '#pager_table_b2bdo',
            sortname: 'id_trans',
            autowidth: true,
			multiselect:false,
            height: '300',
            viewrecords: true,
            rownumbers: true,
            sortorder: "desc",
            caption:"B2B Order Delivery",
            ondblClickRow: function(rowid) {
                alert(rowid);
            },
			footerrow : true,
			userDataOnFooter : true,
            subGrid : true,
            subGridUrl : '<?php echo BASE_URL.'pages/sales_b2b/trb2bdo.php?action=json_sub'; ?>',
            subGridModel: [
			            	{ 
			            		name : ['No','Kode','Barang','Size','Harga','Qty(pcs)','Subtotal'], 
			            		width : [40,40,300,300,50,50,50,50],
			            		align : ['right','center','left','left','right','right','right'],
			            	} 
			            ],
						
            
        });
        $("#table_b2bdo").jqGrid('navGrid','#pager_table_b2bdo',{edit:false,add:false,del:false,search:false});
		

		
		// $("#checkAll").click(function () {
			// $(".chkPrint").prop('checked', $(this).prop('checked'));
		// });
    })
</script>