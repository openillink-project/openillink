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
// 
// Home page : order form
//

require ("includes/config.php");
require ("includes/authip.php");
require ("includes/authcookie.php");
require_once ("includes/connexion.php");
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
							'remarquespub' => "");

$uploaded_orders_messages = array(); // array of messages (error_level, msg, title) related to display to the user

function get_from_post($form_index, $key) {
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

$nom = !empty($_POST['nom'])? $_POST['nom'] : '';
$prenom = !empty($_POST['prenom']) ? $_POST['prenom'] : '';
$service = !empty($_POST['service']) ? $_POST['service'] : '';
$servautre = !empty($_POST['servautre']) ? $_POST['servautre'] : '';
$cgra = !empty($_POST['cgra']) ? $_POST['cgra'] : '';
$cgrb = !empty($_POST['cgrb']) ? $_POST['cgrb'] : '';
$mail = !empty($_POST['mail']) ? $_POST['mail'] : '';
$tel = !empty($_POST['tel']) ? $_POST['tel'] : '';
$adresse = !empty($_POST['adresse']) ? $_POST['adresse'] : '';
$postal = !empty($_POST['postal']) ? $_POST['postal'] : '';
$localite = !empty($_POST['localite']) ? $_POST['localite'] : '';
$envoi = !empty($_POST['envoi']) ? $_POST['envoi'] : '';
$cooc = !empty($_POST['cooc']) ? $_POST['cooc'] : '';

$stade = !empty($_POST['stade']) ? $_POST['stade'] : '';
$localisation = !empty($_POST['localisation']) ? $_POST['localisation'] : '';
$urgent = !empty($_POST['urgent']) ? $_POST['urgent'] : '';
$source = !empty($_POST['source']) ? $_POST['source'] : '';
$sourcenew = !empty($_POST['sourcenew']) ? $_POST['sourcenew'] : '';
$datesaisie = !empty($_POST['datesaisie']) ? $_POST['datesaisie'] : '';
$envoye = !empty($_POST['envoye']) ? $_POST['envoye'] : '';
$facture = !empty($_POST['facture']) ? $_POST['facture'] : '';
$renouveler = !empty($_POST['renouveler']) ? $_POST['renouveler'] : '';
$prix = !empty($_POST['prix']) ? $_POST['prix'] : '';
$avance = !empty($_POST['avance']) ? $_POST['avance'] : '';
$ref = !empty($_POST['ref']) ? $_POST['ref'] : '';
$refinterbib = !empty($_POST['refinterbib']) ? $_POST['refinterbib'] : '';
$remarques = !empty($_POST['remarques']) ? $_POST['remarques'] : '';

$order_form_values = array();

if (isset($_POST['tid_0']) || isset($_GET['tid_0'])) {
	// load from $POST
	$form_index = 0;
	while ($form_index <= max($maxSimultaneousOrders, 1) && (isset($_POST['tid_'.$form_index]) || isset($_GET['tid_'.$form_index]))){
		$order_form = $default_order_form;
		$order_form['tid_code'] = get_from_post($form_index, 'tid');
		$order_form['uids'] = get_from_post($form_index, 'uids');
		$order_form['genre_code'] = get_from_post($form_index, 'genre');
		$order_form['title'] = get_from_post($form_index, 'title');
		$order_form['date'] = get_from_post($form_index, 'date');
		$order_form['volume'] = get_from_post($form_index, 'volume');
		$order_form['issue'] = get_from_post($form_index, 'issue');
		$order_form['suppl'] = get_from_post($form_index, 'suppl');
		$order_form['pages'] = get_from_post($form_index, 'pages');
		$order_form['atitle'] = get_from_post($form_index, 'atitle');
		$order_form['auteurs'] = get_from_post($form_index, 'auteurs');
		$order_form['edition'] = get_from_post($form_index, 'edition');
		$order_form['issn'] = get_from_post($form_index, 'issn');
		$order_form['uid'] = get_from_post($form_index, 'uid');
		$order_form['remarquespub'] = get_from_post($form_index, 'remarquespub');
		array_push($order_form_values, $order_form);
		$form_index += 1;
	}
}

if (!empty($_FILES['order_file']) && 
	 ((isset($_SERVER['CONTENT_LENGTH']) && (int) $_SERVER['CONTENT_LENGTH'] > (1024*1024*(int) ini_get('post_max_size'))) ||
	     $_FILES['order_file']['error'] === UPLOAD_ERR_INI_SIZE)) {
        // The uploaded file was too large
		array_push($uploaded_orders_messages, get_message_box(sprintf(__("The uploaded file is too large (limit: %s MB). It has been ignored."), round(min(parse_size_str(ini_get('upload_max_filesize')), parse_size_str(ini_get('post_max_size'))) / (1024*1024), 0, PHP_ROUND_HALF_DOWN)), 'danger', __("Error:")));
}
if (!empty($_FILES['order_file']) && $_FILES['order_file']['size'] > 0 && is_privileged_enough($monaut, $enableOrdersUploadForUser)) {
	// load from file
	// Remove the first order line if it is empty (it is the default empty form)
	if (count($order_form_values) == 1 && $order_form_values[0]['title'] == "" && $order_form_values[0]['uids'] == "") {
		$order_form_values = array();
	}
	require_once('includes/vendor/RefLib/reflib.php');
	$lib = new RefLib();
	$lib->SetContentsFile($_FILES['order_file']['tmp_name'], pathinfo($_FILES['order_file']['name'], PATHINFO_EXTENSION)); // rather use SetContents
	foreach ($lib->refs as $ref_index => $ref) {
		$order_form = $default_order_form;
		if (!empty($ref['type'])) {
			switch ($ref['type']) {
				case 'Electronic Article':
					$order_form['genre_code'] = 'article';
					break;
				case 'Journal Article':
					$order_form['genre_code'] = 'article';
					break;
				case 'Book':
					$order_form['genre_code'] = 'book';
					break;
				case 'Book Section':
					$order_form['genre_code'] = 'bookitem';
					break;
				case 'Electronic Book Section':
					$order_form['genre_code'] = 'bookitem';
					break;
				case 'Thesis':
					$order_form['genre_code'] = 'thesis';
					break;
				case 'Serial':
					$order_form['genre_code'] = 'journal';
					break;
				case 'Conference Proceedings':
					$order_form['genre_code'] = 'proceeding';
					break;
				case 'Conference Paper':
					$order_form['genre_code'] = 'conference';
					break;
				default:
					$order_form['genre_code'] = '';
			}
		}
		$order_form['title'] = !empty($ref['periodical-title']) ? $ref['periodical-title'] : (!empty($ref['title-secondary']) ? $ref['title-secondary'] : '');
		$order_form['date'] = !empty($ref['year']) ? $ref['year'] : (!empty($ref['date']) ? date('Y', $ref['date']) : '');
		$order_form['volume'] = !empty($ref['volume']) ? $ref['volume'] : '';
		$order_form['issue'] = !empty($ref['number']) ? $ref['number'] : '';
		$order_form['suppl'] = !empty($ref['']) ? $ref[''] : '';
		$order_form['pages'] = !empty($ref['pages']) ? $ref['pages'] : '';
		$order_form['atitle'] = !empty($ref['title']) ? $ref['title'] : '';
		$order_form['auteurs'] = !empty($ref['authors']) ? implode(", ", $ref['authors']) : '';
		$order_form['edition'] = !empty($ref['']) ? $ref[''] : '';
		$order_form['issn'] = !empty($ref['isbn']) ? $ref['isbn'] : '';
		if (!empty($ref['accession-num'])) {
			$order_form['tid_code'] = 'pmid';
			$order_form['uids'] = $ref['accession-num'];
			$order_form['uid'] = 'pmid:' . $ref['accession-num'];
		} else if (!empty($ref['doi'])) {
			$order_form['tid_code'] = 'doi';
			$order_form['uids'] = $ref['doi'];
			$order_form['uid'] = 'doi:' . $ref['doi'];
		}
		$order_form['remarquespub'] = !empty($ref['notes']) ? $ref['notes'] : '';
		array_push($order_form_values, $order_form);
		if (count($order_form_values) >= max($maxSimultaneousOrders, 1)) {
			array_push($uploaded_orders_messages, get_message_box(sprintf(__("The maximum number of simultaneous orders (%d) has been reached. Remaining references have been ignored."), $maxSimultaneousOrders), 'warning', __("Warning")));
			break;
		}
	}
}
// Make sure we always display at least one line
if (count($order_form_values) == 0) {
	array_push($order_form_values, $default_order_form);
}
// Add one more line?
if  (isset($_POST['add_form']) &&  $_POST['add_form'] == '1' && (count($order_form_values) <= max($maxSimultaneousOrders, 1))){
	array_push($order_form_values, $default_order_form);
}
// remove a line?
if  (isset($_POST['remove_form']) && is_numeric($_POST['remove_form']) && intval($_POST['remove_form']) >= 0 && count($order_form_values) > 1){
	array_splice($order_form_values, intval($_POST['remove_form']), 1);
}

$myhtmltitle = $configname[$lang] . " : nouvelle commande";
$mybodyonload = "";
if (!isset($_POST['add_form'])){
	$mybodyonload .= "document.commande.nom.focus();";
}
$mybodyonload .= " remplirauto();";
if (($monaut == "admin")||($monaut == "sadmin")||($monaut == "user")){
    require ("includes/headeradmin.php");
    echo "<h1><center>" . __("Document order form to the ") . " <a href=\"" . $configlibraryurl[$lang] . "\" target=\"_blank\">" . $configlibrary[$lang] . "</a></center></h1>\n";
	if (isset($secondmessage) && array_key_exists($lang, $secondmessage)) {
		echo "<h2><center>" . $secondmessage[$lang] . "</center></h2>\n";
	}
    echo "<script type=\"text/javascript\">\n";
    echo "function textchanged(changes) {\n";
    echo "document.fiche.modifs.value = document.fiche.modifs.value + changes + ' - ';\n";
    echo "}\n";
    echo "function ajoutevaleur(champ) {\n";
    echo "var champ2 = champ + 'new';\n";
    echo "var res = document.getElementById(champ).value;\n";
    echo "if (res == 'new')\n";
    echo "{\n";
    echo "document.getElementById(champ2).style.display='inline';\n";
    echo "}\n";
    echo "}\n";
    echo "</script>\n";
    echo "<form action=\"new.php\" method=\"POST\" enctype=\"multipart/form-data\" id=\"orderform\" name=\"commande\" onsubmit=\"javascript:okcooc()\">\n";
    // START Management Fields
    echo "<input name=\"table\" type=\"hidden\"  value=\"orders\">\n";
    echo "<input name=\"userid\" type=\"hidden\"  value=\"".htmlspecialchars($monnom)."\">\n";
    echo "<input name=\"bibliotheque\" type=\"hidden\"  value=\"".htmlspecialchars($monbib)."\">\n";
    echo "<input name=\"sid\" type=\"hidden\"  value=\"\">\n";
    echo "<input name=\"pid\" type=\"hidden\"  value=\"\">\n";
    if (!empty($referer))
        echo "<input name=\"referer\" type=\"hidden\" value=\"" . htmlspecialchars(rawurlencode($referer)) . "\">\n";
    else
        echo "<input name=\"referer\" type=\"hidden\" value=\"\">\n";
    echo "<input name=\"action\" type=\"hidden\" value=\"saisie\">\n";
    echo "<input name=\"source\" type=\"hidden\" value=\"adminform\">\n";
    echo "<div class=\"box\"><div class=\"box-content\">\n";
    echo "<table border=\"0\" cellspacing=\"0\" cellpadding=\"3\" width=\"100%\">\n";
    echo "<tr><td colspan=\"4\">\n";
	echo '<label for="stade">';
    echo __("Status") . " * </label>: \n";
    echo "<select name=\"stade\" id=\"stade\">\n";
    $reqstatus="SELECT code, title1, title2, title3, title4, title5 FROM status ORDER BY code ASC";
    $optionsstatus="";
    $resultstatus = dbquery($reqstatus);
    while ($rowstatus = iimysqli_result_fetch_array($resultstatus)){
        $codestatus = $rowstatus["code"];
        $namestatus["fr"] = $rowstatus["title1"];
        $namestatus["en"] = $rowstatus["title2"];
        $namestatus["de"] = $rowstatus["title3"];
        $namestatus["it"] = $rowstatus["title4"];
        $namestatus["es"] = $rowstatus["title5"];
        $optionsstatus.="<option value=\"" . htmlspecialchars($codestatus) . "\"";
        $optionsstatus.= ($codestatus == $stade ? " selected " : ""). ">" . htmlspecialchars($namestatus[$lang]) . "</option>\n";
    }
    echo $optionsstatus;
    echo "</select>\n";
    echo "&nbsp;&nbsp;&nbsp;&nbsp;\n";
	echo '<label for="localisation">';
    echo __("Localization") . "</label> : &nbsp;\n";
    echo "<select name=\"localisation\" id=\"localisation\">\n";
    echo "<option value=\"\"></option>";
    echo "<optgroup label=\"" . __("Our Localizations") . "\">\n";
    $reqlocalisation="SELECT code, library, name1, name2, name3, name4, name5 FROM localizations WHERE library = ? ORDER BY name1 ASC";
    $optionslocalisation="";
    $resultlocalisation = dbquery($reqlocalisation, array($monbib), "s");
    while ($rowlocalisation = iimysqli_result_fetch_array($resultlocalisation)){
        $codelocalisation = $rowlocalisation["code"];
        $namelocalisation["fr"] = $rowlocalisation["name1"];
        $namelocalisation["en"] = $rowlocalisation["name2"];
        $namelocalisation["de"] = $rowlocalisation["name3"];
        $namelocalisation["it"] = $rowlocalisation["name4"];
        $namelocalisation["es"] = $rowlocalisation["name5"];
        $optionslocalisation.="<option value=\"".htmlspecialchars($codelocalisation)."\"";
        $optionslocalisation.= ($codelocalisation == $localisation ? " selected " : "").">" . htmlspecialchars($namelocalisation[$lang]) . "</option>\n";
    }
    echo $optionslocalisation;
    // select other libraries
    $reqlocalisationext="SELECT code, name1, name2, name3, name4, name5 FROM libraries WHERE code != ? ORDER BY name1 ASC";
    $optionslocalisationext="";
    $resultlocalisationext = dbquery($reqlocalisationext, array($monbib), "s");
    $nbext = iimysqli_num_rows($resultlocalisationext);
    if ($nbext > 0){
        while ($rowlocalisationext = iimysqli_result_fetch_array($resultlocalisationext)){
            $codelocalisationext = $rowlocalisationext["code"];
            $namelocalisationext["fr"] = $rowlocalisationext["name1"];
            $namelocalisationext["en"] = $rowlocalisationext["name2"];
            $namelocalisationext["de"] = $rowlocalisationext["name3"];
            $namelocalisationext["it"] = $rowlocalisationext["name4"];
            $namelocalisationext["es"] = $rowlocalisationext["name5"];
            $optionslocalisationext.="<option value=\"".htmlspecialchars($codelocalisationext)."\"".($codelocalisationext == $localisation ? " selected " : ""). ">" . htmlspecialchars($namelocalisationext[$lang]) . "</option>\n";
        }
        echo "<optgroup label=\"" . htmlspecialchars(__("Network libraries")) . "\">\n";
        echo $optionslocalisationext;
    }
    echo "</select>\n";
    echo "</td></tr>";

    echo "<tr><td colspan=\"4\">\n";
	echo '<label for="urgent">';
    echo __("Priority") . "</label> : <select name=\"urgent\" id=\"urgent\">\n";
    echo "<option value=\"2\"".('2' == $urgent ? " selected " : ""). ">" . __("Normal") . "</option>\n";
    echo "<option value=\"1\"".('1' == $urgent ? " selected " : ""). ">" . __("Urgent") . "</option>\n";
    echo "<option value=\"3\"".('3' == $urgent ? " selected " : ""). ">" . __("Not a priority") . "</option>\n";
    echo "</select>\n";
	if ($displayFormOrderSourceField) {
		echo "&nbsp;&nbsp;&nbsp;&nbsp;<label for=\"source\">" . __("Origin of the order") . "</label> : \n";
		echo "<select name=\"source\" id=\"source\" onchange=\"ajoutevaleur('source');\">\n";
		echo "<option value=\"\"> </option>\n";
		$reqsource = "SELECT arrivee FROM orders WHERE arrivee != '' GROUP BY arrivee ORDER BY arrivee ASC";
		$optionssource = "";
		$resultsource = dbquery($reqsource);
		while ($rowsource = iimysqli_result_fetch_array($resultsource)){
			$codesource = $rowsource["arrivee"];
			$optionssource.="<option value=\"".htmlspecialchars($codesource)."\"".($codesource == $source ? " selected " : ""). ">".htmlspecialchars($codesource)."</option>\n";
		}
		echo $optionssource;
		echo "<option value=\"new\"".($source == "new" ? " selected " : ""). ">" . __("Add new value...") . "</option>\n";
		echo "</select>\n";
		echo "&nbsp;<input name=\"sourcenew\" id=\"sourcenew\" type=\"text\" size=\"20\" value=\"".htmlspecialchars($sourcenew)."\" style=\"". ($source == "new" ? "display:inline" : "display:none")."\">\n";
	}
    echo "</td></tr><tr><td>\n";
	echo '<label for="datesaisie">';
    echo "<a href=\"#\" title=\"" . __("to be completed only if different from the current date") . "\">" . __("Order date") . "</a></label> : </td><td> \n";
    echo "<input name=\"datesaisie\" id=\"datesaisie\" type=\"text\" size=\"10\" value=\"".htmlspecialchars($datesaisie)."\" class=\"tcal\">\n";
    echo "</td><td>\n";
	echo '<label for="envoye">';
    echo __("Date of shipment") . "</label> : </td><td>\n";
    echo "<input name=\"envoye\" id=\"envoye\" type=\"text\" size=\"10\" value=\"".htmlspecialchars($envoye)."\" class=\"tcal\">\n";
    echo "</td></tr><tr><td>\n";
	echo '<label for="facture">';
    echo __("Invoice date") . "</label> : </td><td>\n";
    echo "<input name=\"facture\" id=\"facture\" type=\"text\" size=\"10\" value=\"".htmlspecialchars($facture)."\" class=\"tcal\">\n";
    echo "</td><td>\n";
	echo '<label for="renouveler">';
    echo __("To be renewed on") . "</label> : </td><td>\n";
    echo "<input name=\"renouveler\" id=\"renouveler\" type=\"text\" size=\"10\" value=\"".htmlspecialchars($renouveler)."\" class=\"tcal\">\n";
    echo "</td></tr><tr><td colspan=\"4\">\n";
	echo '<label for="prix">';
    echo format_string(__("Price (%currency)"), array('currency' => $currency)) . "</label> : &nbsp;\n";
    echo "<input name=\"prix\" id=\"prix\" type=\"text\" size=\"5\" value=\"".htmlspecialchars($prix)."\">\n";
    echo "&nbsp;&nbsp;(<input type=\"checkbox\" name=\"avance\" id=\"avance\" value=\"on\" ".($avance == "on" ? " checked ": "")."/><label for=\"avance\">" . __("order paid in advance") . "</label>) &nbsp;&nbsp;&nbsp;&nbsp;\n";
    echo "</td></tr><tr><td colspan=\"4\">\n";
	echo '<label for="ref">';
    echo __("Provider Ref.") . "</label> : &nbsp;\n";
    echo "<input name=\"ref\" id=\"ref\" type=\"text\" size=\"20\" value=\"".htmlspecialchars($ref)."\">&nbsp;&nbsp;&nbsp;\n";
	if ($displayFormInternalRefField) {
		echo '<label for="refinterbib">';
		echo __("Internal ref. to the library") . "</label> : &nbsp;\n";
		echo "<input name=\"refinterbib\" id=\"refinterbib\" type=\"text\" size=\"20\" value=\"".htmlspecialchars($refinterbib)."\">";
	}
	echo "</td></tr>\n";
	echo "<tr><td valign=\"top\">\n";
	echo '<label for="remarques">';
    echo __("Professional Notes") . "</label> : \n";
    echo "</td><td valign=\"bottom\" colspan=\"3\"><textarea name=\"remarques\" id=\"remarques\" rows=\"2\" cols=\"60\" valign=\"bottom\">".htmlspecialchars($remarques)."</textarea>\n";
    echo "</td></tr>\n";
    echo "</table>\n";
    echo "</div></div>\n";
    echo "<div class=\"box-footer\"><div class=\"box-footer-right\"></div></div>\n";
}
else{
    // display to guest or users not logged in
    if ($monaut == "guest")
        require ("includes/headeradmin.php");
    if ($monaut == "")
        require ("includes/header.php");
    echo "<h1><center>" . __("Document order form to the ") . " <a href=\"" . $configlibraryurl[$lang] . "\" target=\"_blank\">" . $configlibrary[$lang] . "</a></center></h1>\n";
	if (isset($secondmessage) && array_key_exists($lang, $secondmessage)) {
		echo "<h2><center>" . $secondmessage[$lang] . "</center></h2>\n";
	}
    echo "<div class=\"box\"><div class=\"box-content\">\n";
    echo "<b><font color=\"red\">" . __("Please note, all orders are subject to a financial contribution") . "</font></b><br />" . __("Contact us by email for more information (pricing, billing, etc.)") . " : <a href=\"mailto:" . $configlibraryemail[$lang] . "\">" . $configlibraryemail[$lang] . "</a>\n";
    echo "</div></div>\n";
    echo "<div class=\"box-footer\"><div class=\"box-footer-right\"></div></div>\n";
    echo "<form action=\"new.php\" method=\"POST\" enctype=\"multipart/form-data\" id=\"orderform\" name=\"commande\" onsubmit=\"javascript:okcooc()\">\n";
    echo "<input name=\"table\" type=\"hidden\" value=\"orders\">\n";
    echo "<input name=\"userid\" type=\"hidden\" value=\"\">\n";
    echo "<input name=\"bibliotheque\" type=\"hidden\" value=\"\">\n";
    echo "<input name=\"sid\" type=\"hidden\" value=\"\">\n";
    echo "<input name=\"pid\" type=\"hidden\" value=\"\">\n";
    if (!empty($referer))
        echo "<input name=\"referer\" type=\"hidden\" value=\"" . htmlspecialchars(rawurlencode($referer)) . "\">\n";
    else
        echo "<input name=\"referer\" type=\"hidden\" value=\"\">\n";
    echo "<input name=\"action\" type=\"hidden\" value=\"saisie\">\n";
    echo "<input name=\"source\" type=\"hidden\" value=\"publicform\">\n";
}
// END Management Fields

// START User Fields
// Display to all users
echo "<div class=\"box\"><div class=\"box-content\">\n";
echo "<table border=\"0\" cellspacing=\"0\" cellpadding=\"3\" width=\"100%\">\n";
echo "<tr><td>\n";
echo '<label for="nom">';
echo __("Name") . " *</label> : </td><td><input name=\"nom\" id=\"nom\" type=\"text\" size=\"30\" value=\"".htmlspecialchars($nom)."\"></td><td>\n";
echo '<label for="prenom">';
echo __("First name") . " *</label> : </td><td><input name=\"prenom\" id=\"prenom\" type=\"text\" size=\"30\" value=\"".htmlspecialchars($prenom)."\">\n";
if ($directoryurl1 != "")
    echo "&nbsp;<a href=\"javascript:directory('$directoryurl1')\" title=\"" . __("Search the name in the directory of the hospital") . "\"><img src=\"img/directory1.png\"></a>\n";
if ($directoryurl2 != "")
    echo "<a href=\"javascript:directory('$directoryurl2')\" title=\"" . __("Search the name in the directory of the university") . "\"><img src=\"img/directory2.png\"></a>\n";
echo "</td></tr><tr><td>\n";
echo '<label for="service">';
echo __("Unit") . " *</label> : </td><td>\n";
$unitsortlang = "name1";
if ($lang == "en")
    $unitsortlang = "name2";
if ($lang == "de")
    $unitsortlang = "name3";
if ($lang == "it")
    $unitsortlang = "name4";
if ($lang == "es")
    $unitsortlang = "name5";
echo "<select name=\"service\" id=\"service\" style=\"max-width:300px\">\n";
echo "<option value=\"\"></option>\n";
if ($ip1 == 1)
    $requnits="SELECT code, $unitsortlang FROM units WHERE internalip1display = 1 ORDER BY $unitsortlang ASC";
else if ($ip2 == 1)
        $requnits="SELECT code, $unitsortlang FROM units WHERE internalip2display = 1 ORDER BY $unitsortlang ASC";
    else
        $requnits="SELECT code, $unitsortlang FROM units WHERE externalipdisplay = 1 ORDER BY $unitsortlang ASC";
$optionsunits="";
$resultunits = dbquery($requnits);
while ($rowunits = iimysqli_result_fetch_array($resultunits)){
    $codeunits = $rowunits["code"];
    $nameunits = $rowunits[$unitsortlang];
    $optionsunits.="<option value=\"" . htmlspecialchars($codeunits) . "\"";
    $optionsunits.= ($codeunits == $service ? " selected " : "") . ">" . htmlspecialchars($nameunits) . "</option>\n";
}
echo $optionsunits;
echo "</select>\n";
echo "</td><td>\n";
echo '<label for="servautre">';
echo __("Other unit") . "</label> : </td><td>\n";
echo "<input name=\"servautre\" id=\"servautre\" type=\"text\" size=\"30\" value=\"".htmlspecialchars($servautre)."\">\n";
echo "</td></tr>\n";
if ($ip1 == 1){
    echo "<tr><td>\n";
	echo '<label for="cgra">';
    echo __("Budget heading") . "</label> : \n";
    echo "</td><td>\n";
    echo "<input name=\"cgra\" id=\"cgra\" type=\"text\" size=\"30\" value=\"".htmlspecialchars($cgra)."\"></td><td>\n";
	echo '<label for="cgrb">';
    echo __("Budget subheading") . "</label> : </td><td>\n";
    echo "<input name=\"cgrb\" id=\"cgrb\" type=\"text\" size=\"30\" value=\"".htmlspecialchars($cgrb)."\">\n";
    echo "</td></tr>\n";
}
else{
    echo "<input name=\"cgra\" type=\"hidden\"  value=\"\">\n";
    echo "<input name=\"cgrb\" type=\"hidden\"  value=\"\">\n";
}
echo "<tr><td>\n";
echo '<label for="mail">';
echo __("E-Mail") . " *</label> : </td><td>\n";
echo "<input name=\"mail\" id=\"mail\" type=\"text\" size=\"30\" value=\"".htmlspecialchars($mail)."\"></td><td>\n";
echo '<label for="tel">';
echo __("Tel.") . "</label> : </td><td>\n";
echo "<input name=\"tel\" id=\"tel\" type=\"text\" size=\"30\" value=\"".htmlspecialchars($tel)."\">\n";
echo "</td></tr>\n";
echo "<tr><td valign=\"top\">\n";
echo '<label for="adresse">';
echo __("Private address") . "</label> :\n";
echo "</td><td>\n";
echo "<input name=\"adresse\" id=\"adresse\" type=\"text\" size=\"30\" value=\"".htmlspecialchars($adresse)."\">\n";
echo "</td><td>\n";
echo '<label for="postal">';
echo __("Zip code") . "</label> : </td><td>\n";
echo "<input name=\"postal\" id=\"postal\" type=\"text\" size=\"5\" value=\"".htmlspecialchars($postal)."\">\n";
echo "&nbsp;\n";
echo '<label for="localite">';
echo __("City") . "</label> :\n";
echo "<input name=\"localite\" id=\"localite\" type=\"text\" size=\"7\" value=\"".htmlspecialchars($localite)."\">\n";
echo "</td></tr><tr><td valign=\"top\" colspan=\"4\">\n";
echo __("If available at the library") . " : \n";
echo "<input type=\"radio\" name=\"envoi\" id=\"envoimail\" value=\"mail\" ". (($envoi == "" || $envoi == "mail") ? " checked ": "") ."/>\n";
echo '<label for="envoimail">';
echo __("send by e-mail (billed)") . "</label>&nbsp;\n";
echo "<input type=\"radio\" name=\"envoi\" id=\"envoisurplace\" value=\"surplace\" ". ($envoi == "surplace" ? " checked ": "") ."/>\n";
echo '<label for="envoisurplace">';
echo __("let me know and I come to make a copy (not billed)") . "</label>\n";
echo "</td></tr>\n";
echo "<tr>\n";
echo "<td valign=\"top\" colspan=\"4\">\n";
echo "<input type=\"checkbox\" name=\"cooc\" id=\"cooc\" value=\"on\" ". ($cooc == "on" ? " checked ": "") ."/>\n";
echo '<label for="cooc">';
echo __("Remember data for future orders (cookies allowed)") . "</label>&nbsp;&nbsp;|&nbsp;&nbsp;(<A HREF=\"javascript:coocout()\">" . __("delete the cookie") . "</a>)\n";
echo "</td></tr>\n";
echo "</table>\n";
echo "\n";
echo "</div></div>\n";
echo "<div class=\"box-footer\"><div class=\"box-footer-right\"></div></div>\n";
echo "\n";
// END User Fields



// START Document Fields
function get_document_form($lookupuid, $doctypesmessage, $doctypes,  $periodical_title_search_url, $can_remove_orders, $form_index=0, $tid_code="", $uids="", $genre_code="", $title="", $date="", $volume="", $issue="", $suppl="", $pages="", $atitle="", $auteurs="", $edition="", $issn="", $uid="", $remarquespub="") {
	$document_form = "";
	$document_form .=  "<a id=\"order_nb_".$form_index."\"></a>\n";
	$document_form .=  "<div class=\"box\"><div class=\"box-content\">\n";
	$document_form .=  "<div class=\"box\"><div class=\"box-content\">\n";
	$document_form .=  "<center><b><label for=\"tid\">" . __("Fill in the order using") . "</label> </b>\n";
	$document_form .=  "<select name=\"tid_".$form_index."\" id=\"tid_".$form_index."\">\n";
	foreach($lookupuid as $value) {
		$document_form .=  "<option value=\"" . htmlspecialchars($value["code"]) . "\"  ".($tid_code==$value["code"]? 'selected': '').">" . htmlspecialchars($value["name"]) . "</option>\n";
	}
	$document_form .=  "</select>\n";
	$document_form .=  "<input name=\"uids_".$form_index."\" type=\"text\" size=\"20\" value=\"".htmlspecialchars($uids)."\">\n";
	$document_form .=  "<input type=\"button\" value=\"OK\" onclick=\"lookupid(".$form_index.")\"></center>\n";
	$document_form .=  "</div></div>\n";
	$document_form .=  "<div class=\"box-footer\"><div class=\"box-footer-right\"></div></div>\n";
	$document_form .=  "\n";
	$document_form .=  "<table border=\"0\" cellspacing=\"0\" cellpadding=\"3\" width=\"100%\">\n";
	if((!empty($doctypesmessage)) && $doctypesmessage[$lang])
		$document_form .=  "<tr><td><label for=\"genre\">" . $doctypesmessage[$lang] . __("Document type")."</label> : </td><td>\n";
	else
		$document_form .=  "<tr><td><label for=\"genre\">".__("Document type")."</label> : </td><td>\n";
	$document_form .=  "<select name=\"genre_".$form_index."\" id=\"genre_".$form_index."\">\n";
	foreach($doctypes as $value) {
		$document_form .=  "<option value=\"" . htmlspecialchars($value["code"]) . "\" ".($genre_code==$value["code"]? 'selected': '').">" . htmlspecialchars($value["name"]) . "</option>\n";
	}
	$document_form .=  "</select>\n";
	$document_form .=  "<div class=\"formdoc\">\n";
	$document_form .=  "</td></tr><tr><td>\n";
	$document_form .=  '<label for="title">';
	$document_form .=  __("Title of journal or book") . " *</label> : </td><td>\n";
	$document_form .=  "<input name=\"title_".$form_index."\" id=\"title_".$form_index."\" type=\"text\" size=\"80\" value=\"".htmlspecialchars($title)."\">\n";
	$document_form .=  "&nbsp;\n";
	$document_form .=  "<a href=\"javascript:openlist('".$periodical_title_search_url."')\"><img src=\"img/find.png\" title=\"" . __("check on journals database") . "\"></a>\n";
	$document_form .=  "</td></tr><tr><td>\n";
	$document_form .=  '<label for="date">';
	$document_form .=  __("Year") . "</label> : </td><td>\n";
	$document_form .=  "<input name=\"date_".$form_index."\" id=\"date_".$form_index."\" type=\"text\" size=\"3\" value=\"".htmlspecialchars($date)."\">\n";
	$document_form .=  "&nbsp;\n";
	$document_form .=  '<label for="volume">';
	$document_form .=  __("Vol.") . "</label> : \n";
	$document_form .=  "<input name=\"volume_".$form_index."\" id=\"volume_".$form_index."\" type=\"text\" size=\"3\" value=\"".htmlspecialchars($volume)."\">\n";
	$document_form .=  "&nbsp;\n";
	$document_form .=  '<label for="issue">';
	$document_form .=  __("(Issue)") . "</label> : \n";
	$document_form .=  "<input name=\"issue_".$form_index."\" id=\"issue_".$form_index."\" type=\"text\" size=\"3\" value=\"".htmlspecialchars($issue)."\">\n";
	$document_form .=  "&nbsp;\n";
	$document_form .=  '<label for="suppl">';
	$document_form .=  __("Suppl.") . "</label> : \n";
	$document_form .=  "<input name=\"suppl_".$form_index."\" id=\"suppl_".$form_index."\" type=\"text\" size=\"3\" value=\"".htmlspecialchars($suppl)."\">\n";
	$document_form .=  "&nbsp;\n";
	$document_form .=  '<label for="pages">';
	$document_form .=  __("Pages") . "</label> : \n";
	$document_form .=  "<input name=\"pages_".$form_index."\" id=\"pages_".$form_index."\" type=\"text\" size=\"4\" value=\"".htmlspecialchars($pages)."\">\n";
	$document_form .=  "</td></tr><tr><td>\n";
	$document_form .=  '<label for="atitle">';
	$document_form .=  __("Title of article or book chapter") . "</label> : \n";
	$document_form .=  "</td><td>\n";
	$document_form .=  "<input name=\"atitle_".$form_index."\" id=\"atitle_".$form_index."\" type=\"text\" size=\"80\" value=\"".htmlspecialchars($atitle)."\">\n";
	$document_form .=  "</td></tr><tr><td>\n";
	$document_form .=  '<label for="auteurs">';
	$document_form .=  __("Author(s)") . "</label> : \n";
	$document_form .=  "</td><td>\n";
	$document_form .=  "<input name=\"auteurs_".$form_index."\" id=\"auteurs_".$form_index."\" type=\"text\" size=\"80\" value=\"".htmlspecialchars($auteurs)."\">\n";
	$document_form .=  "</td></tr>\n";
	$document_form .=  "<tr><td>\n";
	$document_form .=  '<label for="edition">';
	$document_form .=  __("Edition (for books)") . "</label> : \n";
	$document_form .=  "</td><td>\n";
	$document_form .=  "<input name=\"edition_".$form_index."\" id=\"edition_".$form_index."\" type=\"text\" size=\"14\" value=\"".htmlspecialchars($edition)."\">\n";
	$document_form .=  "&nbsp;\n";
	$document_form .=  "<label for=\"issn\">ISSN / ISBN</label> : \n";
	$document_form .=  "<input name=\"issn_".$form_index."\" id=\"issn_".$form_index."\" type=\"text\" size=\"15\" value=\"".htmlspecialchars($issn)."\">\n";
	$document_form .=  "&nbsp;\n";
	$document_form .=  "<label for=\"uid\">UID</label> : \n";
	$document_form .=  "<input name=\"uid_".$form_index."\" id=\"uid_".$form_index."\" type=\"text\" size=\"15\" value=\"".htmlspecialchars($uid)."\">\n";
	$document_form .=  "</td></tr></div>\n";
	$document_form .=  "<tr><td valign=\"top\">\n";
	$document_form .=  '<label for="remarquespub">';
	$document_form .=  __("Notes") . "</label> : \n";
	$document_form .=  "</td><td valign=\"bottom\"><textarea name=\"remarquespub_".$form_index."\" id=\"remarquespub_".$form_index."\" rows=\"2\" cols=\"60\" valign=\"bottom\">".htmlspecialchars($remarquespub)."</textarea>\n";
	$document_form .=  "</td></tr><tr><td></td><td>\n";
	$document_form .=  "</td></tr>\n";
	$document_form .=  "</table>\n";
	if ($can_remove_orders) {
		$document_form .= '<div class=""><a class="removeLink" style="cursor:pointer;" onclick="form=document.getElementById(\'orderform\');form.remove_form.value='.$form_index.';form.action=\'index.php#order_nb_'.($form_index-1).'\';form.submit();">'.__("Remove").'</a></div>';
	}
	$document_form .=  "</div></div>\n";
	$document_form .=  "<div class=\"box-footer\"><div class=\"box-footer-right\"></div></div>\n";
	return $document_form;
}
$can_remove_orders = false;
if (count($order_form_values) > 1) {
	$can_remove_orders = true;
	echo '<input type="hidden" name="remove_form" value=""/>';
}
foreach ($uploaded_orders_messages as $uploaded_orders_message) {
	echo $uploaded_orders_message;
}
if (is_privileged_enough($monaut, $enableOrdersUploadForUser) && count($order_form_values) < max($maxSimultaneousOrders, 1)) {
	echo '<div class="fileUploadPanel">';
	//echo format_string(__('Fill in your order or %x_url_startupload a file with references%x_url_end:'), array('x_url_start' => '<a href="#" onclick="var fileUploadPanelContent = document.getElementById(\'fileUploadPanelContent\'); if(fileUploadPanelContent.style.display==\'none\'){fileUploadPanelContent.style.display=\'block\';}else{fileUploadPanelContent.style.display=\'none\';}">', 'x_url_end' => '</a>')) . " </p>";
	echo format_string(__('Fill in your order or %x_url_startupload a file with references%x_url_end:'), array('x_url_start' => '<a style="cursor: pointer;" onclick="document.getElementById(\'order_file\').click();">', 'x_url_end' => '</a>'));
	//echo '<div id="fileUploadPanelContent" style="display:none;" class="fileUploadPanelContent">';
	echo '&nbsp;<a href="#" class="helpinfo" onclick="return false;">?<span class="supportedFileFormats">';
	echo htmlspecialchars(__('Supported file formats:'));
	echo '<ul><li>' . htmlspecialchars(__('EndNote XML (created via "File" → "Export" → Type "XML")'));
	echo '</li><li>'. htmlspecialchars(__('RIS'));
	echo '</li><li>'. htmlspecialchars(__('MEDLINE/PubMed (.nbib files created via "Send to" → "Citation Manager")'));
	echo '</ul>';
	echo '(' . sprintf(__('Maximum file size: %s MB'), round(min(parse_size_str(ini_get('upload_max_filesize')), parse_size_str(ini_get('post_max_size'))) / (1024*1024), 0, PHP_ROUND_HALF_DOWN)) . ")";
	echo '</span></a>';
	//echo '<input style="display:none;" type="file" id="order_file" name="order_file" onchange="if(this.value){document.getElementById(\'fileUploadPanelSubmitButton\').style.display=\'inline\';}else{document.getElementById(\'fileUploadPanelSubmitButton\').style.display=\'none\';};"/>';
	echo '<input style="display:none;" type="file" id="order_file" name="order_file" onchange="form=document.getElementById(\'orderform\');form.action=\'index.php\';form.submit();" />';

	//echo '<input id="fileUploadPanelSubmitButton" style="display:none" type="button" value="'. __("Upload file") . '" onclick="form=document.getElementById(\'orderform\');form.action=\'index.php\';form.submit();"/>';
	//echo '</div>';
	echo '</div>';
}
foreach ($order_form_values as $form_index => $value) {
	echo get_document_form($lookupuid, !empty($doctypesmessage) ? $doctypesmessage : "", $doctypes, $periodical_title_search_url, $can_remove_orders, $form_index, $value['tid_code'], $value['uids'], $value['genre_code'], $value['title'], $value['date'], $value['volume'], $value['issue'], $value['suppl'], $value['pages'], $value['atitle'], $value['auteurs'], $value['edition'], $value['issn'], $value['uid'], $value['remarquespub']);
}
if (count($order_form_values) < max($maxSimultaneousOrders, 1))  {
	echo '<div class="addItemPanel"><input type="hidden" name="add_form" value="0"/><a style="cursor:pointer" onclick="form=document.getElementById(\'orderform\');form.add_form.value=1;form.action=\'index.php#order_nb_'.count($order_form_values).'\';form.submit();">'.__("Add an item to the order").'</a></div>';
}
echo '<div class="box-submit-buttons">';
echo "<input type=\"submit\" value=\"" . __("Send order") . "\" onsubmit=\"javascript:okcooc();document.body.style.cursor = 'wait';\">&nbsp;&nbsp;\n";
echo "<input type=\"reset\" value=\"" . __("Reset") . "\">\n";
echo "</div>";
echo "</form>\n";
require ("includes/footer.php");
?>
