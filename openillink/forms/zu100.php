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
// Order form for Zu100
// 
// The follow customer values must be coded in the link URL :
// my_customer_code
// my_customer_name
// my_contact_name
// my_contact_phone
// my_contact_email
// my_contact_address
// my_contact_city
// my_contact_cp
// my_contact_cowntry
// 
// 29.03.2016 MDV add input validation using checkInput defined into toolkit.php

require_once ("includes/config.php");
require_once ("includes/authcookie.php");
require_once ("includes/connexion.php");
require_once ("includes/toolkit.php");

if (($monaut == "admin")||($monaut == "sadmin")||($monaut == "user")){

    $myContactName = ((!empty($_GET['my_contact_name'])) && isValidInput($_GET['my_contact_name'],200,'s',false))?$_GET['my_contact_name']:""; 
    $myCustomerName = ((!empty($_GET['my_customer_name'])) && isValidInput($_GET['my_customer_name'],200,'s',false))?$_GET['my_customer_name']:""; 
    $myContactAddress = ((!empty($_GET['my_contact_address'])) && isValidInput($_GET['my_contact_address'],255,'s',false))?$_GET['my_contact_address']:""; 
    $myContactCity = ((!empty($_GET['my_contact_city'])) && isValidInput($_GET['my_contact_city'],255,'s',false))?$_GET['my_contact_city']:""; 
    $myContactCp = ((!empty($_GET['my_contact_cp'])) && isValidInput($_GET['my_contact_cp'],10,'s',false))?$_GET['my_contact_cp']:"";
    $myContactCountry = ((!empty($_GET['my_contact_country'])) && isValidInput($_GET['my_contact_country'],50,'s',false))?$_GET['my_contact_country']:"";
    $myContactPhone = ((!empty($_GET['my_contact_phone'])) && isValidInput($_GET['my_contact_phone'],20,'s',false))?$_GET['my_contact_phone']:"";
    $myCustomerCode = ((!empty($_GET['my_customer_code'])) && isValidInput($_GET['my_customer_code'],20,'s',false))?$_GET['my_customer_code']:"";
    $myContactMail = ((!empty($_GET['my_contact_email'])) && isValidInput($_GET['my_contact_email'],100,'s',false))?$_GET['my_contact_email']:"";
    $journalTitle = ((!empty($enreg['titre_periodique'])) && isValidInput($enreg['titre_periodique'],255,'s',false))?$enreg['titre_periodique']:"";
    $issn = ((!empty($enreg['issn'])) && isValidInput($enreg['issn'],50,'s',false))?$enreg['issn']:"";
    $year = ((!empty($enreg['annee'])) && isValidInput($enreg['annee'],4,'s',false))?$enreg['annee']:"";
    $volume = ((!empty($enreg['volume'])) && isValidInput($enreg['volume'],100,'s',false))?$enreg['volume']:"";
    if (empty($issue2))
        $issue2 = ((!empty($enreg['issue'])) && isValidInput($enreg['issue'],100,'s',false))?$enreg['issue']:"";
    $pages = ((!empty($enreg['pages'])) && isValidInput($enreg['pages'],50,'s',false))?$enreg['pages']:"";
    $author = ((!empty($enreg['auteurs'])) && isValidInput($enreg['auteurs'],255,'s',false))?$enreg['auteurs']:"";
    $articleTitle = ((!empty($enreg['titre_article'])) && isValidInput($enreg['titre_article'],255,'s',false))?$enreg['titre_article']:"";
    $nom = ((!empty($enreg['nom'])) && isValidInput($enreg['nom'],200,'s',false))?$enreg['nom']:"";
    $illinkid = ((!empty($enreg['illinkid'])) && isValidInput($enreg['illinkid'],8,'s',false))?$enreg['illinkid']:"";
    
    echo "<h2>Envoi de la commande à Zurich 100</h2>\n";
    echo "<FORM method=\"post\" name=\"illform_mbc\" action=\"http://www.uzh.ch/cgi-bin/mailform\">\n";
    echo "<INPUT TYPE=\"hidden\" NAME=\"---TO\" value=\"orders@hbz.uzh.ch\">\n";
    echo "<INPUT TYPE=\"hidden\" NAME=\"-FROM\" value=\"mbc@hbz.uzh.ch\">\n";
    echo "<INPUT TYPE=\"hidden\" NAME=\"-NAME\" value=\"W*W*W\">\n";
    echo "<INPUT TYPE=\"hidden\" NAME=\"-Subject\" value=\"WWW-Bestellung\">\n";
    echo "<INPUT TYPE=\"hidden\" NAME=\"-is_required\" value=\"a-Name:A-SO:B-PY:l-Lieferart\">\n";
    echo "<INPUT TYPE=\"hidden\" NAME=\"-footer\" value=\"INCLUDE:http://www.hbz.uzh.ch/staticfiles/bestaet.html\">\n";
    echo "<Table Border=\"0\" Cellspacing=\"0\" Cellpadding=\"3\" width=\"100%\"></TD></TR>\n";
    echo "<TR><TD>Name: </TD><TD><INPUT TYPE=\"text\" SIZE=\"60\" NAME=\"a-Name\" value=\"$myContactName\"></TD></TR>\n";
    echo "<TR><TD>Inst:</TD><TD><INPUT TYPE=\"text\" SIZE=\"60\" NAME=\"b-Inst\" value=\"$myCustomerName\"></TD></TR>\n";
    echo "<TR><TD>Str.:</TD><TD><INPUT TYPE=\"text\" SIZE=\"60\" NAME=\"c-Str.\" value=\"$myContactAddress\"></TD></TR>\n";
    echo "<TR><TD>Ort:</TD><TD><INPUT TYPE=\"text\" SIZE=\"60\" NAME=\"d-Ort\" value=\"$myContactCity; $myContactCp, $myContactCountry\"></TD></TR>\n";
    echo "<TR><TD>Tel:</TD><TD><INPUT TYPE=\"text\" SIZE=\"60\" NAME=\"e-Tel\" value=\"$myContactPhone\"></TD></TR>\n";
    echo "<TR><TD>Bnum:</TD><TD><INPUT TYPE=\"text\" SIZE=\"60\" NAME=\"f-Bnum\" VALUE=\"$myCustomerCode\"></TD></TR>\n";
    echo "<TR><TD>email:</TD><TD><INPUT TYPE=\"text\" SIZE=\"60\" NAME=\"h-email\" VALUE=\"$myContactMail\"></TD></TR>\n";
    echo "<TR><TD>SO:</TD><TD><INPUT TYPE=\"text\" SIZE=\"60\" NAME=\"A-SO\" VALUE=\"$journalTitle\"></TD></TR>\n";
    echo "<TR><TD>ISSN:</TD><TD><INPUT TYPE=\"text\" SIZE=\"60\" NAME=\"H-ISSN\" VALUE=\"$issn\"></TD></TR>\n";
    echo "<TR><TD>PY:</TD><TD><INPUT TYPE=\"text\" SIZE=\"60\" NAME=\"B-PY\" VALUE=\"$year\"></TD></TR>\n";
    echo "<TR><TD>VO:</TD><TD><INPUT TYPE=\"text\" SIZE=\"60\" NAME=\"C-VO\" VALUE=\"$volume\"></TD></TR>\n";
    echo "<TR><TD>IS:</TD><TD><INPUT TYPE=\"text\" SIZE=\"60\" NAME=\"D-IS\" VALUE=\"$issue2\"></TD></TR>\n";
    echo "<TR><TD>PG:</TD><TD><INPUT TYPE=\"text\" SIZE=\"60\" NAME=\"E-PG\" VALUE=\"$pages\"></TD></TR>\n";
    echo "<TR><TD>AU:</TD><TD><INPUT TYPE=\"text\" SIZE=\"60\" NAME=\"F-AU\" VALUE=\"$author\"></TD></TR>\n";
    echo "<TR><TD>TI:</TD><TD><INPUT TYPE=\"text\" SIZE=\"60\" NAME=\"G-TI\" VALUE=\"$articleTitle\"></TD></TR>\n";
    echo "<TR><TD>Bemerkung:</TD><TD><INPUT TYPE=\"text\" SIZE=\"60\" NAME=\"z-Bemerkung\" VALUE=\"$nom ($illinkid)\"></TD></TR>\n";
    echo "<TR><TD>Ausland:</TD><TD><INPUT TYPE=\"text\" SIZE=\"60\" NAME=\"k-Ausland\" VALUE=\"Inland\"></TD></TR>\n";
    echo "<TR><TD>Lieferart:</TD><TD><INPUT TYPE=\"text\" SIZE=\"60\" NAME=\"l-Lieferart\" VALUE=\"pdf\"></TD></TR>\n";
    echo "<TR><TD>Conf:</TD><TD><INPUT TYPE=\"text\" SIZE=\"60\" NAME=\"z-Conf\" VALUE=\"Ja\"></TD></TR>\n";
    echo "<TR><TD></TD><TD><INPUT TYPE=\"submit\" NAME=\"Submit\" VALUE=\"Senden\"> &nbsp;\n";
    echo "<INPUT TYPE=\"reset\" NAME=\"Submit2\" VALUE=\"Löschen\"></TD></TR></TABLE>\n";
    echo "</FORM>\n";
}
else{
    require ("../includes/header.php");
    require ("../includes/loginfail.php");
    require ("../includes/footer.php");
}
?>
