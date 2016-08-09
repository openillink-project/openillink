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
// Orders search results
// 01.04.2016, MDV Input reading verification
//
require_once ("toolkit.php");

if(in_array ($monaut, array("admin", "sadmin", "user","guest"), true)){

    // Add filter when logged a guest
    if ($monaut == 'guest'){
        /* guest can be either a user with guest credits or a user with an automatic login mail + random password assigned by the system */
        $reqGuest = "SELECT * FROM users WHERE users.name ='$monnom'";
        $resGuest = dbquery($reqGuest);
        $nbGuest = iimysqli_num_rows($resGuest);
        if ($nbGuest==1){
            $guest = iimysqli_result_fetch_array($resGuest);
            $mailGuest = $guest['email'];
        }
        if (empty($mailGuest))
            $mailGuest = ((!empty($monnom)) && isValidInput($monnom,100,'s',false))?$monnom:'';
        
        //$guestFilter = "mail = '".((isset($monnom) && isValidInput($monnom,100,'s',false))? $monnom : '')."' AND ";
        $guestFilter = "mail = '".$mailGuest."' AND ";
        $guestFilterAlone = "mail = '".$mailGuest."'";
    }
    else
        $guestFilter = '';

    /* integration du filtre sur le statut */
    $statuscode = (isset($_GET['statuscode']))?$_GET['statuscode']:'';
    $filtreStatut = '';
    if (!empty($statuscode)) {
        $statuscode = str_replace('_st','',$statuscode);
        $statut = isValidInput($statuscode,6,'s',false)?$statuscode:'';
        $filtreStatut = "  stade = ".intval($statut)." ";
    }
    $champValides = array('id', 'datecom', 'dateenv', 'datefact', 'statut', 'localisation', 'nom', 'email', 'service', 'issn', 'pmid', 'title', 'atitle', 'auteurs', 'reff', 'refb', 'all');
    $champ = ((!empty($_GET['champ'])) && isValidInput($_GET['champ'], 15, 's', false, $champValides))?$_GET['champ']:'';
    $term = (isset($_GET['term']))?$_GET['term']:'';
    if (!empty($champ) && !empty($term)){
        if (empty($from))
            $from=0;
        if($champ == 'id'){
            $id = isValidInput($term,8,'i',false)?$term:'';
            $req2 = "SELECT illinkid FROM orders";
            $conditions = " WHERE $guestFilter illinkid = '$id' ";
        }
        if($champ == 'atitle'){
            $atitle = urldecode($term);
            $atitle = strtr($atitle, ' ', '%');
            $atitle = strtr($atitle, "'", '%');
            $atitle = '%'.$atitle.'%';
            $req2 = "SELECT illinkid FROM orders";
            $conditions = " WHERE ".$guestFilter." titre_article LIKE '$atitle'";
        }
        if($champ == 'auteurs'){
            $atitle = urldecode($term);
            $atitle = strtr($atitle, ' ', '%');
            $atitle = strtr($atitle, "'", '%');
            $atitle = '%'.$atitle.'%';
            $req2 = "SELECT illinkid FROM orders";
            $conditions = " WHERE ".$guestFilter." auteurs like '$atitle'";
        }
        if($champ == 'title'){
            $title = urldecode($term);
            $title = strtr($title, ' ', '%');
            $title = strtr($title, "'", '%');
            $title = '%'.$title.'%';
            $req2 = "SELECT illinkid FROM orders";
            $conditions = " WHERE ".$guestFilter." titre_periodique like '$title'";
        }
        if($champ == 'datecom'){
            $datecom = validateDate($term)?$term:'';
            $req2 = "SELECT illinkid FROM orders";
            $conditions = " WHERE ".$guestFilter." date = '$datecom'";
        }
        if($champ == 'dateenv'){
            $dateenv = validateDate($term)?$term:'';
            $req2 = "SELECT illinkid FROM orders ";
            $conditions = " WHERE ".$guestFilter." envoye = '$dateenv'";
        }
        if($champ == 'datefact'){
            $datefact = validateDate($term)?$term:'';
            $req2 = "SELECT illinkid FROM orders ";
            $conditions = " WHERE ".$guestFilter." facture = '$datefact'";
        }
        if($champ == 'service'){
            $service = isValidInput($term,20,'s',false)?$term:'';
            $req2 = "SELECT illinkid FROM orders ";
            $conditions = " WHERE $guestFilter service like '$service'";
        }
        if($champ == 'issn'){
            $issn = isValidInput($term,50,'s',false)?$term:'';
            $title = '%'.$issn.'%';
            $req2 = "SELECT illinkid FROM orders ";
            $conditions = " WHERE  (issn like '$issn' or eissn like '$issn') and mail = '$monnom'";
        }
        if($champ == 'pmid'){
            $pmid = isValidInput($term,50,'s',false)?$term:'';
            $req2 = "SELECT illinkid FROM orders ";
            $conditions = " WHERE ".$guestFilter." PMID like '$pmid'";
        }
        if($champ == 'localisation'){
            $localisation = isValidInput($term,20,'s',false)?$term:'';
            $req2 = "SELECT illinkid FROM orders ";
            $conditions = " WHERE ".$guestFilter." localisation like '$localisation'";
            }
        if($champ == 'reff'){
            $reff = isValidInput($term,50,'s',false)?'%'.$term.'%':'';
            $req2 = "SELECT illinkid FROM orders ";
            $conditions = " WHERE ".$guestFilter." ref like '$reff'";
        }
        if($champ == 'refb'){
            $refb = isValidInput($term,50,'s',false)?'%'.$term.'%':'';
            $req2 = "SELECT illinkid FROM orders ";
            $conditions = " WHERE ".$guestFilter." and refinterbib like '$refb'";
        }
        if($champ == 'email' && $guestFilter===''){
            $emailr = '%'.$term.'%';
            $req2 = "SELECT illinkid FROM orders ";
            $conditions = " WHERE mail like '$emailr'";
        }
        if($champ == 'all'){
            $all = urldecode($term);
            $all = strtr($all, ' ', '%');
            $all = strtr($all, "'", '%');
            $all = '%'.$all.'%';
            $req2 = "SELECT illinkid FROM orders ";
            $conditions = " WHERE ".$guestFilter." ((illinkid like '$all') or (localisation like '$all') or (ref like '$all') or (nom like '$all') or (prenom like '$all') or (service like '$all') or (cgra like '$all') or (cgrb like '$all') or (mail like '$all') or (tel like '$all') or (adresse like '$all') or (code_postal like '$all') or (localite like '$all') or (envoi_par like '$all') or (titre_periodique like '$all') or (annee like '$all') or (volume like '$all') or (numero like '$all') or (supplement like '$all') or (pages like '$all') or (titre_article like '$all') or (auteurs like '$all') or (edition like '$all') or (isbn like '$all') or (issn like '$all') or (eissn like '$all') or (doi like '$all') or (uid like '$all') or (remarques like '$all') or (remarquespub like '$all') or (historique like '$all') or (refinterbib like '$all') or (PMID like '$all') or (ip like '$all'))";
        }
        if($champ == 'nom'){
            $pos1 = strpos(urldecode($term),',');
            $pos2 = strpos(urldecode($term),' ');
            if (($pos1 === false)&&($pos2 === false)){
                $nom='%'.urldecode($term).'%';
                $req2 = "SELECT illinkid FROM orders ";
                $conditions = " WHERE ".$guestFilter." (nom like '$nom' OR prenom like 'nom')";
            }
            else{
                if ($pos1 === false)
                    $pos=$pos2;
                else
                    $pos=$pos1;
                $nom=trim(substr(urldecode($term),0,$pos));
                $prenom=trim(substr(urldecode($term),$pos+1));
                $req2 = "SELECT illinkid FROM orders ";
                $conditions = " WHERE ".$guestFilter." (nom like '$nom' AND prenom like '$prenom')";
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
}
?>
