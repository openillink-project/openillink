<?php
// ***************************************************************************
// ***************************************************************************
// ***************************************************************************
// This file is part of OpenILLink software.
// Copyright (C) 2005, 2006, 2007, 2008, 2009, 2010, 2011, 2012, 2013, 2015, 2016, 2017, 2018, 2019, 2020, 2024 CHUV.
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
// Page to save the order, display errors or confirm if the order is normally saved
//

//
// START Common Vars

require_once ("connexion.php");
require_once ("toolkit.php");
require_once ("authip.php");

$mes="";
//$doi="";
//$pmid="";
//$isbn="";
//$issn="";
//$eissn="";
$userid = $monnom;
if (empty($userid)){
    $userid = $ip;
}

$referer=(!empty($_POST['referer']))? $_POST['referer'] :'';
//$action=$_POST['action']; // TODO : is that code usefull or useless?
$stade="";

// extended set of common vars
//$uid = ((!empty($_POST['uid'])) && isValidInput($_POST['uid'],50, 's', false))?$_POST['uid']:NULL;
$validTidSet = array('pmid','doi');
/*$tid = ((!empty($_POST['tid'])) && isValidInput($_POST['tid'],4, 's', false,$validTidSet))?$_POST['tid']:'';
if ($tid=='pmid'){
    $uids = trim($_POST['uids']);
    $uids = ((!empty($uids)) && isValidInput($uids,20, 's', false))?$uids:'';
    $pmid = $uids;
}
elseif ($tid=='doi'){
    $uids = trim($_POST['uids']);
    $uids = ((!empty($uids)) && isValidInput($uids,80, 's', false))?$uids:'';
    $doi = $uids;
}
*/
$sid=((!empty($_POST['sid'])) && isValidInput($_POST['sid'],50, 's', false))?$_POST['sid']:'';
$pid=((!empty($_POST['pid'])) && isValidInput($_POST['pid'],50, 's', false))?$_POST['pid']:'';
$source=((!empty($_POST['source'])) && isValidInput($_POST['source'],20, 's', false))?$_POST['source']:'';
$nom=((!empty($_POST['nom'])) && isValidInput($_POST['nom'],100, 's', false))?trim($_POST['nom']):'';
$prenom=((!empty($_POST['prenom'])) && isValidInput($_POST['prenom'],100, 's', false))?trim($_POST['prenom']):'';
$service=((!empty($_POST['service'])) && isValidInput($_POST['service'],20, 's', false))?$_POST['service']:'';
$servautre=((!empty($_POST['servautre'])) && isValidInput($_POST['servautre'],20, 's', false))?$_POST['servautre']:'';
if($servautre)
    $service=$servautre;

$cgra=((!empty($_POST['cgra'])) && isValidInput($_POST['cgra'],10, 's', false))?$_POST['cgra']:'';
$cgrb=((!empty($_POST['cgrb'])) && isValidInput($_POST['cgrb'],10, 's', false))?$_POST['cgrb']:'';

$mail=((!empty($_POST['mail'])) && isValidInput($_POST['mail'],100, 's', false))?trim($_POST['mail']):'';
$tel =((!empty($_POST['tel'])) && isValidInput($_POST['tel'],20, 's', false))?$_POST['tel']:'';
$adresse=((!empty($_POST['adresse'])) && isValidInput($_POST['adresse'],255 ,'s' ,false))?$_POST['adresse']:'';
$postal=((!empty($_POST['postal'])) && isValidInput($_POST['postal'],10, 's', false))?$_POST['postal']:'';
$localite=((!empty($_POST['localite'])) && isValidInput($_POST['localite'],50, 's', false))?$_POST['localite']:'';

$envoi=((!empty($_POST['envoi'])) && isValidInput($_POST['envoi'],50, 's', false))?$_POST['envoi']:'';

$typeDocValidSet = array('article','preprint','book','bookitem','thesis','journal','proceeding','conference','other');
$validation_field = ((!empty($_POST['adresscompl'])) && isValidInput($_POST['adresscompl'],255 ,'s' ,false))?$_POST['adresscompl']:'';
$timestamp = ((!empty($_POST['timestamp'])) && isValidInput($_POST['timestamp'],25 ,'i' ,false))? intval($_POST['timestamp']):0;
$timestamp_signature = ((!empty($_POST['timesignat'])) && isValidInput($_POST['timesignat'],255 ,'s' ,false))?$_POST['timesignat']:'';

