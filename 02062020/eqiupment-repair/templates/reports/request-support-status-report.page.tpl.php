<?php
include '../../../lib/db_config.php';
include '../../../main/modules/Model_Utilities.php';
include '../../../general_sys/modules/suplier_model.php';


function show_date($text){
			if($text!=""){
				$temp = explode('-',$text);
				$date_re = $temp[2].'/'.$temp[1].'/'.$temp[0];
				return $date_re;
			} 
}


if($status=='excel'){
	 $filename = "excel_report".".xls";	
	header("Content-Disposition: attachment; filename=\"$filename\"");
	header("Content-Type: application/vnd.ms-excel"); 
}

$utilMD = new Model_Utilities();
$supMD = new Model_Suplier();
$_status = array("new"=>"รอการแก้ไข","waiting"=>"รอการอนุมัติ","inprogress"=>"กำลังทำการแก้ไข","finished"=>"ทำการแก้ไขเรียร้อยแล้ว","cancel"=>"ถูกยกเลิก","noapprove"=>"ไม่อนุมัติ");
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
$Support = $_REQUEST['Support'];
$sect_data = $utilMD->getSectById($FSectionID);
$brn_data = $utilMD->get_BranchById($FBranchID);
$sup_data = $supMD->get_data($FComClaimID);
function DateDiffshow($begin,$end){
		$strSQL = "SELECT DATEDIFF('$end','$begin') AS diff_date";
		$rst = mysql_query($strSQL);
		if($row=mysql_fetch_array($rst))return $row['diff_date'];
		else return 0;
}
/////////////////////////////////////////////////////////////////////////////////////////////////////////
				$topic_worklate="SELECT
								tbl_worklate.id_worklate,
								tbl_worklate.topic_worklate,
								tbl_worklate.number_topic
								FROM
								general_db.tbl_worklate";
					$qry_topic = mysql_query($topic_worklate);
					$numRows_topic = mysql_num_rows($qry_topic);
					$i=0;
						while($row_topic=mysql_fetch_assoc($qry_topic)){
							$i++;
							$id_show[$i]=$row_topic['id_worklate'];	
							$show[$i]=$row_topic['topic_worklate'];
							$topic_show[$i]=$row_topic['number_topic'];	
					}
//////////////////////////////////////////////////////////////////////////////////////////////////////////
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

$query ="SELECT t0.FStatus,
t0.FSupportID, 
t1.FJobLevel,
t1.FJobresult,
t1.FDetail,
CASE
		WHEN mtrequest_db.t1.FJobLevel ='L' THEN '3'
		WHEN mtrequest_db.t1.FJobLevel ='M' THEN '3'
		WHEN mtrequest_db.t1.FJobLevel ='M1' THEN '7'
		WHEN mtrequest_db.t1.FJobLevel ='M2' THEN '15'
		WHEN mtrequest_db.t1.FJobLevel ='H' THEN '30'
	ELSE '0'
