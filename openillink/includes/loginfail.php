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
// Message displayed if the login fails or if the permissions are fewer than required
//

echo '
	<div class="container">
	<div class="columns is-centered">
	<article class="message is-danger">
  <div class="message-body">
    '. __("You are not allowed to access this page or your session has expired") .'
  </div>
</article></div></div><br/><br/>';
if ($shibboleth == 1){
    echo "<a href=\"". $shibbolethurl . "\"><img src=\"img/shibboleth.png\" alt=\"Shibboleth authentication\" style=\"float:right;\"/></a>";
}
$loginPage = (is_readable ( "login.php" ))? "login.php" : "../login.php";
echo '
<div class="container">
	<div class="columns is-centered">
		<article class="card is-rounded">
			<div class="card-content">
				<h1 class="title">'.__("Log in").'</h1>
				<form name="loginform" id="loginform" action="'.$loginPage.'" method="post">
				<div class="field">
				<p class="control has-icon">
					<input class="input" type="text" name="log" id="log" value="" placeholder="'.__("Username").'">
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
echo '
				<p class="control">
					<input type="submit" name="submit" id="submit" class="button is-primary is-fullwidth" value="'.__("Login").'" />
					<input type="hidden" name="redirect_to" value="/" />
				</p>
				</form>
			</div>
		</article>
	</div>';
if ($displayResendLink){
	echo '
	<div class="columns is-centered section">
		<p><a href="resendcredentials.php" target="_self"> '.__("Request password").'</a> : '.__("Service only available to users with an openillink command").'</p>
	</div>';}
?>
