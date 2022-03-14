
<?php 
include '../../../lib/db_config.php';
include '../../../main/modules/Model_Utilities.php';
include '../../modules/request_model.php';

$utilMD = new Model_Utilities();
$reqMD = new Model_Request();
$_id = $_REQUEST['id'];
$reqData = $reqMD->get_data($_id);
$costData = $reqMD->load_cost($_id);
$estimateData = $reqMD->load_estimate($_id);
$attachs = $reqMD->list_attach($_id);
$states = $reqMD->get_request_state($_id);
$supports = $reqMD->load_support($_id);

if($cmd=="save_from"){
	
		 $sql_mamager = "SELECT
						mtrequest_db.tbl_request.FRequestID,
						mtrequest_db.tbl_request.FReqID,
						mtrequest_db.tbl_request.FManagerID,
						mtrequest_db.tbl_request.FManagerName,
						mtrequest_db.tbl_request.FSupervisorID,
						mtrequest_db.tbl_request.FSupervisorName
						FROM
						mtrequest_db.tbl_request
						WHERE
						mtrequest_db.tbl_request.FRequestID = '".$id."'";
		 $query_mamager = mysql_query($sql_mamager);
		 $row_mamager = mysql_fetch_assoc($query_mamager);	
		 
		 $sql_check_pass = "SELECT
							general_db.tbl_manager.FManagerID,
							general_db.tbl_manager.FName,
							general_db.tbl_manager.pass_manager
							FROM
							general_db.tbl_manager
							WHERE
							general_db.tbl_manager.FManagerID = '".$row_mamager[FManagerID]."'
							AND general_db.tbl_manager.pass_manager = '".$check_password."'
							";
		 $query_check_pass = mysql_query($sql_check_pass);
		 $num_check_pass = mysql_num_rows($query_check_pass);
		 $row_check_pass = mysql_fetch_assoc($query_check_pass);						
	
		
		if($num_check_pass=='1'){
			
			if($radioapprove=='1'){
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
					mtrequest_db.tbl_request.FStatus_sentmail,
					mtrequest_db.tbl_request.FStatus_sentmail_date,
					mtrequest_db.tbl_request.FApprove
					FROM
					mtrequest_db.tbl_request
					WHERE mtrequest_db.tbl_request.FRequestID = '".$id."'
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
							pis_db.tbl_user.user_email,
							pis_db.tbl_user.user_id,
							pis_db.tbl_user.first_name,
							pis_db.tbl_user.last_name
							FROM
							pis_db.tbl_user
							WHERE
							pis_db.tbl_user.user_id = '116'";	
				$results_mail_manager = mysql_query($sql_mail_manager);			
				$record_mail_manager = mysql_fetch_array($results_mail_manager);	
				
				
				require '../../../lib/PHPMailer-master/PHPMailerAutoload.php';
				$link_ckick="<a href='http://10.2.1.251/TNBSystems'>http://10.2.1.251/TNBSystems</a>";	
				//$link_ckick_out="<a href='http://www.nontrcms.com/approvcenter'>http://www.nontrcms.com/approvcenterp</a>";	
				$mail = new PHPMailer();
				$mail->CharSet = "UTF-8";
				$mail->ContentType = "text/html";
				$mail->isSMTP();// Set mailer to use SMTP
				$mail->Host = $record_mail[set_mailer_host];  
				$mail->From = $record_mail[set_mailer_from];
				$mail->FromName = "ใบ MT REQUEST ได้รับการอนุมัติ จากระบบ Approv Center";
				$mail->AddAddress($record_mail_manager[user_email], 1);
				$mail->WordWrap = 50;	// Set word wrap to 50 characters
				$mail->isHTML(true);// Set email format to HTML
				$mail->Subject = "ระบบ Approv Center ใบ MT REQUEST ผ่านการอนุมัติเรียบร้อยแล้ว"; 
				$mail->Body = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"
				.'ใบ MT REQUEST หมายเลข : '.$record_check_mail[FReqNo]
				."<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"
				.'ได้รับการอนุมัติ เรียบร้อยแล้ว '
				."<br/><br/>"
				.'สามารถดูรายละเอียดเพิ่มเติมได้ที่'."<br/>"
				.$link_ckick."<br/>"
				."..................................................................................................................................................................................<br />"
				.'แผนกซ่อมบำรุง'."<br/>"	
				.'โทร 02-097-9555 ต่อ 3211 3213';	
			$mail->send();	
				
			}
				$sql_up = "UPDATE mtrequest_db.tbl_request SET 
							mtrequest_db.tbl_request.FStatus='inprogress',
							mtrequest_db.tbl_request.FReceiveDoc='Y',
							mtrequest_db.tbl_request.approve_date='".date('Y-m-d')."'
						 WHERE 
						 mtrequest_db.tbl_request.FRequestID  = '".$id."'  ";
				$query_up = mysql_query($sql_up);
				
				$sql_up_sup = "UPDATE mtrequest_db.tbl_requestowner SET 
							mtrequest_db.tbl_requestowner.FStatus='inprogress'
						 WHERE 
						 mtrequest_db.tbl_requestowner.FRequestID  = '".$id."'  ";
				$query_up_sup = mysql_query($sql_up_sup);
				echo "1";
			}else if($radioapprove=='2'){
				
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
					mtrequest_db.tbl_request.FStatus_sentmail,
					mtrequest_db.tbl_request.FStatus_sentmail_date,
					mtrequest_db.tbl_request.FApprove
					FROM
					mtrequest_db.tbl_request
					WHERE mtrequest_db.tbl_request.FRequestID = '".$id."'
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
							pis_db.tbl_user.user_email,
							pis_db.tbl_user.user_id,
							pis_db.tbl_user.first_name,
							pis_db.tbl_user.last_name
							FROM
							pis_db.tbl_user
							WHERE
							pis_db.tbl_user.user_id = '116'";	
				$results_mail_manager = mysql_query($sql_mail_manager);			
				$record_mail_manager = mysql_fetch_array($results_mail_manager);	
				
				
				require '../../../lib/PHPMailer-master/PHPMailerAutoload.php';
				$link_ckick="<a href='http://10.2.1.251/TNBSystems'>http://10.2.1.251/TNBSystems</a>";	
				//$link_ckick_out="<a href='http://www.nontrcms.com/approvcenter'>http://www.nontrcms.com/approvcenterp</a>";	
				$mail = new PHPMailer();
				$mail->CharSet = "UTF-8";
				$mail->ContentType = "text/html";
				$mail->isSMTP();// Set mailer to use SMTP
				$mail->Host = $record_mail[set_mailer_host];  
				$mail->From = $record_mail[set_mailer_from];
				$mail->FromName = "ใบ MT REQUEST ไม่ผ่านการอนุมัติ จากระบบ Approv Center";
				$mail->AddAddress($record_mail_manager[user_email], 1);
				$mail->WordWrap = 50;	// Set word wrap to 50 characters
				$mail->isHTML(true);// Set email format to HTML
				$mail->Subject = "ระบบ Approv Center ใบ MT REQUEST ไม่ผ่านการอนุมัติ"; 
				$mail->Body = "เรียนแผนกซ่อมบำรุง"
				."<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"
				.'ใบ MT REQUEST หมายเลข : '.$record_check_mail[FReqNo]
				."<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"
				.'ไม่ผ่านการอนุมัติการพิจารณา โดยคุณ '
				.iconv('tis-620','utf-8',$record_check_mail[FManagerName])
				."<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"
				.'ด้วยเหตุผล : '.$detail_noapprove
				."<br/><br/>"
				.'สามารถดูรายละเอียดเพิ่มเติมได้ที่'."<br/>"
				.$link_ckick."<br/>"
				."..................................................................................................................................................................................<br />"
				.'แผนกซ่อมบำรุง'."<br/>"	
				.'โทร 02-097-9555 ต่อ 3211 3213';	
			$mail->send();	
				
			}
				$sql_up = "UPDATE mtrequest_db.tbl_request SET 
							mtrequest_db.tbl_request.FStatus='noapprove',
							mtrequest_db.tbl_request.detail_noapprove='".iconv("UTF-8","TIS-620",$detail_noapprove)."',
							mtrequest_db.tbl_request.approve_date='".date('Y-m-d')."'
						 WHERE 
						 mtrequest_db.tbl_request.FRequestID  = '".$id."'  ";
				$query_up = mysql_query($sql_up);
				
				$sql_up_sup = "UPDATE mtrequest_db.tbl_requestowner SET 
							mtrequest_db.tbl_requestowner.FStatus='noapprove'
						 WHERE 
						 mtrequest_db.tbl_requestowner.FRequestID  = '".$id."'  ";
				$query_up_sup = mysql_query($sql_up_sup);
				echo "3";
			}else if($radioapprove=='3'){
				
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
					mtrequest_db.tbl_request.FStatus_sentmail,
					mtrequest_db.tbl_request.FStatus_sentmail_date,
					mtrequest_db.tbl_request.FApprove
					FROM
					mtrequest_db.tbl_request
					WHERE mtrequest_db.tbl_request.FRequestID = '".$id."'
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
							pis_db.tbl_user.user_email,
							pis_db.tbl_user.user_id,
							pis_db.tbl_user.first_name,
							pis_db.tbl_user.last_name
							FROM
							pis_db.tbl_user
							WHERE
							pis_db.tbl_user.user_id = '116'";	
				$results_mail_manager = mysql_query($sql_mail_manager);			
				$record_mail_manager = mysql_fetch_array($results_mail_manager);	
				
				
				require '../../../lib/PHPMailer-master/PHPMailerAutoload.php';
				$link_ckick="<a href='http://10.2.1.251/TNBSystems'>http://10.2.1.251/TNBSystems</a>";	
				//$link_ckick_out="<a href='http://www.nontrcms.com/approvcenter'>http://www.nontrcms.com/approvcenterp</a>";	
				$mail = new PHPMailer();
				$mail->CharSet = "UTF-8";
				$mail->ContentType = "text/html";
				$mail->isSMTP();// Set mailer to use SMTP
				$mail->Host = $record_mail[set_mailer_host];  
				$mail->From = $record_mail[set_mailer_from];
				$mail->FromName = "ใบ MT REQUEST ถูกตีกลับจากระบบ Approv Center";
				$mail->AddAddress($record_mail_manager[user_email], 1);
				$mail->WordWrap = 50;	// Set word wrap to 50 characters
				$mail->isHTML(true);// Set email format to HTML
				$mail->Subject = "ระบบ Approv Center ใบ MT REQUEST ถูกตีกลับเพื่อแก้ไข"; 
				$mail->Body = "เรียนแผนกซ่อมบำรุง"
				."<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"
				.'ใบ MT REQUEST หมายเลข : '.$record_check_mail[FReqNo]
				."<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"
				.'ได้มีการตีกลับให้ดำเนินการแก้ไข โดยคุณ '
				.iconv('tis-620','utf-8',$record_check_mail[FManagerName])
				."<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"
				.'ด้วยเหตุผล : '.$detail_noapprove
				."<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"
				.'กรุณาดำเนินการแก้ไข และนำส่งเพื่อพิจารณาใหม่'
				."<br/><br/>"
				.'สามารถดูรายละเอียดเพิ่มเติมได้ที่'."<br/>"
				.$link_ckick."<br/>"
				."..................................................................................................................................................................................<br />"
				.'แผนกซ่อมบำรุง'."<br/>"	
				.'โทร 02-097-9555 ต่อ 3211 3213';	
			$mail->send();	
				
			}
				$sql_up = "UPDATE mtrequest_db.tbl_request SET 
							mtrequest_db.tbl_request.FStatus='returnedit',
							mtrequest_db.tbl_request.detail_noapprove='".iconv("UTF-8","TIS-620",$detail_noapprove)."',
							mtrequest_db.tbl_request.FStatus_sentmail=NULL,
							mtrequest_db.tbl_request.waiting_Approve_emp_id=NULL,
							mtrequest_db.tbl_request.FApprove='N',
							mtrequest_db.tbl_request.approve_date='".date('Y-m-d')."'
						 WHERE 
						 mtrequest_db.tbl_request.FRequestID  = '".$id."'  ";
				$query_up = mysql_query($sql_up);
				
				$sql_up_sup = "UPDATE mtrequest_db.tbl_requestowner SET 
							mtrequest_db.tbl_requestowner.FStatus='returnedit'
						 WHERE 
						 mtrequest_db.tbl_requestowner.FRequestID  = '".$id."'  ";
				$query_up_sup = mysql_query($sql_up_sup);
				echo "4";
			}
		}else{
			echo "2";
		} 
		exit();
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<script  type="text/javascript" src="../../../jsLib/jquery-1.8.0.min.js"></script>
<script src="../../../jsLib/uniform/jquery.uniform.js" type="text/javascript" charset="utf-8"></script>
<script src="../../../jsLib/js_scripts/js_function.js" type="text/javascript" charset="utf-8"></script>
<link href="../../../css/dialog-box.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="../../../jsLib/uniform/css/uniform.default.css" type="text/css" media="screen">
<link href="../../../css/sys_controll.css" rel="stylesheet" type="text/css">
<link href="../../../css/display.css" rel="stylesheet" type="text/css">
<title>Insert title here</title>
<script type="text/javascript" charset="utf-8">
      $(function(){
        $("input, textarea, select").uniform();
      });