END AS NumDateJob,
t1.FRequestID,
t1.FReqNo,
t1.FReqDate,
t1.FFinishDate,
t1.status_closejob,
t1.approve_date,
t1.closejob_date,
t1.closejob_date2,
t1.closejob_emp_date,
t1.FLapAmt,
t1.FPartAmt,
t1.detail_worklate,
t1.id_worklate,
t1.FOth_detail "
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
//if($FRepair_comp_id)$query .=" AND t1.FRepair_comp_id='{$FRepair_comp_id}'";
if($SRequestDate)$query .=" AND t1.FReqDate>='{$SRequestDate}'";
if($ERequestDate)$query .=" AND t1.FReqDate<='{$ERequestDate}'";
if($SDueDate)$query .=" AND t1.FDueDate>='{$SDueDate}'";
if($EDueDate)$query .=" AND t1.FDueDate<='{$EDueDate}'";
if($SFinishDate)$query .=" AND t1.FFinishDate>='{$SFinishDate}'";
if($EFinishDate)$query .=" AND t1.FFinishDate<='{$EFinishDate}'";
if($Support)$query .=" AND t0.FSupportID IN ({$Support})";
$query .=" GROUP BY t0.FRequestID,t0.FSupportID,t0.FStartDate,t0.FFinishDate";
$query .=" ORDER BY t0.FSupportID,t1.FReqNo";
//echo $query;
$results = mysql_query($query);
$rowPerPage = 20;
$numRows = mysql_num_rows($results);
$totalPage = ceil($numRows/$rowPerPage);
while($row=mysql_fetch_object($results)){
	if($_FSupportID!=$row->FSupportID){
		$item=0;
		$_FSupportID=$row->FSupportID;
	}
	$records[$row->FSupportID]['FSupport'] = $row->first_name."&nbsp;&nbsp;".$row->last_name;
	if($item==0){
		$records[$row->FSupportID]['FReqNo'] = $row->FReqNo;
		$records[$row->FSupportID]['FReqDate'] = show_date($row->FReqDate);
		$records[$row->FSupportID]['sec_nameThai']= $row->sec_nameThai;
		$records[$row->FSupportID]['brn_code']= $row->brn_code;
		$records[$row->FSupportID]['FJobLevel']= $row->FJobLevel;
	if($status!='excel'){
		if($row->FJobresult=="1"){
		$records[$row->FSupportID]['FJobresult']="<img src=\"../../../images/OK.gif\" width=\"16\" height=\"15\">";
		}else if($row->FJobresult=="2"){
		$records[$row->FSupportID]['FJobresult2']="<img src=\"../../../images/OK.gif\" width=\"16\" height=\"15\">";
		}
		$records[$row->FSupportID]['NEW']=($row->FStatus=="new")?"<img src=\"../../../images/OK.gif\" width=\"16\" height=\"15\">":"";
		$records[$row->FSupportID]['PROCESS']=($row->FStatus=="inprogress")?"<img src=\"../../../images/OK.gif\" width=\"16\" height=\"15\">":"";
		$records[$row->FSupportID]['APPROVE']=($row->FStatus=="waiting")?"<img src=\"../../../images/OK.gif\" width=\"16\" height=\"15\">":"";
		$records[$row->FSupportID]['COMPLETE']=($row->FStatus=="finished")?"<img src=\"../../../images/OK.gif\" width=\"16\" height=\"15\">":"";
		$records[$row->FSupportID]['NOAPPROVE']=($row->FStatus=="noapprove")?"<img src=\"../../../images/OK.gif\" width=\"16\" height=\"15\">":"";
	}else{
		if($row->FJobresult=="1"){
		$records[$row->FSupportID]['FJobresult']="<font face=\"Wingdings 2\">P</font>";
		}else if($row->FJobresult=="2"){
		$records[$row->FSupportID]['FJobresult2']="<font face=\"Wingdings 2\">P</font>";
		}
		$records[$row->FSupportID]['NEW']=($row->FStatus=="new")?"<font face=\"Wingdings 2\">P</font>":"";
		$records[$row->FSupportID]['PROCESS']=($row->FStatus=="inprogress")?"<font face=\"Wingdings 2\">P</font>":"";
		$records[$row->FSupportID]['APPROVE']=($row->FStatus=="waiting")?"<font face=\"Wingdings 2\">P</font>":"";
		$records[$row->FSupportID]['COMPLETE']=($row->FStatus=="finished")?"<font face=\"Wingdings 2\">P</font>":"";
		$records[$row->FSupportID]['NOAPPROVE']=($row->FStatus=="noapprove")?"<font face=\"Wingdings 2\">P</font>":"";
	}
	
	if($status!='excel'){
		$records[$row->FSupportID]['FReqDate'] = $utilMD->convertDate2Thai($row->FReqDate,"dd-sm");
		$records[$row->FSupportID]['approve_date'] = $utilMD->convertDate2Thai($row->approve_date,"dd-sm");
		$records[$row->FSupportID]['FDetail']= $row->FDetail;
		$records[$row->FSupportID]['FFinishDate'] = $utilMD->convertDate2Thai($row->FFinishDate,"dd-sm");
		$records[$row->FSupportID]['closejob_date'] = $utilMD->convertDate2Thai($row->closejob_date,"dd-sm");
		$records[$row->FSupportID]['closejob_emp_date'] = $utilMD->convertDate2Thai($row->closejob_emp_date,"dd-sm");
		$records[$row->FSupportID]['closejob_date2'] = $utilMD->convertDate2Thai($row->closejob_date2,"dd-sm");
		$records[$row->FSupportID]['DateDiffshow'] = DateDiffshow($row->approve_date,$row->FFinishDate);
		
	}else{
		$records[$row->FSupportID]['FReqDate'] = show_date($row->FReqDate);//$utilMD->convertDate2Thai($EDueDate,"dd-sm")
		$records[$row->FSupportID]['approve_date'] = show_date($row->approve_date);
		$records[$row->FSupportID]['FDetail']= $row->FDetail;
		$records[$row->FSupportID]['FFinishDate'] = show_date($row->FFinishDate);
		$records[$row->FSupportID]['closejob_date'] = show_date($row->closejob_date);
		$records[$row->FSupportID]['closejob_emp_date'] = show_date($row->closejob_emp_date);
		$records[$row->FSupportID]['closejob_date2'] = show_date($row->closejob_date2);
		$records[$row->FSupportID]['DateDiffshow'] = DateDiffshow($row->approve_date,$row->FFinishDate);
	}
		
		$records[$row->FSupportID]['FLapAmt']=number_format($row->FLapAmt,2);
		$records[$row->FSupportID]['FPartAmt']=number_format($row->FPartAmt,2);
		$records[$row->FSupportID]['FOth_detail']=$row->FOth_detail;
		
		$records[$row->FSupportID]['id_worklate'] = $row->id_worklate;
		$records[$row->FSupportID]['detail_worklate'] = $row->detail_worklate;
	}else{
		$records[$row->FSupportID]['details'][$item-1]['FReqNo'] = $row->FReqNo;
		$records[$row->FSupportID]['details'][$item-1]['FReqDate'] = show_date($row->FReqDate);
		$records[$row->FSupportID]['details'][$item-1]['sec_nameThai']= $row->sec_nameThai;
		$records[$row->FSupportID]['details'][$item-1]['brn_code']= $row->brn_code;
		$records[$row->FSupportID]['details'][$item-1]['FJobLevel']= $row->FJobLevel;
	if($status!='excel'){
		if($row->FJobresult=="1"){
		$records[$row->FSupportID]['details'][$item-1]['FJobresult']="<img src=\"../../../images/OK.gif\" width=\"16\" height=\"15\">";
		}else if($row->FJobresult=="2"){
		$records[$row->FSupportID]['details'][$item-1]['FJobresult2']="<img src=\"../../../images/OK.gif\" width=\"16\" height=\"15\">";
		}
		$records[$row->FSupportID]['details'][$item-1]['NEW']=($row->FStatus=="new")?"<img src=\"../../../images/OK.gif\" width=\"16\" height=\"15\">":"";
		$records[$row->FSupportID]['details'][$item-1]['PROCESS']=($row->FStatus=="inprogress")?"<img src=\"../../../images/OK.gif\" width=\"16\" height=\"15\">":"";
		$records[$row->FSupportID]['details'][$item-1]['APPROVE']=($row->FStatus=="waiting")?"<img src=\"../../../images/OK.gif\" width=\"16\" height=\"15\">":"";
		$records[$row->FSupportID]['details'][$item-1]['COMPLETE']=($row->FStatus=="finished")?"<img src=\"../../../images/OK.gif\" width=\"16\" height=\"15\">":"";
		$records[$row->FSupportID]['details'][$item-1]['NOAPPROVE']=($row->FStatus=="noapprove")?"<img src=\"../../../images/OK.gif\" width=\"16\" height=\"15\">":"";
	}else{
		if($row->FJobresult=="1"){
		$records[$row->FSupportID]['details'][$item-1]['FJobresult']="<font face=\"Wingdings 2\">P</font>";
		}else if($row->FJobresult=="2"){
		$records[$row->FSupportID]['details'][$item-1]['FJobresult2']="<font face=\"Wingdings 2\">P</font>";
		}
		$records[$row->FSupportID]['details'][$item-1]['NEW']=($row->FStatus=="new")?"<font face=\"Wingdings 2\">P</font>":"";
		$records[$row->FSupportID]['details'][$item-1]['PROCESS']=($row->FStatus=="inprogress")?"<font face=\"Wingdings 2\">P</font>":"";
		$records[$row->FSupportID]['details'][$item-1]['APPROVE']=($row->FStatus=="waiting")?"<font face=\"Wingdings 2\">P</font>":"";
		$records[$row->FSupportID]['details'][$item-1]['COMPLETE']=($row->FStatus=="finished")?"<font face=\"Wingdings 2\">P</font>":"";
		$records[$row->FSupportID]['details'][$item-1]['NOAPPROVE']=($row->FStatus=="noapprove")?"<font face=\"Wingdings 2\">P</font>":"";
	}
	
	if($status!='excel'){
		$records[$row->FSupportID]['details'][$item-1]['FReqDate'] = $utilMD->convertDate2Thai($row->FReqDate,"dd-sm");
		$records[$row->FSupportID]['details'][$item-1]['approve_date'] = $utilMD->convertDate2Thai($row->approve_date,"dd-sm");
		$records[$row->FSupportID]['details'][$item-1]['FDetail'] = $row->FDetail;
		$records[$row->FSupportID]['details'][$item-1]['FFinishDate'] = $utilMD->convertDate2Thai($row->FFinishDate,"dd-sm");
		$records[$row->FSupportID]['details'][$item-1]['closejob_date'] = $utilMD->convertDate2Thai($row->closejob_date,"dd-sm");
		$records[$row->FSupportID]['details'][$item-1]['closejob_emp_date'] = $utilMD->convertDate2Thai($row->closejob_emp_date,"dd-sm");
		$records[$row->FSupportID]['details'][$item-1]['closejob_date2'] = $utilMD->convertDate2Thai($row->closejob_date2,"dd-sm");
		$records[$row->FSupportID]['details'][$item-1]['DateDiffshow'] =DateDiffshow($row->approve_date,$row->FFinishDate);
	}else{
		$records[$row->FSupportID]['details'][$item-1]['FReqDate'] = show_date($row->FReqDate);
		$records[$row->FSupportID]['details'][$item-1]['approve_date'] = show_date($row->approve_date);
		$records[$row->FSupportID]['details'][$item-1]['FDetail'] = $row->FDetail;
		$records[$row->FSupportID]['details'][$item-1]['FFinishDate'] = show_date($row->FFinishDate);
		$records[$row->FSupportID]['details'][$item-1]['closejob_date'] = show_date($row->closejob_date);
		$records[$row->FSupportID]['details'][$item-1]['closejob_emp_date'] = show_date($row->closejob_emp_date);
		$records[$row->FSupportID]['details'][$item-1]['closejob_date2'] = show_date($row->closejob_date2);
		$records[$row->FSupportID]['details'][$item-1]['DateDiffshow'] =DateDiffshow($row->approve_date,$row->FFinishDate);
	}
	
		$records[$row->FSupportID]['details'][$item-1]['FLapAmt']=number_format($row->FLapAmt,2);
		$records[$row->FSupportID]['details'][$item-1]['FPartAmt']=number_format($row->FPartAmt,2);
		$records[$row->FSupportID]['details'][$item-1]['FOth_detail']=$row->FOth_detail;
		
		$records[$row->FSupportID]['details'][$item-1]['id_worklate'] = $row->id_worklate;
		$records[$row->FSupportID]['details'][$item-1]['detail_worklate'] = $row->detail_worklate;
	} 
	$item++;
}

