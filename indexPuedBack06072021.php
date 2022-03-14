<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-874">
<title>Request</title>
<script  type="text/javascript" src="jsLib/jquery-1.8.0.min.js"></script>
<script type="text/javascript" src="jsLib/jquery-confirm/jquery.confirm.js"></script>
<script type="text/javascript" src="jsLib/tinybox.js"></script>
<script language="javascript" src="jsLib/loading/loading.js" type="text/javascript"></script>


<link href="css/display.css" rel="stylesheet" type="text/css">
<link href="css/input.css" rel="stylesheet" type="text/css">
<link href="jsLib/jquery-confirm/jquery.confirm.css" rel="stylesheet" type="text/css">
<link type="text/css" href="css/popup.css" rel="stylesheet" />
<link type="text/css" href="jsLib/navigation-menu/navigation.css" rel="stylesheet" />
<link rel="stylesheet" href="jsLib/loading/loading.css" type="text/css" />

</head>

<body scrolling="no">
<div id="display-main" class="display-main">
    <div class="global-header">
            		<div class="left-header"></div>
                    <div class="middle-header"></div>
                    <div class="right-header"></div>
                    <div id="sys-title" class="sys-title"></div>
    </div>
      
      <div id="notify2"  style="display:none;position:absolute;right:5px;top:10px;">
	    <nav>
	      <ul>
	        
	        <li id="li-new-isd"><a href="#"  onclick="changePage(32,'templates/administrator/isd-request-assign-owner.tpl.php?status=new');">New Request<span id="new-isd" class="badge">0</span></a></li>
	        <li><a href="#"  onclick="changePage(32,'templates/supports/isd-request-holder.tpl.php?status=new');">งานใหม่<span id="snew-isd" class="badge">0</span></a></li>
            
              <li id="li-return-isd"><a href="#"  onclick="changePage(32,'templates/administrator/isd-request-assign-owner.tpl.php?status=returnedit');">Return Edit<span id="return-isd" class="badge red">0</span></a></li>
	        <li><a href="#"  onclick="changePage(32,'templates/supports/isd-request-holder.tpl.php?status=returnedit');">ผู้อนุมัติตีกลับ<span id="sreturn-isd" class="badge red">0</span></a></li>
            
	        <li><a href="#"  onclick="changePage(32,'templates/supports/isd-request-holder.tpl.php?status=inprogress');">งานค้าง<span id="start-isd"  class="badge green">0</span></a></li>
              <li id="li-approve-isd"><a href="#"   onclick="changePage(32,'templates/administrator/isd-request-assign-owner.tpl.php?status=waiting');">รออนุมัติ<span id="approve-isd" class="badge yellow">0</span></a></li>
	        <li><a href="#"   onclick="changePage(32,'templates/supports/isd-request-holder.tpl.php?status=waiting');">รออนุมัติ<span id="sapprove-isd" class="badge yellow">0</span></a></li>
	        <li id="li-buy-isd"><a href="#"  onclick="changePage(32,'templates/administrator/Aisd-pr-status-list.tpl.php?status=NEW');">สั่งซื้อ<span id="buy-isd" class="badge red">0</span></a></li>
	        <li id="li-sbuy-isd"><a href="#"  onclick="changePage(32,'templates/supports/Sisd-pr-status-list.tpl.php?status=NEW');">สั่งซื้อ<span id="sbuy-isd" class="badge red">0</span></a></li>
             <li id="li-claim-isd"><a href="#"  onclick="changePage(32,'templates/administrator/Aisd-claim-status-list.tpl.php?status=NEW');">ส่งซ่อม/แคลม<span id="claim-isd" class="badge red">0</span></a></li>
	        <li id="li-sclaim-isd"><a href="#"  onclick="changePage(32,'templates/supports/Sisd-claim-status-list.tpl.php?status=NEW');">ส่งซ่อม/แคลม<span id="sclaim-isd" class="badge red">0</span></a></li>
            
            
	      </ul>
	    </nav>
	  </div>
      
      <div id="notify"  style="display:none;position:absolute;right:5px;top:10px;">
	    <nav>
	      <ul>
	        <li id="li-new"><a href="#"  onclick="changePage(18,'templates/administrator/request-assign-owner.tpl.php?status=new');">New Request<span id="new" class="badge">0</span></a></li>
	        <li><a href="#"  onclick="changePage(18,'templates/supports/request-holder.tpl.php?status=new');">งานใหม่<span id="snew" class="badge">0</span></a></li>
	        <li><a href="#"  onclick="changePage(18,'templates/supports/request-holder.tpl.php?status=inprogress');">งานค้าง<span id="start"  class="badge green">0</span></a></li>
	        <li><a href="#"   onclick="changePage(18,'templates/supports/request-holder.tpl.php?status=waiting');">รออนุมัติ<span id="approve" class="badge yellow">0</span></a></li>
	        <li id="li-buy"><a href="#"  onclick="changePage(18,'templates/administrator/purchase-status-list.tpl.php?status=NEW');">สั่งซื้อ<span id="buy" class="badge red">0</span></a></li>
	        <li id="li-sbuy"><a href="#"  onclick="changePage(18,'templates/supports/purchase-status-list.tpl.php?status=NEW');">สั่งซื้อ<span id="sbuy" class="badge red">0</span></a></li>
	      </ul>
	    </nav>
	  </div>
      
    <div id="sys-panel-main" class="sys-panel-main">
    	<div id="sys-panel-list" class="sys-panel-list">
	    		<div id="sys-panel" class="sys-panel"></div>
                
	    		<div id="sys-bottom" class="sys-bottom">
                    <div id="bottom-left" class="bottom-left"></div>
                    <div id="bottom-middle" class="bottom-middle"></div>
	    			<div id="bottom-right" class="bottom-right"></div>
	    	</div>
    	</div>
    	<div id="sys-button" class="sys-button"><a href="javascript:void(0);" onclick="showSystemList();">Systems</a></div>
    </div>
    
    
    
    <div id="global-body" class="global-body"></div>
    <div class="global-footer">
    		<div id="task-bar" class="task-bar"></div>
    		<div class="global-setting" onClick="javascript:logout();"></div>
    </div>
    <div class="login-panel" style="display:none">
    	<div id="login-form" class="login-form">
    		<div class="login-title"></div>
    		<div class="input-row login-input">
    			<div class="user-icon"></div>
    			<div class="text-input">
    				<div class="border-left"></div>
    			 	<input type="text" class="username" name="username" id="username"  style="width: 80%">
    			 	<div class="border-right"></div>
    			</div>
    		</div>
    		<div class="input-row login-input">
    			<div class="key-icon"></div>
    			<div class="text-input">
    				<div class="border-left"></div>
    			 	<input type="password" class="password" name="password" id="password" style="width: 80%">
    			 	<div class="border-right"></div>
    			</div>
    		</div>
    		<div class="input-row" id="emp-panel" style="display:none">
    			<div class="user-icon"></div>
    			<div class="text-input">
    				<div class="border-left"></div>
    			 	<input type="text" class="username" name="emp_code" id="emp-code" style="width: 80%">
    			 	<div class="border-right"></div>
    			</div>
    		</div>
    		<div class="process-task">
    			<div class="loading-process"></div>
    			<div class="loading-status">Please login first !</div>
    		</div>
    		<div class="login">
    			<a href="javascript:void(0);" onclick="javascript:login();">Login</a>
    		</div>
    	</div>
    </div>
    <div class="login-overlay" style="display:none"></div>
