<?php
/* dewak - Building Software
/* Source Copyright 2011 Dewak S.A.
/* Unauthorized reproduction is not allowed
/* ------------------------------------------------
/* $Date: 2011-04-11 18:40:05 $
/* $Author: diego $
*/

//OPEN_CONFIGURATION FILE

$archivo = 'dwk.wallboard.key';

$fp = fopen("./../../../../__swift/files/".$archivo, "r");
$contents = fread($fp, filesize("./../../../../__swift/files/".$archivo));
fclose($fp); 

$iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
$iv = mcrypt_create_iv($iv_size, "1");

$decr2=trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, "DewakFreeProduct", base64_decode($contents), MCRYPT_MODE_ECB, $iv)); 
$decr2=base64_decode($decr2);
$DB= unserialize($decr2);

define("TABLE_PREFIX", $DB["TABLE_PREFIX"]);

require_once './dwk_dbManager.php';

global $_dbCore;

$_dbCore = new dbCore($DB["DB_HOSTNAME"], $DB["DB_USERNAME"], $DB["DB_PASSWORD"], $DB["DB_NAME"], "mysql", false);


function getApikey(){
	global $_dbCore;
	$array=$_dbCore->queryFetch("select * from ".TABLE_PREFIX."settings where vkey='apikey'");
	if (!empty($array["data"])){
		return $array["data"];
	}else{
		return "N/A";
	}
}

function getApisecretkey(){
	global $_dbCore;
	$array=$_dbCore->queryFetch("select * from ".TABLE_PREFIX."settings where vkey='secretkey'");
	if (!empty($array["data"])){
		return $array["data"];
	}else{
		return "N/A";
	}
}

function getKayakoURL(){
	global $_dbCore;
	$array=$_dbCore->queryFetch("select * from ".TABLE_PREFIX."settings where vkey='general_producturl'");
	if (!empty($array["data"])){
		return $array["data"];
	}else{
		return "N/A";
	}
}



function autenticateStaff($user, $password){
	global $_dbCore;
	$return=-1;
	$dwk_pass=sha1($password);
	$sql="select * from ".TABLE_PREFIX."staff where username='".$_dbCore->escape($user)."' and staffpassword='".$_dbCore->escape($dwk_pass)."'";
	$_dbCore->query($sql);
	while($_dbCore->nextRecord()){
		$return=$_dbCore->Record["staffid"];
	}
	return $return;
}


function getStaff($staffid){
	$args["staffid"]=1;
	$staffxml=restApiCall($args, "Base", "Staff", "GET");
	return $staffxml;
}


function getDepartments($staffid){
	//$dptarray=restApiCall("NULL", "Base", "Department", "GET");
	global $_dbCore;
	

	$sql="select groupassigns from ".TABLE_PREFIX."staff where staffid=".$staffid;
	$_dbCore->query($sql);
	while($_dbCore->nextRecord()){
		$dwk_groupassigns=$_dbCore->Record["groupassigns"];
	}
	
	
	if($dwk_groupassigns==0){
		
		$_dbCore->query("select * from ".TABLE_PREFIX."staffassigns where staffid=".$staffid);
		while($_dbCore->nextRecord()){
			$depid[]=$_dbCore->Record["departmentid"];
		}
	}else{
		$sql="select staffgroupid from ".TABLE_PREFIX."staff where staffid=".$staffid;
		$_dbCore->query($sql);
		while($_dbCore->nextRecord()){
			$groupid=$_dbCore->Record["staffgroupid"];
		}
		
		$sql="select * from ".TABLE_PREFIX."groupassigns where staffgroupid=".$groupid;
		$_dbCore->query($sql);
		while($_dbCore->nextRecord()){
			$depid[]=$_dbCore->Record["departmentid"];
		}
	}
	
	
	$_dbCore->query("select * from ".TABLE_PREFIX."departments where departmentid IN(".buildIN($depid)." ) and departmentmodule='tickets' order by title asc");
	while($_dbCore->nextRecord()){
		$dptarray[$_dbCore->Record["departmentid"]]=$_dbCore->Record;
	}
	
	return $dptarray;
}

function getTicketStatuses(){
	//$statusarray=restApiCall("NULL", "Tickets", "TicketStatus", "GET");
	global $_dbCore;
	$_dbCore->query("select * from ".TABLE_PREFIX."ticketstatus order by title asc");
	while($_dbCore->nextRecord()){
		$statusarray[$_dbCore->Record["ticketstatusid"]]=$_dbCore->Record;
	}
	return $statusarray;
}

function getSettings($staffid){
	global $_dbCore;
	$settings=array();
	$_dbCore->query("select * from ".TABLE_PREFIX."_dwk_wallboardsettings where staffid='".$staffid."'");
	while($_dbCore->nextRecord()){
		$settings[$_dbCore->Record["setting"]]=$_dbCore->Record["value"];
	}
	
	if(isSet($settings["departments"])){
		$settings["departments"]=unserialize($settings["departments"]);
	}else{
		$settings["departments"]=array();
	}
	
	if(isSet($settings["statuses"])){
		$settings["statuses"]=unserialize($settings["statuses"]);
	}else{
		$settings["statuses"]=array();
	}
	
	return $settings;
}


