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
// Libraries table : List of all the libraries defined on the internal ILL network
// 

require_once ("config.php");
require ("authcookie.php");
require_once ("connexion.php");

if (!empty($_COOKIE['illinkid'])){
  if (($monaut == "admin")||($monaut == "sadmin")){
    $myhtmltitle = $configname[$lang] . " : ".__("users management");
    require ("headeradmin.php");
    echo "\n";

    // Libraries List
    echo "<h1>".__("Managing network libraries")."</h1>\n";
    $req = "SELECT * FROM libraries ORDER BY name1 ASC";// LIMIT ?, ?";
    $result = dbquery($req);//, array(0,200), 'ii');
    $total_results = iimysqli_num_rows($result);
    $nb = $total_results;

    // Construction du tableau de resultats
    echo "</center>\n";
    echo "<b><br/>".$total_results;
    if ($total_results == 1)
        echo " ".__("library found")."</b></font>\n";
    else
        echo " ".__("libraries found")."</b></font>\n";
    echo "<br/>";
    echo "<br/>";

    echo "<table id=\"one-column-emphasis\" summary=\"\">\n";
    echo "<colgroup>\n";
    echo "<col class=\"oce-first\" />\n";
    echo "</colgroup>\n";
    echo "\n";
    echo "<thead>\n";
    echo "<tr>\n";
    echo "<th scope=\"col\">code</th>\n";
    echo "<th scope=\"col\">".__("Name in French")."</th>\n";
// echo "<th scope=\"col\">name2</th>\n";
// echo "<th scope=\"col\">name3</th>\n";
// echo "<th scope=\"col\">name4</th>\n";
// echo "<th scope=\"col\">name5</th>\n";
    echo "<th scope=\"col\">".__("default")."</th>\n";
    echo "<th scope=\"col\">".__("Shared incoming orders")."</th>\n";
    echo "<th scope=\"col\">".__("Edit")."</th>\n";
    echo "</tr>\n";
    echo "</thead>\n";
    echo "<tbody>\n";
    for ($i=0 ; $i<$nb ; $i++){
        $enreg = iimysqli_result_fetch_array($result);
        $libid = $enreg['id'];
        $libcode = $enreg['code'];
        $libname1 = $enreg['name1'];
        $libname2 = $enreg['name2'];
        $libname3 = $enreg['name3'];
        $libname4 = $enreg['name4'];
        $libname5 = $enreg['name5'];
        $libdef = $enreg['default'];
        $hasSharedOrders = $enreg['has_shared_ordres'];
        echo "<tr>\n";
        echo "<td><b>" . htmlspecialchars($libcode) . "</b></td>\n";
        echo "<td>".htmlspecialchars($libname1)."</td>\n";
        // echo "<td>".$libname2."</td>\n";
        // echo "<td>".$libname3."</td>\n";
        // echo "<td>".$libname4."</td>\n";
        // echo "<td>".$libname5."</td>\n";
        echo "<td>".htmlspecialchars($libdef)."</td>\n";
        echo "<td>".htmlspecialchars($hasSharedOrders)."</td>\n";
        if (($monaut == "admin")||($monaut == "sadmin")){
            echo "<td><a href=\"edit.php?table=libraries&id=".htmlspecialchars($libid)."\"><img src=\"img/edit.png\" title=\"".__("Edit the card")."\" width=\"20\"></a></td>";
        }
        echo "</tr>\n";
    }
    echo "</tbody>\n";
    echo "</table>\n";
    echo "\n";
    echo "<br/><br/><ul>\n";
    echo "<b><a href=\"new.php?table=libraries\">".__("Add a new library")."</a></b>\n";
    echo "<br/><br/>\n";
    echo "</ul>\n";
    require ("footer.php");
  }
  else {
    require ("header.php");
    echo __("Your rights are insufficient to edit this page")."</b></font></center><br /><br /><br /><br />\n";
    require ("footer.php");
  }
}
else {
  require ("header.php");
  require ("loginfail.php");
  require ("footer.php");
}
?>
