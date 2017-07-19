﻿<?php
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
// folders table : record creation form
// 
require_once ("config.php");
require_once ("authcookie.php");
require_once ("connexion.php");

if (!empty($_COOKIE['illinkid'])){
	if (($monaut == "admin")||($monaut == "sadmin")){
		$myhtmltitle = $configname[$lang] . " : nouveau filtre ";
		require ("headeradmin.php");
		echo "<h1>Gestion des filtres : Création d'une nouvelle fiche </h1>\n";
		echo "<br /></b>";
		echo "<ul>\n";
		echo "<form action=\"update.php\" method=\"POST\" enctype=\"x-www-form-encoded\" name=\"fiche\" id=\"fiche\">\n";
		echo "<input name=\"table\" type=\"hidden\" value=\"folders\">\n";
		echo "<input name=\"action\" type=\"hidden\" value=\"new\">\n";
		echo "<table id=\"hor-zebra\">\n";
		echo "<tr><td></td><td><input type=\"submit\" value=\"Enregistrer le nouveau filtre \">\n";
		echo "&nbsp;&nbsp;<input type=\"button\" value=\"Annuler\" onClick=\"self.location='list.php?table=folders'\"></td></tr>\n";
		echo "<tr><td>&nbsp;</td><td>&nbsp;</td></tr>\n";

		// title
		echo "<tr><td><b>Titre *</b></td><td>\n";
		echo "<input name=\"title\" type=\"text\" size=\"40\" value=\"\"></td></tr>\n";
		echo "</td></tr>\n";

		// description
		echo "<tr><td><b>Description *</b></td><td>\n";
		echo "<input name=\"description\" type=\"text\" size=\"60\" value=\"\"></td></tr>\n";
		echo "</td></tr>\n";

		// user assigned to
		echo "<tr><td><b>Attribué à *</b></td><td>\n";
		echo "user <select name=\"user\">\n";
		echo "<option value=\"\"></option>";
		$requsers="SELECT name, login FROM users ORDER BY name ASC";
		$optionsusers="";
		$resultusers = dbquery($requsers);
		$nbusers = iimysqli_num_rows($resultusers);
		if ($nbusers > 0){
			while ($rowusers = iimysqli_result_fetch_array($resultusers)){
				$loginuser = $rowusers["login"];
				$nameuser = $rowusers["name"];
				$optionsusers.="<option value=\"" . htmlspecialchars($loginuser) . "\"";
				$optionsusers.=">" . htmlspecialchars($nameuser) . "</option>\n";
			}
			echo $optionsusers;
		}
		echo "</select> &nbsp;&nbsp;| &nbsp;&nbsp; \n";

		// library assigned to
		echo "bibliothèque </b>&nbsp;&nbsp;\n";
		echo "<select name=\"libraryassigned\">\n";
		echo "<option value=\"\"></option>";
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
				$optionslibraries.=">" . htmlspecialchars($namelibraries[$lang]) . "</option>\n";
			}
			echo $optionslibraries;
		}
		echo "</select></td></tr>\n";

		// position
		echo "<tr><td><b>Position *</b></td><td>\n";
		echo "<input name=\"position\" type=\"text\" size=\"10\" value=\"\"></td></tr>\n";
		echo "</td></tr>\n";

		// Actif / unactif
		echo "<tr><td><b>filtre actif</b></td>";
		echo "<td><input name=\"active\" value=\"1\" type=\"checkbox\" checked></td></tr>\n";
		echo "</td></tr>\n";
		echo "</table>\n";

		// *****************************************************************************************
		// *****************************************************************************************
		// *****************************************************************************************
		// Start Query
		echo "<h2>Critères 1</h2>\n";
		echo "<table id=\"hor-zebra\">\n";

		// library
		echo "<tr><td><b>Bibliothèque d'attribution 1</b></td><td>\n";
		echo "<select name=\"library1\">\n";
		echo "<option value=\"\"></option>";
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
				$optionslibraries.=">" . htmlspecialchars($namelibraries[$lang]) . "</option>\n";
			}
			echo $optionslibraries;
		}
		echo "</select>\n";
		echo " &nbsp; OR  &nbsp; \n";
		echo "</td></tr>\n";

		echo "<tr><td></td><td>\n";
		echo "<select name=\"library2\">\n";
		echo "<option value=\"\"></option>";
		echo $optionslibraries;
		echo "</select>\n";
		echo " &nbsp; OR  &nbsp; \n";
		echo "</td></tr>\n";

		echo "<tr><td></td><td>\n";
		echo "<select name=\"library3\">\n";
		echo "<option value=\"\"></option>";
		echo $optionslibraries;
		echo "</select>\n";
		echo " &nbsp; OR  &nbsp; \n";
		echo "</td></tr>\n";

		echo "<tr><td></td><td>\n";
		echo "<select name=\"library4\">\n";
		echo "<option value=\"\"></option>";
		echo $optionslibraries;
		echo "</select>\n";
		echo " &nbsp; OR  &nbsp; \n";
		echo "</td></tr>\n";

		echo "<tr><td></td><td>\n";
		echo "<select name=\"library5\">\n";
		echo "<option value=\"\"></option>";
		echo $optionslibraries;
		echo "</select>\n";
		echo "</td></tr>\n";

		echo "</td></tr>\n";

		// select status
		$allStatus = readStatus();
		$optionsstatus = "";
		foreach ($allStatus as $status){
			$labelStatus = $status['title1'];
			$labelCode = $status['code'];
			$optionsstatus.="<option value=\"" . htmlspecialchars($labelCode) . "\">".htmlspecialchars($labelStatus)."</option>\n";
		}

		echo "<tr><td class=\"odd\"><b>AND Statut 1</b></td><td class=\"odd\">\n";
		echo "<select name=\"statuscode1\">\n";
		echo "<option value=\"\"></option>";
		echo $optionsstatus;
		echo "</select>";
		echo " &nbsp; OR  &nbsp; \n";
		echo "</td></tr>\n";

		echo "<tr><td class=\"odd\"></td><td class=\"odd\">\n";
		echo "<select name=\"statuscode2\">\n";
		echo "<option value=\"\"></option>";
		echo $optionsstatus;
		echo "</select>";
		echo " &nbsp; OR  &nbsp; \n";
		echo "</td></tr>\n";

		echo "<tr><td class=\"odd\"></td><td class=\"odd\">\n";
		echo "<select name=\"statuscode3\">\n";
		echo "<option value=\"\"></option>";
		echo $optionsstatus;
		echo "</select>";
		echo " &nbsp; OR  &nbsp; \n";
		echo "</td></tr>\n";

		echo "<tr><td class=\"odd\"></td><td class=\"odd\">\n";
		echo "<select name=\"statuscode4\">\n";
		echo "<option value=\"\"></option>";
		echo $optionsstatus;
		echo "</select>";
		echo " &nbsp; OR  &nbsp; \n";
		echo "</td></tr>\n";

		echo "<tr><td class=\"odd\"></td><td class=\"odd\">\n";
		echo "<select name=\"statuscode5\">\n";
		echo "<option value=\"\"></option>";
		echo $optionsstatus;
		echo "</select>";
		echo "</td></tr>\n";

		// select localisations
		echo "<tr><td><b>AND Localisation 1</b></td><td>\n";
		echo "<select name=\"localisation1\">\n";
		echo "<option value=\"\"></option>";
		echo "<optgroup label=\"" . $localisationintmessage[$lang] . "\">\n";
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
			$optionslocalisation.=">" . htmlspecialchars($namelocalisation[$lang]) . "</option>\n";
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
				$optionslocalisationext.="<option value=\"".htmlspecialchars($codelocalisationext)."\">" . htmlspecialchars($namelocalisationext[$lang]) . "</option>\n";
			}
			echo "<optgroup label=\"" . htmlspecialchars($localisationextmessage[$lang]) . "\">\n";
			echo $optionslocalisationext;
		}
		echo "</select>\n";
		echo " &nbsp; OR  &nbsp; \n";
		echo "</td></tr>";

		echo "<tr><td></td><td>\n";
		echo "<select name=\"localisation2\">\n";
		echo "<option value=\"\"></option>";
		echo "<optgroup label=\"" . $localisationintmessage[$lang] . "\">\n";
		echo $optionslocalisation;
		// select other libraries
		echo "<optgroup label=\"" . htmlspecialchars($localisationextmessage[$lang]) . "\">\n";
		echo $optionslocalisationext;
		echo "</select>\n";
		echo " &nbsp; OR  &nbsp; \n";
		echo "</td></tr>";

		echo "<tr><td></td><td>\n";
		echo "<select name=\"localisation3\">\n";
		echo "<option value=\"\"></option>";
		echo "<optgroup label=\"" . $localisationintmessage[$lang] . "\">\n";
		echo $optionslocalisation;
		// select other libraries
		echo "<optgroup label=\"" . htmlspecialchars($localisationextmessage[$lang]) . "\">\n";
		echo $optionslocalisationext;
		echo "</select>\n";
		echo " &nbsp; OR  &nbsp; \n";
		echo "</td></tr>";

		echo "<tr><td></td><td>\n";
		echo "<select name=\"localisation4\">\n";
		echo "<option value=\"\"></option>";
		echo "<optgroup label=\"" . $localisationintmessage[$lang] . "\">\n";
		echo $optionslocalisation;
		// select other libraries
		echo "<optgroup label=\"" . htmlspecialchars($localisationextmessage[$lang]) . "\">\n";
		echo $optionslocalisationext;
		echo "</select>\n";
		echo " &nbsp; OR  &nbsp; \n";
		echo "</td></tr>";

		echo "<tr><td></td><td>\n";
		echo "<select name=\"localisation5\">\n";
		echo "<option value=\"\"></option>";
		echo "<optgroup label=\"" . $localisationintmessage[$lang] . "\">\n";
		echo $optionslocalisation;
		// select other libraries
		echo "<optgroup label=\"" . htmlspecialchars($localisationextmessage[$lang]) . "\">\n";
		echo $optionslocalisationext;
		echo "</select>\n";
		echo "</td></tr>";


		// units
		echo "<tr><td class=\"odd\"><b>AND " . $unitmessage[$lang] . " 1</b></td><td class=\"odd\">\n";
		$unitsortlang = "name1";
		if ($lang == "en")
			$unitsortlang = "name2";
		if ($lang == "de")
			$unitsortlang = "name3";
		if ($lang == "it")
			$unitsortlang = "name4";
		if ($lang == "es")
			$unitsortlang = "name5";
		echo "<select name=\"unit1\" id=\"service\" style=\"max-width:300px\">\n";
		echo "<option value=\"\"></option>\n";
		$requnits="SELECT code, $unitsortlang, library FROM units WHERE id > 0 ORDER BY library, $unitsortlang ASC";
		$optionsunits="";
		$resultunits = dbquery($requnits);
		while ($rowunits = iimysqli_result_fetch_array($resultunits)){
			$codeunits = $rowunits["code"];
			$libraryunits = $rowunits["library"];
			$nameunits = $rowunits[$unitsortlang];
			$optionsunits.="<option value=\"" . htmlspecialchars($codeunits) . "\"";
			$optionsunits.=">" . htmlspecialchars($libraryunits) . " - " . htmlspecialchars($nameunits) . "</option>\n";
		}
		echo $optionsunits;
		echo "</select>\n";
		echo " &nbsp; OR  &nbsp; \n";
		echo "</td></tr>\n";

		echo "<tr><td class=\"odd\"></td><td class=\"odd\">\n";
		echo "<select name=\"unit2\" style=\"max-width:300px\">\n";
		echo "<option value=\"\"></option>\n";
		echo $optionsunits;
		echo "</select>\n";
		echo " &nbsp; OR  &nbsp; \n";
		echo "</td></tr>\n";

		echo "<tr><td class=\"odd\"></td><td class=\"odd\">\n";
		echo "<select name=\"unit3\" style=\"max-width:300px\">\n";
		echo "<option value=\"\"></option>\n";
		echo $optionsunits;
		echo "</select>\n";
		echo " &nbsp; OR  &nbsp; \n";
		echo "</td></tr>\n";

		echo "<tr><td class=\"odd\"></td><td class=\"odd\">\n";
		echo "<select name=\"unit4\" style=\"max-width:300px\">\n";
		echo "<option value=\"\"></option>\n";
		echo $optionsunits;
		echo "</select>\n";
		echo " &nbsp; OR  &nbsp; \n";
		echo "</td></tr>\n";

		echo "<tr><td class=\"odd\"></td><td class=\"odd\">\n";
		echo "<select name=\"unit5\" style=\"max-width:300px\">\n";
		echo "<option value=\"\"></option>\n";
		echo $optionsunits;
		echo "</select>\n";
		echo "</td></tr>\n";


		echo "<tr><td><b>AND Compte 1</b></td><td>\n";
		echo "<select name=\"compte1\">\n";
		echo "<option value=\"\"></option>\n";
		echo "<option value=\"empty\">Vide</option>";
		echo "<option value=\"full\">Rempli</option>";
		echo "</select>\n";
		echo "</td></tr>\n";


		echo "<tr><td><b>AND date de renouvellement 1 </b></td><td>\n";
		echo "<select name=\"renewdate1\">\n";
		echo "<option value=\"\"></option>\n";
		echo "<option value=\"past\">Pasée</option>";
		echo "<option value=\"futur\">Future</option>";
		echo "<option value=\"day\">Date du jour</option>";
		echo "</select>\n";
		echo "</td></tr>\n";


		echo "</table>\n";


		// *****************************************************************************************
		// *****************************************************************************************
		// *****************************************************************************************
		// Start Query 2
		echo "<h2>Critères 2</h2>\n";
		echo "Combiner au précedent avec : <select name=\"bool1\">\n";
		echo "<option value=\"AND\">AND</option>";
		echo "<option value=\"OR\">OR</option>";
		echo "<option value=\"NOT\">NOT</option>";
		echo "</select>\n";

		echo "<table id=\"hor-zebra\">\n";

		// library
		echo "<tr><td><b>Bibliothèque d'attribution 2</b></td><td>\n";
		echo "<select name=\"library6\">\n";
		echo "<option value=\"\"></option>";
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
				$optionslibraries.=">" . htmlspecialchars($namelibraries[$lang]) . "</option>\n";
			}
			echo $optionslibraries;
		}
		echo "</select>\n";
		echo " &nbsp; OR  &nbsp; \n";
		echo "</td></tr>\n";

		echo "<tr><td></td><td>\n";
		echo "<select name=\"library7\">\n";
		echo "<option value=\"\"></option>";
		echo $optionslibraries;
		echo "</select>\n";
		echo " &nbsp; OR  &nbsp; \n";
		echo "</td></tr>\n";

		echo "<tr><td></td><td>\n";
		echo "<select name=\"library8\">\n";
		echo "<option value=\"\"></option>";
		echo $optionslibraries;
		echo "</select>\n";
		echo " &nbsp; OR  &nbsp; \n";
		echo "</td></tr>\n";

		echo "<tr><td></td><td>\n";
		echo "<select name=\"library9\">\n";
		echo "<option value=\"\"></option>";
		echo $optionslibraries;
		echo "</select>\n";
		echo " &nbsp; OR  &nbsp; \n";
		echo "</td></tr>\n";

		echo "<tr><td></td><td>\n";
		echo "<select name=\"library10\">\n";
		echo "<option value=\"\"></option>";
		echo $optionslibraries;
		echo "</select>\n";
		echo "</td></tr>\n";

		echo "</td></tr>\n";

		// select status
		$allStatus = readStatus();
		$optionsstatus = "";
		foreach ($allStatus as $status){
			$labelStatus = $status['title1'];
			$labelCode = $status['code'];
			$optionsstatus.="<option value=\"" . htmlspecialchars($labelCode) . "\">".htmlspecialchars($labelStatus)."</option>\n";
		}

		echo "<tr><td class=\"odd\"><b>AND Statut 2</b></td><td class=\"odd\">\n";
		echo "<select name=\"statuscode6\">\n";
		echo "<option value=\"\"></option>";
		echo $optionsstatus;
		echo "</select>";
		echo " &nbsp; OR  &nbsp; \n";
		echo "</td></tr>\n";

		echo "<tr><td class=\"odd\"></td><td class=\"odd\">\n";
		echo "<select name=\"statuscode7\">\n";
		echo "<option value=\"\"></option>";
		echo $optionsstatus;
		echo "</select>";
		echo " &nbsp; OR  &nbsp; \n";
		echo "</td></tr>\n";

		echo "<tr><td class=\"odd\"></td><td class=\"odd\">\n";
		echo "<select name=\"statuscode8\">\n";
		echo "<option value=\"\"></option>";
		echo $optionsstatus;
		echo "</select>";
		echo " &nbsp; OR  &nbsp; \n";
		echo "</td></tr>\n";

		echo "<tr><td class=\"odd\"></td><td class=\"odd\">\n";
		echo "<select name=\"statuscode9\">\n";
		echo "<option value=\"\"></option>";
		echo $optionsstatus;
		echo "</select>";
		echo " &nbsp; OR  &nbsp; \n";
		echo "</td></tr>\n";

		echo "<tr><td class=\"odd\"></td><td class=\"odd\">\n";
		echo "<select name=\"statuscode10\">\n";
		echo "<option value=\"\"></option>";
		echo $optionsstatus;
		echo "</select>";
		echo "</td></tr>\n";

		// select localisations
		echo "<tr><td><b>AND Localisation 2</b></td><td>\n";
		echo "<select name=\"localisation6\">\n";
		echo "<option value=\"\"></option>";
		echo "<optgroup label=\"" . $localisationintmessage[$lang] . "\">\n";
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
			$optionslocalisation.=">" . htmlspecialchars($namelocalisation[$lang]) . "</option>\n";
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
				$optionslocalisationext.="<option value=\"".htmlspecialchars($codelocalisationext)."\">" . htmlspecialchars($namelocalisationext[$lang]) . "</option>\n";
			}
			echo "<optgroup label=\"" . htmlspecialchars($localisationextmessage[$lang]) . "\">\n";
			echo $optionslocalisationext;
		}
		echo "</select>\n";
		echo " &nbsp; OR  &nbsp; \n";
		echo "</td></tr>";

		echo "<tr><td></td><td>\n";
		echo "<select name=\"localisation7\">\n";
		echo "<option value=\"\"></option>";
		echo "<optgroup label=\"" . $localisationintmessage[$lang] . "\">\n";
		echo $optionslocalisation;
		// select other libraries
		echo "<optgroup label=\"" . htmlspecialchars($localisationextmessage[$lang]) . "\">\n";
		echo $optionslocalisationext;
		echo "</select>\n";
		echo " &nbsp; OR  &nbsp; \n";
		echo "</td></tr>";

		echo "<tr><td></td><td>\n";
		echo "<select name=\"localisation8\">\n";
		echo "<option value=\"\"></option>";
		echo "<optgroup label=\"" . $localisationintmessage[$lang] . "\">\n";
		echo $optionslocalisation;
		// select other libraries
		echo "<optgroup label=\"" . htmlspecialchars($localisationextmessage[$lang]) . "\">\n";
		echo $optionslocalisationext;
		echo "</select>\n";
		echo " &nbsp; OR  &nbsp; \n";
		echo "</td></tr>";

		echo "<tr><td></td><td>\n";
		echo "<select name=\"localisation9\">\n";
		echo "<option value=\"\"></option>";
		echo "<optgroup label=\"" . $localisationintmessage[$lang] . "\">\n";
		echo $optionslocalisation;
		// select other libraries
		echo "<optgroup label=\"" . htmlspecialchars($localisationextmessage[$lang]) . "\">\n";
		echo $optionslocalisationext;
		echo "</select>\n";
		echo " &nbsp; OR  &nbsp; \n";
		echo "</td></tr>";

		echo "<tr><td></td><td>\n";
		echo "<select name=\"localisation10\">\n";
		echo "<option value=\"\"></option>";
		echo "<optgroup label=\"" . $localisationintmessage[$lang] . "\">\n";
		echo $optionslocalisation;
		// select other libraries
		echo "<optgroup label=\"" . htmlspecialchars($localisationextmessage[$lang]) . "\">\n";
		echo $optionslocalisationext;
		echo "</select>\n";
		echo "</td></tr>";


		// units
		echo "<tr><td class=\"odd\"><b>AND " . $unitmessage[$lang] . " 2</b></td><td class=\"odd\">\n";
		$unitsortlang = "name1";
		if ($lang == "en")
			$unitsortlang = "name2";
		if ($lang == "de")
			$unitsortlang = "name3";
		if ($lang == "it")
			$unitsortlang = "name4";
		if ($lang == "es")
			$unitsortlang = "name5";
		echo "<select name=\"unit6\" id=\"service\" style=\"max-width:300px\">\n";
		echo "<option value=\"\"></option>\n";
		$requnits="SELECT code, $unitsortlang, library FROM units WHERE id > 0 ORDER BY library, $unitsortlang ASC";
		$optionsunits="";
		$resultunits = dbquery($requnits);
		while ($rowunits = iimysqli_result_fetch_array($resultunits)){
			$codeunits = $rowunits["code"];
			$libraryunits = $rowunits["library"];
			$nameunits = $rowunits[$unitsortlang];
			$optionsunits.="<option value=\"" . htmlspecialchars($codeunits) . "\"";
			$optionsunits.=">" . htmlspecialchars($libraryunits) . " - " . htmlspecialchars($nameunits) . "</option>\n";
		}
		echo $optionsunits;
		echo "</select>\n";
		echo " &nbsp; OR  &nbsp; \n";
		echo "</td></tr>\n";

		echo "<tr><td class=\"odd\"></td><td class=\"odd\">\n";
		echo "<select name=\"unit7\" style=\"max-width:300px\">\n";
		echo "<option value=\"\"></option>\n";
		echo $optionsunits;
		echo "</select>\n";
		echo " &nbsp; OR  &nbsp; \n";
		echo "</td></tr>\n";

		echo "<tr><td class=\"odd\"></td><td class=\"odd\">\n";
		echo "<select name=\"unit8\" style=\"max-width:300px\">\n";
		echo "<option value=\"\"></option>\n";
		echo $optionsunits;
		echo "</select>\n";
		echo " &nbsp; OR  &nbsp; \n";
		echo "</td></tr>\n";

		echo "<tr><td class=\"odd\"></td><td class=\"odd\">\n";
		echo "<select name=\"unit9\" style=\"max-width:300px\">\n";
		echo "<option value=\"\"></option>\n";
		echo $optionsunits;
		echo "</select>\n";
		echo " &nbsp; OR  &nbsp; \n";
		echo "</td></tr>\n";

		echo "<tr><td class=\"odd\"></td><td class=\"odd\">\n";
		echo "<select name=\"unit10\" style=\"max-width:300px\">\n";
		echo "<option value=\"\"></option>\n";
		echo $optionsunits;
		echo "</select>\n";
		echo "</td></tr>\n";


		echo "<tr><td><b>AND Compte 2</b></td><td>\n";
		echo "<select name=\"compte2\">\n";
		echo "<option value=\"\"></option>\n";
		echo "<option value=\"empty\">Vide</option>";
		echo "<option value=\"full\">Rempli</option>";
		echo "</select>\n";
		echo "</td></tr>\n";


		echo "<tr><td><b>AND date de renouvellement 2 </b></td><td>\n";
		echo "<select name=\"renewdate2\">\n";
		echo "<option value=\"\"></option>\n";
		echo "<option value=\"past\">Pasée</option>";
		echo "<option value=\"futur\">Future</option>";
		echo "<option value=\"day\">Date du jour</option>";
		echo "</select>\n";
		echo "</td></tr>\n";

		echo "</table>\n";


		// *****************************************************************************************
		// *****************************************************************************************
		// *****************************************************************************************
		// Start Query 3

		echo "<h2>Critères 3</h2>\n";
		echo "Combiner au précedent avec : <select name=\"bool2\">\n";
		echo "<option value=\"AND\">AND</option>";
		echo "<option value=\"OR\">OR</option>";
		echo "<option value=\"NOT\">NOT</option>";
		echo "</select>\n";

		echo "<table id=\"hor-zebra\">\n";

		// library
		echo "<tr><td><b>Bibliothèque d'attribution 3</b></td><td>\n";
		echo "<select name=\"library11\">\n";
		echo "<option value=\"\"></option>";
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
				$optionslibraries.=">" . htmlspecialchars($namelibraries[$lang]) . "</option>\n";
			}
			echo $optionslibraries;
		}
		echo "</select>\n";
		echo " &nbsp; OR  &nbsp; \n";
		echo "</td></tr>\n";

		echo "<tr><td></td><td>\n";
		echo "<select name=\"library12\">\n";
		echo "<option value=\"\"></option>";
		echo $optionslibraries;
		echo "</select>\n";
		echo " &nbsp; OR  &nbsp; \n";
		echo "</td></tr>\n";

		echo "<tr><td></td><td>\n";
		echo "<select name=\"library13\">\n";
		echo "<option value=\"\"></option>";
		echo $optionslibraries;
		echo "</select>\n";
		echo " &nbsp; OR  &nbsp; \n";
		echo "</td></tr>\n";

		echo "<tr><td></td><td>\n";
		echo "<select name=\"library14\">\n";
		echo "<option value=\"\"></option>";
		echo $optionslibraries;
		echo "</select>\n";
		echo " &nbsp; OR  &nbsp; \n";
		echo "</td></tr>\n";

		echo "<tr><td></td><td>\n";
		echo "<select name=\"library15\">\n";
		echo "<option value=\"\"></option>";
		echo $optionslibraries;
		echo "</select>\n";
		echo "</td></tr>\n";

		echo "</td></tr>\n";

		// select status
		$allStatus = readStatus();
		$optionsstatus = "";
		foreach ($allStatus as $status){
			$labelStatus = $status['title1'];
			$labelCode = $status['code'];
			$optionsstatus.="<option value=\"" . htmlspecialchars($labelCode) . "\">".htmlspecialchars($labelStatus)."</option>\n";
		}

		echo "<tr><td class=\"odd\"><b>AND Statut 3</b></td><td class=\"odd\">\n";
		echo "<select name=\"statuscode11\">\n";
		echo "<option value=\"\"></option>";
		echo $optionsstatus;
		echo "</select>";
		echo " &nbsp; OR  &nbsp; \n";
		echo "</td></tr>\n";

		echo "<tr><td class=\"odd\"></td><td class=\"odd\">\n";
		echo "<select name=\"statuscode12\">\n";
		echo "<option value=\"\"></option>";
		echo $optionsstatus;
		echo "</select>";
		echo " &nbsp; OR  &nbsp; \n";
		echo "</td></tr>\n";

		echo "<tr><td class=\"odd\"></td><td class=\"odd\">\n";
		echo "<select name=\"statuscode13\">\n";
		echo "<option value=\"\"></option>";
		echo $optionsstatus;
		echo "</select>";
		echo " &nbsp; OR  &nbsp; \n";
		echo "</td></tr>\n";

		echo "<tr><td class=\"odd\"></td><td class=\"odd\">\n";
		echo "<select name=\"statuscode14\">\n";
		echo "<option value=\"\"></option>";
		echo $optionsstatus;
		echo "</select>";
		echo " &nbsp; OR  &nbsp; \n";
		echo "</td></tr>\n";

		echo "<tr><td class=\"odd\"></td><td class=\"odd\">\n";
		echo "<select name=\"statuscode15\">\n";
		echo "<option value=\"\"></option>";
		echo $optionsstatus;
		echo "</select>";
		echo "</td></tr>\n";

		// select localisations
		echo "<tr><td><b>AND Localisation 3</b></td><td>\n";
		echo "<select name=\"localisation11\">\n";
		echo "<option value=\"\"></option>";
		echo "<optgroup label=\"" . $localisationintmessage[$lang] . "\">\n";
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
			$optionslocalisation.=">" . htmlspecialchars($namelocalisation[$lang]) . "</option>\n";
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
				$optionslocalisationext.="<option value=\"".htmlspecialchars($codelocalisationext)."\">" . htmlspecialchars($namelocalisationext[$lang]) . "</option>\n";
			}
			echo "<optgroup label=\"" . htmlspecialchars($localisationextmessage[$lang]) . "\">\n";
			echo $optionslocalisationext;
		}
		echo "</select>\n";
		echo " &nbsp; OR  &nbsp; \n";
		echo "</td></tr>";

		echo "<tr><td></td><td>\n";
		echo "<select name=\"localisation12\">\n";
		echo "<option value=\"\"></option>";
		echo "<optgroup label=\"" . $localisationintmessage[$lang] . "\">\n";
		echo $optionslocalisation;
		// select other libraries
		echo "<optgroup label=\"" . htmlspecialchars($localisationextmessage[$lang]) . "\">\n";
		echo $optionslocalisationext;
		echo "</select>\n";
		echo " &nbsp; OR  &nbsp; \n";
		echo "</td></tr>";

		echo "<tr><td></td><td>\n";
		echo "<select name=\"localisation13\">\n";
		echo "<option value=\"\"></option>";
		echo "<optgroup label=\"" . $localisationintmessage[$lang] . "\">\n";
		echo $optionslocalisation;
		// select other libraries
		echo "<optgroup label=\"" . htmlspecialchars($localisationextmessage[$lang]) . "\">\n";
		echo $optionslocalisationext;
		echo "</select>\n";
		echo " &nbsp; OR  &nbsp; \n";
		echo "</td></tr>";

		echo "<tr><td></td><td>\n";
		echo "<select name=\"localisation14\">\n";
		echo "<option value=\"\"></option>";
		echo "<optgroup label=\"" . $localisationintmessage[$lang] . "\">\n";
		echo $optionslocalisation;
		// select other libraries
		echo "<optgroup label=\"" . htmlspecialchars($localisationextmessage[$lang]) . "\">\n";
		echo $optionslocalisationext;
		echo "</select>\n";
		echo " &nbsp; OR  &nbsp; \n";
		echo "</td></tr>";

		echo "<tr><td></td><td>\n";
		echo "<select name=\"localisation15\">\n";
		echo "<option value=\"\"></option>";
		echo "<optgroup label=\"" . $localisationintmessage[$lang] . "\">\n";
		echo $optionslocalisation;
		// select other libraries
		echo "<optgroup label=\"" . htmlspecialchars($localisationextmessage[$lang]) . "\">\n";
		echo $optionslocalisationext;
		echo "</select>\n";
		echo "</td></tr>";


		// units
		echo "<tr><td class=\"odd\"><b>AND " . $unitmessage[$lang] . " 3</b></td><td class=\"odd\">\n";
		$unitsortlang = "name1";
		if ($lang == "en")
			$unitsortlang = "name2";
		if ($lang == "de")
			$unitsortlang = "name3";
		if ($lang == "it")
			$unitsortlang = "name4";
		if ($lang == "es")
			$unitsortlang = "name5";
		echo "<select name=\"unit11\" style=\"max-width:300px\">\n";
		echo "<option value=\"\"></option>\n";
		$requnits="SELECT code, $unitsortlang, library FROM units WHERE id > 0 ORDER BY library, $unitsortlang ASC";
		$optionsunits="";
		$resultunits = dbquery($requnits);
		while ($rowunits = iimysqli_result_fetch_array($resultunits)){
			$codeunits = $rowunits["code"];
			$libraryunits = $rowunits["library"];
			$nameunits = $rowunits[$unitsortlang];
			$optionsunits.="<option value=\"" . htmlspecialchars($codeunits) . "\"";
			$optionsunits.=">" . htmlspecialchars($libraryunits) . " - " . htmlspecialchars($nameunits) . "</option>\n";
		}
		echo $optionsunits;
		echo "</select>\n";
		echo " &nbsp; OR  &nbsp; \n";
		echo "</td></tr>\n";

		echo "<tr><td class=\"odd\"></td><td class=\"odd\">\n";
		echo "<select name=\"unit12\" style=\"max-width:300px\">\n";
		echo "<option value=\"\"></option>\n";
		echo $optionsunits;
		echo "</select>\n";
		echo " &nbsp; OR  &nbsp; \n";
		echo "</td></tr>\n";

		echo "<tr><td class=\"odd\"></td><td class=\"odd\">\n";
		echo "<select name=\"unit13\" style=\"max-width:300px\">\n";
		echo "<option value=\"\"></option>\n";
		echo $optionsunits;
		echo "</select>\n";
		echo " &nbsp; OR  &nbsp; \n";
		echo "</td></tr>\n";

		echo "<tr><td class=\"odd\"></td><td class=\"odd\">\n";
		echo "<select name=\"unit14\" style=\"max-width:300px\">\n";
		echo "<option value=\"\"></option>\n";
		echo $optionsunits;
		echo "</select>\n";
		echo " &nbsp; OR  &nbsp; \n";
		echo "</td></tr>\n";

		echo "<tr><td class=\"odd\"></td><td class=\"odd\">\n";
		echo "<select name=\"unit15\" style=\"max-width:300px\">\n";
		echo "<option value=\"\"></option>\n";
		echo $optionsunits;
		echo "</select>\n";
		echo "</td></tr>\n";


		echo "<tr><td><b>AND Compte 3</b></td><td>\n";
		echo "<select name=\"compte3\">\n";
		echo "<option value=\"\"></option>\n";
		echo "<option value=\"empty\">Vide</option>";
		echo "<option value=\"full\">Rempli</option>";
		echo "</select>\n";
		echo "</td></tr>\n";


		echo "<tr><td><b>AND date de renouvellement 3 </b></td><td>\n";
		echo "<select name=\"renewdate3\">\n";
		echo "<option value=\"\"></option>\n";
		echo "<option value=\"past\">Pasée</option>";
		echo "<option value=\"futur\">Future</option>";
		echo "<option value=\"day\">Date du jour</option>";
		echo "</select>\n";
		echo "</td></tr>\n";


		echo "<tr><td>&nbsp;</td><td>&nbsp;</td></tr>\n";
		echo "<tr><td></td><td><input type=\"submit\" value=\"Enregistrer le nouveau filtre \">\n";
		echo "&nbsp;&nbsp;<input type=\"button\" value=\"Annuler\" onClick=\"self.location='list.php?table=folders'\"></td></tr>\n";
		echo "</table>\n";
		echo "</form><br /><br />\n";
		require ("footer.php");
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
