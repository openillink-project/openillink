<?php
// ***************************************************************************
// ***************************************************************************
// ***************************************************************************
// OpenLinker is a web based library system designed to manage 
// journals, ILL, document delivery and OpenURL links
// 
// Copyright (C) 2012, Pablo Iriarte
// 
// This program is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
// 
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
// 
// You should have received a copy of the GNU General Public License
// along with this program.  If not, see <http://www.gnu.org/licenses/>.
// 
// ***************************************************************************
// ***************************************************************************
// ***************************************************************************
// List of orders distributed in 4 folders : IN, OUT, ALL and TRASH
// Inbox : new orders to be processed, rejected orders by the other network libraries, renewed orders (ahead of print), orders in process
// 17.03.2016, MDV Replaced connector to db from mysql_ to mysqli_
// 21.03.2016, MDV Input reading verification
// 01.04.2016, MDV refactoring query to avoid duplication of query
//
require_once ("includes/toolkit.php");
require_once ('connexion.php');
$monbibr=$monbib."%";
if (($monaut == "admin")||($monaut == "sadmin")||($monaut == "user")||($monaut == "guest")){
    $myhtmltitle = "Commandes de " . $configinstitution[$lang] . " : liste de commandes";
    $page = ((!empty($_GET['page'])) && isValidInput($_GET['page'],8,'s',false))?$_GET['page']:1;

$link = dbconnect();

// Figure out the limit for the query based on the current page number
    if ($debugOn)
        prof_flag("Start");
    require_once ("headeradmin.php");
    if ($debugOn)
        prof_flag("After head");
    require_once ("searchform.php");
    if ($debugOn)
        prof_flag("After search");
    $madatej=date("Y-m-d");
// Choice of folder
    $folder = ((!empty($_GET['folder'])) && isValidInput($_GET['folder'],6,'s',false))?$_GET['folder']:'';

    //$pageslinksurl = "list.php?folder=".$folder;
    // replaced previous to avoid dropping of arguments in search mode
    $stringQuery = $_SERVER['QUERY_STRING'];
    $pageParam = strstr($stringQuery,'&page');
    $stringQuery = str_replace ( $pageParam , '',$stringQuery);
    $pageslinksurl = strlen($stringQuery) ? basename($_SERVER['PHP_SELF'])."?".$stringQuery : basename($_SERVER['PHP_SELF']);

	// Preparing request to select all localizations of current library
    $reqLoc = "SELECT code FROM localizations WHERE library = ?";
    $resLoc = dbquery($reqLoc, array($monbib), "s");
	$locListArray = array(); // used in order_results.php
    $nbLoc = iimysqli_num_rows($resLoc);
    $locList = '';
    for ($l=0 ; $l<$nbLoc ; $l++){
        $currLoc = iimysqli_result_fetch_array($resLoc);
        $locList = empty($locList)?"'".mysqli_real_escape_string($link, $currLoc['code'])."'":$locList.",'".mysqli_real_escape_string($link, $currLoc['code'])."'";
		$locListArray[] = $currLoc['code'];
    }
    $locCond = empty($locList)?'':" OR orders.localisation IN ($locList) ";

	// Preparing request to select all units/services of current library, for orders without localisation
    $reqServ = "SELECT code FROM units WHERE library = ?";
    $resServ = dbquery($reqServ, array($monbib), "s");
    $nbServ = iimysqli_num_rows($resServ);
	$servListArray = array(); // used in order_results.php
    $servList = '';
    for ($l=0 ; $l<$nbServ ; $l++){
        $currServ = iimysqli_result_fetch_array($resServ);
        $servList = empty($servList)?"'".mysqli_real_escape_string($link, $currServ['code'])."'":$servList.",'".mysqli_real_escape_string($link, $currServ['code'])."'";
		$servListArray[] = $currServ['code'];
    }
    $servCond = ($nbServ > 0 ?" OR orders.service IN ($servList) ":'');

	// Prepare list of special statues: retrieve code configured for each folder/category
    $codeIn = array();
    $codeOut = array();
    $codeTrash = array();
    $codeSpecial = array();
    $statusInfo = readStatus($codeIn, $codeOut,$codeTrash, $codeSpecial);
    $listIn = "'".implode ( "','", $codeIn)."'";
    $listOut = "'".implode (  "','", $codeOut)."'";
    $listTrash = "'".implode (  "','", $codeTrash)."'";
    foreach($codeSpecial as $key => $value){
        $listSpecial[$key] = "'".implode (  "','", $codeSpecial[$key])."'";
    }


    /*
    * If the current library is a main library (i.e. flagged as default), we need to retrieve orders from partners 
	  libraries (i.e. have "shared orders") when these orders have a "new" status and no localization).
    */
	$orphanOrdersCond = "";
    $reqIsMain ="SELECT libraries.default FROM libraries WHERE libraries.default = 1 AND libraries.code=?";
    $resIsMain = dbquery($reqIsMain, array($monbib), "s");
    $isMain = iimysqli_num_rows($resIsMain);
	$sharedLibrariesArray = array(); // used in order_results.php
    if ($isMain > 0){
	    $listBibIn = array();
		$listBibIn[] = $monbib;
		// Select "partners" libraries
        $reqSharing = 'SELECT libraries.code FROM libraries WHERE libraries.has_shared_ordres = 1';
        $resSharing = dbquery($reqSharing);
        $nbSharing = iimysqli_num_rows($resSharing);
        for ($l=0 ; $l<$nbSharing ; $l++){
            $currSharing = iimysqli_result_fetch_array($resSharing);
            $listBibIn[] = mysqli_real_escape_string($link, $currSharing['code']);
			$sharedLibrariesArray[] = $currSharing['code'];
        }
		$listInBib = "'".implode (  "','", $listBibIn)."'";
		$orphanOrdersCond = $isMain?" OR ( orders.localisation = '' AND orders.stade IN (".$listSpecial['new'].")  AND orders.bibliotheque IN (".$listInBib.")) ":"";
    }

	// Depending on the configured mode, we want to hide incoming orders from "In" folder if it is localized to other libraries
	$additionalLocCond = "";
	if ($displayAttributedOrderMode == 1) {
		$additionalLocCond = " AND (orders.localisation = '' OR orders.localisation IS NULL OR orders.localisation IN ($locList) ) ";
	}
	// Building main query to retrieve orders, depending on the current folder (in, all, out, trash)
    $req2 = "SELECT orders.illinkid FROM orders ";
    $conditions = '';
	// Apply these conditions when displaying "IN" folder or when searching:
	//   - Display orders in status 'IN' and those in 'renew' (if expired) only if orders belong to current library or are localized, or (when no localization) in service for current library.
	//   - Also display orders which are rejected for current library
	//   - Also display order from shared/partners libraries if these orders are not localized and are new
	$conditionsParDefauts = " WHERE (".
	"(orders.stade IN ($listIn) OR (orders.stade IN (".$listSpecial['renew'].") AND orders.renouveler <= '".mysqli_real_escape_string($link, $madatej)."')) AND ".
	"((orders.bibliotheque = '". mysqli_real_escape_string($link, $monbib)."' $additionalLocCond) $locCond $servCond )) ".
	"OR (orders.stade IN (".$listSpecial['reject'].") AND orders.bibliotheque = '".mysqli_real_escape_string($link, $monbib)."') $orphanOrdersCond";
    switch ($folder){
        case 'in':
			// Apply "default" conditions
            $conditions = $conditionsParDefauts;
            break;
        case 'out':
			// Display orders in status for 'out' folder for current library (if localized or (when no localization) in service for current library).
            $conditions = "WHERE (orders.bibliotheque = '".mysqli_real_escape_string($link, $monbib)."' $locCond $servCond) AND orders.stade IN ($listOut) ";
            break;
        case 'all':
			// Display all orders for current library (if localized or, when no localization, in service for current library).
            if ($monaut == "sadmin"){}
            else {
                $conditions = "WHERE orders.bibliotheque = '".mysqli_real_escape_string($link, $monbib)."' $locCond  $servCond";
            }
            break;
        case 'trash':
			// // Display orders in status for 'trash' for current library (whatever service or localization).
            $conditions = "WHERE orders.stade IN ($listTrash) AND orders.bibliotheque = '".mysqli_real_escape_string($link, $monbib)."' ";
            break;
        case 'guest':
            /* guest can be either a user with guest credits or a user with an automatic login mail + random password assigned by the system */
            $reqGuest = "SELECT * FROM users WHERE users.name = ?";
            $resGuest = dbquery($reqGuest, array($monnom), "s");
            $nbGuest = iimysqli_num_rows($resGuest);
            if ($nbGuest==1){
                $guest = iimysqli_result_fetch_array($resGuest);
                $mailGuest = $guest['email'];
            }
            if (empty($mailGuest))
                $mailGuest = ((!empty($monnom)) && isValidInput($monnom,100,'s',false))?$monnom:'';
            $conditions = "WHERE orders.mail = '".mysqli_real_escape_string($link, $mailGuest)."' ";
            break;
        case 'search':
			// Will make use '$conditionsParDefauts' variable
            require_once ("search.php");
            break;
        default: // Just in case. Treat same as "IN" folder
            $conditions = $conditionsParDefauts;
            break;
    }
	// Paging
    $from = (($page * $max_results) - $max_results);
    $req2 = "$req2 $conditions ORDER BY illinkid DESC LIMIT $from, $max_results";
$debugOn = false;
    if ($debugOn)
        prof_flag("Before first query");
	// Fetch orders ID for current page
    $result2 = dbquery($req2,NULL,NULL,NULL,$debugOn);
    if ($debugOn)
        prof_flag("After first query");

    $reqCount = "SELECT count(illinkid) AS total FROM orders $conditions";
    $resCount = dbquery($reqCount);
    $count = iimysqli_result_fetch_array($resCount);
    $total_results = $count['total'];

    if($total_results > 0){
        $total_pages = ceil($total_results / $max_results);
        $from = (($page * $max_results) - $max_results);
        for ($i=0 ; $i<$total_results ; $i++){
            $currOrder = iimysqli_result_fetch_array($result2);
            $orderListId[] = mysqli_real_escape_string($link, $currOrder['illinkid']);
        }
        $req = "SELECT orders.illinkid, orders.type_doc, orders.date, orders.stade, orders.localisation, orders.nom, orders.prenom, orders.mail, orders.code_postal, orders.adresse, orders.localite, orders.bibliotheque, orders.prepaye, orders.remarques, orders.urgent, orders.service, orders.titre_article,  orders.auteurs, orders.titre_periodique, orders.volume , orders.numero, orders.pages , orders.annee , orders.PMID ".
            "FROM orders ".
            "WHERE (orders.illinkid IN ('".implode("','",$orderListId)."'))";
        switch ($folder){
            case 'in':
                $req .= " ORDER BY orders.urgent, orders.illinkid DESC";
                break;
            case 'out': case 'all': case 'trash': case 'guest': case 'search':
                $req .= " ORDER BY orders.illinkid DESC";
                break;
            default:
                $req .= " ORDER BY orders.urgent, orders.illinkid DESC";
                break;
        }
        if ($debugOn)
            prof_flag("Before second query");
        $result = dbquery($req);
        if ($debugOn)
            prof_flag("After second query");
        $nb = iimysqli_num_rows($result);
    }
    else
        $nb = 0;
    if ($debugOn)
        prof_flag("Before printing all");
    require_once ("orders_results.php");
    require_once ("footer.php");
    if ($debugOn)
        prof_flag("end of page printing all");
    if ($debugOn)
        prof_print();
}
else {
    require_once ("header.php");
    require_once ("loginfail.php");
    require_once ("footer.php");
}
?>
