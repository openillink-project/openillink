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
// folders table : creation and update of records
// 
require_once ("config.php");
require_once ("authcookie.php");
require_once ("connexion.php");
require_once ("toolkit.php");

if (!empty($_COOKIE['illinkid'])){
	$id = ((!empty($_POST['id'])) && isValidInput($_POST['id'],11,'i',false))?$_POST['id']:NULL;
	$ip = $_SERVER['REMOTE_ADDR'];
	$validActionSet = array('new', 'update', 'delete', 'deleteok');
	$action = ((!empty($_GET['action'])) && isValidInput($_GET['action'],10,'s',false, $validActionSet)) ? $_GET['action'] : NULL;

	if (empty($action))
		$action = ((!empty($_POST['action'])) && isValidInput($_POST['action'],10,'s',false, $validActionSet)) ? $_POST['action'] : '';
	if (($monaut == "admin")||($monaut == "sadmin")){
		// Initialiser les variables
		$foldertitle = "";
		$folderdescription = "";
		$folderuser = "";
		$folderlibrary = "";
		$folderposition = "";
		$folderactive = "";
		$folderquery = "";
		$folderquery1 = "";
		$folderqueryedit = "";
		$folderquery1_library = "";
		$folderquery1_statuscode = "";
		$folderquery1_localisation = "";
		$folderquery1_unit = "";
		$folderquery2 = "";
		$folderquery2_library = "";
		$folderquery2_statuscode = "";
		$folderquery2_localisation = "";
		$folderquery2_unit = "";
		$folderquery3 = "";
		$folderquery3_library = "";
		$folderquery3_statuscode = "";
		$folderquery3_localisation = "";
		$folderquery3_unit = "";
		$bool1 = "";
		$bool2 = "";
		$folderqueryedit = "";
		$mes="";

		$date=date("Y-m-d H:i:s");

		// clean variables
		$foldertitle = ((!empty($_POST['title'])) && isValidInput($_POST['title'],50,'s',false))?trim($_POST['title']):'';
		$folderdescription = ((!empty($_POST['description'])) && isValidInput($_POST['description'],100,'s',false))?trim($_POST['description']):'';
		$folderuser = ((!empty($_POST['user'])) && isValidInput($_POST['user'],100,'s',false))?trim($_POST['user']):'';
		$folderlibrary = ((!empty($_POST['libraryassigned'])) && isValidInput($_POST['libraryassigned'],100,'s',false))?trim($_POST['libraryassigned']):'';
		$folderposition = ((!empty($_POST['position'])) && isValidInput($_POST['position'],10,'i',false))?trim($_POST['position']):0;
		$folderactive = ((!empty($_POST['active'])) && isValidInput($_POST['active'],1,'i',false))?trim($_POST['active']):0;
		$folderqueryedit = ((!empty($_POST['query'])) && isValidInput($_POST['query'],200,'s',false))?trim($_POST['query']):'';


		// Criteria 1
		$folderquery1_library1 = ((!empty($_POST['library1'])) && isValidInput($_POST['library1'],100,'s',false))?trim($_POST['library1']):'';
		$folderquery1_library2 = ((!empty($_POST['library2'])) && isValidInput($_POST['library2'],100,'s',false))?trim($_POST['library2']):'';
		$folderquery1_library3 = ((!empty($_POST['library3'])) && isValidInput($_POST['library3'],100,'s',false))?trim($_POST['library3']):'';
		$folderquery1_library4 = ((!empty($_POST['library4'])) && isValidInput($_POST['library4'],100,'s',false))?trim($_POST['library4']):'';
		$folderquery1_library5 = ((!empty($_POST['library5'])) && isValidInput($_POST['library5'],100,'s',false))?trim($_POST['library5']):'';

		$folderquery1_statuscode1 = ((!empty($_POST['statuscode1'])) && isValidInput($_POST['statuscode1'],10,'i',false))?trim($_POST['statuscode1']):0;
		$folderquery1_statuscode2 = ((!empty($_POST['statuscode2'])) && isValidInput($_POST['statuscode2'],10,'i',false))?trim($_POST['statuscode2']):0;
		$folderquery1_statuscode3 = ((!empty($_POST['statuscode3'])) && isValidInput($_POST['statuscode3'],10,'i',false))?trim($_POST['statuscode3']):0;
		$folderquery1_statuscode4 = ((!empty($_POST['statuscode4'])) && isValidInput($_POST['statuscode4'],10,'i',false))?trim($_POST['statuscode4']):0;
		$folderquery1_statuscode5 = ((!empty($_POST['statuscode5'])) && isValidInput($_POST['statuscode5'],10,'i',false))?trim($_POST['statuscode5']):0;

		$folderquery1_localisation1 = ((!empty($_POST['localisation1'])) && isValidInput($_POST['localisation1'],100,'s',false))?trim($_POST['localisation1']):'';
		$folderquery1_localisation2 = ((!empty($_POST['localisation2'])) && isValidInput($_POST['localisation2'],100,'s',false))?trim($_POST['localisation2']):'';
		$folderquery1_localisation3 = ((!empty($_POST['localisation3'])) && isValidInput($_POST['localisation3'],100,'s',false))?trim($_POST['localisation3']):'';
		$folderquery1_localisation4 = ((!empty($_POST['localisation4'])) && isValidInput($_POST['localisation4'],100,'s',false))?trim($_POST['localisation4']):'';
		$folderquery1_localisation5 = ((!empty($_POST['localisation5'])) && isValidInput($_POST['localisation5'],100,'s',false))?trim($_POST['localisation5']):'';

		$folderquery1_unit1 = ((!empty($_POST['unit1'])) && isValidInput($_POST['unit1'],100,'s',false))?trim($_POST['unit1']):'';
		$folderquery1_unit2 = ((!empty($_POST['unit2'])) && isValidInput($_POST['unit2'],100,'s',false))?trim($_POST['unit2']):'';
		$folderquery1_unit3 = ((!empty($_POST['unit3'])) && isValidInput($_POST['unit3'],100,'s',false))?trim($_POST['unit3']):'';
		$folderquery1_unit4 = ((!empty($_POST['unit4'])) && isValidInput($_POST['unit4'],100,'s',false))?trim($_POST['unit4']):'';
		$folderquery1_unit5 = ((!empty($_POST['unit5'])) && isValidInput($_POST['unit5'],100,'s',false))?trim($_POST['unit5']):'';

		$folderquery1_compte = ((!empty($_POST['compte1'])) && isValidInput($_POST['compte1'],100,'s',false))?trim($_POST['compte1']):'';
		$folderquery1_renewdate = ((!empty($_POST['renewdate1'])) && isValidInput($_POST['renewdate1'],100,'s',false))?trim($_POST['renewdate1']):'';


		// Criteria 2
		$folderquery2_library1 = ((!empty($_POST['library6'])) && isValidInput($_POST['library6'],100,'s',false))?trim($_POST['library6']):'';
		$folderquery2_library2 = ((!empty($_POST['library7'])) && isValidInput($_POST['library7'],100,'s',false))?trim($_POST['library7']):'';
		$folderquery2_library3 = ((!empty($_POST['library8'])) && isValidInput($_POST['library8'],100,'s',false))?trim($_POST['library8']):'';
		$folderquery2_library4 = ((!empty($_POST['library9'])) && isValidInput($_POST['library9'],100,'s',false))?trim($_POST['library9']):'';
		$folderquery2_library5 = ((!empty($_POST['library10'])) && isValidInput($_POST['library10'],100,'s',false))?trim($_POST['library10']):'';

		$folderquery2_statuscode1 = ((!empty($_POST['statuscode6'])) && isValidInput($_POST['statuscode6'],10,'i',false))?trim($_POST['statuscode6']):0;
		$folderquery2_statuscode2 = ((!empty($_POST['statuscode7'])) && isValidInput($_POST['statuscode7'],10,'i',false))?trim($_POST['statuscode7']):0;
		$folderquery2_statuscode3 = ((!empty($_POST['statuscode8'])) && isValidInput($_POST['statuscode8'],10,'i',false))?trim($_POST['statuscode8']):0;
		$folderquery2_statuscode4 = ((!empty($_POST['statuscode9'])) && isValidInput($_POST['statuscode9'],10,'i',false))?trim($_POST['statuscode9']):0;
		$folderquery2_statuscode5 = ((!empty($_POST['statuscode10'])) && isValidInput($_POST['statuscode10'],10,'i',false))?trim($_POST['statuscode10']):0;

		$folderquery2_localisation1 = ((!empty($_POST['localisation6'])) && isValidInput($_POST['localisation6'],100,'s',false))?trim($_POST['localisation6']):'';
		$folderquery2_localisation2 = ((!empty($_POST['localisation7'])) && isValidInput($_POST['localisation7'],100,'s',false))?trim($_POST['localisation7']):'';
		$folderquery2_localisation3 = ((!empty($_POST['localisation8'])) && isValidInput($_POST['localisation8'],100,'s',false))?trim($_POST['localisation8']):'';
		$folderquery2_localisation4 = ((!empty($_POST['localisation9'])) && isValidInput($_POST['localisation9'],100,'s',false))?trim($_POST['localisation9']):'';
		$folderquery2_localisation5 = ((!empty($_POST['localisation10'])) && isValidInput($_POST['localisation10'],100,'s',false))?trim($_POST['localisation10']):'';

		$folderquery2_unit1 = ((!empty($_POST['unit6'])) && isValidInput($_POST['unit6'],100,'s',false))?trim($_POST['unit6']):'';
		$folderquery2_unit2 = ((!empty($_POST['unit7'])) && isValidInput($_POST['unit7'],100,'s',false))?trim($_POST['unit7']):'';
		$folderquery2_unit3 = ((!empty($_POST['unit8'])) && isValidInput($_POST['unit8'],100,'s',false))?trim($_POST['unit8']):'';
		$folderquery2_unit4 = ((!empty($_POST['unit9'])) && isValidInput($_POST['unit9'],100,'s',false))?trim($_POST['unit9']):'';
		$folderquery2_unit5 = ((!empty($_POST['unit10'])) && isValidInput($_POST['unit10'],100,'s',false))?trim($_POST['unit10']):'';

		$folderquery2_compte = ((!empty($_POST['compte2'])) && isValidInput($_POST['compte2'],100,'s',false))?trim($_POST['compte2']):'';
		$folderquery2_renewdate = ((!empty($_POST['renewdate2'])) && isValidInput($_POST['renewdate2'],100,'s',false))?trim($_POST['renewdate2']):'';
		$folderquery2_bool = ((!empty($_POST['bool1'])) && isValidInput($_POST['bool1'],100,'s',false))?trim($_POST['bool1']):'';


		// Criteria 3
		$folderquery3_library1 = ((!empty($_POST['library11'])) && isValidInput($_POST['library11'],100,'s',false))?trim($_POST['library11']):'';
		$folderquery3_library2 = ((!empty($_POST['library12'])) && isValidInput($_POST['library12'],100,'s',false))?trim($_POST['library12']):'';
		$folderquery3_library3 = ((!empty($_POST['library13'])) && isValidInput($_POST['library13'],100,'s',false))?trim($_POST['library13']):'';
		$folderquery3_library4 = ((!empty($_POST['library14'])) && isValidInput($_POST['library14'],100,'s',false))?trim($_POST['library14']):'';
		$folderquery3_library5 = ((!empty($_POST['library15'])) && isValidInput($_POST['library15'],100,'s',false))?trim($_POST['library15']):'';

		$folderquery3_statuscode1 = ((!empty($_POST['statuscode11'])) && isValidInput($_POST['statuscode11'],10,'i',false))?trim($_POST['statuscode11']):0;
		$folderquery3_statuscode2 = ((!empty($_POST['statuscode12'])) && isValidInput($_POST['statuscode12'],10,'i',false))?trim($_POST['statuscode12']):0;
		$folderquery3_statuscode3 = ((!empty($_POST['statuscode13'])) && isValidInput($_POST['statuscode13'],10,'i',false))?trim($_POST['statuscode13']):0;
		$folderquery3_statuscode4 = ((!empty($_POST['statuscode14'])) && isValidInput($_POST['statuscode14'],10,'i',false))?trim($_POST['statuscode14']):0;
		$folderquery3_statuscode5 = ((!empty($_POST['statuscode15'])) && isValidInput($_POST['statuscode15'],10,'i',false))?trim($_POST['statuscode15']):0;

		$folderquery3_localisation1 = ((!empty($_POST['localisation11'])) && isValidInput($_POST['localisation11'],100,'s',false))?trim($_POST['localisation11']):'';
		$folderquery3_localisation2 = ((!empty($_POST['localisation12'])) && isValidInput($_POST['localisation12'],100,'s',false))?trim($_POST['localisation12']):'';
		$folderquery3_localisation3 = ((!empty($_POST['localisation13'])) && isValidInput($_POST['localisation13'],100,'s',false))?trim($_POST['localisation13']):'';
		$folderquery3_localisation4 = ((!empty($_POST['localisation14'])) && isValidInput($_POST['localisation14'],100,'s',false))?trim($_POST['localisation14']):'';
		$folderquery3_localisation5 = ((!empty($_POST['localisation15'])) && isValidInput($_POST['localisation15'],100,'s',false))?trim($_POST['localisation15']):'';

		$folderquery3_unit1 = ((!empty($_POST['unit11'])) && isValidInput($_POST['unit11'],100,'s',false))?trim($_POST['unit11']):'';
		$folderquery3_unit2 = ((!empty($_POST['unit12'])) && isValidInput($_POST['unit12'],100,'s',false))?trim($_POST['unit12']):'';
		$folderquery3_unit3 = ((!empty($_POST['unit13'])) && isValidInput($_POST['unit13'],100,'s',false))?trim($_POST['unit13']):'';
		$folderquery3_unit4 = ((!empty($_POST['unit14'])) && isValidInput($_POST['unit14'],100,'s',false))?trim($_POST['unit14']):'';
		$folderquery3_unit5 = ((!empty($_POST['unit15'])) && isValidInput($_POST['unit15'],100,'s',false))?trim($_POST['unit15']):'';

		$folderquery3_compte = ((!empty($_POST['compte3'])) && isValidInput($_POST['compte3'],100,'s',false))?trim($_POST['compte3']):'';
		$folderquery3_renewdate = ((!empty($_POST['renewdate3'])) && isValidInput($_POST['renewdate3'],100,'s',false))?trim($_POST['renewdate3']):'';
		$folderquery3_bool = ((!empty($_POST['bool2'])) && isValidInput($_POST['bool2'],100,'s',false))?trim($_POST['bool2']):'';


		if ($folderquery1_library1 != ""){
			$folderquery1_library = "bibliotheque LIKE '" . $folderquery1_library1 . "' ";}
		if ($folderquery1_library2 != ""){
			$folderquery1_library .= " OR bibliotheque LIKE '" . $folderquery1_library2 . "' ";}
		if ($folderquery1_library3 != ""){
			$folderquery1_library .= " OR bibliotheque LIKE '" . $folderquery1_library3 . "' ";}
		if ($folderquery1_library4 != ""){
			$folderquery1_library .= " OR bibliotheque LIKE '" . $folderquery1_library4 . "' ";}
		if ($folderquery1_library5 != ""){
			$folderquery1_library .= " OR bibliotheque LIKE '" . $folderquery1_library5 . "' ";}
		if ($folderquery1_library != ""){
			$folderquery1 = "(" . $folderquery1_library . ")";}

		if ($folderquery1_statuscode1 != ""){
			$folderquery1_statuscode = "stade LIKE '" . $folderquery1_statuscode1 . "' ";}
		if ($folderquery1_statuscode2 != ""){
			$folderquery1_statuscode .= " OR stade LIKE '" . $folderquery1_statuscode2 . "' ";}
		if ($folderquery1_statuscode3 != ""){
			$folderquery1_statuscode .= " OR stade LIKE '" . $folderquery1_statuscode3 . "' ";}
		if ($folderquery1_statuscode4 != ""){
			$folderquery1_statuscode .= " OR stade LIKE '" . $folderquery1_statuscode4 . "' ";}
		if ($folderquery1_statuscode5 != ""){
			$folderquery1_statuscode .= " OR stade LIKE '" . $folderquery1_statuscode5 . "' ";}
		if ($folderquery1_statuscode != ""){
			$folderquery1 .= " AND (" . $folderquery1_statuscode . ")";}

		if ($folderquery1_localisation1 != ""){
			$folderquery1_localisation = "localisation LIKE '" . $folderquery1_localisation1 . "' ";}
		if ($folderquery1_localisation2 != ""){
			$folderquery1_localisation .= " OR localisation LIKE '" . $folderquery1_localisation2 . "' ";}
		if ($folderquery1_localisation3 != ""){
			$folderquery1_localisation .= " OR localisation LIKE '" . $folderquery1_localisation3 . "' ";}
		if ($folderquery1_localisation4 != ""){
			$folderquery1_localisation .= " OR localisation LIKE '" . $folderquery1_localisation4 . "' ";}
		if ($folderquery1_localisation5 != ""){
			$folderquery1_localisation .= " OR localisation LIKE '" . $folderquery1_localisation5 . "' ";}
		if ($folderquery1_localisation != ""){
			$folderquery1 .= " AND (" . $folderquery1_localisation . ")";}

		if ($folderquery1_unit1 != ""){
			$folderquery1_unit = "service LIKE '" . $folderquery1_unit1 . "' ";}
		if ($folderquery1_unit2 != ""){
			$folderquery1_unit .= " OR service LIKE '" . $folderquery1_unit2 . "' ";}
		if ($folderquery1_unit3 != ""){
			$folderquery1_unit .= " OR service LIKE '" . $folderquery1_unit3 . "' ";}
		if ($folderquery1_unit4 != ""){
			$folderquery1_unit .= " OR service LIKE '" . $folderquery1_unit4 . "' ";}
		if ($folderquery1_unit5 != ""){
			$folderquery1_unit .= " OR service LIKE '" . $folderquery1_unit5 . "' ";}
		if ($folderquery1_unit != ""){
			$folderquery1 .= " AND (" . $folderquery1_unit . ")";}

		if ($folderquery1_compte != ""){
			$folderquery1 .= " AND (compte LIKE '" . $folderquery1_compte . "')";}

		if ($folderquery1_renewdate != ""){
			$folderquery1 .= " AND (renewdate LIKE '" . $folderquery1_renewdate . "')";}

		if ($folderquery1 != ""){
		$folderquery = $folderquery1;}


		if ($folderquery2_library1 != ""){
			$folderquery2_library = "bibliotheque LIKE '" . $folderquery2_library1 . "' ";}
		if ($folderquery2_library2 != ""){
			$folderquery2_library .= " OR bibliotheque LIKE '" . $folderquery2_library2 . "' ";}
		if ($folderquery2_library3 != ""){
			$folderquery2_library .= " OR bibliotheque LIKE '" . $folderquery2_library3 . "' ";}
		if ($folderquery2_library4 != ""){
			$folderquery2_library .= " OR bibliotheque LIKE '" . $folderquery2_library4 . "' ";}
		if ($folderquery2_library5 != ""){
			$folderquery2_library .= " OR bibliotheque LIKE '" . $folderquery2_library5 . "' ";}
		if ($folderquery2_library != ""){
			$folderquery2 = "(" . $folderquery2_library . ")";}

		if ($folderquery2_statuscode1 != ""){
			$folderquery2_statuscode = "stade LIKE '" . $folderquery2_statuscode1 . "' ";}
		if ($folderquery2_statuscode2 != ""){
			$folderquery2_statuscode .= " OR stade LIKE '" . $folderquery2_statuscode2 . "' ";}
		if ($folderquery2_statuscode3 != ""){
			$folderquery2_statuscode .= " OR stade LIKE '" . $folderquery2_statuscode3 . "' ";}
		if ($folderquery2_statuscode4 != ""){
			$folderquery2_statuscode .= " OR stade LIKE '" . $folderquery2_statuscode4 . "' ";}
		if ($folderquery2_statuscode5 != ""){
			$folderquery2_statuscode .= " OR stade LIKE '" . $folderquery2_statuscode5 . "' ";}
		if ($folderquery2_statuscode != ""){
			$folderquery2 .= " AND (" . $folderquery2_statuscode . ")";}

		if ($folderquery2_localisation1 != ""){
			$folderquery2_localisation = "localisation LIKE '" . $folderquery2_localisation1 . "' ";}
		if ($folderquery2_localisation2 != ""){
			$folderquery2_localisation .= " OR localisation LIKE '" . $folderquery2_localisation2 . "' ";}
		if ($folderquery2_localisation3 != ""){
			$folderquery2_localisation .= " OR localisation LIKE '" . $folderquery2_localisation3 . "' ";}
		if ($folderquery2_localisation4 != ""){
			$folderquery2_localisation .= " OR localisation LIKE '" . $folderquery2_localisation4 . "' ";}
		if ($folderquery2_localisation5 != ""){
			$folderquery2_localisation .= " OR localisation LIKE '" . $folderquery2_localisation5 . "' ";}
		if ($folderquery2_localisation != ""){
			$folderquery2 .= " AND (" . $folderquery2_localisation . ")";}

		if ($folderquery2_unit1 != ""){
			$folderquery2_unit = "service LIKE '" . $folderquery2_unit1 . "' ";}
		if ($folderquery2_unit2 != ""){
			$folderquery2_unit .= " OR service LIKE '" . $folderquery2_unit2 . "' ";}
		if ($folderquery2_unit3 != ""){
			$folderquery2_unit .= " OR service LIKE '" . $folderquery2_unit3 . "' ";}
		if ($folderquery2_unit4 != ""){
			$folderquery2_unit .= " OR service LIKE '" . $folderquery2_unit4 . "' ";}
		if ($folderquery2_unit5 != ""){
			$folderquery2_unit .= " OR service LIKE '" . $folderquery2_unit5 . "' ";}
		if ($folderquery2_unit != ""){
			$folderquery2 .= " AND (" . $folderquery2_unit . ")";}

		if ($folderquery2_compte != ""){
			$folderquery2 .= " AND (compte LIKE '" . $folderquery2_compte . "')";}

		if ($folderquery2_renewdate != ""){
			$folderquery2 .= " AND (renewdate LIKE '" . $folderquery2_renewdate . "')";}

		if ($folderquery2 != ""){
		$folderquery = "(" . $folderquery;
		$folderquery .= ") ". $folderquery2_bool . " (" . $folderquery2 . ")";
		}



		if ($folderquery3_library1 != ""){
			$folderquery3_library = "bibliotheque LIKE '" . $folderquery3_library1 . "' ";}
		if ($folderquery3_library2 != ""){
			$folderquery3_library .= " OR bibliotheque LIKE '" . $folderquery3_library2 . "' ";}
		if ($folderquery3_library3 != ""){
			$folderquery3_library .= " OR bibliotheque LIKE '" . $folderquery3_library3 . "' ";}
		if ($folderquery3_library4 != ""){
			$folderquery3_library .= " OR bibliotheque LIKE '" . $folderquery3_library4 . "' ";}
		if ($folderquery3_library5 != ""){
			$folderquery3_library .= " OR bibliotheque LIKE '" . $folderquery3_library5 . "' ";}
		if ($folderquery3_library != ""){
			$folderquery3 = "(" . $folderquery3_library . ")";}

		if ($folderquery3_statuscode1 != ""){
			$folderquery3_statuscode = "stade LIKE '" . $folderquery3_statuscode1 . "' ";}
		if ($folderquery3_statuscode2 != ""){
			$folderquery3_statuscode .= " OR stade LIKE '" . $folderquery3_statuscode2 . "' ";}
		if ($folderquery3_statuscode3 != ""){
			$folderquery3_statuscode .= " OR stade LIKE '" . $folderquery3_statuscode3 . "' ";}
		if ($folderquery3_statuscode4 != ""){
			$folderquery3_statuscode .= " OR stade LIKE '" . $folderquery3_statuscode4 . "' ";}
		if ($folderquery3_statuscode5 != ""){
			$folderquery3_statuscode .= " OR stade LIKE '" . $folderquery3_statuscode5 . "' ";}
		if ($folderquery3_statuscode != ""){
			$folderquery3 .= " AND (" . $folderquery3_statuscode . ")";}

		if ($folderquery3_localisation1 != ""){
			$folderquery3_localisation = "localisation LIKE '" . $folderquery3_localisation1 . "' ";}
		if ($folderquery3_localisation2 != ""){
			$folderquery3_localisation .= " OR localisation LIKE '" . $folderquery3_localisation2 . "' ";}
		if ($folderquery3_localisation3 != ""){
			$folderquery3_localisation .= " OR localisation LIKE '" . $folderquery3_localisation3 . "' ";}
		if ($folderquery3_localisation4 != ""){
			$folderquery3_localisation .= " OR localisation LIKE '" . $folderquery3_localisation4 . "' ";}
		if ($folderquery3_localisation5 != ""){
			$folderquery3_localisation .= " OR localisation LIKE '" . $folderquery3_localisation5 . "' ";}
		if ($folderquery3_localisation != ""){
			$folderquery3 .= " AND (" . $folderquery3_localisation . ")";}

		if ($folderquery3_unit1 != ""){
			$folderquery3_unit = "service LIKE '" . $folderquery3_unit1 . "' ";}
		if ($folderquery3_unit2 != ""){
			$folderquery3_unit .= " OR service LIKE '" . $folderquery3_unit2 . "' ";}
		if ($folderquery3_unit3 != ""){
			$folderquery3_unit .= " OR service LIKE '" . $folderquery3_unit3 . "' ";}
		if ($folderquery3_unit4 != ""){
			$folderquery3_unit .= " OR service LIKE '" . $folderquery3_unit4 . "' ";}
		if ($folderquery3_unit5 != ""){
			$folderquery3_unit .= " OR service LIKE '" . $folderquery3_unit5 . "' ";}
		if ($folderquery3_unit != ""){
			$folderquery3 .= " AND (" . $folderquery3_unit . ")";}


		if ($folderquery3_compte != ""){
			$folderquery3 .= " AND (compte LIKE '" . $folderquery3_compte . "')";}

		if ($folderquery3_renewdate != ""){
			$folderquery3 .= " AND (renewdate LIKE '" . $folderquery3_renewdate . "')";}
			
		if ($folderquery3 != ""){
		$folderquery = "(" . $folderquery;
		$folderquery .= ") ". $folderquery3_bool . " (" . $folderquery3 . ")";
		}

		// Remove the 'AND ' at the begining of query
		if ($folderquery != "")
		{
			$posand = strpos($folderquery, 'AND ');
			if (($posand == 0) || ($posand == 1))
			{
				$countplaceand = 1;
				$folderquery = str_replace ('AND ' , '', $folderquery, $countplaceand);
			}
			$posand2 = strpos($folderquery, 'AND ');
			if (($posand2 == 0) || ($posand2 == 1))
			{
				$countplaceand = 1;
				$folderquery = str_replace ('AND ' , '', $folderquery, $countplaceand);
			}
		}

		// Remove the 'AND ' at the begining of queryedit
		if ($folderqueryedit != "")
		{
			$posand = strpos($folderqueryedit, 'AND ');
			if (($posand == 0) || ($posand == 1))
			{
				$countplaceand = 1;
				$folderqueryedit = str_replace ('AND ' , '', $folderqueryedit, $countplaceand);
			}
			$posand2 = strpos($folderqueryedit, 'AND ');
			if (($posand2 == 0) || ($posand2 == 1))
			{
				$countplaceand = 1;
				$folderqueryedit = str_replace ('AND ' , '', $folderqueryedit, $countplaceand);
			}
		}

		if ($folderposition < 1 || $folderposition > 999)
			$folderposition = 0;

		if (($action == "update")||($action == "new")){
			// Tester les champs obligatoires
			if ($foldertitle == "")
				$mes = $mes . "<br/>le Titre du filtre est obligatoire";
			if ($folderdescription == "")
				$mes = $mes . "<br/>la description est obligatoire";
			if (($folderuser == "") && ($folderlibrary == ""))
				$mes = $mes . "<br/>l'utilisateur ou la bibliothèque d'assignation est obligatoire";
			if (($folderquery == "") && ($folderqueryedit == ""))
				$mes = $mes . "<br/>une recherche au moins est obligatoire";
			if ($mes != ""){
				require ("headeradmin.php");
				echo "<center><br/><b><font color=\"red\">\n";
				echo $mes."</b></font>\n";
				echo "<br /><br /><a href=\"javascript:history.back();\"><b>retour au formulaire</a></b></center><br /><br /><br /><br />\n";
				require ("footer.php");
			}
			else{
				// Début de l'édition
				if ($action == "update"){
					if ($id != ""){
						$myhtmltitle = $configname[$lang] . " : édition de la fiche du filtre " . htmlspecialchars($id);
						require ("headeradmin.php");
						$reqid = "SELECT * FROM folders WHERE id = ?";
						$resultid = dbquery($reqid, array($id), 'i');
						$nb = iimysqli_num_rows($resultid);
						if ($nb == 1){
							$enregid = iimysqli_result_fetch_array($resultid);
							$query = 'UPDATE folders SET folders.title=?, '.
							'folders.description=?, folders.query=?, '.
							'folders.user=?, folders.library=?, '.
							'folders.active=?, folders.position=? '.
							'WHERE folders.id=?';
							$params = array($foldertitle, $folderdescription, $folderqueryedit, $folderuser, $folderlibrary, $folderactive, $folderposition, $id);
							$typeParam = 'sssssiii';
							$resultupdate = dbquery($query, $params, $typeParam) or die("Error : ".mysqli_error());
							echo "<center><br/><b><font color=\"green\">\n";
							echo "La modification de la fiche ".htmlspecialchars($id)." a été enregistrée avec succès</b></font>\n";
							echo "<br/><br/><br/><a href=\"list.php?table=folders\">Retour à la liste de filtres</a></center>\n";
							require ("footer.php");
						}
						else{
							echo "<center><br/><b><font color=\"red\">\n";
							echo "La modification n'a pas été enregistrée car l'identifiant de la fiche " . htmlspecialchars($id) . " n'a pas été trouvé dans la base.</b></font>\n";
							echo "<br /><br /><b>Veuillez relancer de nouveau votre recherche ou contactez l'administrateur de la base : " . $configemail . "</b></center><br /><br /><br /><br />\n";
							require ("footer.php");
						}
					}
					else{
						require ("headeradmin.php");
						//require ("menurech.php");
						echo "<center><br/><b><font color=\"red\">\n";
						echo "La modification n'a pas été enregistrée car il manque l'identifiant de la fiche</b></font>\n";
						echo "<br /><br /><b>Veuillez relancer de nouveau votre recherche</b></center><br /><br /><br /><br />\n";
						require ("footer.php");
					}
				}
				// Fin de l'édition
				// Début de la création
				if ($action == "new"){
					$myhtmltitle = $configname[$lang] . " : nouveau filtre ";
					require ("headeradmin.php");
					$query = "INSERT INTO `folders` (`title`, `description`, `query`, `user`, `library`, `active`, `position`) VALUES (?, ?, ?, ?, ?, ?, ?)";
					$params = array($foldertitle, $folderdescription, $folderquery, $folderuser, $folderlibrary, $folderactive, $folderposition);
					$id = dbquery($query, $params, 'sssssii') or die("Error : ".mysqli_error());
					echo "<center><br/><b><font color=\"green\">\n";
					echo "La nouvelle fiche ".htmlspecialchars($id)." a été enregistrée avec succès</b></font>\n";
					echo "<br/><br/><br/><a href=\"list.php?table=folders\">Retour à la liste de filtres</a></center>\n";
					echo "</center>\n";
					echo "\n";
					require ("footer.php");
				}
			}
		}
		// Fin de la création
		// Début de la suppresion
		if ($action == "delete"){
			$id= ((!empty($_GET['id'])) && isValidInput($_GET['id'],11,'s',false)) ? $_GET['id'] : "";
			$myhtmltitle = $configname[$lang] . " : confirmation pour la suppresion d'une filtre ";
			require ("headeradmin.php");
			echo "<center><br/><br/><br/><b><font color=\"red\">\n";
			echo "Voulez-vous vraiement supprimer la fiche " . htmlspecialchars($id) . "?</b></font>\n";
			echo "<form action=\"update.php\" method=\"POST\" enctype=\"x-www-form-encoded\" name=\"fiche\" id=\"fiche\">\n";
			echo "<input name=\"table\" type=\"hidden\" value=\"folders\">\n";
			echo "<input name=\"id\" type=\"hidden\" value=\"".htmlspecialchars($id)."\">\n";
			echo "<input name=\"action\" type=\"hidden\" value=\"deleteok\">\n";
			echo "<br /><br />\n";
			echo "<input type=\"submit\" value=\"Confirmer la suppression de la fiche " . htmlspecialchars($id) . " en cliquant ici\">\n";
			echo "</form>\n";
			echo "<br/><br/><br/><a href=\"list.php?table=folders\">Retour à la liste des filtres</a></center>\n";
			echo "</center>\n";
			echo "\n";
			require ("footer.php");
		}
		if ($action == "deleteok"){
			$myhtmltitle = $configname[$lang] . " : supprimer une filtre ";
			require ("headeradmin.php");
			$query = "DELETE FROM folders WHERE folders.id = ?";
			$result = dbquery($query, array($id), 'i') or die("Error : ".mysqli_error());
			echo "<center><br/><b><font color=\"green\">\n";
			echo "La fiche " . htmlspecialchars($id) . " a été supprimée avec succès</b></font>\n";
			echo "<br/><br/><br/><a href=\"list.php?table=folders\">Retour à la liste des filtres</a></center>\n";
			echo "</center>\n";
			echo "\n";
			require ("footer.php");
		}
		// Fin de la suppresion
	}
	else{
		require ("header.php");
		echo "<center><br/><b><font color=\"red\">\n";
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
