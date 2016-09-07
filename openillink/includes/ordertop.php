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
// Orders common informations displyed on the lists
//
if (($monaut == "admin")||($monaut == "sadmin")||($monaut == "user")||($monaut == "guest")){
    echo "Commande no : ".$id."</a>\n";
    echo "  |  Date : ".$date;
    echo "  |  Bibliothèque d'attribution : ";
    echo $enreg['bibliotheque'];
    if ($enreg['localisation'])
        echo "  |  Localisation : ".$localisation;
    if ($enreg['prepaye']=="on")
        echo "  |  <b><font color=\"green\">Payé à l'avance</b></font>";
    if (($enreg['type_doc']!='article') && ($enreg['type_doc']!='Article'))
        echo "&nbsp;&nbsp;&nbsp;<img src=\"img/book.png\">";
    if ($monaut != "guest"){
        if ($enreg['remarques'])
            echo "&nbsp;&nbsp;&nbsp;<a href=\"#\" class=\"info\" onclick=\"return false\"><img src=\"img/alert.png\"><span>".stripslashes($enreg['remarques'])."</span></a>";
    }
    else{
        if (!empty($enreg['remarquespub']))
            echo "&nbsp;&nbsp;&nbsp;<a href=\"#\" class=\"info\" onclick=\"return false\"><img src=\"img/alert.png\"><span>".stripslashes($enreg['remarquespub'])."</span></a>";
    }
    echo "<br />\n";
    echo "<b>Statut : \n";
    echo "<a href=\"#\" onclick=\"return false\" title=\"".htmlspecialchars($statushelp)."\"><font color=\"".$statuscolor."\">".$statusname."</font></a></b>";
    if ((!empty($statusrenew)) && $statusrenew == 1){
        if (!empty($enreg['renouveler']))
            echo " le ".$enreg['renouveler'];
    }
    if ($enreg['urgent']=='1' || $enreg['urgent']=='oui')
        echo " | <b><font color=\"red\">Commande URGENTE</font></b>\n";
    if ($enreg['urgent']=='3' || $enreg['urgent']=='non')
        echo " | <font color=\"SteelBlue\">Commande pas prioritaire</font>\n";
    echo "<br />\n";
    echo "<b>Lecteur : </b><a href=\"list.php?folder=search&champ=nom&term=".urlencode ($nom)."\" title=\"chercher les commandes de ce lecteur\">\n";
    echo $nom."</a>\n";
    if ($mail)
        echo "  |  <b>E-mail : </b><a href=\"list.php?folder=search&champ=email&term=".urlencode ($mail)."\" title=\"chercher les commandes de cet email\">".$mail."</a>\n";
    if ($enreg['adresse'])
        echo "  |  <b>Adresse : </b>".stripslashes($adresse);
    if ($enreg['service'])
        echo "  |  <b>Service : </b><a href=\"list.php?folder=search&champ=service&term=".$enreg['service']."\" title=\"chercher les commandes de ce service\">".$enreg['service']."</a>";
    echo "<br />\n";
    if ($enreg['titre_article'])
        echo "<b>Titre : </b><a href=\"list.php?folder=search&champ=atitle&term=".urlencode ($enreg['titre_article'])."\" title=\"chercher les commandes pour ce titre\">".stripslashes($enreg['titre_article'])."</a><br />\n";
    if ($enreg['auteurs'])
        echo "<b>Auteur(s) : </b>".stripslashes($enreg['auteurs'])."<br />\n";
    if ($enreg['titre_periodique']){
        if (in_array($enreg['type_doc'],array('article', 'Article'), TRUE))
            echo "<b>P&eacute;riodique : </b>\n";
        if ($enreg['type_doc']=='journal')
            echo "<b>P&eacute;riodique : </b>\n";
        if (in_array($enreg['type_doc'],array('Livre', 'book'), TRUE))
            echo "<b>Livre : </b>\n";
        if (in_array($enreg['type_doc'],array('thesis', 'These', 'Thèse'), TRUE))
            echo "<b>Th&egrave;se : </b>\n";
        if (in_array($enreg['type_doc'],array('Chapitre', 'preprint', 'bookitem'), TRUE))
            echo "<b>In : </b>\n";
        if (in_array($enreg['type_doc'],array('autre', 'Autre', 'other'), TRUE))
            echo "<b>In : </b>\n";
        if (in_array($enreg['type_doc'],array('Congres', 'proceeding', 'conference'), TRUE))
            echo "<b>In : </b>\n";
        echo "</b><a href=\"list.php?folder=search&champ=title&term=".urlencode (stripslashes($enreg['titre_periodique']))."\" title=\"chercher les commandes pour ce titre\">".stripslashes($enreg['titre_periodique'])."</a><br />\n";
    }
    if ($enreg['volume'])
        echo "<b>Vol. : </b>".$enreg['volume']."  |  ";
    if ($enreg['numero'])
        echo "<b>Issue : </b>".$enreg['numero']."  |  ";
    if ($enreg['pages'])
        echo "<b>Pages : </b>".$enreg['pages']."  |  ";
    if ($enreg['annee'])
        echo "<b>Ann&eacute;e : </b>".$enreg['annee'];
}
?>
