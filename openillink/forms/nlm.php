<?php
// ***************************************************************************
// ***************************************************************************
// ***************************************************************************
// OpenLinker is a web based library system designed to manage 
// journals, ILL, document delivery and OpenURL links
// 
// Copyright (C) 2012, Pablo Iriarte
// 
// This program is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
// 
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
// 
// You should have received a copy of the GNU General Public License
// along with this program.  If not, see <http://www.gnu.org/licenses/>.
// 
// ***************************************************************************
// ***************************************************************************
// ***************************************************************************
// Order form for the NLM
// 
// The follow customer values must be coded in the link URL :
// my_customer_code
// my_customer_name
// my_contact_first_name
// my_contact_last_name
// my_contact_phone
// my_contact_email
// my_price_limit
// my_delivery_email
// 29.03.2016 MDV add input validation using checkInput defined into toolkit.php
// 

require_once ("config.php");

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
$replaceby[1] = "id=\"authFirstName\" value=\"" . stripslashes($enreg['prenom']) . "\"";
$replace[2] = "id=\"authLastName\" value=\"\"";
$replaceby[2] = "id=\"authLastName\" value=\"" . stripslashes($enreg['nom']) . "\"";
// Contact Person
$replace[3] = "id=\"contactFirstName\" value=\"\"";
$replaceby[3] = "id=\"contactFirstName\" value=\"".$configillmanagerfirstname."\""; 
$replace[4] = "id=\"contactLastName\" value=\"\"";
$replaceby[4] = "id=\"contactLastName\" value=\"".$configillmanagerlastname."\"";
// Borrowing Library
$replace[5] = "id=\"libid\" value=\"\"";
$replaceby[5] = "id=\"libid\" value=\"".$configilllibid."\"";
$replace[6] = "id=\"borrowing\" value=\"\"";
$replaceby[6] = "id=\"borrowing\" value=\"".$configlibname."\"";//
$replace[7] = "id=\"phone\" value=\"\"";
$replaceby[7] = "id=\"phone\" value=\"".$configillmanagertel."\""; // 
$replace[8] = "id=\"email\" value=\"\"";
$replaceby[8] = "id=\"email\" value=\"".$configemaildelivery."\""; // 
// Request Information
if ($enreg['PMID']){
    $replace[9] = "id=\"pubmedid\" value=\"\"";
    $replaceby[9] = "id=\"pubmedid\" value=\"" . stripslashes($enreg['PMID']) . "\"";
}
if ($enreg['issn']){
    $replace[11] = "id=\"BookNumber\" value=\"\"";
    $replaceby[11] = "id=\"BookNumber\" value=\"" . stripslashes($enreg['issn']) . "\"";
}
if ($enreg['titre_periodique']){
    $replace[12] = "id=\"title\" value=\"\"";
    $replaceby[12] = "id=\"title\" value=\"" . stripslashes($enreg['titre_periodique']) . "\"";
}
if ($enreg['auteurs']){
    $replace[13] = "id=\"author\" value=\"\"";
    $replaceby[13] = "id=\"author\" value=\"" . stripslashes($enreg['auteurs']) . "\"";
}
if ($enreg['titre_article']){
    $replace[14] = "id=\"article\" value=\"\"";
    $replaceby[14] = "id=\"article\" value=\"" . stripslashes($enreg['titre_article']) . "\"";
}
if ($enreg['auteurs']){
    $replace[15] = "id=\"articleauthor\" value=\"\"";
    $replaceby[15] = "id=\"articleauthor\" value=\"" . stripslashes($enreg['auteurs']) . "\"";
}
if ($_GET['publisher']){
    $replace[16] = "id=\"publisher\" value=\"\"";
    $replaceby[16] = "id=\"publisher\" value=\"" . stripslashes($enreg['auteurs']) . "\"";
}
if ($enreg['annee']){
    $replace[17] = "id=\"year\" value=\"\"";
    $replaceby[17] = "id=\"year\" value=\"" . stripslashes($enreg['annee']) . "\"";
}
if ($enreg['volume']){
    $replace[18] = "id=\"volume\" value=\"\"";
    $replaceby[18] = "id=\"volume\" value=\"" . stripslashes($enreg['volume']) . "\"";
}
if ($enreg['issue']){
    $replace[19] = "id=\"issue\" value=\"\"";
    $replaceby[19] = "id=\"issue\" value=\"" . stripslashes($enreg['issue']) . "\"";
}
if ($enreg['pages']){
    $replace[20] = "id=\"pages\" value=\"\"";
    $replaceby[20] = "id=\"pages\" value=\"" . stripslashes($enreg['pages']) . "\"";
}
if (($enreg['type_doc'] != "article")&&($enreg['type_doc'] != "bookitem")&&($enreg['type_doc'] != "")){
    $replace[20] = "value=\"Journal\" checked />";
    $replaceby[20] = "value=\"Journal\" />";
    $replace[21] = "value=\"Monograph / Audiovisual\" />";
    $replaceby[21] = "value=\"Monograph / Audiovisual\" checked />";
}
$replace[22] = "id=\"willingtopay\" value=\"\"";
$replaceby[22] = "id=\"willingtopay\" value=\"".$configillmaxprice."\"";
if ($enreg['nom']){
    $replace[23] = "id=\"patronname\" value=\"\"";
    $replaceby[23] = "id=\"patronname\" value=\"" . stripslashes($enreg['nom']) . "\"";
}
if ($enreg['remarques']){
    $commentaire = stripslashes($enreg['remarques']);
}
else{
    $commentaire = (isset($enreg['nom']) && isValidInput($enreg['nom'],100,'s',false))? stripslashes($enreg['nom'].", "):"";
    $commentaire .= (isset($enreg['prenom']) && isValidInput($enreg['prenom'],100,'s',false))?stripslashes($enreg['prenom']." "):"";
    $commentaire .= (isset($enreg['illinkid']) && isValidInput($enreg['illinkid'],8,'i',false))? "(Ref interne:".$enreg['illinkid'].")":"";
}
$replace[24] = '<textarea name="comments" id="comments" value="" onkeyup="return ismaxlength(this)" cols="60" rows="2" maxlength="128"></textarea>';
$replaceby[24] = '<textarea name="comments" id="comments" value="" onkeyup="return ismaxlength(this)" cols="60" rows="2" maxlength="128">' . $commentaire . '</textarea>';
// Service & Delivery Information
$replace[25] = "name=\"service\" value=\"Copy\" />";
$replaceby[25] = "name=\"service\" value=\"Copy\" checked />";
$replace[26] = "name=\"delivery\" value=\"EmailPDF\" />";
$replaceby[26] = "name=\"delivery\" value=\"EmailPDF\" checked />";
$replace[27] = "id=\"pdfemail\" value=\"\"";
$replaceby[27] = "id=\"pdfemail\" value=\"".$configemaildelivery."\"";


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