$header ='<table width="99%" align="center"  border="0" cellspacing="0" cellpadding="0">';
$header.='	<tr>';
$header.='	  <td colspan="2" align="center"><b>รายงานสรุปสถานะงานแจ้งซ่อมแยกตามเจ้าหน้าที่</b></td>';
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
if($status=='excel'){
	$header.='   <td colspan="2"><table width="100%" border="1" cellspacing="0" cellpadding="0" bgcolor="#ffffe0">';
}else{
	$header.='    <td colspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="0">';
}
$header.='      <tr>';
$header.='      	<td width="11%" rowspan="2" align="center"  class="tlb_bg"><b>เจ้าหน้าที่ที่รับผิดชอบ</b></td>';
$header.='     	<td width="6%" rowspan="2" align="center" class="tlb_bg"><b>Req No.</b></td>';
$header.='      	<td width="6%" rowspan="2" align="center" class="tlb_bg"><b>วันที่แจ้ง</b></td>';
$header.='      	<td width="13%" rowspan="2" align="center" class="tlb_bg"><b>แผนก</b></td>';
$header.='      	<td width="3%" rowspan="2" align="center" class="tlb_bg"><b>สาขา</b></td>';
$header.='      	<td width="3%" rowspan="2" align="center" class="tlb_bg"><b>ประเภทงาน</b></td>';
$header.='      	<td width="3%" rowspan="2" align="center" class="tlb_bg"><b>ซ่อมเอง</b></td>';
$header.='      	<td width="3%" rowspan="2" align="center" class="tlb_bg"><b>ผรม.ดำเนินการ</b></td>';
$header.='		<td colspan="15" align="center" class="tlb_bg"><b>สถานะ</b></td>';
$header.='       <td width="5%" align="center" class="tlb_bg" colspan='.$numRows_topic.'><b>เกินกำหนด</b></td>';
$header.='       <td width="1.5%" align="center" class="tlb_bg" rowspan="2"><b>อื่นๆ</b></td>';
$header.='       <td width="1.5%" align="center" class="tlbr_bg" rowspan="2"><b>รายละเอียดปัญหา</b></td>';////tlbr_bg
$header.='		</tr>';
$header.='		<tr>';
$header.='			<td width="6%" align="center" class="lb_bg"><b>แจ้งซ่อม</b></td>';
$header.='			<td width="6%" align="center" class="lb_bg"><b>คำร้องอนุมัติ ผจก สาขา</b></td>';
$header.='			<td width="6%" align="center" class="lb_bg"><b>ไม่อนุมัติ</b></td>';
$header.='			<td width="6%" align="center" class="lb_bg"><b>รอดำเนินการ</b></td>';
$header.='			<td width="6%" align="center" class="lb_bg"><b>Apporve</b></td>';
$header.='			<td width="6%" align="center" class="lb_bg"><b>กำลังดำเนินการ</b></td>';
$header.='			<td width="6%" align="center" class="lb_bg"><b>รายละเอียด / ปัญหา</b></td>';
$header.='			<td width="6%" align="center" class="lb_bg"><b>ปิดงาน 1</b></td>';
$header.='			<td width="6%" align="center" class="lb_bg"><b>ตรวจรับงาน 1</b></td>';
$header.='			<td width="6%" align="center" class="lb_bg"><b>ปิดงาน 2</b></td>';
$header.='			<td width="6%" align="center" class="lb_bg"><b>ตรวจรับงาน 2</b></td>';
$header.='			<td width="6%" align="center" class="lb_bg"><b>ระยะเวลา</b></td>';
$header.='			<td width="6%" align="center" class="lb_bg"><b>ค่าแรง</b></td>';
$header.='			<td width="6%" align="center" class="lb_bg"><b>ค่าอะไหล่</b></td>';
$header.='			<td width="6%" align="center" class="lb_bg"><b>สรุปงาน</b></td>';
for($i=1;$i<=$numRows_topic;$i++){
$header.='           <td align="center" class="lb_bg" width="1.5%"><b>'.$show[$i].'</b></td>';///lrb_bg
}
$header.='		</tr>';
$tr_item ='<tr>';
$tr_item.='		<td class="##class##">&nbsp;##FSupport##</td>';
$tr_item.='		<td align="center" class="lb">&nbsp;##FReqNo##</td>';
$tr_item.='		<td class="lb">&nbsp;##FReqDate##</td>';
$tr_item.='		<td align="center" class="lb">&nbsp;##sec_nameThai##</td>';
$tr_item.='		<td align="center" class="lb">&nbsp;##brn_code##</td>';
$tr_item.='		<td align="center" class="lb">&nbsp;##FJobLevel##</td>';
$tr_item.='		<td align="center" class="lb">&nbsp;##FJobresult##</td>';
$tr_item.='		<td align="center" class="lb">&nbsp;##FJobresult2##</td>';
$tr_item.='		<td align="center" class="lb">&nbsp;##FReqDate##</td>';
$tr_item.='		<td align="center" class="lb">&nbsp;##approve_date##</td>';
$tr_item.='		<td align="center" class="lb">&nbsp;##NOAPPROVE##</td>';
$tr_item.='		<td align="center" class="lb">&nbsp;##NEW##</td>';
$tr_item.='		<td align="center" class="lb">&nbsp;##APPROVE##</td>';
$tr_item.='		<td align="center" class="lb">&nbsp;##PROCESS##</td>';
$tr_item.='		<td align="center" class="lb">&nbsp;##FDetail##</td>';
$tr_item.='		<td align="center" class="lb">&nbsp;##FFinishDate##</td>';
$tr_item.='		<td align="center" class="lb">&nbsp;##closejob_date##</td>';
$tr_item.='		<td align="center" class="lb">&nbsp;##closejob_emp_date##</td>';
$tr_item.='		<td align="center" class="lb">&nbsp;##closejob_date2##</td>';
$tr_item.='		<td align="center" class="lb">&nbsp;##DateDiffshow##</td>';

