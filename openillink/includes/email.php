<?php
// ***************************************************************************
// ***************************************************************************
// ***************************************************************************
// This file is part of OpenILLink software.
// Copyright (C) 2005, 2006, 2007, 2008, 2009, 2010, 2011, 2012, 2013, 2015, 2016, 2017, 2018, 2019 CHUV.
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
						 $signature,
						 $lang){
    $finalMailText = "";

    if (($monaut == "admin")||($monaut == "sadmin")||($monaut == "user")||($monaut == "guest"))
    {
	  $short_titreper = (strlen($titreper) > 100) ? substr($titreper, 0, 90) . "[...]" : $titreper;
	  $short_titreart = (strlen($titreart) > 110) ? substr($titreart, 0, 100) . "[...]" : $titreart;
	  $short_auteurs = (strlen($enreg['auteurs']) > 50) ? substr($enreg['auteurs'], 0, 40) . "[...]" : $enreg['auteurs'];

      $subject = rawurlencode(html_entity_decode(__("Order")." (". $enreg['illinkid'].") : ".$short_titreper.".".$enreg['annee'].";".$enreg['volume'].":".$enreg['pages']));
      $finalMailText .= "&nbsp;&nbsp;<a href=\"";
	  $final_url = "mailto:".htmlspecialchars(urlencode(str_replace(" ", "", $mail)))."?subject=".htmlspecialchars($subject);
      $commandeDet = "";
      $refDet = "";
      if ($enreg['titre_article']!= '')
        $commandeDet .= rawurlencode(__("Title")." : ".html_entity_decode($short_titreart)."\r\n");
      if ($enreg['auteurs']!= '')
        $commandeDet .= rawurlencode(__("Author(s)")." : ".html_entity_decode($short_auteurs)."\r\n");
      if ($enreg['titre_periodique']!= '')
        $commandeDet .= rawurlencode(html_entity_decode(__("Source")." : ".$short_titreper."\r\n"));
      if ($enreg['volume']!= '')
        $commandeDet .= rawurlencode(__("Volume")." : ".$enreg['volume']."\r\n");
      if ($enreg['numero']!= '')
        $commandeDet .= rawurlencode(__("Issue")." : ".$enreg['numero']."\r\n");
      if ($enreg['supplement']!= '')
        $commandeDet .= rawurlencode(__("Suppl.")." : ".$enreg['supplement']."\r\n");
      if ($enreg['pages']!= '')
        $commandeDet .= rawurlencode(__("Pages")." : ".$enreg['pages']."\r\n");
      if ($enreg['annee']!= '')
        $commandeDet .= rawurlencode(__("Year")." : ".$enreg['annee']."\r\n");
      if ($enreg['issn']!= '')
        $commandeDet .= rawurlencode(__("ISSN")." : ".$enreg['issn']."\r\n");
      if ($enreg['isbn']!= '')
        $commandeDet .= rawurlencode(__("ISBN")." : ".$enreg['isbn']."\r\n");
      if ($enreg['PMID']!= '')
        $commandeDet .= rawurlencode(__("PMID")." : ".$enreg['PMID']."\r\n");
      if ($enreg['nom']!= '' && $enreg['prenom']!= '')
        $commandeDet .= rawurlencode(__("Ordered by")." : ".$enreg['nom'].", ".$enreg['prenom']." \r\n");
      if ($enreg['refinterbib']!= '')
        $refDet .= rawurlencode(html_entity_decode(__("Internal library ref.")." : ".$enreg['refinterbib']."\r\n"));

      $body = rawurlencode($mailAllTexts[$lang]['start']. "\r\n\r\n");
      $body .= $commandeDet;
      $body .= $refDet;
      $body .= rawurlencode("\r\n".$mailAllTexts[$lang]['infoservice']).rawurlencode(" \r\n").$monuri.rawurlencode("login.php\r\n");
      $body .= rawurlencode(__("Username")." : ").$maillog.
               rawurlencode(" | ".__("Password")." : ").$passwordg.
               rawurlencode("\r\n\r\n").
               rawurlencode($mailAllTexts[$lang]['copyrightWarning']."\r\n\r\n").
               rawurlencode($mailAllTexts[$lang]['greetings']."\r\n").
               /*rawurlencode(stripslashes("*****************************************************\r\n")).*/
               rawurlencode($mailAllTexts[$lang]['signature']."\r\n\r\n");
	  $body .= rawurlencode($signature);
	  $final_url .= "&amp;body=";
	  $final_url .= substr ( htmlspecialchars($body), 0 , 2050 - strlen($final_url));
      $finalMailText .= $final_url;
      $finalMailText .= "\" title=\"".htmlspecialchars(__("Send a message with the attached document to the user"))."\"><img src=\"img/email.gif\" height=\"20\"></a>\n";
    }
    echo $finalMailText; 
    /* MDV TODO: should be returned instead of directly displayed ; display should be performed by the caller*/
}
/***************************************************/

?>