$consent = ((!empty($_POST['consent'])) && isValidInput($_POST['consent'],1024, 's', false, array($config_dataprotection_consent_version))) ? $_POST['consent'] : null;

function get_from_post($form_index, $key, $maxSize, $type='s', $optional=true, $controlSet=NULL, $default="") {
	if (isset($_POST[$key.'_'.$form_index])) {
		return $_POST[$key.'_'.$form_index];
	} else if (isset($_GET[$key.'_'.$form_index])) { # debug
		return $_GET[$key.'_'.$form_index];
	} else if ($form_index == 0 && isset($_POST[$key])) {
		/* Support legacy single order form case */
		return $_POST[$key];
	} else {
		return "";
	}
}
$default_order_form = array('tid_code' => "",
							'uids' => "",
							'genre_code' => "",
							'title' => "",
							'date' => "",
							'volume' => "",
							'issue' => "",
							'suppl' => "",
							'pages' => "",
							'atitle' => "",
							'auteurs' => "",
							'edition' => "",
							'issn' => "",
							'uid' => "",
							'remarquespub' => "",
							'remarques' => "",
							"pmid" => "",
							"doi" => "",
							"isbn" => "",
							"eissn" => "");
$form_index = 0;
$order_form_values = array();
while ($form_index <= max($maxSimultaneousOrders, 1) && get_from_post($form_index, 'tid', 4, 's', false, $validTidSet, '') != ""){
		$order_form = $default_order_form;
		$validTidSet = array('pmid','doi');


		$order_form['tid_code'] = get_from_post($form_index, 'tid', 4, 's', false, $validTidSet, '');
		if ($order_form['tid_code']=='pmid'){
			$order_form['uids'] = get_from_post($form_index, 'uids', 20, 's', false, '');
			$order_form['pmid'] = $order_form['uids'];
		}
		elseif ($order_form['tid_code']=='doi'){
			$order_form['uids'] = get_from_post($form_index, 'uids', 80, 's', false, '');
			$order_form['doi'] = $order_form['uids'];
		}
		$order_form['genre_code'] = get_from_post($form_index, 'genre', 50, 's', false, $typeDocValidSet, "");
		$order_form['title'] = get_from_post($form_index, 'title', 1000, 's', false, "");
		$order_form['date'] = get_from_post($form_index, 'date', 10, 's', false, "");
		$order_form['volume'] = get_from_post($form_index, 'volume', 50, 's', false, "");
		$order_form['issue'] = get_from_post($form_index, 'issue',100, 's', false, "");
		$order_form['suppl'] = get_from_post($form_index, 'suppl', 100, 's', false, "");
		$order_form['pages'] = get_from_post($form_index, 'pages', 50, 's', false, "");
		$order_form['atitle'] = get_from_post($form_index, 'atitle', 1000, 's', false, "");
		$order_form['auteurs'] = get_from_post($form_index, 'auteurs', 255, 's', false, "");
		$order_form['edition'] = get_from_post($form_index, 'edition', 100, 's', false, "");
		$order_form['issn'] = get_from_post($form_index, 'issn', 50, 's', false, NULL);
		$order_form['uid'] = get_from_post($form_index, 'uid', 50, 's', false, NULL);
		$order_form['remarquespub'] = get_from_post($form_index, 'remarquespub', 4000, 's', false);
		
		if (!empty($order_form['issn'])){
			if (($order_form['genre_code']=='book')||($order_form['genre_code']=='bookitem')||($order_form['genre_code']=='proceeding')||($order_form['genre_code']=='conference')){
				$order_form['isbn']=$order_form['issn'];
				$order_form['issn']=''; // TODO MDV, replaces previous set, verify if it's ok
			}
			else{
				$pos = strpos($order_form['issn'],',');
				if ($pos !== false){
					$order_form['eissn']=substr($order_form['issn'],$pos+1);
					$order_form['issn']=substr($order_form['issn'],0,$pos);
				}
			}
		}


		if($order_form['pmid']==''){
			if(strpos($order_form['uid'], 'pmid:') !== false) {
				$order_form['pmid']=str_replace("pmid:","",$order_form['uid']);
			}
		}

		//$remarquespub=((!empty($_POST['remarquespub'])) && isValidInput($_POST['remarquespub'],4000, 's', false))?$_POST['remarquespub']:'';
		//$remarquespub=str_replace("<script>","",$remarquespub);
		//$remarquespub=str_replace("</script>","",$remarquespub);
		//$remarquespub=str_replace("script","scrpt",$remarquespub);
		array_push($order_form_values, $order_form);
		$form_index += 1;
	}
