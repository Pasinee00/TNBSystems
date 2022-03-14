<?php
 class Model_Purchase{
 	var $tbl_name = "";
 	var $key_id = "";
 	function Model_Purchase(){
 		$this->tbl_name = "mtrequest_db.tbl_purchase";
 		$this->key_id = "tbl_purchase.FPurchaseID";
 	}
 	
 	function insert_data($fields,$id){
 		$field_sql = "";
 		$where_sql = "";
 		
 		foreach($fields as $key=>$val){
 			$field_sql .=(!$field_sql)?$key."='".iconv("utf-8","tis-620",$val)."'":",".$key."='".iconv("utf-8","tis-620",$val)."'";
 		}
 		if($id)$where_sql = $this->key_id."=$id";
 		if(!$id)$sql = "INSERT INTO ".$this->tbl_name." SET $field_sql";
 		else $sql = "UPDATE ".$this->tbl_name." SET $field_sql WHERE $where_sql";
 		$insert_rst = mysql_query($sql);
 		
 		$query1 = "SELECT SUM(FPrice) AS totalParts FROM ".$this->tbl_name." WHERE FRequestID={$fields['FRequestID']} AND purchase_type='part'";
 		$rst1 = mysql_query($query1);
 		$partTotal = 0;
 		while($row1=mysql_fetch_object($rst1)){
 			$partTotal = $row1->totalParts;
 		}
		$query2 = "SELECT SUM(FPrice) AS totalParts FROM ".$this->tbl_name." WHERE FRequestID={$fields['FRequestID']} AND purchase_type='lap'";
 		$rst2 = mysql_query($query2);
 		$lapTotal = 0;
 		while($row2=mysql_fetch_object($rst2)){
 			$lapTotal = $row2->totalParts;
 		}
		
 		$query3 = "UPDATE mtrequest_db.tbl_request SET FPartAmt='".$partTotal."', FLapAmt='".$lapTotal."' WHERE FRequestID={$fields['FRequestID']}";
 		$rst3 = mysql_query($query3);
		//return $query3;
 		@mysql_free_result($insert_rst);
 	}/*End of function insert_data()*/

 	function get_data($id){
 		$dataArr = array();
 		$select_sql ="SELECT t1.*,t2.FSuplierName,t3.FAsset_no AS FAssetNo,t4.emp_name,t5.sec_nameThai,t6.brn_name "
	 				."FROM  {$this->tbl_name} AS t1 "
	 				."LEFT JOIN general_db.tbl_suplier t2 ON(t2.FSuplierID = t1.FComClaimID) "
	 				."LEFT JOIN mtrequest_db.tbl_request t3 ON(t3.FRequestID = t1.FRequestID) "
	 				."LEFT JOIN pis_db.tbl_employee t4 ON(t4.emp_id = t3.FReqID) "
	 				."LEFT JOIN pis_db.tbl_section t5 ON(t5.sec_id = t3.FSectionID) "
	 				."LEFt JOIN pis_db.tbl_branch t6 ON(t6.brn_id = t3.FBranchID) "
	 				."WHERE t1.FPurchaseID ='{$id}' ";
 		$select_rst = mysql_query($select_sql);
 		$columns = mysql_num_fields($select_rst);
 		while($select_row=mysql_fetch_object($select_rst)){
 			for($i=0;$i<$columns;$i++){
 				$field_name = mysql_field_name($select_rst,$i);
 				$dataArr[$field_name] = iconv("tis-620","utf-8",$select_row->$field_name);
 			}
 		}
 		return $dataArr;
 		@mysql_free_result($insert_rst);
 	}//end function get_data($id)

 	function delete_data($id,$rId){
		$delete_sql = "DELETE FROM ".$this->tbl_name." WHERE ".$this->key_id."='$id'";
		$delete_rst = mysql_query($delete_sql);
		
		$query1 = "SELECT SUM(FPrice) AS totalParts FROM ".$this->tbl_name." WHERE FRequestID={$rId} AND purchase_type='part'";
 		$rst1 = mysql_query($query1);
 		$partTotal = 0;
 		while($row1=mysql_fetch_object($rst1)){
 			$partTotal = $row1->totalParts;
 		}
		$query2 = "SELECT SUM(FPrice) AS totalParts FROM ".$this->tbl_name." WHERE FRequestID={$rId} AND purchase_type='lap'";
 		$rst2 = mysql_query($query2);
 		$lapTotal = 0;
 		while($row2=mysql_fetch_object($rst2)){
 			$lapTotal = $row2->totalParts;
 		}
		
 		$query3 = "UPDATE mtrequest_db.tbl_request SET FPartAmt='".$partTotal."', FLapAmt='".$lapTotal."' WHERE FRequestID={$rId}";
 		$rst3 = mysql_query($query3);
		///return $query3;
		@mysql_free_result($insert_rst);
	}
	function getLatestDueDate($rId){
		$_date = "";
		$query = "SELECT FDueDate FROM {$this->tbl_name} WHERE FRequestID={$rId} ORDER BY FDueDate DESC LIMIT 1";
		$rst = mysql_query($query);
		while($row=mysql_fetch_object($rst)){
			$_date = $row->FDueDate;
		}
		return $_date;
	}//End function getLatestDueDate
	function checkPRStatus($rId){
		$isWaiting = false;
		$query = "SELECT * FROM {$this->tbl_name} WHERE FRequestID={$rId} AND FPRDate IS NULL AND FPODate IS NULL";
		$rst = mysql_query($query);
		while($row=mysql_fetch_object($rst)){
			$isWaiting = true;
		}
		return $isWaiting;
	}/*End of function checkPRStatus*/
	function checkPOStatus($rId){
		$isWaiting = false;
		$query = "SELECT * FROM {$this->tbl_name} WHERE FRequestID={$rId} AND FPODate IS NULL AND FPRDate IS NOT NULL";
		$rst = mysql_query($query);
		while($row=mysql_fetch_object($rst)){
			$isWaiting = true;
		}
		return $isWaiting;
	}/*End of function checkPRStatus*/
	function get_data_json($id){
		$select_sql = "SELECT * " .
				"FROM ".$this->tbl_name." t1 " .
				"INNER JOIN mtrequest_db.tbl_request t2 ON(t2.FRequestID = t1.FRequestID) ".
				"LEFT JOIN general_db.tbl_suplier t3 ON(t3.FSuplierID = t1.FComClaimID) ".
				"WHERE t1.FRequestID='{$id}' AND (t1.purchase_type='part' OR t1.purchase_type IS NULL OR t1.purchase_type='')";
		$select_rst = mysql_query($select_sql);
		$_status = array("NEW"=>"euro-sign-status","PUR"=>"pound-sign-status","BUY"=>"dollar-sign-status");
		while($val=mysql_fetch_array($select_rst)){
			$cell = array(
					"order"=>$i
					,"FPurchaseID"=>iconv("TIS-620","UTF-8",$val['FPurchaseID'])
					,"FRequestID"=>iconv("TIS-620","UTF-8",$val['FRequestID'])
					,"FItems"=>iconv("TIS-620","UTF-8",$val['FItems'])
					,"FComClaimID"=>iconv("TIS-620","UTF-8",$val['FComClaimID'])
					,"FBuyDate"=>iconv("TIS-620","UTF-8",$val['FBuyDate'])
					,"FReciveDate"=>iconv("TIS-620","UTF-8",$val['FReciveDate'])
					,"FDateRequest"=>iconv("TIS-620","UTF-8",$val['FDateRequest'])
					,"FReqNo"=>iconv("TIS-620","UTF-8",$val['FReqNo'])
					,"FSuplierName"=>iconv("TIS-620","UTF-8",$val['FSuplierName'])
					,"StatusIcon"=>iconv("TIS-620","UTF-8",$_status[$val['FPurchaseStatus']])
			);
		
			$rows[] = array(
					"id" => $val['FPurchaseID'],
					"cell" => $cell
			);
			$i++;
		}
		$data['rows'] = $rows;
		return $data;
	}
	function get_data_list($params){
		$page = $params['page']; // รับค่าหน้าที่ต้องการนำมาแสดง
		$rp = $params['rp']; // รับค่าจำนวนแสดงต่อ 1 หน้า
		$sortname = $params['sortname']; //  รับค่าเงื่อนไข field ที่ต้องการจัดเรียง
		$sortorder = $params['sortorder']; // รับค่ารูปแบบการจัดเรียงข้อมูล
		$search = $params['search'];
		$where = "";
		if(!empty($params['FRequestID'])){$where.= " AND t1.FRequestID='{$params['FRequestID']}' ";}
		//$where.= "  AND (t1.purchase_type='part' OR t1.purchase_type IS NULL OR t1.purchase_type='') ";
		if(!empty($search)){
			foreach($search as $key=>$val){
				//if(!empty($val))$where .= " AND ".$key." = '{$val}'";
				if($key == 'duplicate'){
					foreach($val as $index=>$item){
						if(!empty($item['value1']))$where .= " AND {$item['key']} {$item['condition1']} '{$item['value1']}'";
						if(!empty($item['value2']))$where .= " AND {$item['key']} {$item['condition2']} '{$item['value2']}'";
					}
				}else if($key=="multi"){
					$_search = $val['value'];	
					$where .= " AND (";
					$_i = 0;
					foreach($val['fields'] as $index=>$item){
						if($_i>0) $where .= " OR ";
						if($item == "like")$where .= "LOCATE('".iconv("UTF-8", "TIS-620",$_search)."',{$index})>0";
						else $where .= "{$index} {$item} '{$_search}'";
						$_i++;
					}
					$where .= ")";
			}else{
					if(!empty($val['value']))$where .= " AND {$key} {$val['condition']} '{$val['value']}'";
				}
				 
			}
		}
		$_status = array("NEW"=>"euro-sign-status","PUR"=>"pound-sign-status","BUY"=>"dollar-sign-status");
			
		// ส่วนการกำหนดค่า กรณีไม่ได้ส่งค่ามา
		if (!$sortname) $sortname = $this->order_name; // ถ้าไม่ส่งค่ามา กำหนดเป็น field ชื่อ arti_id (ขึ้นกับข้อมูลแต่ละคน)
		if (!$sortorder) $sortorder = 'desc'; // ถ้าไม่ส่งรูปแบบการจัดเรียงข้อมูลมา ให้กำหนดเป็น จากมากไปหาน้อย desc
		if (!$page) $page = 1; //  ถ้าไม่ได้ส่งหน้าที่ต้องการแสดงมา ให้แสดงหน้าแรก เป็น 1
		if (!$rp) $rp = 18; // หากไม่กำหนดรายการที่จะแสดงต่อ 1 หน้ามา ให้กำหนดเป็น 10
			
		// ส่วนสำหรับจัดรูปแบบขอบเขตและเงื่อนไขข้อมูลที่ต้องการแสดง
		$start = (($page-1) * $rp);
		$limit = "LIMIT $start, $rp";
		$sort = " ORDER BY t2.FRequestID DESC";
		//if($query){
		//	$where = " AND LOCATE('".iconv("UTF-8", "TIS-620",$query)."', FSuplier)>0 ";
		//}
			
			
		$select_sql = "SELECT * " .
					  "FROM ".$this->tbl_name." t1 " .
				      "INNER JOIN mtrequest_db.tbl_request t2 ON(t2.FRequestID = t1.FRequestID) ".
				      "LEFT JOIN general_db.tbl_suplier t3 ON(t3.FSuplierID = t1.FComClaimID) ".
				      "WHERE 1 $where";
			
		// ส่วนหรับหาว่ามีข้อมูลทั้งหมดเท่าไหร่ เก็บในตัวแปร $total
		$qr = mysql_query($select_sql);
		$total = mysql_num_rows($qr);
			
		// ส่วนสำหรับดึงข้อมูลมาสร้าง json ไฟล์ สำหรับแสดง
		$select_sql = "SELECT t1.*,t2.FReqNo,t3.* " .
				      "FROM ".$this->tbl_name." t1 " .
				      "INNER JOIN mtrequest_db.tbl_request t2 ON(t2.FRequestID = t1.FRequestID) ".
				      "LEFT JOIN general_db.tbl_suplier t3 ON(t3.FSuplierID = t1.FComClaimID) ".
				      "WHERE 1 $where $sort $limit";
		$select_rst = mysql_query($select_sql);

		$i=$start+1;
		$data['page'] = intval($page);
		$data['total_page'] = ceil($total/$rp);
		$data['total'] = intval($total);
		$data['begin'] = $i;
		while($val=mysql_fetch_array($select_rst)){
	
			$cell = array(
					"order"=>$i
					,"FPurchaseID"=>iconv("TIS-620","UTF-8",$val['FPurchaseID'])
					,"FRequestID"=>iconv("TIS-620","UTF-8",$val['FRequestID'])
					,"FItems"=>iconv("TIS-620","UTF-8",$val['FItems'])
					,"FAmount"=>iconv("TIS-620","UTF-8",$val['FAmount'])
					,"FUnit"=>iconv("TIS-620","UTF-8",$val['FUnit'])
					,"FPricePerAmount"=>iconv("TIS-620","UTF-8",$val['FPricePerAmount'])
					,"FPrice"=>iconv("TIS-620","UTF-8",$val['FPrice'])
					,"FComClaimID"=>iconv("TIS-620","UTF-8",$val['FComClaimID'])
					,"FBuyDate"=>iconv("TIS-620","UTF-8",$val['FBuyDate'])
					,"FReciveDate"=>iconv("TIS-620","UTF-8",$val['FReciveDate'])
					,"FDateRequest"=>iconv("TIS-620","UTF-8",$val['FDateRequest'])
					,"FReqNo"=>iconv("TIS-620","UTF-8",$val['FReqNo'])
					,"FSuplierName"=>iconv("TIS-620","UTF-8",$val['FSuplierName'])
					,"StatusIcon"=>iconv("TIS-620","UTF-8",$_status[$val['FPurchaseStatus']])
			);
	
			$rows[] = array(
					"id" => $val['FPurchaseID'],
					"cell" => $cell
			);
			$i++;
		}
		$data['end'] = $i-1;
		$data['rows'] = $rows;
		return $data;
	}//end funciton get_data_list()
	function get_claim_state($cId){
		$query = "SELECT DATE_FORMAT(FDateRequest,'%d-%b-%Y') AS openDate"
				.",DATE_FORMAT(FBuyDate,'%d-%b-%Y') AS FSendtDate"
				.",DATEDIFF(FBuyDate,FDateRequest) AS numStart"
				.",DATE_FORMAT(FReciveDate,'%d-%b-%Y') AS FReciveDate"
				.",DATEDIFF(FReciveDate,FBuyDate) AS numWork "
				."FROM mtrequest_db.tbl_purchase "
				."WHERE FPurchaseID='{$cId}'";
		$result = mysql_query($query);
		while($row=mysql_fetch_object($result)){
			$_arr[0]['date'] = $row->openDate;
			$_arr[0]['numDay'] = 0;
			$_arr[0]['type'] = '';
			$_arr[0]['label'] = iconv("TIS-620","UTF-8",'บันทึก');
	
			if(!empty($row->FSendtDate)){
					$_arr[1]['date'] = $row->FSendtDate;
					$_arr[1]['numDay'] = ($row->numStart>0)?$row->numStart:0.5;
					$_arr[1]['type'] = '';
					$_arr[1]['label'] = iconv("TIS-620","UTF-8",'วันที่สั่งซื้อ');
			}
	
			if(!empty($row->FReciveDate)){
					$_arr[2]['date'] = $row->FReciveDate;
					$_arr[2]['numDay'] = ($row->numWork>0)?$row->numWork:0.5;
					$_arr[2]['type'] = '';
					$_arr[2]['label'] = iconv("TIS-620","UTF-8",'วันที่รับของ');
			}
		}
		return $_arr;
	}/*End function get_claim_state*/
 }/*End of class Model_Claim*/
?>