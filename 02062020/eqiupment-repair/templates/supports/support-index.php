<!DOCTYPE html>
<?php 
include '../../../lib/db_config.php';
include '../../../pis_sys/models/user_model.php';
include '../../modules/request_model.php';


$userMD = new Model_User();
$rMD = new Model_Request();
$empId = $_REQUEST['e_id'];
$userId = $_REQUEST['u_id'];
$compId = $_REQUEST['compId'];

$userInfo = $userMD->get_data($userId);


$param['t2.FStatus'] = 'new';
$param['t2.FSupportID'] = $userId;
$newRequest = $rMD->get_graph_data($param);

$param['t2.FStatus'] = 'inprogress';
$workRequest = $rMD->get_graph_data($param);

$param['t2.FStatus'] = 'waiting';
$assingRequest = $rMD->get_graph_data($param);

$totalRequest = $newRequest+$workRequest+$assingRequest;

$newPercent = 0;
$workPercent = 0;
$assingPercent = 0;
if($totalRequest>0){
	$newPercent = (($newRequest)/$totalRequest)*100;
	$workPercent = (($workRequest)/$totalRequest)*100;
	$assingPercent = (($assingRequest)/$totalRequest)*100;
}

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-874">
<title>Request</title>
<link href="../../../css/sys_controll.css" rel="stylesheet" type="text/css">
<link href="../../../jsLib/jquery-confirm/jquery.confirm.css" rel="stylesheet" type="text/css">
<link href="../../../jsLib/easy-pie-chart/jquery.easy-pie-chart.css" rel="stylesheet" type="text/css">

<script  type="text/javascript" src="../../../jsLib/jquery-1.8.0.min.js"></script>
<script  type="text/javascript" src="../../../jsLib/js_scripts/js_function.js"></script>
<script  type="text/javascript" src="../../../jsLib/jquery-confirm/jquery.confirm.js"></script>
<script  type="text/javascript" src="../../../jsLib/jquery-confirm/js/script.js"></script>
<script  type="text/javascript" src="../../../jsLib/easy-pie-chart/jquery.easy-pie-chart.js"></script>
<style>
	#new-request{
		float: left;
	    height: 48%;
	    padding: 5px;
	    position: relative;
	    width: 49%;
	}
	#start-request{
		width:49%;
		float:left;
		height:48%;
		padding:5px;
	}
	#assing-request{
		width:49%;
		float:left;
		height:48%;
		padding:5px;
	}
	#chart-panel{
		width:49%;
		float:left;
		height:48%;
		padding:5px;
		position:relative;
	}
	#chart-area{
		position:relative;
		width: 62%;
		height:180px;
		padding-top: 15px;
		margin-left:auto;
		margin-right:auto;
	}
	#chart-panel .chart{
		float:left;
		padding-right: 40px;
	}
	.chart .label{
		text-align:center;
	}
	.info-approve{
	background-image:url("../../../images/action-icon/approve.png");
	background-repeat: no-repeat;
	cursor:pointer;
	display:inline-block;
	height:20px;
	padding-left: 30px;
   }
</style>

</head>