$tr_item.='		<td align="center" class="lb">&nbsp;##FLapAmt##</td>';
$tr_item.='		<td align="center" class="lb">&nbsp;##FPartAmt##</td>';
$tr_item.='		<td align="center" class="lb">&nbsp;##FOth_detail##</td>';
for($i=1;$i<=$numRows_topic;$i++){
$tr_item.='         <td align="center" class="lb">&nbsp;##topic'.$id_show[$i].'##</td>';////lrb
}
$tr_item.='         <td align="center" valign="top" class="lb">&nbsp;##other##</td>';
$tr_item.='         <td align="center" valign="top" class="lrb">&nbsp;##Detail_worklate##</td>';
$tr_item.='</tr>';

$footer ='</table></td>';
$footer.='  </tr>';
$footer.='</table>';
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-874">
<title>รายงานสรุปสถานะงานแจ้งซ่อมแยกตามเจ้าหน้าที่</title>
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
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
 				$ControlFin = date ("Y-m-d", strtotime("+".$row['NumDateJob']." day", strtotime($row['FReqDate'])));
				if($row['id_worklate']!=''){
						$topic=$row['id_worklate'];
				}else{
						$topic="Oth";
				}
if($status!='excel'){
		if($row['FFinishDate']!=''){
					if($row['FFinishDate']<=$ControlFin){
							$problem_work_late[$topic][$row['FReqNo']]="";
					}else{
							$problem_work_late[$topic][$row['FReqNo']]="<img src=\"../../../images/OK.gif\" width=\"16\" height=\"15\">";
					}
		}else if($row['FFinishDate']==''){
					if($ControlFin>=date('Y-m-d')){
							$problem_work_late[$topic][$row['FReqNo']]="";
					}else{
						  $problem_work_late[$topic][$row['FReqNo']]="<img src=\"../../../images/OK.gif\" width=\"16\" height=\"15\">";
					}	
		}
}else{
		if($row['FFinishDate']!=''){
					if($row['FFinishDate']<=$ControlFin){
							$problem_work_late[$topic][$row['FReqNo']]="";
					}else{
							/* $problem_work_late[$topic][$row['FReqNo']]="<img src=\"../../../../images/OK.gif\" width=\"16\" height=\"15\">"; */
							$problem_work_late[$topic][$row['FReqNo']]="<font face=\"Wingdings 2\">P</font>";
					}
		}else if($row['FFinishDate']==''){
					if($ControlFin>=date('Y-m-d')){
							$problem_work_late[$topic][$row['FReqNo']]="";
					}else{
						  /* $problem_work_late[$topic][$row['FReqNo']]="<img src=\"../../../../images/OK.gif\" width=\"16\" height=\"15\">"; */
						  $problem_work_late[$topic][$row['FReqNo']]="<font face=\"Wingdings 2\">P</font>";
					}	
		}	
}
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
				 $_tr_item = str_replace("##class##",$class,$tr_item);
				 $_tr_item = str_replace("##FSupport##",$row['FSupport'],$_tr_item);
				 $_tr_item = str_replace("##FReqNo##",$row['FReqNo'],$_tr_item);
				 $_tr_item = str_replace("##FReqDate##",$row['FReqDate'],$_tr_item);
				 $_tr_item = str_replace("##sec_nameThai##",$row['sec_nameThai'],$_tr_item);
				 $_tr_item = str_replace("##brn_code##",$row['brn_code'],$_tr_item);
				 $_tr_item = str_replace("##FJobLevel##",$row['FJobLevel'],$_tr_item);
				
				$_tr_item = str_replace("##FJobresult##",$row['FJobresult'],$_tr_item);
				
				$_tr_item = str_replace("##FJobresult2##",$row['FJobresult2'],$_tr_item);
				
				 $_tr_item = str_replace("##NEW##",$row['NEW'],$_tr_item);
				 $_tr_item = str_replace("##NOAPPROVE##",$row['NOAPPROVE'],$_tr_item);
				 $_tr_item = str_replace("##PROCESS##",$row['PROCESS'],$_tr_item);
				 $_tr_item = str_replace("##APPROVE##",$row['APPROVE'],$_tr_item);
				 $_tr_item = str_replace("##COMPLETE##",$row['COMPLETE'],$_tr_item);
				 $_tr_item = str_replace("##CLOSEJOB1##",$row['CLOSEJOB1'],$_tr_item);
				 $_tr_item = str_replace("##CLOSEJOB2##",$row['CLOSEJOB1'],$_tr_item);
				 $_tr_item = str_replace("##FReqDate##",$row['FReqDate'],$_tr_item);
				 $_tr_item = str_replace("##approve_date##",$row['approve_date'],$_tr_item);
				  $_tr_item = str_replace("##FDetail##",$row['FDetail'],$_tr_item);
				 $_tr_item = str_replace("##FFinishDate##",$row['FFinishDate'],$_tr_item);
				 $_tr_item = str_replace("##closejob_date##",$row['closejob_date'],$_tr_item);
				 $_tr_item = str_replace("##closejob_emp_date##",$row['closejob_emp_date'],$_tr_item);
				 $_tr_item = str_replace("##closejob_date2##",$row['closejob_date2'],$_tr_item);
				 $_tr_item = str_replace("##DateDiffshow##",DateDiffshow($row["approve_date"],$row["FFinishDate"]),$_tr_item);
				 
				 $_tr_item = str_replace("##FLapAmt##",$row['FLapAmt'],$_tr_item);
				 $_tr_item = str_replace("##FPartAmt##",$row['FPartAmt'],$_tr_item);
				 $_tr_item = str_replace("##FOth_detail##",$row['FOth_detail'],$_tr_item);
				 
			 for($r=1;$r<=$numRows_topic;$r++){
				 $_tr_item = str_replace("##topic".$id_show[$r]."##",$problem_work_late[$id_show[$r]][$row['FReqNo']],$_tr_item);
			}
				 $_tr_item = str_replace("##other##",$problem_work_late["Oth"][$row['FReqNo']],$_tr_item);////////////////////////Other
				 $_tr_item = str_replace("##Detail_worklate##",$row['detail_worklate'],$_tr_item);
				   
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
						$_FSupport = $row['FSupport'];
				 	}else{
						$_FSupport = "";
				 	}
