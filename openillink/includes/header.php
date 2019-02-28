<?php
// ***************************************************************************
// ***************************************************************************
// ***************************************************************************
// This file is part of OpenILLink software.
// Copyright (C) 2005, 2006, 2007, 2008, 2009, 2010, 2011, 2012, 2013, 2015, 2016, 2017, 2018, 2019 CHUV.
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
$current_http_protocol = (((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https' || !empty($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] == 'on'))  ? 'https://' : 'http://');
$siteUrl = $current_http_protocol . $_SERVER['SERVER_NAME'] . dirname($_SERVER['PHP_SELF']);

echo "<!DOCTYPE html>\n";
echo "<html lang=\"" . $lang . "\">\n";
echo "<head>\n";

if (isset($configanalytics) && $configanalytics != '') {
	// Code for users to opt-out from Google Analytics tracking here
	// See: https://developers.google.com/analytics/devguides/collection/gajs/#example
	// A link to opt-out would typically be placed in one site "privacy policy" 
	// page, for eg. with <a onclick="gaOptout();return false;">opt out</a>
	echo '<!-- Google Analytics "opt-out" cookie script -->
	<script>

// Disable tracking if the opt-out cookie exists.
var disableStr = \'ga-disable-'.$configanalytics.'\';
if (document.cookie.indexOf(disableStr + \'=true\') > -1) {
  window[disableStr] = true;
}

// Opt-out function
function gaOptout() {
  document.cookie = disableStr + \'=true; expires=Thu, 31 Dec 2099 23:59:59 UTC; path=/\';
  window[disableStr] = true;
}
</script>';

	echo '<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id='.$configanalytics.'"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag(\'js\', new Date());

  gtag(\'config\', \''.$configanalytics.'\''. ($configanalytics_ip_anonymization ? ', { \'anonymize_ip\': true }' : '') .');
</script>';
}

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

if ($config_dataprotection_banner_enable) {
	echo '
<link rel="stylesheet" type="text/css" href="'.$siteUrl.'/css/cookieconsent/cookieconsent.min.css" />
<script src="'.$siteUrl.'/js/cookieconsent/cookieconsent.min.js"></script>';
}
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