<!DOCTYPE HTML">
<?php 
include '../../../lib/db_config.php';
include '../../../main/modules/Model_Utilities.php';
include '../../modules/request_model.php';

$utilMD = new Model_Utilities();
$reqMD = new Model_Request();
echo $_id = $_REQUEST['id'];
$reqData = $reqMD->get_data($_id);
$costData = $reqMD->load_cost($_id);
$estimateData = $reqMD->load_estimate($_id);
$attachs = $reqMD->list_attach($_id);
$states = $reqMD->get_request_state($_id);
$supports = $reqMD->load_support($_id);



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
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-874">
<script  type="text/javascript" src="../../../jsLib/jquery-1.8.0.min.js"></script>
<script src="../../../jsLib/uniform/jquery.uniform.js" type="text/javascript" charset="utf-8"></script>
<script src="../../../jsLib/js_scripts/js_function.js" type="text/javascript" charset="utf-8"></script>
<link href="../../../css/dialog-box.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="../../../jsLib/uniform/css/uniform.default.css" type="text/css" media="screen">
<link href="../../../css/sys_controll.css" rel="stylesheet" type="text/css">
<link href="../../../css/display.css" rel="stylesheet" type="text/css">
<title>Insert title here</title>
<script type="text/javascript" charset="utf-8">
      $(function(){
        $("input, textarea, select").uniform();
      });
