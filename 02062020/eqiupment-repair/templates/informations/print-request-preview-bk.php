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

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-874">
<title>Print Preview</title>
<link href="../../../css/stylesheet_report.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.style1 {
	font-size: 20pt;
	font-weight: bold;
}
.style3 {
	font-size: 18pt;
	font-weight: bold;
}
-->
</style>
</head>
<body scroll="yes"> 
<form name="form1" method="post" action="">&nbsp;
  <table width="98%" height="98%" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td height="20" valign="top" class="TBRL"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="8%" align="center"><img src="../../../images/logoDoc.gif" width="80" height="51"></td>
          <td width="72%"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td align="center"><span class="style1">ใบแจ้งซ่อม / บำรุงรักษา</span></td>
              </tr>
              <tr>
                <td align="center"><span class="style1">หน่วยงานซ่อมบำรุง แผนกธุรการ ส่วนบริหารงานกลาง</span></td>
              </tr>
          </table></td>
          <td width="20%" align="center"><span class="style3">เลขที่</span>&nbsp;&nbsp;
              <?=$reqData['FReqNo']?>
            &nbsp;&nbsp;</td>
        </tr>
      </table></td>
    </tr>
    <tr>
      <td height="100%" valign="top" class="LBR"><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <? list($y,$m,$d) = split("-",$reqData['FReqDate']);?>
          <td width="75%" height="20" bgcolor="#CCCCCC" class="B"><b class="style3">1. ข้อมูลการแจ้งซ่อม</b></td>
          <td width="25%" align="right" bgcolor="#CCCCCC" class="B"><b>วันที่แจ้งเรื่อง</b>  <?=$d?>  <?=$utilMD->getNameMonthFull($m)?>  <?=($y+543)?>          </td>
        </tr>
        <tr>
          <td colspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td colspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td width="13%"><b>ชื่อผู้แจ้ง</b></td>
                      <td width="26%"><?=$utilMD->convert2Thai($reqData['emp_name'])?></td>
                      <td width="7%"><b>สาขา</b></td>
                      <td width="20%"><?=$utilMD->convert2Thai($reqData['brn_name'])?></td>
                      <td width="8%"><b>เบอร์โทร</b></td>
                      <td width="26%"><?=$utilMD->convert2Thai($reqData['FTel'])?></td>
                    </tr>
                    <tr>
                      <td><b>หน่วยงาน</b></td>
                      <td><?=$utilMD->convert2Thai($reqData['FFnc'])?></td>
                      <td><b>แผนก</b></td>
                      <td><?=$utilMD->convert2Thai($reqData['sec_nameThai'])?></td>
                      <td colspan="2" align="center">..............................................................................</td>
                      </tr>
                    <tr>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      <td colspan="2" align="center"><b>ผู้อนุมัติ</b></td>
                      </tr>
                  </table></td>
              </tr>
              <tr>
                <td colspan="2"><table width="146" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td width="146" class="B">มีความประสงค์ขอแจ้งซ่อม</td>
                    </tr>
                </table></td>
              </tr>
              <tr>
                <td width="15%" valign="top"><b>แจ้งซ่อมงานระบบ</b></td>
                <td width="85%"><?=$utilMD->convert2Thai($reqData['FRepairGroupItemName'])?></td>
              </tr>
              
              <tr>
                <td colspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="10%"><b>สถานที่ติดตั้ง</b></td>
                    <td width="5%"><b>สาขา</b></td>
                    <td width="13%" valign="bottom"><?=$utilMD->convert2Thai($reqData['brn_name'])?></td>
                    <td width="11%"><b>อาคาร / สถานที่</b></td>
                    <td width="20%">&nbsp;<?=$utilMD->convert2Thai($reqData['FLocation'])?></td>
                    <td width="3%"><b>ชั้น</b></td>
                    <td width="6%">&nbsp;<?=$utilMD->convert2Thai($reqData['FFloor'])?></td>
                    <td width="4%"><b>ห้อง</b></td>
                    <td width="28%">&nbsp;<?=$utilMD->convert2Thai($reqData['FRoom'])?></td>
                    </tr>
                </table></td>
              </tr>
              <tr>
                <td colspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td valign="top"><b>รายละเอียด / ปัญหา</b>&nbsp;&nbsp;&nbsp;&nbsp;<?=$utilMD->convert2Thai($reqData['FDetail'])?></td>
                    </tr>
                </table></td>
                </tr>
          </table></td>
        </tr>
      </table></td>
    </tr>
    <tr>
      <td height="20" class="LBR_BG"><span class="style3">2. ผลการดำเนินงานของผู้รับผิดชอบ (หน่วยงานซ่อมบำรุง)</span></td>
    </tr>
    <tr>
      <td height="20" class="LBR"><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td width="8%" align="center">&nbsp;</td>
              <td colspan="5"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td colspan="5" align="center"><b>ผลกระทบ</b></td>
                </tr>
                <tr>
                		<td>[<? if($reqData['FLevel']=="1"){print "<img src=\"../../../images/OK.gif\" width=\"12\" height=\"12\">";}else{print "&nbsp;&nbsp;";}?>] กับลูกค้าโดยตรง</td>
                		<td>[<? if($reqData['FLevel']=="2"){print "<img src=\"../../../images/OK.gif\" width=\"12\" height=\"12\">";}else{print "&nbsp;&nbsp;";}?>] กับแผนกต่าง ๆ</td>
                		<td>[<? if($reqData['FLevel']=="3"){print "<img src=\"../../../images/OK.gif\" width=\"12\" height=\"12\">";}else{print "&nbsp;&nbsp;";}?>] ภายในแผนก</td>
                		<td>[<? if($reqData['FJobresult']=="S"){print "<img src=\"../../../images/OK.gif\" width=\"12\" height=\"12\">";}else{print "&nbsp;&nbsp;";}?>] ซ่อมเอง</td>
                		<td>[<? if($reqData['FJobresult']=="O"){print "<img src=\"../../../images/OK.gif\" width=\"12\" height=\"12\">";}else{print "&nbsp;&nbsp;";}?>] ให้ผู้รับเหมาดำเนินการ</td>
                </tr>
              </table></td>
              </tr>
            <tr>
              <td align="center">&nbsp;</td>
              <td colspan="2"><b>ค่าแรง</b></td>
              <td align="center">ราคา</td>
              <td align="right" class="underlinedott" style="width:150px"><? if($reqData['FLapAmt']>0)print number_format($reqData['FLapAmt'],2,".",",");?>&nbsp;</td>
              <td align="center">บาท</td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td  colspan="2"><b>ค่าอะไหล่</b></td>
              <td align="center">ราคา</td>
              <td class="underlinedott" align="right" style="width:150px"><? if($reqData['FPartAmt']>0)print number_format($reqData['FPartAmt'],2,".",",");?>&nbsp;</td>
              <td align="center">บาท</td>
            </tr>
            
            <tr>
              <td align="center">[<? if($FReam=="Y"){print "<img src=\"image/OK.gif\" width=\"12\" height=\"12\">";}else{print "&nbsp;&nbsp;&nbsp;&nbsp;";}?>]</td>
              <td colspan="4">เบิกเงินสดเพื่อจัดซื้อวัสดุมาดำเนินการซ่อมแซ่ม</td>
              </tr>
           <tr>
              <td colspan="5" align="left"><b>ความคิดเห็น</b></td>
            </tr>
            <tr>
            	<td style="height:150px;"></td>
            	<td  colspan="4" valign="top">
            			<?=$utilMD->convert2Thai($reqData['FOth_detail'])?>
            	</td>
            </tr>
            <tr>
              <td height="15" colspan="5"></td>
             </tr>
             <tr>
             		<td></td>
             		<td height="15" colspan="4">
             				<table width="100%" border="0">
             							<tr>
             									<td width="33.33%">
             											<table width="100%" border="0">
             													<tr>
													              <td>&nbsp;</td>
													              <td>&nbsp;</td>
													              <td align="center">ลงชื่อ..............................................................<br>ช่างเทคนิคซ่อมบำรุง</td>
													              <td>&nbsp;</td>
													              <td>&nbsp;</td>
													            </tr>
             											</table>
             									</td>
             									<td width="33.33%">
             											<table width="100%" border="0">
             													 <tr>
														              <td>&nbsp;</td>
														              <td align="center">ลงชื่อ.....................................................................</td>
														              <td>&nbsp;</td>
														            </tr>
														            <tr>
														              <td>&nbsp;</td>
														              <td align="center">หัวหน้าหน่วยงาน</td>
														              <td>&nbsp;</td>
														            </tr>
             											</table>
             											
             									</td>
             									<td width="33.33%">
             										<table width="100%" border="0">
             												 <tr>
													              <td>&nbsp;</td>
													              <td align="center">ลงชื่อ.....................................................................</td>
													              <td>&nbsp;</td>
													            </tr>
													            <tr>
													              <td>&nbsp;</td>
													              <td align="center">หัวหน้างานอาวุโส</td>
													              <td>&nbsp;</td>
													            </tr>
             												
             										</table>
             								   </td>
             							</tr>
             				</table>
             		</td>
             </tr>
          </table></td>
        </tr>
      </table></td>
    </tr>
    <tr>
      <td height="20" class="LBR_BG"><span class="style3">3. การตรวจสอบผลการดำเนินงาน</span></td>
    </tr>
    <tr>
      <td height="20" class="LBR"><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="98%" align="right"> รายการข้างต้นได้รับการซ่อมแซมเป็นที่เรียบร้อยแล้ว และสามารถใช้งานได้ตามปกติเมื่อวันที่..............เดือน...........พ.ศ...........</td>
          <td width="2%" align="right">&nbsp;</td>
        </tr>
        <tr>
          <td align="right">ลงชื่อ..................................................................................ผู้แจ้งซ่อม</td>
          <td align="right">&nbsp;</td>
        </tr>
      </table></td>
    </tr>
    <tr>
      <td height="20" class="LBR"><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="2">4. กรณีที่ไม่สามารถซ่อมได้ให้ผู้แจ้งขออนุมัติทำการสั่งซื้อใหม่ตามสายการบังคับบัญชาจากต้นสังกัด</td>
        </tr>
        <tr>
          <td width="98%" align="right">ลงชื่อ..................................................................................ผู้อนุมัติ</td>
          <td width="2%" align="right">&nbsp;</td>
        </tr>
      </table></td>
    </tr>
            <tr>
          <td colspan="2" align="center"><table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td width="75%"><font size="5">เอกสารฉบับที่ / วันที่ออกเอกสาร / วันที่มีผลบังคับใช้ : 6 / 24/06/2553 / 01/07/2553</font></td>
              <td width="25%" align="right">EF-OFF-63</td>
            </tr>
            <tr>
              <td colspan="2"><font size="5"><b>หมายเหตุ : </b>กรณีแจ้งซ่อมผ่านทางระบบอิเล็คทรอนิกไม่สามารถใช้งานได้ให้แจ้งซ่อมผ่านทางระบบเอกสารตามแบบฟอร์มเลขที่ EF-OFF-63</font></td>
              </tr>
          </table></td>
          </tr>
      </table></td>
    </tr>
  </table>
