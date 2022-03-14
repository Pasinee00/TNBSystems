<?php 
include '../../../../lib/db_config.php';
include '../../../../main/modules/Model_Utilities.php';
include '../../../modules/purchase_model.php';
if($status=='excel'){
	 $filename = "excel_report".".xls";	
	header("Content-Disposition: attachment; filename=\"$filename\"");
	header("Content-Type: application/vnd.ms-excel"); 
}

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

if($SReciveDate) $title .=(empty($title))?"วันที่รับเรื่องตั้งแต่วันที่ : ".$utilMD->convertDate2Thai($SReciveDate,"dd-sm"):"&nbsp;&nbsp;วันที่รับเรื่องตั้งแต่วันที่ : ".$utilMD->convertDate2Thai($SReciveDate,"dd-sm");
if($EReciveDate){
	if(empty($SReviceDate))$title .=(empty($title))?"วันที่รับเรื่องถึงวันที่ : ".$utilMD->convertDate2Thai($EReciveDate,"dd-sm"):"&nbsp;&nbsp;วันที่รับเรื่องถึงวันที่ : ".$utilMD->convertDate2Thai($EReciveDate,"dd-sm");
	else  $title .=" - ".$utilMD->convertDate2Thai($EReciveDate,"dd-sm");
}
if($JobLevel)$title .=(empty($title))?"`ประเภทงาน : ".$JobLevel:"&nbsp;&nbsp;ประเภทงาน : ".$JobLevel;

$query ="SELECT t1.FRequestID,
t1.FReqNo,
t1.FReqDate,
t1.FReciveDate,
t1.FDetail,
CASE
		WHEN mtrequest_db.t1.FJobLevel ='L' THEN '3'
		WHEN mtrequest_db.t1.FJobLevel ='M' THEN '3'
		WHEN mtrequest_db.t1.FJobLevel ='M1' THEN '7'
		WHEN mtrequest_db.t1.FJobLevel ='M2' THEN '15'
		WHEN mtrequest_db.t1.FJobLevel ='H' THEN '30'
	ELSE '0'
END AS NumDateJob,
t1.FEditDate,
t1.FFinishDate,
t1.FJobLevel,
t1.FStatus,
t1.FOth_detail,
t1.detail_worklate,
t1.id_worklate"
.",t6.first_name,t6.last_name "
."FROM mtrequest_db.tbl_request t1 "
."LEFT JOIN pis_db.tbl_employee t2 ON(t2.emp_id = t1.FReqID) "
."LEFT JOIN mtrequest_db.tbl_requestowner t5 ON(t5.FRequestID = t1.FRequestID) "
."LEFT JOIN pis_db.tbl_user t6 ON(t6.user_id = t5.FSupportID) "
."WHERE t1.FStatus IN('waiting','inprogress') ";
if($FSectionID)$query .=" AND t1.FSectionID='{$FSectionID}'";
if($FBranchID)$query .=" AND t1.FBranchID='{$FBranchID}'";
//if($FRepair_comp_id)$query .=" AND t1.FBranchID='{$FRepair_comp_id}'";
if($SRequestDate)$query .=" AND t1.FReqDate>='{$SRequestDate}'";
if($ERequestDate)$query .=" AND t1.FReqDate<='{$ERequestDate}'";
if($SReciveDate)$query .=" AND t1.FReciveDate>='{$SReciveDate}'";
if($EReciveDate)$query .=" AND t1.FReciveDate<='{$EReciveDate}'";
if($JobLevel)$query .=" AND t1.FJobLevel='{$JobLevel}'";
$query .=" ORDER BY t1.FReqNo";
//echo "<br>".$query;
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
	$records[$row->FRequestID]['FOth_detail']= str_replace("<br>"," ",$row->FOth_detail);
	$records[$row->FRequestID]['FEditDate']= $row->FEditDate;
	$records[$row->FRequestID]['FFinishDate']= $row->FFinishDate;
	$records[$row->FRequestID]['FJobLevel']= $row->FJobLevel;
	$records[$row->FRequestID]['sec_nameThai']= $row->sec_nameThai;
	$records[$row->FRequestID]['FSupportName']= $row->first_name."&nbsp;&nbsp;".$row->last_name;
	$records[$row->FRequestID]['PR'] ="";
	$records[$row->FRequestID]['PO'] = "";