</script>
<style type="text/css">
body,td,th {
	font-family: THNiramitAS, Georgia, sans-serif;
}
</style>
</head>
<body>
<div class="dialog-panel" style="height:100%;">
   		<div class="top-row">
   			<div class="left"></div>
   			<div class="center">
   				<span class="dialog-title">ข้อมูลใบแจ้งซ่อม / บำรุงรักษา</span>
   			</div>
   			<div class="right"></div>
   		</div> 
   		<div class="middle-row" style="height:100%;">
   			<div class="left"></div>
   			<div id="dialog-body" class="center" style="height:100px;">
   				<div style="width:100%;height:100%;overflow:auto;float:left;">
   					 <table width="98%"  height="98%" border="0" align="center" cellpadding="0" cellspacing="0">
						 <?PHP if($utilMD->convert2Thai($reqData['FStatus'])=='cancel'){?>
					         <tr>
					           <td style="color: #F4060A">เหตุผลการยกเลิก</td>
					           <td colspan="5"  style="color: #F4060A"><?=$utilMD->convert2Thai($reqData['FCancelRemark'])?></td>
			           </tr>
						 <?PHP }?>
					         <tr>
					           <td width="12%"><strong>รหัสพนักงาน :</strong></td>
					           <td width="22%"><?=$utilMD->convert2Thai($reqData['emp_code'])?> </td>
					           <td width="4%">&nbsp;</td>
					           <td width="15%"><strong>ชื่อ - สกุล :</strong></td>
					           <td width="23%"><?=$utilMD->convert2Thai($reqData['emp_name'])?> </td>
					           <td width="24%"></td>
					         </tr>
					         <tr>
					           <td><strong>ตำแหน่ง :</strong></td>
					           <td><?=$utilMD->convert2Thai( $reqData['post_name'])?></td>
					           <td>&nbsp;</td>
					           <td><strong>แผนก :</strong></td>
					           <td><?=$utilMD->convert2Thai( $reqData['sec_nameThai'])?></td>
					           <td></td>
					         </tr>
					         <tr>
					           <td><strong>สาขา :</strong></td>
					           <td><?=$utilMD->convert2Thai( $reqData['brn_name'])?></td>
					           <td>&nbsp;</td>
					           <td><strong>ผู้จัดการ /หัวหน้างาน :</strong></td>
					           <td><?=$utilMD->convert2Thai($reqData['FManagerName'])?></td>
					           <td></td>
					         </tr>
					          <tr>
					           <td></td>
					           <td></td>
					           <td>&nbsp;</td>
					           <td><strong>ผู้อำนวยการ :</strong></td>
					           <td><?=$utilMD->convert2Thai($reqData['FSupervisorName'])?></td>
					           <td></td>
					         </tr>
					         <tr>
					           <td><strong>เลขที่อ้างอิง   :</strong></td>
					           <td><?=$utilMD->convert2Thai($reqData['FInf_no'])?></td>
					           <td>&nbsp;</td>
					           <td><strong>เลขที่ทรัพย์สิน  :</strong></td>
					           <td colspan="2">
					             <?php 
					             		if(!empty($reqData['FAsset_no'])){echo $reqData['FAsset_no'];}
					             		else echo 'ไม่พบรหัส';
					             ?>
					          </td>
					         </tr>
					         <tr>
					           <td><strong>หน่วยงาน  :</strong></td>
					           <td><?=$utilMD->convert2Thai($reqData['FFnc'])?></td>
					           <td>&nbsp;</td>
					           <td><strong>เบอร์โทร. <font color="#FF0000">*</font> :</strong></td>
					           <td><?=$utilMD->convert2Thai($reqData['FTel'])?></td>
					           <td></td>
					         </tr>
					         <tr>
					           <td><strong>สาขาที่ติดตั้ง   :</strong></td>
					           <td><?=$utilMD->convert2Thai($reqData['FBranchName'])?></td>
					           <td>&nbsp;</td>
					           <td><strong>อาคาร / สถานที่   :</strong></td>
					           <td><?=$utilMD->convert2Thai($reqData['FLocation'])?></td>
					           <td></td>
					         </tr>
					         <tr>
					           <td><strong>ชั้น  :</strong></td>
					           <td><?=$utilMD->convert2Thai($reqData['FFloor'])?></td>
					           <td>&nbsp;</td>
					           <td><strong>ห้อง <font color="#FF0000">*</font> :</strong></td>
					           <td><?=$utilMD->convert2Thai($reqData['FRoom'])?></td>
					           <td></td>
					         </tr>
					         <tr>
					           <td><strong>วันที่ / เวลา :</strong></td>
					           <td><?=$utilMD->convertDate2Thai($reqData['FReqDate'], 'dd-sm')?> / <?=$reqData['FReqTime']?></td>
					           <td>&nbsp;</td>
					           <td><strong>ระดับความสำคัญ  :</strong></td>
					           <td><?=$utilMD->convert2Thai($reqData['FLevelName'])?></td>
					           <td></td>
					         </tr>
					         <tr>
					           <td><strong>ขอแจ้งซ่อม   :</strong></td>
					           <td><?=$utilMD->convert2Thai($reqData['FRepairGroupName'])?> </td>
					           <td>&nbsp;</td>
					           <td><strong>รายการซ่อม   :</strong></td>
					           <td><?=$utilMD->convert2Thai($reqData['FRepairGroupItemName'])?></td>
					           <td></td>
					         </tr>
        
		<tr>
		   <td valign="top"><strong>รายละเอียด / ปัญหา  :</strong></td>
		   <td   valign="top"><?=$utilMD->convert2Thai($reqData['FDetail'])?></td>
		   <td   valign="top">&nbsp;</td>
		   <td   valign="top">&nbsp;</td>
		   <td colspan="2"   valign="top"></td>
		 </tr>				 
		 <tr>
              <td colspan="6"><strong><u>รูปภาพประกอบการแจ้งซ่อม</u></strong></td>
            </tr>
        <tr>
              <td width="18%" height="25">1.บริเวณที่ต้องการแจ้งซ่อม</td>
              <td width="32%"></td>
              <td>&nbsp;</td>
              <td colspan="2">2.อุปกรณ์ที่ต้องการแจ้งซ่อม</td>
		   <td   valign="top">&nbsp;</td>
            </tr>
        <tr>
              <td height="300" colspan="2" align="center">
				   <?PHP if($reqData['FPhoto_1']!=""){?>
				  <img id="photo_1" src="../../../uploads/mt-data/reqNo-<?=$reqData[FRequestID]?>/<?=$reqData['FPhoto_1']?>" width="380" height="230">
			       <?PHP }else{?>
				  <img id="photo_1" src="../../../images/cm/default_photo2.jpg" width="380" height="230">
				   <?PHP }?>
			</td>
              <td width="2%">&nbsp;</td>
              <td colspan="2" align="center">
				   <?PHP if($reqData['FPhoto_2']!=""){?>
				  <img id="photo_2" src="../../../uploads/mt-data/reqNo-<?=$reqData[FRequestID]?>/<?=$reqData['FPhoto_2']?>" width="380" height="230">
			       <?PHP }else{?>
				  <img id="photo_2" src="../../../images/cm/default_photo1.jpg" width="380" height="230">
				   <?PHP }?></td>
		      <td valign="top">&nbsp;</td>
            </tr>
        <tr>
              <td height="25">3.เพิ่มเติม</td>
              <td height="25"></td>
              <td>&nbsp;</td>
              <td>4.เพิ่มเติม</td>
              <td></td>
		   	  <td valign="top">&nbsp;</td>
            </tr>
        <tr>
              <td height="300" colspan="2" align="center">
				  <?PHP if($reqData['FPhoto_3']!=""){?>
				  <img id="photo_3" src="../../../uploads/mt-data/reqNo-<?=$reqData[FRequestID]?>/<?=$reqData['FPhoto_3']?>" width="380" height="230">
			       <?PHP }else{?>
				  <img id="photo_3" src="../../../images/cm/default_photo3.jpg" width="380" height="230">
				   <?PHP }?>
			</td>
              <td>&nbsp;</td>
              <td colspan="2" align="center">
				  <?PHP if($reqData['FPhoto_4']!=""){?>
				  <img id="photo_4" src="../../../uploads/mt-data/reqNo-<?=$reqData[FRequestID]?>/<?=$reqData['FPhoto_4']?>" width="380" height="230">
			       <?PHP }else{?>
				  <img id="photo_4" src="../../../images/cm/default_photo3.jpg" width="380" height="230">
				   <?PHP }?></td>
		      <td   valign="top">&nbsp;</td>
            </tr>
					         <tr>
					          
					           <td   valign="top"><strong>ผลการอนุมัติ</strong></td>
                                <?
								  if($reqData['approve_date']!=""){
										list($y_ma,$m_ma,$d_ma) = split("-",$reqData['approve_date']);
										$closejob_ma_date=$d_ma." ".$utilMD->getNameMonthFull($m_ma)." ".($y_ma+543);
									}else{
										$closejob_ma_date="";
									}
								  ?>
					           <td colspan="2"   valign="top"><? if($closejob_ma_date!=""){ ?><img src="<?=check_signature($reqData['FManagerID'])?>" width="130" height="40"> <?  echo "วันที่"." ".$closejob_ma_date;  } ?></td>
								<td valign="top">&nbsp;</td>
					           <td valign="top" colspan="2">&nbsp;</td>
				             </tr>
					         <tbody>
					         	<tr>
						           <td colspan="6" align="center" style="background-color:#999999;"><b>ผลการดำเนินการของผู้รับผิดชอบ</b></td>
						        </tr>
						        <tr>
						           <td><strong>ผู้รับเรื่อง  :</strong></td>
						           <td><?php print($utilMD->convert2Thai($reqData['recive_name']))?></td>
						           <td></td>
						           <td><strong>วันที่รับเรื่อง :</strong>&nbsp;</td>
						           <td><?=$utilMD->convertDate2Thai($reqData['FReciveDate'], 'dd-sm')?> / <?=$reqData['FReciveTime']?></td>
						           <td></td>
						         </tr>
						         <tr>
						           <td colspan="6">
						           		<table width="100%" border="0" cellspacing="0" cellpadding="0">
					                       <tr>
					                          <td width="50%" valign="top">
					                             <table width="100%" border="0" cellspacing="1" cellpadding="1">
					                                <tr>
					                                   <td width="8%" height="20" align="center">&nbsp;</td>
					                                   <td height="20" colspan="5">
					                                   	  <table width="100%" border="0" cellspacing="0" cellpadding="0">
					                                        <tr>
					                                           <td width="7%" align="center" >[<? if($reqData['FJobresult']=="1"){print "<img src=\"../../../images/OK.gif\" width=\"12\" height=\"12\">";}else{print "&nbsp;&nbsp;";}?>]</td>
					                                           <td width="19%">ซ่อมเอง </td>
					                                           <td width="10%" align="center">[<? if($reqData['FJobresult']=="2"){print "<img src=\"../../../images/OK.gif\" width=\"12\" height=\"12\">";}else{print "&nbsp;&nbsp;";}?>]</td>
					                                           <td width="64%">ให้ผู้รับเหมาดำเนินการ</td>
					                                        </tr>
					                                       </table>
					                                   </td>
					                                 </tr>
					                                <tbody id="cost-list-l">
					                                		<tr>
				                                              <td align="center">&nbsp;</td>
				                                              <td colspan="2"><b>ค่าแรง</b></td>
				                                              <td align="center">ราคา</td>
				                                              <td align="right" class="underlinedott"><? if($reqData['FLapAmt']>0)print number_format($reqData['FLapAmt'],2,".",",");?>&nbsp;</td>
				                                              <td align="center">บาท</td>
				                                            </tr>
					                                </tbody>
					                                 <tr>
					                                    <td height="20">&nbsp;</td>
					                                    <td height="20" colspan="2"><b>ค่าอะไหล่</b></td>
					                                    <td height="20" align="center">&nbsp;</td>
					                                    <td height="20" align="right" class="underlinedott"><? if($reqData['FPartAmt']>0)print number_format($reqData['FPartAmt'],2,".",",");?>&nbsp;</td>
					                                    <td height="20" align="center">บาท</td>
					                                 </tr>
					                                <tbody id="cost-list-p"> </tbody>
					                                <tr>
					                                   <td height="20" align="center">[<? if($reqData['FReam']=="Y"){print "<img src=\"../../../images/OK.gif\" width=\"12\" height=\"12\">";}else{print "&nbsp;&nbsp;&nbsp;&nbsp;";}?>]</td>
					                                   <td height="20">&nbsp;</td>
					                                   <td height="20" colspan="4">เบิกเงินสดเพื่อจัดซื้อวัสดุมาดำเนินการซ่อมแซ่ม</td>
					                                </tr>
					                                <tr>
					                                		<td colspan="6" valign="top">
					                                				<table width="100%" border="0" cellspacing="0" cellpadding="0">
													           			<tr>
													           			   <td width="16%"><strong>กำหนดเสร็จ  :</strong></td>
																           <td width="21%"><?=$utilMD->convert2Thai($reqData['FEstimate'])?></td>
																           <td width="2%"></td>
																           <td width="17%"><strong>วันที่เปิด Job <?php if($userInfo['user_type']=="M"){?><font color="#FF0000">*</font><?php }?> :</strong></td>
																           <td width="22%"><?=$utilMD->convertDate2Thai($reqData['FEditDate'], 'dd-sm')?></td>
													           			</tr>
													           			<tr>
																           <td><strong>วันที่กำหนดเสร็จ  :</strong></td>
																           <td><?=$utilMD->convertDate2Thai($reqData['FDueDate'], 'dd-sm')?></td>
																           <td></td>
																           <td><strong>วันที่ปิดงาน <?php if($close =="y"){?><font color="#FF0000">*</font><?php }?>  :</strong></td>
																           <td><?=$utilMD->convertDate2Thai($reqData['FFinishDate'], 'dd-sm')?></td>
																         </tr>
													           			<tr>
																           <td><strong>ผู้ตรวจรับงาน  :</strong></td>
																           <td><?=$utilMD->convert2Thai($reqData['FAuditorName'])?></td>
																           <td></td>
																           <td><strong>วันที่ตรวจรับงาน  :</strong></td>
																           <td><?=$utilMD->convertDate2Thai($reqData['FAuditDate'], 'dd-sm')?></td>
																         </tr>
																          <tr>
																           <td colspan="3">[<? if($reqData['FApprove']=="Y"){print "<img src=\"../../../images/OK.gif\" width=\"12\" height=\"12\">";}else{print "&nbsp;&nbsp;&nbsp;&nbsp;";}?>] รอการเซ็นต์อนุมัติ</td>
                                                                           <td width="20%"><b>สาเหตุที่ทำให้งานล่าช้า : </b></td>
                                                                           <td><?=$utilMD->convert2Thai($reqData['topic_worklate']);?></td>
																		</tr>
													           		</table>
					                                		</td>
					                                </tr>
					                             </table>
					                           </td>
					                           <td valign="top">
					                             <table width="100%" border="0" cellspacing="1" cellpadding="1">
					                                <tr>
					                                  <td width="9%" height="20" align="center">
					                                  	[<? if($reqData['FCondition']=="1"){print "<img src=\"../../../images/OK.gif\" width=\"12\" height=\"12\">";}else{print "&nbsp;&nbsp;&nbsp;&nbsp;";}?>]
					                                  </td>
					                                  <td height="20" colspan="4">จากการเข้าร่วมตรวจสอบเห็นควรดำเนินการตามเสนอ</td>
					                                </tr>
					                                <tr>
					                                  <td height="20" align="center">
					                                  	[<? if($reqData['FCondition']=="2"){print "<img src=\"../../../images/OK.gif\" width=\"12\" height=\"12\">";}else{print "&nbsp;&nbsp;&nbsp;&nbsp;";}?>]
					                                  </td>
					                                  <td height="20" colspan="4">อื่น ๆ</td>
					                                </tr>
					                                  <tr>
						                                  <td height="20" align="center" valign="top"><b>สรุปงาน :</b></td>
						                                  <td height="20" colspan="4"><?=$utilMD->convert2Thai($reqData['FOth_detail'])?></td>
						                              </tr> 
					                                <tbody id="estimate-list">
					                                	
					                                </tbody>                 
					                              </table>
					                            </td>
					                         </tr>
					                     </table>
						           </td>
						         </tr>
						         <tr>
						           <td colspan="3" valign="top">

						           </td>
						           <td colspan="4" rowspan="5" align="left" valign="top">
						           		<table width="100%" border="0" cellspacing="0" cellpadding="0">
						                   <tr>
						                      <td width="65" valign="top"><strong>ผู้แก้ไข   :</strong></td>
						                      <td align="center" >
						                      		<table width="100%" border="0" cellspacing="0" cellpadding="0" >
							<tr>
									<td class="line-l-dash line-t-dash line-b-dash" width="" align="left">&nbsp;<b>ช่างผู้แก้ไข</b></td>
									<td class="line-l-dash line-t-dash line-b-dash" width="15%" align="center"><b>วันที่เปิดงาน</b></td>
									<td class="line-l-dash line-t-dash line-b-dash" width="15%" align="center"><b>วันที่ปิดงาน</b></td>
									<td class="line-l-dash line-t-dash line-r-dash line-b-dash" width="15%" align="center"><b>สถานะ</b></td>
									<td width="10%" align="center"></td>
							</tr>
					                      				<tbody id="support-list">
					                    				<?php 
													            	$index = 0;
													            	if(!empty($supports)){
															            	foreach($supports as $key=>$val){
																				$index++;
													            ?>
																	           <tr>
											                      						<td class="line-l-dash line-t-dash line-b-dash" width="" align="left">&nbsp;<?=$utilMD->convert2Thai($val['first_name'])?>&nbsp;&nbsp;<?=$utilMD->convert2Thai($val['last_name'])?></td>
											                      						<td class="line-l-dash line-t-dash line-b-dash" width="15%" align="center"><?=$val['FStartDate']?></td>
											                      						<td class="line-l-dash line-t-dash line-b-dash" width="15%" align="center"><?=$val['FFinishDate']?></td>
											                      						<td class="line-l-dash line-t-dash line-r-dash line-b-dash" width="15%" align="center"><?=$val['FStatus']?></td>
											                      						<td width="10%" align="center"></td>
											                      				</tr>
													            <?php 
													            			}
													            	}
													            ?>
					                   					</tbody>
					                      		</table>
						                      		
						                      </td>
						                   </tr>
						                </table>
						           </td>
						         </tr>
					         </tbody>
					         <tr>
					         	<td colspan="6">
					         		<table width="100%">
					         			<tr>
					         				<td width="25%"></td>
					         				<td width="37%">
					         					<div class="search-action">
											  	    <span class="package-status" style="padding-left:23px;padding-right:5px;">รอส่ง claim/ซ่อม</span>
											  		<span class="package-accept-status" style="padding-left:23px;padding-right:5px;">ส่ง claim/ซ่อมแล้ว</span>
											  		<span class="package-download-status" style="padding-left:23px;padding-right:5px;">รับของแล้ว</span>
											  	</div>
					         				</td>
					         				<td width="38%">
					         					<div class="search-action">
											  	    <span class="euro-sign-status" style="padding-left:23px;padding-right:5px;">รอการสั่งซื้อ</span>
											  		<span class="pound-sign-status" style="padding-left:23px;padding-right:5px;">ทำการสั่งซื้อแล้ว</span>
											  		<span class="dollar-sign-status" style="padding-left:23px;padding-right:5px;">ได้รับของแล้ว</span>
											  	</div>
					         				</td>
					         			</tr>
					         			<tr>
					         				<td style="vertical-align:top;">
					         					<div class="list-header">
												  	<ul>
												  		<li></li>
												  		<li style="width:72%;text-align:left;font-size: inherit;">ไฟล์แนบ</li>
												  		<li style="width:21%;font-size: inherit;">Download</li>
												  		<li></li>
												  	</ul>
												</div>
												<div class="list-items" id="file-list" style="overflow:hidden;">
														<ul>
																<?php if(!empty($attachs)){
										   							foreach($attachs as $key=>$val){
											   					?>
																		<li></li>
																		<li style="width:72%;font-size: inherit;text-align:left;"><?php print $utilMD->convert2Thai($val['FAttachName']);?></li>
																		<li style="width:21%"><span class="download-icon" onclick="javascript:downloadFile('<?php print $_id?>','<?php print $utilMD->convert2Thai($val['FAttachName']);?>','<?php print $val['FAttachLink']?>')"></span></li>
																		<li></li>
																<?php 
											   							}
																	 }
																?>
														</ul>
												</div>
					         				</td>
					         				<td style="vertical-align:top;">
					         					<div class="list-header">
												  	<ul>
												  		<li></li>
												  		<li style="width:80%;text-align:left;font-size: inherit;">รายการส่งซ่อม / ส่ง claim</li>
												  		<li style="width:15%;font-size: inherit;">สถานะ</li>
												  		<li></li>
												  	</ul>
												</div>
												<div class="list-items" id="claim-list" style="overflow:hidden;">
		
												</div>
					         				</td>
					         				<td style="vertical-align:top;">
					         					<div class="list-header">
												  	<ul>
												  		<li></li>
												  		<li style="width:80%;text-align:left;font-size: inherit;">รายการสั่งซื้อ</li>
												  		<li style="width:15%;font-size: inherit;">สถานะ</li>
												  		<li></li>
												  	</ul>
												</div>
												<div class="list-items" id="purchase-list" style="overflow:hidden;">
		
												</div>
					         				</td>
					         			</tr>
					         		</table>
					         	</td>
					         </tr>
					         <tr>
					           <td>&nbsp;</td>
					           <td></td>
					           <td>&nbsp;</td>
					           <td>&nbsp;</td>
					           <td></td>
					           <td></td>
					         </tr>
					      </table>
   				</div>
   			</div>
   			<div class="right"></div>
   		</div>
   		<div class="bottom-row">
   			<div class="left"></div>
   			<div class="center">
   			    <ul class="request-state">
   			    	<?php if(!empty($states)){
   							foreach($states as $key=>$val){
   					?>
   								<?php if($val['numDay']>0){?>
   									<li class="arrow-state<?php print $val['type'];?>"><?php print $val['numDay'];?> day</li>
   								<?php }?>
			   			    	<li>
			   			    		<span><?php print $utilMD->convert2Thai($val['label']);?></span>
			   			    		<span><?php print $val['date'];?></span>
			   			    	</li>
   					<?php 
   							}
						 }
					?>
   			    </ul>
   			</div>
   			<div class="right"></div>
   		</div>
   </div>
