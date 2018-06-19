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
// Links table : List of deep links used to search databases or transfert orders into axternal systems
//
require_once ("config.php");
require ("authcookie.php");
require_once ("connexion.php");

if (!empty($_COOKIE['illinkid'])){
    if (($monaut == "admin")||($monaut == "sadmin")){
        $myhtmltitle = $configname[$lang] . " : ".__("links management");
        require ("headeradmin.php");
        echo "\n";
		echo '<nav class="breadcrumb" aria-label="breadcrumbs">
  <ul>
    <li><a href="admin.php">'.__("Administration").'</a></li>
    <li class="is-active"><a href="list.php?table=links" aria-current="page">'.__("Management of external links").'</a></li>
  </ul>
</nav>';
        // links List
        echo "<h1 class=\"title\">".__("Management of external links")."</h1>\n";
        $req = "SELECT * FROM links ORDER BY library ASC, title ASC";// LIMIT ?, ?";
        $result = dbquery($req);//, array(0,200), 'ii');
        $total_results = iimysqli_num_rows($result);
        $nb = $total_results;
        // Construction du tableau de resultats
        echo "</center>\n";
        echo "<b><br/>".$total_results;
        if ($total_results == 1)
            echo " ".__("link found")."</b></font>\n";
        else
            echo " ".__("links found")."</b></font>\n";
        echo "<br/>";
        echo "<br/>";
        echo "<table class=\"table is-hoverable\" id=\"one-column-emphasis\" summary=\"\">\n";
        echo "<colgroup>\n";
        echo "<col class=\"oce-first\" />\n";
        echo "</colgroup>\n";
        echo "\n";
        echo "<thead>\n";
        echo "<tr>\n";
        echo "<th scope=\"col\">".__("Name")."</th>\n";
        echo "<th scope=\"col\">".__("URL")."</th>\n";
        echo "<th scope=\"col\">".__("Search by")."</th>\n";
        echo "<th scope=\"col\">".__("Order form")."</th>\n";
        /*echo "<th scope=\"col\">OpenURL</th>\n";*/
        echo "<th scope=\"col\">".__("Library")."</th>\n";
        echo "<th scope=\"col\">".__("List position")."</th>\n";
        echo "<th scope=\"col\">".__("Active link")."</th>\n";
        echo "<th scope=\"col\">&nbsp;</th>\n";
        echo "</tr>\n";
        echo "</thead>\n";
        echo "<tbody>\n";
        for ($i=0 ; $i<$nb ; $i++){
            $enreg = iimysqli_result_fetch_array($result);
            $linkid = $enreg['id'];
            $linktitle = $enreg['title'];
            $linkurl = $enreg['url'];
            if (strlen($linkurl)>40)
                $linkurls = substr($linkurl, 0, 40) . "[...]";
            else
                $linkurls = $linkurl;
            $linksearch_issn = $enreg['search_issn'];
            $linksearch_isbn = $enreg['search_isbn'];
            $linksearch_ptitle = $enreg['search_ptitle'];
            $linksearch_btitle = $enreg['search_btitle'];
            $linksearch_atitle = $enreg['search_atitle'];
            $linkorder_ext = $enreg['order_ext'];
            $linkorder_form = $enreg['order_form'];
            /*$linkopenurl = $enreg['openurl'];*/
            $linklibrary = $enreg['library'];
            $linkposition = $enreg['ordonnancement'];
            $linkactive = $enreg['active'];
            echo "<tr>\n";
            echo "<td><b>" . htmlspecialchars($linktitle) . "</b></td>\n";
            echo "<td><a href=\"".htmlspecialchars($linkurl)."\" target=\"_blank\">" . htmlspecialchars($linkurls) . "</a></td>\n";
            echo "<td>";
            $separateur = "";
            if ($linksearch_issn == 1){
                echo "ISSN";
                $separateur = " ; ";
            }
            if ($linksearch_isbn == 1){
                echo $separateur . "ISBN";
                $separateur = " ; ";
            }
            if ($linksearch_ptitle == 1){
                echo $separateur .__("Journal title");
                $separateur = " ; ";
            }
            if ($linksearch_btitle == 1){
                echo $separateur .__("Book title");
                $separateur = " ; ";
            }
            if ($linksearch_atitle == 1){
                echo $separateur .__("Article title");
                $separateur = " ; ";
            }
            echo "</td>\n";
            echo "<td>";
            $separateur = "";
            if ($linkorder_ext == 1){
                echo __("External");
                $separateur = " ; ";
            }
            if ($linkorder_form == 1){
                echo __("Internal");
                $separateur = " ; ";
            }
            echo "</td>\n";
            /*echo "<td>".$linkopenurl."</td>\n";*/
            echo "<td>".htmlspecialchars($linklibrary)."</td>\n";
            echo "<td>".htmlspecialchars($linkposition)."</td>\n";
            echo "<td>".htmlspecialchars($linkactive)."</td>\n";
            if (($monaut == "admin")||($monaut == "sadmin")){
                echo "<td><a title=\"".__("Edit the link")."\" href=\"edit.php?table=links&amp;id=".htmlspecialchars($linkid)."\"><i class=\"fas fa-edit has-text-primary\"></i></a></td>";
            }
            echo "</tr>\n";
        }
        echo "</tbody>\n";
        echo "</table>\n";
        echo "\n";
        echo "<br/><br/><ul>\n";
        echo "<b><a class=\"button is-primary\" href=\"new.php?table=links\">".__("Add a new link")."</a></b>\n";
        echo "<br/><br/>\n";
        echo "</ul>\n";
        require ("footer.php");
    }
    else{
        require ("header.php");
        echo __("Your rights are insufficient to edit this page")."</b></font></center><br /><br /><br /><br />\n";
        require ("footer.php");
    }
}
else{
    require ("header.php");
    require ("loginfail.php");
    require ("footer.php");
}
?>
