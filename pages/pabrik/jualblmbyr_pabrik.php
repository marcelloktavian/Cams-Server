<?php require_once '../../include/config.php' ?>
<?php
	if(isset($_GET['action']) && strtolower($_GET['action']) == 'json') {
		$page  = $_GET['page'];
        $limit = $_GET['rows'];
        $sidx  = $_GET['sidx'];
        $sord  = $_GET['sord'];

        if(!$sidx) $sidx=1;
               if ($_REQUEST["_search"] == "false") {
       //$where = "WHERE p.deleted=0 ";
       $where = "WHERE TRUE AND (((p.piutang*30*1.11)-p.pelunasan)>0) AND (p.deleted = 0) and (j.type=2) and (p.id_supplier<>0) ";
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
	  $where = sprintf(" where (p.deleted=0) and (j.type=2) and (p.id_supplier<>0) AND (((p.piutang*30*1.11)-p.pelunasan)>0) AND %s ".$operations[$_REQUEST["searchOper"]], $_REQUEST["searchField"], $value);
	 //echo"<script>alert('where=$where')</script>";
     }
        //$where = "WHERE TRUE AND p.deleted = 0 ";
        //$where = "WHERE TRUE ";
        $q = $db->query("SELECT * FROM `trbeli` p Left Join `tblsupplier` j on (p.id_supplier=j.id) ".$where);
		$count = $q->rowCount();
        
        $count > 0 ? $total_pages = ceil($count/$limit) : $total_pages = 0;
        if ($page > $total_pages) $page=$total_pages;
        $start = $limit*$page - $limit;
        if($start <0) $start = 0;

        $q = $db->query("SELECT p.*, j.namaperusahaan as supplier 
							 FROM `trbeli` p Left Join `tblsupplier` j on (p.id_supplier=j.id) 
							 ".$where."
							 ORDER BY `".$sidx."` ".$sord."
							 LIMIT ".$start.", ".$limit);
		//var_dump($q);die;
		$data1 = $q->fetchAll(PDO::FETCH_ASSOC);
        $responce['page'] = $page;
        $responce['total'] = $total_pages;
        $responce['records'] = $count;
        $i=0;
        foreach($data1 as $line) {
        	$konversi=30;
			
			$allowEdit = array(1,2,3);
			$allowDelete = array(1,2,3);
			if(in_array($_SESSION['user']['access'], $allowEdit))
					$edit = '<a onclick="window.open(\''.BASE_URL.'pages/pabrik/piutang_pabrik_lunasi.php?ids='.$line['id_trans'].'\',\'table_jualpb_blmbyr\')" href="javascript:;">Bayar</a>';
			else
				$edit = '<a onclick="javascript:custom_alert(\'Not Allowed\')" href="javascript:;">Edit</a>';
        	
        	$responce['rows'][$i]['id']   = $line['id_trans'];
            $responce['rows'][$i]['cell'] = array(
                $line['id_trans'],
                $line['supplier'],                
                $line['tgl_trans'],
                number_format($line['totalqty'],0),
				number_format($line['totalqty']*$konversi,0),
				number_format($line['faktur_murni']*$konversi*1.11,0),
				number_format($line['biaya'],0),
				number_format($line['totalfaktur']*$konversi*1.11,0),
				number_format($line['tunai']*$konversi*1.11,0),
				number_format($line['transfer']*$konversi*1.11,0),
				number_format(($line['piutang']*$konversi*1.11-$line['pelunasan']),0),
				$edit,
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
<table id="table_jualpb_blmbyr"></table>
<div id="pager_table_jualpb_blmbyr"></div>

<script type="text/javascript">
    $(document).ready(function(){

        $("#table_jualpb_blmbyr").jqGrid({
            url:'<?php echo BASE_URL.'pages/pabrik/jualblmbyr_pabrik.php?action=json'; ?>',
            datatype: "json",
            colNames:['ID','Customer','Tanggal Transaksi','Pcs','Yard','Faktur','Ongkir','Total Faktur','Tunai','Transfer','Piutang','Aksi'/*,'Delete'*/],
            colModel:[
                {name:'id_trans',index:'id_trans', width:80, search:true, stype:'text', searchoptions:{sopt:['cn']}},
                {name:'namaperusahaan',index:'namaperusahaan', width:100, searchoptions: {sopt:['cn']}},                
                {name:'tgl_trans',index:'tgl_trans', width:80, searchoptions: {sopt:['cn']},formatter:"date", formatoptions:{srcformat:"Y-m-d", newformat:"d/m/Y"}, align:'center'},
                {name:'totalqty',index:'totalqty', align:'right', width:40, searchoptions: {sopt:['cn']}},
                {name:'totalyard',index:'totalqty', align:'right', width:40, searchoptions: {sopt:['cn']}},
                {name:'faktur',index:'faktur', align:'right', width:80, searchoptions: {sopt:['cn']}},
                {name:'biaya',index:'biaya', align:'right', width:60, searchoptions: {sopt:['cn']}},
                {name:'totalfaktur',index:'totalfaktur', align:'right', width:80, searchoptions: {sopt:['cn']}},
                {name:'tunai',index:'tunai', align:'right', width:80, searchoptions: {sopt:['cn']}},
                {name:'transfer',index:'transfer', align:'right', width:80, searchoptions: {sopt:['cn']}},
                {name:'piutang',index:'piutang', align:'right', width:80, searchoptions: {sopt:['cn']}},
                {name:'edit',index:'edit', align:'center', width:50, sortable: false, search: false},
                //{name:'Delete',index:'delete', align:'center', width:50, sortable: false, search: false},
            ],
            rowNum:20,
            rowList:[10,20,30],
            pager: '#pager_table_jualpb_blmbyr',
            sortname: 'id_trans',
            autowidth: true,
            height: '400',
            viewrecords: true,
            rownumbers: true,
            sortorder: "asc",
            caption:"Data Penjualan Pabrik Belum Lunas",
            ondblClickRow: function(rowid) {
                alert(rowid);
            },
            subGrid : true,
            subGridUrl : '<?php echo BASE_URL.'pages/pabrik/jualblmbyr_pabrik.php?action=json_sub'; ?>',
            subGridModel: [
			            	{ 
			            		name : ['No','Kode','Barang','Jenis','Harga','Qty(pcs)','Qty(yard)','Subtotal'], 
			            		width : [40,40,200,100,50,50,50,50],
			            		align : ['right','center','left','left','right','right','right','right'],
			            	} 
			            ],
						
            
        });
        $("#table_jualpb_blmbyr").jqGrid('navGrid','#pager_table_jualpb_blmbyr',{edit:false,add:false,del:false});
    })
</script>