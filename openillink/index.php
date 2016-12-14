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

$myhtmltitle = $configname[$lang] . " : nouvelle commande";
$mybodyonload = "document.commande.nom.focus(); remplirauto();";
if (($monaut == "admin")||($monaut == "sadmin")||($monaut == "user")){
    require ("includes/headeradmin.php");
    echo "<h1><center>" . $firstmessage[$lang] . "<a href=\"" . $configlibraryurl[$lang] . "\" target=\"_blank\">" . $configlibrary[$lang] . "</a></center></h1>\n";
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
    echo "<form action=\"new.php\" method=\"POST\" enctype=\"x-www-form-encoded\" name=\"commande\" onsubmit=\"javascript:okcooc()\">\n";
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
    echo $statusmessage[$lang] . " * </label>: \n";
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
        $optionsstatus.=">" . htmlspecialchars($namestatus[$lang]) . "</option>\n";
    }
    echo $optionsstatus;
    echo "</select>\n";
    echo "&nbsp;&nbsp;&nbsp;&nbsp;\n";
	echo '<label for="localisation">';
    echo $localisationmessage[$lang] . "</label> : &nbsp;\n";
    echo "<select name=\"localisation\" id=\"localisation\">\n";
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
    echo "</td></tr>";

    echo "<tr><td colspan=\"4\">\n";
	echo '<label for="urgent">';
    echo $prioritymessage[$lang] . "</label> : <select name=\"urgent\" id=\"urgent\">\n";
    echo "<option value=\"2\" selected>" . $prioritynormmessage[$lang] . "</option>\n";
    echo "<option value=\"1\">" . $priorityurgmessage[$lang] . "</option>\n";
    echo "<option value=\"3\">" . $prioritynonemessage[$lang] . "</option>\n";
    echo "</select>\n";
	if ($displayFormOrderSourceField) {
		echo "&nbsp;&nbsp;&nbsp;&nbsp;<label for=\"source\">" . $sourcemessage[$lang] . "</label> : \n";
		echo "<select name=\"source\" id=\"source\" onchange=\"ajoutevaleur('source');\">\n";
		echo "<option value=\"\"> </option>\n";
		$reqsource = "SELECT arrivee FROM orders WHERE arrivee != '' GROUP BY arrivee ORDER BY arrivee ASC";
		$optionssource = "";
		$resultsource = dbquery($reqsource);
		while ($rowsource = iimysqli_result_fetch_array($resultsource)){
			$codesource = $rowsource["arrivee"];
			$optionssource.="<option value=\"".htmlspecialchars($codesource)."\">".htmlspecialchars($codesource)."</option>\n";
		}
		echo $optionssource;
		echo "<option value=\"new\">" . $addvaluemessage[$lang] . "</option>\n";
		echo "</select>\n";
		echo "&nbsp;<input name=\"sourcenew\" id=\"sourcenew\" type=\"text\" size=\"20\" value=\"\" style=\"display:none\">\n";
	}
    echo "</td></tr><tr><td>\n";
	echo '<label for="datesaisie">';
    echo "<a href=\"#\" title=\"" . $orderdatehelpmessage[$lang] . "\">" . $orderdatemessage[$lang] . "</a></label> : </td><td> \n";
    echo "<input name=\"datesaisie\" id=\"datesaisie\" type=\"text\" size=\"10\" value=\"\" class=\"tcal\">\n";
    echo "</td><td>\n";
	echo '<label for="envoye">';
    echo $ordersentdatemessage[$lang] . "</label> : </td><td>\n";
    echo "<input name=\"envoye\" id=\"envoye\" type=\"text\" size=\"10\" value=\"\" class=\"tcal\">\n";
    echo "</td></tr><tr><td>\n";
	echo '<label for="facture">';
    echo $orderfactdatemessage[$lang] . "</label> : </td><td>\n";
    echo "<input name=\"facture\" id=\"facture\" type=\"text\" size=\"10\" value=\"\" class=\"tcal\">\n";
    echo "</td><td>\n";
	echo '<label for="renouveler">';
    echo $orderrenewdatemessage[$lang] . "</label> : </td><td>\n";
    echo "<input name=\"renouveler\" id=\"renouveler\" type=\"text\" size=\"10\" value=\"\" class=\"tcal\">\n";
    echo "</td></tr><tr><td colspan=\"4\">\n";
	echo '<label for="prix">';
    echo $pricemessage[$lang] . "</label> : &nbsp;\n";
    echo "<input name=\"prix\" id=\"prix\" type=\"text\" size=\"5\" value=\"\">\n";
    echo "&nbsp;&nbsp;(<input type=\"checkbox\" name=\"avance\" id=\"avance\" value=\"on\" /><label for=\"avance\">" . $paidadvmessage[$lang] . "</label>) &nbsp;&nbsp;&nbsp;&nbsp;\n";
    echo "</td></tr><tr><td colspan=\"4\">\n";
	echo '<label for="ref">';
    echo $refextmessage[$lang] . "</label> : &nbsp;\n";
    echo "<input name=\"ref\" id=\"ref\" type=\"text\" size=\"20\" value=\"\">&nbsp;&nbsp;&nbsp;\n";
	if ($displayFormInternalRefField) {
		echo '<label for="refinterbib">';
		echo $refintmessage[$lang] . "</label> : &nbsp;\n";
		echo "<input name=\"refinterbib\" id=\"refinterbib\" type=\"text\" size=\"20\" value=\"\">";
	}
	echo "</td></tr>\n";
	echo "<tr><td valign=\"top\">\n";
	echo '<label for="remarques">';
    echo $commentsmessage[$lang] . "</label> : \n";
    echo "</td><td valign=\"bottom\" colspan=\"3\"><textarea name=\"remarques\" id=\"remarques\" rows=\"2\" cols=\"60\" valign=\"bottom\"></textarea>\n";
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
    echo "<h1><center>" . $firstmessage[$lang] . "<a href=\"" . $configlibraryurl[$lang] . "\" target=\"_blank\">" . $configlibrary[$lang] . "</a></center></h1>\n";
    echo "<div class=\"box\"><div class=\"box-content\">\n";
    echo "<b><font color=\"red\">" . $alertmessage[$lang] . "</font></b><br />" . $informationmessage[$lang] . " : <a href=\"mailto:" . $configlibraryemail[$lang] . "\">" . $configlibraryemail[$lang] . "</a>\n";
    echo "</div></div>\n";
    echo "<div class=\"box-footer\"><div class=\"box-footer-right\"></div></div>\n";
    echo "<form action=\"new.php\" method=\"POST\" enctype=\"x-www-form-encoded\" name=\"commande\" onsubmit=\"javascript:okcooc()\">\n";
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
echo $namemessage[$lang] . " *</label> : </td><td><input name=\"nom\" id=\"nom\" type=\"text\" size=\"30\" value=\"\"></td><td>\n";
echo '<label for="prenom">';
echo $firstnamemessage[$lang] . " *</label> : </td><td><input name=\"prenom\" id=\"prenom\" type=\"text\" size=\"30\" value=\"\">\n";
if ($directoryurl1 != "")
    echo "&nbsp;<a href=\"javascript:directory('$directoryurl1')\" title=\"" . $directory1message[$lang] . "\"><img src=\"img/directory1.png\"></a>\n";
