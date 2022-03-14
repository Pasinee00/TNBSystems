<!DOCTYPE html>
<?php 
include '../../../lib/db_config.php';
include '../../../main/modules/Model_Utilities.php';
include '../../../pis_sys/models/user_model.php';
	
$utilMD = new Model_Utilities();
$userMD = new Model_User();
$compId = $_REQUEST['compId'];
$empId = $_REQUEST['e_id'];
$userId = $_REQUEST['u_id'];
$status = $_REQUEST['status'];
$comList = $utilMD->get_CompList();
$brnList = $utilMD->get_BranchList($comList);
$sectLst = $utilMD->get_SectList($comList, '');
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-874">
<title>Request</title>
<link href="../../../css/sys_controll.css" rel="stylesheet" type="text/css">
<link href="../../../jsLib/jquery-confirm/jquery.confirm.css" rel="stylesheet" type="text/css">
<link href="../../../jsLib/datepicker/css/default.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="../../../jsLib/uniform/css/uniform.default.css" type="text/css" media="screen">
<link href="../../../css/input.css" rel="stylesheet" type="text/css">

<script  type="text/javascript" src="../../../jsLib/jquery-1.8.0.min.js"></script>
<script  type="text/javascript" src="../../../jsLib/uniform/jquery.uniform.js"  charset="utf-8"></script>
<script  type="text/javascript" src="../../../jsLib/js_scripts/js_function.js"></script>
<script  type="text/javascript" src="../../../jsLib/jquery-confirm/jquery.confirm.js"></script>
<script  type="text/javascript" src="../../../jsLib/jquery-confirm/js/script.js"></script>
<script type="text/javascript" src="../../../jsLib/datepicker/zebra_datepicker.js"></script>


<script>
$(function(){
    $("input, textarea").uniform();
    $(".uniform-select").uniform();
  });
