<?php
function renderLoginBox($request){
	if(!isset($request["user"])){
		$request["user"]="";
	}
	
	if(!isset($request["password"])){
		$request["password"]="";
	}
	
	if(!isset($request["error"])){
		$request["error"]="";
	}
	
	echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="css/base.css" type="text/css" />
<title>Untitled Document</title>
</head>

<body>
<div class="wrapper">

<div id="cabezote">
	<img src="images/logo.png" width="127" height="73" style="margin:10px 0 0 10px"/>
</div><!-- fin cabezote -->


<div id="contenedor-global">
    
    	<div id="content-login">
        
        	<div id="login-left">
                <div class="content-login-left">
                	<img src="images/icon-login.jpg" width="101" height="123" alt""/>
                	<p class="title-login">Login</p>
                </div>
            </div>
            
            <div id="login-right">
            <form name="dwk_login" method="post" action="index.php">
            	<table width="426" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="36"><img src="images/icon-user.jpg" width="30" height="35" /></td>
                    <td width="101"><p class="txt-login">Username:</p></td>
                    <td width="289"><input type="text" id="user" name="user" value="'.$request["user"].'"></td>
                  </tr>
                  <tr>
                    <td colspan="3"><div class="linea-punteada"></div></td>
                  </tr>
                  <tr>
                    <td width="36"><img src="images/icon-password.jpg" width="36" height="31" /></td>
                    <td width="101"><p class="txt-login">Password:</p></td>
                    <td width="289"><input type="password" id="password" name="password" value="'.$request["password"].'"></td>
                  </tr>
                  <tr>
                    <td colspan="3"><div class="linea-punteada"></div></td>
                  </tr> 
                </table>
                <table width="426" border="0" cellspacing="0" cellpadding="0">
                	<tr>
                    <td width="250"> <input type="hidden" name="action" value="login">'.$request["error"].'</td>
                    <td width="176"><input type="submit" name="Submit" value="Submit"></td>
                 	</tr>
                </table>

            </form>
          </div>
        
  </div><!-- content-login -->
    
<div class="push"></div>
</div> <!-- fin contenedor-global -->

</div>
        
        
        <div class="footer">
           <div id="copy">
           		<div id="copy-content">
                    <p>Â© 2008 - 2011 Dewak S.A. | Privacy
               			www.dewak.com
                	</p>

				</div>
	</div> <!-- fin copy -->
        </div>
         
		
</body>
</html>';
	
	
	
}

