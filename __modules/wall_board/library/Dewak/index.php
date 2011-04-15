<?php
/* dewak - Building Software
/* Source Copyright 2011 Dewak S.A.
/* Unauthorized reproduction is not allowed
/* ------------------------------------------------
/* $Date: 2011-04-11 18:40:04 $
/* $Author: diego $
*/
session_start(); 
require_once './dwk_functions.php';
require_once './dwk_renderfunctions.php';

if (!isset($_SESSION["staffid"])){ 
	if(isset($_REQUEST["action"])){
		if($_REQUEST["action"]=="login"){
			$staffid=autenticateStaff($_REQUEST["user"], $_REQUEST["password"]);
			if($staffid!=-1){
				$_SESSION['staffid'] = $staffid; 
				renderDashboard($_REQUEST);
			}else{
				$_REQUEST["error"]="Invalid Username or Password";
				renderLoginBox($_REQUEST);
			}
		}else{
			renderLoginBox($_REQUEST);
		}
	}else{
		renderLoginBox($_REQUEST);
	}
}else{
	if(isset($_REQUEST["action"])){
		if($_REQUEST["action"]=="logout"){
			$_REQUEST["error"]="Logged out successfully";
			unset($_SESSION["staffid"]);
			renderLoginBox($_REQUEST);
		}else if($_REQUEST["action"]=="saveSettings"){
			saveSettings($_REQUEST, $_SESSION["staffid"]);
			renderDashboard($_REQUEST);
		}else{
			renderDashboard($_REQUEST);
		}
	}else{
		renderDashboard($_REQUEST);
	}	
}


?>