function restApiCall($args, $module, $controller, $action){
	require_once './class.xmltoarray.php';
	$apiKey = getApikey();
	
    $secretKey = getApisecretkey();
    // Generates a random string of ten digits
	$salt = mt_rand();
	
   	// Computes the signature by hashing the salt with the secret key as the key
   	$signature = hash_hmac('sha256', $salt, $secretKey, true);

   	// base64 encode...
   	$encodedSignature = base64_encode($signature);

   	// urlencode...
   	$encodedSignature = urlencode($encodedSignature);
	
   	$url=getKayakoURL();
   	
   	if(args==="NULL"){
   		$argtxt="";
   	}else if(count($args)==1){
   		foreach($args as $key => $value){
   			$argtxt=$value."&";
   		}
   	}else{
   		$int=0;
   		foreach($args as $key => $value){
   			if($int==0){
   				$argtxt.=$value."&";
   			}else{
   				$argtxt.=$key."=".$value."&";
   			}
   			$int++;
   		}
   	}
   	
	$peticion=$url."api/index.php?/".$module."/".$controller."/".$action."/&".$argtxt."apikey=".getApikey()."&salt=".$salt."&signature=".$encodedSignature;
	
	$file = file_get_contents($peticion, true);
   	
	$xmlObj    = new XmlToArray($file);
	//Creating Array
	$arrayData = $xmlObj->createArray();
	 
	return $arrayData;
}


function saveSettings($request, $staffid){
	global $_dbCore;
	//encoding settings
	
	$request["departments"]=serialize($request["departments"]);
	$request["statuses"]=serialize($request["statuses"]);
	
	//DEPARTMENTS
	$sql="delete from ".TABLE_PREFIX."_dwk_wallboardsettings where setting='departments' and staffid='".$staffid."'";
	$_dbCore->query($sql);
	
	$sql="insert into ".TABLE_PREFIX."_dwk_wallboardsettings(setting, staffid, value)values('departments','".$staffid."','".$_dbCore->escape($request["departments"])."')";
	$_dbCore->query($sql);
	
	//STATUSES
	$sql="delete from ".TABLE_PREFIX."_dwk_wallboardsettings where setting='statuses' and staffid='".$staffid."'";
	$_dbCore->query($sql);
	
	$sql="insert into ".TABLE_PREFIX."_dwk_wallboardsettings(setting, staffid, value)values('statuses','".$staffid."','".$_dbCore->escape($request["statuses"])."')";
	$_dbCore->query($sql);
	
	//REFRESH
	$sql="delete from ".TABLE_PREFIX."_dwk_wallboardsettings where setting='refresh' and staffid='".$staffid."'";
	$_dbCore->query($sql);
	
	$sql="insert into ".TABLE_PREFIX."_dwk_wallboardsettings(setting, staffid, value)values('refresh','".$staffid."','".$_dbCore->escape($request["refresh"])."')";
	$_dbCore->query($sql);
	
	//NEW TICKETS
	$sql="delete from ".TABLE_PREFIX."_dwk_wallboardsettings where setting='newtickets' and staffid='".$staffid."'";
	$_dbCore->query($sql);
	
	$sql="insert into ".TABLE_PREFIX."_dwk_wallboardsettings(setting, staffid, value)values('newtickets','".$staffid."','".$_dbCore->escape($request["newtickets"])."')";
	$_dbCore->query($sql);
	
	//OVERDUE TICKETS
	$sql="delete from ".TABLE_PREFIX."_dwk_wallboardsettings where setting='overduetickets' and staffid='".$staffid."'";
	$_dbCore->query($sql);
	
	$sql="insert into ".TABLE_PREFIX."_dwk_wallboardsettings(setting, staffid, value)values('overduetickets','".$staffid."','".$_dbCore->escape($request["overduetickets"])."')";
	$_dbCore->query($sql);
	
}


function getCountTicketsPerStatus($staffid){
	error_reporting(E_ALL);
	
	global $_dbCore;
	$settings=getSettings($staffid);

	//getting Ticketstatuses 
	if(count($settings["statuses"])){
		$sql="select * from ".TABLE_PREFIX."ticketstatus where ticketstatusid IN (".buildIN($settings["statuses"]).")";

	}else{
		$sql="select * from ".TABLE_PREFIX."ticketstatus";
	}

	$_dbCore->query($sql);
	while($_dbCore->nextRecord()){
		$return[$_dbCore->Record["ticketstatusid"]]=$_dbCore->Record;
		$return[$_dbCore->Record["ticketstatusid"]]["counttickets"]=0;
	}

	$where="";
	if(count($settings["departments"])){
		$where="where departmentid IN (".buildIN($settings["departments"]).")";
	}
	
	
	if(count($settings["statuses"])){
		if($where!=""){
			$where.=" and ticketstatusid IN (".buildIN($settings["statuses"]).")";
		}else{
			$where="where ticketstatusid IN (".buildIN($settings["statuses"]).")";
		}
	}

	$sql="select ticketstatusid from ".TABLE_PREFIX."tickets ".$where;
	$_dbCore->query($sql);
	while($_dbCore->nextRecord()){
		$return[$_dbCore->Record["ticketstatusid"]]["counttickets"]++;
	}
	
	return $return;
}

