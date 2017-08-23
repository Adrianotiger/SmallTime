<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head>
		<title>IDTime - Stempeln mit Barcodeleser</title>
		<meta http-equiv="refresh" content="2; URL=index.php">
	</head>
	<body>
		<center>
Sie werden nach 2 Sekunden automatisch weitergeleitet.
<?php
	/********************************************************************************
	* Small Time
	/*******************************************************************************
	* Version 0.9.003
	* Author:  IT-Master GmbH
	* www.it-master.ch / info@it-master.ch
	* Copyright (c), IT-Master GmbH, All rights reserved
	********************************************************************************/
	// -----------------------------------------------------------------------------
	// idtime - Stempelzeit via Direkt-URL eintragen, z.B. ID oder
	//          komplette URL von einem Barcode-Scanner
	//
	// Aufruf: SCRIPT_NAME?id=<id>, z.B. http://server/idtime.php?id=f0ab4565d3ead4c9
	//         <id> - SHA-1 aus Benutzer-Login + Benutzer-Passwort-SHA-1
	//                + Blowfish-Hash des Benutzer-Logins,"gesaltet" mit
	//                mit einem "secret" und dem SHA-1 des Benutzer-Passworts 
	// 
	// ACHTUNG: Es wird kein Benutzername oder Passwort abgefragt!
	//          ID-Verfahren weist Sicherheitsmängel auf: Jeder, dem das "secret"
	//          sowie der Passwort-SHA-1 bekannt ist, kann die ID nachbilden!
	//          Wenn das "secret" hier geändert wird, muss es auch in
	//          ./modules/sites_admin/admin04_idtime_generate.php angepasst werden!
	//
	$idtime_secret = 'CHANGEME'; // [./0-9A-Za-z] Mehr als 21 Zeichen führen dazu, dass das Benutzer-Passwort nicht mehr in die ID-Generierung einfliesst.	
	// -----------------------------------------------------------------------------
	// Benutzerdaten in Array ( ID => Pfad ) lesen:
	$_stempelid = array();
	include_once "./include/class_xmlhandle.php";
    $_users = new xml_filehandle("./Data/", "users.xml");
    foreach($_users->_array as $_user) {
		if(isset($_GET['rfid'])) {
			$tempid=trim(@$_user['rfid']);
			$tempid = str_ireplace('\r','',$tempid);
			$tempid = str_ireplace('\n','',$tempid);
			if($tempid==@$_GET['rfid']){
				$user = $_user['pfad'];
			}
		}elseif(isset($_GET['id'])){
			$hash = sha1($_user['name'].$_user['passwort'].crypt($_user['name'], '$2y$04$'.substr($idtime_secret.$_user['passwort'], 0, 22)));
			$ID = substr($hash, 0, 16);
			$_stempelid[$ID] = $_user['pfad'];
			}
	}
	// -----------------------------------------------------------------------------
	// übergebene ID Benutzer zuordnen und Stempelzeit eintragen:
	if (isset($_GET['id'])) {
		$ID = substr($_GET['id'], 0, 16);
		echo $_stempelid[$ID];
		if (isset($_stempelid[$ID])) {
			$user = $_stempelid[$ID];
			$_timestamp = time();
			$_zeilenvorschub= "\r\n";
			$_file = './Data/' . $user . '/Timetable/' . date('Y') . '.' . date('n');
			$fp = fopen($_file, 'a+b') or die("FEHLER - Konnte Stempeldatei nicht &ouml;ffnen!");
			fputs($fp, time().$_zeilenvorschub);
			fclose($fp);
			txt("OK und Stempelzeit f&uuml;r <b>$user</b> eingetragen.", true);
			//$_SESSION['time'] = true; // ?
		}
		else txt("Fehler, unbekannte ID!", false);
	}elseif(isset($_GET['rfid'])){
		if(isset($user)){
			$_timestamp = time();
			$_zeilenvorschub= "\r\n";
			$_file = './Data/' . $user . '/Timetable/' . date('Y') . '.' . date('n');
			$fp = fopen($_file, 'a+b') or die("FEHLER - Konnte Stempeldatei nicht &ouml;ffnen!");
			fputs($fp, time().$_zeilenvorschub);
			fclose($fp);
			txt("OK und Stempelzeit f&uuml;r <b>$user</b> eingetragen.", true);
			//$_SESSION['time'] = true; // ?			
		}else txt("Fehler, unbekannte ID!", false);
	}else{ 
		txt("Fehler, keine ID &uuml;bermittelt!", false);	
	}
	function txt($txt, $ok) {
		echo '<p style="color:'.($ok?'green':'red').'">' . $txt . '</p>';
	}
?>
		</center>
	</body>
</html>