</script>
</head>
<body scrolling="no">
  <div class="content-top">
  	<div class="_content-title">งานในความรับผิดชอบ  </div>
  	<div class="search-action">
  	    <span class="new-status" style="padding-left:30px;padding-right:10px;">รอการ Assign วันเปิดงาน</span>
  		<span class="process-status" style="padding-left:30px;padding-right:10px;">กำลังดำเนินการ</span>
  		<span class="wait-approval-status" style="padding-left:30px;padding-right:10px;">รอการอนุมัติ</span>
  		<span class="lock_disabled-status" style="padding-left:30px;padding-right:10px;">ยกเลิก</span>
  		<span class="lock-status" style="padding-left:30px;padding-right:10px;">Completed</span>
        <span class="noapprov-status" style="padding-left:30px;padding-right:10px;">ไม่อนุมัติ</span>
        <span class="returnedit-status" style="padding-left:30px;padding-right:10px;">ตีกลับแก้ไข</span>
  	</div>
  </div>
  <table width="98%" align="center">
    <tr>
  		<td width="">บริษัท :</td>
  		<td width=""><select name="fields[FRepair_comp_id]" id="FRepair_comp_id" class="uniform-select" onChange="javascript:updateBranch('');javascript:updateSection('');">
  		  <option value="">-------บริษัท-------</option>
  		  <?php if(!empty($comList)){ foreach ($comList as $key=>$val){?>
  		  <option value="<?php echo $val['comp_id'];?>" ><?php echo $val['comp_code']." - ".$val['comp_name'];?></option>
  		  <?php }}?>
	    </select></td>
  		<td width="">สาขา :</td>
  		<td>
  				 <select name="FBranchID" id="FBranchID" class="uniform-select" >
	            	<option value="">---กรุณาเลือกสาขา</option>
	            	<?php if(!empty($brnList)){
	            			foreach($brnList as $key=>$val){
	            	?>
	            				<option value="<?php print($val['brn_id']);?>"><?php print($val['brn_name']);?></option>
	            	<?php }}?>
              </select>
  		</td>
  		<td width="">แผนก : </td>
  		<td width=""><select name="sec_id" id="sec_id" class="uniform-select">
  		  <option value="">---กรุณาเลือกแผนก---</option>
  		  <?php foreach($sectLst as $key=>$val){?>
  		  <option value="<?php print($val['sec_id']);?>">[<?php print($val['sec_code']);?>] <?php print($val['sec_nameThai']);?></option>
  		  <?php }?>
	    </select></td>
  		<td width="">&nbsp;</td>
  		<td><a class="button-bule" href="javascript:void(0);" onclick="javascript:changePage();"> Search </a>
  			 <input type="hidden" name="FSupportID" id="FSupportID" value="<?php print($userId);?>">
  			 <input type="hidden" name="user_type" id="user_type" value="<?php print($userInfo['user_type'])?>">
  		</td>
  	</tr>
    <tr>
      <td>สถานะ </td>
      <td><select name="status" id="status" class="uniform-select">
        <option value="">---ทั้งหมด---</option>
        <option value="new"   selected >---รอการเปิด Job---</option>
        <option value="inprogress" <?php if($status=="inprogress"){?> selected <?php }?>>---กำลังดำเนินการ---</option>
        <option value="waiting" <?php if($status=="waiting"){?> selected <?php }?>>---รอการอนุมัติ---</option>
        <option value="finished" <?php if($status=="finished"){?> selected <?php }?>>---Completed---</option>
        <option value="cancel" <?php if($status=="cancel"){?> selected <?php }?>>---ยกเลิก---</option>
        <option value="noapprove" <?php if($status=="noapprove"){?> selected <?php }?>>---ไม่อนุมัติ---</option>
        <option value="returnedit" <?php if($status=="returnedit"){?> selected <?php }?>>---ตีกลับแก้ไข---</option>
        <option value="W_Approve" <?php if($status=="W_Approve"){?> selected <?php }?>>---งานอนุมัติ W---</option>
      </select></td>
      <td>วันที่บันทึก : </td>
      <td><input type="text" name="StartDate" id="StartDate" style="width:100px;" value="">
        -
      <input type="text" name="EndDate" id="EndDate" style="width:100px;" value=""></td>
      <td>คำค้น : </td>
      <td><input type="text" name="key_search" id="key-search" style="width:180px;" value=""></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
  </table>
  <div class="list-body">
  	<div class="list-header">
	  	<ul>
	  		<li></li>
	  		<li style="width:10%;">Req No</li>
	  		<li style="width:15%;text-align:left">ผู้แจ้ง</li>
	  		<li style="width:10%;text-align:left">แผนก</li>
	  		<li style="width:10%;text-align:left">ประเภทงาน</li>
	  		<li style="width:8%;">สถานะ</li>
            <li style="width:9%;">วันที่อนุมัติ</li>
	  		<li style="width:11%;">วันที่กำหนดเสร็จ</li>
	  		<li style="width:21%;">Action</li>
	  		<li></li>
	  	</ul>
	</div>
	<div class="list-items">
	
	</div>
  </div>
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
  			<input type="hidden" name="rg_id" id="rg_id" value="<?php print($repairgroup_id);?>">
  		</div>
  </div>
