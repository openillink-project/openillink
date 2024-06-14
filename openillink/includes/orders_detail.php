<?php
// ***************************************************************************
// ***************************************************************************
// ***************************************************************************
// This file is part of OpenILLink software.
// Copyright (C) 2005, 2006, 2007, 2008, 2009, 2010, 2011, 2012, 2013, 2015, 2016, 2017, 2018, 2019, 2020, 2023, 2024 CHUV.
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
// Order details only for administrators
//

require_once ("connexion.php");
require_once ("toolkit.php");

if (($monaut == "admin")||($monaut == "sadmin")||($monaut == "user")){
    $id= ((!empty($_GET['id'])) && isValidInput($_GET['id'],8,'s',false)) ? $_GET['id'] : NULL;
    $myhtmltitle = format_string(__("%institution_name order: order %order_id detail"), array('institution_name' => $configinstitution[$lang], 'order_id' => $id));
    if ($id){

		$codeSpecial = array();
		$codeIn = array();
		$codeOut = array();
		$codeTrash = array();
		$statusInfo = readStatus($codeIn, $codeOut, $codeTrash, $codeSpecial);
		$sharedLibrariesArray = getSharingLibrariesForBib($monbib);
		$locListArray = getLibraryLocalizationCodes($monbib);
		$servListArray = getLibraryUnitCodes($monbib);
		$library_signature = getLibrarySignature($monbib);
        $config_display_delivery_choice = isset($config_display_delivery_choice) ? $config_display_delivery_choice : true;
        $config_display_cgr_fields = isset($config_display_cgr_fields) ? $config_display_cgr_fields : false;

        $req = "SELECT orders.*, status.title1 AS statusname, status.help1 AS statushelp, status.special AS statusspecial, status.color AS statuscolor, libraries.name1 AS libname, localizations.name1 AS locname, units.name1 AS unitname ".
        "FROM orders LEFT JOIN status ON orders.stade = status.code LEFT JOIN libraries ON orders.bibliotheque = libraries.code LEFT JOIN localizations ON orders.localisation = localizations.code LEFT JOIN units ON orders.service = units.code ".
        "WHERE orders.illinkid LIKE ? GROUP BY orders.illinkid ORDER BY orders.illinkid DESC";
        $result = dbquery($req,array($id), 'i');
        $nb = iimysqli_num_rows($result);
        require ("headeradmin.php");
        require ("email.php");
        for ($i=0 ; $i<$nb ; $i++){
            $enreg = iimysqli_result_fetch_array($result);
            $id = $enreg['illinkid'];
            $date = $enreg['date'];
            $stade = $enreg['stade'];
            $localisation = $enreg['localisation'];
            $nom = $enreg['nom'].', '.$enreg['prenom'];
            $mail = $enreg['mail'];
            $locname = $enreg['locname'];
            $unitname = $enreg['unitname'];
            $statusname = $enreg['statusname'];
            $statushelp = $enreg['statushelp'];
            $statusspecial = $enreg['statusspecial'];
            $statuscolor = $enreg['statuscolor'];
            $libname = $enreg['libname'];
            $libcode = $enreg['bibliotheque'];

			$is_my_bib = ($monbib == $enreg['bibliotheque']);
			$is_my_service = (in_array($enreg['service'], $servListArray));
			$is_my_localisation = (in_array($localisation, $locListArray));
			$is_shared = ((!empty($enreg['bibliotheque'])) && in_array($enreg['bibliotheque'], $sharedLibrariesArray) && empty($localisation) && in_array($stade, $codeSpecial['new']));

			$maillog = "";
			$passwordg = "";
            if ($mail && $enreg['anonymized'] != 1){
                $pos1 = strpos($mail,';');
                $pos2 = strpos($mail,',');
                $pos3 = strpos($mail,' ');
                if (($pos1 === false)&&($pos2 === false)&&($pos3 === false)){
                    $maillog = strtolower($mail);
                }
                else{
                    if (($pos1 != false)&&($pos2 != false)&&($pos3 != false))
                        $pos = min($pos1, $pos2, $pos3);
                    else if (($pos1 != false)&&($pos2 != false))
                            $pos = min($pos1, $pos2);
                        else if (($pos1 != false)&&($pos3 != false))
                                $pos = min($pos1, $pos3);
                            else if (($pos2 != false)&&($pos3 != false))
                                    $pos = min($pos2, $pos3);
                                else if ($pos1 != false)
                                        $pos = $pos1;
                                    else if ($pos2 != false)
                                            $pos = $pos2;
                                        else if ($pos3 != false)
                                            $pos = $pos3;
                    $maillog = substr($mail,0,$pos);
                    $maillog = strtolower($maillog);
                }
                $mailg = $maillog . $secure_string_guest_login;
                $passwordg = substr(hash("sha256", $mailg), 0, 8);
            }
            $adresse = $enreg['adresse'].', '.$enreg['code_postal'].' '.$enreg['localite'];
            $titreper = $enreg['titre_periodique'];
            $titreart = $enreg['titre_article'];
			echo '<div class="columns box is-gapless">
					<div class="column is-three-quarters">';
            echo "<b>".__("Order number")." : </b>".$id;
            if ($enreg['urgent']=='1' || $enreg['urgent']=='oui')
                echo " (<b><font color=\"red\">".__("Urgent order")."</font></b>)\n";
            if ($enreg['urgent']=='3' || $enreg['urgent']=='non')
                echo " (<font color=\"SteelBlue\">".__("Non-priority order")."</font>)\n";
			if ($is_shared){
				echo '<span class="isSharedOrder">'.__("Shared Incoming Order").'</span>';
			}
			if ($enreg['anonymized'] == 1) {
				echo '&nbsp;<a href="#" class="info has-text-info" onclick="return false"><i class="fas fa-mask"></i><span>'.__("Order anonymized after exceeding maximum data retention period").'</span></a>';
			}
            if (($enreg['type_doc']!='article') && ($enreg['type_doc']!='Article'))
                echo "&nbsp;&nbsp;&nbsp;<img src=\"img/book.png\">";
            echo "<br /><b>".__("Order date")." : </b>".$date;
            if ($enreg['envoye']>0)
                echo "\n<br /><b>". __("Shipment date") ." : </b>".htmlspecialchars($enreg['envoye']);
            if ($enreg['facture']>0)
                echo "\n<br /><b>". __("Billing date") ." : </b>".htmlspecialchars($enreg['facture']);
            if ($enreg['renouveler']>0)
                echo "\n<br /><b>". __("Renewal date") ." : </b>".htmlspecialchars($enreg['renouveler']);
            echo "\n<br /><b>". __("Assignment Library") ." : </b>";
			if (!$is_my_bib) {
				echo '<span class="notMyBib">'. htmlspecialchars($libname) . " (". htmlspecialchars($libcode).")" . '</span>';
			} else {
				echo htmlspecialchars($libname) . " (". htmlspecialchars($libcode).")";
			}
            if ($localisation) {
				echo "\n<br /><b>". __("Localization") ." : </b>" ;
				if (!$is_my_localisation) {
					echo '<span class="notMyLocalisation">'.htmlspecialchars($locname) . " (" . htmlspecialchars($localisation) . ")".'</span>';
				} else {
					echo htmlspecialchars($locname) . " (" . htmlspecialchars($localisation) . ")";
				}
			}
            echo "<br /><b>". __("Status") ." : \n";
            echo "<a href=\"#\" onclick=\"return false\" class=\"statusLink\" title=\"".htmlspecialchars($statushelp)."\"><font color=\"".htmlspecialchars($statuscolor)."\">".htmlspecialchars($statusname)."</font></a></b>";
            if ($statusspecial == "renew"){
                if ($enreg['renouveler']){
					echo format_string(__("%document_on_hold_status_label until %date"), array('date' => $enreg['renouveler'], 'document_on_hold_status_label' => ''));
				}
            }
            echo "<br /><b>". __("User") ." : </b><a href=\"list.php?folder=search&champ=nom&term=".htmlspecialchars(urlencode ($nom))."\" title=\"". __("Search for orders from this user") ."\">\n";
            echo htmlspecialchars($nom)."</a>\n";
            // formated e-mails
            if ($mail){
                echo "<br /><b>". __("E-mail") ." : </b><a href=\"list.php?folder=search&champ=email&term=".htmlspecialchars(urlencode($mail))."\" title=\"". __("Search for orders from this email") ."\">".htmlspecialchars($mail)."</a>\n";
                $monhost = "http://" . $_SERVER['SERVER_NAME'];
                $monuri = $monhost . rtrim(dirname($_SERVER['PHP_SELF']), '/\\') . "/";
                displayMailText($monaut, $monuri, $enreg, $emailTxt, $titreart, $titreper, $nom, $maillog, $passwordg, $mail, $library_signature, $lang);
            }
            if ($enreg['adresse'])
                echo "<br /><b>". __("Address") ." : </b>".htmlspecialchars($adresse);
            if ($enreg['service']) {
				$service_class = "";
				if (!$is_my_service) {
					$service_class = ' class="notMyService" ';
				}
                echo "<br /><b>". __("Service") ." : </b><a ".$service_class."href=\"list.php?folder=search&champ=service&term=".htmlspecialchars(urlencode($enreg['service']))."\" title=\"". __("Search for orders from this service") ."\">".htmlspecialchars($enreg['service'])."</a>\n";
            }
			if ($enreg['type_doc'])
                echo "<br /><b>". __("Document type") ." : </b>".htmlspecialchars($enreg['type_doc']);
            echo "<br />\n";
            if ($enreg['titre_article'])
                echo "<b>". __("Title") ." : </b><a href=\"list.php?folder=search&champ=atitle&term=".htmlspecialchars(urlencode ($enreg['titre_article']))."\" title=\"". __("Search for orders from this title") ."\">".htmlspecialchars($enreg['titre_article'])."</a><br />\n";
            if ($enreg['auteurs'])
                echo "<b>". __("Author(s)") ." : </b>".htmlspecialchars($enreg['auteurs'])."<br />\n";
            if ($enreg['titre_periodique']){
                if (($enreg['type_doc']=='article') || ($enreg['type_doc']=='Article'))
                    echo "<b>". __("Journal") ." : </b>\n";
                if ($enreg['type_doc']=='journal')
                    echo "<b>". __("Journal") ." : </b>\n";
                if (($enreg['type_doc']=='Livre') || ($enreg['type_doc']=='book'))
                    echo "<b>". __("Book") ." : </b>\n";
                if (($enreg['type_doc']=='thesis') || ($enreg['type_doc']=='These') || ($enreg['type_doc']=='Thèse'))
                    echo "<b>". __("Thesis") ." : </b>\n";
                if (($enreg['type_doc']=='Chapitre') || ($enreg['type_doc']=='preprint') || ($enreg['type_doc']=='bookitem'))
                    echo "<b>". __("In") ." : </b>\n";
                if (($enreg['type_doc']=='autre') || ($enreg['type_doc']=='Autre') || ($enreg['type_doc']=='other'))
                    echo "<b>". __("In") ." : </b>\n";
                if (($enreg['type_doc']=='Congres') || ($enreg['type_doc']=='proceeding') || ($enreg['type_doc']=='conference'))
                    echo "<b>". __("In") ." : </b>\n";
                echo "</b><a href=\"list.php?folder=search&champ=title&term=".htmlspecialchars(urlencode ($enreg['titre_periodique']))."\" title=\"". __("Search for orders from this title") ."\">".htmlspecialchars($enreg['titre_periodique'])."</a>\n";
            }
            if ($enreg['volume'])
                echo "<br /><b>". __("Volume") ." : </b>".htmlspecialchars($enreg['volume']);
            if ($enreg['numero'])
                echo "\n<br /><b>". __("Issue") ." : </b>".htmlspecialchars($enreg['numero']);
            if ($enreg['supplement'])
                echo "\n<br /><b>". __("Suppl.") ." : </b>".htmlspecialchars($enreg['supplement']);
            if ($enreg['pages'])
                echo "\n<br /><b>". __("Pages") ." : </b>".htmlspecialchars($enreg['pages']);
            if ($enreg['annee'])
                echo "\n<br /><b>". __("Year") ." : </b>".htmlspecialchars($enreg['annee']);
            if ($enreg['issn'])
                echo "\n<br /><b>ISSN : </b>".htmlspecialchars($enreg['issn']);
            if ($enreg['eissn'])
                echo "\n<br /><b>eISSN : </b>".htmlspecialchars($enreg['eissn']);
            if ($enreg['isbn'])
                echo "\n<br /><b>ISBN : </b>".htmlspecialchars($enreg['isbn']);
            if ($enreg['PMID'])
                echo "\n<br /><b>PMID : </b><a href=\"https://www.ncbi.nlm.nih.gov/entrez/query.fcgi?otool=ichuvlib&cmd=Retrieve&db=pubmed&dopt=citation&list_uids=".htmlspecialchars(urlencode ($enreg['PMID']))."\" target=\"_blank\">".htmlspecialchars($enreg['PMID'])."</a>\n";
            if ($enreg['doi']){
                echo "\n<br /><b>DOI : </b><a href=\"https://dx.doi.org/".htmlspecialchars($enreg['doi'])."\" target=\"_blank\">".htmlspecialchars($enreg['doi'])."</a>\n";
            }
            if ($enreg['uid']){
                $parsed_uid = parse_uid_str($enreg['uid'], false);
                if (array_key_exists('mms', $parsed_uid)) {
                    if ($configMMSdiscoveryurl[$lang] != "") {
                        echo "\n".'<br /><b>MMS : </b><a target="_blank" href="' . str_replace("{MMS_ID}", htmlspecialchars($parsed_uid['mms']), $configMMSdiscoveryurl[$lang]). '">MMS:' . htmlspecialchars($parsed_uid['mms']) . '</a>';
                    } else {
                        echo "\n".'<br /><b>MMS : </b>MMS:' . htmlspecialchars($parsed_uid['mms']);
                    }
                }
                if (array_key_exists('wosut', $parsed_uid)) {
                    echo "\n".'<br /><b>WOSID : </b><a target="_blank" href="https://www.webofscience.com/wos/woscc/full-record/' . htmlspecialchars($parsed_uid['wosut']). '">' . htmlspecialchars($parsed_uid['wosut']) . '</a>';
                }
                $other_identifiers = "";
                foreach ($parsed_uid as $key => $value) {
                    if ($key != "doi" and $key != "mms" && $key != "pmid" && $key != "wosut") {
                        $other_identifiers .= htmlspecialchars($key) . ":" . htmlspecialchars($value) . " ";
                    }
                }
                if ($other_identifiers) {
                    echo "\n<br /><b>". __("Other identifier") ." : </b>".$other_identifiers;
                }
            }
            if ($config_display_cgr_fields){
                if ($enreg['cgra'])
                    echo "\n<br /><b>". __("Management Code A") ." : </b>".htmlspecialchars($enreg['cgra']);
                if ($enreg['cgrb'])
                    echo "\n<br /><b>". __("Management Code B") ." : </b>".htmlspecialchars($enreg['cgrb']);
            }
            if ($enreg['tel'])
                echo "\n<br /><b>". __("Tel. number") ." : </b>".htmlspecialchars($enreg['tel']);
            if ($enreg['saisie_par'])
                echo "\n<br /><b>". __("Entered by") ." : </b>".htmlspecialchars($enreg['saisie_par']);
            if ($enreg['ip'])
                echo "\n<br /><b>". __("IP adress") ." : </b>".htmlspecialchars($enreg['ip']);
            if ($enreg['referer'])
                echo "\n<br /><b>". __("Provenance URL") ." : </b>".htmlspecialchars(rawurldecode($enreg['referer']));
            if ($enreg['arrivee'])
                echo "\n<br /><b>". __("Arrival by") ." : </b>".htmlspecialchars($enreg['arrivee']);
            if ($config_display_delivery_choice) {
                if ($enreg['envoi_par'])
                    echo "\n<br /><b>". __("Send by") ." : </b>";
                if ($enreg['envoi_par'] == 'surplace')
                    echo "<b><font color=\"red\">". __("Inform the reader if available on site") ."</font></b>";
                else
                    echo htmlspecialchars($enreg['envoi_par']);
            }
            if ($enreg['prix'])
                echo "\n<br /><b>". __("Price") ." : </b>".htmlspecialchars($enreg['prix']);
            if ($enreg['prepaye'])
                echo "\n<br /><b><font color=\"green\">". __("Paid in advance") ." : </b>".htmlspecialchars(strtr($enreg['prepaye'], "on", "OK"))." </font>";
            if ($enreg['ref'])
                echo "\n<br /><b>". __("Provider ref.") ." : </b>".htmlspecialchars($enreg['ref']);
            if ($enreg['refinterbib'])
                echo "\n<br /><b>". __("Internal library ref.") ." : </b>".htmlspecialchars($enreg['refinterbib']);
            if ($mail && !empty($maillog))
                echo "\n<br /><b>". __("Guest access code") ." : </b> ". __("Username") .": ".htmlspecialchars($maillog)." | ". __("Password") .": ".htmlspecialchars($passwordg);
            if ($enreg['remarquespub'])
                echo "\n<br /><b>". __("Public comment") ." : </b>".nl2br(htmlspecialchars($enreg['remarquespub']));
            if ($enreg['remarques']){
				$prepared_remarque_for_html = nl2br(htmlspecialchars($enreg['remarques']));
				$prepared_remarque_for_html = str_replace(__("Warning. Possible duplicate of the order."), '<span class="remarkDuplicateOrder">'.__("Warning. Possible duplicate of the order.") . '</span>', $prepared_remarque_for_html);
                echo "\n<br /><b>". __("Professional comment") ." : </b>". $prepared_remarque_for_html;
			}
            echo "\n<br /><br /><b>". __("Order history") ." : </b>\n<br />".str_replace('&lt;br /&gt;', '<br />', htmlspecialchars($enreg['historique']));
			echo '</div>
				  <div class="column">';
            require ("links.php");
			echo '</div>
			</div>'; 
        }
        require ("footer.php");
        }
    else{
        require ("header.php");
        require ("loginfail.php");
        require ("footer.php");
    }
}
else{
    require ("header.php");
    require ("loginfail.php");
    require ("footer.php");
}
?>
