<?php
// ***************************************************************************
// ***************************************************************************
// ***************************************************************************
// This file is part of OpenILLink software.
// Copyright (C) 2005, 2006, 2007, 2008, 2009, 2010, 2011, 2012, 2013, 2015, 2016, 2017, 2018, 2024 CHUV.
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
// Quick search displayed in all the pages
//
require_once('connexion.php');
require_once('toolkit.php');
require_once('translations.php');

$controlSet = array('id', 'datecom', 'dateenv', 'datefact', 'date', 'statut', 'localisation', 'bibliotheque', 'nom', 'email', 'service', 'issn', 'pmid', 'doi', 'title', 'atitle', 'auteurs', 'reff', 'refb', 'all', 'myorders');
$champ = ((!empty($_GET['champ'])) && isValidInput($_GET['champ'],12,'s',false,$controlSet))?$_GET['champ']:'';
$champ2 = ((!empty($_GET['champ2'])) && isValidInput($_GET['champ2'],12,'s',false,$controlSet))?$_GET['champ2']:'';
$champ3 = ((!empty($_GET['champ3'])) && isValidInput($_GET['champ3'],12,'s',false,$controlSet))?$_GET['champ3']:'';
$champ2_operator = ((!empty($_GET['op2'])) && isValidInput($_GET['op2'], 3, 's', false, ['AND', 'OR', 'NOT']))?$_GET['op2']:'AND';
$champ3_operator = ((!empty($_GET['op3'])) && isValidInput($_GET['op3'], 3, 's', false, ['AND', 'OR', 'NOT']))?$_GET['op3']:'AND';
$myorders = ((!empty($_GET['myorders'])) && isValidInput($_GET['myorders'],1,'s',false,array("1")))?$_GET['myorders']:'';
$searchtype = ((!empty($_GET['searchtype'])) && isValidInput($_GET['searchtype'],8,'s',false,array("simple", "advanced")))?$_GET['searchtype']:'simple';
$term = (!empty($_GET['term']))?$_GET['term']:'';
$term2 = (!empty($_GET['term2']))?$_GET['term2']:'';
$term3 = (!empty($_GET['term3']))?$_GET['term3']:'';
$match = ((!empty($_GET['match'])) && isValidInput($_GET['match'], 8, 's', false, ['starts', 'contains', 'exact']))?$_GET['match']:'starts';
$match2 = ((!empty($_GET['match2'])) && isValidInput($_GET['match2'], 8, 's', false, ['starts', 'contains', 'exact']))?$_GET['match2']:'starts';
$match3 = ((!empty($_GET['match3'])) && isValidInput($_GET['match3'], 8, 's', false, ['starts', 'contains', 'exact']))?$_GET['match3']:'starts';


echo '<div class="columns is-mobile is-centered">
  <div class="column">';
echo "<div class=\"box\"><div class=\"box-content\">\n";
echo "<form action=\"list.php\" method=\"GET\" enctype=\"x-www-form-encoded\" name=\"recherche\">\n";
echo "<input name=\"action\" type=\"hidden\" value=\"recherche\">\n";
echo "<input name=\"folder\" type=\"hidden\" value=\"search\">\n";
//echo "<p>";
echo '<div class="field is-horizontal">
	 <div class="field-label is-normal">';
echo "<label class=\"label\" for=\"champ\"><strong>".__("Search")." </strong></label>\n";
echo '</div>';
echo '
<div class="field-body">
<div class="field has-addons is-expanded">
  <div class="control">
	<div class="select  is-fullwidth">
';
echo "<select name=\"champ\" id=\"champ\"  onchange=\"if(this.value=='id'){document.getElementById('advancedsearchmatchfieldone').style.display='none'}else if(document.getElementById('searchtype').value=='advanced'){document.getElementById('advancedsearchmatchfieldone').style.display=''}\">\n";
echo "<option value=\"id\"";
if ((!empty($champ)) && ($champ=='id') )
    echo " selected";
echo ">".__("Order number")."</option>\n";
echo "<option value=\"datecom\"";
if ((isset($champ))&&($champ=='datecom'))
    echo " selected";
echo ">".__("Order date (YYY-MM-DD)")."</option>\n";
echo "<option value=\"dateenv\"";
if ((!empty($champ))&&($champ=='dateenv'))
    echo " selected";
echo ">".__("Sending date (YYY-MM-DD)")."</option>\n";
echo "<option value=\"datefact\"";
if ((!empty($champ))&&($champ=='datefact'))
    echo " selected";
echo ">".__("Billing date (YYY-MM-DD)")."</option>\n";
echo "<option value=\"date\"";
if ((isset($champ))&&($champ=='date'))
    echo " selected";