if($status!='excel'){
	$records[$row->FRequestID]['Start'] = ($row->FStatus=='inprogress' && ($row->FEditDate!='' && $row->FEditDate!='0000-00-00'))?'<img src="../../../../images/OK.gif">':'';
	$records[$row->FRequestID]['Not'] = (($row->FStatus=='waiting' || $row->FStatus=='inprogress') && ($row->FEditDate=='' || $row->FEditDate=='0000-00-00'))?'<img src="../../../../images/OK.gif">':'';
}else{
	$records[$row->FRequestID]['Start'] = ($row->FStatus=='inprogress' && ($row->FEditDate!='' && $row->FEditDate!='0000-00-00'))?'<font face="Wingdings 2">P</font>':'';
	$records[$row->FRequestID]['Not'] = (($row->FStatus=='waiting' || $row->FStatus=='inprogress') && ($row->FEditDate=='' || $row->FEditDate=='0000-00-00'))?'<font face="Wingdings 2">P</font>':'';
}
	$records[$row->FRequestID]['id_worklate'] = $row->id_worklate;
	$records[$row->FRequestID]['detail_worklate'] = $row->detail_worklate;
    if($purMD->checkPRStatus($row->FRequestID)){
		if($status!='excel'){
    		$records[$row->FRequestID]['PR'] ='<img src="../../../../images/OK.gif">';
		}else{
			$records[$row->FRequestID]['PR'] ='<font face="Wingdings 2">P</font>';	
		}
    	$records[$row->FRequestID]['PO'] = "";
    	$records[$row->FRequestID]['Start'] = "";
    	$records[$row->FRequestID]['Not'] = "";
      }else if($purMD->checkPOStatus($row->FRequestID)){
      	$records[$row->FRequestID]['PR'] ="";
		if($status!='excel'){
      		$records[$row->FRequestID]['PO'] = '<img src="../../../../images/OK.gif">';
		}else{
			$records[$row->FRequestID]['PO'] = '<font face="Wingdings 2">P</font>';
		}
      	$records[$row->FRequestID]['Start'] = "";
      	$records[$row->FRequestID]['Not'] = "";
      }
	$item++;
}

 $header='<table width="100%" border="0" cellspacing="0" cellpadding="0">'; 
$header.='  <tr>';
$header.='	   <td colspan="2" align="center"><b>รายงานประเภทงานที่เกินกำหนด</b></td>';
$header.='  </tr>';
$header.='  <tr>';
$header.='	   <td width="91%">'.get_comThai($FRepair_comp_id).'</td>';
$header.='	   <td width="9%" align="right"><b>Page :</b> ##PAGE##/'.$totalPage.'&nbsp;</td>';
$header.='  </tr>';
$header.='  <tr>';
$header.='    <td colspan="2">&nbsp;'.$title.'</td>';
$header.='  </tr>';
$header.='  <tr>';
$header.='    <td height="4" colspan="2"></td>';
$header.='  </tr>';
$header.='  <tr>';
if($status=='excel'){
$header.='   <td colspan="2"><table width="100%" border="1" cellspacing="0" cellpadding="0" bgcolor="#FFF8E9">';
}else{$header.='   <td colspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="0">';}
$header.='      <tr>';
$header.='        <td width="3%" rowspan="2" align="center" class="tlb_bg"><b>ลำดับ</b></td>';
$header.='        <td width="10%" rowspan="2" class="tlb_bg" align="center"><b>ผู้รับผิดชอบ</b></td>';
$header.='        <td width="5%" rowspan="2" align="center" class="tlb_bg"><b>วันที่รับเรื่อง</b></td>';
$header.='        <td width="5%" rowspan="2" align="center" class="tlb_bg"><b>เลขที่ใบแจ้งซ่อม</b></td>';
$header.='        <td width="15%" rowspan="2" class="tlb_bg" align="center"><b>ปัญหางาน</b></td>';
$header.='        <td width="5%" colspan="4" align="center" class="tlb_bg"><b>สถานะงาน</b></td>';
$header.='        <td width="5%" align="center" class="tlb_bg" colspan='.$numRows_topic.'><b>เกินกำหนด</b></td>';
$header.='        <td width="1.5%" align="center" class="tlb_bg" rowspan="2"><b>อื่นๆ</b></td>';
$header.='        <td width="1.5%" align="center" class="tlbr_bg" rowspan="2"><b>รายละเอียดปัญหา</b></td>';////tlbr_bg
$header.='      </tr>';     
$header.='      <tr>';
$header.='           <td align="center" class="lb_bg" width="2.5%"><b>PR</b></td>';
$header.='           <td align="center" class="lb_bg" width="2.5%"><b>PO</b></td>';
$header.='           <td align="center" class="lb_bg" width="2.5%"><b>ซ่อม</b></td>';
$header.='           <td align="center" class="lb_bg" width="3%"><b>ยังไม่<br>เริ่มซ่อม</b></td>';
for($i=1;$i<=$numRows_topic;$i++){
$header.='           <td align="center" class="lb_bg" width="1.5%"><b>'.$show[$i].'</b></td>';///lrb_bg
}
$header.='      </tr>';

