<?php
include '../../../lib/db_config.php';
include '../../../main/modules/Model_Utilities.php';
ini_set('max_execution_time',60000);
set_time_limit (60000);
$utilMD = new Model_Utilities();
$FSectionID = $_REQUEST['sec_id'];
$FBranchID = $_REQUEST['brn_id'];
$SRequestDate = $_REQUEST['SRequestDate'];
$ERequestDate = $_REQUEST['ERequestDate'];
$SDueDate = $_REQUEST['SDueDate'];
$EDueDate = $_REQUEST['EDueDate'];
$SFinishDate = $_REQUEST['SFinishDate'];
$EFinishDate = $_REQUEST['EFinishDate'];
$FStatus = $_REQUEST['FStatus'];
$sect_data = $utilMD->getSectById($FSectionID);
$brn_data = $utilMD->get_BranchById($FBranchID);

$title = "";
if($FSectionID)$title = "แผนก : ".$sect_data['sec_nameThai'];
if($FBranchID)$title .=(empty($title))?"สาขา : ".$brn_data['brn_name']:"&nbsp;&nbsp;สาขา : ".$brn_data['brn_name'];
if($SRequestDate) $title .=(empty($title))?"วันที่แจ้งตั้งแต่วันที่ : ".$utilMD->convertDate2Thai($SRequestDate,"dd-sm"):"&nbsp;&nbsp;วันที่แจ้งตั้งแต่วันที่ : ".$utilMD->convertDate2Thai($SRequestDate,"dd-sm");
if($ERequestDate){
	 if(empty($SRequestDate))$title .=(empty($title))?"วันที่แจ้งถึงวันที่ : ".$utilMD->convertDate2Thai($ERequestDate,"dd-sm"):"&nbsp;&nbsp;วันที่แจ้งถึงวันที่ : ".$utilMD->convertDate2Thai($ERequestDate,"dd-sm");
	 else  $title .=" - ".$utilMD->convertDate2Thai($ERequestDate,"dd-sm");
}

if($SDueDate) $title .=(empty($title))?"วันที่กำหนดเสร็จตั้งแต่วันที่ : ".$utilMD->convertDate2Thai($SDueDate,"dd-sm"):"&nbsp;&nbsp;วันที่กำหนดเสร็จตั้งแต่วันที่ : ".$utilMD->convertDate2Thai($SDueDate,"dd-sm");
if($EDueDate){
	 if(empty($SDueDate))$title .=(empty($title))?"วันที่กำหนดเสร็จถึงวันที่ : ".$utilMD->convertDate2Thai($EDueDate,"dd-sm"):"&nbsp;&nbsp;วันที่กำหนดเสร็จถึงวันที่ : ".$utilMD->convertDate2Thai($EDueDate,"dd-sm");
	 else  $title .=" - ".$utilMD->convertDate2Thai($EDueDate,"dd-sm");
}

if($SFinishDate) $title .=(empty($title))?"วันที่เสร็จตั้งแต่วันที่ : ".$utilMD->convertDate2Thai($SFinishDate,"dd-sm"):"&nbsp;&nbsp;วันที่เสร็จตั้งแต่วันที่ : ".$utilMD->convertDate2Thai($SFinishDate,"dd-sm");
if($EFinishDate){
	 if(empty($SFinishDate))$title .=(empty($title))?"วันที่เสร็จถึงวันที่ : ".$utilMD->convertDate2Thai($EFinishDate,"dd-sm"):"&nbsp;&nbsp;วันที่เสร็จถึงวันที่ : ".$utilMD->convertDate2Thai($EFinishDate,"dd-sm");
	 else  $title .=" - ".$utilMD->convertDate2Thai($EFinishDate,"dd-sm");
}

