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
// Libraries table : record creation form
// 
require_once ("config.php");
require ("authcookie.php");
if (!empty($_COOKIE['illinkid']))
{
if (($monaut == "admin")||($monaut == "sadmin"))
{
$myhtmltitle = $configname[$lang]. __("New network library");
require ("headeradmin.php");
echo "<h1>".__("Libraries management : New card creation")."</h1>\n";
echo "<br /></b>";
echo "<ul>\n";
echo "<form action=\"update.php\" method=\"POST\" enctype=\"x-www-form-encoded\" name=\"fiche\" id=\"fiche\">\n";
echo "<input name=\"table\" type=\"hidden\" value=\"libraries\">\n";
echo "<input name=\"action\" type=\"hidden\" value=\"new\">\n";
echo "<table class=\"genericEditFormOIL\" id=\"hor-zebra\">\n";
echo "<tr><td></td><td><input type=\"submit\" value=\"".__("Save the new library")."\">\n";
echo "&nbsp;&nbsp;<input type=\"button\" value=\"".__("Cancel")."\" onClick=\"self.location='list.php?table=libraries'\"></td></tr>\n";
echo "<tr><td>&nbsp;</td><td>&nbsp;</td></tr>\n";
echo "<tr><td><b>".__("Code")." *</b></td><td>\n";
echo "<input name=\"code\" type=\"text\" size=\"30\" value=\"\"></td></tr>\n";
echo "</td></tr>\n";
echo "<tr><td><b>".__("Name in French")." *</b></td><td><input name=\"name1\" type=\"text\" size=\"30\" value=\"\"></td></tr>\n";
echo "<tr><td><b>".__("Name in English")."</b></td><td><input name=\"name2\" type=\"text\" size=\"30\" value=\"\"></td></tr>\n";
echo "<tr><td><b>".__("Name in German")."</b></td><td><input name=\"name3\" type=\"text\" size=\"30\" value=\"\"></td></tr>\n";
echo "<tr><td><b>".__("Name in Italian")."</b></td><td><input name=\"name4\" type=\"text\" size=\"30\" value=\"\"></td></tr>\n";
echo "<tr><td><b>".__("Name in Spanish")."</b></td><td><input name=\"name5\" type=\"text\" size=\"30\" value=\"\"></td></tr>\n";
echo "<tr><td><b>".__("Main library")."</b></td><td><input name=\"default\" value=\"1\" type=\"checkbox\"></td></tr>\n";
echo "<tr><td><b>".__("Show incoming orders for this library with the main library")."</b></td><td><input name=\"hasSharedOrders\" value=\"1\" type=\"checkbox\"></td></tr>\n";
echo "<tr><td>&nbsp;</td><td>&nbsp;</td></tr>\n";
echo '<tr><td colspan="2">';
echo '<b><label for="signature">'.__("Signature (for emails sent to users)"). '</label></b><br/>';
echo '<textarea id="signature" name="signature" rows="5" cols="80"></textarea>';
echo '</td></tr>';
echo "<tr><td>&nbsp;</td><td>&nbsp;</td></tr>\n";
echo "<tr><td></td><td><input type=\"submit\" value=\"".__("Save the new library")."\">\n";
echo "&nbsp;&nbsp;<input type=\"button\" value=\"".__("Cancel")."\" onClick=\"self.location='list.php?table=libraries'\"></td></tr>\n";
echo "</table>\n";
echo "</form><br /><br />\n";
require ("footer.php");
}
else
{
require ("header.php");
echo "<center><br/><b><font color=\"red\">\n";
echo __("Your rights are insufficient to view this page")."</b></font></center><br /><br /><br /><br />\n";
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
