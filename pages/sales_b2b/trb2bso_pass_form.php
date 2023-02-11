<div class="ui-widget ui-form">
    <div class="ui-widget-header ui-corner-top padding5">
        <?php
        	$action = strtoupper($_GET['action']);
			echo 'PASSWORD REQUIRED';			
        ?>
    </div>
    <div class="ui-widget-content ui-corner-bottom">
        <form id="trolndo_pass_form" method="post" action="<?php echo BASE_URL ?>pages/sales_b2b/trb2bso_confirmed.php?action=process" class="ui-helper-clearfix">
			<label for="password" class="ui-helper-reset label-control">Password</label>
            <div class="ui-corner-all form-control">
                <input value="<?php echo $_GET['id'] ?>" type="hidden" id="id" name="id">	
                <input value="<?php echo $_GET['qty'] ?>" type="hidden" id="qty" name="qty">	
                <input value="" type="password" class="required" id="pass" name="pass">	
            </div>
			
        </form>
    </div>
</div>