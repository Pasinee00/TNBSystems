<!DOCTYPE HTML">
<?php 
include '../../../lib/db_config.php';
include '../../../main/modules/Model_Utilities.php';
include '../../modules/request_model.php';

$utilMD = new Model_Utilities();
$reqMD = new Model_Request();
$_id = $_REQUEST['id'];
$sendTo = $_REQUEST['sendTo'];
$reqData = $reqMD->get_data($_id);
$costData = $reqMD->load_cost($_id);
$estimateData = $reqMD->load_estimate($_id);
$attachs = $reqMD->list_attach($_id);
$states = $reqMD->get_request_state($_id);
if($_id!=''){
$keyInsert = $_id;
}else{
$keyInsert = 0;
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=MS874">
<script  type="text/javascript" src="../../../jsLib/jquery-1.8.0.min.js"></script>
<script src="../../../jsLib/js_scripts/js_function.js" type="text/javascript" charset="utf-8"></script>
<script src="../../../jsLib/uniform/jquery.uniform.js" type="text/javascript" charset="utf-8"></script>
<link rel="stylesheet" href="../../../jsLib/uniform/css/uniform.default.css" type="text/css" media="screen">
<link href="../../../css/dialog-box.css" rel="stylesheet" type="text/css">
<link href="../../../css/sys_controll.css" rel="stylesheet" type="text/css">
<title>Insert title here</title>
<script type="text/javascript" charset="utf-8">
      $(function(){
        $("input, textarea").uniform();
      });
</script>
</head>
<body>
   <div class="dialog-panel" style="height:100%;">
   		<div class="top-row">
   			<div class="left"></div>
   			<div class="center">
   				<span class="dialog-title">ข้อมูล support</span>
   				<input type="hidden" name="is_sendTo" id="is_sendTo" value="<?php print($sendTo);?>">
   			</div>
   			<div class="right"></div>
   		</div> 
   		<div class="middle-row" style="height:100%;">
   			<div class="left"></div>
   			<div id="dialog-body" class="center">
   					 <div class="list-body" style="height:96%;">
					  	<div class="list-header">
						  	<ul>
						  		<li></li>
						  		<li style="width:11%;">Select</li>
						  		<li style="width:61%;text-align:left">Suport Name</li>
						  		<li style="width:11%;">งานค้าง</li>
						  		<li style="width:14%;">งานทั้งหมด</li>
						  		<li></li>
						  	</ul>
						</div>
						<div class="list-items" style="height:100%;">
						
						</div>
					  </div>
   			</div>
   			<div class="right"></div>
   		</div>
   		<div class="bottom-row">
   			<div class="left"></div>
   			<div class="center">
   			    <div class="list-paging">
			  		<div class="paging-infor"><span id="begin-item">1</span>-<span id="end-item">20</span> จากทั้งหมด <span id="total-item">45</span> รายการ</div>
			  		<div class="paging-action">
			  			<ul class="nav-page">
			  				<li><a href="javascript:void(0);" onclick="javascript:go2First();">&laquo;</a></li>
			  				<li><a href="javascript:void(0);" onclick="javascript:go2Pre();">&lsaquo;</a></li>
			  				<li class="paging-select">
			  					<select id="select-page" onchange="javascript:changePage();">
			  					</select>
			  				</li>
			  				<li><a href="javascript:void(0);" onclick="javascript:go2Next();">&rsaquo;</a></li>
			  				<li><a href="javascript:void(0);" onclick="javascript:go2Last();">&raquo;</a></li>
			  			</ul>
			  		</div>
			   </div>
   			</div>
   			<div class="right"></div>
   		</div>
   </div>
</body>
<script>
    var keyInsert = <?=$keyInsert?>;
	$(document).ready(function (){
		//alert(555);
		changePage();
	});

	function go2First(){
		if(($('#select-page').val()*1)>1){
			var page = ($('#select-page').val()*1)-1;
			$('#select-page').val(1);
			changePage();
		}
	}
	function go2Pre(){
		var page = ($('#select-page').val()*1)-1;
		if(page>0){
			$('#select-page').val(page);
			changePage();
		}
	}
	function go2Next(){
		var page = $('#select-page').val()*1;
		var max = $('#select-page option').length*1;
		if(page<max){
			$('#select-page').val(page+1);
			changePage();
		}
	}
	function go2Last(){
		var page = $('#select-page').val()*1;
		var max = $('#select-page option').length*1;
		if(page<max){
			$('#select-page').val(max);
			changePage();
		}
	}

	function changePage(){
		var ids = parent.mainbody.getSupport();
		$.ajax({ 
			url: "../../../pis_sys/controllers/user_controller.php" ,
			type: "POST",
			datatype: "json",
			data: {"function":"support-list",
				   "page":$('#select-page').val(),
				   "rp":6,
				   "search[user_type][value]":"M,AM",
				   "search[user_type][operate]":"IN",
				   "search[user_id][value]":"("+ids+")",
				   "search[user_id][operate]":"NOT IN",
				   "search[user_status][value]":"Y",
				   "search[user_status][operate]":"="
			}
		})
		.success(function(results) { 
			$(".list-items").empty();
			results = jQuery.parseJSON(results);
			var rows = results['rows'];
			var begin = results['begin'];
			var end = results['end'];
			var total = results['total'];
			var total_page = results['total_page'];
			var page = results['page'];
			if(rows!=null){
				for(var i=0;i<rows.length;i++){
					var cell = rows[i]['cell'];
					var key = keyInsert+cell['user_id'];
					var ul = "<ul>";
						ul+= "<li></li>";
					  	ul+= "<li style=\"width:11%;\"><input type=\"checkbox\" onClick=\"javascript:selectSupport(this,'"+cell['user_id']+"','"+cell['name']+"');\"></li>";
					  	ul+= "<li style=\"width:61%;text-align:left;\">"+cell['name']+"</li>";
					  	ul+= "<li style=\"width:11%;\">"+cell['working']+"</li>";
					  	ul+= "<li style=\"width:14%;\">"+cell['total']+"</li>";
					  	ul+= "<li></li>";
					    ul+= "<ul>";
					$(".list-items").append(ul);
				}
			}
			$('#begin-item').empty().html(begin);
			$('#end-item').empty().html(end);
			$('#total-item').empty().html(total);
			renderPage(document.getElementById('select-page'),total_page);
			$('#select-page').val(page);
			
		});
	}/*End function renderPage()*/
	function selectSupport(obj,id,name){
		var order = parent.mainbody.countSupportAssign(); 
		if(obj.checked==true)parent.mainbody.selectSupport(order,id,name,'new','','');
		else parent.mainbody.removeSupport(id,order);
	}
</script>
</html>