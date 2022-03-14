 <?php
 class Model_Documents_app{
 	var $tbl_name = "";
 	var $key_id = "";
 	function Model_Documents_app(){
 		$this->tbl_name = "mtrequest_db.tbl_document_for_approval";
 		$this->key_id = "tbl_document_for_approval.Fdoc_app_id";
 	}
	 
 	
 	function insert_data($fields,$REQUEST){
		$id=$REQUEST["Fdoc_app_id"];
 		$field_sql = "";
 		$where_sql = "";
 		if(empty($fields['Fdoc_app_no'])){
 			list($y,$m,$d) = split("-",$fields['Fdoc_app_date']);
 			$sql  = "SELECT COUNT(Fdoc_app_id)+1 AS newReqNo "
 					." FROM {$this->tbl_name} "
 					." WHERE YEAR(Fdoc_app_date)= '$y' ";
 			$rst = mysql_query($sql);
 			if($row=mysql_fetch_array($rst)){
	 			$Fdoc_app_no = $row['newReqNo'];
			}//end if($row=mysql_fetch_array($rst))
 			else{
 				$Fdoc_app_no = 1;
 			}//end else's if($row=mysql_fetch_array($rst))
 				
 			if($Fdoc_app_no == NULL){$Fdoc_app_no = 1;}
 			
 			if($Fdoc_app_no <= 9 ){$Fdoc_app_no = "MT-00".$Fdoc_app_no."-".(substr($y,2,2)+43);}
 			else if($Fdoc_app_no <= 99){$Fdoc_app_no = "MT-0".$Fdoc_app_no."-".(substr($y,2,2)+43);}
 			else{$Fdoc_app_no = "MT-".$Fdoc_app_no."-".(substr($y,2,2)+43);}
 			
 			$fields['Fdoc_app_no'] = $Fdoc_app_no;
 		}
		
					

 		/* foreach($fields as $key=>$val){
			
 			$field_sql .=(!$field_sql)?$key."=".chk_value(trim(iconv("utf-8","tis-620",$val))):",".$key."=".chk_value(trim(iconv("utf-8","tis-620",$val)));
			
 		} */
		//return $field_sql;
 		if($REQUEST["Fdoc_app_id"]==''){$sql = "INSERT INTO ".$this->tbl_name." SET ";
		}else{ $sql = "UPDATE ".$this->tbl_name." SET ";
				$where_sql = " WHERE Fdoc_app_id='".$REQUEST["Fdoc_app_id"]."'";
		}
		$sql.="  Fcomp_id=".chk_value(trim(iconv("utf-8","tis-620",$fields['Fcomp_id']))); 	
		$sql.=" ,FInf_mt_no=".chk_value(trim(iconv("utf-8","tis-620",$fields['FInf_mt_no'])));  
		$sql.=" ,Fdoc_app_project=".chk_value(trim(iconv("utf-8","tis-620",$fields['Fdoc_app_project'])));  	
		$sql.=" ,Fdoc_app_date=".date_to_datebase2($fields['Fdoc_app_date']);
		$sql.=" ,Fdoc_appSt=".chk_value(trim(iconv("utf-8","tis-620",$fields['Fdoc_appSt'])));  	
		$sql.=" ,FBranchID=".chk_value(trim(iconv("utf-8","tis-620",$fields['FBranchID'])));  		
		$sql.=" ,FworkSt=".chk_value(trim(iconv("utf-8","tis-620",$fields['FworkSt'])));  			
		$sql.=" ,Fwork_price=".chk_value(trim(iconv("utf-8","tis-620",$fields['Fwork_price'])));  
		$sql.=" ,Fdoc_app_no=".chk_value(trim(iconv("utf-8","tis-620",$fields['Fdoc_app_no'])));  
		$sql.=" ,Fdoc_app_name=".chk_value(trim(iconv("utf-8","tis-620",$fields['Fdoc_app_name'])));  
		$sql.=" ,Fmaterial_constructionSt=".chk_value(trim(iconv("utf-8","tis-620",$fields['Fmaterial_constructionSt']))); 
		$sql.=" ,Fcontractor=".chk_value(trim(iconv("utf-8","tis-620",$fields['Fcontractor']))); 
		$sql.=" ,FSupervisorID=".chk_value(trim(iconv("utf-8","tis-620",$fields['FSupervisorID']))); 
		$sql.=" ,FSupervisor_emp_id=".chk_value(trim(iconv("utf-8","tis-620",$fields['FSupervisor_emp_id']))); 
		$sql.=" ,FSupervisorPost_id=".chk_value(trim(iconv("utf-8","tis-620",$fields['FSupervisorPost_id']))); 
		$sql.=" ,FownerID=".chk_value(trim(iconv("utf-8","tis-620",$fields['FownerID']))); 
		$sql.=" ,FownerPost_id=".chk_value(trim(iconv("utf-8","tis-620",$fields['FownerPost_id']))); 
		$sql.=" ,Fowner_emp_id=".chk_value(trim(iconv("utf-8","tis-620",$fields['Fowner_emp_id']))); 		
		$sql.=" ,FJobLevel=".chk_value(trim(iconv("utf-8","tis-620",$fields['FJobLevel']))); 	
		$sql.=" ,Fattach_infor=".chk_value(trim(iconv("utf-8","tis-620",$fields['Fattach_infor']))); 	
		$sql.=" ,Fmachine_year=".chk_value(trim(iconv("utf-8","tis-620",$fields['Fmachine_year']))); 	
		$sql.=" ,Fmachine_price=".chk_value(trim(iconv("utf-8","tis-620",$fields['Fmachine_price']))); 
		$sql.=" ,Fmachine_hisRepair_amt=".chk_value(trim(iconv("utf-8","tis-620",$fields['Fmachine_hisRepair_amt']))); 	
		$sql.=" ,FdamagedSt=".chk_value(trim(iconv("utf-8","tis-620",$fields['FdamagedSt']))); 		
		$sql.=" ,FAcknowledgeSt=".chk_value(trim(iconv("utf-8","tis-620",$fields['FAcknowledgeSt']))); 	
		$sql.=" ,FAsk_for_approvalSt=".chk_value(trim(iconv("utf-8","tis-620",$fields['FAsk_for_approvalSt']))); 		
		$sql.=" ,FTo_approveSt=".chk_value(trim(iconv("utf-8","tis-620",$fields['FTo_approveSt']))); 		
		$sql.=" ,FexpressSt=".chk_value(trim(iconv("utf-8","tis-620",$fields['FexpressSt']))); 		
		$sql.=" ,FPlease_considerSt=".chk_value(trim(iconv("utf-8","tis-620",$fields['FPlease_considerSt']))); 		
		$sql.=" ,FmanagerBP_GSID=".chk_value(trim(iconv("utf-8","tis-620",$fields['FmanagerBP_GSID']))); 		
		$sql.=" ,FmanagerBP_GS_emp_id=".chk_value(trim(iconv("utf-8","tis-620",$fields['FmanagerBP_GS_emp_id']))); 	
		$sql.=" ,FmanagerBP_GSPost_id=".chk_value(trim(iconv("utf-8","tis-620",$fields['FmanagerBP_GSPost_id']))); 	
		$sql.=" ,Fmanager_mtID=".chk_value(trim(iconv("utf-8","tis-620",$fields['Fmanager_mtID']))); 	
		$sql.=" ,Fmanager_mt_emp_id=".chk_value(trim(iconv("utf-8","tis-620",$fields['Fmanager_mt_emp_id']))); 	
		$sql.=" ,Fmanager_mtPost_id=".chk_value(trim(iconv("utf-8","tis-620",$fields['Fmanager_mtPost_id']))); 	
		$sql.=" ,FSupervisorApp=".chk_value(trim(iconv("utf-8","tis-620",$fields['FSupervisorApp']))); 	
		$sql.=" ,FSupervisor_comment=".chk_value(trim(iconv("utf-8","tis-620",$fields['FSupervisor_comment']))); 			
		$sql.=" $where_sql";
 
 		$insert_rst = mysql_query($sql);
 		$_array = array();
 		$_array['req_no'] = $fields['Fdoc_app_no'];
 		$_array['Fdoc_appSt'] = $fields['Fdoc_appSt'];
 		if($REQUEST["Fdoc_app_id"]==''){
			$_array['req_id'] = mysql_insert_id();
		}else{ 
			$_array['req_id']=$REQUEST["Fdoc_app_id"];
		}
		
		
		
		/////////////copy file/////////////
		   $s_File="SELECT
					tbl_docapp_attach_temp.FAttachID,
					tbl_docapp_attach_temp.ip_up,
					tbl_docapp_attach_temp.FAttachName,
					tbl_docapp_attach_temp.FAttachLink,
					tbl_docapp_attach_temp.FAttachType,
					tbl_docapp_attach_temp.FAttachSize,
					tbl_docapp_attach_temp.FAttach_date
					FROM mtrequest_db.tbl_docapp_attach_temp
					WHERE 1
					AND mtrequest_db.tbl_docapp_attach_temp.ip_up='".$_SERVER['REMOTE_ADDR']."'";
			$q_File=mysql_query($s_File);
			$n_File=mysql_num_rows($q_File);
			if($n_File>=1){
				
			while($r_File=mysql_fetch_assoc($q_File)){
				
				
				$dirname = 'reqNo-'.$_array['req_id'];
				$folder = "../../docapp_attach/" . $dirname . "/";
				if (!file_exists($folder)) {
					mkdir("../../docapp_attach/" . $dirname, 0777);
				}


				
				$ext = explode(".",$r_File['FAttachLink']);
			    $fileName_new =$_array['req_id']."-".date('Ymd-Hsi')."-".rand(1,1000).".".end($ext);
			   
				$folder_old = "../../docapp_attach_temp/";
				$flgCopy =copy($folder_old.$r_File['FAttachLink'],$folder.$fileName_new);
				if (!$flgCopy) {
					
				}else{
					$delete_sql = "DELETE FROM mtrequest_db.tbl_docapp_attach_temp WHERE FAttachID='".$r_File[FAttachID]."'";
					$delete_rst = mysql_query($delete_sql);
					unlink('../../docapp_attach_temp/'.$r_File[FAttachLink]);
				
					$sIN_f="INSERT INTO mtrequest_db.tbl_docapp_attach SET ";
					$sIN_f.=" tbl_docapp_attach.Fdoc_app_id='".$_array['req_id']."' ";
					$sIN_f.=" ,tbl_docapp_attach.FAttachName='".$r_File['FAttachName']."' ";
					$sIN_f.=" ,tbl_docapp_attach.FAttachLink='".$fileName_new."' ";
					$sIN_f.=" ,tbl_docapp_attach.FAttachType='".$r_File['FAttachType']."' ";
					$sIN_f.=" ,tbl_docapp_attach.FAttachSize='".$r_File['FAttachSize']."' ";
					$sIN_f.=" ,tbl_docapp_attach.FAttach_date='".date("Y-m-d H:i:s")."' ";
					$qIN_f = mysql_query($sIN_f);

				}
			}
		   }
			
		/////////////end copy file/////////////	
		
		if($fields['Fdoc_appSt']=='waiting'){
		
		
		$Scheck_step = "SELECT
				next_emp_id_approve,
				next_approve_field,
				Fowner_emp_id,
				FmanagerBP_GS_emp_id
				FROM {$this->tbl_name} t1
				WHERE t1.Fdoc_app_id = '".$_array['req_id']."'
				AND t1.Fdoc_appSt = 'waiting' ";
		$Qcheck_step = mysql_query($Scheck_step);			
		$Rcheck_step = mysql_fetch_assoc($Qcheck_step);	
		if($Rcheck_step[next_emp_id_approve]==''){
			$step_approve_id=$Rcheck_step[Fowner_emp_id];
			
			$next_approve_field="Fowner";
		}else{
			$step_approve_id=$Rcheck_step[next_emp_id_approve];
			$next_approve_field=$Rcheck_step[next_approve_field];
		}	
		$field_app_id=$next_approve_field."_emp_id";	
		$sql_check_mail = "SELECT
				t1.Fattach_infor,
				pis_db.tbl_employee.emp_name,
				pis_db.tbl_employee.email_company,
				pis_db.tbl_position.post_name,
				mtrequest_db.t1.FownerID,
				mtrequest_db.t1.FAcknowledgeSt,
				mtrequest_db.t1.FAsk_for_approvalSt,
				mtrequest_db.t1.FTo_approveSt,
				mtrequest_db.t1.FexpressSt,
				mtrequest_db.t1.FPlease_considerSt,
				mtrequest_db.t1.Fdoc_app_project,
				mtrequest_db.t1.Fdoc_app_no,
				mtrequest_db.t1.Fdoc_app_name,
				mtrequest_db.t1.FmanagerBP_GSID,
				mtrequest_db.t1.FSupervisorID
				FROM {$this->tbl_name} t1
				LEFT JOIN pis_db.tbl_employee ON mtrequest_db.t1.$field_app_id = pis_db.tbl_employee.emp_id
				LEFT JOIN pis_db.tbl_position ON pis_db.tbl_employee.post_id = pis_db.tbl_position.post_id
					WHERE t1.Fdoc_app_id = '".$_array['req_id']."'
					AND t1.Fdoc_appSt = 'waiting' ";
			$results_check_mail = mysql_query($sql_check_mail);	
			$num_check_mail = mysql_num_rows($results_check_mail);		
			$rowCHKmail = mysql_fetch_assoc($results_check_mail);
			$Fdoc_app_project="<b>โครงการ</b> ".iconv("tis-620","utf-8",$rowCHKmail[Fdoc_app_project])."<br /><br />";
			$Fdoc_app_name="<b>เรื่อง</b> ".iconv("tis-620","utf-8",$rowCHKmail[Fdoc_app_name])."<br /><br /><br /><br /><br />";
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
				
				//require 'PHPMailer-master/PHPMailerAutoload.php';
				require '../../lib/PHPMailer-master/PHPMailerAutoload.php';
				$link_ckick="<a href='http://10.2.1.233/approvecenter'>http://10.2.1.233/approvecenter</a>";	
				$link_ckick_out="<a href='http://www.nontrcms.com/approvecenter'>http://www.nontrcms.com/approvecenter</a>";
				
				$checkMail = "SELECT user_email FROM pis_db.tbl_user WHERE user_id = '116'";
				$resultCheckMail = mysql_query($checkMail);			
				$fetchCheckMail = mysql_fetch_array($resultCheckMail);
				
				$sendsubject="=?utf-8?b?".base64_encode("เรียนเชิญ คุณ".iconv("tis-620","utf-8",$rowCHKmail[emp_name])." (".iconv("tis-620","utf-8",$rowCHKmail[post_name]).") เซ็นต์อนุมัติเอกสารขอความเห็นชอบและอนุมัติแผนกซ่อมบำรุง เลขที่ $rowCHKmail[Fdoc_app_no]  ผ่านระบบ Approve Center")."?=";
				
				$mail = new PHPMailer();
				$mail->CharSet = "UTF-8";
				$mail->ContentType = "text/html";
				$mail->isSMTP();// Set mailer to use SMTP
				$mail->Host = $record_mail[set_mailer_host];  
				$mail->From = $record_mail[set_mailer_from];
				$mail->FromName ="เอกสารขอความเห็นชอบและอนุมัติแผนกซ่อมบำรุง ผ่านระบบ Approve Center";
				if($rowCHKmail[FmanagerBP_GSID]=="25" || $rowCHKmail[FSupervisorID]=="25" ){
				$mail->AddAddress("isdp@toyotanont.com",1);
				$mail->AddAddress($fetchCheckMail[user_email],2);
				}else{
				$mail->AddAddress($rowCHKmail[email_company],1);
				}
				$mail->WordWrap = 50;	// Set word wrap to 50 characters
				$mail->isHTML(true);// Set email format to HTML
				$mail->Subject =$sendsubject;
				$mail->Body ="เรียนเชิญ คุณ".iconv("tis-620","utf-8",$rowCHKmail[emp_name])." (".iconv("tis-620","utf-8",$rowCHKmail[post_name]).") เซ็นต์อนุมัติเอกสารขอความเห็นชอบและอนุมัติแผนกซ่อมบำรุง เลขที่ $rowCHKmail[Fdoc_app_no]<br /><br />".$Fdoc_app_project." ".$Fdoc_app_name."
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;สามารถดูรายละเอียดเพิ่มเติมได้ที่ภายใน $link_ckick<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ภายนอก $link_ckick_out<br /><br /><br /><br /><br /><br />
..................................................................................................................................................................................<br />
เซ็นต์อนุมัติเอกสารขอความเห็นชอบและอนุมัติแผนกซ่อมบำรุง ผ่านระบบ Approve Center<br />
แผนกซ่อมบำรุง<br />
จากสาขา(ผ่านสายภายใน) โทร. 80123211, 8013213<br />
จากโทรศัพท์/มือถือ โทร. 1144 หรือ 02-097-9555 ต่อ 3211,3213";	
			$mail->send();	
			
			$SQL = "UPDATE  {$this->tbl_name} SET 	
					sentmail_date='".date('Y-m-d H:i:s')."'
					,sentmail_user_id='".$_REQUEST[user_id]."'
					,next_emp_id_approve='".$step_approve_id."'
					,next_approve_field='".$next_approve_field."'";	
			$SQL.= " WHERE Fdoc_app_id='".$_array['req_id']."'";
			mysql_query($SQL); 
			}
		}
		return $_array;
		//return sql_check_mail;
 		@mysql_free_result($insert_rst); 
 	}/*End of function insert_data()*/

 	
 	
 	function get_data($id){
 		$dataArr = array();
 		$select_sql ="SELECT
						t1.*,
						pis_db.tbl_company.comp_code,
						pis_db.tbl_company.comp_name,
						pis_db.tbl_branch.brn_code,
						pis_db.tbl_branch.brn_name,
						general_db.tbl_fjoblevel.FJobLevel_name,
						pos_owner.post_name AS owner_post_name,
						user_owner.first_name AS owner_first_name,
						user_owner.last_name AS owner_last_name,
						tbl_owner_emp.emp_img AS owner_emp_img,
						tbl_owner_emp.signature AS owner_signature,
						tbl_owner_emp.email_company AS owner_email_company,

						pos_manager_mt.post_name AS manager_mt_post_name,
						user_manager_mt.first_name AS manager_mt_first_name,
						user_manager_mt.last_name AS manager_mt_last_name,
						tbl_manager_mt_emp.emp_img AS manager_mt_emp_img,
						tbl_manager_mt_emp.signature AS manager_mt_signature,
						tbl_manager_mt_emp.email_company AS manager_mt_email_company,

						tbl_manager_bpgs.FName AS manager_bpgs_fname,
						pos_bpgs.post_name AS manager_bpgs_post_name,
						tbl_manager_bpgs_emp.emp_img AS manager_bpgs_emp_img,
						tbl_manager_bpgs_emp.signature AS manager_bpgs_signature,
						tbl_manager_bpgs_emp.email_company AS manager_bpgs_email_company,

						tbl_manager_sup.FName AS sup_fname,
						pos_sup.post_name AS sup_post_name,
						tbl_manager_sup_emp.emp_img AS manager_sup_emp_img,
						tbl_manager_sup_emp.signature AS manager_sup_signature,
						tbl_manager_sup_emp.email_company AS manager_sup_email_company,
						
						tbl_return_edit_emp.emp_name AS return_edit_emp_name
						FROM {$this->tbl_name} t1 
LEFT JOIN pis_db.tbl_company ON t1.Fcomp_id = pis_db.tbl_company.comp_id
LEFT JOIN pis_db.tbl_branch ON t1.FBranchID = pis_db.tbl_branch.brn_id
LEFT JOIN general_db.tbl_fjoblevel ON t1.FJobLevel = general_db.tbl_fjoblevel.FJobLevel
LEFT JOIN pis_db.tbl_employee AS tbl_owner_emp ON t1.Fowner_emp_id = tbl_owner_emp.emp_id
LEFT JOIN pis_db.tbl_position AS pos_owner ON t1.FownerPost_id = pos_owner.post_id
LEFT JOIN pis_db.tbl_user AS user_owner ON t1.FownerID = user_owner.user_id


LEFT JOIN pis_db.tbl_employee AS tbl_manager_mt_emp ON t1.Fmanager_mt_emp_id = tbl_manager_mt_emp.emp_id
LEFT JOIN pis_db.tbl_position AS pos_manager_mt ON t1.Fmanager_mtPost_id = pos_manager_mt.post_id
LEFT JOIN pis_db.tbl_user AS user_manager_mt ON t1.Fmanager_mtID = user_manager_mt.user_id

LEFT JOIN pis_db.tbl_employee AS tbl_manager_bpgs_emp ON t1.FmanagerBP_GS_emp_id = tbl_manager_bpgs_emp.emp_id
LEFT JOIN general_db.tbl_manager AS tbl_manager_bpgs ON t1.FmanagerBP_GSID = tbl_manager_bpgs.FManagerID
LEFT JOIN pis_db.tbl_position AS pos_bpgs ON t1.FmanagerBP_GSPost_id = pos_bpgs.post_id

LEFT JOIN pis_db.tbl_employee AS tbl_manager_sup_emp ON t1.FSupervisor_emp_id = tbl_manager_sup_emp.emp_id
LEFT JOIN general_db.tbl_manager AS tbl_manager_sup ON t1.FSupervisorID = tbl_manager_sup.FManagerID
LEFT JOIN pis_db.tbl_position AS pos_sup ON t1.FSupervisorPost_id = pos_sup.post_id

LEFT JOIN pis_db.tbl_employee AS tbl_return_edit_emp ON t1.return_edit_empid = tbl_return_edit_emp.emp_id
						WHERE t1.Fdoc_app_id ='{$id}' GROUP BY t1.Fdoc_app_id";

 		$select_rst = mysql_query($select_sql);
 		$columns = mysql_num_fields($select_rst);
 		while($select_row=mysql_fetch_object($select_rst)){
 			for($i=0;$i<$columns;$i++){
 				$field_name = mysql_field_name($select_rst,$i);
					if($field_name=="Fdoc_app_detail"){
						$dataArr[$field_name]=stripslashes(iconv("tis-620","utf-8",$select_row->$field_name));
					}else{
 					$dataArr[$field_name] = iconv("tis-620","utf-8",$select_row->$field_name);
					}
 			}
 		}
 		return $dataArr;
		//return  $select_sql;
 		@mysql_free_result($insert_rst);
 	}//end function get_data($id)
	 
	 
	 function get_data_list($params){
		$page = $params['page']; // ÃÑº¤èÒË¹éÒ·ÕèµéÍ§¡ÒÃ¹ÓÁÒáÊ´§
		$rp = $params['rp']; // ÃÑº¤èÒ¨Ó¹Ç¹áÊ´§µèÍ 1 Ë¹éÒ
		$sortname = $params['sortname']; //  ÃÑº¤èÒà§×èÍ¹ä¢ field ·ÕèµéÍ§¡ÒÃ¨Ñ´àÃÕÂ§
		$sortorder = $params['sortorder']; // ÃÑº¤èÒÃÙ»áºº¡ÒÃ¨Ñ´àÃÕÂ§¢éÍÁÙÅ
		$search = $params['search'];
		$where = "";
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
 						$where .= " AND {$key} {$val['condition']}  ('".$val['value']."')";
							
					}else{
						if($key=="t1.Fdoc_appSt" && $val['value']=='waiting_M'){
							 $where.= " AND t1.Fdoc_appSt='waiting'";
							 $where.= " AND t1.next_emp_id_approve='1159'";
							
						}else{
							$where .= " AND {$key} {$val['condition']} '{$val['value']}'";
						}
					}
					
				}
			}
			 
		}
		$_arr = array("new"=>"new-status","waiting"=>"wait-approval-status","cancel"=>"lock_disabled-status","finished"=>"lock-status","noapprove"=>"noapprov-status","returnedit"=>"returnedit-status");
			
		// ÊèÇ¹¡ÒÃ¡ÓË¹´¤èÒ ¡Ã³ÕäÁèä´éÊè§¤èÒÁÒ
		if (!$sortname) $sortname = $this->order_name; // ¶éÒäÁèÊè§¤èÒÁÒ ¡ÓË¹´à»ç¹ field ª×èÍ arti_id (¢Öé¹¡Ñº¢éÍÁÙÅáµèÅÐ¤¹)
		if (!$sortorder) $sortorder = 'desc'; // ¶éÒäÁèÊè§ÃÙ»áºº¡ÒÃ¨Ñ´àÃÕÂ§¢éÍÁÙÅÁÒ ãËé¡ÓË¹´à»ç¹ ¨Ò¡ÁÒ¡ä»ËÒ¹éÍÂ desc
		if (!$page) $page = 1; //  ¶éÒäÁèä´éÊè§Ë¹éÒ·ÕèµéÍ§¡ÒÃáÊ´§ÁÒ ãËéáÊ´§Ë¹éÒáÃ¡ à»ç¹ 1
		if (!$rp) $rp = 18; // ËÒ¡äÁè¡ÓË¹´ÃÒÂ¡ÒÃ·Õè¨ÐáÊ´§µèÍ 1 Ë¹éÒÁÒ ãËé¡ÓË¹´à»ç¹ 10
			
		// ÊèÇ¹ÊÓËÃÑº¨Ñ´ÃÙ»áºº¢Íºà¢µáÅÐà§×èÍ¹ä¢¢éÍÁÙÅ·ÕèµéÍ§¡ÒÃáÊ´§
		$start = (($page-1) * $rp);
		$limit = "LIMIT $start, $rp";
		//$sort = "ORDER BY $sortname $sortorder";
		//if($query){
		//	$where = " AND LOCATE('".iconv("UTF-8", "TIS-620",$query)."', FSuplier)>0 ";
		//}
		//$where.= " AND FSectionID='{$sectionID}'";
		//$where.= " AND FBranchID='{$branchID}'";
			
		$select_sql = "SELECT
						t1.*,
						pis_db.tbl_company.comp_code,
						pis_db.tbl_company.comp_name,
						pis_db.tbl_branch.brn_code,
						pis_db.tbl_branch.brn_name,
						general_db.tbl_fjoblevel.FJobLevel_name,
						pos_owner.post_name AS owner_post_name,
						user_owner.first_name AS owner_first_name,
						user_owner.last_name AS owner_last_name,
						tbl_owner_emp.emp_img AS owner_emp_img,
						tbl_owner_emp.signature AS owner_signature,
						tbl_owner_emp.email_company AS owner_email_company,

						pos_manager_mt.post_name AS manager_mt_post_name,
						user_manager_mt.first_name AS manager_mt_first_name,
						user_manager_mt.last_name AS manager_mt_last_name,
						tbl_manager_mt_emp.emp_img AS manager_mt_emp_img,
						tbl_manager_mt_emp.signature AS manager_mt_signature,
						tbl_manager_mt_emp.email_company AS manager_mt_email_company,

						tbl_manager_bpgs.FName AS manager_bpgs_fname,
						pos_bpgs.post_name AS manager_bpgs_post_name,
						tbl_manager_bpgs_emp.emp_img AS manager_bpgs_emp_img,
						tbl_manager_bpgs_emp.signature AS manager_bpgs_signature,
						tbl_manager_bpgs_emp.email_company AS manager_bpgs_email_company,

						tbl_manager_sup.FName AS sup_fname,
						pos_sup.post_name AS sup_post_name,
						tbl_manager_sup_emp.emp_img AS manager_sup_emp_img,
						tbl_manager_sup_emp.signature AS manager_sup_signature,
						tbl_manager_sup_emp.email_company AS manager_sup_email_company,
						
						tbl_return_edit_emp.emp_name AS return_edit_emp_name
						FROM {$this->tbl_name} t1 
