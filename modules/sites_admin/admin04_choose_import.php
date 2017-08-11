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

echo "<a href='?action=import&format=chipdrive'>" . getinfotext("Chipdrive - Import (z.B.GGTimeStamp oder Chip Drive)","td_background_top") . "</a>";
if(isset($_GET['format']) && $_GET['format'] == "chipdrive")
  include "admin04_chipdrive_import.php";
else
  echo "<a href='?action=import&format=chipdrive'>Öffnen</a><br><br>";
    
echo "<a href='?action=import&format=csv'>" . getinfotext("CSV - Import (z.B.IPhone APP TimeOrg - timeorg.zimco.com)","td_background_top") . "</a>";
if(isset($_GET['format']) && $_GET['format'] == "csv")
  include "admin04_csv_import.php";
else
  echo "<a href='?action=import&format=csv'>Öffnen</a><br><br>";

?>
