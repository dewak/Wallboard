<?php

/* dewak - Building Software
/* Source Copyright 2011 Dewak S.A.
/* Unauthorized reproduction is not allowed
/* ------------------------------------------------
/* $Date: 2011-04-11 18:40:04 $
/* $Author: diego $
*/

class dbCore
{

	var $hostname = "";
	var $username = "";
	var $password = "";
	var $name = "";
	var $type = "";
	var $persistent = false;

	var $adodb;

	var $Record = array( );
	var $Record1 = array( );
	var $Record2 = array( );
	var $Record3 = array( );
	var $Record4 = array( );
	var $Record5 = array( );

	var $Row1;
	var $Row2;
	var $Row3;
	var $Row4;
	var $Row5;

	var $linkid = 0;
	var $queryid1 = 0;
	var $queryid2 = 0;
	var $queryid3 = 0;
	var $queryid4 = 0;
	var $queryid5 = 0;

	var $querydb = array( );
	var $querycount = 0;
	var $recordcount = 0;
	var $connected = false;

	function dbCore( $hostname, $username, $password, $dbname, $dbtype, $silent = false, $port = false )
	{
		$this->hostname = $hostname;
		$this->username = $username;
		$this->password = $password;
		$this->name = $dbname;
		$this->dbtype = $dbtype;
		$this->port = $port;
	
		$this->Record = &$this->Record1;
	
		if ( !$this->Connect( ) )
		{
			if ( !$silent )
			{
				$this->stop( $this->fetchLastError( ) );
			}
		
			return false;
		}
		else
		{
			$this->connected = true;
			
			return true;
		}
		
		
	}

	function stop( $errorstr = "" )
	{
		echo "$errorstr";
		exit;
		end;
	}

	function fetchLastError( )
	{
		if ( $this->linkid )
		{
			return mysql_error( $this->linkid );
		}
		return mysql_error( );
	}

	function error( $errorstr )
	{
		echo "$errorstr";
	}

	function Connect( )
	{
		
		if ( $this->persistent == false )
		{
			$this->linkid = @mysql_connect( $this->hostname.iif( !empty( $this->port ), ":".intval( $this->port ) ), $this->username, $this->password, true );
		}
		else
		{
			$this->linkid = @mysql_pconnect( $this->hostname.iif( !empty( $this->port ), ":".intval( $this->port ) ), $this->username, $this->password, true );
		}
		if ( $this->linkid )
		{
			$selectresult = mysql_select_db( $this->name, $this->linkid );
			if ( !$selectresult )
			{
				return false;
			}
			return true;
		}
		else
		{
			return false;
		}
	}

	function fetchmicrotime( )
	{
		list( $usec, $sec ) = explode( " ", microtime( ) );

		return ( float ) ( ( float )$usec + ( float )$sec );
	}

	function query( $querystr, $id = 1, $issilent = false )
	{
		$result = mysql_query( $querystr, $this->linkid );
		$this->{"queryid".$id} = $result;

		if ( !$result )
		{
			if ( !$issilent )
			{
				
				$this->error( "Invalid SQL: ".$querystr." (".mysql_error( $this->linkid ).")" );
			}

			return false;
		}
		else
		{
			return true;
		}
	}

	function queryFetch( $query, $querytcount = 3 )
	{
		$_result = $this->query( $query, $querytcount );
		if ( !$_result )
		{
			return false;
		}

		return $this->nextRecord( $querytcount );
	}

	function nextRecord( $id = 1 )
	{
		$_Query = &$this->{"queryid".$id};

		if ( !$_Query )
		{
			return false;
		}

		$_Record = &$this->{"Record".$id};

		$_Record = @mysql_fetch_array( $_Query, MYSQL_ASSOC );
		if ( is_array( $_Record ) )
		{
			return $_Record;
		}
		else
		{
			return false;
		}
	}

	function shutdownQuery( $query, $noshutdown = false )
	{	
		if ( $noshutdown == true )
		{
			$this->query( $query, 3 );

		}
		
		return true;
	}

	function lastInsertId( )
	{
		return mysql_insert_id( $this->linkid );
	}


	function escape( $data )
	{
		return mysql_real_escape_string( ( string )$data );
	}
}

function iif( $expr, $rtrue = "", $rfalse = "" )
{
	return ($expr ? $rtrue:$rfalse);
}

function buildIN( $dataarray )
{
	$in = "";
	if ( !_is_array( $dataarray ) )
	{
		return "'0'";
	}
	foreach ( $dataarray as $key => $val )
	{
		$in .= "'".addslashes( $val )."',";
	}
	if ( trim( $in ) != "" )
	{
		return substr( $in, 0, -1 );
	}
	else
	{
		return "'0'";
    }
}

function _is_array( $extarray )
{
	if ( !is_array( $extarray ) || !count( $extarray ) )
	{
		return false;
	}
        else
        {
	return true;
        }
}
?>