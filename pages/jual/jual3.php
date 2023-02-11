<?php require_once '../../include/config.php' ?>
<?php
	if(isset($_GET['action']) && strtolower($_GET['action']) == 'json') {
		$page  = $_GET['page'];
        $limit = $_GET['rows'];
        $sidx  = $_GET['sidx'];
        $sord  = $_GET['sord'];
		
		$startdate = isset($_GET['startdate_jual'])?$_GET['startdate_jual']:date('d/m/Y');
		$enddate = isset($_GET['enddate_jual'])?$_GET['enddate_jual']:date('d/m/Y'); 

        $page = isset($_GET['page'])?$_GET['page']:1; // get the requested page
        $limit = isset($_GET['rows'])?$_GET['rows']:10; // get how many rows we want to have into the grid
        $sidx = isset($_GET['sidx'])?$_GET['sidx']:'tgl_trans'; // get index row - i.e. user click to sort
        $sord = isset($_GET['sord'])?$_GET['sord']:''; 
	
      // $value = $_REQUEST["searchString"];
	  // $where = sprintf(" where (p.deleted=0) AND %s ".$operations[$_REQUEST["searchOper"]], $_REQUEST["searchField"], $value);
	 //echo"<script>alert('where=$where')</script>";
     // }
        //$where = "WHERE TRUE AND p.deleted = 0 ";
        $where = "WHERE TRUE ";
		
		if($startdate != null){
			$where .= " AND DATE_FORMAT(tgl_trans,'%d/%m/%Y') BETWEEN '$startdate' AND '$enddate'";
		}	
		
		$sql = "SELECT * FROM `trjual` p Left Join `tblpelanggan` j on (p.id_customer=j.id) ".$where;
        $q = $db->query($sql);
		$count = $q->rowCount();
    //    var_dump($sql);
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
		$grand_faktur=0;$grand_totalfaktur=0;$grand_piutang=0;$grand_tunai=0;$grand_transfer=0;$grand_biaya=0 ;
        foreach($data1 as $line) {
        	
			$allowEdit = array(1,2,3);
			$allowDelete = array(1,2,3);
		    if(in_array($_SESSION['user']['access'], $allowEdit)){
			$edit = '<a onclick="window.open(\''.BASE_URL.'pages/kasir/jual_kasir_yjs_edit.php?id='.$line['id_trans'].'\',\'table_jual\')" href="javascript:;">Nota</a>';
			}
			else
				$edit = '<a onclick="javascript:custom_alert(\'Tidak Boleh Print Nota\')" href="javascript:;">Edit</a>';
				
        	$responce['rows'][$i]['id']   = $line['id_trans'];
            $responce['rows'][$i]['cell'] = array(
                $line['id_trans'],
                $line['namaperusahaan'],                
                $line['tgl_trans'],
                number_format($line['totalqty'],0),
				number_format($line['faktur'],0),
				number_format($line['biaya'],0),
				number_format($line['totalfaktur'],0),
				number_format($line['tunai'],0),
				number_format($line['transfer'],0),
				number_format($line['piutang'],0),
				$edit,
				//$delete,
            );
			$grand_faktur+=$line['faktur'];
			$grand_totalfaktur+=$line['totalfaktur'];
			$grand_piutang+=$line['piutang'];
			$grand_tunai+=$line['tunai'];
			$grand_transfer+=$line['transfer'];
			$grand_biaya+=$line['biaya'];
            $i++;
        }
		$responce['userdata']['faktur'] 		= number_format($grand_faktur,0);
		$responce['userdata']['totalfaktur'] 	= number_format( $grand_totalfaktur,0);
		$responce['userdata']['piutang'] 		= number_format($grand_piutang,0);
		$responce['userdata']['tunai'] 			= number_format($grand_tunai,0);
		$responce['userdata']['transfer']		= number_format($grand_transfer,0);
		$responce['userdata']['biaya'] 			= number_format($grand_biaya,0);
        echo json_encode($responce);
		exit;
	}
	
	elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'json_sub') {
	
		$id = $_GET['id'];
		
		$where = "WHERE pd.id_trans = '".$id."' ";
        $q = $db->query("SELECT pd.id_detail,pd.id_barang,b.nm_barang,pd.id_trans,pd.qty,pd.harga,(pd.qty * pd.harga) as subtotal FROM `trjual_detail` pd INNER JOIN `barang` b ON (pd.kode_brg=b.kode_brg) ".$where);
		
		$count = $q->rowCount();
		
		$q = $db->query("SELECT pd.id_detail,pd.id_barang,b.nm_barang,b.kode_brg,pd.id_trans,pd.qty,pd.harga,(pd.qty * pd.harga) as subtotal FROM `trjual_detail` pd INNER JOIN `barang` b ON (pd.kode_brg=b.kode_brg) ".$where);
		$data1 = $q->fetchAll(PDO::FETCH_ASSOC);
		
        $i=0;
        $responce = '';
        foreach($data1 as $line){
            $responce->rows[$i]['id']   = $line['id_detail'];
            $responce->rows[$i]['cell'] = array(
                $i+1,
                $line['kode_brg'],
                $line['nm_barang'],
                 number_format($line['harga'],0),
                 number_format($line['qty'],0),                
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
				 <input value="" type="text" class="required datepicker"   id="startdate_jual" name="startdate_jual">
				</td>
				<td> s.d.  
				<input value="" type="text" class="required datepicker"  id="enddate_jual" name="enddate_jual">
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
	
<table id="table_jual"></table>
<div id="pager_table_jual"></div>
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
	 
	$('#startdate_jual').datepicker({
		dateFormat: "dd/mm/yy"
	});
	$('#enddate_jual').datepicker({
		dateFormat: "dd/mm/yy"
	});
	$( "#startdate_jual" ).datepicker( 'setDate', '<?php echo date('d/m/Y')?>' );
	$( "#enddate_jual" ).datepicker( 'setDate', '<?php echo date('d/m/Y')?>' );
	
	
	function gridReloadJual(){
		var startdate_jual = $("#startdate_jual").val();
		var enddate_jual = $("#enddate_jual").val();
		var v_url ='<?php echo BASE_URL?>pages/jual/jual.php?action=json&startdate_jual='+startdate_jual+'&enddate_jual='+enddate_jual ;
		jQuery("#table_jual").setGridParam({url:v_url,page:1}).trigger("reloadGrid");
	}
	
    $(document).ready(function(){
		// d = $("#startdate_jual").datepicker("getDate");
		// $("#startdate_jual").datepicker("setDate", new Date(d.getFullYear()+1,d.getMonth(),d.getDate()));
		//alert('kdie');
        $("#table_jual").jqGrid({
            url:'<?php echo BASE_URL.'pages/jual/jual.php?action=json'; ?>',
            /*postData: {
                'title': function() {return $('#sJudul').val(); },
                'sales_id': function() {return $('#sSales_id').val(); },
                'Name': function() {return $('#sCustomFer').val(); },
                'summary_status': function() {return $('#sStatus').val(); },
            },*/
            datatype: "json",
            //colNames:['ID','Customer','Tanggal Transaksi','Qty','Faktur','Ongkos Kuli','Total Faktur','Tunai','Bank','View','Delete'],
            colNames:['ID','Customer','Tanggal Transaksi','Qty','Faktur','Ongkos Kuli','Total Faktur','Tunai','Bank','Piutang','Lihat'],
            colModel:[
                {name:'id_trans',index:'id_trans', width:40, search:true, stype:'text', searchoptions:{sopt:['cn']}},
                {name:'namaperusahaan',index:'namaperusahaan', width:100, searchoptions: {sopt:['cn']}},                
                {name:'tgl_trans',index:'tgl_trans', width:80, searchoptions: {sopt:['cn']},formatter:"date", formatoptions:{srcformat:"Y-m-d", newformat:"d/m/Y"}, align:'center'},
                {name:'totalqty',index:'totalqty', align:'right', width:30, searchoptions: {sopt:['cn']}},
                {name:'faktur',index:'faktur', align:'right', width:80, searchoptions: {sopt:['cn']}},
                {name:'biaya',index:'biaya', align:'right', width:60, searchoptions: {sopt:['cn']}},
                {name:'totalfaktur',index:'totalfaktur', align:'right', width:80, searchoptions: {sopt:['cn']}},
                {name:'tunai',index:'tunai', align:'right', width:80, searchoptions: {sopt:['cn']}},
                {name:'transfer',index:'transfer', align:'right', width:80, searchoptions: {sopt:['cn']}},
                {name:'piutang',index:'piutang', align:'right', width:80, searchoptions: {sopt:['cn']}},
                {name:'edit',index:'edit', align:'center', width:30, sortable: false, search: false},
              //  {name:'Delete',index:'delete', align:'center', width:50, sortable: false, search: false},
            ],
            rowNum:20,
            rowList:[10,20,30],
            pager: '#pager_table_jual',
            sortname: 'id_trans',
            autowidth: true,
            height: '400',
            viewrecords: true,
            rownumbers: true,
            sortorder: "desc",
            caption:"Data Penjualan",
            ondblClickRow: function(rowid) {
                alert(rowid);
            },
			footerrow : true,
			userDataOnFooter : true,
            subGrid : true,
            subGridUrl : '<?php echo BASE_URL.'pages/jual/jual.php?action=json_sub'; ?>',
            subGridModel: [
			            	{ 
			            		name : ['No','Kode','Barang','Harga','Qty(pcs)','Subtotal'], 
			            		width : [40,40,300,50,50,50],
			            		align : ['right','center','left','right','right','right'],
			            	} 
			            ],
						
            
        });
        $("#table_jual").jqGrid('navGrid','#pager_table_jual',{edit:false,add:false,del:false,search:false});
    })
</script>