
<?php 
include '../../../../lib/db_config.php';
include '../../../../main/modules/Model_Utilities.php';
include '../../../modules/documents-app-model.php';

$utilMD = new Model_Utilities();
$reqMD = new Model_Documents_app();
$managerOption = $utilMD->get_ManagerList();
$id = $_REQUEST['id'];
$no = $_REQUEST['no'];

$reqData = $reqMD->get_data($id);
if($select_manager=='ok'){
	
	  
		$sql_check="SELECT
					general_db.tbl_manager.FManagerID,
					general_db.tbl_manager.FName,
					general_db.tbl_manager.emp_code_full,
					pis_db.tbl_employee.emp_id,
					pis_db.tbl_position.post_id,
					pis_db.tbl_position.post_name
					FROM
					general_db.tbl_manager
					LEFT JOIN pis_db.tbl_employee ON general_db.tbl_manager.emp_code_full = pis_db.tbl_employee.emp_code_full
					LEFT JOIN pis_db.tbl_position ON pis_db.tbl_employee.post_id = pis_db.tbl_position.post_id
					WHERE
					tbl_manager.FManagerID='".$smID."' ";		
		$query_check=mysql_query($sql_check); 
		$num_check=mysql_num_rows($query_check); 
		$row_check=mysql_fetch_assoc($query_check);
	
		echo iconv("tis-620","utf-8",trim($row_check[FName]))."|";
		echo trim($row_check[emp_id])."|";
		echo trim($row_check[post_id])."|";
		if($row_check[post_name]!=''){
		echo "( ".iconv("tis-620","utf-8",trim($row_check[post_name]))." )|";
		}else{

			echo "|";
		}
	
	
	
	
		exit();
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<script  type="text/javascript" src="../../../../jsLib/jquery-1.8.0.min.js"></script>
<script src="../../../../jsLib/uniform/jquery.uniform.js" type="text/javascript" charset="utf-8"></script>
<script src="../../../../jsLib/js_scripts/js_function.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript" src="../../../../jsLib/datepicker/zebra_datepicker.js"></script>
<script  type="text/javascript" src="../../../../jsLib/jquery-confirm/jquery.confirm.js"></script>
<script  type="text/javascript" src="../../../../jsLib/jquery-confirm/js/script.js"></script>
<script  type="text/javascript" src="../../../../jsLib/jquery-chosen/chosen.jquery.js"></script>


<link href="../../../../jsLib/jquery-chosen/chosen.css" rel="stylesheet" type="text/css">
<link href="../../../../jsLib/jquery-confirm/jquery.confirm.css" rel="stylesheet" type="text/css">
<link href="../../../../css/dialog-box.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="../../../../jsLib/uniform/css/uniform.default.css" type="text/css" media="screen">
<link href="../../../../jsLib/datepicker/css/default.css" rel="stylesheet" type="text/css" />
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<script type="text/javascript" charset="utf-8">
      $(function(){
        $("input, textarea").uniform();
		 $('.chzn-select').chosen({width: "95%"});
      });
	 
	 
</script>
</head>

<body>
   <div class="dialog-panel">
   		<div class="top-row">
   			<div class="left"></div>
   			<div class="center">
   				<span class="dialog-title">ยืนยันรับเอกสารการอนุมัติเลขที่ <?php print($no);?></span>
   			</div>
   			<div class="right"></div>
   		</div> 
   		<div class="middle-row">
   			<div class="left"></div>
   			<div id="dialog-body" class="center">
            <form name="form-add" id="form-add" action="" method="post"> 
   				<table width="100%" border="0" cellpadding="1" cellspacing="1">
					<?PHP
					if($reqData['FmanagerBP_GSApp']=="Y" || $reqData['FmanagerBP_GSApp']=="N"  || $reqData['FmanagerBP_GSID']==""){
						$display_BP_GSApp='display: none';
						$FmanagerBP_GSApp="";
					}else{
						
						$FmanagerBP_GSApp=$reqData['FmanagerBP_GSID'];
					}
					?>
					
   					<tr style="<?=$display_BP_GSApp?>">
   						<td width="26%" height="20" valign="top"><b>ผู้อนุมัติงาน BP/GS :</b><span id="Manager1"></span> </td>
   						<td width="22%"><?=$reqData['manager_bpgs_fname']?></td>
   						<td width="15%"><input type="radio" name="fields[FmanagerBP_GSApp]"  id="FmanagerBP_GSApp" value="Y" class="managerBP_GSApp" />&nbsp;อนุมัติ</td>
   						<td width="37%"><input type="radio" name="fields[FmanagerBP_GSApp]"  id="FmanagerBP_GSApp" value="N" class="managerBP_GSApp" />&nbsp;ไม่อนุมัติ</td>
   					</tr>
                    <tr  style="<?=$display_BP_GSApp?>">
   						<td width="26%" height="20" valign="top"><b>วันที่ :</b></td>
   						<td><input type="text" name="fields[FmanagerBP_GSApp_date]" id="FmanagerBP_GSApp_date" readonly  value="" ></td>
   						<td>&nbsp;</td>
   						<td>&nbsp;</td>
   					</tr>
					<tr style="<?=$display_BP_GSApp?>"> 
   						<td width="26%" height="20" valign="top"><b>ความคิดเห็น :</b></td>
   						<td colspan="3"><textarea name="fields[FmanagerBP_GS_comment]" id="FmanagerBP_GS_comment" style="width: 95%;"></textarea></td>
   					</tr>
                     
					
                    <tr>
   						<td width="26%" height="20" valign="top"><b>ผู้อนุมัติงาน:</b></td>
   						<td><?=$reqData['sup_fname']?></td>
   						<td></td>
   						<td></td>
   					</tr>
					 <tr>
   						<td height="20" valign="top" colspan="4">
						<input class="SupervisorApp" type="radio" name="fields[FSupervisorApp]" id="FSupervisorApp" value="Y"  />&nbsp;อนุมัติ
						&nbsp;
							<input  class="SupervisorApp"  type="radio" name="fields[FSupervisorApp]"  id="FSupervisorApp" value="N" />&nbsp;ไม่อนุมัติ&nbsp;
						<input  class="SupervisorApp"  type="radio" name="fields[FSupervisorApp]"  id="FSupervisorApp" value="Ynote" />&nbsp;อนุมัติ/หมายเหตุ&nbsp;
						<input  class="SupervisorApp"  type="radio" name="fields[FSupervisorApp]"  id="FSupervisorApp" value="other" />&nbsp;อื่นๆ
							&nbsp;<input type="text" name="fields[FSupervisorOther_note]" id="FSupervisorOther_note"   value="" ></td>
   					</tr>
                    <tr>
   						<td width="26%" height="20" valign="top"><b>วันที่  :</b> <span id="Super2"></span></td>
   						<td><input type="text" name="fields[FSupervisorApp_date]" id="FSupervisorApp_date" readonly  value="" ></td>
   						<td></td>
   						<td></td>
   					</tr>
                    <tr >
   						<td width="26%" height="20" valign="top"><b>ความคิดเห็น  :</b> <span id="Super3"></span></td>
   						<td colspan="3"><textarea name="fields[FSupervisor_comment]" id="FSupervisor_comment"  style="width: 95%;"></textarea>
						<input type="hidden" name="FmanagerBP_GSID" id="FmanagerBP_GSID"  value="<?=$FmanagerBP_GSApp?>" >
							<input type="hidden" name="Fdoc_app_id" id="Fdoc_app_id" value="<?php print($id);?>">
							<input type="hidden" name="u_id" id="u_id" value="<?=$_REQUEST['u_id']?>">
						</td>
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
   			    		<td width="80%" align="left">
   			    			<div class="warning"></div>
   			    		</td>
   			    		<td align="right">
   			    			<input type="button" value="บันทึก" onclick="javascript:confirmDelete();">
   			    		</td>
   			    	</tr>
   			    </table>
   				
   			</div>
   			<div class="right"></div>
   		</div>
   </div>
</body>
<script type="text/javascript">
	
	$(document).ready(function (){
		
		
		 $('#FmanagerBP_GSApp_date').Zebra_DatePicker({
			 // direction: true
		});
		$('#FSupervisorApp_date').Zebra_DatePicker({
			 // direction: true
		});
		
		
		
	});
	$(document).ready(function (){
		
	});
	
	
	function confirmDelete(){
		$('.warning').empty().hide();  
		var is_process = 1;
		 var SupervisorApp=$('.SupervisorApp:checked').val();
		 var managerBP_GSApp=$('.managerBP_GSApp:checked').val();
			 if(managerBP_GSApp!="N"){  
				if(SupervisorApp==undefined){
					$('.warning').empty().html('กรุณาระบุผลการอนุมัติ ผู้อนุมัติงาน').show();
					$('#FSupervisorApp_date').focus();
					is_process = 0;
				}else if(SupervisorApp=="other" && $('#FSupervisorOther_note').val()==''){
					$('.warning').empty().html('กรุณาระบุขยายความอื่นๆ').show();
					$('#FSupervisorOther_note').focus();
					is_process = 0;
					
				}
			    if($('#FSupervisorApp_date').val()==""){
					$('.warning').empty().html('กรุณาระบุวันที่อนุมัติ ผู้อนุมัติงาน').show();
					$('#FSupervisorApp_date').focus();
					is_process = 0;
				}
			 }
		if($('#FmanagerBP_GSID').val()!=""){
		     var managerBP_GSApp=$('.managerBP_GSApp:checked').val();
			   //alert(managerBP_GSApp);
				if(managerBP_GSApp==undefined){
					$('.warning').empty().html('กรุณาระบุผลการอนุมัติ ผู้อนุมัติงาน BP/GS').show();
					$('#FmanagerBP_GSApp_date').focus();
					is_process = 0;
				}
			    if($('#FmanagerBP_GSApp_date').val()==""){
					$('.warning').empty().html('กรุณาระบุวันที่อนุมัติ ผู้อนุมัติงาน BP/GS').show();
					$('#FmanagerBP_GSApp_date').focus();
					is_process = 0;
				}
			
		}
		
		
		
		if(is_process == 1){
		
		var buttons = '[{"title":"OK","class":"blue","action":"saveData();"},{"title":"Cancel","class":"blue","action":""}]';
		buttons = eval(buttons);
		_confirm("warning","Warning","ยืนยันการรับเอกสารการอนุมัติ",buttons);
		}
		
	}
	function saveData(){
		//alert('5555');
			var params = getRequestBody();
			$.ajax({
			type: "POST",
			url: ("../../../controllers/documents-app-controller.php"),
			data: "1&function=receive_doc&"+params,
			//dataType: 'json',
			success: function(data){
				console.log(data);
				if(data==1){
					
					var buttons = '[{"title":"OK","class":"blue","action":""}]';
					buttons = eval(buttons);
					_confirm("infor","Information","บันทึกรับเอกสารเรียบร้อยแล้ว",buttons);
					
					//alert(data);
				   parent.changePage();
				   parent.closePopup(); 
			   }else{
				   var buttons = '[{"title":"OK","class":"blue","action":""}]';
					buttons = eval(buttons);
					_confirm("infor","Information","ไม่สามารถดำเนินการการได้",buttons);
			   }
			}
		});
	}/*End function saveData()*/
	
	function select_manager(select_type){ 
		//alert(select_type);
		var	param = 'select_manager=ok';
				param += '&select_type='+select_type;	
				param += '&smID='+$("#F"+select_type+"ID").val();	
				param += '&xid='+Math.random();		
				//alert(param);
				 			getData = $.ajax({
										url:'?',
										data:encodeURI(param),
										async:false,
										success: function(getData){
											
										var temp = getData.split('|');
											//alert(temp[0]);
										$("#F"+select_type+"_emp_id").val(temp[1]);	
										$("#F"+select_type+"Post_id").val(temp[2]);
										$("#"+select_type+"PostName").html(temp[3]);
										$("#"+select_type+"Name1").html(temp[0]);
										$("#"+select_type+"Name2").html(temp[0]);
										  	
										}
							}).responseText;	
	
    }

</script>
</html>