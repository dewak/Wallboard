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

if($_GET['request']=='loadSettings'){
	$arrayDpts=getDepartments();
	$arryStatus=getTicketStatuses();
	$settings=getSettings($_SESSION["staffid"]);
	echo renderSettings($settings, $arrayDpts, $arryStatus);
	
}else if ($_GET['request']=='showWidgetStatuses'){
  $data=getCountTicketsPerStatus($_SESSION["staffid"]);
  echo renderStatuses($data);

} else if ($_GET['request']=='showWidgetDepartments'){
  $data=getCountTicketsPerDepartment($_SESSION["staffid"]);
  echo renderDepartments($data);

}else if ($_GET['request']=='showWidgetNewTickets'){
  $data=getNewTickets($_SESSION["staffid"]);
  echo renderNewTickets($data);

}else if ($_GET['request']=='showWidgetOverDueTickets'){
  $data=getOverDueTickets($_SESSION["staffid"]);
   echo renderOverDueTickets($data);
}

?>


















