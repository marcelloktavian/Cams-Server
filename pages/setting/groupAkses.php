<link rel="stylesheet" type="text/css" href="assets/css/styles.css" />
<div class="ui-widget ui-form">
  <div class="ui-widget-header ui-corner-top padding5">
    Master Group Akses
    <?php
//        	$action = strtoupper($_GET['action']);
		//	echo $action .' user_group';
		//-------------------fungsi buat bikin nomer---------------------	
    include("../../include/config.php");
    $group_acess = unserialize(file_get_contents("../../GROUP_ACCESS_CACHE".$_SESSION['user']['group_id']));
    // var_dump($group_acess)
    $allow_add=is_show_menu(ADD_POLICY, GroupAkses, $group_acess);



	//$id_registrasi = getnewnotrxwait();

    ?>
  </div>
  <div class="ui-widget-content ui-corner-bottom">
    <form id="frm-group_access" method="post" action="<?php echo BASE_URL ?>pages/setting/userGroup.php?action=process" class="ui-helper-clearfix">


     <label for="name" class="ui-helper-reset label-control">Nama Group :</label>
     <div class="ui-corner-all form-control">

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
       </div>
<div style="border: 1px solid rgb(204, 204, 204); padding: 5px; overflow: auto; width: 750px; height: 350px;background-color: rgb(255, 255, 255);">
       <table id="table_menu"  width='100%' style="border: solid 1px;margin-bottom: 10px">
        <thead>
          <tr>
            <th rowspan="2">Menu</th>
            <th colspan=7>Hak Akses</th>
          </tr>
          <tr>
            <th width="5%"><input type="checkbox" id="viewAll">Lihat</th> 
            <th width="5%"><input type="checkbox" id="addAll">Tambah</th>
            <th width="5%"><input type="checkbox" id="editAll">Edit</th>
            <th width="5%"><input type="checkbox" id="deleteAll">Hapus</th>
            <th width="5%"><input type="checkbox" id="postAll">Post</th>
          </tr>

        </thead>
        
          <tbody id="tbl-body">


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
loadData();
});

  $("#viewAll").click(function () {
    $(".checkBoxView").prop('checked', $(this).prop('checked'));
  });
  $("#deleteAll").click(function () {
    $(".checkBoxDelete").prop('checked', $(this).prop('checked'));
  });
  $("#addAll").click(function () {
    $(".checkBoxAdd").prop('checked', $(this).prop('checked'));
  });
  $("#editAll").click(function () {
    $(".checkBoxEdit").prop('checked', $(this).prop('checked'));
  });
  $("#postAll").click(function () {
    $(".checkBoxPost").prop('checked', $(this).prop('checked'));
  });
  $("#btn-simpan").on('click',function(){
    $.ajax({
      url :'./pages/setting/simpanDataGroupAccess.php',
      data : $('#frm-group_access').serializeArray(),
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
    var v_group_id = $("#group_id").val();
    $('#tbl-body').load('./pages/setting/ambilDataGroupAkses.php',{group_id:v_group_id});
    $("#viewAll").prop('checked', false);
    $("#addAll").prop('checked', false);
    $("#editAll").prop('checked', false);
    $("#deleteAll").prop('checked', false);
    $("#postAll").prop('checked', false);
  }
  loadData();

  $(document).ajaxStart(function ()
  {
    $('body').addClass('wait');

  }).ajaxComplete(function () {

    $('body').removeClass('wait');

  });

</script>