echo ">".__("Date (any; YYY-MM-DD)")."</option>\n";
/*
echo "<option value=\"statut\"";
if ((!empty($champ))&&($champ=='statut'))
    echo " selected";
echo ">Statut</option>\n";
*/
echo "<option value=\"localisation\"";
if ((!empty($champ))&&($champ=='localisation'))
    echo " selected";
echo ">".__("Localization")."</option>\n";
echo "<option value=\"bibliotheque\"";
if ((!empty($champ))&&($champ=='bibliotheque'))
    echo " selected";
echo ">".__("Assignment Library")."</option>\n";
echo "<option value=\"nom\"";
if ((!empty($champ))&&($champ=='nom'))
    echo " selected";
echo ">".__("User name")."</option>\n";
echo "<option value=\"email\"";
if ((!empty($champ))&&($champ=='email'))
    echo " selected";
echo ">".__("User e-mail")."</option>\n";
echo "<option value=\"service\"";
if ((!empty($champ))&&($champ=='service'))
    echo " selected";
echo ">".__("Service")."</option>\n";
echo "<option value=\"issn\"";
if ((!empty($champ))&&($champ=='issn'))
    echo " selected";
echo ">ISSN</option>\n";
echo "<option value=\"pmid\"";
if ((!empty($champ))&&($champ=='pmid'))
    echo " selected";
echo ">PMID</option>\n";
echo "<option value=\"doi\"";
if ((!empty($champ))&&($champ=='doi'))
    echo " selected";
echo ">DOI</option>\n";
echo "<option value=\"title\"";
if ((!empty($champ))&&($champ=='title'))
    echo " selected";
echo ">".__("Journal title")."</option>\n";
echo "<option value=\"atitle\"";
if ((!empty($champ))&&($champ=='atitle'))
    echo " selected";
echo ">".__("Article title")."</option>\n";
echo "<option value=\"auteurs\"";
if ((!empty($champ))&&($champ=='auteurs'))
    echo " selected";
echo ">".__("Author(s)")."</option>\n";
echo "<option value=\"reff\"";
if ((!empty($champ))&&($champ=='reff'))
    echo " selected";
echo ">".__("Provider ref. (Subito n˚)")."</option>\n";
echo "<option value=\"refb\"";
if ((!empty($champ))&&($champ=='refb'))
    echo " selected";
echo ">".__("Internal library ref.")."</option>\n";
echo "<option value=\"all\"";
if ((!empty($champ))&&($champ=='all'))
    echo " selected";
echo ">".__("All over")."</option>\n";
echo "</select>\n";

echo '</div>
  </div>';

echo '  <div class="control" id="advancedsearchmatchfieldone" style="'.($searchtype == 'simple' ? 'display:none' :  ($champ == 'id' ? 'display:none' : '')) .'">
	<div class="select  is-fullwidth">
	  <select name="match">
	      <option value="starts" '.($match == 'starts' ? 'selected' : '').'>'. __("starts with").'</option>
		  <option value="contains" '.($match == 'contains' ? 'selected' : '').'>'. __("contains").'</option>
		  <option value="exact" '.($match == 'exact' ? 'selected' : '').'>'. __("is").'</option>
       </select></div>
  </div>';
  
$allStatus = readStatus();
echo '<p class="control">';
echo "<input class=\"input\" name=\"term\" type=\"text\" value=\"";
if (!empty($term))
    echo htmlspecialchars($_GET['term']);
echo "\">\n";
echo  '</p>';

echo '<a href="#" id="advancedsearchlink" class="is-size-7"  style="'.($searchtype == 'advanced' ? 'display:none' : '').'" onclick="showAdvancedSearch(this)"><i class="fas fa-caret-right"></i> '.__("Advanced search").'</a>';
echo '<input type="hidden" id="searchtype" name="searchtype" value="'.htmlspecialchars($searchtype).'"/>';

echo '</div>';
echo '</div>';
echo '</div>';





/* Second advanced search line */

echo '<div class="field is-horizontal" id="advancedsearchcondition2" style="'.($searchtype == 'simple' ? 'display:none' : '').'">
	 <div class="field-label is-normal">';
echo "<label class=\"label\" for=\"champ2\">&nbsp;</label>\n";
echo '</div>';
echo '
<div class="field-body">

<div class="field has-addons is-expanded">
  <div class="control">
   <div class="select">
	  <select name="op2">
	      <option value="AND" '.($champ2_operator == 'AND' ? 'selected' : '').'>'. __("and").'</option>
		  <option value="OR" '.($champ2_operator == 'OR' ? 'selected' : '').'>'. __("or").'</option>
		  <option value="NOT" '.($champ2_operator == 'NOT' ? 'selected' : '').'>'. __("and not").'</option>
       </select></div>
  </div>
  <div class="control">
	<div class="select  is-fullwidth">
