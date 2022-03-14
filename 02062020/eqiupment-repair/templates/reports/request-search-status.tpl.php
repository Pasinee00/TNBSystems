<!DOCTYPE html>
<?php 
include '../../../lib/db_config.php';
include '../../../main/modules/Model_Utilities.php';
include '../../../pis_sys/models/user_model.php';

$utilMD = new Model_Utilities();
$userMD = new Model_User();
$comList = $utilMD->get_CompList();
$brnList = $utilMD->get_BranchList($comList);
$sectLst = $utilMD->get_SectList($comList, '');

$empId = $_REQUEST['e_id'];
$userId = $_REQUEST['u_id'];
$userData = $userMD->get_data($userId);

$fieldStatus = "t1.FStatus";
if($userData['user_type']=="M") $fieldStatus = "t4.FStatus";

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-874">
<title>Request</title>
<link href="../../../css/sys_controll.css" rel="stylesheet" type="text/css">
<link href="../../../css/display.css" rel="stylesheet" type="text/css">
<link href="../../../jsLib/jquery-confirm/jquery.confirm.css" rel="stylesheet" type="text/css">
<link href="../../../jsLib/datepicker/css/default.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="../../../jsLib/uniform/css/uniform.default.css" type="text/css" media="screen">
<link href="../../../css/input.css" rel="stylesheet" type="text/css">
<link href="../../../css/dialog-box.css" rel="stylesheet" type="text/css">

<script  type="text/javascript" src="../../../jsLib/jquery-1.8.0.min.js"></script>
<script  type="text/javascript" src="../../../jsLib/uniform/jquery.uniform.js"  charset="utf-8"></script>
<script  type="text/javascript" src="../../../jsLib/js_scripts/js_function.js"></script>
<script  type="text/javascript" src="../../../jsLib/jquery-confirm/jquery.confirm.js"></script>
<script  type="text/javascript" src="../../../jsLib/jquery-confirm/js/script.js?version=1.0.0"></script>
<script type="text/javascript" src="../../../jsLib/datepicker/zebra_datepicker.js"></script>


<script>
$(function(){
    $("input, textarea").uniform();
    $(".uniform-select").uniform();
  });
</script>
<style type="text/css">
	.list-body{
		padding:10px;
	}
	.search-list{
		float:left;
		width:25%;
	}
	.search-detail{
		float:right;
		width:74%;
		padding-top:6px;
	}

</style>
</head>