if ($directoryurl2 != "")
    echo "<a href=\"javascript:directory('$directoryurl2')\" title=\"" . $directory2message[$lang] . "\"><img src=\"img/directory2.png\"></a>\n";
echo "</td></tr><tr><td>\n";
echo '<label for="service">';
echo $unitmessage[$lang] . " *</label> : </td><td>\n";
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
    $optionsunits.=">" . htmlspecialchars($nameunits) . "</option>\n";
}
echo $optionsunits;
echo "</select>\n";
echo "</td><td>\n";
echo '<label for="servautre">';
echo $unitothermessage[$lang] . "</label> : </td><td>\n";
echo "<input name=\"servautre\" id=\"servautre\" type=\"text\" size=\"30\" value=\"\">\n";
echo "</td></tr>\n";
if ($ip1 == 1){
    echo "<tr><td>\n";
	echo '<label for="cgra">';
    echo $cgramessage[$lang] . "</label> : \n";
    echo "</td><td>\n";
    echo "<input name=\"cgra\" id=\"cgra\" type=\"text\" size=\"30\" value=\"\"></td><td>\n";
	echo '<label for="cgrb">';
    echo $cgrbmessage[$lang] . "</label> : </td><td>\n";
    echo "<input name=\"cgrb\" id=\"cgrb\" type=\"text\" size=\"30\" value=\"\">\n";
    echo "</td></tr>\n";
}
else{
    echo "<input name=\"cgra\" type=\"hidden\"  value=\"\">\n";
    echo "<input name=\"cgrb\" type=\"hidden\"  value=\"\">\n";
}
echo "<tr><td>\n";
echo '<label for="mail">';
echo $emailmessage[$lang] . " *</label> : </td><td>\n";
echo "<input name=\"mail\" id=\"mail\" type=\"text\" size=\"30\" value=\"\"></td><td>\n";
echo '<label for="tel">';
echo $telmessage[$lang] . "</label> : </td><td>\n";
echo "<input name=\"tel\" id=\"tel\" type=\"text\" size=\"30\" value=\"\">\n";
echo "</td></tr>\n";
echo "<tr><td valign=\"top\">\n";
echo '<label for="adresse">';
echo $addressmessage[$lang] . "</label> :\n";
echo "</td><td>\n";
echo "<input name=\"adresse\" id=\"adresse\" type=\"text\" size=\"30\" value=\"\">\n";
echo "</td><td>\n";
echo '<label for="postal">';
echo $cpmessage[$lang] . "</label> : </td><td>\n";
echo "<input name=\"postal\" id=\"postal\" type=\"text\" size=\"5\" value=\"\">\n";
echo "&nbsp;\n";
echo '<label for="localite">';
echo $citymessage[$lang] . "</label> :\n";
echo "<input name=\"localite\" id=\"localite\" type=\"text\" size=\"7\" value=\"\">\n";
echo "</td></tr><tr><td valign=\"top\" colspan=\"4\">\n";
echo $dispomessage[$lang] . " : \n";
echo "<input type=\"radio\" name=\"envoi\" id=\"envoimail\" value=\"mail\" checked/>\n";
echo '<label for="envoimail">';
echo $dispofactmessage[$lang] . "</label>&nbsp;\n";
echo "<input type=\"radio\" name=\"envoi\" id=\"envoisurplace\" value=\"surplace\" />\n";
echo '<label for="envoisurplace">';
echo $disponotfactmessage[$lang] . "</label>\n";
echo "</td></tr>\n";
echo "<tr>\n";
echo "<td valign=\"top\" colspan=\"4\">\n";
echo "<input type=\"checkbox\" name=\"cooc\" id=\"cooc\" value=\"on\" />\n";
echo '<label for="cooc">';
echo $savecookiemessage[$lang] . "</label>&nbsp;&nbsp;|&nbsp;&nbsp;(<A HREF=\"javascript:coocout()\">" . $deletecookiemessage[$lang] . "</a>)\n";
echo "</td></tr>\n";
echo "</Table>\n";
echo "\n";
echo "</div></div>\n";
echo "<div class=\"box-footer\"><div class=\"box-footer-right\"></div></div>\n";
echo "\n";
// END User Fields