</div>
</body>
<script>
	
		var compId;
		$(document).ready(function() {
			 var doc_height = $(document).height();
			 var doc_width = $(document).width();
			 var log_height =  258;
			 var log_top = (doc_height-log_height)/2;
			 var sys_left = (doc_width-130)/2;
			 
			$('#display-main').height(doc_height);
			 $('#login-form').css('top',log_top+"px");
			$('#global-body').height((doc_height-82));
			 $('#sys-panel-main').css('left',sys_left+"px");
			 if(getCookie("u_id")==null){
				 $('.login-panel').show();
				 $('.login-overlay').show();
			 }else{
				 $('.login-panel').show();
				 compId = getCookie("company_id");
				 loadSys(getCookie("u_id"));
			 }
	 			//chk_runnoTax();
					if(getCookie("emp_id")=='' || getCookie("emp_id")==undefined  || getCookie("emp_id")==null ){
					   $.ajax({ 
								url: "pis_sys/controllers/user_permission_controller.php" ,
								type: "POST",
								datatype: "json",
								data: {"function":"disable_user_login"
								}
							})
							.success(function(result) { 
						  // alert(result);
								if(result>=1){
									$('.loading-process').hide();
									$('.loading-status').empty().html("Please login first !");
									$('#username').val('');
									$('#password').val('');
									$('.login-panel').show();
									$('.login-overlay').show();
									$('#sys-panel').empty();
									$('#global-body').empty();
									$('#task-bar').empty();
									$('#notify').hide();
									$('#notify2').hide();
									$('#li-sbuy').show();
								    $('#li-new').show();
								    $('#li-buy').show();
								}
							});
					}
			
			
		 });
		 





		 $(window).resize(function() {
            	var doc_height = $(document).height();
		        var doc_width = $(document).width();
				var log_height =  258;
				var log_top = (doc_height-log_height)/2;
				var sys_left = (doc_width-130)/2;
				
				$('#display-main').height(doc_height);
				$('#login-form').css('top',log_top+"px");
				$('#global-body').height((doc_height-82));
				$('#sys-panel-main').css('left',sys_left+"px");

				$('#global-body').find('div[id^=div-task-]').each(function(){
						$(this).height((doc_height-82));
				});
				$('#global-body').find('div[id^=iframe-task-]').each(function(){
					$(this).height((doc_height-82));
				});
        });







		 function setCookie(c_name,value,exdays){
				 var exdate=new Date();
				 exdate.setDate(exdate.getDate() + exdays);
				 var c_value=escape(value) + ((exdays==null) ? "" : "; expires="+exdate.toUTCString());
				 document.cookie=c_name + "=" + c_value;
		 }






		 function getCookie(c_name){
		 	var c_value = document.cookie;
			 //alert(c_value);
		 	var c_start = c_value.indexOf(" " + c_name + "=");
		 	if (c_start == -1){
		 		c_start = c_value.indexOf(c_name + "=");
		 	}
		 	if (c_start == -1){
		 		c_value = null;
		 	}else{
		 		c_start = c_value.indexOf("=", c_start) + 1;
		 		var c_end = c_value.indexOf(";", c_start);
		 		if (c_end == -1)
		 		{
		 			c_end = c_value.length;
		 		}
		 		c_value = unescape(c_value.substring(c_start,c_end));
		 	}
			return c_value;
		 }




		 

	    function login(){
			if($('#username').val()==""){
				$('.loading-status').empty().html("<font color=\"#990000\">Please input username</font>");
			}else if($('#password').val()==""){
				$('.loading-status').empty().html("<font color=\"#990000\">Please input password</font>");
			}else if(getCookie("u_id")==null){
				$('.loading-process').show();
				$('.loading-status').empty().html("Checking permission...");
				$.ajax({ 
					url: "pis_sys/controllers/user_permission_controller.php" ,
					type: "POST",
					datatype: "json",
					data: {"function":"check_user_permission",
						   "username":$("#username").val(),
						   "password":$("#password").val()
					}
				})
				.success(function(result) {
					if(result>=1){
						//$('.login-panel').hide();
						//$('.login-overlay').hide();
						compId = getCookie("company_id");
						secId = getCookie("section_id");
						if(getCookie("login_type")=="D"){
							$('.login-input').hide();
							$('.loading-process').hide();
							$('#emp-panel').show();
							$('.loading-status').empty().html("ระบุรหัสพนักงาน 5 หลักท้าย");
						}else{
						   setCookie("emp_id",getCookie("emp_id"),1);
						   loadSys(getCookie("u_id"),getCookie("emp_id"));
						}
					}else{
						$('.loading-status').empty().html("<font color=\"#990000\">ไม่พบข้อมูล</font>");
					}
				});
				
			}else if($('#emp-code').val()!=""){
				$('.loading-process').show();
				$('.loading-status').empty().html("Checking Employee Information...");
				$.ajax({ 
					url: "main/controllers/utilities_controller.php" ,
					type: "POST",
					dataType: 'json',
					data: {"function":"get_EmpInfor",
						   "emp_code":$("#emp-code").val(),
						   "comp_id":compId,
						   "sec_id":secId
					}
				})
				.success(function(json) { 
					if(json['result']==1){
						setCookie("emp_id",json['emp_id'],1);
						loadSys(getCookie("u_id"),getCookie("emp_id"));
						$("#emp-code").val('');
						$('#emp-panel').hide();
						$('.login-input').show();
					}else{
						//logout();
						//alert(setCookie("emp_id",json['emp_id'],1));
						$('.loading-status').empty().html("<font color=\"#990000\">ไม่พบข้อมูล</font>");
						if(getCookie("emp_id")=='' || getCookie("emp_id")==undefined ){
					   $.ajax({ 
								url: "pis_sys/controllers/user_permission_controller.php" ,
								type: "POST",
								datatype: "json",
								data: {"function":"disable_user_login"
								}
							})
							.success(function(result) { 
						//  alert(result);
								if(result>=1){
									window.location.reload();
									/* $('.loading-process').hide();
									$('.login-panel').show();
				 					$('.login-overlay').show();
									$('#emp-panel').hide();
									$('.loading-status').empty().html("Please login first !");
									$('#username').val('');
									$('#password').val(''); */
								}
							});
						}
						
						
					}
				});
			}
		}/*end function login*/


		function logout(){
			$.confirm({
				'title'		: 'Logout Confirmation',
				'message'	: 'You are about to logout to this system. <br />Please to sure, Do you save any thing! Continue?',
				'buttons'	: {
					'Yes'	: {
						'class'	: 'blue',
						'action': function(){
							$.ajax({ 
								url: "pis_sys/controllers/user_permission_controller.php" ,
								type: "POST",
								datatype: "json",
								data: {"function":"disable_user_login"
								}
							})
							.success(function(result) { 
								if(result>=1){ var exdate=new Date();
									document.cookie = "emp_id=; expires="+exdate;
									$('.loading-process').hide();
									$('.loading-status').empty().html("Please login first !");
									$('#username').val('');
									$('#password').val('');
									$('.login-panel').show();
									$('.login-overlay').show();
									$('#sys-panel').empty();
									$('#global-body').empty();
									$('#task-bar').empty();
									$('#notify').hide();
									$('#notify2').hide();
									$('#li-sbuy').show();
								    $('#li-new').show();
								    $('#li-buy').show();
								}
							});
							
						}
					},
					'No'	: {
						'class'	: 'gray',
						'action': function(){}	// Nothing to do in this case. You can as well omit the action property.
					}
				}
			});
		}/*End of function logout*/

		function loadSys(userId,empId){
			var system_url;
			var origin_url   = window.location.origin;
			var origin_urlEx= origin_url.split("//");
			$('.loading-status').empty().html("Loading system...");
			$.ajax({ 
				url: "pis_sys/controllers/user_permission_controller.php" ,
				type: "POST",
				datatype: "json",
				data: {"function":"load_user_system",
					   "userId":userId
				}
			})
			.success(function(result) { 
				result = jQuery.parseJSON(result);
				for(var i=0;i<result.length;i++){
					system = result[i];
					if(origin_urlEx[1]=="10.2.1.143"){
					   system_url=system['url'];
					 }else{
					   system_url=system['urlOutside'];
					 }
						//alert(system_url);
					$('<div class="sys-list"><a href="javascript:void(0);" onClick="javascript:openSys(\''+system['id']+'\',\''+system['system']+'\',\''+system_url+'\',\''+system['system']+'\',\''+empId+'\')">'+system['system']+'</a></div>').appendTo("div[id=sys-panel]");
				}
				$('.login-panel').hide();
				$('.login-overlay').hide();
			});
		}/*end function loadSys()*/

		function showSystemList(){
			if($('#sys-panel-list').css('display')=='none'){
				$('#sys-panel-list').slideDown();
			}else{
				$('#sys-panel-list').slideUp();
			}
		}/*function showSystemList()*/





		function openSys(id,system,url,name,empId){
			
			$('#notify').hide();
			$('#notify2').hide();
			var doc_height = $(document).height();
			var already = false;
			var userType = getCookie("user_type");
			$('div[id=global-body]').find('div[id=div-task-'+id+']').each(function(){
				already = true;
			});
			if(already==false){
				$('div[id=global-body]').find('div[id^=div-task-]').each(function(){
					$(this).css('display','none');
				});
				
				
				$('<div id="task-id-'+id+'" class="task-process" onClick="javascript:activeSys('+id+');" onmouseOver="javascript:onTaskHover('+id+');" onmouseOut="javascript:onTaskOut('+id+');">'+
				   '<div class="left"></div>'+
				   '<div id="task-middle-'+id+'" class="middle">'+
				   '<div id="task-close-'+id+'" onClick="javascript:closeTask('+id+');"  class="task-close" style="display:none"></div>'+
				   '<div id="task-arrow-'+id+'" class="arrow" style="display:none"></div>'+system+
				   '</div>'+
				   '<div class="right"><input type="hidden" id="sys_name_'+id+'" value="'+name+'"></div>'+
				   '</div>').appendTo('div[id=task-bar]');
				var task_width = $('#task-id-'+id+'').width();
				var task_arrow_left = (task_width-17)/2;
				$('#task-arrow-'+id).css('left',task_arrow_left+"px");

				$('<div id="div-task-'+id+'" style="width:100%;height:'+(doc_height-70)+'px;"></div>').appendTo('div[id=global-body]');

				$('<iframe />', {
				    name: 'iframe_task_'+id,
				    id:   'iframe_task_'+id,
				    height:(doc_height-70),
				    scrolling:'no',
				    allowTransparency:'true'
				}).appendTo('div[id=div-task-'+id+']');
				document.getElementById('iframe_task_'+id).src = url+'?sys_id='+id+'&user_id='+getCookie("u_id")+'&emp_id='+getCookie("emp_id")+'&compId='+compId;
				if(id==18 && userType!='E' && userType!='MNG' && userType!='AISD' && userType!='SISD') {
					if(userType=="AM"){
							$('#li-sbuy').hide();
					}else if(userType=="GM"){
						  $('#li-sbuy').hide();
						  $('#li-new').hide();
					}else{
							$('#li-new').hide();
							$('#li-buy').hide();
					}
					$('#notify').show();
					setTimeout("getRequestNotify()",1000);
				}
				
			}else{
				$('div[id=global-body]').find('div[id^=div-task-]').each(function(){
					$(this).css('display','none');
				});
				$('#div-task-'+id).css('display','');
			}
			
			showSystemList();
			$('#sys-title').empty().html(name);

		}/*End of function openSys()*/
		
		
		
		
		
		
		function onTaskHover(id){
				$('#task-arrow-'+id).show();
				$('#task-close-'+id).show();
		}
		function onTaskOut(id){
			$('#task-arrow-'+id).hide();
			$('#task-close-'+id).hide();
		}
		function closeTask(id){
			$('#iframe_task_'+id).remove();
			$('#task-id-'+id).remove();
			$('#div-task-'+id).remove();
		}
		function activeSys(id){
			$('#notify').hide();
			var userType = getCookie("user_type");
			$('div[id=global-body]').find('div[id^=div-task-]').each(function(){
				$(this).css('display','none');
			});
			$('#div-task-'+id).css('display','');
			$('#sys-title').empty().html($('#sys_name_'+id).val());
			if(id==18 && (userType!='E'  && userType!='MNG')){
				$('#notify').show();
			}
		}/*End of function activeSys()*/
		
		
		
		function getRequestNotify(){
			var userId = getCookie("u_id");
			var userType = getCookie("user_type");
			//alert(userType);
			$.ajax({ 
				url: "eqiupment-repair/controllers/request_controller.php" ,
				type: "POST",
				datatype: "json",
				data: {"function":"get_request_notify",
					   "supportID":userId
				}
			})
			.success(function(results) { 
				results = jQuery.parseJSON(results);
				if(userType=="AM"){
						$('#new').empty().html(results['new']);
						$('#snew').empty().html(results['snew']);
						$('#start').empty().html(results['start']);
						$('#approve').empty().html(results['approve']);
						$('#buy').empty().html(results['purchase']);
				}else if(userType=="GM"){
					$('#snew').empty().html(results['snew']);
					$('#start').empty().html(results['start']);
					$('#approve').empty().html(results['sapprove']);
					$('#buy').empty().html(results['purchase']);
				}else{
					$('#snew').empty().html(results['snew']);
					$('#start').empty().html(results['start']);
					$('#approve').empty().html(results['sapprove']);
					$('#sbuy').empty().html(results['purchase']);
				}
				setTimeout("getRequestNotify()",1000);
			});
		}
		
		function getIsdRequestNotify(){
		
			var userId = getCookie("u_id");
			var userType = getCookie("user_type");
				
			$.ajax({ 
				url: "isd-request/controllers/isdrequest-controller.php" ,
				type: "POST",
		datatype: "json",
				data: {"function":"get_request_notify",
					   "supportID":userId
				}
			})
			.success(function(results) { 
			//alert(results);
				results = jQuery.parseJSON(results);
		
				if(userType=="AISD"){
						$('#new-isd').empty().html(results['new']);
						$('#snew-isd').empty().html(results['snew']);
						$('#start-isd').empty().html(results['start']);
						$('#approve-isd').empty().html(results['approve']);
						$('#buy-isd').empty().html(results['purchase']);
						$('#claim-isd').empty().html(results['claim']);
						$('#return-isd').empty().html(results['return']);
					   $('#sreturn-isd').empty().html(results['sreturn']);
					  $('#sapprove-isd').empty().html(results['sapprove']);
						
				}else if(userType=="SISD"){
					$('#snew-isd').empty().html(results['snew']);
					$('#start-isd').empty().html(results['start']);
					$('#sapprove-isd').empty().html(results['sapprove']);
					$('#sbuy-isd').empty().html(results['spurchase']);
					$('#sclaim-isd').empty().html(results['sclaim']);
					$('#sreturn-isd').empty().html(results['sreturn']);
					
				}
				setTimeout("getIsdRequestNotify()",6000);
			});
		}
		
		
		function changePage(id,url){
		    var src = document.getElementById('iframe_task_'+id).src;
		    document.getElementById('iframe_task_'+id).src = src+"&url="+url;
		}
		function show_notify2(userType){
					if(userType=="SISD" || userType=="AISD"){
						if(userType=="AISD"){
								$('#li-buy-isd').hide();
								$('#li-claim-isd').hide();
								$('#li-return-isd').hide();
								$('#li-approve-isd').hide();
						}else if(userType=="SISD"){
								$('#li-approve-isd').hide();
								$('#li-new-isd').hide();
								$('#li-buy-isd').hide();
								$('#li-claim-isd').hide();
								$('#li-return-isd').hide();
						}
						$('#notify2').show();
						setTimeout("getIsdRequestNotify()",1000);
					}
		}
		function hide_notify2(){
		$('#notify2').hide();
		}
		
</script>
</html>