</script>
<style type="text/css">
body,td,th {
	font-family: THNiramitAS, Georgia, sans-serif;
}
</style>
</head>
<body>
   <div class="dialog-panel">
   		<div class="top-row">
   			<div class="left"></div>
   			<div class="center">
   				<span class="dialog-title">การอนุมัติจากผู้จัดการ</span>
   			</div>
   			<div class="right"></div>
   		</div> 
   		<div class="middle-row">
   			<div class="left"></div>
   			<div id="dialog-body" class="center">
            <form name="form-add" action="" method="post"> 
   				<table width="100%" border="0" cellpadding="1" cellspacing="1">
   					<tbody id="user-emptype">
				    </tbody>
   					<tbody id="user-depart" style="display:none">
   					</tbody>
   					<tr>
   					  <td width="38%" align="right">อนุมัติ : </td>
   						<td width="62%" height="20" align="left"><input type="radio" onClick="show_approve();" name="radioapprove" id="radioapprove1" value="1"></td>
					</tr>
   					<tr>
   					  <td align="right">ไม่อนุมัติ : </td>
   					  <td height="20" align="left"><input type="radio" onClick="show_noapprove();" name="radioapprove" id="radioapprove2" value="2"></td>
				    </tr>
                    <tr>
   					  <td align="right">ตีกลับแก้ไข : </td>
   					  <td height="20" align="left"><input type="radio" onClick="show_noapprove();" name="radioapprove" id="radioapprove2" value="3"></td>
				    </tr>
   					<tr>
   					  <td align="right">เหตุผลการไม่อนุมัติ/ตีกลับแก้ไข : </td>
   					  <td height="20" align="left"><input type="text" style="width:50%;" name="detail_noapprove" id="detail_noapprove"></td>
				  </tr>
   					<tr>
   					  <td align="right">ยืนยันตัวตน : </td>
   					  <td height="20" align="left"><input type="password" style="width:50%;" name="check_password" id="check_password"></td>
				  </tr>
   					
   				</table>
             </form>
   			</div>
   			<div class="right"></div>
   		</div>
   		<div class="bottom-row">
   			<div class="left"></div>
   			<div class="center">
   			    <table width="100%">
   			    	<tr>
		    		  <td width="50%" align="left">
		    			<div class="warning">Test warning message</div></td>
   			    		<td align="right">
                        <button onClick="javascript:save_from(<?=$id?>);">ยืนยัน</button>
                        
                        </td>
   			    	</tr>
   			    </table>
   				
   			</div>
   			<div class="right"></div>
   		</div>
   </div>
