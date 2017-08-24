<?php
/********************************************************************************
* Small Time
/*******************************************************************************
* Version 0.896
* Author:  IT-Master GmbH
* www.it-master.ch / info@it-master.ch
* Copyright (c), IT-Master GmbH, All rights reserved
*******************************************************************************/
//-------------------------------------------------------------------------------------------
//CSV funktioniert nur im folgenden format
//-------------------------------------------------------------------------------------------
echo "<center>";
echo "";
echo "
<table width=50% border=0>
<tr>
<td><b>
<ol>
<li>File upload</li>
<li>Importtabelle wird berechnet</li>
<li>Importtabelle-Daten importieren</li>
</ol>
</b></td>
</tr>
</table>
Format der ChipDrive - Datei<br>
<table width=50% border=1>
<tr>
<td>Benutzer</td>
<td>Sequenz</td>
<td>Projekt</td>
<td>Aktivität</td>
<td>Zeit</td>
<td>Status</td>
<td>Prüfzahl</td>
<td>CRC</td>
</tr>
<tr>
<td>2240</td>
<td>5</td>
<td>0</td>
<td>0</td>
<td>5.8.2017 15:32</td>
<td>G</td>
<td>2</td>
<td>0x2E32</td>
</tr>
</table>";
echo "<br>";
echo "<hr color=red>";
//-------------------------------------------------------------------------------------------
//File importieren und speichern unter ./import / import.csv falls ein neues ausgewählt wurde
//-------------------------------------------------------------------------------------------
$_file   = $_FILES['uploadedfile']['tmp_name'];
$_submit = $_POST['submit'];
if($_file && $_submit)
{
  move_uploaded_file($_FILES['uploadedfile']['tmp_name'], "import/import.bin");
  echo "File : './import/import.bin' wurde auf den Server geladen.<br><br>";
  echo "<hr color=red>";
}
//-------------------------------------------------------------------------------------------
//File in ein array laden, falls es existiert
//-------------------------------------------------------------------------------------------
$_file = "./import/import.bin";
if(file_exists($_file))
{
  $_bindat = fopen($_file, "r");
}
//-------------------------------------------------------------------------------------------
//durchsuchen und wenn kein Datum mit Punkt in der ersten Spalte - dann unset der Zeile
//-------------------------------------------------------------------------------------------
$x = 0;
if(file_exists($_file))
{
  $_bindat2 = array();
  for($i=0;$i<filesize($_file) / 16;$i++)
  {
    array_push($_bindat2, fread($_bindat, 16));
  }
  
  fclose($_bindat);
}
//-------------------------------------------------------------------------------------------
//Daten anzeigen lassen wenn $_show = 1
//-------------------------------------------------------------------------------------------
if(file_exists($_file))
{
  $_show = 0;
  if($_show)
  {
    $x = 0;
    foreach($_bindat2 as $_zeile)
    {
      $i = 0;
      echo "Zeile " . $x . "/" . strlen($_zeile) .  " : ";
      echo (ord($_zeile[$i++]) + ord($_zeile[$i++]) * 0x100) . " - ";
      echo (ord($_zeile[$i++]) + ord($_zeile[$i++]) * 0x100) . " - ";
      echo (ord($_zeile[$i++]) + ord($_zeile[$i++]) * 0x100) . " - ";
      echo (ord($_zeile[$i++]) + ord($_zeile[$i++]) * 0x100) . " - ";
      $time = ord($_zeile[$i++]) + ord($_zeile[$i++]) * 0x100 + ord($_zeile[$i++]) * 0x10000 + ord($_zeile[$i++]) * 0x1000000;
      $time *= 60;
      $time += 631152000;   
      echo date("d.m.Y H:i", $time) . " - ";
      echo chr(ord($_zeile[$i++])) . " - ";
      echo ord($_zeile[$i++]) . " - ";
      echo ord($_zeile[$i++]) + ord($_zeile[$i++]) * 0x100;
      echo "<br>";
      $x++;
    }
    echo "<hr color=red>";
  }
}

