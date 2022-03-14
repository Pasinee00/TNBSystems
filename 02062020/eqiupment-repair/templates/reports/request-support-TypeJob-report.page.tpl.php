<?php
include '../../../lib/db_config.php';
include '../../../main/modules/Model_Utilities.php';
include '../../../general_sys/modules/suplier_model.php';
if($_REQUEST['excel_report']!=''){
	$filename = "excel_report".".xls";	
	header("Content-Disposition: attachment; filename=\"$filename\"");
	header("Content-Type: application/vnd.ms-excel"); 
	$e=1;
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
function chk_zero($value){
		if($value=='0'){
			return '';
		}else{return $value;}
}
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
function getNameMonth($Month){
		if($Month == "1" || $Month == "01"){
			$strName = "ม.ค.";
		}else if($Month == "2" || $Month == "02"){
			$strName = "ก.พ.";
		}else if($Month == "3" || $Month == "03"){
			$strName = "มี.ค.";
		}else if($Month == "4" || $Month == "04"){
			$strName = "เม.ย.";
		}else if($Month == "5" || $Month == "05"){
			$strName = "พ.ค.";
		}else if($Month == "6" || $Month == "06"){
			$strName = "มิ.ย.";
		}else if($Month == "7" || $Month == "07"){
			$strName = "ก.ค.";	
		}else if($Month == "8" || $Month == "08"){
			$strName = "ส.ค.";
		}else if($Month == "9" || $Month == "09"){
			$strName = "ก.ย.";
		}else if($Month == "10"){
			$strName = "ต.ค.";
		}else if($Month == "11"){
			$strName = "พ.ย.";
		}else if($Month == "12"){
			$strName = "ธ.ค.";
		}
		
		return $strName;
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

$query ="SELECT
			mtrequest_db.tbl_request.FRequestID,
			mtrequest_db.tbl_request.FReqDate,
			mtrequest_db.tbl_request.FJobLevel,
			mtrequest_db.tbl_requestowner.FSupportID,
			CASE
				WHEN mtrequest_db.tbl_request.FJobLevel ='L' THEN '3'
				WHEN mtrequest_db.tbl_request.FJobLevel ='M' THEN '3'
				WHEN mtrequest_db.tbl_request.FJobLevel ='M1' THEN '7'
				WHEN mtrequest_db.tbl_request.FJobLevel ='M2' THEN '15'
				WHEN mtrequest_db.tbl_request.FJobLevel ='H' THEN '30'
				ELSE '0'
			END AS NumDateJob,
			mtrequest_db.tbl_request.FFinishDate,
			mtrequest_db.tbl_request.FRequestID,
			pis_db.tbl_user.first_name,
			pis_db.tbl_user.last_name,
			mtrequest_db.tbl_request.id_worklate,
			general_db.tbl_worklate.number_topic
			FROM
			mtrequest_db.tbl_request
			LEFT JOIN mtrequest_db.tbl_requestowner ON mtrequest_db.tbl_request.FRequestID = mtrequest_db.tbl_requestowner.FRequestID
			LEFT JOIN pis_db.tbl_user ON mtrequest_db.tbl_requestowner.FSupportID = pis_db.tbl_user.user_id 
			LEFT JOIN general_db.tbl_worklate ON tbl_request.id_worklate = general_db.tbl_worklate.id_worklate
			WHERE 1 ";
if($FSectionID)$query .=" AND tbl_request.FSectionID='{$FSectionID}'";
if($FBranchID)$query .=" AND tbl_request.FBranchID='{$FBranchID}'";
if($FRepair_comp_id)$query .=" AND tbl_request.FRepair_comp_id='{$FRepair_comp_id}'";
if($SRequestDate)$query .=" AND tbl_request.FReqDate>='{$SRequestDate}'";
if($ERequestDate)$query .=" AND tbl_request.FReqDate<='{$ERequestDate}'";
if($SDueDate)$query .=" AND tbl_request.FDueDate>='{$SDueDate}'";
if($EDueDate)$query .=" AND tbl_request.FDueDate<='{$EDueDate}'";
if($SFinishDate)$query .=" AND tbl_request.FFinishDate>='{$SFinishDate}'";
if($EFinishDate)$query .=" AND tbl_request.FFinishDate<='{$EFinishDate}'";
if($Support)$query .=" AND tbl_requestowner.FSupportID IN ({$Support})";
$query .=" GROUP BY tbl_request.FRequestID,
			tbl_requestowner.FSupportID,
			tbl_request.FFinishDate";
$query .=" ORDER BY tbl_requestowner.FSupportID,tbl_request.FReqDate";
//echo $query;
$results = mysql_query($query);
$numRows = mysql_num_rows($results);
$a=1;
while($row=mysql_fetch_assoc($results)){
	$ControlFin = date ("Y-m-d", strtotime("+".$row['NumDateJob']." day", strtotime($row['FReqDate'])));
	$cutDate=explode("-",$row['FReqDate']);
	
	//--------------data Support-------------//
	$Support_numAll[$row['FSupportID']]+=+1;
	if($Support_numAll[$row['FSupportID']]==1 && $row['FSupportID']!=''){
		$Support_num+=+1;
		$Support_name[$Support_num]=$row['first_name']." ".$row['last_name'];
		$Support_ID[$Support_num]=$row['FSupportID'];
	}
	//---------------------------------------//
	if($row['FJobLevel']=='L' || $row['FJobLevel']=='M' || $row['FJobLevel']=='M1' || $row['FJobLevel']=='M2' || $row['FJobLevel']=='H'){
		if($row['id_worklate']!=''){
			$topic=$row['id_worklate'];
		}else{
			$topic="Oth";
		}
		if($row['FFinishDate']!=''){
			if($row['FFinishDate']<=$ControlFin){
				$Colse_NoLimit[$row['FJobLevel']][$row['FSupportID']][$cutDate[0]][$cutDate[1]]+=+1;
				$Colse_NoLimit_botton[$row['FSupportID']][$cutDate[0]][$cutDate[1]]+=+1;
			}else{
				$Colse_OverLimit[$row['FJobLevel']][$row['FSupportID']][$cutDate[0]][$cutDate[1]]+=+1;
				$Colse_OverLimit_botton[$row['FSupportID']][$cutDate[0]][$cutDate[1]]+=+1;
				$Colse_OverLimit_late[$topic][$row['FJobLevel']][$row['FSupportID']][$cutDate[0]][$cutDate[1]]+=+1;
			}
				//echo	$Colse_OverLimit_late['26']['M2']['145']['31']['08']."****".$row['id_worklate'];
		}elseif($row['FFinishDate']==''){
			if($ControlFin>=date('Y-m-d')){
				$Colse_NoLimit_NF[$row['FJobLevel']][$row['FSupportID']][$cutDate[0]][$cutDate[1]]+=+1;
				$Colse_NoLimit_NF_botton[$row['FSupportID']][$cutDate[0]][$cutDate[1]]+=+1;
			}else{
				$Colse_OverLimit_NF[$row['FJobLevel']][$row['FSupportID']][$cutDate[0]][$cutDate[1]]+=+1;
				$Colse_OverLimit_NF_botton[$row['FSupportID']][$cutDate[0]][$cutDate[1]]+=+1;
				$Colse_OverLimit_NF_late[$topic][$row['FJobLevel']][$row['FSupportID']][$cutDate[0]][$cutDate[1]]+=+1;
			}	
		}
		//echo	$Colse_OverLimit_late['26']['M2']['145']['31']['08']."****".$row['id_worklate'];
		
		$Job_All[$row['FJobLevel']][$row['FSupportID']][$cutDate[0]][$cutDate[1]]+=+1;
		$Job_All_botton[$row['FSupportID']][$cutDate[0]][$cutDate[1]]+=+1;
	}
} 
	$cutDateS=explode("-",$SRequestDate);
	$cutDateE=explode("-",$ERequestDate);
	$S_Request=$cutDateS[0]."-".$cutDateS[1]."-01";
	$E_Request=$cutDateE[0]."-".$cutDateE[1]."-01";
	
	
	//$plusMonth = date ("Y-m-d", strtotime("+1 month", strtotime($S_Request)));
	
	
	$startdate = strtotime($S_Request);
 	$enddate = strtotime($E_Request);
    $currentdate = $startdate;
	$num_month=0;
    while ($currentdate <= $enddate) {
		$num_month++;
        $chk_y[$num_month]=date("y", $currentdate)+43;
		$chk_year[$num_month]=date("Y", $currentdate);
        $chk_m[$num_month]=date("m", $currentdate);
		
		
		
       	$currentdate = strtotime('+1 month', $currentdate);  
    } //end loop 
	
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
			$topic_show[$i]=$row_topic['number_topic'];	
	}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>รายงานสรุปประสิทธิภาพการทำงาน ตามประเภทงานซ่อม L,M,H</title>
<link href="../../../css/stylesheet_report.css" rel="stylesheet" type="text/css">


</head>

<body>
<?=$Colse_NoLimit_NF['L'][$Support_ID[$p]][$chk_year[$i]][$chk_m[$i]]; $Colse_NoLimit_NF_left['L'][$Support_ID[$p]]+=+$Colse_NoLimit_NF['L'][$Support_ID[$p]][$chk_year[$i]][$chk_m[$i]];?>
<table width="<?=(50*$num_month)?>%" align="center"  border="1" cellspacing="0" cellpadding="0">
	<tr>
	<td colspan="<?=(6+(4*$num_month))+(($numRows_topic*$num_month)+18)-$e?>" align="center"><b>รายงานสรุปประสิทธิภาพการทำงาน ตามประเภทงานซ่อม L,M,H</b></td>
	</tr>
	<tr style="font-weight: bold; text-align:center">
		<td colspan="2" rowspan="4" bgcolor="#CCCCCC">รายละเอียด</td>
		<?PHP for($i=1;$i<=$num_month;$i++){?>
		<td colspan="<?php echo ($numRows_topic+3);?>" bgcolor="#CCCCCC"><?=getNameMonth($chk_m[$i])?>-<?=$chk_y[$i]?></td><!-- 1-->
        <!--add--><td rowspan="4" bgcolor="#CCCCCC">รวม JOB เกินกำหนด</td>
		<td rowspan="4" bgcolor="#CCCCCC">%ประสิทธิภาพการทำงาน</td>
		<?PHP }?> 
		<td colspan="<?php echo ($numRows_topic+3);?>" bgcolor="#CCCCCC">รวมทั้งหมด</td><!--a-->
        <!--add--><td rowspan="4" bgcolor="#CCCCCC">รวม JOB เกินกำหนด</td>
		<td rowspan="4" bgcolor="#CCCCCC">%ประสิทธิภาพการทำงาน</td>
	</tr>
	<tr style="font-weight: bold; text-align:center">
		<?PHP for($i=1;$i<=$num_month;$i++){?>
		<td colspan="<?php echo ($numRows_topic+3);?>" bgcolor="#CCCCCC">รายละเอียดงานซ่อม</td><!-- 2-->
		<?PHP }?>
		<td colspan="<?php echo ($numRows_topic+3);?>" bgcolor="#CCCCCC">รายละเอียดงานซ่อม</td>
	</tr>
	<tr style="font-weight: bold; text-align:center">
		<?PHP for($i=1;$i<=$num_month;$i++){?>
		<td width="50px" rowspan="2" bgcolor="#CCCCCC">Jobทั้งหมด</td>
		<td rowspan="2" bgcolor="#CCCCCC">ไม่เกินกำหนด</td>
		<td colspan="<?php echo $numRows_topic+1;?>" bgcolor="#CCCCCC">เกินกำหนด</td><!-- 3-->
		<?PHP }?>
		<td rowspan="2" bgcolor="#CCCCCC">Jobทั้งหมด</td>
		<td rowspan="2" bgcolor="#CCCCCC">ไม่เกินกำหนด</td>
		<td colspan="<?php echo $numRows_topic+1;?>" bgcolor="#CCCCCC">เกินกำหนด</td>
	</tr>
	<tr style="font-weight: bold; text-align:center">
		<?PHP for($i=1;$i<=$num_month;$i++){?>
    	<?php for($y = 1; $y <=$numRows_topic; $y++){ ?>
		<td width="25px" bgcolor="#CCCCCC"><?php echo $topic_show[$y]?></td>
        <?php }?>
        <td width="25px" bgcolor="#CCCCCC">อื่นๆ</td>
		<?PHP }?>
		<?php for($y = 1; $y <=$numRows_topic; $y++){ ?>
		<td width="25px" bgcolor="#CCCCCC"><?php echo $topic_show[$y]?></td>
        <?php }?>
        
        <td width="25px" bgcolor="#CCCCCC">อื่นๆ</td>
	</tr>
	<?php for($p=1;$p<=$Support_num;$p++){ ?>
	<tr>
		<td colspan="<?=(6+(4*$num_month))+(($numRows_topic*$num_month)+18)-$e?>" bgcolor="#DDDD00" style="font-weight: bold;">
		<?=iconv("TIS-620","UTF-8",$Support_name[$p]);?>
		</td>
	</tr>
	<tr>
		<td width="70px" rowspan="2" bgcolor="#FFFFF4">1.งาน L (3วัน)</td>
		<td width="50px" bgcolor="#FFFFF4">ปิดงาน</td>
		<?PHP for($i=1;$i<=$num_month;$i++){?>
		<td rowspan="2" align="center" bgcolor="#FFFFF4"><?=$Job_All['L'][$Support_ID[$p]][$chk_year[$i]][$chk_m[$i]]; $Job_All_left['L'][$Support_ID[$p]]+=+$Job_All['L'][$Support_ID[$p]][$chk_year[$i]][$chk_m[$i]];?></td>
		<td align="center" bgcolor="#FFFFF4"><?=$Colse_NoLimit['L'][$Support_ID[$p]][$chk_year[$i]][$chk_m[$i]]; $Colse_NoLimit_left['L'][$Support_ID[$p]]+=+$Colse_NoLimit['L'][$Support_ID[$p]][$chk_year[$i]][$chk_m[$i]];?></td>
      
	<?php for($y = 1; $y <=$numRows_topic; $y++){ ?>
	  <td align="center" bgcolor="#FFFFF4"><?php echo $Colse_OverLimit_late[$id_show[$y]]['L'][$Support_ID[$p]][$chk_year[$i]][$chk_m[$i]];
	  $Late_L[$id_show[$y]]['L'][$Support_ID[$p]]+=+$Colse_OverLimit_late[$id_show[$y]]['L'][$Support_ID[$p]][$chk_year[$i]][$chk_m[$i]];
	  ?></td>
	<?php }?>
    
        <td align="center" bgcolor="#FFFFF4"><?php echo $Colse_OverLimit_late["Oth"]['L'][$Support_ID[$p]][$chk_year[$i]][$chk_m[$i]];
		$Late_Oth_L["Oth"]['L'][$Support_ID[$p]]+=+$Colse_OverLimit_late["Oth"]['L'][$Support_ID[$p]][$chk_year[$i]][$chk_m[$i]];
		?></td>
		<td align="center" bgcolor="#FFFFF4"><?=$Colse_OverLimit['L'][$Support_ID[$p]][$chk_year[$i]][$chk_m[$i]]; $Colse_OverLimit_left['L'][$Support_ID[$p]]+=+$Colse_OverLimit['L'][$Support_ID[$p]][$chk_year[$i]][$chk_m[$i]];?></td>
		<td align="center" bgcolor="#FFFFF4"><?=@number_format((($Colse_NoLimit['L'][$Support_ID[$p]][$chk_year[$i]][$chk_m[$i]]/$Job_All['L'][$Support_ID[$p]][$chk_year[$i]][$chk_m[$i]])*100),2);?>%</td>
     <?PHP }?>
		<td rowspan="2" align="center" bgcolor="#FFFFF4" width="50px"><?=chk_zero($Job_All_left['L'][$Support_ID[$p]]); $Job_All_left_botton[$Support_ID[$p]]+=+$Job_All_left['L'][$Support_ID[$p]];?></td>
		<td align="center" bgcolor="#FFFFF4" width="50px"><?=chk_zero($Colse_NoLimit_left['L'][$Support_ID[$p]]);?></td>
      	<?php for($y = 1; $y <=$numRows_topic; $y++){ ?>
		<td align="center" bgcolor="#FFFFF4"><?php echo chk_zero($Late_L[$id_show[$y]]['L'][$Support_ID[$p]]);?></td>
		<?php }?>
		<td align="center" bgcolor="#FFFFF4"><?php echo chk_zero($Late_Oth_L["Oth"]['L'][$Support_ID[$p]]);?></td>
		<td align="center" bgcolor="#FFFFF4"><?=chk_zero($Colse_OverLimit_left['L'][$Support_ID[$p]]);?></td>
		<td align="center" bgcolor="#FFFFF4"><?=@number_format((($Colse_NoLimit_left['L'][$Support_ID[$p]]/$Job_All_left['L'][$Support_ID[$p]])*100),2);?>%</td>
  </tr>
	<tr>
		<td bgcolor="#FFFFF4">ยังไม่ปิดงาน</td>
      
		<?PHP for($i=1;$i<=$num_month;$i++){?>
		<td align="center" bgcolor="#FFFFF4">&nbsp;</td>
		<?php for($y = 1; $y <=$numRows_topic; $y++){ ?>
	  <td align="center" bgcolor="#FFFFF4"><?php echo $Colse_OverLimit_NF_late[$id_show[$y]]['L'][$Support_ID[$p]][$chk_year[$i]][$chk_m[$i]];
	  $Late_NF_L[$id_show[$y]]['L'][$Support_ID[$p]]+=+$Colse_OverLimit_NF_late[$id_show[$y]]['L'][$Support_ID[$p]][$chk_year[$i]][$chk_m[$i]];
	  ?></td>
		<?php }?>
      
		<td align="center" bgcolor="#FFFFF4"></td>
		<td align="center" bgcolor="#FFFFF4"><?=$Colse_OverLimit_NF['L'][$Support_ID[$p]][$chk_year[$i]][$chk_m[$i]]; $Colse_OverLimit_NF_left['L'][$Support_ID[$p]]+=+$Colse_OverLimit_NF['L'][$Support_ID[$p]][$chk_year[$i]][$chk_m[$i]];?></td>
		<td align="center" bgcolor="#FFFFF4"><?=@number_format((($Colse_NoLimit_NF['L'][$Support_ID[$p]][$chk_year[$i]][$chk_m[$i]]/$Job_All['L'][$Support_ID[$p]][$chk_year[$i]][$chk_m[$i]])*100),2);?>%</td>
		<?PHP }?>
	  <td align="center" bgcolor="#FFFFF4"><?=chk_zero($Colse_NoLimit_NF_left['L'][$Support_ID[$p]]);?></td>
      
		<?php for($y = 1; $y <=$numRows_topic; $y++){ ?>
		<td align="center" bgcolor="#FFFFF4"></td>
		<?php }?>
      
      <td align="center" bgcolor="#FFFFF4">&nbsp;</td>
        <td align="center" bgcolor="#FFFFF4"><?=chk_zero($Colse_OverLimit_NF_left['L'][$Support_ID[$p]]);?></td>
		<td align="center" bgcolor="#FFFFF4"><?=@number_format((($Colse_NoLimit_NF_left['L'][$Support_ID[$p]]/$Job_All_left['L'][$Support_ID[$p]])*100),2);?>%</td>
  </tr>
  <tr>
		<td rowspan="2">2.งาน M (3วัน)</td>
		<td>ปิดงาน</td>
		<?PHP for($i=1;$i<=$num_month;$i++){?>
		<td rowspan="2" align="center"><?=$Job_All['M'][$Support_ID[$p]][$chk_year[$i]][$chk_m[$i]]; $Job_All_left['M'][$Support_ID[$p]]+=+$Job_All['M'][$Support_ID[$p]][$chk_year[$i]][$chk_m[$i]];?></td>
		<td align="center"><?=$Colse_NoLimit['M'][$Support_ID[$p]][$chk_year[$i]][$chk_m[$i]];  $Colse_NoLimit_left['M'][$Support_ID[$p]]+=+$Colse_NoLimit['M'][$Support_ID[$p]][$chk_year[$i]][$chk_m[$i]];?></td>
      
		<?php for($y = 1; $y <=$numRows_topic; $y++){ ?>
		<td align="center"><?php echo $Colse_OverLimit_late[$id_show[$y]]['M'][$Support_ID[$p]][$chk_year[$i]][$chk_m[$i]];
		$Late_M[$id_show[$y]]['M'][$Support_ID[$p]]+=+$Colse_OverLimit_late[$id_show[$y]]['M'][$Support_ID[$p]][$chk_year[$i]][$chk_m[$i]];
		?></td>
		<?php }?>
      
        <td align="center"><?php echo $Colse_OverLimit_late["Oth"]['M'][$Support_ID[$p]][$chk_year[$i]][$chk_m[$i]];
		$Late_Oth_M["Oth"]['M'][$Support_ID[$p]]+=+$Colse_OverLimit_late["Oth"]['M'][$Support_ID[$p]][$chk_year[$i]][$chk_m[$i]];
		?></td>
		<td align="center"><?=$Colse_OverLimit['M'][$Support_ID[$p]][$chk_year[$i]][$chk_m[$i]]; $Colse_OverLimit_left['M'][$Support_ID[$p]]+=+$Colse_OverLimit['M'][$Support_ID[$p]][$chk_year[$i]][$chk_m[$i]];?></td>
		<td align="center"><?=@number_format((($Colse_NoLimit['M'][$Support_ID[$p]][$chk_year[$i]][$chk_m[$i]]/$Job_All['M'][$Support_ID[$p]][$chk_year[$i]][$chk_m[$i]])*100),2);?>%</td>
		<?PHP }?>
		<td rowspan="2" align="center"><?=chk_zero($Job_All_left['M'][$Support_ID[$p]]); $Job_All_left_botton[$Support_ID[$p]]+=+$Job_All_left['M'][$Support_ID[$p]];?></td>
		<td align="center"><?=chk_zero($Colse_NoLimit_left['M'][$Support_ID[$p]]);?></td>
        <?php for($y = 1; $y <=$numRows_topic; $y++){ ?>
		<td align="center"><?php echo chk_zero($Late_M[$id_show[$y]]['M'][$Support_ID[$p]]);?></td>
		<?php }?> 
		<td align="center"><?php echo chk_zero($Late_Oth_M["Oth"]['M'][$Support_ID[$p]]);?></td>
		<td align="center"><?=chk_zero($Colse_OverLimit_left['M'][$Support_ID[$p]]);?></td>
		<td align="center"><?=@number_format((($Colse_NoLimit_left['M'][$Support_ID[$p]]/$Job_All_left['M'][$Support_ID[$p]])*100),2);?>%</td>
      
      
     
	</tr>
	<tr>
		<td>ยังไม่ปิดงาน</td>
		<?PHP for($i=1;$i<=$num_month;$i++){?>
		<td align="center"><?=$Colse_NoLimit_NF['M'][$Support_ID[$p]][$chk_year[$i]][$chk_m[$i]]; $Colse_NoLimit_NF_left['M'][$Support_ID[$p]]+=+$Colse_NoLimit_NF['M'][$Support_ID[$p]][$chk_year[$i]][$chk_m[$i]];?></td>
      
		<?php for($y = 1; $y <=$numRows_topic; $y++){ ?>
		<td align="center"><?php echo $Colse_OverLimit_NF_late[$id_show[$y]]['M'][$Support_ID[$p]][$chk_year[$i]][$chk_m[$i]];
		$Late_NF_M[$id_show[$y]]['M'][$Support_ID[$p]]+=+$Colse_OverLimit_NF_late[$id_show[$y]]['M'][$Support_ID[$p]][$chk_year[$i]][$chk_m[$i]];
	  ?></td>
		<?php }?>
      
        <td align="center"></td>
		<td align="center"><?=$Colse_OverLimit_NF['M'][$Support_ID[$p]][$chk_year[$i]][$chk_m[$i]]; $Colse_OverLimit_NF_left['M'][$Support_ID[$p]]+=+$Colse_OverLimit_NF['M'][$Support_ID[$p]][$chk_year[$i]][$chk_m[$i]];?></td>
	  <td align="center"><?=@number_format((($Colse_NoLimit_NF['M'][$Support_ID[$p]][$chk_year[$i]][$chk_m[$i]]/$Job_All['M'][$Support_ID[$p]][$chk_year[$i]][$chk_m[$i]])*100),2);?>%</td>
		<?PHP }?>
		<td align="center"><?=chk_zero($Colse_NoLimit_NF_left['M'][$Support_ID[$p]]);?></td>
		<?php for($y = 1; $y <=$numRows_topic; $y++){ ?>
		<td align="center"></td>
		<?php }?>
		<td align="center">&nbsp;</td>
		<td align="center"><?=chk_zero($Colse_OverLimit_NF_left['M'][$Support_ID[$p]]);?></td>
		<td align="center"><?=@number_format((($Colse_NoLimit_NF_left['M'][$Support_ID[$p]]/$Job_All_left['M'][$Support_ID[$p]])*100),2);?>%</td>
      
      
        
	</tr>
	<tr>
		<td rowspan="2" bgcolor="#FFFFF4">4.งาน M1 (7วัน)</td>
		<td bgcolor="#FFFFF4">ปิดงาน</td>
		<?PHP for($i=1;$i<=$num_month;$i++){?>
		<td rowspan="2" align="center" bgcolor="#FFFFF4"><?=$Job_All['M1'][$Support_ID[$p]][$chk_year[$i]][$chk_m[$i]]; $Job_All_left['M1'][$Support_ID[$p]]+=+$Job_All['M1'][$Support_ID[$p]][$chk_year[$i]][$chk_m[$i]];?></td>
		<td align="center" bgcolor="#FFFFF4"><?=$Colse_NoLimit['M1'][$Support_ID[$p]][$chk_year[$i]][$chk_m[$i]];  $Colse_NoLimit_left['M1'][$Support_ID[$p]]+=+$Colse_NoLimit['M1'][$Support_ID[$p]][$chk_year[$i]][$chk_m[$i]];?></td>
      
		<?php for($y = 1; $y <=$numRows_topic; $y++){ ?>
		<td align="center" bgcolor="#FFFFF4"><?php echo $Colse_OverLimit_late[$id_show[$y]]['M1'][$Support_ID[$p]][$chk_year[$i]][$chk_m[$i]];
		$Late_M1[$id_show[$y]]['M1'][$Support_ID[$p]]+=+$Colse_OverLimit_late[$id_show[$y]]['M1'][$Support_ID[$p]][$chk_year[$i]][$chk_m[$i]];
		?></td>
		<?php }?>
      
		<td align="center" bgcolor="#FFFFF4"><?php echo $Colse_OverLimit_late["Oth"]['M1'][$Support_ID[$p]][$chk_year[$i]][$chk_m[$i]];
		$$Late_Oth_M1["Oth"]['M1'][$Support_ID[$p]]+=+$Colse_OverLimit_late["Oth"]['M1'][$Support_ID[$p]][$chk_year[$i]][$chk_m[$i]];
		?></td>
		<td align="center" bgcolor="#FFFFF4"><?=$Colse_OverLimit['M1'][$Support_ID[$p]][$chk_year[$i]][$chk_m[$i]]; $Colse_OverLimit_left['M1'][$Support_ID[$p]]+=+$Colse_OverLimit['M1'][$Support_ID[$p]][$chk_year[$i]][$chk_m[$i]];?></td>
		<td align="center" bgcolor="#FFFFF4"><?=@number_format((($Colse_NoLimit['M1'][$Support_ID[$p]][$chk_year[$i]][$chk_m[$i]]/$Job_All['M1'][$Support_ID[$p]][$chk_year[$i]][$chk_m[$i]])*100),2);?>%</td>
	  <?PHP }?>
		<td rowspan="2" align="center" bgcolor="#FFFFF4"><?=chk_zero($Job_All_left['M1'][$Support_ID[$p]]); $Job_All_left_botton[$Support_ID[$p]]+=+$Job_All_left['M1'][$Support_ID[$p]];?></td>
		<td align="center" bgcolor="#FFFFF4"><?=chk_zero($Colse_NoLimit_left['M1'][$Support_ID[$p]]);?></td>
		<?php for($y = 1; $y <=$numRows_topic; $y++){ ?>
		<td align="center" bgcolor="#FFFFF4"><?php echo chk_zero($Late_M1[$id_show[$y]]['M1'][$Support_ID[$p]]);?></td>
		<?php }?>
		<td align="center" bgcolor="#FFFFF4"><?php echo chk_zero($$Late_Oth_M1["Oth"]['M1'][$Support_ID[$p]]);?></td>
		<td align="center" bgcolor="#FFFFF4"><?=chk_zero($Colse_OverLimit_left['M1'][$Support_ID[$p]]);?></td>
		<td align="center" bgcolor="#FFFFF4"><?=@number_format((($Colse_NoLimit_left['M1'][$Support_ID[$p]]/$Job_All_left['M1'][$Support_ID[$p]])*100),2);?>%</td>
      
      
        
	</tr>
	<tr>
		<td bgcolor="#FFFFF4">ยังไม่ปิดงาน</td>
		<?PHP for($i=1;$i<=$num_month;$i++){?>
		<td align="center" bgcolor="#FFFFF4"><?=$Colse_NoLimit_NF['M1'][$Support_ID[$p]][$chk_year[$i]][$chk_m[$i]]; $Colse_NoLimit_NF_left['M1'][$Support_ID[$p]]+=+$Colse_NoLimit_NF['M1'][$Support_ID[$p]][$chk_year[$i]][$chk_m[$i]];?></td>
      
		<?php for($y = 1; $y <=$numRows_topic; $y++){ ?>
		<td align="center" bgcolor="#FFFFF4"><?php echo $Colse_OverLimit_NF_late[$id_show[$y]]['M1'][$Support_ID[$p]][$chk_year[$i]][$chk_m[$i]];
	  $Late_NF_M1[$id_show[$y]]['M1'][$Support_ID[$p]]+=+$Colse_OverLimit_NF_late[$id_show[$y]]['M1'][$Support_ID[$p]][$chk_year[$i]][$chk_m[$i]];
	  ?></td>
		<?php }?>
      
        <td align="center" bgcolor="#FFFFF4"></td>
		<td align="center" bgcolor="#FFFFF4"><?=$Colse_OverLimit_NF['M1'][$Support_ID[$p]][$chk_year[$i]][$chk_m[$i]]; $Colse_OverLimit_NF_left['M1'][$Support_ID[$p]]+=+$Colse_OverLimit_NF['M1'][$Support_ID[$p]][$chk_year[$i]][$chk_m[$i]];?></td>
		<td align="center" bgcolor="#FFFFF4"><?=@number_format((($Colse_NoLimit_NF['M1'][$Support_ID[$p]][$chk_year[$i]][$chk_m[$i]]/$Job_All['M1'][$Support_ID[$p]][$chk_year[$i]][$chk_m[$i]])*100),2);?>%</td>
	  <?PHP }?>
		<td align="center" bgcolor="#FFFFF4"><?=chk_zero($Colse_NoLimit_NF_left['M1'][$Support_ID[$p]]);?></td>
		<?php for($y = 1; $y <=$numRows_topic; $y++){ ?>
		<td align="center" bgcolor="#FFFFF4"></td>
		<?php }?>
		<td align="center" bgcolor="#FFFFF4">&nbsp;</td>
		<td align="center" bgcolor="#FFFFF4"><?=chk_zero($Colse_OverLimit_NF_left['M1'][$Support_ID[$p]]);?></td>
		<td align="center" bgcolor="#FFFFF4"><?=@number_format((($Colse_NoLimit_NF_left['M1'][$Support_ID[$p]]/$Job_All_left['M1'][$Support_ID[$p]])*100),2);?>%</td>
      
      
        
  </tr>
	
	
	
  <tr>
		<td rowspan="2">5.งาน M2 (15วัน)</td>
		<td>ปิดงาน</td>
		<?PHP for($i=1;$i<=$num_month;$i++){?>
		<td rowspan="2" align="center"><?=$Job_All['M2'][$Support_ID[$p]][$chk_year[$i]][$chk_m[$i]]; $Job_All_left['M2'][$Support_ID[$p]]+=+$Job_All['M2'][$Support_ID[$p]][$chk_year[$i]][$chk_m[$i]];?></td>
		<td align="center"><?=$Colse_NoLimit['M2'][$Support_ID[$p]][$chk_year[$i]][$chk_m[$i]];  $Colse_NoLimit_left['M2'][$Support_ID[$p]]+=+$Colse_NoLimit['M2'][$Support_ID[$p]][$chk_year[$i]][$chk_m[$i]];?></td>
      
		<?php for($y = 1; $y <=$numRows_topic; $y++){ ?>
		<td align="center"><?php echo $Colse_OverLimit_late[$id_show[$y]]['M2'][$Support_ID[$p]][$chk_year[$i]][$chk_m[$i]];
		$Late_M2[$id_show[$y]]['M2'][$Support_ID[$p]]+=+$Colse_OverLimit_late[$id_show[$y]]['M2'][$Support_ID[$p]][$chk_year[$i]][$chk_m[$i]];
		?></td>
		<?php }?>
      
        <td align="center"><?php echo $Colse_OverLimit_late["Oth"]['M2'][$Support_ID[$p]][$chk_year[$i]][$chk_m[$i]];
		$Late_Oth_M2["Oth"]['M2'][$Support_ID[$p]]+=+$Colse_OverLimit_late["Oth"]['M2'][$Support_ID[$p]][$chk_year[$i]][$chk_m[$i]];
		?></td>
		<td align="center"><?=$Colse_OverLimit['M2'][$Support_ID[$p]][$chk_year[$i]][$chk_m[$i]]; $Colse_OverLimit_left['M2'][$Support_ID[$p]]+=+$Colse_OverLimit['M2'][$Support_ID[$p]][$chk_year[$i]][$chk_m[$i]];?></td>
		<td align="center"><?=@number_format((($Colse_NoLimit['M2'][$Support_ID[$p]][$chk_year[$i]][$chk_m[$i]]/$Job_All['M2'][$Support_ID[$p]][$chk_year[$i]][$chk_m[$i]])*100),2);?>%</td>
	<?PHP }?>
		<td rowspan="2" align="center"><?=chk_zero($Job_All_left['M2'][$Support_ID[$p]]); $Job_All_left_botton[$Support_ID[$p]]+=+$Job_All_left['M2'][$Support_ID[$p]];?></td>
		<td align="center"><?=chk_zero($Colse_NoLimit_left['M2'][$Support_ID[$p]]);?></td>
		<?php for($y = 1; $y <=$numRows_topic; $y++){ ?>
		<td align="center"><?php echo chk_zero($Late_M2[$id_show[$y]]['M2'][$Support_ID[$p]]);?></td>
		<?php }?>
		<td align="center"><?php echo chk_zero($Late_Oth_M2["Oth"]['M2'][$Support_ID[$p]]);?></td>
		<td align="center"><?=chk_zero($Colse_OverLimit_left['M2'][$Support_ID[$p]]);?></td>
		<td align="center"><?=@number_format((($Colse_NoLimit_left['M2'][$Support_ID[$p]]/$Job_All_left['M2'][$Support_ID[$p]])*100),2);?>%</td>
      
      
        
	</tr>
	<tr>
		<td>ยังไม่ปิดงาน</td>
		<?PHP for($i=1;$i<=$num_month;$i++){?>
		<td align="center"><?=$Colse_NoLimit_NF['M2'][$Support_ID[$p]][$chk_year[$i]][$chk_m[$i]]; $Colse_NoLimit_NF_left['M2'][$Support_ID[$p]]+=+$Colse_NoLimit_NF['M2'][$Support_ID[$p]][$chk_year[$i]][$chk_m[$i]];?></td>
      
		<?php for($y = 1; $y <=$numRows_topic; $y++){ ?>
		<td align="center"><?php echo $Colse_OverLimit_NF_late[$id_show[$y]]['M2'][$Support_ID[$p]][$chk_year[$i]][$chk_m[$i]];
	  $Late_NF_M2[$id_show[$y]]['M2'][$Support_ID[$p]]+=+$Colse_OverLimit_NF_late[$id_show[$y]]['M2'][$Support_ID[$p]][$chk_year[$i]][$chk_m[$i]];
	  ?></td>
		<?php }?>
      
        <td align="center"></td>
		<td align="center"><?=$Colse_OverLimit_NF['M2'][$Support_ID[$p]][$chk_year[$i]][$chk_m[$i]]; $Colse_OverLimit_NF_left['M2'][$Support_ID[$p]]+=+$Colse_OverLimit_NF['M2'][$Support_ID[$p]][$chk_year[$i]][$chk_m[$i]];?></td>
	  <td align="center"><?=@number_format((($Colse_NoLimit_NF['M2'][$Support_ID[$p]][$chk_year[$i]][$chk_m[$i]]/$Job_All['M2'][$Support_ID[$p]][$chk_year[$i]][$chk_m[$i]])*100),2);?>%</td>
    <?PHP }?>
	  <td align="center"><?=chk_zero($Colse_NoLimit_NF_left['M2'][$Support_ID[$p]]);?></td>
      <?php for($y = 1; $y <=$numRows_topic; $y++){ ?>
	  <td align="center"></td>
      <?php }?>
      <td align="center">&nbsp;</td>
      <td align="center"><?=chk_zero($Colse_OverLimit_NF_left['M2'][$Support_ID[$p]]);?></td>
	  <td align="center"><?=@number_format((($Colse_NoLimit_NF_left['M2'][$Support_ID[$p]]/$Job_All_left['M2'][$Support_ID[$p]])*100),2);?>%</td>
      
      
        
      
	</tr>
	<tr>
	  <td rowspan="2" bgcolor="#FFFFF4">6.งาน H (30วัน)</td>
	  <td bgcolor="#FFFFF4">ปิดงาน</td>
    <?PHP for($i=1;$i<=$num_month;$i++){?>
	  <td rowspan="2" align="center" bgcolor="#FFFFF4"><?=$Job_All['H'][$Support_ID[$p]][$chk_year[$i]][$chk_m[$i]]; $Job_All_left['H'][$Support_ID[$p]]+=+$Job_All['H'][$Support_ID[$p]][$chk_year[$i]][$chk_m[$i]];?></td>
	  <td align="center" bgcolor="#FFFFF4"><?=$Colse_NoLimit['H'][$Support_ID[$p]][$chk_year[$i]][$chk_m[$i]];  $Colse_NoLimit_left['H'][$Support_ID[$p]]+=+$Colse_NoLimit['H'][$Support_ID[$p]][$chk_year[$i]][$chk_m[$i]];?></td>
      
      <?php for($y = 1; $y <=$numRows_topic; $y++){ ?>
	  <td align="center" bgcolor="#FFFFF4"><?php echo $Colse_OverLimit_late[$id_show[$y]]['H'][$Support_ID[$p]][$chk_year[$i]][$chk_m[$i]];
	  $Late_H[$id_show[$y]]['H'][$Support_ID[$p]]+=+$Colse_OverLimit_late[$id_show[$y]]['H'][$Support_ID[$p]][$chk_year[$i]][$chk_m[$i]];
	  ?></td>
      <?php }?>
      
		<td align="center" bgcolor="#FFFFF4"><?php echo $Colse_OverLimit_late["Oth"]['H'][$Support_ID[$p]][$chk_year[$i]][$chk_m[$i]];
		$Late_Oth_H["Oth"]['H'][$Support_ID[$p]]+=+$Colse_OverLimit_late["Oth"]['H'][$Support_ID[$p]][$chk_year[$i]][$chk_m[$i]];
		?></td>
      
      <td align="center" bgcolor="#FFFFF4"><?=$Colse_OverLimit['H'][$Support_ID[$p]][$chk_year[$i]][$chk_m[$i]]; $Colse_OverLimit_left['H'][$Support_ID[$p]]+=+$Colse_OverLimit['H'][$Support_ID[$p]][$chk_year[$i]][$chk_m[$i]];?></td>
	  <td align="center" bgcolor="#FFFFF4"><?=@number_format((($Colse_NoLimit['H'][$Support_ID[$p]][$chk_year[$i]][$chk_m[$i]]/$Job_All['H'][$Support_ID[$p]][$chk_year[$i]][$chk_m[$i]])*100),2);?>%</td>
    <?PHP }?>
	  <td rowspan="2" align="center" bgcolor="#FFFFF4"><?=chk_zero($Job_All_left['H'][$Support_ID[$p]]); $Job_All_left_botton[$Support_ID[$p]]+=+$Job_All_left['H'][$Support_ID[$p]];?></td>
	  <td align="center" bgcolor="#FFFFF4"><?=chk_zero($Colse_NoLimit_left['H'][$Support_ID[$p]]);?></td>
      <?php for($y = 1; $y <=$numRows_topic; $y++){ ?>
	  <td align="center" bgcolor="#FFFFF4"><?php echo chk_zero($Late_H[$id_show[$y]]['H'][$Support_ID[$p]]);?></td>
      <?php }?>
      <td align="center" bgcolor="#FFFFF4"><?php echo chk_zero($Late_Oth_H["Oth"]['H'][$Support_ID[$p]]);?></td>
      <td align="center" bgcolor="#FFFFF4"><?=chk_zero($Colse_OverLimit_left['H'][$Support_ID[$p]]);?></td>
	  <td align="center" bgcolor="#FFFFF4"><?=@number_format((($Colse_NoLimit_left['H'][$Support_ID[$p]]/$Job_All_left['H'][$Support_ID[$p]])*100),2);?>%</td>
      
      
        
  </tr>
	<tr>
	  <td bgcolor="#FFFFF4">ยังไม่ปิดงาน</td>
    <?PHP for($i=1;$i<=$num_month;$i++){?>
	  <td align="center" bgcolor="#FFFFF4"><?=$Colse_NoLimit_NF['H'][$Support_ID[$p]][$chk_year[$i]][$chk_m[$i]]; $Colse_NoLimit_NF_left['H'][$Support_ID[$p]]+=+$Colse_NoLimit_NF['H'][$Support_ID[$p]][$chk_year[$i]][$chk_m[$i]];?></td>
      
      <?php for($y = 1; $y <=$numRows_topic; $y++){ ?>
	  <td align="center" bgcolor="#FFFFF4"><?php echo $Colse_OverLimit_NF_late[$id_show[$y]]['H'][$Support_ID[$p]][$chk_year[$i]][$chk_m[$i]];
	  $Late_NF_H[$id_show[$y]]['H'][$Support_ID[$p]]+=+$Colse_OverLimit_NF_late[$id_show[$y]]['H'][$Support_ID[$p]][$chk_year[$i]][$chk_m[$i]];
	  ?></td>
      <?php }?>
      
        <td align="center" bgcolor="#FFFFF4"></td>
      <td align="center" bgcolor="#FFFFF4"><?=$Colse_OverLimit_NF['H'][$Support_ID[$p]][$chk_year[$i]][$chk_m[$i]]; $Colse_OverLimit_NF_left['H'][$Support_ID[$p]]+=+$Colse_OverLimit_NF['H'][$Support_ID[$p]][$chk_year[$i]][$chk_m[$i]];?></td>
	  <td align="center" bgcolor="#FFFFF4"><?=@number_format((($Colse_NoLimit_NF['H'][$Support_ID[$p]][$chk_year[$i]][$chk_m[$i]]/$Job_All['H'][$Support_ID[$p]][$chk_year[$i]][$chk_m[$i]])*100),2);?>%</td>
    <?PHP }?>
	  <td align="center" bgcolor="#FFFFF4"><?=chk_zero($Colse_NoLimit_NF_left['H'][$Support_ID[$p]]);?></td>
      <?php for($y = 1; $y <=$numRows_topic; $y++){ ?>
	  <td align="center" bgcolor="#FFFFF4"></td>
      <?php }?>
      <td align="center" bgcolor="#FFFFF4">&nbsp;</td>
      <td align="center" bgcolor="#FFFFF4"><?=chk_zero($Colse_OverLimit_NF_left['H'][$Support_ID[$p]]);?></td>
      
	  <td align="center" bgcolor="#FFFFF4"><?=@number_format((($Colse_NoLimit_NF_left['H'][$Support_ID[$p]]/$Job_All_left['H'][$Support_ID[$p]])*100),2);?>%</td>
      
      
      
        
      
  </tr>
  
		<tr style="font-weight: bold;">
		<td rowspan="2" bgcolor="#FFFF91">รวมทั้งหมด</td>
		<td bgcolor="#FFFF91">ปิดงาน</td>
		<?PHP for($i=1;$i<=$num_month;$i++){?>
		<td rowspan="2" align="center" bgcolor="#FFFF91"><?=$Job_All_botton[$Support_ID[$p]][$chk_year[$i]][$chk_m[$i]];?></td>
		<td align="center" bgcolor="#FFFF91"><?=chk_zero($Colse_NoLimit_botton[$Support_ID[$p]][$chk_year[$i]][$chk_m[$i]]); $Colse_NoLimit_botton_left[$Support_ID[$p]]+=+$Colse_NoLimit_botton[$Support_ID[$p]][$chk_year[$i]][$chk_m[$i]];?></td>
      
		<?php for($y = 1; $y <=$numRows_topic; $y++){ ?>
		<td align="center" bgcolor="#FFFF91">
        <?php echo chk_zero($Colse_OverLimit_late[$id_show[$y]]['L'][$Support_ID[$p]][$chk_year[$i]][$chk_m[$i]]+$Colse_OverLimit_late[$id_show[$y]]['M'][$Support_ID[$p]][$chk_year[$i]][$chk_m[$i]]+$Colse_OverLimit_late[$id_show[$y]]['M1'][$Support_ID[$p]][$chk_year[$i]][$chk_m[$i]]+$Colse_OverLimit_late[$id_show[$y]]['M2'][$Support_ID[$p]][$chk_year[$i]][$chk_m[$i]]+$Colse_OverLimit_late[$id_show[$y]]['H'][$Support_ID[$p]][$chk_year[$i]][$chk_m[$i]]);?>
		</td>
		<?php }?>
        
        <td align="center" bgcolor="#FFFF91"><?php echo chk_zero($Colse_OverLimit_late["Oth"]['L'][$Support_ID[$p]][$chk_year[$i]][$chk_m[$i]]+$Colse_OverLimit_late["Oth"]['M'][$Support_ID[$p]][$chk_year[$i]][$chk_m[$i]]+$Colse_OverLimit_late["Oth"]['M1'][$Support_ID[$p]][$chk_year[$i]][$chk_m[$i]]+$Colse_OverLimit_late["Oth"]['M2'][$Support_ID[$p]][$chk_year[$i]][$chk_m[$i]]+$Colse_OverLimit_late["Oth"]['H'][$Support_ID[$p]][$chk_year[$i]][$chk_m[$i]]);?></td>
      
      	<td align="center" bgcolor="#FFFF91"><?=$Colse_OverLimit_botton[$Support_ID[$p]][$chk_year[$i]][$chk_m[$i]]; $Colse_OverLimit_botton_left[$Support_ID[$p]]+=+$Colse_OverLimit_botton[$Support_ID[$p]][$chk_year[$i]][$chk_m[$i]];?></td>
		<td align="center" bgcolor="#FFFF91"><?=@number_format((($Colse_NoLimit_botton[$Support_ID[$p]][$chk_year[$i]][$chk_m[$i]]/$Job_All_botton[$Support_ID[$p]][$chk_year[$i]][$chk_m[$i]])*100),2);?>%</td>
		<?PHP }?>
		<td rowspan="2" align="center" bgcolor="#FFFF91"><?=chk_zero($Job_All_left_botton[$Support_ID[$p]]);?></td>
		<td align="center" bgcolor="#FFFF91"><?=chk_zero($Colse_NoLimit_botton_left[$Support_ID[$p]]);?></td>
        <?php for($y = 1; $y <=$numRows_topic; $y++){ ?>
		<td align="center" bgcolor="#FFFF91"><?php echo chk_zero($Late_L[$id_show[$y]]['L'][$Support_ID[$p]]+$Late_M[$id_show[$y]]['M'][$Support_ID[$p]]+$Late_M1[$id_show[$y]]['M1'][$Support_ID[$p]]+$Late_M2[$id_show[$y]]['M2'][$Support_ID[$p]]+$Late_H[$id_show[$y]]['H'][$Support_ID[$p]]);?></td>
      	<?php }?>
		<td align="center" bgcolor="#FFFF91"><?php echo chk_zero($Late_Oth_L["Oth"]['L'][$Support_ID[$p]]+$Late_Oth_M["Oth"]['M'][$Support_ID[$p]]+$Late_Oth_M1["Oth"]['M1'][$Support_ID[$p]]+$Late_Oth_M2["Oth"]['M2'][$Support_ID[$p]]+$Late_Oth_H["Oth"]['H'][$Support_ID[$p]]);?></td>
        <td align="center" bgcolor="#FFFF91"><?=chk_zero($Colse_OverLimit_botton_left[$Support_ID[$p]]);?></td>
		<td align="center" bgcolor="#FFFF91"><?=@number_format((($Colse_NoLimit_botton_left[$Support_ID[$p]]/$Job_All_left_botton[$Support_ID[$p]])*100),2);?>%</td>
		
        
        
      
</tr>
<tr style="font-weight: bold;">
		<td bgcolor="#FFFF91">ยังไม่ปิดงาน</td>
		<?PHP for($i=1;$i<=$num_month;$i++){?>
		<td align="center" bgcolor="#FFFF91"><?=chk_zero($Colse_NoLimit_NF_botton[$Support_ID[$p]][$chk_year[$i]][$chk_m[$i]]); $Colse_NoLimit_NF_bottonLEFT[$Support_ID[$p]]+=+$Colse_NoLimit_NF_botton[$Support_ID[$p]][$chk_year[$i]][$chk_m[$i]];?></td>
      
		<?php for($y = 1; $y <=$numRows_topic; $y++){ ?>
	<td align="center" bgcolor="#FFFF91"><?php echo chk_zero($Colse_OverLimit_NF_late[$id_show[$y]]['L'][$Support_ID[$p]][$chk_year[$i]][$chk_m[$i]]+$Colse_OverLimit_NF_late[$id_show[$y]]['M'][$Support_ID[$p]][$chk_year[$i]][$chk_m[$i]]+$Colse_OverLimit_NF_late[$id_show[$y]]['M1'][$Support_ID[$p]][$chk_year[$i]][$chk_m[$i]]+$Colse_OverLimit_NF_late[$id_show[$y]]['M2'][$Support_ID[$p]][$chk_year[$i]][$chk_m[$i]]+$Colse_OverLimit_NF_late[$id_show[$y]]['H'][$Support_ID[$p]][$chk_year[$i]][$chk_m[$i]]);?></td>
		<?php }?>
        
        <td align="center" bgcolor="#FFFF91"></td>
      
      	<td align="center" bgcolor="#FFFF91"><?=$Colse_OverLimit_NF_botton[$Support_ID[$p]][$chk_year[$i]][$chk_m[$i]]; $Colse_OverLimit_NF_bottonLEFT[$Support_ID[$p]]+=+$Colse_OverLimit_NF_botton[$Support_ID[$p]][$chk_year[$i]][$chk_m[$i]];?></td>
		<td align="center" bgcolor="#FFFF91"><?=@number_format((($Colse_NoLimit_NF_botton[$Support_ID[$p]][$chk_year[$i]][$chk_m[$i]]/$Job_All_botton[$Support_ID[$p]][$chk_year[$i]][$chk_m[$i]])*100),2);?>%</td>
		<?PHP }?>
		<td align="center" bgcolor="#FFFF91"><?=chk_zero($Colse_NoLimit_NF_bottonLEFT[$Support_ID[$p]]);?></td>
        <?php for($y = 1; $y <=$numRows_topic; $y++){ ?>
		<td align="center" bgcolor="#FFFF91"></td>
      	<?php }?>
		<td align="center" bgcolor="#FFFF91">&nbsp;</td>
        <td align="center" bgcolor="#FFFF91"><?=chk_zero($Colse_OverLimit_NF_bottonLEFT[$Support_ID[$p]]);?></td>
		<td align="center" bgcolor="#FFFF91"><?=@number_format((($Colse_NoLimit_NF_bottonLEFT[$Support_ID[$p]]/$Job_All_left_botton[$Support_ID[$p]])*100),2);?>%</td>

<?php } ?>
	</tr>
</table><br>
<table border="1" bgcolor="#FFCCCC" cellpadding="0"  cellspacing="0">
            <tr><th colspan="2">***หมายเหตุ***</th></tr>
            <tr>
                <td>หัวข้อ</td>
                <td>สาเหตุที่ทำให้งานล่าช้า</td>
            </tr>
            <?php $worklate="SELECT
                            tbl_worklate.id_worklate,
                            tbl_worklate.topic_worklate,
                            tbl_worklate.number_topic
                            FROM
                            general_db.tbl_worklate";
                    $qry_worklate = mysql_query($worklate);
                    while($row_worklate=mysql_fetch_assoc($qry_worklate)){
						$con = iconv("TIS-620","UTF-8",$row_worklate['topic_worklate']);
                ?>
            <tr>
                <td align="center"><?php echo $row_worklate['number_topic'];?></td>
                <td><?php echo $con;?></td>
            </tr>
            <?php }?>
</table>
 

</body>
</html>
