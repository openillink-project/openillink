<?php
// ***************************************************************************
// ***************************************************************************
// ***************************************************************************
// This file is part of OpenILLink software.
// Copyright (C) 2005, 2006, 2007, 2008, 2009, 2010, 2011, 2012, 2013, 2015, 2016, 2017, 2018, 2019, 2020, 2024 CHUV.
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

function null_to_empty_string($param) {
    /*
    Return empty string ("") if $param is null, else return $param
    */
    if (is_null($param)) {
        return "";
    } else {
        return $param;
    }
}

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

    $illinkidUrl = $urlencoded? utf8_to_iso8859_1($currEnreg['illinkid']) : $currEnreg['illinkid'];
    $urlWithRealVal = str_replace("XPIDX", urlencode(null_to_empty_string($illinkidUrl)), $urlWithRealVal);

    $doiUrl = $urlencoded? utf8_to_iso8859_1($currEnreg['doi']) : $currEnreg['doi'];
    $urlWithRealVal = str_replace("XDOIX", urlencode(null_to_empty_string($doiUrl)), $urlWithRealVal);

    $pmidUrl = $urlencoded? utf8_to_iso8859_1($currEnreg['PMID']) : $currEnreg['PMID'];
    $urlWithRealVal = str_replace("XPMIDX", urlencode(null_to_empty_string($pmidUrl)),$urlWithRealVal);

    $genreUrl = $urlencoded? utf8_to_iso8859_1($currEnreg['type_doc']) : $currEnreg['type_doc'];
    $urlWithRealVal = str_replace("XGENREX", urlencode(null_to_empty_string($genreUrl)), $urlWithRealVal);

    $auteursUrl = $urlencoded? utf8_to_iso8859_1($currEnreg['auteurs']) : $currEnreg['auteurs'];
    $urlWithRealVal = str_replace("XAULASTX", urlencode(null_to_empty_string($auteursUrl)), $urlWithRealVal);

    $issnUrl = $urlencoded? utf8_to_iso8859_1($currEnreg['issn']) : $currEnreg['issn'];
    $urlWithRealVal = str_replace("XISSNX", urlencode(null_to_empty_string($issnUrl)), $urlWithRealVal);

    $eissnUrl = $urlencoded? utf8_to_iso8859_1($currEnreg['eissn']) : $currEnreg['eissn'];
    $urlWithRealVal = str_replace("XEISSNX", urlencode(null_to_empty_string($eissnUrl)), $urlWithRealVal);

    $isbnUrl = $urlencoded? utf8_to_iso8859_1($currEnreg['isbn']) : $currEnreg['isbn'];
    $urlWithRealVal = str_replace("XISBNX", urlencode(null_to_empty_string($isbnUrl)), $urlWithRealVal);

    $titleUrl = $urlencoded? utf8_to_iso8859_1($stitleclean): $stitleclean;
    $urlWithRealVal = str_replace("XTITLEX", urlencode(null_to_empty_string($titleUrl)), $urlWithRealVal);

    $atitleUrl = $urlencoded? utf8_to_iso8859_1($currEnreg['titre_article']) : $currEnreg['titre_article'];
    $urlWithRealVal = str_replace("XATITLEX", urlencode(null_to_empty_string($atitleUrl)), $urlWithRealVal);

    $volumeUrl = $urlencoded? utf8_to_iso8859_1($currEnreg['volume']) : $currEnreg['volume'];
    $urlWithRealVal = str_replace("XVOLUMEX", urlencode(null_to_empty_string($volumeUrl)), $urlWithRealVal);

    $numeroUrl = $urlencoded? utf8_to_iso8859_1($currEnreg['numero']) : $currEnreg['numero'];
    $urlWithRealVal = str_replace("XISSUEX", urlencode(null_to_empty_string($numeroUrl)), $urlWithRealVal);

    $pagesUrl = $urlencoded? utf8_to_iso8859_1($currEnreg['pages']) : $currEnreg['pages'];
    $urlWithRealVal = str_replace("XPAGESX", urlencode(null_to_empty_string($pagesUrl)), $urlWithRealVal);

    $anneeUrl = $urlencoded? utf8_to_iso8859_1($currEnreg['annee']) : $currEnreg['annee'];
    $urlWithRealVal = str_replace("XDATEX", urlencode(null_to_empty_string($anneeUrl)), $urlWithRealVal);

    $fullnameUrl = $urlencoded? utf8_to_iso8859_1($currEnreg['nom'] . ", " . $currEnreg['prenom']) : ($currEnreg['nom'] . ", " . $currEnreg['prenom']);
    $urlWithRealVal = str_replace("XNAMEX", urlencode(null_to_empty_string($fullnameUrl)), $urlWithRealVal);
    //TODO nouveau placeholder à valider avec Pablo

    $nomUrl = $urlencoded? utf8_to_iso8859_1($currEnreg['nom']) : $currEnreg['nom'];
    $urlWithRealVal = str_replace("XNOMX", urlencode(null_to_empty_string($nomUrl)), $urlWithRealVal);
    //TODO nouveau placeholder à valider avec Pablo


    $prenomUrl = $urlencoded? utf8_to_iso8859_1($currEnreg['prenom']) : $currEnreg['prenom'];
    $urlWithRealVal = str_replace("XPRENOMX", urlencode(null_to_empty_string($prenomUrl)), $urlWithRealVal);
    //TODO nouveau placeholder à valider avec Pablo

    $uidUrl = $urlencoded? utf8_to_iso8859_1($currEnreg['uid']) : $currEnreg['uid'];
    $urlWithRealVal = str_replace("XUIDX", urlencode(null_to_empty_string($uidUrl)), $urlWithRealVal);
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
		$currStatus["title"]["fr"] = $currStatus["title1"];
        $currStatus["title"]["en"] = $currStatus["title2"];
        $currStatus["title"]["de"] = $currStatus["title3"];
        $currStatus["title"]["it"] = $currStatus["title4"];
        $currStatus["title"]["es"] = $currStatus["title5"];
		$currStatus["help"]["fr"] = $currStatus["help1"];
        $currStatus["help"]["en"] = $currStatus["help2"];
        $currStatus["help"]["de"] = $currStatus["help3"];
        $currStatus["help"]["it"] = $currStatus["help4"];
        $currStatus["help"]["es"] = $currStatus["help5"];
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

