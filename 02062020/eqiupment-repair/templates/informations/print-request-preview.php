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
function check_signature($FManagerID){
 		$sql = "SELECT
							general_db.tbl_manager.FManagerID,
							general_db.tbl_manager.FName,
							general_db.tbl_manager.emp_code_full,
							pis_db.tbl_employee.signature
							FROM
							general_db.tbl_manager
							LEFT JOIN pis_db.tbl_employee ON general_db.tbl_manager.emp_code_full = pis_db.tbl_employee.emp_code_full
							WHERE
							general_db.tbl_manager.FManagerID = '".$FManagerID."'
							ORDER BY
							general_db.tbl_manager.FManagerID ASC
						";
 		$results = mysql_query($sql);
		$record = mysql_fetch_array($results);
		//return $sql;
 		return $record[signature];
}
function name_manager($FManagerID){
 		$sql = "SELECT
							general_db.tbl_manager.FManagerID,
							general_db.tbl_manager.FName,
							general_db.tbl_manager.pass_manager
							FROM
							general_db.tbl_manager
							Where general_db.tbl_manager.FManagerID = '".$FManagerID."' ";
 		$results = mysql_query($sql);
		$record = mysql_fetch_array($results);
		//return $sql;
 		return $record[FName];
}

$query2 = "SELECT FItems FROM mtrequest_db.tbl_purchase WHERE FRequestID={$_id} AND purchase_type='lap'";
 		$rst2 = mysql_query($query2);
		$h=0;
		$FItems="";
 		while($row2=mysql_fetch_assoc($rst2)){
 		$h++;

				if($h>1){
						$FItems.=",";
				}
				$FItems.=$row2[FItems];

		}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-874">
