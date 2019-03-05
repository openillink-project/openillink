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
// Home page for administrators

require_once ("includes/config.php");
require_once ("includes/authcookie.php");
require_once ("includes/connexion.php");
require_once ("includes/toolkit.php");

if (!empty($_COOKIE['illinkid'])){
    if (($monaut == "admin")||($monaut == "sadmin")){
        $action = "";
        $action= (!empty($_POST['action'])) && isValidInput($_POST['action'],9,'s',false,array('deleteall'))?$_POST['action']:'';
        $pagetitle = format_string(__("%institution_name orders: empty trash"), array('institution_name' => $configinstitution[$lang]));
        require ("includes/headeradmin.php");
        echo "\n";
		echo '<nav class="breadcrumb" aria-label="breadcrumbs">
  <ul>
    <li><a href="admin.php">'.__("Administration").'</a></li>
    <li class="is-active"><a href="emptytrash.php" aria-current="page">'.__("Empty trash").'</a></li>
  </ul>
</nav>';
        // Liens pour les administrateurs
        echo "<h1 class=\"title\">". __("Empty trash") ."</h1>\n";
        echo "<br/>\n";
        if ($action == "deleteall"){
            $query = "DELETE orders.* FROM status, orders WHERE orders.stade = status.code AND status.trash = 1";
            $result = dbquery($query);
            if ($result){
                echo "<center><br/><b><font color=\"green\">\n";
                echo __("The trash was successfully deleted") ."</b></font>\n";
            }
            else{
               echo "<center><br/><b><font color=\"red\">\n";
               echo __("The trash could not be deleted") ."</b></font>\n";
            }
            echo "<br/><br/><br/><a href=\"list.php?table=orders\">". __("Return to the orders list") ."</a></center>\n";
            echo "</center>\n";
            echo "\n";
            require ("includes/footer.php");
        }
        else{
            echo "<center><br/><br/><br/><b><font color=\"red\">\n";
            echo __("Do you really want to empty the trash definitely?") ."</b></font>\n";
            echo "<form action=\"emptytrash.php\" method=\"POST\" enctype=\"x-www-form-encoded\" name=\"fiche\" id=\"fiche\">\n";
            echo "<input name=\"action\" type=\"hidden\" value=\"deleteall\">\n";
            echo "<br /><br />\n";
            echo "<input type=\"submit\" value=\"". __("Confirm deleting orders in the trash by clicking here") ."\">\n";
            echo "</form>\n";
            echo "<br/><br/><br/><a href=\"list.php?table=orders\">". __("Return to the orders list") ."</a></center>\n";
            echo "</center>\n";
            echo "\n";
            require ("includes/footer.php");
        }
    }
    else {
        require ("includes/header.php");
        echo "<center><br/><b><font color=\"red\">\n";
        echo __("Your rights are insufficient to view this page") ."</b></font></center><br /><br /><br /><br />\n";
        require ("includes/footer.php");
    }
}
else{
    require ("includes/header.php");
    require ("includes/loginfail.php");
    require ("includes/footer.php");
}
?>
