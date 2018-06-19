<?php
// ***************************************************************************
// ***************************************************************************
// ***************************************************************************
// This file is part of OpenILLink software.
// Copyright (C) 2005, 2006, 2007, 2008, 2009, 2010, 2011, 2012, 2013, 2015, 2016, 2017, 2018 CHUV.
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

$controlSet = array('id', 'datecom', 'dateenv', 'datefact', 'statut', 'localisation', 'nom', 'email', 'service', 'issn', 'pmid', 'title', 'atitle', 'auteurs', 'reff', 'refb', 'all', 'myorders');
$champ = ((!empty($_GET['champ'])) && isValidInput($_GET['champ'],12,'s',false,$controlSet))?$_GET['champ']:'';
$myorders = ((!empty($_GET['myorders'])) && isValidInput($_GET['myorders'],1,'s',false,array("1")))?$_GET['myorders']:'';

echo '<div class="columns is-mobile is-centered">
  <div class="column is-three-quarters is-narrow">';
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
echo "<select name=\"champ\">\n";
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

//echo "<font class=\"titleblack10\"> = &nbsp;\n";
$allStatus = readStatus();
echo '<p class="control">';
echo "<input class=\"input\" name=\"term\" type=\"text\" value=\"";
// TODO improve input validation
$term = (!empty($_GET['term']))?$_GET['term']:'';
if (!empty($term))
    echo htmlspecialchars($_GET['term']);
echo "\">\n";
echo  '</p>';
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
echo "<select name=\"statuscode\">\n";
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