<body scrolling="no">
  <div class="content-top">
  	<div class="_content-title">สอบถามสถานะการแจ้งซ่อม  </div>
  	<div class="search-action">
  	    <span class="new-status" style="padding-left:30px;padding-right:10px;">รอการ Assign วันเปิดงาน</span>
  		<span class="process-status" style="padding-left:30px;padding-right:10px;">กำลังดำเนินการ</span>
  		<span class="wait-approval-status" style="padding-left:30px;padding-right:10px;">รอการอนุมัติ</span>
  		<span class="lock_disabled-status" style="padding-left:30px;padding-right:10px;">ยกเลิก</span>
  		<span class="lock-status" style="padding-left:30px;padding-right:10px;">Completed</span>
        <span class="noapprov-status" style="padding-left:30px;padding-right:10px;">ไม่อนุมัติ</span>
        <span class="returnedit-status" style="padding-left:30px;padding-right:10px;">ตีกลับแก้ไข</span>
  	</div>
  </div>
    <table width="98%" align="center">
		
    <tr>
  		<td width="8%">บริษัท :</td>
  		<td width="19%"><select name="fields[FRepair_comp_id]" id="FRepair_comp_id" class="uniform-select" onChange="javascript:updateBranch('');javascript:updateSection('');">
  		  <option value="">-------บริษัท-------</option>
  		  <?php if(!empty($comList)){ foreach ($comList as $key=>$val){?>
  		  <option value="<?php echo $val['comp_id'];?>" ><?php echo $val['comp_code']." - ".$val['comp_name'];?></option>
  		  <?php }}?>
	    </select></td>
  		<td width="8%">สาขา :</td>
  		<td width="28%">
  		     <?php if($userData['user_type']=="E"){
  				print($utilMD->convert2Thai($userData['brn_name']));
  			?>
  				<input type="hidden" name="FBranchID" id="FBranchID" value="<?php print($userData['brn_id']);?>">
  			<?php }else{?>
  		       <select name="FBranchID" id="FBranchID" class="uniform-select" >
	            	<option value="">---กรุณาเลือกสาขา</option>
	            	<?php if(!empty($brnList)){
	            			foreach($brnList as $key=>$val){
	            	?>
	            				<option value="<?php print($val['brn_id']);?>" <?php if($val['brn_id']== $userInfo['brn_id']){?>selected<?php }?>><?php print($val['brn_name']);?></option>
	            	<?php }}?>
              </select>
              <?php }?>
  		</td>
  		<td width="7%">แผนก :</td>
  		<td width="30%"><?php if($userData['user_type']=="E"){
  				print($utilMD->convert2Thai($userData['sec_nameThai']));
  			?>
          <input type="hidden" name="sec_id" id="sec_id" value="<?php print($userData['sec_id']);?>">
          <?php }else{?>
          <select name="sec_id" id="sec_id" class="uniform-select">
            <option value="">---กรุณาเลือกแผนก---</option>
            <?php foreach($sectLst as $key=>$val){?>
            <option value="<?php print($val['sec_id']);?>">[<?php print($val['sec_code']);?>] <?php print($val['sec_nameThai']);?></option>
            <?php }?>
          </select>
        <?php }?></td>
	  </tr>
    <tr>
      <td>สถานะ :</td>
      <td><select name="status" id="status" class="uniform-select">
        <option value="">---ทั้งหมด---</option>
        <option value="new">---รอการ Assign---</option>
        <option value="inprogress" <?php if($status=="inprogress"){?> selected <?php }?>>---กำลังดำเนินการ---</option>
        <option value="waiting" <?php if($status=="waiting"){?> selected <?php }?>>---รอการอนุมัติ---</option>
        <option value="finished" <?php if($status=="finished"){?> selected <?php }?>>---Completed---</option>
        <option value="cancel" <?php if($status=="cancel"){?> selected <?php }?>>---ยกเลิก---</option>
        <option value="noapprove" <?php if($status=="noapprove"){?> selected <?php }?>>---ไม่อนุมัติ---</option>
        <option value="returnedit" <?php if($status=="returnedit"){?> selected <?php }?>>---ตีกลับแก้ไข---</option>
        <option value="W_Approve" <?php if($status=="W_Approve"){?> selected <?php }?>>---งานอนุมัติ W---</option>
      </select></td>
      <td>คำค้น : </td>
      <td><input type="text" name="key_search" id="key-search" style="width:180px;" value="">
      <input type="hidden" name="FSupportID" id="FSupportID" value="<?php print($userId);?>"></td>
      <td align="left">&nbsp;</td>
      <td align="left"><a class="button-bule" href="javascript:void(0);" onclick="javascript:changePage();"> Search </a></td>
      </tr>
  </table>
  <div class="list-body">
  	<div class="search-list">
  		<div class="list-header">
		  	<ul>
		  		<li></li>
		  		<li style="width:24%;">Req No</li>
		  		<li style="width:56%;text-align:left">detail</li>
		  		<li style="width:8%;">สถานะ</li>
		  		<li></li>
		  	</ul>
		</div>
		<div class="list-items" id="request-items">
		
		</div>
		
		<div class="list-paging">
	  		<div class="paging-infor" style="display:none"><span id="begin-item">1</span>-<span id="end-item">20</span> จากทั้งหมด <span id="total-item">45</span> รายการ</div>
	  		<div class="paging-action">
	  			<ul class="nav-page">
	  				<li><a href="javascript:void(0);" onclick="javascript:go2First();">&laquo;</a></li>
	  				<li><a href="javascript:void(0);" onclick="javascript:go2Pre();">&lsaquo;</a></li>
	  				<li class="paging-select" style="width:137px;">
	  					<select id="select-page" style="width:137px;" onchange="javascript:changePage();">
	  					</select>
	  				</li>
	  				<li><a href="javascript:void(0);" onclick="javascript:go2Next();">&rsaquo;</a></li>
	  				<li><a href="javascript:void(0);" onclick="javascript:go2Last();">&raquo;</a></li>
	  			</ul>
	  			<input type="hidden" name="rg_id" id="rg_id" value="<?php print($repairgroup_id);?>">
	  		</div>
	   </div>
  	</div>
  	<div class="search-detail">
		<div class="dialog-panel" style="height:100%;">
	   		<div class="top-row">
	   			<div class="left"></div>
	   			<div class="center">
	   				<span class="dialog-title">Request Detail ของใบแจ้งซ่อมเลขที่ : <span class="request_no" style="font-weight:bold"></span></span>
	   			</div>
	   			<div class="right"></div>
	   		</div> 
	   		<div class="middle-row" style="height:100%;">
	   			<div class="left"></div>
	   			<div id="dialog-body" class="center" style="height:100px;">
	   				<div style="width:100%;height:100%;overflow:auto;">
	   					<form name="form1" method="post" enctype="multipart/form-data" action="../../../controllers/request_controller.php?function=upload" target="upload_target">
					      <table width="100%" align="center">
                           <tr id="show_cancel" style="display:none;">
                             <td style="color: #F00">เหตุผลการยกเลิก</td>
                             <td colspan="5" style="color: #F00"><input type="text" name="fields[FCancelRemark]" id="FCancelRemark" readonly style="width:95%;" value=""></td>
                           </tr>
                           <tr  id="show_noapprove" style="display:none;">
					           <td width="16%" style="color: #F00">***เหตุผลการไม่อนุมัติ/ตีกลับแก้ไข***</td>
					           <td width="21%"><input type="text" name="fields[detail_noapprove]" id="detail_noapprove" readonly style="width:100%;" value=""></td>
					           <td width="2%">&nbsp;</td>
					           <td width="15%" style="color: #F00">***วันที่***</td>
					           <td width="22%"><input type="text" name="fields[approve_date]" id="approve_date" readonly style="width:100%;" value=""></td>
					           <td width="24%">&nbsp;</td>
					         </tr>
					        <?php if($userInfo['user_type']=="AM" && empty($FRequestID)){?>
                             <tr>
					           <td width="16%">รหัสพนักงาน <font color="#FF0000">*</font> :</td>
					           <td width="21%">
					             <input type="text" name="emp_code" id="emp_code" onkeyup="javascript:if(event.keyCode=='13')getEmpInfo();">
					             <input type="hidden" name="fields[FReqID]" id="FReqID" value="">
					           	 <input type="hidden" name="FRequestID" id="FRequestID" value="">
					           	 <input type="hidden" name="fields[FReqNo]" id="FReqNo">
					           	 <input type="hidden" name="user_type" id="user_type" value="<?php print($userInfo['user_type'])?>">
					           	 <input type="hidden" name="fields[FStatus]" id="FStatus" value="S">
					           </td>
					           <td width="2%">&nbsp;</td>
					           <td width="15%">ชื่อ - สกุล :</td>
					           <td width="22%">
					           	  <input type="text" name="emp_name" id="emp_name" style="width:80%;" disabled value="">
					           </td>
					           <td width="24%"></td>
					         </tr>
					         <tr>
					           <td>ตำแหน่ง :</td>
					           <td>
					           	  <input type="text" name="fields[FPosition]" id="FPosition" style="width:80%" disabled value="">
					           </td>
					           <td>&nbsp;</td>
					           <td>แผนก :</td>
					           <td>
					           	   <input type="text" name="sec_nameThai" id="sec_nameThai" style="width:80%" disabled value="">
					           	   <input type="hidden" name="fields[FSectionID]" id="FSectionID" value="">
					           </td>
					           <td></td>
					         </tr>
					         <tr>
					           <td>สาขา :</td>
					           <td>
					           	   <input type="text" name="brn_name" id="brn_name" style="width:80%" disabled value="">
					           </td>
					           <td>&nbsp;</td>
					           <td>ผู้จัดการ /หัวหน้างาน <font color="#FF0000">*</font> :</td>
					           <td>
					           	  <select name="fields[FManagerID]" id="FManagerID" style="width:350px;" class="chzn-select">
					   					<option value="">---หัวหน้างาน / ผู้จัดการ---</option>
					   					<?php if(!empty($managerOption)){ foreach ($managerOption as $key=>$val){?>
					   						<option value="<?php echo $val['FManagerID'];?>"><?php echo $val['FName'];?></option>
					   					<?php }}?>
							     </select>
					           </td>
					           <td></td>
					         </tr>
					          <tr>
					           <td></td>
					           <td></td>
					           <td>&nbsp;</td>
					           <td>ผู้อำนวยการ <font color="#FF0000">*</font> :</td>
					           <td>
					           	  <select name="fields[FSupervisorID]" id="FSupervisorID" style="width:350px;" class="chzn-select">
					   					<option value="">---ผู้อำนวยการ---</option>
					   					<?php if(!empty($managerOption)){ foreach ($managerOption as $key=>$val){?>
					   						<option value="<?php echo $val['FManagerID'];?>"><?php echo $val['FName'];?></option>
					   					<?php }}?>
							    </select>	
					           </td>
					           <td></td>
					         </tr>
					        <?php }else{?>
					         <tr>
					           <td width="12%">รหัสพนักงาน :</td>
					           <td width="22%">
					             <input type="text" name="emp_code" id="emp_code" disabled value="<?php print(iconv("UTF-8", "TIS-620", $empInfo->emp_code));?>">
					             <input type="hidden" name="fields[FReqID]" id="FReqID" value="<?php print(iconv("UTF-8", "TIS-620", $empInfo->emp_id));?>">
					           	 <input type="hidden" name="FRequestID" id="FRequestID" value="<?php print($FRequestID);?>">
					           	 <input type="hidden" name="fields[FReqNo]" id="FReqNo">
					           	 <input type="hidden" name="user_type" id="user_type" value="<?php print($userInfo['user_type'])?>">
					           	 <input type="hidden" name="user_id" id="user_id" value="<?php print($userInfo['user_id']);?>">
					           	 <input type="hidden" name="fields[FStatus]" id="FStatus" value="N">
					           </td>
					           <td width="4%">&nbsp;</td>
					           <td width="15%">ชื่อ - สกุล :</td>
					           <td width="23%">
					           	  <input type="text" name="emp_name" id="emp_name" style="width:80%;" disabled value="<?php print(iconv("UTF-8", "TIS-620", $empInfo->emp_name));?>">
					           </td>
					           <td width="24%"></td>
					         </tr>
					         <tr>
					           <td>ตำแหน่ง :</td>
					           <td>
					           	  <input type="text" name="fields[FPosition]" id="FPosition" style="width:80%" disabled value="<?php print(iconv("UTF-8", "TIS-620", $empInfo->post_name));?>">
					           </td>
					           <td>&nbsp;</td>
					           <td>แผนก :</td>
					           <td>
					           	   <input type="text" name="sec_nameThai" id="sec_nameThai" style="width:80%" disabled value="<?php print(iconv("UTF-8", "TIS-620", $empInfo->sec_nameThai));?>">
					           	   <input type="hidden" name="fields[FSectionID]" id="FSectionID" value="<?php print(iconv("UTF-8", "TIS-620", $empInfo->sec_id));?>">
					           </td>
					           <td></td>
					         </tr>
					         <tr>
					           <td>สาขา :</td>
					           <td>
					           	   <input type="text" name="brn_name" id="brn_name" style="width:80%" disabled value="<?php print(iconv("UTF-8", "TIS-620", $empInfo->brn_name));?>">
					           </td>
					           <td>&nbsp;</td>
					           <td>ผู้จัดการ /หัวหน้างาน :</td>
					           <td>
					           	  <input type="text" name="manager_name" id="FManagerName" style="width:80%" disabled value="<?php print(iconv("UTF-8", "TIS-620", $userInfo['manager_name']));?>">
					           	  <input type="hidden" name="fields[FManagerID]" id="FManagerID" value="<?php print(iconv("UTF-8", "TIS-620", $userInfo['manager_id']));?>">
					           </td>
					           <td></td>
					         </tr>
					          <tr>
					           <td></td>
					           <td></td>
					           <td>&nbsp;</td>
					           <td>ผู้อำนวยการ :</td>
					           <td>
					           	  <input type="text" name="supervisor_name" id="FSupervisorName" style="width:80%" disabled value="<?php print(iconv("UTF-8", "TIS-620", $userInfo['supervisor_name']));?>">
					           	  <input type="hidden" name="fields[FSupervisorID]" id="FSupervisorID" value="<?php print(iconv("UTF-8", "TIS-620", $userInfo['supervisor_id']));?>">
					           </td>
					           <td></td>
					         </tr>
					         <?php }?>
					         <tr>
					           <td>เลขที่อ้างอิง   :</td>
					           <td>
					             <input type="text" name="fields[FInf_no]" id="FInf_no">
					           </td>
					           <td>&nbsp;</td>
					           <td>เลขที่ทรัพย์สิน  :</td>
					           <td colspan="2">
					           	<input type="text" name="fields[FAsset_no]" id="FAsset_no">&nbsp;&nbsp;
					           	<input type="checkbox" name="no_asset" id="no_asset"> ไม่พบรหัส
					           </td>
					         </tr>
					         <tr>
					           <td>หน่วยงาน  :</td>
					           <td><input type="text" name="fields[FFnc]" id="FFnc" style="width:98%;"></td>
					           <td>&nbsp;</td>
					           <td>เบอร์โทร. <font color="#FF0000">*</font> :</td>
					           <td><input type="text" name="fields[FTel]" id="FTel" onkeyup="javascript:changNumeric(this);"></td>
					           <td></td>
					         </tr>
					         <tr>
					           <td>สาขาที่ติดตั้ง   :</td>
					           <td>
					              <input type="text" name="brn_name" id="brn_name" style="width:98%;">
					           </td>
					           <td>&nbsp;</td>
					           <td>อาคาร / สถานที่   :</td>
					           <td><input type="text" name="fields[FLocation]" id="FLocation" style="width:98%;"></td>
					           <td></td>
					         </tr>
					         <tr>
					           <td>ชั้น  :</td>
					           <td><input type="text" name="fields[FFloor]" id="FFloor"></td>
					           <td>&nbsp;</td>
					           <td>ห้อง <font color="#FF0000">*</font> :</td>
					           <td><input type="text" name="fields[FRoom]" id="FRoom"></td>
					           <td></td>
					         </tr>
					         <tr>
					           <td>วันที่ / เวลา :</td>
					           <td>
					           <input type="text" name="fields[FReqDate]" id="FReqDate" readonly value="<?php print($date);?>">
					           - 
					           <input type="text" name="fields[FReqTime]" id="FReqTime" readonly style="width:50px;" value="<?php print($time);?>"></td>
					           <td>&nbsp;</td>
					           <td>ระดับความสำคัญ  :</td>
					           <td>
					           	  <input type="text" name="FLevelName" id="FLevelName" style="width:98%;">
					           </td>
					           <td></td>
					         </tr>
					         <tr>
					           <td>ขอแจ้งซ่อม   :</td>
					           <td>
					              <input type="text" name="FRepairGroupName" id="FRepairGroupName" style="width:98%;">
					           </td>
					           <td>&nbsp;</td>
					           <td>รายการซ่อม   :</td>
					           <td>
					            <input type="text" name="FRepairGroupItemName" id="FRepairItemName" style="width:98%;">
					           </td>
					           <td></td>
					         </tr>
					         <tr>
					           <td valign="top">รายละเอียด / ปัญหา  :</td>
					           <td colspan="5"><textarea name="fields[FDetail]" rows="10" style="width: 728px;" id="FDetail"></textarea></td>
					         </tr>
							  
			<tr>
         	  <td>&nbsp;</td>
         	  <td colspan="5">&nbsp; 
				<table width="100%" border="0" cellspacing="1" cellpadding="1">
        <tr>
              <td colspan="5"><strong><u>รูปภาพประกอบการแจ้งซ่อม</u></strong></td>
            </tr>
        <tr>
              <td width="18%" height="25">1.บริเวณที่ต้องการแจ้งซ่อม</td>
              <td width="32%"></td>
              <td>&nbsp;</td>
              <td colspan="2">2.อุปกรณ์ที่ต้องการแจ้งซ่อม</td>
            </tr>
        <tr>
              <td height="300" colspan="2" align="center"><img id="photo_1" src="../../../images/cm/default_photo2.jpg" width="380" height="230"></td>
              <td width="2%">&nbsp;</td>
              <td colspan="2" align="center"><img id="photo_2" src="../../../images/cm/default_photo1.jpg" width="380" height="230"></td>
            </tr>
        <tr>
              <td height="25">3.เพิ่มเติม</td>
              <td height="25"></td>
              <td>&nbsp;</td>
              <td>4.เพิ่มเติม</td>
              <td></td>
            </tr>
        <tr>
              <td height="300" colspan="2" align="center"><img id="photo_3" src="../../../images/cm/default_photo3.jpg" width="380" height="230"></td>
              <td>&nbsp;</td>
              <td colspan="2" align="center"><img id="photo_4" src="../../../images/cm/default_photo3.jpg" width="380" height="230"></td>
            </tr>
      </table>		  
			 </td>
       	  </tr>
					         <tbody>
					         	<tr>
						           <td colspan="6" align="center" style="background-color:#999999;"><b>ผลการดำเนินการของผู้รับผิดชอบ</b></td>
						        </tr>
						        <tr>
						           <td>ผู้รับเรื่อง  :</td>
						           <td><input type="text" name="recive_name" id="recive_name" style="width:80%" disabled value="<?php print($utilMD->convert2Thai($userInfo['first_name']))?>  <?php print($utilMD->convert2Thai($userInfo['last_name']))?>"></td>
						           <td>&nbsp;<input type="hidden" name="fields[FReciverID]" id="FReciverID" value="<?php print($userInfo['user_id'])?>"></td>
						           <td>วันที่รับเรื่อง :&nbsp;</td>
						           <td>
						           	   <input type="text" name="fields[FReciveDate]" id="FReciveDate" readonly value="<?php print($date);?>">
							           - 
							           <input type="text" name="fields[FReciveTime]" id="FReciveTime" readonly style="width:50px;" value="<?php print($time);?>">
						           </td>
						           <td></td>
						         </tr>
						         <tr>
						           <td colspan="6">
						           		<table width="100%" border="0" cellspacing="0" cellpadding="0">
					                       <tr>
					                          <td width="50%" valign="top">
					                             <table width="100%" border="0" cellspacing="1" cellpadding="1">
					                                <tr>
					                                   <td width="8%" height="20" align="center">&nbsp;</td>
					                                   <td height="20" colspan="5">
					                                   	  <table width="100%" border="0" cellspacing="0" cellpadding="0">
					                                        <tr>
					                                           <td width="7%" align="center">
