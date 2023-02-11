<?php require_once '../../include/config.php' ?>
<?php
	require_once '../../include/print_jual.php';
	if(isset($_GET['action']) && strtolower($_GET['action']) == 'preview') {
		$start = $_GET['start'];
		$end   = $_GET['end'];		
		$s = $db->prepare("SELECT count(j.id_trans) as qtybon,sum(j.totalqty) as totalqty,sum(j.totalfaktur) as totalfaktur,sum(j.tunai) as tunai,sum(j.transfer) as transfer,sum(j.biaya) as ongkos,sum(j.faktur) as faktur FROM trjual j WHERE (j.tgl_trans between ? and ?)");

		$s->execute(array($start,$end));
		$r = $s->fetch(PDO::FETCH_ASSOC);
		
		//$start = $_GET['start'];
		//$end   = $_GET['end'];
		$p = new Printing;
		$p->customSql("SELECT date_format(j.tgl_trans,'%d-%m-%Y') as tgl_trans, j.kode,p.namaperusahaan as customer,j.totalqty,j.faktur,j.biaya,j.totalfaktur,j.tunai,j.transfer,(j.totalfaktur-(j.tunai+j.kartu)) as piutang FROM trjual j left join tblpelanggan p on (j.id_customer=p.id) WHERE (j.tgl_trans between '$start' and '$end')");
		$p->lbField('Tgl.Transaksi','No.Invoice','Nama Pelanggan','Total Qty','Faktur','Ongkos','Total Faktur','Tunai','Transfer','Saldo');
		//$p->title1('<div style="text-align: center; font-size: 20px; text-transform: uppercase">'.$r['project_name'].'</div>');
		$p->title1('<div style="text-align: center; font-size: 20px; text-transform: uppercase">Laporan Penjualan Toko</div>');
		$p->align('tgl_trans','center');
		$p->width('tgl_trans','30');
		$p->align('customer','left');
		$p->width('customer','50');
		$p->align('kode','center');
		$p->width('kode','40');
		$p->width('totalqty','40');
		$p->align('totalqty','right');
		$p->width('faktur','40');
		$p->align('faktur','right');
		$p->width('biaya','40');
		$p->align('biaya','right');
		$p->width('totalfaktur','80');
		$p->align('totalfaktur','right');
		$p->formater('totalfaktur','number');
		$p->width('tunai','80');
		$p->align('tunai','right');
		$p->formater('tunai','number');
		$p->width('transfer','80');
		$p->align('transfer','right');
		$p->formater('transfer','number');
		$p->width('piutang','80');
		$p->align('piutang','right');
		$p->formater('piutang','number');
		//$p->align('totalfaktur','right');
		$p->title2('Jumlah Nota= '.$r['qtybon'].'&nbsp;&nbsp;&nbsp;&nbsp;Total Qty='.$r['totalqty'].'&nbsp;&nbsp;&nbsp;&nbsp;Tunai='.number_format($r['tunai'],0).'&nbsp;&nbsp;&nbsp;&nbsp;Transfer='.number_format($r['transfer'],0).'&nbsp;&nbsp;&nbsp;&nbsp;Faktur='.number_format($r['faktur'],0).'&nbsp;&nbsp;&nbsp;&nbsp;Ongkos='.number_format($r['ongkos'],0).'&nbsp;&nbsp;&nbsp;&nbsp;Total Faktur = '.number_format($r['totalfaktur'],0));
		
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
        Laporan Penjualan Toko
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
				<input value="" type="text" class="required datepicker" id="enddate" name="enddate">
				</td>
				</tr>
				</table>
            </div>
            <label for="" class="ui-helper-reset label-control">&nbsp;</label>
            <div class="ui-corner-all form-control">
            	<button onclick="javascript:window_open('<?php echo BASE_URL ?>pages/laporan/report_jual.php?action=preview&start='+$('#startdate').val()+'&end='+$('#enddate').val())" class="btn" type="button">Preview</button>
            </div>
       	</form>
   	</div>
</div>
