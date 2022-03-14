<!DOCTYPE HTML">
<?php 
include '../../../lib/db_config.php';
include '../../../main/modules/Model_Utilities.php';
include '../../modules/claim_model.php';

$utilMD = new Model_Utilities();
$objMD = new Model_Claim();
$_id = $_REQUEST['id'];
$_no = $_REQUEST['no'];
$claimData = $objMD->get_data($_id);
$claimState = $objMD->get_claim_state($_id);

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=MS874">
<script  type="text/javascript" src="../../../jsLib/jquery-1.8.0.min.js"></script>
<script src="../../../jsLib/uniform/jquery.uniform.js" type="text/javascript" charset="utf-8"></script>
<script src="../../../jsLib/js_scripts/js_function.js" type="text/javascript" charset="utf-8"></script>
<link href="../../../css/dialog-box.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="../../../jsLib/uniform/css/uniform.default.css" type="text/css" media="screen">
<title>Insert title here</title>
<script type="text/javascript" charset="utf-8">
      $(function(){
        $("input, textarea, select").uniform();
      });
</script>
</head>
<body>
   <div class="dialog-panel" style="height:100%;">
   		<div class="top-row">
   			<div class="left"></div>
   			<div class="center">
   				<span class="dialog-title">ข้อมูลการส่งซ่อม /claim ขอใบแจ้งซ่อมเลขที่ : <?php print($_no);?></span>
   			</div>
   			<div class="right"></div>
   		</div> 
   		<div class="middle-row" style="height:100%;">
   			<div class="left"></div>
   			<div id="dialog-body" class="center">
   				<table width="100%" border="0">
   				   <tr>
   						<td style="width:25%"><b>เลขที่ทรัพย์สิน :</b></td>
   						<td colspan="5">&nbsp;<?php print($utilMD->convert2Thai($claimData['FAssetNo']));?></td>
   					</tr>
   					<tr>
   						<td style="width:25%"><b>รายการส่งซ่อม/claim :</b></td>
   						<td colspan="5">&nbsp;<?php print($utilMD->convert2Thai($claimData['FItems']));?></td>
   					</tr>
   					<tr>
   						<td><b>วันที่บันทึก :</b></td>
   						<td>&nbsp;<?php print($claimData['FDateRequest']);?></td>
   						<td style="width:19%"><b>วันที่ส่งซ่อม/claim :</b></td>
   						<td>&nbsp;<?php print($claimData['FSendDate']);?></td>
   						<td style="width:13%" ><b>วันที่รับคืน :</b></td>
   						<td>&nbsp;<?php print($claimData['FReciveDate']);?></td>
   					</tr>
   					<tr>
   						<td><b>Suplier :</b></td>
   						<td colspan="5">&nbsp;<?php print($utilMD->convert2Thai($claimData['FSuplierName']));?></td>
   					</tr>
   				</table>
   			</div>
   			<div class="right"></div>
   		</div>
   		<div class="bottom-row">
   			<div class="left"></div>
   			<div class="center">
   			    <ul class="request-state">
   			    	<?php if(!empty($claimState)){
   							foreach($claimState as $key=>$val){
   					?>
   								<?php if($val['numDay']>0){?>
   									<li class="arrow-state<?php print $val['type'];?>"><?php print $val['numDay'];?> day</li>
   								<?php }?>
			   			    	<li>
			   			    		<span><?php print $utilMD->convert2Thai($val['label']);?></span>
			   			    		<span><?php print $val['date'];?></span>
			   			    	</li>
   					<?php 
   							}
						 }
					?>
   			    </ul>
   			</div>
   			<div class="right"></div>
   		</div>
   </div>
</body>
<script>
function downloadFile(id,filename,url){
	var width = screen.width-10;
	var height = screen.height-60;
	newwindow=window.open('../../../download.php?name='+filename+'&reqId='+id+'&filename='+url,
								  'downloadWindow','width='+width+',height='+height+',left=0,top=0,screenX=0,screenY=0,status=no,menubar=yes,scrollbars=yes,copyhistory=yes, resizable=yes,fullscreen=no');
}
</script>
</html>