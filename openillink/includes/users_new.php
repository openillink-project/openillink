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
// Table users : formulaire de création d'une nouvelle fiche
//
require ("config.php");
require ("authcookie.php");
require_once ("connexion.php");

if (!empty($_COOKIE['illinkid'])){
    if (($monaut == "admin")||($monaut == "sadmin")){
        $myhtmltitle = "Commandes de " . $configinstitution[$lang] . " : nouvelle fiche utilisateur ";
        require ("headeradmin.php");
        echo "<h1>Gestion des utilisateurs : Création d'une nouvelle fiche </h1>\n";
        echo "<br /></b>";
        echo "<ul>\n";
        echo "<form action=\"update.php\" method=\"POST\" enctype=\"x-www-form-encoded\" name=\"fiche\" id=\"fiche\">\n";
        echo "<input name=\"table\" type=\"hidden\" value=\"users\">\n";
        echo "<input name=\"action\" type=\"hidden\" value=\"new\">\n";
        echo "<table id=\"hor-zebra\">\n";
        echo "<tr><td></td><td><input type=\"submit\" value=\"Enregistrer\">\n";
        echo "&nbsp;&nbsp;<input type=\"button\" value=\"Annuler\" onClick=\"self.location='list.php?table=users'\"></td></tr>\n";
        echo "<tr><td>&nbsp;</td><td>&nbsp;</td></tr>\n";
        echo "<tr><td class=\"odd\"><b>Nom *</b></td><td class=\"odd\"><input name=\"name\" type=\"text\" size=\"60\" value=\"\"></td></tr>\n";
        echo "<tr><td><b>E-mail</b></td><td><input name=\"email\" type=\"text\" size=\"60\" value=\"\"></td></tr>\n";
        echo "<tr><td class=\"odd\"><b>Login *</b></td><td class=\"odd\"><input name=\"login\" type=\"text\" size=\"60\" value=\"\"></td></tr>\n";
        echo "<tr><td><b>Status *</b></td><td><input type=\"radio\" name=\"status\" value=\"1\"/> Actif  |  <input type=\"radio\" name=\"status\" value=\"0\"/> Inactif</td></tr>\n";
        echo "<tr><td class=\"odd\"><b>Droits *</b></td><td class=\"odd\">\n";
        echo "<select name=\"admin\" id=\"admin\">\n";
        if ($monaut == "sadmin"){
            echo "<option value=\"1\">Super administrateur</option>\n";
            echo "<option value=\"2\">Administrateur</option>\n";
        }
        echo "<option value=\"3\">Collaborateur</option>\n";
        echo "<option value=\"9\">Invité</option>\n";
        echo "</select></td></tr>\n";
        echo "<tr><td><b>Bibliothèque *</b></td><td>\n";
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
        echo "<tr><td class=\"odd\"><b>Password *</b></td><td class=\"odd\"><input name=\"newpassword1\" type=\"password\" size=\"30\" value=\"\"></td></tr>\n";
        echo "<tr><td><b>Confirmation du password *</b></td><td><input name=\"newpassword2\" type=\"password\" size=\"30\" value=\"\"></td></tr>\n";
        echo "<tr><td>&nbsp;</td><td>&nbsp;</td></tr>\n";
        echo "<tr><td></td><td><input type=\"submit\" value=\"Enregistrer\">\n";
        echo "&nbsp;&nbsp;<input type=\"button\" value=\"Annuler\" onClick=\"self.location='list.php?table=users'\"></td></tr>\n";
        echo "</table>\n";
        echo "</form><br /><br />\n";
        require ("footer.php");
    }
    else {
        require ("header.php");
        echo "<center><br/><b><font color=\"red\">\n";
        echo "Vos droits sont insuffisants pour consulter cette page</b></font></center><br /><br /><br /><br />\n";
        require ("footer.php");
    }
}
else{
    require ("header.php");
    require ("loginfail.php");
    require ("footer.php");
}
?>
