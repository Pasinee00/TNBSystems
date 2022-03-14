<?php
include '../../../lib/db_config.php';
include '../../../main/modules/Model_Utilities.php';
include '../../../general_sys/modules/suplier_model.php';

$utilMD = new Model_Utilities();
$supMD = new Model_Suplier();
$_type = array("SR"=>"ส่งซ่อม","SC"=>"ส่ง Claim");
$FSectionID = $_REQUEST['sec_id'];
$FBranchID = $_REQUEST['brn_id'];
$FRepair_comp_id = $_REQUEST['FRepair_comp_id'];
$SRequestDate = $_REQUEST['SRequestDate'];
$ERequestDate = $_REQUEST['ERequestDate'];
$SSendDate = $_REQUEST['SSendDate'];
$ESendDate = $_REQUEST['ESendDate'];
$SReceiveDate = $_REQUEST['SReceiveDate'];
$EReceiveDate = $_REQUEST['EReceiveDate'];
$FComClaimID = $_REQUEST['FComClaimID'];
$FType_SR = $_REQUEST['FType_SR'];
$FType_SC = $_REQUEST['FType_SC'];
$sect_data = $utilMD->getSectById($FSectionID);
$brn_data = $utilMD->get_BranchById($FBranchID);
$sup_data = $supMD->get_data($FComClaimID);
function get_comThai($comp_id){
		$select_sql = "SELECT
									pis_db.tbl_company.comp_id,
									pis_db.tbl_company.comp_code,
									pis_db.tbl_company.comp_name
									FROM
									pis_db.tbl_company
									WHERE
									pis_db.tbl_company.comp_id = '".$comp_id."' ";
			$select_rst = mysql_query($select_sql);
			$select_row=mysql_fetch_assoc($select_rst);
			return $select_row[comp_name];
}

$title = "";
if($FSectionID)$title = "แผนก : ".$sect_data['sec_nameThai'];
if($FBranchID)$title .=(empty($title))?"สาขา : ".$brn_data['brn_name']:"&nbsp;&nbsp;สาขา : ".$brn_data['brn_name'];
if($SRequestDate) $title .=(empty($title))?"วันที่แจ้งตั้งแต่วันที่ : ".$utilMD->convertDate2Thai($SRequestDate,"dd-sm"):"&nbsp;&nbsp;วันที่แจ้งตั้งแต่วันที่ : ".$utilMD->convertDate2Thai($SRequestDate,"dd-sm");
if($ERequestDate){
	 if(empty($SRequestDate))$title .=(empty($title))?"วันที่แจ้งถึงวันที่ : ".$utilMD->convertDate2Thai($ERequestDate,"dd-sm"):"&nbsp;&nbsp;วันที่แจ้งถึงวันที่ : ".$utilMD->convertDate2Thai($ERequestDate,"dd-sm");
	 else  $title .=" - ".$utilMD->convertDate2Thai($ERequestDate,"dd-sm");
}

if($SSendDate) $title .=(empty($title))?"วันที่ส่งตั้งแต่วันที่ : ".$utilMD->convertDate2Thai($SSendDate,"dd-sm"):"&nbsp;&nbsp;วันที่ส่งตั้งแต่วันที่ : ".$utilMD->convertDate2Thai($SSendDate,"dd-sm");
if($ESendDate){
	 if(empty($SSendDate))$title .=(empty($title))?"วันที่ส่งถึงวันที่ : ".$utilMD->convertDate2Thai($ESendDate,"dd-sm"):"&nbsp;&nbsp;วันที่ส่งถึงวันที่ : ".$utilMD->convertDate2Thai($ESendDate,"dd-sm");
	 else  $title .=" - ".$utilMD->convertDate2Thai($ESendDate,"dd-sm");
}

if($SReceiveDate) $title .=(empty($title))?"วันที่รับคืนตั้งแต่วันที่ : ".$utilMD->convertDate2Thai($SReceiveDate,"dd-sm"):"&nbsp;&nbsp;วันที่รับคืนตั้งแต่วันที่ : ".$utilMD->convertDate2Thai($SReceiveDate,"dd-sm");
if($EReceiveDate){
	 if(empty($SReceiveDate))$title .=(empty($title))?"วันที่รับคืนถึงวันที่ : ".$utilMD->convertDate2Thai($EReceiveDate,"dd-sm"):"&nbsp;&nbsp;วันที่รับคืนถึงวันที่ : ".$utilMD->convertDate2Thai($EReceiveDate,"dd-sm");
	 else  $title .=" - ".$utilMD->convertDate2Thai($EReceiveDate,"dd-sm");
}
if($FComClaimID)$title .=(empty($title))?" Suplier :".$utilMD->convert2Thai($sup_data['FSuplierName']):"&nbsp;&nbsp;Suplier :".$utilMD->convert2Thai($sup_data['FSuplierName']);

