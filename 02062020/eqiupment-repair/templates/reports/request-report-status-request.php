<!DOCTYPE HTML">
<?php 
include '../../../lib/db_config.php';
include '../../../main/modules/Model_Utilities.php';

$utilMD = new Model_Utilities();

$comList = $utilMD->get_CompList();
$brnList = $utilMD->get_BranchList($comList);
$sectLst = $utilMD->get_SectList($comList, '');
$repairGroups = $utilMD->get_RepairGroupList();
$_suplierList = $utilMD->get_suplierList();
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<script  type="text/javascript" src="../../../jsLib/jquery-1.8.0.min.js"></script>
<script src="../../../jsLib/uniform/jquery.uniform.js" type="text/javascript" charset="utf-8"></script>
<script src="../../../jsLib/js_scripts/js_function.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript" src="../../../jsLib/datepicker/zebra_datepicker.js"></script>
<script  type="text/javascript" src="../../../jsLib/jquery-confirm/jquery.confirm.js"></script>
<script  type="text/javascript" src="../../../jsLib/jquery-confirm/js/script.js"></script>

<link href="../../../css/dialog-box.css" rel="stylesheet" type="text/css">
<link href="../../../css/sys_controll.css" rel="stylesheet" type="text/css">
<link href="../../../css/input.css" rel="stylesheet" type="text/css">
<link href="../../../css/stylesheet_report.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="../../../jsLib/uniform/css/uniform.default.css" type="text/css" media="screen">
<link href="../../../jsLib/jquery-confirm/jquery.confirm.css" rel="stylesheet" type="text/css">
<link href="../../../jsLib/datepicker/css/default.css" rel="stylesheet" type="text/css" />
<title></title>
<script type="text/javascript" charset="utf-8">
      $(function(){
        $("input, textarea, select").uniform();
      });
</script>
<style>
		.Zebra_DatePicker_Icon_Wrapper{width:115px;}