';
echo "<select name=\"champ2\" id=\"champ2\" onchange=\"if(this.value=='id'){document.getElementById('advancedsearchmatchfieldtwo').style.display='none'}else if(document.getElementById('searchtype').value=='advanced'){document.getElementById('advancedsearchmatchfieldtwo').style.display=''}\">\n";
echo "<option value=\"id\"";
if ((!empty($champ2)) && ($champ2=='id') )
    echo " selected";
echo ">".__("Order number")."</option>\n";
echo "<option value=\"datecom\"";
if ((isset($champ2))&&($champ2=='datecom'))
    echo " selected";
echo ">".__("Order date (YYY-MM-DD)")."</option>\n";
echo "<option value=\"dateenv\"";
if ((!empty($champ2))&&($champ2=='dateenv'))
    echo " selected";
echo ">".__("Sending date (YYY-MM-DD)")."</option>\n";
echo "<option value=\"datefact\"";
if ((!empty($champ2))&&($champ2=='datefact'))
    echo " selected";
echo ">".__("Billing date (YYY-MM-DD)")."</option>\n";
echo "<option value=\"date\"";
if ((isset($champ2))&&($champ2=='date'))
    echo " selected";
echo ">".__("Date (any; YYY-MM-DD)")."</option>\n";
/*
echo "<option value=\"statut\"";
if ((!empty($champ))&&($champ=='statut'))
    echo " selected";
echo ">Statut</option>\n";
*/
echo "<option value=\"localisation\"";
if ((!empty($champ2))&&($champ2=='localisation'))
    echo " selected";
echo ">".__("Localization")."</option>\n";
echo "<option value=\"bibliotheque\"";
if ((!empty($champ2))&&($champ2=='bibliotheque'))
    echo " selected";
echo ">".__("Assignment Library")."</option>\n";
echo "<option value=\"nom\"";
if ((!empty($champ2))&&($champ2=='nom'))
    echo " selected";
echo ">".__("User name")."</option>\n";
echo "<option value=\"email\"";
if ((!empty($champ2))&&($champ2=='email'))
    echo " selected";
echo ">".__("User e-mail")."</option>\n";
echo "<option value=\"service\"";
if ((!empty($champ2))&&($champ2=='service'))
    echo " selected";
echo ">".__("Service")."</option>\n";
echo "<option value=\"issn\"";
if ((!empty($champ2))&&($champ2=='issn'))
    echo " selected";
echo ">ISSN</option>\n";
echo "<option value=\"pmid\"";
if ((!empty($champ2))&&($champ2=='pmid'))
    echo " selected";
echo ">PMID</option>\n";
echo "<option value=\"doi\"";
if ((!empty($champ2))&&($champ2=='doi'))
    echo " selected";
echo ">DOI</option>\n";
echo "<option value=\"title\"";
if ((!empty($champ2))&&($champ2=='title'))
    echo " selected";
echo ">".__("Journal title")."</option>\n";
echo "<option value=\"atitle\"";
if ((!empty($champ2))&&($champ2=='atitle'))
    echo " selected";
echo ">".__("Article title")."</option>\n";
echo "<option value=\"auteurs\"";
if ((!empty($champ2))&&($champ2=='auteurs'))
    echo " selected";
echo ">".__("Author(s)")."</option>\n";
echo "<option value=\"reff\"";
if ((!empty($champ2))&&($champ2=='reff'))
    echo " selected";
echo ">".__("Provider ref. (Subito n˚)")."</option>\n";
echo "<option value=\"refb\"";
if ((!empty($champ2))&&($champ2=='refb'))
    echo " selected";
echo ">".__("Internal library ref.")."</option>\n";
echo "<option value=\"all\"";
if ((!empty($champ2))&&($champ2=='all'))
    echo " selected";
echo ">".__("All over")."</option>\n";
echo "</select>\n";

echo '</div>
  </div>';

echo '  <div class="control"  id="advancedsearchmatchfieldtwo"  '.($champ2 == 'id' ? 'style="display:none"' : '').'>
	<div class="select  is-fullwidth">
	  <select name="match2">
	      <option value="starts" '.($match2 == 'starts' ? 'selected' : '').'>'. __("starts with").'</option>
		  <option value="contains" '.($match2 == 'contains' ? 'selected' : '').'>'. __("contains").'</option>
		  <option value="exact" '.($match2 == 'exact' ? 'selected' : '').'>'. __("is").'</option>
       </select></div>
  </div>';
  