$query ="SELECT t1.FRequestID,t1.FReqNo,t1.FDueDate,t1.FFinishDate "
				 .",t2.brn_code "
		         .",t3.sec_nameThai "
				 .",t4.FReqCostDetail,t4.FReqCost,t4.FReqCostType "
				 ."FROM mtrequest_db.tbl_request t1 "
				 ."LEFT JOIN pis_db.tbl_branch t2 ON(t2.brn_id = t1.FBranchID) "
				 ."LEFT JOIN pis_db.tbl_section t3 ON(t3.sec_id = t1.FSectionID) "
				 ."LEFT JOIN mtrequest_db.tbl_requestcost t4 ON(t4.FRequestID = t1.FRequestID) "
				 ."WHERE t4.FReqCost>0 ";
if($FSectionID)$query .=" AND t1.FSectionID='{$FSectionID}'";
if($FBranchID)$query .=" AND t1.FBranchID='{$FBranchID}'";
if($SRequestDate)$query .=" AND t1.FReqDate>='{$SRequestDate}'";
if($ERequestDate)$query .=" AND t1.FReqDate<='{$ERequestDate}'";
if($SDueDate)$query .=" AND t1.FDueDate>='{$SDueDate}'";
if($EDueDate)$query .=" AND t1.FDueDate<='{$EDueDate}'";
if($SFinishDate)$query .=" AND t1.FFinishDate>='{$SFinishDate}'";
if($EFinishDate)$query .=" AND t1.FFinishDate<='{$EFinishDate}'";
if($FStatus)$query .=" AND t1.FStatus='{$FStatus}'";
 $query .=" ORDER BY t1.FReqNo,t4.FReqCostID";
$results = mysql_query($query);
$rowPerPage = 20;
$numRows = mysql_num_rows($results);
$totalPage = ceil($numRows/$rowPerPage);
while($row=mysql_fetch_object($results)){
	if($_reqNo!=$row->FReqNo){
		$item=0;
		$_reqNo=$row->FReqNo;
	}
	$_label =($row->FReqCostType=="L")?"ค่าอะไหล่":$row->FReqCostDetail;
	$records[$row->FRequestID]['FReqNo'] = $row->FReqNo;
	$records[$row->FRequestID]['brn_code'] = $row->brn_code;
	$records[$row->FRequestID]['sec_nameThai']= $row->sec_nameThai;
	$records[$row->FRequestID]['FFinishDate']= $utilMD->convertDate2Thai($row->FFinishDate,"dd-sm");
	if($item==0){
		$records[$row->FRequestID]['FDetail']= $_label;
		$records[$row->FRequestID]['FCost']= number_format($row->FReqCost,2,".",",");
	}else{
		$records[$row->FRequestID]['details'][$item-1]['FDetail'] = $_label;
		$records[$row->FRequestID]['details'][$item-1]['FCost']= number_format($row->FReqCost,2,".",",");
		$totalRow++;
	}
	$SUM =$SUM+$row->FReqCost;
	$item++;
}
$totalRow += count($records);
$header ='<table width="99%" align="center"  border="0" cellspacing="0" cellpadding="0">';
$header.='	<tr>';
$header.='	  <td colspan="2" align="center"><b>รายงานสรุปค่าใช้จ่ายในการซ่อม</b></td>';
$header.='	</tr>';
$header.='	<tr>';
$header.='    <td width="91%">บริษัทโตโยต้านนทบุรี ผู้จำหน่ายโตโยต้า จำกัด</td>';
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
$header.=' 			<td width="5%" align="center" class="tlb_bg"><b>ลำดับ</b></td>';
$header.=' 			<td width="10%" align="center" class="tlb_bg"><b>Req No.</b></td>';
$header.=' 			<td width="26%" class="tlb_bg"><b>แผนก</b></td>';
$header.=' 			<td width="6%" align="center" class="tlb_bg"><b>สาขา</b></td>';
$header.=' 			<td width="10%" align="center" class="tlb_bg"><b>วันที่เสร็จ</b></td>';
$header.=' 			<td width="31%" class="tlb_bg"><b>รายการ</b></td>';
$header.=' 			<td width="12%" align="right" class="tlbr_bg"><b>จำนวนเงิน&nbsp;&nbsp;</b></td>';
$header.='      </tr>';

