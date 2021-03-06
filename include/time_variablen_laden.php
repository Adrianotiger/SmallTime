<?php
/*******************************************************************************
* Small Time Start, Variablen deklarieren
/*******************************************************************************
* Version 0.896
* Author:  IT-Master GmbH
* www.it-master.ch / info@it-master.ch
* Copyright (c), IT-Master GmbH, All rights reserved
*******************************************************************************/
// ----------------------------------------------------------------------------
// TIMESTAMP
// ----------------------------------------------------------------------------
$_time = new time();
if(isset($_GET["timestamp"])){
	$_time->set_timestamp($_GET["timestamp"]);
}
$_time->set_monatsname($_settings->_array["Monatsanzeige"]["value"]);
// ----------------------------------------------------------------------------
// USERDATEN
// ----------------------------------------------------------------------------
$_user = new time_user();
if(isset($_GET['admin_id'])){
	$_id = $_GET['admin_id'];
	$_SESSION['id'] = $_id;
	$_SESSION['username'] = $_users->_array[$_id]['name'];
	$_SESSION['passwort'] = $_users->_array[$_id]['passwort'];
	$_SESSION['datenpfad'] = $_users->_array[$_id]['pfad'];
  $_SESSION['rfid'] = $_users->_array[$_id]['rfid'];	
}
$_user->load_data_session();	
// ----------------------------------------------------------------------------
// Absenzen - array
// ----------------------------------------------------------------------------
$_absenz = new time_absenz($_user->_ordnerpfad, $_time->_jahr);