<input type="radio" name="fields[FJobresult]" id="FJobresult" class="FJobresult_1 Noboder" <? if($FJobresult==1){?> checked <? }?> value="1" >
					                                           </td>
					                                           <td width="19%">ซ่อมเอง </td>
					                                           <td width="10%" align="center">
<input type="radio" name="fields[FJobresult]" id="FJobresult" class="FJobresult_2 Noboder" <? if($FJobresult==2){?> checked <? }?> value="2">
					                                           	</td>
					                                           <td width="64%">ให้ผู้รับเหมาดำเนินการ</td>
					                                        </tr>
					                                       </table>
					                                   </td>
					                                 </tr>
					                                <tbody id="cost-list-l">
					                                
					                                </tbody>
					                                  <tr>
					                                    <td height="20">&nbsp;</td>
					                                    <td height="20" colspan="2"><b>ค่าแรง</b></td>
					                                    <td height="20" align="center">ราคา</td>
					                                    <td height="20" align="right" class="underlinedott"><input name="fields[FLapAmt]" type="text" id="FLapAmt"  style="width:80px;text-align:right;"></td>
					                                    <td height="20" align="center">บาท</td>
					                                 </tr>
					                                
					                                 <tr>
					                                    <td height="20">&nbsp;</td>
					                                    <td height="20" colspan="2"><b>ค่าอะไหล่</b></td>
					                                    <td height="20" align="center">ราคา(รวม)</td>
					                                    <td height="20" align="right" class="underlinedott"><input name="fields[FPartAmt]" type="text" id="FPartAmt" style="width:80px;text-align:right;"></td>
					                                    <td height="20" align="center">บาท</td>
					                                 </tr>
					                                <tbody id="cost-list-p">
					                                
					                                </tbody>
					                                <tr>
					                                   <td height="20" align="center">
					                                   		<input type="checkbox" name="fields[FReam]" id="FReam" <? if($FReam=="Y"){?> checked <? }?> value="Y" class="FReam">
					                                   	</td>
					                                   <td height="20">&nbsp;</td>
					                                   <td height="20" colspan="4">เบิกเงินสดเพื่อจัดซื้อวัสดุมาดำเนินการซ่อมแซ่ม</td>
					                                </tr>
                                                    <tr>
					                                   <td height="20" align="center">&nbsp;</td>
					                                   <td height="20">&nbsp;</td>
					                                   <td height="20" colspan="4">
                                                       <select name="fields[FCharge_contractor]" id="FCharge_contractor" class="uniform-select">
                                                        <option value="">---กรุณาเลือก</option>
                                                        <option value="1">บจก.เค.แอล.ออโต้เพนท์</option>
                                                        <option value="2">บจก.เอส.เค.ออโต้เพนท์</option>
                                                        <option value="3">บจก.พี.บี.ออโต้เพนท์</option>
                                                        <option value="4">บจก.สิริโรจน์ บอดี้เพนท์</option>
                                                        <option value="5">บจก.สบาย คาร์วอร์ช</option>
                                                        <option value="6">บจก.อาร์ แอนด์ ดี 999</option>
                                                     </select></td>
					                                </tr>
					                             </table>
					                           </td>
					                           <td valign="top">
					                             <table width="100%" border="0" cellspacing="1" cellpadding="1">
					                                <tr>
					                                  <td width="9%" height="20" align="center">
					                                  	<input type="radio" name="fields[FCondition]" id="FCondition" value="1" class="FCondition_1">
					                                  </td>
					                                  <td height="20" colspan="4">จากการเข้าร่วมตรวจสอบเห็นควรดำเนินการตามเสนอ</td>
					                                </tr>
					                                <tr>
					                                  <td height="20" align="center">
					                                  	<input type="radio" name="fields[FCondition]" id="FCondition" <? if($FCondition=="2"){?> checked <? }?> value="2" class="FCondition_2">
					                                  </td>
					                                  <td height="20" colspan="4">อื่น ๆ</td>
					                                </tr>
					                              </table>
					                            </td>
					                         </tr>
					                     </table>
						           </td>
						         </tr>
						         <tr>
						           <td colspan="4" valign="top">
						           		<table width="100%" border="0" cellspacing="0" cellpadding="0">
						           			<tr>
						           			   <td width="16%">กำหนดเสร็จ  :</td>
									           <td width="21%"><input type="text" name="fields[FEstimate]" id="FEstimate" style="width:80px;text-align:center;" onkeyup="javascript:changNumeric(this);" value=""></td>
									           <td width="2%"></td>
									           <td width="17%">วันที่เปิด Job <?php if($userInfo['user_type']=="M"){?><font color="#FF0000">*</font><?php }?> :</td>
									           <td width="22%"><input type="text" name="fields[FEditDate]" id="FEditDate" style="width:80%;" value=""></td>
						           			</tr>
						           			<tr>
									           <td>วันที่กำหนดเสร็จ  :</td>
									           <td><input type="text" name="fields[FDueDate]" id="FDueDate" readonly style="width:80%;" value=""></td>
									           <td></td>
									           <td>วันที่ปิิดงาน <?php if($close =="y"){?><font color="#FF0000">*</font><?php }?>  :</td>
									           <td><input type="text" name="fields[FFinishDate]" id="FFinishDate" style="width:80%;" value=""></td>
									         </tr>
						           			<tr>
									           <td>ผู้ตรวจรับงาน  :</td>
									           <td><input type="text" name="fields[FAuditorName]" id="FAuditorName" style="width:80%;" value=""></td>
									           <td></td>
									           <td>วันที่ตรวจรับงาน  :</td>
									           <td><input type="text" name="fields[FAuditDate]" id="FAuditDate" style="width:80%;" value=""></td>
									         </tr>
									          <tr>
									           <td></td>
									           <td>
									              <?php if($userInfo['user_type']=="AM"){?>
									           		<input type="checkbox" name="fields[FApprove]" id="FApprove" value="Y" class="FApprove"> รอการเซ็นต์อนุมัติ  
									           	  <?php }else{?>
									           	  	<input type="checkbox" name="FApprove" id="FApprove" disabled value="Y" class="FApprove"> รอการเซ็นต์อนุมัติ 
									           	  <?php }?>
									           	  <input type="hidden" name="is_sendTo" id="is_sendTo" value="">
									           </td>
									           <td></td>
									           <td></td>
									           <td></td>
									         </tr>
						           		</table>
						           </td>
						           <td colspan="3" rowspan="5" valign="top" align="center">
			                      		<table width="100%" border="0" cellspacing="0" cellpadding="0" >
			                      		   <tr>
			                      		   			<td class="line-l-dash line-t-dash line-r-dash" style="background-color:#F4F4F4;" colspan="5" align="center"><b>ผู้แก้ไข</b></td>
			                      		   </tr>
		                      				<tr>
		                      						<td class="line-l-dash line-t-dash line-b-dash" width="" align="left">&nbsp;<b>ช่างผู้แก้ไข</b></td>
		                      						<td class="line-l-dash line-t-dash line-b-dash" width="18%" align="center"><b>วันที่เปิดงาน</b></td>
		                      						<td class="line-l-dash line-t-dash line-b-dash" width="18%" align="center"><b>วันที่ปิดงาน</b></td>
		                      						<td class="line-l-dash line-t-dash line-r-dash line-b-dash" width="18%" align="center"><b>สถานะ</b></td>
		                      				</tr>
		                      				<tbody id="support-list">
			                    
			                   				</tbody>
		                      		</table>
						           </td>
						         </tr>
					         </tbody>
					         <tr>
					         		<td colspan="6">
					         				<table width="100%">
				         						 <tr>
				                                  	<td height="20" width="12%"  align="left" valign="top"><b>สรุปงาน :</b></td>
				                                  	<td height="20" ><textarea name="fields[FOth_detail]" rows="10" style="width: 90%;" id="FOth_detail"></textarea></td>
		                               			 </tr>  
					         				</table>
					         		</td>
					         </tr>
					         <tr>
					         	<td colspan="6">
					         		<table width="100%">
					         			<tr>
					         				<td width="25%"></td>
					         				<td width="37%">
					         					<div class="search-action">
											  	    <span class="package-status" style="padding-left:23px;padding-right:5px;">รอส่ง claim/ซ่อม</span>
											  		<span class="package-accept-status" style="padding-left:23px;padding-right:5px;">ส่ง claim/ซ่อมแล้ว</span>
											  		<span class="package-download-status" style="padding-left:23px;padding-right:5px;">รับของแล้ว</span>
											  	</div>
					         				</td>
					         				<td width="38%">
					         					<div class="search-action">
											  	    <span class="euro-sign-status" style="padding-left:23px;padding-right:5px;">รอการสั่งซื้อ</span>
											  		<span class="pound-sign-status" style="padding-left:23px;padding-right:5px;">ทำการสั่งซื้อแล้ว</span>
											  		<span class="dollar-sign-status" style="padding-left:23px;padding-right:5px;">ได้รับของแล้ว</span>
											  	</div>
					         				</td>
					         			</tr>
					         			<tr>
					         				<td style="vertical-align:top;">
					         					<div class="list-header">
												  	<ul>
												  		<li></li>
												  		<li style="width:72%;text-align:left;font-size: inherit;">ไฟล์แนบ</li>
												  		<li style="width:21%;font-size: inherit;">Download</li>
												  		<li></li>
												  	</ul>
												</div>
												<div class="list-items" id="file-list" style="overflow:hidden;">
		
												</div>
					         				</td>
					         				<td style="vertical-align:top;">
					         					<div class="list-header">
												  	<ul>
												  		<li></li>
												  		<li style="width:80%;text-align:left;font-size: inherit;">รายการส่งซ่อม / ส่ง claim</li>
												  		<li style="width:15%;font-size: inherit;">สถานะ</li>
												  		<li></li>
												  	</ul>
												</div>
												<div class="list-items" id="claim-list" style="overflow:hidden;">
		
												</div>
					         				</td>
					         				<td style="vertical-align:top;">
					         					<div class="list-header">
												  	<ul>
												  		<li></li>
												  		<li style="width:80%;text-align:left;font-size: inherit;">รายการสั่งซื้อ</li>
												  		<li style="width:15%;font-size: inherit;">สถานะ</li>
												  		<li></li>
												  	</ul>
												</div>
												<div class="list-items" id="purchase-list" style="overflow:hidden;">
		
												</div>
					         				</td>
					         			</tr>
					         		</table>
					         	</td>
					         </tr>
					         <tr>
					           <td>&nbsp;</td>
					           <td></td>
					           <td>&nbsp;</td>
					           <td>&nbsp;</td>
					           <td></td>
					           <td></td>
					         </tr>
					      </table>
					    </form>
					</div>
	   			</div>
	   			<div class="right"></div>
	   		</div>
	   		<div class="bottom-row">
	   			<div class="left"></div>
	   			<div class="center">
	   			   <ul class="request-state">
	   			   </ul>
	   			</div>
	   			<div class="right"></div>
	   		</div>
	   </div>
		
  	</div>
  	
  </div>
 
