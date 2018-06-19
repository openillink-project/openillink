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
// Links displayed on the order details
//
// MDV - function to replace url placeholders
// OpenURL replacements
// sid : XSIDX
// pid : XPIDX
// doi : XDOIX
// pmid (PubMed identifier) : XPMIDX
// genre (Document Type) : XGENREX
// aulast (Authors names) : XAULASTX
// issn : XISSNX
// eissn : XEISSNX
// isbn : XISBNX
// title (Journal name) : XTITLEX
// atitle (Article/chapter title) : XATITLEX
// volume : XVOLUMEX
// issue : XISSUEX
// pages : XPAGESX
// date : XDATEX
// uid : XUIDX
// 
// Other replacements
// end user name : XNAMEX
// end user firstname : XPRENOMX
// end user lastname : XNOMX

// function used for resolving variables into values for preparint links to 
// external databases
function replaceExistingPlaceHolders(
    $currEnreg, /* source array where real value are stored*/
    $stitleclean, /* title prepared for url query*/
    $url, /*stringvalue with placeholders*/
    $urlencoded = FALSE /*encode url*/) {

    $urlWithRealVal = $url;

    $illinkidUrl = $urlencoded? utf8_decode($currEnreg['illinkid']) : $currEnreg['illinkid'];
    $urlWithRealVal = str_replace("XPIDX", urlencode($illinkidUrl), $urlWithRealVal);

    $doiUrl = $urlencoded? utf8_decode($currEnreg['doi']) : $currEnreg['doi'];
    $urlWithRealVal = str_replace("XDOIX", urlencode($doiUrl), $urlWithRealVal);

    $pmidUrl = $urlencoded? utf8_decode($currEnreg['PMID']) : $currEnreg['PMID'];
    $urlWithRealVal = str_replace("XPMIDX", urlencode($pmidUrl),$urlWithRealVal);

    $genreUrl = $urlencoded? utf8_decode($currEnreg['type_doc']) : $currEnreg['type_doc'];
    $urlWithRealVal = str_replace("XGENREX", urlencode($genreUrl), $urlWithRealVal);

    $auteursUrl = $urlencoded? utf8_decode($currEnreg['auteurs']) : $currEnreg['auteurs'];
    $urlWithRealVal = str_replace("XAULASTX", urlencode($auteursUrl), $urlWithRealVal);

    $issnUrl = $urlencoded? utf8_decode($currEnreg['issn']) : $currEnreg['issn'];
    $urlWithRealVal = str_replace("XISSNX", urlencode($issnUrl), $urlWithRealVal);

    $eissnUrl = $urlencoded? utf8_decode($currEnreg['eissn']) : $currEnreg['eissn'];
    $urlWithRealVal = str_replace("XEISSNX", urlencode($eissnUrl), $urlWithRealVal);

    $isbnUrl = $urlencoded? utf8_decode($currEnreg['isbn']) : $currEnreg['isbn'];
    $urlWithRealVal = str_replace("XISBNX", urlencode($isbnUrl), $urlWithRealVal);

    $titleUrl = $urlencoded? utf8_decode($stitleclean): $stitleclean;
    $urlWithRealVal = str_replace("XTITLEX", urlencode($titleUrl), $urlWithRealVal);

    $atitleUrl = $urlencoded? utf8_decode($currEnreg['titre_article']) : $currEnreg['titre_article'];
    $urlWithRealVal = str_replace("XATITLEX", urlencode($atitleUrl), $urlWithRealVal);

    $volumeUrl = $urlencoded? utf8_decode($currEnreg['volume']) : $currEnreg['volume'];
    $urlWithRealVal = str_replace("XVOLUMEX", urlencode($volumeUrl), $urlWithRealVal);

    $numeroUrl = $urlencoded? utf8_decode($currEnreg['numero']) : $currEnreg['numero'];
    $urlWithRealVal = str_replace("XISSUEX", urlencode($numeroUrl), $urlWithRealVal);

    $pagesUrl = $urlencoded? utf8_decode($currEnreg['pages']) : $currEnreg['pages'];
    $urlWithRealVal = str_replace("XPAGESX", urlencode($pagesUrl), $urlWithRealVal);

    $anneeUrl = $urlencoded? utf8_decode($currEnreg['annee']) : $currEnreg['annee'];
    $urlWithRealVal = str_replace("XDATEX", urlencode($anneeUrl), $urlWithRealVal);

    $fullnameUrl = $urlencoded? utf8_decode($currEnreg['nom'] . ", " . $currEnreg['prenom']) : ($currEnreg['nom'] . ", " . $currEnreg['prenom']);
    $urlWithRealVal = str_replace("XNAMEX", urlencode($fullnameUrl), $urlWithRealVal);
    //TODO nouveau placeholder à valider avec Pablo

    $nomUrl = $urlencoded? utf8_decode($currEnreg['nom']) : $currEnreg['nom'];
    $urlWithRealVal = str_replace("XNOMX", urlencode($nomUrl), $urlWithRealVal);
    //TODO nouveau placeholder à valider avec Pablo


    $prenomUrl = $urlencoded? utf8_decode($currEnreg['prenom']) : $currEnreg['prenom'];
    $urlWithRealVal = str_replace("XPRENOMX", urlencode($prenomUrl), $urlWithRealVal);
    //TODO nouveau placeholder à valider avec Pablo

    $uidUrl = $urlencoded? utf8_decode($currEnreg['uid']) : $currEnreg['uid'];
    $urlWithRealVal = str_replace("XUIDX", urlencode($uidUrl), $urlWithRealVal);
    /*
    if ($urlencoded){
        $urlWithRealVal = urlencode(htmlentities($urlWithRealVal));
    }
    */
    
    return $urlWithRealVal;
}

