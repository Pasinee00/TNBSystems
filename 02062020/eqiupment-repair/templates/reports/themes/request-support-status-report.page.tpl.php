<?php
include '../../../lib/db_config.php';
include '../../../main/modules/Model_Utilities.php';
include '../../../general_sys/modules/suplier_model.php';

$utilMD = new Model_Utilities();
$supMD = new Model_Suplier();
$_status = array("N"=>"รอการแก้ไข","A"=>"รอการอนุมัติ","S"=>"กำลังทำการแก้ไข","F"=>"ทำการแก้ไขเรียร้อยแล้ว","C"=>"ถูกยกเลิก");
$FSectionID = $_REQUEST['sec_id'];
$FBranchID = $_REQUEST['brn_id'];
$SRequestDate = $_REQUEST['SRequestDate'];
$ERequestDate = $_REQUEST['ERequestDate'];
$SDueDate = $_REQUEST['SDueDate'];
$EDueDate = $_REQUEST['EDueDate'];
$SFinishDate = $_REQUEST['SFinishDate'];
$EFinishDate = $_REQUEST['EFinishDate'];
$FStatus = $_REQUEST['FStatus'];
$Support = $_REQUEST['Support'];
$sect_data = $utilMD->getSectById($FSectionID);
$brn_data = $utilMD->get_BranchById($FBranchID);
$sup_data = $supMD->get_data($FComClaimID);

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

$query ="SELECT t0.FStatus,t0.FSupportID, t1.FRequestID,t1.FReqNo,t1.FReqDate,t1.FFinishDate "
				.",t2.first_name,t2.last_name "
				.",t3.brn_code "
				.",t4.sec_nameThai "
				."FROM mtrequest_db.tbl_requestowner t0 "
				."LEFT JOIN mtrequest_db.tbl_request t1 ON(t1.FRequestID = t0.FRequestID) "
				."LEFT JOIN pis_db.tbl_user t2 ON(t2.user_id = t0.FSupportID) "
				."LEFT JOIN pis_db.tbl_branch t3 ON(t3.brn_id = t1.FBranchID) "
				."LEFT JOIN pis_db.tbl_section t4 ON(t4.sec_id = t1.FSectionID) "
				."WHERE 1 ";
if($FSectionID)$query .=" AND t1.FSectionID='{$FSectionID}'";
if($FBranchID)$query .=" AND t1.FBranchID='{$FBranchID}'";
if($SRequestDate)$query .=" AND t1.FReqDate>='{$SRequestDate}'";
if($ERequestDate)$query .=" AND t1.FReqDate<='{$ERequestDate}'";
if($SDueDate)$query .=" AND t1.FDueDate>='{$SDueDate}'";
if($EDueDate)$query .=" AND t1.FDueDate<='{$EDueDate}'";
if($SFinishDate)$query .=" AND t1.FFinishDate>='{$SFinishDate}'";
if($EFinishDate)$query .=" AND t1.FFinishDate<='{$EFinishDate}'";
$query .=" ORDER BY t0.FSupportID,t1.FReqNo";
$results = mysql_query($query);
$rowPerPage = 20;
$numRows = mysql_num_rows($results);
$totalPage = ceil($numRows/$rowPerPage);
while($row=mysql_fetch_object($results)){
	if($_reqNo!=$row->FReqNo){
		$item=0;
		$_reqNo=$row->FReqNo;
	}
	$records[$row->FSupportID]['FSupport'] = $row->first_name."&nbsp;&nbsp;".$row->last_name;
	if($item==0){
		$records[$row->FSupportID]['FReqNo'] = $row->FReqNo;
		$records[$row->FSupportID]['FReqDate'] = $utilMD->convertDate2Thai($row->FReqDate,"dd-sm");
		$records[$row->FSupportID]['sec_nameThai']= $row->sec_nameThai;
		$records[$row->FSupportID]['brn_code']= $row->brn_code;
		$records[$row->FSupportID]['NEW']=($row->FStatus=="N")?"":"<img src=\"../../../images/OK.gif\" width=\"16\" height=\"15\">";
		$records[$row->FSupportID]['PROCESS']=($row->FStatus=="S")?"":"<img src=\"../../../images/OK.gif\" width=\"16\" height=\"15\">";
		$records[$row->FSupportID]['APPROVE']=($row->FStatus=="A")?"":"<img src=\"../../../images/OK.gif\" width=\"16\" height=\"15\">";
		$records[$row->FSupportID]['COMPLETE']=($row->FStatus=="F")?"":"<img src=\"../../../images/OK.gif\" width=\"16\" height=\"15\">";
	}else{
		$records[$row->FSupportID]['details'][$item-1]['FReqNo'] = $row->FReqNo;
		$records[$row->FSupportID]['details'][$item-1]['FReqDate'] = $utilMD->convertDate2Thai($row->FReqDate,"dd-sm");
		$records[$row->FSupportID]['details'][$item-1]['sec_nameThai']= $row->sec_nameThai;
		$records[$row->FSupportID]['details'][$item-1]['brn_code']= $row->brn_code;
		$records[$row->FSupportID]['details'][$item-1]['NEW']=($row->FStatus=="N")?"":"<img src=\"../../../images/OK.gif\" width=\"16\" height=\"15\">";
		$records[$row->FSupportID]['details'][$item-1]['PROCESS']=($row->FStatus=="S")?"":"<img src=\"../../../images/OK.gif\" width=\"16\" height=\"15\">";
		$records[$row->FSupportID]['details'][$item-1]['APPROVE']=($row->FStatus=="A")?"":"<img src=\"../../../images/OK.gif\" width=\"16\" height=\"15\">";
		$records[$row->FSupportID]['details'][$item-1]['COMPLETE']=($row->FStatus=="F")?"":"<img src=\"../../../images/OK.gif\" width=\"16\" height=\"15\">";
	}
	$item++;
}
$utilMD->_print($records);
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-874">
<title>รายงานสรุปสถานะงานแจ้งซ่อมแยกตามเจ้าหน้าที่</title>

