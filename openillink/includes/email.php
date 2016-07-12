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
// formated e-mail
//

/***************************************************/
/******* TEXTES pour l'email **********************/
/***************************************************/
$emailTxt = array();
$emailTxt['fr']['commande'] = "Commande";
$emailTxt['fr']['titre'] = "Titre";
$emailTxt['fr']['aut'] = "Auteur(s)";
$emailTxt['fr']['src'] = "Source";
$emailTxt['fr']['volume'] = "Volume";
$emailTxt['fr']['issue'] = "Issue";
$emailTxt['fr']['annee'] = "Année";
$emailTxt['fr']['suppl'] = "Suppl.";
$emailTxt['fr']['issn'] = "ISSN";
$emailTxt['fr']['pages'] = "Pages";
$emailTxt['fr']['isbn'] = "ISBN";
$emailTxt['fr']['pmid'] = "PMID";
//MDV - 2016.01.04: suite aux retours de SG, suppression du texte $emailTxt['fr']['autreId'] =  "Autre identifiant";
$emailTxt['fr']['intRef'] = "Réf int.";
//MDV - 2016.01.04: suite aux retours de SG, suppression du texte $emailTxt['fr']['commPar'] = "Commandé par";
$emailTxt['fr']['remarques'] = "Remarques";
$emailTxt['fr']['username'] = "Username";
$emailTxt['fr']['pwd'] = "Password";
$emailTxt['fr']['debut'] = "Bonjour\r\n\r\n".
"Nous vous remercions de votre commande:\r\n\r\n";
$emailTxt['fr']['infoservice'] = "\r\nSuivez vos commandes d'articles en temps réel en vous connectant à: \r\n";
/*
 MDV - 2016.01.04: suite aux retours de SG, remplace le message 
$emailTxt['fr']['debut'] = "Bonjour\r\n\r\n".
"Suite à votre commande d'article, nous avons le plaisir de vous transmettre en fichier attaché le document demandé:\r\n\r\n";
$emailTxt['fr']['infoservice'] = "\r\nNouveau service : suivez vos commandes d'articles en temps réel. Vous pouvez vous connecter à l'adresse \r\n";
 */
$emailTxt['fr']['mentionDroitAuteur'] = "Selon les règles en vigueur sur les droits d'auteur, le fichier joint concernant cette publication ne doit être utilisée que pour votre usage personnel et à des fins de recherche scientifique. Elle ne doit pas être reproduite ni distribuée.\r\n";
$emailTxt['fr']['salutations'] = "Meilleurs messages\r\n";
$emailTxt['fr']['texteAideCurseur'] = "envoyer un message avec le document attaché au lecteur";
$emailTxt['fr']['signature'] = "Votre service de Prêt entre bibliothèques.\r\n\r\n".
"BIBLIOTHÈQUE UNIVERSITAIRE DE MÉDECINE \r\n".
"CHUV BH 08 \r\n".
"Rue du Bugnon 46 \r\n".
"CH 1011 Lausanne SWITZERLAND \r\n".
"Courriel : docdelivery@chuv.ch \r\n".
"Tél. : +41 21 314 52 82 \r\n".
/*"Fax : +41 21 314 50 70 \r\n".*/
"Site web : http://www.bium.ch \r\n";
/***************************************************/

/***************************************************/
/******* encodage et création de l'email ***********/
/***************************************************/
function displayMailText($monaut,
                         $monuri,
                         $enreg,
                         $mailAllTexts,
                         $titreart,
                         $titreper,
                         $nom,
                         $maillog,
                         $passwordg,
                         $mail){
    $finalMailText = "";

    if (($monaut == "admin")||($monaut == "sadmin")||($monaut == "user")||($monaut == "guest"))
    {
      $subject = rawurlencode(html_entity_decode($mailAllTexts['fr']['commande']." (". $enreg['illinkid'].") : ".$titreper.".".$enreg['annee'].";".$enreg['volume'].":".$enreg['pages']));
      $finalMailText .= "&nbsp;&nbsp;<a href=\"mailto:".$mail."?subject=".$subject;
      $commandeDet = "";
      $refDet = "";
      if ($enreg['titre_article']!= '')
        $commandeDet .= rawurlencode($mailAllTexts['fr']['titre']." : ".stripslashes(html_entity_decode($titreart))."\r\n");
      if ($enreg['auteurs']!= '')
        $commandeDet .= rawurlencode($mailAllTexts['fr']['aut']." : ".stripslashes(html_entity_decode($enreg['auteurs']))."\r\n");
      if ($enreg['titre_periodique']!= '')
        $commandeDet .= rawurlencode(html_entity_decode($mailAllTexts['fr']['src']." : ".$titreper."\r\n"));
      if ($enreg['volume']!= '')
        $commandeDet .= rawurlencode($mailAllTexts['fr']['volume']." : ".$enreg['volume']."\r\n");
      if ($enreg['numero']!= '')
        $commandeDet .= rawurlencode($mailAllTexts['fr']['issue']." : ".$enreg['numero']."\r\n");
      if ($enreg['supplement']!= '')
        $commandeDet .= rawurlencode($mailAllTexts['fr']['suppl']." : ".$enreg['supplement']."\r\n");
      if ($enreg['pages']!= '')
        $commandeDet .= rawurlencode($mailAllTexts['fr']['pages']." : ".$enreg['pages']."\r\n");
      if ($enreg['annee']!= '')
        $commandeDet .= rawurlencode($mailAllTexts['fr']['annee']." : ".$enreg['annee']."\r\n");
      if ($enreg['issn']!= '')
        $commandeDet .= rawurlencode($mailAllTexts['fr']['issn']." : ".$enreg['issn']."\r\n");
      if ($enreg['isbn']!= '')
        $commandeDet .= rawurlencode($mailAllTexts['fr']['isbn']." : ".$enreg['isbn']."\r\n");
      if ($enreg['PMID']!= '')
        $commandeDet .= rawurlencode($mailAllTexts['fr']['pmid']." : ".$enreg['PMID']."\r\n");
      if ($enreg['refinterbib']!= '')
        $refDet .= rawurlencode(html_entity_decode($mailAllTexts['fr']['intRef']." : ".$enreg['refinterbib']."\r\n"));
      $body = rawurlencode(stripslashes($mailAllTexts['fr']['debut']));
      $body .= $commandeDet;
      $body .= $refDet;
      $body .= rawurlencode(stripslashes($mailAllTexts['fr']['infoservice'])).$monuri.rawurlencode(stripslashes("login.php\r\n"));
      $body .= rawurlencode(stripslashes($mailAllTexts['fr']['username']." : ")).$maillog.
               rawurlencode(stripslashes(" | ".$mailAllTexts['fr']['pwd']." : ")).$passwordg.
               rawurlencode(stripslashes("\r\n\r\n")).
               rawurlencode(stripslashes($mailAllTexts['fr']['mentionDroitAuteur'])).
               rawurlencode(stripslashes($mailAllTexts['fr']['salutations']."\r\n")).
               /*rawurlencode(stripslashes("*****************************************************\r\n")).*/
               rawurlencode(stripslashes($mailAllTexts['fr']['signature']));
      $finalMailText .= "&body=".substr ( $body, 0 , 1959 );
      $finalMailText .= "\" title=\"".$mailAllTexts['fr']['texteAideCurseur']."\"><img src=\"img/email.gif\" height=\"20\"></a>\n";
    }
    echo $finalMailText; 
    /* MDV TODO: should be returned instead of directly displayed ; display should be performed by the caller*/
}
/***************************************************/

?>
