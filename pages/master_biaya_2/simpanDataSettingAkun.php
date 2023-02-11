<?php

include("../../include/config.php");
// require_once '../../include/config.php'
$rowcount = $_POST['rowcount'];
$i=0;
	while(  $i<$rowcount){
		$idx=$i;
		  //add here
		$menu = $_POST['menu_id'.$idx];
        if ((isset($_POST['debet'.$idx])) ) {
            $sql = "UPDATE `setting_akun` SET `akun_debet`='".$_POST['debet'.$idx]."' WHERE `id`='".$menu."'";
            $stmt = $db->prepare($sql);
            $stmt->execute();
        }

        if ((isset($_POST['kredit'.$idx])) ) {
            $sql = "UPDATE `setting_akun` SET `akun_kredit`='".$_POST['kredit'.$idx]."' WHERE `id`='".$menu."'";
            $stmt = $db->prepare($sql);
            $stmt->execute();
        }
		$i++;
	}