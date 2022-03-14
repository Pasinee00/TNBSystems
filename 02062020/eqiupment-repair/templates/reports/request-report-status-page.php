<?php 
include '../../../lib/db_config.php';
include '../../../main/modules/Model_Utilities.php';
$utilMD = new Model_Utilities();
$sect_data = $utilMD->getSectById($sec_id);
if($brn_id != ""){
	$brn_data = $utilMD->get_BranchById($brn_id);
}

if($status=='excel'){
	header("content-type:text/plain;charset=tis620");
 	$name="รายงานใบแจ้งซ่อม";
 	$file=$name.".xls";
 	header('Content-type: application/xls');
	header('Content-Disposition: attachment; filename='.$file); 
}



//print_r($brn_data);
function comp_name($comp_id){
	$sql = "SELECT comp_name
			FROM pis_db.tbl_company 
			WHERE pis_db.tbl_company.status_user='1' AND pis_db.tbl_company.comp_id = '".$comp_id."'
			ORDER BY order_by";
	$query = mysql_query($sql);
	$fetch = mysql_fetch_assoc($query);
	
	return $fetch['comp_name'];
}

function getNameMonth($Month){
	$MonthName = "";
	switch($Month){
		case 01 :
			$MonthName = "ม.ค.";
			break;
		case 02 :
			$MonthName = "ก.พ.";
			break;
		case 03 :
			$MonthName = "มี.ค.";
			break;
		case 04 :
			$MonthName = "เม.ษ.";
			break;
		case 05 :
			$MonthName = "พ.ค.";
			break;
		case 06 :
			$MonthName = "มิ.ย.";
			break;
		case 07 :
			$MonthName = ".ก.ค.";
			break;
		case 08 :
			$MonthName = "ส.ค.";
			break;
		case 09 :
			$MonthName = "ก.ย.";
			break;
		case 10 :
			$MonthName = "ต.ค.";
			break;
		case 11 :
			$MonthName = "พ.ย.";
			break;
		case 12 :
			$MonthName = "ธ.ค.";
			break;
	}
	
	
	return $MonthName;
}
	
function format_date($date){
	$formatDate = "";
	if($date != ""){
		$tmp = explode("-",$date);	
		$formatDate = $tmp[2]." ".getNameMonth($tmp[1])." ".($tmp[0] + 543);
	}else{
		$formatDate = "";
	}
	
	return $formatDate;
}

function branch_name($brn_id){
	$branch = "	SELECT brn_code 
				FROM pis_db.tbl_branch 
				WHERE brn_id = '".$brn_id."' ";
	$query_branch = mysql_query($branch);
	$fetch_branch = mysql_fetch_assoc($query_branch);
	
	return $fetch_branch['brn_name'];
	//return $branch;
}


function section_name($sec_id){
	$section = " SELECT sec_nameThai 
				 FROM pis_db.tbl_section
				 WHERE sec_id = '".$sec_id."' ";
	$query_section = mysql_query($section);
	$fetch_section = mysql_fetch_assoc($query_section);
	
	return $fetch_section['sec_nameThai'];
}




