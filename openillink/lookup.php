<?php
// ***************************************************************************
// ***************************************************************************
// ***************************************************************************
// This file is part of OpenILLink software.
// Copyright (C) 2005, 2006, 2007, 2008, 2009, 2010, 2011, 2012, 2013, 2015, 2016, 2017, 2018, 2020, 2023, 2024, 2025 CHUV.
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
// Lookup page to import external content from bibliographic databases : PubMed, Web of Science, CrossRef, RERO, etc.
//
require_once ("includes/config.php");
require_once ("includes/toolkit.php");
$isbn = !empty($_GET['isbn']) ? $_GET['isbn'] : null;
if(isset($isbn) && !empty($isbn)){
    $isbn = (isset($isbn) &&  isValidInput($isbn,17,'s',false))?trim($isbn):NULL;
    $url = "http://opac.rero.ch/gateway?function=MARCSCR&search=KEYWORD&u1=7&rootsearch=KEYWORD&t1=" . urlencode($isbn);
    //  $url = $_SERVER['QUERY_STRING'];
    $ch = curl_init($url);
    curl_exec($ch);
    curl_close($ch);
}

$swissbib_identifier = !empty($_GET['swissbib-identifier']) ? $_GET['swissbib-identifier'] : null;
/* Works for ISBN and other identifiers at swissbib*/
if(isset($swissbib_identifier) && !empty($swissbib_identifier)){
    $swissbib_identifier = (isset($swissbib_identifier) &&  isValidInput($swissbib_identifier,50,'s',false))?trim($swissbib_identifier):NULL;
    $url = "https://sru.swissbib.ch/sru/search/defaultdb?operation=searchRetrieve&recordSchema=info%3Asru%2Fschema%2Fjson&maximumRecords=1&startRecord=0&recordPacking=XML&availableDBs=defaultdb&sortKeys=Submit+query&query=+dc.identifier+%3D+" . urlencode($swissbib_identifier);
	//  $url = $_SERVER['QUERY_STRING'];
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_exec($ch);
    curl_close($ch);
}

$swissbib_renouvaud_identifier = !empty($_GET['swissbib-renouvaud-mms']) ? $_GET['swissbib-renouvaud-mms'] : null;
/* Works for Renouvaud MMS. MMS ID can be 8 to 19 digits long */
if(isset($swissbib_renouvaud_identifier) && !empty($swissbib_renouvaud_identifier)){
	$swissbib_renouvaud_identifier = preg_replace('/[^0-9.]+/', '', $swissbib_renouvaud_identifier);
    $swissbib_renouvaud_identifier = (isset($swissbib_renouvaud_identifier) &&  isValidInput($swissbib_renouvaud_identifier,19,'s',false))?trim($swissbib_renouvaud_identifier):NULL;
    $url = "https://sru.swissbib.ch/sru/search/defaultdb?operation=searchRetrieve&recordSchema=info%3Asru%2Fschema%2Fjson&maximumRecords=1&startRecord=0&recordPacking=XML&availableDBs=defaultdb&sortKeys=Submit+query&query=+dc.anywhere+%3D+.EXLNZ-41BCULAUSA_NETWORK." . urlencode($swissbib_renouvaud_identifier) . "+OR+dc.anywhere+%3D+.VAUD." . urlencode($swissbib_renouvaud_identifier);
	//  $url = $_SERVER['QUERY_STRING'];
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_exec($ch);
    curl_close($ch);
}

$sru_marcxml_isbn = !empty($_GET['sru-marcxml-isbn']) ? $_GET['sru-marcxml-isbn'] : null;
/* Retrieve with ISBN via SRU protocol */
if(isset($sru_marcxml_isbn) && !empty($sru_marcxml_isbn) && isset($sru_marcxml_isbn_url) && !empty($sru_marcxml_isbn_url)){
    $sru_marcxml_isbn = (isset($sru_marcxml_isbn) &&  isValidInput($sru_marcxml_isbn,50,'s',false))?trim($sru_marcxml_isbn):NULL;
    $url = str_replace('_OPENILLINK_ISBN_', urlencode($sru_marcxml_isbn), $sru_marcxml_isbn_url);
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    header("Content-type: text/xml");
    curl_exec($ch);
    curl_close($ch);
}

$sru_marcxml_isbn2 = !empty($_GET['sru-marcxml-isbn2']) ? $_GET['sru-marcxml-isbn2'] : null;
/* Retrieve with ISBN via SRU protocol, 2nd option */
if(isset($sru_marcxml_isbn2) && !empty($sru_marcxml_isbn2) && isset($sru_marcxml_isbn_url2) && !empty($sru_marcxml_isbn_url2)){
    $sru_marcxml_isbn2 = (isset($sru_marcxml_isbn2) &&  isValidInput($sru_marcxml_isbn2,50,'s',false))?trim($sru_marcxml_isbn2):NULL;
    $url = str_replace('_OPENILLINK_ISBN_', urlencode($sru_marcxml_isbn2), $sru_marcxml_isbn_url2);
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    header("Content-type: text/xml");
    curl_exec($ch);
    curl_close($ch);
}

