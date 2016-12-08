<?php
// ***************************************************************************
// ***************************************************************************
// ***************************************************************************
// OpenLinker is a web based library system designed to manage 
// journals, ILL, document delivery and OpenURL links
// 
// Copyright (C) 2012, Pablo Iriarte
// 
// This program is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
// 
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
// 
// You should have received a copy of the GNU General Public License
// along with this program.  If not, see <http://www.gnu.org/licenses/>.
// 
// ***************************************************************************
// ***************************************************************************
// ***************************************************************************
// Libraries table : edit form
// 
// 11.03.2016, MDV Replaced connector to db from mysql_ to mysqli_
// 21.03.2016, MDV Input reading verification
// 01.04.2016, MDV suppressed reference to undefined local file menurech.php

require_once ("config.php");
require_once ("authcookie.php");
require_once ("connexion.php");
require_once ("includes/toolkit.php");

$montitle = "Gestion des bibliothèques";
$id = ((!empty($_GET['id'])) && isValidInput($_GET['id'],11,'s',false)) ? $_GET['id']:"";
if (!empty($_COOKIE['illinkid'])){
    if (($monaut == "admin")||($monaut == "sadmin")){
        if ($id!=""){
            $req = "SELECT * FROM libraries WHERE id = ?";
            $myhtmltitle = "Commandes de l'".$configinstitution[$lang].": édition de la bibliothèque ". htmlspecialchars($id);
            $montitle = "Gestion des bibliothèques : édition de la fiche " . htmlspecialchars($id);
            require ("headeradmin.php");
            $result = dbquery($req, array($id), 's');
            $nb = iimysqli_num_rows($result);
            if ($nb == 1){
                echo "<h1>" . $montitle . "</h1>\n";
                echo "<br /></b>";
                echo "<ul>\n";
                $enreg = iimysqli_result_fetch_array($result);
                $libid = $enreg['id'];
                $libcode = $enreg['code'];
                $name["fr"] = $enreg['name1'];
                $name["en"] = $enreg['name2'];
                $name["de"] = $enreg['name3'];
                $name["it"] = $enreg['name4'];
                $name["es"] = $enreg['name5'];
                $libdef = $enreg['default'];
                $hasSharedOrders = $enreg['has_shared_ordres'];
				$signature = $enreg['signature'];
                echo "<form action=\"update.php\" method=\"POST\" enctype=\"x-www-form-encoded\" name=\"fiche\" id=\"fiche\">\n";
                echo "<input name=\"table\" type=\"hidden\" value=\"libraries\">\n";
                echo "<input name=\"id\" type=\"hidden\" value=\"".$libid."\">\n";
                echo "<input name=\"action\" type=\"hidden\" value=\"update\">\n";
                echo "<table id=\"hor-zebra\">\n";
                echo "<tr><td></td><td><input type=\"submit\" value=\"Enregistrer les modifications\">\n";
                echo "&nbsp;&nbsp;<input type=\"button\" value=\"Annuler\" onClick=\"self.location='list.php?table=libraries'\">\n";
                echo "&nbsp;&nbsp;<input type=\"button\" value=\"Supprimer\" onClick=\"self.location='update.php?action=delete&table=libraries&id=" . htmlspecialchars($libid) . "'\"></td></tr>\n";
                echo "<tr><td>&nbsp;</td><td>&nbsp;</td></tr>\n";
                echo "<tr><td><b>Code *</b></td><td>\n";
                echo "<input name=\"code\" type=\"text\" size=\"30\" value=\"" . htmlspecialchars($libcode) . "\"></td></tr>\n";
                echo "</td></tr>\n";
                echo "<tr><td class=\"odd\"><b>".$guiLabelName1[$lang]." *</b></td><td class=\"odd\"><input name=\"name1\" type=\"text\" size=\"30\" value=\"" . htmlspecialchars($name["fr"]) . "\"></td></tr>\n";
                echo "<tr><td><b>".$guiLabelName2[$lang]."</b></td><td><input name=\"name2\" type=\"text\" size=\"30\" value=\"" . htmlspecialchars($name["en"]) . "\"></td></tr>\n";
                echo "<tr><td class=\"odd\"><b>".$guiLabelName3[$lang]."</b></td><td class=\"odd\"><input name=\"name3\" type=\"text\" size=\"30\" value=\"" . htmlspecialchars($name["de"]) . "\"></td></tr>\n";
                echo "<tr><td><b>".$guiLabelName4[$lang]."</b></td><td><input name=\"name4\" type=\"text\" size=\"30\" value=\"" . htmlspecialchars($name["it"]) . "\"></td></tr>\n";
                echo "<tr><td class=\"odd\"><b>".$guiLabelName5[$lang]."</b></td><td class=\"odd\"><input name=\"name5\" type=\"text\" size=\"30\" value=\"" . htmlspecialchars($name["es"]) . "\"></td></tr>\n";
                echo "<tr><td><b>Default</b></td><td><input name=\"default\" value=\"1\" type=\"checkbox\"";
                if ($libdef==1)
                    echo " checked";
                echo "></td></tr>\n";
                echo "<tr><td class=\"odd\"><b>Afficher les ordres entrants pour cette bibliothèque avec la bibliothèque principale</b></td><td class=\"odd\"><input name=\"hasSharedOrders\" value=\"1\" type=\"checkbox\"";
                if ($hasSharedOrders==1)
                    echo " checked";
                echo "></td></tr>\n";
				echo "<tr><td>&nbsp;</td><td>&nbsp;</td></tr>\n";
				echo '<tr><td colspan="2">';
				echo '<b><label for="signature">'.$guiLibrarySignature[$lang]. '</label></b><br/>';
				echo '<textarea id="signature" name="signature" rows="5" cols="80">'. htmlspecialchars($signature) . '</textarea>';
				echo '</td></tr>';
                echo "<tr><td>&nbsp;</td><td>&nbsp;</td></tr>\n";
                echo "<tr><td></td><td><input type=\"submit\" value=\"Enregistrer les modifications\">\n";
                echo "&nbsp;&nbsp;<input type=\"button\" value=\"Annuler\" onClick=\"self.location='list.php?table=libraries'\">\n";
                echo "&nbsp;&nbsp;<input type=\"button\" value=\"Supprimer\" onClick=\"self.location='update.php?action=delete&table=libraries&id=" . htmlspecialchars($libid) . "'\"></td></tr>\n";
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