$query = "SELECT t1.* "
		.",t2.FReqNo "
		.",t3.sec_nameThai "
		.",t4.brn_name "
		."FROM mtrequest_db.tbl_claim t1 "
		."LEFT JOIN mtrequest_db.tbl_request t2 ON(t2.FRequestID = t1.FRequestID) "
		."LEFT JOIN pis_db.tbl_section t3 ON(t3.sec_id = t2.FSectionID) "
		."LEFT JOIN pis_db.tbl_branch t4 ON(t4.brn_id = t2.FBranchID) "
		."WHERE 1";
if($FSectionID)$query .=" AND t2.FSectionID='{$FSectionID}'";
if($FBranchID)$query .=" AND t2.FBranchID='{$FBranchID}'";
//if($FRepair_comp_id)$query .=" AND t2.FRepair_comp_id='{$FRepair_comp_id}'";
if($FComClaimID)$query .=" AND t1.FComClaimID='{$FComClaimID}'";
if($SRequestDate)$query .=" AND t1.FDateRequest>='{$SRequestDate}'";
if($ERequestDate)$query .=" AND t1.FDateRequest<='{$ERequestDate}'";
if($SSendDate)$query .=" AND t1.FSendDate>='{$SSendDate}'";
if($ESendDate)$query .=" AND t1.FSendDate<='{$ESendDate}'";
if($SReceiveDate)$query .=" AND t1.FReciveDate>='{$SReceiveDate}'";
if($EReceiveDate)$query .=" AND t1.FReciveDate<='{$EReceiveDate}'";
if($FType_SC)$query .=" AND t1.FType='{$FType_SC}'";
if($FType_SR)$query .=" AND t1.FType='{$FType_SR}'";
$query .=" ORDER BY t2.FReqNo";

$results = mysql_query($query);
$rowPerPage = 20;
$numRows = mysql_num_rows($results);
$totalPage = ceil($numRows/$rowPerPage);
while($row=mysql_fetch_object($results)){
	if($_reqNo!=$row->FReqNo){
		$item=0;
		$_reqNo=$row->FReqNo;
	}
	$records[$row->FRequestID]['FReqNo'] = $row->FReqNo;
	$records[$row->FRequestID]['sec_nameThai'] = $row->sec_nameThai;
	$records[$row->FRequestID]['brn_name']= $row->brn_name;
	$records[$row->FRequestID]['claims'][$item]['FItems'] = $row->FItems; 
	$records[$row->FRequestID]['claims'][$item]['FDateRequest'] = $row->FDateRequest;
	$records[$row->FRequestID]['claims'][$item]['FSendDate'] = $row->FSendDate;
	$records[$row->FRequestID]['claims'][$item]['FReciveDate'] = $row->FReciveDate;
	$records[$row->FRequestID]['claims'][$item]['FType'] = $row->FType;
	$item++;
}

$header ='<table width="100%" border="0" cellspacing="0" cellpadding="0">';
$header.='	<tr>';
$header.='	  <td colspan="2" align="center"><b>รายงานการส่งซ่อม / ส่ง claim</b></td>';
$header.='	</tr>';
$header.='	<tr>';
$header.='    <td width="91%">'.get_comThai($FRepair_comp_id).'</td>';
$header.='    <td width="9%" align="right"><b>Page :</b> ##PAGE##/'.$totalPage.'&nbsp;</td>';
$header.='  </tr>';
$header.='  <tr>';
$header.='    <td colspan="2">&nbsp;'.$title.'</td>';
$header.='  </tr>';
$header.='  <tr>';
$header.='    <td height="4" colspan="2"></td>';
$header.='  </tr>';
$header.='  <tr>';
$header.='    <td colspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="0">';
$header.='      <tr>';
$header.='        <td width="6%" align="center" class="tlb_bg"><b>ลำดับ</b></td>';
$header.='        <td width="8%" align="center" class="tlb_bg"><b>Req No.</b></td>';
$header.='        <td width="18%" class="tlb_bg"><b>แผนกที่แจ้ง</b></td>';
$header.='        <td width="10%" class="tlb_bg"><b>สาขาที่แจ้ง</b></td>';
$header.='        <td width="25%" class="tlb_bg"><b>รายการที่ส่งซ่อม / Claim</b></td>';
$header.='        <td width="8%" align="center" class="tlb_bg"><b>ประเภท</b></td>';
$header.='        <td width="9%" align="center" class="tlb_bg"><b>วันที่แจ้ง</b></td>';
$header.='        <td width="8%" align="center" class="tlb_bg"><b>วันที่ส่ง</b></td>';
$header.='        <td width="8%" align="center" class="tlbr_bg"><b>วันที่รับ</b></td>';
$header.='      </tr>';