LEFT JOIN pis_db.tbl_company ON t1.Fcomp_id = pis_db.tbl_company.comp_id
LEFT JOIN pis_db.tbl_branch ON t1.FBranchID = pis_db.tbl_branch.brn_id
LEFT JOIN general_db.tbl_fjoblevel ON t1.FJobLevel = general_db.tbl_fjoblevel.FJobLevel
LEFT JOIN pis_db.tbl_employee AS tbl_owner_emp ON t1.Fowner_emp_id = tbl_owner_emp.emp_id
LEFT JOIN pis_db.tbl_position AS pos_owner ON t1.FownerPost_id = pos_owner.post_id
LEFT JOIN pis_db.tbl_user AS user_owner ON t1.FownerID = user_owner.user_id


LEFT JOIN pis_db.tbl_employee AS tbl_manager_mt_emp ON t1.Fmanager_mt_emp_id = tbl_manager_mt_emp.emp_id
LEFT JOIN pis_db.tbl_position AS pos_manager_mt ON t1.Fmanager_mtPost_id = pos_manager_mt.post_id
LEFT JOIN pis_db.tbl_user AS user_manager_mt ON t1.Fmanager_mtID = user_manager_mt.user_id

LEFT JOIN pis_db.tbl_employee AS tbl_manager_bpgs_emp ON t1.FmanagerBP_GS_emp_id = tbl_manager_bpgs_emp.emp_id
LEFT JOIN general_db.tbl_manager AS tbl_manager_bpgs ON t1.FmanagerBP_GSID = tbl_manager_bpgs.FManagerID
LEFT JOIN pis_db.tbl_position AS pos_bpgs ON t1.FmanagerBP_GSPost_id = pos_bpgs.post_id

