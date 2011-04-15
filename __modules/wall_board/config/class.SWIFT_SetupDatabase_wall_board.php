<?php
/* Dewak - Building Software
/* Source Copyright 2011 Dewak S.A.
/* Unauthorized reproduction is not allowed
/* ------------------------------------------------
/* $Date: 2011-04-11 18:40:08 $
/* $Author: diego $
*/

define("MODULE_WALL_BOARD","wall_board");

/**
 * The Main Installer
 * 
 * @author Dewak S.A.
 */
class SWIFT_SetupDatabase_wall_board extends SWIFT_SetupDatabase
{
	// Core Constants
	const PAGE_COUNT = 1;
		
	/**
	 * Constructor
	 *
	 * @author Dewak S.A.
	 * @return bool "true" on Success, "false" otherwise
	 */
	public function __construct()
	{
		parent::__construct(MODULE_WALL_BOARD);

		return true;
	}

	/**
	 * Destructor
	 *
	 * @author Dewak S.A.
	 * @return bool "true" on Success, "false" otherwise
	 */
	public function __destruct()
	{
		parent::__destruct();

		return true;
	}

	/**
	 * Loads the table into the container
	 * 
	 * @author Dewak S.A.
	 * @return bool "true" on Success, "false" otherwise
	 */
	public function LoadTables()
	{		
		return true;
	}

	/**
	 * Get the Page Count for Execution
	 * 
	 * @author Dewak S.A.
	 * @return bool "true" on Success, "false" otherwise
	 */
	public function GetPageCount()
	{
		return self::PAGE_COUNT;
	}

	/**
	 * Function that does the heavy execution
	 * 
	 * @author Dewak S.A.
	 * @param int $_pageIndex The Page Index
	 * @return bool "true" on Success, "false" otherwise
	 */
	public function Install($_pageIndex){
		$dwk_db["DB_HOSTNAME"]=DB_HOSTNAME;
		$dwk_db["DB_USERNAME"]=DB_USERNAME;
		$dwk_db["DB_PASSWORD"]=DB_PASSWORD;
		$dwk_db["DB_NAME"]=DB_NAME;
		$dwk_db["DB_PORT"]=DB_PORT;
		$dwk_db["TABLE_PREFIX"]=TABLE_PREFIX;
		
		$str_serialized= serialize($dwk_db);
    	$text=base64_encode($str_serialized);
    	
    	$iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
        $iv = mcrypt_create_iv($iv_size, "1");
    	$encr1=trim(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, "DewakFreeProduct", $text, MCRYPT_MODE_ECB,$iv))); 

		$archivo = 'dwk.wallboard.key';
		$fp = fopen('./' . SWIFT_BASEDIRECTORY . '/' . SWIFT_FILESDIRECTORY . '/'.$archivo, "w");
		$write = fputs($fp, $encr1);
		fclose($fp);
		
		$sql="CREATE TABLE `". TABLE_PREFIX ."_dwk_wallboardsettings` (
										`id` int(11) NOT NULL auto_increment,
  										`setting` varchar(50) NOT NULL default '0',
  										`staffid` int(10) NOT NULL default '0',
  										`value`  TEXT NOT NULL default '',
  										PRIMARY KEY  (`id`));";
		$this->Database->Query($sql);
		
		parent::Install($_pageIndex);

		return true;
	}

	/**
	 * Uninstalls the module
	 * 
	 * @author Dewak S.A.
	 * @return bool "true" on Success, "false" otherwise
	 */
	public function Uninstall()
	{		
		$sql="DROP `". TABLE_PREFIX ."_dwk_wallboardsettings`";
		$this->Database->Query($sql);
		parent::Uninstall();
		
		return true;
	}

	/**
	 * Upgrade the Module
	 *
	 * @author Dewak S.A.
	 * @return bool "true" on Success, "false" otherwise
	 */
	public function Upgrade()
	{
		parent::Upgrade();

		return true;
	}
}
?>