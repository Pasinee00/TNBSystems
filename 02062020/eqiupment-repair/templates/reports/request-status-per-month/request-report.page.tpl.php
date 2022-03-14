<?php 
include '../../../../lib/db_config.php';
include '../../../../main/modules/Model_Utilities.php';
//ini_set('max_execution_time',60000);
//set_time_limit (60000);
if($cmd=='excel'){
	$filename = "excel_report".".xls";	
	header("Content-Disposition: attachment; filename=\"$filename\"");
	header("Content-Type: application/vnd.ms-excel"); 
}
ini_set('memory_limit', '2048M');
ini_set("max_execution_time" ,30000);
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
 /* LEFT JOIN pis_db.tbl_employee AS t2 ON (t2.emp_id = t1.FReqID)
LEFT JOIN pis_db.tbl_employeehist AS t3 ON (t3.emp_code = t2.emp_code AND (t3.emp_flg IS NULL OR t3.emp_flg = '')) */
$query ="SELECT
t1.FRequestID,
t1.FJobresult,
CASE
				WHEN mtrequest_db.t1.FJobLevel ='L' THEN '3'
				WHEN mtrequest_db.t1.FJobLevel ='M' THEN '3'
				WHEN mtrequest_db.t1.FJobLevel ='M1' THEN '7'
				WHEN mtrequest_db.t1.FJobLevel ='M2' THEN '15'
				WHEN mtrequest_db.t1.FJobLevel ='H' THEN '30'
				ELSE '0'
			END AS NumDateJob,
t1.FReqNo,
t1.FReqDate,
t1.FReciveDate,
t1.FDetail,
t1.FEditDate,
t1.FFinishDate,
t1.FJobLevel,
t1.FMachineTypeID,
t4.sec_nameThai,
t5.FMachineTypeName,
t5.FJob_description,
t6.FJobLevel_name,
t1.id_worklate,
t1.detail_worklate
FROM
mtrequest_db.tbl_request AS t1

LEFT JOIN pis_db.tbl_section AS t4 ON (t4.sec_id = t1.FSectionID)
LEFT JOIN general_db.tbl_machinetype AS t5 ON (t1.FMachineTypeID = t5.FMachineTypeID)
LEFT JOIN general_db.tbl_fjoblevel AS t6 ON t1.FJobresult = t6.FJobresult AND t1.FJobLevel = t6.FJobLevel

WHERE 1 ";
if($FSectionID)$query .=" AND t4.sec_id='{$FSectionID}'";
if($FBranchID)$query .=" AND t1.FBranchID='{$FBranchID}'";
//if($FRepair_comp_id)$query .=" AND t1.FBranchID='{$FRepair_comp_id}'";
if($SRequestDate)$query .=" AND t1.FReqDate>='{$SRequestDate}'";
if($ERequestDate)$query .=" AND t1.FReqDate<='{$ERequestDate}'";
if($SDueDate)$query .=" AND t1.FDueDate>='{$SDueDate}'";
if($EDueDate)$query .=" AND t1.FDueDate<='{$EDueDate}'";
$query .=" ORDER BY t1.FReqNo";
//echo $query;
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
	$records[$row->FRequestID]['FReqDate'] = $row->FReqDate;
	$records[$row->FRequestID]['FReciveDate']= $row->FReciveDate;
	$records[$row->FRequestID]['FDetail']= $row->FDetail;
	$records[$row->FRequestID]['FEditDate']= $row->FEditDate;
	$records[$row->FRequestID]['FFinishDate']= $row->FFinishDate;
	$records[$row->FRequestID]['FJobLevel_name']= $row->FJobLevel_name;
	$records[$row->FRequestID]['NumDateJob']= $row->NumDateJob;
	
	if($row->id_worklate!=''){
			$topic=$row->id_worklate;
		}else{
			$topic="Oth";
	}
	$records[$row->FRequestID]['id_worklate']= $row->id_worklate;
	$records[$row->FRequestID]['detail_worklate']= $row->detail_worklate;
	
	if($row->FJobresult=="1"){
		$records[$row->FRequestID]['FJobresult']="<img src=\"../../../../images/OK.gif\" width=\"16\" height=\"15\">";
		if($cmd=='excel'){
			$records[$row->FRequestID]['FJobresult']="<font face=\"Wingdings 2\">P</font>";
		}
		
	}else if($row->FJobresult=="2"){
		$records[$row->FRequestID]['FJobresult2']="<img src=\"../../../../images/OK.gif\" width=\"16\" height=\"15\">";
		if($cmd=='excel'){
			$records[$row->FRequestID]['FJobresult2']="<font face=\"Wingdings 2\">P</font>";
		}
	}
	$records[$row->FRequestID]['sec_nameThai']= $row->sec_nameThai;
    $records[$row->FRequestID]['FJob_description']= $row->FJob_description;
	if($row->FJob_description=="1"){
		$records[$row->FRequestID]['FJob_description']="งานทั่วไป";
	}else if($row->FJob_description=="2"){
		$records[$row->FRequestID]['FJob_description']="งานเครื่องจักร";
	}else if($row->FJob_description=="3"){
		$records[$row->FRequestID]['FJob_description']="ขอใช้แบบฟอร์ม";
	}else if($row->FJob_description=="4"){
		$records[$row->FRequestID]['FJob_description']="งานนอกแผน";
	}else if($row->FJob_description=="5"){
		$records[$row->FRequestID]['FJob_description']="งานโปรเจ็ค";
	}else if($row->FJob_description=="6"){
		$records[$row->FRequestID]['FJob_description']="งานที่รอผู้บริหารพิจารณา";
	}
	$records[$row->FRequestID]['FMachineTypeName']= $row->FMachineTypeName;
}