<body scrolling="no">
  <input type="hidden" name="sect_id" id="sect_id" value="<?php print($userInfo['sec_id']);?>">
  <input type="hidden" name="brn_id" id="brn_id" value="<?php print($userInfo['brn_id']);?>">
  <input type="hidden" name="user_id" id="user_id" value="<?php print($userId)?>">
  <div id="new-request">
	    <div class="content-top" style="width: 95%;">
		  	<div class="_content-title">คำร้องรอดำเนินการ</div>
	    </div>
	    <div class="list-body" style="height: 69%;">
	  	  <div class="list-header">
		  	<ul>
		  		<li></li>
		  		<li style="width:19%;">Req-No.</li>
		  		<li style="width:19%;">Date</li>
		  		<li style="width:43%;text-align:left;">Employee Request</li>
		  		<li style="width:14%;">Action</li>
		  	</ul>
		</div>
		<div class="list-items preloading" style="height: 79%;">
		
		</div>
	   </div>
	   <div class="list-paging">
	  		<div class="paging-infor"><span id="begin-item">1</span>-<span id="end-item">20</span> จากทั้งหมด <span id="total-item">45</span> รายการ</div>
	  		<div class="paging-action">
	  			<ul class="nav-page">
	  				<li><a href="javascript:void(0);" onclick="javascript:go2First('new');">&laquo;</a></li>
	  				<li><a href="javascript:void(0);" onclick="javascript:go2Pre('new');">&lsaquo;</a></li>
	  				<li class="paging-select">
	  					<select id="select-page-new" onchange="javascript:changePage('new');">
	  					</select>
	  				</li>
	  				<li><a href="javascript:void(0);" onclick="javascript:go2Next('new');">&rsaquo;</a></li>
	  				<li><a href="javascript:void(0);" onclick="javascript:go2Last('new');">&raquo;</a></li>
	  			</ul>
	  		</div>
	   </div>
  </div>
  <div id="start-request">
  	  <div class="content-top" style="width: 95%;">
		  	<div class="_content-title">คำร้องกำลังดำเนินการ</div>
	    </div>
	    <div class="list-body" style="height: 69%;">
	  	  <div class="list-header">
		  	<ul>
		  		<li></li>
		  		<li style="width:19%;">Req-No.</li>
		  		<li style="width:19%;">กำหนดเสร็จ</li>
		  		<li style="width:30%;text-align:left;">Employee Request</li>
                <li style="width:13%;text-align:left;">Status</li>
		  		<li style="width:14%;">Action</li>
		  	</ul>
		</div>
		<div class="list-items preloading" >
		
		</div>
	   </div>
	   <div class="list-paging">
	  		<div class="paging-infor"><span id="begin-item">1</span>-<span id="end-item">20</span> จากทั้งหมด <span id="total-item">45</span> รายการ</div>
	  		<div class="paging-action">
	  			<ul class="nav-page">
	  				<li><a href="javascript:void(0);" onclick="javascript:go2First('start');">&laquo;</a></li>
	  				<li><a href="javascript:void(0);" onclick="javascript:go2Pre('start');">&lsaquo;</a></li>
	  				<li class="paging-select">
	  					<select id="select-page-start" onchange="javascript:changePage('start');">
	  					</select>
	  				</li>
	  				<li><a href="javascript:void(0);" onclick="javascript:go2Next('start');">&rsaquo;</a></li>
	  				<li><a href="javascript:void(0);" onclick="javascript:go2Last('start');">&raquo;</a></li>
	  			</ul>
	  		</div>
	   </div>
  </div>
  <div id="assing-request">
  	    <div class="content-top" style="width: 95%;">
		  	<div class="_content-title">คำร้องรอการอนุมัติ</div>
	    </div>
	    <div class="list-body" style="height: 69%;">
	  	  <div class="list-header">
		  	<ul>
		  		<li></li>
		  		<li style="width:19%;">Req-No.</li>
		  		<li style="width:19%;">Start Date</li>
		  		<li style="width:47%;text-align:left;">Employee Request</li>
		  		<li style="width:14%;"></li>
		  	</ul>
		</div>
		<div class="list-items preloading" >
		
		</div>
	   </div>
	   <div class="list-paging">
	  		<div class="paging-infor"><span id="begin-item">1</span>-<span id="end-item">20</span> จากทั้งหมด <span id="total-item">45</span> รายการ</div>
	  		<div class="paging-action">
	  			<ul class="nav-page">
	  				<li><a href="javascript:void(0);" onclick="javascript:go2First('assing');">&laquo;</a></li>
	  				<li><a href="javascript:void(0);" onclick="javascript:go2Pre('assing');">&lsaquo;</a></li>
	  				<li class="paging-select">
	  					<select id="select-page-assing" onchange="javascript:changePage('assing');">
	  					</select>
	  				</li>
	  				<li><a href="javascript:void(0);" onclick="javascript:go2Next('assing');">&rsaquo;</a></li>
	  				<li><a href="javascript:void(0);" onclick="javascript:go2Last('assing');">&raquo;</a></li>
	  			</ul>
	  		</div>
	   </div>
  </div>
  <div id="chart-panel">
      <div id="chart-area">
      		<div class="chart">
		           <div class="percentage" data-percent="<?php print($newPercent);?>" data-bar-color="#0000FF"><span><?php print(number_format($newPercent,0,".",","));?></span>%</div>
		           <div class="label">รอดำเนินการ</div>
		    </div>
		    <div class="chart">
		           <div class="percentage" data-percent="<?php print($workPercent);?>" data-bar-color="#009900"><span><?php print(number_format($workPercent,0,".",","));?></span>%</div>
		           <div class="label">กำลังดำเนินการ</div>
		    </div>
		    <div class="chart" style="padding-right:0px;">
		           <div class="percentage" data-percent="<?php print($assingPercent);?>" data-bar-color="#FF9900"><span><?php print(number_format($assingPercent,0,".",","));?></span>%</div>
		           <div class="label">รอการอนุมัติ</div>
		    </div>
      </div>
  </div>