echo '<p class="control">';
echo "<input class=\"input\" name=\"term2\" type=\"text\" value=\"";
if (!empty($term2))
    echo htmlspecialchars($_GET['term2']);
echo "\">\n";
echo  '</p>';
echo '</div>';
echo '</div>';
echo '</div>';

/* Third advanced search line */

echo '<div class="field is-horizontal" id="advancedsearchcondition3" style="'.($searchtype == 'simple' ? 'display:none' : '').'">
	 <div class="field-label is-normal">';
echo "<label class=\"label\" for=\"champ3\"> &nbsp;</label>\n";
echo '</div>';
echo '
<div class="field-body">
<div class="field has-addons is-expanded">
  <div class="control">
	<div class="select">
	  <select name="op3">
	      <option value="AND" '.($champ3_operator == 'AND' ? 'selected' : '').'>'. __("and").'</option>
		  <option value="OR" '.($champ3_operator == 'OR' ? 'selected' : '').'>'. __("or").'</option>
		  <option value="NOT" '.($champ3_operator == 'NOT' ? 'selected' : '').'>'. __("and not").'</option>
       </select></div>
  </div>
  <div class="control">
	<div class="select  is-fullwidth">
';
echo "<select name=\"champ3\" id=\"champ3\" onchange=\"if(this.value=='id'){document.getElementById('advancedsearchmatchfieldthree').style.display='none'}else if(document.getElementById('searchtype').value=='advanced'){document.getElementById('advancedsearchmatchfieldthree').style.display=''}\">\n";
echo "<option value=\"id\"";
if ((!empty($champ3)) && ($champ3=='id') )
    echo " selected";
echo ">".__("Order number")."</option>\n";
echo "<option value=\"datecom\"";
if ((isset($champ3))&&($champ3=='datecom'))
    echo " selected";
echo ">".__("Order date (YYY-MM-DD)")."</option>\n";
echo "<option value=\"dateenv\"";
if ((!empty($champ3))&&($champ3=='dateenv'))
    echo " selected";
echo ">".__("Sending date (YYY-MM-DD)")."</option>\n";
echo "<option value=\"datefact\"";
if ((!empty($champ3))&&($champ3=='datefact'))
    echo " selected";
echo ">".__("Billing date (YYY-MM-DD)")."</option>\n";
echo "<option value=\"date\"";
if ((isset($champ3))&&($champ3=='date'))
    echo " selected";
echo ">".__("Date (any; YYY-MM-DD)")."</option>\n";
/*
echo "<option value=\"statut\"";
if ((!empty($champ))&&($champ=='statut'))
    echo " selected";
echo ">Statut</option>\n";
*/
echo "<option value=\"localisation\"";
if ((!empty($champ3))&&($champ3=='localisation'))
    echo " selected";
echo ">".__("Localization")."</option>\n";
echo "<option value=\"bibliotheque\"";
if ((!empty($champ3))&&($champ3=='bibliotheque'))
    echo " selected";
echo ">".__("Assignment Library")."</option>\n";
echo "<option value=\"nom\"";
if ((!empty($champ3))&&($champ3=='nom'))
    echo " selected";
echo ">".__("User name")."</option>\n";
echo "<option value=\"email\"";
if ((!empty($champ3))&&($champ3=='email'))
    echo " selected";
echo ">".__("User e-mail")."</option>\n";
echo "<option value=\"service\"";
if ((!empty($champ3))&&($champ3=='service'))
    echo " selected";
echo ">".__("Service")."</option>\n";
echo "<option value=\"issn\"";
if ((!empty($champ3))&&($champ3=='issn'))
    echo " selected";
echo ">ISSN</option>\n";
echo "<option value=\"pmid\"";
if ((!empty($champ3))&&($champ3=='pmid'))
    echo " selected";
echo ">PMID</option>\n";
echo "<option value=\"doi\"";
if ((!empty($champ3))&&($champ3=='doi'))
    echo " selected";
echo ">DOI</option>\n";
echo "<option value=\"title\"";
if ((!empty($champ3))&&($champ3=='title'))
    echo " selected";
echo ">".__("Journal title")."</option>\n";
echo "<option value=\"atitle\"";
if ((!empty($champ3))&&($champ3=='atitle'))
    echo " selected";
echo ">".__("Article title")."</option>\n";
echo "<option value=\"auteurs\"";
if ((!empty($champ3))&&($champ3=='auteurs'))
    echo " selected";
echo ">".__("Author(s)")."</option>\n";
echo "<option value=\"reff\"";
if ((!empty($champ3))&&($champ3=='reff'))
    echo " selected";
