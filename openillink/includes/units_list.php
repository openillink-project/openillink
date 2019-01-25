<?php
// ***************************************************************************
// ***************************************************************************
// ***************************************************************************
// This file is part of OpenILLink software.
// Copyright (C) 2005, 2006, 2007, 2008, 2009, 2010, 2011, 2012, 2013, 2015, 2016, 2017, 2018, 2019 CHUV.
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
// Units table : List of all the units defined on the internal ILL network
// 

require_once ("config.php");
require_once ("authcookie.php");
require_once ("connexion.php");
if (!empty($_COOKIE['illinkid']))
{
if (($monaut == "admin")||($monaut == "sadmin"))
{
$myhtmltitle = $configname[$lang] . " : ".__("Units management");
require ("headeradmin.php");
echo "\n";
echo '<nav class="breadcrumb" aria-label="breadcrumbs">
  <ul>
    <li><a href="admin.php">'.__("Administration").'</a></li>
    <li class="is-active"><a href="list.php?table=units" aria-current="page">'.__("Management of network units").'</a></li>
  </ul>
</nav>';
// 
// Localizations List
// 
echo "<h1 class=\"title\">".__("Management of network units")."</h1>\n";
$req = "SELECT * FROM units ORDER BY name1 ASC, code ASC";// LIMIT 0, 200";
$result = dbquery($req);
$total_results = iimysqli_num_rows($result);
$nb = $total_results;

// Construction du tableau de resultats
echo "</center>\n";
echo "<b><br/>".$total_results;
if ($total_results == 1)
echo " ".__("unit found")."</b></font>\n";
else
echo " ".__("units found")."</b></font>\n";
echo "<br/>";
echo "<br/>";

echo "<table class=\"table is-hoverable\" id=\"one-column-emphasis\" summary=\"\">\n";
echo "<colgroup>\n";
echo "<col class=\"oce-first\" />\n";
echo "</colgroup>\n";
echo "\n";
echo "<thead>\n";
echo "<tr>\n";
echo "<th scope=\"col\">".__("Code")."</th>\n";
echo "<th scope=\"col\">".__("Name in French")."</th>\n";
// echo "<th scope=\"col\">name2</th>\n";
// echo "<th scope=\"col\">name3</th>\n";
// echo "<th scope=\"col\">name4</th>\n";
// echo "<th scope=\"col\">name5</th>\n";
echo "<th scope=\"col\">".__("Library")."</th>\n";
echo "<th scope=\"col\">".__("Department")."</th>\n";
echo "<th scope=\"col\">".__("Faculty")."</th>\n";
echo "<th scope=\"col\">".__("ip int.1")."</th>\n";
echo "<th scope=\"col\">".__("ip int.2")."</th>\n";
echo "<th scope=\"col\">".__("ip ext.")."</th>\n";
echo "<th scope=\"col\">".__("Need validation")."</th>\n";
echo "<th scope=\"col\">".__("Edit")."</th>\n";
echo "</tr>\n";
echo "</thead>\n";
echo "<tbody>\n";
for ($i=0 ; $i<$nb ; $i++)
{
$enreg = iimysqli_result_fetch_array($result);
$unitid = $enreg['id'];
$unitcode = $enreg['code'];
$unitname1 = $enreg['name1'];
$unitname2 = $enreg['name2'];
$unitname3 = $enreg['name3'];
$unitname4 = $enreg['name4'];
$unitname5 = $enreg['name5'];
$unitlibrary = $enreg['library'];
$unitdepartment = $enreg['department'];
$unitfaculty = $enreg['faculty'];
$unitip1 = $enreg['internalip1display'];
$unitip2 = $enreg['internalip2display'];
$unitipext = $enreg['externalipdisplay'];
$validation = $enreg['validation'];
echo "<tr>\n";
echo "<td><b>" . htmlspecialchars($unitcode) . "</b></td>\n";
echo "<td>".htmlspecialchars($unitname1)."</td>\n";
// echo "<td>".$unitname2."</td>\n";
// echo "<td>".$unitname3."</td>\n";
// echo "<td>".$unitname4."</td>\n";
// echo "<td>".$unitname5."</td>\n";
echo "<td>".htmlspecialchars($unitlibrary)."</td>\n";
echo "<td>".htmlspecialchars($unitdepartment)."</td>\n";
echo "<td>".htmlspecialchars($unitfaculty)."</td>\n";
echo "<td>".htmlspecialchars($unitip1)."</td>\n";
echo "<td>".htmlspecialchars($unitip2)."</td>\n";
echo "<td>".htmlspecialchars($unitipext)."</td>\n";
echo "<td>".htmlspecialchars($validation)."</td>\n";
if (($monaut == "admin")||($monaut == "sadmin"))
{
echo "<td><a title=\"".__("Edit the unit")."\" href=\"edit.php?table=units&id=".htmlspecialchars($unitid)."\"><i class=\"fas fa-edit has-text-primary\"></i></a></td>";
}
echo "</tr>\n";
}
echo "</tbody>\n";
echo "</table>\n";
echo "\n";
echo "<br/><br/><ul>\n";
echo "<b><a class=\"button is-primary\" href=\"new.php?table=units\">".__("Add a new unit")." </a></b>\n";
echo "<br/><br/>\n";
echo "</ul>\n";
require ("footer.php");
}
else
{
require ("header.php");
echo __("Your rights are insufficient to edit this record")."</b></font></center><br /><br /><br /><br />\n";
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
