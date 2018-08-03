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
// Headers common to all pages
//

header ('Content-type: text/html; charset=utf-8');
$debugOn = false;
error_reporting(-1);
ini_set('display_errors', 'On');

// Set the $siteUrl as base url for the stylesheets, scripts and links.
$siteUrl = (isset($_SERVER['HTTPS']) ? 'https://' : 'http://') . $_SERVER['SERVER_NAME'] . dirname($_SERVER['PHP_SELF']);

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

echo '<link rel="home" href="'.$siteUrl.'" />' ;

echo '
<link rel="stylesheet" href="'.$siteUrl.'/css/'. (isset($config_css_framework) ? $config_css_framework : 'openillink_bulma.css') .'">
<link rel="stylesheet" href="'.$siteUrl.'/css/'. (isset($config_css_main) ? $config_css_main : 'openillink.css') .'">
';

if (isset($config_css_custom) && $config_css_custom != '') {
	echo '<link rel="stylesheet" href="'.$siteUrl.'/css/'.$config_css_custom.'">';
}

echo '<link rel="stylesheet" href="'.$siteUrl.'/css/font-awesome/css/all.css">';

echo '
<script type="text/javascript" src="'.$siteUrl.'/js/script.js"></script>
<script type="text/javascript" src="'.$siteUrl.'/js/bulma.js"></script>
';

echo "</head>\n";
if (empty($mybodyonload)){
  $mybodyonload = '';
}
echo "<body onload=\"" . $mybodyonload . "\">\n";

echo '
<nav class="navbar has-shadow">
	<div class="container">
		<div class="navbar-brand">
			<a class="navbar-item" href="'.$siteUrl.'"><span class="title is-3">'.$sitetitle[$lang].'</span></a>
			<a role="button" class="navbar-burger" data-target="navMenu" aria-label="menu" aria-expanded="false">
				<span></span>
				<span></span>
				<span></span>
			</a>
		</div>
		
		<div id="navMenu" class="navbar-menu">
			<div class="navbar-start">
			</div>
			<div class="navbar-end">
				<a class="navbar-item is-tab" href="' .$atozlinkurl[$lang]. '" title="' . $atozname[$lang] . '"><span class="icon"><i class="fa fa-compass fa-lg"></i></span></a>
				<span class="navbar-item"><a class="button is-primary" href="index.php" title="' .__("New Order"). '">' .__("New Order"). '</a></span>
				';
				if (count($config_available_langs) > 1) {
				echo '<div class="navbar-item has-dropdown is-hoverable">
					<a class="navbar-link">'.strtoupper($lang).'</a>
					<div class="navbar-dropdown">';
						if (in_array('de', $config_available_langs)) {
							echo '<a class="navbar-item" href="'.$siteUrl.'?lang=de" title="Deutsch">DE</a>';
						}
						if (in_array('en', $config_available_langs)) {
							echo '<a class="navbar-item" href="'.$siteUrl.'?lang=en" title="English">EN</a>';
						}
						if (in_array('es', $config_available_langs)) {
							echo '<a class="navbar-item" href="'.$siteUrl.'?lang=es" title="Español">ES</a>';
						}
						if (in_array('fr', $config_available_langs)) {
							echo '<a class="navbar-item" href="'.$siteUrl.'?lang=fr" title="Français">FR</a>';
					    }
						if (in_array('it', $config_available_langs)) {
							echo '<a class="navbar-item" href="'.$siteUrl.'?lang=it" title="Italiano">IT</a>';
						}
					echo '</div>';
				}
				echo '</div>
				<a class="navbar-item is-tab" href="login.php" title="'.__("Login").'"><span class="icon"><i class="fas fa-user-circle fa-lg"></i></span></a>
			</div>
		</div>
	</div>
</nav>
';

echo '
	<section class="section">
<div class="container">
';

?>