/*$typedoc=((!empty($_POST['genre'])) && isValidInput($_POST['genre'],50, 's', false, $typeDocValidSet))?$_POST['genre']:'';
$journal=((!empty($_POST['title'])) && isValidInput($_POST['title'],1000, 's', false))?trim($_POST['title']):'';
$annee=((!empty($_POST['date'])) && isValidInput($_POST['date'],10, 's', false))?$_POST['date']:'';
$vol=((!empty($_POST['volume'])) && isValidInput($_POST['volume'],50, 's', false))?$_POST['volume']:'';
$no=((!empty($_POST['issue'])) && isValidInput($_POST['issue'],100, 's', false))?$_POST['issue']:'';
$suppl=((!empty($_POST['suppl'])) && isValidInput($_POST['suppl'],100, 's', false))?$_POST['suppl']:'';
$pages=((!empty($_POST['pages'])) && isValidInput($_POST['pages'],50, 's', false))?$_POST['pages']:'';
$titre=((!empty($_POST['atitle'])) && isValidInput($_POST['atitle'],1000, 's', false))?trim($_POST['atitle']):'';
$auteurs=((!empty($_POST['auteurs'])) && isValidInput($_POST['auteurs'],255, 's', false))?$_POST['auteurs']:'';
$edition=((!empty($_POST['edition'])) && isValidInput($_POST['edition'],100, 's', false))?$_POST['edition']:'';
$issn = ((!empty($_POST['issn'])) && isValidInput($_POST['issn'],50, 's', false))?$_POST['issn']:NULL;
*/
/*
if (!empty($issn)){
    if (($typedoc=='book')||($typedoc=='bookitem')||($typedoc=='proceeding')||($typedoc=='conference')){
        $isbn=$issn;
        $issn=''; // TODO MDV, replaces previous set, verify if it's ok
    }
    else{
        $pos = strpos($issn,',');
        if ($pos !== false){
            $eissn=substr($issn,$pos+1);
            $issn=substr($issn,0,$pos);
        }
    }
}


if($pmid==''){
    if(strpos($uid, 'pmid:') !== false) {
        $pmid=str_replace("pmid:","",$uid);
	}
}

$remarquespub=((!empty($_POST['remarquespub'])) && isValidInput($_POST['remarquespub'],4000, 's', false))?$_POST['remarquespub']:'';
$remarquespub=str_replace("<script>","",$remarquespub);
$remarquespub=str_replace("</script>","",$remarquespub);
//$remarquespub=str_replace("script","scrpt",$remarquespub);
*/
$bibliotheque="";
$localisation="";
$validation = 0;
$stade = NULL;
$order_form_values_count = count($order_form_values);
// retrieve default status for new orders
// 1) If multiple orders, get status marked with "newmultiple"
// 2) if single order (or not found for multiple), get status marked with "new"
if ($order_form_values_count > 1) {
	// Multiple orders: try to retrieve special initial status for new multiple orders
	$reqstatus="SELECT code FROM status WHERE status.special = ?";
	$resultstatus = dbquery($reqstatus,array('newmultiple'), 's');
	while ($rowstatus = iimysqli_result_fetch_array($resultstatus)){
		$stade = $rowstatus["code"];
	}
}
if (is_null($stade)) {
	$reqstatus="SELECT code FROM status WHERE status.special = ?";
	$resultstatus = dbquery($reqstatus,array('new'), 's');
	while ($rowstatus = iimysqli_result_fetch_array($resultstatus)){
		$stade = $rowstatus["code"];
	}
}
// retrieve default library, localization and validation constraint for given service
if (!empty($service)){
	$reqlibfromunits="SELECT library, validation FROM units WHERE units.code = ?";
	$resultunits = dbquery($reqlibfromunits,array($service), 's');
	while ($rowunits = iimysqli_result_fetch_array($resultunits)){
		$bibliotheque = $rowunits["library"];
		$localisation =  $rowunits["library"];
		$validation =  $rowunits["validation"];
	}
}

