<?php
	include '../../lib/db_config.php';
	include '../modules/request_model.php';
	
	$userMD = new Model_Request();
	$function = $_REQUEST['function'];
	$id = $_REQUEST['FRequestID']; 
	
	if($function == "list"){
		$results = $userMD->get_data_list($_REQUEST);
		echo json_encode($results);
	}else if($function == "get_data_section_list"){
		$results = $userMD->get_data_section_list($_REQUEST);
		echo json_encode($results);
	}else if($function == "get"){
		$results = $userMD->get_data($_REQUEST['FRequestID']);
		echo json_encode($results);
	}else if($function == "req-support"){
		$results = $userMD->get_req_support($_REQUEST['FRequestID'],$_REQUEST['FSupportID']);
		echo json_encode($results);
	}else if($function == 'insert_data'){
		
		$costs = $_REQUEST['fields']['costs'];
		$estimate = $_REQUEST['fields']['estimate'];
		$supports = $_REQUEST['fields']['supports'];
		unset($_REQUEST['fields']['costs']);
		unset($_REQUEST['fields']['estimate']);
		unset($_REQUEST['fields']['supports']);
		$results = $userMD->insert_data($_REQUEST['fields'],$_REQUEST['FRequestID']);
		
		if(!empty($supports)){
			foreach($supports as $key=>$val){
				if($_REQUEST['FRequestID'])$record['FRequestID'] = $_REQUEST['FRequestID'];
				else $record['FRequestID'] = $results['req_id'];
				if($_REQUEST['fields']['FStatus']=="waiting"){
					$record['FStatus']="waiting";
				}else if($_REQUEST['fields']['FStatus']=="W_Approve"){
					$record['FStatus']="W_Approve";
				}else{
					if($val['finish_date']!="" && $val['finish_date']!="0000-00-00"){
					$record['FStatus']="finished";
					}else if($val['start_date']!="" && $val['start_date']!="0000-00-00"){
					$record['FStatus']="inprogress";
					}else{
					$record['FStatus']=$val['status'];
					}
				}
				//$record['FStatus'] = ($_REQUEST['fields']['FStatus']=="waiting")?"waiting":$val['status'];
				$record['FStartDate'] = $val['start_date'];
				$record['FFinishDate'] = $val['finish_date'];
				$record['FSupportID'] = $val['id'];
				$record['FOrder'] = $val['order'];
				$userMD->update_support($record);
			}
			
			/*if($_REQUEST['user_type']=='M' || ($_REQUEST['user_id']==$record['FSupportID']) || $_REQUEST['user_type']=='GM'){
				$fields = array();
				$fields['FRequestID'] = $_REQUEST['FRequestID'];
				$fields['FSupportID'] = $_REQUEST['user_id'];
				$fields['FStartDate'] = $_REQUEST['fields']['FEditDate'];
				$fields['FDueDate'] = $_REQUEST['fields']['FDueDate'];
				$fields['FStatus'] = $_REQUEST['fields']['FStatus'];
				$rst = $userMD->open_request($fields);
			}*/
		}/*End if(!empty($supports))*/
		
		echo json_encode($results);
	}else if($function == 'open_request'){
		$fields = array();
		$fields['FRequestID'] = $_REQUEST['FRequestID'];
		$fields['FSupportID'] = $_REQUEST['user_id'];
		$fields['FStartDate'] = $_REQUEST['fields']['FEditDate'];
		$fields['FDueDate'] = $_REQUEST['fields']['FDueDate'];
		$fields['FStatus'] = $_REQUEST['fields']['FStatus'];
		$results = $userMD->open_request($fields);
		
		unset($_REQUEST['fields']['FEditDate']);
		unset($_REQUEST['fields']['FDueDate']);
		unset($_REQUEST['fields']['FStatus']);
		
		$costs = $_REQUEST['fields']['costs'];
		$estimate = $_REQUEST['fields']['estimate'];
		$supports = $_REQUEST['fields']['supports'];
		unset($_REQUEST['fields']['costs']);
		unset($_REQUEST['fields']['estimate']);
		unset($_REQUEST['fields']['supports']);
		
		$results = $userMD->insert_data($_REQUEST['fields'],$_REQUEST['FRequestID']);
		/*foreach($costs as $key=>$val){
			if($_REQUEST['FRequestID'])$val['FRequestID'] = $_REQUEST['FRequestID'];
			else $val['FRequestID'] = $results['req_id'];
			$userMD->update_cost($val);
		}
		
		foreach($estimate as $key=>$val){
			if($_REQUEST['FRequestID'])$val['FRequestID'] = $_REQUEST['FRequestID'];
			else $val['FRequestID'] = $results['req_id'];
			$userMD->update_estimate($val);
		}*/
		
		echo json_encode($results);
	}else if($function == 'close_request'){
		$fields = array();
		$fields['FRequestID'] = $_REQUEST['FRequestID'];
		$fields['FSupportID'] = $_REQUEST['user_id'];
		$fields['FStartDate'] = $_REQUEST['fields']['FEditDate'];
		$fields['FFinishDate'] = $_REQUEST['fields']['FFinishDate'];
		$fields['FStatus'] = $_REQUEST['fields']['FStatus'];
		
		unset($_REQUEST['fields']['FEditDate']);
		unset($_REQUEST['fields']['FDueDate']);
		unset($_REQUEST['fields']['FStatus']);
		unset($_REQUEST['fields']['FFinishDate']);
		
		$costs = $_REQUEST['fields']['costs'];
		$estimate = $_REQUEST['fields']['estimate'];
		$supports = $_REQUEST['fields']['supports'];
		unset($_REQUEST['fields']['costs']);
		unset($_REQUEST['fields']['estimate']);
		unset($_REQUEST['fields']['supports']);
		
		$results = $userMD->insert_data($_REQUEST['fields'],$_REQUEST['FRequestID']);

		foreach($supports as $key=>$val){
			if($_REQUEST['FRequestID'])$record['FRequestID'] = $_REQUEST['FRequestID'];
			else $record['FRequestID'] = $results['req_id'];
			$record['FStatus'] = "new";
			$record['FSupportID'] = $val['id'];
			$record['FOrder'] = $val['order'];
			if($val['send_to']=='Y')$userMD->update_support($record);
		}
		
		$fields['FStatus'] = "inprogress";
		 $results = $userMD->close_request($fields);
		echo json_encode($results);
	}else if($function=='close_request_all'){
		$fields = array();
		$fields['FRequestID'] = $_REQUEST['FRequestID'];
		$fields['FSupportID'] = $_REQUEST['user_id'];
		$fields['FStartDate'] = $_REQUEST['fields']['FEditDate'];
		$fields['FFinishDate'] = $_REQUEST['fields']['FFinishDate'];
		$fields['FStatus'] = $_REQUEST['fields']['FStatus'];
		
		unset($_REQUEST['fields']['FEditDate']);
		unset($_REQUEST['fields']['FDueDate']);
		unset($_REQUEST['fields']['FStatus']);
		unset($_REQUEST['fields']['FFinishDate']);
		
		$costs = $_REQUEST['fields']['costs'];
		$estimate = $_REQUEST['fields']['estimate'];
		$supports = $_REQUEST['fields']['supports'];
		unset($_REQUEST['fields']['costs']);
		unset($_REQUEST['fields']['estimate']);
		unset($_REQUEST['fields']['supports']);
		
		$results = $userMD->insert_data($_REQUEST['fields'],$_REQUEST['FRequestID']);
		
		foreach($supports as $key=>$val){
			if($_REQUEST['FRequestID'])$record['FRequestID'] = $_REQUEST['FRequestID'];
			else $record['FRequestID'] = $results['req_id'];
			$record['FStatus'] = "new";
			$record['FSupportID'] = $val['id'];
			if($val['send_to']=='Y')$userMD->update_support($record);
		}
		
		$fields['FStatus'] = "finished";
		$results = $userMD->close_request_all($fields);
		echo json_encode($results);
	}else if($function == 'delete'){
		$userMD->delete_data($_REQUEST['FRequestID']);
	}else if($function == 'cancel'){
		$results=$userMD->cancel_request($_REQUEST['FRequestID'],$_REQUEST['FCancelRemark']);
		
		echo json_encode($results);
	}else if($function == 'remove_support'){
		$userMD->remove_support($_REQUEST['FRequestID'],$_REQUEST['FSupportID'],$_REQUEST['FOrder']);
		echo '11';
	}else if($function == 'list_attach'){
		$results = $userMD->list_attach($_REQUEST['FRequestID']);
		echo json_encode($results);
	}else if($function == 'load_support'){
		$results = $userMD->load_support($_REQUEST['FRequestID']);
		echo json_encode($results);
	}else if($function == 'upload_temp'){
		/* $dirname = 'reqNo-'.$id;
		$folder = "../../attachment/" . $dirname . "/";
		if (!file_exists($folder)) {
			mkdir("../../attachment/" . $dirname, 0777);
		}
		
		$_fileName = $_FILES["fileUpload"]['name'];
		$_fileType = $_FILES["fileUpload"]['type'];
		$_fileSize = $_FILES["fileUpload"]['size'];
		$ext = explode(".",$_fileName);
		$fileName = $id."-".date('Ymd-Hs').".".$ext[1];
		
		copy($_FILES["fileUpload"]["tmp_name"],$folder.$fileName);
		
		$field['FRequestID'] = $id;
		$field['FAttachName'] = $_fileName;
		$field['FAttachLink'] = $fileName;
		$field['FAttachType'] = $_fileType;
		$field['FAttachSize'] = $_fileSize;
		
		$_id = $userMD->add_attach($field); */
		
		$folder = "../../attachment_temp/";
		
		
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
	}else if($function == 'uploadImgMT'){
		$dirname = 'reqNo-'.$id;
		$folder = "../../uploads/mt-data/" . $dirname . "/";
		if (!file_exists($folder)) {
			mkdir("../../uploads/mt-data/" . $dirname, 0777);
		}
		
		$delete_sql = "SELECT 
						tbl_request.FPhoto_1,
						tbl_request.FPhoto_2,
						tbl_request.FPhoto_3,
						tbl_request.FPhoto_4
						FROM mtrequest_db.tbl_request WHERE FRequestID='".$id."'";
 		$delete_rst = mysql_query($delete_sql);
		$delete_row = mysql_fetch_assoc($delete_rst);
		if($_FILES["photo_1"]['name']!=""){
			$_fileName1 = $_FILES["photo_1"]['name'];
			$_fileType1 = $_FILES["photo_1"]['type'];
			$_fileSize1 = $_FILES["photo_1"]['size'];
			$ext = explode(".",$_fileName1);
			$fileName1 = $id."-".date('Ymd-Hs')."-P1.".strtolower($ext[1]);
			
			if($delete_row["FPhoto_1"]!=''){
				$p1=unlink('../../uploads/mt-data/'.$dirname.'/'.$delete_row['FPhoto_1']);
				if($p1){
				copy($_FILES["photo_1"]["tmp_name"],$folder.$fileName1);
				$field['FPhoto_1'] = $fileName1;
				}else{
					$field['FPhoto_1'] =$_REQUEST['FPhoto_1'];
				}	
			}else{
				copy($_FILES["photo_1"]["tmp_name"],$folder.$fileName1);
				$field['FPhoto_1'] = $fileName1;
			}
		}
		
		
		if($_FILES["photo_2"]['name']!=""){
			$_fileName2 = $_FILES["photo_2"]['name'];
			$_fileType2 = $_FILES["photo_2"]['type'];
			$_fileSize2 = $_FILES["photo_2"]['size'];
			$ext = explode(".",$_fileName2);
			$fileName2 = $id."-".date('Ymd-Hs')."-P2.".strtolower($ext[1]);
			
			if($delete_row["FPhoto_2"]!=''){
				$p2=unlink('../../uploads/mt-data/'.$dirname.'/'.$delete_row['FPhoto_2']);
				if($p2){
				copy($_FILES["photo_2"]["tmp_name"],$folder.$fileName2);
				$field['FPhoto_2'] = $fileName2;
				}else{
					$field['FPhoto_2'] =$_REQUEST['FPhoto_2'];
				}		
			}else{
				copy($_FILES["photo_2"]["tmp_name"],$folder.$fileName2);
				$field['FPhoto_2'] = $fileName2;
			}
		}
		
		
		if($_FILES["photo_3"]['name']!=""){
			$_fileName3 = $_FILES["photo_3"]['name'];
			$_fileType3 = $_FILES["photo_3"]['type'];
			$_fileSize3 = $_FILES["photo_3"]['size'];
			$ext = explode(".",$_fileName3);
			$fileName3 = $id."-".date('Ymd-Hs')."-P3.".strtolower($ext[1]);
			
			if($delete_row["FPhoto_3"]!=''){
				$p3=unlink('../../uploads/mt-data/'.$dirname.'/'.$delete_row['FPhoto_3']);
				if($p3){
				copy($_FILES["photo_3"]["tmp_name"],$folder.$fileName3);
				$field['FPhoto_3'] = $fileName3;
				}else{
					$field['FPhoto_3'] =$_REQUEST['FPhoto_3'];
				}		
			}else{
				copy($_FILES["photo_3"]["tmp_name"],$folder.$fileName3);
				$field['FPhoto_3'] = $fileName3;
			}
		}
		
		
		if($_FILES["photo_4"]['name']!=""){
			$_fileName4 = $_FILES["photo_4"]['name'];
			$_fileType4= $_FILES["photo_4"]['type'];
			$_fileSize4 = $_FILES["photo_4"]['size'];
			$ext = explode(".",$_fileName4);
			$fileName4 = $id."-".date('Ymd-Hs')."-P4.".strtolower($ext[1]);
			
			if($delete_row["FPhoto_4"]!=''){
				$p4=unlink('../../uploads/mt-data/'.$dirname.'/'.$delete_row['FPhoto_4']);
				if($p4){
				copy($_FILES["photo_4"]["tmp_name"],$folder.$fileName4);
				$field['FPhoto_4'] = $fileName4;
				}else{
					$field['FPhoto_4'] =$_REQUEST['FPhoto_4'];
				}		
			}else{
				copy($_FILES["photo_4"]["tmp_name"],$folder.$fileName4);
				$field['FPhoto_4'] = $fileName4;
			}
		}

		
			$userMD->add_imgMT($field,$id);
		
	}else if($function == 'delete_file_temp'){
		 $userMD->delete_file_temp($_REQUEST['id'],$_REQUEST['url']);
	}else if($function == 'delete_file'){
		$userMD->delete_file($_REQUEST['rId'],$_REQUEST['id'],$_REQUEST['url']);
	}else if($function=='load_cost'){
		$results = $userMD->load_cost($_REQUEST['FRequestID']);
		echo json_encode($results);
	}else if($function=='load_estimate'){
		$results = $userMD->load_estimate($_REQUEST['FRequestID']);
		echo json_encode($results);
	}else if($function=='get_request_state'){
		$results = $userMD->get_request_state($_REQUEST['FRequestID']);
		echo json_encode($results);
	}else if($function=='get_request_notify'){
		$results = $userMD->get_request_notify($_REQUEST['supportID']);
		echo json_encode($results);
	}else if($function=='receive_doc'){
		$userMD->receive_doc($id);
	}else if($function=="check_owner"){
		$results = $userMD->check_owner($_REQUEST['RequestID'],$_REQUEST['supportID']);
		echo $results;
	}else if($function=="cal_DueDate"){
		$results = $userMD->cal_DueDate($_REQUEST);
		echo $results;
	}
?>