function getCountTicketsPerDepartment($staffid){
	error_reporting(E_ALL);
	
	global $_dbCore;
	$settings=getSettings($staffid);

	//getting TicketDepartments
	if(count($settings["departments"])){
		$sql="select * from ".TABLE_PREFIX."departments where departmentid IN (".buildIN($settings["departments"]).")";
	}else{
		$sql="select * from ".TABLE_PREFIX."departments";
	}

	$_dbCore->query($sql);
	while($_dbCore->nextRecord()){
		$return[$_dbCore->Record["departmentid"]]=$_dbCore->Record;
		$return[$_dbCore->Record["departmentid"]]["counttickets"]=0;
	}

	$where="";
	if(count($settings["departments"])){
		$where="where departmentid IN (".buildIN($settings["departments"]).")";
	}
	
	if(count($settings["statuses"])){
		if($where!=""){
			$where.=" and ticketstatusid IN (".buildIN($settings["statuses"]).")";
		}else{
			$where="where ticketstatusid IN (".buildIN($settings["statuses"]).")";
		}
	}

	$sql="select departmentid from ".TABLE_PREFIX."tickets ".$where;
	
	$_dbCore->query($sql);
	while($_dbCore->nextRecord()){
		$return[$_dbCore->Record["departmentid"]]["counttickets"]++;
	}
	
	return $return;
}


function getNewTickets($staffid){
	error_reporting(E_ALL);
	
	global $_dbCore;
	$settings=getSettings($staffid);

	if(isSet($settings["departments"]) && isSet($settings["statuses"])){
	
	//getting TicketDepartments
		if(count($settings["departments"])){
			$sql="select * from ".TABLE_PREFIX."departments where departmentid IN (".buildIN($settings["departments"]).")";
		}else{
			$sql="select * from ".TABLE_PREFIX."departments";
		}
	
		$_dbCore->query($sql);
		while($_dbCore->nextRecord()){
			$return[$_dbCore->Record["departmentid"]]=$_dbCore->Record;
			$return[$_dbCore->Record["departmentid"]]["counttickets"]=0;
		}
	
		$where="";
		if(count($settings["departments"])){
			$where="where departmentid IN (".buildIN($settings["departments"]).")";
		}
		
		if(count($settings["statuses"])){
			if($where!=""){
				$where.=" and ticketstatusid IN (".buildIN($settings["statuses"]).")";
			}else{
				$where="where ticketstatusid IN (".buildIN($settings["statuses"]).")";
			}
		}
		
		$sql="select * from ".TABLE_PREFIX."tickets ".$where." order by dateline desc Limit ".$settings["newtickets"];
		
		$_dbCore->query($sql);
		while($_dbCore->nextRecord()){
			$return[$_dbCore->Record["ticketid"]]=$_dbCore->Record;
		}
	}else{
		$return["NULL"]["subject"]="Please check settings tab";
	}
	
	return $return;
}


function getOverDueTickets($staffid){
 	
	global $_dbCore;
	$settings=getSettings($staffid);

	//getting TicketDepartments
	
	if(isSet($settings["departments"]) && isSet($settings["statuses"])){
	
		if(count($settings["departments"])){
			$sql="select * from ".TABLE_PREFIX."departments where departmentid IN (".buildIN($settings["departments"]).")";
		}else{
			$sql="select * from ".TABLE_PREFIX."departments";
		}
	
		$_dbCore->query($sql);
		while($_dbCore->nextRecord()){
			$return[$_dbCore->Record["departmentid"]]=$_dbCore->Record;
			$return[$_dbCore->Record["departmentid"]]["counttickets"]=0;
		}
	
		$where="";
		if(count($settings["departments"])){
			$where="where departmentid IN (".buildIN($settings["departments"]).")";
		}
		
		if(count($settings["statuses"])){
			if($where!=""){
				$where.=" and ticketstatusid IN (".buildIN($settings["statuses"]).")";
			}else{
				$where="where ticketstatusid IN (".buildIN($settings["statuses"]).")";
			}
		}
		
		$sql="select * from ".TABLE_PREFIX."tickets ".$where." order by duetime asc Limit ".$settings["overduetickets"];
		
		$_dbCore->query($sql);
		while($_dbCore->nextRecord()){
			$return[$_dbCore->Record["ticketid"]]=$_dbCore->Record;
		}
	}else{
		$return["NULL"]["subject"]="Please check settings tab";
	}
	return $return;
}


?>