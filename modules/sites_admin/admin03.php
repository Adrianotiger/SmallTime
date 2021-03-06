<?php
/********************************************************************************
* Small Time
/*******************************************************************************
* Version 0.9.001
* Author:  IT-Master GmbH
* www.it-master.ch / info@it-master.ch
* Copyright (c), IT-Master GmbH, All rights reserved
*******************************************************************************/
include_once "./include/class_xmlhandle.php";

echo "<table width=100% border=0 cellpadding=5 cellspacing=1>";
$_benutzer = new xml_filehandle("./Data/", "users.xml");
$i=1;
foreach($_benutzer->_array as $udata){
	$_userdaten_tmp = file("./Data/".$udata['pfad']."/userdaten.txt");
	echo "<tr>";
	if($_user->_loginname == $udata['name']){
		echo "<td class='td_background_info' width=100 align=left>";
	}else{
		echo "<td class='td_background_top' width=100 align=left>";
	}
	echo "<a title='Userdaten anzeigen von diesem Monat' href='?action=user_personalkarte&admin_id=".$udata['id']."'><img src='images/icons/user.png' border=0> ";
	echo "Nr. ". $udata['id'] . " / " ;
	echo $_userdaten_tmp[0];
	echo "</a>";
	//-----------------------------------------------------------------------------
	// Mitarbeiter - Menu
	//-----------------------------------------------------------------------------
	if($i > 1){
		$tmpstr= "<span class='btn'><a title='L&ouml;schen eines Users' href='?action=delete_user&delete_user_id=".$udata['id']."'><img src='images/icons/delete.png' border=0></a></span>";
	}else{
		$tmpstr= "";
	}	
	echo "</td></tr><tr><td>";
	echo "
	<div class='btn-group'>
		<span class='btn";
		if($_action == 'show_time' && $udata['id'] ==$_SESSION['id']) echo ' active'; 
		echo "'>
               	<a title='Monatsansicht' href='?action=show_time&admin_id=".$udata['id']."'><img src='images/icons/calendar_view_month.png' border='0'></a>
               </span>
               <span class='btn";
		if($_action == 'show_year2' && $udata['id'] ==$_SESSION['id']) echo ' active'; 
		echo "'>
               	<a title='Jahres&uuml;bersicht Details' href='?action=show_year2&admin_id=".$udata['id']."''><img src='images/icons/calendar.png' border='0'></a>
               </span>
               <span class='btn";
		if($_action == 'show_year' && $udata['id'] ==$_SESSION['id']) echo ' active'; 
		echo "'>
               	<a title='Jahres&uuml;bersicht' href='?action=show_year&admin_id=".$udata['id']."'><img src='images/icons/table_multiple.png' border=0></a>
               </span>
               <span class='btn";
		if($_action == 'show_pdf' && $udata['id'] ==$_SESSION['id']) echo ' active'; 
		echo "'>
               	<a title='Vorhandene PDF' href='?action=show_pdf&admin_id=".$udata['id']."'><img src='images/icons/page_white_acrobat.png' border=0></a>
               </span>
               <span class='btn";
		if($_action == 'zip_user' && $udata['id'] ==$_SESSION['id']) echo ' active'; 
		echo "'>
               	<a title='ZIP-Datei erstellen' href='?action=zip_user&admin_id=".$udata['id']."'><img src='images/icons/page_white_zip.png' border=0></a>
               </span>
               <span class='btn";
		if($_action == 'user_einstellungen' && $udata['id'] ==$_SESSION['id']) echo ' active'; 
		echo "'>
               	<a title='Grundeinstellungen' href='?action=user_einstellungen&admin_id=".$udata['id']."'><img src='images/icons/folder_user.png' border=0></a>
               </span>
               <span class='btn";
		if($_action == 'user_edit' && $udata['id'] ==$_SESSION['id']) echo ' active'; 
		echo "'>
               	<a title='Userdaten einstellen' href='?action=user_edit&admin_id=".$udata['id']."'><img src='images/icons/user_edit.png' border=0></a>
               </span>";
          
    /*
    echo "     <span class='btn";
		if($_action == 'user_edit_absenzen' && $udata['id'] ==$_SESSION['id']) echo ' active'; 
		echo "'>
               	<a title='Absenzen einstellen' href='?action=user_edit_absenzen&admin_id=".$udata['id']."'><img src='images/icons/date_edit.png' border=0></a>
               </span>"
               	*/
  echo $tmpstr;
  echo "</div>
	</td></tr>";
	$i++;
}
echo "</table>";

