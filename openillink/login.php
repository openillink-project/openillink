<?php
// ***************************************************************************
// ***************************************************************************
// ***************************************************************************
// This file is part of OpenILLink software.
// Copyright (C) 2005, 2006, 2007, 2008, 2009, 2010, 2011, 2012, 2013, 2015, 2016, 2017, 2018, 2019, 2024 CHUV.
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
// Login form
//
require_once ("includes/config.php");
require_once ("includes/authcookie.php");
require_once ("includes/connexion.php");
require_once ("includes/toolkit.php");

$logok=0;
$current_http_protocol = (((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https' || !empty($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] == 'on'))  ? 'https://' : 'http://');
$monhost = $current_http_protocol . $_SERVER['SERVER_NAME'];
$monuri = $monhost . rtrim(dirname($_SERVER['PHP_SELF']), '/\\') . "/";
$rediradmin = "Location: " . $monuri . "list.php?folder=in";
$rediruser = "Location: " . $monuri . "list.php?folder=in";
$redirguest = "Location: " . $monuri . "list.php?folder=guest";

$validActionSet = array('logout', 'shibboleth', 'ssologoutok');
$action = (!empty($_GET['action']) && isValidInput($_GET['action'],11,'s',false,$validActionSet))?$_GET['action']:NULL;
if (!empty($_COOKIE['illinkid'])) {
	$complement = "&action=$action&monaut=$monaut";
	if (empty($action) && ($monaut=="sadmin"))
		header("$rediradmin".$complement);
	if (empty($action) && ($monaut=="admin"))
		header("$rediradmin".$complement);
	if (empty($action) && ($monaut=="user"))
		header("$rediruser".$complement);
	if (empty($action) && ($monaut=="guest"))
		header("$redirguest".$complement);
}
if(!empty($action)){
    if ($action == 'logout'){
		$logged_in_with_shibboleth = false;
		if (!empty($_COOKIE['illinkid']) && !empty($_COOKIE['illinkid']['sso']) && $_COOKIE['illinkid']['sso'] == "1") {
			$logged_in_with_shibboleth = true;
		}
        setcookie('illinkid[nom]', '', (time() - 31536000));
        setcookie('illinkid[bib]', '', (time() - 31536000));
        setcookie('illinkid[aut]', '', (time() - 31536000));
        setcookie('illinkid[log]', '', (time() - 31536000));
		setcookie('illinkid[chk]', '', (time() - 31536000));
		setcookie('illinkid[exp]', '', (time() - 31536000));
		setcookie('illinkid[sso]', '', (time() - 31536000));
		$monnom = "";
		$monlog = "";
		$monbib = "";
		$monaut = "";
		if ($config_shibboleth_enabled == 1 && $logged_in_with_shibboleth) {
			header("Location: " . str_replace('_OPENILLINK_RETURN_URL_', $monuri.'login.php?action=logoutok', $config_shibboleth_logout_url));
		}
    }

    // *********************************
    // *********************************
    // shibboleth authentication
    // *********************************
    // *********************************
    if (($config_shibboleth_enabled == 1) && ($action == 'shibboleth')){
		$email = "";
		if (array_key_exists($config_shibboleth_email_attr, $_SERVER)) {
			$email = strtolower($_SERVER[$config_shibboleth_email_attr]);
		}
        if (strlen($email)<6){
            $mes=format_string(__("Your institutional login is not authorized to access %sitename. If needed please %x_url_startcontact the administrator%x_url_end."), array('x_url_start' => '<a href="mailto:'.$configemail.'">', 'x_url_end' => '</a>', 'sitename' => $sitetitle[$lang]));
        }
        else{
            // check if the user id and password combination exist in database
            $req = "SELECT * FROM users WHERE email = ?";
            $result = dbquery($req, array($email), "s");
            $nb = iimysqli_num_rows($result);
            if ($nb == 1){
                // the user id and password match
                $logok=$logok+1;
                for ($i=0 ; $i<$nb ; $i++){
                    $enreg = iimysqli_result_fetch_array($result);
					$status = $enreg['status'];
					if (1 == $status) {
						$nom = $enreg['name'];
						$login = $enreg['login'];
						$library = $enreg['library'];
						$admin = $enreg['admin'];
						create_session_cookie($nom, $library, $admin, $login, true, ($current_http_protocol=='https://'));
						if (in_array($enreg['admin'], array($auth_sadmin, $auth_admin))) {
						   header("$rediradmin");
						}
						if ($enreg['admin'] == $auth_user) {
						   header("$rediruser");
						}
						if ($enreg['admin'] == $auth_guest) {
						   header("$redirguest");
						}
					} else {
						# Generic error message: we do not want to disclose that account exists but been disabled
						$mes=__("The username or password you entered is incorrect");
					}
                }
            }
            else{
                 // the user id and password don't match, so guest with login = email
                 $logok=$logok+1;
				 create_session_cookie($email, 'guest', $auth_guest, $email, true, ($current_http_protocol=='https://'));
                 header("$redirguest");
            }
        }
    }
}
// *********************************
// *********************************
// login/password authentication
// *********************************
// *********************************

$log = ((!empty($_POST['log'])) && isValidInput($_POST['log'],255,'s',false))?$_POST['log']:NULL;
$pwd = ((!empty($_POST['pwd'])) && isValidInput($_POST['pwd'],255,'s',false))?$_POST['pwd']:NULL;
$login_type = '';
if ((!empty($log))&&(!empty($pwd))){
    $logok=0;
    // check if the user id and password combination exist in database
    $req = "SELECT * FROM users WHERE login = ?";
    $result = dbquery($req, array($log), "s");
    $nb = iimysqli_num_rows($result);
    if ($nb == 1){
		$enreg = iimysqli_result_fetch_array($result);
		$password_hash_matched_p = password_verify($pwd, $enreg['password']);
		if (!$password_hash_matched_p && hash_equals(md5($pwd), $enreg['password'])) {
			# old hashing technique still stored in DB for user, update password hash now
			$query = "UPDATE users SET password=? WHERE user_id=?";
			dbquery($query, array(password_hash($pwd, PASSWORD_DEFAULT), $enreg['user_id']), 'si') or die("Error : ".mysqli_error(dbconnect()));
			$password_hash_matched_p = true;
		}
		if ($password_hash_matched_p) {
			$login_type = 'account'; // login type set to account even if user is deactivated
			$status = $enreg['status'];
			if (1 == $status) {
				$logok=$logok+1;
				$nom = $enreg['name'];
				$login = $enreg['login'];
				$library = $enreg['library'];
				$admin = $enreg['admin'];
				create_session_cookie($nom, $library, $admin, $login, false, ($current_http_protocol=='https://'));
				if (in_array($enreg['admin'], array($auth_sadmin, $auth_admin))) {
				   header("$rediradmin");
				}
				if ($enreg['admin'] == $auth_user) {
				   header("$rediruser");
				}
				if ($enreg['admin'] == $auth_guest) {
				   header("$redirguest");
				}
			} else {
				$mes=__("The username or password you entered is incorrect");
			}
		} else {
			$mes=__("The username or password you entered is incorrect");
		}
    } else {
        $mes=__("The username or password you entered is incorrect");
	}
}
if (((!empty($log)) && (!empty($pwd))) && ($login_type != 'account')){
    if ($logok==0){
        // Connexion par login crypté
        $mailg = strtolower($log) . $secure_string_guest_login;
        $passwordg = substr(hash("sha256", $mailg), 0, 8);
        if ($pwd == $passwordg){
			$login_type = 'guest_account';
            $logok=$logok+1;
			create_session_cookie(strtolower($log), 'guest', $auth_guest, strtolower($log), false, ($current_http_protocol=='https://'));
            header("$redirguest");
        }
        else {
            $mes=__("The username or password you entered is incorrect");
		}
    }
}
if ((empty($log) || empty($pwd)) && !empty($_POST['submit'])){
	$mes = __("The username and password must be provided");
}

if($config_shibboleth_enabled && !empty($action) && $action == 'ssologoutok' && empty($monaut)) {
	$mes = __("You have been logged out. To complete the process you MUST close your browser!");
}

if (empty($monaut)) {
	require ("includes/header.php");
} else {
	require ("includes/headeradmin.php");
}

if (!empty($mes)){
	echo '
	<div class="container">
	<div class="columns is-centered">
	<article class="message is-danger">
  <div class="message-body">
    '.$mes.'
  </div>
</article></div></div><br/><br/>';
}

if (empty($log)) {
   $log='';
}
echo '
<div class="container">
	<div class="columns is-centered">
		<article class="card is-rounded">
			<div class="card-content">
				<h1 class="title">'.__("Log in").'</h1>
				<form name="loginform" id="loginform" action="login.php" method="post">
				<div class="field">
				<p class="control has-icon">
					<input class="input" type="text" name="log" id="log" value="' . htmlspecialchars($log) . '" placeholder="'.__("Username").'" required>
					 <span class="icon is-small is-left">
						<i class="fa fa-user"></i>
					</span>
				</p>
				</div>
				<div class="field">
				<p class="control has-icon">
					<input class="input" type="password" name="pwd" id="pwd" value="" placeholder="'.__("Password").'" required>
					 <span class="icon is-small is-left">
						<i class="fa fa-lock"></i>
					</span>
				</p>
				</div>';
/*
echo '
				<p class="control">
					<label class="checkbox">
					<input type="checkbox" id="rememberme" name="rememberme" value="forever">Remember me</label>
				</p>';
*/
echo '
				<p class="control">
					<input type="submit" name="submit" id="submit" class="button is-primary is-fullwidth" value="'.__("Login").'" />
					<input type="hidden" name="redirect_to" value="/" />
				</p>
				</form>
			</div>
		</article>
	</div>';
if ($config_shibboleth_enabled == 1){
	echo '<div class="columns is-centered" style="margin-top:5px"><p>' . format_string(__('or %x_url_startlog in with your institutional account%x_url_end%description'), array('x_url_start' => '<a href="'.$config_shibboleth_login_url.'">', 'x_url_end' => '</a>', 'description' => $config_shibboleth_login_description[$lang])) . '</p></div>';
}
if ((!empty($action)) && $action == 'logout'){
    $monnom="";
    $monaut="";
    $monlog="";
}
if ($displayResendLink){
echo '
	<div class="columns is-centered section">
		<p><a href="resendcredentials.php" target="_self"> '.__("Request password").'</a> : '.__("Service only available to users with an openillink command").'</p>
	</div>';
}

echo '
</div>';

require ("includes/footer.php");
?>