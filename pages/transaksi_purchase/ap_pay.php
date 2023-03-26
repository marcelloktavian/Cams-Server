<div class="ui-widget ui-form">
    <div class="ui-widget-header ui-corner-top padding5">
        <?php
			echo 'PAY AP';			
        ?>
    </div>
    <div class="ui-widget-content ui-corner-bottom">
        <form id="trolndo_pass_form" method="post" action="<?php echo BASE_URL ?>#" class="ui-helper-clearfix">

          <label for="akun_kredit" class="ui-helper-reset label-control">Akun Kredit</label>
          <div class="ui-corner-all form-control">
              <input value="" type="text" class="required" id="akun_kredit" name="akun_kredit">	
          </div>

          <label for="tanggal_pelunasan_ap" class="ui-helper-reset label-control">Tanggal Pelunasan</label>
          <div class="ui-corner-all form-control">
              <input value="" type="text" class="required " id="tanggal_pelunasan_ap" name="tanggal_pelunasan_ap">	
          </div>

        </form>
    </div>
</div>

<script>
  $('#tanggal_pelunasan_ap').datepicker({
    dateFormat: "dd/mm/yy",
  });
  $("#tanggal_pelunasan_ap").datepicker( 'setDate', 'today' );
</script>