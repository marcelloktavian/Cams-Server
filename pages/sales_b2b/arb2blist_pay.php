<style>
  #sisa_piutang:focus{
    outline: none;
  }
</style>

<div class="ui-widget ui-form">
  <div class="ui-widget-header ui-corner-top padding5">AR B2B Pay</div>
  <div class="ui-widget-content ui-corner-bottom">
    <form id="trolndo_pass_form" method="post" action="pages/sales_b2b/arb2blist.php?action=pembayaran" class="ui-helper-clearfix">
      <label for="date_arb2blist" class="ui-helper-reset label-control">Tanggal Pembayaran</label>
      <div class="ui-corner-all form-control">
        <input value="" type="text" class="required datepicker" id="date_arb2blist" name="date_arb2blist" readonly>
      </div>
      <label for="akun_debet_arb2blist" class="ui-helper-reset label-control">Akun Debet</label>
      <div class="ui-corner-all form-control">
        <input value="" type="text" class="required" id="akun_debet_arb2blist" name="akun_debet_arb2blist">	
      </div>
      <label for="payment_arb2blist" class="ui-helper-reset label-control">Payment</label>
      <div class="ui-corner-all form-control">
        <input value="" type="text" class="required" id="payment_arb2blist" name="payment_arb2blist" onkeypress="return event.charCode >= 48 && event.charCode <= 57">	
      </div>
      <label for="sisa_piutang_arb2blist" class="ui-helper-reset label-control">Sisa Piutang</label>
      <div class="ui-corner-all form-control">
        <input value="<?= number_format($_GET['sisa_piutang'], 0) ?>" type="text" class="required" id="" name="" style="background-color:#eae8dd;" readonly>
        <input value="<?= round($_GET['sisa_piutang']) ?>" type="hidden" class="required" id="sisa_piutang_arb2blist" name="sisa_piutang_arb2blist" style="background-color:#eae8dd;" readonly>
      </div>
      <input id="id_akun_arb2blist" name="id_akun_arb2blist" value="<?= $_GET['id_akun'] ?>" hidden>
      <input id="no_telp_arb2blist" name="no_telp_arb2blist" value="<?= $_GET['no_telp'] ?>" hidden>
    </form>
  </div>
</div>

<script>
  $('#date_arb2blist').datepicker({
    dateFormat: "dd/mm/yy",
  });
  $("#date_arb2blist").datepicker( 'setDate', 'today' );

  $('#akun_debet_arb2blist').autocomplete("pages/sales_b2b/arb2b_akun_list.php", {width: 400});

  $('#payment_arb2blist').on('keydown',function(){
    if(parseInt($('#payment_arb2blist').val()) > parseInt($('#sisa_piutang_arb2blist').val())){
      $('#payment_arb2blist').val($('#sisa_piutang_arb2blist').val());
    }
  });

  $('#payment_arb2blist').on('keyup',function(){
    if(parseInt($('#payment_arb2blist').val()) > parseInt($('#sisa_piutang_arb2blist').val())){
      $('#payment_arb2blist').val($('#sisa_piutang_arb2blist').val());
    }
  });
</script>