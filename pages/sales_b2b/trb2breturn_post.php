<?php if(isset($_GET['action']) && strtolower($_GET['action']) == 'post'){ ?>

<div class="ui-widget ui-form">
  <div class="ui-widget-header ui-corner-top padding5">B2B Return Post</div>
  <div class="ui-widget-content ui-corner-bottom">
    <form id="b2breturn_post_form" method="post" action="pages/sales_b2b/trb2breturn.php?action=post_process" class="ui-helper-clearfix">
      <input type="hidden" id="id_b2breturn" name="id_b2breturn" value="<?= $_GET['id'] ?>" />
      <input type="hidden" id="post_value" name="post_value" value="1" />
      <label for="date_aplist" class="ui-helper-reset label-control">Tanggal Pembayaran</label>
      <div class="ui-corner-all form-control">
        <input value="" type="text" class="required datepicker" id="date_b2breturn_post" name="date_b2breturn_post" readonly>
      </div>
      <label for="akun_kredit_b2breturn_post" class="ui-helper-reset label-control">Akun Kredit</label>
      <div class="ui-corner-all form-control">
        <input value="" type="text" class="required" id="akun_kredit_b2breturn_post" name="akun_kredit_b2breturn_post">	
      </div>
    </form>
  </div>
</div>

<script>
  $('#date_b2breturn_post').datepicker({
    dateFormat: "dd/mm/yy",
  });

  $('#akun_kredit_b2breturn_post').autocomplete("pages/sales_b2b/trb2breturn_get_akun.php?action=getkredit", {width: 400});
</script>

<?php } else if (isset($_GET['action']) && strtolower($_GET['action']) == 'post') { ?> 

  <div class="ui-widget ui-form">
  <div class="ui-widget-header ui-corner-top padding5">B2B Return Unpost</div>
  <div class="ui-widget-content ui-corner-bottom">
    <form id="b2breturn_post_form" method="post" action="pages/sales_b2b/trb2breturn.php?action=post_process" class="ui-helper-clearfix">
      <input type="hidden" id="id_b2breturn" name="id_b2breturn" value="<?= $_GET['id'] ?>" />
      <input type="hidden" id="post_value" name="post_value" value="0" />
      <label class="ui-helper-reset label-control">Apakah anda yakin sekali ?</label>
    </form>
  </div>
</div>

<?php } ?>

