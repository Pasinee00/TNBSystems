<!DOCTYPE HTML">
<?php 
include '../../../../lib/db_config.php';
include '../../../../main/modules/Model_Utilities.php';
include '../../../modules/request_model.php';
include '../../../modules/purchase_model.php';

$utilMD = new Model_Utilities();
$purMD = new Model_Purchase();
$_status = array("new"=>"รอการแก้ไข","waiting"=>"รอการอนุมัติ","inprogress"=>"กำลังทำการแก้ไข","finished"=>"ทำการแก้ไขเรียร้อยแล้ว","cancel"=>"ถูกยกเลิก");
$FSectionID = $_REQUEST['sec_id'];
$FBranchID = $_REQUEST['brn_id'];
$SRequestDate = $_REQUEST['SRequestDate'];
$ERequestDate = $_REQUEST['ERequestDate'];
$SDueDate = $_REQUEST['SDueDate'];
$EDueDate = $_REQUEST['EDueDate'];
$SFinishDate = $_REQUEST['SFinishDate'];
$EFinishDate = $_REQUEST['EFinishDate'];
$Status = $_REQUEST['status'];
$Support_id = $_REQUEST['support_id'];
$Support_name = $_REQUEST['support_name'];
$Status = str_replace("\\","",$Status);

$query ="SELECT
		t0.FStatus,
		t0.FSupportID,
		t0.FStatus,
		t1.FRequestID,
		t1.FReqNo,
		t1.FReqDate,
		 DATEDIFF(t1.FDueDate,NOW()) AS numDay,
		t1.FDueDate,
		t1.FReciveDate,
		t1.FJobLevel,
		t1.FEstimate,
		t2.first_name,
		t2.last_name,
		t3.brn_code,
		t4.sec_nameThai,
		t5.FJobLevel_name
		FROM
		mtrequest_db.tbl_requestowner AS t0
		LEFT JOIN mtrequest_db.tbl_request AS t1 ON (t1.FRequestID = t0.FRequestID)
		LEFT JOIN pis_db.tbl_user AS t2 ON (t2.user_id = t0.FSupportID)
		LEFT JOIN pis_db.tbl_branch AS t3 ON (t3.brn_id = t1.FBranchID)
		LEFT JOIN pis_db.tbl_section AS t4 ON (t4.sec_id = t1.FSectionID)
		LEFT JOIN general_db.tbl_fjoblevel AS t5 ON t1.FJobresult = t5.FJobresult AND t1.FJobLevel = t5.FJobLevel
WHERE t0.FStatus IN({$Status}) ";
if($FSectionID)$query .=" AND t1.FSectionID='{$FSectionID}'";
if($FBranchID)$query .=" AND t1.FBranchID='{$FBranchID}'";
if($SRequestDate)$query .=" AND t1.FReqDate>='{$SRequestDate}'";
if($ERequestDate)$query .=" AND t1.FReqDate<='{$ERequestDate}'";
if($SDueDate)$query .=" AND t1.FDueDate>='{$SDueDate}'";
if($EDueDate)$query .=" AND t1.FDueDate<='{$EDueDate}'";
if($SFinishDate)$query .=" AND t1.FFinishDate>='{$SFinishDate}'";
if($EFinishDate)$query .=" AND t1.FFinishDate<='{$EFinishDate}'";
if($Support_id)$query .=" AND t0.FSupportID IN ({$Support_id})";

if($chk_over=='In'){$query .=" AND DATEDIFF(t1.FDueDate,NOW())>=0";
	 }elseif($chk_over=='Over'){ $query .=" AND DATEDIFF(t1.FDueDate,NOW())<0";}

$query .=" ORDER BY t0.FSupportID,t1.FReqNo";
$results = mysql_query($query);

?>
<html>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=windows-874">
	<script type="text/javascript" src="../../../../jsLib/jquery-1.8.0.min.js"></script>
	<script src="../../../../jsLib/uniform/jquery.uniform.js" type="text/javascript" charset="utf-8"></script>
	<script src="../../../../jsLib/js_scripts/js_function.js" type="text/javascript" charset="utf-8"></script>
	<script type="text/javascript" src="../../../../jsLib/jquery-nicescroll/jquery.nicescroll.min.js"></script>
	<link href="../../../../css/report-border.css" rel="stylesheet" type="text/css">
	<link href="../../../../css/dialog-box.css" rel="stylesheet" type="text/css">
	<link rel="stylesheet" href="../../../../jsLib/uniform/css/uniform.default.css" type="text/css" media="screen">
	<link href="../../../../css/sys_controll.css" rel="stylesheet" type="text/css">
	<title>Insert title here</title>
	<script type="text/javascript" charset="utf-8">
		$( function () {
			$( "input, textarea, select" ).uniform();
		} );
	</script>
	<style type="text/css">
		body,
		td,
		th {
			font-family: THNiramitAS, Georgia, sans-serif;
		}
	</style>
