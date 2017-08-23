<?php
/********************************************************************************
* Small Time
/*******************************************************************************
* Version 0.9.016c
* Author:  IT-Master GmbH
* www.it-master.ch / info@it-master.ch
* Copyright (c), IT-Master GmbH, All rights reserved
*******************************************************************************/
$_anzeige= "";
$_anzeige = $_anzeige .'<form method="POST" action="?action=settings">';
$_anzeige = $_anzeige . '<table border="0" width="100%" cellpadding=3 cellspacing=1>';
$y=0;
//print_r($_settings->_array);
foreach($_settings->_array as $_zeile){
	//------------------------------------------------------------------------------------
	//Webseiten - Einstellungen
	//------------------------------------------------------------------------------------
	$_anzeige = $_anzeige . "<tr width=50%>";
  if($_zeile['type'] == "text")
  {
    $_anzeige .= "<td class='td_background_tag' align=left width=180>";
    if(isset($_zeile['img'])) 
      $_anzeige .= "<img src='".$_zeile['img']."' border='0' /> &nbsp; "; 
    $_anzeige .= $_zeile['id'] . "</td>";
    $_anzeige .= '<td class="td_background_tag"><input class="biginput" type="text" name="'.$_zeile['id'].'" value="'.$_zeile['value'].'" size="74"></td>';
    $_anzeige .= "<td class='td_background_tag'><img title='".$_zeile['beschreibung']."' src='images/icons/information.png' border=0></td></tr>";
  }
  else if($_zeile['type'] == "option")
  {
    $_anzeige .= "<tr class='td_background_tag'><td align=left>";
    if(isset($_zeile['img'])) 
      $_anzeige .= "<img src='".$_zeile['img']."' border='0' /> &nbsp; "; 
    $_anzeige .= $_zeile['id'] . "</td>";
    
    $_anzeige .= '<td><table border="0" cellspacing="0" cellpadding="0" ><tr>';
    $_optionen = explode(",",$_zeile['options']);
    $j=0;
    foreach($_optionen as $_option) {
      $_anzeige .= '<td><input type="radio" value="'.$j.'" name="'.$_zeile['id'].'" '.($_zeile['value']==$j?'checked':'').'></td>';
      if(file_exists("images/country/24/{$_option}.png"))
        $_anzeige .= '<td><img title="'.$_option.'" src="images/country/24/'.$_option.'.png"></td>'; 
      else
        $_anzeige .= '<td>'.$_option.'</td>';
      $j++;
    }
    $_anzeige .= '</tr></table></td>';
    $_anzeige .= "<td><img title='".$_zeile['beschreibung']."' src='images/icons/information.png' border=0></td></tr>";
    $_anzeige .= "<tr>";
  }
}

$_anzeige = $_anzeige . "	
	<tr><td class=td_background_heute colspan='3' align=center >
		<input name=senden value=senden type=submit>
	</td></tr>";
$_anzeige = $_anzeige . "</table>";
$_anzeige = $_anzeige . "</form>";
echo $_anzeige;