LEFT JOIN pis_db.tbl_employee AS tbl_manager_sup_emp ON t1.FSupervisor_emp_id = tbl_manager_sup_emp.emp_id
LEFT JOIN general_db.tbl_manager AS tbl_manager_sup ON t1.FSupervisorID = tbl_manager_sup.FManagerID
LEFT JOIN pis_db.tbl_position AS pos_sup ON t1.FSupervisorPost_id = pos_sup.post_id

LEFT JOIN pis_db.tbl_employee AS tbl_return_edit_emp ON t1.return_edit_empid = tbl_return_edit_emp.emp_id
						
				     	WHERE 1 $where  GROUP BY t1.Fdoc_app_id";
			
		// ÊèÇ¹ËÃÑºËÒÇèÒÁÕ¢éÍÁÙÅ·Ñé§ËÁ´à·èÒäËÃè à¡çºã¹µÑÇá»Ã $total
		$qr = mysql_query($select_sql);
		$total = mysql_num_rows($qr);
			
		// ÊèÇ¹ÊÓËÃÑº´Ö§¢éÍÁÙÅÁÒÊÃéÒ§ json ä¿Åì ÊÓËÃÑºáÊ´§
		$select_sql = "SELECT
						t1.*,
						pis_db.tbl_company.comp_code,
						pis_db.tbl_company.comp_name,
						pis_db.tbl_branch.brn_code,
						pis_db.tbl_branch.brn_name,
						general_db.tbl_fjoblevel.FJobLevel_name,
						pos_owner.post_name AS owner_post_name,
						user_owner.first_name AS owner_first_name,
						user_owner.last_name AS owner_last_name,
						tbl_owner_emp.emp_img AS owner_emp_img,
						tbl_owner_emp.signature AS owner_signature,
						tbl_owner_emp.email_company AS owner_email_company,

						pos_manager_mt.post_name AS manager_mt_post_name,
						user_manager_mt.first_name AS manager_mt_first_name,
						user_manager_mt.last_name AS manager_mt_last_name,
						tbl_manager_mt_emp.emp_img AS manager_mt_emp_img,
						tbl_manager_mt_emp.signature AS manager_mt_signature,
						tbl_manager_mt_emp.email_company AS manager_mt_email_company,

						tbl_manager_bpgs.FName AS manager_bpgs_fname,
						pos_bpgs.post_name AS manager_bpgs_post_name,
						tbl_manager_bpgs_emp.emp_img AS manager_bpgs_emp_img,
						tbl_manager_bpgs_emp.signature AS manager_bpgs_signature,
						tbl_manager_bpgs_emp.email_company AS manager_bpgs_email_company,

						tbl_manager_sup.FName AS sup_fname,
						pos_sup.post_name AS sup_post_name,
						tbl_manager_sup_emp.emp_img AS manager_sup_emp_img,
						tbl_manager_sup_emp.signature AS manager_sup_signature,
						tbl_manager_sup_emp.email_company AS manager_sup_email_company,
						
						tbl_return_edit_emp.emp_name AS return_edit_emp_name
						FROM {$this->tbl_name} t1 
