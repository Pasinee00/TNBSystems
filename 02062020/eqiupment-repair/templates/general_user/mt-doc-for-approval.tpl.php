<!DOCTYPE html>
<?php 
include '../../../lib/db_config.php';
include '../../../main/modules/Model_Utilities.php';
$utilMD = new Model_Utilities();
$compId = (!empty($_REQUEST['compId']))?$_REQUEST['compId'] : "7";
$comList = $utilMD->get_CompList();
$brnList = $utilMD->get_BranchList($comList);
$sectLst = $utilMD->get_SectList($comList, '');
$empId = $_REQUEST['e_id'];
$userId = $_REQUEST['u_id'];
$status = $_REQUEST['status'];
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
  	<div class="_content-title">เอกสารขอความเห็นชอบและอนุมัติ </div>
  	<div class="search-action">
  	    <span class="new-status" style="padding-left:30px;padding-right:10px;">รอการส่งขออนุมัติ</span>
  		<span class="wait-approval-status" style="padding-left:30px;padding-right:10px;">รอการอนุมัติ</span>
  		<span class="lock_disabled-status" style="padding-left:30px;padding-right:10px;">ยกเลิก</span>
		<span class="lock-status" style="padding-left:30px;padding-right:10px;">Completed</span>
       	<span class="noapprov-status" style="padding-left:30px;padding-right:10px;">ไม่อนุมัติ</span>
        <span class="returnedit-status" style="padding-left:30px;padding-right:10px;">ตีกลับแก้ไข</span>
        </div>
  </div>
 
  <table width="98%" align="center" border="0">
    <tr>
  		<td width="6%" >บริษัท :
		
		<?PHP echo strpos("พัชรีพร สุวัฒน","พัชรีพร สุวัฒนพงษ์");
			/* if(strpos("พัชรีพร สุวัฒนพงษ์","พัชรีพร")){
				echo "1111";
			}else{
				echo "2222";
			}  */
			
			?>
			<?php

echo strpos("Hello world","wo");

