<?php
// ***************************************************************************
// ***************************************************************************
// ***************************************************************************
// This file is part of OpenILLink software.
// Copyright (C) 2005, 2006, 2007, 2008, 2009, 2010, 2011, 2012, 2013, 2015, 2016, 2017, 2018, 2019, 2024 CHUV.
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
// Units table : edit form
// 
require_once ("config.php");
require_once ("authcookie.php");
require_once ("connexion.php");
require_once ("toolkit.php");

$id="";
$montitle = __("Units management");
$id=isValidInput($_GET['id'],11,'i',false)?$_GET['id']:'';
if (!empty($_COOKIE['illinkid'])){
    if (($monaut == "admin")||($monaut == "sadmin")){
        if ($id!=""){
            $req = "SELECT * FROM units WHERE id = ?";
            $myhtmltitle = $configname[$lang] . " : ". format_string(__("edition of the unit %id"), array('id' => $id));
            $montitle = format_string(__("Units management : edition of the unit %id"), array('id' => $id));
            require ("headeradmin.php");
			echo '<nav class="breadcrumb" aria-label="breadcrumbs">
  <ul>
    <li><a href="admin.php">'.__("Administration").'</a></li>
    <li><a href="list.php?table=units">'.__("Management of network units").'</a></li>
    <li class="is-active"><a href="edit.php?table=units" aria-current="page">'.format_string(__("Edit unit %id"), array('id' => $id)).'</a></li>
  </ul>
</nav>';
            $result = dbquery($req, array($id), "i");
            $nb = iimysqli_num_rows($result);
            if ($nb == 1){
                echo "<h1 class=\"title\">" . $montitle . "</h1>\n";
                echo "<br /></b>";
                echo "<ul>\n";
                $enreg = iimysqli_result_fetch_array($result);
                $unitid = $enreg['id'];
                $unitcode = $enreg['code'];
                $name["fr"] = $enreg['name1'];
                $name["en"] = $enreg['name2'];
                $name["de"] = $enreg['name3'];
                $name["it"] = $enreg['name4'];
                $name["es"] = $enreg['name5'];
                $unitlibrary = $enreg['library'];
                $unitdepartment = $enreg['department'];
                $unitfaculty = $enreg['faculty'];
                $unitip1 = $enreg['internalip1display'];
                $unitip2 = $enreg['internalip2display'];
                $unitipext = $enreg['externalipdisplay'];
                $validation = $enreg['validation'];
                echo "<script type=\"text/javascript\">\n";
                echo "function textchanged(changes) {\n";
                echo "document.fiche.modifs.value = document.fiche.modifs.value + changes + ' - ';\n";
                echo "}\n";
                echo "function ajoutevaleur(champ) {\n";
                echo "var champ2 = champ + 'new';\n";
                echo "var res = document.getElementById(champ).value;\n";
                echo "if (res == 'new')\n";
                echo "{\n";
                echo "document.getElementById(champ2).style.display='inline';\n";
                echo "}\n";
                echo "}\n";
                echo "</script>\n";
                echo "<form action=\"update.php\" method=\"POST\" enctype=\"x-www-form-encoded\" name=\"fiche\" id=\"fiche\">\n";
                echo "<input name=\"table\" type=\"hidden\" value=\"units\">\n";
                echo "<input name=\"id\" type=\"hidden\" value=\"".htmlspecialchars($unitid)."\">\n";
                echo "<input name=\"action\" type=\"hidden\" value=\"update\">\n";
                echo "<table class=\"table is-striped\" id=\"hor-zebra\">\n";
                echo "<tr><td></td><td><div class=\"field is-grouped\"><input class=\"button is-primary\" type=\"submit\" value=\"".__("Save changes")."\">\n";
                echo "&nbsp;&nbsp;<input class=\"button\" type=\"button\" value=\"".__("Cancel")."\" onClick=\"self.location='list.php?table=units'\">\n";
                echo "&nbsp;&nbsp;<input class=\"button is-danger\" type=\"button\" value=\"".__("Remove")."\" onClick=\"self.location='update.php?action=delete&table=units&id=" . htmlspecialchars($unitid) . "'\"></div></td></tr>\n";
                echo "<tr><td>&nbsp;</td><td>&nbsp;</td></tr>\n";
                echo "<tr><td><b>Code *</b></td><td>\n";
                echo "<input name=\"code\" type=\"text\" size=\"60\" value=\"" . htmlspecialchars($unitcode) . "\"></td></tr>\n";
                echo "</td></tr>\n";
                echo "<tr><td class=\"odd\"><b>".__("Name in French")." *</b></td><td class=\"odd\"><input name=\"name1\" type=\"text\" size=\"60\" value=\"" . htmlspecialchars($name["fr"]?$name["fr"]:"") . "\"></td></tr>\n";
                echo "<tr><td><b>".__("Name in English")."</b></td><td><input name=\"name2\" type=\"text\" size=\"60\" value=\"" . htmlspecialchars($name["en"]?$name["en"]:"") . "\"></td></tr>\n";
                echo "<tr><td class=\"odd\"><b>".__("Name in German")."</b></td><td class=\"odd\"><input name=\"name3\" type=\"text\" size=\"60\" value=\"" . htmlspecialchars($name["de"]?$name["de"]:"") . "\"></td></tr>\n";
                echo "<tr><td><b>".__("Name in Italian")."</b></td><td><input name=\"name4\" type=\"text\" size=\"60\" value=\"" . htmlspecialchars($name["it"]?$name["it"]:"") . "\"></td></tr>\n";
                echo "<tr><td class=\"odd\"><b>".__("Name in Spanish")."</b></td><td class=\"odd\"><input name=\"name5\" type=\"text\" size=\"60\" value=\"" . htmlspecialchars($name["es"]?$name["es"]:"") . "\"></td></tr>\n";
                echo "<tr><td><b>Bibliothèque d'attribution</b></td><td>\n";
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
                        if ($unitlibrary == $codelibraries)
                            $optionslibraries.=" selected";
                        $optionslibraries.=">" . htmlspecialchars($namelibraries[$lang]) . "</option>\n";
                    }
                    echo $optionslibraries;
                }
                echo "</select></td></tr>\n";
                echo "<tr><td class=\"odd\"><b>".__("Department")."</b></td><td class=\"odd\">\n";
                echo "<select name=\"department\" id=\"department\" onchange=\"ajoutevaleur('department');\">\n";
                echo "<option value=\"\"> </option>\n";
                $reqdepartment = "SELECT department FROM units WHERE department != '' GROUP BY department ORDER BY department ASC";
                $optionsdepartment = "";
                $resultdepartment = dbquery($reqdepartment);
                while ($rowdepartment = iimysqli_result_fetch_array($resultdepartment)){
                    $codedepartment = $rowdepartment["department"];
                    $optionsdepartment.="<option value=\"" . htmlspecialchars($codedepartment) . "\"";
                    if ($unitdepartment == $codedepartment)
                        $optionsdepartment.=" selected";
                    $optionsdepartment.=">" . htmlspecialchars($codedepartment) . "</option>\n";
                }
                echo $optionsdepartment;
                echo "<option value=\"new\">" . __("Add new value...") . "</option>\n";
                echo "</select>\n";
                echo "&nbsp;<input name=\"departmentnew\" id=\"departmentnew\" type=\"text\" size=\"30\" value=\"\" style=\"display:none\">\n";
                echo "</td></tr>\n";
                echo "<tr><td><b>".__("Faculty")."</b></td><td>\n";
                echo "<select name=\"faculty\" id=\"faculty\" onchange=\"ajoutevaleur('faculty');\">\n";
                echo "<option value=\"\"> </option>\n";
                $reqfaculty = "SELECT faculty FROM units WHERE faculty != '' GROUP BY faculty ORDER BY faculty ASC";
                $optionsfaculty = "";
                $resultfaculty = dbquery($reqfaculty);
                while ($rowfaculty = iimysqli_result_fetch_array($resultfaculty)){
                    $codefaculty = $rowfaculty["faculty"];
                    $optionsfaculty.="<option value=\"" . htmlspecialchars($codefaculty) . "\"";
                    if ($unitfaculty == $codefaculty)
                        $optionsfaculty.=" selected";
                    $optionsfaculty.=">" . htmlspecialchars($codefaculty) . "</option>\n";
                }
                echo $optionsfaculty;
                echo "<option value=\"new\">" . __("Add new value...") . "</option>\n";
                echo "</select>\n";
                echo "&nbsp;<input name=\"facultynew\" id=\"facultynew\" type=\"text\" size=\"30\" value=\"\" style=\"display:none\">\n";
                echo "</td></tr>\n";
                echo "<tr><td class=\"odd\"><b>".__("IP-based display")."</b></td><td class=\"odd\">\n";
                echo "<input name=\"ip1\" value=\"1\" type=\"checkbox\"";
                if ($unitip1 == 1)
                    echo " checked";
                echo ">".__("Internal IP 1")." &nbsp;&nbsp;|&nbsp;&nbsp; \n";
                echo "<input name=\"ip2\" value=\"1\" type=\"checkbox\"";
                if ($unitip2 == 1)
                    echo " checked";
                echo ">".__("Internal IP 2")." &nbsp;&nbsp;|&nbsp;&nbsp; \n";
                echo "<input name=\"ipext\" value=\"1\" type=\"checkbox\"";
                if ($unitipext == 1)
                    echo " checked";
                echo ">".__("External IP")."</td></tr>\n";
                echo "<tr><td><b>".__("Need validation")."</b></td><td>\n";
                echo "<input name=\"validation\" value=\"1\" type=\"checkbox\"";
                if ($validation == 1)
                    echo " checked";
                echo "></td></tr>\n";
                echo "<tr><td>&nbsp;</td><td>&nbsp;</td></tr>\n";
                echo "<tr><td></td><td><div class=\"field is-grouped\"><input class=\"button is-primary\" type=\"submit\" value=\"".__("Save changes")."\">\n";
                echo "&nbsp;&nbsp;<input class=\"button\" type=\"button\" value=\"".__("Cancel")."\" onClick=\"self.location='list.php?table=units'\">\n";
                echo "&nbsp;&nbsp;<input class=\"button is-danger\" type=\"button\" value=\"".__("Remove")."\" onClick=\"self.location='update.php?action=delete&table=units&id=" . htmlspecialchars($unitid) . "'\"></div></td></tr>\n";
                echo "</table>\n";
                echo "</form><br /><br />\n";
                require ("footer.php");
            }
            else{
                echo "<center><br/><b><font color=\"red\">\n";
                echo __("The record was not found in the database.")."</b></font>\n";
                echo "<br /><br /><b>".__("Please restart your search or contact the database administrator")." : " . $configemail . "</b></center><br /><br /><br /><br />\n";
                require ("footer.php");
            }
        }
        else{
            require ("header.php");
            //require ("menurech.php");
            echo "<center><br/><b><font color=\"red\">\n";
            echo __("The record was not found in the database.")."</b></font>\n";
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
        echo __("Your rights are insufficient to edit this record")."</b></font></center><br /><br /><br /><br />\n";
        require ("footer.php");
    }
}
else{
    require ("header.php");
    require ("loginfail.php");
    require ("footer.php");
}
?>
