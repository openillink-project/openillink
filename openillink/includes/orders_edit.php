<?php
// ***************************************************************************
// ***************************************************************************
// ***************************************************************************
// This file is part of OpenILLink software.
// Copyright (C) 2005, 2006, 2007, 2008, 2009, 2010, 2011, 2012, 2013, 2015, 2016, 2017, 2018, 2020, 2023, 2024 CHUV.
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
require_once ("toolkit.php");

if (($monaut == "admin")||($monaut == "sadmin")||($monaut == "user")){
    $id= ((!empty($_GET['id'])) && isValidInput($_GET['id'],8,'s',false)) ? $_GET['id'] : NULL;
    $myhtmltitle = format_string(__("%institution_name: order %order_id edition"), array('institution_name' => $configinstitution[$lang], 'order_id' => $id));
    if ($id){
        $req = "select * from orders where illinkid = ? order by illinkid desc";
        $result = dbquery($req, array($id), 'i');
        $nb = iimysqli_num_rows($result);
        require ("headeradmin.php");
        $config_display_delivery_choice = isset($config_display_delivery_choice) ? $config_display_delivery_choice : true;
        $config_display_cgr_fields = isset($config_display_cgr_fields) ? $config_display_cgr_fields : false;
		echo '<script type="text/javascript">var referer="";</script>';
        for ($num_result=0 ; $num_result<$nb ; $num_result++){
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
            echo "<form action=\"update.php\" method=\"POST\" enctype=\"multipart/form-data\" name=\"commande\">\n";
            echo '<section class="message">
	<div class="message-body orderEditAttributedLibraryBox">';
			echo "<input name=\"id\" type=\"hidden\"  value=\"".htmlspecialchars($id)."\">\n";
            echo "<input name=\"userid\" type=\"hidden\"  value=\"".htmlspecialchars($enreg['saisie_par'])."\">\n";
            echo "<input name=\"ip\" type=\"hidden\"  value=\"".htmlspecialchars($enreg['ip'])."\">\n";
// echo "<input name=\"referer\" type=\"hidden\"  value=\"".$enreg['referer']."\">\n";
            echo "<input name=\"doi\" type=\"hidden\"  value=\"".htmlspecialchars($enreg['doi'])."\">\n";
            echo "<input name=\"historique\" type=\"hidden\"  value=\"".htmlspecialchars($enreg['historique'])."\">\n";
			echo '<div class="field is-horizontal">';
            echo "<b><font color=\"red\">".format_string(__("Order %order_id modification"), array('order_id' => htmlspecialchars($id)))."</font></b>\n";
            echo "<input name=\"action\" type=\"hidden\" value=\"update\">\n";
            echo "<input name=\"modifs\" type=\"hidden\" value=\"\">\n";
            echo "&nbsp;&nbsp;|&nbsp;&nbsp;<label class=\"label field-label\" style=\"margin-bottom:0\" for=\"bibliotheque\">".__("Attributed to library")."</label>";
			echo '<div class="control has-icons-left">';
			echo '<div class="select">';
			echo '<select name="bibliotheque" id="bibliotheque" onchange="textchanged(\'bibliotheque\')">';
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
			echo '</div>
				<div class="icon is-small is-left">
				<i class="fas fa-university"></i>
				</div>
				</div>';
            echo "&nbsp;&nbsp;|&nbsp;&nbsp;<input type=\"submit\" class=\"button\" value=\"" . __("Submit") . "\" onsubmit=\"javascript:okcooc();document.body.style.cursor = 'wait';\">\n";
			echo '</div>'; // end field
            echo '</div><!-- end message-body -->
	</section><!-- end message -->';
			// START Management Fields
			echo '<section class="message">
	<div class="message-body">';
			echo '<div class="columns is-gapless is-columns-form">
  <div class="column is-one-fifth">';
            // Begin Status Field
			echo '<div class="field is-horizontal">';
			echo '<label class="label field-label is-normal required" for="stade">';
            echo __("Status") . "</label>\n";
			echo '</div></div>
	 <div class="column is-3">';
			echo '<div class="control is-expanded has-icons-left">';
			echo '<div class="select is-fullwidth">';
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
	echo '</div>
	<div class="icon is-small is-left">
      <i class="fas fa-code-branch"></i>
    </div>
	</div></div>';

            // END Status Field
            // Begin Localization Field
			echo '<div class="column is-3">';
			echo '<div class="field is-horizontal">';
            $localisationok = 0;
            echo "&nbsp;&nbsp;&nbsp;&nbsp;\n";
			echo '<label class="label field-label is-normal" for="localisation">';
            echo __("Localization") . "</label>\n";
            echo '</div></div>
	 <div class="column is-3">';
			echo '<div class="control is-expanded has-icons-left">';
			echo '<div class="select is-fullwidth">';
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
			echo '</div>
	<div class="icon is-small is-left">
      <i class="fas fa-map-marker-alt"></i>
    </div>
	</div></div>
	</div>';
            // END Localization Field
            // Start Priority Field
			echo '<div class="columns is-gapless is-columns-form">
  <div class="column is-one-fifth">';
			echo '<div class="field is-horizontal">';
			echo '<label class="label field-label is-normal" for="urgent">';
            echo __("Priority") . "</label>\n";
			echo '</div></div>
	 <div class="column is-3">';
	echo '<div class="control is-expanded has-icons-left">';
	echo '<div class="select is-fullwidth">';
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
			echo '</div>
		<div class="icon is-small is-left">
      <i class="fas fa-flag"></i>
    </div>
	</div></div>';
            // END Priority Field

            // Start Origin Field
			if ($displayFormOrderSourceField) {
				echo '<div class="column is-3">';
				echo '<div class="field is-horizontal">';
				echo "<label class=\"label field-label is-normal\" for=\"source\">" . __("Origin of the order") . "</label>\n";
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
					$optionssource.="<option value=\"" . htmlspecialchars($codesource) . "\"";
					if ($enreg['arrivee'] == $codesource)
						$optionssource.=" selected";
					$optionssource.=">" . htmlspecialchars($codesource) . "</option>\n";
				}
				echo $optionssource;
				echo "<option value=\"new\">" . __("Add new value...") . "</option>\n";
				echo "</select>\n";
				echo '</div>
	<div class="icon is-small is-left">
      <i class="fas fa-globe"></i>
    </div>
		</div></div>
	 <div class="column is-2">';
				echo '<div class="control">';
				echo "<input class=\"input\" name=\"sourcenew\" id=\"sourcenew\" type=\"text\" size=\"20\" value=\"\" style=\"display:none\">\n";
				echo '</div></div>
		</div>';
			}
            // END Origin Field
            // Start Dates
			echo '<div class="columns is-gapless is-columns-form">
  <div class="column is-one-fifth">';
			echo '<div class="field is-horizontal">';
			echo '<label class="label field-label is-normal" for="datesaisie">';
            echo "<a href=\"#\" title=\"" . __("to be completed only if different from the current date") . "\">" . __("Order date") . "</a></label>\n";
            echo '</div></div>
	 <div class="column is-3">';
			echo '<div class="control has-icons-left">';
			echo "<input class=\"input tcal\" autocomplete=\"off\" name=\"datesaisie\" id=\"datesaisie\" type=\"text\" size=\"10\" value=\"".htmlspecialchars($enreg['date'] ? $enreg['date'] : "")."\" class=\"tcal\" onchange=\"textchanged('datesaisie')\">\n";
			echo '<div class="icon is-small is-left">
      <i class="fas fa-download"></i>
    </div>';
			echo '</div></div>
	 <div class="column is-3">';
			echo '<div class="field is-horizontal">';
			echo '<label class="label field-label is-normal" for="envoye">';
            echo __("Date of shipment") . "</label> \n";
			echo '</div></div>
	 <div class="column is-3">';
			echo '<div class="control has-icons-left">';
            echo "<input class=\"input tcal\" name=\"envoye\" id=\"envoye\" type=\"text\" size=\"10\" value=\"".htmlspecialchars($enreg['envoye'] ? $enreg['envoye'] : "")."\" class=\"tcal\" onchange=\"textchanged('envoye')\">\n";
            echo '<div class="icon is-small is-left">
      <i class="fas fa-upload"></i>
    </div>';
			echo '</div></div>
	</div>
	<div class="columns is-gapless is-columns-form">
	 <div class="column is-one-fifth">';
			echo '<div class="field is-horizontal">';
			echo '<label class="label field-label is-normal" for="facture">';
			echo __("Invoice date") . "</label>\n";
            echo '</div></div>
	 <div class="column is-3">';
	echo '<div class="control has-icons-left">';
			echo "<input class=\"input tcal\" autocomplete=\"off\" name=\"facture\" id=\"facture\" type=\"text\" size=\"10\" value=\"".htmlspecialchars($enreg['facture'] ? $enreg['facture'] : "")."\"  onchange=\"textchanged('facture')\">\n";
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
			echo "<input class=\"input tcal\" autocomplete=\"off\" name=\"renouveler\" id=\"renouveler\" type=\"text\" size=\"10\" value=\"".htmlspecialchars($enreg['renouveler'] ? $enreg['renouveler'] : "")."\" onchange=\"textchanged('renouveler')\">\n";
			echo '<div class="icon is-small is-left">
      <i class="fas fa-bell"></i>
    </div>';
			echo '</div></div></div>';
            // END Dates
            // START Price Field and Internal references
			echo '<div class="columns is-gapless is-columns-form">
  <div class="column is-one-fifth">';
			echo '<div class="field is-horizontal">';
			echo '<label class="label field-label is-normal" for="prix">';
            echo format_string(__("Price (%currency)"), array('currency' => $currency)) . "</label>\n";
            echo '</div></div>
	 <div class="column is-3">';
			echo '<div class="control has-icons-left">';
			echo "<input class=\"input\" name=\"prix\" id=\"prix\" type=\"text\" size=\"5\" value=\"".htmlspecialchars($enreg['prix'] ? $enreg['prix'] : "")."\" onchange=\"textchanged('prix')\">\n";
            echo '<div class="icon is-small is-left">
      <i class="far fa-money-bill-alt"></i>
    </div></div>';
			echo '</div><div class="column is-two-fifth">';
			echo '<div class="field">';
			echo '<div class="control">';
			echo "&nbsp;&nbsp;(<label class=\"checkbox\"> <input type=\"checkbox\" name=\"avance\" id=\"avance\" value=\"on\"";
            if ($enreg['prepaye']=='on')
                echo " checked";
            echo " onclick=\"textchanged('prepaye')\">\n" . __("order paid in advance") . "\n</label>) &nbsp;&nbsp;&nbsp;&nbsp;\n";
			echo '</div></div></div>
	</div>
	 <div class="columns is-gapless is-columns-form">
  <div class="column is-one-fifth">';
			echo '<div class="field is-horizontal">';
			echo '<label class="label field-label is-normal" for="ref">';
            echo __("Provider Ref.") . "</label> \n";
            echo '</div></div>
	 <div class="column is-3">';
			echo '<div class="control has-icons-left">';
			echo "<input class=\"input\" name=\"ref\" id=\"ref\" type=\"text\" size=\"20\" value=\"".htmlspecialchars($enreg['ref'] ? $enreg['ref'] : "")."\" onchange=\"textchanged('ref fournisseur')\">\n";
			echo '<div class="icon is-small is-left">
      <i class="fas fa-barcode"></i>
    </div>';
			echo '</div></div>';
			if ($displayFormInternalRefField) {
				echo '<div class="column is-3">';
				echo '<div class="field is-horizontal">';
				echo '<label class="label field-label is-normal" for="refinterbib">';
				echo __("Internal ref. to the library") . "</label>\n";
				echo '</div></div>
	 <div class="column is-3">';
				echo '<div class="control has-icons-left">';
				echo "<input class=\"input\" name=\"refinterbib\" id=\"refinterbib\" type=\"text\" size=\"20\" value=\"".htmlspecialchars($enreg['refinterbib'] ? $enreg['refinterbib'] : "")."\" onchange=\"textchanged('ref interne')\">\n";
				echo '<div class="icon is-small is-left">
      <i class="fas fa-tag"></i>
    </div></div></div>';
			} else {
				echo "<input name=\"refinterbib\" id=\"refinterbib\" type=\"hidden\" value=\"".htmlspecialchars($enreg['refinterbib'] ? $enreg['refinterbib'] : "")."\" >\n";
			}
            // END Price Field and Internal references
            // Start Private Notes
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
			echo "<textarea class=\"textarea\" name=\"remarques\" id=\"remarques\" rows=\"2\" cols=\"68\" valign=\"bottom\" onchange=\"textchanged('remarques')\">".htmlspecialchars($enreg['remarques'] ? $enreg['remarques'] : "")."</textarea>\n";
            // END Private Notes
			echo '</div></div>';
			echo '</div>'; // end columns
			echo '</div><!-- end message-body -->
	</section><!-- end message -->';

            echo "<div class=\"box-footer\"><div class=\"box-footer-right\"></div></div>\n";
            // END Management Fields

			// START User Fields
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
        <div class="control has-icons-left">';
            echo "<input name=\"nom\" id=\"nom\" class=\"input\" type=\"text\" size=\"25\" value=\"".htmlspecialchars($enreg['nom'])."\" onchange=\"textchanged('nom')\" required>\n";
            echo ' <span class="icon is-small is-left">
			<i class="fas fa-user"></i>
         </span>
        </div>
    </div>
	<div class="column is-2">
		<div class="field is-horizontal">
		<label class="label field-label is-normal required" for="prenom">';
			echo __("First name") . ' </label>
			</div>
	</div>
	<div class="column is-one-quarter">
        <div class="control">
		<input name="prenom" id="prenom" class="input" type="text" size="25" value="'.htmlspecialchars($enreg['prenom']).'" onchange="textchanged(\'prenom\')" required>
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
if ($directoryurl2 != ""){
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
		';
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
            echo "<select name=\"service\" id=\"service\" onchange=\"textchanged('service'); document.commande.servautre.value = '';\">\n";
            echo "<option value=\"\">&nbsp;</option>\n";
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
				echo '       </div>
	   <span class="icon is-small is-left">
		<i class="fas fa-sitemap"></i>
        </span>
	   </div>
	</div>
	<div class="column is-2">
		<div class="field is-horizontal">
		<label class="label field-label is-normal" for="servautre">';
                echo __("Other unit") . "</label>";
				echo '</div>
	</div>
	<div class="column is-one-quarter">
        <div class="control">';
                echo "<input name=\"servautre\" class=\"input\" id=\"servautre\" type=\"text\" size=\"30\" value=\"";
                if ($unitsok == 0)
                    echo htmlspecialchars($enreg['service'] ? $enreg['service'] : "");
                echo "\" onchange=\"textchanged('service autre')\">\n";
                echo " </div>
    </div>
	</div>";
                if ($ip1 == 1 && $config_display_cgr_fields){
					echo '<div class="columns is-gapless is-columns-form">
    <div class="column is-2">
	  <div class="field is-horizontal">
      <label class="label field-label is-normal" for="cgra">';
                    echo __("Budget heading") . "</label>";
                    echo '</div>
	</div>
	<div class="column is-one-quarter">
        <div class="control">';
                    echo "<input name=\"cgra\" id=\"cgra\" class=\"input\" type=\"text\" size=\"30\" value=\"".htmlspecialchars($enreg['cgra'] ? $enreg['cgra'] : "")."\" onchange=\"textchanged('cgra')\">\n";

					echo '</div>
    </div>
	<div class="column is-2">
		<div class="field is-horizontal">
	    <label class="label field-label is-normal" for="cgrb">';
					echo __("Budget subheading") . "</label>\n";
                    echo '</div>
	</div>
	<div class="column is-one-quarter">
        <div class="control">';
					echo "<input name=\"cgrb\"  class=\"input\" id=\"cgrb\" type=\"text\" size=\"30\" value=\"".htmlspecialchars($enreg['cgrb'] ? $enreg['cgrb'] : "")."\" onchange=\"textchanged('cgrb')\">\n";
					echo '        </div>
    </div>
    </div>';
				}
                else{
                    echo "<input name=\"cgra\" type=\"hidden\"  value=\"".htmlspecialchars($enreg['cgra'] ? $enreg['cgra'] : "")."\">\n";
                    echo "<input name=\"cgrb\" type=\"hidden\"  value=\"".htmlspecialchars($enreg['cgrb'] ? $enreg['cgrb'] : "")."\">\n";
                }
				echo '	<div class="columns is-gapless is-columns-form">
    <div class="column is-2">
	  <div class="field is-horizontal">
      <label class="label field-label is-normal" for="mail">';
                echo __("E-Mail") . "</label>\n";
				echo '</div>
	</div>
	<div class="column is-one-quarter">
        <div class="control has-icons-left">';
                echo "<input name=\"mail\" id=\"mail\" class=\"input\" type=\"text\" size=\"30\" value=\"".htmlspecialchars($enreg['mail'] ? $enreg['mail'] : "")."\" onchange=\"textchanged('email')\">\n";
                echo '<span class="icon is-small is-left">
			<i class="fas fa-envelope"></i>
         </span>
        </div>
    </div>
	<div class="column is-2">
		<div class="field is-horizontal">
		<label class="label field-label is-normal" for="tel">';
				echo __("Tel.") . "</label>\n";
				echo '
		</div>
	</div>
	<div class="column is-one-quarter">
        <div class="control has-icons-left">';
                echo "<input name=\"tel\" class=\"input\" id=\"tel\" type=\"text\" size=\"30\" value=\"".htmlspecialchars($enreg['tel'] ? $enreg['tel'] : "")."\" onchange=\"textchanged('tel')\">\n";
                echo '<span class="icon is-small is-left">
			<i class="fas fa-phone"></i>
         </span>
        </div>
       </div>
      </div>';
                echo '<div class="columns is-gapless is-columns-form">
    <div class="column is-2">
	  <div class="field is-horizontal">
      <label class="label field-label is-normal" for="adresse">';
                echo __("Private address") . "</label>";
                echo '</div>
	</div>
	<div class="column is-one-quarter">
        <div class="control has-icons-left">';
				echo "<input name=\"adresse\" class=\"input\" id=\"adresse\" type=\"text\" size=\"30\" value=\"".htmlspecialchars($enreg['adresse'] ? $enreg['adresse'] : "")."\" onchange=\"textchanged('adresse')\">\n";
                echo '<span class="icon is-small is-left">
			<i class="fas fa-home"></i>
         </span>
        </div>
    </div>
    <div class="column is-2">
	  <div class="field is-horizontal">
      <label class="label field-label is-normal" for="postal">';
				echo __("Zip code") . "</label>";
				echo '</div>
	</div>
	<div class="column is-1">
        <div class="control">';
                echo "<input name=\"postal\" id=\"postal\" class=\"input\" type=\"text\" size=\"5\" value=\"".htmlspecialchars($enreg['code_postal'] ? $enreg['code_postal'] : "")."\" onchange=\"textchanged('code postal')\">\n";
                echo '</div>
    </div>
    <div class="column is-narrow">
	  <div class="field is-horizontal" style="display:block">
      <label class="label field-label is-normal" for="localite">&nbsp;&nbsp;&nbsp;&nbsp;';
				echo __("City") . "</label>";
				echo '</div>
	</div>
	<div class="column is-2">
        <div class="control">';
                echo "<input name=\"localite\" id=\"localite\"  class=\"input\" type=\"text\" size=\"7\" value=\"".htmlspecialchars($enreg['localite'] ? $enreg['localite'] : "")."\" onchange=\"textchanged('localite')\">\n";
                echo '</div>
    </div>
    </div>
';
if ($config_display_delivery_choice) {
    echo'
	<div class="columns is-gapless is-columns-form">
    <div class="column is-3">
	  <div class="field is-horizontal">
      <label class="label field-label is-normal" for="envoi">'.__("If available at the library:").'</label>
	  </div>
	</div>
	<div class="column is-9">
		<div class="field is-horizontal">
        <div class="control">
         <label class="radio field-label is-normal">';
                echo "<input type=\"radio\" name=\"envoi\" id=\"envoimail\" value=\"mail\"";
                if ($enreg['envoi_par']=='mail')
                    echo " checked";
                echo " onclick=\"textchanged('envoi')\"/>\n" .__("Send by e-mail (billed)");
				echo '</label>';
				echo '<label class="radio field-label is-normal">';
                echo "<input type=\"radio\" name=\"envoi\" id=\"envoisurplace\" value=\"surplace\"";
                if ($enreg['envoi_par']=='surplace')
                    echo " checked";
                echo " onclick=\"textchanged('envoi')\"/>\n";
                echo __("let me know and I come to make a copy (not billed)") . "</label>\n";
                echo ' </div>
       </div>
	   </div>
      </div>';
}
	  echo '<div class="columns is-gapless is-columns-form">
    <div class="column is-2">
		&nbsp;
	</div>
	<div class="column is-10">
	  <div class="field is-horizontal">
      <div class="field-body">
       <div class="field">
        <div class="control">
         <label class="checkbox">
          <input id="cooc" name="cooc" type="checkbox" value="on">
          '.__("Remember data for future orders (cookies allowed)").' | (<a href="javascript:coocout()">'. __("delete the cookie") .'</a>)
        </label>
        </div>
       </div>
      </div>
	  </div>

	</div>
	</div>
  </div>
</section>';
                // END User Fields
                // START Document Fields
				echo '<section class="message">
		<div class="message-header">
		<div class="container">
	<div class="columns is-vcentered">
	  <div class="column has-text-right is-two-fifths">
       <label class="has-text-white" for="tid_0">'.  __("Fill in the order using") .'</label>
	   </div><!-- end first column-->
	   <div class="column">
	   <div class="field-body">
        <div class="field has-addons">
         <div class="control">
          <span class="select is-fullwidth">
			<select id="tid_0" name="tid_0">';
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
                echo '</select>
				</span>
         </div>
         <div class="control"><input class="input" name="uids_0" placeholder="'. __("Identifier") .'" type="text" value="'.htmlspecialchars($uids).'"></div>
         <div class="control"><input class="button is-primary" onclick="lookupid(0, \''.htmlspecialchars($configemail).'\'); textchanged(\'ref écrasée par PMID\');" type="button" value="'. __("Fill in") .'"></div>
        </div>
       </div>

	   </div > <!-- end second .column -->
	   </div> <!-- end .columns -->
	   </div> <!-- end .container -->
	   </div> <!-- end message-header -->
    <div class="message-body">
	<div class="columns is-gapless is-columns-form">
    <div class="column is-one-fifth">
	<div class="field is-horizontal">';

                echo '<label class="label field-label is-normal" for="genre_0">' . (((!empty($doctypesmessage)) && $doctypesmessage[$lang])? $doctypesmessage[$lang]:'Type de document') . "</label>\n";
                echo '	</div>
	</div>
	<div class="column is-one-quarter">
        <div class="control">
         <div class="select is-fullwidth">
          <select id="genre_0" name="genre_0" onchange="textchanged(\'type_doc\')">';
                $i = 0;
                while ($doctypes[$i]["code"]){
                    echo "<option value=\"" . htmlspecialchars($doctypes[$i]["code"]) . "\"";
                    if ($enreg['type_doc']==$doctypes[$i]["code"])
                        echo " selected";
                    echo ">" . htmlspecialchars($doctypes[$i]["name"]) . "</option>\n";
                    $i = $i + 1;
                }
				echo '</select>
         </div>
        </div>
       </div>
	</div>

	<div class="columns is-gapless is-columns-form">
    <div class="column is-one-fifth">
	  <div class="field is-horizontal">
      <label class="label field-label is-normal required" for="title_0">'.__("Journal / Book").'</label>
	  </div>
	</div>	<div class="column is-three-quarters">
        <div class="control">
         <input class="input" id="title_0" name="title_0" type="text" value="'.htmlspecialchars($enreg['titre_periodique']).'" placeholder="'.__("Journal or book title").'" onchange="textchanged(\'titre_periodique\');resolve(0, 1);">
        </div>
    </div>
	<div class="column is-2">
	<div class="field is-horizontal">
	<span class="buttons label field-label is-normal has-text-left"> &nbsp;
       <a href="javascript:openlist(\''.$periodical_title_search_url.'\', \'0\')"><span class="is-light" title="'. __("check on journals database") .'"><i aria-hidden="true" class="fa fa-search fa-lg"></i></span></a>
     </span>
	 </div>
      </div>
	  </div>
	  	<div class="columns is-gapless is-columns-form">
    <div class="column is-one-fifth">
	  <div class="field is-horizontal">
      <label class="label field-label is-normal" for="date_0">'.__('Year').'</label>
	  </div>
	</div>
	<div class="column is-1">
        <div class="control">
         <input class="input" id="date_0" name="date_0" type="text" value="'.htmlspecialchars($enreg['annee']).'" onchange="textchanged(\'date\');resolve(0, 1);">
        </div>
    </div>
     <div class="column is-1">
	  <div class="field is-horizontal">
      <label class="label field-label is-normal" for="volume_0">'.__('Vol.').'</label>
	  </div>
	</div>
	<div class="column is-1">
        <div class="control">
         <input class="input" id="volume_0" name="volume_0" type="text" value="'.htmlspecialchars($enreg['volume']).'" onchange="textchanged(\'volume\');resolve(0, 1);">
        </div>
    </div>
	     <div class="column is-1">
	  <div class="field is-horizontal">
      <label class="label field-label is-normal" for="issue_0">'.__('Issue').'</label>
	  </div>
	</div>
	<div class="column is-1">
        <div class="control">
         <input class="input" id="issue_0" name="issue_0" type="text" value="'.htmlspecialchars($enreg['numero']).'" onchange="textchanged(\'numero\');resolve(0, 1);">
       </div>
    </div>
     <div class="column is-1">
	  <div class="field is-horizontal">
      <label class="label field-label is-normal" for="suppl_0">'.__('Suppl.').'</label>
	  </div>
	</div>
	<div class="column is-1">
        <div class="control">
         <input class="input" id="suppl_0" name="suppl_0" type="text" value="'.htmlspecialchars($enreg['supplement']).'" onchange="textchanged(\'suppl\');resolve(0, 1);">
       </div>
    </div>
     <div class="column is-1">
	  <div class="field is-horizontal">
      <label class="label field-label is-normal" for="pages_0">'.__('Pages').'</label>
	  </div>
	</div>
	<div class="column is-1">
        <div class="control">
         <input class="input" id="pages_0" name="pages_0" type="text" value="'.htmlspecialchars($enreg['pages']).'" onchange="textchanged(\'pages\');resolve(0, 1);">
        </div>
       </div>
      </div>


	<div class="columns is-gapless is-columns-form">
    <div class="column is-one-fifth">
	  <div class="field is-horizontal">
      <label class="label field-label is-normal" for="atitle_0">'.__("Title").'</label>
      	  </div>
	</div>
	<div class="column is-three-quarters">
        <div class="control">
         <input class="input" id="atitle_0" name="atitle_0" type="text" value="'.htmlspecialchars($enreg['titre_article']).'" placeholder="'.__("Article or chapter title").'" onchange="textchanged(\'titre_article\');resolve(0, 1);">
        </div>
       </div>
      </div>

	<div class="columns is-gapless is-columns-form">
    <div class="column is-one-fifth">
	  <div class="field is-horizontal">
      <label class="label field-label is-normal" for="auteurs_0">'.__("Author(s)").'</label>
      </div>
	</div>
	<div class="column is-three-quarters">
        <div class="control">
         <input class="input" id="auteurs_0" name="auteurs_0" type="text" value="'.htmlspecialchars($enreg['auteurs']).'" onchange="textchanged(\'auteurs\');resolve(0, 1);">
        </div>
       </div>
      </div>

	<div class="columns is-gapless is-columns-form">
    <div class="column is-one-fifth">
	  <div class="field is-horizontal">
      <label class="label field-label is-normal" for="edition_0">'.__("Edition").'</label>
 	  </div>
	</div>
	<div class="column is-2">
        <div class="control">
         <input class="input" id="edition_0" name="edition_0" type="text" value="'.htmlspecialchars($enreg['edition']).'" placeholder="'.__("(for books)").'" onchange="textchanged(\'edition\')">
        </div>
    </div>
     <div class="column is-2">
	  <div class="field is-horizontal">
      <label class="label field-label is-normal" for="issn_0">'.__('ISSN / ISBN').'</label>
	  </div>
	</div>
	<div class="column is-2">
        <div class="control">
         <input class="input" id="issn_0" name="issn_0" type="text"  value="'. ($enreg['isbn']!="" ? htmlspecialchars($enreg['isbn']) : $enreg['issn'] . ($enreg['eissn']!="" ? ",".htmlspecialchars($enreg['eissn']) : "")).'" onchange="textchanged(\'issn\');resolve(0, 1);">
		</div>
    </div>
     <div class="column is-1">
	  <div class="field is-horizontal">
      <label class="label field-label is-normal" for="uid_0">'.__('UID').'</label>
	  </div>
	</div>
	<div class="column is-2">
        <div class="control">
         <input class="input" id="uid_0" name="uid_0" type="text" value="'.htmlspecialchars($enreg['uid']).'" onchange="textchanged(\'uid\');resolve(0, 1);">
        </div>
       </div>
      </div>

	<div class="columns is-gapless is-columns-form">
    <div class="column is-one-fifth">
	  <div class="field is-horizontal">
      <label class="label field-label is-normal" for="remarquespub_0">'.__("Notes").'</label>
      </div>
	</div>
	<div class="column is-three-quarters">
        <div class="control">
         <textarea id="remarquespub_0" name="remarquespub_0" class="textarea" placeholder="" rows="2" onchange="textchanged(\'remarquespub\')">'.htmlspecialchars($enreg['remarquespub']).'</textarea>
        </div>
       </div>
	</div>
	';
	// Resolved block, if enabled
	if (isset($config_link_resolver_base_openurl) && $config_link_resolver_base_openurl != ''){
		// check if resolved links exist in cache
		$resolver_search_params = "pmid=" . urlencode($enreg['PMID']) . "&mms_id=" . urlencode(($tid == "renouvaudmms_swissbib" ? $uids : "")) . "&doi=" . urlencode($enreg['doi']) . "&genre=" . urlencode($enreg['type_doc']) . "&atitle=" . urlencode($enreg['titre_article']) . "&title=" . urlencode($enreg['titre_periodique']) . "&date=" . urlencode($enreg['annee']) . "&volume=" . urlencode($enreg['volume']) . "&issue=" . urlencode($enreg['numero']) . "&suppl=" . urlencode($enreg['supplement']) . "&pages=" . urlencode($enreg['pages']) . "&author=" . urlencode($enreg['auteurs']) . "&issn_isbn=" . urlencode(($enreg['isbn'] != "" ? $enreg['isbn'] : ($enreg['issn'] != "" ? $enreg['issn'] : ($enreg['eissn'] != "" ? $enreg['eissn'] : "")))) . "&edition=" . urlencode($enreg['edition']);
		$resolved_block_content = "";
		$resolved_block_style = 'display:none';
		$query = "SELECT cache FROM `resolver_cache` WHERE params=? LIMIT 1";
		$res = dbquery($query, array($resolver_search_params), 's');
		if (iimysqli_num_rows($res) > 0) {
			$resolved_block_content = json_decode(iimysqli_result_fetch_array($res)['cache'], true)['msg'];
			$resolved_block_style = '';
		}
		echo '<div class="columns is-gapless is-columns-form">
  <input type="hidden" id="resolver_search_params_0" name="resolver_search_params_0" value="'.htmlspecialchars($resolver_search_params).'" />
  <div class="column" id="resolvedurlblock_0" style="'.$resolved_block_style.'">'.$resolved_block_content.'</div></div>';
	}

	 echo ' </div>
	</section>';


                // END Document Fields
				// BEGIN SUBMISSION BUTTONS
				echo '



	<div class="container">
	  <div class="field is-grouped is-grouped-centered">
	  <p class="control">
      <input type="submit" class="button is-primary" value="'. __("Save").'" onsubmit="javascript:okcooc();document.body.style.cursor = \'wait\';" />
      </p>
	  <p class="control">
	  <input type="reset" value="'. __("Reset") .'" class="button" />
	  </p>
	  ';
	    if ($monaut == "sadmin") {
            echo '<p class="control">
				<input type="button" class="button is-danger" value="'.__("Permanently delete this order"). '" onClick="self.location=\'update.php?action=delete&amp;table=orders&amp;id=' . htmlspecialchars($id) . '\'">
				</p>';
        }
	  echo '
	  </div>
	</div>
	';
				// END SUBMISSION BUTTONS
				echo "</form>\n";
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
