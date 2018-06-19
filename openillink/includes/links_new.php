<?php
// ***************************************************************************
// ***************************************************************************
// ***************************************************************************
// This file is part of OpenILLink software.
// Copyright (C) 2005, 2006, 2007, 2008, 2009, 2010, 2011, 2012, 2013, 2015, 2016, 2017, 2018 CHUV.
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
// links table : record creation form
// 
require_once ("config.php");
require ("authcookie.php");
require_once ("connexion.php");

if (!empty($_COOKIE['illinkid'])){
    if (($monaut == "admin")||($monaut == "sadmin")){
        $myhtmltitle = $configname[$lang] . " : " .__("New external link");
        require ("headeradmin.php");
		echo '<nav class="breadcrumb" aria-label="breadcrumbs">
  <ul>
    <li><a href="admin.php">'.__("Administration").'</a></li>
    <li><a href="list.php?table=links">'.__("Management of external links").'</a></li>
	<li class="is-active"><a href="new.php?table=links" aria-current="page">'.__("New link").'</a></li>
  </ul>
</nav>';
        echo "<h1 class=\"title\">".__("Links management : New record creation")."</h1>\n";
        echo "<br /></b>";
        echo "<ul>\n";
        echo "<form action=\"update.php\" method=\"POST\" enctype=\"x-www-form-encoded\" name=\"fiche\" id=\"fiche\">\n";
        echo "<input name=\"table\" type=\"hidden\" value=\"links\">\n";
        echo "<input name=\"action\" type=\"hidden\" value=\"new\">\n";
        echo "<table class=\"table is-striped\" id=\"hor-zebra\">\n";
        echo "<tr><td></td><td><input class=\"button is-primary\"  type=\"submit\" value=\"".__("Save the new link")."\">\n";
        echo "&nbsp;&nbsp;<input class=\"button\" type=\"button\" value=\"".__("Cancel")."\" onClick=\"self.location='list.php?table=links'\"></td></tr>\n";
        echo "<tr><td>&nbsp;</td><td>&nbsp;</td></tr>\n";
        echo "<tr><td><b>".__("Name")." *</b></td><td>\n";
        echo "<input name=\"title\" type=\"text\" size=\"60\" value=\"\"></td></tr>\n";
        echo "</td></tr>\n";
        echo "<tr><td class=\"odd\"><b>URL *</b></td><td class=\"odd\"><input name=\"url\" type=\"text\" size=\"50\" value=\"\">&nbsp;&nbsp;";
        echo "<input name=\"openurl\" value=\"1\" type=\"checkbox\"> OpenURL</td></tr>\n";
        /* MDV - 13.05.2015 : added line to update link position in the displayed list of link  in the table*/
        echo "<tr><td><b>".__("Position in the list")."</b></td><td><input name=\"ordonnancement\" type=\"text\" size=\"5\" value=\"\">&nbsp;&nbsp;</td></tr>";
        echo "<tr><td><b>".__("Search link by identifier")."</b></td><td>";
        echo "<input name=\"search_issn\" value=\"1\" type=\"checkbox\">ISSN &nbsp;&nbsp;|&nbsp;&nbsp; ";
        echo "<input name=\"search_isbn\" value=\"1\" type=\"checkbox\">ISBN";
        echo "</td></tr>\n";
        echo "<tr><td class=\"odd\"><b>".__("Search link by title")."</b></td><td class=\"odd\">";
        echo "<input name=\"search_ptitle\" value=\"1\" type=\"checkbox\">".__("of journal")." &nbsp;&nbsp;|&nbsp;&nbsp; ";
        echo "<input name=\"search_btitle\" value=\"1\" type=\"checkbox\">".__("of book")."&nbsp;&nbsp;|&nbsp;&nbsp; ";
        echo "<input name=\"search_atitle\" value=\"1\" type=\"checkbox\">".__("of article or chapter")."\n";
        echo "</td></tr>\n";
        echo "<tr><td><b>Lien de commande</b></td><td>";
        echo "<input name=\"order_ext\" value=\"1\" type=\"checkbox\">".__("External form")." &nbsp;&nbsp;|&nbsp;&nbsp; ";
        echo "<input name=\"order_form\" value=\"1\" type=\"checkbox\">".__("Internal form")."\n";
        echo "</td></tr>\n";
        echo "<tr><td class=\"odd\"><b>".__("Assignment library")."</b></td><td class=\"odd\">\n";
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
                $optionslibraries.=">" . htmlspecialchars($namelibraries[$lang]) . "</option>\n";
            }
            echo $optionslibraries;
        }
        echo "</select></td></tr>\n";
        echo "<tr><td><b>".__("Transform url arguments from UTF-8 to ISO-8859-1")."</b></td>".
        '<td><input type="checkbox"  value="1" name="url_encoded" id="url_encoded" /></td></tr>';
        echo "<tr><td><b>".__("Ignore the words in title of the journal / book")."/b></td>".
        "<td><input name=\"skip_words\" value=\"1\" type=\"checkbox\"> ".__("not significants")." ('of', 'the', 'The', '&', 'and', '-') | ".
        "<input name=\"skip_txt_after_mark\" value=\"1\" type=\"checkbox\"> ".__("after the symbol")." (':', '=', '.', ';', '(')</td></tr>\n";
        echo "<tr><td><b>".__("Active link")."</b></td><td><input name=\"active\" value=\"1\" type=\"checkbox\" checked></td></tr>\n";
        echo "<tr><td>&nbsp;</td><td>&nbsp;</td></tr>\n";
        echo "<tr><td></td><td><input class=\"button is-primary\" type=\"submit\" value=\"".__("Save new link")."\">\n";
        echo "&nbsp;&nbsp;<input class=\"button\" type=\"button\" value=\"".__("Cancel")."\" onClick=\"self.location='list.php?table=links'\"></td></tr>\n";
        echo "</table>\n";
        echo "</form><br /><br />\n";
        require ("footer.php");
    }
    else{
        require ("header.php");
        echo "<center><br/><b><font color=\"red\">\n";
        echo __("Your rights are insufficient to view this page")."</b></font></center><br /><br /><br /><br />\n";
        require ("footer.php");
    }
}
else{
    require ("header.php");
    require ("loginfail.php");
    require ("footer.php");
}
?>
