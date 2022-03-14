<?php 
include '../../../../lib/db_config.php';
include '../../../../main/modules/Model_Utilities.php';
include '../../../../pis_sys/models/user_model.php';

$utilMD = new Model_Utilities();
$userMD = new Model_User();
$empId = $_REQUEST['e_id'];
$userId = $_REQUEST['u_id'];
$Fdoc_app_id = $_REQUEST['Fdoc_app_id'];
$compId = (!empty($_REQUEST['compId']))?$_REQUEST['compId'] : "7";
$comList = $utilMD->get_CompList();
//$brnList = $utilMD->get_BranchList('');
$empInfo= json_decode($utilMD->get_EmpById($empId));
$userInfo = $userMD->get_data($userId);
$Fdoc_app_id = $_REQUEST['Fdoc_app_id'];
$managerOption = $utilMD->get_ManagerList();
$EditorMtList = $utilMD->get_EditorMtList();
$FJobLevelGROUP = $utilMD->FJobLevelGROUPDocApp();
//$FLevel = $utilMD->FLevel();

if($JobLevel_Data=='ok'){
		$sql_check="SELECT
					tbl_fjoblevel.FJobLevel
					FROM general_db.tbl_fjoblevel
					WHERE
					tbl_fjoblevel.FJobLevel='".$FJobLevel."' ";		
		$query_check=mysql_query($sql_check); 
		$row_check=mysql_fetch_assoc($query_check);
		echo trim($row_check[FJobLevel]);
	
	
		exit();
}

if($getInf_mt_no=='ok'){
		$sql_check="SELECT
					tbl_request.FReqNo,
					tbl_request.FRepair_comp_id,
					tbl_request.FBranchID,
					tbl_request.FJobLevel
					FROM
					mtrequest_db.tbl_request
					WHERE
					tbl_request.FReqNo='".$FInf_mt_no."' ";		
		$query_check=mysql_query($sql_check); 
		$num_check=mysql_num_rows($query_check); 
		$row_check=mysql_fetch_assoc($query_check);
	if($num_check<=0){
		echo "0|";
	}else{
		echo trim($row_check[FRepair_comp_id])."|";
		echo trim($row_check[FBranchID])."|";
		echo trim($row_check[FJobLevel])."|";
		
	}
	
	
		exit();
}
if($select_manager=='ok'){
	
	  
		$sql_check="SELECT
					general_db.tbl_manager.FManagerID,
					general_db.tbl_manager.FName,
					general_db.tbl_manager.emp_code_full,
					pis_db.tbl_employee.emp_id,
					pis_db.tbl_position.post_id,
					pis_db.tbl_position.post_name
					FROM
					general_db.tbl_manager
					LEFT JOIN pis_db.tbl_employee ON general_db.tbl_manager.emp_code_full = pis_db.tbl_employee.emp_code_full
					LEFT JOIN pis_db.tbl_position ON pis_db.tbl_employee.post_id = pis_db.tbl_position.post_id
					WHERE
					tbl_manager.FManagerID='".$smID."' ";		
		$query_check=mysql_query($sql_check); 
		$num_check=mysql_num_rows($query_check); 
		$row_check=mysql_fetch_assoc($query_check);
	
		echo iconv("tis-620","utf-8",trim($row_check[FName]))."|";
		echo trim($row_check[emp_id])."|";
		echo trim($row_check[post_id])."|";
		if($row_check[post_name]!=''){
		echo "( ".iconv("tis-620","utf-8",trim($row_check[post_name]))." )|";
		}else{

			echo "|";
		}
	
	
	
	
		exit();
}

if($select_manager_mt=='ok'){
	
	  
		$sql_check="SELECT
					tbl_user.user_id,
					tbl_user.emp_id,
					tbl_user.first_name,
					tbl_user.last_name,
					tbl_position.post_id,
					tbl_position.post_name
					FROM
					pis_db.tbl_user
					LEFT JOIN pis_db.tbl_employee ON tbl_user.emp_id = tbl_employee.emp_id
					LEFT JOIN pis_db.tbl_position ON tbl_employee.post_id = tbl_position.post_id
					WHERE
					tbl_user.user_id='".$smID."' ";		
		$query_check=mysql_query($sql_check); 
		$num_check=mysql_num_rows($query_check); 
		$row_check=mysql_fetch_assoc($query_check);
	
		echo iconv("tis-620","utf-8",trim("คุณ".$row_check[first_name]." ".$row_check[last_name]))."|";
		echo trim($row_check[emp_id])."|";
		echo trim($row_check[post_id])."|";
	if($row_check[post_name]!=''){
		echo "( ".iconv("tis-620","utf-8",trim($row_check[post_name]))." )|";
	}else{
		
		echo "|";
	}
	
	
	
		exit();
}

$date = date('Y-m-d');
$time = (date('H')).":".date('i');

$s_deFileTemp="SELECT
				FAttachID,
				ip_up,
				FAttachName,
				FAttachLink,
				FAttachType,
				FAttachSize,
				FAttach_date
				FROM mtrequest_db.tbl_docapp_attach_temp
				WHERE 1
				AND (ip_up='".$_SERVER['REMOTE_ADDR']."'
				OR FAttach_date < '".$date."') ";
$q_deFileTemp=mysql_query($s_deFileTemp);
while($r_deFileTemp=mysql_fetch_assoc($q_deFileTemp)){
	$delete_sql = "DELETE FROM mtrequest_db.tbl_docapp_attach_temp WHERE FAttachID='".$r_deFileTemp[FAttachID]."'";
	$delete_rst = mysql_query($delete_sql);
	unlink('../../../../docapp_attach_temp/'.$r_deFileTemp[FAttachLink]);
} 		
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-874">
<title>Request</title>
<script  type="text/javascript" src="../../../../jsLib/jquery-1.8.0.min.js"></script>
<!-- Select2 -->
<script src="../../../../css/select2/dist/js/select2.full.min.js"></script>
<script src="../../../../jsLib/uniform/jquery.uniform.js" type="text/javascript" charset="utf-8"></script>

<script  type="text/javascript" src="../../../../jsLib/jquery-chosen/chosen.jquery.js"></script>
<script src="../../../../jsLib/js_scripts/js_function.js" type="text/javascript" charset="utf-8"></script>
<script  type="text/javascript" src="../../../../jsLib/jquery-confirm/jquery.confirm.js"></script>
<script  type="text/javascript" src="../../../../jsLib/jquery-confirm/js/script.js"></script>
<script type="text/javascript" src="../../../../jsLib/datepicker/zebra_datepicker.js"></script>

 
 <!-- Select2 -->
<link rel="stylesheet" href="../../../../css/select2/dist/css/select2.min.css">
<link href="../../../../css/dialog-box.css" rel="stylesheet" type="text/css">
<link href="../../../../css/display.css" rel="stylesheet" type="text/css">
<link href="../../../../jsLib/jquery-chosen/chosen.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="../../../../jsLib/uniform/css/uniform.default.css" type="text/css" media="screen">
<link href="../../../../css/sys_controll.css" rel="stylesheet" type="text/css">
<link href="../../../../css/input.css" rel="stylesheet" type="text/css">
<link href="../../../../jsLib/jquery-confirm/jquery.confirm.css" rel="stylesheet" type="text/css">
<link href="../../../../jsLib/datepicker/css/default.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="../../../ckeditor/ckeditor.js"></script>
<script>
$(function(){
   // $("input, textarea").uniform();
    $(".uniform-select").uniform();

  });
</script>
<style>

 .FontPued {
           font-size: 12px;
 		  font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
       } 
