<?php require_once '../include/config.php' ?>
<?php
	if(isset($_GET['action']) && strtolower($_GET['action']) == 'json') {
		$page  = $_GET['page'];
        $limit = $_GET['rows'];
        $sidx  = $_GET['sidx'];
        $sord  = $_GET['sord'];

        if(!$sidx) $sidx=1;

        $where = "WHERE TRUE AND p.deleted = 0 ";
        $q = $db->query("SELECT * FROM `project` p ".$where);
		$count = $q->rowCount();
        
        $count > 0 ? $total_pages = ceil($count/$limit) : $total_pages = 0;
        if ($page > $total_pages) $page=$total_pages;
        $start = $limit*$page - $limit;
        if($start <0) $start = 0;

        $q = $db->query("SELECT p.*,
						(SELECT COUNT(1) FROM `angket_masuk` am INNER JOIN `project_detail` pdt ON pdt.project_detail_id = am.project_detail_id WHERE pdt.project_id = p.project_id) total_masuk,
						concat(round(( (SELECT COUNT(1) FROM `angket_masuk` am INNER JOIN `project_detail` pdt ON pdt.project_detail_id = am.project_detail_id WHERE pdt.project_id = p.project_id)/total_angket * 100 ),2),'%') AS persentase
						FROM `project` p
						 ".$where." 
						 ORDER BY is_start DESC, `".$sidx."` ".$sord."
						 LIMIT ".$start.", ".$limit);
		$data1 = $q->fetchAll(PDO::FETCH_ASSOC);
        $responce['page'] = $page;
        $responce['total'] = $total_pages;
        $responce['records'] = $count;
        $i=0;
        foreach($data1 as $line) {
        	if($line['is_start'] == 1) {
        		$allowUpload = array(1,2,3);
				if(in_array($_SESSION['user']['access'], $allowUpload))
					$upload = '<a onclick="javascript:popup_form_upload(\''.BASE_URL.'pages/upload_angket.php?action=upload&id='.$line['project_id'].'\',\'table_upload_angket\')" href="javascript:;">Upload</a>';									
				else
					$upload = '<a onclick="javascript:custom_alert(\'Not Allowed\')" href="javascript:;">Upload</a>';        		
        	}
			else {
				$upload = 'not started';
			}
				
        	
			
        	$responce['rows'][$i]['id']   = $line['project_id'];
            $responce['rows'][$i]['cell'] = array(
                $line['project_name'],
                $line['project_description'],                
                $line['project_start'],
                $line['project_end'],                
                number_format($line['total_angket'],0),
                $line['total_masuk'],
                $line['persentase'],
				$upload,				
            );
            $i++;
        }
        echo json_encode($responce);
		exit;
	}
	elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'json_sub') {
		$id = $_GET['id'];
		
		$where = "WHERE pd.closed=0 AND pd.project_id = '".$id."' ";
        $q = $db->query("SELECT * FROM `project_detail` pd INNER JOIN `city` c ON pd.city_id=c.city_id INNER JOIN `province` p ON pd.province_id=p.province_id ".$where);
		
		$count = $q->rowCount();
		
		$q = $db->query("SELECT pd.*, c.city_name, p.province_name,
						(SELECT COUNT(1) FROM `angket_masuk` am WHERE pd.project_detail_id = am.project_detail_id) total_masuk,
						concat(round(( (SELECT COUNT(1) FROM `angket_masuk` am WHERE pd.project_detail_id = am.project_detail_id)/jumlah_angket * 100 ),2),'%') AS persentase
						FROM `project_detail` pd 
						INNER JOIN `city` c ON pd.city_id=c.city_id 
						INNER JOIN `province` p ON pd.province_id=p.province_id ".$where);
		$data1 = $q->fetchAll(PDO::FETCH_ASSOC);
		
        $i=0;
        $responce = '';
        foreach($data1 as $line){
            $responce->rows[$i]['id']   = $line['project_detail_id'];
            $responce->rows[$i]['cell'] = array(
                $i+1,
                $line['province_name'],
                $line['city_name'],
                $line['jumlah_angket'],                
                $line['total_masuk'],
                $line['persentase'],
            );
            $i++;
        }
        echo json_encode($responce);
		exit;
	}
	elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'upload') {
		include 'upload_angket_form.php';exit();
		exit;
	}
	elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'process') {
		$filename = '';			
		if (isset($_FILES['file_upload']['tmp_name']) && $_FILES['file_upload']['tmp_name'] != '') {
			$info = pathinfo($_FILES['file_upload']['name']);
			$ext = $info['extension'];
			$filename = "angket_".$_POST['project_detail_id']."_".$_SESSION['user']['user_id']."_".date('Y_m_d_H_i_s').".".$ext;
 			$target = '../files/upload/angket/'.$filename;
 			move_uploaded_file( $_FILES['file_upload']['tmp_name'], $target);
		}
			
		
		$stmt = $db->prepare("INSERT INTO angket_masuk(`project_detail_id`,`file_upload`,`create_by`,`create_date`) VALUES(?, ?, ?, NOW())");
		if($stmt->execute(array($_POST['project_detail_id'],$filename,$_SESSION['user']['user_id']))) {
			$r['status'] = TRUE;
			$r['message'] = 'Success';
		}
		else {
			$r['status'] = FALSE;
			$r['message'] = 'Failed';
		}
		echo json_encode($r);
		exit;
	}	
