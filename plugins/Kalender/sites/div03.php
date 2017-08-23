<?php
/********************************************************************************
* Small Time - Plugin : Kalender Absenzenansicht der Mitarbeiter
/*******************************************************************************
* Version 0.899
* Author:  IT-Master GmbH
* www.it-master.ch / info@it-master.ch
* Copyright (c), IT-Master GmbH, All rights reserved
*******************************************************************************/
echo '<table cellpadding="2" cellspacing="1" border="0" width="100%">';
echo '
	<tr>
		<td width="20" height="22" class="td_background_top">ID</td>
		<td valign="middle" align="left" class="td_background_top">Name</td>
		<td valign="middle" align="left" class="td_background_top">Std.</td>
	</tr>';
include_once "./include/class_xmlhandle.php";
$_users = new xml_filehandle("./Data/", "users.xml");
foreach($_users->_array as $_user) {
	
	if(file_exists("./Data/".$_user['pfad']."/Timetable/total.txt")){
		$totale = file("./Data/".$_user['pfad']."/Timetable/total.txt");
		$time = round($totale[0],2);
		if($time <0){
			$time = "<font class=minus>".$time."</font>";
		}
	}else{
		$time = "xxx";
	}		
	$_userdaten_tmp = file("./Data/".$_user['pfad']."/userdaten.txt");
	echo '
	<tr>
		<td width="20" height="22" class="td_background_info">'.$_user.'</td>
		<td valign="middle" align="left" class="td_background_tag">'.$_userdaten_tmp[0].'</td>
		<td valign="middle" align="left" class="td_background_tag">'.$time.'</td>
	</tr>'; 
	$i++;
}
echo '</table>';