function skipWords($skipTK, $title){
   if ($skipTK) {
      $tclean = $title;
      $tclean = str_replace(" & "," ",$tclean);
      $tclean = str_replace(" the "," ",$tclean);
      $tclean = str_replace("The "," ",$tclean);
      $tclean = str_replace(" and "," ",$tclean);
      $tclean = str_replace(" of "," ",$tclean);
      $tclean = str_replace(" - "," ",$tclean);
      return $tclean;
    }
    return $title;
}

function skipTxtAfterSign($skipAP, $text){
    if($skipAP) {
        $shortTxt = $text;
        $pos1 = strpos($shortTxt, ":");
        if ($pos1 !== false)
            $shortTxt = substr($shortTxt, 0, $pos1);
        $pos2 = strpos($shortTxt, "=");
        if ($pos2 !== false)
            $shortTxt = substr($shortTxt, 0, $pos2);
        $pos3 = strpos($shortTxt, ".");
        if ($pos3 !== false)
            $shortTxt = substr($shortTxt, 0, $pos3);
        $pos4 = strpos($shortTxt, ";");
        if ($pos4 !== false)
            $shortTxt = substr($shortTxt, 0, $pos4);
        $pos5 = strpos($shortTxt, "(");
        if ($pos5 !== false)
            $shortTxt = substr($shortTxt, 0, $pos5);
        return $shortTxt;
    }
    return $text;
}

// 
function isValidInput($inputToCheck,
                      $maxSize,
                      $type = 's',
                      $optional = true,
                      $controlSet = NULL){
	/*
	  Warning: "0" is not considered valid when 'optional' is true, due to behaviour of 'empty()' php function.
	*/
    $isValid = $optional || (!empty($inputToCheck));
    if (isset($inputToCheck) && $isValid){
        $strCopy = strval($inputToCheck);
        $isValid = (strlen($strCopy) <= $maxSize);
        switch ($type) {
            case 'i':
                $isValid &= is_numeric($inputToCheck);
                break;
            case 's': default:
                $isValid &= is_string($inputToCheck);
                break;
        }
        if (!empty($controlSet) && is_array($controlSet)){
            $isValid &= in_array ( $inputToCheck ,$controlSet , TRUE);
        }
    }
    return $isValid;
}

