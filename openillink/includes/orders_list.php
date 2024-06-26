<?php
// ***************************************************************************
// ***************************************************************************
// ***************************************************************************
// This file is part of OpenILLink software.
// Copyright (C) 2005, 2006, 2007, 2008, 2009, 2010, 2011, 2012, 2013, 2015, 2016, 2017, 2018, 2019, 2024 CHUV.
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
// List of orders distributed in 4 folders : IN, OUT, ALL and TRASH
// Inbox : new orders to be processed, rejected orders by the other network libraries, renewed orders (ahead of print), orders in process
//
require_once ("toolkit.php");
require_once ("connexion.php");
$monbibr=$monbib."%";
if (($monaut == "admin")||($monaut == "sadmin")||($monaut == "user")||($monaut == "guest")){
	$myhtmltitle = format_string(__("%institution_name orders: orders list"), array('institution_name' => $configinstitution[$lang]));
	$page = ((!empty($_GET['page'])) && isValidInput($_GET['page'],8,'s',false))?$_GET['page']:1;

$export_format = ((!empty($_GET['export'])) && isValidInput($_GET['export'], 10, 's', true, array('html', 'csv', 'endnotexml', 'medline', 'ris'))) ? $_GET['export']: 'html';
if ($export_format != 'html') {
	// no paging when exporting
	$page = 1;
}

$link = dbconnect();

// Figure out the limit for the query based on the current page number
$debugOn = (!empty($configdebuglogging)) && in_array($configdebuglogging, array('DEV', 'TEST'));

	if ($debugOn)
		prof_flag("Start");
	if ($export_format == 'html') {
		require_once ("headeradmin.php");
		if ($debugOn)
			prof_flag("After head");
		require_once ("searchform.php");
		if ($debugOn)
			prof_flag("After search");
}
	$madatej=date("Y-m-d");
// Choice of folder
	$folder = ((!empty($_GET['folder'])) && isValidInput($_GET['folder'],6,'s',false))?$_GET['folder']:'';

	// $pageslinksurl = "list.php?folder=".$folder;
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
	$servCond = ($nbServ > 0 ?" OR (orders.service IN ($servList) AND (orders.localisation IS NULL OR orders.localisation = ''))":'');

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
	$conditionsParDefauts = " WHERE ((".
	"(orders.stade IN ($listIn) OR (orders.stade IN (".$listSpecial['renew'].") AND orders.renouveler <= '".mysqli_real_escape_string($link, $madatej)."')) AND ".
	"((orders.bibliotheque = '". mysqli_real_escape_string($link, $monbib)."' $additionalLocCond) $locCond $servCond )) ".
	"OR (orders.stade IN (".$listSpecial['reject'].") AND orders.bibliotheque = '".mysqli_real_escape_string($link, $monbib)."') $orphanOrdersCond )";
	switch ($folder){
		case 'in':
			// Apply "default" conditions
			$conditions = $conditionsParDefauts;
			// Also apply further configured constraints if available
			if (isset($configINFolderCustomConstraints) && array_key_exists($monbib, $configINFolderCustomConstraints)) {
				$conditions .= " " . $configINFolderCustomConstraints[$monbib];
			}
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
			// Display orders in status for 'trash' for current library (whatever service or localization).
			$conditions = "WHERE orders.stade IN ($listTrash) AND orders.bibliotheque = '".mysqli_real_escape_string($link, $monbib)."' ";
			break;
		case 'perso':
			// Display orders fo personalized folders.
			$myfolderid = ((!empty($_GET['folderid'])) && isValidInput($_GET['folderid'],6,'i',false))?$_GET['folderid']:0;
			$reqfold = "SELECT * FROM folders WHERE id = ?";
			$resultfold = dbquery($reqfold, array($myfolderid), 'i');
			$nbfold = iimysqli_num_rows($resultfold);
			if ($nbfold == 1) {
				$enreg = iimysqli_result_fetch_array($resultfold);
                if (!empty($enreg['query'])) {
                    $conditions = "WHERE " . prepare_folder_query($enreg['query']);
                } else {
                    $conditions = $conditionsParDefauts;
                }
			}
			else {
				$conditions = $conditionsParDefauts;
			}
			break;
		case 'guest':
			/* guest can be either a user with guest credits or a user with an automatic login mail + random password assigned by the system */
			$reqGuest = "SELECT * FROM users WHERE users.name = ?";
			$resGuest = dbquery($reqGuest, array($monnom), "s");
			$nbGuest = iimysqli_num_rows($resGuest);
			if ($nbGuest==1){
				$guest = iimysqli_result_fetch_array($resGuest);
				$mailGuest = $guest['email'];
				$conditions = "WHERE saisie_par = '".mysqli_real_escape_string($link, $monnom)."' ";
			}
			if (empty($mailGuest)) {
				$mailGuest = ((!empty($monnom)) && isValidInput($monnom,100,'s',false))?$monnom:'';
				$conditions = "WHERE orders.mail = '".mysqli_real_escape_string($link, $mailGuest)."' ";
			}
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
	$req2 = "$req2 $conditions ORDER BY illinkid DESC";
	if ($export_format == 'html') {
		// paging only with html output, not when exporting
		$from = (($page * $max_results) - $max_results);
		$req2 = "$req2 LIMIT $from, $max_results";
	}
	if ($debugOn)
		prof_flag("Before first query");
	// Fetch orders ID for current page
	try {
        $result2 = dbquery($req2,NULL,NULL,NULL,$debugOn);
        $nb_orders_counts = iimysqli_num_rows($result2);
    } catch (mysqli_sql_exception $e) {
        $nb_orders_counts = 0;
    }
    
	if ($debugOn)
		prof_flag("After first query");

    try {
        $reqCount = "SELECT count(illinkid) AS total FROM orders $conditions";
        $resCount = dbquery($reqCount);
        $count = iimysqli_result_fetch_array($resCount);
        $total_results = $count['total'];
    } catch (mysqli_sql_exception $e) {
        $total_results = 0;
        echo '<strong style="color:red">' . __("There is an error with the query parameters defined for this folder") . "</strong><br>";
    }
	if($total_results > 0){
		$total_pages = ceil($total_results / $max_results);

		for ($i=0 ; $i<$nb_orders_counts ; $i++){
			$currOrder = iimysqli_result_fetch_array($result2);
			$orderListId[] = mysqli_real_escape_string($link, $currOrder['illinkid']);
		}
		if ($export_format == 'html') {
			$req = "SELECT orders.illinkid, orders.type_doc, orders.date, orders.renouveler, orders.stade, orders.localisation, orders.nom, orders.prenom, orders.mail, orders.code_postal, orders.adresse, orders.localite, orders.bibliotheque, orders.prepaye, orders.remarques, orders.urgent, orders.service, orders.titre_article,  orders.auteurs, orders.titre_periodique, orders.volume , orders.numero, orders.pages , orders.annee , orders.PMID, orders.doi, orders.anonymized";
		} else {
			if (is_privileged_enough($monaut, "user")) {
				$req = "SELECT orders.illinkid, orders.stade, orders.localisation, orders.date, orders.envoye, orders.facture, orders.renouveler, orders.prix, orders.prepaye, orders.ref, orders.refinterbib, orders.arrivee, orders.nom, orders.prenom, orders.service, orders.cgra, orders.cgrb, orders.mail, orders.tel, orders.adresse, orders.code_postal, orders.localite, orders.urgent, orders.envoi_par, orders.type_doc, orders.titre_periodique, orders.annee, orders.volume, orders.numero, orders.supplement, orders.pages, orders.titre_article, orders.auteurs, orders.edition, orders.isbn, orders.issn, orders.eissn, orders.doi, orders.uid, orders.remarques, orders.remarquespub, orders.historique, orders.saisie_par, orders.bibliotheque, orders.PMID, orders.ip, orders.referer, orders.user_consent, orders.anonymized";
			} else {
				$req = "SELECT orders.illinkid, orders.stade, orders.localisation, orders.date, orders.envoye, orders.facture, orders.prix, orders.service, orders.cgra, orders.cgrb, orders.nom, orders.prenom, orders.mail, orders.tel, orders.adresse, orders.code_postal, orders.localite, orders.envoi_par, orders.type_doc, orders.titre_periodique, orders.annee, orders.volume, orders.numero, orders.supplement, orders.pages, orders.titre_article, orders.auteurs, orders.edition, orders.isbn, orders.issn, orders.eissn, orders.doi, orders.uid, orders.remarquespub, orders.bibliotheque, orders.PMID";
			}
		}
		$req .= " FROM orders ".
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
	if ($export_format == 'html') {
		require_once ("orders_results.php");
		require_once ("footer.php");
	} else if ($export_format == 'csv') {
		
		header("Content-Type: application/force-download");
		header("Content-Type: application/octet-stream");
		header("Content-Type: application/download");
		header("Content-Disposition: attachment;filename=openillink.csv");
		header("Content-Transfer-Encoding: binary");
		$out = fopen('php://output', 'w');
		echo "\xEF\xBB\xBF"; # UTF-8 BOM
		if (is_privileged_enough($monaut, "user")) {
			fputcsv($out, array(__("Order number"), __("Status"), __("Localization"), __("Order date"), __("Date of shipment"), __("Billing date"), __("Renewal date"), format_string(__("Price (%currency)"), array('currency' => $currency)), __("order paid in advance"), __("Provider Ref."),__("Internal ref. to the library"), __("Origin of the order"), __("Name"), __("First name"), __("Unit"), __("Budget heading"), __("Budget subheading"), __("E-Mail"), __("Tel."), __("Address"), __("Postal code"), __("City"), __("Priority"), __("Delivery type"), __("Document type"), __("Journal or book title"), __('Year'), __('Volume'), __('Issue'), __('Suppl.'), __('Pages'), __("Article or chapter title"), __("Author(s)"), __("Edition"), __('ISBN'), __('ISSN'), __("e-ISSN"), __("DOI"), __('UID'), __("Professional Notes"), __("Notes"), __("Order history"), __("Entered by"), __("Assignment Library"), __("PMID"), __("IP adress"), __("Provenance URL"), __("User consent"), __("Anonymized")), ";");
		} else {
			fputcsv($out, array(__("Order number"), __("Status"), __("Localization"), __("Order date"), __("Date of shipment"), __("Billing date"), format_string(__("Price (%currency)"), array('currency' => $currency)), __("Unit"), __("Budget heading"), __("Budget subheading"), __("Name"), __("First name"), __("E-Mail"), __("Tel."), __("Address"), __("Postal code"), __("City"), __("Delivery type"), __("Document type"), __("Journal or book title"), __('Year'), __('Volume'), __('Issue'), __('Suppl.'), __('Pages'), __("Article or chapter title"), __("Author(s)"), __("Edition"), __('ISBN'), __('ISSN'), __("e-ISSN"), __("DOI"), __('UID'), __("Notes"), __("Assignment Library"), __("PMID")), ";");
		}
		
		for ($i=0 ; $i<$nb ; $i++){
			fputcsv($out, iimysqli_result_fetch_array($result), ";");
		}
		fclose($out);
	} else if (in_array($export_format, array('endnotexml', 'medline', 'ris'))) {
		require_once('includes/vendor/RefLib/reflib.php');
		$reflib = new RefLib();
		$type_to_type = array("article" => "Journal Article",
							  "preprint" => "Manuscript",
							  "book" => "Book",
							  "bookitem" => "Book Section",
							  "thesis" => "Thesis",
							  "journal" => "Serial",
							  "proceeding" => "Conference Proceedings",
							  "conference" => "Conference Paper",
							  "other" => "Unpublished Work");
		for ($i=0 ; $i<$nb ; $i++){
			$order_line = iimysqli_result_fetch_array($result);
			$reflib->Add(array(
						'authors' => explode(",", $order_line["auteurs"]),
						//'address' => $order_line["adresse"] . " " . $order_line["code_postal"] . " " . $order_line["localite"],
						//'contact-name' => $order_line["prenom"] . " " . $order_line["nom"],
						//'contact-email' => $order_line["mail"],
						'type' => (array_key_exists($order_line["type_doc"], $type_to_type) ?  $type_to_type[$order_line["type_doc"]]: $type_to_type["other"]),
						'title' => $order_line["titre_article"],
						'title-secondary' => $order_line["titre_periodique"],
						//'title-short' => $order_line[""],
						'periodical-title' => $order_line["titre_periodique"],
						'pages' => $order_line["pages"],
						'volume' => $order_line["volume"],
						'number' => $order_line["numero"],
						//'section' => $order_line[""],
						'year' => $order_line["annee"],
						//'date' => $order_line[""],
						//'abstract' => $order_line[""],
						//'urls' => $order_line[""],
						'notes' => $order_line["remarquespub"],
						//'research-notes' => $order_line[""],
						'isbn' => (!empty($order_line["isbn"]) ? $order_line["isbn"] : (!empty($order_line["issn"]) ? $order_line["issn"] : $order_line["eissn"])),
						//'label' => $order_line[""],
						//'caption' => $order_line[""],
						//'language' => $order_line[""],
						'custom1' => $order_line["prenom"] . " " . $order_line["nom"],
						'custom2' => $order_line["adresse"] . " " . $order_line["code_postal"] . " " . $order_line["localite"],
						'custom3' => $order_line["mail"],
						'custom4' => $order_line["illinkid"],
						'custom5' => $order_line["bibliotheque"],
						'custom6' => $order_line["date"],
						'custom7' => $order_line["service"],
						'doi' => $order_line["doi"],
						'accession-num' => $order_line["PMID"],
					));
		}
		if ($export_format == "endnotexml") {
			$reflib->DownloadContents('openillink.xml', "endnotexml");
		} else if ($export_format == "ris") {
			$reflib->DownloadContents('openillink.ris', "ris");
		} else if ($export_format == "medline") {
			$reflib->DownloadContents('openillink.nbib', "medline");
		}
		
	}
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