$header = '<table width="200%" border="0" cellspacing="0" cellpadding="0">';
$header.='  <tr>';
$header.=' 		<td colspan="2" align="center"><b>รายงานสรุปประจำเดือน</b></td>';
$header.=' 	 </tr>';
$header.=' 	 <tr>';
$header.=' 		<td width="91%">'.get_comThai($FRepair_comp_id).'</td>';
$header.=' 		<td width="9%" align="right"><b>Page :</b> ##PAGE##/'.$totalPage.'&nbsp;</td>';
$header.=' 	  </tr>';
$header.=' 	  <tr>';
$header.=' 	    <td colspan="2">&nbsp;'.$title.'</td>';
$header.=' 	  </tr>';
$header.=' 	  <tr>';
$header.=' 	    <td height="4" colspan="2"></td>';
$header.=' 	  </tr>';
$header.=' 	  <tr>';
if($cmd=='excel'){
$header.=' 	    <td colspan="2"><table width="100%" border="1" cellspacing="0" cellpadding="0" bgcolor="#FFE9E9">';
}else{$header.=' 	    <td colspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="0">';}
$header.=' 	      <tr>';
$header.=' 	        <td width="0.5%" align="center" rowspan="2" class="tlb_bg"><b>ลำดับ</b></td>';
$header.=' 	        <td width="2%" align="center" colspan="2" class="tlb_bg"><b>วันที่</b></td>';
$header.=' 	        <td width="1%" align="center"  rowspan="2" class="tlb_bg"><b>เลขที่ใบแจ้งซ่อม</b></td>';
$header.=' 	        <td width="3%" rowspan="2" align="center" class="tlb_bg"><b>แผนก</b></td>';
$header.=' 	        <td width="7%" rowspan="2" align="center" class="tlb_bg"><b>ปัญหางาน</b></td>';
$header.=' 	        <td width="1%" rowspan="2" align="center" class="tlb_bg"><b>ประเภทงาน</b></td>';
$header.=' 	        <td width="4%" rowspan="2" align="center" class="tlb_bg"><b>ลักษณะงาน</b></td>';
$header.=' 	        <td width="2%" rowspan="2" align="center" class="tlb_bg"><b>รายการเครื่องจักร</b></td>';
$header.=' 	        <td width="1.5%" rowspan="2" align="center" class="tlb_bg"><b>ซ่อมเอง</b></td>';
$header.=' 	        <td width="1%" rowspan="2" align="center" class="tlb_bg"><b>ผรม.ดำเนินการ</b></td>';
$header.=' 	        <td width="10%"  colspan="2"  align="center" class="tlb_bg"><b>วันที่</b></td>';
$header.=' 	        <td width="10%" align="center" class="tlb_bg" colspan='.$numRows_topic.'><b>เกินกำหนด</b></td>';
$header.=' 	        <td rowspan="2" width="0.5%" align="center" class="tlb_bg"><b>อื่นๆ</b></td>';
$header.=' 	        <td rowspan="2" width="0.5%" align="center" class="tlbr_bg"><b>รายละเอียดปัญหา</b></td>';

$header.=' 	      </tr>';
$header.=' 	      <tr>';
$header.=' 	      		<td align="center" class="lb_bg" width="2%"><b>หน่วยงานแจ้ง</b></td>';
$header.=' 	      		<td align="center" class="lb_bg" width="2%"><b>MT รับเรื่อง</b></td>';
$header.=' 	      		<td align="center" class="lb_bg" width="2%"><b>ตรวจสอบ</b></td>';
$header.=' 	      		<td align="center" class="lb_bg" width="2%"><b>ปิดงาน</b></td>';
for($i=1;$i<=$numRows_topic;$i++){
	if($i==$numRows_topic){
$header.=' 	      		<td align="center" class="lb_bg" width="1%"><b>'.$show[$i].'</b></td>';/////lrb_bg
	}else{
$header.=' 	      		<td align="center" class="lb_bg" width="1%"><b>'.$show[$i].'</b></td>';
	}
}
$header.=' 	      </tr>';

