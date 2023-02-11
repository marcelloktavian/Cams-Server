<?php require_once 'include/config.php' ?>
<?php require_once 'include/ajax_call.php' ?>
<!DOCTYPE html>
<html>
    <head>
        <title>CAMS</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">        
        <!--
		<link href="assets/css/sunny/jquery-ui-1.9.2.custom.css" rel="stylesheet" media="screen">
        <link href="assets/css/sunny/ui.layout.css" rel="stylesheet" media="screen">
		-->
		<link href="assets/css/pepper-grinder/jquery.ui.theme.css" rel="stylesheet" media="screen">
        <link href="assets/css/pepper-grinder/jquery-ui.css" rel="stylesheet" media="screen">
		
		<link href="assets/css/ui.jqgrid.css" rel="stylesheet" media="screen">
		<link href="assets/css/jquery.treeview.css" rel="stylesheet" media="screen">
		<link href="assets/css/style.css" rel="stylesheet" media="screen">
    </head>
    <body>
    	
    	<div class="ui-layout-north" id="northPanel" > 
			<?php			
				echo '<div id="topLeft" class="pullLeft" style="font-size: 14px">Camou Administration System (CAMS)</div>';	
			?> 
		</div>
		
		<div class="ui-layout-west" id="westPanel">
			
			<div id="wrap-app">		
				<?php
					include 'menu.php';
				?>
			</div>
			<h3><a href="<?php echo BASE_URL ?>logout.php">LOG OUT</a></h3>
		</div>
		
		<div class="ui-layout-center" id="centerPanel">
			<?php
				include 'tabs.php';
			?>
		</div>

		<div class="ui-layout-south" id="southPanel">
			<span style="font-weight: bold; font-size: 11px; float: right; margin-right: 15px">Copyright 2018 FootPrint.com</span>
		</div>
    	<div id="buat_form"></div>
    	<div id="buat_dialog"></div>
    	<script type="text/javascript" src="assets/js/jquery-1.10.2.min.js"></script>
    	<script type="text/javascript" src="assets/js/jquery-migrate-1.2.1.min.js"></script>
    	<script type="text/javascript" src="assets/js/jquery.livequery.min.js"></script>
    	<script type="text/javascript" src="assets/js/jquery-ui-1.9.2.custom.min.js"></script>
    	<script type="text/javascript" src="assets/js/jquery.layout-latest.min.js"></script>
    	<script type="text/javascript" src="assets/js/jquery.layout-latest.min.js"></script>
    	<script type="text/javascript" src="assets/js/i18n/grid.locale-id.js"></script>
    	<script type="text/javascript" src="assets/js/jquery.jqGrid.min.js"></script>
    	<script type="text/javascript" src="assets/js/jquery.cookie.js"></script>
    	<script type="text/javascript" src="assets/js/jquery.treeview.js"></script>
    	<script type="text/javascript" src="assets/js/jquery.iframe-post-form.js"></script>
    	<script type="text/javascript">
    	$(function(){
    		var myLayout;
    		myLayout =  $('body').layout({
				 north: {
						spacing_open:1
					,	minSize: 30
					,	togglerLength_open:0
					,	togglerLength_closed:-1
					,	resizable:false
					,	slidable:false 
					,	paneClass:"northPanel"
				},west:{
						spacing_open:5
					,	spacing_closed:12
					,	fxName:"slide"
				    ,   fxSpeed:"fast"
				},south:{
						spacing_open:1
					,	minSize: 20
					,	togglerLength_open:0
					,	togglerLength_closed:-1
					,	resizable:false
					,	slidable:false 
					,	paneClass:"northPanel"
				}
			});
			
			$("#wrap-app").accordion({	
				collapsible: false,
				heightStyle: "content",
				/*activate: function( event, ui ) {
					id 	  = $(ui.newHeader).attr('id');				  	
			  	},
			  	beforeActivate: function( event, ui ) {
			  		
			  	},
			  	create: function( event, ui ) {
				  	id 	  = $(ui.header).attr('id');				  	
			  	}*/
			});
			$("#browser").treeview({
				collapsed: true,
				unique: true,
				persist: "cookie",
			});
			$("#browser2").treeview({
				collapsed: true,
				unique: true,
				persist: "cookie",
			});
			var tabTemplate = "<li><a href='#{href}'>#{label}</a> <span class='ui-icon ui-icon-close'>Remove Tab</span></li>";		
			var maintab =jQuery('#tabsContent','#centerPanel').tabs({
		        add: function(e, ui) {
		            $(ui.tab).parents('li:first')
		                .append('<span class="ui-tabs-close ui-icon ui-icon-close" title="Close Tab"> </span>')
		                .find('span.ui-tabs-close')
		                .click(function() {
		                    maintab.tabs('remove', $('li', maintab).index($(this).parents('li:first')[0]));
		                });
		            maintab.tabs('select', '#' + ui.panel.id);
		        },
		        activate: function(e, ui) {
			        /*var tabHref = $(ui.newTab).find('a').attr('href');				
					
			        if(tabHref.length > 0) {
			        	tabHref = tabHref.split('_');
			        	var tabId = tabHref[1];
			        	var url = "infomodule";
			        	$.post(url,{moduleId:tabId},function(data){
							$("#topLeft").html( data );
				        });
			        }*/
				}
		    });
		    
		    $("span.file").live("click", function() {
			    var labelTab 	= $(this).html();
	    	 	var idTab	 	= "#tab_"+$(this).attr('id');
		    	var pathModul	= $(this).attr('rel');
		    	if($(idTab).html() != null ) {
					maintab.tabs('select', idTab);
				} else {
					maintab.tabs('add', idTab, labelTab);	
					$(idTab, "#tabsContent").html('<span class="loaderMini">&nbsp;</span>');
					<?php 
					$ajax_call = new ajax_call;
					echo $ajax_call->ajax_request( 
						array(
							'url'=>'pathModul',
							'type'=>'\'GET\'',
							'data'=>'{}',
							'success'=>'function(data){ $(idTab,"#tabsContent").html(data); }',
						)
					);
					?>
				}
		   	});
		   	$('#browser span.folder, #browser span.file').css('cursor','pointer');
		   	$('.btn').livequery(function(){
		   		$(this).button();
		   	});
		   	$('.datepicker').livequery(function(){
		   		$(this).datepicker({
		   			dateFormat: 'yy-mm-dd',
		   			changeMonth: true,
		   			changeYear: true,
		   			showOn: "button",
					buttonImage: "<?php echo BASE_URL; ?>assets/images/calendar.gif",
					buttonImageOnly: true,
		   		});
		   	});
		   	$('.number').live('keydown', function(event){
		   		// backspace, delete, tab, escape, enter and .
		        if ( $.inArray(event.keyCode,[46,8,9,27,13,190]) !== -1 ||
		             //Ctrl+A
		            (event.keyCode == 65 && event.ctrlKey === true) || 
		             //home, end, left, right
		            (event.keyCode >= 35 && event.keyCode <= 39)) {		                 
		                 return;
		        }
		        else {
		            // Ensure that it is a number and stop the keypress
		            if (event.shiftKey || (event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105 )) {
		                event.preventDefault(); 
		            }   
		        }
		   	});
		   	$(".jtable th").livequery(function(){
		   		$(this).each(function() {
					$(this).addClass("ui-state-default");
				});
		   	})
			$(".jtable td").livequery(function(){
				$(this).each(function() {
					$(this).addClass("ui-widget-content");
				});
			});
		     	
			//dimatikan dulu buat testing di pembelian
			//bisa diganti pake # buat separatornya
			//$('.province_id_project').live('change', function(){
			$('#province_id').live('change', function(){
				//var ix = $('.province_id_project').index($(this));
				var ix = $('#province_id').index($(this));
				var dis = $(this);
				var disval = dis.val();
				//custom_alert('hai='+r.status+', value='+disval);
				
				$.ajax({
					url: "<?php echo BASE_URL; ?>pages/project.php?action=get_city&pid="+disval,
					type: 'get',
					dataType: 'json',
					
					beforeSend: function() {
						$('.city_id_box').eq(ix).html('Please Wait..');
					},
					
					success: function(r) {
						//if(r.status == 1) {
						if(r.st == 1) {
							$('.city_id_box').eq(ix).html(r.resp);
							//alert('hai sukses broo,status='+r.status+',Respon='+r.resp);
							alert('hai sukses broo,status='+r.st+',Respon='+r.resp);
						}
						else {
							alert(r.message);
						}
					},
					error:function() { alert('error uy');}
				})
			});			
			 
			//$('.barang_id_project').live('change', function(){
			$('.barang_id').live('change', function(){
				//var ix = $('.barang_id_project').index($(this));
				var ix = $('.barang_id').index($(this));
				var dis = $(this);
				var disval = dis.val();
				//custom_alert('hai='+r.status+', value='+disval);
				
				$.ajax({
					url: "<?php echo BASE_URL; ?>pages/beli.php?action=get_harga&pid="+disval,
					type: 'get',
					dataType: 'json',
					beforeSend: function() {
						$('.harga_id_box').eq(ix).html('Silakan tunggu..');
						//alert('hai='+r.status);
					},
					success: function(r) {
						if(r.status == 1) {
						//if(r.st == 1) {
							$('.harga_id_box').eq(ix).html(r.resp);
							//alert('hai sukses'+r.resp);
						}
						else {
							alert(r.message);
						}
					},
					error:function(r) { 
					//alert('error uy,status='+r.st+',pesan='+r.message);
					alert('error uy,status='+r.status+',pesan='+r.message);
					}
				})
			});			
			
			
			$('.qty').live('blur', function(){
				var ix = $('.barang_id').index($(this));
				var ih = $('.harga_id').index($(this));
				
				//var n = $("span."+table_id+"_num:last").text();
				var n = $("span.tblItem_num:last").text();
			    var no = parseInt(n);
			
				//var dis = $(this);
				var dis = $('.harga_id');
				var disval = dis.val();
				//var disval = ix.val();sq
				//custom_alert('harga='+disval);
				//custom_alert('row='+no.toString());
				//custom_alert('harga='+$('.harga_id[n]').val());
				//validasi qty
				//menghitung nilai subtotal dari grid 
				var sum = 0;
				var subtotal=0
				$('.qty').each(function(){
				    sum += parseInt($(this).val());
				    //subtotal = parseInt(10 * $('.harga_id').val());
					//subtotal=
				});
				
				var qty = sum.toString();
				var sub = subtotal.toString();
				$('#total_qty').val(qty); 						
				$('.subtotal').val(sub); 						
				
			});
			
			
			$('.jumlah_angket').live('blur', function(){
				//menghitung nilai subtotal dari grid 
				var sum = 0;
				$('.jumlah_angket').each(function(){
				    sum += parseInt($(this).val());
					
				});
				
				var qty = sum.toString();
				$('#total_qty').val(qty); 
				
				if(parseInt($('#total_qty').val()) < sum) {
					custom_alert('Total Qty Tidak Memenuhi!','',$(this));			
				 }
				
			});
			
		});
		
		function add_row(table_id) {
			var row = $('table#'+table_id+' tbody tr:last').clone();
			$("span."+table_id+"_num:first").text('1');
			var n = $("span."+table_id+"_num:last").text();
			var no = parseInt(n);
			var c = no + 1;
			$('table#'+table_id+' tbody tr:last').after(row);
			$('table#'+table_id+' tbody tr:last input').val("");
			
			//$('table#'+table_id+' tbody tr:last input.datepicker').removeAttr('id').removeClass("hasDatepicker").datepicker({dateFormat: 'yy-mm-dd', changeYear: true, changeMonth:true});;
		    //$('table#'+table_id+' tbody tr:last input.datepicker_exp').removeAttr('id').removeClass("hasDatepicker").datepicker({dateFormat: 'yy-mm-dd', changeYear: true, changeMonth:true});;
			
			$('table#'+table_id+' tbody tr:last div').text("");
		    $('table#'+table_id+' tbody tr:last span.span_clear').text("");
		    $('table#'+table_id+' tbody tr:last select').prop("selectedIndex", 0);
			$("span."+table_id+"_num:last").text(c);
			$('table#'+table_id+' tbody tr:last span.city_id_box').html("<select name='city_id[]' id='city_id' class='city_id required'><option value=''>--Choose--</option></select>");
									
		}
		
		function add_row_purchase(table_id) {
			var row = $('table#'+table_id+' tbody tr:last').clone();
			$("span."+table_id+"_num:first").text('1');
			var n = $("span."+table_id+"_num:last").text();
			var no = parseInt(n);
			var c = no + 1;
			$('table#'+table_id+' tbody tr:last').after(row);
			$('table#'+table_id+' tbody tr:last input').val("");
			
			//$('table#'+table_id+' tbody tr:last input.datepicker').removeAttr('id').removeClass("hasDatepicker").datepicker({dateFormat: 'yy-mm-dd', changeYear: true, changeMonth:true});;
		    //$('table#'+table_id+' tbody tr:last input.datepicker_exp').removeAttr('id').removeClass("hasDatepicker").datepicker({dateFormat: 'yy-mm-dd', changeYear: true, changeMonth:true});;
			
			//$('table#'+table_id+' tbody tr:last div').text("");
		    //$('table#'+table_id+' tbody tr:last span.span_clear').text("");
		    //$('table#'+table_id+' tbody tr:last select').prop("selectedIndex", 0);
			$("span."+table_id+"_num:last").text(c);
			
			$('table#'+table_id+' tbody tr:last span.harga_id_box').html("<select name='harga_id[]' id='harga_id' class='harga_id required'><option value=''>--Pilih--</option></select>");
						
		}
		
		function del_row(dis, conname) {
			if($('.'+conname).length > 1) {
				custom_confirm('Hapus baris ini??', function(){
					$(dis).parent().parent().parent().remove();
			//menghitung nilai subtotal dari grid 
				var sum = 0;
				$('.jumlah_angket').each(function(){
				    sum += parseInt($(this).val());
					
				});
				
				var qty = sum.toString();
				$('#total_qty').val(qty); 
									
					
				})
			}
			else {
				custom_alert('Tidak bisa');
			}
		}
		
		function custom_confirm(prompt, action, title, focus){
			if (focus === undefined) focus = "";
		    if (title === undefined) title = "Confirmation";
		    if ($("#confirm_dialog").length == 0){
		        //$("#content").append('<div id="confirm_dialog" title="' + title + '"><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>' + prompt + '</div>');
		        $("#buat_dialog").html('<div id="confirm_dialog" title="' + title + '"><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>' + prompt + '</div>');
		        $("#confirm_dialog").dialog({
		        	modal: true,
		        	zIndex: 9999999,
		        	buttons: {
		        		Yes: function(){
		        			 $(this).dialog('close'); 
		        			 action(); 
		        		}, 
		        		No: function(){
		        			 $(this).dialog('close');
		        			 $(focus).focus(); 
		        		}
		        	},
		        	close: function() {
				    	$(this).remove() 
				    },
		        });
		    }
		    else {
		        $("#confirm_dialog").html(prompt);
		        $("#confirm_dialog").dialog({
		        	modal: true,
		        	zIndex: 9999999,
		        	buttons: {
		        		Yes: function(){
		        			 $(this).dialog('close'); 
		        			 action(); 
		        		}, 
		        		No: function(){
		        			 $(this).dialog('close'); 
		        		}
		        	},
		        	close: function() {
				    	$(this).remove() 
				    },        	
		        });        
		        $("#confirm_dialog").dialog('open');
		    }
		}
		
		function custom_alert(prompt, title, focus, act){
		    if (title === undefined || title === '') title = "Alert";
		    if (focus === undefined) focus = "";
		    if (act === undefined) act = "";
		    if ($("#alert_dialog").length == 0){
		        $("#buat_dialog").html('<div id="alert_dialog" title="' + title + '"><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>' + prompt + '</div>');
		        if(act == '') {
		            $("#alert_dialog").dialog({
		                modal: true,
		                buttons: {
		                    Ok: function(){
		                         $(this).dialog('close');
		                         $(focus).focus();                   
		                    }
		                },
		                open: function() {
		                    $(this).parents('.ui-dialog-buttonpane button:eq(0)').focus(); 
		                },          
		            });
		       }
		       else {
		            $("#alert_dialog").dialog({
		                modal: true,
		                buttons: {
		                    Ok: function(){
		                         $(this).dialog('close');
		                         act();                  
		                    }
		                },
		                open: function() {
		                    $(this).parents('.ui-dialog-buttonpane button:eq(0)').focus(); 
		                },          
		            });
		            $('#alert_dialog').bind('dialogclose', function(event) {
		                act();
		            });
		       }        
		    }
		    else {
		        if(act == '') {
		            $("#alert_dialog").html(prompt);
		            //$("#alert_dialog").dialog('open');
		            $("#alert_dialog").dialog({
		                modal: true,
		                zIndex: 9999999,
		                buttons: {
		                    Ok: function(){
		                         $(this).dialog('close');
		                         $(focus).focus();                   
		                    }
		                },
		                open: function() {
		                    $(this).parents('.ui-dialog-buttonpane button:eq(0)').focus(); 
		                },
		                close: function() {
		                    $(this).remove() 
		                },          
		            });
		        }
		        else {
		            $("#alert_dialog").html(prompt);
		            $("#alert_dialog").dialog({
		                modal: true,
		                zIndex: 9999999,
		                buttons: {
		                    Ok: function(){
		                         $(this).dialog('close');
		                         //act();                    
		                    }
		                },
		                open: function() {
		                    $(this).parents('.ui-dialog-buttonpane button:eq(0)').focus(); 
		                },
		                close: function() {
		                    $(this).remove() 
		                },          
		            });
		            $('#alert_dialog').bind('dialogclose', function(event) {
		            	$(focus).focus();
		                act();
		            });
		        }
		    }
		}
		
		function start_link(url, grid_id, dis) {
			custom_confirm('Apakah anda yakin?<br />Proses tidak bisa dibatalkan!!', function(){
				$.ajax({
					url: url,
					type: 'get',
					dataType: 'json',
					beforeSend: function(){
						
					},
					success: function(resp) {
						if(resp.stat == 1) {
							custom_alert(resp.message);
							$('#'+grid_id).trigger('reloadGrid');
							$(dis).html('started');
						}
						else {
							custom_alert(resp.message);
						}
					}
				});
			});
		}
		
		function link_ajax(url, grid_id) {
			custom_confirm('Apakah anda yakin?', function(){
				$.ajax({
					url: url,
					type: 'get',
					dataType: 'json',
					beforeSend: function(){
						
					},
					success: function(resp) {
						if(resp.stat == 1) {
							custom_alert(resp.message);
							$('#'+grid_id).trigger('reloadGrid');
						}
						else {
							custom_alert(resp.message);
						}
					}
				});
			});
		}
		
		function ajax_submit_form(form_id) {
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
				custom_confirm('Apakah anda yakin?', function() {
					$.ajax({
						url: $('#'+form_id).attr('action'),
						type: $('#'+form_id).attr('method'),
						data: $('#'+form_id).serialize(),
						dataType: 'json',
						beforeSend: function() {
							
						},
						success: function(r) {
							if(r.status == 1) {
								custom_alert(r.message);
								$('#'+form_id)[0].reset();								
							}
							else {
								custom_alert(r.message);
							}					
						},
					});
				});
			}
		}
		
		function ajax_submit(form_id, grid, close) {
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
				custom_confirm('Apakah anda yakin sekali?', function() {
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
		
		function ajax_submit_upload(form_id, grid, close) {
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
				custom_confirm('Apakah anda yakin?', function() {
					$('#'+form_id).iframePostForm ({
                    post : function (){
                        //$('#uploading').modal();
                    },
                    complete : function (data){
                        console.log(data);
                        var o = $.parseJSON(data);
                        if(o.status === true) {
                            custom_alert(o.message);
                            $('#'+grid).trigger('reloadGrid');
							$('#alert_dialog_form').dialog('close');                            
                        }
                        else {
                            custom_alert(o.message);
                        }
                    }
                });
                $('#'+form_id).submit();
					/*$.ajax({
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
					});*/
				});
			}
		}
		
		function popup_form_upload(url, grid,title,act){
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
				        	//minHeight: 250,
				        	buttons: {
				        		/*Save: function(){
				        			var con = confirm('Are you sure?');
				        			if(con === true) {
					        			var id_form = $('#alert_dialog_form').find('form').attr('id');
					        			ajax_submit(id_form, grid);
					        			$('#'+id_form)[0].reset();
					        		}
				        		},*/
				        		'Save': function(){				        			
			        				var id_form = $('#alert_dialog_form').find('form').attr('id');
				        			ajax_submit_upload(id_form, grid, true);				        								        		
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
				        	//minHeight: 250,
				        	buttons: {
				        		/*Save: function(){
				        			var con = confirm('Are you sure?');
				        			if(con === true) {
					        			var id_form = $('#alert_dialog_form').find('form').attr('id');
					        			ajax_submit(id_form, grid);
					        			$('#'+id_form)[0].reset();
					        		}
				        		},*/
				        		'Save': function(){				        			
				        			var id_form = $('#alert_dialog_form').find('form').attr('id');
				        			ajax_submit_upload(id_form, grid, true);				        								        	
				        		},
				        		Close: function(){
				        			 $(this).dialog('close');				        			 
				        		}
				        	},
				        	/*open: function() {
				        		$('#search').focus();
						    	$(this).parents('.ui-dialog-buttonpane button:eq(0)').focus(); 
						    },
						    close: function() {
						    	$('#alert_dialog_form').html('');
						    	$('#alert_dialog_form').remove() 
						    },*/				    
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
		
		function popup_form(url, grid,title,act){
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
				        	//minHeight: 250,
				        	buttons: {
				        		/*Save: function(){
				        			var con = confirm('Are you sure?');
				        			if(con === true) {
					        			var id_form = $('#alert_dialog_form').find('form').attr('id');
					        			ajax_submit(id_form, grid);
					        			$('#'+id_form)[0].reset();
					        		}
				        		},*/
				        		'Simpan': function(){				        			
			        				var id_form = $('#alert_dialog_form').find('form').attr('id');
				        			ajax_submit(id_form, grid, true);				        								        		
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
				        	//minHeight: 250,
				        	buttons: {
				        		/*Save: function(){
				        			var con = confirm('Are you sure?');
				        			if(con === true) {
					        			var id_form = $('#alert_dialog_form').find('form').attr('id');
					        			ajax_submit(id_form, grid);
					        			$('#'+id_form)[0].reset();
					        		}
				        		},*/
				        		'Save': function(){				        			
				        			var id_form = $('#alert_dialog_form').find('form').attr('id');
				        			ajax_submit(id_form, grid, true);				        								        	
				        		},
				        		Close: function(){
				        			 $(this).dialog('close');				        			 
				        		}
				        	},
				        	/*open: function() {
				        		$('#search').focus();
						    	$(this).parents('.ui-dialog-buttonpane button:eq(0)').focus(); 
						    },
						    close: function() {
						    	$('#alert_dialog_form').html('');
						    	$('#alert_dialog_form').remove() 
						    },*/				    
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
		function window_open(url) {
			window.open(url, '_blank', '');
		}
    	</script>
	</body>
</html>