function prepare_folder_query($query) {
	/*
	Returns the prepared query of a folder, for eg. by replacing keywords 'past', 'futur'.
	*/
	$today = date("Y-m-d");
	$mystringrenewpast = " renouveler < '" . $today . "' AND renouveler > '1900-01-01'";
	$mystringrenewfutur = " renouveler > '" . $today . "'";
	$mystringrenewday = " renouveler = '" . $today . "'";
	$query = str_replace ("renewdate LIKE 'past'" , $mystringrenewpast, $query);
	$query = str_replace ("renewdate LIKE 'futur'" , $mystringrenewfutur, $query);
	$query = str_replace ("renewdate LIKE 'day'" , $mystringrenewday, $query);
	$posand = strpos($query, 'AND ');
	if (($posand == 0) || ($posand == 1))
	{
		$countreplaceand = 1;
		$query = str_replace ('AND ' , '', $query, $countreplaceand);
	}
	$posand2 = strpos($query, 'AND ');
	if (($posand2 == 0) || ($posand2 == 1))
	{
		$countreplaceand = 1;
		$query = str_replace ('AND ' , '', $query, $countreplaceand);
	}
	return $query;
}

function update_folders_item_count($only_if_necessary = false) {
	/*
	Updates the count for each folder (filter) in the database.

	if the configuration variable 'config_display_folders_count' is false, this
	function has not effect.

	if $only_if_necessary is true, then only the folders that need to be refreshed are updated.
	*/
	global $config_display_folders_count;

	if (isset($config_display_folders_count) && !$config_display_folders_count) {
		// $config_display_folders_count is defined and set to false: do not update items count
		return;
	}

	$today = date("Y-m-d");
	if ($only_if_necessary) {
		$reqfolders = "SELECT id, title, description, query, order_count, count_updated FROM folders WHERE active = 1 AND (order_count IS NULL OR (query LIKE \"%renewdate%\" AND count_updated < CURDATE()))";
	} else {
		$reqfolders = "SELECT id, title, description, query, order_count, count_updated FROM folders WHERE active = 1";
	}
	$resultfolders = dbquery($reqfolders);
	while ($rowfolders = iimysqli_result_fetch_array($resultfolders)){
			$folderId = $rowfolders["id"];
			$queryfolder = $rowfolders["query"];
            if (empty($queryfolder)) {
                continue;
            }
			$thisFolderCount = $rowfolders["order_count"];
			$thisFolderCountUpdate = $rowfolders["count_updated"];

			if ($only_if_necessary && !is_null($thisFolderCount) && (strpos($queryfolder, 'renewdate') === false || $thisFolderCountUpdate >= $today) ) {
				continue; // skip this line that does not need to be updated apparently
			}

			$reqFolderCount = "UPDATE folders SET count_updated = NOW(), order_count = (SELECT count(illinkid) as foldercount FROM orders WHERE ";
			$myfolderquery =  prepare_folder_query($rowfolders["query"]);
			$reqFolderCount .= $myfolderquery . ") WHERE folders.id = ?";
            
            try {
                $success = dbquery($reqFolderCount, array($folderId) , 'i');
            } catch (mysqli_sql_exception $e) {
                continue;
            }
	}
}

