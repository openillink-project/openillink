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
// Status table : record creation form
// 
require ("config.php");
require ("authcookie.php");
if (!empty($_COOKIE['illinkid'])){
    if (($monaut == "admin")||($monaut == "sadmin")){
        $myhtmltitle = $configname[$lang] . " : nouvelle étape de la commande ";
        require ("headeradmin.php");
        echo "<h1>Gestion des étapes de la commande : Création d'une nouvelle fiche </h1>\n";
        echo "<br /></b>";
        echo "<ul>\n";
        echo "<form action=\"update.php\" method=\"POST\" enctype=\"x-www-form-encoded\" name=\"fiche\" id=\"fiche\">\n";
        echo "<input name=\"table\" type=\"hidden\" value=\"status\">\n";
        echo "<input name=\"action\" type=\"hidden\" value=\"new\">\n";
        echo "<table id=\"hor-zebra\">\n";
        echo "<tr><td></td><td><input type=\"submit\" value=\"Enregistrer la nouvelle étape\">\n";
        echo "&nbsp;&nbsp;<input type=\"button\" value=\"Annuler\" onClick=\"self.location='list.php?table=status'\"></td></tr>\n";
        echo "<tr><td>&nbsp;</td><td>&nbsp;</td></tr>\n";
        echo "<tr><td><b>Code (numérique) *</b></td><td>\n";
        echo "<input name=\"code\" type=\"text\" size=\"6\" maxlength=\"6\" value=\"\"></td></tr>\n";
        echo "</td></tr>\n";
        echo "<tr><td class=\"odd\"><b>".$guiLabelName1[$lang]." *</b></td><td class=\"odd\"><input name=\"title1\" type=\"text\" size=\"60\" value=\"\"></td></tr>\n";
        echo "<tr><td><b>".$guiLabelHelp1[$lang]."</b></td><td><input name=\"help1\" type=\"text\" size=\"60\" value=\"\"></td></tr>\n";
        echo "<tr><td class=\"odd\"><b>".$guiLabelName2[$lang]."</b></td><td class=\"odd\"><input name=\"title2\" type=\"text\" size=\"60\" value=\"\"></td></tr>\n";
        echo "<tr><td><b>".$guiLabelHelp2[$lang]."</b></td><td><input name=\"help2\" type=\"text\" size=\"60\" value=\"\"></td></tr>\n";
        echo "<tr><td class=\"odd\"><b>".$guiLabelName3[$lang]."</b></td><td class=\"odd\"><input name=\"title3\" type=\"text\" size=\"60\" value=\"\"></td></tr>\n";
        echo "<tr><td><b>".$guiLabelHelp3[$lang]."</b></td><td><input name=\"help3\" type=\"text\" size=\"60\" value=\"\"></td></tr>\n";
        echo "<tr><td class=\"odd\"><b>".$guiLabelName4[$lang]."</b></td><td class=\"odd\"><input name=\"title4\" type=\"text\" size=\"60\" value=\"\"></td></tr>\n";
        echo "<tr><td><b>".$guiLabelHelp4[$lang]."</b></td><td><input name=\"help4\" type=\"text\" size=\"60\" value=\"\"></td></tr>\n";
        echo "<tr><td class=\"odd\"><b>".$guiLabelName5[$lang]."</b></td><td class=\"odd\"><input name=\"title5\" type=\"text\" size=\"60\" value=\"\"></td></tr>\n";
        echo "<tr><td><b>".$guiLabelHelp5[$lang]."</b></td><td><input name=\"help5\" type=\"text\" size=\"60\" value=\"\"></td></tr>\n";
        echo "<tr><td class=\"odd\"><b>".$guiFolderIn[$lang]."</b></td><td class=\"odd\"><input name=\"in\" value=\"1\" type=\"checkbox\"></td></tr>\n";
        echo "<tr><td><b>".$guiFolderOut[$lang]."</b></td><td><input name=\"out\" value=\"1\" type=\"checkbox\"></td></tr>\n";
        echo "<tr><td class=\"odd\"><b>".$guiFolderTrash[$lang]."</b></td><td class=\"odd\"><input name=\"trash\" value=\"1\" type=\"checkbox\"></td></tr>\n";
        echo "<tr><td><b>".$guiStatusSpecial[$lang]."</b></td><td>";
        echo "<select name=\"special\">\n";
        echo "<option value=\"\"></option>\n";
        echo "<option value=\"new\">Nouvelle commande (new)</option>\n";
        echo "<option value=\"sent\">Commande envoyée (sent)</option>\n";
        echo "<option value=\"paid\">Commande soldée (paid)</option>\n";
        echo "<option value=\"renew\">Commande à renouveler (renew)</option>\n";
        echo "<option value=\"reject\">Commande rejetée (reject)</option>\n";
        echo "<option value=\"tobevalidated\">Commande à valider (tobevalidated)</option>\n";
        echo "</select>\n";
        echo "</td></tr>\n";
        echo "<tr><td class=\"odd\"><b>".$guiColor[$lang]."</b></td><td class=\"odd\"><input name=\"color\" type=\"text\" size=\"60\" value=\"\"></td></tr>\n";
        echo "<tr><td>&nbsp;</td><td>&nbsp;</td></tr>\n";
        echo "<tr><td></td><td><input type=\"submit\" value=\"Enregistrer la nouvelle étape\">\n";
        echo "&nbsp;&nbsp;<input type=\"button\" value=\"Annuler\" onClick=\"self.location='list.php?table=status'\"></td></tr>\n";
        echo "</table>\n";
        echo "</form><br /><br />\n";
        require ("footer.php");
    }
    else{
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
