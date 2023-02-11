<?php require_once '../../include/config.php' ?>
<?php
	if(isset($_GET['action']) && strtolower($_GET['action']) == 'json') {
		$page  = $_GET['page'];
        $limit = $_GET['rows'];
        $sidx  = $_GET['sidx'];
        $sord  = $_GET['sord'];
		
		$startdate = isset($_GET['startdate_jualpb'])?$_GET['startdate_jualpb']:date('Y-m-d');
		$enddate = isset($_GET['enddate_jualpb'])?$_GET['enddate_jualpb']:date('Y-m-d'); 

        $page = isset($_GET['page'])?$_GET['page']:1; // get the requested page
        $limit = isset($_GET['rows'])?$_GET['rows']:10; // get how many rows we want to have into the grid
        $sidx = isset($_GET['sidx'])?$_GET['sidx']:'tgl_trans'; // get index row - i.e. user click to sort
        $sord = isset($_GET['sord'])?$_GET['sord']:''; 
	
      // $value = $_REQUEST["searchString"];
	  // $where = sprintf(" where (p.deleted=0) AND %s ".$operations[$_REQUEST["searchOper"]], $_REQUEST["searchField"], $value);
	 //echo"<script>alert('where=$where')</script>";
     // }
        $where = "WHERE TRUE AND p.deleted = 0 ";
        //$where = "WHERE TRUE ";
		
		if($startdate != null){
			$where .= "AND p.id_supplier <> 0 AND DATE(tgl_trans) BETWEEN STR_TO_DATE('$startdate','%d/%m/%Y')   AND STR_TO_DATE('$enddate','%d/%m/%Y')";
			// $where .= " AND DATE_FORMAT(tgl_trans,'%d/%m/%Y') BETWEEN '$startdate' AND '$enddate'";
		}	
		
	//	$sql = "SELECT * FROM `trjual` p Left Join `tblpelanggan` j on (p.id_customer=j.id) ".$where;
		$sql = "SELECT p.id_trans,p.tgl_trans,p.kode,p.faktur_murni,p.totalfaktur,p.totalqty,p.biaya,p.piutang,p.transfer,p.tunai,p.kartu,j.namaperusahaan FROM `trbeli` p Left Join `tblsupplier` j on (p.id_supplier=j.id) ".$where;
        $q = $db->query($sql);
		$count = $q->rowCount();
        //var_dump($sql);die;
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
        	//var_dump($line);die;
			$konversi=30;
			$allowNota = array(1,2,3);
			$allowEdit = array(1,2,3);
			$allowDelete = array(1,2,3);
		    if(in_array($_SESSION['user']['access'], $allowNota)){
			$nota = '<a onclick="window.open(\''.BASE_URL.'pages/pabrik/jual_pabrik_notajns.php?id_trans='.$line['id_trans'].'\',\'table_jualpb\')" href="javascript:;">Nota</a>';
			}
			else
				$nota = '<a onclick="javascript:custom_alert(\'Tidak Boleh Print Nota\')" href="javascript:;">Nota</a>';
			
			if(in_array($_SESSION['user']['access'], $allowEdit)){
			$edit = '<a onclick="window.open(\''.BASE_URL.'pages/pabrik/jual_pabrik_edit.php?ids='.$line['id_trans'].'\',\'table_jualpb\')" href="javascript:;">Edit</a>';
			}
			else
				$edit = '<a onclick="javascript:custom_alert(\'Tidak Boleh Print Nota\')" href="javascript:;">Edit</a>';
			
			/*
			if(in_array($_SESSION['user']['access'], $allowDelete))
				$delete = '<a onclick="javascript:link_ajax(\''.BASE_URL.'pages/pabrik/jual_pabrik.php?action=delete&id='.$line['id_trans'].'\',\'table_jualpb\')" href="javascript:;">BATAL</a>';
			else
				$delete = '<a onclick="javascript:custom_alert(\'Tidak Bole dibatalkan\')" href="javascript:;">Delete</a>';
			*/	
        	$responce['rows'][$i]['id']   = $line['id_trans'];
            $responce['rows'][$i]['cell'] = array(
                $line['id_trans'],
                $line['kode'],                
                $line['namaperusahaan'],                
                $line['tgl_trans'],
                number_format($line['totalqty'],0),
				number_format($line['totalqty']*$konversi,0),
				number_format($line['faktur_murni']*$konversi,0),
				number_format($line['totalfaktur']*$konversi*1.11,0),
				number_format($line['transfer']*$konversi*1.11,0),
				number_format($line['tunai']*$konversi*1.11,0),
				number_format($line['piutang']*$konversi*1.11,0),
				$nota,
				$edit,
            );
			$grand_qty+=$line['totalqty'];
			$grand_faktur+=$line['faktur_murni']*$konversi;
			$grand_totalfaktur+=$line['totalfaktur']*$konversi;
			$grand_piutang+=$line['piutang']*$konversi;
			$grand_tunai+=$line['tunai']*$konversi;
			$grand_transfer+=$line['transfer']*$konversi;
			$grand_biaya+=$line['biaya'];
            $i++;
        }
		$responce['userdata']['totalqty'] 		= number_format($grand_qty,0);
		$responce['userdata']['faktur_murni'] 	= number_format($grand_faktur,0);
		$responce['userdata']['biaya'] 			= number_format($grand_biaya,0);
        $responce['userdata']['totalfaktur'] 	= number_format( $grand_totalfaktur,0);
		$responce['userdata']['tunai'] 			= number_format($grand_tunai,0);
		$responce['userdata']['transfer']		= number_format($grand_transfer,0);
		$responce['userdata']['piutang'] 		= number_format($grand_piutang,0);
		echo json_encode($responce);
		exit;
	}
	elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'delete') {
		//update trjual agar jadi nol krn void invoice
		$stmt = $db->prepare("Update trbeli set totalfaktur=0,biaya=0,faktur=0,totalqty=0,tunai=0,transfer=0,kartu=0,deposit=0,piutang=0,pelunasan=0 WHERE id_trans=?");
		$stmt->execute(array($_GET['id']));
		//update trbeli_detail agar jadi nol krn void invoice
		$stmt = $db->prepare("update trbeli_detail set qty=0,harga=0 WHERE id_trans=?");
		$stmt->execute(array($_GET['id']));
		/*
		$stmt = $db->prepare("delete from trjual_detail WHERE id_trans=?");
		$stmt->execute(array($_GET['id']));
		*/
		//update trjual_print agar jadi nol krn void invoice
		//$stmt = $db->prepare("Update trjual_print set kuantum=0,harga=0,harga_plus_ppn=0 WHERE id_trans=?");
		//$stmt->execute(array($_GET['id']));
		/*
		$stmt = $db->prepare("delete from trjual_print WHERE id_trans=?");
		$stmt->execute(array($_GET['id']));
		*/
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
        //$sql_sub="SELECT pd.id_detail,pd.id_barang,b.nm_barang,pd.id_trans,pd.qty,pd.harga,(pd.qty * pd.harga) as subtotal FROM `trbeli_detail` pd INNER JOIN `barang` b ON (pd.id_barang=b.id_barang) ".$where;
		//var_dump($sql_sub); die;
		//$where = "WHERE pd.id_trans = '".$id."' ";
        $q = $db->query("SELECT jb.nm_jenis,jb.hrg_yard, pd.id_detail,pd.id_barang,b.nm_barang,pd.id_trans,pd.qty,(pd.qty*30) as yard,pd.harga,(pd.qty * pd.harga*30) as subtotal FROM `trbeli_detail` pd INNER JOIN `barang` b ON (pd.id_barang=b.id_barang) LEFT JOIN `jenis_barang` jb ON (pd.id_jenis=jb.id_jenis) ".$where);
        //$q = $db->query($sql_sub);
		
		$count = $q->rowCount();
		
		$q = $db->query("SELECT jb.nm_jenis,jb.hrg_yard, pd.id_detail,pd.id_barang,b.nm_barang,b.kode_brg,pd.id_trans,pd.qty,(pd.qty*30) as yard,pd.harga,(pd.qty * jb.hrg_yard*30) as subtotal FROM `trbeli_detail` pd INNER JOIN `barang` b ON (pd.id_barang=b.id_barang) LEFT JOIN `jenis_barang` jb ON (pd.id_jenis=jb.id_jenis) ".$where);
		//$q = $db->query($sql_sub);
		$data1 = $q->fetchAll(PDO::FETCH_ASSOC);
		
        $i=0;
        $responce = '';
        foreach($data1 as $line){
            $responce->rows[$i]['id']   = $line['id_detail'];
            $responce->rows[$i]['cell'] = array(
                $i+1,
                $line['kode_brg'],
                $line['nm_barang'],
                $line['nm_jenis'],
                 number_format($line['hrg_yard'],0),
                 number_format($line['qty'],0),                
                 number_format($line['yard'],0),                
                 number_format($line['subtotal'],0),                
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
        	<label for="project_id" class="ui-helper-reset label-control">Tanggal</label>
            <div class="ui-corner-all form-control">
            	<table>
				<tr>
				<td>
				 <input value="" type="text" class="required datepicker"   id="startdate_jualpb" name="startdate_jualpb">
				</td>
				<td> s.d.  
				<input value="" type="text" class="required datepicker"  id="enddate_jualpb" name="enddate_jualpb">
				</td>
				</tr>
				</table>
            </div>
            <label for="" class="ui-helper-reset label-control">&nbsp;</label>
            <div class="ui-corner-all form-control">
            	<button onclick="gridReloadJual()" class="btn" type="button">Cari</button>
            </div>
       	</form>
   	</div>
</div>
	
<table id="table_jualpb"></table>
<div id="pager_table_jualpb"></div>
<div class="btn_box">
<!--
<a href="javascript: void(0)" 
   onclick="window.open('pages/jual/jual_detail.php', 
  'windowname1', 
  'width=1000, height=400,scrollbars=yes'); 
   return false;">
   <button class="btn btn-success">Tambah</button></a>
   
</br>
-->
<?php
	/*
	$allow = array(1,2,3);
	if(in_array($_SESSION['user']['access'], $allow)) {
		echo '<button type="button" onclick="javascript:popup_form(\''.BASE_URL.'pages/beli.php?action=add\',\'table_beli\')" class="btn">Tambah</button>';
		
	}
	*/
?>
</div>
<script type="text/javascript">
	 
	$('#startdate_jualpb').datepicker({
		dateFormat: "dd/mm/yy"
	});
	$('#enddate_jualpb').datepicker({
		dateFormat: "dd/mm/yy"
	});
	$( "#startdate_jualpb" ).datepicker( 'setDate', '<?php echo date('d/m/Y')?>' );
	$( "#enddate_jualpb" ).datepicker( 'setDate', '<?php echo date('d/m/Y')?>' );
	
	
	function gridReloadJual(){
		var startdate_jualpb = $("#startdate_jualpb").val();
		var enddate_jualpb = $("#enddate_jualpb").val();
		var v_url ='<?php echo BASE_URL?>pages/pabrik/jual_pabrik.php?action=json&startdate_jualpb='+startdate_jualpb+'&enddate_jualpb='+enddate_jualpb ;
		jQuery("#table_jualpb").setGridParam({url:v_url,page:1}).trigger("reloadGrid");
	}
	
    $(document).ready(function(){
		// d = $("#startdate_jual").datepicker("getDate");
		// $("#startdate_jual").datepicker("setDate", new Date(d.getFullYear()+1,d.getMonth(),d.getDate()));
		//alert('kdie');
        $("#table_jualpb").jqGrid({
            url:'<?php echo BASE_URL.'pages/pabrik/jual_pabrik.php?action=json'; ?>',
            /*postData: {
                'title': function() {return $('#sJudul').val(); },
                'sales_id': function() {return $('#sSales_id').val(); },
                'Name': function() {return $('#sCustomFer').val(); },
                'summary_status': function() {return $('#sStatus').val(); },
            },*/
            datatype: "json",
            //colNames:['ID','Customer','Tanggal Transaksi','Qty','Faktur','Ongkos Kuli','Total Faktur','Tunai','Bank','View','Delete'],
            colNames:['ID','Kode','Customer','Tgl.Trans.','Pcs','Yard','Faktur','Total Faktur','Tunai','Bank','Piutang','Lihat','Edit'],
            colModel:[
                {name:'id_trans',index:'id_trans', width:30, search:true, stype:'text', searchoptions:{sopt:['cn']}},
                {name:'kode',index:'kode', width:50, search:true, stype:'text', searchoptions:{sopt:['cn']}},
                {name:'namaperusahaan',index:'namaperusahaan', width:100, searchoptions: {sopt:['cn']}},                
                {name:'tgl_trans',index:'tgl_trans', width:75, searchoptions: {sopt:['cn']},formatter:"date", formatoptions:{srcformat:"Y-m-d", newformat:"d/m/Y"}, align:'center'},
                {name:'totalqty',index:'totalqty', align:'right', width:30, searchoptions: {sopt:['cn']}},
                {name:'totalyard',index:'totalyard', align:'right', width:30, searchoptions: {sopt:['cn']}},
                {name:'faktur_murni',index:'faktur_murni', align:'right', width:75, searchoptions: {sopt:['cn']}},
                {name:'totalfaktur',index:'totalfaktur', align:'right', width:75, searchoptions: {sopt:['cn']}},
                {name:'tunai',index:'tunai', align:'right', width:75, searchoptions: {sopt:['cn']}},
                {name:'transfer',index:'transfer', align:'right', width:75, searchoptions: {sopt:['cn']}},
                {name:'piutang',index:'piutang', align:'right', width:75, searchoptions: {sopt:['cn']}},
                {name:'nota',index:'nota', align:'center', width:30, sortable: false, search: false},
                {name:'edit',index:'edit', align:'center', width:30, sortable: false, search: false},
            ],
            rowNum:50,
            rowList:[10,20,30],
            pager: '#pager_table_jualpb',
            sortname: 'id_trans',
            autowidth: true,
            height: '400',
            viewrecords: true,
            rownumbers: true,
            sortorder: "desc",
            caption:"Data Penjualan Pabrik",
            ondblClickRow: function(rowid) {
                alert(rowid);
            },
			footerrow : true,
			userDataOnFooter : true,
            subGrid : true,
            subGridUrl : '<?php echo BASE_URL.'pages/pabrik/jual_pabrik.php?action=json_sub'; ?>',
            subGridModel: [
			            	{ 
			            		name : ['No','Kode','Barang','Jenis','Harga','Qty(pcs)','Yard','Subtotal'], 
			            		width : [30,40,250,100,50,50,50,50],
			            		align : ['right','center','left','left','right','right','right','right'],
			            	} 
			            ],
						
            
        });
        $("#table_jualpb").jqGrid('navGrid','#pager_table_jualpb',{edit:false,add:false,del:false,search:false});
    })
</script>