echo ">".__("Provider ref. (Subito n˚)")."</option>\n";
echo "<option value=\"refb\"";
if ((!empty($champ3))&&($champ3=='refb'))
    echo " selected";
echo ">".__("Internal library ref.")."</option>\n";
echo "<option value=\"all\"";
if ((!empty($champ3))&&($champ3=='all'))
    echo " selected";
echo ">".__("All over")."</option>\n";
echo "</select>\n";

echo '</div>
  </div>';

echo '  <div class="control" id="advancedsearchmatchfieldthree" '.($champ3 == 'id' ? 'style="display:none"' : '').'>
	<div class="select  is-fullwidth">
	  <select name="match3">
	      <option value="starts" '.($match == 'starts' ? 'selected' : '').'>'. __("starts with").'</option>
		  <option value="contains" '.($match3 == 'contains' ? 'selected' : '').'>'. __("contains").'</option>
		  <option value="exact" '.($match3 == 'exact' ? 'selected' : '').'>'. __("is").'</option>
       </select></div>
  </div>';
  
echo '<p class="control">';
echo "<input class=\"input\" name=\"term3\" type=\"text\" value=\"";
if (!empty($term3))
    echo htmlspecialchars($_GET['term3']);
echo "\">\n";
echo  '</p>';

echo '<a href="#" id="simplesearchlink" class="is-size-7" style="'.($searchtype == 'simple' ? 'display:none' : '').'" onclick="showSimpleSearch(this)"><i class="fas fa-caret-up"></i> '.__("Simple search").'</a>';

echo '</div>';
echo '</div>';
echo '</div>';
//echo "</p>";
/*
echo "&nbsp;&nbsp;&nbsp;<a href=\"#\" class=\"info\" onclick=\"return false\">[Codes des étapes]<span>\n";
$reqstatus="SELECT code, title1 FROM status ORDER BY code ASC";
$resultstatus = dbquery($reqstatus);
while ($rowstatus = iimysqli_result_fetch_array($resultstatus)){
    echo $rowstatus["title1"] . " : " . $rowstatus["code"] . "<br/>\n";
}
echo "</span></a>&nbsp;\n";
*/

//echo "<p>";
echo '<div class="field is-horizontal">
  <div class="field-label is-normal">';
echo "<label class=\"label\" for=\"statuscode\"><strong>".__("Filter by status")." </strong></label>";
echo '</div>
	<div class="field-body">
		<div class="field is-narrow">
			<div class="control">
				<div class="select is-fullwidth">';
echo "<select name=\"statuscode\" id=\"statuscode\">\n";
echo '<option value="0"></option>';
foreach ($allStatus as $status){
    $labelStatus = $status['title1'];
    $labelCode = $status['code'];
    echo '<option value="'.htmlspecialchars($labelCode).'_st"';
    $statuscode = (isset($_GET['statuscode']))?$_GET['statuscode']:'';
    if ((!empty($statuscode)) && ($statuscode==($labelCode.'_st')) )
        echo " selected";
    echo ">".htmlspecialchars($labelStatus)."</option>\n";
}
echo "</select>";
echo '        </div>
      </div>
    </div>
  </div>
  </div>';
//echo "</p>";
if ($monaut != 'guest'){
	echo '<div class="field is-horizontal">
  <div class="field-label"></div>
  <div class="field-body">
  <div class="field">
  <div class="control">
        <label class="checkbox">';
	echo '<input type="checkbox" '.('1' == $myorders ? ' checked="checked" ' : '').'name="myorders" value="1" id="searchordersbyme"/>
	'.htmlspecialchars(__("Orders submitted by me only"));
	echo '
		</label>
		</div>
    </div>
  </div>
</div>';
}
echo '<div class="field is-horizontal">
  <div class="field-label"><strong>';
echo __("Use");
echo '</strong></div>
  <div class="field-body">
  <div class="field is-expanded">
      <div class="field">';
echo __("Search and filter can be used cumulatively or separately");
echo '</div>
    </div>
  </div>
</div>';
//echo "<p><strong>".__("Use").":</strong> ".__("Search and filter can be used cumulatively or separately")."</p>";
echo '
<div class="field is-horizontal">
  <div class="field-label">
    <!-- Left empty for spacing -->
  </div>
  <div class="field-body">
<div class="field">
  <div class="control">';
echo "<input class=\"button is-primary\" type=\"submit\" value=\"Ok\">";
echo '  </div>
</div>
  </div>
</div>';
echo "</form>\n";
echo "</div></div></div></div>\n";
echo "<br/>\n";
?>