LEFT JOIN pis_db.tbl_company ON t1.Fcomp_id = pis_db.tbl_company.comp_id
LEFT JOIN pis_db.tbl_branch ON t1.FBranchID = pis_db.tbl_branch.brn_id
LEFT JOIN general_db.tbl_fjoblevel ON t1.FJobLevel = general_db.tbl_fjoblevel.FJobLevel
LEFT JOIN pis_db.tbl_employee AS tbl_owner_emp ON t1.Fowner_emp_id = tbl_owner_emp.emp_id
LEFT JOIN pis_db.tbl_position AS pos_owner ON t1.FownerPost_id = pos_owner.post_id
LEFT JOIN pis_db.tbl_user AS user_owner ON t1.FownerID = user_owner.user_id


LEFT JOIN pis_db.tbl_employee AS tbl_manager_mt_emp ON t1.Fmanager_mt_emp_id = tbl_manager_mt_emp.emp_id
LEFT JOIN pis_db.tbl_position AS pos_manager_mt ON t1.Fmanager_mtPost_id = pos_manager_mt.post_id
LEFT JOIN pis_db.tbl_user AS user_manager_mt ON t1.Fmanager_mtID = user_manager_mt.user_id

LEFT JOIN pis_db.tbl_employee AS tbl_manager_bpgs_emp ON t1.FmanagerBP_GS_emp_id = tbl_manager_bpgs_emp.emp_id
LEFT JOIN general_db.tbl_manager AS tbl_manager_bpgs ON t1.FmanagerBP_GSID = tbl_manager_bpgs.FManagerID
LEFT JOIN pis_db.tbl_position AS pos_bpgs ON t1.FmanagerBP_GSPost_id = pos_bpgs.post_id