$_timelistMitarbeiter = array();
//-------------------------------------------------------------------------------------------
//Daten anzeigen lassen und berechnen der timestamp
//-------------------------------------------------------------------------------------------
if(file_exists($_file))
{
  $_timelistMitarbeiter = array();
  $_show = 0;
  {
    $table = "";
    $table .= "<table bgcolor=white border=0 width=100% cellpadding=3 cellspacing=1>";
    $x = 0;
    foreach($_bindat2 as $_zeile)
    {
      $table .= '<tr>';
      $table .= '<td class="td_background_info" widht=20>';
      $table .= "ID:" . $x. "";
      $table .= "</td>";
      $i = 0;
      for($z = 0; $z < 4;$z++)
      {
        $table .= '<td class="td_background_tag" widht=20>';
        $table .= (ord($_zeile[$i++]) + ord($_zeile[$i++]) * 0x100);
        $table .= "</td>";
      }

      $table .= '<td class="td_background_info" widht=20>';
      $table .= "Convert:";
      $table .= "</td>";
      //-------------------------------------------------------------------------------------------
      //Datum konvertieren:
      $i = 8;
      $time1 = ord($_zeile[$i++]) + ord($_zeile[$i++]) * 0x100 + ord($_zeile[$i++]) * 0x10000 + ord($_zeile[$i++]) * 0x1000000;
      $time1 *= 60;
      $time1 += gmmktime(0,0,0,1,1,1990);
      
      //-------------------------------------------------------------------------------------------
      //timestamp 1
      $table .= "<td class='td_background_tag' widht=20> timestamp : ";
      $table .= $time1;
      $table .= '</td>';
      //-------------------------------------------------------------------------------------------
      //timestamp 2
      $table .= "<td class='td_background_tag' widht=20> time : ";
      $table .= gmdate("d.m.Y H:i", $time1);
      $table .= '</td>';
      $table .= '</tr>';
      
      $x++;
      if(!isset($_timelistMitarbeiter[(ord($_zeile[0]) + ord($_zeile[1]) * 0x100)])) 
        $_timelistMitarbeiter[(ord($_zeile[0]) + ord($_zeile[1]) * 0x100)] = array();
      array_push($_timelistMitarbeiter[(ord($_zeile[0]) + ord($_zeile[1]) * 0x100)], $time1);
    }
    $table .= "</table>";
    $table .= "<hr color=red>";
  }
  if($_show) echo $table;
}
//Convert String to int
function convertint($_string)
{
  for($z = 0; $z <= 255; $z++)
  {
    if(!$z >= 48 && !$z <= 57)
    {
      $_string = str_replace(chr($z), '', $_string);
    }
  }
  return (int)$_string;
}
?>
<form enctype="multipart/form-data" action="?action=import&format=chipdrive" method="POST">
  <input type="hidden" name="MAX_FILE_SIZE" value="500000" />
  BIN - File: <input name="uploadedfile" type="file" /> <input type="submit" name="submit" value="Upload File" />
</form>
<?php
echo "<hr color=red>";
//wenn Bereit, importieren
if($_POST['importieren'])
{
  include_once "./include/class_xmlhandle.php";
  $_users = new xml_filehandle("./Data/", "users.xml");
  $users = array();
  foreach($_users->_array as $_user) 
  {
    $users[$_user['rfid']]['username'] = $_user['name'];
    $users[$_user['rfid']]['pfad'] = $_user['pfad'];
  }
    
  foreach($_timelistMitarbeiter as $_mitarbeiterID => $_timelist)
  {
    if(array_key_exists($_mitarbeiterID, $users))
    {
      echo "Mitarbeiter : " . $_mitarbeiterID . " (" . $users[$_mitarbeiterID]['username'] . ") wird importiert...<br>";
      $tot = 0;
      $neu = 0;
      $alt = 0;
      foreach($_timelistMitarbeiter[$_mitarbeiterID] as $time)
      {
        $tot++;
        if($_time->save_timestamp($time, $users[$_mitarbeiterID]['pfad']))
          $neu++;
        else
          $alt++;
      }
      echo "<ul style='width:200px;text-align:left;'><li>Total: $tot<li>Hinzugefügt: $neu<li>Bereits vorhanden: $alt</ul>";
    }
    else
    {
      echo "Mitarbeiter : " . $_mitarbeiterID . " nicht gefunden!<br>";
    }
  }
  echo "Import erfolgreich<br>";
  echo "L&ouml;sche Datei<br>";
  $_file = "./import/import.bin";
  if(file_exists($_file))
  {
    unlink($_file);
    echo "Datei delete<br>";
  }
  else
  {
    echo "No Datei!<br>";
  }

}
else if(file_exists($_file))
{
  echo "Datei erfolgreich hochgeladen und bereit für den Import:<br>";
  echo '<form action="?action=import&format=chipdrive" method="POST">';
  echo '<input type="submit" name="importieren" value="importieren">';
  echo '</form>';
}
echo "</center>";
?>
