<?php
include '../../../lib/db_config.php';
include '../../../main/modules/Model_Utilities.php';
include '../../../general_sys/modules/suplier_model.php';

$utilMD = new Model_Utilities();
$supMD = new Model_Suplier();
$_status = array("new"=>"รอการแก้ไข","waiting"=>"รอการอนุมัติ","inprogress"=>"กำลังทำการแก้ไข","finished"=>"ทำการแก้ไขเรียร้อยแล้ว","cancel"=>"ถูกยกเลิก");
$FSectionID = $_REQUEST['sec_id'];
$FBranchID = $_REQUEST['brn_id'];
$FRepair_comp_id = $_REQUEST['FRepair_comp_id'];
$SRequestDate = $_REQUEST['SRequestDate'];
$ERequestDate = $_REQUEST['ERequestDate'];
$SDueDate = $_REQUEST['SDueDate'];
$EDueDate = $_REQUEST['EDueDate'];
$SFinishDate = $_REQUEST['SFinishDate'];
$EFinishDate = $_REQUEST['EFinishDate'];
$FStatus = $_REQUEST['FStatus'];
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

$query ="SELECT t1.FRequestID,t1.FReqNo,t1.FDueDate,t1.FFinishDate,t1.FOth_detail,t1.FDetail "
		         .",t2.FMachineTypeName "
		         .",t3.FRepairGroupName "
		         ."FROM mtrequest_db.tbl_request t1 "
		         ."LEFT JOIN general_db.tbl_machinetype t2 ON(t2.FMachineTypeID = t1.FMachineTypeID) "
		         ."LEFT JOIN general_db.tbl_repairgroup t3 ON(t3.FRepairGroupID = t1.FRepairGroupID) "
		         ."WHERE 1 ";
//if($FRepair_comp_id)$query .=" AND t1.FRepair_comp_id='{$FRepair_comp_id}'";
if($FSectionID)$query .=" AND t1.FSectionID='{$FSectionID}'";
if($FBranchID)$query .=" AND t1.FBranchID='{$FBranchID}'";
if($SRequestDate)$query .=" AND t1.FReqDate>='{$SRequestDate}'";
if($ERequestDate)$query .=" AND t1.FReqDate<='{$ERequestDate}'";
if($SDueDate)$query .=" AND t1.FDueDate>='{$SDueDate}'";
if($EDueDate)$query .=" AND t1.FDueDate<='{$EDueDate}'";
if($SFinishDate)$query .=" AND t1.FFinishDate>='{$SFinishDate}'";
if($EFinishDate)$query .=" AND t1.FFinishDate<='{$EFinishDate}'";
if($FStatus)$query .=" AND t1.FStatus='{$FStatus}'";
$query .=" ORDER BY t1.FReqNo";
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
	$records[$row->FRequestID]['FRepairGroupName'] = $row->FRepairGroupName;
	$records[$row->FRequestID]['FMachineTypeName']= $row->FMachineTypeName;
	$records[$row->FRequestID]['FDueDate']= $row->FDueDate;
	$records[$row->FRequestID]['FFinishDate']= $row->FFinishDate;
	$records[$row->FRequestID]['FOth_detail']= $row->FOth_detail;
	$records[$row->FRequestID]['FDetail'] = $row->FDetail;

	$item++;
}

$header ='<table width="100%" border="0" cellspacing="0" cellpadding="0">';
$header.='	<tr>';
$header.='	  <td colspan="2" align="center"><b>รายงานติดตามสถานะงานซ่อม ที่ '.$_status[$FStatus].'</b></td>';
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
$header.=' 		<td width="4%" align="center" class="tlb_bg"><b>ลำดับ</b></td>';
$header.='        <td width="9%" align="center" class="tlb_bg"><b>Req No.</b></td>';
$header.='        <td width="15%" align="center" class="tlb_bg"><b>กลุ่มงาน</b></td>';
$header.='        <td width="22%" align="center" class="tlb_bg"><b>เครื่องจักร</b></td>';
$header.='        <td width="8%" align="center" class="tlb_bg"><b>กำหนดเสร็จ</b></td>';
$header.='        <td width="8%" align="center" class="tlb_bg"><b>วันที่เสร็จ</b></td>';
$header.='        <td width="17%" align="center" class="tlb_bg"><b>รายละเอียด</b></td>';
$header.='        <td width="17%" align="center" class="tlbr_bg"><b>สรุปงาน</b></td>';
$header.='      </tr>';

