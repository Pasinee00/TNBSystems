<!DOCTYPE HTML">
<?php 
include '../../../lib/db_config.php';
include '../../../main/modules/Model_Utilities.php';
include '../../modules/purchase_model.php';

$utilMD = new Model_Utilities();
$objMD = new Model_Purchase();
$comList = $utilMD->get_CompList();
$brnList = $utilMD->get_BranchList($comList);
$sectLst = $utilMD->get_SectList($comList, '');
$_suplierList = $utilMD->get_suplierList();

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=MS874">
<script  type="text/javascript" src="../../../jsLib/jquery-1.8.0.min.js"></script>
<script src="../../../jsLib/uniform/jquery.uniform.js" type="text/javascript" charset="utf-8"></script>
<script src="../../../jsLib/js_scripts/js_function.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript" src="../../../jsLib/datepicker/zebra_datepicker.js"></script>

<link href="../../../css/dialog-box.css" rel="stylesheet" type="text/css">
<link href="../../../css/input.css" rel="stylesheet" type="text/css">
<link href="../../../css/stylesheet_report.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="../../../jsLib/uniform/css/uniform.default.css" type="text/css" media="screen">
<link href="../../../jsLib/datepicker/css/default.css" rel="stylesheet" type="text/css" />
<title>Insert title here</title>
<script type="text/javascript" charset="utf-8">
      $(function(){
        $("input, textarea, select").uniform();
      });
</script>
</head>
<body>
   <div class="dialog-panel condition-panel">
   		<div class="top-row">
   			<div class="left"></div>
   			<div class="center">
   				<span class="dialog-title">รายงานการสั่งซื้อ</span>
   			</div>
   			<div class="right"></div>
   		</div> 
   		<div class="middle-row" style="height:100%;">
   			<div class="left"></div>
   			<div id="dialog-body" class="center">
   				<table width="100%" border="0">
                	<tr>
   							<td style="width:15%"><b>บริษัท :</b></td>
   							<td colspan="5"><select name="fields[FRepair_comp_id]" id="FRepair_comp_id" class="uniform-select" onChange="javascript:updateBranch('');javascript:updateSection('');">
             						<option value="">---ทั้งหมด---</option>
             						<?php if(!empty($comList)){ foreach ($comList as $key=>$val){?>
             						<option value="<?php echo $val['comp_id'];?>" ><?php echo $val['comp_code']." - ".$val['comp_name'];?></option>
             						<?php }}?>
        						</select></td>
   				        </tr>
   				   <tr>
   						<td style="width:15%"><b>สาขา :</b></td>
   						<td colspan="5"><select name="brn_id" id="brn_id" class="uniform-select">
						  <option value="">---ทั้งหมด---</option>
   							  <?php if(!empty($brnList)){
				            			foreach($brnList as $key=>$val){
				            	?>
   							  <option value="<?php print($val['brn_id']);?>" <?php if($val['brn_id']== $userInfo['brn_id']){?>selected<?php }?>><?php print($val['brn_name']);?></option>
   							  <?php }}?>
						  </select>
   						</td>
   					</tr>
   					 <tr>
   						<td><span style="width:15%"><b>แผนก :</b></span></td>
   						<td colspan="5"><select name="sec_id" id="sec_id" class="uniform-select">
   						  <option value="">---ทั้งหมด---</option>
   						  <?php if(!empty($sectList)){
				            			foreach($sectList as $key=>$val){
				            	?>
   						  <option value="<?php print($val['sec_id']);?>"><?php print($val['sec_nameThai']);?></option>
   						  <?php }}?>
					    </select></td>
   					</tr>
   					<tr>
   						<td><b>วันที่แจ้ง :</b></td>
   						<td colspan="2" style="width:35%">
   							<input type="text" name="SRequestDate" id="SRequestDate" style="width:80%;" value="">
   							-
   							<input type="text" name="ERequestDate" id="ERequestDate" style="width:80%;" value="">
   						</td>
   						<td style="width:15%"><b>วันที่สั่งซื้อ :</b></td>
   						<td colspan="2" style="width:35%">
   							<input type="text" name="SBuyDate" id="SBuyDate" style="width:80%;" value="">
   							-
   							<input type="text" name="EBuyDate" id="EBuyDate" style="width:80%;" value="">
   						</td>
   					</tr>
   					<tr>
   						<td><b>วันที่รับของ :</b></td>
   						<td colspan="4">
   							<input type="text" name="SReceiveDate" id="SReceiveDate" style="width:80%;" value="">
   							-
   							<input type="text" name="EReceiveDate" id="EReceiveDate" style="width:80%;" value="">
   						</td>
   					</tr>
   					<tr>
   						<td><b>Suplier :</b></td>
   						<td colspan="5">
   							<select class="uniform-select" name="FComClaimID" id="FComClaimID">
   							  <option value="">---ทั้งหมด---</option>
	   						  <?php if(!empty($_suplierList)){ foreach ($_suplierList as $key=>$val){?>
	   						  	<option value="<?php echo $val['FSuplierID'];?>" <?php if($val['FSuplierID']==$claimData['FComClaimID']){?> selected <?php }?>><?php echo $val['FSuplierName'];?></option>
	   						  <?php }}?>
   							</select>
   						</td>
   					</tr>
   				</table>
   			</div>
   			<div class="right"></div>
   		</div>
   		<div class="bottom-row">
   			<div class="left"></div>
   			<div class="center">
   				<table width="100%">
   			    	<tr>
   			    		<td width="50%" align="left">
   			    			<div class="warning"></div>
   			    		</td>
   			    		<td align="right">
   			    				<a class="button-bule" href="javascript:void(0);" onclick="javascript:newWindow();"> ออกรายงาน  </a>
   			    		</td>
   			    	</tr>
   			    </table>
   			</div>
   			<div class="right"></div>
   		</div>
   </div>
