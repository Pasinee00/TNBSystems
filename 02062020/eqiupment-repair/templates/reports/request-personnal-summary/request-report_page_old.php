<?php
include '../../../../lib/db_config.php';
include '../../../../main/modules/Model_Utilities.php';
$utilMD = new Model_Utilities();
$FJobLevelGROUP = $utilMD->FJobLevelGROUP();
$_status = array("new"=>"รอการแก้ไข","waiting"=>"รอการอนุมัติ","inprogress"=>"กำลังทำการแก้ไข","finished"=>"ทำการแก้ไขเรียร้อยแล้ว","cancel"=>"ถูกยกเลิก");
	$filename = "excel_report".".xls";	
	header("Content-Disposition: attachment; filename=\"$filename\"");
	header("Content-Type: application/vnd.ms-excel"); 
	
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

function countJob($sId,$secId,$brnId,$sRDate,$eRDate,$sDueDate,$eDueDate,$status,$type,$chk_over=''){
	$numRec=0;
	$query ="SELECT COUNT(t1.FRequestID) AS numRec "
	."FROM mtrequest_db.tbl_requestowner t0 "
	."INNER JOIN mtrequest_db.tbl_request t1 ON(t1.FRequestID = t0.FRequestID) "
	."WHERE t0.FSupportID= {$sId}";
	if($secId)$query .=" AND t1.FSectionID='{$secId}'";
	if($brnId)$query .=" AND t1.FBranchID='{$brnId}'";
	if($sRDate)$query .=" AND t1.FReqDate>='{$sRDate}'";
	if($eRDate)$query .=" AND t1.FReqDate<='{$eRDate}'";
	if($sDueDate)$query .=" AND t1.FDueDate>='{$sDueDate}'";
	if($eDueDate)$query .=" AND t1.FDueDate<='{$eDueDate}'";
	if($status)$query .=" AND t0.FStatus IN ({$status})";
	if($type)$query .=" AND t1.FJobLevel= '{$type}'";
	
	 if($chk_over=='In'){$query .=" AND DATEDIFF(t1.FDueDate,NOW())>=0";
	 }elseif($chk_over=='Over'){ $query .=" AND  DATEDIFF(t1.FDueDate,NOW())<0";} 
	//$query .=" GROUP BY t0.FSupportID";
	$results = mysql_query($query);
	while($row=mysql_fetch_object($results)){
		$numRec+= $row->numRec;
	}
	return $numRec;
}
function countJobresult($sId,$secId,$brnId,$sRDate,$eRDate,$sDueDate,$eDueDate,$status,$type){
	$numRec=0;
	$query ="SELECT COUNT(t1.FRequestID) AS numRec "
	."FROM mtrequest_db.tbl_requestowner t0 "
	."INNER JOIN mtrequest_db.tbl_request t1 ON(t1.FRequestID = t0.FRequestID) "
	."WHERE t0.FSupportID= {$sId}";
	if($secId)$query .=" AND t1.FSectionID='{$secId}'";
	if($brnId)$query .=" AND t1.FBranchID='{$brnId}'";
	if($sRDate)$query .=" AND t1.FReqDate>='{$sRDate}'";
	if($eRDate)$query .=" AND t1.FReqDate<='{$eRDate}'";
	if($sDueDate)$query .=" AND t1.FDueDate>='{$sDueDate}'";
	if($eDueDate)$query .=" AND t1.FDueDate<='{$eDueDate}'";
	if($status)$query .=" AND t0.FStatus IN ({$status})";
	if($type)$query .=" AND t1.FJobresult= '{$type}'";
	if($type)$query .=" AND t1.FJobLevel= '{$type}'";
	
	//$query .=" GROUP BY t0.FRequestID";
	$results = mysql_query($query);
	
	while($row=mysql_fetch_object($results)){
		$numRec+= $row->numRec;
	}
	return $numRec;
}
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
<? 
$sql ="SELECT *  FROM
			mtrequest_db.tbl_request
			LEFT JOIN mtrequest_db.tbl_requestowner ON mtrequest_db.tbl_request.FRequestID = mtrequest_db.tbl_requestowner.FRequestID
			LEFT JOIN pis_db.tbl_user ON mtrequest_db.tbl_requestowner.FSupportID = pis_db.tbl_user.user_id
			WHERE 1
