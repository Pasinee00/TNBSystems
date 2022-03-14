<!DOCTYPE HTML">
<?php 
include '../../../../lib/db_config.php';
include '../../../../main/modules/Model_Utilities.php';

$utilMD = new Model_Utilities();
$id = $_REQUEST['id'];
$no = $_REQUEST['no'];
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=MS874">
<script  type="text/javascript" src="../../../../jsLib/jquery-1.8.0.min.js"></script>
<script src="../../../../jsLib/uniform/jquery.uniform.js" type="text/javascript" charset="utf-8"></script>
<script src="../../../../jsLib/js_scripts/js_function.js" type="text/javascript" charset="utf-8"></script>
<link href="../../../../css/dialog-box.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="../../../../jsLib/uniform/css/uniform.default.css" type="text/css" media="screen">
<title>Insert title here</title>
<script type="text/javascript" charset="utf-8">
      $(function(){
        $("input, textarea, select").uniform();
      });
</script>
</head>
<body>
   <div class="dialog-panel">
   		<div class="top-row">
   			<div class="left"></div>
   			<div class="center">
   				<span class="dialog-title">ยกเลิกใบ Request เลขที่ <?php print($no);?></span>
   			</div>
   			<div class="right"></div>
   		</div> 
   		<div class="middle-row">
   			<div class="left"></div>
   			<div id="dialog-body" class="center">
            <form name="form-add" action="" method="post"> 
   				<table width="100%" border="0" cellpadding="1" cellspacing="1">
   					<tr>
   						<td width="25%" height="20" valign="top"><b>เหตุผล :</b> <font color="#990000">*</font></td>
   						<td>
   							<textarea rows="5" style="width:95%" name="FCancelRemark" id="FCancelRemark"></textarea>
   							<input type="hidden" name="FRequestID" id="FRequestID" value="<?php print($id);?>">
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
   			    		<td width="50%" align="left">
   			    			<div class="warning"></div>
   			    		</td>
   			    		<td align="right">
   			    			<input type="button" value="บันทึก" onclick="javascript:saveData();">
   			    		</td>
   			    	</tr>
   			    </table>
   				
   			</div>
   			<div class="right"></div>
   		</div>
   </div>
</body>
<script type="text/javascript">
	function saveData(){
		$('.warning').empty().hide();
		var is_process = 1;
		
		if($('#FCancelRemark').val()==""){
			$('.warning').empty().html('กรุณาระบุเหตุผล').show();
			$('#FCancelRemark').focus();
			is_process = 0;
		}
		
		if(is_process == 1){
			var params = getRequestBody();
            //alert(params);
			$.ajax({
				type: "POST",
				url: ("../../../controllers/request_controller.php"),
				data: "1&function=cancel&"+params,
            
				dataType: 'json',
				success: function(data){
				    parent.changePage('new');
					parent.closePopup(); 
				}
			});
		}
	}/*End function saveData()*/

</script>
</html>