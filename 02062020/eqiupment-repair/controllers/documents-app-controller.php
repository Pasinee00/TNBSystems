<?php
	include '../../lib/db_config.php';
	include '../modules/documents-app-model.php';
	
	$userMD = new Model_Documents_app();
	$function = $_REQUEST['function'];
	$id = $_REQUEST['Fdoc_app_id']; 
	
	 if($function == 'insert_data'){
		$results = $userMD->insert_data($_REQUEST['fields'],$_REQUEST);
		echo json_encode($results);
		// echo json_encode($results);
	}else if($function == "get"){
		$results = $userMD->get_data($_REQUEST['Fdoc_app_id']);
		echo json_encode($results);
	}else if($function == "get_data_list"){
		$results = $userMD->get_data_list($_REQUEST);
		echo json_encode($results);
	}else if($function == "upDetailDocApp"){
		$sql = "UPDATE  mtrequest_db.tbl_document_for_approval SET Fdoc_app_detail='".$_REQUEST[Fdoc_app_detail]."' WHERE Fdoc_app_id= $_REQUEST[Fdoc_app_id]"; 
 		$insert_rst = mysql_query($sql);
		echo $insert_rst;
	}else if($function == 'upload_temp'){
		
		$folder = "../../docapp_attach_temp/";
		$_fileName = $_FILES["fileUpload"]['name'];
		$_fileType = $_FILES["fileUpload"]['type'];
		$_fileSize = $_FILES["fileUpload"]['size'];
		$ext = explode(".",$_fileName);
		$fileName = $_SERVER['REMOTE_ADDR']."-".date('Ymd-Hs').".".end($ext);
		copy($_FILES["fileUpload"]["tmp_name"],$folder.$fileName);
		
		$field['ip_up']=$_SERVER['REMOTE_ADDR'];
		$field['FAttach_date']=date("Y-m-d");
		$field['FAttachName'] = $_fileName;
		$field['FAttachLink'] = $fileName;
		$field['FAttachType'] = $_fileType;
		$field['FAttachSize'] = $_fileSize;
		
		$_id = $userMD->add_attach_temp($field);
		echo "<script>";
		echo "  parent.uploadCompleteTemp('".$_id."','".$_fileName."','".$field['FAttachLink']."')";
		echo "</script>";
	}else if($function == 'delete_file_temp'){
		 $userMD->delete_file_temp($_REQUEST['id'],$_REQUEST['url']);
	}else if($function == 'list_attach'){
		$results = $userMD->list_attach($_REQUEST['Fdoc_app_id']);
		echo json_encode($results);
	}else if($function == 'delete_file'){
		$userMD->delete_file($_REQUEST['rId'],$_REQUEST['id'],$_REQUEST['url']);
	}else if($function == 'cancel'){
		echo $userMD->cancel_doc($_REQUEST['Fdoc_app_id'],$_REQUEST['FCancelRemark']);
	}else if($function == 'delete'){
		$userMD->delete_data($_REQUEST['Fdoc_app_id']);
	}else if($function=='receive_doc'){
		echo $userMD->receive_doc($_REQUEST,$_REQUEST['fields']);
	}
?>