</body>
<script>
$(document).ready(function() {
	 var doc_height = $(document).height();
	 var condition_height = $('.condition-panel').height();
	 var log_top = (doc_height-condition_height)/2;
	 
	 $('.condition-panel').css('top',log_top+"px");

	 $('#SRequestDate').Zebra_DatePicker();
	 $('#ERequestDate').Zebra_DatePicker();
	 $('#SBuyDate').Zebra_DatePicker();
	 $('#EBuyDate').Zebra_DatePicker();
	 $('#SReceiveDate').Zebra_DatePicker();
	 $('#EReceiveDate').Zebra_DatePicker();
	 $('.selector').css('width','80%');
	 $('.selector > span').css('width','80%');
	 $('.uniform-select').css('width','85%');
});
function newWindow(){
	var width = screen.width-10;
	var height = screen.height-60;
	var params = "";
	params +="sec_id="+$('#sec_id').val();
	params +="&brn_id="+$('#brn_id').val();
    params +="&FRepair_comp_id="+$('#FRepair_comp_id').val();
	params +="&SRequestDate="+$('#SRequestDate').val();
	params +="&ERequestDate="+$('#ERequestDate').val();
	params +="&SBuyDate="+$('#SBuyDate').val();
	params +="&EBuyDate="+$('#EBuyDate').val();
	params +="&SReceiveDate="+$('#SReceiveDate').val();
	params +="&EReceiveDate="+$('#EReceiveDate').val();
	params +="&FComClaimID="+$('#FComClaimID').val();
	newwindow=window.open('request-purchase-report.page.tpl.php?'+params,
								  'reportWindow'+Math.random()*10000,'width='+width+',height='+height+',left=0,top=0,screenX=0,screenY=0,status=no,menubar=yes,scrollbars=yes,copyhistory=yes, resizable=yes,fullscreen=no');
}
	function updateBranch(selected){
			$.ajax({
					type: "POST",
					url: ("../../../main/controllers/utilities_controller.php"),
					data: "1&function=get_BranchJson&comp_id="+$('#FRepair_comp_id').val(),
					dataType: 'json',
					success: function(data){
							appendOption(document.getElementById('brn_id'),data);
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