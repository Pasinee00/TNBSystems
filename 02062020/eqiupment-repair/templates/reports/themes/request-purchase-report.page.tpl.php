<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-874">
<title>รายงานการส่งซ่อม / ส่ง claim</title>

<link href="../../../css/stylesheet_report.css" rel="stylesheet" type="text/css">
</head>

<body>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td colspan="2" align="center"><b>รายงานการส่งซ่อม / ส่ง claim</b></td>
  </tr>
  <tr>
    <td width="91%">บริษัทโตโยต้านนทบุรี ผู้จำหน่ายโตโยต้า จำกัด</td>
    <td width="9%" align="right"><b>Page :</b> <?php print($page);?>/<?php print($totalPage);?>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2">&nbsp;<?php print($title);?></td>
  </tr>
  <tr>
    <td height="4" colspan="2"></td>
  </tr>
  <tr>
    <td colspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="4%" align="center" class="tlb_bg"><b>ลำดับ</b></td>
        <td width="7%" align="center" class="tlb_bg"><b>Req No.</b></td>
        <td width="15%" class="tlb_bg"><b>แผนก</b></td>
        <td width="14%" class="tlb_bg"><b>สาขา</b></td>
        <td width="24%" class="tlb_bg"><b>รายการที่สั่งซื้อ</b></td>
        <td width="20%" class="tlb_bg"><b>บริษัทที่สั่ง</b></td>
        <td width="8%" align="center" class="tlb_bg"><b>วันที่ส่ง</b></td>
        <td width="8%" align="center" class="tlbr_bg"><b>วันที่รับ</b></td>
      </tr>     
      <tr>
        <td align="center" class="lb">&nbsp;<?php print($i);?></td>
        <td align="center" class="lb">&nbsp;<?php print($row['FReqNo']);?></td>
        <td class="lb">&nbsp;<?php print($row['sec_nameThai']);?></td>
        <td class="lb">&nbsp;<?php print($row['brn_name']);?></td>
        <td class="lb">&nbsp;<?php print("1. ".$row['claims'][0]['FItems']);?></td>
        <td class="lb">&nbsp;<?php print($utilMD->convertDate2Thai($row['claims'][0]['FDateRequest'],"dd-sm"));?></td>
        <td align="center" class="lb">&nbsp;<?php print($utilMD->convertDate2Thai($row['claims'][0]['FSendDate'],"dd-sm"));?></td>
        <td align="center" class="lrb">&nbsp;<?php print($utilMD->convertDate2Thai($row['claims'][0]['FReciveDate'],"dd-sm"));?></td>
      </tr>
    </table></td>
  </tr>
</table>
</body>
</html>