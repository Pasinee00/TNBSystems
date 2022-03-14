<?php
	include '../lib/db_config.php';
	include '../main/modules/Model_Utilities.php';
	include '../pis_sys/models/user_model.php';
	
	$objMD = new Model_Utilities();
	$userMD = new Model_User();
	$userData = $userMD->get_data($_REQUEST['u_id']);
	$url = $_REQUEST["url"];
	if($userData['user_type']=="E"){
		$url = 'templates/general_user/general-index.php?u_id='.$_REQUEST['u_id'].'&e_id='.$_REQUEST['e_id'].'&compId='.$_REQUEST['compId'];
	}
	else if($userData['user_type']=="AM"){
		$url = 'templates/administrator/administrator-index.php?u_id='.$_REQUEST['u_id'].'&e_id='.$_REQUEST['e_id'].'&compId='.$_REQUEST['compId'];
	}
	else if($userData['user_type']=="M"){
		$url = 'templates/supports/support-index.php?u_id='.$_REQUEST['u_id'].'&e_id='.$_REQUEST['e_id'].'&compId='.$_REQUEST['compId'];
	}
    
	print "<script>";
	print "	window.location.href='{$url}';";
	print "</script>";
?>