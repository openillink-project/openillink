<?php
// ***************************************************************************
// ***************************************************************************
// ***************************************************************************
// This file is part of OpenILLink software.
// Copyright (C) 2017 UNIGE.
// Copyright (C) 2017, 2018, 2024 CHUV.
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
// folders table : edit form
// 
require_once ("config.php");
require_once ("authcookie.php");
require_once ("connexion.php");
require_once ("toolkit.php");

$id="";
$montitle = __("Filters management");
$id= ((!empty($_GET['id'])) && isValidInput($_GET['id'],11,'s',false)) ? $_GET['id'] : "";
if (!empty($_COOKIE['illinkid'])){
	if (($monaut == "admin")||($monaut == "sadmin")){
		if ($id!=""){
			$myhtmltitle = $configname[$lang] . " : ". format_string(__("Edit filter %id_filter"), array('id_filter' => $id));
			$montitle = format_string(__("Filters management : Edit filter %id_filter"), array('id_filter' => $id));
			require ("headeradmin.php");
			echo '<nav class="breadcrumb" aria-label="breadcrumbs">
  <ul>
    <li><a href="admin.php">'.__("Administration").'</a></li>
    <li><a href="list.php?table=folders" aria-current="page">'.__("Filters management").'</a></li>
	<li class="is-active"><a href="edit.php?table=folders" aria-current="page">'.format_string(__("Edit filter %id_filter"), array('id_filter' => $id)).'</a></li>
  </ul>
</nav>';
			$req = "SELECT * FROM folders WHERE id = ?";
			$result = dbquery($req, array($id), 'i');
			$nb = iimysqli_num_rows($result);
			if ($nb == 1) {
				echo "<h1 class=\"title\">" . $montitle . "</h1>\n";
				echo "<br /></b>";
				echo "<ul>\n";
				$enreg = iimysqli_result_fetch_array($result);
				$folderid = $enreg['id'];
				$foldertitle = $enreg['title'];
				$folderdescription= $enreg['description'];
				$folderquery = $enreg['query'];
				$folderuser = $enreg['user'];
				$folderlibrary = $enreg['library'];
				$folderposition = $enreg['position'];
				$folderactive = $enreg['active'];
				echo "<form action=\"update.php\" method=\"POST\" enctype=\"x-www-form-encoded\" name=\"fiche\" id=\"fiche\">\n";
				echo "<input name=\"table\" type=\"hidden\" value=\"folders\">\n";
				echo "<input name=\"id\" type=\"hidden\" value=\"".htmlspecialchars($folderid)."\">\n";
				echo "<input name=\"action\" type=\"hidden\" value=\"update\">\n";
				echo "<table class=\"table is-striped\" id=\"hor-zebra\" class=\"genericEditFormOIL\">\n";
                echo "<tr><td></td><td><div class=\"field is-grouped\">";
                if (isset($config_folders_web_administration) && $config_folders_web_administration > 1) {
                    echo "<input class=\"button is-primary\" type=\"submit\" value=\"".__("Save changes")."\">\n";
                }
				echo "&nbsp;&nbsp;<input class=\"button\" type=\"button\" value=\"".__("Cancel")."\" onClick=\"self.location='list.php?table=folders'\">\n";
                if (isset($config_folders_web_administration) && $config_folders_web_administration > 0) {
                    echo "&nbsp;&nbsp;<input class=\"button is-danger\" type=\"button\" value=\"".__("Remove")."\" onClick=\"self.location='update.php?action=delete&table=folders&id=" . $folderid . "'\">";
                }
				echo "</div></td></tr>\n<tr><td>&nbsp;</td><td>&nbsp;</td></tr>\n";
				echo "<tr><td><b>Titre *</b></td><td>\n";
				echo "<input name=\"title\" type=\"text\" size=\"60\" value=\"" . htmlspecialchars($foldertitle) . "\"></td></tr>\n";
				echo "</td></tr>\n";
				echo "<tr><td><b>".__("Description")." *</b></td><td>\n";
				echo "<input name=\"description\" type=\"text\" size=\"60\" value=\"" . htmlspecialchars($folderdescription) . "\"></td></tr>\n";
				echo "</td></tr>\n";
				echo "<tr><td><b>".__("Attributed to")." *</b></td><td>\n";
				echo "user <select name=\"user\">\n";
				$requsers="SELECT login, name FROM users ORDER BY name ASC";
				$optionsusers="<option value=\"\"></option>";
				$resultusers = dbquery($requsers);
				$nbusers = iimysqli_num_rows($resultusers);
				if ($nbusers > 0){
					while ($rowusers = iimysqli_result_fetch_array($resultusers)){
						$codeusers = $rowusers["login"];
						$nameusers = $rowusers["name"];
						$optionsusers.="<option value=\"" . htmlspecialchars($codeusers) . "\"";
						if ($folderuser == $codeusers)
							$optionsusers.=" selected";
						$optionsusers.=">" . htmlspecialchars($nameusers) . "</option>\n";
					}
					echo $optionsusers;
				}
				echo "</select>&nbsp;&nbsp;&nbsp;\n";
				echo "| &nbsp;&nbsp;".__("Library")." </b>&nbsp;&nbsp;\n";
				echo "<select name=\"libraryassigned\">\n";
				$reqlibraries="SELECT code, name1, name2, name3, name4, name5 FROM libraries ORDER BY name1 ASC";
				$optionslibraries="<option value=\"\"></option>";
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
						if ($folderlibrary == $codelibraries)
							$optionslibraries.=" selected";
						$optionslibraries.=">" . htmlspecialchars($namelibraries[$lang]) . "</option>\n";
					}
					echo $optionslibraries;
				}
				echo "</select></td></tr>\n";

				echo "<tr><td><b>".__("Position")."</b></td><td>\n";
				echo "<input name=\"position\" type=\"text\" size=\"10\" value=\"" . htmlspecialchars($folderposition) . "\"></td></tr>\n";
				echo "</td></tr>\n";

				echo "<tr><td><b>filtre actif</b></td><td><input name=\"active\" value=\"1\" type=\"checkbox\"";
				if ($folderactive == 1)
					echo " checked";
				echo "></td></tr>\n";

				echo "<tr><td><b>".__("Query")." *</b></td><td>\n";
				echo "<textarea name=\"query\" id=\"query\" rows=\"3\" cols=\"60\" valign=\"bottom\">" . htmlspecialchars($folderquery) . "</textarea></td></tr>\n";

				echo "<tr><td>&nbsp;</td><td>&nbsp;</td></tr>\n";
                echo "<tr><td></td><td><div class=\"field is-grouped\">";
                if (isset($config_folders_web_administration) && $config_folders_web_administration > 1) {
                    echo "<input class=\"button is-primary\" type=\"submit\" value=\"".__("Save changes")."\">\n";
                }
				echo "&nbsp;&nbsp;<input class=\"button\" type=\"button\" value=\"".__("Cancel")."\" onClick=\"self.location='list.php?table=folders'\">\n";
                if (isset($config_folders_web_administration) && $config_folders_web_administration > 0) {
				echo "&nbsp;&nbsp;<input class=\"button is-danger\" type=\"button\" value=\"".__("Remove")."\" onClick=\"self.location='update.php?action=delete&table=folders&id=" . $folderid . "'\">";
                }
				echo "</div></td></tr>\n</table>\n";
				echo "</form><br /><br />\n";
				require ("footer.php");
			}
			else{
				echo "<center><br/><b><font color=\"red\">\n";
				echo format_string(__("The record %id_record was not found in the database."), array('id_record' => $id))."</b></font>\n";
				echo "<br /><br /><b>".__("Please restart your search or contact the database administrator")." : " . $configemail . "</b></center><br /><br /><br /><br />\n";
				require ("footer.php");
			}
		}
		else{
			require ("header.php");
			echo '<nav class="breadcrumb" aria-label="breadcrumbs">
  <ul>
    <li><a href="admin.php">'.__("Administration").'</a></li>
        <li><a href="list.php?table=folders" aria-current="page">'.__("Filters management").'</a></li>
	<li class="is-active"><a href="edit.php?table=folders" aria-current="page">'.format_string(__("Edit filter %id_filter"), array('id_filter' => $id)).'</a></li>
  </ul>
</nav>';
			//require ("menurech.php");
			echo "<center><br/><b><font color=\"red\">\n";
			echo __("The record was not found in the database."). "</b></font>\n";
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
