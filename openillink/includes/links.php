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
// Links displayed on the order details
//
require_once('toolkit.php');
require_once('connexion.php');

echo "<div id=\"illinks\">\n";
echo "<div class=\"box\"><div class=\"box-content\">\n";
echo "<ul><li><a href=\"edit.php?table=orders&amp;id=".htmlspecialchars($enreg['illinkid'])."\"><b><font color=\"red\">\n";
echo __("Edit order")."</font></a></b></li>\n";
// echo "<ul><li><b><font color=\"grey\">\n";
// echo __("Edit order [under maintenance]")."</font></b></li>\n";
if ($directoryurl1 != ""){
   $mydirectory1search = str_replace("XNAMEX", urlencode($enreg['nom']),$directoryurl1);
   $mydirectory1search = str_replace("XFIRSTNAMEX",urlencode ($enreg['prenom']),$mydirectory1search);
   echo "<li><a href=\"" . htmlspecialchars($mydirectory1search) . "\" target=_blank title=\"" . __("Search the name in the directory of the hospital") . "\">\n";
   echo $directoryname1 . "</a></li>\n";
}

if ($directoryurl2 != ""){
   $mydirectory2search = str_replace("XNAMEX",urlencode ($enreg['nom']),$directoryurl2);
   $mydirectory2search = str_replace("XFIRSTNAMEX",urlencode ($enreg['prenom']),$mydirectory2search);
   echo "<li><a href=\"" . htmlspecialchars($mydirectory2search) . "\" target=_blank title=\"" . __("Search the name in the directory of the university") . "\">\n";
   echo $directoryname2 . "</a></li>\n";
}
echo "</ul>\n";

// MDV 22.07.2016 Commented out since following code is actually never used, should it be kept for some reason? 
//$titreebsco = str_replace(" ","* ",$stitleclean);
//$titreebsco = $titreebsco ."*";

// Add suppl. to issue 
$issue2 = $enreg['numero'];
if ($enreg['supplement']!=''){
   if ($enreg['numero']!='')
      $issue2 = $issue2 . " suppl. " . $enreg['supplement'];
   else
      $issue2 = "suppl. " . $enreg['supplement'];
}

