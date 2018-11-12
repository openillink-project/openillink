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
    $champValides = array('id', 'datecom', 'dateenv', 'datefact', 'date', 'statut', 'localisation', 'bibliotheque',  'nom', 'email', 'service', 'issn', 'pmid', 'title', 'atitle', 'auteurs', 'reff', 'refb', 'all');
    $champ1 = ((!empty($_GET['champ'])) && isValidInput($_GET['champ'], 15, 's', false, $champValides))?$_GET['champ']:'';
	$champ2 = ((!empty($_GET['champ2'])) && isValidInput($_GET['champ2'], 15, 's', false, $champValides))?$_GET['champ2']:'';
	$champ2_operator = ((!empty($_GET['op2'])) && isValidInput($_GET['op2'], 3, 's', false, ['AND', 'OR', 'NOT']))?$_GET['op2']:'AND';
	$champ3 = ((!empty($_GET['champ3'])) && isValidInput($_GET['champ3'], 15, 's', false, $champValides))?$_GET['champ3']:'';
	$champ3_operator = ((!empty($_GET['op3'])) && isValidInput($_GET['op3'], 3, 's', false, ['AND', 'OR', 'NOT']))?$_GET['op3']:'AND';
    $term1 = (isset($_GET['term']))?$_GET['term']:'';
	$term2 = (isset($_GET['term2']))?$_GET['term2']:'';
	$term3 = (isset($_GET['term3']))?$_GET['term3']:'';
	$match = ((!empty($_GET['match'])) && isValidInput($_GET['match'], 8, 's', false, ['starts', 'contains', 'exact']))?$_GET['match']:'starts';
	$match2 = ((!empty($_GET['match2'])) && isValidInput($_GET['match2'], 8, 's', false, ['starts', 'contains', 'exact']))?$_GET['match2']:'starts';
	$match3 = ((!empty($_GET['match3'])) && isValidInput($_GET['match3'], 8, 's', false, ['starts', 'contains', 'exact']))?$_GET['match3']:'starts';
	$myorders = ((!empty($_GET['myorders'])) && isValidInput($_GET['myorders'],1,'s',false,array("1")))?$_GET['myorders']:'';
	$searchtype = ((!empty($_GET['searchtype'])) && isValidInput($_GET['searchtype'],8,'s',false,array("simple", "advanced")))?$_GET['searchtype']:'simple';
	if ($searchtype == 'simple') {
		$term2 = "";
		$term3 = "";
		if (in_array($champ1, array('all', 'atitle', 'title', 'auteurs', 'email'))) {
			# backward-compatible behaviour
			$match = "contains";
		} else {
			$match = "starts";
		}
	}
    if ((!empty($champ1) && !empty($term1)) || (!empty($champ2) && !empty($term2)) || (!empty($champ3) && !empty($term3))) {
        //if (empty($from))
        //    $from=0;
		$search_form_conditions = [[$champ1, $term1 , '', $match],
								  [$champ2, $term2 , $champ2_operator, $match2],
								  [$champ3, $term3 , $champ3_operator, $match3]];
		$req2 = "SELECT illinkid FROM orders";
		$conditions = "";
		foreach ($search_form_conditions as $search_form_condition) {
			$champ = $search_form_condition[0];
			$term = $search_form_condition[1];
			$operator = $search_form_condition[2];
			$must_close_parenthesis = false; // for nesting conditions
			if ($operator == "NOT") {
				$operator = "AND NOT";
			}
			$match_type = $search_form_condition[3];
			if ($match_type == "contains") {
				$match_type_sql = "LIKE";
				$fuzzy_start = "%";
				$fuzzy_end = "%";
			} else if ($match_type == "starts") {
				$match_type_sql = "LIKE";
				$fuzzy_start = "";
				$fuzzy_end = "%";
			} else {
				$match_type_sql = '=';
				$fuzzy_start = "";
				$fuzzy_end = "";
			}
			if (!empty($term)){
				if ($conditions != "" && $operator != "") {
					$conditions = "(". $conditions . " " . $operator . " ";
					$must_close_parenthesis = true;
				}
				$conditions .= " ( "; // Open parenthesis for local condition
				if($champ == 'id'){
					$id = isValidInput($term,8,'i',false)?$term:'';
					$conditions .= " illinkid = '".mysqli_real_escape_string($link,$id)."' ";
				}
				if($champ == 'atitle'){
					$atitle = $term;
					if ($match_type_sql == 'LIKE') {
						$atitle = strtr($atitle, ' ', '%');
						$atitle = strtr($atitle, "'", '%');
					}
					$atitle = $fuzzy_start.$atitle.$fuzzy_end;
					$conditions .= " titre_article ".$match_type_sql." '".mysqli_real_escape_string($link, $atitle)."'";
				}
				if($champ == 'auteurs'){
					$atitle = $term;
					if ($match_type_sql == 'LIKE') {
						$atitle = strtr($atitle, ' ', '%');
						$atitle = strtr($atitle, "'", '%');
					}
					$atitle = $fuzzy_start.$atitle.$fuzzy_end;
					$conditions .= " auteurs ".$match_type_sql." '".mysqli_real_escape_string($link, $atitle)."'";
				}
				if($champ == 'title'){
					$title = $term;
					if ($match_type_sql == 'LIKE') {
						$title = strtr($title, ' ', '%');
						$title = strtr($title, "'", '%');
					}
					$title = $fuzzy_start.$title.$fuzzy_end;
					$conditions .= " titre_periodique ".$match_type_sql." '".mysqli_real_escape_string($link, $title)."'";
				}
				if($champ == 'datecom'){
					$datecom = $fuzzy_start.$term.$fuzzy_end;
					$conditions .= " date ".$match_type_sql." '".mysqli_real_escape_string($link, $datecom)."'";
				}
				if($champ == 'dateenv'){
					$dateenv = $fuzzy_start.$term.$fuzzy_end;
					$conditions .= " envoye ".$match_type_sql." '".mysqli_real_escape_string($link, $dateenv)."'";
				}
				if($champ == 'datefact'){
					$datefact = $fuzzy_start.$term.$fuzzy_end;
					$conditions .= " facture ".$match_type_sql." '".mysqli_real_escape_string($link, $datefact)."'";
				}
				if($champ == 'date'){
					$date = $fuzzy_start.$term.$fuzzy_end;
					$conditions .= " ( facture ".$match_type_sql." '".mysqli_real_escape_string($link, $date)."' OR envoye ".$match_type_sql." '".mysqli_real_escape_string($link, $date)."' OR date ".$match_type_sql." '".mysqli_real_escape_string($link, $date)."'". ")";
				}
				if($champ == 'service'){
					$service = isValidInput($term,20,'s',false)?$term:'';
					$service = $fuzzy_start.$service.$fuzzy_end;
					$conditions .= " service ".$match_type_sql." '".mysqli_real_escape_string($link, $service)."'";
				}
				if($champ == 'issn'){
					$issn = isValidInput($term,50,'s',false)?$term:'';
					$issn = $fuzzy_start.$issn.$fuzzy_end;
					$conditions .= "  (issn ".$match_type_sql." '".mysqli_real_escape_string($link, $issn)."' or eissn ".$match_type_sql." '".mysqli_real_escape_string($link, $issn)."')";
				}
				if($champ == 'pmid'){
					$pmid = isValidInput($term,50,'s',false)?$term:'';
					$pmid = $fuzzy_start.$pmid.$fuzzy_end;
					$conditions .= " PMID ".$match_type_sql." '".mysqli_real_escape_string($link, $pmid)."'";
				}
				if($champ == 'localisation'){
					$localisation = isValidInput($term,20,'s',false)?$term:'';
					$localisation = $fuzzy_start.$localisation.$fuzzy_end;
					$conditions .= " localisation ".$match_type_sql." '".mysqli_real_escape_string($link, $localisation)."'";
					}
				if($champ == 'bibliotheque'){
					$bibliotheque = isValidInput($term,20,'s',false)?$term:'';
					$bibliotheque = $fuzzy_start.$bibliotheque.$fuzzy_end;
					$conditions .= " bibliotheque ".$match_type_sql." '".mysqli_real_escape_string($link, $bibliotheque)."'";
					}
				if($champ == 'reff'){
					$reff = isValidInput($term,50,'s',false)?'%'.$term.'%':'';
					$conditions .= " ref ".$match_type_sql." '".mysqli_real_escape_string($link, $reff)."'";
				}
				if($champ == 'refb'){
					$refb = isValidInput($term,50,'s',false)?'%'.$term.'%':'';
					$refb = $fuzzy_start.$refb.$fuzzy_end;
					$conditions .= " refinterbib ".$match_type_sql." '".mysqli_real_escape_string($link, $refb)."'";
				}
				if($champ == 'email'){
					$emailr = $fuzzy_start.$term.$fuzzy_end;
					$conditions .= " mail ".$match_type_sql." '".mysqli_real_escape_string($link, $emailr)."'";
				}
				if($champ == 'all'){
					$all = $term;
					if ($match_type_sql == 'LIKE') {
						$all = strtr($all, ' ', '%');
						$all = strtr($all, "'", '%');
					}
					$all = $fuzzy_start. mysqli_real_escape_string($link, $all).$fuzzy_end;
					$conditions .= " ((illinkid ".$match_type_sql." '$all') or (localisation ".$match_type_sql." '$all') or (bibliotheque ".$match_type_sql." '$all') or (ref ".$match_type_sql." '$all') or (nom ".$match_type_sql." '$all') or (prenom ".$match_type_sql." '$all') or (service ".$match_type_sql." '$all') or (cgra ".$match_type_sql." '$all') or (cgrb ".$match_type_sql." '$all') or (mail ".$match_type_sql." '$all') or (tel ".$match_type_sql." '$all') or (adresse ".$match_type_sql." '$all') or (code_postal ".$match_type_sql." '$all') or (localite ".$match_type_sql." '$all') or (envoi_par ".$match_type_sql." '$all') or (titre_periodique ".$match_type_sql." '$all') or (annee ".$match_type_sql." '$all') or (volume ".$match_type_sql." '$all') or (numero ".$match_type_sql." '$all') or (supplement ".$match_type_sql." '$all') or (pages ".$match_type_sql." '$all') or (titre_article ".$match_type_sql." '$all') or (auteurs ".$match_type_sql." '$all') or (edition ".$match_type_sql." '$all') or (isbn ".$match_type_sql." '$all') or (issn ".$match_type_sql." '$all') or (eissn ".$match_type_sql." '$all') or (doi ".$match_type_sql." '$all') or (uid ".$match_type_sql." '$all') or (remarques ".$match_type_sql." '$all') or (remarquespub ".$match_type_sql." '$all') or (historique ".$match_type_sql." '$all') or (refinterbib ".$match_type_sql." '$all') or (PMID ".$match_type_sql." '$all') or (ip ".$match_type_sql." '$all'))";
				}
				if($champ == 'nom'){
					$pos1 = strpos($term,',');
					$pos2 = strpos($term,' ');
					if (($pos1 === false)&&($pos2 === false)){
						// Searched name ha not space or comma -> search in both first and last name database column
						$nom=$fuzzy_start.$term.$fuzzy_end;
						$conditions .= " (nom ".$match_type_sql." '".mysqli_real_escape_string($link, $nom)."' OR prenom ".$match_type_sql." '".mysqli_real_escape_string($link, $nom)."')";
					}
					else{
						// If value has comma, then last name is probably first item. If value has space, then last name is probably second item
						if ($pos1 === false)
							$pos=$pos2;
						else
							$pos=$pos1;
						$nom=$fuzzy_start. trim(substr($term,0,$pos)).$fuzzy_end;
						$prenom=$fuzzy_start. trim(substr($term,$pos+1)).$fuzzy_end;
						$conditions .= " (nom ".$match_type_sql." '".mysqli_real_escape_string($link, $nom)."' AND prenom ".$match_type_sql." '".mysqli_real_escape_string($link, $prenom)."')";
					}
				}
				$conditions .= " ) "; // close local condition
				if ($must_close_parenthesis) {
					$conditions .= " ) "; // close nested parenthesis
				}
			}
		}
		if (!empty($conditions)) {
			$conditions = $guestFilter . ' ' . $conditions;
		}
		$conditions = 'WHERE '.$conditions;
		$conditions = $conditions.(empty($filtreStatut)?'':' AND '.$filtreStatut);
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
