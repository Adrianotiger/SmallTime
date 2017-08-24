<?php
/********************************************************************************
* Small Time
/*******************************************************************************
* Version 0.9.016
* Author:  IT-Master GmbH
* www.it-master.ch / info@it-master.ch
* Copyright (c), IT-Master GmbH, All rights reserved
*******************************************************************************/
$active 	= '  class="active"';
$fade  	= ' active in';
$s01    	= $active;
$s011   	= $fade;
$s02    	= '';
$s021   	= '';
$s03    	= '';
$s031   	= '';
$s04      = '';
$s041     = '';
$s05      = '';
$s051     = '';
if(isset($_GET['menue']))
{
	if($_GET['menue'] == "multilogin")
	{
		$s01 = '';
		$s011= '';;
		$s02 = $active;
		$s021= $fade;
	}
	elseif($_GET['menue'] == "logoundfarben")
	{
		$s01 = '';
		$s011= '';;
		$s03 = $active;
		$s031= $fade;
	}
	elseif($_GET['menue'] == "pausen")
	{
		$s01 = '';
		$s011= '';;
		$s04 = $active;
		$s041= $fade;
	}
  elseif($_GET['menue'] == "absenzen")
  {
    $s01 = '';
    $s011= '';;
    $s05 = $active;
    $s051= $fade;
  }
}
?>
<div id="kn">
	<ul id="myTab" class="nav nav-tabs">
		<li<?php echo $s01; ?>>
			<a data-toggle="tab" href="#s1">
				<img src="./images/icons/cog_go.png" alt="" /> Settings
			</a>
		</li>
		<li<?php echo $s04; ?>>
			<a data-toggle="tab" href="#s4">
				<img src="./images/icons/cog_go.png" alt="" /> Pausen
			</a>
		</li>
		<li<?php echo $s05; ?>>
      <a data-toggle="tab" href="#s5">
        <img src="./images/icons/cog_go.png" alt="" /> Absenzen
      </a>
    </li>
		<li<?php echo $s02; ?>>
			<a data-toggle="tab" href="#s2">
				<img src="./images/icons/cog_go.png" alt="" /> Multilogin
			</a>
		</li>
		<li<?php echo $s03; ?>>
			<a data-toggle="tab" href="#s3">
				<img src="./images/icons/cog_go.png" alt="" /> Logo
			</a>
		</li>

	</ul>
	<div id="myTabContent" class="tab-content">
		<div id="s1" class="tab-pane fade<?php echo $s011; ?>">
			<p id="content">
				<?php
				include('./modules/sites_admin/settings/001.php');
				?>
			</p>
		</div>
		<div id="s2" class="tab-pane fade<?php echo $s021; ?>">
			<p id="content">
				<?php
				include('./modules/sites_admin/settings/002.php');
				?>
			</p>
		</div>
		<div id="s3" class="tab-pane fade<?php echo $s031; ?>">
			<p id="content">
				<?php
				include('./modules/sites_admin/settings/003.php');
				?>
			</p>
		</div>
		<div id="s4" class="tab-pane fade<?php echo $s041; ?>">
			<p id="content">
				<?php
				include('./modules/sites_admin/settings/pausen.php');
				?>
			</p>
		</div>
		<div id="s5" class="tab-pane fade<?php echo $s051; ?>">
      <p id="content">
        <?php
        include('./modules/sites_admin/settings/absenzen.php');
        ?>
      </p>
    </div>
	</div>
</div>