";
if($FSectionID)$sql .=" AND mtrequest_db.tbl_request.FSectionID='".$FSectionID."' ";
if($FBranchID)$sql .=" AND mtrequest_db.tbl_request.FBranchID='".$FBranchID."' ";
if($SRequestDate)$sql .=" AND mtrequest_db.tbl_request.FReqDate>='".$SRequestDate."' ";
if($ERequestDate)$sql .=" AND mtrequest_db.tbl_request.FReqDate<='".$ERequestDate."' ";
if($SDueDate)$sql .=" AND mtrequest_db.tbl_request.FDueDate>='".$SDueDate."' ";
if($EDueDate)$sql .=" AND mtrequest_db.tbl_request.FDueDate<='".$EDueDate."' ";
if($SFinishDate)$sql .=" AND mtrequest_db.tbl_request.FFinishDate>='".$SFinishDate."' ";
if($EFinishDate)$sql .=" AND mtrequest_db.tbl_request.FFinishDate<='".$EFinishDate."' ";
if($Support)$sql .=" AND mtrequest_db.tbl_requestowner.FSupportID IN ({$Support})";
//echo $sql;
$query = mysql_query($sql);
while($row=mysql_fetch_object($query)){
}
?>
  <tr>
    <td rowspan="3" align="center" valign="middle" bgcolor="#FFFFE6" style="font-weight: bold"><strong>ชื่อผู้รับผิดชอบ</strong></td>
	  <?php 
					$c=0;
					if(!empty($FJobLevelGROUP)){ foreach ($FJobLevelGROUP as $key=>$val){
						$c++;
					 }}?>
    <td width="<?=(5*$c)?>%"  colspan="<?=$c?>" rowspan="2" align="center" valign="middle" bgcolor="#FFFFE6" style="font-weight: bold"><strong>ประเภทงาน</strong></td>
   <td rowspan="3" align="center" valign="middle" bgcolor="#FFFFE6" style="font-weight: bold"><strong>ซ่อมเอง</strong></td>
   <td rowspan="3" align="center" valign="middle" bgcolor="#FFFFE6" style="font-weight: bold"><strong>ผรม.ดำเนินการ</strong></td>
    <td colspan="6" align="center" bgcolor="#FFFFE6" style="font-weight: bold" widht="60%"><strong>จำนวนงาน</strong></td>
  </tr>
  <tr>
    <td rowspan="2" align="center" bgcolor="#FFFFE6" style="font-weight: bold"><strong>รับทั้งหมด</strong></td>
    <td colspan="3" align="center" bgcolor="#FFFFE6" style="font-weight: bold"><strong>อยู่ระหว่างดำเนินการ</strong></td>
    <td rowspan="2" align="center" bgcolor="#FFFFE6" style="font-weight: bold"><strong>จบ</strong></td>
    <td rowspan="2" align="center" bgcolor="#FFFFE6" style="font-weight: bold"><strong>ยังไม่ได้ดำเนินการ</strong></td>
  </tr>
  <tr>
	 <?php if(!empty($FJobLevelGROUP)){ foreach ($FJobLevelGROUP as $key=>$val){?>
						<td width="5%" align="center" bgcolor="#FFFFE6"><?=iconv("utf-8","tis-620",$val[FJobLevel_name])?></td>
					<?PHP }}?>
    <?php /* <td align="center" bgcolor="#FFFFE6" style="font-weight: bold">S</td>
    <td align="center" bgcolor="#FFFFE6" style="font-weight: bold">L</td>
    <td align="center" bgcolor="#FFFFE6" style="font-weight: bold">M</td>
    <td align="center" bgcolor="#FFFFE6" style="font-weight: bold">H</td>
    <td align="center" bgcolor="#FFFFE6" style="font-weight: bold">P</td> */?>
    <td align="center" bgcolor="#FFFFE6" style="font-weight: bold">ไม่เกินกำหนด</td>
    <td align="center" bgcolor="#FFFFE6" style="font-weight: bold">เกินกำหนด</td>
    <td align="center" bgcolor="#FFFFE6" style="font-weight: bold">รวม</td>
  </tr>
  <?	