</body>
<script type="text/javascript">
	var div_id = '';
    var dept_id = '';
    var sec_id = '';
	function selectUserType(){
		if($('#login_type').val()=="E"){
			$('#user-depart').hide();
			$('#user-emptype').show();
		}else if($('#login_type').val()=="D"){
			$('#user-emptype').hide();
			$('#user-depart').show();
		}
	}

	 $(function(){
        $("input, textarea").uniform();
        $(".uniform-select").uniform();
        $('.chzn-select').chosen({width: "95%"});
      });
	
	function save_from(id){
			  var radioapprove=$('[name=radioapprove]:checked').val();
			  if(radioapprove==undefined){
				alert('กรุณาเลือกการอนุมัติ หรือไม่อนุมัติ');
				return false;
			  }
			  var  	param = "id="+id;
					param += "&check_password="+$("#check_password").val();
					param += "&radioapprove="+radioapprove;
					param += "&detail_noapprove="+$("#detail_noapprove").val();
					param += "&cmd=save_from";	
					param += "&xid="+Math.random();	
					//alert(param);
		$.ajax({
		uel:'?',
		data:encodeURI(param),
		type:'POST',
		success: function(data){
			//alert(data);
					if(data==1){
						alert('ทำการอนุมัติเรียบร้อยค่ะ');
						parent.changePage('assing');
						parent.changePage('start');
						parent.closePopup();
					}else if(data==3){
						alert('บันทึกข้อมูลไม่อนุมัติเรียบร้อยค่ะ');
						parent.changePage('assing');
						parent.changePage('start');
						parent.closePopup();
					}else if(data==4){
						alert('บันทึกข้อมูลตีกลับแก้ไขเรียบร้อยค่ะ');
						parent.changePage('assing');
						parent.changePage('start');
						parent.closePopup();
					}else{
						alert('รหัสยืนยันไม่ถูกต้อง');
					}
			}
		});		
	}
	function show_approve(){
		$('#detail_noapprove').attr("disabled",true);
		$('#detail_noapprove').val('');
	}
	function show_noapprove(){
		$('#detail_noapprove').attr("disabled",false);
	}
</script>
</html>