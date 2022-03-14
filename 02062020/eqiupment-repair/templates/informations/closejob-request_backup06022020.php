
<?php 
include '../../../lib/db_config.php';
include '../../../main/modules/Model_Utilities.php';
include '../../modules/request_model.php';

$utilMD = new Model_Utilities();
$reqMD = new Model_Request();
$_id = $_REQUEST['id'];
$reqData = $reqMD->get_data($_id);
$costData = $reqMD->load_cost($_id);
$estimateData = $reqMD->load_estimate($_id);
$attachs = $reqMD->list_attach($_id);
$states = $reqMD->get_request_state($_id);
$supports = $reqMD->load_support($_id);

if($cmd=="save_from"){
	if($radioapprove=="1"){
			$sql_check_date = "SELECT
							tbl_request.closejob_date,
							tbl_request.status_closejob
							FROM
							mtrequest_db.tbl_request
							WHERE
							mtrequest_db.tbl_request.FRequestID  = '".$id."'
							";
		 	$query_check_date = mysql_query($sql_check_date);
		 	$num_check_date = mysql_num_rows($query_check_date);
		 	$row_check_date = mysql_fetch_assoc($query_check_date);	
		 	
			if($row_check_date[closejob_date]==NULL && $row_check_date[closejob_date]==''){
				$sql_up = "UPDATE mtrequest_db.tbl_request SET 
							mtrequest_db.tbl_request.status_closejob='1',
							mtrequest_db.tbl_request.closejob_date='".$closejob_date."'
						 WHERE 
						 mtrequest_db.tbl_request.FRequestID  = '".$id."'  ";
				$query_up = mysql_query($sql_up);
			}else{
				$sql_up = "UPDATE mtrequest_db.tbl_request SET 
							mtrequest_db.tbl_request.status_closejob='1',
							mtrequest_db.tbl_request.closejob_date2='".$closejob_date."'
						 WHERE 
						 mtrequest_db.tbl_request.FRequestID  = '".$id."'  ";
				$query_up = mysql_query($sql_up);	
			}
			echo "1";
	}else if($radioapprove=="2"){
			$sql_up = "UPDATE mtrequest_db.tbl_request SET 
						mtrequest_db.tbl_request.status_closejob='2',
						mtrequest_db.tbl_request.closejob_date='".$closejob_date."',
						mtrequest_db.tbl_request.closejob_detail='".iconv("UTF-8","TIS-620",$closejob_detail)."'
					 WHERE 
					 mtrequest_db.tbl_request.FRequestID  = '".$id."'  ";
			$query_up = mysql_query($sql_up);
			echo "3";
	}else{
			echo "2";
	}
exit();
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<script  type="text/javascript" src="../../../jsLib/jquery-1.8.0.min.js"></script>
<script src="../../../jsLib/uniform/jquery.uniform.js" type="text/javascript" charset="utf-8"></script>
<script src="../../../jsLib/js_scripts/js_function.js" type="text/javascript" charset="utf-8"></script>
<link href="../../../css/dialog-box.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="../../../jsLib/uniform/css/uniform.default.css" type="text/css" media="screen">
<link href="../../../css/sys_controll.css" rel="stylesheet" type="text/css">
<link href="../../../css/display.css" rel="stylesheet" type="text/css">
<title>Insert title here</title>
<script type="text/javascript" charset="utf-8">
      $(function(){
        $("input, textarea, select").uniform();
      });
</script>
<style type="text/css">
body,td,th {
	font-family: THNiramitAS, Georgia, sans-serif;
}
</style>
</head>
<body>
   <div class="dialog-panel">
   		<div class="top-row">
   			<div class="left"></div>
   			<div class="center">
   				<span class="dialog-title">ตรวจรับงาน</span>
   			</div>
   			<div class="right"></div>
   		</div> 
   		<div class="middle-row">
   			<div class="left"></div>
   			<div id="dialog-body" class="center">
            <form name="form-add" action="" method="post">
              <table width="100%" border="0" cellpadding="1" cellspacing="1">
				  <tbody id="user-emptype">
			    </tbody>
   					<tbody id="user-depart" style="display:none">
   					</tbody>
                     <?  $sql_show = "SELECT
							tbl_request.closejob_date,
							tbl_request.status_closejob,
							tbl_request.closejob_date2,
							tbl_request.closejob_detail,
							tbl_request.approve_date,
							tbl_request.detail_noapprove
							FROM
							mtrequest_db.tbl_request
							WHERE
							mtrequest_db.tbl_request.FRequestID  = '".$id."'
							";
							$query_show = mysql_query($sql_show);
							$num_show = mysql_num_rows($query_show);
							$row_show = mysql_fetch_assoc($query_show);	
						  
					?>
   					<tr>
   						<td width="28%" height="20" align="right">วันที่ตรวจรับงาน : </td>
   						<td width="72%" align="left"><?=date('d/m/Y');?>
				      <input type="hidden" style="width:20%;" name="closejob_date" id="closejob_date" value="<?=date('Y-m-d')?>"></td>
				</tr>
   					<tr>
   					  <td height="20" align="right">ตรวจรับงาน : </td>
   					  <td align="left"><input type="radio" onClick="show_approve();" name="radioapprove" id="radioapprove1" value="1"></td>
			    </tr>
   					<tr>
   					  <td height="20" align="right">ไม่ตรวจรับงาน : </td>
   					  <td align="left"><input type="radio" onClick="show_noapprove();" name="radioapprove" id="radioapprove2" value="2"></td>
			    </tr>
   					<tr>
   					  <td height="20" align="right">เหตุผลที่ไม่ตรวจรับงาน  : </td>
   					  <td align="left"><input type="text" style="width:80%;" name="closejob_detail" id="closejob_detail"></td>
			    </tr>
   					<tr>
   					  <td height="20" align="right" style="color: #F00">***กรณี มีการไม่ตรวจรับงาน***</td>
   					  <td align="left"><? if($row_show[status_closejob]=='3'){ echo $row_show[closejob_date]."  ".iconv("TIS-620","UTF-8",$row_show[closejob_detail]);} ?></td>
			    </tr>
   					
			  </table>
             </form>
   			</div>
   			<div class="right"></div>
   		</div>
   		<div class="bottom-row">
   			<div class="left"></div>
   			<div class="center">
   			    <table width="100%">
   			    	<tr>
		    		  <td width="50%" align="left">
		    			<div class="warning">Test warning message</div></td>
   			    		<td align="right">
                        <button onClick="javascript:save_from(<?=$id?>);">ยืนยันการบันทึกข้อมูล</button>
                        
                        </td>
   			    	</tr>
   			    </table>
   				
   			</div>
   			<div class="right"></div>
   		</div>
   </div>
</body>
<script type="text/javascript">
	var div_id = '';
    var dept_id = '';
    var sec_id = '';
	function selectUserType(){
		if($('#login_type').val()=="E"){
			$('#user-depart').hide();
			$('#user-emptype').show();
		}else if($('#login_type').val()=="D"){
			$('#user-emptype').hide();
			$('#user-depart').show();
		}
	}

	 $(function(){
        $("input, textarea").uniform();
        $(".uniform-select").uniform();
        $('.chzn-select').chosen({width: "95%"});
      });
	
	function save_from(id){	
	  	//alert(id);
		var 	radioapprove=$('[name=radioapprove]:checked').val();	
		//alert(radioapprove);
		if(radioapprove==undefined){
				alert('กรุณาเลือกการตรวจรับงาน หรือไม่ตรวจรับงาน');
				return false;
		}
   		var  	param = "id="+id;
			  	param += "&closejob_date="+$("#closejob_date").val();
			  	param += "&closejob_detail="+$("#closejob_detail").val();
				param += "&radioapprove="+radioapprove;
				param += "&cmd=save_from";	
				param += "&xid="+Math.random();	
		//alert(param);
		$.ajax({
		uel:'?',
		data:encodeURI(param),
		type:'POST',
		success: function(data){
					if(data==1){
						alert('ทำการตรวจรับงานเรียบร้อยค่ะ');
						parent.changePage('assing');
						parent.changePage('start');
						parent.changePage('closejob');
						parent.closePopup();
					}else if(data==3){
						alert('บันทึกข้อมูลการไม่ตรวจรับงานเรียบร้อยค่ะ \n  ข้อมูลได้ส่งกลับให้เจ้าหน้าที่ดำเนินการแก้ไขต่อไปค่ะ');
						parent.changePage('assing');
						parent.changePage('start');
						parent.changePage('closejob');
						parent.closePopup();
					}else{
						alert('เกิดข้อผิดพลาดในการตรวจรับงาน');
					}
			}
		});		
	}
	function show_approve(){
		$('#closejob_detail').attr("disabled",true);
		$('#closejob_detail').val('');
	}
	function show_noapprove(){
		$('#closejob_detail').attr("disabled",false);
	}
</script>
</html>