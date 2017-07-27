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
// Localizations table : edit form
// 
require_once ("config.php");
require_once ("authcookie.php");
require_once ("connexion.php");
require_once ("includes/toolkit.php");

$montitle = __("Localizations management");
$id= ((!empty($_GET['id'])) && isValidInput($_GET['id'],11,'s',false)) ? $_GET['id'] : "";
if (!empty($_COOKIE['illinkid'])){
    if (($monaut == "admin")||($monaut == "sadmin")){
        if ($id!=""){
            $myhtmltitle = $configname[$lang] . " : ".format_string(__("edition of localization %id"), array('id' => $id));
            $montitle = format_string(__("Localizations management : edition of card %id_card"), array('id_card' => htmlspecialchars($id)));
            require ("headeradmin.php");
            $req = "SELECT * FROM localizations WHERE id = ?";
            $result = dbquery($req, array($id), 'i');
            $nb = iimysqli_num_rows($result);
            if ($nb == 1){
                echo "<h1>" . $montitle . "</h1>\n";
                echo "<br /></b>";
                echo "<ul>\n";
                $enreg = iimysqli_result_fetch_array($result);
                $locid = $enreg['id'];
                $loccode = $enreg['code'];
                $loclibrarary = $enreg['library'];
                $name["fr"] = $enreg['name1'];
                $name["en"] = $enreg['name2'];
                $name["de"] = $enreg['name3'];
                $name["it"] = $enreg['name4'];
                $name["es"] = $enreg['name5'];
                echo "<form action=\"update.php\" method=\"POST\" enctype=\"x-www-form-encoded\" name=\"fiche\" id=\"fiche\">\n";
                echo "<input name=\"table\" type=\"hidden\" value=\"localizations\">\n";
                echo "<input name=\"id\" type=\"hidden\" value=\"".htmlspecialchars($locid)."\">\n";
                echo "<input name=\"action\" type=\"hidden\" value=\"update\">\n";
                echo "<table id=\"hor-zebra\">\n";
                echo "<tr><td></td><td><input type=\"submit\" value=\"".__("Save changes")."\">\n";
                echo "&nbsp;&nbsp;<input type=\"button\" value=\"".__("Cancel")."\" onClick=\"self.location='list.php?table=localizations'\">\n";
                echo "&nbsp;&nbsp;<input type=\"button\" value=\"".__("Remove")."\" onClick=\"self.location='update.php?action=delete&table=localizations&id=" . htmlspecialchars($locid) . "'\"></td></tr>\n";
                echo "<tr><td>&nbsp;</td><td>&nbsp;</td></tr>\n";
                echo "<tr><td><b>".__("Code")." *</b></td><td>\n";
                echo "<input name=\"code\" type=\"text\" size=\"60\" value=\"" . htmlspecialchars($loccode) . "\"></td></tr>\n";
                echo "</td></tr>\n";
                echo "<tr><td class=\"odd\"><b>".__("Name in French")." *</b></td><td class=\"odd\"><input name=\"name1\" type=\"text\" size=\"60\" value=\"" . htmlspecialchars($name["fr"]) . "\"></td></tr>\n";
                echo "<tr><td><b>".__("Name in English")."</b></td><td><input name=\"name2\" type=\"text\" size=\"60\" value=\"" . htmlspecialchars($name["en"]) . "\"></td></tr>\n";
                echo "<tr><td class=\"odd\"><b>".__("Name in German")."</b></td><td class=\"odd\"><input name=\"name3\" type=\"text\" size=\"60\" value=\"" . htmlspecialchars($name["de"]) . "\"></td></tr>\n";
                echo "<tr><td><b>".__("Name in Italian")."</b></td><td><input name=\"name4\" type=\"text\" size=\"60\" value=\"" . htmlspecialchars($name["it"]) . "\"></td></tr>\n";
                echo "<tr><td class=\"odd\"><b>".__("Name in Spanish")."</b></td><td class=\"odd\"><input name=\"name5\" type=\"text\" size=\"60\" value=\"" . htmlspecialchars($name["es"]) . "\"></td></tr>\n";
                echo "<tr><td><b>".__("Assignment library")."</b></td><td>\n";
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
                        if ($loclibrarary == $codelibraries)
                            $optionslibraries.=" selected";
                        $optionslibraries.=">" . htmlspecialchars($namelibraries[$lang]) . "</option>\n";
                    }
                    echo $optionslibraries;
                }
                echo "</select></td></tr>\n";
                echo "<tr><td>&nbsp;</td><td>&nbsp;</td></tr>\n";
                echo "<tr><td></td><td><input type=\"submit\" value=\"".__("Save changes")."\">\n";
                echo "&nbsp;&nbsp;<input type=\"button\" value=\"".__("Cancel")."\" onClick=\"self.location='list.php?table=localizations'\">\n";
                echo "&nbsp;&nbsp;<input type=\"button\" value=\"".__("Remove")."\" onClick=\"self.location='update.php?action=delete&table=localizations&id=" . htmlspecialchars($locid) . "'\"></td></tr>\n";
                echo "</table>\n";
                echo "</form><br /><br />\n";
                require ("footer.php");
            }
            else{
                echo "<center><br/><b><font color=\"red\">\n";
                echo format_string(__("The card %id_card was not found in the database."), array('id_card' => htmlspecialchars($id)))."</b></font>\n";
                echo "<br /><br /><b>".__("Please restart your search or contact the database administrator")." : " . $configemail . "</b></center><br /><br /><br /><br />\n";
                require ("footer.php");
            }
        }
        else{
            require ("header.php");
            //require ("menurech.php");
            echo "<center><br/><b><font color=\"red\">\n";
            echo __("The card %id_card was not found in the database.")."</b></font>\n";
            echo "<br /><br /><b>".__("Please restart your search or contact the database administrator")." : " . $configemail . "</b></center><br /><br /><br /><br />\n";
            echo "<br /><br />\n";
            echo "</ul>\n";
            echo "\n";
            require ("footer.php");
        }
    }
    else{
        require ("header.php");
        echo "<center><br/><b><font color=\"red\">\n";
        echo __("Your rights are insufficient to edit this card")."</b></font></center><br /><br /><br /><br />\n";
        require ("footer.php");
    }
}
else{
    require ("header.php");
    require ("loginfail.php");
    require ("footer.php");
}
?>