function renderDashboard(){
	require_once 'dwk_functions.php';
	
	echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" >
<head>

	<meta http-equiv="Content-type" content="text/html; charset=UTF-8" />
	<title>WallBoard</title>

	<meta name="author" content="Dewak S.A." /> 
	<meta name="title" content="WallBoard" />
	<meta name="description" content="" />
	<meta name="keywords" content="" />

	<link rel="stylesheet" href="css/base.css" type="text/css" />
    

	<link rel="shortcut icon" href="favicon.ico" />
	<link rel="icon" href="favicon.ico" type="image/x-icon" />
	
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.min.js"></script>
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.10/jquery-ui.min.js"></script>
	<script type="text/javascript" src="JSCharts/jscharts.js"></script>
	
</head>
<body>

<div class="wrapper">
	
	<div id="wallboard">
		
    	<div id="cabezote">
			<img src="images/logo.png" width="127" height="73" style="margin:10px 0 0 10px"/>    
            
		<div id="taskbar">
			
			<div class="menu">
				<div class="menu-item settings-btn">
					<a href="javascript:;">Settings</a>
				
					<div class="menu-item-content"> <div class="menu-item-content-final">';
	$arrayDpts=getDepartments($_SESSION["staffid"]);
	$arryStatus=getTicketStatuses();
	$settings=getSettings($_SESSION["staffid"]);
	echo renderSettings($settings, $arrayDpts, $arryStatus);					
									
	echo '			</div></div>
				</div>
				
				<div class="menu-item logout-btn">
				
					<a href="index.php?action=logout">Logout</a>
				</div>
				
			</div>
            
          </div>
          
         </div><!-- fin cabezote -->
          
         </div>
         
         
       
	
		<div>
		
		<div id="portlets">
   
          <div class="columns-2">
            
            <div class="column first">
             	<div class="portlet">
             		 <div class="header"><p class="title">Tickets X Department</p></div>
             		 <div id="widgetDepartments" class="content">Loading Module ...</div>
             	</div>
             
                 <div class="portlet">
                      <div class="header"><p class="title">New Tickets</p></div>
                      <div id="widgetNewTickets" class="content">Loading Module ...
                      
                      </div>
                 </div>
             
           </div>
            
            <div class="column last">
             
                 <div class="portlet">
                      <div class="header"><p class="title">Tickets X Status</p></div>
                      <div id="widgetStatuses" class="content">Loading Module ...</div>
                 </div>
                 
                 <div class="portlet">
                  	<div class="header"><p class="title">Overdue Tickets</p></div>

                      <div id="widgetOverDueTickets" class="content">Loading Module ...</div>
                 </div>
             
            </div>
          </div>
			
		<div class="clr"></div>
		</div>
		
	</div>

	<script type="text/javascript" src="js/wallboard.js"></script>
    <script language="JavaScript" src="dwk_ajax.js"></script>
	
	<script language="JavaScript">
        showWidgetStatuses();
        showWidgetDepartments();
        showWidgetNewTickets();
        showWidgetOverDueTickets();
  
        
       window.onresize = function() {
			showWidgetStatuses();
	        showWidgetDepartments();
	        showWidgetNewTickets();
	        showWidgetOverDueTickets();
		} 
        
    function runCron(){
		showWidgetStatuses();
        showWidgetDepartments();
        showWidgetNewTickets();
        showWidgetOverDueTickets();
	}';
	
	
	if(isset($settings["refresh"])){
		if($settings["refresh"]=="30"){
			echo 'setInterval("runCron()", 30000);';
		}else if($settings["refresh"]=="60"){
			echo 'setInterval("runCron()", 60000);';
		}else if($settings["refresh"]=="120"){
			echo 'setInterval("runCron()", 120000);';
		}
	}
	
	
  
    echo '</script>
    
<div class="push"></div>
</div>

</body>
</html>';
	
	
	
	
}



function renderSettings($settings, $departments, $statuses){
	echo '<form name="form1" method="post" action="index.php">
			<table class="table-style-1">
				<tr>
					<td><label for="field-1">Departments</label></td>
					<td>
						 <select id="departments[]" name="departments[]" size="6" multiple="multiple"  style="width: 90%">';
		
							foreach ($departments as $key => $value){
								if(in_array($key, $settings["departments"])){
									echo "<option id='".$key."' value='".$key."' selected>".$value["title"]."</option>";
								}else{
									echo "<option id='".$key."' value='".$key."'>".$value["title"]."</option>";
								}
							}
		  echo 			'</select>
	  				</td>
	  			</tr>
	  			
	  			<tr>
					<td><label for="field-2">Statuses</label></td>
					<td>
						 <select id="statuses[]" name="statuses[]" size="6" multiple="multiple" style="width: 90%">';
		
							foreach ($statuses as $key => $value){
									if(in_array($key, $settings["statuses"])){
										echo "<option id='".$key."' value='".$key."' selected>".$value["title"]."</option>";
									}else{
										echo "<option id='".$key."' value='".$key."'>".$value["title"]."</option>";
									}
								}
		  echo 			'</select>
	  				</td>
	  			</tr>
	  			
	  	<tr>
					<td><label for="field-3">Auto Refresh Grid</label></td>
					<td>
						 <select id="refresh" name="refresh" style="width: 90%">';
						  	if($settings["refresh"]=="30"){
						  		echo "<option value='30' selected>Every 30 Seconds</option>";
						  	}else{
						  		echo "<option value='30'>Every 30 Seconds</option>";
						  	}
						  	
							if($settings["refresh"]=="60"){
						  		echo "<option value='60' selected>Every 60 Seconds</option>";
						  	}else{
						  		echo "<option value='60'>Every 60 Seconds</option>";
						  	}
						  	
							if($settings["refresh"]=="120"){
						  		echo "<option value='120' selected>Every 2 minutes</option>";
						  	}else{
						  		echo "<option value='120'>Every 2 minutes</option>";
						  	}
					  	
		  echo 			'</select>
	  				</td>
	  			</tr>	

	  	<tr>
			<td width="30%"><label for="field-4">New tickets</label></td>
			<td width="70%"><input type="newtickets" name="newtickets" value="'.$settings["newtickets"].'" id="newtickets" style="width: 90%" /></td>
		</tr>	

		<tr>
			<td width="30%"><label for="field-5">Overdue tickets</label></td>
			<td width="70%"><input type="overduetickets" name="overduetickets" value="'.$settings["overduetickets"].'" id="overduetickets" style="width: 90%" /></td>
		</tr>	
	  			
	  			
			<tr>
				<td colspan="2" align="center"><input type="submit" value="Save" class="button" />
				<input type="hidden" name="action" value="saveSettings">
				</td>
			</tr>	
		</table>
	</form>';
	
}

