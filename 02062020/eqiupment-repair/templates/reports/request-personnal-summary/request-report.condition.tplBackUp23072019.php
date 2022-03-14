<?php 
include '../../../../lib/db_config.php';
include '../../../../main/modules/Model_Utilities.php';
include '../../../modules/purchase_model.php';

$utilMD = new Model_Utilities();
$objMD = new Model_Purchase();
$compId = $_REQUEST['compId'];
$brnList = $utilMD->get_BranchList($compId);
$sectList = $utilMD->get_SectList($compId,'');
$_suplierList = $utilMD->get_suplierList();
?>
<?php /* <!DOCTYPE HTML"> */?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=MS874">
<script  type="text/javascript" src="../../../../jsLib/jquery-1.8.0.min.js"></script>
<script src="../../../../jsLib/uniform/jquery.uniform.js" type="text/javascript" charset="utf-8"></script>
<script src="../../../../jsLib/js_scripts/js_function.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript" src="../../../../jsLib/datepicker/zebra_datepicker.js"></script>
<script  type="text/javascript" src="../../../../jsLib/jquery-confirm/jquery.confirm.js"></script>
<script  type="text/javascript" src="../../../../jsLib/jquery-confirm/js/script.js"></script>
<script type="text/javascript" src="../../../../jsLib/jquery-nicescroll/jquery.nicescroll.min.js"></script>

<link href="../../../../css/dialog-box.css" rel="stylesheet" type="text/css">
<link href="../../../../css/sys_controll.css" rel="stylesheet" type="text/css">
<link href="../../../../css/input.css" rel="stylesheet" type="text/css">
<link href="../../../../css/stylesheet_report.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="../../../../jsLib/uniform/css/uniform.default.css" type="text/css" media="screen">
<link href="../../../../jsLib/jquery-confirm/jquery.confirm.css" rel="stylesheet" type="text/css">
<link href="../../../../jsLib/datepicker/css/default.css" rel="stylesheet" type="text/css" />
<title>Insert title here</title>
<script type="text/javascript" charset="utf-8">
      $(function(){
        $("input, textarea, select").uniform();
      });
</script>

