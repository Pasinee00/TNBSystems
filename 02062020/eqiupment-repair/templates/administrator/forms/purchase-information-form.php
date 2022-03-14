<!DOCTYPE HTML">
<?php 
include '../../../../lib/db_config.php';
include '../../../../main/modules/Model_Utilities.php';
include '../../../modules/purchase_model.php';

$utilMD = new Model_Utilities();
$objMD = new Model_Purchase();
$_id = $_REQUEST['id'];
$_no = $_REQUEST['no'];
$_suplierList = $utilMD->get_suplierList();
$purchaseData = $objMD->get_data($_id);
$purchaseState = $objMD->get_claim_state($_id);

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
        $("input, textarea, select").uniform();
      });
</script>
</head>
<body>
<form>
   <div class="dialog-panel" style="height:100%;">
   		<div class="top-row">
   			<div class="left"></div>
   			<div class="center">
   				<span class="dialog-title">ข้อมูลการสั่งซื้อ ขอใบแจ้งซ่อมเลขที่ : <?php print($_no);?></span>
   				<input type="hidden" name="fields[FPurchaseStatus]" id="FPurchaseStatus" value="<?php print($purchaseData['FPurchaseStatus']);?>">
   				<input type="hidden" name="FPurchaseID" id="FPurchaseID" value="<?php print($purchaseData['FPurchaseID']);?>">
   				<input type="hidden" name="fields[FRequestID]" id="FRequestID" value="<?php print($purchaseData['FRequestID']);?>">
   			</div>
   			<div class="right"></div>
   		</div> 
   		<div class="middle-row" style="height:100%;">
   			<div class="left"></div>
   			<div id="dialog-body" class="center">
   				<table width="100%" border="0">
   				   <tr>
   						<td style="width:18%"><b>เลขที่ทรัพย์สิน :</b></td>
   						<td colspan="3">&nbsp;<?php print($utilMD->convert2Thai($purchaseData['FAssetNo']));?></td>
   					</tr>
   					<tr>
   						<td><b>ผู้แจ้งซ่อม :</b></td>
   						<td colspan="3">&nbsp;<?php print($utilMD->convert2Thai($purchaseData['emp_name']));?></td>
   					</tr>
   					<tr>
   						<td><b>แผนก :</b></td>
   						<td>&nbsp;<?php print($utilMD->convert2Thai($purchaseData['sec_nameThai']));?></td>
   						<td><b>สาขา :</b></td>
   						<td>&nbsp;<?php print($utilMD->convert2Thai($purchaseData['brn_name']));?></td>
   					</tr>
   					<tr>
   						<td valign="top"><b>รายการ :</b></td>
   						<td valign="top" height="95" colspan="3">&nbsp;<?php print($utilMD->convert2Thai($purchaseData['FItems']));?></td>
   						
   					</tr>
   					<tr>
   						<td><b>จำนวนที่สั่ง :</b></td>
   						<td><input type="text" name="fields[FAmount]" id="FAmount" style="width:100px;text-align:center;"" onkeyup="javascript:changNumeric(this);calTotalPrice();" value="<?php print($utilMD->convert2Thai($purchaseData['FAmount']));?>"></td>
   						<td><b>หน่วยนับ :</b></td>
   						<td><input type="text" name="fields[FUnit]" id="FUnit" style="width:100px;text-align:center;" value="<?php print($utilMD->convert2Thai($purchaseData['FUnit']));?>">
   						   (ตัวอย่าง -  เส้น,เมตร ฯลฯ)
   						</td>
   					</tr>
   					<tr>
   						<td><b>ราคาต่อชิ้น :</b></td>
   						<td><input type="text" name="fields[FPricePerAmount]" id="FPricePerAmount" style="width:100px;text-align:right;"" onkeyup="javascript:changNumeric(this);calTotalPrice();" value="<?php print($utilMD->convert2Thai($purchaseData['FPricePerAmount']));?>"></td>
   						<td><b>ราคารวม :</b></td>
   						<td><input type="text" name="fields[FPrice]" id="FPrice" style="width:100px;text-align:right;" value="<?php print($utilMD->convert2Thai($purchaseData['FPrice']));?>"></td>
   					</tr>
   					<tr>
   						<td><b>Suplier :</b></td>
   						<td>
   							<select name="fields[FComClaimID]" id="FComClaimID" class="uniform-select" style="width:180px">
	   						  <option value="">---กรุณาเลือกบริษัท---</option>
	   						  <?php if(!empty($_suplierList)){ foreach ($_suplierList as $key=>$val){?>
	   						  <option value="<?php echo $val['FSuplierID'];?>" <?php if($val['FSuplierID']==$purchaseData['FComClaimID']){?> selected <?php }?>><?php echo $val['FSuplierName'];?></option>
	   						  <?php }}?>
						    </select>
   						</td>
   						<td><b>วันที่บันทึก :</b></td>
   						<td>&nbsp;<?php print($purchaseData['FDateRequest']);?></td>
   					</tr>
   					<tr>
   						<td><b>วันที่สั่งซื้อ :</b></td>
   						<td>&nbsp;<input type="text" name="fields[FBuyDate]" id="FBuyDate" style="width:100px;" value="<?php print($purchaseData['FBuyDate']);?>"></td>
   						<td style="width:13%" ><b>วันที่รับของ :</b></td>
   						<td>&nbsp;<input type="text" name="fields[FReciveDate]" id="FReciveDate" style="width:100px;" value="<?php print($purchaseData['FReciveDate']);?>"></td>
   					</tr>
   						<tr>
   						<td><b>เลขที่ PR :</b></td>
   						<td>&nbsp;<input type="text" name="fields[FPRNo]" id="FPRNo" style="width:100px;" value="<?php print($purchaseData['FPRNo']);?>"></td>
   						<td><b>วันที่ออก PR :</b></td>
   						<td>&nbsp;<input type="text" name="fields[FPRDate]" id="FPRDate" style="width:100px;" value="<?php print($purchaseData['FPRDate']);?>"></td>
   					</tr>
   					<tr>
   						<td><b>เลขที่ PO :</b></td>
   						<td>&nbsp;<input type="text" name="fields[FPONo]" id="FPONo" style="width:100px;" value="<?php print($purchaseData['FPONo']);?>"></td>
   						<td><b>วันที่ออก PO :</b></td>
   						<td>&nbsp;<input type="text" name="fields[FPODate]" id="FPODate" style="width:100px;" value="<?php print($purchaseData['FPODate']);?>"></td>
   					</tr>
   					<tr>
   						<td><b>กำหนดวันของเข้า :</b></td>
   						<td>&nbsp;<input type="text" name="fields[FDueDate]" id="FDueDate" style="width:100px;" value="<?php print($purchaseData['FDueDate']);?>"></td>
   						<td style="width:13%" ></td>
   						<td>&nbsp;</td>
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
   			    				<input type="button" value="บันทึก" onclick="javascript:saveData();">
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
	$( "#FBuyDate" ).datepicker({dateFormat:"yy-mm-dd"});
	$( "#FReciveDate" ).datepicker({dateFormat:"yy-mm-dd"});
	$( "#FPRDate" ).datepicker({dateFormat:"yy-mm-dd"});
	$( "#FPODate" ).datepicker({dateFormat:"yy-mm-dd"});
	$( "#FDueDate" ).datepicker({dateFormat:"yy-mm-dd"});
});
function calTotalPrice(){
	var amount = $('#FAmount').val()*1;
	var price = $('#FPricePerAmount').val()*1;
	var totalPrice = amount*price;
	$('#FPrice').val(totalPrice.toFixed(2));
}
function saveData(){
	$('.warning').empty().hide();
	if($('#FBuyDate').val()!="" && $('#FReciveDate').val()=="")$('#FPurchaseStatus').val('PUR');
	else if($('#FReciveDate').val()!="")$('#FPurchaseStatus').val('BUY');
	var params = getRequestBody();
	$.ajax({
		type: "POST",
		url: ("../../../controllers/purchase_controller.php"),
		data: "1&function=insert_data&"+params,
		dataType: 'json',
		success: function(data){
		    parent.changePage();
			parent.closePopup(); 
		}
	});
}/*End function saveData()*/
</script>
</html>