</body>
<script>
	$(document).ready(function (){
		var height = $(document).height();
		var main_body_height = height-140;
		$(".list-body").height(main_body_height);
		$(".list-items").height(main_body_height-40);

		$('#StartDate').Zebra_DatePicker({
			  //direction: true
		});
		$('#EndDate').Zebra_DatePicker({
			  //direction: true
		});

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

	function closePopup(){
		parent.closePopup();
	}
	
	function openPopup(id){
		if(isNaN(id))id='';
		parent.TINY.box.show({iframe:'../general_sys/templates/forms/repairgroupitem.form.php?id='+id+'&rg_id='+$('#rg_id').val(),boxid:'frameless',width:570,height:169,fixed:false,maskopacity:40});
	}
	function addNewRequest(){
		window.location.href = '../general_user/forms/repair-request.form.php?u_id=<?php print($userId);?>&e_id=<?php print($empId);?>&back=assign';
	}
	function assingSupport(id){
		if(isNaN(id))id='';
		window.location.href = '../general_user/forms/repair-request.form.php?u_id=<?php print($userId);?>&e_id=<?php print($empId);?>&FRequestID='+id+'&back=assign';
	}
	function confirmDelete(id){
		var buttons = '[{"title":"OK","class":"blue","action":"deleteData('+id+');"},{"title":"Cancel","class":"blue","action":""}]';
		buttons = eval(buttons);
		_confirm("warning","Warning","Confirm to delete data",buttons);
	}
	function openInformation(id){
		var w = screen.width-40;
		var h = screen.height-260;
		if(isNaN(id))id='';
		parent.TINY.box.show({iframe:'../eqiupment-repair/templates/informations/information-request.php?id='+id,boxid:'frameless',width:w,height:h,fixed:false,maskopacity:40});
	}
	function openClaims(id,no){
		var w = screen.width-40;
		var h = screen.height-260;
		if(isNaN(id))id='';
		parent.TINY.box.show({iframe:'../eqiupment-repair/templates/supports/claim-information.php?id='+id+'&r_no='+no,boxid:'frameless',width:w,height:h,fixed:false,maskopacity:40});
	}
	function openPurchase(id,no){
		var w = screen.width-40;
		var h = screen.height-260;
		if(isNaN(id))id='';
		parent.TINY.box.show({iframe:'../eqiupment-repair/templates/supports/purchase-information.php?id='+id+'&r_no='+no,boxid:'frameless',width:w,height:h,fixed:false,maskopacity:40});
	}
	function openJob(id){
		if(isNaN(id))id='';
		window.location.href = '../general_user/forms/repair-request.form.php?u_id=<?php print($userId);?>&e_id=<?php print($empId);?>&compId=<?php print($compId);?>&FRequestID='+id;
	}
	function closeJob(id){
		if(isNaN(id))id='';
		window.location.href = '../general_user/forms/repair-request.form.php?u_id=<?php print($userId);?>&e_id=<?php print($empId);?>&compId=<?php print($compId);?>&FRequestID='+id+'&close=holder';
	}
	function  deleteData(id){
		$.ajax({
			type: "POST",
			url: ("../../controllers/request_controller.php"),
			data: "1&function=delete&FRequestID="+id,
			dataType: 'json',
			success: function(data){
				changePage();
			}
		});
	}
	

	function changePage(){
		$.ajax({ 
			url: "../../controllers/request_controller.php" ,
			type: "POST",
			datatype: "json",
			data: {"function":"get_data_section_list",
				   "page":$('#select-page').val(),
				   //"search[t1.FRepair_comp_id][value]":$('#FRepair_comp_id').val(),
				   //"search[t1.FRepair_comp_id][condition]":"=",
				   "search[t1.FSectionID][value]":$('#sec_id').val(),
				   "search[t1.FSectionID][condition]":"=",
				   "search[t1.FBranchID][value]":$('#FBranchID').val(),
				   "search[t1.FBranchID][condition]":"=",
				   "search[t4.FStatus][value]":$('#status').val(),
				   "search[t4.FStatus][condition]":"=",
				   "search[duplicate][0][key]":"FReqDate",
				   "search[duplicate][0][value1]":$('#StartDate').val(),
				   "search[duplicate][0][condition1]":">=",
				   "search[duplicate][0][value2]":$('#EndDate').val(),
				   "search[duplicate][0][condition2]":"<=",
				   "search[FSupportID][value]":$('#FSupportID').val(),
				   "search[FSupportID][condition]":"=",
				   "search[multi][value]":$('#key-search').val(),
				   "search[multi][fields][FDetail]":"like",
				   "search[multi][fields][FSerial]":"like",
				   "search[multi][fields][FReqNo]":"like",
				   "keysearch":$('#key-search').val()
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
					var ul = "<ul>";
						ul+= "<li></li>";
					  	ul+= "<li style=\"width:10%;\">"+cell['FReqNo']+"</li>";
					  	ul+= "<li style=\"width:15%;text-align:left;\">"+cell['RequestName']+"</li>";
					  	ul+= "<li style=\"width:10%;text-align:left;\">"+cell['FSectionName']+"</li>";
					  	ul+= "<li style=\"width:10%;text-align:left;\">"+cell['FRepairGroupItemName']+"</li>";
					  	ul+= "<li style=\"width:8%;\"><span class=\""+cell['OwnerStatusIcon']+"\"></span></li>";
						ul+= "<li style=\"width:9%;\">"+cell['approve_date']+"</li>";
					  	ul+= "<li style=\"width:11%;\">"+cell['FReqDate']+"</li>";
					  	ul+= "<li style=\"width:7.33%;\"><span class=\"info-icon\" onclick=\"javascript:openInformation('"+cell['FRequestID']+"');\">Information</span></li>";
					  	ul+= "<li style=\"width:6.33%;\"><span class=\"shuffle-icon\" onclick=\"javascript:openClaims('"+cell['FRequestID']+"','"+cell['FReqNo']+"');\">Claim</span></li>";
					  	ul+= "<li style=\"width:5.33%;\"><span class=\"dollar-icon\" onclick=\"javascript:openPurchase('"+cell['FRequestID']+"','"+cell['FReqNo']+"');\">Order</span></li>";
					  	if(cell['OwnerStatus']=='inprogress')ul+= "<li style=\"width:7.33%;\"><span class=\"shut-down-icon\" onclick=\"javascript:closeJob('"+cell['FRequestID']+"');\">ปิดงาน</span></li>";
					  	else if(cell['OwnerStatus']=='new')ul+= "<li style=\"width:7.33%;\"><span class=\"tools-icon\" onclick=\"javascript:openJob('"+cell['FRequestID']+"');\">เปิดงาน</span></li>";
						 else if(cell['OwnerStatus']=='returnedit')ul+= "<li style=\"width:7.33%;\"><span class=\"tools-icon\" onclick=\"javascript:closeJob('"+cell['FRequestID']+"');\">แก้ไขงาน</span></li>";
					  	else ul+= "<li style=\"width:7.33%;\"><span class=\"shut-down-disable-icon\">ปิดงาน</span></li>";
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

	function cancelReq(no,id){
    	parent.TINY.box.show({iframe:'../eqiupment-repair/templates/general_user/forms/request-cancel.form.php?id='+id+'&no='+no,boxid:'frameless',width:570,height:240,fixed:false,maskopacity:40});
    }
   
	function openPrint(id){
	    	var width = screen.width-10;
			var height = screen.height-60;
	    	newwindow=window.open('../informations/print-request-preview.php?id='+id,
					  'requestInformationWindow-'+id,'width='+width+',height='+height+',left=0,top=0,screenX=0,screenY=0,status=no,menubar=yes,scrollbars=yes,copyhistory=yes, resizable=yes,fullscreen=no');
	}/*End of function openInfor(id)*/
	
	function updateBranch(selected){
			$.ajax({
					type: "POST",
					url: ("../../../main/controllers/utilities_controller.php"),
					data: "1&function=get_BranchJson&comp_id="+$('#FRepair_comp_id').val(),
					dataType: 'json',
					success: function(data){
							appendOption(document.getElementById('FBranchID'),data);
							if(selected!=''){
									$("#brn_id option[value=" +selected+"]").attr("selected","selected") ;
							}
							setSelectValue('FBranchID');
					}
				});
	}	
	function updateSection(selected){
			$.ajax({
					type: "POST",
					url: ("../../../main/controllers/utilities_controller.php"),
					data: "1&function=get_SectJson&comp_id="+$('#FRepair_comp_id').val(),
					dataType: 'json',
					success: function(data){
							appendOption(document.getElementById('sec_id'),data);
							if(selected!=''){
									$("#sec_id option[value=" +selected+"]").attr("selected","selected") ;
							}
							setSelectValue('sec_id');
					}
				});
	}
</script>
</html>