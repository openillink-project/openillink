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
// Order form for the NLM
// 
// The following customer values can be coded in the link URL :
// - my_customer_code
// - my_customer_name
// - my_contact_first_name
// - my_contact_last_name
// - my_contact_phone
// - my_contact_email
// - my_price_limit
// - my_delivery_email
//
// If the above values are not specified, then default values are read from config:
// - configilllibid
// - configlibname
// - configillmanagerfirstname
// - configillmanagerlastname
// - configillmanagertel
// - configemaildelivery
// - configillmaxprice
// - configemaildelivery

if (file_exists ("config.php"))
    require_once ("config.php");

$my_customer_code = $configilllibid;
$my_customer_name = $configlibname;
$my_contact_first_name = $configillmanagerfirstname;
$my_contact_last_name = $configillmanagerlastname;
$my_contact_phone = $configillmanagertel;
$my_contact_email = $configemaildelivery;
$my_price_limit = $configillmaxprice;
$my_delivery_email = $configemaildelivery;

// overwrite default config with values set in URL, if they exist
if (isset($_GET["my_customer_code"])) {
	$my_customer_code = $_GET['my_customer_code'];
}
if (isset($_GET["my_customer_name"])) {
	$my_customer_name = $_GET['my_customer_name'];
}
if (isset($_GET["my_contact_first_name"])) {
	$my_contact_first_name = $_GET['my_contact_first_name'];
}
if (isset($_GET["my_contact_last_name"])) {
	$my_contact_last_name = $_GET['my_contact_last_name'];
}
if (isset($_GET["my_contact_email"])) {
	$my_contact_email = $_GET['my_contact_email'];
}
if (isset($_GET["my_price_limit"])) {
	$my_price_limit = $_GET['my_price_limit'];
}
if (isset($_GET["my_delivery_email"])) {
	$my_delivery_email = $_GET['my_delivery_email'];
}

// create curl resource
$ch = curl_init();

// set url
// $url = "http://wwwcf.nlm.nih.gov/mainweb/siebel/ill/index.cfm";
// wwwcf.nlm.nih.gov/mainweb/siebel/ill/index.cfm

curl_setopt($ch, CURLOPT_URL, "wwwcf.nlm.nih.gov/mainweb/siebel/ill/index.cfm");

//return the transfer as a string
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);


// set the UA
curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);

// Alternatively, lie, and pretend to be a browser
// curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1)');

// $output contains the output string
 curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
$output = curl_exec($ch);

// Copyright & Authorization
$replace[0] = "value=\"108(g)(2) Guidelines (CCG)\"";
$replaceby[0] = $replace[0] . " checked ";

