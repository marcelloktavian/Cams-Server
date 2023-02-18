<?php require_once '../include/config.php' ?>
<?php
	if(isset($_GET['action']) && strtolower($_GET['action']) == 'json') {
		$page  = $_GET['page'];
        $limit = $_GET['rows'];
        $sidx  = $_GET['sidx'];
        $sord  = $_GET['sord'];

        if(!$sidx) $sidx=1;

        $where = "WHERE TRUE AND deleted = 0 ";
        $q = $db->query("SELECT * FROM `project` p ".$where);
		$count = $q->rowCount();
        
        $count > 0 ? $total_pages = ceil($count/$limit) : $total_pages = 0;
        if ($page > $total_pages) $page=$total_pages;
        $start = $limit*$page - $limit;
        if($start <0) $start = 0;

        $q = $db->query("SELECT *
							 FROM `project`
							 ".$where."
							 ORDER BY `".$sidx."` ".$sord."
							 LIMIT ".$start.", ".$limit);
		$data1 = $q->fetchAll(PDO::FETCH_ASSOC);
        $responce['page'] = $page;
        $responce['total'] = $total_pages;
        $responce['records'] = $count;
        $i=0;
        foreach($data1 as $line) {
        	
			$allowEdit = array(1,2,3);
			$allowDelete = array(1,2,3);
			if(in_array($_SESSION['user']['access'], $allowEdit))
				if($line['is_start'] == 0) {
					$edit = '<a onclick="javascript:popup_form(\''.BASE_URL.'pages/project.php?action=edit&id='.$line['project_id'].'\',\'table_project\')" href="javascript:;">Edit</a>';
				}
				else {
					$edit = '<a onclick="javascript:custom_alert(\'Project Started!\')" href="javascript:;">Started</a>';
				}				
			else
				$edit = '<a onclick="javascript:custom_alert(\'Not Allowed\')" href="javascript:;">Edit</a>';
        	
			if(in_array($_SESSION['user']['access'], $allowDelete))
				if($line['is_start'] == 0) {
					$delete = '<a onclick="javascript:link_ajax(\''.BASE_URL.'pages/project.php?action=delete&id='.$line['project_id'].'\',\'table_project\')" href="javascript:;">Delete</a>';
				}
				else {
					$delete = '<a onclick="javascript:custom_alert(\'Project Started!\')" href="javascript:;">Started</a>';
				}
			else
				$delete = '<a onclick="javascript:custom_alert(\'Not Allowed\')" href="javascript:;">Delete</a>';
			
        	$responce['rows'][$i]['id']   = $line['project_id'];
            $responce['rows'][$i]['cell'] = array(
                $line['project_name'],
                $line['project_description'],                
                $line['project_start'],
                $line['project_end'],
                number_format($line['total_angket'],0),
				$edit,
				$delete,
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
		
		$q = $db->query("SELECT pd.*, c.city_name, p.province_name FROM `project_detail` pd INNER JOIN `city` c ON pd.city_id=c.city_id INNER JOIN `province` p ON pd.province_id=p.province_id ".$where);
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
            );
            $i++;
        }
        echo json_encode($responce);
		exit;
	}
	elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'get_city') {
		$select = $db->prepare("SELECT * FROM `city` c WHERE c.province_id=?");
		$select->execute(array($_GET['pid']));
		$rows = $select->fetchAll(PDO::FETCH_ASSOC);
		$echo = '<select name="city_id[]" id="city_id" class="city_id required">';
		$echo .= '<option value="">--Choose--</option>';
		foreach($rows as $r) {
			$echo .= '<option value="'.$r['city_id'].'">'.$r['city_name'].'</option>';
		}
		$echo .= '</select>';
		//$res['status'] = 1;
		$res['st'] = 1;
		$res['resp'] = $echo;
		echo json_encode($res);
		exit;
	}
	elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'add') {
		include 'project_form.php';exit();
		exit;
	}
	elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'edit') {
		include 'project_form_edit.php';exit();
		exit;
	}
	elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'delete') {
		$stmt = $db->prepare("UPDATE project SET deleted=? WHERE project_id=?");
		$stmt->execute(array(1, $_GET['id']));
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
	elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'processadd') {
		$stmt = $db->prepare("INSERT INTO project(`project_name`,`project_description`,`project_start`,`project_end`,`total_angket`,`user_create`,`create_date`) VALUES(?, ?, ?, ?, ?, ?, NOW())");
		if($stmt->execute(array($_POST['project_name'],$_POST['project_description'],$_POST['project_start'],$_POST['project_end'],$_POST['total_angket'],$_SESSION['user']['user_id']))) {
			$projectId = $db->lastInsertId();
			foreach($_POST['city_id'] as $k => $v) {
				$pd = $db->prepare("INSERT INTO project_detail(`project_id`,`province_id`,`city_id`,`jumlah_angket`) VALUES('".$projectId."', ?, ?, ?)");
				$pd->execute(array($_POST['province_id'][$k],$v,$_POST['jumlah_angket'][$k]));
			}
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
	elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'processedit') {
		$stmt = $db->prepare("DELETE FROM project_detail WHERE project_id=:id");
		$stmt->bindValue(':id', $_POST['project_id'], PDO::PARAM_INT);
		$stmt->execute();
		$affected_rows = $stmt->rowCount();
				
		$stmt = $db->prepare("UPDATE project SET project_name=?, project_description=?, project_start=?, project_end=?, total_angket=?, user_update=?, update_date = NOW() WHERE project_id=?");
		$stmt->execute(array($_POST['project_name'], $_POST['project_description'], $_POST['project_start'], $_POST['project_end'], $_POST['total_angket'], $_SESSION['user']['user_id'], $_POST['project_id']));
		$affected_rows = $stmt->rowCount();
		
		//$stmt = $db->prepare("UPDATE project SET ");
		$projectId = $_POST['project_id'];
		foreach($_POST['city_id'] as $k => $v) {
			$pd = $db->prepare("INSERT INTO project_detail(`project_id`,`province_id`,`city_id`,`jumlah_angket`) VALUES('".$projectId."', ?, ?, ?)");
			$pd->execute(array($_POST['province_id'][$k],$v,$_POST['jumlah_angket'][$k]));
		}
		$r['stat'] = 1;
		$r['message'] = 'Success';
		
		
		echo json_encode($r);
		exit;
	}
?>
<table id="table_project"></table>
<div id="pager_table_project"></div>
<div class="btn_box">
<?php
	$allow = array(1,2,3);
	if(in_array($_SESSION['user']['access'], $allow)) {
		echo '<button type="button" onclick="javascript:popup_form(\''.BASE_URL.'pages/project.php?action=add\',\'table_project\')" class="btn">Tambah</button>';
	}
?>
</div>
<script type="text/javascript">
    $(document).ready(function(){

        $("#table_project").jqGrid({
            url:'<?php echo BASE_URL.'pages/project.php?action=json'; ?>',
            /*postData: {
                'title': function() {return $('#sJudul').val(); },
                'sales_id': function() {return $('#sSales_id').val(); },
                'Name': function() {return $('#sCustomer').val(); },
                'summary_status': function() {return $('#sStatus').val(); },
            },*/
            datatype: "json",
            colNames:['Project Name','Project Description','Start Project','End Project','Total Angket','Edit','Delete'],
            colModel:[
                {name:'project_name',index:'project_name', width:200, searchoptions: {sopt:['cn']}},
                {name:'project_description',index:'project_description', width:300, searchoptions: {sopt:['cn']}},                
                {name:'project_start',index:'project_start', width:150, searchoptions: {sopt:['cn']}},
                {name:'project_end',index:'project_end', width:150, searchoptions: {sopt:['cn']}},
                {name:'total_angket',index:'total_angket', align:'right', width:100, searchoptions: {sopt:['cn']}},
                {name:'Edit',index:'edit', align:'center', width:50, sortable: false, search: false},
                {name:'Delete',index:'delete', align:'center', width:50, sortable: false, search: false},
            ],
            rowNum:10,
            rowList:[10,20,30],
            pager: '#pager_table_project',
            sortname: 'project_name',
            autowidth: true,
            height: '230',
            viewrecords: true,
            rownumbers: true,
            sortorder: "asc",
            caption:"Master project",
            ondblClickRow: function(rowid) {
                alert(rowid);
            },
            subGrid : true,
            subGridUrl : '<?php echo BASE_URL.'pages/project.php?action=json_sub'; ?>',
            subGridModel: [
			            	{ 
			            		name : ['No','Province','City','Total Angket'], 
			            		width : [40,300,300,100],
			            		align : ['right','left','left','right'],
			            	} 
			            ],
            
        });
        $("#table_project").jqGrid('navGrid','#pager_table_project',{edit:false,add:false,del:false});
    })
</script>