<?php
// ***************************************************************************
// ***************************************************************************
// ***************************************************************************
// This file is part of OpenILLink software.
// Copyright (C) 2005, 2006, 2007, 2008, 2009, 2010, 2011, 2012, 2013, 2015, 2016, 2017, 2018 CHUV.
// Original author(s): Pablo Iriarte <pablo@iriarte.ch>
// Other contributors are listed in the AUTHORS file at the top-level
// directory of this distribution.
// 
// OpenILLink is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
// 
// OpenILLink is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
// 
// You should have received a copy of the GNU General Public License
// along with OpenILLink.  If not, see <http://www.gnu.org/licenses/>.
// 
// ***************************************************************************
// ***************************************************************************
// ***************************************************************************
// Headers for the administrators or users logged-in
//

$fileLogin = "login.php"; //(is_readable ( "login.php" ))? "login.php" : "../login.php";
$fileIndex = "index.php"; //(is_readable ( "index.php" ))? "index.php" : "../index.php";
$fileList = "list.php";   //(is_readable ( "list.php" ))? "list.php" : "../list.php";
$fileAdmin = "admin.php"; //(is_readable ( "admin.php" ))? "admin.php" : "../admin.php";
$fileReports = "reports.php"; //(is_readable ( "reports.php" ))? "reports.php" : "../reports.php";
$debugOn = (!empty($configdebuglogging)) && in_array($configdebuglogging, array('DEV', 'TEST'));

header ('Content-type: text/html; charset=utf-8');
error_reporting(-1);
ini_set('display_errors', 'On');

// Set the $siteUrl as base url for the stylesheets, scripts and links.
$configSiteUrl = (isset($_SERVER['HTTPS']) ? 'https://' : 'http://') . $_SERVER['SERVER_NAME'] . dirname($_SERVER['PHP_SELF']);

// paniers à afficher : in / out / sent / default = in
$folderMenu = "";
if(!isset($_GET['folder']))
{
$folder = "in";
}
else
{ 
$folder = $_GET['folder'];
$folderMenu = $folder;
}
if(!isset($_GET['folderid']))
{
$folderid = 0;
}
else
{ 
$folderid = $_GET['folderid'];
}
// echo $folder;

echo "<!DOCTYPE html>\n";
echo "<html lang=\"" . $lang . "\">\n";
echo "<head>\n";
echo "<meta charset=\"utf-8\">\n";
echo "<meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">\n";
echo "<title>";
if (!empty($myhtmltitle))
  echo $myhtmltitle;
else
  echo "OpenILLink";
echo "</title>\n";
echo "\n";

echo '<link rel="home" href="'.$configSiteUrl.'" />';

echo '
<link rel="stylesheet" href="'.$configSiteUrl.'/css/'. (isset($config_css_framework) ? $config_css_framework : 'openillink_bulma.css') .'">
<link rel="stylesheet" href="'.$configSiteUrl.'/css/'. (isset($config_css_main) ? $config_css_main : 'openillink.css') .'">
';

if (isset($config_css_custom) && $config_css_custom != '') {
	echo '<link rel="stylesheet" href="'.$configSiteUrl.'/css/'.$config_css_custom.'">';
}

echo '<link rel="stylesheet" href="'.$configSiteUrl.'/css/font-awesome/css/all.css">
<link rel="stylesheet" href="'.$configSiteUrl.'/css/calendar.css">
';

echo '
<script type="text/javascript" src="'.$configSiteUrl.'/js/script.js"></script>
<script type="text/javascript" src="'.$configSiteUrl.'/js/bulma.js"></script>
<script type="text/javascript" src="'.$configSiteUrl.'/js/calendar.js"></script>
';
echo "</head>\n";
if (!empty($mybodyonload))
	echo "<body onload=\"" . $mybodyonload . "\">\n";
else
	echo "<body onload=\"\">\n";
echo "\n";
echo '
<nav class="navbar has-shadow">
	<div class="container">
		<div class="navbar-brand">
			<a class="navbar-item" href="'.$configSiteUrl.'"><span class="title is-3">'.$sitetitle[$lang].'</span></a>
			<a role="button" class="navbar-burger" data-target="navMenu" aria-label="menu" aria-expanded="false">
				<span></span>
				<span></span>
				<span></span>
			</a>
		</div>
		
		<div id="navMenu" class="navbar-menu">
			<div class="navbar-start">
			</div>
			<div class="navbar-end">';
if (($monaut == "admin")||($monaut == "sadmin")||($monaut == "user")){
echo '
				<a class="navbar-item is-tab'.($folderMenu == 'in'? ' is-active' : '').'" href="'.$fileList.'?folder=in" title="'.__("Inbox").'">'.__("In").'</a>
				<a class="navbar-item is-tab'.($folderMenu == 'out'? ' is-active' : '').'" href="'.$fileList.'?folder=out" title="'.__("Orders sent to the outside and not yet received").'">'.__("Out").'</a>
				<a class="navbar-item is-tab'.($folderMenu == 'all'? ' is-active' : '').'" href="'.$fileList.'?folder=all" title="'.__("All orders").'">'.__("All").'</a>
				<a class="navbar-item is-tab'.($folderMenu == 'trash'? ' is-active' : '').'" href="'.$fileList.'?folder=trash" title="'.__("Orders deleted").'">'.__("Trash").'</a>';
			
			// Folders personalized
			require_once ("folders.php");
}
if ($monaut == "guest"){
echo '			<a class="navbar-item is-tab" href="'.$fileList.'?folder=guest" title=" '.__("See all my orders"). '">'.__("My orders").'</a>';
}
		
echo'
				<a class="navbar-item is-tab" href="' .$atozlinkurl[$lang]. '" title="' . $atozname[$lang] . '"><span class="icon"><i class="fa fa-compass fa-lg"></i></span></a>
				<span class="navbar-item"><a class="button is-primary" href="index.php" title="' .__("New Order"). '">' .__("New Order"). '</a></span>
				<a class="navbar-item is-tab" href="'.$fileAdmin.'" title="' . __("Administration of users and values") . '"><i class="fa fa-cogs fa-lg"></i></a>
				<a class="navbar-item is-tab" href="'.$fileReports.'" title="' . __("Obtain configured reports") . '"><span class="icon"><i class="fa fa-chart-bar fa-lg"></i></span></a>
				<a class="navbar-item is-tab" href="'.$fileLogin.'?action=logout" title="Logout ('.htmlspecialchars($monnom).')"><i class="fa fa-sign-out-alt fa-lg"></i></a>
			</div>
		</div>
	</div>
</nav>
';

echo '<section class="section">
	<div class="container">
';
?>