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
require ("includes/config.php");
require_once ("includes/authcookie.php");
if (!empty($_COOKIE['illinkid'])){
    if (($monaut == "admin")||($monaut == "sadmin")||($monaut == "user")){
        require ("includes/headeradmin.php");
        echo "\n";
        echo "<br/><br/>\n";
        // Liens pour les administrateurs
        echo "<h1 class=\"title\">Administration</h1>\n";
        echo "<br/>\n";
		echo "<div class=\"keepLists\">";
        echo "<ul>\n";
        // echo "<li><h2 class=\"is-size-5\"><a href=\"adminsearch.php\">Recherche administrateur</a></h2></li>\n";
        if (($monaut == "admin")||($monaut == "sadmin")){
            echo "<li><h2 class=\"is-size-5\"><a href=\"list.php?table=users\"><i class=\"fas fa-users\"></i> ". __("User management") ."</a></h2></li>\n";
            echo "<ul><li><h3><a href=\"new.php?table=users\">". __("Create a new user") ."</a></h3></li>\n";
            echo "<li><h3><a href=\"edit.php?table=users&action=updateprofile\">". __("Change my access codes") ."</a></h2></li></ul>\n";
            echo "<li><h2 class=\"is-size-5\"><a href=\"list.php?table=libraries\"><i class=\"fas fa-university\"></i> ". __("Librairies management") ."</a></h2></li>\n";
            echo "<ul><li><h3><a href=\"new.php?table=libraries\">". __("Create a new library") ."</a></h2></li></ul>\n";
            echo "<li><h2 class=\"is-size-5\"><a href=\"list.php?table=localizations\"><i class=\"fas fa-map-marked-alt\"></i> ". __("Location management") ."</a></h2></li>\n";
            echo "<ul><li><h3><a href=\"new.php?table=localizations\">". __("Create a new location") ."</a></h2></li></ul>\n";
            echo "<li><h2 class=\"is-size-5\"><a href=\"list.php?table=status\"><i class=\"fas fa-code-branch\"></i> ". __("Order steps management") ."</a></h2></li>\n";
            echo "<ul><li><h3><a href=\"new.php?table=status\">". __("Create a new order step") ."</a></h2></li></ul>\n";
            echo "<li><h2 class=\"is-size-5\"><a href=\"list.php?table=units\"><i class=\"fas fa-sitemap\"></i> ". __("Management of units / services") ."</a></h2></li>\n";
            echo "<ul><li><h3><a href=\"new.php?table=units\">". __("Create a new unit / service") ."</a></h2></li></ul>\n";
            echo "<li><h2 class=\"is-size-5\"><a href=\"list.php?table=links\"><i class=\"fas fa-link\"></i> ". __("Managing outgoings links") ."</a></h2></li>\n";
            echo "<ul><li><h3><a href=\"new.php?table=links\">". __("Create a new outgoing link") ."</a></h2></li></ul>\n";
            echo "<li><h2 class=\"is-size-5\"><a href=\"list.php?table=folders\"><i class=\"far fa-folder-open\"></i> ". __("Filters management") ."</a></h2></li>\n";
            echo "<ul><li><h3><a href=\"new.php?table=folders\">". __("Create a new filter") ."</a></h2></li></ul>\n";
            echo "<br/><br/>\n";
            echo "<li><h2 class=\"is-size-5\"><a href=\"anonymizeorders.php\"><i class=\"fas fa-mask\"></i>&nbsp;" . __("Anonymize old orders") . "</a></h2></li>\n";
            echo "<br/>\n";
            echo "<li><h2 class=\"is-size-5\"><a href=\"emptytrash.php\"><i class=\"far fa-trash-alt\"></i>" . __("Empty trash") . "</a></h2></li>\n";
        }
        else
            echo "<li><h2 class=\"is-size-5\"><a href=\"edit.php?table=users&action=updateprofile\">". __("Change my access codes") ."</a></h2></li>\n";
        echo "</ul>\n";
		echo "</div>";
        echo "<br/><br/>\n";
        echo "\n";
        require ("includes/footer.php");
    }
    else{
        require ("includes/header.php");
        require ("includes/loginfail.php");
        require ("includes/footer.php");
    }
}
else{
    require ("includes/header.php");
    require ("includes/loginfail.php");
    require ("includes/footer.php");
}
?>
