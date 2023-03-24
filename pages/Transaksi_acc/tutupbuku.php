
<?php

require_once '../../include/config.php';
include "../../include/koneksi.php";

$group_access   = unserialize(file_get_contents('../../GROUP_ACCESS_CACHE'.$_SESSION['user']['group_id']));
$allow_add      = is_show_menu(ADD_POLICY   , TutupBuku, $group_access);
$allow_edit     = is_show_menu(EDIT_POLICY  , TutupBuku, $group_access);
$allow_delete   = is_show_menu(DELETE_POLICY, TutupBuku, $group_access);
$allow_post     = is_show_menu(POST_POLICY  , TutupBuku, $group_access);

?>

<script>
function yec_popup_form(url, grid,title,act){
  if(title === undefined || title == '') {
    title = '&nbsp;';
  }
  if(act === undefined || act == '') {
    act = function(){$('#alert_dialog_form').dialog('close')};
  }
    $.ajax({
      url: url,
      beforeSend: function(){
      },
      success: function(response) {
        if($('#alert_dialog_form').length == 0) {
          $('#buat_form').html('<div title="'+title+'" id="alert_dialog_form" style="width: 880px;">' + response + '</div>');
          $('#alert_dialog_form').dialog({
            modal: true,
            minWidth: 900,
            buttons: {
              'Simpan': function(){				        			
                var id_form = $('#alert_dialog_form').find('form').attr('id');
                yec_ajax_submit(id_form, grid, true);				        								        		
              },
              Close: function(){
                  $(this).dialog('close');				        			 
              }
            },				        				    				       	
          });
        }
        else {
          $('#alert_dialog_form').html(response);
          $('#alert_dialog_form').dialog({
            modal: true,
            minWidth: 900,
            buttons: {
              'Save': function(){				        			
                var id_form = $('#alert_dialog_form').find('form').attr('id');
                yec_ajax_submit(id_form, grid, true);				        								        	
              },
              Close: function(){
                  $(this).dialog('close');				        			 
              }
            },			    
          });		       
        }		        	      
      },
      statusCode: {
      404: function() {
        $('#alert_dialog_form').html('ERROR 404<br />Page Not Found!<br />');
      }
    },
        dataType:'html'  		
    });
    return false;
}
function yec_ajax_submit(form_id, grid, close) {
  var req = $('#'+form_id+' input.required, #'+form_id+' select.required, #'+form_id+' textarea.required');
  var conf=0;
  var alert_message = '';
  $.each(req, function(i,v){
    $(this).removeClass('ui-state-error');
    if($(this).val() == '') {
      var id = $(this).attr('id');
      var label = $("label[for='"+id+"']").text();
      label = label.replace('*','');
      alert_message += '<b>'+label+'</b> required!';
      $(this).addClass('ui-state-error');
      custom_alert(alert_message,'',$(this));		
      conf++;
      return false;
    }		
  })
  if(conf === 0) {
    const date_yec = $("input[id='date_yec']").val();

    const [month, year] = date_yec.split('/');
    const monthIndex = parseInt(month, 10) - 1;
    const dateObj = new Date(year, monthIndex);
    const formattedDate = new Intl.DateTimeFormat('en-US', { month: 'long', year: 'numeric' }).format(dateObj);

    custom_confirm('Apakah anda yakin menutup periode accounting hingga tanggal terakhir di bulan '+ formattedDate +' ? Data yang sudah ditutup tidak bisa diubah lagi.', function() {
      $.ajax({
        url: $('#'+form_id).attr('action'),
        type: $('#'+form_id).attr('method'),
        data: $('#'+form_id).serialize(),
        dataType: 'json',
        beforeSend: function() {
          
        },
        success: function(r) {
          if(r.stat == 1) {
            custom_alert(r.message);
            $('#'+grid).trigger('reloadGrid');
            $('#alert_dialog_form').dialog('close');
          }
          else {
            custom_alert(r.message);
          }					
        },
      });
    });
  }
}
</script>

<div class="btn_box">
  <from id="periodeYEC" method="" action="" class="ui-helper-clearifx">
    <label for="" class="ui-helper-reset label-control">&nbsp;</label>
    <div class="ui-corner-all form-control">
      <button onclick="closeButton()" class="btn" type="button">Close</button>
    </div>
  </from>
</div>

<table id="table_yec"></table>
<div id="pager_table_yec"></div>

<script type="text/javascript">
  $('#periode').datepicker({
    dateFormat: "mm/yy",
  });
  $("#periode").datepicker( 'setDate', 'today' );

  function closeButton(){
    yec_popup_form('<?= BASE_URL ?>pages/Transaksi_acc/tutupbuku_closePass.php','table_yec');
  }

  $(document).ready(function(){
    $('#table_yec').jqGrid({
      url       : '<?= BASE_URL.'pages/Transaksi_acc/tutupbuku_request.php?action=json';?>',
      datatype  : 'json',
      colNames  : ['Periode Tutup Buku','PIC','Tanggal Tutup Buku', 'Detail'],
      colModel  : [
        {name:'periodeTutupBuku', index: 'periodeTutupBuku', align: 'center', width:100, searchoptions: {sopt:['cn']}},
        {name:'pic', index: 'pic', align: 'center', width:100, searchoptions: {sopt:['cn']}},
        {name:'tanggalTutupBuku', index: 'tanggalTutupBuku', align: 'center', width:100, searchoptions: {sopt:['cn']}},
        {name:'detail', index: 'detail', align: 'center', width:30, searchoptions: {sopt:['cn']}},
      ],
      rowNum        : 20,
      rowList       : [10, 20, 30],
      pager         : '#pager_table_yec',
      sortname      : 'id',
      autowidth     : true,
      height        : '460',
      viewrecords   : true,
      rownumbers    : true,
      sortorder     : 'desc',
      caption       : "Tutup Buku",
      ondblClickRow : function(rowid){
        alert(rowid);
      },
    });
    $('#table_yec').jqGrid('navGrid', '#pager_table_yec', {edit:false, add:false, del:false, search:false});
  });
</script>