$tr_item ='<tr>';
$tr_item.='		<td align="center" class="##class##">&nbsp;##i##</td>';
$tr_item.='		<td align="center" class="##class##">&nbsp;##FReqNo##</td>';
$tr_item.='		<td class="##class##">&nbsp;##sec_nameThai##</td>';
$tr_item.='		<td align="center" class="##class##">&nbsp;##brn_code##</td>';
$tr_item.='		<td align="center" class="##class##">&nbsp;##FFinishDate##</td>';
$tr_item.=		'<td class="lb">&nbsp;##FDetail##</td>';
$tr_item.='		<td align="right" class="lrb">##FCost##&nbsp;&nbsp;</td>';
$tr_item.='</tr>';

$footerSum ='<tr>';
$footerSum.='	<td align="center">&nbsp;</td>';
$footerSum.='	<td align="center">&nbsp;</td>';
$footerSum.='	<td>&nbsp;</td>';
$footerSum.='	<td align="center">&nbsp;</td>';
$footerSum.='	<td align="center">&nbsp;</td>';
$footerSum.='	<td align="right" class="lb_bg"><b>รวมทั้งสิ้น</b></td>';
$footerSum.='	<td align="right" class="lrb_bg">'.number_format($SUM,2,".",",").'&nbsp;&nbsp;</td>';
$footerSum.='</tr>';

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
				 $numRec++;
				 if($item==1){
				 	print(str_replace("##PAGE##",$page,$header));
				 	$page++;
				 } 
				 if(count($row['details'])==0)$class = "lb";
				 else $class = "l";
 
				 $_tr_item = str_replace("##class##",$class,$tr_item);
				 $_tr_item = str_replace("##i##",$i,$_tr_item);
				 $_tr_item = str_replace("##FReqNo##",$row['FReqNo'],$_tr_item);
				 $_tr_item = str_replace("##sec_nameThai##",$row['sec_nameThai'],$_tr_item);
				 $_tr_item = str_replace("##brn_code##",$row['brn_code'],$_tr_item);
				 $_tr_item = str_replace("##FFinishDate##",$row['FFinishDate'],$_tr_item);
				 $_tr_item = str_replace("##FDetail##",$row['FDetail'],$_tr_item);
				 $_tr_item = str_replace("##FCost##",$row['FCost'],$_tr_item);
				 print ($_tr_item);
				 for($index=0;$index<count($row['details']);$index++){
					$item++;
					$numRec++;
					if($index==(count($row['details'])-1))$class="lb";
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
						$_brn_code = $row['brn_code'];
						$_finishDate =$row['FFinishDate'];
				 	}else{
						$_reqNo = "";
						$_sec_nameThai = "";
						$_brn_code = "";
						$_finishDate = "";
				 	}
				 	
				   $_tr_item = str_replace("##class##",$class,$tr_item);
				   $_tr_item = str_replace("##i##","",$_tr_item);
				   $_tr_item = str_replace("##FReqNo##",$_reqNo,$_tr_item);
				   $_tr_item = str_replace("##sec_nameThai##",$_sec_nameThai,$_tr_item);
				   $_tr_item = str_replace("##brn_code##",$_brn_code,$_tr_item);
				   $_tr_item = str_replace("##FFinishDate##",$_finishDate,$_tr_item);
				   $_tr_item = str_replace("##FDetail##",$row['details'][$index]['FDetail'],$_tr_item);
				   $_tr_item = str_replace("##FCost##",$row['details'][$index]['FCost'],$_tr_item);
					print ($_tr_item);
					if($item==$rowPerPage){
						if($numRec==$totalRow)print($footerSum);
						print($footer);
						if($page<=$totalPage)print("<BR style=\"page-break-after: always;\">");
						$item=0;
					}
				 }
				 if($item==$rowPerPage){
					if($numRec==$totalRow)print($footerSum);
				 	print($footer);
				 	if($page<$totalPage)print("<BR style=\"page-break-after: always;\">");
				 	$item=0;
				 }
     		}/*End of foreach($_array as $key=>$val)*/
     		if($item<$rowPerPage && $item>0){
     				print($footerSum);
					print($footer);
     		}
      	  }/*End of if(!empty($_array))*/
      ?>
</body>
</html>