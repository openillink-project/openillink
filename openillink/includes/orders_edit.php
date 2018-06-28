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
// Order edition form
//
require_once ("authip.php");
require_once ("connexion.php");
require_once ("includes/toolkit.php");

if (($monaut == "admin")||($monaut == "sadmin")||($monaut == "user")){
    $id= ((!empty($_GET['id'])) && isValidInput($_GET['id'],8,'s',false)) ? $_GET['id'] : NULL;
    $myhtmltitle = format_string(__("%institution_name: order %order_id edition"), array('institution_name' => $configinstitution[$lang], 'order_id' => $id));
    if ($id){
        $req = "select * from orders where illinkid like ? order by illinkid desc";
        $result = dbquery($req, array($id), 'i');
        $nb = iimysqli_num_rows($result);
        require ("headeradmin.php");
        for ($i=0 ; $i<$nb ; $i++){
            $enreg = iimysqli_result_fetch_array($result);
            $id = $enreg['illinkid'];
            echo "<script type=\"text/javascript\">\n";
            echo "function textchanged(changes) {\n";
            echo "document.commande.modifs.value = document.commande.modifs.value + changes + ' - ';\n";
            echo "}\n";
            echo "function ajoutevaleur(champ) {\n";
            echo "var champ2 = champ + 'new';\n";
            echo "var res = document.getElementById(champ).value;\n";
            echo "if (res == 'new')\n";
            echo "{\n";
            echo "document.getElementById(champ2).style.display='inline';\n";
            echo "}\n";
            echo "document.commande.modifs.value = document.commande.modifs.value + champ + ' - ';\n";
            echo "}\n";
            echo "</script>\n";
            echo "<form action=\"update.php\" method=\"POST\" enctype=\"x-www-form-encoded\" name=\"commande\">\n";
            echo "<input name=\"id\" type=\"hidden\"  value=\"".htmlspecialchars($id)."\">\n";
            echo "<input name=\"userid\" type=\"hidden\"  value=\"".htmlspecialchars($enreg['saisie_par'])."\">\n";
            echo "<input name=\"ip\" type=\"hidden\"  value=\"".htmlspecialchars($enreg['ip'])."\">\n";
// echo "<input name=\"referer\" type=\"hidden\"  value=\"".$enreg['referer']."\">\n";
            echo "<input name=\"doi\" type=\"hidden\"  value=\"".htmlspecialchars($enreg['doi'])."\">\n";
            echo "<input name=\"historique\" type=\"hidden\"  value=\"".htmlspecialchars($enreg['historique'])."\">\n";
            echo "<b><font color=\"red\">".format_string(__("Order %order_id modification"), array('order_id' => htmlspecialchars($id)))."</font></b>\n";
            echo "<input name=\"action\" type=\"hidden\" value=\"update\">\n";
            echo "<input name=\"modifs\" type=\"hidden\" value=\"\">\n";
            echo "&nbsp;&nbsp;|&nbsp;&nbsp;<b><label for=\"bibliotheque\">".__("Attributed to library")."</label></b> <select name=\"bibliotheque\" id=\"bibliotheque\" onchange=\"textchanged('bibliotheque')\">\n";
            $reqlibraries="SELECT code, name1, name2, name3, name4, name5 FROM libraries ORDER BY name1 ASC";
            $optionslibraries="";
            $resultlibraries = dbquery($reqlibraries);
            $nblibs = iimysqli_num_rows($resultlibraries);
            if ($nblibs > 0){
                while ($rowlibraries = iimysqli_result_fetch_array($resultlibraries)){
                    $codelibraries = $rowlibraries["code"];
                    $namelibraries["fr"] = $rowlibraries["name1"];
                    $namelibraries["en"] = $rowlibraries["name2"];
                    $namelibraries["de"] = $rowlibraries["name3"];
                    $namelibraries["it"] = $rowlibraries["name4"];
                    $namelibraries["es"] = $rowlibraries["name5"];
                    $optionslibraries.="<option value=\"" . htmlspecialchars($codelibraries) . "\"";
                    if ($enreg['bibliotheque'] == $codelibraries ||
                    ($enreg['bibliotheque']== '' && $monbib == $codelibraries ))
                        $optionslibraries.=" selected";
                    $optionslibraries.=">" . htmlspecialchars($namelibraries[$lang]) . "</option>\n";
                }
                echo $optionslibraries;
            }
            echo "</select>\n";
            echo "&nbsp;&nbsp;|&nbsp;&nbsp;<input type=\"submit\" value=\"" . __("Submit") . "\" onsubmit=\"javascript:okcooc();document.body.style.cursor = 'wait';\">\n";
            echo "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\n";
            echo "<tr><td valign=\"top\" width=\"90%\">\n";
            echo "<div class=\"box\"><div class=\"box-content\">\n";
            // START Management Fields
            echo "<table border=\"0\" cellspacing=\"0\" cellpadding=\"3\" width=\"100%\">\n";
            echo "<tr><td colspan=\"4\">\n";
            // Begin Status Field
			echo '<label for="stade">';
            echo __("Status") . " *</label> : \n";
            echo "<select name=\"stade\" id=\"stade\" onchange=\"textchanged('stade')\">\n";
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
                if ($enreg['stade'] == $codestatus)
                    $optionsstatus.=" selected";
                $optionsstatus.=">" . htmlspecialchars($namestatus[$lang]) . "</option>\n";
            }
            echo $optionsstatus;
            echo "</select>\n";
            // END Status Field
            // Begin Localization Field
            $localisationok = 0;
            echo "&nbsp;&nbsp;&nbsp;&nbsp;\n";
			echo '<label for="localisation">';
            echo __("Localization") . "</label> : &nbsp;\n";
            echo "<select name=\"localisation\" id=\"localisation\" onchange=\"textchanged('localisation')\">\n";
            echo "<option value=\"\"></option>";
            echo "<optgroup label=\"" . __("Our Localizations") . "\">\n";
            $reqlocalisation="SELECT code, library, name1, name2, name3, name4, name5 FROM localizations WHERE library = ? ORDER BY name1 ASC";
            $optionslocalisation="";
            $resultlocalisation = dbquery($reqlocalisation,array($monbib),'s');
            while ($rowlocalisation = iimysqli_result_fetch_array($resultlocalisation)){
                $codelocalisation = $rowlocalisation["code"];
                $namelocalisation["fr"] = $rowlocalisation["name1"];
                $namelocalisation["en"] = $rowlocalisation["name2"];
                $namelocalisation["de"] = $rowlocalisation["name3"];
                $namelocalisation["it"] = $rowlocalisation["name4"];
                $namelocalisation["es"] = $rowlocalisation["name5"];
                $optionslocalisation.="<option value=\"".htmlspecialchars($codelocalisation)."\"";
                if ($enreg['localisation'] == $codelocalisation){
                    $optionslocalisation.=" selected";
                    $localisationok = 1;
                }
                $optionslocalisation.=">" . htmlspecialchars($namelocalisation[$lang]) . "</option>\n";
            }
            echo $optionslocalisation;
            // select other libraries
            $reqlocalisationext="SELECT code, name1, name2, name3, name4, name5 FROM libraries WHERE code != ? ORDER BY name1 ASC";
            $optionslocalisationext="";
            $resultlocalisationext = dbquery($reqlocalisationext,array($monbib), 's');
            $nbext = iimysqli_num_rows($resultlocalisationext);
            if ($nbext > 0){
                while ($rowlocalisationext = iimysqli_result_fetch_array($resultlocalisationext)){
                    $codelocalisationext = $rowlocalisationext["code"];
                    $namelocalisationext["fr"] = $rowlocalisationext["name1"];
                    $namelocalisationext["en"] = $rowlocalisationext["name2"];
                    $namelocalisationext["de"] = $rowlocalisationext["name3"];
                    $namelocalisationext["it"] = $rowlocalisationext["name4"];
                    $namelocalisationext["es"] = $rowlocalisationext["name5"];
                    $optionslocalisationext.="<option value=\"" . htmlspecialchars($codelocalisationext) . "\"";
                    if ($enreg['localisation'] == $codelocalisationext){
                        $optionslocalisationext.=" selected";
                        $localisationok = 1;
                    }
                    $optionslocalisationext.=">" . htmlspecialchars($namelocalisationext[$lang]) . "</option>\n";
                }
                echo "<optgroup label=\"" . __("Network libraries") . "\">\n";
                echo $optionslocalisationext;
            }
            if (0 == $localisationok){
                // Localization has not been found in current library. Fetch label from db if it exists
                $localisation_label = array($lang => $enreg['localisation']);
                $library_label = array($lang => "");
                $localization_label_query = "SELECT localizations.name1, localizations.name2, localizations.name3, localizations.name4, localizations.name5, libraries.name1 as library_name1, libraries.name2 as library_name2, libraries.name3 as library_name3, libraries.name4 as library_name4, libraries.name5 as library_name5 FROM localizations LEFT JOIN libraries ON localizations.library=libraries.code WHERE localizations.code = ?";
                $localization_label_result = dbquery($localization_label_query, array($enreg['localisation']), 's');
                while ($row_localisation_labels = iimysqli_result_fetch_array($localization_label_result)){
                    $localisation_label["fr"] = $row_localisation_labels["name1"];
                    $localisation_label["en"] = $row_localisation_labels["name2"];
                    $localisation_label["de"] = $row_localisation_labels["name3"];
                    $localisation_label["it"] = $row_localisation_labels["name4"];
                    $localisation_label["es"] = $row_localisation_labels["name5"];
                    $library_label["fr"] = $row_localisation_labels["library_name1"];
                    $library_label["en"] = $row_localisation_labels["library_name2"];
                    $library_label["de"] = $row_localisation_labels["library_name3"];
                    $library_label["it"] = $row_localisation_labels["library_name4"];
                    $library_label["es"] = $row_localisation_labels["library_name5"];
                }

                echo "<optgroup label=\"Others\">\n";
                echo "<option value=\"" . htmlspecialchars($enreg['localisation']) . "\" selected> " . htmlspecialchars($library_label[$lang]) . " - " . htmlspecialchars($localisation_label[$lang]) . " (" . htmlspecialchars($enreg['localisation'] . ")") . "</option>\n";
            }
            echo "</select>\n";
            // END Localization Field
            echo "</td></tr>";
            // Start Priority Field
            echo "<tr><td colspan=\"4\">\n";
			echo '<label for="urgent">';
            echo __("Priority") . "</label> : \n";
            echo "<select name=\"urgent\" id=\"urgent\" onchange=\"textchanged('priorite')\">\n";
            echo "<option value=\"2\"";
            if ($enreg['urgent']=='2')
                echo " selected";
            echo ">" . __("Normal") . "</option>\n";
            echo "<option value=\"1\"";
            if ($enreg['urgent']=='1')
                echo " selected";
            echo ">" . __("Urgent") . "</option>\n";
            echo "<option value=\"3\"";
            if ($enreg['urgent']=='3')
                echo " selected";
            echo ">" . __("Not a priority") . "</option>\n";
            echo "</select>\n";
            // END Priority Field

            // Start Origin Field
			if ($displayFormOrderSourceField) {
				echo "&nbsp;&nbsp;&nbsp;&nbsp;<label for=\"source\">" . __("Origin of the order") . "</label> : \n";
				echo "<select name=\"source\" id=\"source\" onchange=\"ajoutevaleur('source');\">\n";
				echo "<option value=\"\"> </option>\n";
				$reqsource = "SELECT arrivee FROM orders WHERE arrivee != '' GROUP BY arrivee ORDER BY arrivee ASC";
				$optionssource = "";
				$resultsource = dbquery($reqsource);
				while ($rowsource = iimysqli_result_fetch_array($resultsource)){
					$codesource = $rowsource["arrivee"];
					$optionssource.="<option value=\"" . htmlspecialchars($codesource) . "\"";
					if ($enreg['arrivee'] == $codesource)
						$optionssource.=" selected";
					$optionssource.=">" . htmlspecialchars($codesource) . "</option>\n";
				}
				echo $optionssource;
				echo "<option value=\"new\">" . __("Add new value...") . "</option>\n";
				echo "</select>\n";
				echo "&nbsp;<input name=\"sourcenew\" id=\"sourcenew\" type=\"text\" size=\"20\" value=\"\" style=\"display:none\">\n";
			}
            echo "</td></tr>\n";
            // END Origin Field
            // Start Dates
            echo "<tr><td>\n";
			echo '<label for="datesaisie">';
            echo "<a href=\"#\" title=\"" . __("to be completed only if different from the current date") . "\">" . __("Order date") . "</a></label> : </td><td> \n";
            echo "<input name=\"datesaisie\" id=\"datesaisie\" type=\"text\" size=\"10\" value=\"".htmlspecialchars($enreg['date'])."\" class=\"tcal\" onchange=\"textchanged('datesaisie')\">\n";
            echo "</td><td>\n";
			echo '<label for="envoye">';
            echo __("Date of shipment") . "</label> : </td><td>\n";
            echo "<input name=\"envoye\" id=\"envoye\" type=\"text\" size=\"10\" value=\"".htmlspecialchars($enreg['envoye'])."\" class=\"tcal\" onchange=\"textchanged('envoye')\">\n";
            echo "</td></tr><tr><td>\n";
			echo '<label for="facture">';
            echo __("Invoice date") . "</label> : </td><td>\n";
            echo "<input name=\"facture\" id=\"facture\" type=\"text\" size=\"10\" value=\"".htmlspecialchars($enreg['facture'])."\" class=\"tcal\" onchange=\"textchanged('facture')\">\n";
            echo "</td><td>\n";
			echo '<label for="renouveler">';

            echo __("To be renewed on") . "</label> : </td><td>\n";
            echo "<input name=\"renouveler\" id=\"renouveler\" type=\"text\" size=\"10\" value=\"".htmlspecialchars($enreg['renouveler'])."\" class=\"tcal\" onchange=\"textchanged('renouveler')\">\n";
            echo "</td></tr>\n";

            // END Dates
            // START Price Field and Internal references
            echo "<tr><td colspan=\"4\">\n";
			echo '<label for="prix">';
            echo format_string(__("Price (%currency)"), array('currency' => $currency)) . "</label> : &nbsp;\n";
            echo "<input name=\"prix\" id=\"prix\" type=\"text\" size=\"5\" value=\"".htmlspecialchars($enreg['prix'])."\" onchange=\"textchanged('prix')\">\n";
            echo "&nbsp;&nbsp;(<input type=\"checkbox\" name=\"avance\" id=\"avance\" value=\"on\"";
            if ($enreg['prepaye']=='on')
                echo " checked";
            echo " onclick=\"textchanged('prepaye')\"/><label for=\"avance\">" . __("order paid in advance") . "</label>) &nbsp;&nbsp;&nbsp;&nbsp;\n";
            echo "</td></tr>\n";
            echo "<tr><td colspan=\"4\">\n";
			echo '<label for="ref">';
            echo __("Provider Ref.") . "</label> : &nbsp;\n";
            echo "<input name=\"ref\" id=\"ref\" type=\"text\" size=\"20\" value=\"".htmlspecialchars($enreg['ref'])."\" onchange=\"textchanged('ref fournisseur')\">&nbsp;&nbsp;&nbsp;\n";
			if ($displayFormInternalRefField) {
				echo '<label for="refinterbib">';
				echo __("Internal ref. to the library") . "</label> : &nbsp;\n";
				echo "<input name=\"refinterbib\" id=\"refinterbib\" type=\"text\" size=\"20\" value=\"".htmlspecialchars($enreg['refinterbib'])."\" onchange=\"textchanged('ref interne')\">\n";
			} else {
				echo "<input name=\"refinterbib\" id=\"refinterbib\" type=\"hidden\" value=\"".htmlspecialchars($enreg['refinterbib'])."\" >\n";
			}
            echo "</td></tr>\n";
            // END Price Field and Internal references
            // Start Private Notes
            echo "<tr><td valign=\"top\">\n";
			echo '<label for="remarques">';
            echo __("Professional Notes") . "</label> : \n";
            echo "</td><td valign=\"bottom\" colspan=\"3\"><textarea name=\"remarques\" id=\"remarques\" rows=\"2\" cols=\"68\" valign=\"bottom\" onchange=\"textchanged('remarques')\">".htmlspecialchars($enreg['remarques'])."</textarea>\n";
            echo "</td></tr>\n";
            // END Private Notes
            echo "</table>\n";
            echo "</div></div>\n";
            echo "<div class=\"box-footer\"><div class=\"box-footer-right\"></div></div>\n";
            // END Management Fields
            // START User Fields
            echo "<div class=\"box\"><div class=\"box-content\">\n";
            echo "<table border=\"0\" cellspacing=\"0\" cellpadding=\"3\" width=\"100%\">\n";
            echo "<tr><td>\n";
			echo '<label for="nom">';
            echo __("Name") . " *</label> : </td><td><input name=\"nom\" id=\"nom\" type=\"text\" size=\"25\" value=\"".htmlspecialchars($enreg['nom'])."\" onchange=\"textchanged('nom')\"></td><td>\n";
            echo '<label for="prenom">';
			echo __("First name") . " *</label> : </td><td><input name=\"prenom\" id=\"prenom\" type=\"text\" size=\"25\" value=\"".htmlspecialchars($enreg['prenom'])."\" onchange=\"textchanged('prenom')\"><span>\n";
            if ($directoryurl1 != "")
                echo "&nbsp;<a href=\"javascript:directory('" . $directoryurl1 . "')\" title=\"" . __("Search the name in the directory of the hospital") . "\"><img src=\"img/directory1.png\"></a>\n";
            if ($directoryurl2 != "")
                echo "<a href=\"javascript:directory('" . $directoryurl2 . "')\" title=\"" . __("Search the name in the directory of the university") . "\"><img src=\"img/directory2.png\"></a>\n";
            echo "</span></td></tr><tr><td>\n";
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
            $unitsok = 0;
            echo "<select name=\"service\" id=\"service\" style=\"max-width:300px\" onchange=\"textchanged('service'); document.commande.servautre.value = '';\">\n";
            echo "<option value=\"\"></option>\n";
            if ($ip1 == 1) {
                $requnits="SELECT code, $unitsortlang FROM units WHERE internalip1display = 1 ORDER BY $unitsortlang ASC";
            } else if ($ip2 == 1) {
                    $requnits="SELECT code, $unitsortlang FROM units WHERE internalip2display = 1 ORDER BY $unitsortlang ASC";
			} else {
                    $requnits="SELECT code, $unitsortlang FROM units WHERE externalipdisplay = 1 ORDER BY $unitsortlang ASC";
			}
                $optionsunits="";
                $resultunits = dbquery($requnits);
                while ($rowunits = iimysqli_result_fetch_array($resultunits)){
                    $codeunits = $rowunits["code"];
                    $nameunits = $rowunits[$unitsortlang];
                    $optionsunits.="<option value=\"".htmlspecialchars($codeunits)."\" ";
                    if ($enreg['service'] == $codeunits){
                        $optionsunits.=" selected";
                        $unitsok = 1;
                    }
                    $optionsunits.=">" . htmlspecialchars($nameunits) . "</option>\n";
                }
                echo $optionsunits;
                echo "</select>\n";
                echo "</td><td>\n";
				echo '<label for="servautre">';
                echo __("Other unit") . "</label> : </td><td>\n";
                echo "<input name=\"servautre\" id=\"servautre\" type=\"text\" size=\"30\" value=\"";
                if ($unitsok == 0)
                    echo htmlspecialchars($enreg['service']);
                echo "\" onchange=\"textchanged('service autre')\">\n";
                echo "</td></tr>\n";
                if ($ip1 == 1){
                    echo "<tr><td>\n";
					echo '<label for="cgra">';
                    echo __("Budget heading") . "</label> : \n";
                    echo "</td><td>\n";
                    echo "<input name=\"cgra\" id=\"cgra\" type=\"text\" size=\"30\" value=\"".htmlspecialchars($enreg['cgra'])."\" onchange=\"textchanged('cgra')\"></td><td>\n";
                    echo '<label for="cgrb">';
					echo __("Budget subheading") . "</label> : </td><td>\n";
                    echo "<input name=\"cgrb\" id=\"cgrb\" type=\"text\" size=\"30\" value=\"".htmlspecialchars($enreg['cgrb'])."\" onchange=\"textchanged('cgrb')\">\n";
                    echo "</td></tr>\n";
                }
                else{
                    echo "<input name=\"cgra\" type=\"hidden\"  value=\"".htmlspecialchars($enreg['cgra'])."\">\n";
                    echo "<input name=\"cgrb\" type=\"hidden\"  value=\"".htmlspecialchars($enreg['cgrb'])."\">\n";
                }
                echo "<tr><td>\n";
				echo '<label for="mail">';
                echo __("E-Mail") . " *</label> : </td><td>\n";
                echo "<input name=\"mail\" id=\"mail\" type=\"text\" size=\"30\" value=\"".htmlspecialchars($enreg['mail'])."\" onchange=\"textchanged('email')\"></td><td>\n";
                echo '<label for="tel">';
				echo __("Tel.") . "</label> : </td><td>\n";
                echo "<input name=\"tel\" id=\"tel\" type=\"text\" size=\"30\" value=\"".htmlspecialchars($enreg['tel'])."\" onchange=\"textchanged('tel')\">\n";
                echo "</td></tr>\n";
                echo "<tr><td valign=\"top\">\n";
				echo '<label for="adresse">';
                echo __("Private address") . "</label> :\n";
                echo "</td><td>\n";
				echo "<input name=\"adresse\" id=\"adresse\" type=\"text\" size=\"30\" value=\"".htmlspecialchars($enreg['adresse'])."\" onchange=\"textchanged('adresse')\">\n";
                echo "</td><td>\n";
                echo '<label for="postal">';
				echo __("Zip code") . "</label> : </td><td>\n";
                echo "<input name=\"postal\" id=\"postal\" type=\"text\" size=\"5\" value=\"".htmlspecialchars($enreg['code_postal'])."\" onchange=\"textchanged('code postal')\">\n";
                echo "&nbsp;\n";
                echo '<label for="localite">';
				echo __("City") . "</label> :\n";
                echo "<input name=\"localite\" id=\"localite\" type=\"text\" size=\"7\" value=\"".htmlspecialchars($enreg['localite'])."\" onchange=\"textchanged('localite')\">\n";
                echo "</td></tr><tr><td valign=\"top\" colspan=\"4\">\n";
				echo __("If available at the library") . " : \n";
                echo "<input type=\"radio\" name=\"envoi\" id=\"envoimail\" value=\"mail\"";
                if ($enreg['envoi_par']=='mail')
                    echo " checked";
                echo " onclick=\"textchanged('envoi')\"/>\n";
				echo '<label for="envoimail">';
                echo __("send by e-mail (billed)") . "</label>&nbsp;\n";
                echo "<input type=\"radio\" name=\"envoi\" id=\"envoisurplace\" value=\"surplace\"";
                if ($enreg['envoi_par']=='surplace')
                    echo " checked";
                echo " onclick=\"textchanged('envoi')\"/>\n";
				echo '<label for="envoisurplace">';
                echo __("let me know and I come to make a copy (not billed)") . "</label>\n";
                echo "</td></tr>\n";
                echo "<tr>\n";
                echo "<td valign=\"top\" colspan=\"4\">\n";
                echo "<input type=\"checkbox\" name=\"cooc\" id=\"cooc\" value=\"on\" />\n";
				echo '<label for="cooc">';
                echo __("Remember data for future orders (cookies allowed)") . "</label>&nbsp;&nbsp;|&nbsp;&nbsp;(<A HREF=\"javascript:coocout()\">" . __("delete the cookie") . "</a>)\n";
                echo "</td></tr>\n";
                echo "</Table>\n";
                echo "\n";
                echo "</div></div>\n";
                echo "<div class=\"box-footer\"><div class=\"box-footer-right\"></div></div>\n";
                // END User Fields
                // START Document Fields
                echo "<div class=\"box\"><div class=\"box-content\">\n";
                echo "<div class=\"box\"><div class=\"box-content\">\n";
                echo "<center><b><label for=\"tid_0\">" . __("Fill in the order using") . "</label> </b>\n";
				$tid = "";
				$uids = "";
				if (!empty($enreg['PMID'])) {
					$uids = $enreg['PMID'];
					$tid = "pmid";
				} else if (!empty($enreg['doi'])) {
					$uids = $enreg['doi'];
					$tid = "doi";
				} else if (strpos($enreg['uid'], ":") !== false) {
					// retrieve value from uid
					$tid_and_uids = explode(":", $enreg['uid'], 2);
					$tid = $tid_and_uids[0];
					if ($tid == "WOSUT") {
						$tid = "WOSID";
					}
					if ($tid == "MMS") {
						$tid = "renouvaudmms_swissbib";
					}
					$uids = $tid_and_uids[1];
				}
                echo "<select name=\"tid_0\" id=\"tid_0\">\n";
                $i = 0;
                while ($lookupuid[$i]["name"]){
					$selected = "";
					if (strpos(strtolower($lookupuid[$i]["code"]), strtolower($tid), 0 ) === 0) {
						// best effort to select the right menu: code must start with uid prefix
                        $selected = " selected ";
					}
                    echo "<option value=\"" . htmlspecialchars($lookupuid[$i]["code"]) . '"' . $selected. ">" . htmlspecialchars($lookupuid[$i]["name"]) . "</option>\n";
                    $i = $i + 1;
                }
                echo "</select>\n";
                echo "<input name=\"uids_0\" type=\"text\" size=\"20\" value=\"". htmlspecialchars($uids). "\">\n";
                echo "<input type=\"button\" value=\"OK\" onclick=\"lookupid(0); textchanged('ref écrasée par PMID');\"></center>\n";
                echo "</div></div>\n";
                echo "<div class=\"box-footer\"><div class=\"box-footer-right\"></div></div>\n";
                echo "\n";
                echo "<table border=\"0\" cellspacing=\"0\" cellpadding=\"3\" width=\"100%\">\n";
                echo "<tr><td><label for=\"genre_0\">" . (((!empty($doctypesmessage)) && $doctypesmessage[$lang])? $doctypesmessage[$lang]:'Type de document') . "</label> : </td><td>\n";
                echo "<select name=\"genre_0\" id=\"genre_0\" onchange=\"textchanged('type_doc')\">\n";
                $i = 0;
                while ($doctypes[$i]["code"]){
                    echo "<option value=\"" . htmlspecialchars($doctypes[$i]["code"]) . "\"";
                    if ($enreg['type_doc']==$doctypes[$i]["code"])
                        echo " selected";
                    echo ">" . htmlspecialchars($doctypes[$i]["name"]) . "</option>\n";
                    $i = $i + 1;
                }
                echo "</select>\n";
                echo "<div class=\"formdoc\">\n";
                echo "</td></tr><tr><td>\n";
				echo '<label for="title_0">';
                echo __("Title of journal or book") . " *</label> : </td><td>\n";
                echo "<input name=\"title_0\" id=\"title_0\" type=\"text\" size=\"80\" value=\"".htmlspecialchars($enreg['titre_periodique'])."\" onchange=\"textchanged('titre_periodique')\">\n";
                echo "&nbsp;\n";
                echo "<a href=\"javascript:openlist('".$periodical_title_search_url."', '0')\"><img src=\"img/find.png\" title=\"" . __("check on journals database") . "\"></a>\n";
                echo "</td></tr><tr><td>\n";
				echo '<label for="date_0">';
                echo __("Year") . " *</label> : </td><td>\n";
                echo "<input name=\"date_0\" id=\"date_0\" type=\"text\" size=\"3\" value=\"".htmlspecialchars($enreg['annee'])."\" onchange=\"textchanged('date')\">\n";
                echo "&nbsp;\n";
				echo '<label for="volume_0">';
                echo __("Vol.") . " *</label> : \n";
                echo "<input name=\"volume_0\" id=\"volume_0\" type=\"text\" size=\"3\" value=\"".htmlspecialchars($enreg['volume'])."\" onchange=\"textchanged('volume')\">\n";
                echo "&nbsp;\n";
				echo '<label for="issue_0">';
                echo __("(Issue)") . "</label> : \n";
                echo "<input name=\"issue_0\" id=\"issue_0\" type=\"text\" size=\"3\" value=\"".htmlspecialchars($enreg['numero'])."\" onchange=\"textchanged('numero')\">\n";
                echo "&nbsp;\n";
				echo '<label for="suppl_0">';
                echo __("Suppl.") . "</label> : \n";
                echo "<input name=\"suppl_0\" id=\"suppl_0\" type=\"text\" size=\"3\" value=\"".htmlspecialchars($enreg['supplement'])."\" onchange=\"textchanged('suppl')\">\n";
                echo "&nbsp;\n";
				echo '<label for="pages_0">';
                echo __("Pages") . " *</label> : \n";
                echo "<input name=\"pages_0\" id=\"pages_0\" type=\"text\" size=\"4\" value=\"".htmlspecialchars($enreg['pages'])."\" onchange=\"textchanged('pages')\">\n";
                echo "</td></tr><tr><td>\n";
				echo '<label for="atitle_0">';
                echo __("Title of article or book chapter") . "</label> : \n";
                echo "</td><td>\n";
                $titreart = $enreg['titre_article'];
                echo "<input name=\"atitle_0\" id=\"atitle_0\" type=\"text\" size=\"80\" value=\"".htmlspecialchars($titreart)."\" onchange=\"textchanged('titre_article')\">\n";
                echo "</td></tr><tr><td>\n";
				echo '<label for="auteurs_0">';
                echo __("Author(s)") . "</label> : \n";
                echo "</td><td>\n";
                echo "<input name=\"auteurs_0\" id=\"auteurs_0\" type=\"text\" size=\"80\" value=\"".htmlspecialchars($enreg['auteurs'])."\" onchange=\"textchanged('auteurs')\">\n";
                echo "</td></tr>\n";
                echo "<tr><td>\n";
				echo '<label for="edition_0">';
                echo __("Edition (for books)") . "</label> : \n";
                echo "</td><td>\n";
                echo "<input name=\"edition_0\" id=\"edition_0\" type=\"text\" size=\"14\" value=\"".htmlspecialchars($enreg['edition'])."\" onchange=\"textchanged('edition')\">\n";
                echo "&nbsp;\n";
                echo "<label for=\"issn_0\">ISSN / ISBN</label> : \n";
                echo "<input name=\"issn_0\" id=\"issn_0\" type=\"text\" size=\"15\" value=\"";
                if ($enreg['isbn']!="")
                    echo htmlspecialchars($enreg['isbn']);
                else {
                    echo $enreg['issn'];
                    if ($enreg['eissn']!="")
                        echo ",".htmlspecialchars($enreg['eissn']);
                }
                echo "\" onchange=\"textchanged('issn')\">\n";
                echo "&nbsp;\n";
                echo "<label for=\"uid_0\">UID</label> : \n";
                echo "<input name=\"uid_0\" id=\"uid_0\" type=\"text\" size=\"15\" value=\"".$enreg['uid']."\" onchange=\"textchanged('uid')\">\n";
                echo "</td></tr></div>\n";
                echo "<tr><td valign=\"top\">\n";
				echo '<label for="remarquespub_0">';
                echo __("Notes") . "</label> : \n";
                echo "</td><td valign=\"bottom\"><textarea name=\"remarquespub_0\" id=\"remarquespub_0\" rows=\"2\" cols=\"60\" valign=\"bottom\" onchange=\"textchanged('remarquespub')\">".htmlspecialchars($enreg['remarquespub'])."</textarea>\n";
                echo "</td></tr><tr><td></td><td>\n";
                echo "</td></tr>\n";
                echo "</table>\n";
                echo "</div></div>\n";
                echo "<div class=\"box-footer\"><div class=\"box-footer-right\"></div></div>\n";
                // END Document Fields
				// BEGIN SUBMISSION BUTTONS
				echo '<div class="box-submit-buttons">';
				echo "<input type=\"submit\" value=\"" . __("Submit") . "\" onsubmit=\"javascript:okcooc();document.body.style.cursor = 'wait';\">&nbsp;&nbsp;\n";
                echo "<input type=\"reset\" value=\"" . __("Reset") . "\">&nbsp;&nbsp;\n";
                if ($monaut == "sadmin") {
                    echo "<input type=\"button\" value=\"Supprimer definitivement cette commande\" onClick=\"self.location='update.php?action=delete&amp;table=orders&amp;id=" . htmlspecialchars($id) . "'\">\n";
                }
				echo "</div>";
				// END SUBMISSION BUTTONS
				echo "</form>\n";
                echo "</td></tr></table>\n";
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