</body>
<script>
function downloadFile(id,filename,url){
	var width = screen.width-10;
	var height = screen.height-60;
	newwindow=window.open('../../../download.php?name='+filename+'&reqId='+id+'&filename='+url,
								  'downloadWindow','width='+width+',height='+height+',left=0,top=0,screenX=0,screenY=0,status=no,menubar=yes,scrollbars=yes,copyhistory=yes, resizable=yes,fullscreen=no');
}
function openClaimInfor(id,no){
	var w = 700;
	var h = 250;
	if(isNaN(id))id='';
	parent.TINY.box.show({iframe:'../eqiupment-repair/templates/informations/information-claim.php?id='+id+'&no='+no,boxid:'frameless',width:w,height:h,fixed:false,maskopacity:40});
}
function getClaimItems(id){
	$.ajax({ 
		url: "../../controllers/claim_controller.php" ,
		type: "POST",
		datatype: "json",
		data: {"function":"get_json",
			   "FRequestID":id
		}
	})
	.success(function(results) { 
		$("#claim-list").empty();
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
			  	ul+= "<li style=\"width:80%;text-align:left;font-size: inherit;\">"+cell['FItems']+"</li>";
			  	ul+= "<li style=\"width:15%;\"><span class=\""+cell['StatusIcon']+"\"></span></li>";
			  	ul+= "<li></li>";
			    ul+= "<ul>";
				$("#claim-list").append(ul);
			}
		}
		
	});
}/*End of function getClaimItems()*/
function openPurchaseInfor(id,no){
	var w = 700;
	var h = 350;
	if(isNaN(id))id='';
	parent.TINY.box.show({iframe:'../eqiupment-repair/templates/informations/information-purchase.php?id='+id+'&no='+no,boxid:'frameless',width:w,height:h,fixed:false,maskopacity:40});
}
function getPurchaseItems(id){
	$.ajax({ 
		url: "../../controllers/purchase_controller.php" ,
		type: "POST",
		datatype: "json",
		data: {"function":"get_json",
			   "FRequestID":id
		}
	})
	.success(function(results) { 
		$("#purchase-list").empty();
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
			  	ul+= "<li style=\"width:80%;text-align:left;font-size: inherit;\">"+cell['FItems']+"</li>";
			  	ul+= "<li style=\"width:15%;\"><span class=\""+cell['StatusIcon']+"\"></span></li>";
			  	ul+= "<li></li>";
			    ul+= "<ul>";
				$("#purchase-list").append(ul);
			}
		}
		
	});
}/*End of function getClaimItems()*/

$(document).ready(function (){
	getPurchaseItems(<?=$_id?>);
	getClaimItems(<?=$_id?>);
});
</script>
</html>