<?php
/*******************************************************************************
* Einstellugnen von Small Time
/*******************************************************************************
* Version 0.9.008
* Author:  IT-Master GmbH
* www.it-master.ch / info@it-master.ch
* Copyright (c), IT-Master GmbH, All rights reserved
*******************************************************************************/
class time_settings{
	private 	$_filename 	= "./include/Settings/settings.txt";
	public	$_array		= array();	
	// Einstellungen und globale Variablen 
	// Beschreibung, Eintrag, Info 
	private 	$_file;
	function __construct(){
		//$this->_file = new time_filehandle("./include/Settings/","settings.txt","#");
		//$this->_array = $this->_file->_array;
    $this->_file = new xml_filehandle("./include/Settings/","settings.xml");
    foreach($this->_file->_array as $arrVal)
    {
      $this->_array[$arrVal['id']] =  $arrVal;
    }
	}
	function save_settings(){
	  foreach($_POST as $_postkey => $_postvalue)
    {
      $_postkey = str_replace("_", " ", $_postkey);
      if(isset($this->_file->_array[$_postkey]))
        $this->_array[$_postkey]['value'] = $this->_file->_array[$_postkey]['value'] = $_postvalue;
    }
    
    $this->_file->save_xml();
	}
	function save_array($arr){
	  /*
		$_zeilenvorschub = "\r\n";
		//$anzahl = $_POST['anzahl'];
		$fp = fopen($this->_filename,"w+");
		foreach($arr as $eintrag){
			fputs($fp, $eintrag . $_zeilenvorschub);
		}
		fclose($fp);
     */
	}
}