</head>

<body>
	<div class="dialog-panel" style="height:100%;">
		<div class="top-row">
			<div class="left"></div>
			<div class="center">
				<span class="dialog-title">รายการงานที่อยู่ระหว่างดำเนินการ&nbsp;&nbsp;<b>ผู้รับผิดชอบ : </b> <?php print($Support_name);?></span>
			</div>
			<div class="right"></div>
		</div>
		<div class="middle-row" style="height:100%;">
			<div class="left"></div>
			<div id="dialog-body" class="center">
				<div class="dialog-body" style="width:100%;height:100%;float:left;">
					<table class="report-header" width="99%" border="0" align="center" cellpadding="0" cellspacing="0">
						<tr>
							<td align="center" class="tlb_bg" width="5%"><b>#</b>
							</td>
							<td align="center" class="tlb_bg" width="10%"><b>วันที่</b></td>
							<td align="center" class="tlb_bg" width="10%"><b>วันที่รับ
							</td>
							<td align="center" class="tlb_bg" width="14%">&nbsp;&nbsp;<b>เลขที่ใบแจ้งซ่อม</b>
							</td>
							<td align="center" class="tlb_bg" width="8%"><b>ประเภทงาน</b>
							</td>
							<td align="center" class="tlb_bg" width="10%"><b>สถานะ</b>
							</td>
							<td align="center" class="tlb_bg" width="15%"><b>กำหนดส่งของ</b>
							</td>
							<td align="center" class="tlb_bg" width="10%"><b>กำหนดเสร็จ</b>
							</td>
							<td align="center" class="tlbr_bg" width="20%"><b>ระยะเวลาดำเนินงาน (เกินกำหนด)</b>
							</td>
						</tr>
					</table>
					<div class="report-detail" style="width:99%;margin:0px auto">
						<table id="tbl-report-detail" width="100%" border="0" cellspacing="0" cellpadding="0">
							<?php 
								$i=0;
			   				    		while($row=mysql_fetch_object($results)){
											$i++;
			   				    ?>
							<tr>
								<td align="center" class="lb" width="5%">
									<?=$i?>
								</td>
								<td align="center" class="lb" width="10%">
								<?php print($utilMD->convertDate2Thai($row->FReqDate,"dd-sm"));?>
								</td>
								<td align="center" class="lb" width="10%">
									<?php print($utilMD->convertDate2Thai($row->FReciveDate,"dd-sm"));?>
								</td>
								<td align="center" class="lb" width="14%">&nbsp;&nbsp;
									<?php print($row->FReqNo);?>
								</td>
								<td align="center" class="lb" width="8%">
									<?php print($row->FJobLevel_name);?>
								</td>
								<td align="center" class="lb" width="10%">
									<?php 
				   					 								if($row->FStatus=="inprogress" || $row->FStatus=="returnedit"){
				   					 									print("กำลังดำเนินการ");
				   					 								}else if($row->FStatus=="waiting"){print("รอการเซ็นอนุมัติ");}
				   					 						?>
								</td>
								<td align="center" class="lb" width="15%">
									<?php 
				   					 								print($utilMD->convertDate2Thai($purMD->getLatestDueDate($row->FRequestID),"dd-sm"));
				   					 						?>
								</td>
								<td align="center" class="lb" width="10%">
									<?php print($utilMD->convertDate2Thai($row->FDueDate,"dd-sm"));?>
								</td>
								<td align="center" class="lrb" width="20%">
									<?php 
				   					// $nowDate = date('Y-m-d');
									// $FReqDate_nextDay=date('Y-m-d',strtotime('+1 day',strtotime($row->FReqDate))); 
				   					// $numDay = DateDiff($nowDate,$row->FDueDate);
				   					// $numDueDate = DateDiff($row->FReqDate,$row->FDueDate);
								//	echo $numDay ."-->".MTJobL ."-->";
				   				if($row->numDay<0){
									print('<span style="color:red">'.($row->numDay).'</span>');
								}else{ 
									print('<span style="color:green">+'.($row->numDay).'</span>');
								}
								
				   					 						?>
								</td>
							</tr>
							<?php }?>
						</table>
					</div>
				</div>
			</div>
			<div class="right"></div>
		</div>
		<div class="bottom-row">
			<div class="left"></div>
			<div class="center">
				<ul class="request-state">
				</ul>
			</div>
			<div class="right"></div>
		</div>
	</div>
</body>
<script>
	$( document ).ready( function () {
		var doc_height = screen.height - 365;
		var report_headerH = $( '.report-header' ).height();
		var report_detailH = doc_height - report_headerH;

		$( '.report-detail' ).css( 'height', report_detailH + 'px' );
		var nice = $( ".report-detail" ).niceScroll( {
			touchbehavior: false,
			cursoropacitymax: 0.6,
			cursorwidth: 5
		} );
	} );
</script>

</html>