<div class="ui-widget ui-form">
    <div class="ui-widget-header ui-corner-top padding5">
        <?php
            $action = strtoupper($_GET['action']);
            echo 'PASSWORD REQUIRED';			
        ?>
    </div>
    <div class="ui-widget-content ui-corner-bottom">
        <form id="trolndo_pass_form" method="post" action="<?php echo BASE_URL ?>pages/Transaksi_acc/penyusutan.php?action=process_passhapus" class="ui-helper-clearfix">
			  <label for="password" class="ui-helper-reset label-control">Password</label>
            <div class="ui-corner-all form-control">
                <input value="<?php echo $_GET['nama_aset'] ?>" type="hidden" id="nama_aset" name="nama_aset">
                <input value="" type="password" class="required" id="pass_jm_edit" name="pass_jm_edit">
            </div>
        </form>
    </div>
</div>
<!-- 
<style>
  .hideee{
    display: none !important;
  }
</style>

<script>
  $(document).ready(function(){
    setTimeout(function() {
      $('div.ui-dialog-buttonpane.ui-widget-content.ui-helper-clearfix > div.ui-dialog-buttonset > button:first-child').parent().prepend('<button id="submit_button_pass" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only"><span class="ui-button-text">Save</span></button>');

      $('#submit_button_pass').click(function(){
      $('#trolndo_pass_form').submit();

      $('#alert_dialog_form').dialog('close');
    });
    }, 10);
    setTimeout(function() {
      $('div.ui-dialog-buttonpane.ui-widget-content.ui-helper-clearfix > div.ui-dialog-buttonset > button:first-child').remove();
    }, 2);
    
  });
</script> -->