LEFT JOIN pis_db.tbl_employee AS tbl_manager_sup_emp ON t1.FSupervisor_emp_id = tbl_manager_sup_emp.emp_id
LEFT JOIN general_db.tbl_manager AS tbl_manager_sup ON t1.FSupervisorID = tbl_manager_sup.FManagerID
LEFT JOIN pis_db.tbl_position AS pos_sup ON t1.FSupervisorPost_id = pos_sup.post_id

LEFT JOIN pis_db.tbl_employee AS tbl_return_edit_emp ON t1.return_edit_empid = tbl_return_edit_emp.emp_id
				     	WHERE 1 $where  GROUP BY t1.Fdoc_app_id ORDER BY t1.Fdoc_app_id DESC $limit ";
		$select_rst = mysql_query($select_sql);
		
		$i=$start+1;
		$data['page'] = intval($page);
		$data['total_page'] = ceil($total/$rp);
		$data['total'] = intval($total);
		$data['begin'] = $i;
		while($val=mysql_fetch_array($select_rst)){
			if($val["FSupervisorApp"]!=''){
				$approval_name=iconv("tis-620","utf-8",$val["sup_fname"]);
			}elseif($val["FmanagerBP_GSApp"]!='' && $val["FmanagerBP_GSID"]!=''){
				$approval_name=iconv("tis-620","utf-8",$val["manager_bpgs_fname"]);
			}elseif($val["Fmanager_mtApp"]!=''){
				$approval_name="คุณ".iconv("tis-620","utf-8",$val["manager_mt_first_name"])." ".iconv("tis-620","utf-8",$val["manager_mt_last_name"]);
			}elseif($val["FownerApp"]!=''){
				$approval_name="คุณ".iconv("tis-620","utf-8",$val["owner_first_name"])." ".iconv("tis-620","utf-8",$val["owner_last_name"]);
			}else{
				$approval_name="-";
			}
			 
			
			$val['Fdoc_app_project'] = str_replace(iconv("UTF-8", "TIS-620",$params['keysearch']), "<font color=\"#FF0000\">".iconv("UTF-8", "TIS-620",$params['keysearch'])."</font>", $val['Fdoc_app_project']);
			$val['Fdoc_app_name'] = str_replace(iconv("UTF-8", "TIS-620",$params['keysearch']), "<font color=\"#FF0000\">".iconv("UTF-8", "TIS-620",$params['keysearch'])."</font>", $val['Fdoc_app_name']);
			$val['Fdoc_app_no'] = str_replace(iconv("UTF-8", "TIS-620",$params['keysearch']), "<font color=\"#FF0000\">".iconv("UTF-8", "TIS-620",$params['keysearch'])."</font>", $val['Fdoc_app_no']);
			$cell = array(
					"order"=>$i
					,"Fdoc_app_id"=>iconv("TIS-620","UTF-8",$val['Fdoc_app_id'])
					,"Fdoc_app_no"=>iconv("TIS-620","UTF-8",$val['Fdoc_app_no'])
					,"Fdoc_app_project"=>iconv("TIS-620","UTF-8",$val['Fdoc_app_project']) 
				
					,"Fmanager_mtApp"=>iconv("TIS-620","UTF-8",$val['Fmanager_mtApp'])  
					,"FownerApp"=>iconv("TIS-620","UTF-8",$val['FownerApp']) 
				
					,"Fdoc_app_name"=>iconv("TIS-620","UTF-8",$val['Fdoc_app_name'])
					,"Fdoc_app_date"=>iconv("TIS-620","UTF-8",$val['Fdoc_app_date'])
					,"approval_name"=>$approval_name
					,"StatusIcon"=>$_arr[$val['Fdoc_appSt']]
					,"Fdoc_appSt"=>iconv("TIS-620","UTF-8",$val['Fdoc_appSt'])
			);
		
			$rows[] = array(
					"id" => $val['Fdoc_app_id'],
					"cell" => $cell
			);
			$i++;
		}
		$data['end'] = $i-1;
		$data['rows'] = $rows;
		return $data;
		//return $select_sql;
	}/*End of function get_data_user_list()*/
	 
	function add_attach_temp($fields){
 		$field_sql = "";
 		$where_sql = "";
 		foreach($fields as $key=>$val){
 			$field_sql .=(!$field_sql)?$key."='".$val."'":",".$key."='".$val."'";
 		}
 		$sql = "INSERT INTO mtrequest_db.tbl_docapp_attach_temp SET $field_sql";
 		$insert_rst = mysql_query($sql);
 		$FAttachID = mysql_insert_id();
 		return $FAttachID;
 	} 
	function delete_file_temp($id,$url){
 	 	$delete_sql = "DELETE FROM mtrequest_db.tbl_docapp_attach_temp WHERE FAttachID='$id'";
 		$delete_rst = mysql_query($delete_sql);
 		
 		unlink('../../docapp_attach_temp/'.$url);
 	}
	 
	function delete_file($rId,$id,$url){
 		 $delete_sql = "DELETE FROM mtrequest_db.tbl_docapp_attach WHERE FAttachID='$id'";
 		$delete_rst = mysql_query($delete_sql);
 		
 		unlink('../../docapp_attach/reqNo-'.$rId.'/'.$url);
 	} 
	 
	function list_attach($rId){
 		$query = "SELECT * "
 				."FROM mtrequest_db.tbl_docapp_attach WHERE Fdoc_app_id='{$rId}'";
 		$results = mysql_query($query);
 		$index = 0;
 		$_arr = array();
 		while($record = mysql_fetch_object($results)){
 			$_arr[$index]['FAttachID'] = $record->FAttachID;
 			$_arr[$index]['Fdoc_app_id'] = $record->Fdoc_app_id;
 			$_arr[$index]['FAttachName'] = iconv("tis-620","utf-8",$record->FAttachName);
 			$_arr[$index]['FAttachLink'] = $record->FAttachLink;
 			$_arr[$index]['FAttachType'] = $record->FAttachType;
 			$_arr[$index]['FAttachSize'] = $record->FAttachSize;
 			
 			$index++;
 		}
 		return $_arr;
 	}/*End of function list_attach($rId)*/ 
	 
	 function cancel_doc($_id,$_remark){
		$query = "UPDATE mtrequest_db.tbl_document_for_approval SET Fdoc_appSt='cancel',FCancelRemark='".iconv("utf-8","tis-620",$_remark)."' WHERE Fdoc_app_id='{$_id}'";
		$update_rst = mysql_query($query);
		return $query;
	}/*End of function cancel_request()*/
	 
	 function delete_data($id){
		$delete_sql = "DELETE FROM ".$this->tbl_name." WHERE ".$this->key_id."='$id'";
		$delete_rst = mysql_query($delete_sql);
		
		$_list =$this->list_attach($id);
		if(!empty($_list)){
			foreach($_list as $key=>$val){
				$this->delete_file($id,$val['FAttachID'],$val['FAttachLink']);
			}
		}
		
		$delete_sql = "DELETE FROM mtrequest_db.tbl_docapp_attach WHERE Fdoc_app_id='{$id}'";
		$delete_rst = mysql_query($delete_sql);
		
		
	}
	 
	 
	 function receive_doc($_param,$fields){
		    $field_sql="";
		foreach($fields as $key=>$val){
 			$field_sql .=(!$field_sql)?$key."='".iconv("utf-8","tis-620",$val)."'":",".$key."='".iconv("utf-8","tis-620",$val)."'";
 		} 
		//	
			//$_param['FmanagerBP_GSID'];
			
			$sql = "UPDATE mtrequest_db.tbl_document_for_approval SET $field_sql";
		 if($fields[FmanagerBP_GSApp]=="N" || $fields[FSupervisorApp]=="N"){
			$sql.= ",Fdoc_appSt='noapprove'";
		 }else{
			$sql.= ",Fdoc_appSt='finished'";
		 }
			$sql.= ",next_emp_id_approve=NULL
					  ,next_approve_field=NULL ";
			if($_param['FmanagerBP_GSID']!=''){
				$sql.= ",FmanagerBP_GSApp_user_id='{$_param['u_id']}'";
			}
		 	$sql.= ",FSupervisorApp_user_id='{$_param['u_id']}'";
		 	$sql.= " WHERE Fdoc_app_id='{$_param['Fdoc_app_id']}'";
			$rst = mysql_query($sql);
		 if($rst){
			 return "1";
		 }else{
			 return "0";
		 }
			
			
	
	}/*End of function recieve_doc($_id)*/
	
	
 }
	
	
?>