<style>
.dialog-panel{
	width:80%;
}
.report-header {
    width: 100%;
}
.report-header td {
    background-color: #09a2e4;
    border-right: 1px solid #016c9a;
    border-top: 1px solid #016c9a;
}
.report-detail{
	width: 100%;
}
.preloading{
   background-image: url('../../../../images/preload.gif');
   background-repeat: none;
   background-position: center;
}
#report-content{
	background-color : #FFFFFF;
	border: 1px solid #999999;
	width : 98%;
	margin : 30px auto 0px;
}
</style>
</head>
<body>
   <div class="dialog-panel condition-panel" style="top:15.5px;">
   		<div class="top-row">
   			<div class="left"></div>
   			<div class="center">
   				<span class="dialog-title">��§ҹ��ػ����ؤ��</span>
   			</div>
   			<div class="right"></div>
   		</div> 
   		<div class="middle-row" style="height:100%;">
   			<div class="left"></div>
   			<div id="dialog-body" class="center">
   				<table width="100%" border="0">
   				   <tr>
   						<td style="width:15%"><b>Ἱ� :</b></td>
   						<td>
   							<select name="sec_id" id="sec_id" class="uniform-select">
   								<option value="">---������---</option>
				            	<?php if(!empty($sectList)){
				            			foreach($sectList as $key=>$val){
				            	?>
				            				<option value="<?php print($val['sec_id']);?>"><?php print($val['sec_nameThai']);?></option>
				            	<?php }}?>
   							</select>
   						</td>
   						<td><b>�Ң� :</b></td>
   						<td>
   							<select name="brn_id" id="brn_id" class="uniform-select">
   								<option value="">---������---</option>
				            	<?php if(!empty($brnList)){
				            			foreach($brnList as $key=>$val){
				            	?>
				            				<option value="<?php print($val['brn_id']);?>" <?php if($val['brn_id']== $userInfo['brn_id']){?>selected<?php }?>><?php print($val['brn_name']);?></option>
				            	<?php }}?>
   							</select>
   						</td>
   					</tr>
   					<tr>
   						<td><b>�ѹ����� :</b></td>
   						<td style="width:35%">
   							<input type="text" name="SRequestDate" id="SRequestDate" style="width:80%;" value="">
   							-
   							<input type="text" name="ERequestDate" id="ERequestDate" style="width:80%;" value="">
   						</td>
   						<td style="width:15%"><b>�ѹ����˹����� :</b></td>
   						<td style="width:35%">
   							<input type="text" name="SDueDate" id="SDueDate" style="width:80%;" value="">
   							-
   							<input type="text" name="EDueDate" id="EDueDate" style="width:80%;" value="">
   						</td>
   					</tr>
     			</table>
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
                            <a class="button-bule" href="javascript:void(0);" onclick="javascript:new_oldWindow();"> EXCEL��§ҹ���  </a>
   			    		</td>
   			    		<td align="right">
   			    				<a class="button-bule" href="javascript:void(0);" onclick="javascript:runReport();"> �͡��§ҹ  </a>
   			    		</td>
   			    	</tr>
   			    </table>
   			</div>
   			<div class="right"></div>
   		</div>
   </div>
   <div id="report-content">
   		<table class="report-header" border="0" cellspacing="0" cellpadding="0">
   				<tr>
   						<td align="center" rowspan="2" width="20%" valign="middle">&nbsp;&nbsp;<b>���ͼ���Ѻ�Դ�ͺ</b></td>
   						<td align="center" colspan="5" width="20%" valign="middle"><b>�������ҹ</b></td>
						<td align="center" rowspan="2" width="5%" valign="middle">&nbsp;&nbsp;<b>�����ͧ</b></td>
						<td align="center" rowspan="2" width="5%" valign="middle">&nbsp;&nbsp;<b>���.���Թ���</b></td>
   						<td align="center" colspan="4" widht="50%"><b>�ӹǹ�ҹ</b></td>

   				</tr>
   				<tr>
   					    	<td align="center" width="5%">S</td>
   					    	<td align="center" width="5%">L</td>
   					    	<td align="center" width="5%">M</td>
   					    	<td align="center" width="5%">H</td>
   					    	<td align="center" width="5%">P</td>
   						<td align="center" width="8%"><b>�Ѻ������</b></td>
   						<td align="center" width="8%"><b>���������ҧ���Թ���</b></td>
   						<td align="center" width="8%"><b>��</b></td>
   						<td align="center" width="8%"><b>�ѧ�������Թ���</b></td>
   				</tr>
   		</table>
   		<div class="report-detail">
   				<table id="tbl-report-detail" width="100%" border="0" cellspacing="0" cellpadding="0">
   						<tbody id="tb-report-detail">
   							
   						</tbody>
   				</table>
   		</div>
   </div>
</body>
<script>
$(document).ready(function() {
	 var doc_height = $(document).height();
	 var condition_height = $('.condition-panel').height();
	 var log_top = (doc_height-condition_height)/2;
	 var report_content_height = doc_height - (condition_height+39);
	 var report_headerH = $('.report-header').height();
	 var report_detailH  = report_content_height - report_headerH;
	 
	 $('#report-content').css('height',report_content_height+'px');
	 $('.report-detail').css('height',report_detailH+'px');


	 $('#SRequestDate').Zebra_DatePicker();
	 $('#ERequestDate').Zebra_DatePicker();
	 $('#SDueDate').Zebra_DatePicker();
	 $('#EDueDate').Zebra_DatePicker();
	 $('.selector').css('width','80%');
	 $('.selector > span').css('width','80%');
	 $('.uniform-select').css('width','85%');
});

function new_oldWindow(){
   
	var width = screen.width-10;
	var height = screen.height-60;
	var params = "";
	params +="sec_id="+$('#sec_id').val();
	params +="&brn_id="+$('#brn_id').val();
	params +="&SRequestDate="+$('#SRequestDate').val();
	params +="&ERequestDate="+$('#ERequestDate').val();
	params +="&SDueDate="+$('#SDueDate').val();
	params +="&EDueDate="+$('#EDueDate').val();
	newwindow=window.open('request-report_page_old.php?'+params,							  'reportWindow'+Math.random()*10000,'width='+width+',height='+height+',left=0,top=0,screenX=0,screenY=0,status=no,menubar=yes,scrollbars=yes,copyhistory=yes, resizable=yes,fullscreen=no');
}

