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
// Order form for Basel Library network
// 
// The follow customer values must be coded in the link URL :
// my_customer_code
// my_customer_password
// my_customer_name
// 
require_once ("includes/config.php");
require_once ("includes/authcookie.php");
require_once ("includes/connexion.php");
require_once ("includes/toolkit.php");

if (($monaut == "admin")||($monaut == "sadmin")||($monaut == "user")){
    $customerCode = $configBaselCode;
    $customerPassword = $configBaselPassword;
    $customerName = $configBaselName;

    $meduid = $enreg['PMID'];
    if (empty($meduid)) {
        $meduid = ((!empty($_GET['meduid'])) && isValidInput($_GET['meduid'],20,'s',false))?$_GET['meduid']:"";
    }
    $journal = $enreg['titre_periodique'];
    $issn = urlencode($enreg['issn']);
    $year = urlencode($enreg['annee']);
    $volume = urlencode($enreg['volume']);
    if (empty($issue2))
        $issue2 = urlencode($enreg['numero']);
    $pages = urlencode($enreg['pages']);
    $author = $enreg['auteurs'];
    $article = $enreg['titre_article'];
    $commentaire = "Ref interne:".$enreg['illinkid'];
    echo "<h2>Envoi de la commande au réseau Bâle/Berne</h2>\n";
    echo "<FORM method=\"post\" name=\"ILL\" action=\"http://www.ub.unibas.ch/cgi-bin/sfx_dod_m.pl\">\n";
    echo "<INPUT TYPE=\"hidden\" NAME=\"action\" value=\"submit\">\n";
    echo "<INPUT TYPE=\"hidden\" NAME=\"uid\" VALUE=\"".htmlspecialchars($customerCode)."\">\n";
    echo "<INPUT TYPE=\"hidden\" NAME=\"pwd\" VALUE=\"".htmlspecialchars($customerPassword)."\">\n";
    echo "<INPUT TYPE=\"hidden\" NAME=\"Source\" VALUE=\"OpenILLink ( ".htmlspecialchars($customerName)." )\">\n";
    echo "<INPUT TYPE=\"hidden\" NAME=\"pickup\" VALUE=\"EMAIL - E-Mail\">\n";
    echo "<INPUT TYPE=\"hidden\" NAME=\"sfxurl\" value=\"#sfxurl\">\n";
    echo "<INPUT TYPE=\"hidden\" NAME=\"meduid\" VALUE=\"".htmlspecialchars($meduid)."\">\n";
    echo "<INPUT TYPE=\"hidden\" NAME=\"Journal\" VALUE=\"".htmlspecialchars($journal)."\">\n";
    echo "<INPUT TYPE=\"hidden\" NAME=\"ISSN\" VALUE=\"".htmlspecialchars($issn)."\">\n";
    echo "<INPUT TYPE=\"hidden\" NAME=\"Year\" VALUE=\"".htmlspecialchars($year)."\">\n";
    echo "<INPUT TYPE=\"hidden\" NAME=\"Volume\" VALUE=\"".htmlspecialchars($volume)."\">\n";
    echo "<INPUT TYPE=\"hidden\" NAME=\"Issue\" VALUE=\"".htmlspecialchars($issue2)."\">\n";
    echo "<INPUT TYPE=\"hidden\" NAME=\"Pages\" VALUE=\"".htmlspecialchars($pages)."\">\n";
    echo "<INPUT TYPE=\"hidden\" NAME=\"Author\" VALUE=\"".htmlspecialchars($author)."\">\n";
    echo "<INPUT TYPE=\"hidden\" NAME=\"Article\" VALUE=\"".htmlspecialchars($article)."\">\n";
    echo "<INPUT TYPE=\"hidden\" NAME=\"bemerkung\" VALUE=\"".htmlspecialchars($commentaire)."\">\n";
    echo "<INPUT TYPE=\"hidden\" NAME=\"legal\" VALUE=\"on\">\n";
    echo "<INPUT TYPE=\"hidden\" NAME=\"B1\" VALUE=\"Bestellung abschicken\">\n";
    echo "</FORM>\n";
}
else{
    require_once('../includes/config.php');
    require_once('../includes/translations.php');
    require_once ("../includes/header.php");
    require_once ("../includes/loginfail.php");
    require_once ("../includes/footer.php");
}
?>