// If service require validation, retrieve default status
if ($validation == 1){
	$reqstatus="SELECT code FROM status WHERE status.special = ?";
	$resultstatus = dbquery($reqstatus,array('tobevalidated'), 's');
	while ($rowstatus = iimysqli_result_fetch_array($resultstatus))
		$stade = $rowstatus["code"];
}
//
// END common vars
//
// START admin vars
//
if ( in_array ($monaut, array('admin', 'sadmin','user'), true)){
    $remarques=((!empty($_POST['remarques'])) && isValidInput($_POST['remarques'],4000, 's', false))?$_POST['remarques']:'';
    // overwrite localisation if given
	$localisation= ((!empty($_POST['localisation'])) && isValidInput($_POST['localisation'],20,'s',false))? $_POST['localisation']: $localisation;
	// overwrite stade with computed localization if left as default.
    $stade=((!empty($_POST['stade'])) && isValidInput($_POST['stade'],3,'i',false) && $_POST['stade'] != "0")? $_POST['stade']:$stade;
    $date= ((!empty($_POST['datesaisie'])) && validateDate($_POST['datesaisie']))?$_POST['datesaisie']:NULL;
    if(empty($date))
        $date=date("Y-m-d");
    $date2=date("d/m/Y H:i:s");
    $envoye=((!empty($_POST['envoye'])) && validateDate($_POST['envoye']))?$_POST['envoye']:NULL;
    $facture=((!empty($_POST['facture'])) && validateDate($_POST['facture']))?$_POST['facture']:NULL;
    $renouveler=((!empty($_POST['renouveler'])) && validateDate($_POST['renouveler']))?$_POST['renouveler']:NULL;
    $reqstatus="SELECT code FROM status WHERE status.special = ?";
    $resultstatus = dbquery($reqstatus,array('renew'), 's');
    while ($rowstatus = iimysqli_result_fetch_array($resultstatus)){
        $codestatus = $rowstatus["code"];
        if (($stade==$codestatus) && empty($renouveler)){
            $renouveler = date("Y-m-d", mktime(0, 0, 0, date("m")+1, date("d"), date("Y")));
        }
    }
	// overwrite bibliotheque if given
    $bibliotheque=((!empty($_POST['bibliotheque'])) && isValidInput($_POST['bibliotheque'],50, 's', false))?$_POST['bibliotheque']:$bibliotheque;
    $prix=((!empty($_POST['prix'])) && isValidInput($_POST['prix'],4, 's', false))?$_POST['prix']:'';
    $prepaye=((!empty($_POST['avance'])) && isValidInput($_POST['avance'],3, 's', false))?$_POST['avance']:'';
    $urgent=((!empty($_POST['urgent'])) && isValidInput($_POST['urgent'],3, 's', false))?$_POST['urgent']:'';
    $ref=((!empty($_POST['ref'])) && isValidInput($_POST['ref'],50, 's', false))?$_POST['ref']:'';
    $refinterbib=((!empty($_POST['refinterbib'])) && isValidInput($_POST['refinterbib'],50, 's', false))?$_POST['refinterbib']:'';
    $is_valid_timestamp_check = true; # always valid when authenticated
    // END admin vars
}
else{
    // START public vars
    $remarques = "";
    $date=date("Y-m-d");
    $date2=date("d/m/Y H:i:s");
	// When no library found for given service, use main library and localization
	if ($bibliotheque == ""){
		$reqlibdefault="SELECT code FROM libraries WHERE libraries.default = ?";
		$resultlibdefault = dbquery($reqlibdefault,array(1),'s');
		while ($rowlibdefault = iimysqli_result_fetch_array($resultlibdefault)){
			$bibliotheque = $rowlibdefault["code"];
			$localisation =  $rowlibdefault["code"];
		}
	}
    $is_valid_timestamp_check = true;
    if ($ipwww == 1 && $config_secure_secret_key != '' && !empty($config_secure_secret_key)) {
        // outside institution IP range
        $current_timestamp = time();
        $checked_timestamp_signature = hash_hmac("sha256", strval($timestamp), $config_secure_secret_key);
        if (!hash_equals($checked_timestamp_signature, $timestamp_signature)) {
            $is_valid_timestamp_check = false;
        } else if (($current_timestamp - $timestamp) < $config_min_form_filling_time) {
            // Form was filled in too quickly (less than configured).
            $is_valid_timestamp_check = false;
        } else if (($current_timestamp - $timestamp) > 60 * 60 * 24 ) {
            // Form was filled in too slow (more 24 hours).
            $is_valid_timestamp_check = false;
        } 
    }
    // END public vars
}


