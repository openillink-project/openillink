<?php
// ***************************************************************************
// ***************************************************************************
// ***************************************************************************
// This file is part of OpenILLink software.
// Copyright (C) 2005, 2006, 2007, 2008, 2009, 2010, 2011, 2012, 2013, 2015, 2016, 2017, 2018, 2019, 2023, 2024, 2025 CHUV.
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
							'remarquespub' => "",
							'need_lookup' => false,
							'resolver_search_params' => "");

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

$nom = !empty($_POST['nom'])? substr($_POST['nom'], 0, 100) : '';
$prenom = !empty($_POST['prenom']) ? substr($_POST['prenom'], 0, 100) : '';
$service = !empty($_POST['service']) ? substr($_POST['service'], 0, 20) : '';
$servautre = !empty($_POST['servautre']) ? substr($_POST['servautre'], 0, 255) : '';
$cgra = !empty($_POST['cgra']) ? substr($_POST['cgra'], 0, 10) : '';
$cgrb = !empty($_POST['cgrb']) ? substr($_POST['cgrb'], 0, 10) : '';
$mail = !empty($_POST['mail']) ? substr($_POST['mail'], 0, 100) : '';
$tel = !empty($_POST['tel']) ? substr($_POST['tel'], 0, 20) : '';
$adresse = !empty($_POST['adresse']) ? substr($_POST['adresse'], 0, 255) : '';
$postal = !empty($_POST['postal']) ? substr($_POST['postal'], 0, 10) : '';
$localite = !empty($_POST['localite']) ? substr($_POST['localite'], 0, 50) : '';
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
$prix = !empty($_POST['prix']) ? substr($_POST['prix'], 0, 4) : '';
$avance = !empty($_POST['avance']) ? $_POST['avance'] : '';
$ref = !empty($_POST['ref']) ? substr($_POST['ref'], 0, 50) : '';
$refinterbib = !empty($_POST['refinterbib']) ? substr($_POST['refinterbib'], 0, 50) : '';
$remarques = !empty($_POST['remarques']) ? $_POST['remarques'] : '';
$adresscompl = !empty($_POST['adresscompl']) ? $_POST['adresscompl'] : '';

$order_form_values = array();

