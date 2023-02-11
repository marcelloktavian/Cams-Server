<form id="" enctype="multipart/form-data" method="post" action="" class="">
	<input class="" type="file" name="file_upload" id="file_upload" />
	<input name="submit" type="submit" />
</form>
<?php
echo "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	if(isset($_POST['submit'])) {
		$filename = '';			
		if (isset($_FILES['file_upload']['tmp_name']) && $_FILES['file_upload']['tmp_name'] != '') {
			$info = pathinfo($_FILES['file_upload']['name']);
			$ext = $info['extension'];
			$filename = "test_".date('YmdHis').$ext;
 			$target = './images/bank/'.$_FILES['file_upload']['name'];
			if(@copy( $_FILES['file_upload']['tmp_name'], $target)) {
				chmod($target, 0755);
 				echo 'oke';
 			}
			else {
				echo 'Gagal move oke';
			}
		}	
		
	}
?>