$tr_item='<tr>';
$tr_item.=' 		<td align="center" class="lb">&nbsp;##i##</td>';
$tr_item.='      <td align="center" class="lb">&nbsp;##FReqDate##</td>';
$tr_item.='       <td align="center" class="lb">&nbsp;##FReciveDate##</td>';
$tr_item.='        <td align="center" class="lb">&nbsp;##FReqNo##</td>';
$tr_item.='        <td class="lb">&nbsp;##sec_nameThai##</td>';
$tr_item.='        <td align="left" class="lb">&nbsp;##FDetail##</td>';
$tr_item.='        <td align="center" class="lb">##FJobLevel_name##</td>';
$tr_item.='        <td align="center" class="lb">##FJob_description##</td>';
$tr_item.='        <td align="center" class="lb">##FMachineTypeName##</td>';
$tr_item.='        <td align="center" class="lb">##FJobresult##</td>';
$tr_item.='        <td align="center" class="lb">##FJobresult2##</td>';
$tr_item.='        <td align="center" class="lb">&nbsp;##FEditDate##</td>';
$tr_item.='        <td align="center" class="lb">&nbsp;##FFinishDate##</td>';
for($i=1;$i<=$numRows_topic;$i++){
	if($i==$numRows_topic){
$tr_item.='        <td align="center" class="lb">&nbsp;##topic'.$id_show[$i].'##</td>';/////lrb
	}else{
$tr_item.='        <td align="center" class="lb">&nbsp;##topic'.$id_show[$i].'##</td>';
	}
}
$tr_item.='        <td align="center" class="lb">&nbsp;##Other##</td>';
$tr_item.='        <td align="center" class="lrb">&nbsp;##Detail_worklate##</td>';
$tr_item.=' </tr>';

$footer ='</table></td>';
$footer.='  </tr>';
$footer.='</table>';
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-874">
<title>รายงานสรุปประจำเดือน</title>

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
	//echo $row['FReqDate']."****"."<br>";
 	 $ControlFin = date ("Y-m-d", strtotime("+".$row['NumDateJob']." day", strtotime($row['FReqDate'])));
	if($row['id_worklate']!=''){
				$topic=$row['id_worklate'];
	}else{
				$topic="Oth";
	}
if($cmd!='excel'){
		if($row['FFinishDate']!=''){
					if($row['FFinishDate']<=$ControlFin){
							$problem_work_late[$topic][$row['FReqNo']]="";
					}else{
							$problem_work_late[$topic][$row['FReqNo']]="<img src=\"../../../../images/OK.gif\" width=\"16\" height=\"15\">";
					}
		}else if($row['FFinishDate']==''){
					if($ControlFin>=date('Y-m-d')){
							$problem_work_late[$topic][$row['FReqNo']]="";
					}else{
						   $problem_work_late[$topic][$row['FReqNo']]="<img src=\"../../../../images/OK.gif\" width=\"16\" height=\"15\">";
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
			}elseif($row['FFinishDate']==''){
						if($ControlFin>=date('Y-m-d')){
								$problem_work_late[$topic][$row['FReqNo']]="";
						}else{
							  /* $problem_work_late[$topic][$row['FReqNo']]="<img src=\"../../../../images/OK.gif\" width=\"16\" height=\"15\">"; */
							  $problem_work_late[$topic][$row['FReqNo']]="<font face=\"Wingdings 2\">P</font>";
						}	
			}
}
/////////////////////////////////////////////////////////////////////////////////////////////////////
				 $_tr_item = str_replace("##class##",$class,$tr_item);
				 $_tr_item = str_replace("##i##",$i,$_tr_item);
				 $_tr_item = str_replace("##FReqDate##",$utilMD->convertDate2Thai($row['FReqDate'],"dd-sm"),$_tr_item);
				 $_tr_item = str_replace("##FReciveDate##",$utilMD->convertDate2Thai($row['FReciveDate'],"dd-sm"),$_tr_item);
				 $_tr_item = str_replace("##FReqNo##",$row['FReqNo'],$_tr_item);
				 $_tr_item = str_replace("##sec_nameThai##",$row['sec_nameThai'],$_tr_item);
				 $_tr_item = str_replace("##FDetail##",$row['FDetail'],$_tr_item);
				 $_tr_item = str_replace("##FJobLevel_name##",$row['FJobLevel_name'],$_tr_item);
				 $_tr_item = str_replace("##FJobresult##",$row['FJobresult'],$_tr_item);
				 $_tr_item = str_replace("##FJob_description##",$row['FJob_description'],$_tr_item);
				$_tr_item = str_replace("##FMachineTypeName##",$row['FMachineTypeName'],$_tr_item);
				$_tr_item = str_replace("##FJobresult2##",$row['FJobresult2'],$_tr_item);
				 $_tr_item = str_replace("##FEditDate##",$utilMD->convertDate2Thai($row['FEditDate'],"dd-sm"),$_tr_item);
				 $_tr_item = str_replace("##FFinishDate##",$utilMD->convertDate2Thai($row['FFinishDate'],"dd-sm"),$_tr_item);
				 /////////////////////////////////////////////////////
		for($r=1;$r<=$numRows_topic;$r++){
				 $_tr_item = str_replace("##topic".$id_show[$r]."##",$problem_work_late[$id_show[$r]][$row['FReqNo']],$_tr_item);
		}
				$_tr_item = str_replace("##Other##",$problem_work_late["Oth"][$row['FReqNo']],$_tr_item);
				$_tr_item = str_replace("##Detail_worklate##",$row['detail_worklate'],$_tr_item);
				 /////////////////////////////////////////////////////
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