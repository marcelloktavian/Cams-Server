<style>
  #sisa_piutang:focus{
    outline:none;
  }
</style>

<div class="ui-widget ui-form">
  <div class="ui-widget-header ui-corner-top padding5">AR Credit Pay</div>
  <div class="ui-widget-content ui-corner-bottom">
    <form id="trolndo_pass_form" method="post" action="pages/sales_online/trolnarcredit.php?action=pembayaran" class="ui-helper-clearfix">
      <label for="date_arcrpay" class="ui-helper-reset label-control">Tanggal Pembayaran</label>
      <div class="ui-corner-all form-control">
        <input value="" type="text" class="required datepicker" id="date_arcrpay" name="date_arcrpay" readonly>
      </div>
      <label for="akun_debet_arcrpay" class="ui-helper-reset label-control">Akun Debet</label>
      <div class="ui-corner-all form-control">
        <input value="" type="text" class="required" id="akun_debet_arcrpay" name="akun_debet_arcrpay">	
      </div>
      <label for="payment_arcrpay" class="ui-helper-reset label-control">Payment</label>
      <div class="ui-corner-all form-control">
        <input value="" type="text" class="required" id="payment_arcrpay" name="payment_arcrpay">	
      </div>
      <label for="sisa_piutang" class="ui-helper-reset label-control">Sisa Piutang</label>
      <div class="ui-corner-all form-control">
        <input value="<?= number_format($_GET['sisa_piutang'], 0) ?>" type="text" class="required" id="sisa_piutang" name="sisa_piutang" style="background-color:#eae8dd;" readonly>	
      </div>
      <input id="no_akun" name="no_akun" value="<?= $_GET['no_akun'] ?>" hidden>
    </form>
  </div>
</div>

<script>
  $('#date_arcrpay').datepicker({
    dateFormat: "dd/mm/yy",
  });
  $("#date_arcrpay").datepicker( 'setDate', 'today' );

  $('#akun_debet_arcrpay').autocomplete("pages/sales_online/trolnarcredit_lookup_akun.php?action=getdebet", {width: 400});

  $('#payment_arcrpay').on('keydown',function(){
    if(parseInt($('#payment_arcrpay').val()) > parseInt($('#sisa_piutang').val())){
      $('#payment_arcrpay').val($('#sisa_piutang').val());
    }
  });

  $('#payment_arcrpay').on('keyup',function(){
    if(parseInt($('#payment_arcrpay').val()) > parseInt($('#sisa_piutang').val())){
      $('#payment_arcrpay').val($('#sisa_piutang').val());
    }
  });
</script>