</body>
<script>
	$(document).ready(function (){
		changePage('new');	
		changePage('start');
		changePage('assing');

		$('.percentage').easyPieChart({
            animate: 1000,
            scaleColor:'#999999',
            onStep: function(value) {
                //this.$el.find('span').text(~~value);
            }
        });
	});

	function go2First(type){
		if(($('#select-page-'+type).val()*1)>1){
			var page = ($('#select-page-'+type).val()*1)-1;
			$('#select-page-'+type).val(1);
			changePage(type);
		}
	}
	function go2Pre(type){
		var page = ($('#select-page-'+type).val()*1)-1;
		if(page>0){
			$('#select-page-'+type).val(page);
			changePage(type);
		}
	}
	function go2Next(type){
		var page = $('#select-page-'+type).val()*1;
		var max = $('#select-page-'+type+' option').length*1;
		if(page<max){
			$('#select-page-'+type).val(page+1);
			changePage(type);
		}
	}
	function go2Last(type){
		var page = $('#select-page-'+type).val()*1;
		var max = $('#select-page-'+type+' option').length*1;
		if(page<max){
			$('#select-page-'+type).val(max);
			changePage(type);
		}
	}

	function closePopup(){
		parent.closePopup();
	}

	function openPopup(id){
		if(isNaN(id))id='';
		window.location.href = '../general_user/forms/repair-request.form.php?u_id=<?php print($userId);?>&e_id=<?php print($empId);?>&compId=<?php print($compId);?>&FRequestID='+id;
	}
	function closeJob(id){
		if(isNaN(id))id='';
		window.location.href = '../general_user/forms/repair-request.form.php?u_id=<?php print($userId);?>&e_id=<?php print($empId);?>&compId=<?php print($compId);?>&FRequestID='+id+'&close=y';
	}
	function openInformation(id){
		var w = screen.width-40;
		var h = screen.height-260;
		if(isNaN(id))id='';
		parent.TINY.box.show({iframe:'../eqiupment-repair/templates/informations/information-request.php?id='+id,boxid:'frameless',width:w,height:h,fixed:false,maskopacity:40});
	}
	function confirmDelete(id){
		var buttons = '[{"title":"OK","class":"blue","action":"deleteData('+id+');"},{"title":"Cancel","class":"blue","action":""}]';
		buttons = eval(buttons);
		_confirm("warning","Warning","Confirm to delete data",buttons);
	}
	
	function  deleteData(id){
		$.ajax({
			type: "POST",
			url: ("../controllers/machinetype_controller.php"),
			data: "1&function=delete&FMachineTypeID="+id,
			dataType: 'json',
			success: function(data){
				changePage();
			}
		});
	}
	function repairManage(id){
		parent.document.getElementById('mainbody').src = '../general_sys/templates/repairitem_manage.tpl.php?mt_id='+id;
	}

	function changePage(type){
		var page = "";
		var status = "";
		if(type=="new"){
			page = $('#'+type+'-request select[id=select-page-'+type+']').val();
			status = 'new';
		}else if(type=="start"){
			page = $('#'+type+'-request select[id=select-page-'+type+']').val();
			status = 'inprogress';
		}else if(type=="assing"){
			page = $('#'+type+'-request select[id=select-page-'+type+']').val();
			status = 'waiting';
		}
		$.ajax({ 
			url: "../../controllers/request_controller.php" ,
			type: "POST",
			datatype: "json",
			data: {"function":"get_data_section_list",
				   "page":page,
				   "rp":5,
				   "search[t4.FStatus][value]":status,
				   "search[t4.FStatus][condition]":"=",
				   "search[t4.FSupportID][value]":$('#user_id').val(),
				   "search[t4.FSupportID][condition]":'='
			}
		})
		.success(function(results) { 
			$("#"+type+"-request  div.list-items").empty();
			$("#"+type+"-request  div.list-items").removeClass("preloading");
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
					  	ul+= "<li style=\"width:18%;\">"+cell['FReqNo']+"</li>";
					  	ul+= "<li style=\"width:20%;\">"+cell['FReqDate']+"</li>";
					  	if(type=='new'){
					  		ul+= "<li style=\"width:37%;text-align:left;\">"+cell['RequestName']+"</li>";
					  		ul+= "<li style=\"width:23.33%;\"><span class=\"tools-icon\" onclick=\"javascript:openPopup('"+cell['FRequestID']+"');\">เปิด Job</span></li>";
						}else if(type=="start"){
							ul+= "<li style=\"width:30%;text-align:left;\">"+cell['RequestName']+"</li>";
							if(cell['FStatus']=='inprogress'  && cell['approve_date']!=''){
					  		ul+= "<li style=\"width:15.33%;\"><span class=\"info-approve\" > อนุมัติแล้ว</span></li>";
							}else if(cell['FStatus']=='inprogress' &&  cell['approve_date']==''){
					  		ul+= "<li style=\"width:15.33%;\">ดำเนินการ</li>";
							}
					  		ul+= "<li style=\"width:15%;\"><span class=\"shut-down-icon\" onclick=\"javascript:closeJob('"+cell['FRequestID']+"');\">ปิด Job</span></li>";
					  		ul+= "<li style=\"width:0.33%;\"></li>";
						}else if(type=="assing"){
					  		ul+= "<li style=\"width:48%;text-align:left;\">"+cell['RequestName']+"</li>";
					  		ul+= "<li style=\"width:11.33%;\"></li>";
							//ul+= "<li style=\"width:11.33%;\"><span class=\"office-folder-icon\" onclick=\"javascript:receiveDoc('"+cell['FRequestID']+"');\">Receive</span></li>";
					  		ul+= "<li style=\"width:0.33%;\"></li>";
						}
					  	//ul+= "<li style=\"width:10.33%;\"><span style=\"width:90px;\" class=\"book-icon\" onclick=\"javascript:repairManage('"+cell['FMachineTypeID']+"');\">อาการแจ้งซ่อม</span></li>";
					  	ul+= "<li></li>";
					    ul+= "<ul>";
					$("#"+type+"-request  div.list-items").append(ul);
				}
			}
			$('#'+type+'-request span[id=begin-item]').empty().html(begin);
			$('#'+type+'-request span[id=end-item]').empty().html(end);
			$('#'+type+'-request span[id=total-item]').empty().html(total);
			renderPage(document.getElementById('select-page-'+type),total_page);
			$('#'+type+'-request select[id=select-page-'+type+']').val(page);
			if(type=='new')setTimeout("changePage('start')",1000);
			else if(type=='start')setTimeout("changePage('assing')",1000);
			else if(type=='assing')setTimeout("changePage('new')",6000);
			
		});
	}/*End function renderPage()*/

    function cancelReq(no,id){
    	parent.TINY.box.show({iframe:'../eqiupment-repair/templates/general_user/forms/request-cancel.form.php?id='+id+'&no='+no,boxid:'frameless',width:570,height:240,fixed:false,maskopacity:40});
    }
    function openPrint(id){
    	var width = screen.width-10;
		var height = screen.height-60;
    	newwindow=window.open('../informations/print-request.php?id='+id,
				  'requestInformationWindow-'+id,'width='+width+',height='+height+',left=0,top=0,screenX=0,screenY=0,status=no,menubar=yes,scrollbars=yes,copyhistory=yes, resizable=yes,fullscreen=no');
    }/*End of function openInfor(id)*/

    function receiveDoc(id){
		$.ajax({
			type: "POST",
			url: ("../../controllers/request_controller.php"),
			data: "1&function=receive_doc&FRequestID="+id,
			dataType: 'json',
			success: function(data){
					var buttons = '[{"title":"OK","class":"blue","action":""}]';
					buttons = eval(buttons);
					_confirm("infor","Information","บันทึกรับเอกสารเรียบร้อยแล้ว",buttons);
			}
		});
	}/*End of function receiveDoc()*/
	
</script>
</html>