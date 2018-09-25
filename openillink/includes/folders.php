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
// Folders displayed on the header
//
require_once("toolkit.php");
require_once("connexion.php");

$today = date("Y-m-d");
$totalFolderItemsCount = 0;
update_folders_item_count(true);
$reqfolders="SELECT id, title, description, query, order_count, count_updated FROM folders WHERE active = 1 AND (user = ? OR library = ?) ORDER BY position, title ASC";
$listfolders="";
$resultfolders = dbquery($reqfolders, array($monlog, $monbib), 'ss');
$nbfolders = iimysqli_num_rows($resultfolders);
if ($nbfolders > 0){
	$folderMenuTitle = __ ("Folders");
	$folderMenuIsSelected = false;
	$listfolders.= "";
	while ($rowfolders = iimysqli_result_fetch_array($resultfolders)){
		$idfolder = $rowfolders["id"];
		$titlefolder = $rowfolders["title"];
		$descriptionfolder = $rowfolders["description"];
		$queryfolder = $rowfolders["query"];
		$thisFolderCount = $rowfolders["order_count"];
		if (is_null($thisFolderCount)) {$thisFolderCount = 0;}
		$totalFolderItemsCount += $thisFolderCount;

		$listfolders.="<a class=\"navbar-item".((strval($idfolder) == strval($folderid))?' is-active':'')."\" href=\"list.php?folder=perso&folderid=" . htmlspecialchars($idfolder) . "\" title=\"" . htmlspecialchars($descriptionfolder) . "\"";
		$listfolders.=">" . htmlspecialchars($titlefolder) . ((!isset($config_display_folders_count) || $config_display_folders_count) && $thisFolderCount > 0 ?" <span class=\"openillink-badge is-size-7\">".$thisFolderCount."</span>" : "") . "</a>\n";
		if (strval($idfolder) == strval($folderid)) {
			$folderMenuIsSelected = true;
			$folderMenuTitle = htmlspecialchars($titlefolder);
			if (strlen($folderMenuTitle) > 30) {
				$folderMenuTitle = substr($folderMenuTitle, 0, 29) . "&hellip;";
			}
			if ((!isset($config_display_folders_count) || $config_display_folders_count) && $thisFolderCount > 0) {
				$folderMenuTitle .= " <span class=\"openillink-badge is-size-7\">".$thisFolderCount."</span>";
			}
		}
	}
	echo '	<div class="navbar-item has-dropdown is-hoverable">
				<a class="navbar-link'. ($folderMenuIsSelected ? " is-active" : "").'">'. $folderMenuTitle . (!$folderMenuIsSelected && (!isset($config_display_folders_count) || $config_display_folders_count) && $totalFolderItemsCount > 0 ?" <span class=\"openillink-badge is-size-7\">".$totalFolderItemsCount."</span>" : "") .'</a>
				<div class="navbar-dropdown">';
	echo $listfolders;
	echo '
		</div>
	</div>
	';
}

?>
