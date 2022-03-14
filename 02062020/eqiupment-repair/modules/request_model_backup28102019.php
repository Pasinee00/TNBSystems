 <?php
 class Model_Request{
 	var $tbl_name = "";
 	var $key_id = "";
 	function Model_Request(){
 		$this->tbl_name = "mtrequest_db.tbl_request";
 		$this->key_id = "tbl_request.FRequestID";
 	}
 	function chk_holiday($holiday_date){
		$sh="SELECT
			pis_db.tb_holiday.holiday_id,
			pis_db.tb_holiday.holiday_date,
			pis_db.tb_holiday.holiday_detail
			FROM pis_db.tb_holiday
			WHERE
			pis_db.tb_holiday.holiday_date='".$holiday_date."'";
		$qh=mysql_query($sh);
		$nh=mysql_num_rows($qh);
		return $nh;
	}
 	function insert_data($fields,$id){
 		$field_sql = "";
 		$where_sql = "";
 		if(empty($fields['FReqNo'])){
 			list($y,$m,$d) = split("-",$fields['FReqDate']);
 			$sql  = "SELECT COUNT(FRequestID)+1 AS newReqNo "
 					." FROM {$this->tbl_name} "
 					." WHERE YEAR(FReqDate)= '$y' ";
 			$rst = mysql_query($sql);
 			if($row=mysql_fetch_array($rst)){
	 			$FReqNo = $row['newReqNo'];
			}//end if($row=mysql_fetch_array($rst))
 			else{
 				$FReqNo = 1;
 			}//end else's if($row=mysql_fetch_array($rst))
 				
 			if($FReqNo == NULL){$FReqNo = 1;}
 			
 			if($FReqNo <= 9 ){$FReqNo = "MT-".(substr($y,2,2)+43)."-00".$FReqNo;}
 			else if($FReqNo <= 99){$FReqNo = "MT-".(substr($y,2,2)+43)."-0".$FReqNo;}
 			else{$FReqNo = "MT-".(substr($y,2,2)+43)."-".$FReqNo;}
 			
 			$fields['FReqNo'] = $FReqNo;
 		}
 		foreach($fields as $key=>$val){
 			$field_sql .=(!$field_sql)?$key."='".trim(iconv("utf-8","tis-620",$val))."'":",".$key."='".trim(iconv("utf-8","tis-620",$val))."'";
 		}
 		if($id)$where_sql = $this->key_id."=$id";
 		if(!$id)$sql = "INSERT INTO ".$this->tbl_name." SET $field_sql";
 		else $sql = "UPDATE ".$this->tbl_name." SET $field_sql WHERE $where_sql";
 		$insert_rst = mysql_query($sql);
 		$_array = array();
 		$_array['req_no'] = $fields['FReqNo'];
 		if(!$id)$_array['req_id'] = mysql_insert_id();
 		else $_array['req_id'] =  $id;
		$id = $_array['req_id'];
		
		
		/////////////copy file/////////////
		   $s_File="SELECT
					tbl_attachment_temp.FAttachID,
					tbl_attachment_temp.ip_up,
					tbl_attachment_temp.FAttachName,
					tbl_attachment_temp.FAttachLink,
					tbl_attachment_temp.FAttachType,
					tbl_attachment_temp.FAttachSize,
					tbl_attachment_temp.FAttach_date
					FROM mtrequest_db.tbl_attachment_temp
					WHERE 1
					AND mtrequest_db.tbl_attachment_temp.ip_up='".$_SERVER['REMOTE_ADDR']."'";
			$q_File=mysql_query($s_File);
			$n_File=mysql_num_rows($q_File);
			if($n_File>=1){
				
			while($r_File=mysql_fetch_assoc($q_File)){
				
				
				$dirname = 'reqNo-'.$id;
				$folder = "../../attachment/" . $dirname . "/";
				if (!file_exists($folder)) {
					mkdir("../../attachment/" . $dirname, 0777);
				}


				
				$ext = explode(".",$r_File['FAttachLink']);
			    $fileName_new =$_array['req_id']."-".date('Ymd-Hsi')."-".rand(1,1000).".".end($ext);
			   
				$folder_old = "../../attachment_temp/";
				$flgCopy =copy($folder_old.$r_File['FAttachLink'],$folder.$fileName_new);
				if (!$flgCopy) {
					
				}else{
					$delete_sql = "DELETE FROM mtrequest_db.tbl_attachment_temp WHERE FAttachID='".$r_File[FAttachID]."'";
					$delete_rst = mysql_query($delete_sql);
					unlink('../../attachment_temp/'.$r_File[FAttachLink]);
				
					$sIN_f="INSERT INTO mtrequest_db.tbl_attachment SET ";
					$sIN_f.=" tbl_attachment.FRequestID='".$id."' ";
					$sIN_f.=" ,tbl_attachment.FAttachName='".$r_File['FAttachName']."' ";
					$sIN_f.=" ,tbl_attachment.FAttachLink='".$fileName_new."' ";
					$sIN_f.=" ,tbl_attachment.FAttachType='".$r_File['FAttachType']."' ";
					$sIN_f.=" ,tbl_attachment.FAttachSize='".$r_File['FAttachSize']."' ";
					$sIN_f.=" ,tbl_attachment.FAttach_date='".date("Y-m-d H:i:s")."' ";
					$qIN_f = mysql_query($sIN_f);

				}
			}
		   }
			
		/////////////end copy file/////////////	
 		
		
			$sql_check_mail = "SELECT
					mtrequest_db.tbl_request.FRequestID,
					mtrequest_db.tbl_request.FReqID,
					mtrequest_db.tbl_request.FReqDate,
					mtrequest_db.tbl_request.FSupervisorID,
					mtrequest_db.tbl_request.FSupervisorName,
					mtrequest_db.tbl_request.FManagerID,
					mtrequest_db.tbl_request.FManagerName,
					mtrequest_db.tbl_request.FReqNo,
					mtrequest_db.tbl_request.FStatus,
					mtrequest_db.tbl_request.FJobLevel,
					mtrequest_db.tbl_request.FStatus_sentmail,
					mtrequest_db.tbl_request.FStatus_sentmail_date,
					mtrequest_db.tbl_request.FApprove
					FROM
					mtrequest_db.tbl_request
					WHERE mtrequest_db.tbl_request.FRequestID = '".$id."'
					AND mtrequest_db.tbl_request.FStatus_sentmail IS NULL
					AND mtrequest_db.tbl_request.FApprove = 'Y' ";
			$results_check_mail = mysql_query($sql_check_mail);	
			$num_check_mail = mysql_num_rows($results_check_mail);		
			$record_check_mail = mysql_fetch_array($results_check_mail);
			if($num_check_mail>='1'){
				$sql_mail = "SELECT
						pis_db.tb_set_mailer.set_mailer_id,
						pis_db.tb_set_mailer.set_mailer_host,
						pis_db.tb_set_mailer.set_mailer_from,
						pis_db.tb_set_mailer.set_mailer_user,
						pis_db.tb_set_mailer.set_mailer_pass,
						pis_db.tb_set_mailer.status_use
						FROM
						pis_db.tb_set_mailer";	
				$results_mail = mysql_query($sql_mail);			
				$record_mail = mysql_fetch_array($results_mail);
				
				$sql_mail_manager = "SELECT
						general_db.tbl_manager.FManagerID,
						general_db.tbl_manager.FName,
						general_db.tbl_manager.pass_manager,
						general_db.tbl_manager.emp_code_full,
						pis_db.tbl_employee.email_company,
						pis_db.tbl_employee.emp_name,
						pis_db.tbl_employee.emp_id
						FROM
						general_db.tbl_manager
						LEFT JOIN pis_db.tbl_employee ON general_db.tbl_manager.emp_code_full = pis_db.tbl_employee.emp_code_full
						WHERE general_db.tbl_manager.FManagerID = '".$record_check_mail[FManagerID]."' ";	
				$results_mail_manager = mysql_query($sql_mail_manager);			
				$record_mail_manager = mysql_fetch_array($results_mail_manager);	
				
				//require 'PHPMailer-master/PHPMailerAutoload.php';
				require '../../lib/PHPMailer-master/PHPMailerAutoload.php';
				$link_ckick="<a href='http://10.2.1.233/approvecenter'>http://10.2.1.233/approvecenter</a>";	
				$link_ckick_out="<a href='http://www.nontrcms.com/approvecenter'>http://www.nontrcms.com/approvecenter</a>";	
				$mail = new PHPMailer();
				$mail->CharSet = "UTF-8";
				$mail->ContentType = "text/html";
				$mail->isSMTP();// Set mailer to use SMTP
				$mail->Host = $record_mail[set_mailer_host];  
				$mail->From = $record_mail[set_mailer_from];
				$mail->FromName = iconv("tis-620","utf-8","เชิญอนุมัติงานใบ MT REQUEST จากระบบ Approve Center");
				$mail->AddAddress($record_mail_manager[email_company], 1);
				$mail->WordWrap = 50;	// Set word wrap to 50 characters
				$mail->isHTML(true);// Set email format to HTML
				$mail->Subject = iconv("tis-620","utf-8","ระบบ Approve Center เชิญอนุมัติงาน ใบ MT REQUEST"); 
				$mail->Body = iconv('tis-620','utf-8','เรียนเชิญ คุณ ')
				.iconv('tis-620','utf-8',$record_mail_manager[emp_name])
				.iconv('tis-620','utf-8'," อนุมัติงานจากใบ MT REQUEST ผ่านระบบ Approve Center")
				."<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"
				.iconv('tis-620','utf-8','ใบ MT REQUEST หมายเลข ')
				.iconv('tis-620','utf-8',$record_check_mail[FReqNo])
				."<br/><br/>"
				.iconv('tis-620','utf-8','สามารถดูรายละเอียดเพิ่มเติมได้ที่ ')."<br/>"
				.iconv('tis-620','utf-8','สำหรับภายใน'). $link_ckick."<br/>"
				.iconv('tis-620','utf-8','สำหรับภายนอก '). $link_ckick_out."<br/>"
				."..................................................................................................................................................................................<br />"
				.iconv('tis-620','utf-8','แผนกซ่อมบำรุง')."<br/>"	
				.iconv('tis-620','utf-8','โทร.02-097-9555 ต่อ 3211 3213');	
			$mail->send();	
			
			$SQL = "UPDATE  
					mtrequest_db.tbl_request
					SET 	
					mtrequest_db.tbl_request.FStatus_sentmail='5' ,
					mtrequest_db.tbl_request.FStatus_sentmail_date='".date('Y-m-d')."',
					mtrequest_db.tbl_request.waiting_Approve_emp_id='".$record_mail_manager[emp_id]."'
					";	
			$SQL.= " WHERE mtrequest_db.tbl_request.FRequestID='".$id."'";
			$results_tbl_pr = mysql_query($SQL); 
			}
			
			
		//return $record_check_mail[FJobLevel];
		return $_array;
 		@mysql_free_result($insert_rst);
 	}/*End of function insert_data()*/
 	function open_request($fields){
 		$query = "UPDATE mtrequest_db.tbl_requestowner SET "
 					   ."FStatus='inprogress' "
 					   .",FStartDate='{$fields['FStartDate']}' "
 					   ."WHERE FRequestID= {$fields['FRequestID']} "
 					   ."AND FSupportID= {$fields['FSupportID']} "
 					   ."AND (FStatus='new' OR FStatus='waiting') ";
 		$rst = mysql_query($query);
 		
 	//	$query = "UPDATE mtrequest_db.tbl_request SET FEditDate='{$fields['FStartDate']}',FDueDate='{$fields['FDueDate']}' "
			$query = "UPDATE mtrequest_db.tbl_request SET FEditDate='{$fields['FStartDate']}' "
 				."WHERE FRequestID='{$fields['FRequestID']}' "
 		        ."AND (FEditDate IS NULL OR FEditDate='0000-00-00')";
 		$rst = mysql_query($query);
 	}/*End of function open_request($fields,$id)*/
 	function close_request($fields){
 		$query = "UPDATE mtrequest_db.tbl_requestowner SET "
				 		."FStatus='finished' "
				 		.",FFinishDate='{$fields['FFinishDate']}' "
				 		."WHERE FRequestID= {$fields['FRequestID']} "
				 		."AND FSupportID= {$fields['FSupportID']} "
				 		."AND FStatus='inprogress' ";
 		$rst = mysql_query($query);
 		
 		/*$num_rec = 0;
 		$query = "SELECT COUNT(FSupportID) AS num_rec FROM mtrequest_db.tbl_requestowner WHERE FRequestID='{$fields['FRequestID']}' AND FStatus!='finished'";
 		$rst = mysql_query($query);
 		while($record=mysql_fetch_object($rst)){
 			$num_rec = $record->num_rec;
 		}
 		if($num_rec==0){
 			$query = "UPDATE mtrequest_db.tbl_request SET FFinishDate='{$fields['FFinishDate']}',FStatus='finished' "
 					."WHERE FRequestID='{$fields['FRequestID']}' ";
 			$rst = mysql_query($query);
 		}*/
 	}/*End of function close_request()*/
	 
 	
 	function close_request_all($fields){
 		  $query = "UPDATE mtrequest_db.tbl_requestowner SET "
 		  			     ."FStatus='finished',"
 		  			     ."FFinishDate='{$fields['FFinishDate']}' "
 		  			     ."WHERE FRequestID={$fields['FRequestID']}"
 		  			     ." AND FStatus!='finished'";
 		  $rst = mysql_query($query);
 		  
 		  $query = "UPDATE mtrequest_db.tbl_request SET FFinishDate='{$fields['FFinishDate']}',FStatus='finished' "
 		  ."WHERE FRequestID='{$fields['FRequestID']}' ";
 		  $rst = mysql_query($query);
 	}
 	
 	function update_cost($record){
 		$query = "REPLACE INTO mtrequest_db.tbl_requestcost (FReqCostID,FRequestID,FReqCostDetail,FReqCost,FReqCostType)"
 				."VALUE("
 				." '{$record['FReqCostID']}'"
 				.",'{$record['FRequestID']}'"
 				.",'".iconv("utf-8","tis-620",$record['FReqCostDetail'])."'"
 				.",'{$record['FReqCost']}'"
 				.",'{$record['FReqCostType']}'"
 				.")";
 		$rst= mysql_query($query);
 		
 	}/*End of function update_cost()*/
 	function update_estimate($record){
 		$query = "REPLACE INTO mtrequest_db.tbl_requestestimate (FReqEstimateID,FRequestID,FReqEstimate)"
 				."VALUE("
 				." '{$record['FReqEstimateID']}'"
 		        .",'{$record['FRequestID']}'"
 		        .",'".iconv("utf-8","tis-620",$record['FReqEstimate'])."'"
 				.")";
 	 	$rst= mysql_query($query);
 	 					
 	}/*End of function update_cost()*/
 	function update_support($record){
 		foreach($record as $key=>$val){
 			if(!empty($val)){
 				$fields .=(!empty($fields))?",".$key : $key;
 				$value .=(!empty($value))?",'".$val."'" : "'".$val."'";
 			}
 			
 		}
 		$query = "REPLACE INTO mtrequest_db.tbl_requestowner (".$fields.") "
 				."VALUE(".$value.")";
 		$rst = mysql_query($query); 
 	}
 	
 	function load_support($_rId){
 		$_arr = array();
 		$index = 0;
 		$query = "SELECT * "
 				."FROM mtrequest_db.tbl_requestowner "
 				."LEFT JOIN pis_db.tbl_user ON(tbl_user.user_id = tbl_requestowner.FSupportID) "
 				."WHERE tbl_requestowner.FRequestID='{$_rId}' "
 				."ORDER BY mtrequest_db.tbl_requestowner.FStartDate DESC,mtrequest_db.tbl_requestowner.FFinishDate DESC ";
 		$results = mysql_query($query);
 		$columns = mysql_num_fields($results);
 		while($select_row=mysql_fetch_object($results)){
 			for($i=0;$i<$columns;$i++){
 				$field_name = mysql_field_name($results,$i);
 				$_arr[$index][$field_name] = iconv("tis-620","utf-8",$select_row->$field_name);
 			}
 			$index++;
 		}
 		return $_arr;
 	}/*End of function load_support($_rId)*/
 	
 	function load_cost($_rId){
 		$_arr = array();
 		$index = 0;
 		$query = "SELECT * "
 				."FROM mtrequest_db.tbl_purchase "
 				."WHERE tbl_purchase.FRequestID='{$_rId}'  AND (purchase_type='part' OR purchase_type IS NULL OR purchase_type='') "
 				."ORDER BY tbl_purchase.FPurchaseID ";
		//LIMIT 0,7;
 		$results = mysql_query($query);
 	    $columns = mysql_num_fields($results);
 		while($select_row=mysql_fetch_object($results)){
 			for($i=0;$i<$columns;$i++){
 				$field_name = mysql_field_name($results,$i);
 				$_arr[$index][$field_name] = iconv("tis-620","utf-8",$select_row->$field_name);
 			}
 			$index++;
 		}
 		if($index<7){
 			for($i=$index;$i<7;$i++){
 				$_arr[$i]['FPurchaseID'] = $i;
 				$_arr[$i]['FRequestID'] = "";
 				$_arr[$i]['FComClaimID'] = "";
 				$_arr[$i]['FItems'] = "";
 				$_arr[$i]['FPrice'] = "";
				$_arr[$i]['FAmount'] = "";
				$_arr[$i]['FUnit'] = "";
 			}
 		}
 	return $_arr;
 	}/*End of function load_cost()*/
 	function load_estimate($_rId){
 		$_arr = array();
 		$index = 0;
 		$query = "SELECT * "
 				."FROM mtrequest_db.tbl_requestestimate "
 				."WHERE tbl_requestestimate.FRequestID='{$_rId}' "
 		        ."ORDER BY tbl_requestestimate.FReqEstimateID";
 		$results = mysql_query($query);
 		$columns = mysql_num_fields($results);
 		while($select_row=mysql_fetch_object($results)){
 		 	for($i=0;$i<$columns;$i++){
 		 		$field_name = mysql_field_name($results,$i);
 		 		$_arr[$index][$field_name] = iconv("tis-620","utf-8",$select_row->$field_name);
 		 	}
 		 	$index++;
 		}
 		if(empty($_arr)){
 			for($i=1;$i<=6;$i++){
 				$_arr[$i]['FReqEstimateID'] = $i;
 				$_arr[$i]['FReqEstimate'] = '';
 			}
 		}
 		return $_arr;
 	}/*End of function load_estimate()*/
 	
 	function add_attach_temp($fields){
 		$field_sql = "";
 		$where_sql = "";
 		foreach($fields as $key=>$val){
 			$field_sql .=(!$field_sql)?$key."='".$val."'":",".$key."='".$val."'";
 		}
 		$sql = "INSERT INTO mtrequest_db.tbl_attachment_temp SET $field_sql";
 		$insert_rst = mysql_query($sql);
 		$FAttachID = mysql_insert_id();
 		return $FAttachID;
 	}
 	function list_attach($rId){
 		$query = "SELECT * "
 				."FROM mtrequest_db.tbl_attachment WHERE FRequestID='{$rId}'";
 		$results = mysql_query($query);
 		$index = 0;
 		$_arr = array();
 		while($record = mysql_fetch_object($results)){
 			$_arr[$index]['FAttachID'] = $record->FAttachID;
 			$_arr[$index]['FRequestID'] = $record->FRequestID;
 			$_arr[$index]['FAttachName'] = iconv("tis-620","utf-8",$record->FAttachName);
 			$_arr[$index]['FAttachLink'] = $record->FAttachLink;
 			$_arr[$index]['FAttachType'] = $record->FAttachType;
 			$_arr[$index]['FAttachSize'] = $record->FAttachSize;
 			
 			$index++;
 		}
 		return $_arr;
 	}/*End of function list_attach($rId)*/
	 
	function add_imgMT($fields,$id){
 		$field_sql = "";
 		$where_sql = "";
 		foreach($fields as $key=>$val){
 			$field_sql .=(!$field_sql)?$key."='".$val."'":",".$key."='".$val."'";
				
 		}
 		$sql = "UPDATE mtrequest_db.tbl_request SET $field_sql WHERE FRequestID= $id"; 
 		$insert_rst = mysql_query($sql);
		$delete_sql = "SELECT 
						tbl_request.FPhoto_1,
						tbl_request.FPhoto_2,
						tbl_request.FPhoto_3,
						tbl_request.FPhoto_4
						FROM mtrequest_db.tbl_request WHERE FRequestID='".$id."'";
 		$delete_rst = mysql_query($delete_sql);
		$delete_row = mysql_fetch_assoc($delete_rst);
		echo "<script>parent.set_FPhoto('$delete_row[FPhoto_1]','$delete_row[FPhoto_2]','$delete_row[FPhoto_3]','$delete_row[FPhoto_4]')</script>";
 	}
	 
	function delete_file_temp($id,$url){
 	 	$delete_sql = "DELETE FROM mtrequest_db.tbl_attachment_temp WHERE FAttachID='$id'";
 		$delete_rst = mysql_query($delete_sql);
 		
 		unlink('../../attachment_temp/'.$url);
 	} 
	 
 	function delete_file($rId,$id,$url){
 		 $delete_sql = "DELETE FROM mtrequest_db.tbl_attachment WHERE FAttachID='$id'";
 		$delete_rst = mysql_query($delete_sql);
 		
 		unlink('../../attachment/reqNo-'.$rId.'/'.$url);
 	}
 	
 	function get_data($id){
 		$dataArr = array();
 		$select_sql ="SELECT t1.* "
 				    .",t2.emp_code,t2.emp_name "
 				    .",t4.sec_nameThai "
 				    .",t5.post_name AS FPosition "
 				    .",t6.brn_name "
 				    .",t7.FRepairGroupItemName "
 				    .",t9.brn_name AS FBranchName "
 				    .",t10.FRepairGroupName "
					.",t11.comp_code "		
 				    .",CONCAT(t8.first_name,' ',t8.last_name) AS recive_name "
 				    .",CASE t1.FLevel "
 				    ."		WHEN '1' THEN 'ผลกระทบกับลูกค้าโดยตรง' "
 				    ."		WHEN '2' THEN 'ผลกระทบกับแผนกต่าง ๆ' "
 				    ."		WHEN '3' THEN 'ผลกระทบภายในแผนก' "
 				    ."END AS FLevelName "
 				    .",CASE t1.FJobresult "
 				    ."		WHEN '1' THEN 'ซ่อมเอง' "
 				    ."   	WHEN '2' THEN 'ให้ผู้รับเหมาดำเนินการ' "
 				    ."END AS FJobresultLabel "
 				    .",general_db.tbl_fjoblevel.FJobLevel_name "
 				    .",general_db.tbl_fjoblevel.num_work "
	 				."FROM  {$this->tbl_name} t1 "
	 				."LEFT JOIN pis_db.tbl_employee t2 ON(t2.emp_id = t1.FReqID) "
	 			    ."LEFT JOIN pis_db.tbl_employeehist t3 ON(t3.emp_code = t2.emp_code AND (t3.emp_flg IS NULL OR t3.emp_flg = '')) "
	 			    ."LEFT JOIN pis_db.tbl_section t4 ON(t4.sec_id = t1.FSectionID) "
	 			    ."LEFT JOIN pis_db.tbl_position t5 ON(t5.post_id = t3.post_id) "
	 			    ."LEFT JOIN pis_db.tbl_branch t6 ON(t6.brn_id = t2.brn_id) "
	 			    ."LEFT JOIN general_db.tbl_repairgroupitem t7 ON(t7.FRepairGroupItemID = t1.FRepairGroupItemID) "
	 			    ."LEFT JOIN pis_db.tbl_user t8 ON(t8.user_id = t1.FReciverID) "
	 			    ."LEFT JOIN pis_db.tbl_branch t9 ON(t9.brn_id = t1.FBranchID) "
	 			    ."LEFT JOIN general_db.tbl_repairgroup t10 ON(t10.FRepairGroupID = t1.FRepairGroupID) "
					."LEFT JOIN pis_db.tbl_company t11 ON(t11.comp_id = t1.FRepair_comp_id) "
					."LEFT JOIN general_db.tbl_fjoblevel ON t1.FJobLevel = general_db.tbl_fjoblevel.FJobLevel AND t1.FJobresult = general_db.tbl_fjoblevel.FJobresult "
	 				."WHERE t1.FRequestID ='{$id}'";

 		$select_rst = mysql_query($select_sql);
 		$columns = mysql_num_fields($select_rst);
 		while($select_row=mysql_fetch_object($select_rst)){
 			for($i=0;$i<$columns;$i++){
 				$field_name = mysql_field_name($select_rst,$i);
 				$dataArr[$field_name] = iconv("tis-620","utf-8",$select_row->$field_name);
 			}
 		}
 		return $dataArr;
		//return  $select_sql;
 		@mysql_free_result($insert_rst);
 	}//end function get_data($id)
 	function get_req_support($_rId,$_sId){
 		$dataArr = array();
 		$select_sql = "SELECT * "
 				     ."FROM mtrequest_db.tbl_requestowner "
 				     ."WHERE FSupportID='{$_sId}' "
 				     ."AND FRequestID='{$_rId}' ";
 		$select_rst = mysql_query($select_sql);
 		$columns = mysql_num_fields($select_rst);
 		while($select_row=mysql_fetch_object($select_rst)){
 			for($i=0;$i<$columns;$i++){
 				$field_name = mysql_field_name($select_rst,$i);
 				if($field_name=="FStartDate"){
 					$dataArr['FEditDate'] = iconv("tis-620","utf-8",$select_row->$field_name);
 				}
 				$dataArr[$field_name] = iconv("tis-620","utf-8",$select_row->$field_name);
 			}
 		}
 		return $dataArr;
 		@mysql_free_result($insert_rst);
 	}
 	function remove_support($_rId,$_sId,$_order){
 		$query = "DELETE FROM mtrequest_db.tbl_requestowner WHERE FRequestID={$_rId} AND FSupportID={$_sId} AND FOrder='{$_order}'";
 		$result = mysql_query($query);
 	}/*end of function remove_support($_sId)*/
 	function delete_data($id){
		$delete_sql = "DELETE FROM ".$this->tbl_name." WHERE ".$this->key_id."='$id'";
		$delete_rst = mysql_query($delete_sql);
		
		$_list =$this->list_attach($id);
		if(!empty($_list)){
			foreach($_list as $key=>$val){
				$this->delete_file($id,$val['FAttachID'],$val['FAttachLink']);
			}
		}
		
		$delete_sql = "DELETE FROM mtrequest_db.tbl_attachment WHERE FRequestID='{$id}'";
		$delete_rst = mysql_query($delete_sql);
		
		$delete_sql = "DELETE FROM mtrequest_db.tbl_requestcost WHERE FRequestID='{$id}'";
		$delete_rst = mysql_query($delete_sql);
		
		$delete_sql = "DELETE FROM mtrequest_db.tbl_requestestimate WHERE FRequestID='{$id}'";
		$delete_rst = mysql_query($delete_sql);
		
		$delete_sql = "DELETE FROM mtrequest_db.tbl_requestowner WHERE FRequestID='{$id}'";
		$delete_rst = mysql_query($delete_sql);
	}
	function cancel_request($_id,$_remark){
		$query = "UPDATE mtrequest_db.tbl_request SET FStatus='cancel',FCancelRemark='".iconv("utf-8","tis-620",$_remark)."' WHERE FRequestID='{$_id}'";
		$update_rst = mysql_query($query);
		//echo $query;
	}/*End of function cancel_request()*/
	
	function get_data_section_list($params){
		$page = $params['page']; // รับค่าหน้าที่ต้องการนำมาแสดง
		$rp = $params['rp']; // รับค่าจำนวนแสดงต่อ 1 หน้า
		$sortname = $params['sortname']; //  รับค่าเงื่อนไข field ที่ต้องการจัดเรียง
		$sortorder = $params['sortorder']; // รับค่ารูปแบบการจัดเรียงข้อมูล
		$search = $params['search'];
		$status_where_close = $params['status_where_close'];
		$status_where_close_dis = $params['status_where_close_dis'];
		$where = "";
		$sec_code = "";
		$select_sql = "SELECT sec_code FROM pis_db.tbl_section WHERE sec_id='{$search["FSectionID"]["value"]}'";
		$rst = mysql_query($select_sql);
		if($row=mysql_fetch_object($rst))$sec_code = $row->sec_code;
		foreach($search as $key=>$val){
			//if(!empty($val))$where .= " AND ".$key." = '{$val}'";
			if($key == 'duplicate'){
				foreach($val as $index=>$item){
					if(!empty($item['value1']))$where .= " AND {$item['key']} {$item['condition1']} '{$item['value1']}'";
					if(!empty($item['value2']))$where .= " AND {$item['key']} {$item['condition2']} '{$item['value2']}'";
				}
			}else if($key=="multi"){
					$_search = $val['value'];	
					$where .= " AND (";
					$_i = 0;
					foreach($val['fields'] as $index=>$item){
						if($_i>0) $where .= " OR ";
						if($item == "like")$where .= "LOCATE('".iconv("UTF-8", "TIS-620",$_search)."',{$index})>0";
						else $where .= "{$index} {$item} '{$_search}'";
						$_i++;
					}
					$where .= ")";
			}else{
				if(!empty($val['value'])){
					if($val['condition']=="like"){
						$where .= " AND  LOCATE('".iconv("UTF-8", "TIS-620",$val['value'])."',{$key})>0";
					}else if($val['condition']=="in"){
								$val['value'] = str_replace(",","','",$val['value']);
								//$where .= " AND t1.FFinishDate >= '2016-09-01'";
								
							if($val['value']=="finished"){
								//$where .= " AND {$key} {$val['condition']} '{$val['value']}'";
								$where .= " AND t1.FFinishDate >= '2016-09-01'";
									if($status_where_close=='1'){
										$where .= " AND t1.status_closejob != ''";
									} 
									if($status_where_close_dis=='1'){
										$where .= " AND t1.status_closejob = '2'";
									}
									
							}
 						$where .= " AND {$key} {$val['condition']}  ('".$val['value']."')";
							
					}else{
						if($key=="FSectionID"){
							if(!empty($sec_code)){
								$where .= " AND ({$key} {$val['condition']} '{$val['value']}')";
							}else{
								$where .= " AND {$key} {$val['condition']} '{$val['value']}'";
							}
						}else{
							if($val['value']=="finished"){
								//$where .= " AND {$key} {$val['condition']} '{$val['value']}'";
								$where .= " AND t1.FFinishDate >= '2016-09-01'";
									if($status_where_close=='1'){
										$where .= " AND t1.status_closejob != ''";
									} 
									if($status_where_close_dis=='1'){
										$where .= " AND t1.status_closejob = '2'";
									}
									
							}else{
								$where .= " AND {$key} {$val['condition']} '{$val['value']}'";
							}
							
						}
					}
					
				}
			}
			 
		}
		$_arr = array("new"=>"new-status","inprogress"=>"process-status","waiting"=>"wait-approval-status","cancel"=>"lock_disabled-status","finished"=>"lock-status","noapprove"=>"noapprov-status","returnedit"=>"returnedit-status","W_Approve"=>"W_Approve-status");
			
		// ส่วนการกำหนดค่า กรณีไม่ได้ส่งค่ามา
		if (!$sortname) $sortname = $this->order_name; // ถ้าไม่ส่งค่ามา กำหนดเป็น field ชื่อ arti_id (ขึ้นกับข้อมูลแต่ละคน)
		if (!$sortorder) $sortorder = 'desc'; // ถ้าไม่ส่งรูปแบบการจัดเรียงข้อมูลมา ให้กำหนดเป็น จากมากไปหาน้อย desc
		if (!$page) $page = 1; //  ถ้าไม่ได้ส่งหน้าที่ต้องการแสดงมา ให้แสดงหน้าแรก เป็น 1
		if (!$rp) $rp = 18; // หากไม่กำหนดรายการที่จะแสดงต่อ 1 หน้ามา ให้กำหนดเป็น 10
			
		// ส่วนสำหรับจัดรูปแบบขอบเขตและเงื่อนไขข้อมูลที่ต้องการแสดง
		$start = (($page-1) * $rp);
		$limit = "LIMIT $start, $rp";
		//$sort = "ORDER BY $sortname $sortorder";
		//if($query){
		//	$where = " AND LOCATE('".iconv("UTF-8", "TIS-620",$query)."', FSuplier)>0 ";
		//}
		//$where.= " AND FSectionID='{$sectionID}'";
		//$where.= " AND FBranchID='{$branchID}'";
			
		$select_sql = "SELECT * "
				     ."FROM  mtrequest_db.tbl_request t1 "
				     ."LEFT JOIN pis_db.tbl_employee t2 ON(t2.emp_id = t1.FReqID) "
				     ."LEFT JOIN pis_db.tbl_section t3 ON(t3.sec_id = t1.FSectionID) "
				     ."LEFT JOIN mtrequest_db.tbl_requestowner t4 ON (t4.FRequestID = t1.FRequestID) " 
				     ."WHERE 1 $where GROUP BY t1.FRequestID ";
			
		// ส่วนหรับหาว่ามีข้อมูลทั้งหมดเท่าไหร่ เก็บในตัวแปร $total
		$qr = mysql_query($select_sql);
		$total = mysql_num_rows($qr);
			
		// ส่วนสำหรับดึงข้อมูลมาสร้าง json ไฟล์ สำหรับแสดง
		$select_sql = "SELECT t1.*,t2.*,t3.*,t4.FSupportID,t4.FStatus AS owner_status,t5.FRepairGroupItemName "
				     ."FROM ".$this->tbl_name." t1 "
				     ."LEFT JOIN pis_db.tbl_employee t2 ON(t2.emp_id = t1.FReqID) "
				     ."LEFT JOIN pis_db.tbl_section t3 ON(t3.sec_id = t1.FSectionID) "
				     ."LEFT JOIN mtrequest_db.tbl_requestowner t4 ON (t4.FRequestID = t1.FRequestID) "
				     ."LEFT JOIN general_db.tbl_repairgroupitem t5 ON (t5.FRepairGroupItemID = t1.FRepairGroupItemID) " 
				     ."WHERE 1 $where  GROUP BY t1.FRequestID ORDER BY t1.FRequestID DESC $limit ";
		$select_rst = mysql_query($select_sql);
		
		$i=$start+1;
		$data['page'] = intval($page);
		$data['total_page'] = ceil($total/$rp);
		$data['total'] = intval($total);
		$data['begin'] = $i;
		while($val=mysql_fetch_array($select_rst)){
			$status = $val['FStatus'];
			if(!empty($val['owner_status']))$status = $val['owner_status'];
			$val['FDetail'] = str_replace(iconv("UTF-8", "TIS-620",$params['keysearch']), "<font color=\"#FF0000\">".iconv("UTF-8", "TIS-620",$params['keysearch'])."</font>", $val['FDetail']);
			if (strlen($val['FRepairGroupItemName'])> 30){
				$val['FRepairGroupItemName'] = substr($val['FRepairGroupItemName'],0,30)."...";
			}
			$cell = array(
					"order"=>$i
					,"FRequestID"=>iconv("TIS-620","UTF-8",$val['FRequestID'])
					,"FReqID"=>iconv("TIS-620","UTF-8",$val['FReqID'])
					,"FPosition"=>iconv("TIS-620","UTF-8",$val['FPosition'])
					,"FSectionID"=>iconv("TIS-620","UTF-8",$val['FSectionID'])
					,"FFnc"=>iconv("TIS-620","UTF-8",$val['FFnc'])
					,"FBranchID"=>iconv("TIS-620","UTF-8",$val['FBranchID'])
					,"FBranchID_login"=>iconv("TIS-620","UTF-8",$val['FBranchID_login'])
					,"FTel"=>iconv("TIS-620","UTF-8",$val['FTel'])
					,"FReqDate"=>iconv("TIS-620","UTF-8",$val['FReqDate'])
					,"FReqTime"=>iconv("TIS-620","UTF-8",$val['FReqTime'])
					,"FSupervisorID"=>iconv("TIS-620","UTF-8",$val['FSupervisorID'])
					,"FManagerID"=>iconv("TIS-620","UTF-8",$val['FManagerID'])
					,"FReqNo"=>iconv("TIS-620","UTF-8",$val['FReqNo'])
					,"FInf_no"=>iconv("TIS-620","UTF-8",$val['FInf_no'])
					,"RequestName"=>iconv("TIS-620","UTF-8",$val['emp_name'])
					,"FSectionName"=>iconv("TIS-620","UTF-8",$val['sec_nameThai'])
					,"StatusIcon"=>$_arr[$val['FStatus']]
					,"FStatus"=>iconv("TIS-620","UTF-8",$val['FStatus'])
					,"OwnerStatus"=>iconv("TIS-620","UTF-8",$val['owner_status'])
					,"OwnerStatusIcon"=>$_arr[$val['owner_status']]
					,"FDetail"=>iconv("TIS-620","UTF-8",$val['FDetail'])
					,"FRepairGroupItemName" =>iconv("TIS-620", "UTF-8", $val['FRepairGroupItemName'])
					,"status_closejob" =>iconv("TIS-620", "UTF-8", $val['status_closejob'])
					,"closejob_date" =>iconv("TIS-620", "UTF-8", $val['closejob_date'])
					,"closejob_detail" =>iconv("TIS-620", "UTF-8", $val['closejob_detail'])
					,"closejob_emp_date" =>iconv("TIS-620", "UTF-8", $val['closejob_emp_date'])
					,"closejob_emp_detail" =>iconv("TIS-620", "UTF-8", $val['closejob_emp_detail'])
					,"approve_date" =>iconv("TIS-620", "UTF-8", $val['approve_date'])
			);
		
			$rows[] = array(
					"id" => $val['FReqestID'],
					"cell" => $cell
			);
			$i++;
		}
		$data['end'] = $i-1;
		$data['rows'] = $rows;
		return $data;
		//return $select_sql;
	}/*End of function get_data_user_list()*/
	
	function get_data_list($params){
		$page = $params['page']; // รับค่าหน้าที่ต้องการนำมาแสดง
		$rp = $params['rp']; // รับค่าจำนวนแสดงต่อ 1 หน้า
		$sortname = $params['sortname']; //  รับค่าเงื่อนไข field ที่ต้องการจัดเรียง
		$sortorder = $params['sortorder']; // รับค่ารูปแบบการจัดเรียงข้อมูล
		$query = $params['search'];
			
		// ส่วนการกำหนดค่า กรณีไม่ได้ส่งค่ามา
		if (!$sortname) $sortname = $this->order_name; // ถ้าไม่ส่งค่ามา กำหนดเป็น field ชื่อ arti_id (ขึ้นกับข้อมูลแต่ละคน)
		if (!$sortorder) $sortorder = 'desc'; // ถ้าไม่ส่งรูปแบบการจัดเรียงข้อมูลมา ให้กำหนดเป็น จากมากไปหาน้อย desc
		if (!$page) $page = 1; //  ถ้าไม่ได้ส่งหน้าที่ต้องการแสดงมา ให้แสดงหน้าแรก เป็น 1
		if (!$rp) $rp = 18; // หากไม่กำหนดรายการที่จะแสดงต่อ 1 หน้ามา ให้กำหนดเป็น 10
			
		// ส่วนสำหรับจัดรูปแบบขอบเขตและเงื่อนไขข้อมูลที่ต้องการแสดง
		$start = (($page-1) * $rp);
		$limit = "LIMIT $start, $rp";
		//$sort = "ORDER BY $sortname $sortorder";
		//if($query){
		//	$where = " AND LOCATE('".iconv("UTF-8", "TIS-620",$query)."', FSuplier)>0 ";
		//}
			
			
		$select_sql = "SELECT * " .
				"FROM ".$this->tbl_name." WHERE 1 $where";
			
		// ส่วนหรับหาว่ามีข้อมูลทั้งหมดเท่าไหร่ เก็บในตัวแปร $total
		$qr = mysql_query($select_sql);
		$total = mysql_num_rows($qr);
			
		// ส่วนสำหรับดึงข้อมูลมาสร้าง json ไฟล์ สำหรับแสดง
		$select_sql = "SELECT * " .
				"FROM ".$this->tbl_name." " .
				"WHERE 1 $where $sort $limit";
		$select_rst = mysql_query($select_sql);

		$i=$start+1;
		$data['page'] = intval($page);
		$data['total_page'] = ceil($total/$rp);
		$data['total'] = intval($total);
		$data['begin'] = $i;
		while($val=mysql_fetch_array($select_rst)){
	
			$cell = array(
					"order"=>$i
					,"FReqestID"=>iconv("TIS-620","UTF-8",$val['FRequestID'])
					,"FReqID"=>iconv("TIS-620","UTF-8",$val['FReqID'])
					,"FPosition"=>iconv("TIS-620","UTF-8",$val['FPosition'])
					,"FSectionID"=>iconv("TIS-620","UTF-8",$val['FSectionID'])
					,"FFnc"=>iconv("TIS-620","UTF-8",$val['FFnc'])
					,"FBranchID"=>iconv("TIS-620","UTF-8",$val['FBranchID'])
					,"FTel"=>iconv("TIS-620","UTF-8",$val['FTel'])
					,"FReqDate"=>iconv("TIS-620","UTF-8",$val['FReqDate'])
					,"FReqTime"=>iconv("TIS-620","UTF-8",$val['FReqTime'])
					,"FSupervisorID"=>iconv("TIS-620","UTF-8",$val['FSupervisorID'])
					,"FManagerID"=>iconv("TIS-620","UTF-8",$val['FManagerID'])
					,"FReqNo"=>iconv("TIS-620","UTF-8",$val['FReqNo'])
					,"FInf_no"=>iconv("TIS-620","UTF-8",$val['FInf_no'])
					,"status_closejob"=>iconv("TIS-620","UTF-8",$val['status_closejob'])
					,"closejob_date"=>iconv("TIS-620","UTF-8",$val['closejob_date'])
					,"closejob_detail"=>iconv("TIS-620","UTF-8",$val['closejob_detail'])
					,"closejob_emp_date"=>iconv("TIS-620","UTF-8",$val['closejob_emp_date'])
					,"closejob_emp_detail"=>iconv("TIS-620","UTF-8",$val['closejob_emp_detail'])
			);
	
			$rows[] = array(
					"id" => $val['FReqestID'],
					"cell" => $cell
			);
			$i++;
		}
		$data['end'] = $i-1;
		$data['rows'] = $rows;
		return $data;
	}//end funciton get_data_list()
	function get_graph_data($param){
		$where = "";
		foreach($param as $key=>$val){
			$where .= " AND ".$key." = '{$val}'";
		}
		$select_sql = "SELECT COUNT(t1.FRequestID) AS num_rec "
				     ."FROM {$this->tbl_name} t1 "
				     ."LEFT JOIN mtrequest_db.tbl_requestowner t2 ON(t2.FRequestID = t1.FRequestID) "
					 ."WHERE 1 $where AND YEAR(t1.FReqDate) = YEAR(NOW())  GROUP BY t1.FRequestID";
		$select_rst = mysql_query($select_sql);
		$num_rec = 0;
		while($row = mysql_fetch_object($select_rst)){$num_rec += $row->num_rec;}
		return $num_rec;
	}/*End of function get_graph_data($param)*/
	function get_request_state($rId){
		$_arr = array();
		$query = "SELECT DATE_FORMAT(FReqDate,'%d-%b-%Y') AS openDate"
				.",DATE_FORMAT(FReciveDate,'%d-%b-%Y') AS startDate"
				.",DATEDIFF(FReciveDate,FReqDate) AS numStart"
				.",DATE_FORMAT(FEditDate,'%d-%b-%Y') AS workDate"
				.",DATEDIFF(FEditDate,FReciveDate) AS numWork"
				.",DATE_FORMAT(FDueDate,'%d-%b-%Y') AS estimateDate"
				.",FEstimate AS estTime"
				.",DATE_FORMAT(FFinishDate,'%d-%b-%Y') AS finishDate"
				.",DATEDIFF(FFinishDate,FEditDate) AS numFinish "
				."FROM mtrequest_db.tbl_request "
			    ."WHERE FRequestID='{$rId}'";
		$result = mysql_query($query);
		while($row=mysql_fetch_object($result)){
			$_arr[0]['date'] = $row->openDate;
			$_arr[0]['numDay'] = 0;
			$_arr[0]['type'] = '';
			$_arr[0]['label'] = iconv("TIS-620","UTF-8",'ส่งใบคำร้อง');
			
			if(!empty($row->startDate)){
				$_arr[1]['date'] = $row->startDate;
				$_arr[1]['numDay'] = ($row->numStart>0)?$row->numStart:0.5;
				$_arr[1]['type'] = '';
				$_arr[1]['label'] = iconv("TIS-620","UTF-8",'วันที่รับ');
			}
			
			if(!empty($row->workDate)){
				$_arr[2]['date'] = $row->workDate;
				$_arr[2]['numDay'] = ($row->numWork>0)?$row->numWork:0.5;
				$_arr[2]['type'] = '';
				$_arr[2]['label'] = iconv("TIS-620","UTF-8",'วันที่เริ่มแก้ไข');
			}
			
			if(!empty($row->estimateDate)){
				$_arr[3]['date'] = $row->estimateDate;
				$_arr[3]['numDay'] = ($row->estTime>0)?$row->estTime:0.5;
				$_arr[3]['type'] = '-estimate';
				$_arr[3]['label'] = iconv("TIS-620","UTF-8",'วันที่กำหนดเสร็จ');
			}
			
			if(!empty($row->finishDate)){
				$_arr[4]['date'] = $row->finishDate;
				$_arr[4]['numDay'] = ($row->numFinish>0)?$row->numFinish:0.5;
				if($_arr[4]['numDay']>$_arr[3]['numDay'])$_arr[4]['type'] = '-over';
				else $_arr[4]['type']='';
				$_arr[4]['label'] = iconv("TIS-620","UTF-8",'วันที่เสร็จ');
			}
		}
		return $_arr;
	}
	function get_request_notify($_suportId){
		$_arr['new'] = 0;
		$_arr['approve'] = 0;
		$_arr['snew']=0;
		$_arr['start']=0;
		$_arr['sapprove']=0;
		$_arr['purchase']=0;
		$_arr['spurchase']=0;
		$query = "SELECT COUNT(FRequestID) AS num_rec "
				         ."FROM mtrequest_db.tbl_request "
				         ."WHERE FStatus='new' "
				         ."GROUP BY FRequestID";
		$rst = mysql_query($query);
		while($row=mysql_fetch_object($rst)){$_arr['new']+=$row->num_rec;}
		
		$query ="SELECT COUNT(FRequestID) AS num_rec "
						 ."FROM mtrequest_db.tbl_request "
						 ."WHERE FStatus='waiting' "
						."GROUP BY FRequestID";
		$rst = mysql_query($query);
		while($row=mysql_fetch_object($rst)){$_arr['approve']+=$row->num_rec;}
		
		$query ="SELECT COUNT(t1.FPurchaseID) AS num_rec "
				         ."FROM mtrequest_db.tbl_purchase t1 "
				        ."WHERE t1.FPurchaseStatus IN ('NEW','PUR') "
						."GROUP BY t1.FRequestID";
		$rst = mysql_query($query);
		while($row=mysql_fetch_object($rst)){$_arr['purchase']+=$row->num_rec;}
		
		$query ="SELECT COUNT(FRequestID) AS num_rec "
				         ."FROM mtrequest_db.tbl_requestowner "
				         ."WHERE FStatus='new' "
				         ."AND FSupportID='{$_suportId}' "
						."GROUP BY FSupportID";
		$rst = mysql_query($query);
		while($row=mysql_fetch_object($rst)){$_arr['snew']+=$row->num_rec;}
		
		$query ="SELECT COUNT(FRequestID) AS num_rec "
				         ."FROM mtrequest_db.tbl_requestowner "
				         ."WHERE FStatus='inprogress' "
						."AND FSupportID='{$_suportId}' "
						."GROUP BY FSupportID";
		$rst = mysql_query($query);
		while($row=mysql_fetch_object($rst)){$_arr['start']+=$row->num_rec;}

		$query ="SELECT COUNT(FRequestID) AS num_rec "
				         ."FROM mtrequest_db.tbl_requestowner "
				         ."WHERE FStatus='waiting' "
						 ."AND FSupportID='{$_suportId}' "
						 ."GROUP BY FSupportID";
		$rst = mysql_query($query);
		while($row=mysql_fetch_object($rst)){$_arr['sapprove']+=$row->num_rec;}
		
		$query ="SELECT COUNT(t1.FPurchaseID) AS num_rec "
				         ."FROM mtrequest_db.tbl_purchase t1 "
				         ."LEFT JOIN mtrequest_db.tbl_requestowner t2 ON(t2.FRequestID = t1.FRequestID) "
				         ."WHERE t1.FPurchaseStatus IN ('NEW','PUR') "
				         ."AND t2.FSupportID='{$_suportId}' "
						 ."GROUP BY t1.FRequestID";
		$rst = mysql_query($query);
		while($row=mysql_fetch_object($rst)){$_arr['spurchase']+=$row->num_rec;}
		
		return $_arr;
	}/*End of function get_request_notify()*/
	
	function receive_doc($_id){
		    
		    $_arr = $this->get_data($_id);
			$query = "UPDATE mtrequest_db.tbl_request SET FStatus='inprogress',FReceiveDoc='Y'  WHERE FRequestID='{$_id}'";
			$rst = mysql_query($query);
			
			if($_arr['FEditDate'] !="")$_status = 'inprogress';
		    else $_status = 'new';
			
			$query = "UPDATE mtrequest_db.tbl_requestowner SET FStatus='{$_status}' WHERE FRequestID='{$_id}'";
			$rst = mysql_query($query);
	
	}/*End of function recieve_doc($_id)*/
	
	function check_owner($rId,$sId){
			$_result = 0;
			$query = "SELECT * FROM mtrequest_db.tbl_requestowner WHERE FSupportID='{$sId}' AND FRequestID='{$rId}'";
			$rst = mysql_query($query);
			while($row=mysql_fetch_object($rst)){$_result=1;}
			return $_result;
	}/*End of fuction check_owner()*/
	 
	 function cal_DueDate($params){
		
			$query = "SELECT
					tbl_fjoblevel.FJobLevel,
					tbl_fjoblevel.FJobLevel_name,
					tbl_fjoblevel.num_work,
					tbl_fjoblevel.FJobresult
					FROM
					general_db.tbl_fjoblevel
					WHERE
					tbl_fjoblevel.FJobLevel='".trim($params["FJobLevel"])."'
					AND 
					tbl_fjoblevel.FJobresult='".trim($params["FJobresult"])."'";
			$rst = mysql_query($query);
			$row=mysql_fetch_assoc($rst);
	if($params["FEstimate"]!=''){
		$FEstimate=$params["FEstimate"];
	}else{
		if($row["num_work"]!=''){
			$FEstimate=$row["num_work"];
		}else{
			$FEstimate='';
		}
	}
	if($FEstimate!=''){	 
	$startdate = strtotime($params["FReqDate"]);/////
	//$enddate1=date('Y-m-d',strtotime('+1 day',strtotime($params["FReciveDate"]))); //return 
	$enddate2=date('Y-m-d',strtotime('+'.$FEstimate. 'day',strtotime($params["FReqDate"]))); //return 	
	$enddate = strtotime($enddate2);////
    $currentdate = $startdate;
	$i=0;
    while ($currentdate <= $enddate) {
		$i++;
   		$chk_holiday=$this->chk_holiday(date('Y-m-d',$currentdate));
		//echo $chk_holiday."<br>";
        if ((date('D', $currentdate) == 'Sun') || $chk_holiday>=1) {
            $return = $return + 1;
        }
		
       	$currentdate = strtotime('+1 day', $currentdate);
    } //end loop
		if($return<=0){ 
			 $DueDate=date('Y-m-d',strtotime('+0 day',$enddate)); //return 
		}else{
			 $DueDate=date('Y-m-d',strtotime('+'.$return. 'day',$enddate)); //return 
		}
		
		
		
	$startDueDate = strtotime($DueDate);
	$endDueDate = strtotime('+20 day', $startDueDate);
    $currentDueDate = $startDueDate;
    while ($currentDueDate <= $endDueDate) {
		
   		$chk_holiday=$this->chk_holiday(date('Y-m-d',$currentDueDate));
        if ((date('D', $currentDueDate) == 'Sun') || $chk_holiday>=1) {
            $returnDueDate = $returnDueDate + 1;
       		$currentDueDate= strtotime('+1 day', $currentDueDate);
        }else{
			break;
		}
    } //end loop
    if($returnDueDate<=0){ 
    $DueDateF=date('Y-m-d',strtotime('+0 day',$startDueDate)); 
	}else{
    $DueDateF=date('Y-m-d',strtotime('+'.$returnDueDate. 'day',$startDueDate)); 
	}
		
		
	  }
		return  $FEstimate.'|'.$DueDateF;
		
	}
	 
 }/*End of class model_user*/
?>