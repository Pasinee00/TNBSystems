<?php
include '../../../../lib/db_config.php';
include '../../../../main/modules/Model_Utilities.php';
include '../../../modules/purchase_model.php';

	$filename = "excel_report".".xls";	
	header("Content-Disposition: attachment; filename=\"$filename\"");
	header("Content-Type: application/vnd.ms-excel"); 
	
$utilMD = new Model_Utilities();
$purMD = new Model_Purchase();
$_status = array("new"=>"รอการแก้ไข","waiting"=>"รอการอนุมัติ","inprogress"=>"กำลังทำการแก้ไข","finished"=>"ทำการแก้ไขเรียร้อยแล้ว","cancel"=>"ถูกยกเลิก");
$FSectionID = $_REQUEST['sec_id'];
$FBranchID = $_REQUEST['brn_id'];
$FRepair_comp_id = $_REQUEST['FRepair_comp_id'];
$SRequestDate = $_REQUEST['SRequestDate'];
$ERequestDate = $_REQUEST['ERequestDate'];
$SReciveDate = $_REQUEST['SReciveDate'];
$EReciveDate = $_REQUEST['EReciveDate'];
$JobLevel = $_REQUEST['JobLevel'];
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
function DateThaishow($_date){
			$ex = split("-",$_date);
			if($_date!=''){
				 return $ex[2]."/".($ex[1])."/".($ex[0]+543);
			}
}

$title = "";
if($FSectionID)$title = "แผนก : ".$sect_data['sec_nameThai'];
if($FBranchID)$title .=(empty($title))?"สาขา : ".$brn_data['brn_name']:"&nbsp;&nbsp;สาขา : ".$brn_data['brn_name'];
if($SRequestDate) $title .=(empty($title))?"วันที่แจ้งตั้งแต่วันที่ : ".$utilMD->convertDate2Thai($SRequestDate,"dd-sm"):"&nbsp;&nbsp;วันที่แจ้งตั้งแต่วันที่ : ".$utilMD->convertDate2Thai($SRequestDate,"dd-sm");
if($ERequestDate){
	if(empty($SRequestDate))$title .=(empty($title))?"วันที่แจ้งถึงวันที่ : ".$utilMD->convertDate2Thai($ERequestDate,"dd-sm"):"&nbsp;&nbsp;วันที่แจ้งถึงวันที่ : ".$utilMD->convertDate2Thai($ERequestDate,"dd-sm");
	else  $title .=" - ".$utilMD->convertDate2Thai($ERequestDate,"dd-sm");
}

if($SReciveDate) $title .=(empty($title))?"วันที่รับเรื่องตั้งแต่วันที่ : ".$utilMD->convertDate2Thai($SReciveDate,"dd-sm"):"&nbsp;&nbsp;วันที่รับเรื่องตั้งแต่วันที่ : ".$utilMD->convertDate2Thai($SReciveDate,"dd-sm");
if($EReciveDate){
	if(empty($SReviceDate))$title .=(empty($title))?"วันที่รับเรื่องถึงวันที่ : ".$utilMD->convertDate2Thai($EReciveDate,"dd-sm"):"&nbsp;&nbsp;วันที่รับเรื่องถึงวันที่ : ".$utilMD->convertDate2Thai($EReciveDate,"dd-sm");
	else  $title .=" - ".$utilMD->convertDate2Thai($EReciveDate,"dd-sm");
}
if($JobLevel)$title .=(empty($title))?"`ประเภทงาน : ".$JobLevel:"&nbsp;&nbsp;ประเภทงาน : ".$JobLevel;