$sql = "SELECT
			mtrequest_db.tbl_request.FReqNo,
			pis_db.tbl_section.sec_nameThai,
			pis_db.tbl_fnc.fnc_name,
			pis_db.tbl_company.comp_name,

			CASE
				WHEN mtrequest_db.tbl_request.FJobLevel ='L' THEN '3'
				WHEN mtrequest_db.tbl_request.FJobLevel ='M' THEN '3'
				WHEN mtrequest_db.tbl_request.FJobLevel ='M1' THEN '7'
				WHEN mtrequest_db.tbl_request.FJobLevel ='M2' THEN '15'
				WHEN mtrequest_db.tbl_request.FJobLevel ='H' THEN '30'
				ELSE '0'
			END AS NumDateJob,
			pis_db.tbl_branch.brn_code,
			mtrequest_db.tbl_request.FReqDate,
			mtrequest_db.tbl_request.approve_date,
			mtrequest_db.tbl_request.FReciveDate,
			mtrequest_db.tbl_request.FAsset_no,
			mtrequest_db.tbl_request.FSerial,
			general_db.tbl_repairgroup.FRepairGroupName,
			general_db.tbl_repairgroupitem.FRepairGroupItemName,
			mtrequest_db.tbl_request.FDetail,
			mtrequest_db.tbl_request.FLapAmt,
			mtrequest_db.tbl_request.FPartAmt,
			mtrequest_db.tbl_request.FOth_detail,
			mtrequest_db.tbl_request.FDueDate,
			mtrequest_db.tbl_request.FFinishDate,
			general_db.tbl_machinetype.FMachineTypeName,
			general_db.tbl_machinetype.FJobLevel,
			mtrequest_db.tbl_request.id_worklate,
			general_db.tbl_worklate.topic_worklate,
			general_db.tbl_worklate.number_topic,
			mtrequest_db.tbl_request.detail_worklate
		FROM
			mtrequest_db.tbl_request
			LEFT JOIN pis_db.tbl_section ON mtrequest_db.tbl_request.FSectionID = pis_db.tbl_section.sec_id
			LEFT JOIN pis_db.tbl_fnc ON mtrequest_db.tbl_request.FFnc = pis_db.tbl_fnc.fnc_id
			LEFT JOIN pis_db.tbl_company ON mtrequest_db.tbl_request.FRepair_comp_id = pis_db.tbl_company.comp_id
			LEFT JOIN pis_db.tbl_branch ON mtrequest_db.tbl_request.FBranchID = pis_db.tbl_branch.brn_id
			LEFT JOIN general_db.tbl_repairgroup ON mtrequest_db.tbl_request.FRepairGroupID = general_db.tbl_repairgroup.FRepairGroupID
			LEFT JOIN general_db.tbl_repairgroupitem ON mtrequest_db.tbl_request.FRepairGroupItemID = general_db.tbl_repairgroupitem.FRepairGroupItemID
			LEFT JOIN general_db.tbl_machinetype ON mtrequest_db.tbl_request.FMachineTypeID = general_db.tbl_machinetype.FMachineTypeID
			LEFT JOIN general_db.tbl_worklate ON mtrequest_db.tbl_request.id_worklate = general_db.tbl_worklate.id_worklate
			WHERE 1";

			if($FRepair_comp_id != ""){
				$sql .= " AND mtrequest_db.tbl_request.FRepair_comp_id = '".$FRepair_comp_id."' ";
			}
			
			if($brn_id != ""){
				$sql .= " AND mtrequest_db.tbl_request.FBranchID = '".$brn_id."' ";
			}
			
			if($sec_id != ""){
				$sql .= " AND mtrequest_db.tbl_request.FSectionID = '".$sec_id."' ";
			}
		
			
			if($SRequestDate != "" && $ERequestDate != ""){
				$sql .= " AND mtrequest_db.tbl_request.FReqDate BETWEEN '".$SRequestDate."' AND '".$ERequestDate."' ";
			}
			
			if($SDefineFinalDate != "" && $EDefineFinalDate != ""){
				$sql .= " AND mtrequest_db.tbl_request.FDueDate BETWEEN '".$SDefineFinalDate."' AND '".$EDefineFinalDate."' ";
			}
			
			if($SFinalDate != "" && $EFinalDate != ""){
				$sql .= " AND mtrequest_db.tbl_request.approve_date BETWEEN '".$SFinalDate."' AND '".$EFinalDate."' ";
			}
			
			if($FStatus != ""){
				$sql .= " AND mtrequest_db.tbl_request.FStatus = '".$FStatus."' ";
			}
			
			if($FRepairGroupID != ""){
				$sql .= " AND mtrequest_db.tbl_request.FRepairGroupID = '".$FRepairGroupID."' ";
			}
			
			if($FRepairGroupItemID != ""){
				$sql .=  " AND mtrequest_db.tbl_request.FRepairGroupItemID = '".$FRepairGroupItemID."' ";	
			}
			
			//echo $sql;
	
	$query = mysql_query($sql);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="../../../css/bootstrap/fontawesome/css/fontawesome.css">
<link rel="stylesheet" type="text/css" href="../../../css/bootstrap/css/bootstrap.min.css">

<link rel="stylesheet" type="text/css" href="../../../bower_components/font-awesome/css/font-awesome.min.css">
<title>Untitled Document</title>
<style>
	body{
		font-size:12px;	
	}
	.color_td{
		background-color:#2980b9; 
		color:#ffffff;
		font-weight:bold;

	}