<link href="../../../css/stylesheet_report.css" rel="stylesheet" type="text/css">
</head>

<body>
<table width="99%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td colspan="2" align="center"><b>รายงานสรุปสถานะงานแจ้งซ่อมแยกตามเจ้าหน้าที่</b></td>
  </tr>
  <tr>
    <td width="91%">บริษัทโตโยต้านนทบุรี ผู้จำหน่ายโตโยต้า จำกัด</td>
    <td width="9%" align="right"><b>Page :</b> &nbsp;</td>
  </tr>
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td height="4" colspan="2"></td>
  </tr>
  <tr>
    <td colspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="27%" rowspan="2" class="tlb_bg"><b>เจ้าหน้าที่ที่รับผิดชอบ</b></td>
        <td width="7%" rowspan="2" align="center" class="tlb_bg"><b>Req No.</b></td>
        <td width="7%" rowspan="2" align="center" class="tlb_bg"><b>วันที่แจ้ง</b></td>
        <td width="22%" rowspan="2" class="tlb_bg"><b>แผนก</b></td>
        <td width="6%" rowspan="2" align="center" class="tlb_bg"><b>สาขา</b></td>
        <td colspan="4" align="center" class="tlbr_bg"><b>สถานะ</b></td>
        </tr>     
      <tr>
        <td width="8%" align="center" class="lb_bg"><b>รอดำเนินการ</b></td>
        <td width="8%" align="center" class="lb_bg"><b>Apporve</b></td>
        <td width="8%" align="center" class="lb_bg"><b>กำลังดำเนินการ</b></td>
        <td width="7%" align="center" class="lrb_bg"><b>แก้ไขแล้ว</b></td>
      </tr>
      <tr>
        <td class="lb">&nbsp;</td>
        <td align="center" class="lb">&nbsp;</td>
        <td align="center" class="lb">&nbsp;</td>
        <td class="lb">&nbsp;</td>
        <td align="center" class="lb">&nbsp;</td>
        <td align="center" class="lb">&nbsp;</td>
        <td align="center" class="lb">&nbsp;</td>
        <td align="center" class="lb">&nbsp;</td>
        <td align="center" class="lrb">&nbsp;</td>
      </tr>
    </table></td>
  </tr>
</table>
</body>
</html>