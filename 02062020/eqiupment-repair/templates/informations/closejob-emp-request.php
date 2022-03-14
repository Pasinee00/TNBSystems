
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
			
			$sql_up = "UPDATE mtrequest_db.tbl_request SET 
						mtrequest_db.tbl_request.status_closejob='3',
						mtrequest_db.tbl_request.closejob_emp_date='".$closejob_emp_date."',
						mtrequest_db.tbl_request.closejob_emp_detail='".iconv("UTF-8","TIS-620",$closejob_emp_detail)."',
						mtrequest_db.tbl_request.FOth_detail='".iconv("UTF-8","TIS-620",$FOth_detail)."'
					 WHERE 
					 mtrequest_db.tbl_request.FRequestID  = '".$id."'  ";
			$query_up = mysql_query($sql_up);
			if($query_up){	
				echo "1";
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
   				<span class="dialog-title">ทำการแก้ไขงานที่ไม่ตรวจรับ</span>
   			</div>
   			<div class="right"></div>
   		</div> 
   		<div class="middle-row">
   			<div class="left"></div>
   			<div id="dialog-body" class="center">
            <form name="form-add" action="" method="post">
              <table width="100%" border="0" cellpadding="0" cellspacing="0">
              <?
              		$sql = "SELECT
						mtrequest_db.tbl_request.FOth_detail,
						mtrequest_db.tbl_request.closejob_emp_detail,
						mtrequest_db.tbl_request.closejob_emp_date,
						mtrequest_db.tbl_request.closejob_detail,
						mtrequest_db.tbl_request.closejob_date,
						mtrequest_db.tbl_request.status_closejob,
						mtrequest_db.tbl_request.FRequestID
						FROM
						mtrequest_db.tbl_request
						WHERE 
						mtrequest_db.tbl_request.FRequestID  = '".$id."'
						";
					$query = mysql_query($sql);
					$num = mysql_num_rows($query);
					$row = mysql_fetch_array($query);
					$ex=explode("-",$row[closejob_date]);
					$closejob_date=$ex[2]."/".$ex[1]."/".$ex[0];
	

			  ?>
				  <tbody id="user-emptype">
				    </tbody>
   					<tbody id="user-depart" style="display:none">
   					</tbody>
   					<tr>
   						<td width="34%" height="20" align="right" bgcolor="#E5E5E5">วันที่ไม่ตรวจรับงาน : </td>
   						<td width="66%" align="left" bgcolor="#E5E5E5">&nbsp;&nbsp;<?=$closejob_date?></td>
					</tr>
   					<tr>
   					  <td height="20" align="right" bgcolor="#E5E5E5">เหตุผลที่ไม่ตรวจรับงาน : </td>
   					  <td align="left" bgcolor="#E5E5E5">&nbsp;&nbsp;<?=iconv("TIS-620","UTF-8",$row[closejob_detail])?></td>
				  </tr>
   					<tr>
   					  <td height="20" align="right">วันที่ปิดงาน : </td>
   					  <td align="left">&nbsp;&nbsp;<?=date('d/m/Y')?>
                      <input type="hidden" style="width:20%;" name="closejob_emp_date" id="closejob_emp_date" value="<?=date('Y-m-d')?>"></td>
			    </tr>
   					<tr>
   					  <td height="20" align="right">สรุปงาน : </td>
   					  <td align="left">&nbsp;&nbsp;<textarea name="FOth_detail" id="FOth_detail" cols="50" rows="6"><?=iconv("TIS-620","UTF-8",$row[FOth_detail])?></textarea></td>
			    </tr>
   				<tr>
   					  <td height="20" align="right">ข้อเสนอแนะ (Admin) : </td>
   					  <td align="left">&nbsp;&nbsp;<input type="text" style="width:80%;" name="closejob_emp_detail" id="closejob_emp_detail"></td>
			    </tr>
   					<tr>
   					  <td height="20" align="center">&nbsp;</td>
   					  <td align="left">&nbsp;</td>
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
                        <button onClick="javascript:save_from(<?=$id?>);">ปิดงาน</button>
                        
                        </td>
   			    	</tr>
   			    </table>
   				
   			</div>
   			<div class="right"></div>
   		</div>
   </div>
</body>
<script type="text/javascript">
	

	function save_from(id){	
	  	//alert(id);
   		var  	param = "id="+id;
			  	param += "&closejob_emp_date="+$("#closejob_emp_date").val();
			  	param += "&closejob_emp_detail="+$("#closejob_emp_detail").val();
				param += "&FOth_detail="+$("#FOth_detail").val();
				param += "&cmd=save_from";	
				param += "&xid="+Math.random();	
		//alert(param);
		$.ajax({
		uel:'?',
		data:encodeURI(param),
		type:'POST',
		success: function(data){
					if(data==1){
						alert('บันทึกการแก้ไขงานเรียบร้อยแล้วค่ะ');
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
</script>
</html>