<link rel="stylesheet" type="text/css" href="assets/css/styles.css" />
<div class="ui-widget ui-form">
  <div class="ui-widget-header ui-corner-top padding5">
    Setting Account
    <?php
//        	$action = strtoupper($_GET['action']);
		//	echo $action .' user_group';
		//-------------------fungsi buat bikin nomer---------------------	
    include("../../include/config.php");
    $group_acess = unserialize(file_get_contents("../../GROUP_ACCESS_CACHE".$_SESSION['user']['group_id']));
    $allow_add=is_show_menu(ADD_POLICY, SettingAkun, $group_acess);



	//$id_registrasi = getnewnotrxwait();

    ?>
  </div>
  <div class="ui-widget-content ui-corner-bottom">
    <form id="frm-setting_akun" method="post" action="<?php echo BASE_URL ?>pages/master_biaya/settingakun.php?action=process" class="ui-helper-clearfix">


     <!-- <label for="name" class="ui-helper-reset label-control">Nama Group :</label> -->
     <!-- <div class="ui-corner-all form-control">

       <select class="required" name="group_id" id="group_id">
         <option value="-1">-pilih-</option>
         <?php
         $query = $db->query("SELECT * FROM `group`");
         $rows = $query->fetchAll(PDO::FETCH_ASSOC);
         foreach($rows as $r) {
							$select = '';//isset($row['group_id']) && $row['group_id'] == $r['id'] ? 'selected' : ''; 
							echo '<option '.$select.' value="'.$r['id'].'">'.$r['nama'].'</option>';
						}
           ?>
         </select>
       </div> -->
<div style="border: 1px solid rgb(204, 204, 204); padding: 5px; overflow: auto; width: 1000px; height: 350px;background-color: rgb(255, 255, 255);">
       <table id="table_setting_akun"  width='100%' style="border: solid 1px;margin-bottom: 10px">
        <thead>
          <tr>
            <th rowspan="2">Menu</th>
            <th colspan=7>Account</th>
          </tr>
          <tr>
            <th width="5%">Debet</th> 
            <th width="5%">Kredit</th>
          </tr>

        </thead>
        
          <tbody id="tbl-body-setting_akun">

          </tbody>
      </table> 
      </div> 
      <?php
      $statusToko = '';
    $getStat = $db->prepare("SELECT * FROM tbl_status LIMIT 1");
    $getStat->execute();
    $stat = $getStat->fetchAll();
    foreach ($stat as $stats) {
        $statusToko = $stats['status'];
    }
    
    if ($statusToko == 'Tutup') {
        echo '<button type="button" onclick="javascript:custom_alert(\'Maaf, Toko Sudah Tutup\')" class="btn">Save</button>';
    }else{
      if($allow_add){
        echo '<button id="btn-simpan" type="button" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" role="button" aria-disabled="false"><span class="ui-button-text">Save</span></button>';
      }}
      ?>
    </form>
  </div>
</div>
<style type="text/css">
  body.wait *, body.wait
  {
    cursor: progress !important;
  }
</style>
<script>
  $("#group_id").on('change',function(){
//       alert('here') ;
// loadData();
});

  $("#btn-simpan").on('click',function(){
    $.ajax({
      url :'./pages/master_biaya/simpanDataSettingAkun.php',
      data : $('#frm-setting_akun').serializeArray(),
      type : 'POST',
      success : function(d){
        alert('Data telah disimpan');
        loadData();
      },
      error : function (){
        alert('Data gagal disimpan');
      }
    });


  });

  function loadData(){
    // var v_group_id = $("#group_id").val();
    $('#tbl-body-setting_akun').load('./pages/master_biaya/ambilDataSettingAkun.php');
  }
  loadData();

  $(document).ajaxStart(function ()
  {
    $('body').addClass('wait');

  }).ajaxComplete(function () {

    $('body').removeClass('wait');

  });

</script>
