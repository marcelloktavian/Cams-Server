<?php require_once '../../include/config.php' ?>
<?php
$group_acess = unserialize(file_get_contents("../../GROUP_ACCESS_CACHE".$_SESSION['user']['group_id']));
if(isset($_GET['action']) && strtolower($_GET['action']) == 'get_pelanggan') {
  $sql_products ="SELECT a.* FROM `mst_coa` a ";
  $query = '';
  $countnya = 0;
  $q = $db->query($sql_products.' where a.deleted=0 ORDER BY noakun ASC');
  $data1 = $q->fetchAll(PDO::FETCH_ASSOC);
  foreach($data1 as $line) {
    if ($countnya == 0) {
      $query .= "(SELECT id, noakun, nama, jenis from mst_coa where id='".$line['id']."' ORDER BY noakun ASC)";
    } else {
      $query .= " UNION ALL (SELECT id, noakun, nama, jenis from mst_coa  where id='".$line['id']."' ORDER BY noakun ASC) ";
    }
    $countnya++;
    $q2 = $db->query("SELECT * FROM det_coa WHERE id_parent='".$line['id']."' ORDER by noakun ASC");
    $data2 = $q2->fetchAll(PDO::FETCH_ASSOC);
    foreach($data2 as $line2) {
      $query .= " UNION ALL (SELECT '' as id, noakun, nama, '' as jenis from det_coa where id='".$line2['id']."' ORDER BY noakun ASC) ";
    }
  }
  $p = $db->query($query);
  $rows = $p->fetchAll(PDO::FETCH_ASSOC);
  $response = array();
  $response[] = array('key'=>'','value'=>'--Pilih No Akun--');
  foreach ($rows as $r) { 
      $response[] = array('key'=>$r['noakun'],'value'=>$r['noakun'].' ('.$r['nama'].')');
  }
  echo json_encode($response);
  exit;
}

$qlog = $db->query("SELECT * FROM tbl_logyec WHERE closed=1");
$datalog = $qlog->fetchAll(PDO::FETCH_ASSOC);
$month='';
$year='';
foreach($datalog as $linelog) {
  $month = $linelog['month'];
  $year = $linelog['year'];
}
?>
<input type="hidden" name="month_arsipbuku" id="month_arsipbuku" value="<?=$month?>">
<input type="hidden" name="year_arsipbuku" id="year_arsipbuku" value="<?=$year?>">
<div class="ui-widget ui-form" style="margin-bottom:5px">
  <div class="ui-widget-header ui-corner-top padding5">
    Print Buku Besar
  </div>
    <div class="ui-widget-content ui-corner-bottom">
      <form id="report_project_form" method="" action="" class="ui-helper-clearfix">
        <label for="" class="ui-helper-reset label-control">Bulan</label>
        <div class="ui-corner-all form-control">
          <table>
            <tr>
              <td>
                <input value="" type="text" class="required datepicker" id="startdate_arsip_bukubesarrpt" name="startdate_arsip_bukubesarrpt">
              </td>
            </tr>
          </table>
        </div>
        <label for="" class="ui-helper-reset label-control">No Akun</label>
        <div class="ui-corner-all form-control">
          <table>
            <tr>
              <td> 
                <select name="noakun_arsipbukubesar_id" id="noakun_arsipbukubesar_id" class="my-select"></select>
              </td>
            </tr>
          </table>
        </div>
        <label for="" class="ui-helper-reset label-control">&nbsp;</label>
      <div class="ui-corner-all form-control">
        <button onclick="printArsipBukuBesar()" class="btn" type="button">Cetak</button>
        <button onclick="printArsipBukuBesarExcel()" class="btn" type="button">Excel</button>
      </div>
    </form>
  </div>
</div>

<script type="text/javascript">
  $('#startdate_arsip_bukubesarrpt').datepicker({
		dateFormat: "mm/yy",
	});
	$( "#startdate_arsip_bukubesarrpt" ).datepicker( 'setDate', 'today' );

	function printArsipBukuBesar() {
    var month = $("#month_arsipbuku").val();
    var year = $("#year_arsipbuku").val();
		var startdate = $('#startdate_arsip_bukubesarrpt').val();
		var akun = $('#noakun_arsipbukubesar_id').val();

    if(month != '' && year != ''){
      var ex1 = startdate.split("/");

      if(month < 10){
        month = '0'+month;
      }
      if(ex1[0] <= month && ex1[1] <= year){
        alert('Tanggal Sudah Tutup Buku');
      }else{
        window_open('<?php echo BASE_URL ?>pages/report_acc/rpt_arsip_bukubesar.php?action=print&start='+startdate+'&akun='+akun);
      }
    }else{
      window_open('<?php echo BASE_URL ?>pages/report_acc/rpt_arsip_bukubesar.php?action=print&start='+startdate+'&akun='+akun);
    }
	}

  function printArsipBukuBesarExcel() {
    var month = $("#month_arsipbuku").val();
    var year = $("#year_arsipbuku").val();
    var startdate = $('#startdate_arsip_bukubesarrpt').val();
    var akun = $('#noakun_arsipbukubesar_id').val();

    if(month != '' && year != ''){
      var ex1 = startdate.split("/");

      if(startdate <= (month+"/"+year)){
        alert('Tanggal Sudah Tutup Buku');
      }else{
        window_open('<?php echo BASE_URL ?>pages/report_acc/rpt_arsip_bukubesar.php?action=excel&start='+startdate+'&akun='+akun);
      }
    }else{
      window_open('<?php echo BASE_URL ?>pages/report_acc/rpt_arsip_bukubesar.php?action=excel&start='+startdate+'&akun='+akun);
    }
	}

  load_pelanggan = function (){
    list_pelanggan = document.getElementById('noakun_arsipbukubesar_id');
    $.ajax({
      url:'<?=BASE_URL?>pages/report_acc/arsip_bukubesar_rpt.php?action=get_pelanggan',
      success:function(result) {
        $("#noakun_arsipbukubesar_id").empty();
        result = JSON.parse(result);

        for (a in result) {					 
          $('#noakun_arsipbukubesar_id').append(new Option(result[a].value,result[a].key));
        }
        $('#noakun_arsipbukubesar_id').select2().trigger('change');
      }
    });
  }

  $(document).ready(function() { 
    load_pelanggan();
    $("#noakun_arsipbukubesar_id").select2({
      width: '397px', 
      dropdownAutoWidth : true
    });           
  });
</script>