// Authorized by
$replace[1] = "id=\"authFirstName\" value=\"\"";
$replaceby[1] = "id=\"authFirstName\" value=\"" . htmlspecialchars($enreg['prenom']) . "\"";
$replace[2] = "id=\"authLastName\" value=\"\"";
$replaceby[2] = "id=\"authLastName\" value=\"" . htmlspecialchars($enreg['nom']) . "\"";
// Contact Person
$replace[3] = "id=\"contactFirstName\" value=\"\"";
$replaceby[3] = "id=\"contactFirstName\" value=\"".htmlspecialchars($my_contact_first_name)."\"";
$replace[4] = "id=\"contactLastName\" value=\"\"";
$replaceby[4] = "id=\"contactLastName\" value=\"".htmlspecialchars($my_contact_last_name)."\"";
// Borrowing Library
$replace[5] = "id=\"libid\" value=\"\"";
$replaceby[5] = "id=\"libid\" value=\"".htmlspecialchars($my_customer_code)."\"";
$replace[6] = "id=\"borrowing\" value=\"\"";
$replaceby[6] = "id=\"borrowing\" value=\"".htmlspecialchars($my_customer_code)."\"";//
$replace[7] = "id=\"phone\" value=\"\"";
$replaceby[7] = "id=\"phone\" value=\"".htmlspecialchars($my_contact_phone)."\""; //
$replace[8] = "id=\"email\" value=\"\"";
$replaceby[8] = "id=\"email\" value=\"".htmlspecialchars($my_contact_email)."\""; //
// Request Information
if ($enreg['PMID']){
    $replace[9] = "id=\"pubmedid\" value=\"\"";
    $replaceby[9] = "id=\"pubmedid\" value=\"" . htmlspecialchars($enreg['PMID']) . "\"";
}
if ($enreg['issn']){
    $replace[11] = "id=\"BookNumber\" value=\"\"";
    $replaceby[11] = "id=\"BookNumber\" value=\"" . htmlspecialchars($enreg['issn']) . "\"";
}
if ($enreg['titre_periodique']){
    $replace[12] = "id=\"title\" value=\"\"";
    $replaceby[12] = "id=\"title\" value=\"" . htmlspecialchars($enreg['titre_periodique']) . "\"";
}
if ($enreg['auteurs']){
    $replace[13] = "id=\"author\" value=\"\"";
    $replaceby[13] = "id=\"author\" value=\"" . htmlspecialchars($enreg['auteurs']) . "\"";
}
if ($enreg['titre_article']){
    $replace[14] = "id=\"article\" value=\"\"";
    $replaceby[14] = "id=\"article\" value=\"" . htmlspecialchars($enreg['titre_article']) . "\"";
}
if ($enreg['auteurs']){
    $replace[15] = "id=\"articleauthor\" value=\"\"";
    $replaceby[15] = "id=\"articleauthor\" value=\"" . htmlspecialchars($enreg['auteurs']) . "\"";
}
if ($_GET['publisher']){
    $replace[16] = "id=\"publisher\" value=\"\"";
    $replaceby[16] = "id=\"publisher\" value=\"" . htmlspecialchars($enreg['auteurs']) . "\"";
}
if ($enreg['annee']){
    $replace[17] = "id=\"year\" value=\"\"";
    $replaceby[17] = "id=\"year\" value=\"" . htmlspecialchars($enreg['annee']) . "\"";
}
if ($enreg['volume']){
    $replace[18] = "id=\"volume\" value=\"\"";
    $replaceby[18] = "id=\"volume\" value=\"" . htmlspecialchars($enreg['volume']) . "\"";
}
if ($enreg['issue']){
    $replace[19] = "id=\"issue\" value=\"\"";
    $replaceby[19] = "id=\"issue\" value=\"" . htmlspecialchars($enreg['issue']) . "\"";
}
if ($enreg['pages']){
    $replace[20] = "id=\"pages\" value=\"\"";
    $replaceby[20] = "id=\"pages\" value=\"" . htmlspecialchars($enreg['pages']) . "\"";
}
if (($enreg['type_doc'] != "article")&&($enreg['type_doc'] != "bookitem")&&($enreg['type_doc'] != "")){
    $replace[20] = "value=\"Journal\" checked />";
    $replaceby[20] = "value=\"Journal\" />";
    $replace[21] = "value=\"Monograph / Audiovisual\" />";
    $replaceby[21] = "value=\"Monograph / Audiovisual\" checked />";
}
$replace[22] = "id=\"willingtopay\" value=\"\"";
$replaceby[22] = "id=\"willingtopay\" value=\"".htmlspecialchars($my_price_limit)."\"";
if ($enreg['nom']){
    $replace[23] = "id=\"patronname\" value=\"\"";
    $replaceby[23] = "id=\"patronname\" value=\"" . htmlspecialchars($enreg['nom']) . "\"";
}
if ($enreg['remarques']){
    $commentaire = htmlspecialchars($enreg['remarques']);
}
else{
    $commentaire = ((!empty($enreg['nom'])) && isValidInput($enreg['nom'],100,'s',false))? htmlspecialchars($enreg['nom'].", "):"";
    $commentaire .= ((!empty($enreg['prenom'])) && isValidInput($enreg['prenom'],100,'s',false))?htmlspecialchars($enreg['prenom']." "):"";
    $commentaire .= ((!empty($enreg['illinkid'])) && isValidInput($enreg['illinkid'],8,'i',false))? "(Ref interne:".htmlspecialchars($enreg['illinkid']).")":"";
}
$replace[24] = '<textarea name="comments" id="comments" value="" onkeyup="return ismaxlength(this)" cols="60" rows="2" maxlength="128"></textarea>';
$replaceby[24] = '<textarea name="comments" id="comments" value="" onkeyup="return ismaxlength(this)" cols="60" rows="2" maxlength="128">' . $commentaire . '</textarea>';
// Service & Delivery Information
$replace[25] = "name=\"service\" value=\"Copy\" />";
$replaceby[25] = "name=\"service\" value=\"Copy\" checked />";
$replace[26] = "name=\"delivery\" value=\"EmailPDF\" />";
$replaceby[26] = "name=\"delivery\" value=\"EmailPDF\" checked />";
$replace[27] = "id=\"pdfemail\" value=\"\"";
$replaceby[27] = "id=\"pdfemail\" value=\"".htmlspecialchars($my_delivery_email)."\"";


$output = str_replace($replace, $replaceby , $output);
// close curl resource to free up system resources
curl_close($ch);
 if(curl_error($ch)){
    echo 'error:' . curl_error($ch);
}
elseif($output)
    echo $output;
else 
    echo "output nok and error could not be retrieved";

?>