<?php 
include '../../../lib/db_config.php';
include '../../../main/modules/Model_Utilities.php';
include '../../modules/documents-app-model.php';
function DateThai2($strDate){
		if($strDate!='' && $strDate!='0000-00-00' && $strDate!='00/00/0000'){
		$strYear = date("Y",strtotime($strDate))+543;
		$strMonth= date("n",strtotime($strDate));
		$strDay= date("j",strtotime($strDate));
		//$strHour= date("H",strtotime($strDate));
		//$strMinute= date("i",strtotime($strDate));
		//$strSeconds= date("s",strtotime($strDate));
		$strMonthCut = Array("","ม.ค.","ก.พ.","มี.ค.","เม.ย.","พ.ค.","มิ.ย.","ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย.","ธ.ค.");
		$strMonthThai=$strMonthCut[$strMonth];
		return "$strDay $strMonthThai $strYear";
		//return "$strDay $strMonthThai $strYear, $strHour:$strMinute";
		}
	}

$utilMD = new Model_Utilities();
$reqMD = new Model_Documents_app();
$_id = $_REQUEST['id'];
$reqData = $reqMD->get_data($_id);

?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Print Preview</title>
<link href="../../../css/stylesheet_report_print.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--

.style1 {
	font-size: 20pt;
	font-weight: bold;
}
.style3 {
	font-size: 10pt;
}
.style4 {color: #FF0000}
	.border_all{ border:1px solid #000;}
.border_all_Tnone{ border:1px solid #000; border-top:none;}
.border_all_Bnone{ border:1px solid #000; border-bottom:none;}
.border_TR{ border-top:1px solid #000;border-right:1px solid #000;}
.border_TL{ border-top:1px solid #000;border-left:1px solid #000;}
.border_BR{ border-bottom:1px solid #000;border-right:1px solid #000;}
.border_BL{ border-bottom:1px solid #000; border-left:1px solid #000;}
.border_t{ border-top:1px solid #000;}
.border_b{ border-bottom:1px solid #000;}
.border_r{ border-right:1px solid #000;}
.border_l{ border-left:1px solid #000;}
.border_b_double{border-bottom: double;}
.box_request {border:none;border-bottom: 1px dotted ;}
	
p {
  margin-top: -2px;
  margin-bottom: -2px;
  margin-left: 0;
  margin-right: 0;
	
}	
-->
</style>
</head>

<body scroll="yes" style=" font-size: 5;"> 
 <form name="form1" id="form1"   action="../../../controllers/documents-app-controller.php?"  method="post" enctype="multipart/form-data"  target="upload_target">
      <table width="98%" align="center" border="0" cellpadding="0" cellspacing="0" class="border_all"> 
		  
		  <?php /* /////////////////////////////////////////////////// */?>
		<tr>
		  <td width="21%"  class="border_b" align="center">&nbsp;</td>
		  <td width="46%"  class="border_b" align="center"><b>เอกสารขอความเห็นชอบและอนุมัติ</b></td>
		  <td width="33%"  class="border_b" ><?=$reqData[comp_name]?></td>
		</tr>
		   <?php /* /////////////////////////////////////////////////// */?>
		 
		  <?PHP if($reqData['return_edit_empid']!=''){?>
		  <tr>
			  <td colspan="3" align="center"><font color="#F8070B">
				  <?='('.DateThai2($reqData['return_edit_date']).')&nbsp;คุณ'.$reqData['return_edit_emp_name'].'ตีกลับแผนกซ่อมบำรุงด้วยเหตุผล <b>'.$reqData['return_edit_comment'].'</b>'?></font></td>
		  </tr>
		  <?PHP }?>
		  <?php /* /////////////////////////////////////////////////// */?>
		  <?php /* /////////////////////////////////////////////////// */?>
		  <tr>
			  <td colspan="3">
				  
			  			<table width="100%" border="0">
						  <tbody>
							<tr>
							  <td width="5%">โครงการ </td>
							  <td width="52%"  class="box_request"><?=$reqData[Fdoc_app_project]?></td>
							  <td width="16%" align="right">อ้างอิง MT Request</td>
							  <td width="11%" class="box_request" align="center"><?=$reqData[FInf_mt_no]?>&nbsp;</td>
							  <td width="6%" align="right">วันที่</td>
							  <td width="10%" align="center"  class="box_request"><?=DateThai2($reqData[Fdoc_app_date])?></td>
							</tr>
						  </tbody>
						</table>

				  
			  </td>
		  </tr>
		  <?php /* /////////////////////////////////////////////////// */?>
		  
		  
		  
		  <tr>
			  <td colspan="3">
			  
				  <table width="100%" border="0">
						  <tbody>
							<tr  height="25">
							  <td width="5%">สาขา</td>
							  <td width="34%"  class="box_request"><?=$reqData[brn_name]?></td>
								 <td width="2%">
									 <?PHP if($reqData[FworkSt]=='Y'){?>
									 <img src="../../../images/checked.jpg" width="15" height="15" >
									 <?PHP }else{?>
									 
									 <img src="../../../images/unchecked.jpg" width="15" height="15" >
									 <?PHP }?>
								</td>
								 <td width="11%">ราคาค่างาน</td>
							  <td width="12%" align="center" class="box_request"><?=number_format($reqData[Fwork_price],2)?>&nbsp;</td>
							 <td width="10%">บาท</td>
							  <td width="16%" align="right">เลขที่</td>
							  <td width="10%" align="center"  class="box_request"><?=$reqData[Fdoc_app_no]?></td>
							</tr>
						  </tbody>
						</table>
				  
				  
			  </td>
		  </tr>
		  <?php /* /////////////////////////////////////////////////// */?>
		  <tr>
			  <td colspan="3" class="border_b_double">
				  <table width="100%" border="0">
						  <tbody>
							<tr>
							  <td width="5%">เรื่อง </td>
							  <td width="34%"  class="box_request"><?=$reqData[Fdoc_app_name]?></td>
								 <td width="2%">
									  <?PHP if($reqData[Fmaterial_constructionSt]=='1'){?>
									 <img src="../../../images/checked.jpg" width="15" height="15" >
									 <?PHP }else{?>
									 
									 <img src="../../../images/unchecked.jpg" width="15" height="15" >
									 <?PHP }?>
								</td>
							   <td width="11%">แบบก่อสร้าง</td>
							  <td width="2%"> 
								 <?PHP if($reqData[Fmaterial_constructionSt]=='2'){?>
									 <img src="../../../images/checked.jpg" width="15" height="15" >
									 <?PHP }else{?>
									 
									 <img src="../../../images/unchecked.jpg" width="15" height="15" >
									 <?PHP }?>
								</td>
							 <td width="10%">วัสดุ</td>
							  <td width="8%" align="right">ผู้รับเหมา</td>
								
							  <td width="28%"  class="box_request"><?=$reqData[Fcontractor]?></td>
							</tr>
						  </tbody>
						</table>
			  
			  </td>
		  </tr>
		  <?php /* /////////////////////////////////////////////////// */?>
		  <tr>
			  <td colspan="3" class="border_b_double">
			  
			  <table width="100%" border="0">
						  <tbody>
							<tr>
							  <td width="7%">เรียน </td>
							  <td width="32%"  class="box_request"><?=$reqData[sup_fname]?></td>
								 <td width="2%">&nbsp;</td>
							   <td width="3%">จาก</td>
							   <td width="24%"  class="box_request">คุณ<?=$reqData[owner_first_name]?> <?=$reqData[owner_last_name]?></td>
							  <td width="7%" align="right">ประเภทงาน</td>
								
							  <td width="25%" align="center"  class="box_request"><?=$reqData[FJobLevel_name]?></td>
							</tr>
						  </tbody>
						</table>
				  
				  
			  </td>
		  
		  </tr>
		  <?php /* /////////////////////////////////////////////////// */?>
		  <tr>
			  <td colspan="3">
			  
				  <table width="100%" border="0">
						  <tbody>
							<tr>
							  <td width="12%">สิ่งที่แนบมาด้วย</td>
							  <td width="88%"  class="box_request"><?=$reqData[Fattach_infor]?></td>
							</tr>
						  </tbody>
						</table>
				  
			  </td>
		  </tr>
		  <?php /* /////////////////////////////////////////////////// */?>
		  <tr>
			  <td colspan="3">
			  
				  <table width="100%" border="0">
						  <tbody>
							<tr>
							  <td width="16%">ปีที่ซื้อเครื่องจักร </td>
							  <td width="9%" align="center"  class="box_request">ปี&nbsp;<?=($reqData[Fmachine_year]+543)?></td>
								 <td width="11%">ราคาเครื่องจักร</td>
								 <td width="13%" align="center"  class="box_request"><?=number_format($reqData[Fmachine_price],2)?></td>
								<td width="7%">&nbsp;บาท
							 </td>
								 <td width="20%">รวมค่าใช้จ่ายซ่อมที่ผ่านมา</td>
								<td width="10%" align="center"  class="box_request"><?=number_format($reqData[Fmachine_hisRepair_amt],2)?>&nbsp;</td>
								<td width="14%">บาท </td>
							</tr>
						  </tbody>
						</table>
				  
			  </td>
		  </tr>
		  <?php /* /////////////////////////////////////////////////// */?>
		  <tr>
			  <td colspan="3"  class="border_b_double">
			  
				  <table width="100%" border="0">
						  <tbody>
							<tr>
							  <td width="13%">ลักษณะการชำรุด </td>
								 <td width="2%">
									 <?PHP if($reqData[FdamagedSt]=='1'){?>
									 <img src="../../../images/checked.jpg" width="15" height="15" >
									 <?PHP }else{?>
									 
									 <img src="../../../images/unchecked.jpg" width="15" height="15" >
									 <?PHP }?> 
								</td>
							   <td width="14%">ชำรุดตามสภาพ</td>
							  <td width="2%"> 
								   <?PHP if($reqData[FdamagedSt]=='2'){?>
									 <img src="../../../images/checked.jpg" width="15" height="15" >
									 <?PHP }else{?>
									 
									 <img src="../../../images/unchecked.jpg" width="15" height="15" >
									 <?PHP }?> 
								</td>
							 <td width="18%">ชำรุดจากใช้งาน</td>
							  <td width="51%"> </td>
							</tr>
						  </tbody>
						</table>
				  
			  </td>
		  </tr>
		  <?php /* /////////////////////////////////////////////////// */?>
		  <tr>
			  <td colspan="3" class="border_b_double">
			  
				  <table width="100%" border="0">
						  <tbody>
							<tr>
							  <td width="12%">&nbsp;</td>
								 <td width="2%">
									 <?PHP if($reqData[FAcknowledgeSt]=='Y'){?>
									 <img src="../../../images/checked.jpg" width="15" height="15" >
									 <?PHP }else{?>
									 
									 <img src="../../../images/unchecked.jpg" width="15" height="15" >
									 <?PHP }?> 
								</td>
							   <td width="15%">เพื่อรับทราบ</td>
							  <td width="2%"> 
								   <?PHP if($reqData[FAsk_for_approvalSt]=='Y'){?>
									 <img src="../../../images/checked.jpg" width="15" height="15" >
									 <?PHP }else{?>
									 
									 <img src="../../../images/unchecked.jpg" width="15" height="15" >
									 <?PHP }?> 
								</td>
							 <td width="15%">ขอความเห็นชอบ</td>
								<td width="2%"> 
									<?PHP if($reqData[FTo_approveSt]=='Y'){?>
									 <img src="../../../images/checked.jpg" width="15" height="15" >
									 <?PHP }else{?>
									 
									 <img src="../../../images/unchecked.jpg" width="15" height="15" >
									 <?PHP }?> 
								</td>
							 <td width="15%">เพื่ออนุมัติ</td>
								<td width="2%"> 
									<?PHP if($reqData[FexpressSt]=='Y'){?>
									 <img src="../../../images/checked.jpg" width="15" height="15" >
									 <?PHP }else{?>
									 
									 <img src="../../../images/unchecked.jpg" width="15" height="15" >
									 <?PHP }?>
								</td>
							 <td width="12%">ด่วน</td>
								<td width="2%"> 
									<?PHP if($reqData[FPlease_considerSt]=='Y'){?>
									 <img src="../../../images/checked.jpg" width="15" height="15" >
									 <?PHP }else{?>
									 
									 <img src="../../../images/unchecked.jpg" width="15" height="15" >
									 <?PHP }?>
								</td>
							 <td width="12%">โปรดพิจารณา</td>
							  <td width="9%"> </td>
							</tr>
						  </tbody>
						</table>
			  
			  </td>
		  </tr>
		  <?php /* /////////////////////////////////////////////////// */?>
		  <tr>
			  <td colspan="3">
			  <?=$reqData[Fdoc_app_detail]?>
			  </td>
		  
		  </tr>
		  <?php /* /////////////////////////////////////////////////// */?>
		  <tr>
			  <td colspan="3" class="border_b">
				  <table width="100%" border="0">
						  <tbody>
							<tr>
						 <?php /* //////////////////////sub///////////////////////////// */?>		
						 <td width="70%">
								  
						<table width="100%" border="0">
						  <tbody>
							<tr>
							  <td width="1%">&nbsp;</td>
							  <td width="94%">
						
						 <?php /* //////////////////////sub///////////////////////////// */?>
	 					<?php 
							if($reqData[FmanagerBP_GSID]!=''){
								if($reqData[FmanagerBP_GS_comment]==''){
										$class_boxgs1="box_request";
										$class_boxgs2="";
										$class_boxnone1="";
								 }else{
										$class_boxgs1="";
										$class_boxgs2="box_request";
										$class_boxnone1="display: none;";
								 }
									
						 ?>		
						 <table width="100%" border="0" class="border_all">
						  <tbody>
							  <tr>
							  <td width="16%">
								  <?PHP if($reqData[FmanagerBP_GSApp]=='Y'){?>
									 <img src="../../../images/checked.jpg" width="15" height="15" >
									 <?PHP }else{?>
									 
									 <img src="../../../images/unchecked.jpg" width="15" height="15" >
									 <?PHP }?> อนุมัติ
								</td>
							  <td width="84%" class="<?=$class_boxgs1?>">&nbsp;<?=$reqData[FmanagerBP_GS_comment]?></td>
							  </tr>
							   <tr>
							    <td>
									<?PHP if($reqData[FmanagerBP_GSApp]=='N'){?>
									 <img src="../../../images/checked.jpg" width="15" height="15" >
									 <?PHP }else{?>
									 
									 <img src="../../../images/unchecked.jpg" width="15" height="15" >
									 <?PHP }?> ไม่อนุมัติ
								  </td>
							  <td  class="<?=$class_boxgs1?>">&nbsp;</td>
							  </tr>
							
							  <tr>
							    <td colspan="2"  class="<?=$class_boxgs1?>" style="<?=$class_boxnone1?>">&nbsp;</td>
							  </tr>
							
							  <tr>  
							    <td height="50" align="center" colspan="2">
								   <?PHP if($reqData[FmanagerBP_GSApp]=='Y'){?>
								  <img src="<?=$reqData[manager_bpgs_signature]?>"height="50" width="150">
									 <?PHP }elseif($reqData[FmanagerBP_GSApp]=='N'){?>
									 
								  <img src="../../../images/not-approved.jpg" height="50" width="150">
									 <?PHP }?>
							    </td>
							  </tr>
					        <tr>
					          <td align="center" colspan="2"><?=DateThai2($reqData[FmanagerBP_GSApp_date])?></td>
					        </tr>
					        <tr>
						      <td align="center" colspan="2"><?=$reqData[manager_bpgs_fname]?></td>
						      </tr>
						    <tr>
						      <td align="center" colspan="2"><?=$reqData[manager_bpgs_post_name]?></td>
							  </tr>
						  </tbody>
				 </table>
					<?PHP }?>
						 <?php /* //////////////////////sub///////////////////////////// */?>			
							 </td>
							  <td width="5%">&nbsp;</td>
							
							</tr>	
						  </tbody>	
						</table>  
						 
								  
								  
							</td>
							
						 <?php /* //////////////////////sub///////////////////////////// */?>	
							  <td width="30%">
								  
								 <table width="100%" border="0">
						  <tbody>
							<tr>
							  <td width="22%" align="center">จึงเรียนมาเพื่อทราบ</td>
							</tr>
							  <tr>
						      <td><?=$reqData[Fowner_comment]?></td>
					        </tr>
							<tr>
							  <td width="22%" align="center" height="50">
								  <?PHP if($reqData[FownerApp]=='Y'){?>
									 <img src="<?=$reqData[owner_signature]?>"height="50" width="150">
									 <?PHP }elseif($reqData[FownerApp]=='N'){?>
									 
									 <img src="../../../images/not-approved.jpg" height="50" width="150">
									 <?PHP }?>
								</td>
						    </tr>
						    <tr>
						      <td align="center"><?=DateThai2($reqData[FownerApp_date])?></td>
					        </tr>
						    <tr>
						      <td width="22%" align="center">คุณ<?=$reqData[owner_first_name]?> <?=$reqData[owner_last_name]?></td>
					        </tr>
							  <tr>
							  <td width="22%" align="center"><?=$reqData[owner_post_name]?></td>
							</tr>
							  <tr>
						      <td align="center"><?=$reqData[Fmanager_mt_comment]?></td>
					        </tr>
							<tr>
							  <td width="22%" align="center" height="50">
								<?PHP if($reqData[Fmanager_mtApp]=='Y'){?>
									 <img src="<?=$reqData[manager_mt_signature]?>"height="50" width="150">
									 <?PHP }elseif($reqData[Fmanager_mtApp]=='N'){?>
									 
									 <img src="../../../images/not-approved.jpg" height="50" width="150">
									 <?PHP }?>
								</td>
						    </tr>
						    <tr>
						      <td align="center"><?=DateThai2($reqData[Fmanager_mtApp_date])?></td>
					        </tr>
						    <tr>
							    <td align="center">คุณ<?=$reqData[manager_mt_first_name]?> <?=$reqData[manager_mt_last_name]?></td>
					        </tr>
						    <tr>
							  <td width="22%" align="center"><?=$reqData[manager_mt_post_name]?></td>
							</tr>
						  </tbody>
				 </table> 
								  
								  
							  </td>
						    </tr>
							</tbody>
				  </table>
				  
			  	  
			  </td>
		  </tr>
		  <?php /* /////////////////////////////////////////////////// */?>
		  <tr>
			  <td colspan="3" class="border_b_double">
			  
				 <table width="100%" border="0">
						  <tbody>
							<tr>
							  <td width="7%" align="center">ถึง</td>
							  <td width="32%"  class="box_request">&nbsp;คุณ<?=$reqData[owner_first_name]?> <?=$reqData[owner_last_name]?></select>
							 </td>
								 <td width="2%">&nbsp;</td>
							   <td width="3%">จาก</td>
							   <td width="31%"  class="box_request">&nbsp;<?=$reqData[sup_fname]?></td>
							  <td width="10%" align="right"></td>
								
							  <td width="15%"></td>
							</tr>
						  </tbody>
						</table> 
				  
				  
			  </td>
		  </tr>
		<?php /* /////////////////////////////////////////////////// */?>
		  <tr>
			  <td colspan="3">
				 
				<table width="100%" border="0">
						  <tbody>
							<tr>
							  <td width="18%">ผลการพิจารณา</td>
								 <td width="2%">
									 <?PHP if($reqData[FSupervisorApp]=='Y'){?>
									 <img src="../../../images/checked.jpg" width="15" height="15" >
									 <?PHP }else{?>
									 
									 <img src="../../../images/unchecked.jpg" width="15" height="15" >
									 <?PHP }?>
								</td>
							   <td width="8%">อนุมัติ</td>
							  <td width="2%">
								   <?PHP if($reqData[FSupervisorApp]=='N'){?>
									 <img src="../../../images/checked.jpg" width="15" height="15" >
									 <?PHP }else{?>
									 
									 <img src="../../../images/unchecked.jpg" width="15" height="15" >
									 <?PHP }?>
							</td>
							 <td width="8%">ไม่อนุมัติ</td>
								<td width="2%">
									  <?PHP if($reqData[FSupervisorApp]=='Ynote'){?>
									 <img src="../../../images/checked.jpg" width="15" height="15" >
									 <?PHP }else{?>
									 
									 <img src="../../../images/unchecked.jpg" width="15" height="15" >
									 <?PHP }?>
								</td>
							 <td width="16%" >อนุมัติ/หมายเเหตุ</td>
								<td width="2%">
									  <?PHP if($reqData[FSupervisorApp]=='other'){?>
									 <img src="../../../images/checked.jpg" width="15" height="15" >
									 <?PHP }else{?>
									 
									 <img src="../../../images/unchecked.jpg" width="15" height="15" >
									 <?PHP }?>
							  </td>
							 <td width="7%">อื่นๆ</td>
							 <td width="35%"><input name="fields[FSupervisorOther_note]" type="text" class="box_request" id="FSupervisorOther_note" style="text-align: center; width: 95%;"  value="<?=$reqData[FSupervisorOther_note]?>" readonly></td>
							</tr>
						  </tbody>
						</table>  
				  
			  </td>
		 </tr>
		  <?php /* /////////////////////////////////////////////////// */?>
		  <tr>
			  <td colspan="3">
			  <?PHP
								if($reqData[FSupervisor_comment]==''){
										$class_boxgs3="box_request";
										$class_boxgs4="";
										$class_boxnone="";
									
								 }else{
										$class_boxgs3="";
										$class_boxgs4="box_request";
										$class_boxnone="display: none;";
								 }
				?>
				 <table width="100%" border="0">
						  <tbody>
							<tr>
							  <td align="center" height="50">
						<table width="100%" border="0">
						  <tbody>
							<tr height="25">
							  <td width="15%">ความคิดเห็น</td>
								 <td width="85%" class="<?=$class_boxgs3?>">
										 <?=$reqData[FSupervisor_comment]?>
								</td>
							</tr>
							  <tr height="25"  style="<?=$class_boxnone?>">
								 <td colspan="2" class="<?=$class_boxgs3?>"></td>
							</tr>
						  </tbody>
						</table>
								
						      </td>
							  <td align="center">
								  <?PHP if($reqData[FSupervisorApp]!='N' && $reqData[FSupervisorApp]!=''){?>
									 <img src="<?=$reqData[manager_sup_signature]?>"height="50" width="150">
									 <?PHP }elseif($reqData[FSupervisorApp]=='N'){?>
									 
									 <img src="../../../images/not-approved.jpg" height="50" width="150">
									 <?PHP }?>
							  </td>
						    </tr>
							<tr height="25">
							  <td align="center" class="<?=$class_boxgs3?>">&nbsp;</td>
						      <td align="center"><?=DateThai2($reqData[FSupervisorApp_date])?></td>
						    </tr>
							<tr  height="25">
							    <td align="center" class="<?=$class_boxgs3?>">&nbsp;</td>
							    <td align="center">&nbsp;<?=$reqData[sup_fname]?></td>
						    </tr>
						    <tr  height="25">
							  <td width="78%" align="center" class="<?=$class_boxgs3?>">&nbsp;</td>
							  <td width="22%" align="center">&nbsp;<?=$reqData[sup_post_name]?></td>
							</tr>
						  </tbody>
				 </table> 
				  
			  </td>
		</tr>
		  <?php /* /////////////////////////////////////////////////// */?>
	
	</tr>
</table>
<table width="99%" cellpadding="0" cellspacing="0">
		  <?php /* /////////////////////////////////////////////////// */?>
		  <tr><td >&nbsp;&nbsp;&nbsp;&nbsp;เอกสารฉบับที่/วันที่ออกเอกสาร/วันที่บังคับใช้ : 2/21/02/2562 / 22/02/2562</td>
			  <td align="right">MTS-APP-01</td>
			</tr>
		  <?php /* /////////////////////////////////////////////////// */?>
	 </table>
		
    </form>
</body>
</html>