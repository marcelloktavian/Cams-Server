<?php require_once '../../include/config.php' ?>
<?php
	require_once '../../include/print.php';
	if(isset($_GET['action']) && strtolower($_GET['action']) == 'preview') {
		//$s = $db->prepare("SELECT p.*,(SELECT COUNT(1) FROM `angket_masuk` am INNER JOIN `project_detail` pdt ON pdt.project_detail_id = am.project_detail_id WHERE pdt.project_id = p.project_id) total_masuk,concat(round(( (SELECT COUNT(1) FROM `angket_masuk` am INNER JOIN `project_detail` pdt ON pdt.project_detail_id = am.project_detail_id WHERE pdt.project_id = p.project_id)/total_angket * 100 ),2),'%') AS persentase FROM project p WHERE p.project_id=?");

		//$s->execute(array($_GET['id']));
		//$r = $s->fetch(PDO::FETCH_ASSOC);
		$p = new Printing;
		//$p->customSql("SELECT (SELECT province_name FROM province p WHERE pd.province_id=p.province_id) province_name,(SELECT city_name FROM city c WHERE pd.city_id=c.city_id) city_name,pd.jumlah_angket,(SELECT COUNT(1) FROM `angket_masuk` am WHERE pd.project_detail_id = am.project_detail_id) total_masuk,concat(round(( (SELECT COUNT(1) FROM `angket_masuk` am WHERE pd.project_detail_id = am.project_detail_id)/jumlah_angket * 100 ),2),'%') AS persentase FROM project_detail pd WHERE pd.project_id='".$_GET['id']."'");
		$p->customSql("SELECT b.id_trans,b.id_supplier,b.tgl_trans, b.totalqty,b.totalfaktur FROM trbeli b WHERE b.faktur=0");
		$p->lbField('ID','Supplier','Tgl.Transaksi','Total Qty','Total Faktur');
		//$p->title1('<div style="text-align: center; font-size: 20px; text-transform: uppercase">'.$r['project_name'].'</div>');
		$p->title1('<div style="text-align: center; font-size: 20px; text-transform: uppercase">Laporan Pengiriman Barang</div>');
		//$p->title2('Total Angket = '.$r['total_angket'].'<br />Total Masuk = '.$r['total_masuk'].' ('.$r['persentase'].')<br />Project Start '.date('d F Y', strtotime($r['project_start'])).' - '.date('d F Y', strtotime($r['project_end'])));
		/*
		$p->align('jumlah_angket', 'right');
		$p->align('total_masuk', 'right');
		$p->align('persentase', 'right');
		$p->width('city_name', '150');
		$p->width('province_name', '150');
		$p->width('persentase', '100');
		*/
		$p->align('id_trans','center');
		$p->width('id_trans','150');
		$p->align('id_supplier','left');
		$p->align('tgl_trans','center');
		$p->width('totalqty','150');
		$p->align('totalqty','right');
		$p->width('totalfaktur','150');
		$p->align('totalfaktur','right');
		
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
        Report Pengiriman Barang
    </div>
    <div class="ui-widget-content ui-corner-bottom">
        <form id="report_project_form" method="" action="" class="ui-helper-clearfix">
        	<label for="project_id" class="ui-helper-reset label-control">Tanggal</label>
            <div class="ui-corner-all form-control">
            	<table>
				<tr>
				<td>
				 <input value="" type="text" class="required datepicker" id="startdate" name="startdate">
				</td>
				<td> s.d.
				<input value="" type="text" class="required datepicker" id="enddate" name="enddate">/select>
				</td>
				</tr>
				</table>
            </div>
            <label for="" class="ui-helper-reset label-control">&nbsp;</label>
            <div class="ui-corner-all form-control">
            	<button onclick="javascript:window_open('<?php echo BASE_URL ?>pages/laporan/report_beli.php?action=preview&id='+$('#project_id').val())" class="btn" type="button">Preview</button>
            </div>
       	</form>
   	</div>
</div>