$historique= format_string(__("Order entered by %user_id on the %date"), array('user_id' => $userid, 'date' => $date2));
if (empty($nom))
    $mes= __("name required");
if (empty($service) && empty($servautre))
    $mes=$mes."<br>".__("service or organisation name is required");
foreach($order_form_values as $order_form) {
	if (empty($order_form['title'])) {
		$mes=$mes."<br>".__("journal or book title is required");
		break;
	}
}
if (empty($mail) && empty($adresse))
    $mes=$mes."<br>".__("e-mail or private address are required");
if (empty($consent)) {
	if (($config_dataprotection_consent_mode == 2 || ($config_dataprotection_consent_mode == 1 && !is_privileged_enough($monaut, "user"))) &&
		($config_dataprotection_consent_conditionsofuse_url[$lang] != "" || $config_dataprotection_consent_legal_information_url[$lang] != "")) {

			  if ($config_dataprotection_consent_conditionsofuse_url[$lang] != "" && $config_dataprotection_consent_legal_information_url[$lang] != "") {
				$mes .= "<br/>".__("You must agree to the service privacy policy and conditions of use");
			  } else if ($config_dataprotection_consent_legal_information_url[$lang] != "") {
				$mes .= "<br/>".__("You must agree to the service privacy policy");
			  } else if ($config_dataprotection_consent_conditionsofuse_url[$lang] != "") {
				$mes .= "<br/>".__("You must agree to the service conditions of use");
			  }
	}
}

