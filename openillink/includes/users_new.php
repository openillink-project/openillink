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
// Table users : formulaire de création d'une nouvelle fiche
//
require_once ("config.php");
require ("authcookie.php");
require_once ("connexion.php");

if (!empty($_COOKIE['illinkid'])){
    if (($monaut == "admin")||($monaut == "sadmin")){
        $myhtmltitle = $configname[$lang] . " : ".__("New user");
        require ("headeradmin.php");
		echo '<nav class="breadcrumb" aria-label="breadcrumbs">
  <ul>
    <li><a href="admin.php">'.__("Administration").'</a></li>
    <li><a href="list.php?table=users">'.__("Users management").'</a></li>
	<li class="is-active"><a href="edit.php?table=users" aria-current="page">'.__("New user").'</a></li>
  </ul>
</nav>';
        echo "<h1 class=\"title\">".__("Users management : new user")."</h1>\n";
        echo "<br /></b>";
        echo "<ul>\n";
        echo "<form action=\"update.php\" method=\"POST\" enctype=\"x-www-form-encoded\" name=\"fiche\" id=\"fiche\">\n";
        echo "<input name=\"table\" type=\"hidden\" value=\"users\">\n";
        echo "<input name=\"action\" type=\"hidden\" value=\"new\">\n";
        echo "<table class=\"table is-striped\" id=\"hor-zebra\">\n";
        echo "<tr><td></td><td><input class=\"button is-primary\" type=\"submit\" value=\"".__("Save changes")."\">\n";
        echo "&nbsp;&nbsp;<input class=\"button\" type=\"button\" value=\"".__("Cancel")."\" onClick=\"self.location='list.php?table=users'\"></td></tr>\n";
        echo "<tr><td>&nbsp;</td><td>&nbsp;</td></tr>\n";
        echo "<tr><td class=\"odd\"><b>".__("Name")." *</b></td><td class=\"odd\"><input name=\"name\" type=\"text\" size=\"60\" value=\"\"></td></tr>\n";
        echo "<tr><td><b>".__("E-Mail")."</b></td><td><input name=\"email\" type=\"text\" size=\"60\" value=\"\"></td></tr>\n";
        echo "<tr><td class=\"odd\"><b>".__("Username")." *</b></td><td class=\"odd\"><input name=\"login\" type=\"text\" size=\"60\" value=\"\"></td></tr>\n";
        echo "<tr><td><b>".__("Status")." *</b></td><td><input type=\"radio\" name=\"status\" value=\"1\"/> ".__("Active")."  |  <input type=\"radio\" name=\"status\" value=\"0\"/> ".__("Inactive")."</td></tr>\n";
        echo "<tr><td class=\"odd\"><b>".__("Rights")." *</b></td><td class=\"odd\">\n";
        echo "<select name=\"admin\" id=\"admin\">\n";
        if ($monaut == "sadmin"){
            echo "<option value=\"1\">".__("Super Administrator")."</option>\n";
            echo "<option value=\"2\">".__("Administrator")."</option>\n";
        }
        echo "<option value=\"3\">".__("Collaborator")."</option>\n";
        echo "<option value=\"9\">".__("Guest")."</option>\n";
        echo "</select></td></tr>\n";
        echo "<tr><td><b>".__("Library")." *</b></td><td>\n";
        echo "<select name=\"library\">\n";
        $reqlibraries="SELECT code, name1, name2, name3, name4, name5 FROM libraries ORDER BY name1 ASC";
        $optionslibraries="";
        $resultlibraries = dbquery($reqlibraries);
        $nblibs = iimysqli_num_rows($resultlibraries);
        if ($nblibs > 0){
            while ($rowlibraries = iimysqli_result_fetch_array($resultlibraries)){
                $codelibraries = $rowlibraries["code"];
                $namelibraries["fr"] = $rowlibraries["name1"];
                $namelibraries["en"] = $rowlibraries["name2"];
                $namelibraries["de"] = $rowlibraries["name3"];
                $namelibraries["it"] = $rowlibraries["name4"];
                $namelibraries["es"] = $rowlibraries["name5"];
                $optionslibraries.="<option value=\"" . htmlspecialchars($codelibraries) . "\"";
                if ($monbib == $rowlibraries["code"])
                    $optionslibraries.=" selected=\"selected\" ";
                $optionslibraries.=">" . htmlspecialchars($namelibraries[$lang]) . "</option>\n";
            }
            echo $optionslibraries;
        }
        echo "</select></td></tr>\n";
        echo "<tr><td class=\"odd\"><b>".__("Password")." *</b></td><td class=\"odd\"><input name=\"newpassword1\" type=\"password\" size=\"30\" value=\"\"></td></tr>\n";
        echo "<tr><td><b>".__("Confirm password")." *</b></td><td><input name=\"newpassword2\" type=\"password\" size=\"30\" value=\"\"></td></tr>\n";
        echo "<tr><td>&nbsp;</td><td>&nbsp;</td></tr>\n";
        echo "<tr><td></td><td><input class=\"button is-primary\" type=\"submit\" value=\"".__("Save changes")."\">\n";
        echo "&nbsp;&nbsp;<input class=\"button\" type=\"button\" value=\"".__("Cancel")."\" onClick=\"self.location='list.php?table=users'\"></td></tr>\n";
        echo "</table>\n";
        echo "</form><br /><br />\n";
        require ("footer.php");
    }
    else {
        require ("header.php");
        echo "<center><br/><b><font color=\"red\">\n";
        echo __("Your rights are insufficient to edit this record")."<</b></font></center><br /><br /><br /><br />\n";
        require ("footer.php");
    }
}
else{
    require ("header.php");
    require ("loginfail.php");
    require ("footer.php");
}
?>
