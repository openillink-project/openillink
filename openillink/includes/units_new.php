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
// Units table : record creation form
// 
require ("config.php");
require ("authcookie.php");
require_once ("connexion.php");
if (!empty($_COOKIE['illinkid']))
{
if (($monaut == "admin")||($monaut == "sadmin"))
{
$myhtmltitle = $configname[$lang] . " : nouvelle unité du réseau ";
require ("headeradmin.php");

echo "<h1>Gestion des unités : Création d'une nouvelle fiche </h1>\n";
echo "<br /></b>";
echo "<ul>\n";
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
echo "<input name=\"action\" type=\"hidden\" value=\"new\">\n";
echo "<table id=\"hor-zebra\">\n";
echo "<tr><td></td><td><input type=\"submit\" value=\"Enregistrer la nouvelle unité \">\n";
echo "&nbsp;&nbsp;<input type=\"button\" value=\"Annuler\" onClick=\"self.location='list.php?table=units'\"></td></tr>\n";
echo "<tr><td>&nbsp;</td><td>&nbsp;</td></tr>\n";
echo "<tr><td><b>Code *</b></td><td>\n";
echo "<input name=\"code\" type=\"text\" size=\"60\" value=\"\"></td></tr>\n";
echo "</td></tr>\n";
echo "<tr><td class=\"odd\"><b>".$guiLabelName1[$lang]." *</b></td><td class=\"odd\"><input name=\"name1\" type=\"text\" size=\"60\" value=\"\"></td></tr>\n";
echo "<tr><td><b>".$guiLabelName2[$lang]."</b></td><td><input name=\"name2\" type=\"text\" size=\"60\" value=\"\"></td></tr>\n";
echo "<tr><td class=\"odd\"><b>".$guiLabelName3[$lang]."</b></td><td class=\"odd\"><input name=\"name3\" type=\"text\" size=\"60\" value=\"\"></td></tr>\n";
echo "<tr><td><b>".$guiLabelName4[$lang]."</b></td><td><input name=\"name4\" type=\"text\" size=\"60\" value=\"\"></td></tr>\n";
echo "<tr><td class=\"odd\"><b>".$guiLabelName5[$lang]."</b></td><td class=\"odd\"><input name=\"name5\" type=\"text\" size=\"60\" value=\"\"></td></tr>\n";
echo "<tr><td><b>Bibliothèque d'attribution</b></td><td>\n";
echo "<select name=\"library\">\n";
$reqlibraries="SELECT code, name1, name2, name3, name4, name5 FROM libraries ORDER BY name1 ASC";
$optionslibraries="";
$resultlibraries = dbquery($reqlibraries);
$nblibs = iimysqli_num_rows($resultlibraries);
if ($nblibs > 0)
{
while ($rowlibraries = iimysqli_result_fetch_array($resultlibraries))
{
$codelibraries = $rowlibraries["code"];
$namelibraries["fr"] = $rowlibraries["name1"];
$namelibraries["en"] = $rowlibraries["name2"];
$namelibraries["de"] = $rowlibraries["name3"];
$namelibraries["it"] = $rowlibraries["name4"];
$namelibraries["es"] = $rowlibraries["name5"];
$optionslibraries.="<option value=\"" . htmlspecialchars($codelibraries) . "\"";
$optionslibraries.=">" . htmlspecialchars($namelibraries[$lang]) . "</option>\n";
}
echo $optionslibraries;
}
echo "</select></td></tr>\n";
echo "<tr><td class=\"odd\"><b>".$guiDepartment[$lang]."</b></td><td class=\"odd\">\n";
echo "<select name=\"department\" id=\"department\" onchange=\"ajoutevaleur('department');\">\n";
echo "<option value=\"\"> </option>\n";
$reqdepartment = "SELECT department FROM units WHERE department != '' GROUP BY department ORDER BY department ASC";
$optionsdepartment = "";
$resultdepartment = dbquery($reqdepartment);
while ($rowdepartment = iimysqli_result_fetch_array($resultdepartment))
{
$codedepartment = $rowdepartment["department"];
$optionsdepartment.="<option value=\"" . htmlspecialchars($codedepartment) . "\"";
$optionsdepartment.=">" . htmlspecialchars($codedepartment) . "</option>\n";
}
echo $optionsdepartment;
echo "<option value=\"new\">" . $addvaluemessage[$lang] . "</option>\n";
echo "</select>\n";
echo "&nbsp;<input name=\"departmentnew\" id=\"departmentnew\" type=\"text\" size=\"30\" value=\"\" style=\"display:none\">\n";
echo "</td></tr>\n";
echo "<tr><td><b>".$guiFaculty[$lang]."</b></td><td>\n";
echo "<select name=\"faculty\" id=\"faculty\" onchange=\"ajoutevaleur('faculty');\">\n";
echo "<option value=\"\"> </option>\n";
$reqfaculty = "SELECT faculty FROM units WHERE faculty != '' GROUP BY faculty ORDER BY faculty ASC";
$optionsfaculty = "";
$resultfaculty = dbquery($reqfaculty);
while ($rowfaculty = iimysqli_result_fetch_array($resultfaculty))
{
$codefaculty = $rowfaculty["faculty"];
$optionsfaculty.="<option value=\"" . htmlspecialchars($codefaculty) . "\"";
$optionsfaculty.=">" . htmlspecialchars($codefaculty) . "</option>\n";
}
echo $optionsfaculty;
echo "<option value=\"new\">" . $addvaluemessage[$lang] . "</option>\n";
echo "</select>\n";
echo "&nbsp;<input name=\"facultynew\" id=\"facultynew\" type=\"text\" size=\"30\" value=\"\" style=\"display:none\">\n";
echo "</td></tr>\n";
echo "<tr><td class=\"odd\"><b>Affichage selon IP</b></td><td class=\"odd\"><input name=\"ip1\" value=\"1\" type=\"checkbox\">IP interne 1 &nbsp;&nbsp;|&nbsp;&nbsp; <input name=\"ip2\" value=\"1\" type=\"checkbox\">IP interne 2 &nbsp;&nbsp;|&nbsp;&nbsp; <input name=\"ipext\" value=\"1\" type=\"checkbox\">IP externe</td></tr>\n";
echo "<tr><td><b>".$guiNeedValidation[$lang]."</b></td><td>\n";
echo "<input name=\"validation\" value=\"1\" type=\"checkbox\"></td></tr>\n";
echo "<tr><td>&nbsp;</td><td>&nbsp;</td></tr>\n";
echo "<tr><td></td><td><input type=\"submit\" value=\"Enregistrer la nouvelle unité \">\n";
echo "&nbsp;&nbsp;<input type=\"button\" value=\"Annuler\" onClick=\"self.location='list.php?table=units'\"></td></tr>\n";
echo "</table>\n";
echo "</form><br /><br />\n";
require ("footer.php");
}
else
{
require ("header.php");
echo "<center><br/><b><font color=\"red\">\n";
echo "Vos droits sont insuffisants pour consulter cette page</b></font></center><br /><br /><br /><br />\n";
require ("footer.php");
}
}
else
{
require ("header.php");
require ("loginfail.php");
require ("footer.php");
}
?>
