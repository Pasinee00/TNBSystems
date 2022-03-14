<?php
	include '../../lib/db_config.php';
	include '../modules/purchase_model.php';
	
	$objMD = new Model_Purchase();
	$function = $_REQUEST['function'];
	$id = $_REQUEST['FPurchaseID']; 
	
	if($function == "list"){
		$results = $objMD->get_data_list($_REQUEST);
		echo json_encode($results);
	}else if($function == "get"){
		$results = $objMD->get_data($_REQUEST['FPurchaseID']);
		echo json_encode($results);
	}else if($function == 'insert_data'){
		$results = $objMD->insert_data($_REQUEST['fields'],$_REQUEST['FPurchaseID']);
		echo json_encode($results);
	}else if($function == 'delete'){
		$objMD->delete_data($_REQUEST['FPurchaseID'],$_REQUEST['FRequestID']);
	}else if($function == 'get_json'){
		$results = $objMD->get_data_json($_REQUEST['FRequestID']);
		echo json_encode($results);
	}
?>