<?php
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
// folders table : List of folders used to manage orders
//
require_once ("config.php");
require_once ("authcookie.php");
require_once ("connexion.php");

if (!empty($_COOKIE['illinkid'])){
	if (($monaut == "admin")||($monaut == "sadmin")){
		$myhtmltitle = $configname[$lang] . " : gestion des filtres";
		require ("headeradmin.php");
		echo "\n";
		// Folders List
		echo "<h1>Gestion des filtres</h1>\n";
		$req = "SELECT * FROM folders ORDER BY title ASC, user ASC";// LIMIT ?, ?";
		$result = dbquery($req);//, array(0,200), 'ii');
		$total_results = iimysqli_num_rows($result);
		$nb = $total_results;
		// Construction du tableau de resultats
		echo "</center>\n";
		echo "<b><br/>".$total_results;
		if ($total_results == 1)
			echo " filtre trouvé</b></font>\n";
		else
			echo " filtres trouvés</b></font>\n";
		echo "<br/>";
		echo "<br/>";
		echo "<table id=\"one-column-emphasis\" summary=\"\">\n";
		echo "<colgroup>\n";
		echo "<col class=\"oce-first\" />\n";
		echo "</colgroup>\n";
		echo "\n";
		echo "<thead>\n";
		echo "<tr>\n";
		echo "<th scope=\"col\">Titre</th>\n";
		echo "<th scope=\"col\">Description</th>\n";
		echo "<th scope=\"col\">Recherche</th>\n";
		echo "<th scope=\"col\">User</th>\n";
		echo "<th scope=\"col\">Bibliothèque</th>\n";
		echo "<th scope=\"col\">Position dans le menu</th>\n";
		echo "<th scope=\"col\">filtre actif</th>\n";
		echo "<th scope=\"col\"></th>\n";
		echo "</tr>\n";
		echo "</thead>\n";
		echo "<tbody>\n";
		for ($i=0 ; $i<$nb ; $i++){
			$enreg = iimysqli_result_fetch_array($result);
			$folderid = $enreg['id'];
			$foldertitle = $enreg['title'];
			$folderdescription= $enreg['description'];
			$folderurl = $enreg['query'];
			if (strlen($folderurl)>40)
				$folderurls = substr($folderurl, 0, 40) . "[...]";
			else
				$folderurls = $folderurl;
			$folderuser = $enreg['user'];
			$folderlibrary = $enreg['library'];
			$folderposition = $enreg['position'];
			$folderactive = $enreg['active'];
			echo "<tr>\n";
			echo "<td><b>" . htmlspecialchars($foldertitle) . "</b></td>\n";
			echo "<td><b>" . htmlspecialchars($folderdescription) . "</b></td>\n";
			echo "<td><a href=\"".htmlspecialchars($folderurl)."\" target=\"_blank\">" . htmlspecialchars($folderurls) . "</a></td>\n";
			echo "<td>".htmlspecialchars($folderuser)."</td>\n";
			echo "<td>".htmlspecialchars($folderlibrary)."</td>\n";
			echo "<td>".htmlspecialchars($folderposition)."</td>\n";
			echo "<td>".htmlspecialchars($folderactive)."</td>\n";
			if (($monaut == "admin")||($monaut == "sadmin")){
				echo "<td><a href=\"edit.php?table=folders&amp;id=".htmlspecialchars($folderid)."\"><img src=\"img/edit.png\" title=\"Editer la fiche\" width=\"20\"></a></td>";
			}
			echo "</tr>\n";
		}
		echo "</tbody>\n";
		echo "</table>\n";
		echo "\n";
		echo "<br/><br/><ul>\n";
		echo "<b><a href=\"new.php?table=folders\">Ajouter un nouveau filtre </a></b>\n";
		echo "<br/><br/>\n";
		echo "</ul>\n";
		require ("footer.php");
	}
	else{
		require ("header.php");
		echo "Vos droits sont insuffisants pour consulter cette page</b></font></center><br /><br /><br /><br />\n";
		require ("footer.php");
	}
}
else{
	require ("header.php");
	require ("loginfail.php");
	require ("footer.php");
}
?>