</body>
<script>
	$(document).ready(function (){
		var height = $(document).height();
		var main_body_height = height-120;
		$(".list-body").height(main_body_height);
		$("#request-items").height(main_body_height-75);
		$(".search-list").height(main_body_height);
		$(".search-detail").height(main_body_height);
		changePage();
	});

	function go2First(){
		if(($('#select-page').val()*1)>1){
			var page = ($('#select-page').val()*1)-1;
			$('#select-page').val(1);
			changePage();
		}
	}
	function go2Pre(){
		var page = ($('#select-page').val()*1)-1;
		if(page>0){
			$('#select-page').val(page);
			changePage();
		}
	}
	function go2Next(){
		var page = $('#select-page').val()*1;
		var max = $('#select-page option').length*1;
		if(page<max){
			$('#select-page').val(page+1);
			changePage();
		}
	}
	function go2Last(){
		var page = $('#select-page').val()*1;
		var max = $('#select-page option').length*1;
		if(page<max){
			$('#select-page').val(max);
			changePage();
		}
	}
    function selectSupport(id,name,status,start_date,finish_date){
        var html = '<tr id="support-'+id+'">';
        html+= '  <td class="line-l-dash line-b-dash" align="left">&nbsp;'+name+'</td>';
        html+='	  <td class="line-l-dash line-b-dash" align="center">'+start_date+'</td>';
        html+='	  <td class="line-l-dash line-b-dash" align="center">'+finish_date+'</td>';
        html+='	  <td class="line-l-dash line-r-dash line-b-dash" align="center"><b>'+status+'</b></td>';
        html+= '</tr>';
    	$('#support-list').append(html);
	}
    function uploadComplete(id,filename,url){
        var li = '<ul>';
        	li+= '	<li></li>';
		    li+= '  <li style="width:72%;font-size: inherit;text-align:left;">'+filename+'</li>';
		    li+= '	<li style="width:21%"><span class="download-icon" onclick="javascript:downloadFile('+$('#FRequestID').val()+',\''+filename+'\',\''+url+'\')"></div></li>';
		    li+= '	<li></li>';
		    li+= '</ul>';
		$('#file-list').append(li);
    }
    function downloadFile(id,filename,url){
    	var width = screen.width-10;
		var height = screen.height-60;
		newwindow=window.open('../../../download.php?name='+filename+'&reqId='+id+'&filename='+url,
									  'downloadWindow','width='+width+',height='+height+',left=0,top=0,screenX=0,screenY=0,status=no,menubar=yes,scrollbars=yes,copyhistory=yes, resizable=yes,fullscreen=no');
    }
    function openClaimInfor(id,no){
		var w = 700;
		var h = 250;
		if(isNaN(id))id='';
		parent.TINY.box.show({iframe:'../eqiupment-repair/templates/informations/information-claim.php?id='+id+'&no='+no,boxid:'frameless',width:w,height:h,fixed:false,maskopacity:40});
	}
    function getClaimItems(id){
    	$.ajax({ 
			url: "../../controllers/claim_controller.php" ,
			type: "POST",
			datatype: "json",
			data: {"function":"get_json",
				   "FRequestID":id
			}
		})
		.success(function(results) { 
			$("#claim-list").empty();
			results = jQuery.parseJSON(results);
			var rows = results['rows'];
			var begin = results['begin'];
			var end = results['end'];
			var total = results['total'];
			var total_page = results['total_page'];
			var page = results['page'];
			if(rows!=null){
				for(var i=0;i<rows.length;i++){
					var cell = rows[i]['cell'];
					var ul = "<ul style=\"cursor:pointer\" onclick=\"javascript:openClaimInfor('"+cell['FClaimID']+"','"+cell['FReqNo']+"');\">";
					ul+= "<li></li>";
				  	ul+= "<li style=\"width:80%;text-align:left;font-size: inherit;\">"+cell['FItems']+"</li>";
				  	ul+= "<li style=\"width:15%;\"><span class=\""+cell['StatusIcon']+"\"></span></li>";
				  	ul+= "<li></li>";
				    ul+= "<ul>";
					$("#claim-list").append(ul);
				}
			}
			
		});
    }/*End of function getClaimItems()*/
    function openPurchaseInfor(id,no){
		var w = 800;
		var h = 550;
		if(isNaN(id))id='';
		parent.TINY.box.show({iframe:'../eqiupment-repair/templates/informations/information-purchase.php?id='+id+'&no='+no,boxid:'frameless',width:w,height:h,fixed:false,maskopacity:40});
	}
    function getPurchaseItems(id){
    	$.ajax({ 
			url: "../../controllers/purchase_controller.php" ,
			type: "POST",
			datatype: "json",
			data: {"function":"get_json",
				   "FRequestID":id
			}
		})
		.success(function(results) { 
			$("#purchase-list").empty();
			results = jQuery.parseJSON(results);
			var rows = results['rows'];
			var begin = results['begin'];
			var end = results['end'];
			var total = results['total'];
			var total_page = results['total_page'];
			var page = results['page'];
			if(rows!=null){
				for(var i=0;i<rows.length;i++){
					var cell = rows[i]['cell'];
					var ul = "<ul style=\"cursor:pointer\" onclick=\"openPurchaseInfor('"+cell['FPurchaseID']+"','"+cell['FReqNo']+"');\">";
					ul+= "<li></li>";
				  	ul+= "<li style=\"width:80%;text-align:left;font-size: inherit;\">"+cell['FItems']+"</li>";
				  	ul+= "<li style=\"width:15%;\"><span class=\""+cell['StatusIcon']+"\"></span></li>";
				  	ul+= "<li></li>";
				    ul+= "<ul>";
					$("#purchase-list").append(ul);
				}
			}
			
		});
    }/*End of function getClaimItems()*/
    function getCostItems(id){
    	$.ajax({ 
			url: "../../controllers/request_controller.php" ,
			type: "POST",
			datatype: "json",
			data: {"function":"load_cost",
				   "FRequestID":id
			}
		})
		.success(function(results) { 
			$("#cost-list-l").empty();
			$("#cost-list-p").empty();
			results = jQuery.parseJSON(results);
			var rows = results;
			for(var i=0;i<=5;i++){
				var cell = rows[i];
           		var ul = '<tr>';
                    ul+= '	<td height="20"></td>';
                    ul+= '	<td width="3%" height="20" align="center">'+i+'.</td>';
                    ul+= '	<td width="56%" height="20" class="underlinedott">'+cell['FReqCostDetail']+'</td>';
                    ul+= '	<td width="8%" height="20" align="center">ราคา</td>';
                    ul+= '	<td width="16%" height="20" align="right" class="underlinedott">'+numberWithCommas(cell['FReqCost'])+'</td>';
                    ul+= '	<td width="9%" height="20" align="center">บาท</td>';
                 	ul+= '</tr>';
           		$("#cost-list-p").append(ul);
			}
		});
	}/*End of function getCostItem()*/
	function get_request_state(id){
		$.ajax({ 
			url: "../../controllers/request_controller.php" ,
			type: "POST",
			datatype: "json",
			data: {"function":"get_request_state",
				   "FRequestID":id
			}
		})
		.success(function(results) { 
			$(".request-state").empty();
			results = jQuery.parseJSON(results);
			for(var i=0;i<results.length;i++){
				var cell = results[i];
           		var ul = "";
           		if(cell['numDay']>0){
					ul+= '<li class="arrow-state'+cell['type']+'">'+cell['numDay']+' day</li>';
               	}
			    	ul+='<li>';
			    	ul+='	<span>'+cell['label']+'</span>';
			    	ul+='	<span>'+cell['date']+'</span>';
			    	ul+='</li>';
           		$(".request-state").append(ul);
			}
		});
	 }/*End of get_request_state(id)*/
	 function getEstimateItems(id){
	    	$.ajax({ 
				url: "../../controllers/request_controller.php" ,
				type: "POST",
				datatype: "json",
				data: {"function":"load_estimate",
					   "FRequestID":id
				}
			})
			.success(function(results) { 
				$("#estimate-list").empty();
				results = jQuery.parseJSON(results);
				for(var i=0;i<results.length;i++){
					var cell = results[i];
	           		var ul = '<tr>';
                        ul+= '	<td height="20" align="center">'+cell['FReqEstimateID']+'</td>';
                     	ul+= '	<td width="88%" height="20" colspan="3" class="underlinedott">'+cell['FReqEstimate']+'</td>';
                     	ul+= '	<td width="3%"></td>';
                   		ul+= '</tr>'; 
	           		$("#estimate-list").append(ul);
				}
			});
		}/*End of function getCostItem()*/
	function getRequestDetail(id){
		$("#FRequestID").val(id);
		var params = getRequestBody();
		$.ajax({
			type: "POST",
			url: ("../../controllers/request_controller.php"),
			data: "1&function=get&"+params,
			dataType: 'json',
			success: function(json){
				  assignFields(json);
				 // alert(json['FPartAmt']);
				 
				
					 if(json['FLapAmt']=='' || json['FLapAmt']==' '){
						 $('#FLapAmt').val('');
					}else{
						 $('#FLapAmt').val(json['FLapAmt']);
					}
				 if(json['FPartAmt']=='' || json['FPartAmt']==' ' ){
						 $('#FPartAmt').val('');
					}else{
						 $('#FPartAmt').val(json['FPartAmt']);
					}
				
				
					 if(json['FPhoto_1']!=''){
						 $('#photo_1').attr("src","../../../uploads/mt-data/reqNo-"+$('#FRequestID').val()+"/"+json['FPhoto_1']);
					}else{
						 $('#photo_1').attr("src","../../../images/cm/default_photo2.jpg");
					}
					 if(json['FPhoto_2']!=''){
						 $('#photo_2').attr("src","../../../uploads/mt-data/reqNo-"+$('#FRequestID').val()+"/"+json['FPhoto_2']);
					}else{
						 $('#photo_2').attr("src","../../../images/cm/default_photo1.jpg");
					}
				    if(json['FPhoto_3']!=''){
						$('#photo_3').attr("src","../../../uploads/mt-data/reqNo-"+$('#FRequestID').val()+"/"+json['FPhoto_3']);
					 }else{
						 $('#photo_3').attr("src","../../../images/cm/default_photo3.jpg");
					}
				
					  if(json['FPhoto_4']!=''){
						  $('#photo_4').attr("src","../../../uploads/mt-data/reqNo-"+$('#FRequestID').val()+"/"+json['FPhoto_4']); 
				      }else{
						 $('#photo_4').attr("src","../../../images/cm/default_photo3.jpg");
					}
				
					
				
				
				  if(json['FStatus']=="noapprove" || json['FStatus']=="returnedit"){
					  $('#show_noapprove').show();
				  }else{
					    $('#show_noapprove').hide();
				  }
				if(json['FStatus']=="cancel"){
					  $('#show_cancel').show();
				  }else{
					    $('#show_cancel').hide();
				  }
				
				
				  if(json['FAsset_no']==""){
					  $('#no_asset').attr('checked','checked');
					  if(json['FApprove']=="Y") $('#FApprove').attr('checked','checked');
					  if(json['FReam']=="Y")$('#FReam').attr('checked','checked');
					  $(function(){
						  $.uniform.update("#no_asset");
						  $.uniform.update("#FApprove");
						  $.uniform.update("#FReam");
					  });
				  }
				  
				  $('.request_no').empty().html(json['FReqNo']);
				  $('#attachment').show();
				  $.ajax({
						type: "POST",
						url: ("../../controllers/request_controller.php"),
						data: "1&function=list_attach&FRequestID="+$('#FRequestID').val(),
						dataType: 'json',
						success: function(json){
							$('#file-list').empty();
							for(var i=0;i<json.length;i++){
								var files = json[i];
								uploadComplete(files['FAttachID'],files['FAttachName'],files['FAttachLink']);
							}
						}
				  });
				  $.ajax({
						type: "POST",
						url: ("../../controllers/request_controller.php"),
						data: "1&function=load_support&FRequestID="+$('#FRequestID').val(),
						dataType: 'json',
						success: function(json){
							$('#support-list').empty();
							for(var i=0;i<json.length;i++){
								var fields = json[i];
								var name = fields['first_name']+'  '+fields['last_name'];
								selectSupport(fields['FSupportID'],name,fields['FStatus'],fields['FStartDate'],fields['FFinishDate']);
							}
						}
				  });

				  getClaimItems(id);
				  getPurchaseItems(id);
				  //getCostItems(id);
				  //getEstimateItems(id);
				  get_request_state(id);
			}
		});
	}/*End of function getRequestDetail*/
		
	function changePage(){
		var fieldStatus = "<?php print($fieldStatus)?>";
		
		$.ajax({ 
			url: "../../controllers/request_controller.php" ,
			type: "POST",
			datatype: "json",
			data: {"function":"get_data_section_list",
				   "page":$('#select-page').val(),
				   "search[t1.FRepair_comp_id][value]":$('#FRepair_comp_id').val(),
				   "search[t1.FRepair_comp_id][condition]":"=",
				   "search[t1.FSectionID][value]":$('#sec_id').val(),
				   "search[t1.FSectionID][condition]":"=",
				   "search[t1.FBranchID][value]":$('#FBranchID').val(),
				   "search[t1.FBranchID][condition]":"=",
				   "search[<?php print($fieldStatus)?>][value]":$('#status').val(),
				   "search[<?php print($fieldStatus)?>][condition]":"=",
				   "search[duplicate][0][key]":"FReqDate",
				   "search[duplicate][0][value1]":$('#StartDate').val(),
				   "search[duplicate][0][condition1]":">=",
				   "search[duplicate][0][value2]":$('#EndDate').val(),
				   "search[duplicate][0][condition2]":"<=",
				   "search[multi][value]":$('#key-search').val(),
				   "search[multi][fields][FDetail]":"like",
				   "search[multi][fields][FSerial]":"like",
				   "search[multi][fields][FReqNo]":"like",
				   "keysearch":$('#key-search').val()
			}
		})
		.success(function(results) { 
			//alert(results);
			$("#request-items").empty();
			results = jQuery.parseJSON(results);
			var rows = results['rows'];
			var begin = results['begin'];
			var end = results['end'];
			var total = results['total'];
			var total_page = results['total_page'];
			var page = results['page'];
			if(rows!=null){
				for(var i=0;i<rows.length;i++){
					var cell = rows[i]['cell'];
					var ul = "<ul>";
						ul+= "<li style=\"width: auto;\"></li>";
					  	ul+= "<li style=\"width: 100%;height:auto;\">";
					  	ul+= "<table width=\"100%\" border=\"0\">";
					  	ul+= "	<tr style=\"cursor:pointer;\" onclick=\"getRequestDetail('"+cell['FRequestID']+"')\">";
					  	ul+= "		<td style=\"width:24%;vertical-align: top;\">"+cell['FReqNo']+"</td>";
					  	ul+= "		<td style=\"text-align:left;;vertical-align: top;\">"+cell['FDetail']+"</td>";
					  	ul+= "		<td style=\"width:14%;vertical-align: top;\"><span class=\""+cell['StatusIcon']+"\"></span></td>";
					  	ul+= "	</tr>";
					  	ul+= "</table>";
					  	ul+= "</li>";
					  	//ul+= "<li></li>";
					    ul+= "<ul>";
					$("#request-items").append(ul);
				}
			}
			$('#begin-item').empty().html(begin);
			$('#end-item').empty().html(end);
			$('#total-item').empty().html(total);
			renderPage(document.getElementById('select-page'),total_page);
			$('#select-page').val(page);
			
		});
	}/*End function renderPage()*/
	
		function updateBranch(selected){
			$.ajax({
					type: "POST",
					url: ("../../../main/controllers/utilities_controller.php"),
					data: "1&function=get_BranchJson&comp_id="+$('#FRepair_comp_id').val(),
					dataType: 'json',
					success: function(data){
							appendOption(document.getElementById('FBranchID'),data);
							if(selected!=''){
									$("#brn_id option[value=" +selected+"]").attr("selected","selected") ;
							}
							setSelectValue('FBranchID');
					}
				});
	}	
	function updateSection(selected){
			$.ajax({
					type: "POST",
					url: ("../../../main/controllers/utilities_controller.php"),
					data: "1&function=get_SectJson&comp_id="+$('#FRepair_comp_id').val(),
					dataType: 'json',
					success: function(data){
							appendOption(document.getElementById('sec_id'),data);
							if(selected!=''){
									$("#sec_id option[value=" +selected+"]").attr("selected","selected") ;
							}
							setSelectValue('sec_id');
					}
				});
	}

</script>
</html>