////////////////////////////////////////////////////////////////////////START////////////////////////////////////////////////////////////////////////////////////////////////////
 				$ControlFin = date ("Y-m-d", strtotime("+".$row['NumDateJob']." day", strtotime($row['FReqDate'])));
				if($row['details'][$index]['id_worklate']!=''){
						$topic=$row['details'][$index]['id_worklate'];
				}else{
						$topic="Oth";
				}
if($status!='excel'){
		if($row['details'][$index]['FFinishDate']!=''){
					if($row['details'][$index]['FFinishDate']<=$ControlFin){
							$problem_work_late[$topic][$row['details'][$index]['FReqNo']]="";
					}else{
							$problem_work_late[$topic][$row['details'][$index]['FReqNo']]="<img src=\"../../../images/OK.gif\" width=\"16\" height=\"15\">";
					}
		}else if($row['FFinishDate']==''){
					if($ControlFin>=date('Y-m-d')){
							$problem_work_late[$topic][$row['details'][$index]['FReqNo']]="";
					}else{
						  $problem_work_late[$topic][$row['details'][$index]['FReqNo']]="<img src=\"../../../images/OK.gif\" width=\"16\" height=\"15\">";
					}	
		}
}else{
		if($row['details'][$index]['FFinishDate']!=''){
					if($row['details'][$index]['FFinishDate']<=$ControlFin){
							$problem_work_late[$topic][$row['details'][$index]['FReqNo']]="";
					}else{
							/* $problem_work_late[$topic][$row['FReqNo']]="<img src=\"../../../../images/OK.gif\" width=\"16\" height=\"15\">"; */
							$problem_work_late[$topic][$row['details'][$index]['FReqNo']]="<font face=\"Wingdings 2\">P</font>";
					}
		}else if($row['details'][$index]['FFinishDate']==''){
					if($ControlFin>=date('Y-m-d')){
							$problem_work_late[$topic][$row['details'][$index]['FReqNo']]="";
					}else{
						  /* $problem_work_late[$topic][$row['FReqNo']]="<img src=\"../../../../images/OK.gif\" width=\"16\" height=\"15\">"; */
						  $problem_work_late[$topic][$row['details'][$index]['FReqNo']]="<font face=\"Wingdings 2\">P</font>";
					}	
		}	
}
////////////////////////////////////////////////////////////////////////END////////////////////////////////////////////////////////////////////////////////////////////////////////
				    $_tr_item = str_replace("##class##",$class,$tr_item);
				    $_tr_item = str_replace("##FSupport##",$_FSupport,$_tr_item);
				    $_tr_item = str_replace("##FReqNo##",$row['details'][$index]['FReqNo'],$_tr_item);
				    $_tr_item = str_replace("##FReqDate##",$row['details'][$index]['FReqDate'],$_tr_item);
				    $_tr_item = str_replace("##sec_nameThai##",$row['details'][$index]['sec_nameThai'],$_tr_item);
				    $_tr_item = str_replace("##brn_code##",$row['details'][$index]['brn_code'],$_tr_item);
					$_tr_item = str_replace("##FJobLevel##",$row['details'][$index]['FJobLevel'],$_tr_item);
					
					$_tr_item = str_replace("##FJobresult##",$row['details'][$index]['FJobresult'],$_tr_item);
					
					$_tr_item = str_replace("##FJobresult2##",$row['details'][$index]['FJobresult2'],$_tr_item);
				
				    $_tr_item = str_replace("##NEW##",$row['details'][$index]['NEW'],$_tr_item);
					$_tr_item = str_replace("##NOAPPROVE##",$row['details'][$index]['NOAPPROVE'],$_tr_item);
				    $_tr_item = str_replace("##PROCESS##",$row['details'][$index]['PROCESS'],$_tr_item);
				    $_tr_item = str_replace("##APPROVE##",$row['details'][$index]['APPROVE'],$_tr_item);
				    $_tr_item = str_replace("##COMPLETE##",$row['details'][$index]['COMPLETE'],$_tr_item);
				    $_tr_item = str_replace("##CLOSEJOB1##",$row['details'][$index]['CLOSEJOB1'],$_tr_item);
				    $_tr_item = str_replace("##CLOSEJOB2##",$row['details'][$index]['CLOSEJOB2'],$_tr_item);
					$_tr_item = str_replace("##FReqDate##",$row['details'][$index]['FReqDate'],$_tr_item);
					$_tr_item = str_replace("##approve_date##",$row['details'][$index]['approve_date'],$_tr_item);
					$_tr_item = str_replace("##FDetail##",$row['details'][$index]['FDetail'],$_tr_item);
					$_tr_item = str_replace("##FFinishDate##",$row['details'][$index]['FFinishDate'],$_tr_item);
					$_tr_item = str_replace("##closejob_date##",$row['details'][$index]['closejob_date'],$_tr_item);
					$_tr_item = str_replace("##closejob_emp_date##",$row['details'][$index]['closejob_emp_date'],$_tr_item);
					$_tr_item = str_replace("##closejob_date2##",$row['details'][$index]['closejob_date2'],$_tr_item);		
					$_tr_item = str_replace("##DateDiffshow##",$row['details'][$index]['DateDiffshow'],$_tr_item);		
					
				$_tr_item = str_replace("##FLapAmt##",$row['details']['FLapAmt'][$index],$_tr_item);
				$_tr_item = str_replace("##FPartAmt##",$row['details']['FPartAmt'][$index],$_tr_item);
				$_tr_item = str_replace("##FOth_detail##",$row['details']['FOth_detail'][$index],$_tr_item);
				
			for($r=1;$r<=$numRows_topic;$r++){
				 $_tr_item = str_replace("##topic".$id_show[$r]."##",$problem_work_late[$id_show[$r]][$row['details'][$index]['FReqNo']],$_tr_item);
			}
				 $_tr_item = str_replace("##other##",$problem_work_late["Oth"][$row['details'][$index]['FReqNo']],$_tr_item);////////////////////////Other
				 $_tr_item = str_replace("##Detail_worklate##",$row['details'][$index]['detail_worklate'],$_tr_item);
				 
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
     		if($item<$rowPerPage && $item>0){
     			print($footer);
     		}
      	  }/*End of if(!empty($_array))*/
      ?>
</body>
</html>