$config_display_delivery_choice = isset($config_display_delivery_choice) ? $config_display_delivery_choice : true;
$config_display_cgr_fields = isset($config_display_cgr_fields) ? $config_display_cgr_fields : false;

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
		$order_form['resolver_search_params'] = get_from_post($form_index, 'resolver_search_params');
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
	if (count($lib->refs) == 0) {
		array_push($uploaded_orders_messages, get_message_box(__("No references found in uploaded file. Please make sure it is in the list of supported file formats."), 'danger', __("Error")));
	}
	foreach ($lib->refs as $ref_index => $reference) {
		$order_form = $default_order_form;
		if (!empty($reference['type'])) {
			switch ($reference['type']) {
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
		$order_form['title'] = !empty($reference['periodical-title']) ? $reference['periodical-title'] : (!empty($reference['title-secondary']) ? $reference['title-secondary'] : (!empty($reference['title']) ? $reference['title'] : ''));
		$order_form['date'] = !empty($reference['year']) ? $reference['year'] : (!empty($reference['date']) ? date('Y', $reference['date']) : '');
		$order_form['volume'] = !empty($reference['volume']) ? $reference['volume'] : '';
		$order_form['issue'] = !empty($reference['number']) ? $reference['number'] : '';
		$order_form['suppl'] = !empty($reference['']) ? $reference[''] : '';
		$order_form['pages'] = !empty($reference['pages']) ? $reference['pages'] : '';
		// Article title is moved to Periodical title if periodical title is not known
		$order_form['atitle'] = (!empty($reference['title']) && $order_form['title'] != $reference['title']) ? $reference['title'] : '';
		$order_form['auteurs'] = !empty($reference['authors']) ? implode(", ", $reference['authors']) : '';
		$order_form['edition'] = !empty($reference['']) ? $reference[''] : '';
		$order_form['issn'] = !empty($reference['isbn']) ? $reference['isbn'] : '';
		if (!empty($reference['accession-num'])) {
			if (substr(strtolower($reference['accession-num']), 0, 4) == "wos:") {
				$order_form['tid_code'] = 'wosid';
				$order_form['uids'] = substr($reference['accession-num'], 4);
				$order_form['uid'] = 'WOSUT:' . substr($reference['accession-num'], 4);
			} else {
				$order_form['tid_code'] = 'pmid';
				$order_form['uids'] = $reference['accession-num'];
				$order_form['uid'] = 'pmid:' . $reference['accession-num'];
				if ($lib->driver->driverName == "PMIDS") {
					// the file was just a list of pmids: we need to look them up
					$order_form['need_lookup'] = true;
				}
			}
		} else if (!empty($reference['doi'])) {
			$order_form['tid_code'] = 'doi';
			$order_form['uids'] = $reference['doi'];
			$order_form['uid'] = 'doi:' . $reference['doi'];
		}
		$order_form['remarquespub'] = !empty($reference['notes']) ? $reference['notes'] : '';
        $order_form['remarquespub'] .= !empty($reference['urls']) ? "\n" . implode("\n", $reference['urls']) : '';
		array_push($order_form_values, $order_form);
		if (count($order_form_values) >= max($maxSimultaneousOrders, 1)) {
			array_push($uploaded_orders_messages, get_message_box(sprintf(__("The maximum number of simultaneous orders (%d) has been reached. Remaining references have been ignored."), $maxSimultaneousOrders), 'warning', __("Warning")));
			break;
		}
	}
} else if (isset($_GET['PMID_1']) && $maxSimultaneousOrders >= 100) {
	$pmid_form_index = 1;
	while ($pmid_form_index <= 100 && isset($_GET['PMID_'.$pmid_form_index])){
			$order_form = $default_order_form;
			$order_form['tid_code'] = "pmid";
			$order_form['uids'] = get_from_post($pmid_form_index, 'PMID');
			$order_form['need_lookup'] = true;
			array_push($order_form_values, $order_form);
			$pmid_form_index += 1;
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

$myhtmltitle = $configname[$lang] . " : " . __("New Order");
$mybodyonload = "";
if (!isset($_POST['add_form'])){
	$mybodyonload .= "document.commande.nom.focus();";
}
$mybodyonload .= " remplirauto('".htmlspecialchars($configemail)."');";
if (($monaut == "admin")||($monaut == "sadmin")||($monaut == "user")){
    require ("includes/headeradmin.php");
	echo '<script type="text/javascript">var referer="'. (isset($referer)  ? htmlspecialchars(ltrim(rtrim(json_encode($referer), '"'), '"')) : '').'";</script>';
    echo '<section class="hero">
			<div class="hero-body">
				<div class="has-text-centered">';
	echo '<h1 class="title">' . __("Document order form to the ") . " <a href=\"" . $configlibraryurl[$lang] . "\" target=\"_blank\">" . $configlibrary[$lang] . "</a></h1>\n";
	if (isset($secondmessage) && array_key_exists($lang, $secondmessage)) {
		echo '<h2 class="subtitle">' . $secondmessage[$lang] . "</h2>\n";
	}
    echo '</div>
			</div>
		</section>';
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
    echo '<section class="message">
	<div class="message-body">';

	echo "<input name=\"table\" type=\"hidden\"  value=\"orders\">\n";
    echo "<input name=\"userid\" type=\"hidden\"  value=\"".htmlspecialchars($monnom)."\">\n";
    echo "<input name=\"bibliotheque\" type=\"hidden\"  value=\"".htmlspecialchars($monbib)."\">\n";
    echo "<input name=\"sid\" type=\"hidden\"  value=\"\">\n";
    echo "<input name=\"pid\" type=\"hidden\"  value=\"\">\n";
    if (!empty($referer))
        echo "<input name=\"referer\" type=\"hidden\" value=\"" . htmlspecialchars($referer) . "\">\n";
    else
        echo "<input name=\"referer\" type=\"hidden\" value=\"\">\n";
    echo "<input name=\"action\" type=\"hidden\" value=\"saisie\">\n";
    echo "<input name=\"source\" type=\"hidden\" value=\"adminform\">\n";
	echo '<div class="columns is-gapless is-columns-form">
  <div class="column is-one-fifth">';
	echo '<div class="field is-horizontal">';
	echo '<label class="label field-label is-normal required" for="stade">';
    echo __("Status") . "</label>";
	echo '</div></div>
	 <div class="column is-3">';
	echo '<div class="control is-expanded has-icons-left">';
	echo '<div class="select is-fullwidth">';
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
	echo '</div>
	<div class="icon is-small is-left">
      <i class="fas fa-code-branch"></i>
    </div>
	</div></div>
	 <div class="column is-3">';
	echo '<div class="field is-horizontal">';
	echo '<label class="label field-label is-normal" for="localisation">';
    echo __("Localization") . "</label>";
	echo '</div></div>
	 <div class="column is-3">';
	echo '<div class="control is-expanded has-icons-left">';
	echo '<div class="select is-fullwidth">';
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
	echo '</div>
	<div class="icon is-small is-left">
      <i class="fas fa-map-marker-alt"></i>
    </div>
	</div></div>
	</div>';
	echo '<div class="columns is-gapless is-columns-form">
  <div class="column is-one-fifth">';
	echo '<div class="field is-horizontal">';
	echo '<label class="label field-label is-normal" for="urgent">';
    echo __("Priority") . "</label>";
	echo '</div></div>
	 <div class="column is-3">';
	echo '<div class="control is-expanded has-icons-left">';
	echo '<div class="select is-fullwidth">';
	echo "<select name=\"urgent\" id=\"urgent\">\n";
    echo "<option value=\"2\"".('2' == $urgent ? " selected " : ""). ">" . __("Normal") . "</option>\n";
    echo "<option value=\"1\"".('1' == $urgent ? " selected " : ""). ">" . __("Urgent") . "</option>\n";
    echo "<option value=\"3\"".('3' == $urgent ? " selected " : ""). ">" . __("Not a priority") . "</option>\n";
    echo "</select>\n";
	echo '</div>
		<div class="icon is-small is-left">
      <i class="fas fa-flag"></i>
    </div>
	</div></div>';
	if ($displayFormOrderSourceField) {
		echo '<div class="column is-3">';
		echo '<div class="field is-horizontal">';
		echo "<label class=\"label field-label is-normal\" for=\"source\">" . __("Origin of the order") . "</label>";
		echo '</div></div>
		<div class="column is-3">';
		echo '<div class="control is-expanded has-icons-left">';
		echo '<div class="select is-fullwidth">';
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
		echo '</div>
	<div class="icon is-small is-left">
      <i class="fas fa-globe"></i>
    </div>
		</div></div>
	 <div class="column is-2">';
		echo '<div class="control">';
		echo "<input class=\"input\" name=\"sourcenew\" id=\"sourcenew\" type=\"text\" size=\"20\" value=\"".htmlspecialchars($sourcenew)."\" style=\"". ($source == "new" ? "display:inline" : "display:none")."\">\n";
		echo '</div></div>
		</div>';
	}
	echo '<div class="columns is-gapless is-columns-form">
  <div class="column is-one-fifth">';
    echo '<div class="field is-horizontal">';
	echo '<label class="label field-label is-normal" for="datesaisie">';
    echo "<a title=\"" . __("to be completed only if different from the current date") . "\">" . __("Order date") . "</a></label>";
    echo '</div></div>
	 <div class="column is-3">';
	echo '<div class="control has-icons-left">';
	echo "<input class=\"input tcal\" autocomplete=\"off\" name=\"datesaisie\" id=\"datesaisie\" type=\"text\" size=\"10\" value=\"".htmlspecialchars($datesaisie)."\">\n";
	echo '<div class="icon is-small is-left">
      <i class="fas fa-download"></i>
    </div>';
	echo '</div></div>
	 <div class="column is-3">';
	echo '<div class="field is-horizontal">';
	echo '<label class="label field-label is-normal" for="envoye">';
    echo __("Date of shipment") . "</label>";
	echo '</div></div>
	 <div class="column is-3">';
	echo '<div class="control has-icons-left">';
    echo "<input class=\"input tcal\" autocomplete=\"off\" name=\"envoye\" id=\"envoye\" type=\"text\" size=\"10\" value=\"".htmlspecialchars($envoye)."\">\n";
	echo '<div class="icon is-small is-left">
      <i class="fas fa-upload"></i>
    </div>';
	echo '</div></div>
	</div>
	<div class="columns is-gapless is-columns-form">
	 <div class="column is-one-fifth">';
	echo '<div class="field is-horizontal">';
	echo '<label class="label field-label is-normal" for="facture">';
    echo __("Invoice date") . "</label>";
	echo '</div></div>
	 <div class="column is-3">';
	echo '<div class="control has-icons-left">';
    echo "<input class=\"input tcal\" autocomplete=\"off\" name=\"facture\" id=\"facture\" type=\"text\" size=\"10\" value=\"".htmlspecialchars($facture)."\">\n";
	echo '<div class="icon is-small is-left">
      <i class="fas fa-piggy-bank"></i>
    </div>';
	echo '</div></div>
	 <div class="column is-3">';
	echo '<div class="field is-horizontal">';
	echo '<label class="label field-label is-normal" for="renouveler">';
    echo __("To be renewed on") . "</label>";
	echo '</div></div>
	 <div class="column is-3">';
	 echo '<div class="control has-icons-left">';
    echo "<input class=\"input tcal\" autocomplete=\"off\" name=\"renouveler\" id=\"renouveler\" type=\"text\" size=\"10\" value=\"".htmlspecialchars($renouveler)."\">\n";
	echo '<div class="icon is-small is-left">
      <i class="fas fa-bell"></i>
    </div>';
	echo '</div></div>
	</div>
	 <div class="columns is-gapless is-columns-form">
  <div class="column is-one-fifth">';
	echo '<div class="field is-horizontal">';
	echo '<label class="label field-label is-normal" for="prix">';
    echo format_string(__("Price (%currency)"), array('currency' => $currency)) . "</label>";
	echo '</div></div>
	 <div class="column is-3">';
	echo '<div class="control has-icons-left">';
    echo "<input class=\"input\" name=\"prix\" id=\"prix\" type=\"text\" size=\"5\" value=\"".htmlspecialchars($prix)."\" maxlength=\"4\">\n";
	echo '<div class="icon is-small is-left">
      <i class="far fa-money-bill-alt"></i>
    </div></div>';
	echo '</div><div class="column is-two-fifth">';
	echo '<div class="field">';
	echo '<div class="control">';
    echo "&nbsp;&nbsp;(<label class=\"checkbox\"> <input type=\"checkbox\" name=\"avance\" id=\"avance\" value=\"on\" ".($avance == "on" ? " checked ": "").">\n". __("order paid in advance") . "\n</label>) &nbsp;&nbsp;&nbsp;&nbsp;\n";
	echo '</div></div></div>
	</div>
	 <div class="columns is-gapless is-columns-form">
  <div class="column is-one-fifth">';
	echo '<div class="field is-horizontal">';
	echo '<label class="label field-label is-normal" for="ref">';
    echo __("Provider Ref.") . "</label>";
	echo '</div></div>
	 <div class="column is-3">';
	echo '<div class="control has-icons-left">';
    echo "<input class=\"input\" name=\"ref\" id=\"ref\" type=\"text\" size=\"20\" value=\"".htmlspecialchars($ref)."\" maxlength=\"50\">\n";
	echo '<div class="icon is-small is-left">
      <i class="fas fa-barcode"></i>
    </div>';
	echo '</div></div>';
	if ($displayFormInternalRefField) {
		echo '<div class="column is-3">';
		echo '<div class="field is-horizontal">';
		echo '<label class="label field-label is-normal" for="refinterbib">';
		echo __("Internal ref. to the library") . "</label>";
		echo '</div></div>
	 <div class="column is-3">';
		echo '<div class="control has-icons-left">';
		echo "<input class=\"input\" name=\"refinterbib\" id=\"refinterbib\" type=\"text\" size=\"20\" value=\"".htmlspecialchars($refinterbib)."\" maxlength=\"50\">";
		echo '<div class="icon is-small is-left">
      <i class="fas fa-tag"></i>
    </div></div></div>';
	}
	echo '
	</div>
	 <div class="columns is-gapless is-columns-form">
  <div class="column is-one-fifth">';
	echo '<div class="field is-horizontal">';
	echo '<label class="label field-label is-normal" for="remarques">';
    echo __("Professional Notes") . "</label>";
	echo '</div></div>
	 <div class="column is-9">';
	echo '<div class="control">';
    echo "<textarea class=\"textarea\" name=\"remarques\" id=\"remarques\" rows=\"2\" cols=\"60\" valign=\"bottom\">".htmlspecialchars($remarques)."</textarea>\n";
	echo '</div></div>';
	echo '</div>'; // end columns
    echo '</div><!-- end message-body -->
	</section><!-- end message -->';
}
else{
    // display to guest or users not logged in
    if ($monaut == "guest")
        require ("includes/headeradmin.php");
    if ($monaut == "")
        require ("includes/header.php");

	echo '<script type="text/javascript">var referer="'. (isset($referer)  ? htmlspecialchars(ltrim(rtrim(json_encode($referer), '"'), '"')) : '').'";</script>';

    echo '<section class="hero">
			<div class="hero-body">
				<div class="has-text-centered">';
	echo '<h1 class="title">' . __("Document order form to the ") . " <a href=\"" . $configlibraryurl[$lang] . "\" target=\"_blank\">" . $configlibrary[$lang] . "</a></h1>\n";
	if (isset($secondmessage) && array_key_exists($lang, $secondmessage)) {
		echo '<h2 class="subtitle">' . $secondmessage[$lang] . "</h2>\n";
	}
	if (isset($thirdmessage) && array_key_exists($lang, $thirdmessage)) {
		echo $thirdmessage[$lang];
	}
    echo '</div>
			</div>
		</section>';


    echo "<form action=\"new.php\" method=\"POST\" enctype=\"multipart/form-data\" id=\"orderform\" name=\"commande\" onsubmit=\"javascript:okcooc()\">\n";
    echo "<input name=\"table\" type=\"hidden\" value=\"orders\">\n";
    echo "<input name=\"userid\" type=\"hidden\" value=\"".htmlspecialchars($monnom)."\">\n";
    echo "<input name=\"bibliotheque\" type=\"hidden\" value=\"".htmlspecialchars($monbib)."\">\n";
    echo "<input name=\"sid\" type=\"hidden\" value=\"\">\n";
    echo "<input name=\"pid\" type=\"hidden\" value=\"\">\n";
    if (!empty($referer))
        echo "<input name=\"referer\" type=\"hidden\" value=\"" . htmlspecialchars($referer) . "\">\n";
    else
        echo "<input name=\"referer\" type=\"hidden\" value=\"\">\n";
    echo "<input name=\"action\" type=\"hidden\" value=\"saisie\">\n";
    echo "<input name=\"source\" type=\"hidden\" value=\"publicform\">\n";
}
// END Management Fields

// START User Fields
// Display to all users
echo '
<section class="message">
	<div class="message-header">'.__("Contact and billing details").'</div>
	<div class="message-body">
	<div class="columns is-gapless is-columns-form">
    <div class="column is-2">
	<div class="field is-horizontal">
      <label class="label field-label is-normal required" for="nom">'.__("Name").'</label>
	</div>
	</div>
	<div class="column is-one-quarter">
        <div class="control has-icons-left">
         <input id="nom" name="nom" class="input" type="text" value="'.htmlspecialchars($nom).'" required maxlength="100">
         <span class="icon is-small is-left">
			<i class="fas fa-user"></i>
         </span>
        </div>
    </div>
	<div class="column is-2">
		<div class="field is-horizontal">
		<label class="label field-label is-normal required" for="prenom">
		'.__("First name").'
		</label>
		</div>
	</div>
	<div class="column is-one-quarter">
        <div class="control">
         <input id="prenom" name="prenom" class="input" type="text" value="'.htmlspecialchars($prenom).'" required maxlength="100">
        </div>
	</div>
	<div class="column is-2">
	<div class="field is-horizontal">
   <span class="buttons label field-label is-normal has-text-left" style=""> &nbsp;';
if ($directoryurl1 != "") {
    $directoryurl_post_data1_param = "{}";
    if (isset($directoryurl_post_data1) && !empty($directoryurl_post_data1)) {
         $directoryurl_post_data1_param = htmlspecialchars(json_encode($directoryurl_post_data1));
    }
	echo " <a href=\"javascript:directory('$directoryurl1', $directoryurl_post_data1_param)\" class=\"is-light\" title=\"" . __("Search the name in the directory of the hospital") . "\"><span class=\"directoryurl1\"><i aria-hidden=\"true\" class=\"fa fa-address-book fa-lg\"></i></span></a>\n";
}
if ($directoryurl2 != "") {
    $directoryurl_post_data2_param = "{}";
    if (isset($directoryurl_post_data2) && !empty($directoryurl_post_data2)) {
         $directoryurl_post_data2_param = htmlspecialchars(json_encode($directoryurl_post_data2));
    }
	echo "&nbsp;<a href=\"javascript:directory('$directoryurl2', $directoryurl_post_data2_param)\" class=\"is-light\" title=\"" . __("Search the name in the directory of the university") . "\"><span class=\"directoryurl2\"><i aria-hidden=\"true\" class=\"fa fa-address-book fa-lg\"></i></span></a>\n";
}
echo '</span>
	</div>
      </div>
	  </div>
	<div class="columns is-gapless is-columns-form">
    <div class="column is-2">
	  <div class="field is-horizontal">
      <label class="label field-label is-normal required" for="service">'.__("Unit").'</label>
	  </div>
	</div>
	<div class="column is-one-quarter">
        <div class="control has-icons-left is-expanded">
         <div class="select is-fullwidth">
          <select id="service" name="service">
						<option value="">&nbsp;</option>';
$unitsortlang = "name1";
if ($lang == "en")
    $unitsortlang = "name2";
if ($lang == "de")
    $unitsortlang = "name3";
if ($lang == "it")
    $unitsortlang = "name4";
if ($lang == "es")
    $unitsortlang = "name5";
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
echo '
          </select>
       </div>
	   <span class="icon is-small is-left">
		<i class="fas fa-sitemap"></i>
        </span>
	   </div>
	</div>
	<div class="column is-2">
		<div class="field is-horizontal">
		<label class="label field-label is-normal" for="servautre">
		'.__("Other unit").'
		</label>
		</div>
	</div>
	<div class="column is-one-quarter">
        <div class="control">
         <input id="servautre" name="servautre" class="input" type="text" value="'.htmlspecialchars($servautre).'" maxlength="255">
       </div>
    </div>
	</div>

	<div class="columns is-gapless is-columns-form">
    <div class="column is-2">
	  <div class="field is-horizontal">
      <label class="label field-label is-normal" for="mail">'.__("E-Mail").'</label>
	  </div>
	</div>
	<div class="column is-one-quarter">
        <div class="control has-icons-left">
         <input id="mail" name="mail" class="input" type="email" value="'.htmlspecialchars($mail).'" maxlength="100">
         <span class="icon is-small is-left">
			<i class="fas fa-envelope"></i>
         </span>
        </div>
    </div>
	<div class="column is-2">
		<div class="field is-horizontal">
		<label class="label field-label is-normal" for="tel">
		'.__("Tel.").'
		</label>
		</div>
	</div>
	<div class="column is-one-quarter">
        <div class="control has-icons-left">
         <input id="tel" name="tel" class="input" type="tel"  value="'.htmlspecialchars($tel).'" maxlength="20">
         <span class="icon is-small is-left">
			<i class="fas fa-phone"></i>
         </span>
        </div>
       </div>
      </div>
';
if ($ip1 == 1 && $config_display_cgr_fields){
	echo '
	<div class="columns is-gapless is-columns-form">
    <div class="column is-2">
	  <div class="field is-horizontal">
      <label class="label field-label is-normal" for="cgra">'.str_replace(' ', '&nbsp;', __("Budget heading")).'</label>
	  </div>
	</div>
	<div class="column is-one-quarter">
        <div class="control">
         <input id="cgra" name="cgra" class="input" type="text" value="'.htmlspecialchars($cgra).'" placeholder="'.__("CGR A").'" maxlength="10">
        </div>
    </div>
	<div class="column is-2">
		<div class="field is-horizontal">
	    <label class="label field-label is-normal" for="cgrb">'.str_replace(' ', '&nbsp;', __("Budget subheading")).'</label>
		</div>
	</div>
	<div class="column is-one-quarter">
        <div class="control">
         <input id="cgrb" name="cgrb" class="input" type="text"  value="'.htmlspecialchars($cgrb).'" placeholder="'.__("CGR B").'" maxlength="10">
        </div>
    </div>
    </div>';
} else {
    echo "<input name=\"cgra\" type=\"hidden\"  value=\"\">\n";
    echo "<input name=\"cgrb\" type=\"hidden\"  value=\"\">\n";
}
echo '
	<div class="columns is-gapless is-columns-form">
    <div class="column is-2">
	  <div class="field is-horizontal">
      <label class="label field-label is-normal" for="adresse">'.__("Private address").'</label>
	  </div>
	</div>
	<div class="column is-one-quarter">
        <div class="control has-icons-left">
         <input id="adresse" name="adresse" class="input" type="text" value="'.htmlspecialchars($adresse).'" maxlength="255">
		 <span class="icon is-small is-left">
			<i class="fas fa-home"></i>
         </span>
        </div>
    </div>
    <div class="column is-2">
	  <div class="field is-horizontal">
      <label class="label field-label is-normal" for="postal">'.__("Postal code").'</label>
	  </div>
	</div>
	<div class="column is-1">
        <div class="control">
         <input id="postal" name="postal" class="input" type="text" value="'.htmlspecialchars($postal).'" maxlength="10">
        </div>
    </div>
    <div class="column is-narrow">
	  <div class="field is-horizontal" style="display:block">
      <label class="label field-label is-normal" for="localite">&nbsp;&nbsp;&nbsp;&nbsp;'.__("City").'</label>
	  </div>
	</div>
	<div class="column is-2">
        <div class="control">
         <input id="localite" name="localite" class="input" type="text" value="'.htmlspecialchars($localite).'" maxlength="50">
        </div>
    </div>
    </div>
';
if ($config_display_delivery_choice) {
    echo '
        <div class="columns is-gapless is-columns-form">
        <div class="column is-3">
          <div class="field is-horizontal">
          <label class="label field-label is-normal" for="envoi">'.__("If available at the library:").'</label>
          </div>
        </div>
        <div class="column is-9">
        <div class="field is-horizontal">
            <div class="control">
             <label class="radio field-label is-normal">
              <input type="radio" id="envoimail" name="envoi" '. (($envoi == "" || $envoi == "mail") ? " checked ": "") .' value="mail"> '.__("Send by e-mail (billed)").'</label>
             <label class="radio field-label is-normal">
              <input type="radio" id="envoisurplace" name="envoi" '. ($envoi == "surplace" ? " checked ": "") .' value="surplace"> '.__("Let me know and I come to make a copy (not billed)").'</label>
            </div>
           </div>
          </div>
          </div>
    ';
}
$timestamp = time();
$timestamp_signature = "";
if ($config_secure_secret_key != '' && !empty($config_secure_secret_key)) {
    $timestamp_signature = hash_hmac("sha256", strval($timestamp), $config_secure_secret_key);
}
echo '
	<div class="columns is-gapless is-columns-form">
    <div class="column is-2">
		&nbsp;
	</div>
	<div class="column is-10">
	  <div class="field is-horizontal">
      <div class="field-body">
       <div class="field">
        <div class="control">
         <label class="checkbox">
          <input id="cooc" name="cooc" type="checkbox" '. ($cooc == "on" ? " checked ": "") .'value="on">
          '.__("Remember data for future orders (cookies allowed)").' | (<a href="javascript:coocout()">'. __("delete the cookie") .'</a>)
        </label>
        </div>
       </div>
       <div class="field is-hidden">
        <div class="control">
         <input id="adresscompl" name="adresscompl" class="input" type="text" value="'.htmlspecialchars($adresscompl).'" autocomplete="off">
         <input id="timestamp" name="timestamp" type="hidden" value="'.htmlspecialchars($timestamp).'" autocomplete="off" readonly>
         <input id="timesignat" name="timesignat" type="hidden" value="'.htmlspecialchars($timestamp_signature).'" autocomplete="off" readonly>
        </div>
        <label class="label" for="adresscompl">'. _("This field should remain unchanged") .'</label>
       </div>
      </div>
	  </div>
	</div>
	</div>
  </div>
</section>';

function get_document_form($lookupuid, $doctypesmessage, $doctypes,  $periodical_title_search_url, $can_remove_orders, $form_index=0, $tid_code="", $uids="", $genre_code="", $title="", $date="", $volume="", $issue="", $suppl="", $pages="", $atitle="", $auteurs="", $edition="", $issn="", $uid="", $remarquespub="", $resolver_search_params="") {
	global $configemail, $config_link_resolver_base_openurl;
	$document_form = "";
	$document_form .=  "<a id=\"order_nb_".$form_index."\"></a>\n";
	$document_form .= '
	<section class="message">
		<div class="message-header">
		<div class="container">
	<div class="columns is-vcentered">
	  <div class="column has-text-right is-two-fifths">
       <label class="has-text-white" for="tid_'.$form_index.'">'.  __("Fill in the order using") .'</label>
	   </div><!-- end first column-->
	   <div class="column">
	   <div class="field-body">
        <div class="field has-addons">
         <div class="control">
          <span class="select is-fullwidth">
			<select id="tid_'.$form_index.'" name="tid_'.$form_index.'">';
				foreach($lookupuid as $value) {
	$document_form .=  "<option value=\"" . htmlspecialchars($value["code"]) . "\" ".($tid_code==$value["code"]? 'selected': '').">" . htmlspecialchars($value["name"]) . "</option>\n";
}
$document_form .= '
			</select>
		</span>
         </div>
         <div class="control"><input class="input" name="uids_'.$form_index.'" placeholder="'. __("Identifier") .'" type="text" value="'.htmlspecialchars($uids).'"></div>
         <div class="control"><input class="button is-primary" onclick="lookupid('.$form_index.', \''.htmlspecialchars($configemail).'\')" type="button" value="'. __("Fill in") .'"></div>
        </div>
       </div>

	   </div > <!-- end second .column -->
	   </div> <!-- end .columns -->
	   </div> <!-- end .container -->
	   </div> <!-- end message-header -->
    <div class="message-body">';

	$document_form .= '
	<div class="columns is-gapless is-columns-form">
    <div class="column is-one-fifth">
	<div class="field is-horizontal">
	';
if((!empty($doctypesmessage)) && $doctypesmessage[$lang])
		$document_form .=  '<label class="label field-label is-normal" for="genre_'.$form_index.'">'. $doctypesmessage[$lang] . __("Document type").'</label>';
else
		$document_form .=  '<label class="label field-label is-normal" for="genre_'.$form_index.'">'.__("Document type").'</label>';

	$document_form .=  '
	</div>
	</div>
	<div class="column is-one-quarter">
        <div class="control">
         <div class="select is-fullwidth">
          <select id="genre_'.$form_index.'" name="genre_'.$form_index.'">';
			  foreach($doctypes as $value) {
	$document_form .=  "<option value=\"" . htmlspecialchars($value["code"]) . "\" ".($genre_code==$value["code"]? 'selected': '')." >" . htmlspecialchars($value["name"]) . "</option>\n";
}
	$document_form .=  '
		</select>
         </div>
        </div>
       </div>
	</div>

	<div class="columns is-gapless is-columns-form">
    <div class="column is-one-fifth">
	  <div class="field is-horizontal">
      <label class="label field-label is-normal required" for="title_'.$form_index.'">'.__("Journal / Book").'</label>
	  </div>
	</div>
	<div class="column is-three-quarters">
        <div class="control">
         <input class="input" id="title_'.$form_index.'" name="title_'.$form_index.'" type="text" value="'.htmlspecialchars($title).'" onchange="resolve('.$form_index.', 1);" placeholder="'.__("Journal or book title").'">
        </div>
    </div>
	<div class="column is-2">
	<div class="field is-horizontal">
	<span class="buttons label field-label is-normal has-text-left"> &nbsp;
       <a href="javascript:openlist(\''.$periodical_title_search_url.'\', \''.$form_index.'\')"><span class="is-light" title="'. __("check on journals database") .'"><i aria-hidden="true" class="fa fa-search fa-lg"></i></span></a>
     </span>
	 </div>
      </div>
	  </div>
	<div class="columns is-gapless is-columns-form">
    <div class="column is-one-fifth">
	  <div class="field is-horizontal">
      <label class="label field-label is-normal" for="date_'.$form_index.'">'.__('Year').'</label>
	  </div>
	</div>
	<div class="column is-1">
        <div class="control">
         <input class="input" id="date_'.$form_index.'" name="date_'.$form_index.'" type="text" value="'.htmlspecialchars(substr($date, 0, 10)).'" onchange="resolve('.$form_index.', 1);" maxlength="10">
        </div>
    </div>
     <div class="column is-1">
	  <div class="field is-horizontal">
      <label class="label field-label is-normal" for="volume_'.$form_index.'">'.__('Vol.').'</label>
	  </div>
	</div>
	<div class="column is-1">
        <div class="control">
         <input class="input" id="volume_'.$form_index.'" name="volume_'.$form_index.'" type="text" value="'.htmlspecialchars(substr($volume, 0, 50)).'" onchange="resolve('.$form_index.', 1);" maxlength="50">
        </div>
    </div>
     <div class="column is-1">
	  <div class="field is-horizontal">
      <label class="label field-label is-normal" for="issue_'.$form_index.'">'.__('Issue').'</label>
	  </div>
	</div>
	<div class="column is-1">
        <div class="control">
         <input class="input" id="issue_'.$form_index.'" name="issue_'.$form_index.'" type="text" value="'.htmlspecialchars(substr($issue, 0, 100)).'" onchange="resolve('.$form_index.', 1);" maxlength="100">
       </div>
    </div>
     <div class="column is-1">
	  <div class="field is-horizontal">
      <label class="label field-label is-normal" for="suppl_'.$form_index.'">'.__('Suppl.').'</label>
	  </div>
	</div>
	<div class="column is-1">
        <div class="control">
         <input class="input" id="suppl_'.$form_index.'" name="suppl_'.$form_index.'" type="text" value="'.htmlspecialchars(substr($suppl, 0, 100)).'" onchange="resolve('.$form_index.', 1);" maxlength="100">
       </div>
    </div>
     <div class="column is-1">
	  <div class="field is-horizontal">
      <label class="label field-label is-normal" for="pages_'.$form_index.'">'.__('Pages').'</label>
	  </div>
	</div>
	<div class="column is-1">
        <div class="control">
         <input class="input" id="pages_'.$form_index.'" name="pages_'.$form_index.'" type="text" value="'.htmlspecialchars(substr($pages, 0, 50)).'" onchange="resolve('.$form_index.', 1);"  maxlength="50">
        </div>
       </div>
      </div>


	<div class="columns is-gapless is-columns-form">
    <div class="column is-one-fifth">
	  <div class="field is-horizontal">
      <label class="label field-label is-normal" for="atitle_'.$form_index.'">'.__("Title").'</label>
      	  </div>
	</div>
	<div class="column is-three-quarters">
        <div class="control">
         <input class="input" id="atitle_'.$form_index.'" name="atitle_'.$form_index.'" type="text" value="'.htmlspecialchars($atitle).'" placeholder="'.__("Article or chapter title").'" onchange="resolve('.$form_index.', 1);">
        </div>
       </div>
      </div>

	<div class="columns is-gapless is-columns-form">
    <div class="column is-one-fifth">
	  <div class="field is-horizontal">
      <label class="label field-label is-normal" for="auteurs_'.$form_index.'">'.__("Author(s)").'</label>
      </div>
	</div>
	<div class="column is-three-quarters">
        <div class="control">
         <input class="input" id="auteurs_'.$form_index.'" name="auteurs_'.$form_index.'" type="text" value="'.htmlspecialchars(substr($auteurs, 0, 255)).'" onchange="resolve('.$form_index.', 1);" maxlength="255">
        </div>
       </div>
      </div>

	<div class="columns is-gapless is-columns-form">
    <div class="column is-one-fifth">
	  <div class="field is-horizontal">
      <label class="label field-label is-normal" for="edition_'.$form_index.'">'.__("Edition").'</label>
 	  </div>
	</div>
	<div class="column is-2">
        <div class="control">
         <input class="input" id="edition_'.$form_index.'" name="edition_'.$form_index.'" type="text" value="'.htmlspecialchars(substr($edition, 0, 100)).'" placeholder="'.__("(for books)").'" onchange="resolve('.$form_index.', 1);" maxlength="100">
        </div>
    </div>
     <div class="column is-2">
	  <div class="field is-horizontal">
      <label class="label field-label is-normal" for="issn_'.$form_index.'">'.__('ISSN / ISBN').'</label>
	  </div>
	</div>
	<div class="column is-2">
        <div class="control">
         <input class="input" id="issn_'.$form_index.'" name="issn_'.$form_index.'" type="text"  value="'.htmlspecialchars(substr($issn, 0, 50)).'" onchange="resolve('.$form_index.', 1);" maxlength="50">
		</div>
    </div>
     <div class="column is-1">
	  <div class="field is-horizontal">
      <label class="label field-label is-normal" for="uid_'.$form_index.'">'.__('UID').'</label>
	  </div>
	</div>
	<div class="column is-2">
        <div class="control">
         <input class="input" id="uid_'.$form_index.'" name="uid_'.$form_index.'" type="text" value="'.htmlspecialchars(substr($uid, 0, 255)).'" onchange="resolve('.$form_index.', 1);" maxlength="255">
        </div>
       </div>
      </div>

	<div class="columns is-gapless is-columns-form">
    <div class="column is-one-fifth">
	  <div class="field is-horizontal">
      <label class="label field-label is-normal" for="remarquespub_'.$form_index.'">'.__("Notes").'</label>
      </div>
	</div>
	<div class="column is-three-quarters">
        <div class="control">
         <textarea id="remarquespub_'.$form_index.'" name="remarquespub_'.$form_index.'" class="textarea" placeholder="" rows="2">'.htmlspecialchars($remarquespub).'</textarea>
        </div>
       </div>
      </div>
  
  ';
    // Resolved block, if enabled
	if (isset($config_link_resolver_base_openurl) && $config_link_resolver_base_openurl != ''){
		// check if resolved links exist in cache
		$resolved_block_content = "";
		$resolved_block_style = 'display:none';
		$query = "SELECT cache FROM `resolver_cache` WHERE params=? LIMIT 1";
		$res = dbquery($query, array($resolver_search_params), 's');
		if (iimysqli_num_rows($res) > 0) {
			$resolved_block_content = json_decode(iimysqli_result_fetch_array($res)['cache'], true)['msg'];
			$resolved_block_style = '';
		}
		$document_form .= '<div class="columns is-gapless is-columns-form">
  <input type="hidden" id="resolver_search_params_'. $form_index .'" name="resolver_search_params_'. $form_index .'" value="'.htmlspecialchars($resolver_search_params).'" />
  <div class="column" id="resolvedurlblock_'. $form_index .'" style="'.$resolved_block_style.'">'.$resolved_block_content.'</div></div>';
	}

	if ($can_remove_orders) {
		$document_form .= '<div class="control"><a class="removeLink button is-danger is-outlined" style="cursor:pointer;" onclick="form=document.getElementById(\'orderform\');form.remove_form.value='.$form_index.';form.action=\'index.php#order_nb_'.($form_index-1).'\';form.submit();">'.__("Remove").'</a></div>';
	}
	$document_form .= '	</div>
	</section>';
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
	echo format_string(__('Fill in your order or %x_url_startupload a file with references%x_url_end:'), array('x_url_start' => '<a style="cursor: pointer;" onclick="document.getElementById(\'order_file\').click();">', 'x_url_end' => '</a>'));
	echo '&nbsp;<a href="#" class="helpinfo" onclick="return false;">?<span class="supportedFileFormats">';
	echo htmlspecialchars(__('Supported file formats:'));
	echo '<ul><li>' . htmlspecialchars(__('EndNote XML (created via "File" → "Export" → Type "XML")'));
	echo '</li><li>'. htmlspecialchars(__('RIS'));
	echo '</li><li>'. htmlspecialchars(__('MEDLINE/PubMed (.nbib files created via "Send to" → "Citation Manager")'));
	echo '</li><li>'. htmlspecialchars(__('List of PubMed identifiers (comma-separated PMIDs)'));
	echo '</ul>';
	echo sprintf(__('Maximum file size: %s MB'), round(min(parse_size_str(ini_get('upload_max_filesize')), parse_size_str(ini_get('post_max_size'))) / (1024*1024), 0, PHP_ROUND_HALF_DOWN)) . "<br/>";
	echo sprintf(__('Maximum number of references: %s'), $maxSimultaneousOrders);
	echo '</span></a>';
	echo '<input style="display:none;" type="file" id="order_file" name="order_file" onchange="form=document.getElementById(\'orderform\');form.action=\'index.php\';form.submit();" />';

	echo '</div>';
}
$form_index_to_lookup = array();
foreach ($order_form_values as $form_index => $value) {
	echo get_document_form($lookupuid, !empty($doctypesmessage) ? $doctypesmessage : "", $doctypes, $periodical_title_search_url, $can_remove_orders, $form_index, $value['tid_code'], $value['uids'], $value['genre_code'], $value['title'], $value['date'], $value['volume'], $value['issue'], $value['suppl'], $value['pages'], $value['atitle'], $value['auteurs'], $value['edition'], $value['issn'], $value['uid'], $value['remarquespub'], $value['resolver_search_params']);
	if ($value['need_lookup']) {
		$form_index_to_lookup[]=$form_index;
	}
}
if (count($form_index_to_lookup) > 0){
	// Auto-fill those PMIDs given via PMID_% url paramters
	echo '<script type="text/javascript">lookup_pmids(['. join(',', $form_index_to_lookup) .'], "'.htmlspecialchars($configemail).'", 3);</script>';
}
if (count($order_form_values) < max($maxSimultaneousOrders, 1))  {
	echo '<div class="addItemPanel"><input type="hidden" name="add_form" value="0"/><a style="cursor:pointer" onclick="form=document.getElementById(\'orderform\');form.add_form.value=1;form.action=\'index.php#order_nb_'.count($order_form_values).'\';form.submit();">'.__("Add an item to the order").'</a></div>';
}

echo '



	<div class="container">';
if (($config_dataprotection_consent_mode == 2 || ($config_dataprotection_consent_mode == 1 && !is_privileged_enough($monaut, "user"))) &&
	($config_dataprotection_consent_conditionsofuse_url[$lang] != "" || $config_dataprotection_consent_legal_information_url[$lang] != "")) {
	echo '
		<div class="field is-grouped is-grouped-centered">
		  <div class="control" style="flex-shrink: 1">
			<label class="checkbox">
			  <input type="checkbox" name="consent" value="'.$config_dataprotection_consent_version.'" required>
			  ';
			  if ($config_dataprotection_consent_conditionsofuse_url[$lang] != "" && $config_dataprotection_consent_legal_information_url[$lang] != "") {
				echo format_string(__('I agree to the service %x_url_start_1privacy policy%x_url_end_1 and %x_url_start_2conditions of use%x_url_end_2'),
				  array('x_url_start_1' => '<a href="'.$config_dataprotection_consent_legal_information_url[$lang].'" target="_blank">',
						'x_url_end_1' => '</a>',
						'x_url_start_2' => '<a href="'.$config_dataprotection_consent_conditionsofuse_url[$lang].'" target="_blank">',
						'x_url_end_2' => '</a>'));
			  } else if ($config_dataprotection_consent_legal_information_url[$lang] != "") {
				echo format_string(__('I agree to the service %x_url_start_1privacy policy%x_url_end_1'),
				  array('x_url_start_1' => '<a href="'.$config_dataprotection_consent_legal_information_url[$lang].'" target="_blank">',
						'x_url_end_1' => '</a>'));

			  } else if ($config_dataprotection_consent_conditionsofuse_url[$lang] != "") {
				echo format_string(__('I agree to the service %x_url_start_1conditions of use%x_url_end_1'),
				  array('x_url_start_1' => '<a href="'.$config_dataprotection_consent_conditionsofuse_url[$lang].'" target="_blank">',
						'x_url_end_1' => '</a>'));
			  }
	echo '
			</label>
		  </div>
		</div>
		';
}
echo '
	  <div class="field is-grouped is-grouped-centered">
	  <p class="control">
      <input type="submit" class="button is-primary" value="'. ((($monaut == "guest")||($monaut == "")) ? __("Send order") : __("Save order")) .'" onsubmit="javascript:okcooc();document.body.style.cursor = \'wait\';" />
      </p>
	  <p class="control">
	  <input type="reset" value="'. __("Reset") .'" class="button" />
	  </p>
	  </div>
	</div>
	';

echo "</form>\n";

require ("includes/footer.php");
?>