?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-874">
<title>รายงานสรุปสถานะงานแจ้งซ่อมแยกตามเจ้าหน้าที่</title>
<?php /* <link href="../../../css/stylesheet_report.css" rel="stylesheet" type="text/css"> */?>
</head>
<style>
.fontWingdings{
	font-family:"Wingdings 2";
}
</style>
<body><table width="100%" border="1" cellpadding="0" cellspacing="0">
  <tr>
    <td width="12%" rowspan="2" align="center" bgcolor="#FFFFE1" style="font-weight: bold">ลำดับ</td>
    <td width="5%" rowspan="2" align="center" bgcolor="#FFFFE1" style="font-weight: bold">ผู้รับผิดชอบ</td>
    <td width="5%" rowspan="2" align="center" bgcolor="#FFFFE1" style="font-weight: bold">วันที่รับเรื่อง</td>
    <td width="4%" rowspan="2" align="center" bgcolor="#FFFFE1" style="font-weight: bold">เลขที่ใบแจ้งซ่อม</td>
    <td width="3%" rowspan="2" align="center" bgcolor="#FFFFE1" style="font-weight: bold">ปัญหางาน</td>
    <td colspan="4" align="center" bgcolor="#FFFFE1" style="font-weight: bold">สถานะงาน</td>
  </tr>
  <tr>
    <td width="7%" align="center" bgcolor="#FFFFE1" style="font-weight: bold">PR</td>
    <td width="8%" align="center" bgcolor="#FFFFE1" style="font-weight: bold">PO</td>
    <td width="7%" align="center" bgcolor="#FFFFE1" style="font-weight: bold">ซ่อม</td>
    <td width="8%" align="center" bgcolor="#FFFFE1" style="font-weight: bold">ยังไม่<br>เริ่มซ่อม</td>
  </tr>
  <?
  $query ="SELECT t1.FRequestID,t1.FReqNo,t1.FReqDate,t1.FReciveDate,t1.FDetail,t1.FEditDate,t1.FFinishDate,t1.FJobLevel,t1.FStatus,t1.FOth_detail "
.",t4.sec_nameThai "
.",t6.first_name,t6.last_name "
."FROM mtrequest_db.tbl_request t1 "
."LEFT JOIN pis_db.tbl_employee t2 ON(t2.emp_id = t1.FReqID) "
."LEFT JOIN pis_db.tbl_employeehist t3 ON(t3.emp_code = t2.emp_code AND (t3.emp_flg IS NULL OR t3.emp_flg = '')) "
."LEFT JOIN pis_db.tbl_section t4 ON(t4.sec_id = t3.sec_id) "
."LEFT JOIN mtrequest_db.tbl_requestowner t5 ON(t5.FRequestID = t1.FRequestID) "
."LEFT JOIN pis_db.tbl_user t6 ON(t6.user_id = t5.FSupportID) "
."WHERE t1.FStatus IN('waiting','inprogress') ";
if($FSectionID)$query .=" AND t1.FSectionID='{$FSectionID}'";
if($FBranchID)$query .=" AND t1.FBranchID='{$FBranchID}'";
if($SRequestDate)$query .=" AND t1.FReqDate>='{$SRequestDate}'";
if($ERequestDate)$query .=" AND t1.FReqDate<='{$ERequestDate}'";
if($SReciveDate)$query .=" AND t1.FReciveDate>='{$SReciveDate}'";
if($EReciveDate)$query .=" AND t1.FReciveDate<='{$EReciveDate}'";
if($JobLevel)$query .=" AND t1.FJobLevel='{$JobLevel}'";
$query .=" GROUP BY t1.FReqNo";
$query .=" ORDER BY t1.FReqNo";
//echo $query;
$results = mysql_query($query);
$numRows = mysql_num_rows($results);
  $i=0;
  while($row=mysql_fetch_object($results)){ 
  $i++;
  
   
   if($row->FStatus=='inprogress' &&  ($row->FEditDate!='' && $row->FEditDate!='0000-00-00')){
	   $row->Start='&#10003';
   }
   if(($row->FStatus=='waiting' || $row->FStatus=='inprogress') && ($row->FEditDate=='' || $row->FEditDate=='0000-00-00')){
	   $row->Not='&#10003';
   }
   if($purMD->checkPRStatus($row->FRequestID)){
	   	$row->PR='&#10003';
	    $row->PO='';
		$row->Start='';
		$row->Not='';
   }else if($purMD->checkPOStatus($row->FRequestID)){
	   	$row->PR='';
	    $row->PO='&#10003';
		$row->Start='';
		$row->Not='';
   }
  

  ?>
  <tr>
    <td align="center"><?=$i?></td>
    <td><?=$row->first_name."&nbsp;&nbsp;".$row->last_name?></td>
    <td align="center"><?=DateThaishow($row->FReciveDate)?></td>
    <td align="center"><?=$row->FReqNo?></td>
    <td align="left"><?=$row->FOth_detail?></td>
    <td align="center"><?=$row->PR?></td>
    <td align="center"><?=$row->PO?></td>
    <td align="center"><?=$row->Start?></td>
    <td align="center"><?=$row->Not?></td>
   
  </tr>
  <? } ?>
</table>

</body>
</html>