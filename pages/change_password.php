<?php require_once '../include/config.php' ?>
<?php
	if(isset($_GET['action']) && strtolower($_GET['action']) == 'process') {
		$s = $db->prepare("SELECT * FROM user WHERE user_id=? AND password=?");
		$s->execute(array($_SESSION['user']['user_id'],md5($_POST['old_pwd'])));
		$c = $s->rowCount();
		$rp=array();
		if($c == 0) {
			$rp['status'] = 0;
			$rp['message'] = 'Wrong Old Password';
		}		
		elseif($_POST['new_pwd'] != $_POST['new_pwd2']){
			$rp['status'] = 0;
			$rp['message'] = 'Password Confirmation Not Same';
		}
		elseif(strlen($_POST['new_pwd'])  < 3 ) {
			$rp['status'] = 0;
			$rp['message'] = 'Length Password Must be Greater Then 3';
		}
		else {
			$u = $db->prepare("UPDATE user SET password=? WHERE user_id=?");
			$u->execute(array(md5($_POST['new_pwd']), $_SESSION['user']['user_id']));
			$affected_rows = $u->rowCount();
			if($affected_rows > 0) {
				$rp['status'] = 1;
				$rp['message'] = 'Your Password Successfully Changed';
			}
			else {
				$rp['status'] = 0;
				$rp['message'] = 'Something Wrong!';
			}
		}
		echo json_encode($rp);
		exit;
	}
?>
<div class="ui-widget ui-form">
    <div class="ui-widget-header ui-corner-top padding5">
        Change Password
    </div>
    <div class="ui-widget-content ui-corner-bottom">
        <form id="change_password_form" method="post" action="<?php echo BASE_URL ?>pages/change_password.php?action=process" class="ui-helper-clearfix">
        	<label for="old_pwd" class="ui-helper-reset label-control">Old Password</label>
            <div class="ui-corner-all form-control">
            	<input type="password" class="required" id="old_pwd" name="old_pwd">
            </div>
            <label for="new_pwd" class="ui-helper-reset label-control">New Password</label>
            <div class="ui-corner-all form-control">
            	<input type="password" class="required" id="new_pwd" name="new_pwd">
            </div>
            <label for="new_pwd2" class="ui-helper-reset label-control">New Password Confirmation</label>
            <div class="ui-corner-all form-control">
            	<input type="password" class="required" id="new_pwd2" name="new_pwd2">
            </div>
            <label for="" class="ui-helper-reset label-control">&nbsp;</label>
            <div class="ui-corner-all form-control">
            	<button onclick="javascript:ajax_submit_form('change_password_form')" class="btn" type="button">Change</button>
            </div>
       	</form>
   	</div>
</div>