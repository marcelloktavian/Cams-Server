<?php

include("../../include/config.php");
// require_once '../../include/config.php'
$rowcount = $_POST['rowcount'];
$group_id = $_POST['group_id'];
$i=0;
if ($group_id!=-1) {
	while(  $i<$rowcount){
		$idx=$i+1;
		  //add here
		$policy ="";
		if((isset($_POST['chkView'.$idx]))&&($_POST['chkView'.$idx] != null)) $policy .= 'VIEW;';
		if((isset($_POST['chkAdd'.$idx]))&&($_POST['chkAdd'.$idx] != null)) $policy .= 'ADD;';
		if((isset($_POST['chkEdit'.$idx])) &&($_POST['chkEdit'.$idx] != null)) $policy .= 'EDIT;';
		if((isset($_POST['chkDelete'.$idx]))&&($_POST['chkDelete'.$idx] != null)) $policy .= 'DELETE;';
		if((isset($_POST['chkPost'.$idx]))&&($_POST['chkPost'.$idx] != null)) $policy .= 'POST;';
		if((isset($_POST['chkPrint'.$idx])) &&($_POST['chkPrint'.$idx] != null)) $policy .= 'PRINT;';
		if((isset($_POST['chkReturn'.$idx]))&&($_POST['chkReturn'.$idx] != null)) $policy .= 'RETURN;';
	//	  if($_POST['chkPrint'][$i] != null) $policy .= 'PRINT;';
//		  if($_POST['chkExcel'][$i] != null) $policy .= 'EXCEL;';
//		  if($_POST['chkImport'][$i] != null) $policy .= 'IMPORT;';
//		  if($_POST['chkProses'][$i] != null) $policy .= 'PROSES;';
//		  if($_POST['chkCopy'][$i] != null) $policy .= 'COPY;';
//		  if($_POST['chkAuto'][$i] != null) $policy .= 'AUTOTAB;';

		$sql = "REPLACE INTO group_access VALUES (".$_POST['menu_id'][$i]." ,".$group_id.",'".$policy."');";
		$stmt = $db->prepare($sql);
		// var_dump($sql);
		$stmt->execute();
		$i++;
	}
}