$tr_item ='<tr>';
$tr_item.='	<td align="center" class="##class##" valign="top">&nbsp;##i##</td>';
$tr_item.='	<td align="center" class="##class##" valign="top">&nbsp;##FReqNo##</td>';
$tr_item.='	<td class="##class##" valign="top">&nbsp;##FRepairGroupName##</td>';
$tr_item.='	<td class="##class##" valign="top">&nbsp;##FMachineTypeName##</td>';
$tr_item.='	<td align="center" class="##class##" valign="top">&nbsp;##FDueDate##</td>';
$tr_item.='	<td align="center" class="##class##" valign="top">&nbsp;##FFinishDate##</td>';
$tr_item.='	<td class="##class##">&nbsp;##FDetail##</td>';
$tr_item.='	<td class="lrb">&nbsp;##FOth_detail##</td>';
$tr_item.='</tr>';

$footer ='</table></td>';
$footer.='  </tr>';
$footer.='</table>';
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-874">
<title>รายงานติดตามสถานะงานซ่อม ที่ <?php print ($_status[$FStatus]);?></title>

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
				 if(count($row['details'])==0 || $item==$rowPerPage)$class = "lb";
				 else $class = "l";
 
				 $_tr_item = str_replace("##class##",$class,$tr_item);
				 $_tr_item = str_replace("##i##",$i,$_tr_item);
				 $_tr_item = str_replace("##FReqNo##",$row['FReqNo'],$_tr_item);
				 $_tr_item = str_replace("##FRepairGroupName##",$row['FRepairGroupName'],$_tr_item);
				 $_tr_item = str_replace("##FMachineTypeName##",$row['FMachineTypeName'],$_tr_item);
				 $_tr_item = str_replace("##FDueDate##",$utilMD->convertDate2Thai($row['FDueDate'],"dd-sm"),$_tr_item);
				 $_tr_item = str_replace("##FFinishDate##",$utilMD->convertDate2Thai($row['FFinishDate'],"dd-sm"),$_tr_item);
				 $_tr_item = str_replace("##FDetail##",$row['FDetail'],$_tr_item);
				 $_tr_item = str_replace("##FOth_detail##",$row['FOth_detail'],$_tr_item);
				 print ($_tr_item);
				 if($item==$rowPerPage){
				 	print($footer);
				 	if($page<=$totalPage)print("<BR style=\"page-break-after: always;\">");
				 	$item=0;
				 }
				 for($index=0;$index<count($row['details']);$index++){
					$item++;
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
						$_repairGroupName = $row['FRepairGroupName'];
						$_machineTypeName = $row['FMachineTypeName'];
						$_dueDate = $utilMD->convertDate2Thai($row['FDueDate'],"dd-sm");
						$_finishDate = $utilMD->convertDate2Thai($row['FFinishDate'],"dd-sm");
						$no = $i;
				 	}else{
						$_reqNo = "";
						$_repairGroupName = "";
						$_machineTypeName = "";
						$_dueDate = "";
						$_finishDate = "";
						$no = "";
				 	}
				 	
				   $_tr_item = str_replace("##class##",$class,$tr_item);
				   $_tr_item = str_replace("##i##",$no,$_tr_item);
				   $_tr_item = str_replace("##FReqNo##",$_reqNo,$_tr_item);
				   $_tr_item = str_replace("##FRepairGroupName##",$_repairGroupName,$_tr_item);
				   $_tr_item = str_replace("##FMachineTypeName##",$_machineTypeName,$_tr_item);
				   $_tr_item = str_replace("##FDueDate##",$_dueDate,$_tr_item);
				   $_tr_item = str_replace("##FFinishDate##",$_finishDate,$_tr_item);
				   $_tr_item = str_replace("##FDetail##",$row['details'][$index]['FDetail'],$_tr_item);
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