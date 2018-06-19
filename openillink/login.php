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
// Login form
//
require_once ("includes/config.php");
require_once ("includes/authcookie.php");
require_once ("includes/connexion.php");
require_once ("includes/toolkit.php");

$logok=0;
$monhost = "http://" . $_SERVER['SERVER_NAME'];
$monuri = $monhost . rtrim(dirname($_SERVER['PHP_SELF']), '/\\') . "/";
$rediradmin = "Location: " . $monuri . "list.php?folder=in";
$rediruser = "Location: " . $monuri . "list.php?folder=in";
$redirguest = "Location: " . $monuri . "list.php?folder=guest";

$validActionSet = array('logout', 'shibboleth');
$action = (!empty($_GET['action']) && isValidInput($_GET['action'],10,'s',false,$validActionSet))?$_GET['action']:NULL;
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
        setcookie('illinkid[nom]', '', (time() - 31536000));
        setcookie('illinkid[bib]', '', (time() - 31536000));
        setcookie('illinkid[aut]', '', (time() - 31536000));
        setcookie('illinkid[log]', '', (time() - 31536000));
    }

    // *********************************
    // *********************************
    // shibboleth authentication
    // *********************************
    // *********************************
    if (($shibboleth == 1) && ($action == 'shibboleth')){
        $email = 'nobody@nowhere.ch';
        // $email = strtolower($_SERVER['mail']);
        $email = strtolower($_SERVER['Shib-InetOrgPerson-mail']);
        if (strlen($email)<6){
            $email = 'nobody@nowhere.ch';
            $mes='Votre login Shibboleth ne correspond pas avec un compte sur OpenILLink, veuillez contacter l\'administrateur du site : ' . $configemail;
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
                    $nom = $enreg['name'];
                    $login = $enreg['login'];
                    $status = $enreg['status'];
                    $library = $enreg['library'];
                    $admin = $enreg['admin'];
                    $admin = md5 ($admin . $secure_string_cookie);
                    setcookie('illinkid[nom]', $nom, (time() + 36000));
                    setcookie('illinkid[bib]', $library, (time() + 36000));
                    setcookie('illinkid[aut]', $admin, (time() + 36000));
                    setcookie('illinkid[log]', $login, (time() + 36000));
                    if ($monaut=="sadmin")
                        header("$rediradmin");
                    if ($monaut=="admin")
                        header("$rediradmin");
                    if ($monaut=="user")
                        header("$rediruser");
                    if ($monaut=="guest")
                        header("$redirguest");
                }
            }
            else{
                 // the user id and password don't match, so guest with login = email
                 $cookie_guest = md5 ("9" . $secure_string_cookie);
                 $logok=$logok+1;
                 setcookie('illinkid[nom]', $email, (time() + 36000));
                 setcookie('illinkid[bib]', 'guest', (time() + 36000));
                 setcookie('illinkid[aut]', $cookie_guest, (time() + 36000));
                 setcookie('illinkid[log]', $email, (time() + 36000));
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
if ((!empty($log))&&(!empty($pwd))){
    $logok=0;
    $password=md5($pwd);
    // check if the user id and password combination exist in database
    $req = "SELECT * FROM users WHERE login = ? AND password = ?";
    $result = dbquery($req, array($log, $password), "ss");
    $nb = iimysqli_num_rows($result);
    if ($nb == 1){
        // the user id and password match,
        $logok=$logok+1;
        $enreg = iimysqli_result_fetch_array($result);
        $nom = $enreg['name'];
        $login = $enreg['login'];
        $status = $enreg['status'];
        $library = $enreg['library'];
        $admin = $enreg['admin'];
        $admin = md5 ($admin . $secure_string_cookie);
        setcookie('illinkid[nom]', $nom, (time() + 36000));
        setcookie('illinkid[bib]', $library, (time() + 36000));
        setcookie('illinkid[aut]', $admin, (time() + 36000));
        setcookie('illinkid[log]', $login, (time() + 36000));
        if (in_array($enreg['admin'], array($auth_sadmin, $auth_admin)))
           header("$rediradmin");
        if ($enreg['admin'] == $auth_user)
           header("$rediruser");
        if ($enreg['admin'] == $auth_guest)
           header("$redirguest");
    }
    else
        $mes='Le login ou le password ne sont pas corrects';
}
if ((!empty($log))||(!empty($pwd))){
    if ($logok==0){
        // Connexion par login crypté
        $mailg = strtolower($log) . $secure_string_guest_login;
        $passwordg = substr(md5($mailg), 0, 8);
        if ($pwd == $passwordg){
            $cookie_guest = md5 ($auth_guest . $secure_string_cookie);
            $logok=$logok+1;
            setcookie('illinkid[nom]', strtolower($log), (time() + 36000));
            setcookie('illinkid[bib]', 'guest', (time() + 36000));
            setcookie('illinkid[aut]', $cookie_guest, (time() + 36000));
            setcookie('illinkid[log]', strtolower($log), (time() + 36000));
            header("$redirguest");
        }
        else
            $mes='Le login ou le password ne sont pas corrects';
    }
}
require ("includes/header.php");

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
if ($shibboleth == 1)
    echo "<a href=\"". $shibbolethurl . "\"><img src=\"img/shibboleth.png\" alt=\"Shibboleth authentication\" style=\"float:right;\"/></a>";
if (empty($log))
 $log='';

echo '
<div class="container">
	<div class="columns is-centered">
		<article class="card is-rounded">
			<div class="card-content">
				<h1 class="title">'.__("Log in").'</h1>
				<form name="loginform" id="loginform" action="login.php" method="post">
				<div class="field">
				<p class="control has-icon">
					<input class="input" type="text" name="log" id="log" value="' . htmlspecialchars($log) . '" placeholder="'.__("Username").'">
					 <span class="icon is-small is-left">
						<i class="fa fa-user"></i>
					</span>
				</p>
				</div>
				<div class="field">
				<p class="control has-icon">
					<input class="input" type="password" name="pwd" id="pwd" value="" placeholder="'.__("Password").'">
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