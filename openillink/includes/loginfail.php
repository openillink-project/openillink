<?php
// ***************************************************************************
// ***************************************************************************
// ***************************************************************************
// This file is part of OpenILLink software.
// Copyright (C) 2005, 2006, 2007, 2008, 2009, 2010, 2011, 2012, 2013, 2015, 2016, 2017 CHUV.
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
echo "<div class=\"box\"><div class=\"box-content\">\n";
echo "<b><font color=\"red\">Vous n'êtes pas autorisé à acceder à cette page ou votre session a expiré</font></b><br />\n";
// (MDV) allow loginfail to work for subdirectory pages as well
$loginPage = (is_readable ( "login.php" ))? "login.php" : "../login.php";
echo "<form name=\"loginform\" id=\"loginform\" action=\"$loginPage\" method=\"post\">\n";
echo "<p><label>Username:<br /><input type=\"text\" name=\"log\" id=\"log\" value=\"\" size=\"20\" tabindex=\"1\" /></label></p>\n";
echo "<p><label>Password:<br /> <input type=\"password\" name=\"pwd\" id=\"pwd\" value=\"\" size=\"20\" tabindex=\"2\" /></label></p>\n";
echo "<p>\n";
// echo "  <label><input name=\"rememberme\" type=\"checkbox\" id=\"rememberme\" value=\"forever\" tabindex=\"3\" /> \n";
// echo "  Garder en mémoire</label></p>\n";
// echo "<p>\n";
echo "	<input type=\"submit\" name=\"submit\" id=\"submit\" value=\"login\" tabindex=\"4\" />\n";
echo "	<input type=\"hidden\" name=\"redirect_to\" value=\"in/\" />\n";
echo "</p>\n";
echo "<br />\n";
echo "</div></div><div class=\"box-footer\"><div class=\"box-footer-right\"></div></div>\n";
echo "</form>\n";
if ($displayResendLink){
    echo '<p><a href="resendcredentials.php" target="_self"> Demander le mot de passe</a> : service seulement disponible pour les utilisateurs avec une commande openillink</p>';
}
?>
