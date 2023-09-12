<?php require_once '../../include/config.php' ?>
<?php
$group_acess = unserialize(file_get_contents("../../GROUP_ACCESS_CACHE".$_SESSION['user']['group_id']));
$allow_delete = is_show_menu(DELETE_POLICY, TroubleOrder, $group_acess);

	if(isset($_GET['action']) && strtolower($_GET['action']) == 'json') {
		$page  = $_GET['page'];
        $limit = $_GET['rows'];
        $sidx  = $_GET['sidx'];
        $sord  = $_GET['sord'];

        // if(!$sidx) $sidx=1;
        //        if ($_REQUEST["_search"] == "false") {
       		//$where = " WHERE m.totalqty <> (SELECT SUM(jumlah_beli) FROM olnsodetail AS d WHERE d.id_trans=m.id_trans) ";
    //    } else {
    //    $operations = array(
    //     'eq' => "= '%s'",            // Equal
    //     'ne' => "<> '%s'",           // Not equal
    //     'lt' => "< '%s'",            // Less than
    //     'le' => "<= '%s'",           // Less than or equal
    //     'gt' => "> '%s'",            // Greater than
    //     'ge' => ">= '%s'",           // Greater or equal
    //     'bw' => "like '%s%%'",       // Begins With
    //     'bn' => "not like '%s%%'",   // Does not begin with
    //     'in' => "in ('%s')",         // In
    //     'ni' => "not in ('%s')",     // Not in
    //     'ew' => "like '%%%s'",       // Ends with
    //     'en' => "not like '%%%s'",   // Does not end with
    //     'cn' => "like '%%%s%%'",     // Contains
    //     'nc' => "not like '%%%s%%'", // Does not contain
    //     'nu' => "is null",           // Is null
    //     'nn' => "is not null"        // Is not null
	// 	); 
	
    //   $value = $_REQUEST["searchString"];
	//   $where = sprintf(" WHERE m.totalqty <> (SELECT SUM(jumlah_beli) FROM olnsodetail AS d WHERE d.id_trans=m.id_trans) AND %s ".$operations[$_REQUEST["searchOper"]], $_REQUEST["searchField"], $value);
	//  //echo"<script>alert('where=$where')</script>";
    //  }
        $sql_trouble = "SELECT dt.id_trans,DATE_FORMAT(m.lastmodified,'%d/%m/%Y') AS tglposted, m.ref_kode AS id_web,m.exp_fee AS ongkir,d.nama AS dropshipper,SUM(dt.jumlah_beli) AS qty_detail,m.totalqty,SUM(ceil(dt.subtotal * (1-m.discount))) AS total_detail,m.total,m.faktur ,m.nama AS pembeli,e.nama AS expedition,m.state,m.discount AS discdp,m.discount_faktur AS disc_faktur, m.deposit, m.transfer FROM olnsodetail dt  INNER JOIN olnso m ON dt.id_trans = m.id_trans  LEFT JOIN mst_dropshipper d ON m.id_dropshipper = d.id LEFT JOIN mst_expedition e ON m.id_expedition = e.id WHERE ((m.deleted=0) AND (m.state='1') AND DATE(m.lastmodified) BETWEEN STR_TO_DATE('01/01/".date("Y")."','%d/%m/%Y') AND STR_TO_DATE('31/12/".date("Y")."','%d/%m/%Y')) GROUP BY dt.id_trans HAVING (SUM(CEIL(dt.subtotal * (1-m.discount))) <> m.faktur) OR (SUM(dt.jumlah_beli) <> m.totalqty) ORDER BY m.id_trans ASC";
        // var_dump($sql_trouble);die;
		$q = $db->query($sql_trouble);
        $count = $q->rowCount();
        
        $count > 0 ? $total_pages = ceil($count/$limit) : $total_pages = 0;
        if ($page > $total_pages) $page=$total_pages;
        $start = $limit*$page - $limit;
        if($start <0) $start = 0;

        $q = $db->query($sql_trouble ." LIMIT ".$start.", ".$limit);
		$data1 = $q->fetchAll(PDO::FETCH_ASSOC);
        $responce['page'] = $page;
        $responce['total'] = $total_pages;
        $responce['records'] = $count;
        $i=0;
        foreach($data1 as $line) {
        	
			// if($allow_delete)
			// 	$delete = '<a onclick="javascript:link_ajax(\''.BASE_URL.'pages/report_online/rpt_trouble.php?action=delete&id='.$line['id_trans'].'\',\'table_trouble\')" href="javascript:;">Cancel</a>';
			// else
			// 	$delete = '<a onclick="javascript:custom_alert(\'Tidak Boleh dibatalkan\')" href="javascript:;">Delete</a>';
	
			$status='';
			if($line['deposit']==0 && $line['transfer']==0){
				$status = 'Credit';
			}else{
				$status = 'Cash';
			}
			
			$responce['rows'][$i]['id']   = $line['id_trans'];
            $responce['rows'][$i]['cell'] = array(
                $line['id_trans'],
                $line['tglposted'],
                $line['id_web'],                
                $line['dropshipper'],                
                number_format($line['qty_detail'],0),
				number_format($line['totalqty'],0),
				number_format($line['total_detail'],0),
				number_format($line['total'],0),
				number_format($line['faktur'],0),
				$status,
				
				// $delete,
            );
            $i++;
        }
        echo json_encode($responce);
		exit;
	}
	elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'json_sub') {
	
		$id = $_GET['id'];
		
		$where = "WHERE pd.id_trans = '".$id."' ";
        $sql_data="SELECT pd.* FROM `olnsodetail` pd ".$where;
		//var_dump($sql_data); die;
		$q = $db->query($sql_data);
		$count = $q->rowCount();
		$data1 = $q->fetchAll(PDO::FETCH_ASSOC);
		
        $i=0;
        $responce = '';
        foreach($data1 as $line){
		$delete_detail = '<a onclick="javascript:link_ajax(\''.BASE_URL.'pages/report_online/rpt_trouble.php?action=delete_detail&id='.$line['id_so_d'].'\',\'table_trouble\')" href="javascript:;">Delete</a>';
            $responce->rows[$i]['id']   = $line['id_trans'];
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
<table id="table_trouble"></table>
<div id="pager_table_trouble"></div>

<script type="text/javascript">
    $(document).ready(function(){

        $("#table_trouble").jqGrid({
            url:'<?php echo BASE_URL.'pages/report_online/rpt_trouble.php?action=json'; ?>',
            datatype: "json",
            colNames:['ID','Tanggal Transaksi','ID WEB','Dropshipper','Qty Detail','Qty Master','Total Detail','Total Master','Faktur','Status'],
            colModel:[
                {name:'id_trans',index:'id_trans', width:80, search:true, stype:'text', searchoptions:{sopt:['cn']}},
                {name:'tgl_trans',index:'tglposted', width:80, searchoptions: {sopt:['cn']},formatter:"date", formatoptions:{srcformat:"Y-m-d", newformat:"d/m/Y"}, align:'center'},
                {name:'id_web',index:'id_web', width:80, searchoptions: {sopt:['cn']}},  
                {name:'dropshipper',index:'dropshipper', width:100, search:true, stype:'text', searchoptions:{sopt:['cn']}},
                {name:'qty_detail',index:'qty_detail', align:'right', width:80, searchoptions: {sopt:['cn']}},
                {name:'totalqty',index:'totalqty', align:'right', width:80, searchoptions: {sopt:['cn']}},              
				{name:'total_detail',index:'total_detail', align:'right', width:80, searchoptions: {sopt:['cn']}},
                {name:'total',index:'total', align:'right', width:80, searchoptions: {sopt:['cn']}},
                {name:'faktur',index:'faktur', align:'right', width:80, searchoptions: {sopt:['cn']}},
                {name:'status',index:'status', align:'center', width:40, searchoptions: {sopt:['cn']}},
            ],
            rowNum:20,
            rowList:[10,20,30],
            pager: '#pager_table_trouble',
            sortname: 'dt.id_trans',
            autowidth: true,
            height: '400',
            viewrecords: true,
            rownumbers: true,
            sortorder: "asc",
            caption:"Trouble Order",
            ondblClickRow: function(rowid) {
                alert(rowid);
            },
            subGrid : true,
            subGridUrl : '<?php echo BASE_URL.'pages/report_online/rpt_trouble.php?action=json_sub'; ?>',
            subGridModel: [
			            	{ 
			            		name : ['No','Kode','Barang','Size','Harga (inc PPN)','Qty(pcs)','Subtotal'], 
			            		width : [40,60,150,60,50,30,50,50],
			            		align : ['right','left','left','center','right','right','right',],
			            	} 
			            ],
						
            
        });
        $("#table_trouble").jqGrid('navGrid','#pager_table_trouble',{edit:false,add:false,del:false});
    })
</script>