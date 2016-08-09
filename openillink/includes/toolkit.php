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

    $illinkidUrl = $urlencoded? urlencode(stripslashes(utf8_decode($currEnreg['illinkid']))) : stripslashes($currEnreg['illinkid']);
    $urlWithRealVal = str_replace("XPIDX", $illinkidUrl, $urlWithRealVal);

    $doiUrl = $urlencoded? urlencode(stripslashes(utf8_decode($currEnreg['doi']))) : stripslashes($currEnreg['doi']);
    $urlWithRealVal = str_replace("XDOIX", $doiUrl, $urlWithRealVal);

    $pmidUrl = $urlencoded? urlencode(stripslashes(utf8_decode($currEnreg['PMID']))) : stripslashes($currEnreg['PMID']);
    $urlWithRealVal = str_replace("XPMIDX", $pmidUrl,$urlWithRealVal);

    $genreUrl = $urlencoded? urlencode(stripslashes(utf8_decode($currEnreg['type_doc']))) : stripslashes($currEnreg['type_doc']);
    $urlWithRealVal = str_replace("XGENREX", $genreUrl, $urlWithRealVal);

    $auteursUrl = $urlencoded? urlencode(stripslashes(utf8_decode($currEnreg['auteurs']))) : stripslashes($currEnreg['auteurs']);
    $urlWithRealVal = str_replace("XAULASTX", $auteursUrl, $urlWithRealVal);

    $issnUrl = $urlencoded? urlencode(stripslashes(utf8_decode($currEnreg['issn']))) : stripslashes($currEnreg['issn']);
    $urlWithRealVal = str_replace("XISSNX", $issnUrl, $urlWithRealVal);

    $eissnUrl = $urlencoded? urlencode(stripslashes(utf8_decode($currEnreg['eissn']))) : stripslashes($currEnreg['eissn']);
    $urlWithRealVal = str_replace("XEISSNX", $eissnUrl, $urlWithRealVal);

    $isbnUrl = $urlencoded? urlencode(stripslashes(utf8_decode($currEnreg['isbn']))) : stripslashes($currEnreg['isbn']);
    $urlWithRealVal = str_replace("XISBNX", $isbnUrl, $urlWithRealVal);

    $titleUrl = $urlencoded? urlencode(stripslashes(utf8_decode($stitleclean))): stripslashes($stitleclean);
    $urlWithRealVal = str_replace("XTITLEX", $titleUrl, $urlWithRealVal);

    $atitleUrl = $urlencoded? urlencode(stripslashes(utf8_decode($currEnreg['titre_article']))) : stripslashes($currEnreg['titre_article']);
    $urlWithRealVal = str_replace("XATITLEX", $atitleUrl, $urlWithRealVal);

    $volumeUrl = $urlencoded? urlencode(stripslashes(utf8_decode($currEnreg['volume']))) : stripslashes($currEnreg['volume']);
    $urlWithRealVal = str_replace("XVOLUMEX", $volumeUrl, $urlWithRealVal);

    $numeroUrl = $urlencoded? urlencode(stripslashes(utf8_decode($currEnreg['numero']))) : stripslashes($currEnreg['numero']);
    $urlWithRealVal = str_replace("XISSUEX", $numeroUrl, $urlWithRealVal);

    $pagesUrl = $urlencoded? urlencode(stripslashes(utf8_decode($currEnreg['pages']))) : stripslashes($currEnreg['pages']);
    $urlWithRealVal = str_replace("XPAGESX", $pagesUrl, $urlWithRealVal);

    $anneeUrl = $urlencoded? urlencode(stripslashes(utf8_decode($currEnreg['annee']))) : stripslashes($currEnreg['annee']);
    $urlWithRealVal = str_replace("XDATEX", $anneeUrl, $urlWithRealVal);

    $fullnameUrl = $urlencoded? urlencode(stripslashes(utf8_decode($currEnreg['nom'] . ", " . $currEnreg['prenom']))) : stripslashes(($currEnreg['nom'] . ", " . $currEnreg['prenom']));
    $urlWithRealVal = str_replace("XNAMEX", $fullnameUrl, $urlWithRealVal);
    //TODO nouveau placeholder à valider avec Pablo

    $nomUrl = $urlencoded? urlencode(stripslashes(utf8_decode($currEnreg['nom']))) : stripslashes($currEnreg['nom']);
    $urlWithRealVal = str_replace("XNOMX", $nomUrl, $urlWithRealVal);
    //TODO nouveau placeholder à valider avec Pablo


    $prenomUrl = $urlencoded? urlencode(stripslashes(utf8_decode($currEnreg['prenom']))) : stripslashes($currEnreg['prenom']);
    $urlWithRealVal = str_replace("XPRENOMX", $prenomUrl, $urlWithRealVal);
    //TODO nouveau placeholder à valider avec Pablo

    $uidUrl = $urlencoded? urlencode(stripslashes(utf8_decode($currEnreg['uid']))) : stripslashes($currEnreg['uid']);
    $urlWithRealVal = str_replace("XUIDX", $uidUrl, $urlWithRealVal);
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
    .'<h1>'.$title.'</h1>'
    .$mainTxt
    .'</div>'
    .'</div>'
    .'<div class="box-footer"><div class="box-footer-right"></div></div>'
;
    return $boxedContent;
}

?>