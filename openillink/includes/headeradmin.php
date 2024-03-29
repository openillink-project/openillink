<?php
// ***************************************************************************
// ***************************************************************************
// ***************************************************************************
// This file is part of OpenILLink software.
// Copyright (C) 2005, 2006, 2007, 2008, 2009, 2010, 2011, 2012, 2013, 2015, 2016, 2017, 2018, 2019, 2021 CHUV.
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
$current_http_protocol = (((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https' || !empty($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] == 'on') || (!empty($_SERVER['REQUEST_SCHEME']) && $_SERVER['REQUEST_SCHEME'] == 'https') || (!empty($_SERVER['SCRIPT_URI']) && strlen($_SERVER['SCRIPT_URI']) > 4 && substr($_SERVER['SCRIPT_URI'], 0, 5) == 'https'))  ? 'https://' : 'http://');
$configSiteUrl = $current_http_protocol . $_SERVER['SERVER_NAME'] . dirname($_SERVER['PHP_SELF']);

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

if ($config_dataprotection_banner_enable) {
	echo '
<link rel="stylesheet" type="text/css" href="'.$configSiteUrl.'/css/cookieconsent/cookieconsent.min.css" />
<script src="'.$configSiteUrl.'/js/cookieconsent/cookieconsent.min.js"></script>';
}

if (isset($config_units_search_enabled) && $config_units_search_enabled == 1) {
	echo '
<link href="'.$configSiteUrl.'/css/select2/select2.min.css" rel="stylesheet" />
';
}
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
if (isset($config_units_search_enabled) && $config_units_search_enabled == 1) {
	echo '
<script src="'.$configSiteUrl.'/js/jquery/jquery.min.js"></script> ';
}
if (isset($config_units_search_enabled) && $config_units_search_enabled == 1) {
	echo '
<script src="'.$configSiteUrl.'/js/select2/select2.min.js"></script>
<script src="'.$configSiteUrl.'/js/select2/i18n/'.$lang.'.js"></script>
<script src="'.$configSiteUrl.'/js/maximize-select2-height/maximize-select2-height.min.js"></script>
<script>
$(document).ready(function() {
    $(\'#service\').select2({
	dropdownAutoWidth : true,
	language: "'.$lang.'"}).maximizeSelect2Height();
});
</script>
';
}

echo '<script type="text/javascript">var resolver_enabled='.((isset($config_link_resolver_base_openurl) && $config_link_resolver_base_openurl != '') ? 'true': 'false' ).';</script>';

if ($config_dataprotection_banner_enable) {
	echo '
<script>
window.addEventListener("load", function(){
window.cookieconsent.initialise({

  "theme": "classic",
  "content": {
	"message": '. json_encode($config_dataprotection_banner_message[$lang]).',
	"link": '. json_encode($config_dataprotection_banner_legal_information_link_name[$lang]).',
    "href":  '. json_encode($config_dataprotection_banner_legal_information_url[$lang]).',
	"dismiss": "OK"
  },
  "cookie": {
             "name": "openillink_cookieconsent_status",
             "domain": window.location.hostname,
             "expiryDays": 365
  }
})});
</script>';
}
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
				<span class="navbar-item"><a class="button is-primary" href="index.php" title="' .__("New Order"). '">' .__("New Order"). '</a></span>';
if (($monaut == "admin")||($monaut == "sadmin")||($monaut == "user")){
	echo '
				<a class="navbar-item is-tab" href="'.$fileAdmin.'" title="' . __("Administration of users and values") . '"><i class="fa fa-cogs fa-lg"></i></a>
				<a class="navbar-item is-tab" href="'.$fileReports.'" title="' . __("Obtain configured reports") . '"><span class="icon"><i class="fa fa-chart-bar fa-lg"></i></span></a>
				';
}
				if (count($config_available_langs) > 1) {
				echo '<div class="navbar-item has-dropdown is-hoverable">
					<a class="navbar-link">'.strtoupper($lang).'</a>
					<div class="navbar-dropdown">';
						$current_url = $current_http_protocol . $_SERVER['HTTP_HOST'] .  parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
						$url_params = $_GET;
						if (in_array('de', $config_available_langs)) {
							$url_params['lang'] = 'de';
							echo '<a class="navbar-item" href="'.$current_url. '?' . htmlspecialchars(http_build_query($url_params)). '" title="Deutsch">DE</a>';
						}
						if (in_array('en', $config_available_langs)) {
							$url_params['lang'] = 'en';
							echo '<a class="navbar-item" href="'.$current_url. '?' . htmlspecialchars(http_build_query($url_params)).'" title="English">EN</a>';
						}
						if (in_array('es', $config_available_langs)) {
							$url_params['lang'] = 'es';
							echo '<a class="navbar-item" href="'.$current_url. '?' . htmlspecialchars(http_build_query($url_params)).'" title="Español">ES</a>';
						}
						if (in_array('fr', $config_available_langs)) {
							$url_params['lang'] = 'fr';
							echo '<a class="navbar-item" href="'.$current_url. '?' . htmlspecialchars(http_build_query($url_params)).'" title="Français">FR</a>';
					    }
						if (in_array('it', $config_available_langs)) {
							$url_params['lang'] = 'it';
							echo '<a class="navbar-item" href="'.$current_url. '?' . htmlspecialchars(http_build_query($url_params)).'" title="Italiano">IT</a>';
						}
					echo '</div>';
				}
				echo '</div>


				<a class="navbar-item is-tab" href="'.$fileLogin.'?action=logout" title="Logout ('.htmlspecialchars($monnom).')">'.htmlspecialchars($monnom).'&nbsp;&nbsp;<i class="fa fa-sign-out-alt fa-lg"></i></a>
			</div>
		</div>
	</div>
</nav>
';

echo '<section class="section">
	<div class="container">
';
?>