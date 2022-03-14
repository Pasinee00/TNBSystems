<!DOCTYPE html>
<?php 
include '../../../lib/db_config.php';
include '../../../main/modules/Model_Utilities.php';
include '../../../pis_sys/models/user_model.php';
include '../../modules/request_model.php';

$utilMD = new Model_Utilities();
$userMD = new Model_User();
$reqMD = new Model_Request();
$empId = $_REQUEST['e_id'];
$userId = $_REQUEST['u_id'];
$back = $_REQUEST['back'];
$close = $_REQUEST['close'];
$compId = $_REQUEST['compId'];
$repairGroups = $utilMD->get_RepairGroupList();
$machineType = $utilMD->get_MachineTypeList();
$brnList = $utilMD->get_BranchList($compId);
$empInfo= json_decode($utilMD->get_EmpById($empId));
$userInfo = $userMD->get_data($userId);
$FRequestID = $_REQUEST['FRequestID'];
$costData = $reqMD->load_cost($FRequestID);
$estimateData = $reqMD->load_estimate($FRequestID);
$managerOption = $utilMD->get_ManagerList();

if($back=="assign"){
	$backUrl = '../../administrator/request-assign-owner.tpl.php';	
}else if($close=="holder"){
	$backUrl = '../../supports/request-holder.tpl.php';
}else{
	if($userInfo['user_type']=="E")
		$backUrl ='../general-index.php';
	else if($userInfo['user_type']=="AM")
		$backUrl ='../../administrator/administrator-index.php';
	else if($userInfo['user_type']=="M")
		$backUrl ='../../supports/support-index.php';
}

$date = date('Y-m-d');
$time = (date('H')-1).":".date('i');

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-874">
<title>Request</title>
<script  type="text/javascript" src="../../../../jsLib/jquery-1.8.0.min.js"></script>
<script src="../../../../jsLib/uniform/jquery.uniform.js" type="text/javascript" charset="utf-8"></script>
<script  type="text/javascript" src="../../../../jsLib/jquery-chosen/chosen.jquery.js"></script>
<script src="../../../../jsLib/js_scripts/js_function.js" type="text/javascript" charset="utf-8"></script>
<script  type="text/javascript" src="../../../../jsLib/jquery-confirm/jquery.confirm.js"></script>
<script  type="text/javascript" src="../../../../jsLib/jquery-confirm/js/script.js"></script>
<script type="text/javascript" src="../../../../jsLib/datepicker/zebra_datepicker.js"></script>

<link href="../../../../css/dialog-box.css" rel="stylesheet" type="text/css">
<link href="../../../../css/display.css" rel="stylesheet" type="text/css">
<link href="../../../../jsLib/jquery-chosen/chosen.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="../../../../jsLib/uniform/css/uniform.default.css" type="text/css" media="screen">
<link href="../../../../css/sys_controll.css" rel="stylesheet" type="text/css">
<link href="../../../../css/input.css" rel="stylesheet" type="text/css">
<link href="../../../../jsLib/jquery-confirm/jquery.confirm.css" rel="stylesheet" type="text/css">
<link href="../../../../jsLib/datepicker/css/default.css" rel="stylesheet" type="text/css" />
<script>
$(function(){
    $("input, textarea").uniform();
    $(".uniform-select").uniform();
  });
</script>
</head>