</style>
</head>
<body>
<table width="300%" border="1" cellpadding="0"  cellspacing="0">
  <?php $topic_worklate="SELECT
								tbl_worklate.id_worklate,
								tbl_worklate.topic_worklate,
								tbl_worklate.number_topic
								FROM
								general_db.tbl_worklate";
					$qry_topic = mysql_query($topic_worklate);
					$numRows_topic = mysql_num_rows($qry_topic);
					$o=0;
					while($row_topic = mysql_fetch_assoc($qry_topic)){
						$o++;
						$topic_num[$o]=$row_topic["number_topic"];
						$worklate[$o]=iconv("TIS-620","UTF-8",$row_topic["topic_worklate"]);
						
					}
				?>
                
  <tr>
	<td width="9%" colspan="<?php echo $numRows_topic+22;?>" align="center" class="color_td">รายงานสรุปใบแจ้งซ่อม</td>
  </tr>
  <tr>
	<td colspan="<?php echo $numRows_topic+22;?>" class="color_td" align="left"><?=iconv("tis-620","utf-8",comp_name($FRepair_comp_id));?></td>
  </tr>
  <tr>
	<td colspan="4" class="color_td">แผนก : <?=iconv("tis-620","utf-8",$sect_data['sec_nameThai']);?></td>
	<td width="2%" colspan="<?php echo $numRows_topic+18;?>" class="color_td">สาขา : <?=iconv("tis-620","utf-8",$brn_data['brn_name']);?></td>
  </tr>              
					
  <tr>
    <td width="1%" rowspan="2" align="center" class="color_td">ลำดับ</td>
    <td width="1%" rowspan="2" align="center" class="color_td">เลข Job</td>
    <td width="1%" rowspan="2" align="center" class="color_td">แผนก</td>
    <td width="4.5%" rowspan="2" align="center" class="color_td">บริษัท</td>
    <td width="1%" rowspan="2" align="center" class="color_td">สาขา</td>
	<td width="3%" rowspan="2" align="center" class="color_td">วันที่แจ้ง</td>
    <td width="3%" rowspan="2" align="center" class="color_td">วันที่อนุมัติ</td>
    <td width="3%" rowspan="2" align="center" class="color_td">วันที่รับ</td>
    <td width="4%" rowspan="2" align="center" class="color_td">รหัสทรัพย์สิน MT</td>
    <td width="1%" rowspan="2" align="center" class="color_td">หมายเลขอะไหล่</td>
    <td width="4%" rowspan="2" align="center" class="color_td">ขอแจ้งซ่อม</td>
    <td width="3%" rowspan="2" align="center" class="color_td">รายการซ่อม </td>
    <td width="5%" rowspan="2" align="center" class="color_td">รายละเอียด</td>
    <td width="2%" rowspan="2" align="center" class="color_td">ค่าแรง</td>
    <td width="2%" rowspan="2" align="center" class="color_td">ค่าอะไหล่</td>
    <td width="6%" rowspan="2" align="center" class="color_td">รายละเอียดอื่น</td>
    <td width="3%" rowspan="2" align="center" class="color_td">วันกำหนดเสร็จ</td>
    <td width="2%" rowspan="2" align="center" class="color_td">วันที่เสร็จสิ้น</td>
    <td width="2%" rowspan="2" align="center" class="color_td">เครื่องจักร</td>
    <td width="1%" rowspan="2" align="center" class="color_td">ประเภท</td>
    <td width="5%" colspan="<?=$numRows_topic?>" align="center" class="color_td">เกินกำหนด</td>
    <td width="0.5%" rowspan="2" align="center" class="color_td">อื่นๆ</td>
    <td width="0.5%" rowspan="2" align="center" class="color_td">รายละเอียดปัญหา</td>
  </tr>
  <tr>
  <? for($t=1;$t<=$numRows_topic;$t++){?>
    <td align="center" class="color_td"><?=$worklate[$t]?></td>

    
  <? } ?>  
  </tr>
  <? $i=1; 
		
		while($fetch = mysql_fetch_assoc($query)){ 
				$ControlFin = date ("Y-m-d", strtotime("+".$fetch['NumDateJob']." day", strtotime($fetch['FReqDate'])));
				$cutDate=explode("-",$fetch['FReqDate']);
				
		if($fetch['FJobLevel']=='L' || $fetch['FJobLevel']=='M' || $fetch['FJobLevel']=='M1' || $fetch['FJobLevel']=='M2' || $fetch['FJobLevel']=='H'){
			if($fetch['id_worklate']!=''){
				$topic=$fetch['id_worklate'];
			}else{
				$topic="Oth";
			}
		if($fetch['FFinishDate']!=''){
			if($fetch['FFinishDate']<=$ControlFin){
				$Colse_NoLimit[$fetch['FJobLevel']][$fetch['FSupportID']][$cutDate[0]][$cutDate[1]]+=+1;
				$Colse_NoLimit_botton[$fetch['FSupportID']][$cutDate[0]][$cutDate[1]]+=+1;
			}else{
				$Colse_OverLimit[$fetch['FJobLevel']][$fetch['FSupportID']][$cutDate[0]][$cutDate[1]]+=+1;
				$Colse_OverLimit_botton[$fetch['FSupportID']][$cutDate[0]][$cutDate[1]]+=+1;
				$Colse_OverLimit_late[$topic][$fetch['FJobLevel']][$fetch['FSupportID']][$cutDate[0]][$cutDate[1]]+=+1;
			}
				//echo	$Colse_OverLimit_late['26']['M2']['145']['31']['08']."****".$row['id_worklate'];
		}else if($fetch['FFinishDate']==''){
			if($ControlFin>=date('Y-m-d')){
				$Colse_NoLimit_NF[$fetch['FJobLevel']][$fetch['FSupportID']][$cutDate[0]][$cutDate[1]]+=+1;
				$Colse_NoLimit_NF_botton[$fetch['FSupportID']][$cutDate[0]][$cutDate[1]]+=+1;
			}else{
				$Colse_OverLimit_NF[$fetch['FJobLevel']][$fetch['FSupportID']][$cutDate[0]][$cutDate[1]]+=+1;
				$Colse_OverLimit_NF_botton[$fetch['FSupportID']][$cutDate[0]][$cutDate[1]]+=+1;
				$Colse_OverLimit_NF_late[$topic][$fetch['FJobLevel']][$fetch['FSupportID']][$cutDate[0]][$cutDate[1]]+=+1;
			}	
		}
	}
	
	if($fetch["id_worklate"]!='' && $fetch['FFinishDate']>=$ControlFin){
		 if($status=='excel'){
		 $icon[$fetch["number_topic"]][$fetch["FReqNo"]]="<font face=\"Wingdings 2\">P</font>"; 
		 }else{
		 $icon[$fetch["number_topic"]][$fetch["FReqNo"]]='<i class="fa fa-check" aria-hidden="true"></i>'; 
		 }
	 }else{
		 
		 $icon[$fetch["number_topic"]][$fetch["FReqNo"]]="&nbsp;";
	 }
	 
	 if($fetch["id_worklate"]=='' && $fetch['FFinishDate']!=''){ 
		 if($status=='excel'){
		 $i_con="<font face=\"Wingdings 2\">P</font>"; 
		 }else{
		 $i_con='<i class="fa fa-check" aria-hidden="true"></i>'; 
		 }
	 }else{ 
	 $i_con= "&nbsp;";
	 }
	 
	 
?>
  <tr>
    <td align="center"><?php echo $i++;?></td>
    <td align="center"><?=$fetch["FReqNo"]?></td>
    <td><?=iconv("tis-620","utf-8",$fetch["sec_nameThai"])?></td>
    <td><?=iconv("tis-620","utf-8",$fetch["comp_name"])?></td>
    <td align="center"><?=iconv("tis-620","utf-8",$fetch["brn_code"])?></td>
    <td align="center"><?=format_date($fetch["FReqDate"])?></td>
    <td align="center"><?=format_date($fetch["approve_date"])?></td>
    <td align="center"><?=format_date($fetch["FReciveDate"])?></td>
    <td align="center"><?=$fetch["FAsset_no"]?></td>
    <td><?=$fetch["FSerial"]?></td>
    <td><?=iconv("tis-620","utf-8",$fetch["FRepairGroupName"])?></td>
    <td><?=iconv("tis-620","utf-8",$fetch["FRepairGroupItemName"])?></td>
    <td><?=iconv("tis-620","utf-8",$fetch["FDetail"])?></td>
    <td align="right"><?=$fetch["FLapAmt"]?></td>
    <td align="right"><?=$fetch["FPartAmt"]?></td>
    <td><?=iconv("tis-620","utf-8",$fetch["FOth_detail"])?></td>
    <td align="center"><?=format_date($fetch["FDueDate"])?></td>
    <td align="center"><?=format_date($fetch["FFinishDate"])?></td>
    <td><?=iconv("tis-620","utf-8",$fetch["FMachineTypeName"])?></td>
    <td align="center"><?=$fetch["FJobLevel"];?></td>
    <? for($t=1;$t<=$numRows_topic;$t++){?>
    <td align="center"><?=$icon[$topic_num[$t]][$fetch["FReqNo"]];?></td>
    <? } ?>  
    <td align="center"><?=$i_con?></td>
    <td align="center"><?=$fetch["detail_worklate"];?></td>
  </tr>
  <?php }?>
</table>
</body>
</html>
<script type="text/javascript" src="../../../css/bootstrap/fontawesome/js/fontawesome.js"></script>
<script type="text/javascript" src="../../../css/bootstrap/js/bootstrap.min.js"></script>

