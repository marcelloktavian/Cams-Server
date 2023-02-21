<div class="ui-widget ui-form">
    <div class="ui-widget-header ui-corner-top padding5">
        <?php
        	$action = strtoupper($_GET['action']);
			echo 'PASSWORD REQUIRED';			
        ?>
    </div>
    <div class="ui-widget-content ui-corner-bottom">
        <form id="po_pass_form" method="post" action="pages/transaksi_purchase/po.php?action=process_pass&val=<?= $_GET['val'] ?>" class="ui-helper-clearfix">
			<label for="password" class="ui-helper-reset label-control">Password</label>
            <div class="ui-corner-all form-control">
                <input value="<?php echo $_GET['id'] ?>" type="hidden" id="id_po" name="id_po">	
                <input value="" type="password" class="required" id="pass_po" name="pass_po">	
            </div>
        </form>
    </div>
</div>