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
// Orders common informations displayed on the lists
//
if (($monaut == "admin")||($monaut == "sadmin")||($monaut == "user")||($monaut == "guest")){
    echo format_string(__("Order number %order_id"), array('order_id' => $id)). "</a>\n";
    echo "  |  ".__("Date")." : ".$date;
    echo "  |  ".__("Assignment library")." : ";
	if (!$is_my_bib) {
		echo '<span class="notMyBib">'. $enreg['bibliotheque'] . '</span>';
	} else {
		echo $enreg['bibliotheque'];
	}
    if ($enreg['localisation']){
		echo "  |  ".__("Localisation")." : ";
		if (!$is_my_localisation) {
			echo '<span class="notMyLocalisation">'.htmlspecialchars($localisation).'</span>';
		} else {
			echo htmlspecialchars($localisation);
		}
	}
	if ($is_shared){
		echo '<span class="isSharedOrder">'.__("Shared incoming order").'</span>';
	}
    if ($enreg['prepaye']=="on")
        echo "  |  <b><font color=\"green\">".__("Paid in advance")."</b></font>";
    if (($enreg['type_doc']!='article') && ($enreg['type_doc']!='Article'))
        echo "&nbsp;&nbsp;&nbsp;<img src=\"img/book.png\">";
    if ($monaut != "guest"){
        if ($enreg['remarques'])
            echo "&nbsp;&nbsp;&nbsp;<a href=\"#\" class=\"info\" onclick=\"return false\"><img src=\"img/alert.png\"><span>".htmlspecialchars($enreg['remarques'])."</span></a>";
    }
    else{
        if (!empty($enreg['remarquespub']))
            echo "&nbsp;&nbsp;&nbsp;<a href=\"#\" class=\"info\" onclick=\"return false\"><img src=\"img/alert.png\"><span>".htmlspecialchars($enreg['remarquespub'])."</span></a>";
    }
	if ($enreg['anonymized'] == 1) {
		    echo '&nbsp;&nbsp;&nbsp;<a href="#" class="info" onclick="return false"><i class="fas fa-mask" style="font-size: large;color: gray;"></i><span>'.__("Order anonymized after exceeding maximum data retention period").'</span></a>';
	}
    echo "<br />\n";
    echo "<b>". __("Status") ." : \n";
    echo "<a href=\"#\" class=\"statusLink\" onclick=\"return false\" title=\"".htmlspecialchars($statushelp)."\"><font color=\"".htmlspecialchars($statuscolor)."\">".htmlspecialchars($statusname)."</font></a></b>";
    if ((!empty($statusrenew)) && $statusrenew == 1){
        if (!empty($enreg['renouveler']))
            echo " le ".$enreg['renouveler'];
    }
    if ($enreg['urgent']=='1' || $enreg['urgent']=='oui')
        echo " | <b><font color=\"red\">".__("Urgent order")."</font></b>\n";
    if ($enreg['urgent']=='3' || $enreg['urgent']=='non')
        echo " | <font color=\"SteelBlue\">".__("Non-priority order")."</font>\n";
    echo "<br />\n";
    echo "<b>". __("User") ." : </b><a href=\"list.php?folder=search&champ=nom&term=".htmlspecialchars(urlencode ($nom))."\" title=\"".__("Non-priority order")."\">\n";
    echo htmlspecialchars($nom)."</a>\n";
    if ($mail)
        echo "  |  <b>". __("E-mail") ." : </b><a href=\"list.php?folder=search&champ=email&term=".htmlspecialchars(urlencode ($mail))."\" title=\"". __("Search for orders from this email") ."\">".htmlspecialchars($mail)."</a>\n";
    if ($enreg['adresse'])
        echo "  |  <b>". __("Address") ." : </b>".htmlspecialchars($adresse);
    if ($enreg['service']) {
		echo "  |  <b>". __("Service") ." : </b>";
		$service_class = "";
		if (!$is_my_service) {
			$service_class = ' class="notMyService" ';
		}
		echo "<a ".$service_class."href=\"list.php?folder=search&champ=service&term=".htmlspecialchars(urlencode ($enreg['service']))."\" title=\"".__("Search for orders from this service") ."\">".htmlspecialchars($enreg['service'])."</a>";
	}
	echo "<br />\n";
    if ($enreg['titre_article'])
        echo "<b>". __("Title") ." : </b><a href=\"list.php?folder=search&champ=atitle&term=".htmlspecialchars(urlencode ($enreg['titre_article']))."\" title=\"". __("Search for orders from this title") ."\">".htmlspecialchars($enreg['titre_article'])."</a><br />\n";
    if ($enreg['auteurs'])
        echo "<b>". __("Author(s)") ." : </b>".htmlspecialchars($enreg['auteurs'])."<br />\n";
    if ($enreg['titre_periodique']){
        if (in_array($enreg['type_doc'],array('article', 'Article'), TRUE))
            echo "<b>". __("Journal") ." : </b>\n";
        if ($enreg['type_doc']=='journal')
            echo "<b>". __("Journal") ." : </b>\n";
        if (in_array($enreg['type_doc'],array('Livre', 'book'), TRUE))
            echo "<b>". __("Book") ." : </b>\n";
        if (in_array($enreg['type_doc'],array('thesis', 'These', 'Thèse'), TRUE))
            echo "<b>". __("Thesis") ." : </b>\n";
        if (in_array($enreg['type_doc'],array('Chapitre', 'preprint', 'bookitem'), TRUE))
            echo "<b>". __("In") ." : </b>\n";
        if (in_array($enreg['type_doc'],array('autre', 'Autre', 'other'), TRUE))
            echo "<b>". __("In") ." : </b>\n";
        if (in_array($enreg['type_doc'],array('Congres', 'proceeding', 'conference'), TRUE))
            echo "<b>". __("In") ." : </b>\n";
        echo "</b><a href=\"list.php?folder=search&champ=title&term=".htmlspecialchars(urlencode ($enreg['titre_periodique']))."\" title=\"". __("Search for orders from this title") ."\">".htmlspecialchars($enreg['titre_periodique'])."</a><br />\n";
    }
    if ($enreg['volume'])
        echo "<b>". __("Volume") ." : </b>".htmlspecialchars($enreg['volume'])."  |  ";
    if ($enreg['numero'])
        echo "<b>". __("Issue") ." : </b>".htmlspecialchars($enreg['numero'])."  |  ";
    if ($enreg['pages'])
        echo "<b>". __("Pages") ." : </b>".htmlspecialchars($enreg['pages'])."  |  ";
    if ($enreg['annee'])
        echo "<b>". __("Year") ." : </b>".htmlspecialchars($enreg['annee']);
    if ($enreg['PMID']) {
		if ($enreg['volume'] or $enreg['pages'] or $enreg['annee']) {
			echo "  |  ";
		}
		echo "<b>PMID : </b><a href=\"https://www.ncbi.nlm.nih.gov/entrez/query.fcgi?otool=ichuvlib&cmd=Retrieve&db=pubmed&dopt=citation&list_uids=".htmlspecialchars($enreg['PMID'])."\" target=\"_blank\">".htmlspecialchars($enreg['PMID'])."</a>";
	}
}
?>
