<?php 
include '../../../../lib/db_config.php';
include '../../../../main/modules/Model_Utilities.php';

$utilMD = new Model_Utilities();
$FJobLevelGROUP = $utilMD->FJobLevelGROUP();

$_status = array("new"=>"รอการแก้ไข","waiting"=>"รอการอนุมัติ","inprogress"=>"กำลังทำการแก้ไข","finished"=>"ทำการแก้ไขเรียร้อยแล้ว","cancel"=>"ถูกยกเลิก");
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
	// $query .=" GROUP BY t0.FSupportID";
	
	//echo $query;
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
	
	//$query .=" GROUP BY t0.FRequestID";
	$results = mysql_query($query);
	
	while($row=mysql_fetch_object($results)){
		$numRec+= $row->numRec;
	}
	return $numRec;
}

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
	$records[$item]['support_id'] = $row->user_id;
	$records[$item]['support_name'] =  iconv("tis-620","utf-8",$row->first_name."  ".$row->last_name);
	$records[$item]['totalJob'] = countJob($row->user_id,$FSectionID,$FBranchID,$SRequestDate,$ERequestDate,$SDueDate,$EDueDate,'','');
	$records[$item]['inprogressJob_In'] = countJob($row->user_id,$FSectionID,$FBranchID,$SRequestDate,$ERequestDate,$SDueDate,$EDueDate,"'waiting','inprogress','returnedit'",'','In');
	$records[$item]['inprogressJob_Over'] = countJob($row->user_id,$FSectionID,$FBranchID,$SRequestDate,$ERequestDate,$SDueDate,$EDueDate,"'waiting','inprogress','returnedit'",'','Over'); 
	$records[$item]['inprogressJob'] = countJob($row->user_id,$FSectionID,$FBranchID,$SRequestDate,$ERequestDate,$SDueDate,$EDueDate,"'waiting','inprogress','returnedit'",'');
	$records[$item]['finishedJob'] = countJob($row->user_id,$FSectionID,$FBranchID,$SRequestDate,$ERequestDate,$SDueDate,$EDueDate,"'finished','noapprove'",'');
	$records[$item]['noapproveJob'] = countJob($row->user_id,$FSectionID,$FBranchID,$SRequestDate,$ERequestDate,$SDueDate,$EDueDate,"'noapprove'",'');
	
	
	$records[$item]['newJob'] = countJob($row->user_id,$FSectionID,$FBranchID,$SRequestDate,$ERequestDate,$SDueDate,$EDueDate,"'new'",'');
	
	if(!empty($FJobLevelGROUP)){
		foreach ($FJobLevelGROUP as $key=>$val){
		$records[$item][$val["FJobLevel"]] = countJob($row->user_id,$FSectionID,$FBranchID,$SRequestDate,$ERequestDate,$SDueDate,$EDueDate,"",$val["FJobLevel"]);
		}
	}
	/* $records[$item]['JobS'] = countJob($row->user_id,$FSectionID,$FBranchID,$SRequestDate,$ERequestDate,$SDueDate,$EDueDate,"","S");
	$records[$item]['JobL'] = countJob($row->user_id,$FSectionID,$FBranchID,$SRequestDate,$ERequestDate,$SDueDate,$EDueDate,"","L");
	$records[$item]['JobM'] = countJob($row->user_id,$FSectionID,$FBranchID,$SRequestDate,$ERequestDate,$SDueDate,$EDueDate,"","M");
	$records[$item]['JobH'] = countJob($row->user_id,$FSectionID,$FBranchID,$SRequestDate,$ERequestDate,$SDueDate,$EDueDate,"","H");
	$records[$item]['JobP'] = countJob($row->user_id,$FSectionID,$FBranchID,$SRequestDate,$ERequestDate,$SDueDate,$EDueDate,"","P"); */
	
	$records[$item]['Jobresult'] = countJobresult($row->user_id,$FSectionID,$FBranchID,$SRequestDate,$ERequestDate,$SDueDate,$EDueDate,"","1");
	$records[$item]['Jobresult2'] = countJobresult($row->user_id,$FSectionID,$FBranchID,$SRequestDate,$ERequestDate,$SDueDate,$EDueDate,"","2");
	$item++;
}

echo json_encode($records);
?>