</style>
</head>
<body>
    <div class="dialog-panel condition-panel">
        <div class="top-row">
            <div class="left"></div>
                <div class="center">
                    <span class="dialog-title">รายงานใบแจ้งซ่อม</span>
                </div>
            <div class="right"></div>
        </div> 

        <div class="middle-row" style="height:100%;">
            <div class="left"></div>
                <div id="dialog-body" class="center">
                    <table width="100%" border="0">
                    
                        <tr>
   							<td style="width:15%"><b>บริษัท :</b></td>
   							<td colspan="5">
                                <select name="fields[FRepair_comp_id]" id="FRepair_comp_id" class="uniform-select" onChange="javascript:updateBranch('');javascript:updateSection('');">
             						<option value="">---เลือกบริษัท---</option>
             						<?php if(!empty($comList)){ foreach ($comList as $key=>$val){?>
             						<option value="<?php echo $val['comp_id'];?>" ><?php echo $val['comp_code']." - ".iconv("tis-620","utf-8",$val['comp_name']);?></option>
             						<?php }}?>
        						</select>
                            </td>
   				        </tr>
                        
                        
                        <tr>
                            <td style="width:15%"><b>สาขา :</b></td>
                            <td colspan="5"><select name="brn_id" id="brn_id" class="uniform-select">
                              <option value="">---เลือกสาขา---</option>
                                  <?php if(!empty($brnList)){
                                            foreach($brnList as $key=>$val){
                                    ?>
                                  <option value="<?php print($val['brn_id']);?>" <?php if($val['brn_id']== $userInfo['brn_id']){?>selected<?php }?>><?php print($val['brn_name']);?></option>
                                  <?php }}?>
                              </select>
                            </td>
   						</tr>
                        
                        <tr>
                            <td><span style="width:15%"><b>แผนก :</b></span></td>
                            <td colspan="5"><select name="sec_id" id="sec_id" class="uniform-select">
                              <option value="">----เลือกแผนก---</option>
                              <?php if(!empty($sectList)){
                                            foreach($sectList as $key=>$val){
                                    ?>
                              <option value="<?php print($val['sec_id']);?>"><?php print($val['sec_nameThai']);?></option>
                              <?php }}?>
                            </select></td>
   						</tr>
                        
                        <tr>
                            <td><b>วันที่แจ้ง :</b></td>
                            <td colspan="2" style="width:35%">
                                <input type="text" name="SRequestDate" id="SRequestDate" style="width:100%;" value="">
                                -
                                <input type="text" name="ERequestDate" id="ERequestDate" style="width:100%;" value="">
                            </td>
   						</tr>
                        
                        <tr>
                            <td><b>วันที่กำหนดเสร็จ :</b></td>
                            <td colspan="2" style="width:35%">
                                <input type="text" name="SDefineFinalDate" id="SDefineFinalDate" style="width:100%;" value="">
                                -
                                <input type="text" name="EDefineFinalDate" id="EDefineFinalDate" style="width:100%;" value="">
                            </td>
   						</tr>
                        
                        
                         <tr>
                            <td><b>วันที่เสร็จ :</b></td>
                            <td colspan="2" style="width:35%">
                                <input type="text" name="SFinalDate" id="SFinalDate" style="width:100%;" value="">
                                -
                                <input type="text" name="EFinalDate" id="EFinalDate" style="width:100%;" value="">
                            </td>
   						</tr>
                        
                        <tr>
                            <td><b>สถานะ :</b></td>
                            <td colspan="5">
                                <select class="uniform-select" name="FStatus" id="FStatus">
                                    <option value="">---เลือกสถานะ---</option>
                                    <option value="new">---รอการแก้ไข---</option>
                                    <option value="waiting">---รอการอนุมัติ---</option>
                                    <option value="inprogress">---กำลังทำการแก้ไข---</option>
                                    <option value="finished">---ทำการแก้ไขเสร็จเรียบร้อย---</option>
                                    <option value="cancel">---ถูกยกเลิก---</option>
                                    <option value="noapprove">---ไม่อนุมัติ---</option>
                                </select>
                            </td>
   						</tr>
                        
                         <tr>
           <td><b>ขอแจ้งซ่อม</b> <font color="#FF0000">*</font> :</td>
           <td><select name="fields[FRepairGroupID]" id="FRepairGroupID" class="uniform-select" onChange="javascript:updateRepairItem(this.value);">
             <option value="">---กรุณาเลือกรายการขอแจ้งซ่อม</option>
             <?php if(!empty($repairGroups)){
            			foreach($repairGroups as $key=>$val){
            	?>
             <option value="<?=$val['FRepairGroupID']?>"><?=iconv("tis-620","utf-8",$val['FRepairGroupName'])?></option>
             <?php }}?>
           </select></td>
           <td>&nbsp;</td>
           <td><b>รายการซ่อม</b>  <font color="#FF0000">*</font> :</td>
           <td>
            <select name="fields[FRepairGroupItemID]" id="FRepairGroupItemID" class="uniform-select" style="width:200px;">
            	<option value="">---กรุณาเลือกรายการซ่อม</option>
            </select>
           </td>
           <td></td>
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
   			    			 <a class="button-bule" href="javascript:void(0);" onclick="javascript:newExcel();"> EXCEL</a>
   			    		</td>
   			    		<td align="right">
   			    				<a class="button-bule" href="javascript:void(0);" onclick="javascript:newPage();"> Search</a>
   			    		</td>
   			    	</tr>
   			    </table>
   			</div>
   			<div class="right"></div>
   		</div>
        
    </div>
    <script>
        $(document).ready(function() {
            var doc_height = $(document).height();
            var condition_height = $('.condition-panel').height();
            var log_top = (doc_height-condition_height)/2;
            
            $('.condition-panel').css('top',log_top+"px");

            $('#SRequestDate').Zebra_DatePicker();
            $('#ERequestDate').Zebra_DatePicker();
            $('#SDefineFinalDate').Zebra_DatePicker();
            $('#EDefineFinalDate').Zebra_DatePicker();
            $('#SFinalDate').Zebra_DatePicker();
            $('#EFinalDate').Zebra_DatePicker();
        	
            $('.selector').css('width','80%');
            $('.selector > span').css('width','80%');
            $('.uniform-select').css('width','85%');  
        });
                
        
    function updateBranch(selected){
			$.ajax({
					type: "POST",
					url: ("../../../main/controllers/utilities_controller.php"),
					data: "1&function=get_BranchJson&comp_id="+$('#FRepair_comp_id').val(),
					dataType: 'json',
					success: function(data){
							appendOption(document.getElementById('brn_id'),data);
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
    
    function updateRepairItem(selected){
		$.ajax({
				type: "POST",
				url: ("../../../main/controllers/utilities_controller.php"),
				data: "1&function=get_RepairGroupItemJson&group_id="+selected,
				dataType: 'json',
				success: function(data){
                		//console.log(data);
						appendOption(document.getElementById('FRepairGroupItemID'),data);
						$("#FRepairGroupItemID option[value=" +selected+"]").attr("selected","selected") ;
						setSelectValue('FRepairGroupItemID');
						
				}
			});
    }
    
    
    
    function newPage(){
    	var param = "FRepair_comp_id="+$('#FRepair_comp_id').val();
        	param += "&brn_id="+$('#brn_id').val();
            param += "&sec_id="+$('#sec_id').val();
            param += "&SRequestDate="+$('#SRequestDate').val();
            param += "&ERequestDate="+$('#ERequestDate').val();
            param += "&SDefineFinalDate="+$('#SDefineFinalDate').val();
            param += "&EDefineFinalDate="+$('#EDefineFinalDate').val();
            param += "&SFinalDate="+$('#SFinalDate').val();
            param += "&EFinalDate="+$('#EFinalDate').val();
            param += "&FStatus="+$('#FStatus').val();
            param += "&FRepairGroupID="+$('#FRepairGroupID').val();
            param += "&FRepairGroupItemID="+$('#FRepairGroupItemID').val();
            
            window.open('request-report-status-page.php?'+param, '_blank');
        
    }
    
    function newExcel(){
    	var param = "FRepair_comp_id="+$('#FRepair_comp_id').val();
        	param += "&brn_id="+$('#brn_id').val();
            param += "&sec_id="+$('#sec_id').val();
            param += "&SRequestDate="+$('#SRequestDate').val();
            param += "&ERequestDate="+$('#ERequestDate').val();
            param += "&SDefineFinalDate="+$('#SDefineFinalDate').val();
            param += "&EDefineFinalDate="+$('#EDefineFinalDate').val();
            param += "&SFinalDate="+$('#SFinalDate').val();
            param += "&EFinalDate="+$('#EFinalDate').val();
            param += "&FStatus="+$('#FStatus').val();
            param += "&FRepairGroupID="+$('#FRepairGroupID').val();
            param += "&FRepairGroupItemID="+$('#FRepairGroupItemID').val();
            param += "&status=excel";
            //alert(param);
            window.open('request-report-status-page.php?'+param);
            
            
    }
    </script>
</body>
</html>