.border_all{ border:1px solid #000;}
.border_all_Tnone{ border:1px solid #000; border-top:none;}
.border_all_Bnone{ border:1px solid #000; border-bottom:none;}
.border_TR{ border-top:1px solid #000;border-right:1px solid #000;}
.border_TL{ border-top:1px solid #000;border-left:1px solid #000;}
.border_BR{ border-bottom:1px solid #000;border-right:1px solid #000;}
.border_BL{ border-bottom:1px solid #000; border-left:1px solid #000;}
.border_t{ border-top:1px solid #000;}
.border_b{ border-bottom:1px solid #000;}
.border_r{ border-right:1px solid #000;}
.border_l{ border-left:1px solid #000;}
.border_b_double{border-bottom: double;}
.box_request {border:none;border-bottom: 1px dotted ; }
.cursor_p {cursor:pointer;}	
</style>
</head>

<body scrolling="no">
  <div class="content-top">
  	<div class="_content-title">สร้างเอกสารขอความเห็นชอบและอนุมัติ</div>
  	<div class="search-action">
  		<span class="label">&nbsp;</span>
  		<span class="request_no" style="color:#000066;font-weight:bold"></span>
  	</div>
  </div>
  <div class="list-body" style="box-shadow: 0 0 5px #26384A;overflow:auto">
    <form name="form1" id="form1"   action="../../../controllers/documents-app-controller.php?"  method="post" enctype="multipart/form-data"  target="upload_target">
  <br>
      <table width="98%" align="center" border="0" cellpadding="0" cellspacing="0" class="border_all"> 
		  
		  <?php /* /////////////////////////////////////////////////// */?>
		<tr>
		  <td height="35" width="25%"  class="border_b" align="center">&nbsp;</td>
		  <td width="45%"  class="border_b" align="center">เอกสารขอความเห็นชอบและอนุมัติ</td>
		  <td width="30%"  class="border_b" >
			  <select name="fields[Fcomp_id]" id="Fcomp_id" class="chzn-select" onChange="javascript:updateBranch('');" style="width: 95%;">
				 <option value="">-------------------บริษัท-------------------</option>
				 <?php if(!empty($comList)){ foreach ($comList as $key=>$val){?>
				 <option value="<?php echo $val['comp_id'];?>" ><?php echo $val['comp_code']." - ".$val['comp_name'];?></option>
				 <?php }}?>
			   </select>
			</td>
		</tr>
		   <?php /* /////////////////////////////////////////////////// */?>
		  <tr><td colspan="3">&nbsp;</td></tr>
		   <?php /* /////////////////////////////////////////////////// */?>
		   <tr id="show_return_edit"  style="display: none;">
			  <td colspan="3">
					<div id="return_edit_emp_name"></div>
				  
			  </td>
		  </tr>
		  <?php /* /////////////////////////////////////////////////// */?>
		  <tr>
			  <td colspan="3">
				  
			  			<table width="100%" border="0">
						  <tbody>
							<tr>
							  <td width="10%">อ้างอิง MT Request</td>
							  <td width="15%"><input type="text" class="box_request" style="width: 70%;" name="fields[FInf_mt_no]" id="FInf_mt_no" value="" onkeyup="javascript:if(event.keyCode=='13')getInf_mt_no();">
								  <img src="../../../../images/action-icon/Search01.png" style="cursor: pointer;" onClick="getInf_mt_no();"></td>
							  <td width="75%" align="right"></td>
							</tr>
						  </tbody>
						</table>

				  
			  </td>
		  </tr>
		  <?php /* /////////////////////////////////////////////////// */?>
		  <tr>
			  <td colspan="3">
				  
			  			<table width="100%" border="0">
						  <tbody>
							<tr>
							  <td width="7%">โครงการ <font color="#FF0000">*</font> </td>
							  <td width="68%"><input type="text" class="box_request" style="width: 90%;" name="fields[Fdoc_app_project]" id="Fdoc_app_project" value=""></td>
							  <td width="10%" align="right">วันที่</td>
							  <td width="15%">
								  <input type="text" class="box_request" style="text-align: center; width: 95%;" name="fields[Fdoc_app_date]" id="Fdoc_app_date" readonly value="<?php print($date);?>">
								  
								   <input type="hidden" name="Fdoc_app_id" id="Fdoc_app_id" value="<?=$Fdoc_app_id?>">
								  <input type="hidden" class="box_request" name="fields[Fdoc_appSt]" id="Fdoc_appSt" value="new">
								  <input type="hidden" name="user_id" id="user_id" value="<?=$userId?>">
								  <input type="hidden"  name="function" id="function" value="">
								  
							 </td>
							</tr>
						  </tbody>
						</table>

				  
			  </td>
		  </tr>
		  <?php /* /////////////////////////////////////////////////// */?>
		  <tr>
			  <td colspan="3">
			  
				  <table width="100%" border="0">
						  <tbody>
							<tr>
							  <td width="7%">สาขา <font color="#FF0000">*</font> </td>
							  <td width="32%">
		  <select name="fields[FBranchID]" id="FBranchID" class="uniform-select" style="width:">
		 <option value="">-------สาขา-------</option>
		 <?php if(!empty($brnList)){ foreach ($brnList as $key=>$val){?>
		 <option value="<?php echo $val['brn_id'];?>"  ><?php echo $val['brn_code']." - ".$val['brn_name'];?></option>
		 <?php }}?>
	   </select>
								</td>
								 <td width="2%">
									 <input type="checkbox" name="fields[FworkSt]" id="FworkSt" value="Y" class="FworkSt">
								</td>
								 <td width="7%">ราคาค่างาน</td>
							  <td width="15%"> 
								  <input type="text" class="box_request" style="text-align: center; width: 80%;" name="fields[Fwork_price]" id="Fwork_price"  value="" readonly onkeyup="javascript:changNumeric(this);">&nbsp;บาท</td>
							 <td width="11%"></td>
							  <td width="10%" align="right">เลขที่</td>
								
							  <td width="15%">
							    <input type="text" class="box_request" style="text-align: center; width: 95%;" name="fields[Fdoc_app_no]" id="Fdoc_app_no" readonly value="">
							 </td>
							</tr>
						  </tbody>
						</table>
				  
				  
			  </td>
		  </tr>
		  <?php /* /////////////////////////////////////////////////// */?>
		  <tr>
			  <td colspan="3">
			  
				  <table width="100%" border="0">
						  <tbody>
							<tr>
							  <td width="7%">เรื่อง <font color="#FF0000">*</font> </td>
							  <td width="32%">
								<input type="text" class="box_request" style="width: 80%;" name="fields[Fdoc_app_name]" id="Fdoc_app_name"  value="">
							 </td>
								 <td width="2%">
									 <input type="checkbox" name="fields[Fmaterial_constructionSt]" id="Fmaterial_constructionSt1" value="1" class="Fm_constructionSt">
								</td>
							   <td width="7%">แบบก่อสร้าง</td>
							  <td width="2%"> 
								 <input type="checkbox" name="fields[Fmaterial_constructionSt]" id="Fmaterial_constructionSt2" value="2" class="Fm_constructionSt"></td>
							 <td width="24%">วัสดุ</td>
							  <td width="10%" align="right">ผู้รับเหมา</td>
								
							  <td width="15%">
							    <input type="text" class="box_request" style="width: 95%;" name="fields[Fcontractor]" id="Fcontractor"  value="">
							 </td>
							</tr>
						  </tbody>
						</table>
			  
			  </td>
		  </tr>
		  <?php /* /////////////////////////////////////////////////// */?>
		  <tr>
			  <td colspan="3">
			  
			  <table width="100%" border="0">
						  <tbody>
							<tr>
							  <td width="7%">เรียน <font color="#FF0000">*</font> </td>
							  <td width="32%">
<select name="fields[FSupervisorID]" id="FSupervisorID" style="width: 80%;" class="chzn-select" onChange="select_manager('Supervisor');">
<option value="">---ผู้อนุมัติงาน---</option>
<?php if(!empty($managerOption)){ foreach ($managerOption as $key=>$val){?>
	<option value="<?php echo $val['FManagerID'];?>"><?php echo $val['FName'];?></option>
<?php }}?>
</select>
					
	<input type="hidden" name="fields[FSupervisor_emp_id]" id="FSupervisor_emp_id">
	<input type="hidden" name="fields[FSupervisorPost_id]" id="FSupervisorPost_id">			  
								  
							 </td>
								 <td width="2%">&nbsp;</td>
							   <td width="3%">จาก</td>
							   <td width="31%">
		<select name="fields[FownerID]" id="FownerID" style="width: 80%;" class="chzn-select" onChange="select_managerMT('owner');">
   					<option value="">---เจ้าหน้าที่ซ่อมบำรุง---</option>
   					 <?php  if(!empty($EditorMtList)){ foreach ($EditorMtList as $key=>$val){  ?>
             <option value="<?php echo $val['user_id'];?>"  >คุณ<?php echo $val['first_name']."  ".$val['last_name'];?></option>
             <?php }}?>
	 </select>			    
	<input type="hidden" name="fields[FownerPost_id]" id="FownerPost_id">
	<input type="hidden" name="fields[Fowner_emp_id]" id="Fowner_emp_id">
							</td>
							  <td width="10%" align="right">ประเภทงาน</td>
								
							  <td width="15%">
					<select name="fields[FJobLevel]" id="FJobLevel" class="uniform-select" onChange="select_JobLevel();" >
		            	<option value="">--ประเภทงาน --</option>
                         <?php if(!empty($FJobLevelGROUP)){ foreach ($FJobLevelGROUP as $key=>$val){?>
					 <option value="<?php echo $val['FJobLevel'];?>"><?php echo iconv("UTF-8","TIS-620",$val['FJobLevel_name'])?></option>
					 <?php }}?>
                      </select>    
							 </td>
							</tr>
						  </tbody>
						</table>
				  
				  
			  </td>
		  
		  </tr>
		  <?php /* /////////////////////////////////////////////////// */?>
		  <tr>
			  <td colspan="3" class="border_b_double">&nbsp;</td>
		  </tr>
		  <?php /* /////////////////////////////////////////////////// */?>
		  <tr><td colspan="3">&nbsp;</td></tr>
		  <?php /* /////////////////////////////////////////////////// */?>
		  <tr>
			  <td colspan="3">
			  
				  <table width="100%" border="0">
						  <tbody>
							<tr>
							  <td width="10%">สิ่งที่แนบมาด้วย</td>
							  <td width="90%">
								<input type="text" class="box_request" style="width: 99%;" name="fields[Fattach_infor]" id="Fattach_infor"  value="">
							 </td>
							</tr>
						  </tbody>
						</table>
				  
			  </td>
		  </tr>
		  <?php /* /////////////////////////////////////////////////// */?>
		  <tr>
			  <td colspan="3">
			  
				  <table width="100%" border="0">
						  <tbody>
							<tr>
							  <td width="10%">ปีที่ซื้อเครื่องจักร <font color="#FF0000">*</font>&nbsp;ปี</td>
							  <td width="13%">
	 <select name="fields[Fmachine_year]" id="Fmachine_year" class="uniform-select" style="width:">
		 <option value="">-------ปีที่ซื้อเครื่องจักร-------</option>
		 <?php  
		 $sy=(date("Y")-50);
		 $ey=(date("Y")+3);
		 for ($y=$sy;$y<=$ey;$y++){
		 ?>
		 <option value="<?php echo $y;?>" <?PHP if($y==date("Y")){  echo "selected";}?>   ><?php echo ($y+543)?></option>
		 <?php 	}?>
	   </select>
							 </td>
								 <td width="7%">ราคาเครื่องจักร</td>
								<td width="15%">
								<input type="text" class="box_request" style="width: 80%; text-align: center;" name="fields[Fmachine_price]" id="Fmachine_price"  value="" onkeyup="javascript:changNumeric(this);">&nbsp;บาท
							 </td>
								 <td width="12%">รวมค่าใช้จ่ายซ่อมที่ผ่านมา</td>
								<td width="15%">
								<input type="text" class="box_request" style="width: 80%; text-align: center;" name="fields[Fmachine_hisRepair_amt]" id="Fmachine_hisRepair_amt"  value="" onkeyup="javascript:changNumeric(this);">&nbsp;บาท
							 </td>
								<td width="28%">&nbsp;</td>
							</tr>
						  </tbody>
						</table>
				  
			  </td>
		  </tr>
		  <?php /* /////////////////////////////////////////////////// */?>
		  <tr>
			  <td colspan="3">
			  
				  <table width="100%" border="0">
						  <tbody>
							<tr>
							  <td width="10%">ลักษณะการชำรุด <font color="#FF0000">*</font> </td>
								 <td width="2%">
									 <input type="checkbox" name="fields[FdamagedSt]" id="FdamagedSt1" value="1" class="FdamagedSt">
								</td>
							   <td width="7%">ชำรุดตามสภาพ</td>
							  <td width="2%"> 
								 <input type="checkbox" name="fields[FdamagedSt]" id="FdamagedSt2" value="2" class="FdamagedSt"></td>
							 <td width="24%">ชำรุดจากใช้งาน</td>
							  <td width="60%"> </td>
							</tr>
						  </tbody>
						</table>
				  
			  </td>
		  </tr>
		  
		   <?php /* /////////////////////////////////////////////////// */?>
		  <tr>
			  <td colspan="3" class="border_b_double">&nbsp;</td>
		  </tr>
		  <?php /* /////////////////////////////////////////////////// */?>
		  <tr>
			  <td colspan="3" class="border_b_double">
			  
				  <table width="100%" border="0">
						  <tbody>
							<tr>
							  <td width="20%">&nbsp;</td>
								 <td width="2%">
									 <input type="checkbox" name="fields[FAcknowledgeSt]" id="FAcknowledgeSt" value="Y">
								</td>
							   <td width="10%">เพื่อรับทราบ</td>
							  <td width="2%"> 
								 <input type="checkbox" name="fields[FAsk_for_approvalSt]" id="FAsk_for_approvalSt" value="Y" >
								</td>
							 <td width="10%">ขอความเห็นชอบ</td>
								<td width="2%"> 
								 <input type="checkbox" name="fields[FTo_approveSt]" id="FTo_approveSt" value="Y">
								</td>
							 <td width="10%">เพื่ออนุมัติ</td>
								<td width="2%"> 
								 <input type="checkbox" name="fields[FexpressSt]" id="FexpressSt" value="Y">
								</td>
							 <td width="10%">ด่วน</td>
								<td width="2%"> 
								 <input type="checkbox" name="fields[FPlease_considerSt]" id="FPlease_considerSt" value="Y">
								</td>
							 <td width="10%">โปรดพิจารณา</td>
							  <td width="20%"> </td>
							</tr>
						  </tbody>
						</table>
			  
			  </td>
		  </tr>
		  <?php /* /////////////////////////////////////////////////// */?>
		  <tr>
			  <td colspan="3">
			  
				   <textarea cols="80" class="span6" id="Fdoc_app_detail" name="Fdoc_app_detail" rows="10"><?=stripslashes($row[Fdoc_app_detail])?>
                                                    </textarea>
				  
			  </td>
		  
		  </tr>
		  <?php /* /////////////////////////////////////////////////// */?>
		  <tr>
			  <td colspan="3">
				  <table width="100%" border="0">
						  <tbody>
							<tr>
						 <?php /* //////////////////////sub///////////////////////////// */?>		
						 <td width="70%">
								  
						<table width="100%" border="0">
						  <tbody>
							<tr>
							  <td width="19%">&nbsp;</td>
							  <td width="41%">
						
						 <?php /* //////////////////////sub///////////////////////////// */?>		
						 <table width="100%" border="0" class="border_all">
						  <tbody>
							<tr>
							  <td width="43%">&nbsp;</td>
							  </tr>
							<tr>
							  <td width="43%">
								  <input type="checkbox" name="FmanagerBP_GSApp" id="FmanagerBP_GSApp" value="Y" disabled> อนุมัติ
								</td>
							  </tr>
							  <tr>
							    <td width="43%">
									<input type="checkbox" name="FmanagerBP_GSApp" id="FmanagerBP_GSApp" value="N" disabled> ไม่อนุมัติ
								  </td>
							  </tr>
							
							  <tr>
							    <td width="43%">
								  <textarea style="width: 98%;" class="span6" id="FmanagerBP_GS_comment" name="FmanagerBP_GS_comment" rows="5" readonly></textarea>
								</td>
							  </tr>
							
							  <tr>  
							    <td width="43%" height="50" align="center"><div id="managerBP_GSSignature">&nbsp;</div></td>
							  </tr>
						    <tr>
						      <td align="center"><select name="fields[FmanagerBP_GSID]" id="FmanagerBP_GSID" style="width: 80%;" class="chzn-select"  onChange="select_manager('managerBP_GS');">
						        <option value="">---ผู้อนุมัติงาน BP/GS---</option>
						        <?php if(!empty($managerOption)){ foreach ($managerOption as $key=>$val){?>
						        <option value="<?php echo $val['FManagerID'];?>"><?php echo $val['FName'];?></option>
						        <?php }}?>
						        </select>
								
	<input type="hidden" name="fields[FmanagerBP_GS_emp_id]" id="FmanagerBP_GS_emp_id">
	<input type="hidden" name="fields[FmanagerBP_GSPost_id]" id="FmanagerBP_GSPost_id">
								</td>
						      </tr>
						    <tr>
						      <td width="43%" align="center">
								  <div id="managerBP_GSPostName">&nbsp;</div></td>
							  </tr>
						  </tbody>
				 </table>
					
						 <?php /* //////////////////////sub///////////////////////////// */?>			
							 </td>
							  <td width="40%">&nbsp;</td>
							
							</tr>	
						  </tbody>	
						</table>  
						 
								  
								  
							</td>
							
						 <?php /* //////////////////////sub///////////////////////////// */?>	
							  <td width="30%">
								  
								 <table width="100%" border="0">
						  <tbody>
							<tr>
							  <td width="22%" align="center">จึงเรียนมาเพื่อทราบ</td>
							</tr>
							  <tr>
							  <td width="22%" align="center"><span id="Fowner_comment"></span></td>
						    </tr>
							<tr>
							  <td width="22%" align="center"><span id="ownerSignature"></span></td>
						    </tr>
						    <tr>
						      <td width="22%" align="center"><span id="ownerName2"></span></td>
					        </tr>
							  <tr>
							  <td width="22%" align="center"><span id="ownerPostName"></span></td>
							</tr>
							  <tr>
							  <td width="22%" align="center"><span id="Fmanager_mt_comment"></span></td>
							</tr>
							<tr>
							  <td width="22%" align="center" height="50"><span id="manager_mtSignature"></span></td>
						    </tr>
						    <tr>
							    <td align="center">
<select name="fields[Fmanager_mtID]" id="Fmanager_mtID" style="width: 80%;" class="chzn-select" onChange="select_managerMT('manager_mt');">
  <option value="">---ผู้อนุมัติงานแผนกซ่อมบำรุง---</option>
  <?php  if(!empty($EditorMtList)){ foreach ($EditorMtList as $key=>$val){  ?>
	<option value="<?php echo $val['user_id'];?>"  >คุณ<?php echo $val['first_name']."  ".$val['last_name'];?></option>
	<?php }}?>
  </select>
	<input type="hidden" name="fields[Fmanager_mt_emp_id]" id="Fmanager_mt_emp_id">
	<input type="hidden" name="fields[Fmanager_mtPost_id]" id="Fmanager_mtPost_id">
						        </td>
					        </tr>
						    <tr>
							  <td width="22%" align="center"><span id="manager_mtPostName"></span></td>
							</tr>
						  </tbody>
				 </table> 
								  
								  
							  </td>
						    </tr>
							</tbody>
				  </table>
				  
			  	  
			  </td>
		  </tr>
		  <?php /* /////////////////////////////////////////////////// */?>
		  <tr><td colspan="3" class="border_b">&nbsp;</td></tr>
		  <?php /* /////////////////////////////////////////////////// */?>
		  <tr>
			  <td colspan="3">
			  
				 <table width="100%" border="0">
						  <tbody>
							<tr>
							  <td width="7%" align="center">ถึง</td>
							  <td width="32%"><span id="ownerName1">&nbsp;</span></select>
							 </td>
								 <td width="2%">&nbsp;</td>
							   <td width="3%">จาก</td>
							   <td width="31%"><span id="SupervisorName1">&nbsp;</span></td>
							  <td width="10%" align="right"></td>
								
							  <td width="15%"></td>
							</tr>
						  </tbody>
						</table> 
				  
				  
			  </td>
		  </tr>
		<?php /* /////////////////////////////////////////////////// */?>
		  <tr>
			  <td colspan="3" class="border_b_double">&nbsp;</td>
		  </tr>
		  <?php /* /////////////////////////////////////////////////// */?>
		  <tr>
			  <td colspan="3">
				 
				<table width="100%" border="0">
						  <tbody>
							<tr>
							  <td width="20%">ผลการพิจารณา</td>
								 <td width="2%">
									 <input type="checkbox" name="fields[FSupervisorApp]" id="FSupervisorAppY" value="Y" class="FSupervisorApp" disabled>
								</td>
							   <td width="10%">อนุมัติ</td>
							  <td width="2%"> 
								 <input type="checkbox" name="fields[FSupervisorApp]" id="FSupervisorAppN" value="N" class="FSupervisorApp" disabled></td>
							 <td width="10%">ไม่อนุมัติ</td>
								<td width="2%"> 
								 <input type="checkbox" name="fields[FSupervisorApp]" id="FSupervisorAppYnote" value="Ynote" class="FSupervisorApp" disabled></td>
							 <td width="10%" >อนุมัติ/หมายเเหตุ</td>
								<td width="2%">
							  <input type="checkbox" name="fields[FSupervisorApp]" id="FSupervisorAppother" value="other" class="FSupervisorApp" disabled></td>
							 <td width="2%">อื่นๆ</td>
							 <td width="41%"><input name="fields[FSupervisorOther_note]" type="text" class="box_request" id="FSupervisorOther_note" style="text-align: center; width: 95%;"  value="" readonly></td>
							</tr>
						  </tbody>
						</table>  
				  
			  </td>
		 </tr>
		  <?php /* /////////////////////////////////////////////////// */?>
		  <tr>
			  <td colspan="3">
			  
				  <table width="100%" border="0">
						  <tbody>
							<tr>
							  <td width="8%">ความคิดเห็น</td>
								 <td width="92%">
<textarea style="width: 97%;" name="fields[FSupervisor_comment]" id="FSupervisor_comment" readonly></textarea>
								</td>
							</tr>
						  </tbody>
						</table>
				  
			  </td>
	    </tr>
		  <?php /* /////////////////////////////////////////////////// */?>
		  <tr>
			  <td colspan="3">
			  
				 <table width="100%" border="0">
						  <tbody>
							<tr>
							  <td align="center">&nbsp;</td>
							  <td>&nbsp;</td>
							  <td align="center">&nbsp;</td>
						    </tr>
							<tr>
							  <td align="center" height="50">&nbsp;</td>
							  <td>&nbsp;</td>
							  <td align="center"><span id="SupervisorSignature">&nbsp;</span></td>
						    </tr>
							<tr>
							    <td align="center">&nbsp;</td>
							    <td>&nbsp;</td>
							    <td align="center"><span id="SupervisorName2">&nbsp;</span></td>
						    </tr>
						    <tr>
							  <td width="39%" align="center">&nbsp;</td>
							  <td width="39%">&nbsp;</td>
							   <td width="22%" align="center"><span id="SupervisorPostName">&nbsp;</span></td>
							</tr>
						  </tbody>
				 </table> 
				  
			  </td>
		</tr>
		  <?php /* /////////////////////////////////////////////////// */?>
		  <tr><td colspan="3">
			 <table width="100%" border="0">  
			  <tbody id="attachment" >
         	<tr>
	           <td><b>ไฟล์แนบ</b> : &nbsp;</td>
	           <td colspan="5">
	           		<input type="file" name="fileUpload" id="fileUpload">
				   <div class="button-bule" onclick="javascript:uploadData();">เพิ่มไฟล์</div>
	           </td>
	         </tr>
	         <tr id="show_attach-list_temp" style="display:none;">
	           <td>&nbsp;</td>
	           <td colspan="5" id="attach-list_temp" >
	           		
	           </td>
	         </tr>
             <tr>
	           <td>&nbsp;</td>
	           <td colspan="5" id="attach-list" >
	           		
	           </td>
	         </tr>
         </tbody>
			  </table>
			</td></tr>
		  <?php /* /////////////////////////////////////////////////// */?>
	
	</tr>
		  <?php /* /////////////////////////////////////////////////// */?>
		  <tr><td colspan="3"></td></tr>
		  <?php /* /////////////////////////////////////////////////// */?>
	 </table>
		
    </form>
    <iframe id="upload_target" name="upload_target" src="" style="width:100%;height:400px;border:1px solid #ccc; display:none"></iframe>
  </div>
  <div class="content-top" >
    <table width="98%" align="center">
    	<tr>
   			<td height="20" align="center">
			<a class="button-bule"  id="SendMail-btn" href="javascript:void(0);" onclick="javascript:chkSave();"> ส่งขออนุมัติ  </a>	
   			<a class="button-bule"  id="save-btn" href="javascript:void(0);" onclick="javascript:saveData('');"> บันทึก  </a>
			<a class="button-bule"  id="print-btn" href="javascript:void(0);" onclick="javascript:printData();"> พิมพ์  </a>	
   			<a class="button-bule" href="javascript:void(0);" onclick="javascript:cancel();"> ยกเลิก  </a>
   			</td>
   		</tr>
    </table>	
  </div>
  <div class="login-overlay" style="display:none">
  		<div class="preloading"></div>
  </div>
  
</body>

<script>
	 ////blog_detail คือ id textarea
  //<![CDATA[\
	 CKEDITOR.replace( 'Fdoc_app_detail',{
		toolbar:[ ['Source','Preview','Bold', 'Italic', 'Underline', '-', 'Subscript','Strike', 'Superscript', '-', 'NumberedList', 'BulletedList', '-', 'Link', 'Unlink'],['Outdent', 'Indent', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', 'Language'],
  ['Image','Video', 'Flash', 'Smiley','Iframe', '-', 'Table', 'HorizontalRule', 'SpecialChar'/* ,'Font','FontSize','Styles', 'Format' */,'TextColor', 'BGColor' ] ,['Maximize', 'ShowBlocks' ]], 
	  language : 'en',
	  height : 650,
	  filebrowserBrowseUrl : '../../../ckeditor/ckfinder/ckfinder.html'/* ,
	  filebrowserImageBrowseUrl : 'ckeditor/ckfinder/ckfinder.html?Type=Images',
	  filebrowserFlashBrowseUrl : 'ckeditor/ckfinder/ckfinder.html?Type=Flash',
	  filebrowserUploadUrl : 'ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
	  filebrowserImageUploadUrl : 'ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
	  filebrowserFlashUploadUrl : 'ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash' */
	  } );
	
	
	//$('.select2').select2();
	$('.select2').select2({ containerCssClass: "FontPued" ,dropdownCssClass: "FontPued" });
	var  user_id = '<?=$userId?>';
	$(document).ready(function (){
		var height = $(document).height();
		var main_body_height = height-85;
		var preloading = (height-40)/2;
		$(".list-body").height(main_body_height);
		$(".preloading").attr("style","margin-top:"+preloading+"px;");
		
	
		

	});

	 $(function(){
        $('.chzn-select').chosen({});
		 
		 $('#print-btn').hide();   		 
		 $('.FworkSt').click(function(){
			 var FworkSt=$('.FworkSt:checked').val();
				 if(FworkSt=="Y"){
				  $('#Fwork_price').attr("readonly",false) ;
				}else{
				  $('#Fwork_price').attr("readonly",true) ;
				  $('#Fwork_price').val("") ;
				} 
			});
		 
		 	$('.Fm_constructionSt').click(function(){
				//alert('55559');
				var valueFmc=$(this).val();
				if(valueFmc==1){
				  $('#Fmaterial_constructionSt2').attr("checked",false) ;
				}else if(valueFmc==2){
				  $('#Fmaterial_constructionSt1').attr("checked",false) ;
				}
			});
		 
		 	$('.FdamagedSt').click(function(){
				//alert('55559');
				var valuedst=$(this).val();
				if(valuedst==1){
				  $('#FdamagedSt2').attr("checked",false) ;
				}else if(valuedst==2){
				  $('#FdamagedSt1').attr("checked",false) ;
				}
			});
		 
		   $('.FSupervisorApp').click(function(){
				//alert('55559');
				var valueSApp=$(this).val();
			    var valueSApp2=$('.FSupervisorApp:checked').val();
			   
				if(valueSApp2==undefined){
				  $('#FSupervisorOther_note').val("") ;
				  $('#FSupervisorOther_note').attr("readonly",true) ;
				  $('#FSupervisor_comment').attr("readonly",true) ;
				  $('#FSupervisor_comment').val("") ;
				}else if(valueSApp=="Y"){
				  $('#FSupervisorAppYnote').attr("checked",false) ;
				  $('#FSupervisorAppN').attr("checked",false) ;
				  $('#FSupervisorAppother').attr("checked",false) ;
				  $('#FSupervisorOther_note').val("") ;
				  $('#FSupervisorOther_note').attr("readonly",true) ;
				  $('#FSupervisor_comment').attr("readonly",false) ;
				}else if(valueSApp=="Ynote"){
				  $('#FSupervisorAppY').attr("checked",false) ;
				  $('#FSupervisorAppN').attr("checked",false) ;
				  $('#FSupervisorAppother').attr("checked",false) ;
				  $('#FSupervisorOther_note').val("") ;
				  $('#FSupervisorOther_note').attr("readonly",true) ;
				  $('#FSupervisor_comment').attr("readonly",false) ;
				}else if(valueSApp=="N"){
				  $('#FSupervisorAppYnote').attr("checked",false) ;
				  $('#FSupervisorAppY').attr("checked",false) ;
				  $('#FSupervisorAppother').attr("checked",false) ;
				  $('#FSupervisorOther_note').val("") ;
				  $('#FSupervisorOther_note').attr("readonly",true) ;
				  $('#FSupervisor_comment').attr("readonly",false) ;
				}else if(valueSApp=="other"){
				  $('#FSupervisorAppY').attr("checked",false) ;
				  $('#FSupervisorAppYnote').attr("checked",false) ;
				  $('#FSupervisorAppN').attr("checked",false) ;
				  $('#FSupervisorOther_note').attr("readonly",false) ;
				  $('#FSupervisor_comment').attr("readonly",false) ;
				}
			});
		 
		 
     });


	function select_JobLevel(){ 
		var	param = 'JobLevel_Data=ok';
				param += '&FJobLevel='+$('#FJobLevel').val();	
				param += '&xid='+Math.random();		
				//alert(param);
				 			getData = $.ajax({
										url:'?',
										data:encodeURI(param),
										async:false,
										success: function(getData){
										$("#FJobLevel").val(getData);	
										  	
										}
							}).responseText;	
	
    }
	
	
	function select_manager(select_type){ 
		//alert(select_type);
		var	param = 'select_manager=ok';
				param += '&select_type='+select_type;	
				param += '&smID='+$("#F"+select_type+"ID").val();	
				param += '&xid='+Math.random();		
				//alert(param);
				 			getData = $.ajax({
										url:'?',
										data:encodeURI(param),
										async:false,
										success: function(getData){
											
										var temp = getData.split('|');
											//alert(temp[0]);
										$("#F"+select_type+"_emp_id").val(temp[1]);	
										$("#F"+select_type+"Post_id").val(temp[2]);
										$("#"+select_type+"PostName").html(temp[3]);
										$("#"+select_type+"Name1").html(temp[0]);
										$("#"+select_type+"Name2").html(temp[0]);
										  	
										}
							}).responseText;	
	
    }
	function select_managerMT(select_type){ 
		//alert(select_type);
		var	param = 'select_manager_mt=ok';
				param += '&select_type='+select_type;	
				param += '&smID='+$("#F"+select_type+"ID").val();	
				param += '&xid='+Math.random();		
				//alert(param);
				 			getData = $.ajax({
										url:'?',
										data:encodeURI(param),
										async:false,
										success: function(getData){
											
										var temp = getData.split('|');
											//alert(temp[0]);
										$("#F"+select_type+"_emp_id").val(temp[1]);	
										$("#F"+select_type+"Post_id").val(temp[2]);
										$("#"+select_type+"PostName").html(temp[3]);
										$("#"+select_type+"Name1").html(temp[0]);
										$("#"+select_type+"Name2").html(temp[0]);
										  	
										}
							}).responseText;	
	
    }
	
	
	function getInf_mt_no(){
		var	param = 'getInf_mt_no=ok';
				param += '&FInf_mt_no='+$('#FInf_mt_no').val();	
				param += '&xid='+Math.random();		
//			/	alert(param);
				 			getData = $.ajax({
										url:'?',
										data:encodeURI(param),
										async:false,
										success: function(getData){
										var temp = getData.split('|');
											//alert(temp[0])
										if(temp[0]==0){
   var buttons = '[{"title":"OK","class":"blue","action":"$(\'#FEstimate\').focus();"}]';
	buttons = eval(buttons);
	   _confirm("warning","Warning","ไม่พบเลข  MT Request เลขที่ "+$('#FInf_mt_no').val(),buttons);
										}
										$("#Fcomp_id").val(temp[0]).trigger("liszt:updated");
										updateBranch(temp[1]);	
										$("#FJobLevel").val(temp[2]);
										$(function(){
											  $.uniform.update("#FJobLevel");
										  });
											
										//$("#FJobLevel_type option[value=" +temp[3]+"]").attr("selected","selected") ;
									}
							}).responseText;	
	
    }
	function update_detail(){
		//alert('555');
			$('#function').val('upDetailDocApp');
			document.forms[0].submit();
		
    }
	
	 function uploadData(){
		//alert('555');
		if($('#fileUpload').val()==""){
			alert('กรุณาเลือกไฟล์');
		}else{
			//$('#form1').submit();
			$('#function').val('upload_temp');
			
			document.forms[0].submit();
			//$('#form1').attr("action","../../../controllers/documents-app-controller.php?function=upload_temp");
			
		}
    }
	function uploadCompleteTemp(id,filename,url){
		//alert(url);
		
		$('#show_attach-list_temp').show();
        var li = '<ul id="attach-list_temp-'+id+'" class="attach-list">';
		    li+= '   <li style="width:95%"><img src="../../../../images/status-icon/new.gif"">&nbsp;<a href="javascript:void(0);" onclick="javascript:downloadFileTemp(\''+filename+'\',\''+url+'\')">'+filename+'</a></li>';
		    li+= '   <li style="width:5%"><div class="trash-icon" onclick="javascript:deleteFileTemp('+id+',\''+url+'\');"></div></li>';
		    li+= '</ul>';
		$('#attach-list_temp').append(li);
    }
	
    function deleteFileTemp(id,url){
		//alert(url);
		//alert(id);
    	$.ajax({
			type: "POST",
			url: ("../../../controllers/documents-app-controller.php"),
			data: "1&function=delete_file_temp&id="+id+'&url='+url,
			//dataType: 'json',
			success: function(data){
				//alert(id);
				$('#attach-list_temp-'+id).remove(); 
			}
		});
    }
	
    function downloadFileTemp(filename,url){
    	var width = screen.width-10;
		var height = screen.height-60;
		newwindow=window.open('../../../../download-DocAattachTemp.php?name='+filename+'&filename='+url,
									  'downloadWindow','width='+width+',height='+height+',left=0,top=0,screenX=0,screenY=0,status=no,menubar=yes,scrollbars=yes,copyhistory=yes, resizable=yes,fullscreen=no');
    }
	function list_attach(id,filename,url){
	//	alert(id);
        var li = '<ul id="attach-list-'+id+'" class="attach-list">';
		    li+= '   <li style="width:95%"> <a href="javascript:void(0);" onclick="javascript:downloadFile('+$('#Fdoc_app_id').val()+',\''+filename+'\',\''+url+'\')">'+filename+'</a></li>';
		    li+= '   <li style="width:5%"><div class="trash-icon" onclick="javascript:deleteFile('+id+',\''+url+'\');"></div></li>';
		    li+= '</ul>';
		$('#attach-list').append(li);
    }
    function deleteFile(id,url){
		//alert($('#Fdoc_app_id').val());
		//alert(id);
    	$.ajax({
			type: "POST",
			url: ("../../../controllers/documents-app-controller.php"),
			data: "1&function=delete_file&rId="+$('#Fdoc_app_id').val()+'&id='+id+'&url='+url,
			//dataType: 'json',
			success: function(data){
				$('#attach-list-'+id).remove(); 
			}
		});
    }
    function downloadFile(id,filename,url){
    	var width = screen.width-10;
		var height = screen.height-60;
		newwindow=window.open('../../../../download-DocAattach.php?name='+filename+'&reqId='+id+'&filename='+url,
									  'downloadWindow','width='+width+',height='+height+',left=0,top=0,screenX=0,screenY=0,status=no,menubar=yes,scrollbars=yes,copyhistory=yes, resizable=yes,fullscreen=no');
    } 
	
	
	function chkSave(send_app){
		
		var Fm_constructionSt=$('.Fm_constructionSt:checked').val();
		var FdamagedSt=$('.FdamagedSt:checked').val();
		var FworkSt=$('.FworkSt:checked').val();
		if($('#FSupervisorID').val()=="" && send_app==1){
    		var buttons = '[{"title":"OK","class":"blue","action":"$(\'#FSupervisorID\').focus();"}]';
			buttons = eval(buttons);
			_confirm("warning","Warning","กรุณาระบุผู้อนุมัติงาน",buttons);
        }else if($('#FownerID').val()=="" && send_app==1){
    		var buttons = '[{"title":"OK","class":"blue","action":"$(\'#FSupervisorID\').focus();"}]';
			buttons = eval(buttons);
			_confirm("warning","Warning","กรุณาระบุเจ้าหน้าที่ซ่อมบำรุง",buttons);
        }else if($('#Fmanager_mtID').val()=="" && send_app==1){
    		var buttons = '[{"title":"OK","class":"blue","action":"$(\'#Fmanager_mtID\').focus();"}]';
			buttons = eval(buttons);
			_confirm("warning","Warning","กรุณาระบุผู้อนุมัติงานแผนกซ่อมบำรุง",buttons);
        }else if($('#Fcomp_id').val()==""){
    		var buttons = '[{"title":"OK","class":"blue","action":"$(\'#Fcomp_id\').focus();"}]';
			buttons = eval(buttons);
			_confirm("warning","Warning","กรุณาระบุบริษัท",buttons);
        }else if($('#FBranchID').val()==""){
    		var buttons = '[{"title":"OK","class":"blue","action":"$(\'#FBranchID\').focus();"}]';
			buttons = eval(buttons);
			_confirm("warning","Warning","กรุณาระบุสาขา",buttons);
        }else if(FworkSt!=undefined && $('#Fwork_price').val()==""){
    		var buttons = '[{"title":"OK","class":"blue","action":"$(\'#Fwork_price\').focus();"}]';
			buttons = eval(buttons);
			_confirm("warning","Warning","กรุณาระบุราคาค่างาน",buttons);
        }else if($('#Fdoc_app_project').val()==""){
    		var buttons = '[{"title":"OK","class":"blue","action":"$(\'#Fdoc_app_project\').focus();"}]';
			buttons = eval(buttons);
			_confirm("warning","Warning","กรุณาระบุชื่อโครงการ",buttons);
        }else if($('#Fdoc_app_name').val()==""){
    		var buttons = '[{"title":"OK","class":"blue","action":"$(\'#Fdoc_app_name\').focus();"}]';
			buttons = eval(buttons);
			_confirm("warning","Warning","กรุณาระบุชื่อเรื่อง",buttons);
        }else if(Fm_constructionSt=="" || Fm_constructionSt==undefined){
    		var buttons = '[{"title":"OK","class":"blue","action":"$(\'#Fm_constructionSt\').focus();"}]';
			buttons = eval(buttons);
			_confirm("warning","Warning","กรุณาระบุแบบก่อสร้างหรือวัสดุอย่างใดอย่างหนึ่ง",buttons);
        }else if($('#FSupervisorID').val()=="" && status_Sapp==1){
    		var buttons = '[{"title":"OK","class":"blue","action":"$(\'#FSupervisorID\').focus();"}]';
			buttons = eval(buttons);
			_confirm("warning","Warning","กรุณาระบุผู้อนุมัติงาน",buttons);
        }else if($('#FownerID').val()=="" && status_Sapp==1){
    		var buttons = '[{"title":"OK","class":"blue","action":"$(\'#FSupervisorID\').focus();"}]';
			buttons = eval(buttons);
			_confirm("warning","Warning","กรุณาระบุเจ้าหน้าที่ซ่อมบำรุง",buttons);
        }else if($('#Fmachine_year').val()==""){
    		var buttons = '[{"title":"OK","class":"blue","action":"$(\'#Fmachine_year\').focus();"}]';
			buttons = eval(buttons);
			_confirm("warning","Warning","กรุณาระบุปีที่ซื้อเครื่องจักร",buttons);
        }else if(FdamagedSt=="" || FdamagedSt==undefined){
    		var buttons = '[{"title":"OK","class":"blue","action":"$(\'#FdamagedSt\').focus();"}]';
			buttons = eval(buttons);
			_confirm("warning","Warning","กรุณาระบุลักษณะการชำรุด",buttons);
        } else{
			  var buttons = '[{"title":"OK","class":"blue","action":"saveData(1);"},{"title":"Cancel","class":"blue","action":""}]';
				buttons = eval(buttons);
				_confirm("warning","Warning","ยืนยันการส่งขออนุมัติ",buttons);
			
		}
	}
	
	
    function saveData(status_Sapp){
		if(status_Sapp==1){
		   $('#Fdoc_appSt').val('waiting');
			
		}
    	
				  $('#function').val('');	    
					var params = getRequestBody();
			//alert(params);
					$.ajax({
							type: "POST",
							url: ("../../../controllers/documents-app-controller.php"),
							data: "1&function=insert_data&"+params,
							dataType: 'json',
							success: function(data){
								console.log(data);
								//alert(data);
								/* if(data['Fdoc_appSt']=='waiting'){
									$('#save-btn').hide();
								} */
			$('#Fdoc_app_id').val(data['req_id']);
			$('#Fdoc_app_no').val(data['req_no']);
			$('.request_no').empty().html(data['req_no']);
		 	$('#print-btn').show(); 
			update_detail();
								if(status_Sapp==1){
									cancel();
								}else{
			var buttons = '[{"title":"OK","class":"blue","action":"$(\'.login-overlay\').hide();"}]';
				buttons = eval(buttons);
				_confirm("infor","Warning","ทำการบันทึกข้อมูลเรียบร้อยแล้ว",buttons);
									
								$('#show_attach-list_temp').hide();
							$('#attach-list_temp').empty();
							$('#attach-list').empty();
					  			  $('#attachment').show();
								  $.ajax({
										type: "POST",
										url: ("../../../controllers/documents-app-controller.php"),
										data: "1&function=list_attach&Fdoc_app_id="+$('#Fdoc_app_id').val(),
										dataType: 'json',
										success: function(json){
											for(var i=0;i<json.length;i++){
												var files = json[i];
												list_attach(files['FAttachID'],files['FAttachName'],files['FAttachLink']);
											}
								}
						  });	
									
								}

									
						}
					});
				
					
			
			
		
    }/*End of function checkData()*/
    
    if($('#Fdoc_app_id').val()!=""){
			var params = getRequestBody();
			$.ajax({
				type: "POST",
				url: ("../../../controllers/documents-app-controller.php"),
				data: "1&function=get&"+params,
				dataType: 'json',
				success: function(json){
					  assignFields(json);
					if(json['Fdoc_appSt']=='waiting' || json['Fdoc_appSt']=='finished'  || json['Fdoc_appSt']=='noapprove'  || json['Fdoc_appSt']=='cancel'){
						  $("#FownerID").prop('disabled', true);
						  $("#Fmanager_mtID").prop('disabled', true);
						  $("#FmanagerBP_GSID").prop('disabled', true);
						  $("#FSupervisorID").prop('disabled', true);
						 $('#save-btn').hide();
						if(json['Fdoc_appSt']!='waiting'){
							$('#SendMail-btn').hide();
						}
					 }
					
					
					//'waiting','finished','noapprove','cancel','returnedit','new'
					  $("#FownerID").trigger("liszt:updated");
					  $("#Fmanager_mtID").trigger("liszt:updated");
					  $("#FmanagerBP_GSID").trigger("liszt:updated");
					  $("#FSupervisorID").trigger("liszt:updated");
					
					
					  $("#Fcomp_id").trigger("liszt:updated");
					  updateBranch(json["FBranchID"]);	
					  $('#print-btn').show(); 
					if(json['FownerApp']!='' && json['FownerApp_date']!='' && json['FownerApp_date']!='0000-00-00'){
						if(json['FownerApp']=="Y"){
					   $("#ownerSignature").html('<img src="'+json['owner_signature']+'" height="50" width="150"><br>'+json['FownerApp_date']);
						}else{
						$("#ownerSignature").html('<img src="../../../../images/not-approved.jpg" height="50" width="150"><br>'+json['FownerApp_date']);
						}
					 }	
					$("#ownerPostName").html(json['owner_post_name']);
					$("#ownerName1").html('คุณ'+json['owner_first_name']+' '+json['owner_last_name']);
					$("#ownerName2").html('คุณ'+json['owner_first_name']+' '+json['owner_last_name']);
					
					$("#Fowner_comment").html(json['Fowner_comment']);
					
					if(json['Fmanager_mtApp']!='' && json['Fmanager_mtApp_date']!='' && json['Fmanager_mtApp_date']!='0000-00-00'){
						if(json['Fmanager_mtApp']=="Y"){
					   $("#manager_mtSignature").html('<img src="'+json['manager_mt_signature']+'" height="50" width="150"><br>'+json['Fmanager_mtApp_date']);
						}else{
						$("#manager_mtSignature").html('<img src="../../../../images/not-approved.jpg" height="50" width="150"><br>'+json['Fmanager_mtApp_date']);
						}
						
					 }	
					
					$("#Fmanager_mt_comment").html(json['Fmanager_mt_comment']);
					$("#manager_mtPostName").html(json['manager_mt_post_name']);
					
					if(json['FmanagerBP_GSApp']!='' && json['FmanagerBP_GSApp_date']!='' && json['FmanagerBP_GSApp_date']!='0000-00-00'){
					 if(json['FmanagerBP_GSApp']=="Y"){
					   $("#managerBP_GSSignature").html('<img src="'+json['manager_bpgs_signature']+'" height="50" width="150"><br>'+json['FmanagerBP_GSApp_date']);
						}else{
						$("#managerBP_GSSignature").html('<img src="../../../../images/not-approved.jpg" height="50" width="150"><br>'+json['FmanagerBP_GSApp_date']);
						}
					}	
					$("#managerBP_GSPostName").html(json['manager_bpgs_post_name']);
					
					if(json['FSupervisorApp']!='' && json['FSupervisorApp_date']!='' && json['FSupervisorApp_date']!='0000-00-00'){
						if(json['FSupervisorApp']=="N"){
						$("#SupervisorSignature").html('<img src="../../../../images/not-approved.jpg" height="50" width="150"><br>'+json['FSupervisorApp_date']);
						}else{ 
							$("#SupervisorSignature").html('<img src="'+json['manager_sup_signature']+'" height="50" width="150"><br>'+json['FSupervisorApp_date']);
						}
					 }	
					$("#SupervisorPostName").html(json['sup_post_name']);
					$("#SupervisorName1").html(json['sup_fname']);
					$("#SupervisorName2").html(json['sup_fname']);
					
					  $('.request_no').empty().html(json['Fdoc_app_no']);
						if(json['Fmaterial_constructionSt']==1){
						  $('#Fmaterial_constructionSt1').attr("checked",true) ;
						}else if(json['Fmaterial_constructionSt']==2){
						  $('#Fmaterial_constructionSt2').attr("checked",true) ;
						}
						if(json['FdamagedSt']==1){
						  $('#FdamagedSt1').attr("checked",true) ;
						}else if(json['FdamagedSt']==2){
						  $('#FdamagedSt2').attr("checked",true) ;
						}
						if(json['FSupervisorApp']=="Y"){
						  $('#FSupervisorAppY').attr("checked",true) ;
						}else if(json['FSupervisorApp']=="N"){
						  $('#FSupervisorAppN').attr("checked",true) ;
						}else if(json['FSupervisorApp']=="other"){
						  $('#FSupervisorAppother').attr("checked",true) ;
						}else if(json['FSupervisorApp']=="Ynote"){
						  $('#FSupervisorAppYnote').attr("checked",true) ;
						}
					
					
			if(json['return_edit_empid']!='' && json['return_edit_empid']!=null && json['return_edit_empid']!=NaN){
			   $("#show_return_edit").show();
					$("#return_edit_emp_name").html('<font color="#F8070B">('+json['return_edit_date']+')คุณ'+json['return_edit_emp_name']+'ตีกลับแผนกซ่อมบำรุงด้วยเหตุผล <b>'+json['return_edit_comment']+'</b></font>');
			   
			   
		   }
					
					 $('#attachment').show();
					  $.ajax({
							type: "POST",
							url: ("../../../controllers/documents-app-controller.php"),
							data: "1&function=list_attach&Fdoc_app_id="+$('#Fdoc_app_id').val(),
							dataType: 'json',
							success: function(json){
								for(var i=0;i<json.length;i++){
									var files = json[i];
									list_attach(files['FAttachID'],files['FAttachName'],files['FAttachLink']);
								}
							}
					  });
				}	
			});
			 			
	}else{
	var default_table='<p>รายละเอียดเนื้อหา1</p>';		
		default_table+='<p>รายละเอียดเนื้อหา2</p>';	
		default_table+='<p>รายละเอียดเนื้อหา3</p>';	
		default_table+='<p>รายละเอียดเนื้อหา4</p>';	
		default_table+='<p>รายละเอียดเนื้อหา5</p>';
		default_table+='<p style="margin-left: 50px;margin-bottom: -1px;">บริษัท......</p><center><table width="90%" border="1" cellpadding="0" cellspacing="0"><tbody >';
		default_table+='<tr>';
		default_table+='<td width="5%"  align="center"><b>ลำดับ</b></td>';
	    default_table+='<td width="55%" align="center"><b>รายการ</b></td>';
	    default_table+='<td width="10%" align="center"><b>จำนวน</b></td>';
	    default_table+='<td width="10%" align="center"><b>หน่วย</b></td>';
	    default_table+='<td width="10%" align="center"><b>ราคา/หน่วย</b></td>';
	    default_table+='<td width="10%" align="center"><b>ราคารวม</b></td>';
	    default_table+='</tr>';
		for(var y=1; y<=10;y++){
		default_table+='<tr>';
	    default_table+='<td  align="center">&nbsp;</td>';
	    default_table+='<td>&nbsp;</td>';
	    default_table+='<td align="center">&nbsp;</td>';
	    default_table+='<td align="center">&nbsp;</td>';
	    default_table+='<td align="right">&nbsp;</td>';
	    default_table+='<td align="right">&nbsp;</td>';
	    default_table+='</tr>';
		}
		default_table+='<tr>';
	    default_table+='<td colspan="5"  align="center"><b>รวมทั้งสิ้น</b></td>';
	    default_table+='<td  align="right"></td>';
	    default_table+='</tr>';	 
        default_table+='</tbody>';
				
		$("#Fdoc_app_detail").html(default_table);
	}
	function updateBranch(selected){
		//alert(selected);
			$.ajax({
					type: "POST",
					url: ("../../../../main/controllers/utilities_controller.php"),
					data: "1&function=get_BranchJson&comp_id="+$('#Fcomp_id').val(),
					dataType: 'json',
					success: function(data){
							appendOption(document.getElementById('FBranchID'),data);
							if(selected!=''){
									$("#FBranchID option[value=" +selected+"]").attr("selected","selected") ;
							}
							
							setSelectValue('FBranchID');
					}
				});
	}

	function cancel(){
		window.location.href = '../mt-doc-for-approval.tpl.php?u_id=<?php print($userId);?>&e_id=<?php print($empId);?>&compId=<?php print($compId);?>'
    }
	function printData(){
		var id=$('#Fdoc_app_id').val();
    		var width = screen.width-10;
			var height = screen.height-60;
	    	newwindow=window.open('../../informations/print-doc-for-approval.php?id='+id,
					  'requestInformationWindow-'+id,'width='+width+',height='+height+',left=0,top=0,screenX=0,screenY=0,status=no,menubar=yes,scrollbars=yes,copyhistory=yes, resizable=yes,fullscreen=no');
    }
	
</script>
</html>