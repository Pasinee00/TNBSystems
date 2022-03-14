<!doctype html>
<?php 
include '../../../lib/db_config.php';
include '../../../main/modules/Model_Utilities.php';

$utilMD = new Model_Utilities();
$_id = $_REQUEST['id'];
$_no = $_REQUEST['r_no'];

?>
<html>
<head>
<meta charset="utf-8">
<script  type="text/javascript" src="../../../jsLib/jquery-1.8.0.min.js"></script>
<script src="../../../jsLib/js_scripts/js_function.js" type="text/javascript" charset="utf-8"></script>
<script src="../../../jsLib/uniform/jquery.uniform.js" type="text/javascript" charset="utf-8"></script>
<script  type="text/javascript" src="../../../jsLib/jquery-confirm/jquery.confirm.js"></script>
<script  type="text/javascript" src="../../../jsLib/jquery-confirm/js/script.js"></script>

<link rel="stylesheet" href="../../../jsLib/uniform/css/uniform.default.css" type="text/css" media="screen">
<link href="../../../css/dialog-box.css" rel="stylesheet" type="text/css">
<link href="../../../css/sys_controll.css" rel="stylesheet" type="text/css">
<link href="../../../css/display.css" rel="stylesheet" type="text/css">
<link href="../../../css/input.css" rel="stylesheet" type="text/css">
<link href="../../../jsLib/jquery-confirm/jquery.confirm.css" rel="stylesheet" type="text/css">
<title>Insert title here</title>
<script type="text/javascript" charset="utf-8">
      $(function(){
        $("input, textarea").uniform();
        $(".uniform-select").uniform();
      });
</script>
</head>

