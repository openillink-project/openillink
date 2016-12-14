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
// Status table : edit form
// 
require ("config.php");
require ("authcookie.php");
require_once ("connexion.php");
require_once ("toolkit.php");

$id="";
$montitle = "Gestion des status";
$id =  isValidInput($_GET['id'],11,'i',false)?$_GET['id']:'';
if (!empty($_COOKIE['illinkid'])){
    if (($monaut == "admin")||($monaut == "sadmin")){
        if ($id!=""){
            $req = "SELECT * FROM status WHERE id = ?";
            $myhtmltitle = $configname[$lang] . " : édition de l'étape de la commande " . $id;
            $montitle = "Gestion des étapes de la commande : édition de la fiche " . $id;
            require ("headeradmin.php");
            $result = dbquery($req, array($id), "i");
            $nb = iimysqli_num_rows($result);
            if ($nb == 1){
                echo "<h1>" . $montitle . "</h1>\n";
                echo "<br /></b>";
                echo "<ul>\n";
                $enreg = iimysqli_result_fetch_array($result);
                $statusid = $enreg['id'];
                $statuscode = $enreg['code'];
                $name["fr"] = $enreg['title1'];
                $name["en"] = $enreg['title2'];
                $name["de"] = $enreg['title3'];
                $name["it"] = $enreg['title4'];
                $name["es"] = $enreg['title5'];
                $help["fr"] = $enreg['help1'];
                $help["en"] = $enreg['help2'];
                $help["de"] = $enreg['help3'];
                $help["it"] = $enreg['help4'];
                $help["es"] = $enreg['help5'];
                $in = $enreg['in'];
                $out = $enreg['out'];
                $trash = $enreg['trash'];
                $special = $enreg['special'];
                $color = $enreg['color'];
                echo "<form action=\"update.php\" method=\"POST\" enctype=\"x-www-form-encoded\" name=\"fiche\" id=\"fiche\">\n";
                echo "<input name=\"table\" type=\"hidden\" value=\"status\">\n";
                echo "<input name=\"id\" type=\"hidden\" value=\"".htmlspecialchars($statusid)."\">\n";
                echo "<input name=\"action\" type=\"hidden\" value=\"update\">\n";
                echo "<table id=\"hor-zebra\">\n";
                echo "<tr><td></td><td><input type=\"submit\" value=\"Enregistrer les modifications\">\n";
                echo "&nbsp;&nbsp;<input type=\"button\" value=\"Annuler\" onClick=\"self.location='list.php?table=status'\">\n";
                echo "&nbsp;&nbsp;<input type=\"button\" value=\"Supprimer\" onClick=\"self.location='update.php?action=delete&table=status&id=" . htmlspecialchars($statusid) . "'\"></td></tr>\n";
                echo "<tr><td>&nbsp;</td><td>&nbsp;</td></tr>\n";
                echo "<tr><td><b>Code *</b></td><td>\n";
                echo "<input name=\"code\" type=\"text\" size=\"60\" value=\"" . htmlspecialchars($statuscode) . "\"></td></tr>\n";
                echo "</td></tr>\n";
                echo "<tr><td class=\"odd\"><b>".$guiLabelName1[$lang]." *</b></td><td class=\"odd\"><input name=\"title1\" type=\"text\" size=\"60\" value=\"" . htmlspecialchars($name["fr"]) . "\"></td></tr>\n";
                echo "<tr><td><b>".$guiLabelHelp1[$lang]."</b></td><td><input name=\"help1\" type=\"text\" size=\"60\" value=\"" . htmlspecialchars($help["fr"]) . "\"></td></tr>\n";
                echo "<tr><td class=\"odd\"><b>".$guiLabelName2[$lang]."</b></td><td class=\"odd\"><input name=\"title2\" type=\"text\" size=\"60\" value=\"" . htmlspecialchars($name["en"]) . "\"></td></tr>\n";
                echo "<tr><td><b>".$guiLabelHelp2[$lang]."</b></td><td><input name=\"help2\" type=\"text\" size=\"60\" value=\"" . htmlspecialchars($help["en"]) . "\"></td></tr>\n";
                echo "<tr><td class=\"odd\"><b>".$guiLabelName3[$lang]."</b></td><td class=\"odd\"><input name=\"title3\" type=\"text\" size=\"60\" value=\"" . htmlspecialchars($name["de"]) . "\"></td></tr>\n";
                echo "<tr><td><b>".$guiLabelHelp3[$lang]."</b></td><td><input name=\"help3\" type=\"text\" size=\"60\" value=\"" . htmlspecialchars($help["de"]) . "\"></td></tr>\n";
                echo "<tr><td class=\"odd\"><b>".$guiLabelName4[$lang]."</b></td><td class=\"odd\"><input name=\"title4\" type=\"text\" size=\"60\" value=\"" . htmlspecialchars($name["it"]) . "\"></td></tr>\n";
                echo "<tr><td><b>".$guiLabelHelp4[$lang]."</b></td><td><input name=\"help4\" type=\"text\" size=\"60\" value=\"" . htmlspecialchars($help["it"]) . "\"></td></tr>\n";
                echo "<tr><td class=\"odd\"><b>".$guiLabelName5[$lang]."</b></td><td class=\"odd\"><input name=\"title5\" type=\"text\" size=\"60\" value=\"" . htmlspecialchars($name["es"]) . "\"></td></tr>\n";
                echo "<tr><td><b>".$guiLabelHelp5[$lang]."</b></td><td><input name=\"help5\" type=\"text\" size=\"60\" value=\"" . htmlspecialchars($help["es"]) . "\"></td></tr>\n";
                echo "<tr><td class=\"odd\"><b>".$guiFolderIn[$lang]."</b></td><td class=\"odd\"><input name=\"in\" value=\"1\" type=\"checkbox\"";
                if ($in==1)
                    echo " checked";
                echo "></td></tr>\n";
                echo "<tr><td><b>".$guiFolderOut[$lang]."</b></td><td><input name=\"out\" value=\"1\" type=\"checkbox\"";
                if ($out==1)
                    echo " checked";
                echo "></td></tr>\n";
                echo "<tr><td class=\"odd\"><b>".$guiFolderTrash[$lang]."</b></td><td class=\"odd\"><input name=\"trash\" value=\"1\" type=\"checkbox\"";
                if ($trash==1)
                    echo " checked";
                echo "></td></tr>\n";
                echo "<tr><td><b>".$guiStatusSpecial[$lang]."</b></td><td>";
                echo "<select name=\"special\">\n";
                echo "<option value=\"\"></option>\n";
                echo "<option value=\"new\"";
                if ($special == "new")
                    echo " selected";
                echo ">Nouvelle commande (new)</option>\n";
                echo "<option value=\"sent\"";
                if ($special == "sent")
                    echo " selected";
                echo ">Commande envoyée (sent)</option>\n";
                echo "<option value=\"paid\"";
                if ($special == "paid")
                    echo " selected";
                echo ">Commande soldée (paid)</option>\n";
                echo "<option value=\"renew\"";
                if ($special == "renew")
                    echo " selected";
                echo ">Commande à renouveler (renew)</option>\n";
                echo "<option value=\"reject\"";
                if ($special == "reject")
                    echo " selected";
                echo ">Commande rejetée (reject)</option>\n";
                    echo "<option value=\"tobevalidated\"";
                if ($special == "tobevalidated")
                    echo " selected";
                echo ">Commande à valider (tobevalidated)</option>\n";
                echo "</select>\n";
                echo "</td></tr>\n";
                echo "<tr><td><b>".$guiColor[$lang]."</b></td><td><input name=\"color\" type=\"text\" size=\"60\" value=\"" . htmlspecialchars($color) . "\"></td></tr>\n";
                echo "<tr><td>&nbsp;</td><td>&nbsp;</td></tr>\n";
                echo "<tr><td></td><td><input type=\"submit\" value=\"Enregistrer les modifications\">\n";
                echo "&nbsp;&nbsp;<input type=\"button\" value=\"Annuler\" onClick=\"self.location='list.php?table=status'\">\n";
                echo "&nbsp;&nbsp;<input type=\"button\" value=\"Supprimer\" onClick=\"self.location='update.php?action=delete&table=status&id=" . htmlspecialchars($statusid) . "'\"></td></tr>\n";
                echo "</table>\n";
                echo "</form><br /><br />\n";
                require ("footer.php");
            }
            else{
                echo "<center><br/><b><font color=\"red\">\n";
                echo "La fiche " . htmlspecialchars($id) . " n'a pas été trouvée dans la base.</b></font>\n";
                echo "<br /><br /><b>Veuillez relancer de nouveau votre recherche ou contactez l'administrateur de la base : " . $configemail . "</b></center><br /><br /><br /><br />\n";
                require ("footer.php");
            }
        }
        else{
            require ("header.php");
            //require ("menurech.php");
            echo "<center><br/><b><font color=\"red\">\n";
            echo "La fiche n'a pas été trouvée dans la base.</b></font>\n";
            echo "<br /><br /><b>Veuillez relancer de nouveau votre recherche ou contactez l'administrateur de la base : " . $configemail . "</b></center><br /><br /><br /><br />\n";
            echo "<br /><br />\n";
            echo "</ul>\n";
            echo "\n";
            require ("footer.php");
        }
    }
    else{
        require ("header.php");
        echo "<center><br/><b><font color=\"red\">\n";
        echo "Vos droits sont insuffisants pour éditer cette fiche</b></font></center><br /><br /><br /><br />\n";
        require ("footer.php");
    }
}
else{
    require ("header.php");
    require ("loginfail.php");
    require ("footer.php");
}
?>
