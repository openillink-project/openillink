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
// Folders displayed on the header
//
require_once("toolkit.php");
require_once("connexion.php");



$reqfolders="SELECT id, title, description, query FROM folders WHERE active = 1 AND (user = ? OR library = ?) ORDER BY position, title ASC";
$listfolders="";
$resultfolders = dbquery($reqfolders, array($monlog, $monbib), 'ss');
$nbfolders = iimysqli_num_rows($resultfolders);
if ($nbfolders > 0){
	echo '	<div class="navbar-item has-dropdown is-hoverable">
				<a class="navbar-link">Folders</a>
				<div class="navbar-dropdown">';
	$listfolders.= "";
	while ($rowfolders = iimysqli_result_fetch_array($resultfolders)){
		$idfolder = $rowfolders["id"];
		$titlefolder = $rowfolders["title"];
		$descriptionfolder = $rowfolders["description"];
		$queryfolder = $rowfolders["query"];
		$listfolders.="<a class=\"navbar-item\" href=\"list.php?folder=perso&folderid=" . htmlspecialchars($idfolder) . "\" title=\"" . htmlspecialchars($descriptionfolder) . "\"";
		if ($idfolder == $folderid)
			$listfolders.=" class=\"selected\"";
		$listfolders.=">" . htmlspecialchars($titlefolder) . "</a>\n";
	}
	echo $listfolders;
	echo '
		</div>
	</div>
	';
}

?>
