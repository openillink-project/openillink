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
$emailTxt['fr']['commPar'] = "Commandé par";
$emailTxt['fr']['remarques'] = "Remarques";
$emailTxt['fr']['username'] = "Username";
$emailTxt['fr']['pwd'] = "Password";
$emailTxt['fr']['debut'] = "Bonjour,\r\n\r\n".
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
$emailTxt['fr']['signature'] = "Votre service de Prêt entre bibliothèques.\r\n\r\n";
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
                         $mail,
						 $signature){
    $finalMailText = "";

    if (($monaut == "admin")||($monaut == "sadmin")||($monaut == "user")||($monaut == "guest"))
    {
	  $short_titreper = (strlen($titreper) > 100) ? substr($titreper, 0, 90) . "[...]" : $titreper;
	  $short_titreart = (strlen($titreart) > 110) ? substr($titreart, 0, 100) . "[...]" : $titreart;
	  $short_auteurs = (strlen($enreg['auteurs']) > 50) ? substr($enreg['auteurs'], 0, 40) . "[...]" : $enreg['auteurs'];

      $subject = rawurlencode(html_entity_decode($mailAllTexts['fr']['commande']." (". $enreg['illinkid'].") : ".$short_titreper.".".$enreg['annee'].";".$enreg['volume'].":".$enreg['pages']));
      $finalMailText .= "&nbsp;&nbsp;<a href=\"";
	  $final_url = "mailto:".htmlspecialchars(urlencode(str_replace(" ", "", $mail)))."?subject=".htmlspecialchars($subject);
      $commandeDet = "";
      $refDet = "";
      if ($enreg['titre_article']!= '')
        $commandeDet .= rawurlencode($mailAllTexts['fr']['titre']." : ".html_entity_decode($short_titreart)."\r\n");
      if ($enreg['auteurs']!= '')
        $commandeDet .= rawurlencode($mailAllTexts['fr']['aut']." : ".html_entity_decode($short_auteurs)."\r\n");
      if ($enreg['titre_periodique']!= '')
        $commandeDet .= rawurlencode(html_entity_decode($mailAllTexts['fr']['src']." : ".$short_titreper."\r\n"));
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
      if ($enreg['nom']!= '' && $enreg['prenom']!= '')
        $commandeDet .= rawurlencode($mailAllTexts['fr']['commPar']." : ".$enreg['nom'].", ".$enreg['prenom']." \r\n");
      if ($enreg['refinterbib']!= '')
        $refDet .= rawurlencode(html_entity_decode($mailAllTexts['fr']['intRef']." : ".$enreg['refinterbib']."\r\n"));
      $body = rawurlencode($mailAllTexts['fr']['debut']);
      $body .= $commandeDet;
      $body .= $refDet;
      $body .= rawurlencode($mailAllTexts['fr']['infoservice']).$monuri.rawurlencode("login.php\r\n");
      $body .= rawurlencode($mailAllTexts['fr']['username']." : ").$maillog.
               rawurlencode(" | ".$mailAllTexts['fr']['pwd']." : ").$passwordg.
               rawurlencode("\r\n\r\n").
               rawurlencode($mailAllTexts['fr']['mentionDroitAuteur']).
               rawurlencode($mailAllTexts['fr']['salutations']."\r\n").
               /*rawurlencode(stripslashes("*****************************************************\r\n")).*/
               rawurlencode($mailAllTexts['fr']['signature']);
	  $body .= rawurlencode($signature);
	  $final_url .= "&amp;body=";
	  $final_url .= substr ( htmlspecialchars($body), 0 , 2050 - strlen($final_url));
      $finalMailText .= $final_url;
      $finalMailText .= "\" title=\"".htmlspecialchars($mailAllTexts['fr']['texteAideCurseur'])."\"><img src=\"img/email.gif\" height=\"20\"></a>\n";
    }
    echo $finalMailText; 
    /* MDV TODO: should be returned instead of directly displayed ; display should be performed by the caller*/
}
/***************************************************/

?>