$tr_item='<tr>';
$tr_item.=' 		   <td align="center" valign="top" class="lb">&nbsp;##i##</td>';
$tr_item.='         <td align="left" valign="top" class="lb">&nbsp;##FSupportName##</td>';
$tr_item.='         <td align="center" valign="top" class="lb">&nbsp;##FReciveDate##</td>';
$tr_item.='         <td align="center" valign="top" class="lb">&nbsp;##FReqNo##</td>';
$tr_item.='         <td class="lb">&nbsp;##FDetail##</td>';
$tr_item.='         <td align="center" valign="top" class="lb">&nbsp;##PR##</td>';
$tr_item.='         <td align="center" valign="top" class="lb">&nbsp;##PO##</td>';
$tr_item.='         <td align="center" valign="top" class="lb">&nbsp;##Start##</td>';
$tr_item.='         <td align="center" valign="top" class="lb">&nbsp;##Not##</td>';
for($i=1;$i<=$numRows_topic;$i++){
$tr_item.='         <td align="center" class="lb">&nbsp;##topic'.$id_show[$i].'##</td>';////lrb
}
$tr_item.='         <td align="center" valign="top" class="lb">&nbsp;##other##</td>';
$tr_item.='         <td align="center" valign="top" class="lrb">&nbsp;##Detail_worklate##</td>';
$tr_item.=' </tr>';

$footer ='</table></td>';
$footer.='  </tr>';
$footer.='</table>';
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-874">
<title>รายงานประเภทงานที่เกินกำหนด</title>

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
				$ControlFin = date ("Y-m-d", strtotime("+".$row['NumDateJob']." day", strtotime($row['FReqDate'])));
////////////////////////////////////////////////////////////////////////////////////////
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
		}else if($row['FFinishDate']==''){
					if($ControlFin>=date('Y-m-d')){
							$problem_work_late[$topic][$row['FReqNo']]="";
					}else{
						  /* $problem_work_late[$topic][$row['FReqNo']]="<img src=\"../../../../images/OK.gif\" width=\"16\" height=\"15\">"; */
						  $problem_work_late[$topic][$row['FReqNo']]="<font face=\"Wingdings 2\">P</font>";
					}	
		}	
}
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
				 $_tr_item = str_replace("##class##",$class,$tr_item);
				 $_tr_item = str_replace("##i##",$i,$_tr_item);
				 $_tr_item = str_replace("##FSupportName##",$row['FSupportName'],$_tr_item);
				 $_tr_item = str_replace("##FReciveDate##",$utilMD->convertDate2Thai($row['FReciveDate'],"dd-sm"),$_tr_item);
				 $_tr_item = str_replace("##FReqNo##",$row['FReqNo'],$_tr_item);
				 $_tr_item = str_replace("##FDetail##",$row['FOth_detail'],$_tr_item);
				 $_tr_item = str_replace("##PR##",$row['PR'],$_tr_item);
				 $_tr_item = str_replace("##PO##",$row['PO'],$_tr_item);
				 $_tr_item = str_replace("##Start##",$row['Start'],$_tr_item);
				 $_tr_item = str_replace("##Not##",$row['Not'],$_tr_item);
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
     		}/*End of foreach($_array as $key=>$val)*/
     		if($item<$rowPerPage){
     			print($footer);
     		}
      	  }/*End of if(!empty($_array))*/
      ?>
</body>
</html>