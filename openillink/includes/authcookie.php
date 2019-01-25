<?php
// ***************************************************************************
// ***************************************************************************
// ***************************************************************************
// This file is part of OpenILLink software.
// Copyright (C) 2005, 2006, 2007, 2008, 2009, 2010, 2011, 2012, 2013, 2015, 2016, 2017, 2019 CHUV.
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
// 
// Authentication by cookie
//
require_once ("config.php");
require_once ('toolkit.php');

$monnom = "";
$monlog = "";
$monbib = "";
$monaut = "";

if (!empty($_COOKIE['illinkid'])){
    $cookie_name = ((!empty($_COOKIE['illinkid']['nom'])) && isValidInput($_COOKIE['illinkid']['nom'],255,'s',false) )? $_COOKIE['illinkid']['nom']:'';
    $cookie_library = ((!empty($_COOKIE['illinkid']['bib'])) && isValidInput($_COOKIE['illinkid']['bib'],50,'s',false) )? $_COOKIE['illinkid']['bib']:'';
    $cookie_auth_level = ((!empty($_COOKIE['illinkid']['aut'])) && isValidInput($_COOKIE['illinkid']['aut'],1,'i',false) )? $_COOKIE['illinkid']['aut']: '';
    $cookie_login = ((!empty($_COOKIE['illinkid']['log'])) && isValidInput($_COOKIE['illinkid']['log'],255,'s',false) )? $_COOKIE['illinkid']['log']:'';
	$cookie_expiration_time= ((!empty($_COOKIE['illinkid']['exp'])) && isValidInput($_COOKIE['illinkid']['exp'],255,'i',false) )? $_COOKIE['illinkid']['exp']: '';
	$cookie_checksum = ((!empty($_COOKIE['illinkid']['chk'])) && isValidInput($_COOKIE['illinkid']['chk'],255,'s',false) )? $_COOKIE['illinkid']['chk']: '';
	$cookie_sso = ((!empty($_COOKIE['illinkid']['sso'])) && isValidInput($_COOKIE['illinkid']['sso'],1,'i',false) )? $_COOKIE['illinkid']['sso']: '';
	
	$checksum = hash("sha256", $cookie_name.$cookie_library.strval($cookie_auth_level).$cookie_login.strval($cookie_expiration_time).($cookie_sso ? '1' : '0').$secure_string_cookie);
	
	if (($checksum == $cookie_checksum) && ($cookie_expiration_time > time())){
		$monnom = $cookie_name;
		$monlog = $cookie_login;
		$monbib = $cookie_library;
		if ($cookie_auth_level == $auth_sadmin) {
			$monaut = "sadmin";
		} elseif ($cookie_auth_level == $auth_admin){
			$monaut = "admin";
		} elseif ($cookie_auth_level == $auth_user){
			$monaut = "user";
		} elseif ($cookie_auth_level == $auth_guest){
			$monaut = "guest";
		}
	}
}

function create_session_cookie($name, $library, $auth_level, $login, $is_sso=false, $secure=true, $httponly=true, $duration=36000) {
	/* Set a cookie for the given values */
	global $secure_string_cookie;
	$current_time = time();
	$expiration_time = $current_time + $duration;
	$checksum = hash("sha256", $name.$library.strval($auth_level).$login.strval($expiration_time).($is_sso ? '1' : '0').$secure_string_cookie);
	setcookie('illinkid[nom]', $name, $expiration_time, "", "", $secure, $httponly);
	setcookie('illinkid[bib]', $library, $expiration_time, "", "", $secure, $httponly);
	setcookie('illinkid[aut]', $auth_level, $expiration_time, "", "", $secure, $httponly);
	setcookie('illinkid[log]', $login, $expiration_time, "", "", $secure, $httponly);
	setcookie('illinkid[chk]', $checksum, $expiration_time, "", "", $secure, $httponly);
	setcookie('illinkid[exp]', $expiration_time, $expiration_time, "", "", $secure, $httponly);
	setcookie('illinkid[sso]', $is_sso ? '1' : '0', $expiration_time, "", "", $secure, $httponly);
}
?>
