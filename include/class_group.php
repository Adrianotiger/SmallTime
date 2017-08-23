<?php
/*******************************************************************************
* Gruppen - Klasse
/*******************************************************************************
* Version 0.896
* Author:  IT-Master GmbH
* www.it-master.ch / info@it-master.ch
* Copyright (c), IT-Master GmbH, All rights reserved
*******************************************************************************/
class time_group
{
	//private $_filename = "./Data/group.xml";
	public 	$_array = NULL;
	function __construct($_grpwahl)
	{
		if($_grpwahl >= 0)
		{
			$_groups = new xml_filehandle("./Data/","group.xml" );
			$_users  = new xml_filehandle("./Data/","users.xml" );
      $x=-1;
			foreach($_groups->_array as $_group)
			{
			  $x++;
        if($x==0) continue; // Administrator
			  $tmpgrp = explode(",",$_group['mitglieder']);
				$y      = 0;
				foreach($tmpgrp as $ma)
				{   
					// Gruppenbezeichnung
					$this->_array[0][$x][] = $_group['name'];
					// Mitarbeiter ID
					$this->_array[1][$x][] = $ma;
					// Mitarbeiter Ordnerpfad
					$this->_array[2][$x][] = $_users->_array[$ma]['pfad'];
					// Mitarbeiter Kurzzeichen
					$this->_array[3][$x][] = $_users->_array[$ma]['name'];
					// Mitarbeiter Name
					$this->_array[4][$x][] = $this->get_userdata($_users->_array[$ma]['pfad']);
					// Mitarbeiter Stempelzeiten
					$this->_array[5][$x][] = $this->get_timestamps($_users->_array[$ma]['pfad']);
					$u = 0;
					// Anzahl Stempelzeiten
					$this->_array[6][$x][$y] = count($this->_array[5][$x][$y]);
					// Passwort
					$this->_array[7][$x][$y] = $_users->_array[$ma]['passwort'];
					$y++;
				}
			}
		}
	}
	function __destruct()
	{
	}
	function get_userdata($_ordnerpfad)
	{
		$tmp = "";
		$file= "./Data/". $_ordnerpfad. "/userdaten.txt";
		if(file_exists($file))	$tmp = file($file);
		return $tmp[0];
	}
	function get_usergroup($userid)
	{
	  $_groups = new xml_filehandle("./Data/","group.xml" );
		foreach($_groups->array as $_group)
		{
			$mitglieder = explode(",", $_group['mitglieder']);
			foreach($mitglieder as $users)
			{
				if($users == $userid and $x > 0)return $x;
			}
			$x++;
		}
	}
	function get_timestamps($_ordnerpfad)
	{
		$_w_jahr = date("Y", time());
		$_w_monat= date("n", time());
		$_file   = "./Data/".$_ordnerpfad."/Timetable/".$_w_jahr.".".$_w_monat;
		if(file_exists($_file))
		{
			$_timeTable = file($_file);
			sort($_timeTable);
		}
		else
		{
			$_timeTable = NULL;
		}
		// Anzeige der heutigen Stempelzeiten (nur heute $temptime)
		$_temptime = array();
		$_str = "";
		if(count($_timeTable))
		{
			foreach($_timeTable as $_time)
			{
				//Datenüberprüfung und Bereinigung
				$_time = trim($_time);
				$_time = str_replace("\r", "", $_time);
				$_time = str_replace("\n", "", $_time);
				if($_time)
				{
					//Stempelzeit berechnen
					$_w_jahr     = date("Y", time());
					$_w_monat    = date("n", time());
					$_w_tag      = date("j", time());
					$_w_stunde   = date("H", time());
					$_w_minute   = date("i", time());
					$_w_sekunde  = date("s", time());

					$_w_jahr_t   = date("Y", $_time);
					$_w_monat_t  = date("n", $_time);
					$_w_tag_t    = date("j", $_time);
					$_w_stunde_t = date("H", $_time);
					$_w_minute_t = date("i", $_time);
					$_w_sekunde_t= date("s", $_time);

					if($_w_jahr == $_w_jahr_t && $_w_monat == $_w_monat_t && $_w_tag == $_w_tag_t)
					{
						$_temptime[] = $_w_stunde_t . ":" . $_w_minute_t;
					}
				}
				else
				{
					$_datum   = date("d.m.Y",time());
					$_uhrzeit = date("H:i",time());
					$_datetime= $_datum." - ".$_uhrzeit;
					$_debug   = new time_filehandle("./debug/","time.txt",";");
					$_debug->insert_line("Time;" . $_datetime . ";Fehler in class_group;141;" .$_file.";Leerzeile entdeckt");
				}
			}
		}
		return $_temptime;
	}
	function get_groups()
	{
		//--------------------------------------
		//Absenzen: Gruppen in ein Array laden
		//--------------------------------------
		if(file_exists("./Data/"."group.xml"))
		{
			$_groups = new xml_filehandle("./Data/","group.xml" );
      return $_groups->_array;
		}
		else
		{
			return false;
		}
	}
	function del_group($id)
	{
		$_groups = new xml_filehandle("./Data/","group.xml" );
    $_groups->delete_entry($id + 1);
	}
	function save_group()
	{
		$_anzahl         = $_POST['anzahl'];
		$_groups = new xml_filehandle("./Data/","group.xml" );
    $x = 0;
    foreach($_groups->_array as $_group)
		{
			$_temp_e = $_POST['e'.$x];
			$_temp_v = $_POST['v'.$x];
			$_temp_u = $_POST['u'.$x];
			if($_group['name'] <> $_temp_v || $_group['mitglieder'] <> $_temp_u)
			{
				$_groups->update_entry($x+1, array("id"=>$_group['id'], "name"=>$_temp_v, "mitglieder"=>$_temp_u));
			}
      $x++;
		}
    if($_anzahl > $x++ && $_POST['v'.$x] <> "")
    {
      $_groups->update_entry($_POST['e'.$x], array("id"=>$_POST['e'.$x], "name"=>$_POST['v'.$x], "mitglieder"=>$_POST['u'.$x]));
    }
	}
}