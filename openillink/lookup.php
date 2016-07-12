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
// Lookup page to import external content from bibliographic databases : PubMed, Web of Science, CrossRef, RERO, etc.
// 01.04.2016, MDV, Add input validation
//
require_once ("includes/config.php");
require_once ("includes/toolkit.php");
$isbn = $_GET['isbn'];
if(isset($isbn) && !empty($isbn)){
    $isbn = (isset($isbn) &&  isValidInput($isbn,17,'s',false))?trim($isbn):NULL;
    $url = "http://opac.rero.ch/gateway?function=MARCSCR&search=KEYWORD&u1=7&rootsearch=KEYWORD&t1=" . $isbn;
    //  $url = $_SERVER['QUERY_STRING'];
    $ch = curl_init($url);
    curl_exec($ch);
    curl_close($ch);
}

$reroid = $_GET['reroid'];
if(isset($reroid) && !empty($reroid)){
    $reroid = (isset($reroid) &&  isValidInput($reroid,50,'s',false))?trim($reroid):NULL;
    //  $url = $_SERVER['QUERY_STRING'];
    $url = "http://opac.rero.ch/gateway?function=MARCSCR&search=KEYWORD&u1=12&rootsearch=KEYWORD&t1=$reroid";
    $ch = curl_init($url);
    curl_exec($ch);
    curl_close($ch);
}

$pmid = $_GET['pmid'];
if(isset($pmid) && !empty($pmid)){
    $pmid = (isset($pmid) &&  isValidInput($pmid,50,'s',false))?trim($pmid):NULL;
    $url = "http://eutils.ncbi.nlm.nih.gov/entrez/eutils/esummary.fcgi?db=pubmed&retmode=xml&tool=OpenLinker&email=" . $configemail . "&id=" . $pmid;
    //  $url = $_SERVER['QUERY_STRING'];
    $ch = curl_init($url);
    curl_exec($ch);
    curl_close($ch);
}

$doi = $_GET['doi'];
if(isset($doi) && !empty($doi)){
    $doi = (isset($doi) &&  isValidInput($doi,100,'s',false))?trim($doi):NULL;
    $fp = fsockopen("www.crossref.org", 80, $errno, $errstr, 30);
    if (!$fp) {
        echo "$errstr ($errno)<br />\n";
    }
    else {
        $out = "GET /openurl/?pid=" . $configcrossrefpid1 . "%3A" . $configcrossrefpid2 . "&noredirect=true&id=doi%3A" . $doi . " HTTP/1.1\r\n";
        $out .= "Host: www.crossref.org\r\n";
        $out .= "Connection: Close\r\n\r\n";
        fwrite($fp, $out);
        while (!feof($fp)) {
            echo fgets($fp, 128);
        }
        fclose($fp);
    }
}

$ut = $_GET['wosid'];
if (isset($ut) && !empty($ut)){
    $ut = (isset($ut) &&  isValidInput($ut,100,'s',false))?trim($ut):NULL;
    $ut = trim($ut);
    $url = "http://www2.unil.ch/openillink/openlinker/isi/wos.php?ut=".$ut;
    $ch = curl_init($url);
    curl_exec($ch);
    curl_close($ch);
}
?>
