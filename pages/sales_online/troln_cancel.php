<?php require_once '../../include/config.php' ?>
<?php
	if(isset($_GET['action']) && strtolower($_GET['action']) == 'json') {
		$page  = $_GET['page'];
        $limit = $_GET['rows'];
        $sidx  = $_GET['sidx'];
        $sord  = $_GET['sord'];

        if(!$sidx) $sidx=1;
               if ($_REQUEST["_search"] == "false") {
       $where = "WHERE TRUE AND (p.state='0') AND (p.totalqty=0) ";
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
	  $where = sprintf(" where TRUE AND (p.state='0') AND (p.totalqty=0) AND %s ".$operations[$_REQUEST["searchOper"]], $_REQUEST["searchField"], $value);
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

        $responce['page'] = $page;
        $responce['total'] = $total_pages;
        $responce['records'] = $count;
        $i=0;
        foreach($data1 as $line) {
        	
			$allowEdit = array(1,2,3);
			$allowDelete = array(1,2,3);
			/*
			if(in_array($_SESSION['user']['access'], $allowEdit))
					$edit = '<a onclick="window.open(\''.BASE_URL.'pages/summary_online/piutang_detail_lunasi.php?ids='.$line['id_trans'].'\',\'table_jual_cancel\')" href="javascript:;">Pay</a>';
			
			
            if(in_array($_SESSION['user']['access'], $allowEdit))
			$edit = '<a onclick="javascript:custom_alert(\'Under construction\')" href="javascript:;">Pay</a>';			
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
				//$edit,
				//$delete,
            );
            $i++;
        }
        echo json_encode($responce);
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
   onclick="window.open('pages/laporan/report_jualblmbyr.php')">
   <button class="btn btn-success">Cetak</button></a>  
<?php } ?>
</br>
</div>
<table id="table_jual_cancel"></table>
<div id="pager_table_jual_cancel"></div>


<script type="text/javascript">
    $(document).ready(function(){

        $("#table_jual_cancel").jqGrid({
            url:'<?php echo BASE_URL.'pages/sales_online/troln_cancel.php?action=json'; ?>',
            datatype: "json",
            colNames:['ID','Customer','Tanggal Transaksi','Qty','Faktur','Ongkir','Total Faktur','Tunai','Transfer'/*,'Piutang','Aksi','Delete'*/],
            colModel:[
                {name:'id_trans',index:'id_trans', width:80, search:true, stype:'text', searchoptions:{sopt:['cn']}},
                {name:'namaperusahaan',index:'namaperusahaan', width:100, searchoptions: {sopt:['cn']}},                
                {name:'tgl_trans',index:'tgl_trans', width:80, searchoptions: {sopt:['cn']},formatter:"date", formatoptions:{srcformat:"Y-m-d", newformat:"d/m/Y"}, align:'center'},
                {name:'totalqty',index:'totalqty', align:'right', width:40, searchoptions: {sopt:['cn']}},
                {name:'faktur',index:'faktur', align:'right', width:80, searchoptions: {sopt:['cn']}},
                {name:'biaya',index:'biaya', align:'right', width:60, searchoptions: {sopt:['cn']}},
                {name:'totalfaktur',index:'totalfaktur', align:'right', width:80, searchoptions: {sopt:['cn']}},
                {name:'tunai',index:'tunai', align:'right', width:80, searchoptions: {sopt:['cn']}},
                {name:'transfer',index:'transfer', align:'right', width:80, searchoptions: {sopt:['cn']}},
                //{name:'piutang',index:'piutang', align:'right', width:80, searchoptions: {sopt:['cn']}},
                //{name:'edit',index:'edit', align:'center', width:50, sortable: false, search: false},
                //{name:'Delete',index:'delete', align:'center', width:50, sortable: false, search: false},
            ],
            rowNum:20,
            rowList:[10,20,30],
            pager: '#pager_table_jual_cancel',
            sortname: 'id_trans',
            autowidth: true,
            height: '400',
            viewrecords: true,
            rownumbers: true,
            sortorder: "asc",
            caption:"Cancel Order (Online)",
            ondblClickRow: function(rowid) {
                alert(rowid);
            },
            subGrid : true,
            subGridUrl : '<?php echo BASE_URL.'pages/sales_online/troln_cancel.php?action=json_sub'; ?>',
            subGridModel: [
			            	{ 
			            		name : ['No','Kode','Barang','Harga','Qty(pcs)','Subtotal'], 
			            		width : [40,40,300,50,50,50],
			            		align : ['right','center','left','right','right','right'],
			            	} 
			            ],
						
            
        });
        $("#table_jual_cancel").jqGrid('navGrid','#pager_table_jual_cancel',{edit:false,add:false,del:false});
    })
</script>