// performs input validation for dates
function validateDate($date){
    $d = DateTime::createFromFormat('Y-m-d', $date);
    return $d && $d->format('Y-m-d') === $date;
}

// return the input value if valid; if not valid default value is returned
function safeSetInput($inputToCheck,
                      $maxSize,
                      $type = 's',
                      $defaultValue = NULL,
                      $optional = true){
    $isValid = isValidInput($inputToCheck, $maxSize, $type, $optional);
    if ($isValid)
        return $inputToCheck;
    else
        return $defaultValue;
}

function convertLtGtToTxtValue($inputToNormalize){
    $result = $inputToNormalize;
    $result = str_replace("<","[lt]",$result);
    $result = str_replace(">","[gt]",$result);
    return $result;
}


// Call this at each point of interest, passing a descriptive string
function prof_flag($str)
{
    global $prof_timing, $prof_names;
    $prof_timing[] = microtime(true);
    $prof_names[] = $str;
}

// Call this when you're done and want to see the results
function prof_print()
{
    global $prof_timing, $prof_names;
    $size = count($prof_timing);
    for($i=0;$i<$size - 1; $i++)
    {
        echo "<b>{$prof_names[$i]}</b><br>";
        echo sprintf("&nbsp;&nbsp;&nbsp;%f<br>", $prof_timing[$i+1]-$prof_timing[$i]);
    }
    echo "<b>{$prof_names[$size-1]}</b><br>";
}

function readStatus(&$codeIn = NULL, &$codeOut = NULL, &$codeTrash = NULL, &$codeSpecial = NULL)
{
	/*
	Update the input arrays 'codeIn', 'codeOut', 'codeTrash' and 'codeSpecial' with status codes
	configured to appear in folders 'In', 'Out' and 'Trash', as well as special codes.
	*/
    $statusInfo = array();
    $statusReq = "SELECT * from status;";
    $statusRes = dbquery($statusReq);
    $fillInArray = isset($codeIn);
    $fillOutArray = isset($codeOut);
    $fillTrashArray = isset($codeTrash);
    $fillSpecialArray = isset($codeSpecial);
    $nbSt = iimysqli_num_rows($statusRes);
    for ($s=0 ; $s<$nbSt ; $s++){
        $currStatus = iimysqli_result_fetch_array($statusRes);
        if ($fillInArray && $currStatus['in']===1)
            $codeIn[] = $currStatus['code'];
        if ($fillOutArray && $currStatus['out']===1)
            $codeOut[] = $currStatus['code'];
        if ($fillTrashArray && $currStatus['trash']===1)
            $codeTrash[] = $currStatus['code'];
        if ($fillSpecialArray && $currStatus['special']!=''){
            if (isset($codeSpecial[$currStatus['special']]))
                array_push($codeSpecial[$currStatus['special']], $currStatus['code']);
            else
                $codeSpecial[$currStatus['special']] = array($currStatus['code']);
        }
        $statusInfo[$currStatus['code']] = $currStatus;
    }
    return $statusInfo;
}

function boxContent($class, $title, $mainTxt){
    $boxedContent = '<div class="box">'
    .'<div class="box-content '.$class.'">'
    .'<h2 class="title is-5">'.$title.'</h1>'
    .$mainTxt
    .'</div>'
    .'</div>'
    .'<div class="box-footer"><div class="box-footer-right"></div></div>'
;
    return $boxedContent;
}