// START Document Fields
echo "<div class=\"box\"><div class=\"box-content\">\n";
echo "<div class=\"box\"><div class=\"box-content\">\n";
echo "<center><b><label for=\"tid\">" . $lookupmessage[$lang] . "</label> </b>\n";
echo "<select name=\"tid\" id=\"tid\">\n";
foreach($lookupuid as $value) {
    echo "<option value=\"" . htmlspecialchars($value["code"]) . "\">" . htmlspecialchars($value["name"]) . "</option>\n";
}
echo "</select>\n";
echo "<input name=\"uids\" type=\"text\" size=\"20\" value=\"\">\n";
echo "<input type=\"button\" value=\"OK\" onclick=\"lookupid()\"></center>\n";
echo "</div></div>\n";
echo "<div class=\"box-footer\"><div class=\"box-footer-right\"></div></div>\n";
echo "\n";
echo "<table border=\"0\" cellspacing=\"0\" cellpadding=\"3\" width=\"100%\">\n";
if((!empty($doctypesmessage)) && $doctypesmessage[$lang])
    echo "<tr><td><label for=\"genre\">" . $doctypesmessage[$lang] . "Type de document</label> : </td><td>\n";
else
    echo "<tr><td><label for=\"genre\">Type de document</label> : </td><td>\n";