<title>Print Preview</title>
<link href="../../../css/stylesheet_report_print.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.style1 {
	font-size: 20pt;
	font-weight: bold;
}
.style3 {
	font-size: 14pt;
	font-weight: bold;
}
.style4 {color: #FF0000}
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
                <td width="13%" rowspan="2" align="center"><? if($utilMD->convert2Thai($reqData['FStatus'])=="noapprove"){ ?><img src="../../../images/action-icon/not_approve1.png" width="60" height="60"> <? } ?></td>
                <td width="87%" align="center"><span class="style1">ใบแจ้งซ่อม / บำรุงรักษา</span></td>
              </tr>
              <tr>
                <td align="center"><span class="style1">หน่วยงานซ่อมบำรุง แผนกธุรการ ส่วนบริหารงานกลาง</span></td>
              </tr>
          </table></td>
          <td width="20%" align="center"><span class="style3">เลขที่</span>&nbsp;&nbsp; <?=$reqData['FReqNo']?>
            &nbsp;&nbsp;</td>
        </tr>
      </table></td>
    </tr>
    <tr>
      <td height="100%" valign="top" class="LBR"><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
        <? list($y,$m,$d) = split("-",$reqData['FReqDate']);?>
          <td width="67%" height="20" bgcolor="#CCCCCC" class="B"><b class="style3">1. ข้อมูลการแจ้งซ่อม</b></td>
          <td width="33%" align="right" bgcolor="#CCCCCC" class="B"><b>วันที่แจ้งเรื่อง</b> <?=$d?>  <?=$utilMD->getNameMonthFull($m)?>  <?=($y+543)?></td>
        </tr>
        <tr>
          <td colspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td colspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                	<? if($utilMD->convert2Thai($reqData['FStatus'])=="noapprove" || $utilMD->convert2Thai($reqData['FStatus'])=="cancel" || $utilMD->convert2Thai($reqData['FStatus'])=="returnedit"){ ?>
                    <tr>
                      <td><? if($utilMD->convert2Thai($reqData['FStatus'])=="noapprove"){?>
                      	<span style="color: #F00">*เหตุผลการไม่อนุมัติ*</span>
                      <? }else if($utilMD->convert2Thai($reqData['FStatus'])=="returnedit"){ ?>
                        <span style="color: #F00">*เหตุผลการตีกลับ*</span>
                      <? }else if($utilMD->convert2Thai($reqData['FStatus'])=="cancel"){ ?>
                       	<span style="color: #F00">*ไม่อนุมัติจากแผนกซ่อมบำรุง*</span>
                      <? } ?>    
                        </td>
                    
					  <td colspan="5" style="color: #F00"><? if($utilMD->convert2Thai($reqData['FStatus'])=="noapprove"){?>
                      	 <?=$utilMD->convert2Thai($reqData['detail_noapprove'])?>
                      <? }else if($utilMD->convert2Thai($reqData['FStatus'])=="returnedit"){ ?>
                         <?=$utilMD->convert2Thai($reqData['detail_noapprove'])?>
                      <? }else if($utilMD->convert2Thai($reqData['FStatus'])=="cancel"){ ?>
                       	 <?=$utilMD->convert2Thai($reqData['FCancelRemark'])?>
                      <? } ?>    
                        </td>
					</tr>
                    <? } ?>
                  
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
                      <?
					  if($reqData['approve_date']!="" && $reqData['FApprove']=='Y'){
							list($y_ma,$m_ma,$d_ma) = split("-",$reqData['approve_date']);
							$closejob_ma_date=$d_ma." ".$utilMD->getNameMonthFull($m_ma)." ".($y_ma+543);
						}else{
							$closejob_ma_date="";
						}
					  ?>
                      <td colspan="2" align="center"><? if($closejob_ma_date!=""){ ?><img src="<?=check_signature($reqData['FManagerID'])?>" width="130" height="40"> <? } ?></td>
                      </tr>
                    <tr>
                      <td><b>เลขที่ทรัพย์สิน</b></td>
                      <td><? if($reqData['FAsset_no']!=''){ echo $reqData['FAsset_no'] ; }else{ echo "-" ;}  ?></td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      <td colspan="2" align="center"><? if($closejob_ma_date!=""){ echo "วันที่"." ".$closejob_ma_date; } ?></td>
                      </tr>
                  </table></td>
              </tr>
              <tr>
                <td colspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td width="66%" class="B">มีความประสงค์ขอแจ้งซ่อม</td>
                      <td width="34%" align="center" class="B"><b>ผู้อนุมัติ</b></td>
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
                    <td width="13%" valign="middle"><?=$utilMD->convert2Thai($reqData['FBranchName'])?>-[<?=$utilMD->convert2Thai($reqData['comp_code'])?>]</td>
                    <td width="11%"><b>อาคาร / สถานที่</b></td>
                    <td width="20%">&nbsp;<?=$utilMD->convert2Thai($reqData['FLocation'])?></td>
                    <td width="3%"><b>ชั้น</b></td>
                    <td width="16%">&nbsp;<?=$utilMD->convert2Thai($reqData['FFloor'])?></td>
                    <td width="3%"><b>ห้อง</b></td>
                    <td width="19%">&nbsp;<?=$utilMD->convert2Thai($reqData['FRoom'])?></td>
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
      <td height="100%" valign="top" class="LBR_BG">
	     <table width="100%" border="0" cellspacing="0" cellpadding="0">
	     <? list($y,$m,$d) = split("-",$reqData['FReciveDate']);?>
	      <tr> <td width="50%" height="10" class="LBR_BG"><span class="B"><b>วันที่รับเรื่อง </b><?=$d?>  <?=$utilMD->getNameMonthFull($m)?>  <?=($y+543)?></span></td>
		       <td width="50%" class="LBR_BG"><span class="B"><b>&nbsp;&nbsp;ระยะเวลาการซ่อมเสร็จภายใน วันที่  </b>
		       			 <?php 
		       			 			if(!empty($reqData['FDueDate'])){
		       			 				list($y,$m,$d) = split("-",$reqData['FDueDate']);
		       			 				print $d." ".$utilMD->getNameMonthFull($m)." ".($y+543);
		       			 			}else{
		       			 				print "-";
		       			 			}
		       			 ?>
		       	</span></td>
		   </tr>
	      <tr>
	        <td height="10" class="LBR_BG"><span class="style3">2. ผลการดำเนินงานของผู้รับผิดชอบ (หน่วยงานซ่อมบำรุง)</span></td>
	        <td width="50%"><b>&nbsp;&nbsp;<span class="style4"> กำหนดเริ่ม  &nbsp;
                           <?php 
		       			 			if(!empty($reqData['FReciveDate'])){
		       			 				list($y,$m,$d) = split("-",$reqData['FReciveDate']);
		       			 				print $d." ".$utilMD->getNameMonthFull($m)." ".($y+543);
		       			 			}else{
		       			 				print "-";
		       			 			}
		       			 ?>
&nbsp;&nbsp;    สิ้นสุด     
								&nbsp;
                                <?php 
		       			 			if(!empty($reqData['FDueDate'])){
		       			 				list($y,$m,$d) = split("-",$reqData['FDueDate']);
		       			 				print $d." ".$utilMD->getNameMonthFull($m)." ".($y+543);
		       			 			}else{
		       			 				print "-";
		       			 			}
		       			 ?>
&nbsp;&nbsp;    วันเริ่มแก้ไข   
			                  	&nbsp;
                                <?php 
		       			 			if(!empty($reqData['FEditDate'])){
		       			 				list($y,$m,$d) = split("-",$reqData['FEditDate']);
		       			 				print $d." ".$utilMD->getNameMonthFull($m)." ".($y+543);
		       			 			}else{
		       			 				print "-";
		       			 			}
		       			  ?>
            </span></b></td>
           </tr>
        </table>
	  </td>
    </tr>
    <tr>
      <td height="20" class="LBR"><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td width="8%" align="center">&nbsp;</td>
              <td colspan="7"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td colspan="6" align="center"><b>ผลกระทบ</b></td>
                </tr>
                <tr>
                  <td>[<? if($reqData['FLevel']=="1"){print "<img src=\"../../../images/OK.gif\" width=\"12\" height=\"12\">";}else{print "&nbsp;&nbsp;";}?>]</td>
                  <td>กับลูกค้าโดยตรง</td>
                  <td>[<? if($reqData['FLevel']=="2"){print "<img src=\"../../../images/OK.gif\" width=\"12\" height=\"12\">";}else{print "&nbsp;&nbsp;";}?>]</td>
                  <td width="25%">กับแผนกต่าง ๆ</td>
                  <td width="6%">[<? if($reqData['FLevel']=="3"){print "<img src=\"../../../images/OK.gif\" width=\"12\" height=\"12\">";}else{print "&nbsp;&nbsp;";}?>]</td>
                  <td width="23%">ภายในแผนก</td>
                </tr>
                <tr>
                  <td width="8%">[<? if($reqData['FJobresult']=="1"){print "<img src=\"../../../images/OK.gif\" width=\"12\" height=\"12\">";}else{print "&nbsp;&nbsp;";}?>]</td>
                  <td width="31%">ซ่อมเอง </td>
                  <td width="7%">[<? if($reqData['FJobresult']=="2"){print "<img src=\"../../../images/OK.gif\" width=\"12\" height=\"12\">";}else{print "&nbsp;&nbsp;";}?>]</td>
                  <td colspan="3">ให้ผู้รับเหมาดำเนินการ</td>
                </tr>
              </table></td>
              </tr>
             <tr>
              <td align="center">&nbsp;</td>
              <td colspan="2">&nbsp;</td>
              <td align="center">จำนวน</td>
              <td align="center">หน่วย</td>
              <td align="center">&nbsp;</td>
              <td align="right">&nbsp;</td>
              <td align="center">&nbsp;</td>
            </tr>
            <tr>
              <td colspan="2"><b>&nbsp;&nbsp;&nbsp;&nbsp;ค่าแรง</b></td>
              <td colspan="3" class="underlinedott"><?=$FItems?></td>
              <td align="center">ราคา</td>
              <td align="right" class="underlinedott"><? if($reqData['FLapAmt']>0)print number_format($reqData['FLapAmt'],2,".",",");?>&nbsp;</td>
              <td align="center">บาท</td>
            </tr>
            <tr>
              <td colspan="2" ><b>&nbsp;&nbsp;&nbsp;&nbsp;ค่าอะไหล่</b></td>
              <td>&nbsp;</td>
              <td align="center">&nbsp;</td>
              <td align="center">&nbsp;</td>
              <td align="center">&nbsp;</td>
              <td align="right">&nbsp;</td>
              <td align="center">&nbsp;</td>
            </tr>
            <?php 
            	$FPart_amt_total = 0;
            	$index = 0;
            	$index2 = 0;
            	foreach($costData as $key=>$val){
				    $FPart_amt = $val['FPrice'];
				    $FPart_amt_total += $FPart_amt;
				    $index++;
					if($index<=7){
				    $index2++;
            ?>
            <tr>
              <td>&nbsp;</td>
              <td width="3%" align="center"><?=$index?>.</td>
              <td width="56%" class="underlinedott">&nbsp;<?=$utilMD->convert2Thai($val['FItems'])?></td>
              <td width="8%" class="underlinedott" align="center">&nbsp;<?=$utilMD->convert2Thai($val['FAmount'])?></td>
              <td width="8%" class="underlinedott" align="center">&nbsp;<?=$utilMD->convert2Thai($val['FUnit'])?></td>
              <td width="8%" align="center">ราคา</td>
              <td width="16%" class="underlinedott" align="right"><? if($FPart_amt>0)print number_format($FPart_amt,2,".",",");?>&nbsp;</td>
              <td width="9%" align="center">บาท</td>
            </tr>
             <?php }} ?>
            <tr>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td align="right">รวมเป็นจำนวนเงินทั้งหมด <?=$index?> รายการ</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td class="underlinedott" align="right"><? if($FPart_amt_total>0)print number_format($FPart_amt_total,2,".",",");?>&nbsp;</td>
              <td align="center">บาท</td>
            </tr>
            <tr>
              <td align="center">[<? if($reqData['FReam']=="Y"){print "<img src=\"../../../images/OK.gif\" width=\"12\" height=\"12\">";}else{print "&nbsp;&nbsp;&nbsp;&nbsp;";}?>]</td>
              <td>&nbsp;</td>
              <td colspan="6">เบิกเงินสดเพื่อจัดซื้อวัสดุมาดำเนินการซ่อมแซ่ม</td>
           </tr>
           <tr>
              <td align="center"></td>
              <td>&nbsp;</td>
              <td colspan="6">เก็บค่าใช้จ่ายผู้รับเหมา : <? if($reqData['FCharge_contractor']=='1'){
				  																	echo "บจก.เค.แอล.ออโต้เพนท์";
			  																	}else if($reqData['FCharge_contractor']=='2'){
				  																	echo "บจก.เอส.เค.ออโต้เพนท์";
			  																	}else if($reqData['FCharge_contractor']=='3'){
				  																	echo "บจก.พี.บี.ออโต้เพนท์";
			  																	}else if($reqData['FCharge_contractor']=='4'){
				  																	echo "บจก.สิริโรจน์ บอดี้เพนท์";
			  																	}else if($reqData['FCharge_contractor']=='5'){
				  																	echo "บจก.สบาย คาร์วอร์ช";
			  																	}else if($reqData['FCharge_contractor']=='6'){
				  																	echo "บจก.อาร์ แอนด์ ดี 999";
			  																	}else{
				  																	echo "-";
			  																	}
				  															?></td>
            </tr>
            <tr>
              <td height="15" colspan="8"></td>
              </tr>
            <tr>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td colspan="4" align="center">ลงชื่อ..............................................................<br>ช่างเทคนิคซ่อมบำรุง</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
          </table></td>
          <td width="50%" class="L"><table width="100%" border="0" cellspacing="0" cellpadding="0">
            
            <tr>
              <td colspan="3" align="center"><b>ความคิดเห็น</b></td>
              </tr>
            <tr>
              <td width="2%" align="center">&nbsp;</td>
              <td height="200" class="" valign="top">&nbsp;<?=$utilMD->convert2Thai($reqData['FOth_detail'])?></td>
              <td width="3%">&nbsp;</td>
            </tr>
            <tr>
              <td height="15" colspan="3"></td>
            </tr>
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
            <tr>
              <td>&nbsp;</td>
              <td align="center">&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
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
            <tr>
              <td>&nbsp;</td>
              <td align="center">&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <?
			if($reqData['closejob_emp_date']!=""){
				list($y_emp,$m_emp,$d_emp) = split("-",$reqData['closejob_emp_date']);
				$closejob_emp_date=$d_emp." ".$utilMD->getNameMonthFull($m_emp)." ".($y_emp+543);
            }else{
				$closejob_emp_date="";
			}
			?>
            <tr>
              <td>&nbsp;</td>
              <td align="center">วันที่ปิดงาน &nbsp;&nbsp;<?=$closejob_emp_date?></td>
              <td>&nbsp;</td>
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
      <td height="20" ><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
          <td width="75%"><font size="5">เอกสารฉบับที่ / วันที่ออกเอกสาร / วันที่มีผลบังคับใช้ : 6 / 24/06/2553 / 01/07/2553</font></td>
          <td width="25%" align="right">EF-OFF-63</td>
        </tr>
        <tr>
          <td colspan="2"><font size="5"><b>หมายเหตุ : </b>กรณีแจ้งซ่อมผ่านทางระบบอิเล็คทรอนิกไม่สามารถใช้งานได้ให้แจ้งซ่อมผ่านทางระบบเอกสารตามแบบฟอร์มเลขที่ EF-OFF-63</font></td>
        </tr>
      </table></td>
    </tr>
  </table>
</form> 
</body>
</html>
