<div class="ui-widget ui-form">
    <div class="ui-widget-header ui-corner-top padding5">
        <?php
            echo 'PASSWORD REQUIRED';			
        ?>
    </div>
    <div class="ui-widget-content ui-corner-bottom">
        <form id="trolndo_pass_form" method="post" action="pages/Transaksi_acc/tutupbuku.php?action=process_close" class="ui-helper-clearfix" target="_blank">
			<label for="password" class="ui-helper-reset label-control">Password</label>
            <div class="ui-corner-all form-control">
                <input value="<?=  $_GET['date'] ?>" type="text" id="date_yec" name="date_yec" hidden>
                <input value="" type="password" class="required" id="pass_yec" name="pass_yec">	
            </div>
        </form>
    </div>
</div>