?>
<table id="table_upload_angket"></table>
<div id="pager_table_upload_angket"></div>
<script type="text/javascript">
    $(document).ready(function(){

        $("#table_upload_angket").jqGrid({
            url:'<?php echo BASE_URL.'pages/upload_angket.php?action=json'; ?>',
            /*postData: {
                'title': function() {return $('#sJudul').val(); },
                'sales_id': function() {return $('#sSales_id').val(); },
                'Name': function() {return $('#sCustomer').val(); },
                'summary_status': function() {return $('#sStatus').val(); },
            },*/
            datatype: "json",
            colNames:['Project Name','Project Description','Start Project','End Project','Total Angket','Angket Masuk','Persentase','Upload'],
            colModel:[
                {name:'project_name',index:'project_name', width:200, searchoptions: {sopt:['cn']}},
                {name:'project_description',index:'project_description', width:300, searchoptions: {sopt:['cn']}},                
                {name:'project_start',index:'project_start', width:150, searchoptions: {sopt:['cn']}},
                {name:'project_end',index:'project_end', width:150, searchoptions: {sopt:['cn']}},
                {name:'total_angket',index:'total_angket', align:'right', width:100, searchoptions: {sopt:['cn']}},
                {name:'angket_masuk',index:'total_masuk', align:'right', width:100, searchoptions: {sopt:['cn']}},
                {name:'persentase',index:'persentase', align:'right', width:100, searchoptions: {sopt:['cn']}},
                {name:'Upload',index:'upload', align:'center', width:50, sortable: false, search: false},                
            ],
            rowNum:10,
            rowList:[10,20,30],
            pager: '#pager_table_upload_angket',
            sortname: 'update_date',
            autowidth: true,
            height: '230',
            viewrecords: true,
            rownumbers: true,
            sortorder: "desc",
            caption:"Master project",
            ondblClickRow: function(rowid) {
                alert(rowid);
            },
            subGrid : true,
            subGridUrl : '<?php echo BASE_URL.'pages/upload_angket.php?action=json_sub'; ?>',
            subGridModel: [
			            	{ 
			            		name : ['No','Province','City','Total Angket','Angket Masuk','Persentase'], 
			            		width : [40,300,300,100,100,100],
			            		align : ['right','left','left','right','right','right'],
			            	} 
			            ],
            
        });
        $("#table_upload_angket").jqGrid('navGrid','#pager_table_upload_angket',{edit:false,add:false,del:false});
        
        var expandSubGrid = function(){
		    $('#table_upload_angket').jqGrid('expandSubGridRow', expandedRowID);
		    $('#table_upload_angket_' + expandedRowID).trigger('reloadGrid');
		};
		var grid_loadComplete = function () {
		    window.setTimeout(expandSubGrid, 1);
		};
        
    })
</script>