$sru_marcxml_mms = !empty($_GET['sru-marcxml-mms']) ? $_GET['sru-marcxml-mms'] : null;
/* Retrieve with MMS via SRU protocol. MMS ID can be 8 to 19 digits long */
if(isset($sru_marcxml_mms) && !empty($sru_marcxml_mms) && isset($sru_marcxml_mms_url) && !empty($sru_marcxml_mms_url)){
	$sru_marcxml_mms = preg_replace('/[^0-9.]+/', '', $sru_marcxml_mms);
    $sru_marcxml_mms = (isset($sru_marcxml_mms) &&  isValidInput($sru_marcxml_mms,19,'s',false))?trim($sru_marcxml_mms):NULL;
    $url = str_replace('_OPENILLINK_MMS_', urlencode($sru_marcxml_mms), $sru_marcxml_mms_url);
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    header("Content-type: text/xml");
    curl_exec($ch);
    curl_close($ch);
}

$reroid = !empty($_GET['reroid']) ? $_GET['reroid'] : null;
if(isset($reroid) && !empty($reroid)){
    $reroid = (isset($reroid) &&  isValidInput($reroid,50,'s',false))?trim($reroid):NULL;
    //  $url = $_SERVER['QUERY_STRING'];
    $url = "http://opac.rero.ch/gateway?function=MARCSCR&search=KEYWORD&u1=12&rootsearch=KEYWORD&t1=". urlencode($reroid);
    $ch = curl_init($url);
    curl_exec($ch);
    curl_close($ch);
}

$pmid = !empty($_GET['pmid']) ? $_GET['pmid'] : null;
if(isset($pmid) && !empty($pmid)){
    $pmid = (isset($pmid) &&  isValidInput($pmid,50,'s',false))?trim($pmid):NULL;
    $url = "https://eutils.ncbi.nlm.nih.gov/entrez/eutils/esummary.fcgi?version=2.0&db=pubmed&retmode=xml&tool=OpenLinker&email=" . urlencode($configemail) . "&id=" . urlencode($pmid) . (isset($configNCBIAPIKey) && urlencode($configNCBIAPIKey) ? "&api_key=".urlencode($configNCBIAPIKey) : "");
    //  $url = $_SERVER['QUERY_STRING'];
    $ch = curl_init($url);
    // following ssl ca fix should be dealt with by updating php config, it's not needed for prod server, may arise on local test server needing ssl ca fixing
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $res = curl_exec($ch);
	$http_status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
	if (is_string($res)) {
		if (isset($configNCBIAPIKey) && $configNCBIAPIKey) {
			// Error response might contain API key. Make sure we do not include in response
			$res = str_replace ($configNCBIAPIKey, "", $res);
		}
	} else {
		$res = "";
	}
	header("Content-type: text/xml");
	http_response_code($http_status_code); // might return 429 (Too Many Requests)
	echo $res;
}

$doi = !empty($_GET['doi']) ? $_GET['doi'] : null;
if(isset($doi) && !empty($doi)){
    $doi = (isset($doi) &&  isValidInput($doi,100,'s',false))?trim($doi):NULL;
    $fp = fsockopen("ssl://doi.crossref.org", 443, $errno, $errstr, 30);
    if (!$fp) {
        echo "$errstr ($errno)<br />\n";
    }
    else {
        $out = "GET /openurl/?pid=" . $configcrossrefpid1 . "%3A" . $configcrossrefpid2 . "&noredirect=true&id=doi%3A" . urlencode($doi) . " HTTP/1.1\r\n";
        $out .= "Host: www.crossref.org\r\n";
        $out .= "Connection: Close\r\n\r\n";
        fwrite($fp, $out);
        while (!feof($fp)) {
            echo fgets($fp, 128);
        }
        fclose($fp);
    }
}

$ut = !empty($_GET['wosid']) ? $_GET['wosid'] : null;
if (isset($ut) && !empty($ut) && isset($configWOSStarterRestAPIKey)){
    $ut = (isset($ut) &&  isValidInput($ut,100,'s',false))?trim($ut):NULL;
    if (strncmp($ut, "WOS:", 4) != 0) {
        if (strncmp($ut, "wos:", 4) == 0) {
            $ut = "WOS:" . substr($ut, 4);
        } else {
            $ut = "WOS:" . $ut;
        }
    }
    $url = 'https://api.clarivate.com/apis/wos-starter/v1/documents/' . urlencode(trim($ut));
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER,
                    ['X-ApiKey: ' . $configWOSStarterRestAPIKey,
                     'Accept: application/json']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $res = curl_exec($ch);
    $http_status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
	header("Content-type: application/json");
	http_response_code($http_status_code);
	if (is_string($res)) {
        $res_json = json_decode($res);
        if (isset($res_json->citations)) {
            unset($res_json->citations);
        }
        $res =  json_encode($res_json);
    }
	echo $res;
}
?>
