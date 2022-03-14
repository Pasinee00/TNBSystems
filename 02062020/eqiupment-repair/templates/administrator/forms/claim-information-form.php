<!DOCTYPE HTML">
<?php 
include '../../../../lib/db_config.php';
include '../../../../main/modules/Model_Utilities.php';
include '../../../modules/claim_model.php';

$utilMD = new Model_Utilities();
$objMD = new Model_Claim();
$_id = $_REQUEST['id'];
$_no = $_REQUEST['no'];
$_suplierList = $utilMD->get_suplierList();
$claimData = $objMD->get_data($_id);
$claimState = $objMD->get_claim_state($_id);

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=MS874">
<script  type="text/javascript" src="../../../../jsLib/jquery-1.8.0.min.js"></script>
<script src="../../../../jsLib/uniform/jquery.uniform.js" type="text/javascript" charset="utf-8"></script>
<script src="../../../../jsLib/js_scripts/js_function.js" type="text/javascript" charset="utf-8"></script>
<script src="../../../../jsLib/jquery-ui/js/jquery-ui-1.10.3.custom.js"></script>

<link href="../../../../css/dialog-box.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="../../../../jsLib/uniform/css/uniform.default.css" type="text/css" media="screen">
<link rel="stylesheet" href="../../../../jsLib/jquery-ui/css/flick/jquery-ui-1.10.3.custom.min.css" type="text/css" media="screen">
<title>Insert title here</title>
<script type="text/javascript" charset="utf-8">
      $(function(){
        $("input, textarea, select,button").uniform();
      });
</script>
</head>
<body>
<form>
   <div class="dialog-panel" style="height:100%;">
   		<div class="top-row">
   			<div class="left"></div>
   			<div class="center">
   				<span class="dialog-title">ข้อมูลการส่งซ่อม /claim ขอใบแจ้งซ่อมเลขที่ : <?php print($_no);?></span>
   				<input type="hidden" name="fields[FClaimStatus]" id="FClaimStatus" value="<?php print($claimData['FClaimStatus']);?>">
   				<input type="hidden" name="FClaimID" id="FClaimID" value="<?php print($claimData['FClaimID']);?>">
   			</div>
   			<div class="right"></div>
   		</div> 
   		<div class="middle-row" style="height:100%;">
   			<div class="left"></div>
   			<div id="dialog-body" class="center">
   				<table width="100%" border="0">
   				   <tr>
   						<td style="width:23%"><b>เลขที่ทรัพย์สิน :</b></td>
   						<td colspan="5">&nbsp;<?php print($utilMD->convert2Thai($claimData['FAssetNo']));?></td>
   					</tr>
   					<tr>
   						<td><b>รายการส่งซ่อม/claim :</b></td>
   						<td colspan="5">&nbsp;<?php print($utilMD->convert2Thai($claimData['FItems']));?></td>
   					</tr>
   					<tr>
   						<td><b>Suplier :</b></td>
   						<td colspan="5">&nbsp;
   							<select name="fields[FComClaimID]" id="FComClaimID" class="uniform-select" style="width:180px">
	   						  <option value="">---กรุณาเลือกบริษัท---</option>
	   						  <?php if(!empty($_suplierList)){ foreach ($_suplierList as $key=>$val){?>
	   						  <option value="<?php echo $val['FSuplierID'];?>" <?php if($val['FSuplierID']==$claimData['FComClaimID']){?> selected <?php }?>><?php echo $val['FSuplierName'];?></option>
	   						  <?php }}?>
						    </select>
   						</td>
   					</tr>
   					<tr>
   						<td><b>วันที่บันทึก :</b></td>
   						<td>&nbsp;<?php print($claimData['FDateRequest']);?></td>
   						<td style="width:19%"><b>วันที่ส่งซ่อม/claim :</b></td>
   						<td>&nbsp;<input type="text" name="fields[FSendDate]" id="FSendDate" style="width:100px;" value="<?php print($claimData['FSendDate']);?>"></td>
   						<td style="width:13%" ><b>วันที่รับคืน :</b></td>
   						<td>&nbsp;<input type="text" name="fields[FReciveDate]" id="FReciveDate" style="width:100px;" value="<?php print($claimData['FReciveDate']);?>"></td>
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
   			    			<?php if($claimData['FClaimStatus']!="BACK"){?>
   			    				<input type="button" value="บันทึก" onclick="javascript:saveData();">
   			    			<?php }?>
   			    		</td>
   			    	</tr>
   			    </table>
   			</div>
   			<div class="right"></div>
   		</div>
   </div>
 </form>
</body>
<script>
$(document).ready(function (){
	$( "#FSendDate" ).datepicker({dateFormat:"yy-mm-dd"});
	$( "#FReciveDate" ).datepicker({dateFormat:"yy-mm-dd"});
});
function saveData(){
	$('.warning').empty().hide();
	if($('#FClaimStatus').val()=='NEW'){
		if($('#FComClaimID').val()==""){
			$('.warning').empty().html('กรุณาระบุบริษัทที่ส่งซ่อม/claim').show();
			$('#FComClaimID').focus();
		}else if($('#FSendDate').val()==""){
			$('.warning').empty().html('กรุณาระบุวันที่ส่งซ่อม/claim').show();
		}else{
			$('#FClaimStatus').val('SEND');
			var params = getRequestBody();
			$.ajax({
				type: "POST",
				url: ("../../../controllers/claim_controller.php"),
				data: "1&function=insert_data&"+params,
				dataType: 'json',
				success: function(data){
				    parent.changePage();
					parent.closePopup(); 
				}
			});
		}
	}else if($('#FClaimStatus').val()=='SEND'){
		if($('#FReciveDate').val()==""){
			$('.warning').empty().html('กรุณาระบุวันที่รับคืน').show();
		}else{
			$('#FClaimStatus').val('BACK');
			var params = getRequestBody();
			$.ajax({
				type: "POST",
				url: ("../../../controllers/claim_controller.php"),
				data: "1&function=insert_data&"+params,
				dataType: 'json',
				success: function(data){
				    parent.changePage();
					parent.closePopup(); 
				}
			});
		}
	}
}/*End function saveData()*/
</script>
</html>