if(!$show_user){

	echo "<hr>";
	//-----------------------------------------------------------------------------
	// Anzeige der Summen aus Statistik
	//-----------------------------------------------------------------------------
	echo "<table class='table' border=0 cellpadding=3 cellspacing=1>";
	echo "<tr>";
	echo "<td class='td_background_top' align=left colspan=2>Mitarbeiterdaten</td>";
	echo "</tr>";
	echo "<tr>";
	echo "<td class=td_background_tag align=left>Name</td>";
	echo "<td class=td_background_tag align=left>$_user->_name</td>";
	echo "</tr>";
	echo "<tr>";
	echo "<td class=td_background_tag align=left>Start - Datum</td>";
	echo "<td class=td_background_tag align=left>".date("d.m.Y",$_user->_BeginnDerZeitrechnung)."</td>";
	echo "</tr>";
	echo "<tr>";
	echo "<td class=td_background_tag align=left>Anstellung</td>";
	echo "<td class=td_background_tag align=left>$_user->_SollZeitProzent %</td>";
	echo "</tr>";
	echo "<tr>";
	echo "<td class=td_background_tag align=left>Sollstd. / Wo</td>";
	echo "<td class=td_background_tag align=left>$_user->_SollZeitProWoche h</td>";
	echo "</tr>";
	if(!$show_user_only){
		echo "<tr>";
		echo "<td class='td_background_top' align=left colspan=2>Total - Saldi ende Monat</td>";
		echo "</tr>";
		if($_user->_modell==2) {
			$str = "Monatssaldo";
		}elseif($_user->_modell==1) {
			$str = "Jahressaldo";
		}else {
			$str = "Zeitsaldo";
		}
		echo "<tr>";
		echo "<td class='alert";
		echo $_jahr->_saldo_t >= 0 ? " alert-success" : " alert-error";
		echo "' align=left>".$str."</td>";
		echo "<td class=td_background_tag align=left>$_jahr->_saldo_t Std.</td>";
		echo "</tr>";
		
		
		echo "<tr>";
		echo "<td class='alert";
		echo $_jahr->_saldo_F >= 0 ? " alert-success" : " alert-error";
		echo "' align=left>Feriensaldo</td>";
		echo "<td class=td_background_tag align=left>$_jahr->_saldo_F Tage</td>";
		echo "</tr>";
		// Falls Settings - ferien nur bis heute Berechnet werden zukünftige anzeigen lassen
		if($_settings->_array["Absenz Berechnung 1"]["value"]==1){
			echo "<tr>";
			echo "<td class='td_background_tag' align=left>geplante Ferien</td>";
			echo "<td class=td_background_tag align=left>$_jahr->_summe_Fz Tage</td>";
			echo "</tr>";
		}
		echo "<tr>";
		echo "<td class='td_background_top' align=left colspan=2>Monats - Summen</td>";
		echo "</tr>";
		echo "<tr>";
		echo "<td class=td_background_tag align=left>Monat&nbsp;</td>";
		echo "<td class=td_background_tag align=left>";
		echo $_time->_monatname . " ". $_time->_jahr. "</td>";
		echo "</tr>";
		echo "<tr>";
		echo "<td class='alert";
		echo $_monat->_SummeSaldoProMonat >= 0 ? " alert-success" : " alert-error";
		echo "' align=left>Saldo</td>";
		echo "<td class=td_background_tag align=left>$_monat->_SummeSaldoProMonat Std.</td>";
		echo "</tr>";
		echo "<tr>";
		echo "<td class=td_background_tag align=left>Soll</td>";
		echo "<td class=td_background_tag align=left>$_monat->_SummeSollProMonat Std.</td>";
		echo "</tr>";

		foreach ($_monat->get_calc_absenz() as $werte){
			if($werte[3]<>0){
				echo "<tr>";
				echo "<td class=td_background_wochenende width=100 align=left>$werte[0]</td>";
				echo "<td class=td_background_wochenende align=left>";	
				echo "$werte[3] Tage ($werte[1])";		
				echo "</td>";
				echo "</tr>";			
			}
		}
	}
	echo "</table>";
}