function startsWith($haystack, $needle)
{
     $length = strlen($needle);
     return (substr($haystack, 0, $length) === $needle);
}

function endsWith($haystack, $needle)
{
    $length = strlen($needle);
    if ($length == 0) {
        return true;
    }

    return (substr($haystack, -$length) === $needle);
}

function resolve_link($pmid, $mms_id, $doi, $genre, $atitle, $title, $year, $volume, $issue, $suppl, $pages, $author, $issn_isbn, $edition, $user_ip) {
	/*
	Access the configured resolver to retrieve information about the given document
	*/
	
	$response = array("has_fulltext"=>false,
					  "services" => array(),
					  "response" => array());
	
	global $config_link_resolver_user_ip_forwarding_mode;
	global $config_link_resolver_base_openurl;
	global $config_link_resolver_custom_parameters;
	if (!empty($config_link_resolver_base_openurl)){
		$openurl_parameters = array(
			'svc_dat' => 'CTO',
		);
		
		$openurl_parameters = array_merge ($openurl_parameters, $config_link_resolver_custom_parameters);

		
		if ($config_link_resolver_user_ip_forwarding_mode == "forward"){
			$openurl_parameters['user_ip'] = $user_ip;
		} else if ($config_link_resolver_user_ip_forwarding_mode == "server"){
			$openurl_parameters['user_ip'] = $_SERVER['SERVER_ADDR'];
		} else if (!is_null($config_link_resolver_user_ip_forwarding_mode)){
			if (strpos($config_link_resolver_user_ip_forwarding_mode, ' ') !== false) {
				// forward user IP if falls within range. Otherwise forward default provided IP
				$exploded_config_link_resolver_user_ip_forwarding_mode = explode(" ", $config_link_resolver_user_ip_forwarding_mode);
				if (startsWith($user_ip, $exploded_config_link_resolver_user_ip_forwarding_mode[0])) {
					$openurl_parameters['user_ip'] = $user_ip;
				} else {
					$openurl_parameters['user_ip'] = $exploded_config_link_resolver_user_ip_forwarding_mode[1];
				}
			} else {
				$openurl_parameters['user_ip'] = $config_link_resolver_user_ip_forwarding_mode;
			}
		}
		if (!empty($pmid)) {
			$openurl_parameters['id'] = "pmid:" . $pmid;
		}
		if (!empty($mms_id)) {
			$openurl_parameters['rft.mms_id'] = $mms_id;
		}
		if (!empty($doi)) {
			$openurl_parameters['rft.doi'] = $doi;
		}
		if (!empty($atitle)) {
			$openurl_parameters['rft.atitle'] = $atitle;
		}
		if (!empty($title)) {
			if ($genre == "book") {
				$openurl_parameters['rft.btitle'] = $title;
			} else {
				$openurl_parameters['rft.jtitle'] = $title;
			}
		}
		if (!empty($genre)) {
			$openurl_parameters['rft.genre'] = $genre;
		}
		if (!empty($year)) {
			$openurl_parameters['rft.pubyear'] = $year;
			$openurl_parameters['rft.date'] = $year;
		}
		if (!empty($volume)) {
			$openurl_parameters['rft.volume'] = $volume;
		}
		if (!empty($issue)) {
			$openurl_parameters['rft.issue'] = $issue;
		}
		/*if (!empty($suppl)) {
			$openurl_parameters['rft.suppl'] = $suppl;
		}*/
		if (!empty($pages)) {
			//if (strpos($pages, '-') !== false || strpos($pages, ',') !== false) {
			//	$openurl_parameters['rft.pages'] = $pages;
			//} else {
				$openurl_parameters['rft.spage'] = $pages;
			//}
		}
		if (!empty($author)) {
			$openurl_parameters['rft.au'] = $author;
		}
		if (!empty($issn_isbn)) {
			if ($genre == "book") {
				$openurl_parameters['rft.isbn'] = $issn_isbn;
			} else {
				$openurl_parameters['rft.issn'] = $issn_isbn;
			}
		}
		if (!empty($edition)) {
			$openurl_parameters['rft.edition'] = $edition;
		}
		$openurl_resolver = $config_link_resolver_base_openurl . "?" . http_build_query($openurl_parameters);

		// Resolve and parse
		$resolved_obj = simplexml_load_file($openurl_resolver);
		if ($resolved_obj !== false) {
			// Register namespaces
			foreach($resolved_obj->getDocNamespaces() as $strPrefix => $strNamespace) {
				if(strlen($strPrefix)==0) {
					$strPrefix="a"; 
				}
				$resolved_obj->registerXPathNamespace($strPrefix,$strNamespace);
			}
			

			$has_fulltext = false;
			foreach ($resolved_obj->xpath("//a:context_service[@service_type='getFullTxt']") as $service) {

				$service->registerXPathNamespace("b", "http://com/exlibris/urm/uresolver/xmlbeans/u");
				$is_filtered = count($service -> xpath("b:keys/b:key[@id='Filtered']")) > 0;
                $is_related_service = $service -> xpath("b:keys/b:key[@id='is_related_service']")[0] == "true";

				if (!$is_filtered && !$is_related_service) {
					$package_display_name = (string)$service -> xpath("b:keys/b:key[@id='package_display_name']")[0];
					$preferred_link = (int)$service -> xpath("b:keys/b:key[@id='preferred_link']")[0] == 1;
					$is_free = (int)$service -> xpath("b:keys/b:key[@id='Is_free']")[0] == 1;
					$resolution_url = (string)$service -> xpath("b:resolution_url")[0];
                    $public_note = "";
                    $public_note_nodes = $service -> xpath("b:keys/b:key[@id='public_note']");
                    if (count($public_note_nodes) > 0) {
                        $public_note = (string)$public_note_nodes[0];
                    }
					$response['services'][] = array('package_display_name' => $package_display_name,
													'resolution_url' => $resolution_url,
													'preferred_link' =>$preferred_link,
													'is_free' => $is_free,
                                                    'public_note' => $public_note);
					$has_fulltext = true;
				}
			};
			$response['has_fulltext'] = $has_fulltext;
			$response['response'] = $resolved_obj;
			// Order by preferred service and then by package name
			usort($response['services'], function($a, $b)
										{
											if ($a['preferred_link'] == $b['preferred_link']) {
												return strcmp($a['package_display_name'], $b['package_display_name']);
											} else { 
												return $b['preferred_link'] - $a['preferred_link'];
											}
										});
		}
	}
	return $response;
	
}