<body scrolling="no">
  <div class="content-top">
  	<div class="_content-title">สร้างใบคำร้องแจ้งซ่อม</div>
  	<div class="search-action">
  		<span class="label">Request No : </span>
  		<span class="request_no" style="color:#000066;font-weight:bold"></span>
  	</div>
  </div>
  
  <div class="list-body" style="box-shadow: 0 0 5px #26384A;overflow:auto">
    <form name="form1" method="post" enctype="multipart/form-data" action="../../../controllers/request_controller.php?function=upload" target="upload_target">
      <table width="98%" align="center">
         <tr>
           <td width="12%"><b>รหัสพนักงาน</b> :</td>
           <td width="22%"  id="emp_code"></td>
           <td width="4%">&nbsp;</td>
           <td width="15%"><b>ชื่อ - สกุล</b> :</td>
           <td width="23%"  id="emp_name"></td>
           <td width="24%"></td>
         </tr>
         <tr>
           <td><b>ตำแหน่ง</b> :</td>
           <td id="FPosition"></td>
           <td>&nbsp;</td>
           <td><b>แผนกที่ส่งคำร้อง</b> :</td>
           <td id="sec_nameThai"></td>
           <td></td>
         </tr>
         <tr>
           <td><b>สาขาที่ส่งคำร้อง</b> :</td>
           <td id="brn_name"></td>
           <td>&nbsp;</td>
           <td><b>ผู้จัดการ /หัวหน้างาน</b> :</td>
           <td id="FManagerName"></td>
           <td></td>
         </tr>
          <tr>
           <td></td>
           <td></td>
           <td>&nbsp;</td>
           <td><b>ผู้อำนวยการ</b> :</td>
           <td id="FSupervisorName"></td>
           <td></td>
         </tr>
         <tr>
           <td><b>เลขที่อ้างอิง</b>  <font color="#FF0000">*</font> :</td>
           <td id="FInf_no"></td>
           <td>&nbsp;</td>
           <td><b>เลขที่ทรัพย์สิน</b> <font color="#FF0000">*</font>:</td>
           <td id="FAsset_no"></td>
           <td></td>
         </tr>
         <tr>
           <td><b>หน่วยงาน</b> <font color="#FF0000">*</font> :</td>
           <td id="FFnc"></td>
           <td>&nbsp;</td>
           <td><b>เบอร์โทร.</b> <font color="#FF0000">*</font> :</td>
           <td id="FTel"></td>
           <td></td>
         </tr>
         <tr>
           <td><b>สาขาที่ติดตั้ง</b> <font color="#FF0000">*</font> :</td>
           <td> </td>
           <td>&nbsp;</td>
           <td><b>อาคาร / สถานที่ </b> <font color="#FF0000">*</font> :</td>
           <td id="FLocation"></td>
           <td></td>
         </tr>
         <tr>
           <td><b>ชั้น</b> <font color="#FF0000">*</font> :</td>
           <td id="FFloor"></td>
           <td>&nbsp;</td>
           <td><b>ห้อง</b> <font color="#FF0000">*</font> :</td>
           <td id="FRoom"></td>
           <td></td>
         </tr>
         <tr>
           <td><b>วันที่ / เวลา</b> :</td>
           <td><span id="FReqDate"></span> - <span id="FReqTime"></span></td>
           <td>&nbsp;</td>
           <td><b>ระดับความสำคัญ</b> <font color="#FF0000">*</font> :</td>
           <td id="FLevelName"></td>
           <td></td>
         </tr>
         <tr>
           <td><b>ขอแจ้งซ่อม</b>  <font color="#FF0000">*</font> :</td>
           <td>
           	  <select name="fields[FRepairGroupID]" id="FRepairGroupID" class="uniform-select" onChange="javascript:updateRepairItem('');">
            	<option value="">---กรุณาเลือกรายการขอแจ้งซ่อม</option>
            	<?php if(!empty($repairGroups)){
            			foreach($repairGroups as $key=>$val){
            	?>
            				<option value="<?php print($val['FRepairGroupID']);?>"><?php print($val['FRepairGroupName']);?></option>
            	<?php }}?>
              </select>
           </td>
           <td>&nbsp;</td>
           <td><b>รายการซ่อม</b>  <font color="#FF0000">*</font> :</td>
           <td>
            <select name="fields[FRepairGroupItemID]" id="FRepairGroupItemID" class="uniform-select" style="width:200px;">
            	<option value="">---กรุณาเลือกรายการซ่อม</option>
            </select>
           </td>
           <td></td>
         </tr>
         <tr>
           <td valign="top"><b>รายละเอียด / ปัญหา</b> <font color="#FF0000">*</font> :</td>
           <td colspan="5" id="FDetail"></td>
         </tr>
         <tbody>
         	<tr>
	           <td colspan="6" align="center" style="background-color:#999999;"><b>ผลการดำเนินการของผู้รับผิดชอบ</b></td>
	        </tr>
	        <tr>
	           <td><b>ผู้รับเรื่อง</b>  :</td>
	           <td id="recive_name"></td>
	           <td>&nbsp;</td>
	           <td><b>วันที่รับเรื่อง</b> :&nbsp;</td>
	           <td><span id="FReciveDate"></span> - <span id="FReciveTime"></span></td>
	           <td></td>
	         </tr>
	         <tr>
	           <td><b>เครื่องจักร</b>  :</td>
	           <td>
	           		<select name="fields[FMachineTypeID]" id="FMachineTypeID" class="uniform-select" onChange="javascript:updateRepairType('');">
		            	<option value="">---กรุณาเลือกรายการเครื่องจักร</option>
		            	<?php if(!empty($machineType)){
		            			foreach($machineType as $key=>$val){
		            	?>
		            				<option value="<?php print($val['FMachineTypeID']);?>"><?php print($val['FMachineTypeName']);?></option>
		            	<?php }}?>
	              </select>
	           </td>
	           <td>&nbsp;</td>
	           <td><b>แจ้งซ่อม</b> :&nbsp;</td>
	           <td>
	           		<select name="fields[FRepairItemID]" id="FRepairItemID" class="uniform-select" >
		            	<option value="">---กรุณาเลือกรายการแจ้งซ่อม</option>
	              </select>
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
                                   <td height="20" colspan="5"  id="FJobresultLabel"></td>
                                 </tr>
                                 <?php 
					            	if(!empty($costData['L'])){
					            		foreach($costData['L'] as $key=>$val){
									   	 $FLap_amt = $val['FReqCost'];
					             ?>
		                                 <tr>
		                                    <td height="20">&nbsp;
		                                      <input type="hidden" name="fields[costs][<?=$key?>][FReqCostID]" value="<?=$val['FReqCostID']?>">
										      <input type="hidden" name="fields[costs][<?=$key?>][FReqCostDetail]" value="<?=$utilMD->convert2Thai($val['FReqCostDetail'])?>">
										      <input type="hidden" name="fields[costs][<?=$key?>][FReqCostType]" value="<?=$val['FReqCostType']?>">
		                                    </td>
		                                    <td height="20" colspan="2"><b>ค่าแรง</b></td>
		                                    <td height="20" align="center">ราคา</td>
		                                    <td height="20" align="right" class="underlinedott"><input name="fields[costs][<?=$key?>][FReqCost]" type="text" id="FLap_amt" onkeyup="javascript:changNumeric(this);" style="width:80px;text-align:right;" value="<? if($FLap_amt>0)print number_format($FLap_amt,2,".",",");?>"></td>
		                                    <td height="20" align="center">บาท</td>
		                                 </tr>
		                         <?php 
					            		}
									}
					            ?>
                                 <tr>
                                    <td height="20">&nbsp;</td>
                                    <td height="20" colspan="2"><b>ค่าอะไหล่</b></td>
                                    <td height="20" align="center">&nbsp;</td>
                                    <td height="20" align="right" class="underlinedott">&nbsp;</td>
                                    <td height="20" align="center">&nbsp;</td>
                                 </tr>
                                  <?php 
					            	$FPart_amt_total = 0;
					            	$index = 0;
					            	if(!empty($costData['P'])){
					            	  foreach($costData['P'] as $key=>$val){
									    $FPart_amt = $val['FReqCost'];
									    $FPart_amt_total += $FPart_amt;
									    $index++;
					            ?>
		                                 <tr>
		                                   <td height="20">
		                                   		<input type="hidden" name="fields[costs][<?=$key?>][FReqCostID]" value="<?=$val['FReqCostID']?>">
										        <input type="hidden" name="fields[costs][<?=$key?>][FReqCostType]" value="<?=$val['FReqCostType']?>">
		                                   </td>
		                                   <td width="3%" height="20" align="center"><?=$index?>.</td>
		                                   <td width="56%" height="20" class="underlinedott"><input name="fields[costs][<?=$key?>][FReqCostDetail]" type="text" style="width:80%" value="<?=$utilMD->convert2Thai($val['FReqCostDetail'])?>" maxlength="100"></td>
		                                   <td width="8%" height="20" align="center">ราคา</td>
		                                   <td width="16%" height="20" align="right" class="underlinedott"><input name="fields[costs][<?=$key?>][FReqCost]" type="text" style="width:80px;text-align:right;" onkeyup="javascript:changNumeric(this);" value="<? if($FPart_amt>0)print number_format($FPart_amt,2,".",",");?>"></td>
		                                   <td width="9%" height="20" align="center">บาท</td>
		                                </tr>
                                <?php 
					            	  }
					            	}
					            ?> 
                                <tr>
                                   <td height="20" align="center">
                                   		<input type="checkbox" name="fields[FReam]" id="FReam" <? if($FReam=="Y"){?> checked <? }?> value="Y" class="FReam">
                                   	</td>
                                   <td height="20">&nbsp;</td>
                                   <td height="20" colspan="4">เบิกเงินสดเพื่อจัดซื้อวัสดุมาดำเนินการซ่อมแซ่ม</td>
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
                                <?php 
					            	$index = 0;
					            	if(!empty($estimateData)){
					            	  foreach($estimateData as $key=>$val){
										$index++;
					            ?>
	                                <tr>
	                                  <td height="20" align="center"><?=$index?></td>
	                                  <td width="88%" height="20" colspan="3" class="underlinedott"><input name="fields[estimate][<?=$key?>][FReqEstimate]" type="text" style="width:80%" value="<?=$utilMD->convert2Thai($val['FReqEstimate'])?>" maxlength="100"></td>
	                                  <td width="3%">
	                                  		<input type="hidden" name="fields[estimate][<?=$key?>][FReqEstimateID]" value="<?=$val['FReqEstimateID']?>">
	                                  </td>
	                                </tr> 
                                <?php 
					            	 }
					            	}
					            ?>                   
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
	           			   <td width="12%"><b>กำหนดเสร็จ</b> <font color="#FF0000">*</font> :</td>
				           <td width="23%"><input type="text" name="fields[FEstimate]" id="FEstimate" style="width:80px;text-align:center;" onkeyup="javascript:changNumeric(this);" value=""></td>
				           <td width="4%"></td>
				           <td width="15%"><b>วันที่เปิด Job</b> <?php if($userInfo['user_type']=="M"){?><font color="#FF0000">*</font><?php }?> :</td>
				           <td width="24%"><input type="text" name="fields[FEditDate]" id="FEditDate" style="width:80%;" value=""></td>
	           			</tr>
	           			<tr id="close-job">
				           <td><b>วันที่กำหนดเสร็จ </b> :</td>
				           <td><input type="text" name="fields[FDueDate]" id="FDueDate" readonly style="width:50%;" value=""></td>
				           <td></td>
				           <td><b>วันที่ปิดงาน</b ><?php if($close =="y"){?><font color="#FF0000">*</font><?php }?>  :</td>
				           <td><input type="text" name="fields[FFinishDate]" id="FFinishDate" style="width:80%;" value=""></td>
				         </tr>
	           			<tr>
				           <td><b>ผู้ตรวจรับงาน</b>  :</td>
				           <td><input type="text" name="fields[FAuditorName]" id="FAuditorName" style="width:80%;" value=""></td>
				           <td></td>
				           <td><b>วันที่ตรวจรับงาน</b>  :</td>
				           <td><input type="text" name="fields[FAuditDate]" id="FAuditDate" style="width:80%;" value=""></td>
				         </tr>
				          <tr>
				           <td></td>
				           <td>
				              <?php if($userInfo['user_type']=="AM" || $userInfo['user_type']=="M"){?>
				           		<input type="checkbox" name="fields[FApprove]" id="FApprove" value="Y" class="FApprove"> รอการเซ็นต์อนุมัติ  
				           	  <?php }else{?>
				           	  	<input type="checkbox" name="FApprove" id="FApprove" disabled value="Y" class="FApprove"> รอการเซ็นต์อนุมัติ 
				           	  <?php }?>
				           	  <input type="hidden" name="is_sendTo" id="is_sendTo" value="">
				           	  <input type="hidden" name="FReceiveDoc" id="FReceiveDoc" value="">
				           </td>
				           <td></td>
				           <td></td>
				           <td></td>
				         </tr>
	           		</table>
	           </td>
	           <td colspan="3" rowspan="5" valign="top" align="center">
	                <?php if($userInfo['user_type']=="AM"){?>
		           		<table width="250px" border="0" cellspacing="0" cellpadding="0">
		                   <tr>
		                      <td>ผู้แก้ไข  <font color="#FF0000">*</font> :</td>
		                      <td align="center" width="24">
		                      	<span class="add-icon" style="width:24px;" onclick="javascript:openPopup();"></span>
		                      </td>
		                   </tr>
		                   <tbody id="support-list">
		                    
		                   </tbody>
		                </table>
	                <?php }else{?>
		           		<table width="250px" border="0" cellspacing="0" cellpadding="0">
		                   <tr>
		                      <td width="65" valign="top">ผู้แก้ไข  <font color="#FF0000">*</font> :</td>
		                      <td align="center" >
		                      		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		                      			<tbody id="support-list">
		                    
		                   				</tbody>
		                      		</table>
		                      </td>
		                   </tr>
		                   
		                </table>
	                <?php }?>
	           </td>
	         </tr>
         </tbody>
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
    <iframe id="upload_target" name="upload_target" src="" style="width:100%;height:400px;border:1px solid #ccc; display:none"></iframe>
  </div>
  <div class="content-top">
    <table width="98%" align="center">
    	<tr>
   			<td height="20" align="center">
   			    <?php if($userInfo['user_type']=="M"){?>
   			           <a class="button-bule" id="save-btn" href="javascript:void(0);" onclick="javascript:saveData();"> บันทึก  </a>&nbsp;
   			    		<a class="button-bule" style="display:<?php if(empty($back) and empty($close)){echo "";}else{echo "none;";}?>" id="openJob-btn" href="javascript:void(0);" onclick="javascript:openJob();"> เปิด Job  </a>&nbsp;
   			    	    <a class="button-bule" style="display:<?php if($close=="y" OR $close=="holder"){echo "";}else{echo "none;";}?>" id="closeJob-btn" href="javascript:void(0);" onclick="javascript:closeJob();"> ปิด Job  </a>&nbsp;
   			    		<a class="button-bule" style="display:<?php if($close=="y" OR $close=="holder"){echo "";}else{echo "none;";}?>" id="sendTo-btn" href="javascript:void(0);" onclick="javascript:sendToJob(<?php print($FRequestID);?>);"> Send To  </a>&nbsp;
   			   <?php }else if($userInfo['user_type']=="AM"){?>
               	      <a class="button-bule" id="save-btn" href="javascript:void(0);" onclick="javascript:saveData();"> บันทึก  </a>&nbsp;
   			    		<a class="button-bule"  id="sendTo-btn" href="javascript:void(0);" onclick="javascript:sendToJob(<?php print($FRequestID);?>);"> Send To  </a>&nbsp;
                <?php }else{?>
   					  <a class="button-bule" href="javascript:void(0);" onclick="javascript:saveData();"> บันทึก  </a>&nbsp;
   				<?php }?>
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
	$(document).ready(function (){
		var height = $(document).height();
		var main_body_height = height-85;
		var preloading = (height-40)/2;
		$(".list-body").height(main_body_height);
		$(".preloading").attr("style","margin-top:"+preloading+"px;");

		$('#FReciveDate').Zebra_DatePicker({
			  direction: true
		});
		$('#FEditDate').Zebra_DatePicker({
			 onSelect : function(){
				 		if($("#FEstimate").val()==""){
								var buttons = '[{"title":"OK","class":"blue","action":"$(\'#FEditDate\').val(\'\');$(\'#FEstimate\').focus();"}]';
								buttons = eval(buttons);
								_confirm("warning","Warning","กรุณาระบุจำนวนวันกำหนดเสร็จ",buttons);
						}else{
								var newDate = 	incr_date($('#FEditDate').val(),(($('#FEstimate').val()*1)-1));
								$('#FDueDate').val(newDate);
						}
				 }
		});
		$('#FFinishDate').Zebra_DatePicker();
		$('#FAuditDate').Zebra_DatePicker();
	});

	 $(function(){
        $('.chzn-select').chosen({width: "95%"});
     });

	function openPopup(id,sendTo){
		if(isNaN(id))id='';
		if(sendTo=="sendTo")parent.TINY.box.show({iframe:'../eqiupment-repair/templates/informations/support-information.php?id='+id,boxid:'frameless',width:650,height:350,fixed:false,maskopacity:40,closejs:function(){closeJob()}});
		else parent.TINY.box.show({iframe:'../eqiupment-repair/templates/informations/support-information.php?id='+id,boxid:'frameless',width:650,height:350,fixed:false,maskopacity:40});
	}

	function cancel(){
		window.location.href = '<?php print($backUrl);?>?u_id=<?php print($userId);?>&e_id=<?php print($empId);?>&compId=<?php print($compId);?>'
    }
	function getEmpInfo(){
		$.ajax({ 
			url: "../../../../main/controllers/utilities_controller.php" ,
			type: "POST",
			dataType: 'json',
			data: {"function":"get_EmpInfor",
				   "emp_code":$("#emp_code").val(),
				   "comp_id":<?php print($compId);?>
			}
		})
		.success(function(json) { 
			if(json['result']==1){
				$('#emp_name').val(json['first_name']+" "+json['last_name']);
				$('#FReqID').val(json['emp_id']);
				$('#FPosition').val(json['post_name']);
				$('#sec_nameThai').val(json['sec_nameThai']);
				$('#FSectionID').val(json['sec_id']);
				$('#brn_name').val(json['brn_name']);
				$('#FBranchID').val(json['brn_id']);
				 $(function(){
					  $.uniform.update("#FBranchID");
				  });
			}else{
				var buttons = '[{"title":"OK","class":"blue","action":"$(\'#emp_code\').focus();"}]';
				buttons = eval(buttons);
				_confirm("warning","Warning","ไม่พบข้อมูลของพนักงานรหัส "+$('#emp_code').val()+" กรุณาตรวจสอบความถูกต้อง",buttons);
			}
		});
	}
	function updateRepairItem(selected){
		$.ajax({
				type: "POST",
				url: ("../../../../main/controllers/utilities_controller.php"),
				data: "1&function=get_RepairGroupItemJson&group_id="+$('#FRepairGroupID').val(),
				dataType: 'json',
				success: function(data){
						appendOption(document.getElementById('FRepairGroupItemID'),data);
						$("#FRepairGroupItemID option[value=" +selected+"]").attr("selected","selected") ;
						setSelectValue('FRepairGroupItemID');
						
				}
			});
    }
	function updateRepairType(selected){
		$.ajax({
				type: "POST",
				url: ("../../../../main/controllers/utilities_controller.php"),
				data: "1&function=get_RepairItemJson&mtype_id="+$('#FMachineTypeID').val(),
				dataType: 'json',
				success: function(data){
						appendOption(document.getElementById('FRepairItemID'),data);
						$("#FRepairItemID option[value=" +selected+"]").attr("selected","selected") ;
						setSelectValue('FRepairItemID');
						
				}
			});
    }
    function uploadData(){
		document.forms[0].submit();
    }
    function uploadComplete(id,filename,url){
        var li = '<ul id="attach-list-'+id+'" class="attach-list">';
		    li+= '   <li style="width:80%"> <a href="javascript:void(0);" onclick="javascript:downloadFile('+$('#FRequestID').val()+',\''+filename+'\',\''+url+'\')">'+filename+'</a></li>';
		    li+= '   <li style="width:20%"><div class="trash-icon" onclick="javascript:deleteFile('+id+',\''+url+'\');"></div></li>';
		    li+= '</ul>';
		$('#attach-list').append(li);
    }
    function deleteFile(id,url){
    	$.ajax({
			type: "POST",
			url: ("../../../controllers/request_controller.php"),
			data: "1&function=delete_file&rId="+$('#FRequestID').val()+'&id='+id+'&url='+url,
			dataType: 'json',
			success: function(data){
				$('#attach-list-'+id).remove(); 
			}
		});
    }
    function downloadFile(id,filename,url){
    	var width = screen.width-10;
		var height = screen.height-60;
		newwindow=window.open('../../../../download.php?name='+filename+'&reqId='+id+'&filename='+url,
									  'downloadWindow','width='+width+',height='+height+',left=0,top=0,screenX=0,screenY=0,status=no,menubar=yes,scrollbars=yes,copyhistory=yes, resizable=yes,fullscreen=no');
    }
    function selectSupport(id,name,status){
		var html = '<tr id="support-'+id+'">';
            html+= '   <td>&nbsp;'+name+'</td>';
            html+= '   <td align="center" width="24">';
			if($('#user_type').val()=='AM' || $('#is_sendTo').val()=='Y')html+= '      <span class="remove-icon" style="padding-left:0px;width: 24px;" onClick="javascript:confirmRemove('+id+');"></span>';
			html+= '	  <input type="hidden" name="fields[supports]['+id+'][id]" id="support_'+id+'" value="'+id+'">';
			html+= '	  <input type="hidden" name="fields[supports]['+id+'][send_to]" id="send_to_'+id+'" value="'+$('#is_sendTo').val()+'">';
			html+= '	  <input type="hidden" name="fields[supports]['+id+'][status]" id="support_status_'+id+'" value="'+status+'">';
            html+= '   </td>';
            html+= '</tr>';
        $('#support-list').append(html);
	}
	function getSupport(){
		var ids = 0;
		$('#support-list').find('input[id^=support_]').each(function(){
			if($(this).val()!='N' && $(this).val()!='S')ids +=','+$(this).val();
		});
		return ids;
	}
	function confirmRemove(sId){
		var buttons = '[{"title":"OK","class":"blue","action":"removeSupport('+sId+');"},{"title":"Cancel","class":"blue","action":""}]';
		buttons = eval(buttons);
		_confirm("warning","Warning","Confirm to remove",buttons);
	}
	function removeSupport(sId){
		$.ajax({
			type: "POST",
			url: ("../../../controllers/request_controller.php"),
			data: "1&function=remove_support&FRequestID="+$('#FRequestID').val()+'&FSupportID='+sId,
			dataType: 'json',
			success: function(data){
				$('#support-'+sId).remove(); 
			}
		});
	}
	
	function openJob(){
		var is_process = 1;
		if($('#FEditDate').val()==""){
			var buttons = '[{"title":"OK","class":"blue","action":""}]';
			buttons = eval(buttons);
			_confirm("warning","Warning","กรุณาระบุวันที่เปิด Job",buttons);
		}else{
			$('.login-overlay').show();
			var dueDate = incr_date($('#FEditDate').val(),$('#FEstimate').val());
			$('#FDueDate').val(dueDate);
			$('#FStatus').val("S");
			var params = getRequestBody();
			$.ajax({
				type: "POST",
				url: ("../../../controllers/request_controller.php"),
				data: "1&function=open_request&"+params,
				dataType: 'json',
				success: function(data){
					$('.login-overlay').hide(); 
					$('#closeJob-btn').show();
					$('#sendTo-btn').show();
					$('#openJob-btn').hide();
					//$('#close-job').show();
				}
			});
		}
	}
	function closeJob(){
		if($('#FFinishDate').val()==""){
			var buttons = '[{"title":"OK","class":"blue","action":""}]';
			buttons = eval(buttons);
			_confirm("warning","Warning","กรุณาระบุวันที่ปิด Job",buttons);
		}else{
			var supports = 0;
			if($('#is_sendTo').val()=='Y'){
				$('#support-list').find('input[id^=send_to_]').each(function(){
					if($(this).val()=='Y')supports++;
				});
				if(supports==0){
					var buttons = '[{"title":"OK","class":"blue","action":"openPopup('+$('#FRequestID').val()+')"}]';
					buttons = eval(buttons);
					_confirm("warning","Warning","กรุณาระบุผู้รับงานต่อ",buttons);
				}else{
					$('.login-overlay').show();
					var params = getRequestBody();
					$.ajax({
						type: "POST",
						url: ("../../../controllers/request_controller.php"),
						data: "1&function=close_request&"+params,
						dataType: 'json',
						success: function(data){
							$('.login-overlay').hide(); 
							cancel();
						}
					});
				}
			}else{
				$('.login-overlay').show();
				var params = getRequestBody();
				$.ajax({
					type: "POST",
					url: ("../../../controllers/request_controller.php"),
					data: "1&function=close_request&"+params,
					dataType: 'json',
					success: function(data){
						$('.login-overlay').hide(); 
						cancel();
					}
				});
			}
		}
	}
	function sendToJob(id){
		if($('#FFinishDate').val()==""){
			var buttons = '[{"title":"OK","class":"blue","action":""}]';
			buttons = eval(buttons);
			_confirm("warning","Warning","กรุณาระบุวันที่ปิด Job",buttons);
		}else{
			$('#is_sendTo').val('Y');
			openPopup(id,'sendTo');
			$('#sendTo-btn').hide();
		}
	}
	
    function saveData(){
    	if($('#FReqID').val()==""){
    		var buttons = '[{"title":"OK","class":"blue","action":"$(\'#emp_code\').focus();"}]';
			buttons = eval(buttons);
			_confirm("warning","Warning","กรุณาระบุรหัสพนักงานที่ส่งคำร้อง",buttons);
        }else if($('#FManagerID').val()==""){
        	var buttons = '[{"title":"OK","class":"blue","action":"$(\'#FManagerID\').focus();"}]';
			buttons = eval(buttons);
			_confirm("warning","Warning","กรุณาระบุผู้จัดการ /หัวหน้างาน",buttons);
        }else if($('#FSupervisorID').val()==""){
        	var buttons = '[{"title":"OK","class":"blue","action":"$(\'#FSupervisorID\').focus();"}]';
			buttons = eval(buttons);
			_confirm("warning","Warning","กรุณาระบุผู้อำนวยการ",buttons);
        }else if($('#FInf_no').val()==""){
			var buttons = '[{"title":"OK","class":"blue","action":"$(\'#FInf_no\').focus();"}]';
			buttons = eval(buttons);
			_confirm("warning","Warning","กรุณาระบุเลขที่อ้างอิง",buttons);
		}else if($('#FAsset_no').val()=="" && $('#no_asset').is(':checked')==false){
			var buttons = '[{"title":"OK","class":"blue","action":"$(\'#FAsset_no\').focus();"}]';
			buttons = eval(buttons);
			_confirm("warning","Warning","กรุณาระบุเลขที่ทรัพย์สิน",buttons);
	    }else if($('#FFnc').val()==""){
	    	var buttons = '[{"title":"OK","class":"blue","action":"$(\'#FFnc\').focus();"}]';
			buttons = eval(buttons);
			_confirm("warning","Warning","กรุณาระบุหน่วยงาน",buttons);
		}else if($('#FTel').val()==""){
			var buttons = '[{"title":"OK","class":"blue","action":"$(\'#FTel\').focus();"}]';
			buttons = eval(buttons);
			_confirm("warning","Warning","กรุณาระบุเบอร์โทร.",buttons);
		}else if($('#FBranchID').val()==""){
			var buttons = '[{"title":"OK","class":"blue","action":"$(\'#FBranchID\').focus();"}]';
			buttons = eval(buttons);
			_confirm("warning","Warning","กรุณาระบุสาขาที่ติดตั้ง",buttons);
	    }else if($('#FLocation').val()==""){
	    	var buttons = '[{"title":"OK","class":"blue","action":"$(\'#FLocation\').focus();"}]';
			buttons = eval(buttons);
			_confirm("warning","Warning","กรุณาระบุอาคาร / สถานที่",buttons);
		}else if($('#FFloor').val()==""){
			var buttons = '[{"title":"OK","class":"blue","action":"$(\'#FFloor\').focus();"}]';
			buttons = eval(buttons);
			_confirm("warning","Warning","กรุณาระบุชั้น",buttons);
	    }else if($('#FRoom').val()==""){
	    	var buttons = '[{"title":"OK","class":"blue","action":"$(\'#FRoom\').focus();"}]';
			buttons = eval(buttons);
			_confirm("warning","Warning","กรุณาระบุ ห้อง",buttons);
		}else if($('#FLevel').val()==""){
			var buttons = '[{"title":"OK","class":"blue","action":"$(\'#FLevel\').focus();"}]';
			buttons = eval(buttons);
			_confirm("warning","Warning","กรุณาระบุ ระดับความสำคัญ",buttons);
		}else if($('#FRepairGroupID').val()==""){
			var buttons = '[{"title":"OK","class":"blue","action":"$(\'#FRepairGroupID\').focus();"}]';
			buttons = eval(buttons);
			_confirm("warning","Warning","กรุณาระบุ รายการขอแจ้งซ่อม",buttons);
	    }else if($('#FRepairGroupItemID').val()==""){
	    	var buttons = '[{"title":"OK","class":"blue","action":"$(\'#FRepairGroupItemID\').focus();"}]';
			buttons = eval(buttons);
			_confirm("warning","Warning","กรุณาระบุรายการซ่อม",buttons);
		}else if($('#FDetail').val()==""){
			var buttons = '[{"title":"OK","class":"blue","action":"$(\'#FDetail\').focus();"}]';
			buttons = eval(buttons);
			_confirm("warning","Warning","กรุณาระบุรายละเอียด / ปัญหา",buttons);
	    }else{
		    var is_process = 1;
		    var supports = getSupport();
		    if($('#user_type').val()=="AM"){
				if($('#FEstimate').val()==""){
					is_process = 0;
					var buttons = '[{"title":"OK","class":"blue","action":"$(\'#FEstimate\').focus();"}]';
					buttons = eval(buttons);
					_confirm("warning","Warning","กรุณาระบุจำนวนวันกำหนดเสร็จ",buttons);
				}else if(supports=="0"){
					is_process = 0;
					var buttons = '[{"title":"OK","class":"blue","action":"openPopup();"}]';
					buttons = eval(buttons);
					_confirm("warning","Warning","กรุณาระบุผู้รับผิดชอบ",buttons);
				}
				if($('#FEditDate').val()!=="" && $('#FFinishDate').val()=="")$('#FStatus').val('S');
				else if($('#FFinishDate').val()!='')$('#FStatus').val('F');
			}/*End if($('#user_type').val()=="AM")*/
		    if(is_process==1){
				$('.login-overlay').show();
				if(($('#FStatus').val()=="N" || $('#FStatus').val()=="A") && $('#user_type').val()!="E"){
					if($('#FStatus').val()=="N")$('#FStatus').val("S");
					if($('#FApprove').is(':checked')==true)$('#FStatus').val("A");
					else $('#FStatus').val("S");
				}else{
					if($('#FApprove').is(':checked')==true && $('#FReceiveDoc').val()=='N'){
						$('#FStatus').val("A");
					}
				}
				var params = getRequestBody();
				$.ajax({
					type: "POST",
					url: ("../../../controllers/request_controller.php"),
					data: "1&function=insert_data&"+params,
					dataType: 'json',
					success: function(data){
						$('#FRequestID').val(data['req_id']);
						$('#FReqNo').val(data['req_no']);
						$('.request_no').empty().html(data['req_no']);
						$('#attachment').show();
						$('.login-overlay').hide(); 
						//if($('#FEditDate').val()!="")$('#close-job').show();
					}
				});
		    }
		}
    }/*End of function checkData()*/
    
    if($('#FRequestID').val()!=""){
			var params = getRequestBody();
			$.ajax({
				type: "POST",
				url: ("../../../controllers/request_controller.php"),
				data: "1&function=get&"+params,
				dataType: 'json',
				success: function(json){
					  assignFields(json);
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
					  //if(json['FEditDate']!="")$('#close-job').show();
					  if(json['FStatus']=="A"){
						  $('#FApprove').attr('checked','checked');
						  $(function(){
							  $.uniform.update("#FApprove");
						  });
					  }
					  updateRepairItem(json['FRepairGroupItemID']);
					  $('.request_no').empty().html(json['FReqNo']);
					  $('#attachment').show();
					  $.ajax({
							type: "POST",
							url: ("../../../controllers/request_controller.php"),
							data: "1&function=list_attach&FRequestID="+$('#FRequestID').val(),
							dataType: 'json',
							success: function(json){
								for(var i=0;i<json.length;i++){
									var files = json[i];
									uploadComplete(files['FAttachID'],files['FAttachName'],files['FAttachLink']);
								}
							}
					  });
				}
			});
	}
</script>
</html>