</form> 
</body>
<script language="javascript">
		function onSave(){
				if(window.document.all.FReqName.value == ""){
						alert('กรุณาระบุผู้แจ้ง');
						window.document.all.FReqName.focus();
				}else if(window.document.all.FPosition.value == ""){
						alert('กรุณาระบุตำแหน่ง');
						window.document.all.FPosition.focus();
				}else if(window.document.all.FSectionID.value == "0"){
						alert('กรุณาระบุแผนก');
						window.document.all.FSectionID.focus();
				}else if(window.document.all.FBranchID.value == "0"){
						alert('กรุณาระบุสาขา');
						window.document.all.FBranchID.focus();
				}else if(window.document.all.FReqDate.value == ""){
						alert('กรุณาระบุวันที่');
				}else if(window.document.all.FDetail.value == ""){
						alert('กรุณาระบุรายละเอียด / ปัญหา');
						window.document.all.FDetail.focus();
				}else if(window.document.all.FCondition.value == ""){
						alert('กรุณาระบุเงื่อนไข / ข้อมูลที่ต้องการ');
						window.document.all.FCondition.focus();
				}else{
						window.document.all.cmd.value = "save";
						window.document.form1.submit();
				}
		}
		
		function onCancel(){
				window.location.href = 'selectPage.php';
		}
		
		function openDialog(url,value){
					var Arg="dialogWidth:"+550+"px;dialogHeight:"+350+"px;center:yes;status:no;help:no";
					var win = window.showModalDialog('popup/selectDialog.php?url='+url+'&value='+value+'&ref=' + Math.round(Math.random()*1000000000), self, Arg);		
		}
		
</script>
</html>