?>
		</td>
  		<td width="22%" ><select name="fields[Fcomp_id]" id="Fcomp_id" class="uniform-select" onChange="javascript:updateBranch('');javascript:updateSection('');">
             <option value="">-------บริษัท-------</option>
             <?php if(!empty($comList)){ foreach ($comList as $key=>$val){?>
             <option value="<?php echo $val['comp_id'];?>" ><?php echo $val['comp_code']." - ".$val['comp_name'];?></option>
             <?php }}?>
        </select></td>
  		<td width="9%">สาขา :</td>
  		<td width="27%"><select name="FBranchID" id="FBranchID" class="uniform-select" >
  		  <option value="">---กรุณาเลือกสาขา</option>
  		  <?php if(!empty($brnList)){
	            			foreach($brnList as $key=>$val){
	            	?>
  		  <option value="<?php print($val['brn_id']);?>" <?php if($val['brn_id']== $userInfo['brn_id']){?>selected<?php }?>><?php print($val['brn_name']);?></option>
  		  <?php }}?>
	    </select></td>
  		<td width="7%">&nbsp;</td>
  		<td width="21%">&nbsp;</td>
  		<td width="8%" rowspan="2" align="center"><a class="button-bule" href="javascript:void(0);" onclick="javascript:changePage();"> Search </a>	    </td>
	</tr>
    <tr>
      <td>สถานะ :</td>
      <td><select name="status" id="status" class="uniform-select">
        <option value="">---ทั้งหมด---</option>
        <option value="new"   selected >---รอการส่งขออนุมัติ---</option>
        <option value="waiting" <?php if($status=="waiting"){?> selected <?php }?>>---รอการอนุมัติ---</option>
        <option value="finished" <?php if($status=="finished"){?> selected <?php }?>>---Completed---</option>
        <option value="cancel" <?php if($status=="cancel"){?> selected <?php }?>>---ยกเลิก---</option>
        <option value="noapprove" <?php if($status=="noapprove"){?> selected <?php }?>>---ไม่อนุมัติ---</option>
        <option value="returnedit" <?php if($status=="returnedit"){?> selected <?php }?>>---ตีกลับแก้ไข---</option>
		  <option value="waiting_M" <?php if($status=="waiting_M"){?> selected <?php }?>>---รอการอนุมัติจากคุณพัชรีพร สุวัฒนพงษ์---</option>
      </select></td>
      <td>วันที่บันทึก :</td>
      <td><input type="text" name="StartDate" id="StartDate" style="width:100px;" value="">
      - 
      <input type="text" name="EndDate" id="EndDate" style="width:100px;" value=""></td>
      <td>คำค้น :</td>
      <td><input type="text" name="key_search" id="key-search" style="width:180px;" value=""></td>
    </tr>
  </table>
  <div class="list-body">
  	<div class="list-header">
	  	<ul>
	  		<li></li>
	  		<li style="width:10%;">Req No</li>
	  		<li style="width:19%;text-align:left">โครงการ</li>
	  		<li style="width:15%;text-align:left">เรื่อง</li>
	  		<li style="width:10%;text-align:left">ผู้อนุมัติ/ไม่อนุมัติล่าสุด</li>
	  		<li style="width:8%;">สถานะ</li>
	  		<li style="width:11%;">วันที่บันทึก</li>
	  		<li style="width:21%;">Action</li>
	  		<li>
	  			<span class="add-icon" onclick="javascript:addNewDocForApp();"></span>
	  		</li>
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
	
	function addNewDocForApp(){
		window.location.href = '../general_user/forms/mt-doc-for-approval.form.php?u_id=<?php print($userId);?>&e_id=<?php print($empId);?>';
	}
	function edit_doc_app(id){
		if(isNaN(id))id='';
		window.location.href = '../general_user/forms/mt-doc-for-approval.form.php?u_id=<?php print($userId);?>&e_id=<?php print($empId);?>&compId=<?php print($compId);?>&Fdoc_app_id='+id+'&back=assign';
	}
	function confirmDelete(id){
		var buttons = '[{"title":"OK","class":"blue","action":"deleteData('+id+');"},{"title":"Cancel","class":"blue","action":""}]';
		buttons = eval(buttons);
		_confirm("warning","Warning","Confirm to delete data",buttons);
	}
	
	function  deleteData(id){
		$.ajax({
			type: "POST",
			url: ("../../controllers/documents-app-controller.php"),
			data: "1&function=delete&Fdoc_app_id="+id,
		//	dataType: 'json',
			success: function(data){
				changePage();
			}
		});
	}
	

	function changePage(){
		$.ajax({ 
			url: "../../controllers/documents-app-controller.php",
			type: "POST",
			datatype: "json",
			data: {"function":"get_data_list",
				   "page":$('#select-page').val(),
				   "search[t1.Fcomp_id][value]":$('#Fcomp_id').val(),
				   "search[t1.Fcomp_id][condition]":"=",
				   "search[t1.FBranchID][value]":$('#FBranchID').val(),
				   "search[t1.FBranchID][condition]":"=",
				   "search[t1.Fdoc_appSt][value]":$('#status').val(),
				   "search[t1.Fdoc_appSt][condition]":"=",
				   "search[duplicate][0][key]":"Fdoc_app_date",
				   "search[duplicate][0][value1]":$('#StartDate').val(),
				   "search[duplicate][0][condition1]":">=",
				   "search[duplicate][0][value2]":$('#EndDate').val(),
				   "search[duplicate][0][condition2]":"<=",
				   "search[multi][value]":$('#key-search').val(),
				   "search[multi][fields][Fdoc_app_project]":"like",
				   "search[multi][fields][Fdoc_app_name]":"like",
				   "search[multi][fields][Fdoc_app_no]":"like",
				   "keysearch":$('#key-search').val()
			}
		})
		.success(function(results) { 
			//alert(results);
			console.log(results);
			$(".list-items").empty();
			results = jQuery.parseJSON(results);
			var rows = results['rows'];
			var begin = results['begin'];
			var end = results['end'];
			var total = results['total'];
			var total_page = results['total_page'];
			var page = results['page'];
			//alert(rows);
			if(rows!=null){
				for(var i=0;i<rows.length;i++){
					var cell = rows[i]['cell'];
					var ul = "<ul>";
						ul+= "<li></li>";
					  	ul+= "<li style=\"width:10%;\">"+cell['Fdoc_app_no']+"</li>";
					  	ul+= "<li style=\"width:19%;text-align:left;\">"+cell['Fdoc_app_project']+"</li>";
					  	ul+= "<li style=\"width:15%;text-align:left;\">"+cell['Fdoc_app_name']+"</li>";
					  	ul+= "<li style=\"width:10%;text-align:left;\">"+cell['approval_name']+"</li>";
					  	ul+= "<li style=\"width:8%;\"><span class=\""+cell['StatusIcon']+"\"></span></li>";
					  	ul+= "<li style=\"width:11%;\">"+cell['Fdoc_app_date']+"</li>";
						ul+= "<li style=\"width:6.33%;\">";
						if(cell['Fdoc_appSt']=="waiting" && cell['FownerApp']=="Y" && cell['Fmanager_mtApp']=="Y"){
						ul+= "<span class=\"office-folder-icon\" onclick=\"javascript:receiveDoc('"+cell['Fdoc_app_no']+"','"+cell['Fdoc_app_id']+"');\">Receive</span>";
						   }
						ul+= "</li>";
					  	ul+= "<li style=\"width:4.33%;\"><span class=\"print-icon\" onclick=\"javascript:openPrint('"+cell['Fdoc_app_id']+"');\">Print</span></li>";
					  	ul+= "<li style=\"width:5%;\"><span class=\"edit-icon\" onclick=\"javascript:edit_doc_app('"+cell['Fdoc_app_id']+"');\">แก้ไข</span></li>";
					  	ul+= "<li style=\"width:5.33%;\"><span class=\"remove-icon\" onclick=\"javascript:cancelReq('"+cell['Fdoc_app_no']+"',"+cell['Fdoc_app_id']+");\">Cancel</span></li>";
					  	ul+= "<li style=\"width:5.33%;\"><span class=\"trash-icon\" onclick=\"javascript:confirmDelete('"+cell['Fdoc_app_id']+"');\">Del</span></li>";
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
    	parent.TINY.box.show({iframe:'../eqiupment-repair/templates/general_user/forms/doc-app-cancel.form.php?id='+id+'&no='+no,boxid:'frameless',width:570,height:240,fixed:false,maskopacity:40});
    }
   
	function openPrint(id){
	    	var width = screen.width-10;
			var height = screen.height-60;
	    	newwindow=window.open('../informations/print-doc-for-approval.php?id='+id,
					  'requestInformationWindow-'+id,'width='+width+',height='+height+',left=0,top=0,screenX=0,screenY=0,status=no,menubar=yes,scrollbars=yes,copyhistory=yes, resizable=yes,fullscreen=no');
	}/*End of function openInfor(id)*/
	
	function updateBranch(selected){
			$.ajax({
					type: "POST",
					url: ("../../../main/controllers/utilities_controller.php"),
					data: "1&function=get_BranchJson&comp_id="+$('#Fcomp_id').val(),
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
					data: "1&function=get_SectJson&comp_id="+$('#Fcomp_id').val(),
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
	function receiveDoc(no,id){
		//alert(no);alert(S);
    	parent.TINY.box.show({iframe:'../eqiupment-repair/templates/general_user/forms/receive_doc_app.form.php?id='+id+'&no='+no+'&u_id=<?php print($userId);?>',boxid:'frameless',width:570,height:390,fixed:false,maskopacity:40});
    }
</script>
</html>