function getLibraryLocalizationCodes($monbib) {
	/*
		Returns the list of localization codes for the given library
	*/
	$locListArray = array();
    $reqLoc = "SELECT code FROM localizations WHERE library = ?";
    $resLoc = dbquery($reqLoc, array($monbib), "s");
    $nbLoc = iimysqli_num_rows($resLoc);
    for ($l=0 ; $l<$nbLoc ; $l++){
        $currLoc = iimysqli_result_fetch_array($resLoc);
		$locListArray[] = $currLoc['code'];
    }
	return $locListArray;
}
function getLibraryUnitCodes($monbib) {
	/*
		Returns the list of unit codes for the given library
	*/
	$servListArray = array();
	$reqServ = "SELECT code FROM units WHERE library = ?";
    $resServ = dbquery($reqServ, array($monbib), "s");
    $nbServ = iimysqli_num_rows($resServ);
    for ($l=0 ; $l<$nbServ ; $l++){
        $currServ = iimysqli_result_fetch_array($resServ);
		$servListArray[] = $currServ['code'];
    }
	return $servListArray;
}

function getSharingLibrariesForBib($monbib) {
	/*
		Returns the list of libraries sharing order with the given library
	*/
	$sharedLibrariesArray = array();
    $reqIsMain ="SELECT libraries.default FROM libraries WHERE libraries.default = 1 AND libraries.code=?";
    $resIsMain = dbquery($reqIsMain, array($monbib), "s");
    $isMain = iimysqli_num_rows($resIsMain);
    if ($isMain > 0){
		// Select "partners" libraries
        $reqSharing = 'SELECT libraries.code FROM libraries WHERE libraries.has_shared_ordres = 1';
        $resSharing = dbquery($reqSharing);
        $nbSharing = iimysqli_num_rows($resSharing);
        for ($l=0 ; $l<$nbSharing ; $l++){
            $currSharing = iimysqli_result_fetch_array($resSharing);
			$sharedLibrariesArray[] = $currSharing['code'];
        }
    }
	return $sharedLibrariesArray;
}
function getLibrarySignature($monbib) {
	/*
		Returns the email signature for the given library
	*/
	$servListArray = array();
	$req = "SELECT libraries.signature FROM libraries WHERE libraries.code = ?";
    $res = dbquery($req, array($monbib), "s");
    $signature = iimysqli_result_fetch_array($res);
	return $signature['signature'];
}
function is_privileged_enough($current_user_type, $min_user_type) {
	/*
	Check that the given $current_user_type is at least as privileged as the target $min_user_type
	*/
	return ($min_user_type == "guest" || 
			($min_user_type == "user" && in_array($current_user_type, array('user', 'admin', 'sadmin'))) || 
			($min_user_type == "admin" && in_array($current_user_type, array('admin', 'sadmin'))) || 
			($min_user_type == "sadmin" && $current_user_type == 'sadmin'));
}

function parse_size_str($size_str) {
	/*
	Return in bytes (as integer) the size given in string (for eg. "2KB" -> 2048
	*/
    switch (substr ($size_str, -1)) {
        case 'M': case 'm': return (int)$size_str * 1048576;
        case 'K': case 'k': return (int)$size_str * 1024;
        case 'G': case 'g': return (int)$size_str * 1073741824;
        default: return (is_numeric($size_str) ? (int)$size_str : $size_str);
    }
}

function get_message_box($message, $message_type='success', $title=null, $escape_for_html=true) {
	/*
	Return an HTML warning/error box
	
	Parameters:
	- $message (string): the message to be displayed.
	- $message (string): the (optional) title of the alert.
	- $escape_for_html (boolean): if true message content for HTML
	- $message_type (string): one of 'success', 'warning', 'info', 'danger'
	*/
	$output = '<div class="alert alert-'.$message_type.'" role="alert">';
	if (!empty($title)){
		$output .= '<strong>' . htmlspecialchars($title) . '</strong>&nbsp;';
	}
	if ($escape_for_html){
		$output .= htmlspecialchars($message);
	} else {
		$output .= $message;
	}
	$output .= '</div>';
	return $output;
}
?>