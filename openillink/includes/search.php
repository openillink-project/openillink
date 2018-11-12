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
// Orders search results
//
require_once ("toolkit.php");

$link = dbconnect();

if(in_array ($monaut, array("admin", "sadmin", "user","guest"), true)){

    // Add filter when logged a guest
    if ($monaut == 'guest'){
        /* guest can be either a user with guest credits or a user with an automatic login mail + random password assigned by the system */
        $reqGuest = "SELECT * FROM users WHERE users.name = ?";
        $resGuest = dbquery($reqGuest, array($monnom), "s");
        $nbGuest = iimysqli_num_rows($resGuest);
        if ($nbGuest==1){
            $guest = iimysqli_result_fetch_array($resGuest);
            $mailGuest = $guest['email'];
			$guestFilterAlone = " saisie_par = '".mysqli_real_escape_string($link, $monnom)."'";
			$guestFilter = $guestFilterAlone . " AND ";
        }
        if (empty($mailGuest)) {
            $mailGuest = ((!empty($monnom)) && isValidInput($monnom,100,'s',false))?$monnom:'';
			$guestFilter = "mail = '". mysqli_real_escape_string($link, $mailGuest)."' AND ";
			$guestFilterAlone = "mail = '".mysqli_real_escape_string($link, $mailGuest)."'";
		}
    }
    else
        $guestFilter = '';

    /* integration du filtre sur le statut */
    $statuscode = (isset($_GET['statuscode']))?$_GET['statuscode']:'';
    $filtreStatut = '';
    if (!empty($statuscode)) {
        $statuscode = str_replace('_st','',$statuscode);
        $statut = isValidInput($statuscode,6,'s',false) || $statuscode == "0" ? $statuscode : '';
        $filtreStatut = "  stade = ".mysqli_real_escape_string($link, $statut)." ";
    }
    $champValides = array('id', 'datecom', 'dateenv', 'datefact', 'statut', 'localisation', 'bibliotheque',  'nom', 'email', 'service', 'issn', 'pmid', 'title', 'atitle', 'auteurs', 'reff', 'refb', 'all');
    $champ = ((!empty($_GET['champ'])) && isValidInput($_GET['champ'], 15, 's', false, $champValides))?$_GET['champ']:'';
    $term = (isset($_GET['term']))?$_GET['term']:'';
	$myorders = ((!empty($_GET['myorders'])) && isValidInput($_GET['myorders'],1,'s',false,array("1")))?$_GET['myorders']:'';
    if (!empty($champ) && !empty($term)){
        if (empty($from))
            $from=0;
        if($champ == 'id'){
            $id = isValidInput($term,8,'i',false)?$term:'';
            $req2 = "SELECT illinkid FROM orders";
            $conditions = " WHERE $guestFilter illinkid = '".mysqli_real_escape_string($link,$id)."' ";
        }
        if($champ == 'atitle'){
            $atitle = $term;
            $atitle = strtr($atitle, ' ', '%');
            $atitle = strtr($atitle, "'", '%');
            $atitle = '%'.$atitle.'%';
            $req2 = "SELECT illinkid FROM orders";
            $conditions = " WHERE ".$guestFilter." titre_article LIKE '".mysqli_real_escape_string($link, $atitle)."'";
        }
        if($champ == 'auteurs'){
            $atitle = $term;
            $atitle = strtr($atitle, ' ', '%');
            $atitle = strtr($atitle, "'", '%');
            $atitle = '%'.$atitle.'%';
            $req2 = "SELECT illinkid FROM orders";
            $conditions = " WHERE ".$guestFilter." auteurs like '".mysqli_real_escape_string($link, $atitle)."'";
        }
        if($champ == 'title'){
            $title = $term;
            $title = strtr($title, ' ', '%');
            $title = strtr($title, "'", '%');
            $title = '%'.$title.'%';
            $req2 = "SELECT illinkid FROM orders";
            $conditions = " WHERE ".$guestFilter." titre_periodique like '".mysqli_real_escape_string($link, $title)."'";
        }
        if($champ == 'datecom'){
            $datecom = validateDate($term)?$term:'';
            $req2 = "SELECT illinkid FROM orders";
            $conditions = " WHERE ".$guestFilter." date = '".mysqli_real_escape_string($link, $datecom)."'";
        }
        if($champ == 'dateenv'){
            $dateenv = validateDate($term)?$term:'';
            $req2 = "SELECT illinkid FROM orders ";
            $conditions = " WHERE ".$guestFilter." envoye = '".mysqli_real_escape_string($link, $dateenv)."'";
        }
        if($champ == 'datefact'){
            $datefact = validateDate($term)?$term:'';
            $req2 = "SELECT illinkid FROM orders ";
            $conditions = " WHERE ".$guestFilter." facture = '".mysqli_real_escape_string($link, $datefact)."'";
        }
        if($champ == 'service'){
            $service = isValidInput($term,20,'s',false)?$term:'';
            $req2 = "SELECT illinkid FROM orders ";
            $conditions = " WHERE $guestFilter service like '".mysqli_real_escape_string($link, $service)."'";
        }
        if($champ == 'issn'){
            $issn = isValidInput($term,50,'s',false)?$term:'';
            $req2 = "SELECT illinkid FROM orders ";
            $conditions = " WHERE  (issn like '%".mysqli_real_escape_string($link, $issn)."%' or eissn like '%".mysqli_real_escape_string($link, $issn)."%')";
        }
        if($champ == 'pmid'){
            $pmid = isValidInput($term,50,'s',false)?$term:'';
            $req2 = "SELECT illinkid FROM orders ";
            $conditions = " WHERE ".$guestFilter." PMID like '".mysqli_real_escape_string($link, $pmid)."'";
        }
        if($champ == 'localisation'){
            $localisation = isValidInput($term,20,'s',false)?$term:'';
            $req2 = "SELECT illinkid FROM orders ";
            $conditions = " WHERE ".$guestFilter." localisation like '".mysqli_real_escape_string($link, $localisation)."'";
            }
        if($champ == 'bibliotheque'){
            $bibliotheque = isValidInput($term,20,'s',false)?$term:'';
            $req2 = "SELECT illinkid FROM orders ";
            $conditions = " WHERE ".$guestFilter." bibliotheque like '".mysqli_real_escape_string($link, $bibliotheque)."'";
            }
        if($champ == 'reff'){
            $reff = isValidInput($term,50,'s',false)?'%'.$term.'%':'';
            $req2 = "SELECT illinkid FROM orders ";
            $conditions = " WHERE ".$guestFilter." ref like '".mysqli_real_escape_string($link, $reff)."'";
        }
        if($champ == 'refb'){
            $refb = isValidInput($term,50,'s',false)?'%'.$term.'%':'';
            $req2 = "SELECT illinkid FROM orders ";
            $conditions = " WHERE ".$guestFilter." and refinterbib like '".mysqli_real_escape_string($link, $refb)."'";
        }
        if($champ == 'email' && $guestFilter===''){
            $emailr = '%'.$term.'%';
            $req2 = "SELECT illinkid FROM orders ";
            $conditions = " WHERE mail like '".mysqli_real_escape_string($link, $emailr)."'";
        }
        if($champ == 'all'){
            $all = $term;
            $all = strtr($all, ' ', '%');
            $all = strtr($all, "'", '%');
            $all = '%'. mysqli_real_escape_string($link, $all).'%';
            $req2 = "SELECT illinkid FROM orders ";
            $conditions = " WHERE ".$guestFilter." ((illinkid like '$all') or (localisation like '$all') or (ref like '$all') or (nom like '$all') or (prenom like '$all') or (service like '$all') or (cgra like '$all') or (cgrb like '$all') or (mail like '$all') or (tel like '$all') or (adresse like '$all') or (code_postal like '$all') or (localite like '$all') or (envoi_par like '$all') or (titre_periodique like '$all') or (annee like '$all') or (volume like '$all') or (numero like '$all') or (supplement like '$all') or (pages like '$all') or (titre_article like '$all') or (auteurs like '$all') or (edition like '$all') or (isbn like '$all') or (issn like '$all') or (eissn like '$all') or (doi like '$all') or (uid like '$all') or (remarques like '$all') or (remarquespub like '$all') or (historique like '$all') or (refinterbib like '$all') or (PMID like '$all') or (ip like '$all'))";
        }
        if($champ == 'nom'){
            $pos1 = strpos($term,',');
            $pos2 = strpos($term,' ');
            if (($pos1 === false)&&($pos2 === false)){
                $nom='%'.$term.'%';
                $req2 = "SELECT illinkid FROM orders ";
                $conditions = " WHERE ".$guestFilter." (nom like '".mysqli_real_escape_string($link, $nom)."' OR prenom like '".mysqli_real_escape_string($link, $nom)."')";
            }
            else{
                if ($pos1 === false)
                    $pos=$pos2;
                else
                    $pos=$pos1;
                $nom=trim(substr($term,0,$pos));
                $prenom=trim(substr($term,$pos+1));
                $req2 = "SELECT illinkid FROM orders ";
                $conditions = " WHERE ".$guestFilter." (nom like '".mysqli_real_escape_string($link, $nom)."' AND prenom like '".mysqli_real_escape_string($link, $prenom)."')";
            }
        }
        $conditions = (empty($conditions)?' WHERE ':$conditions).(empty($filtreStatut)?'':' AND '.$filtreStatut);
    }
    else{
        if (!empty($guestFilter) || !empty($filtreStatut)) {
            $conditions = '';
            $conditions = (empty($guestFilterAlone)?'':$guestFilterAlone);
            $conditions .= (empty($filtreStatut)?'':(empty($guestFilterAlone)?'':' AND ').$filtreStatut.' ');
            $conditions = ' WHERE '.$conditions;
        }
        else {
            $conditions = $conditionsParDefauts;
        }
    }
	if ($myorders == "1") {
		$saisie_par_condition = " saisie_par = '".mysqli_real_escape_string($link, $monnom)."'";
		$conditions = empty($conditions) ? (' WHERE '. $saisie_par_condition) : (str_replace("WHERE", "WHERE (", $conditions) . ') AND ' . $saisie_par_condition) ;
	}
}
?>