$query ="SELECT t0.FStatus,t0.FSupportID,t1.FJobresult, t1.FRequestID,t1.FReqNo,t1.FReqDate,t1.FFinishDate "
."FROM mtrequest_db.tbl_requestowner t0 "
."INNER JOIN mtrequest_db.tbl_request t1 ON(t1.FRequestID = t0.FRequestID) "
."WHERE 1 ";
if($FSectionID)$query .=" AND t1.FSectionID='{$FSectionID}'";
if($FBranchID)$query .=" AND t1.FBranchID='{$FBranchID}'";
if($SRequestDate)$query .=" AND t1.FReqDate>='{$SRequestDate}'";
if($ERequestDate)$query .=" AND t1.FReqDate<='{$ERequestDate}'";
if($SDueDate)$query .=" AND t1.FDueDate>='{$SDueDate}'";
if($EDueDate)$query .=" AND t1.FDueDate<='{$EDueDate}'";
if($SFinishDate)$query .=" AND t1.FFinishDate>='{$SFinishDate}'";
if($EFinishDate)$query .=" AND t1.FFinishDate<='{$EFinishDate}'";
if($Support)$query .=" AND t0.FSupportID IN ({$Support})";
$query .=" GROUP BY t0.FSupportID";
$results = mysql_query($query);
$supportIds = "('0'";
while($row=mysql_fetch_object($results)){$supportIds.= ",'".$row->FSupportID."'";}
$supportIds.=")";

$query = "SELECT t1.user_id,t1.first_name,t1.last_name "
." FROM pis_db.tbl_user t1 "
." WHERE t1.user_id IN".$supportIds 
." AND t1.user_type IN('M','AM')";
$results = mysql_query($query);
$item = 0;
while($row=mysql_fetch_object($results)){
  ?>
  <tr>
    <td width="12%"><?=$row->first_name."  ".$row->first_name;?></td>
	 <?php if(!empty($FJobLevelGROUP)){ foreach ($FJobLevelGROUP as $key=>$val){?>
			<td width="5%" align="center"><?=countJob($row->user_id,$FSectionID,$FBranchID,$SRequestDate,$ERequestDate,$SDueDate,$EDueDate,"",$val['FJobLevel']);?></td>
	<?PHP }}?>
   
    <?php /* <td width="5%" align="center"><?=countJob($row->user_id,$FSectionID,$FBranchID,$SRequestDate,$ERequestDate,$SDueDate,$EDueDate,"","S");?></td>
    <td width="5%" align="center"><?=countJob($row->user_id,$FSectionID,$FBranchID,$SRequestDate,$ERequestDate,$SDueDate,$EDueDate,"","L");?></td>
    <td width="5%" align="center"><?=countJob($row->user_id,$FSectionID,$FBranchID,$SRequestDate,$ERequestDate,$SDueDate,$EDueDate,"","M");?></td>
    <td width="4%" align="center"><?=countJob($row->user_id,$FSectionID,$FBranchID,$SRequestDate,$ERequestDate,$SDueDate,$EDueDate,"","H");?></td>
    <td width="3%" align="center"><?=countJob($row->user_id,$FSectionID,$FBranchID,$SRequestDate,$ERequestDate,$SDueDate,$EDueDate,"","P");?></td> */?>
	  
    <td width="3%" align="center"><?=countJobresult($row->user_id,$FSectionID,$FBranchID,$SRequestDate,$ERequestDate,$SDueDate,$EDueDate,"","1");?></td>
    <td width="3%" align="center"><?=countJobresult($row->user_id,$FSectionID,$FBranchID,$SRequestDate,$ERequestDate,$SDueDate,$EDueDate,"","2");?></td>
    <td width="3%" align="center"><?=countJob($row->user_id,$FSectionID,$FBranchID,$SRequestDate,$ERequestDate,$SDueDate,$EDueDate,'','');?></td>
    <td width="1%" align="center"><?=countJob($row->user_id,$FSectionID,$FBranchID,$SRequestDate,$ERequestDate,$SDueDate,$EDueDate,"'waiting','inprogress','returnedit'",'','In');?></td>
    <td width="1%" align="center"><?=countJob($row->user_id,$FSectionID,$FBranchID,$SRequestDate,$ERequestDate,$SDueDate,$EDueDate,"'waiting','inprogress','returnedit'",'','Over');?></td>
    <td width="1%" align="center"><?=countJob($row->user_id,$FSectionID,$FBranchID,$SRequestDate,$ERequestDate,$SDueDate,$EDueDate,"'waiting','inprogress','returnedit'",'');?></td>
    <td width="3%" align="center"><?=countJob($row->user_id,$FSectionID,$FBranchID,$SRequestDate,$ERequestDate,$SDueDate,$EDueDate,"'finished','noapprove'",'');?></td>
    <td width="3%" align="center"><?=countJob($row->user_id,$FSectionID,$FBranchID,$SRequestDate,$ERequestDate,$SDueDate,$EDueDate,"'new'",'')?></td>
  </tr>
  <? } ?>
</table>

</body>
</html>