// Links by article title
if ($enreg['titre_article']!=''){
    /* MDV - 15.12.2015 : display group title only if at least one link has actually been defined for the group; code moved from line 96 to line 103*/
    /*echo "</ul><b>Chercher par titre d'article</b>\n";*/ 
    $reqlinks="SELECT title, url, url_encoded FROM links WHERE search_atitle = 1 AND library = ? AND active = 1 ORDER BY ordonnancement, title ASC";
    $listlinks="";
    $resultlinks = dbquery($reqlinks, array($monbib), "s");
    $nblinks = iimysqli_num_rows($resultlinks);
    if ($nblinks > 0){
        echo "</ul><b>".__("Search by article title")."</b>\n";
        while ($rowlinks = iimysqli_result_fetch_array($resultlinks)){
            $linktitle = $rowlinks["title"];
            $linkurl = $rowlinks["url"];
            $linkurlencoded = $rowlinks["url_encoded"]==1?true:false;
            /* MDV - replace all placeholders with a single function call to replaceExistingPlaceHolders */
            //$linkurlreplace = str_replace("XTITLEX",urlencode ($enreg['titre_article']),$linkurl);
            $linkurlreplace = replaceExistingPlaceHolders($enreg,$enreg['titre_article'], $linkurl, $linkurlencoded);
            $listlinks.="<li><a href=\"" . htmlspecialchars($linkurlreplace) . "\" target=\"_blank\">" . htmlspecialchars($linktitle) . "</a></li>\n";
        }
        echo "<ul>\n";
        echo $listlinks;
        echo "</ul>\n";
    }
    // Links by journal title or ISSN
}
if (($enreg['type_doc']=='article')||($enreg['type_doc']=='Article')||($enreg['type_doc']=='preprint')||($enreg['type_doc']=='journal')){
    // Links by journal ISSN
    if ($enreg['issn']!=''){
        /* MDV - 15.12.2015 : display group title only if at least one link has actually been defined for the group; code moved from line 127 to line 134*/
        /*echo "<b>Chercher par ISSN</b>\n";*/
        $reqlinks="SELECT title, url, url_encoded,skip_words, skip_txt_after_mark FROM links WHERE search_issn = 1 AND library = ? AND active = 1 ORDER BY ordonnancement, title ASC";
        $listlinks="";
        $resultlinks = dbquery($reqlinks, array($monbib), "s");
        $nblinks = iimysqli_num_rows($resultlinks);
        if ($nblinks > 0){
            echo "<b>".__("Search by ISSN")."</b>\n";
            while ($rowlinks = iimysqli_result_fetch_array($resultlinks)){
                $linktitle = $rowlinks["title"];
                $linkurl = $rowlinks["url"];
                $linkurlencoded = $rowlinks["url_encoded"]==1?true:false;
                $linkskip_words = $rowlinks["skip_words"]==1?true:false;
                $linkskip_after_mark = $rowlinks["skip_txt_after_mark"]==1?true:false;
                /* MDV - replace all placeholders with a single function call to replaceExistingPlaceHolders */
                //$linkurlreplace = str_replace("XISSNX",urlencode ($enreg['issn']),$linkurl);
                // Cleaning journal title to improve search results
                $stitleclean = skipWords($linkskip_words, $enreg['titre_periodique']);
                // $stitleclean = str_replace("-"," ",$stitleclean);
                $stitleclean = skipTxtAfterSign($linkskip_after_mark, $stitleclean);
                $linkurlreplace = replaceExistingPlaceHolders($enreg,'',$linkurl, $linkurlencoded);
                $listlinks.="<li><a href=\"" . htmlspecialchars($linkurlreplace) . "\" target=\"_blank\">" . htmlspecialchars($linktitle) . "</a></li>\n";
            }
            echo "<ul>\n";
            echo $listlinks;
            echo "</ul>\n";
        }
    }
    //if ($enreg['issn']=='')
    if ($enreg['titre_periodique']!=''){
        /* MDV - 15.12.2015 : display group title only if at least one link has actually been defined for the group; code moved from line 154 to line 161*/
        /*echo "<b>Chercher par titre du périodique</b>\n";*/
        $reqlinks="SELECT title, url, url_encoded,skip_words, skip_txt_after_mark FROM links WHERE search_ptitle = 1 AND library = ? AND active = 1 ORDER BY ordonnancement, title ASC";
        $listlinks="";
        $resultlinks = dbquery($reqlinks, array($monbib), "s");
        $nblinks = iimysqli_num_rows($resultlinks);
        if ($nblinks > 0){
            echo "<b>".__("Search by journal title")."</b>\n";
            while ($rowlinks = iimysqli_result_fetch_array($resultlinks)){
                $linktitle = $rowlinks["title"];
                $linkurl = $rowlinks["url"];
                $linkurlencoded = $rowlinks["url_encoded"]==1?true:false;
                $linkskip_words = $rowlinks["skip_words"]==1?true:false;
                $linkskip_after_mark = $rowlinks["skip_txt_after_mark"]==1?true:false;

                /* MDV - replace all placeholders with a single function call to replaceExistingPlaceHolders */
                //$linkurlreplace = str_replace("XTITLEX",urlencode ($stitleclean),$linkurl);
                $stitleclean = skipWords($linkskip_words, $enreg['titre_periodique']);
                $stitleclean = skipTxtAfterSign($linkskip_after_mark, $stitleclean);

                $linkurlreplace = replaceExistingPlaceHolders($enreg, $stitleclean, $linkurl, $linkurlencoded);
                $listlinks.="<li><a href=\"" .  htmlspecialchars($linkurlreplace) . "\" target=\"_blank\">" . htmlspecialchars($linktitle) . "</a></li>\n";
            }
            echo "<ul>\n";
            echo $listlinks;
            echo "</ul>\n";
        }
    }
}
// Links for books, chapters and thesis
$documentWithISBN = array('book', 'bookitem', 'livre', 'Livre', 'Thèse', 'thesis');
if (in_array($enreg['type_doc'], $documentWithISBN, TRUE)){
    // Links by ISBN
    if ($enreg['isbn']!=''){
        /* MDV - 15.12.2015 : display group title only if at least one link has actually been defined for the group; code moved from line 186 to line 193*/
        //echo "<b>Chercher par ISBN</b>\n";
        $reqlinks="SELECT title, url, url_encoded,skip_words, skip_txt_after_mark FROM links WHERE search_isbn = 1 AND library = ? AND active = 1 ORDER BY ordonnancement, title ASC";
        $listlinks="";
        $resultlinks = dbquery($reqlinks, array($monbib), "s");
        $nblinks = iimysqli_num_rows($resultlinks);
        if ($nblinks > 0){
            echo "<b>".__("Search by ISBN")."</b>\n";
            while ($rowlinks = iimysqli_result_fetch_array($resultlinks)){
                $linktitle = $rowlinks["title"];
                $linkurl = $rowlinks["url"];
                $linkurlencoded = $rowlinks["url_encoded"]==1?true:false;
                $linkskip_words = $rowlinks["skip_words"]==1?true:false;
                $linkskip_after_mark = $rowlinks["skip_txt_after_mark"]==1?true:false;
                /* MDV - replace all placeholders with a single function call to replaceExistingPlaceHolders */
                //$linkurlreplace = str_replace("XISBNX",urlencode ($stitleclean),$linkurl);
                $stitleclean = skipWords($linkskip_words, $enreg['titre_periodique']);
                // $stitleclean = str_replace("-"," ",$stitleclean);
                $stitleclean = skipTxtAfterSign($linkskip_after_mark, $stitleclean);
                $linkurlreplace = replaceExistingPlaceHolders($enreg, $stitleclean, $linkurl, $linkurlencoded);
                $listlinks.="<li><a href=\"" . htmlspecialchars($linkurlreplace) . "\" target=\"_blank\">" . htmlspecialchars($linktitle) . "</a></li>\n";
            }
            echo "<ul>\n";
            echo $listlinks;
            echo "</ul>\n";
        }
    }
    // Links by Book Title
    if ($enreg['titre_periodique']!=''){
        /* MDV - 15.12.2015 : display group title only if at least one link has actually been defined for the group; code moved from line 212 to line 219*/
        //echo "<b>Chercher par titre du livre</b>\n";
        $reqlinks="SELECT title, url, url_encoded,skip_words, skip_txt_after_mark FROM links WHERE search_btitle = 1 AND library = ? AND active = 1 ORDER BY ordonnancement, title ASC";
        $listlinks="";
        $resultlinks = dbquery($reqlinks, array($monbib), "s");
        $nblinks = iimysqli_num_rows($resultlinks);
        if ($nblinks > 0){
            echo "<b>".__("Search by book title")."</b>\n";
            while ($rowlinks = iimysqli_result_fetch_array($resultlinks)){
                $linktitle = $rowlinks["title"];
                $linkurl = $rowlinks["url"];
                $linkurlencoded = $rowlinks["url_encoded"]==1?true:false;
                $linkskip_words = $rowlinks["skip_words"]==1?true:false;
                $linkskip_after_mark = $rowlinks["skip_txt_after_mark"]==1?true:false;
                // MDV - remplacement de tous les placeholders d'un coup
                //$linkurlreplace = str_replace("XTITLEX",urlencode ($stitleclean),$linkurl);
                $stitleclean = skipWords($linkskip_words, $enreg['titre_periodique']);
                // $stitleclean = str_replace("-"," ",$stitleclean);
                $stitleclean = skipTxtAfterSign($linkskip_after_mark, $stitleclean);
                $linkurlreplace = replaceExistingPlaceHolders($enreg, $stitleclean, $linkurl, $linkurlencoded);
                $listlinks.="<li><a href=\"" . htmlspecialchars($linkurlreplace) . "\" target=\"_blank\">" . htmlspecialchars($linktitle) . "</a></li>\n";
            }
            echo "<ul>\n";
            echo $listlinks;
            echo "</ul>\n";
        }
    }
}
// Links to transfert orders
$reqlinks="SELECT title, url, openurl, order_form, url_encoded,skip_words, skip_txt_after_mark FROM links WHERE (order_ext = 1 OR order_form = 1) AND library = ? AND active = 1 ORDER BY ordonnancement, title ASC";
$listlinks="";
$resultlinks = dbquery($reqlinks, array($monbib), "s");
$nblinks = iimysqli_num_rows($resultlinks);
if ($nblinks > 0){
    echo "<b>".__("Processing the order")."</b>\n";
    while ($rowlinks = iimysqli_result_fetch_array($resultlinks)){
        $linktitle = $rowlinks["title"];
        $linkurl = $rowlinks["url"];
        $linkopenurl = $rowlinks["openurl"];
        $linkorder_form = $rowlinks["order_form"];
        $linkskip_words = $rowlinks["skip_words"]==1?true:false;
        $linkskip_after_mark = $rowlinks["skip_txt_after_mark"]==1?true:false;
        $stitleclean = skipWords($linkskip_words, $enreg['titre_periodique']);
        // $stitleclean = str_replace("-"," ",$stitleclean);
        $stitleclean = skipTxtAfterSign($linkskip_after_mark, $stitleclean);

        if ($linkopenurl == 1){
            // OpenURL 0.1 Spec
            // http://alcme.oclc.org/openurl/docs/pdf/openurl-01.pdf
            // ORIGIN-DESCRIPTION ::= sid '=' VendorID ':' DatabaseID
            // GLOBAL-NAMESPACE ::= ( 'doi' | 'pmid' | 'bibcode' | 'oai' )
            // OBJECT-METADATA-ZONE ::= META-TAG '=' META -VALUE (& META -TAG '=' META-VALUE) *
            // META-TAG ::= ( 'genre' | 'aulast' | 'aufirst' | 'auinit' | 'auinit1' | 'auinitm' | 'coden' | 'issn' | 'eissn' | 'isbn' | 'title' | 'stitle' | 'atitle' | 'volume' | 'part' | 'issue' | 'spage' | 'epage' | 'pages' | 'artnum' | 'sici' | 'bici' | 'ssn' | 'quarter' | 'date' )
            // genre bundles:
            // journal (a journal, volume of a journal, issue of a journal)
            // book (a book)
            // conference (a publication bundling proceedings of a conference)
            // individual items:
            // article (a journal article)
            // preprint (a preprint)
            // proceeding (a conference proceeding)
            // bookitem (an item that is part of a book)
            // LOCAL-IDENTIFIER-ZONE ::= 'pid' '=' VCHAR+
            // 
            $pos = strpos($linkurl, "?");
            if ($pos === false)
                $linkurl = $linkurl . "?" . $openurlsid;
            else
                $linkurl = $linkurl . "&" . $openurlsid;
            if ($enreg['doi']!='')
                $linkurl .= "&id=doi:" . urlencode ($enreg['doi']);
            if ($enreg['PMID']!='')
                $linkurl .= "&id=pmid:" . urlencode ($enreg['PMID']);
            // if ($enreg['uid']!='')
            // $linkurl .= "&id=" . urlencode ($enreg['uid'];
            $linkurl .= "&genre=" . urlencode ($enreg['type_doc']);
            $linkurl .= "&aulast=" . urlencode ($enreg['auteurs']);
            $linkurl .= "&issn=" . urlencode ($enreg['issn']);
            $linkurl .= "&eissn=" . urlencode ($enreg['eissn']);
            $linkurl .= "&isbn=" . urlencode ($enreg['isbn']);
            $linkurl .= "&title=" . urlencode ($stitleclean);
            $linkurl .= "&atitle=" . urlencode ($enreg['titre_article']);
            $linkurl .= "&volume=" . urlencode ($enreg['volume']);
            $linkurl .= "&issue=" . urlencode ($issue2);
            $linkurl .= "&pages=" . urlencode ($enreg['pages']);
            $linkurl .= "&date=" . urlencode ($enreg['annee']);
        }
        if ($linkorder_form == 1)
            $linkurl = $linkurl . "&id=" . $enreg['illinkid'];
        $linkurlreplace = $linkurl;
        $linkurlreplace = str_replace("XSIDX",$openurlsid,$linkurlreplace);
        $linkurlencoded = $rowlinks["url_encoded"]==1?true:false;
        // MDV - remplacement de tous les placeholders d'un coup
        $linkurlreplace = replaceExistingPlaceHolders($enreg,$stitleclean,$linkurlreplace, $linkurlencoded);
        $listlinks.="<li><a href=\"" . htmlspecialchars($linkurlreplace) . "\" target=\"_blank\">" . htmlspecialchars($linktitle) . "</a></li>\n";
    }
    echo "<ul>\n";
    echo $listlinks;
    echo "</ul>\n";
}

echo "<br /></div></div><div class=\"box-footer\"><div class=\"box-footer-right\"></div></div>\n";
echo "</div>\n";
?>