function runReport(){
	$("#tbl-report-detail").hide();
	$("#tb-report-detail").empty();
	$(".report-detail").addClass("preloading");
	$.ajax({ 
		url: "request-report.query.php" ,
		type: "POST",
		datatype: "json",
		data: {"sec_id":$('#sec_id').val(),
					"brn_id":$('#brn_id').val(),
					"SRequestDate":$('#SRequestDate').val(),
					"ERequestDate":$('#ERequestDate').val(),
					"SDueDate":$('#SDueDate').val(),
					"EDueDate":$('#EDueDate').val()
		}
	})
	.success(function(results) { 
    //alert(results);
		results = jQuery.parseJSON(results);
		if(results!=null){
			for(var i=0;i<results.length;i++){
				var cell = results[i];
				var td = '<tr>';
				td+='			<td align="left" class="rb" width="20%">&nbsp;&nbsp;'+cell['support_name']+'</td>';
				td+='			<td align="center" class="rb" width="5%">&nbsp;'+cell['JobS']+'</td>';
				td+='			<td align="center" class="rb" width="5%">&nbsp;'+cell['JobL']+'</td>';
				td+='			<td align="center" class="rb" width="5%">&nbsp;'+cell['JobM']+'</td>';
				td+='			<td align="center" class="rb" width="5%">&nbsp;'+cell['JobH']+'</td>';
				td+='			<td align="center" class="rb" width="5%">&nbsp;'+cell['JobP']+'</td>';
                td+='			<td align="center" class="rb" width="5%">&nbsp;'+cell['Jobresult']+'</td>';
                td+='			<td align="center" class="rb" width="5%">&nbsp;'+cell['Jobresult2']+'</td>';
				td+='			<td align="center" class="rb" width="8%">'+cell['totalJob']+'</td>';
				td+='			<td align="center" class="rb" width="8%" onmouseOver="this.style.cursor=\'pointer\';"  onclick="javascript:openInformation(\''+cell['support_id']+'\',\''+cell['support_name']+'\',1);">'+cell['inprogressJob']+'</td>';
				td+='			<td align="center" class="rb" width="8%">'+cell['finishedJob']+'</td>';
				td+='			<td align="center" class="b" width="8%" onmouseOver="this.style.cursor=\'pointer\';"  onclick="javascript:openInformation(\''+cell['support_id']+'\',\''+cell['support_name']+'\',2);">'+cell['newJob']+'</td>';
				td+= "<tr>";
				$("#tb-report-detail").append(td);
			}
		}
		$("#tbl-report-detail").show();
		$(".report-detail").removeClass("preloading");
		 var nice = $(".report-detail").niceScroll({touchbehavior:false,cursoropacitymax:0.6,cursorwidth:5});
	});
	
}

function openInformation(id,name,status){
	var w = screen.width-40;
	var h = screen.height-260;
	if(isNaN(id))id='';
	var params = "";
	params +="sec_id="+$('#sec_id').val();
	params +="&brn_id="+$('#brn_id').val();
	params +="&SRequestDate="+$('#SRequestDate').val();
	params +="&ERequestDate="+$('#ERequestDate').val();
	params +="&SDueDate="+$('#SDueDate').val();
	params +="&EDueDate="+$('#EDueDate').val();
	params +="&support_id="+id;
	params +="&support_name="+name;
	if(status==1){
		params +="&status="+"'inprogress','waiting','returnedit'";
		parent.TINY.box.show({iframe:'../eqiupment-repair/templates/reports/request-personnal-summary/request-report-inprogress.tpl.php?id='+id+'&'+params,boxid:'frameless',width:w,height:h,fixed:false,maskopacity:40});
	}else if(status==2){
		params +="&status="+"'new'";
		parent.TINY.box.show({iframe:'../eqiupment-repair/templates/reports/request-personnal-summary/request-report-not-start.tpl.php?id='+id+'&'+params,boxid:'frameless',width:w,height:h,fixed:false,maskopacity:40});
	}
}

function newWindow(){
	var width = screen.width-10;
	var height = screen.height-60;
	var params = "";
	params +="sec_id="+$('#sec_id').val();
	params +="&brn_id="+$('#brn_id').val();
	params +="&SRequestDate="+$('#SRequestDate').val();
	params +="&ERequestDate="+$('#ERequestDate').val();
	params +="&SDueDate="+$('#SDueDate').val();
	params +="&EDueDate="+$('#EDueDate').val();
	newwindow=window.open('request-report.page.tpl.php?'+params,
								  'reportWindow'+Math.random()*10000,'width='+width+',height='+height+',left=0,top=0,screenX=0,screenY=0,status=no,menubar=yes,scrollbars=yes,copyhistory=yes, resizable=yes,fullscreen=no');
}

</script>
</html>