if ($mes){
    if (in_array ( $monaut, array('admin', 'sadmin', 'user'), true ))
        require ("headeradmin.php");
    else
        require ("header.php");
    echo "\n";
    echo "<div class=\"box\"><div class=\"box-content\">\n";
    echo "<center><b><font color=\"red\">\n";
    echo $mes."</b></font>\n";
    echo "<br /><br /><a href=\"javascript:history.back();\"><b>".__("Back to entry form")."</a></b></center><br />\n";
    echo "</div></div><div class=\"box-footer\"><div class=\"box-footer-right\"></div></div>\n";
    require ("footer.php");
// Error message
}
else{
    
    
    if ($validation_field == "" && $is_valid_timestamp_check) { // Silently (more or less) ignore otherwise
    // No errors, searching duplicates
    // Recherche de doublons par PMID ou par volume année et pages
	$order_index = 0;
	foreach($order_form_values as $index => $order_form) {
		$order_index += 1;
		$pages_array = preg_split("/[\s,-]+/", $order_form['pages']);
		$start_page = reset($pages_array); // Retrieve first value, in a php < 5.4 compatible manner
		$start_page_regexp = '^' . preg_quote($start_page) . '([^0-9]|$)';
		$req2 = "";
		if ($order_form['pmid']!=''){
			if (($order_form['volume']!='') && ($order_form['date']!='') && ($start_page!='')) {
				$req2 = "SELECT illinkid FROM orders WHERE PMID LIKE ? OR (annee LIKE ? AND volume LIKE ? AND pages RLIKE ?) ORDER BY illinkid DESC";
				$param2 = array($order_form['pmid'], $order_form['date'], $order_form['volume'], $start_page_regexp);
				$typeparam2 = 'ssss';
			}
			else {
				$req2 = "SELECT illinkid FROM orders WHERE PMID LIKE ? ORDER BY illinkid DESC";
				$param2 = array($order_form['pmid']);
				$typeparam2 = 's';
			}
		}
		else{
			if (($order_form['volume']!='') && ($order_form['date']!='') && ($start_page!='')){
				$req2 = "SELECT illinkid FROM orders WHERE annee LIKE ? AND volume LIKE ? AND pages RLIKE ? ORDER BY illinkid DESC";
				$param2 = array($order_form['date'], $order_form['volume'], $start_page_regexp);
				$typeparam2 = 'sss';
			}
		}
		$order_form['remarques'] = $remarques;
		if ($req2!=''){
			$result2 = dbquery($req2,$param2,$typeparam2);
			$nb = iimysqli_num_rows($result2);
			if ($nb > 0){
				if ($order_form['remarques'])
					$order_form['remarques'] .= "\r\n";
				$order_form['remarques'] .= __("Warning. Possible duplicate of the order."); // string must be kept in sync with order_details.php for highlighting
				for ($i=0 ; $i<$nb ; $i++){
					$enreg2 = iimysqli_result_fetch_array($result2);
					$doublon = $enreg2['illinkid'];
					$order_form['remarques'] .= " ".$doublon;
				}
			}
		}
		// fin de la recherche des doublons
		/* Label multiple orders */
		if ($order_form_values_count > 1) {
			if ($order_form['remarques']) {
				$order_form['remarques'] .= "\r\n";
			}
			$order_form['remarques'] .= sprintf(__("Multiple orders (%d/%d)"), $order_index, $order_form_values_count);
		}
		$order_form_values[$index]['remarques'] = $order_form['remarques']; // save to $order_form_values for later reuse when iterating from this variable
		// START save record
		if ( in_array ($monaut, array('admin', 'sadmin','user'), true)){
			$query ="INSERT INTO `orders` (`stade`, `localisation`, `date`, `envoye`, `facture`, `renouveler`, `prix`, `prepaye`, `ref`, `arrivee`, `nom`, `prenom`, `service`, `cgra`, `cgrb`, `mail`, `tel`, `adresse`, `code_postal`, `localite`, `type_doc`, `urgent`, `envoi_par`, `titre_periodique`, `annee`, `volume`, `numero`, `supplement`, `pages`, `titre_article`, `auteurs`, `edition`, `isbn`, `issn`, `eissn`, `doi`, `uid`, `remarques`, `remarquespub`, `historique`, `saisie_par`, `bibliotheque`, `refinterbib`, `PMID`, `ip`, `referer`, `user_consent`, `sid`, `pid`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
			$params = array(!empty($stade) ? $stade : 0, $localisation, $date, $envoye, $facture, $renouveler, $prix, $prepaye, $ref, $source, $nom, $prenom, $service, $cgra, $cgrb, $mail, $tel, $adresse, $postal, $localite, $order_form['genre_code'], $urgent, $envoi, $order_form['title'], $order_form['date'], $order_form['volume'], $order_form['issue'], $order_form['suppl'], $order_form['pages'], $order_form['atitle'], $order_form['auteurs'], $order_form['edition'], $order_form['isbn'], $order_form['issn'], $order_form['eissn'], $order_form['doi'], $order_form['uid'], $order_form['remarques'], $order_form['remarquespub'], $historique, $userid, $bibliotheque, $refinterbib, $order_form['pmid'], $ip, $referer, $consent, $sid, $pid);
			$monno = dbquery($query, $params, 'issssssssssssssssssssssssssssssssssssssssssssssss') or die("Error : ".mysqli_error(dbconnect()));
			update_folders_item_count();
		}
		else{
			$query ="INSERT INTO `orders` (`stade`, `localisation`, `date`, `envoye`, `facture`, `renouveler`, `prix`, `prepaye`, `ref`, `arrivee`, `nom`, `prenom`, `service`, `cgra`, `cgrb`, `mail`, `tel`, `adresse`, `code_postal`, `localite`, `type_doc`, `urgent`, `envoi_par`, `titre_periodique`, `annee`, `volume`, `numero`, `supplement`, `pages`, `titre_article`, `auteurs`, `edition`, `isbn`, `issn`, `eissn`, `doi`, `uid`, `remarques`, `remarquespub`, `historique`, `saisie_par`, `bibliotheque`, `refinterbib`, `PMID`, `ip`, `referer`, `user_consent`, `sid`, `pid`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
			$params = array(!empty($stade) ? $stade : 0, $localisation, $date, NULL , NULL, NULL, '', '', '', $source, $nom, $prenom, $service, $cgra, $cgrb, $mail, $tel, $adresse, $postal, $localite, $order_form['genre_code'], '2', $envoi, $order_form['title'], $order_form['date'], $order_form['volume'], $order_form['issue'], $order_form['suppl'], $order_form['pages'], $order_form['atitle'], $order_form['auteurs'], $order_form['edition'], $order_form['isbn'], $order_form['issn'], $order_form['eissn'], $order_form['doi'], $order_form['uid'], $order_form['remarques'], $order_form['remarquespub'], $historique, $userid, $bibliotheque, '', $order_form['pmid'], $ip, $referer, $consent, $sid, $pid);
			$monno = dbquery($query, $params, 'issssssssssssssssssssssssssssssssssssssssssssssss') or die("Error : ".mysqli_error(dbconnect()));
			update_folders_item_count();
		}
    }
    $success_message = __("Your order has been successfully registered and will be processed soon");
    $success_color = "green";
	} else {
        $monno = "0";
        $success_message = format_string(__("Your order has NOT been successfully registered. Please contact the library at %x_configemaildelivery to place your order"), array('x_configemaildelivery' => $configemaildelivery));
        $success_color = "red";
    }
	if ( in_array ($monaut, array('admin', 'sadmin','user'), true)){
		require ("headeradmin.php");
	} else {
		require ("header.php");
	}
    echo "\n";
    echo "<div class=\"box\"><div class=\"box-content\">\n";
    echo "\n";
echo "<br/>\n";
if ($debugOn){
    echo 'userid(post):'.$_POST['userid'].';';
    echo 'userid(server):'.$_SERVER['REMOTE_ADDR'].';';
}
echo "<br/>\n";
echo "\n";
    echo "<center><b><font color=\"".$success_color."\">".$success_message."</b></center></font>\n";
    echo "<div class=\"hr\"><hr></div>\n";
    echo "<table class=\"table is-striped\" align=\"center\">\n";
    echo "</td></tr>\n";
    echo "<tr><td width=\"90\"><b>".__("Order")."</b></td>\n";
    echo "<td><b>$monno</b></td></tr>\n";
    echo "<tr><td width=\"90\"><b>".__("Name")."</b></td>\n";
    echo "<td>".htmlspecialchars($nom). ", ". htmlspecialchars($prenom)."</td></tr>\n";
    if ($mail) {
        echo "<tr><td width=\"90\"><b>".__("E-Mail")."</b></td>\n";
        echo "<td>".htmlspecialchars($mail)."</td></tr>\n";
    }
    if ($service) {
        echo "<tr><td width=\"90\"><b>".__("Service")."</b></td>\n";
        echo "<td>".htmlspecialchars($service)."</td></tr>\n";
    }
    if ($tel) {
        echo "<tr><td width=\"90\"><b>".__("Tel.")."</b></td>\n";
        echo "<td>" . htmlspecialchars($tel) . "</td></tr>\n";
    }
    if ($adresse) {
        echo "<tr><td width=\"90\"><b>".__("Address")."</b></td>\n";
        echo "<td>" . htmlspecialchars ($adresse) . " ; " . htmlspecialchars ($postal) . ", " . htmlspecialchars ($localite) ."</td></tr>\n";
    }
	foreach($order_form_values as $order_form) {
		echo "<tr><td style=\"border-top:1px dotted #ccc;\" width=\"90\"><b>".__("Document")."</b></td>\n";
		echo "<td style=\"border-top:1px dotted #ccc;\">".htmlspecialchars($order_form['genre_code'])."</td></tr>\n";
		if ($order_form['atitle']) {
			echo "<tr><td width=\"90\"><b>".__("Title")."</b></td>\n";
			echo "<td>" . htmlspecialchars ($order_form['atitle']) . "</td></tr>\n";
		}
		if ($order_form['auteurs']) {
			echo "<tr><td width=\"90\"><b>".__("Authors")."</b></td>\n";
			echo "<td>" . htmlspecialchars ($order_form['auteurs']) . "</td></tr>\n";
		}
		if ($order_form['genre_code'] == 'Article')
			echo "<tr><td width=\"90\"><b>".__("Journal")."</b></td>\n";
		else
			echo "<tr><td width=\"90\"><b>".__("Book title")."</b></td>\n";
		echo "<td>" . htmlspecialchars ($order_form['title']) . "</td>\n";
		echo "</tr><tr>\n";
		if ($order_form['date']) {
			echo "<td width=\"90\"><b>".__("Year")."</b></td>\n";
			echo "<td>".htmlspecialchars ($order_form['date'])."</td></tr>\n";
		}
		if ($order_form['volume']) {
			echo "<tr><td  width=\"90\" valign=\"top\"><b>".__("Volume")."</b></td>\n";
			echo "<td>".htmlspecialchars ($order_form['volume'])."</td></tr>\n";
		}
		if ($order_form['issue']) {
			echo "<tr><td  width=\"90\" valign=\"top\"><b>".__("Number")."</b></td>\n";
			echo "<td>".htmlspecialchars ($order_form['issue'])."</td></tr>\n";
		}
		if ($order_form['suppl']) {
			echo "<tr><td  width=\"90\" valign=\"top\"><b>".__("Suppl.")."</b></td>\n";
			echo "<td>".htmlspecialchars ($order_form['suppl'])."</td></tr>\n";
		}
		if ($order_form['pages']) {
			echo "<tr><td  width=\"90\" valign=\"top\"><b>".__("Pages")."</b></td>\n";
			echo "<td>".htmlspecialchars ($order_form['pages'])."</td></tr>\n";
		}
		if ($order_form['edition']) {
			echo "<tr><td  width=\"90\" valign=\"top\"><b>".__("Edition")."</b></td>\n";
			echo "<td>".htmlspecialchars ($order_form['edition'])."</td></tr>\n";
		}
		if ($order_form['isbn']) {
			echo "<tr><td  width=\"90\" valign=\"top\"><b>ISBN</b></td>\n";
			echo "<td>".htmlspecialchars ($order_form['isbn'])."</td></tr>\n";
		}
		if ($order_form['issn']) {
			echo "<tr><td  width=\"90\" valign=\"top\"><b>ISSN</b></td>\n";
			echo "<td>".htmlspecialchars ($order_form['issn'])."</td></tr>\n";
		}
		if ($order_form['eissn']) {
			echo "<tr><td  width=\"90\" valign=\"top\"><b>eISSN</b></td>\n";
			echo "<td>".htmlspecialchars ($order_form['eissn'])."</td></tr>\n";
		}
		if ($order_form['pmid']) {
			echo "<tr><td  width=\"90\" valign=\"top\"><b>PMID</b></td>\n";
			echo "<td>".htmlspecialchars($order_form['pmid'])."</td></tr>\n";
		}
		if ($order_form['doi']) {
			echo "<tr><td  width=\"90\" valign=\"top\"><b>DOI</b></td>\n";
			echo "<td>".htmlspecialchars($order_form['doi'])."</td></tr>\n";
		}
		if ($order_form['uid']) {
			echo "<tr><td  width=\"90\" valign=\"top\"><b>UID</b></td>\n";
			echo "<td>".htmlspecialchars ($order_form['uid'])."</td></tr>\n";
		}
		if (in_array($monaut, array('admin', 'sadmin','user'), true)){
			if ($order_form['remarques']) {
				echo "<tr><td  width=\"90\" valign=\"top\"><b>".__("Professional comment")."</b></td>\n";
				echo "<td>". nl2br(htmlspecialchars($order_form['remarques']))."</td></tr>\n";
			}
		}
		if ($order_form['remarquespub']) {
			echo "<tr><td  width=\"90\" valign=\"top\"><b>".__("Public comment")."</b></td>\n";
			echo "<td>". nl2br(htmlspecialchars($order_form['remarquespub']))."</td></tr>\n";
		}
	}
    echo "</table>\n";
    echo "<div class=\"hr\"><hr></div>\n";
    echo "<b><center><a href=\"index.php\">".__("Fill in a new order")."</a></center></b>\n";
    echo "</div></div><div class=\"box-footer\"><div class=\"box-footer-right\"></div></div>\n";
    require ("footer.php");
}
?>