echo "<select name=\"genre\" id=\"genre\">\n";
foreach($doctypes as $value) {
    echo "<option value=\"" . htmlspecialchars($value["code"]) . "\">" . htmlspecialchars($value[$lang]) . "</option>\n";
}
echo "</select>\n";
echo "<div class=\"formdoc\">\n";
echo "</td></tr><tr><td>\n";
echo '<label for="title">';
echo $stitlemessage[$lang] . " *</label> : </td><td>\n";
echo "<input name=\"title\" id=\"title\" type=\"text\" size=\"80\" value=\"\">\n";
echo "&nbsp;\n";
echo "<a href=\"javascript:openlist('".$periodical_title_search_url."')\"><img src=\"img/find.png\" title=\"" . $atozlinkmessage[$lang] . "\"></a>\n";
echo "</td></tr><tr><td>\n";
echo '<label for="date">';
echo $yearmessage[$lang] . "</label> : </td><td>\n";
echo "<input name=\"date\" id=\"date\" type=\"text\" size=\"3\" value=\"\">\n";
echo "&nbsp;\n";
echo '<label for="volume">';
echo $volumemessage[$lang] . "</label> : \n";
echo "<input name=\"volume\" id=\"volume\" type=\"text\" size=\"3\" value=\"\">\n";
echo "&nbsp;\n";
echo '<label for="issue">';
echo $issuemessage[$lang] . "</label> : \n";
echo "<input name=\"issue\" id=\"issue\" type=\"text\" size=\"3\" value=\"\">\n";
echo "&nbsp;\n";
echo '<label for="suppl">';
echo $supplementmessage[$lang] . "</label> : \n";
echo "<input name=\"suppl\" id=\"suppl\" type=\"text\" size=\"3\" value=\"\">\n";
echo "&nbsp;\n";
echo '<label for="pages">';
echo $pagesmessage[$lang] . "</label> : \n";
echo "<input name=\"pages\" id=\"pages\" type=\"text\" size=\"4\" value=\"\">\n";
echo "</td></tr><tr><td>\n";
echo '<label for="atitle">';
echo $atitlemessage[$lang] . "</label> : \n";
echo "</td><td>\n";
echo "<input name=\"atitle\" id=\"atitle\" type=\"text\" size=\"80\" value=\"\">\n";
echo "</td></tr><tr><td>\n";
echo '<label for="auteurs">';
echo $authorsmessage[$lang] . "</label> : \n";
echo "</td><td>\n";
echo "<input name=\"auteurs\" id=\"auteurs\" type=\"text\" size=\"80\" value=\"\">\n";
echo "</td></tr>\n";
echo "<tr><td>\n";
echo '<label for="edition">';
echo $editionmessage[$lang] . "</label> : \n";
echo "</td><td>\n";
echo "<input name=\"edition\" id=\"edition\" type=\"text\" size=\"14\" value=\"\">\n";
echo "&nbsp;\n";
echo "<label for=\"issn\">ISSN / ISBN</label> : \n";
echo "<input name=\"issn\" id=\"issn\" type=\"text\" size=\"15\" value=\"\">\n";
echo "&nbsp;\n";
echo "<label for=\"uid\">UID</label> : \n";
echo "<input name=\"uid\" id=\"uid\" type=\"text\" size=\"15\" value=\"\">\n";
echo "</td></tr></div>\n";
echo "<tr><td valign=\"top\">\n";
echo '<label for="remarquespub">';
echo $publiccommentsmessage[$lang] . "</label> : \n";
echo "</td><td valign=\"bottom\"><textarea name=\"remarquespub\" id=\"remarquespub\" rows=\"2\" cols=\"60\" valign=\"bottom\"></textarea>\n";
echo "</td></tr><tr><td></td><td>\n";
echo "</td></tr>\n";
echo "</table>\n";
echo "</div></div>\n";
echo "<div class=\"box-footer\"><div class=\"box-footer-right\"></div></div>\n";
echo '<div class="box-submit-buttons">';
echo "<input type=\"submit\" value=\"" . $submitmessage[$lang] . "\" onsubmit=\"javascript:okcooc();document.body.style.cursor = 'wait';\">&nbsp;&nbsp;\n";
echo "<input type=\"reset\" value=\"" . $resetmessage[$lang] . "\">\n";
echo "</div>";
echo "</form>\n";
require ("includes/footer.php");
?>
