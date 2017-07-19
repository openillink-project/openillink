﻿<?php
// ***************************************************************************
// ***************************************************************************
// ***************************************************************************
// This file is part of OpenILfolder software.
// Copyright (C) 2005, 2006, 2007, 2008, 2009, 2010, 2011, 2012, 2013, 2015, 2016, 2017 CHUV.
// Original author(s): Pablo Iriarte <pablo@iriarte.ch>
// Other contributors are listed in the AUTHORS file at the top-level
// directory of this distribution.
// 
// OpenILfolder is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
// 
// OpenILfolder is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
// 
// You should have received a copy of the GNU General Public License
// along with OpenILfolder.  If not, see <http://www.gnu.org/licenses/>.
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
$montitle = "Gestion des filtres";
$id= ((!empty($_GET['id'])) && isValidInput($_GET['id'],11,'s',false)) ? $_GET['id'] : "";
if (!empty($_COOKIE['illinkid'])){
	if (($monaut == "admin")||($monaut == "sadmin")){
		if ($id!=""){
			$myhtmltitle = $configname[$lang] . " : édition du filtre " . $id;
			$montitle = "Gestion des filtres : édition de la fiche " . $id;
			require ("headeradmin.php");
			$req = "SELECT * FROM folders WHERE id = ?";
			$result = dbquery($req, array($id), 'i');
			$nb = iimysqli_num_rows($result);
			if ($nb == 1) {
				echo "<h1>" . $montitle . "</h1>\n";
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
				echo "<table id=\"hor-zebra\" class=\"genericEditFormOIL\">\n";
				echo "<tr><td></td><td><input type=\"submit\" value=\"Enregistrer les modifications\">\n";
				echo "&nbsp;&nbsp;<input type=\"button\" value=\"Annuler\" onClick=\"self.location='list.php?table=folders'\">\n";
				echo "&nbsp;&nbsp;<input type=\"button\" value=\"Supprimer\" onClick=\"self.location='update.php?action=delete&table=folders&id=" . $folderid . "'\"></td></tr>\n";
				echo "<tr><td>&nbsp;</td><td>&nbsp;</td></tr>\n";
				echo "<tr><td><b>Titre *</b></td><td>\n";
				echo "<input name=\"title\" type=\"text\" size=\"60\" value=\"" . htmlspecialchars($foldertitle) . "\"></td></tr>\n";
				echo "</td></tr>\n";
				echo "<tr><td><b>Description *</b></td><td>\n";
				echo "<input name=\"description\" type=\"text\" size=\"60\" value=\"" . htmlspecialchars($folderdescription) . "\"></td></tr>\n";
				echo "</td></tr>\n";
				echo "<tr><td><b>Attribué à *</b></td><td>\n";
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
				echo "| &nbsp;&nbsp;bibliothèque </b>&nbsp;&nbsp;\n";
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

				echo "<tr><td><b>Position</b></td><td>\n";
				echo "<input name=\"position\" type=\"text\" size=\"10\" value=\"" . htmlspecialchars($folderposition) . "\"></td></tr>\n";
				echo "</td></tr>\n";

				echo "<tr><td><b>filtre actif</b></td><td><input name=\"active\" value=\"1\" type=\"checkbox\"";
				if ($folderactive == 1)
					echo " checked";
				echo "></td></tr>\n";

				echo "<tr><td><b>Requête *</b></td><td>\n";
				echo "<textarea name=\"query\" id=\"query\" rows=\"3\" cols=\"60\" valign=\"bottom\">" . htmlspecialchars($folderquery) . "</textarea></td></tr>\n";

				echo "<tr><td>&nbsp;</td><td>&nbsp;</td></tr>\n";
				echo "<tr><td></td><td><input type=\"submit\" value=\"Enregistrer les modifications\">\n";
				echo "&nbsp;&nbsp;<input type=\"button\" value=\"Annuler\" onClick=\"self.location='list.php?table=folders'\">\n";
				echo "&nbsp;&nbsp;<input type=\"button\" value=\"Supprimer\" onClick=\"self.location='update.php?action=delete&table=folders&id=" . $folderid . "'\"></td></tr>\n";
				echo "</table>\n";
				echo "</form><br /><br />\n";
				require ("footer.php");
			}
			else{
				echo "<center><br/><b><font color=\"red\">\n";
				echo "La fiche " . $id . " n'a pas été trouvé dans la base.</b></font>\n";
				echo "<br /><br /><b>Veuillez relancer de nouveau votre recherche ou contactez l'administrateur de la base : " . $configemail . "</b></center><br /><br /><br /><br />\n";
				require ("footer.php");
			}
		}
		else{
			require ("header.php");
			//require ("menurech.php");
			echo "<center><br/><b><font color=\"red\">\n";
			echo "La fiche n'a pas été trouvé dans la base.</b></font>\n";
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
