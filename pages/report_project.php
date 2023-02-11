<?php require_once '../include/config.php' ?>
<?php
	require_once '../include/print.php';
	if(isset($_GET['action']) && strtolower($_GET['action']) == 'preview') {
		$s = $db->prepare("SELECT p.*,
							(SELECT COUNT(1) FROM `angket_masuk` am INNER JOIN `project_detail` pdt ON pdt.project_detail_id = am.project_detail_id WHERE pdt.project_id = p.project_id) total_masuk,
							concat(round(( (SELECT COUNT(1) FROM `angket_masuk` am INNER JOIN `project_detail` pdt ON pdt.project_detail_id = am.project_detail_id WHERE pdt.project_id = p.project_id)/total_angket * 100 ),2),'%') AS persentase
							FROM project p WHERE p.project_id=?");
		$s->execute(array($_GET['id']));
		$r = $s->fetch(PDO::FETCH_ASSOC);
		$p = new Printing;
		$p->customSql("SELECT (SELECT province_name FROM province p WHERE pd.province_id=p.province_id) province_name,
						(SELECT city_name FROM city c WHERE pd.city_id=c.city_id) city_name,
						pd.jumlah_angket,
						(SELECT COUNT(1) FROM `angket_masuk` am WHERE pd.project_detail_id = am.project_detail_id) total_masuk,
						concat(round(( (SELECT COUNT(1) FROM `angket_masuk` am WHERE pd.project_detail_id = am.project_detail_id)/jumlah_angket * 100 ),2),'%') AS persentase
						FROM project_detail pd
						WHERE pd.project_id='".$_GET['id']."'");
		$p->lbField('Provinsi','Kota','Total Angket','Jumlah Masuk','Persentase');
		$p->title1('<div style="text-align: center; font-size: 20px; text-transform: uppercase">'.$r['project_name'].'</div>');
		$p->title2('Total Angket = '.$r['total_angket'].'<br />Total Masuk = '.$r['total_masuk'].' ('.$r['persentase'].')<br />Project Start '.date('d F Y', strtotime($r['project_start'])).' - '.date('d F Y', strtotime($r['project_end'])));
		$p->align('jumlah_angket', 'right');
		$p->align('total_masuk', 'right');
		$p->align('persentase', 'right');
		$p->width('city_name', '150');
		$p->width('province_name', '150');
		$p->width('persentase', '100');
		
		if(isset($_GET['type']) && $_GET['type'] == 'pdf') {
		    echo $p->draw('pdf');
		}
		elseif(isset($_GET['type']) && $_GET['type'] == 'xls') {
		    echo $p->draw('xls');
		}
		else {
		    echo $p->draw('html');
		}		
		exit;
	}
?>
<div class="ui-widget ui-form">
    <div class="ui-widget-header ui-corner-top padding5">
        Report Project
    </div>
    <div class="ui-widget-content ui-corner-bottom">
        <form id="report_project_form" method="" action="" class="ui-helper-clearfix">
        	<label for="project_id" class="ui-helper-reset label-control">Project</label>
            <div class="ui-corner-all form-control">
            	<select name="project_id" id="project_id">
            		<option value="">--Choose--</option>
            		<?php
            			$p = $db->query("SELECT * FROM project p ORDER BY p.project_name");
            			$rows = $p->fetchAll(PDO::FETCH_ASSOC);
						foreach ($rows as $r) {
							echo '<option value="'.$r['project_id'].'">'.$r['project_name'].'</option>';
						}
            		?>
            	</select>
            </div>
            <label for="" class="ui-helper-reset label-control">&nbsp;</label>
            <div class="ui-corner-all form-control">
            	<button onclick="javascript:window_open('<?php echo BASE_URL ?>pages/report_project.php?action=preview&id='+$('#project_id').val())" class="btn" type="button">Preview</button>
            </div>
       	</form>
   	</div>
</div>