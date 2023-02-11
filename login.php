<?php require_once 'include/config.php' ?>
<?php require_once 'include/ajax_call.php' ?>
<?php
	if(IS_AJAX) {
		$select = $db->prepare("SELECT * FROM user WHERE username=:username AND password=:password AND deleted = 0");
		$select->execute(array(':username' => $_POST['username'], ':password' => md5($_POST['password'])));
		$count = $select->rowCount();
		if($count > 0) {
			$row = $select->fetch(PDO::FETCH_ASSOC);
			$_SESSION['cams_logged_in'] = TRUE;
			$_SESSION['user'] = $row;
			$r['stat'] = 1;
			$r['message'] = 'Login Success';
		}
		else {
			$r['stat'] = 0;
			$r['message'] = 'Wrong Username or Password';
		}		
		echo json_encode($r);
		exit;
	}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Login</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">        
        <link href="assets/css/base/jquery.ui.theme.css" rel="stylesheet" media="screen">
        <link href="assets/css/base/jquery-ui.css" rel="stylesheet" media="screen">
		<link href="assets/css/ui.jqgrid.css" rel="stylesheet" media="screen">
		<link href="assets/css/jquery.treeview.css" rel="stylesheet" media="screen">
		<link href="assets/css/style.css" rel="stylesheet" media="screen">		
    </head>
    <body>
    	
    	<div id="loginBox">
			<p class="validateTips"></p>
			<form id="formLogin">
				<fieldset>
					<label for="name">username</label>
					<input type="text" style="text-transform: uppercase" name="username" id="username" class="text ui-widget-content ui-corner-all" />
					<label for="password">password</label>
					<input type="password" name="password" id="password" value="" class="text ui-widget-content ui-corner-all" />					
				</fieldset>
			</form>
		</div>
    	
    	<script type="text/javascript" src="assets/js/jquery-1.10.2.min.js"></script>
    	<script type="text/javascript" src="assets/js/jquery-migrate-1.2.1.min.js"></script>
    	<script type="text/javascript" src="assets/js/jquery-ui-1.9.2.custom.min.js"></script>
    	<script type="text/javascript" src="assets/js/jquery.layout-latest.min.js"></script>
    	<script type="text/javascript" src="assets/js/jquery.layout-latest.min.js"></script>
    	<script type="text/javascript" src="assets/js/i18n/grid.locale-id.js"></script>
    	<script type="text/javascript" src="assets/js/jquery.jqGrid.min.js"></script>
    	<script type="text/javascript" src="assets/js/jquery.cookie.js"></script>
    	<script type="text/javascript" src="assets/js/jquery.treeview.js"></script>
    	<script type="text/javascript">
    		$(document).ready(function(){
				var username = $( "#username" ),
					password = $( "#password" ),
					allFields = $( [] ).add( username ).add( password );
					tips = $( ".validateTips" );
					baseUrl = '<?php echo BASE_URL;?>'
					
				$( "#loginBox" ).dialog({
					title:'Login',
					autoOpen: true,
					width: 350,
					modal: true,
					draggable: false,
					resizable: false,
					closeText: "hide" ,
					dialogClass: "noClose",
					position: { my: "top-80%", at: "center", of: window  },
					buttons: {
						"Sign In": function() {
							var bValid = true;
							allFields.removeClass( "ui-state-error" );
							bValid = bValid && checkLength( username, "username", 1, 7 );
							bValid = bValid && checkLength( password, "password", 3, 16 );
							bValid = bValid && checkRegexp( username, /^[a-z]([0-9a-z_])+$/i, "Username may consist of a-z, 0-9, underscores, begin with a letter." );
							bValid = bValid && checkRegexp( password, /^([0-9a-zA-Z])+$/, "Password field only allow : a-z 0-9." );
							if ( bValid ) {
								var uName = $( "#username" ).val();
								var uPass = $( "#password" ).val();
								var uComp = $( "#company" ).val();
								
								doLogin(uName, uPass, uComp);
							}
						},
						"Reset": function() {
							resetForm('#formLogin');
						}
					},
					close: function() {
						allFields.val( "" ).removeClass( "ui-state-error" );
					}
				});
				
				$('#username').keyup(function(){
					$(this).val(String($(this).val()).toUpperCase());
				});
				
				focusCursor();
				$("#password").keypress(function(e){ 
				    var code = e.which; 
				    if(code==13)e.preventDefault();
				    if(code==32||code==13||code==188||code==186){
				    	var uName = $( "#username" ).val();
						var uPass = $( "#password" ).val();
						doLogin(uName, uPass);
				    }
				});
	
			});
			
			function checkLength( o, n, min, max ) {
				if ( o.val().length > max || o.val().length < min ) {
					o.addClass( "ui-state-error" );
					updateTips("Length of username must be between 1 and 7.");
					return false;
				} else {
					return true;
				}
			}
			
			function updateTips( t ) {
				tips
					.text( t )
					.addClass( "ui-state-highlight" );
				setTimeout(function() {
					tips.removeClass( "ui-state-highlight", 1500 );
				}, 500 );
			}
			
			function checkRegexp( o, regexp, n ) {
				if ( !( regexp.test( o.val() ) ) ) {
					o.addClass( "ui-state-error" );
					updateTips( n );
					return false;
				} else {
					return true;
				}
			}
			
			function resetForm(form) { 
				$(form).each(function(){ this.reset(); });
			}
			
			function doLogin(uName, uPass){
				var params	= 'username='+uName;
					params	+= '&password='+uPass;					 
				$.ajax({
					type: 'POST', 
					url: baseUrl+'login.php', 
					data: params, 
					async: false,
					dataType: 'json', 
					success: function(data) {
						if (data.stat == 1){
							window.location = baseUrl;
						}else{
							updateTips(data.message);
						} 
					}
				});
			}
	
			function focusCursor() {
			   if (formLogin.username.value!='') formLogin.userpassword.focus();
			   else formLogin.username.focus();
			}
		</script>    	
	</body>
</html>
	