$tr_item ='<tr>';
$tr_item.='	<td align="center" class="##class##">&nbsp;##i##</td>';
$tr_item.='	<td align="center" class="##class##">&nbsp;##FReqNo##</td>';
$tr_item.='	<td class="##class##">&nbsp;##sec_nameThai##</td>';
$tr_item.='	<td class="##class##">&nbsp;##brn_name##</td>';
$tr_item.='	<td class="lb">&nbsp;##item##. ##FItems##</td>';
$tr_item.='	<td align="center" class="lb">&nbsp;##FType##</td>';
$tr_item.='	<td align="center" class="lb">&nbsp;##FDateRequest##</td>';
$tr_item.='	<td align="center" class="lb">&nbsp;##FSendDate##</td>';
$tr_item.='	<td align="center" class="lrb">&nbsp;##FReciveDate##</td>';
$tr_item.='</tr>';

$footer ='</table></td>';
$footer.='  </tr>';
$footer.='</table>';
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-874">
<title>รายงานการส่งซ่อม / ส่ง claim</title>

<link href="../../../css/stylesheet_report.css" rel="stylesheet" type="text/css">
</head>

<body>
     <?php 
        $page = 1;
     	$i = 0;
     	$item = 0;
     	$numRec = 0;
     	if(!empty($records)){
	     	foreach($records as $key=>$row){
				 $item++;
				 $i++;
				 if($item==1){
				 	print(str_replace("##PAGE##",$page,$header));
				 	$page++;
				 }
				 if(count($row['claims'])==1)$class = "lb";
				 else $class = "l";
 
				 $_tr_item = str_replace("##class##",$class,$tr_item);
				 $_tr_item = str_replace("##i##",$i,$_tr_item);
				 $_tr_item = str_replace("##FReqNo##",$row['FReqNo'],$_tr_item);
				 $_tr_item = str_replace("##sec_nameThai##",$row['sec_nameThai'],$_tr_item);
				 $_tr_item = str_replace("##brn_name##",$row['brn_name'],$_tr_item);
				 $_tr_item = str_replace("##item##",1,$_tr_item);
				 $_tr_item = str_replace("##FItems##",$row['claims'][0]['FItems'],$_tr_item);
				 $_tr_item = str_replace("##FType##",$_type[$row['claims'][0]['FType']],$_tr_item);
				 $_tr_item = str_replace("##FDateRequest##",$utilMD->convertDate2Thai($row['claims'][0]['FDateRequest'],"dd-sm"),$_tr_item);
				 $_tr_item = str_replace("##FSendDate##",$utilMD->convertDate2Thai($row['claims'][0]['FSendDate'],"dd-sm"),$_tr_item);
				 $_tr_item = str_replace("##FReciveDate##",$utilMD->convertDate2Thai($row['claims'][0]['FReciveDate'],"dd-sm"),$_tr_item);
				 print ($_tr_item);
				 for($index=1;$index<count($row['claims']);$index++){
					$item++;
					if($index==(count($row['claims'])-1))$class="lb";
				 	else $class="l";
				 	if($item==1){
				 		print(str_replace("##PAGE##",$page,$header));
				 		$page++;
				 	}
				 	if($item==$rowPerPage){
				 		$class="lb";
				 	}
				 	if($item==1){
						$_reqNo = $row['FReqNo'];
						$_sec_nameThai = $row['sec_nameThai'];
						$_brn_name = $row['brn_name'];
				 	}else{
						$_reqNo = "";
						$_sec_nameThai = "";
						$_brn_name = "";
				 	}
				 	
					$_tr_item = str_replace("##class##",$class,$tr_item);
					$_tr_item = str_replace("##i##","",$_tr_item);
					$_tr_item = str_replace("##FReqNo##",$_reqNo,$_tr_item);
					$_tr_item = str_replace("##sec_nameThai##",$_sec_nameThai,$_tr_item);
					$_tr_item = str_replace("##brn_name##",$_brn_name,$_tr_item);
					$_tr_item = str_replace("##item##",($index+1),$_tr_item);
					$_tr_item = str_replace("##FItems##",$row['claims'][$index]['FItems'],$_tr_item);
					$_tr_item = str_replace("##FType##",$_type[$row['claims'][$index]['FType']],$_tr_item);
					$_tr_item = str_replace("##FDateRequest##",$utilMD->convertDate2Thai($row['claims'][$index]['FDateRequest'],"dd-sm"),$_tr_item);
					$_tr_item = str_replace("##FSendDate##",$utilMD->convertDate2Thai($row['claims'][$index]['FSendDate'],"dd-sm"),$_tr_item);
					$_tr_item = str_replace("##FReciveDate##",$utilMD->convertDate2Thai($row['claims'][$index]['FReciveDate'],"dd-sm"),$_tr_item);
					print ($_tr_item);
					if($item==$rowPerPage){
						print($footer);
						if($page<=$totalPage)print("<BR style=\"page-break-after: always;\">");
						$item=0;
					}
				 }
				 if($item==$rowPerPage){
				 	print($footer);
				 	if($page<$totalPage)print("<BR style=\"page-break-after: always;\">");
				 	$item=0;
				 }
     		}/*End of foreach($_array as $key=>$val)*/
     		if($item<$rowPerPage){
     			print($footer);
     		}
      	  }/*End of if(!empty($_array))*/
      ?>
</body>
</html>