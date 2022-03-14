<!DOCTYPE html>
<?php 
	include '../lib/db_config.php';
	include '../main/modules/Model_Utilities.php';
	include '../pis_sys/models/user_model.php';
	
	$objMD = new Model_Utilities();
	$userMD = new Model_User();
	$menu = $objMD->genMenu2Show($_REQUEST['sys_id'], 0, 0,$_REQUEST['user_id'],$_REQUEST['emp_id'],$_REQUEST['compId']);
	$userData = $userMD->get_data($_REQUEST['user_id']);
	$url = $_REQUEST["url"];
	if(empty($url)){
		if($userData['user_type']=="E"){
			$url = 'templates/general_user/general-index.php?u_id='.$_REQUEST['user_id'].'&e_id='.$_REQUEST['emp_id'].'&compId='.$_REQUEST['compId'];
		}
		else if($userData['user_type']=="AM"){
			$url = 'templates/administrator/administrator-index.php?u_id='.$_REQUEST['user_id'].'&e_id='.$_REQUEST['emp_id'].'&compId='.$_REQUEST['compId'];
		}
		else if($userData['user_type']=="M"){
			$url = 'templates/supports/support-index.php?u_id='.$_REQUEST['user_id'].'&e_id='.$_REQUEST['emp_id'].'&compId='.$_REQUEST['compId'];
		}
	}else{
		$url .= '&u_id='.$_REQUEST['user_id'].'&e_id='.$_REQUEST['emp_id'].'&compId='.$_REQUEST['compId'];
	}
	
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-874">
<title>Request</title>
<link type="text/css" href="../jsLib/jquery-menu/menu.css" rel="stylesheet">
<link href="../css/sys_controll.css" rel="stylesheet" type="text/css">
<script  type="text/javascript" src="../jsLib/jquery-1.8.0.min.js"></script>
<script  type="text/javascript" src="../jsLib/jquery-menu/menu.js"></script>
<script type="text/javascript" src="../jsLib/tinybox.js"></script>
<script language="javascript" src="../jsLib/loading/loading.js" type="text/javascript"></script>

<link type="text/css" href="../css/popup.css" rel="stylesheet" />
<link rel="stylesheet" href="../jsLib/loading/loading.css" type="text/css" />
</head>

<body class="landing" bgcolor="f5f5f5"  onLoad="_body_onload();" scrolling="no">
<style type="text/css">
* { margin:0;
    padding:0;
}
div#menu { margin:5px 0 auto; }
div#copyright {
    font:11px 'Trebuchet MS';
    color:#222;
    text-indent:30px;
    padding:40px 0 0 0;
    display:none;
}
div#copyright a { color:#aaa; }
div#copyright a:hover { color:#222; }
</style>

<div id="menu">
    <ul class="menu">
		<?php print($menu);?>
    </ul>
</div>
<iframe id="mainbody" name="mainbody" width="100%" frameborder="0" scrolling="no" src="<?php print($url);?>"></iframe>

<div id="copyright">Copyright &copy; 2013 <a href="http://apycom.com/">Apycom jQuery Menus</a></div>
</body>
</html>
<script>
	$(document).ready(function (){
		var height = $(document).height();
		var main_body_height = height-50;
		$("#mainbody").height(main_body_height);
	});

	function openMenu(url){
		document.getElementById('mainbody').src = url;
	}/*End function openMenu*/

	function closePopup(){
		TINY.box.hide();
	}
	function addCloseSendTo(){
			alert('^_^');
	}
	function changePage(type){
		if(type=='')mainbody.changePage();
		else mainbody.changePage(type);
	}
</script>