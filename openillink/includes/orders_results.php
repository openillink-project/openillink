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
// List of orders used in different pages : list, search results, guest, etc.

require_once ("connexion.php");

if (($monaut == "admin")||($monaut == "sadmin")||($monaut == "user")||($monaut == "guest")){
    // Build Page Number Hyperlinks
    require ("pages.php");
/*
    $statusReq = "SELECT * from status;";
    $statusRes = dbquery($statusReq);
    $nbSt = iimysqli_num_rows($statusRes);
    for ($s=0 ; $s<$nbSt ; $s++){
        $currStatus = iimysqli_result_fetch_array($statusRes);
        $statusInfo[$currStatus['code']] = $currStatus;
    }
*/
    if ($total_results == 1)
        echo "<b>" . format_string(__("%total_results order found"), array('total_results' => $total_results))."</b>\n";
    else
        echo "<b>" . format_string(__("%total_results orders found"), array('total_results' => $total_results))."</b>\n";
    echo "<br />";
    echo "<br />";

    if ($nb > 0){
        echo "<table class=\"ordersList\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\n";
        echo "<tr><td valign=\"top\" width=\"95%\">\n";
        for ($i=0 ; $i<$nb ; $i++){
            $enreg = iimysqli_result_fetch_array($result);
            $id = $enreg['illinkid'];
            $type_doc = $enreg['type_doc'];
            $date = $enreg['date'];
            $stade = $enreg['stade'];
            $localisation = $enreg['localisation'];
            $nom = $enreg['nom'].', '.$enreg['prenom'];
            $mail = $enreg['mail'];
            $adresse = $enreg['adresse'].', '.$enreg['code_postal'].' '.$enreg['localite'];
            $statusname = $statusInfo[$stade]['title'][$lang];
            $statushelp = $statusInfo[$stade]['help'][$lang];
			$is_my_bib = ($monbib == $enreg['bibliotheque']);
			$is_my_service = (in_array($enreg['service'], $servListArray));
			$is_my_localisation = (in_array($localisation, $locListArray));
			$is_shared = ((!empty($enreg['bibliotheque'])) && in_array($enreg['bibliotheque'], $sharedLibrariesArray) && empty($localisation) && in_array($stade, $codeSpecial['new']));
			$statusrenew = 0;
			if (!empty($statusInfo[$stade]) && !empty($statusInfo[$stade]['special']) && $statusInfo[$stade]['special'] == 'renew'){
                $statusrenew = 1;
            }
            $statuscolor = $statusInfo[$stade]['color'];
            echo "<table Border=\"0\" Cellspacing=\"0\" Cellpadding=\"0\">\n";
            echo "<tr><td valign=\"top\" width=\"20\">&nbsp;</td>\n";
            echo "<td valign=\"top\" align=\"left\">\n";
            if ($monaut != "guest")
                echo "<a href=\"detail.php?table=orders&id=".$id."\" title=\"".__("See full record")."\">\n";
            require ("ordertop.php");
            echo "<br>\n";
            echo "</td></tr>\n";
            echo "<tr><td>&nbsp;</td></tr>\n";
            echo "<tr><td>&nbsp;</td></tr>\n";
            echo "</table>\n";
        }
        echo "</td></tr>\n";
        echo "</table>\n";
        // Build Page Number Hyperlinks
        require ("pages.php");
		// Build export menu
		//$url_params = $_GET;
		//$url_params['lang'] = 'de';
		//htmlspecialchars(http_build_query($url_params))
		$current_url = $current_http_protocol . $_SERVER['HTTP_HOST'] .  parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
		echo '<div class="dropdown is-hoverable is-up">
  <div class="dropdown-trigger">
    <button class="button" aria-haspopup="true" aria-controls="dropdown-menu">
      <span>' . __("Export") . '</span>
      <span class="icon is-small">
        <i class="fas fa-angle-down" aria-hidden="true"></i>
      </span>
    </button>
  </div>
<div class="dropdown-menu" id="dropdown-menu" role="menu">
    <div class="dropdown-content">
      <a href="'. $_SERVER['REQUEST_URI'] . '&export=csv" class="dropdown-item">
        ' . __("CSV (separator: semi-colons)") . '
      </a>
      <a href="'. $_SERVER['REQUEST_URI'] . '&export=endnotexml" class="dropdown-item">
        EndNote XML
      </a>
      <a href="'. $_SERVER['REQUEST_URI'] . '&export=ris" class="dropdown-item">
        RIS
      </a>
      <a href="'. $_SERVER['REQUEST_URI'] . '&export=medline" class="dropdown-item">
        MEDLINE
      </a>
    </div>
  </div>
</div>
  ';

    }
}
?>
