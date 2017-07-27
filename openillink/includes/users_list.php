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
// Table users : Liste complète des utlisateurs
//
require_once("connexion.php");

if (($monaut == "admin")||($monaut == "sadmin")){
    $myhtmltitle = "Commandes de " . $configinstitution[$lang] . " : gestion des utilisateurs";
    require ("headeradmin.php");
    echo "\n";
    // Liste des utilisateurs
    echo "<h1>".__("Users management")."</h1>\n";
    $req = "SELECT * FROM users ORDER BY library ASC, name ASC";// LIMIT 0, 200";
    $result = dbquery($req);
    $total_results = iimysqli_num_rows($result);
    $nb = $total_results;
    // Construction du tableau de resultats
    echo "</center>\n";
    echo "<b><br/>".$total_results;
    if ($total_results == 1)
        echo " ".__("user found")."</b></font>\n";
    else
        echo " ".__("users found")."</b></font>\n";
    echo "<br/>";
    echo "<br/>";

    echo "<table id=\"one-column-emphasis\" summary=\"\">\n";
    echo "<colgroup>\n";
    echo "<col class=\"oce-first\" />\n";
    echo "</colgroup>\n";
    echo "\n";
    echo "<thead>\n";
    echo "<tr>\n";
    echo "<th scope=\"col\">".__("Nom")."</th>\n";
    echo "<th scope=\"col\">".__("E-Mail")."</th>\n";
    echo "<th scope=\"col\">".__("Library")."</th>\n";
    echo "<th scope=\"col\">".__("Username")."</th>\n";
    echo "<th scope=\"col\">".__("Rights")."</th>\n";
    echo "<th scope=\"col\">".__("Status")."</th>\n";
    echo "<th scope=\"col\"></th>\n";
    echo "</tr>\n";
    echo "</thead>\n";
    echo "<tbody>\n";
    for ($i=0 ; $i<$nb ; $i++){
        $enreg = iimysqli_result_fetch_array($result);
        $user_id = $enreg['user_id'];
        $name = $enreg['name'];
        $email = $enreg['email'];
        $login = $enreg['login'];
        $status = $enreg['status'];
        $admin = $enreg['admin'];
        $library = $enreg['library'];
        echo "<tr>\n";
        echo "<td><b>";
        echo htmlspecialchars($name);
        echo "</b></td>\n";
        echo "\n";
        echo "<td>".htmlspecialchars($email)."</b>\n";
        echo "</td>\n";
        echo "\n";
        echo "<td>".htmlspecialchars($library)."</b>\n";
        echo "</td>\n";
        echo "<td>";
        echo htmlspecialchars($login)."\n";
        echo "</td>\n";
        echo "<td>";
        if ($admin == 1)
            echo __("Super Administrator");
        if ($admin == 2)
            echo __("Administrator");
        if ($admin == 3)
            echo __("Collaborator");
        if ($admin > 3)
            echo __("Guest");
        echo "</td>\n";
        echo "<td>";
        if ($status == 1)
            echo __("Active");
        else
            echo __("Inactive");
        echo "</td>\n";
        if (($monaut == "admin")||($monaut == "sadmin")){
            echo "<td><a href=\"edit.php?table=users&id=".$user_id."\"><img src=\"img/edit.png\" title=\"".__("Edit")."\" width=\"20\"></a></td>";
        }
        echo "</tr>\n";
    }
    echo "</tbody>\n";
    echo "</table>\n";
    echo "\n";
    echo "<br/><br/><ul>\n";
    echo "<b><a href=\"new.php?table=users\">".__("Add a new user")."</a></b>\n";
    echo "<br/><br/>\n";
    echo "</ul>\n";
    require ("footer.php");
}
else{
    require ("header.php");
    echo __("Your rights are insufficient to edit this card")."</b></font></center><br /><br /><br /><br />\n";
    require ("footer.php");
}
?>