function renderStatuses($data){
	
	$dwk_data= "new Array(";
	$count=0;
	
	foreach($data as $key => $value){
		if($count!=0){
			$dwk_data.=",";
		}
	 	$dwk_data.="['".$value["title"]."', ".$value["counttickets"]."]";
	 	$count++;
	 }
	$dwk_data.=")";
	
	echo $dwk_data;
	
	
   /* echo '<table width="200" border="1">
	  <tr>
	    <td>Status</td>
	    <td>Cantidad</td>
	  </tr>';
	  
	  foreach($data as $key => $value){
        echo '<tr>
    	    <td>'.$value["title"].'</td>
    	    <td>'.$value["counttickets"].'</td>
    	  </tr>';
    }
    
    echo '</table>'; */

}

function renderDepartments($data){
	
	$dwk_data= "new Array(";
	$count=0;
	
	foreach($data as $key => $value){
		if($count!=0){
			$dwk_data.=",";
		}
	 	$dwk_data.="['".$value["title"]."', ".$value["counttickets"]."]";
	 	$count++;
	 }
	$dwk_data.=")";
	
	echo $dwk_data;
	
	
}

function renderNewTickets($data){
    echo '<table width="100%" border="0" cellspacing="0" cellpadding="0">
          	<tr class="tabla-title">
               <td class="borde-tablas padding-tablas">Ticket ID</td>
               <td class="padding-tablas">Subject</td>
            </tr>';
    
     $id=1;
     foreach($data as $key => $value){
	     if(isSet($value["ticketid"])){
	     	if($id==1){
	     		echo '  <tr class="tabla1">
                             <td class="borde-tablas padding-tablas">'.$value["ticketid"].'</td>
                              <td class="padding-tablas">'.$value["subject"].'</td>
                         </tr>';
	     		
	     
	     		$id=2;
	     	}else{
	     		echo '  <tr class="tabla2">
                             <td class="borde-tablas padding-tablas">'.$value["ticketid"].'</td>
                              <td class="padding-tablas">'.$value["subject"].'</td>
                         </tr>';
	     	
	     		$id=1;
	     	}
	     	
	     	
	     }
     }
    
   echo  '</table>';
    
    
}

function renderOverDueTickets($data){
    echo '<table width="100%" border="0" cellspacing="0" cellpadding="0">
          	<tr class="tabla-title">
               <td class="borde-tablas padding-tablas">Ticket ID</td>
               <td class="padding-tablas">Subject</td>
            </tr>';
    
     $id=1;
     foreach($data as $key => $value){
	     if(isSet($value["ticketid"])){
	     	if($id==1){
	     		echo '  <tr class="tabla1">
                             <td class="borde-tablas padding-tablas">'.$value["ticketid"].'</td>
                              <td class="padding-tablas">'.$value["subject"].'</td>
                         </tr>';
	     		$id=2;
	     	}else{
	     		echo '  <tr class="tabla2">
                             <td class="borde-tablas padding-tablas">'.$value["ticketid"].'</td>
                              <td class="padding-tablas">'.$value["subject"].'</td>
                         </tr>';
	     		$id=1;
	     	}
	     }
     }
    
   echo  '</table>';
    
    
    
    
}