<body>
   <div class="dialog-panel" style="height:100%;">
   		<div class="top-row">
   			<div class="left"></div>
   			<div class="center">
   				<span class="dialog-title">ข้อมูลการสั่งซื้ออุปกรณ์ใหม่</span>
   			</div>
   			<div class="right"></div>
   		</div> 
   		<div class="middle-row" style="height:100%;">
   			<div class="left"></div>
   			<div id="dialog-body" class="center">
   			<form id="save_frm" name="save_frm" method="post" action="" style="margin:0px;pedding:0px;">
   			<table width="80%" align="center">
   				<tr>
   					<td width="20%" style="width:10%;"><strong>เลขที่ Request :</strong></td>
   					<td colspan="3">
   						<?php print($_no);?>
   						<input type="hidden" name="fields[FRequestID]" id="FRequestID" value="<?php print($_id);?>">
   						<input type="hidden" name="FPurchaseID" id="FPurchaseID">
   						<input type="hidden" name="fields[FDateRequest]" id="FDateRequest" value="<?php print(date('Y-m-d'));?>">
   					</td>
   				</tr>
   				<tr>
   					<td><strong>รายการที่สั่งซื้อ <font color="#FF0000">*</font> : </strong></td>
   					<td colspan="3"><input type="text" name="fields[FItems]" id="FItems" style="width:81%;"></td>
   				</tr>
   				<tr>
   				  <td><strong>ประเภท<font color="#FF0000">*</font> : </strong></td>
   				  <td colspan="3">
					 <select name="fields[purchase_type]" id="purchase_type" class="uniform-select">
						 <option value=""> - - - เลือกประเภท - - - </option>
						 <option value="lap"> - - - ค่าแรง - - - </option>
						 <option value="part"> - - - ค่าอะไหล่ - - - </option>
					  </select>
					
				</td>
			  </tr>
   				<tr>
   						<td><strong>จำนวนที่สั่ง :</strong></td>
   						<td width="12%"><input type="text" name="fields[FAmount]" id="FAmount" style="width:100px;text-align:center;" onkeyup="javascript:changNumeric(this);calTotalPrice();"></td>
   						<td width="13%"><strong>หน่วยนับ :</strong></td>
   						<td width="55%"><input type="text" name="fields[FUnit]" id="FUnit" style="width:100px;text-align:center;">
   						  (ตัวอย่าง - เส้น,เมตร ฯลฯ) 
   						</td>
   				</tr>
   				<tr>
   						<td><strong>ราคาต่อชิ้น :</strong></td>
   						<td><input type="text" name="fields[FPricePerAmount]" id="FPricePerAmount" style="width:100px;text-align:right;" onkeyup="javascript:changNumeric(this);calTotalPrice();"></td>
   						<td><strong>ราคารวม :</strong></td>
   						<td><input type="text" name="fields[FPrice]" id="FPrice" style="width:100px;text-align:right;" readonly></td>
   				</tr>
   				<tr>
   					<td align="right" style="padding-right:90px;" colspan="4">
   						<a class="button-bule" href="javascript:void(0);" onclick="javascript:onSave();"> บันทึก</a>&nbsp;
   						<a class="button-bule" href="javascript:void(0);" onclick="javascript:cancelData();"> ยกเลิก  </a>
   					</td>
   				</tr>
   			</table>
   			</form>
   					 <div class="list-body" style="height:71%;">
					  	<div class="list-header">
						  	<ul>
						  		<li></li>
						  		<li style="width:5%;">No</li>
						  		<li style="width:20%;text-align:left">รายการ</li>
						  		<li style="width:10%;text-align:center">จำนวน</li>
						  		<li style="width:10%;text-align:right">ราคาต่อหน่วย (บาท)</li>
						  		<li style="width:10%;text-align:right">ราคารวม (บาท)</li>
						  		<li style="width:10%;">วันที่บันทึก</li>
						  		<li style="width:10%;">วันที่สั่ง</li>
						  		<li style="width:10%;">วันที่ได้รับ</li>
						  		<li style="width:13%;">Action</li>
						  		<li></li>
						  	</ul>
						</div>
						<div class="list-items" style="height:88%;overflow:yes;">
						
						</div>
					  </div>
   			</div>
   			<div class="right"></div>
   		</div>
   		<div class="bottom-row">
   			<div class="left"></div>
   			<div class="center">
   			    <div class="list-paging">
			  		<div class="paging-infor"><span id="begin-item">0</span>-<span id="end-item">0</span> จากทั้งหมด<span id="total-item">0</span>รายการ</div>
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
	$(document).ready(function (){
		changePage();
	});
	function calTotalPrice(){
		var amount = $('#FAmount').val()*1;
		var price = $('#FPricePerAmount').val()*1;
		var totalPrice = amount*price;
		$('#FPrice').val(totalPrice.toFixed(2));
	}
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

	function onSave(){
		
		if($('#FItems').val()==""){
			var buttons = '[{"title":"OK","class":"blue","action":"$(\'#FItems\').focus();"}]';
			buttons = eval(buttons);
			_confirm("warning","Warning","กรุณาระบุรายการที่สั่งซื้อ",buttons);
		}else if($('#purchase_type').val()==""){
			var buttons = '[{"title":"OK","class":"blue","action":"$(\'#purchase_type\').focus();"}]';
			buttons = eval(buttons);
			_confirm("warning","Warning","กรุณาเลือกประเภทรายการ",buttons);
		}else{
			var params = getRequestBody();
			//alert(params);
			$.ajax({
				type: "POST",
				url: ("../../controllers/purchase_controller.php"),
				data: "1&function=insert_data&"+params,
				dataType: 'json',
				success: function(data){
					console.log(data);
					cancelData();
					changePage();
				}
			});
		}
	}/*End function onSave()*/
	function cancelData(){
		$('#FItems').val('');
		$('#FPurchaseID').val('');
		$('#FAmount').val('');
		$('#FUnit').val('');
		$('#FPricePerAmount').val('');
		$('#FPrice').val('');
		$('#purchase_type').val('');
		$.uniform.update("#purchase_type");
	}/*End function cancelData()*/
	function getData(id){
		$.ajax({
			type: "POST",
			url: ("../../controllers/purchase_controller.php"),
			data: "1&function=get&FPurchaseID="+id,
			dataType: 'json',
			success: function(json){
				  assignFields(json);
				$('#purchase_type').val(json["purchase_type"]);
				$.uniform.update("#purchase_type");
			}
		});
	}/*End function getData()*/
	function confirmDelete(id){
		var buttons = '[{"title":"OK","class":"blue","action":"deleteData('+id+');"},{"title":"Cancel","class":"blue","action":""}]';
		buttons = eval(buttons);
		_confirm("warning","Warning","Confirm to delete data",buttons);
	}
	
	function  deleteData(id){
		$.ajax({
			type: "POST",
			url: ("../../controllers/purchase_controller.php"),
			data: "1&function=delete&FPurchaseID="+id+"&FRequestID="+$('#FRequestID').val(),
			dataType: 'json',
			success: function(data){
				console.log(data);
				changePage();
			}
		});
	}

	function changePage(){
		
		$.ajax({ 
			url: "../../controllers/purchase_controller.php" ,
			type: "POST",
			datatype: "json",
			data: {"function":"list",
				   "page":$('#select-page').val(),
				   "FRequestID":$('#FRequestID').val()
			}
		})
		.success(function(results) { 
			//console.log(results);
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
					var ul = "<ul>";
						ul+= "<li></li>";
					  	ul+= "<li style=\"width:5%;\">"+cell['order']+"</li>";
					  	ul+= "<li style=\"width:20%;text-align:left;\">"+cell['FItems']+"</li>";
					  	ul+= "<li style=\"width:10%;text-align:center;\">"+cell['FAmount']+'&nbsp;'+cell['FUnit']+"</li>";
					  	ul+= "<li style=\"width:10%;text-align:right;\">"+cell['FPricePerAmount']+"</li>";
					  	ul+= "<li style=\"width:10%;text-align:right;\">"+cell['FPrice']+"</li>";
					  	ul+= "<li style=\"width:10%;\">"+cell['FDateRequest']+"</li>";
					  	ul+= "<li style=\"width:10%;\">"+cell['FBuyDate']+"</li>";
					  	ul+= "<li style=\"width:10%;\">"+cell['FReciveDate']+"</li>";
					  	ul+= "<li style=\"width:6.33%;\"><span class=\"edit-icon\" onclick=\"javascript:getData('"+cell['FPurchaseID']+"');\">Edit</span></li>";
					  	ul+= "<li style=\"width:6.33%;\"><span class=\"trash-icon\" onclick=\"javascript:confirmDelete('"+cell['FPurchaseID']+"');\">Del</span></li>";
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
</script>
</html>