function utf8_to_iso8859_1($string) {
    /* 
    Convert from UTF-8 to ISO-8859-1
    Replacement for deprecated function "utf8_decode" in PHP9.
    Copied from Symfony package:
    https://github.com/symfony/polyfill-php72/blob/v1.26.0/Php72.php#L40-65
    
    */
    $s = (string) $string;
    $len = \strlen($s);

    for ($i = 0, $j = 0; $i < $len; ++$i, ++$j) {
        switch ($s[$i] & "\xF0") {
            case "\xC0":
            case "\xD0":
                $c = (\ord($s[$i] & "\x1F") << 6) | \ord($s[++$i] & "\x3F");
                $s[$j] = $c < 256 ? \chr($c) : '?';
                break;

            case "\xF0":
                ++$i;
                // no break

            case "\xE0":
                $s[$j] = '?';
                $i += 2;
                break;

            default:
                $s[$j] = $s[$i];
        }
    }

    return substr($s, 0, $j);
}

function parse_uid_str($uid, $filter_codes=true) {
    /*
    Parses the $uid string paramter (eg. "pmid:1234567 DOI:10/12345678" and returns an array:
    Eg. {'pmid'-> "12345678",
         'doi' -> "10/12345678",
         'wosut'  -> "000376516500021",
         'isbn' -> "9780323640770"}
         
     if $filter_codes is true, then only allowed keys are returned
    */
    global $lookupuid; // Accessing the global lookup array
    $result = array(); // Initialize the result array

    $codes_lowercase = array("mms", "wosid", "isbn", "pmid", "doi", "wosut", "rero");
    
    foreach ($lookupuid as $entry) {
        if (isset($entry['code'])) {
            $codes_lowercase[] = strtolower($entry['code']);
        }
    }
    
    // Split the input string by spaces
    $entries = explode(' ', $uid);
    
    foreach ($entries as $entry) {
        // Split each entry by the first colon ":". When no colon, skip
        if (strpos($entry, ":") === false) {
            continue;
        }
        list($key, $value) = explode(':', $entry, 2);
        
        // Normalize the key to lowercase
        $key = strtolower($key);
        
        if (in_array ($key, $codes_lowercase) || !$filter_codes) {
            $result[$key] = $value;
        }
    }
    
    return $result;
}

?>