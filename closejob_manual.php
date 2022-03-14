<?php
include 'lib/db_config.php';
/*

///////////////ปิด job manual <2560
 $sql="SELECT
		tbl_request.FReqNo,
		tbl_request.FRequestID,
		tbl_request.FStatus,
		tbl_request.FFinishDate,
		tbl_request.status_closejob,
		tbl_request.closejob_date,
		tbl_request.closejob_date2,
		tbl_request.closejob_detail,
		tbl_request.closejob_emp_date,
		tbl_request.closejob_emp_detail,
		tbl_request.closejob_emp_name
		FROM
		mtrequest_db.tbl_request
		WHERE tbl_request.FStatus NOT IN ('finished','cancel','noapprove','waiting') AND FReqDate<='2019-12-31'";
$query=mysql_query($sql);
while($row=mysql_fetch_assoc($query)){
	$MaxFFinishDate='';
	 $sMax=" SELECT
			MAX(tbl_requestowner.FFinishDate) AS MaxFFinishDate
			FROM mtrequest_db.tbl_requestowner
			WHERE tbl_requestowner.FRequestID='".$row[FRequestID]."'
			GROUP BY tbl_requestowner.FRequestID";
	$qMax=mysql_query($sMax);
	$rMax=mysql_fetch_assoc($qMax);
	if($rMax[MaxFFinishDate]!='' && $row[MaxFFinishDate]!='0000-00-00'){
		$MaxFFinishDate=$rMax[MaxFFinishDate];
	}elseif($row[FFinishDate]!='' && $row[FFinishDate]!='0000-00-00'){
		$MaxFFinishDate=$row[FFinishDate];
	}else{
		$MaxFFinishDate=date('Y-m-d');
	}
	
	   $sUPOW=" UPDATE mtrequest_db.tbl_requestowner SET
			tbl_requestowner.FStatus='finished',
			tbl_requestowner.FFinishDate='".$MaxFFinishDate."'
			WHERE tbl_requestowner.FStatus  NOT IN ('finished','cancel','noapprove','waiting') AND
			tbl_requestowner.FRequestID='".$row[FRequestID]."'";
	$qUPOW=mysql_query($sUPOW);
	
	
	
	 $sUP=" UPDATE mtrequest_db.tbl_request SET
			tbl_request.FStatus='finished',
			tbl_request.FFinishDate='".$MaxFFinishDate."',
			tbl_request.closejob_manual='Y',
			tbl_request.closejob_manual_date='".date("Y-m-d H:i:s")."',
			tbl_request.closejob_manual_ip='10.2.1.134',
			tbl_request.status_closejob='1',
			tbl_request.closejob_date='".date("Y-m-d")."',
			tbl_request.closejob_emp_name='ระบบข้อมูล'
			WHERE tbl_request.FRequestID='".$row[FRequestID]."'";
	$qUP=mysql_query($sUP);
	
	
	
	
}*/

////////////////////**********////////////////////////
/*
function chk_requestowner($reg_id){
	$sql="SELECT
			tbl_requestowner.FFinishDate,
			tbl_requestowner.FStatus
			FROM
			mtrequest_db.tbl_requestowner
			WHERE 1 AND tbl_requestowner.FStatus NOT IN ('finished','cancel','noapprove','waiting') 
			AND tbl_requestowner.FRequestID='".$reg_id."'";
	$query=mysql_query($sql);
	return $num=mysql_num_rows($query);
	
}

$sql="SELECT
		tbl_request.FReqNo,
		tbl_request.FRequestID,
		tbl_request.FStatus,
		tbl_request.FFinishDate
		FROM
		mtrequest_db.tbl_request
		WHERE tbl_request.FStatus NOT IN ('finished','cancel','noapprove','waiting') 
		ORDER BY tbl_request.FReqNo";
$query=mysql_query($sql);
$i=0;
while($row=mysql_fetch_assoc($query)){
	$chk_towner=chk_requestowner($row[FRequestID]);
	
	if($chk_towner<=0){
		$i++;
		
	 $sMax=" SELECT
			MAX(tbl_requestowner.FFinishDate) AS MaxFFinishDate
			FROM mtrequest_db.tbl_requestowner
			WHERE tbl_requestowner.FRequestID='".$row[FRequestID]."'
			GROUP BY tbl_requestowner.FRequestID";
	$qMax=mysql_query($sMax);
	$rMax=mysql_fetch_assoc($qMax);	
	if($rMax[MaxFFinishDate]!='' && $row[MaxFFinishDate]!='0000-00-00'){
		$MaxFFinishDate=$rMax[MaxFFinishDate];
	}else{
		$MaxFFinishDate=date('Y-m-d');
	}
	
	echo "(".$i."-".$row[FReqNo].")";
		  $sUP=" UPDATE mtrequest_db.tbl_request SET";
		  $sUP.=" tbl_request.closejob_manual='Y'
				 ,tbl_request.closejob_manual_date='".date("Y-m-d H:i:s")."'
				 ,tbl_request.closejob_manual_ip='10.2.1.134'";
			$sUP.=",tbl_request.FStatus='finished'";
			$sUP.=",tbl_request.FFinishDate='".$MaxFFinishDate."'";
	
	echo	$sUP.=" WHERE tbl_request.FRequestID='".$row[FRequestID]."'";
		echo "<br>";
	}
		$qUP=mysql_query($sUP);
}*/
/*if($chk_towner<=0){
		  $sUP.=",tbl_request.FStatus='finished'";
	}else{
		  $sUP.=",tbl_request.FFinishDate=null";
	}*/

?>
