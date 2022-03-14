<?php 
include '../../../../lib/db_config.php';
include '../../../../main/modules/Model_Utilities.php';

$utilMD = new Model_Utilities();

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

$query ="SELECT t1.FRequestID,t1.FReqNo,t1.FReqDate,t1.FReciveDate,t1.FDetail,t1.FEditDate,t1.FFinishDate,t1.FJobLevel "
.",t4.sec_nameThai "
.",t5.FPurchaseID,t5.FItems,t5.FPRDate,t5.FPODate,t5.FDueDate "
."FROM mtrequest_db.tbl_purchase t5  "
."LEFT JOIN mtrequest_db.tbl_request t1 ON(t5.FRequestID = t1.FRequestID) "
."LEFT JOIN pis_db.tbl_employee t2 ON(t2.emp_id = t1.FReqID) "
."LEFT JOIN pis_db.tbl_employeehist t3 ON(t3.emp_code = t2.emp_code AND (t3.emp_flg IS NULL OR t3.emp_flg = '')) "
."LEFT JOIN pis_db.tbl_section t4 ON(t4.sec_id = t1.FSectionID) "
."WHERE 1 ";
if($FSectionID)$query .=" AND t4.sec_id='{$FSectionID}'";
if($FBranchID)$query .=" AND t1.FBranchID='{$FBranchID}'";
//if($FRepair_comp_id)$query .=" AND t1.FRepair_comp_id='{$FRepair_comp_id}'";
if($SRequestDate)$query .=" AND t1.FReqDate>='{$SRequestDate}'";
if($ERequestDate)$query .=" AND t1.FReqDate<='{$ERequestDate}'";
if($SDueDate)$query .=" AND t1.FDueDate>='{$SDueDate}'";
if($EDueDate)$query .=" AND t1.FDueDate<='{$EDueDate}'";
$query .=" ORDER BY t1.FReqNo";
$results = mysql_query($query);
$rowPerPage = 20;
$numRows = mysql_num_rows($results);
$totalPage = ceil($numRows/$rowPerPage);
$index = 0;
while($row=mysql_fetch_object($results)){
	$records[$index]['FReqNo'] = $row->FReqNo;
	$records[$index]['FReqDate'] = $row->FReqDate;
	$records[$index]['FReciveDate']= $row->FReciveDate;
	$records[$index]['FDetail']= $row->FDetail;
	$records[$index]['FEditDate']= $row->FEditDate;
	$records[$index]['FFinishDate']= $row->FFinishDate;
	$records[$index]['FJobLevel']= $row->FJobLevel;
	$records[$index]['sec_nameThai']= $row->sec_nameThai;
	$records[$index]['FItems'] = $row->FItems;
	$records[$index]['FPRStartDate'] = $row->FPRDate;
	$records[$index]['FPRApproveDate'] = $row->FPRDate;
	$records[$index]['FPOApproveDate'] = $row->FPODate;
	$records[$index]['FPurReciveDate'] = $row->FDueDate;
	$index++;
}

$header = '<table width="99%" border="0" align="center" cellpadding="0" cellspacing="0">';
$header.='		<tr>';
$header.='			<td colspan="2" align="center"><b>รายงานติดตามงาน</b></td>';
$header.='		</tr>';
$header.='		<tr>';
$header.='			<td width="91%">'.get_comThai($FRepair_comp_id).'</td>';
$header.='			<td width="9%" align="right"><b>Page :</b>##PAGE##/'.$totalPage.'&nbsp;</td>';
$header.='		</tr>';
$header.='		<tr>';
$header.='			<td colspan="2">&nbsp;'.$title.'</td>';
$header.='		</tr>';
$header.='		<tr>';
$header.='			<td height="4" colspan="2"></td>';
$header.='		</tr>';
$header.='		<tr>';
$header.='			<td colspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="0">';
$header.='		<tr>';
$header.='			<td align="center" width="6%" rowspan="2" class="tlb_bg" valign="middle"><b>ลำดับ</b></td>';
$header.='			<td align="left" width="" rowspan="2" class="tlb_bg" valign="middle"><b>เลขที่ใบแจ้งซ่อม</b></td>';
$header.='			<td align="center" width="16%" colspan="2" class="tlb_bg"><b>วันที่</b></td>';
$header.='			<td align="center" width="16%" colspan="2" class="tlb_bg"><b>วันที่</b></td>';
$header.='			<td align="center" width="8%"  rowspan="2" class="tlbr_bg" valing="middle"><b>วันที่รับของ</b></td>';
$header.='		</tr>';
$header.='		<tr>';
$header.='			<td align="center" width="8%" class="lb_bg"><b>ตรวจสอบ</b></td>';
$header.='			<td align="center" width="8%" class="lb_bg"><b>ออก PR</b></td>';
$header.='			<td align="center" width="8%" class="lb_bg"><b>อนุมัติ PR</b></td>';
$header.='			<td align="center" width="8%" class="lb_bg"><b>อนุมัติ PO</b></td>';
$header.='		</tr>';

$tr_item='<tr>';
$tr_item.=' 		<td align="center" class="lb">&nbsp;##i##</td>';
$tr_item.='      <td align="left" class="lb">&nbsp;[<b>##FReqNo##</b>]  ##FItems##</td>';
$tr_item.='       <td align="center" class="lb">&nbsp;##FEditDate##</td>';
$tr_item.='        <td align="center" class="lb">&nbsp;##FPRStartDate##</td>';
$tr_item.='        <td align="center" class="lb">&nbsp;##FPRApproveDate##</td>';
$tr_item.='        <td align="center" class="lb">&nbsp;##FPOApproveDate##</td>';
$tr_item.='        <td align="center" class="lrb">&nbsp##FPurReciveDate##</td>';
$tr_item.=' </tr>';

$footer ='</table></td>';
$footer.='  </tr>';
$footer.='</table>';
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-874">
<title>รายงานติดตามงาน</title>

<link href="../../../../css/stylesheet_report.css" rel="stylesheet" type="text/css">
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
				 $_tr_item = (!empty($row['FItems']))?str_replace("##FItems##"," - ".$row['FItems'],$_tr_item):str_replace("##FItems##","",$_tr_item);
				 $_tr_item = str_replace("##FEditDate##",$utilMD->convertDate2Thai($row['FEditDate'],"dd-sm"),$_tr_item);
				 $_tr_item = str_replace("##FPRStartDate##",$utilMD->convertDate2Thai($row['FPRStartDate'],"dd-sm"),$_tr_item);
				 $_tr_item = str_replace("##FPRApproveDate##",$utilMD->convertDate2Thai($row['FPRApproveDate'],"dd-sm"),$_tr_item);
				 $_tr_item = str_replace("##FPOApproveDate##",$utilMD->convertDate2Thai($row['FPOApproveDate'],"dd-sm"),$_tr_item);
				 $_tr_item = str_replace("##FPurReciveDate##",$utilMD->convertDate2Thai($row['FPurReciveDate'],"dd-sm"),$_tr_item);
				 
				 print ($_tr_item);
				 if($item==$rowPerPage){
				 	print($footer);
				 	if($page<=$totalPage)print("<BR style=\"page-break-after: always;\">");
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