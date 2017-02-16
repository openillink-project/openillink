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
// Page to save the order, display errors or confirm if the order is normally saved
//

//
// START Common Vars

require_once ("connexion.php");
require_once ("toolkit.php");

$mes="";
$doi="";
$pmid="";
$isbn="";
$issn="";
$eissn="";
$userid = $monnom;
if (empty($userid)){
    $userid = ((!empty($_SERVER['REMOTE_ADDR'])) && isValidInput($_SERVER['REMOTE_ADDR'],50,'s',false))?$_SERVER['REMOTE_ADDR']:NULL;
}

$referer=(!empty($_POST['referer']))? $_POST['referer'] :'';
//$action=$_POST['action']; // TODO : is that code usefull or useless?
$stade="";

// extended set of common vars
$uid = ((!empty($_POST['uid'])) && isValidInput($_POST['uid'],50, 's', false))?$_POST['uid']:NULL;
$validTidSet = array('pmid','doi');
$tid = ((!empty($_POST['tid'])) && isValidInput($_POST['tid'],4, 's', false,$validTidSet))?$_POST['tid']:'';
if ($tid=='pmid'){
    $uids = trim($_POST['uids']);
    $uids = ((!empty($uids)) && isValidInput($uids,20, 's', false))?$uids:'';
    $pmid = $uids;
}
elseif ($tid=='doi'){
    $uids = trim($_POST['uids']);
    $uids = ((!empty($uids)) && isValidInput($uids,80, 's', false))?$uids:'';
    $doi = $uids;
}
$sid=((!empty($_POST['sid'])) && isValidInput($_POST['sid'],50, 's', false))?$_POST['sid']:'';
$pid=((!empty($_POST['pid'])) && isValidInput($_POST['pid'],50, 's', false))?$_POST['pid']:'';
$source=((!empty($_POST['source'])) && isValidInput($_POST['source'],20, 's', false))?$_POST['source']:'';
$nom=((!empty($_POST['nom'])) && isValidInput($_POST['nom'],100, 's', false))?trim($_POST['nom']):'';
$prenom=((!empty($_POST['prenom'])) && isValidInput($_POST['prenom'],100, 's', false))?trim($_POST['prenom']):'';
$service=((!empty($_POST['service'])) && isValidInput($_POST['service'],20, 's', false))?$_POST['service']:'';
$servautre=((!empty($_POST['servautre'])) && isValidInput($_POST['servautre'],20, 's', false))?$_POST['servautre']:'';
if($servautre)
    $service=$servautre;

$cgra=((!empty($_POST['cgra'])) && isValidInput($_POST['cgra'],10, 's', false))?$_POST['cgra']:'';
$cgrb=((!empty($_POST['cgrb'])) && isValidInput($_POST['cgrb'],10, 's', false))?$_POST['cgrb']:'';

$mail=((!empty($_POST['mail'])) && isValidInput($_POST['mail'],100, 's', false))?trim($_POST['mail']):'';
$tel =((!empty($_POST['tel'])) && isValidInput($_POST['tel'],20, 's', false))?$_POST['tel']:'';
$adresse=((!empty($_POST['adresse'])) && isValidInput($_POST['adresse'],255 ,'s' ,false))?$_POST['adresse']:'';
$postal=((!empty($_POST['postal'])) && isValidInput($_POST['postal'],10, 's', false))?$_POST['postal']:'';
$localite=((!empty($_POST['localite'])) && isValidInput($_POST['localite'],50, 's', false))?$_POST['localite']:'';

$envoi=((!empty($_POST['envoi'])) && isValidInput($_POST['envoi'],50, 's', false))?$_POST['envoi']:'';

$typeDocValidSet = array('article','preprint','book','bookitem','thesis','journal','proceeding','conference','other');
$typedoc=((!empty($_POST['genre'])) && isValidInput($_POST['genre'],50, 's', false, $typeDocValidSet))?$_POST['genre']:'';
$journal=((!empty($_POST['title'])) && isValidInput($_POST['title'],1000, 's', false))?trim($_POST['title']):'';
$annee=((!empty($_POST['date'])) && isValidInput($_POST['date'],10, 's', false))?$_POST['date']:'';
$vol=((!empty($_POST['volume'])) && isValidInput($_POST['volume'],50, 's', false))?$_POST['volume']:'';
$no=((!empty($_POST['issue'])) && isValidInput($_POST['issue'],100, 's', false))?$_POST['issue']:'';
$suppl=((!empty($_POST['suppl'])) && isValidInput($_POST['suppl'],100, 's', false))?$_POST['suppl']:'';
$pages=((!empty($_POST['pages'])) && isValidInput($_POST['pages'],50, 's', false))?$_POST['pages']:'';
$titre=((!empty($_POST['atitle'])) && isValidInput($_POST['atitle'],1000, 's', false))?trim($_POST['atitle']):'';
$auteurs=((!empty($_POST['auteurs'])) && isValidInput($_POST['auteurs'],255, 's', false))?$_POST['auteurs']:'';
$edition=((!empty($_POST['edition'])) && isValidInput($_POST['edition'],100, 's', false))?$_POST['edition']:'';
$issn = ((!empty($_POST['issn'])) && isValidInput($_POST['issn'],50, 's', false))?$_POST['issn']:NULL;

if (!empty($issn)){
    if (($typedoc=='book')||($typedoc=='bookitem')||($typedoc=='proceeding')||($typedoc=='conference')){
        $isbn=$issn;
        $issn=''; // TODO MDV, replaces previous set, verify if it's ok
    }
    else{
        $pos = strpos($issn,',');
        if ($pos !== false){
            $eissn=substr($issn,$pos+1);
            $issn=substr($issn,0,$pos);
        }
    }
}


if($pmid==''){
    if(strpos($uid, 'pmid:') !== false) {
        $pmid=str_replace("pmid:","",$uid);
	}
}

$remarquespub=((!empty($_POST['remarquespub'])) && isValidInput($_POST['remarquespub'],4000, 's', false))?$_POST['remarquespub']:'';
$remarquespub=str_replace("<script>","",$remarquespub);
$remarquespub=str_replace("</script>","",$remarquespub);
//$remarquespub=str_replace("script","scrpt",$remarquespub);

$bibliotheque="";
$localisation="";
$validation = 0;
// retrieve default status for new orders
$reqstatus="SELECT code FROM status WHERE status.special = ?";
$resultstatus = dbquery($reqstatus,array('new'), 's');
while ($rowstatus = iimysqli_result_fetch_array($resultstatus)){
	$stade = $rowstatus["code"];
}
// retrieve default library, localization and validation constraint for given service
if (!empty($service)){
	$reqlibfromunits="SELECT library, validation FROM units WHERE units.code = ?";
	$resultunits = dbquery($reqlibfromunits,array($service), 's');
	while ($rowunits = iimysqli_result_fetch_array($resultunits)){
		$bibliotheque = $rowunits["library"];
		$localisation =  $rowunits["library"];
		$validation =  $rowunits["validation"];
	}
}

// If service require validation, retrieve default status
if ($validation == 1){
	$reqstatus="SELECT code FROM status WHERE status.special = ?";
	$resultstatus = dbquery($reqstatus,array('tobevalidated'), 's');
	while ($rowstatus = iimysqli_result_fetch_array($resultstatus))
		$stade = $rowstatus["code"];
}
//
// END common vars
//
// START admin vars
//
if ( in_array ($monaut, array('admin', 'sadmin','user'), true)){
    $remarques=((!empty($_POST['remarques'])) && isValidInput($_POST['remarques'],4000, 's', false))?$_POST['remarques']:'';
    // overwrite localisation if given
	$localisation= ((!empty($_POST['localisation'])) && isValidInput($_POST['localisation'],20,'s',false))? $_POST['localisation']: $localisation;
	// overwrite stade with computed localization if left as default.
    $stade=((!empty($_POST['stade'])) && isValidInput($_POST['stade'],3,'i',false) && $_POST['stade'] != "0")? $_POST['stade']:$stade;
    $date= ((!empty($_POST['datesaisie'])) && validateDate($_POST['datesaisie']))?$_POST['datesaisie']:NULL;
    if(empty($date))
        $date=date("Y-m-d");
    $date2=date("d/m/Y H:i:s");
    $envoye=((!empty($_POST['envoye'])) && validateDate($_POST['envoye']))?$_POST['envoye']:'';
    $facture=((!empty($_POST['facture'])) && validateDate($_POST['facture']))?$_POST['facture']:'';
    $renouveler=((!empty($_POST['renouveler'])) && validateDate($_POST['renouveler']))?$_POST['renouveler']:'';
    $reqstatus="SELECT code FROM status WHERE status.special = ?";
    $resultstatus = dbquery($reqstatus,array('renew'), 's');
    while ($rowstatus = iimysqli_result_fetch_array($resultstatus)){
        $codestatus = $rowstatus["code"];
        if (($stade==$codestatus) && ($renouveler=='')){
            $renouveler = date("Y-m-d", mktime(0, 0, 0, date("m")+1, date("d"), date("Y")));
        }
    }
	// overwrite bibliotheque if given
    $bibliotheque=((!empty($_POST['bibliotheque'])) && isValidInput($_POST['bibliotheque'],50, 's', false))?$_POST['bibliotheque']:$bibliotheque;
    $prix=((!empty($_POST['prix'])) && isValidInput($_POST['prix'],4, 's', false))?$_POST['prix']:'';
    $prepaye=((!empty($_POST['avance'])) && isValidInput($_POST['avance'],3, 's', false))?$_POST['avance']:'';
    $urgent=((!empty($_POST['urgent'])) && isValidInput($_POST['urgent'],3, 's', false))?$_POST['urgent']:'';
    $ref=((!empty($_POST['ref'])) && isValidInput($_POST['ref'],50, 's', false))?$_POST['ref']:'';
    $refinterbib=((!empty($_POST['refinterbib'])) && isValidInput($_POST['refinterbib'],50, 's', false))?$_POST['refinterbib']:'';
    // END admin vars
}
else{
    // START public vars
    $remarques = "";
    $date=date("Y-m-d");
    $date2=date("d/m/Y H:i:s");
	// When no library found for given service, use main library and localization
	if ($bibliotheque == ""){
		$reqlibdefault="SELECT code FROM libraries WHERE libraries.default = ?";
		$resultlibdefault = dbquery($reqlibdefault,array(1),'s');
		while ($rowlibdefault = iimysqli_result_fetch_array($resultlibdefault)){
			$bibliotheque = $rowlibdefault["code"];
			$localisation =  $rowlibdefault["code"];
		}
	}
    // END public vars
}

$ip=$_SERVER['REMOTE_ADDR'];
$historique='Commande saisie par ' . $userid . ' le ' . $date2;
if (empty($nom))
    $mes="le nom est obligatoire";
if (empty($service) && empty($servautre))
    $mes=$mes."<br>le nom du service ou de l'institution est obligatoire";
if (empty($journal))
    $mes=$mes."<br>le titre du périodique ou du livre est obligatoire";
if (empty($mail) && empty($adresse))
    $mes=$mes."<br>le e-mail ou l'adresse privée sont obligatoires";
if ($mes){
    if (in_array ( $monaut, array('admin', 'sadmin', 'user'), true ))
        require ("headeradmin.php");
    else
        require ("header.php");
    echo "\n";
    echo "<div class=\"box\"><div class=\"box-content\">\n";
    echo "<center><b><font color=\"red\">\n";
    echo $mes."</b></font>\n";
    echo "<br /><br /><a href=\"javascript:history.back();\"><b>retour au formulaire de saisie</a></b></center><br />\n";
    echo "</div></div><div class=\"box-footer\"><div class=\"box-footer-right\"></div></div>\n";
    require ("footer.php");
// Error message
}
else{
    // No errors, searching duplicates
    // Recherche de doublons par PMID ou par volume année et pages
    $req2 = "";
    if ($pmid!=''){
        if (($vol!='') && ($annee!='') && ($pages!='')) {
            $req2 = "SELECT illinkid FROM orders WHERE PMID LIKE ? OR (annee LIKE ? AND volume LIKE ? AND pages LIKE ?) ORDER BY illinkid DESC";
            $param2 = array($pmid, $annee, $vol, $pages);
            $typeparam2 = 'ssss';
        }
        else {
            $req2 = "SELECT illinkid FROM orders WHERE PMID LIKE ? ORDER BY illinkid DESC";
            $param2 = array($pmid);
            $typeparam2 = 's';
        }
    }
    else{
        if (($vol!='') && ($annee!='') && ($pages!='')){
            $req2 = "SELECT illinkid FROM orders WHERE annee LIKE ? AND volume LIKE ? AND pages LIKE ? ORDER BY illinkid DESC";
            $param2 = array($annee, $vol, $pages);
            $typeparam2 = 'sss';
        }
    }
    if ($req2!=''){
        $result2 = dbquery($req2,$param2,$typeparam2);
        $nb = iimysqli_num_rows($result2);
        if ($nb > 0){
            if ($remarques)
                $remarques = $remarques."\r\n";
            $remarques = $remarques."ATTENTION POSSIBLE DOUBLON DE LA COMMANDE";
            for ($i=0 ; $i<$nb ; $i++){
                $enreg2 = iimysqli_result_fetch_array($result2);
                $doublon = $enreg2['illinkid'];
                $remarques = $remarques." ".$doublon;
            }
        }
    }
    // fin de la recherche des doublons
    // START save record
    if ( in_array ($monaut, array('admin', 'sadmin','user'), true)){
        $query ="INSERT INTO `orders` (`illinkid`, `stade`, `localisation`, `date`, `envoye`, `facture`, `renouveler`, `prix`, `prepaye`, `ref`, `arrivee`, `nom`, `prenom`, `service`, `cgra`, `cgrb`, `mail`, `tel`, `adresse`, `code_postal`, `localite`, `type_doc`, `urgent`, `envoi_par`, `titre_periodique`, `annee`, `volume`, `numero`, `supplement`, `pages`, `titre_article`, `auteurs`, `edition`, `isbn`, `issn`, `eissn`, `doi`, `uid`, `remarques`, `remarquespub`, `historique`, `saisie_par`, `bibliotheque`, `refinterbib`, `PMID`, `ip`, `referer`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $params = array('', !empty($stade) ? $stade : '', $localisation, $date, $envoye, $facture, $renouveler, $prix, $prepaye, $ref, $source, $nom, $prenom, $service, $cgra, $cgrb, $mail, $tel, $adresse, $postal, $localite, $typedoc, $urgent, $envoi, $journal, $annee, $vol, $no, $suppl, $pages, $titre, $auteurs, $edition, $isbn, $issn, $eissn, $doi, $uid, $remarques,$remarquespub, $historique, $userid, $bibliotheque, $refinterbib, $pmid, $ip, $referer);
		$monno = dbquery($query, $params, 'sssssssssssssssssssssssssssssssssssssssssssssss') or die("Error : ".mysqli_error(dbconnect()));
        require ("headeradmin.php");
    }
    else{
        $query ="INSERT INTO `orders` (`illinkid`, `stade`, `localisation`, `date`, `envoye`, `facture`, `renouveler`, `prix`, `prepaye`, `ref`, `arrivee`, `nom`, `prenom`, `service`, `cgra`, `cgrb`, `mail`, `tel`, `adresse`, `code_postal`, `localite`, `type_doc`, `urgent`, `envoi_par`, `titre_periodique`, `annee`, `volume`, `numero`, `supplement`, `pages`, `titre_article`, `auteurs`, `edition`, `isbn`, `issn`, `eissn`, `doi`, `uid`, `remarques`, `remarquespub`, `historique`, `saisie_par`, `bibliotheque`, `refinterbib`, `PMID`, `ip`, `referer`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $params = array('', !empty($stade) ? $stade : '', $localisation, $date, '' , '', '', '', '', '', $source, $nom, $prenom, $service, $cgra, $cgrb, $mail, $tel, $adresse, $postal, $localite, $typedoc, '2', $envoi, $journal, $annee, $vol, $no, $suppl, $pages, $titre, $auteurs, $edition, $isbn, $issn, $eissn, $doi, $uid, $remarques, $remarquespub, $historique, $userid, $bibliotheque, '', $pmid, $ip, $referer);
		$monno = dbquery($query, $params, 'sssssssssssssssssssssssssssssssssssssssssssssss') or die("Error : ".mysqli_error(dbconnect()));
        require ("header.php");
    }
    echo "\n";
    echo "<div class=\"box\"><div class=\"box-content\">\n";
    echo "\n";
echo "<br/>\n";
if ($debugOn){
    echo 'userid(post):'.$_POST['userid'].';';
    echo 'userid(server):'.$_SERVER['REMOTE_ADDR'].';';
}
echo "<br/>\n";
echo "\n";
    echo "<center><b><font color=\"green\">Votre commande a été enregistrée avec succès et sera traitée prochainement</b></center></font>\n";
    echo "<div class=\"hr\"><hr></div>\n";
    echo "<table align=\"center\">\n";
    echo "</td></tr>\n";
    echo "<tr><td width=\"90\"><b>Commande</b></td>\n";
    echo "<td><b>$monno</b></td></tr>\n";
    echo "<tr><td width=\"90\"><b>Nom</b></td>\n";
    echo "<td>".htmlspecialchars($nom). ", ". htmlspecialchars($prenom)."</td></tr>\n";
    if ($mail) {
        echo "<tr><td width=\"90\"><b>Courriel</b></td>\n";
        echo "<td>".htmlspecialchars($mail)."</td></tr>\n";
    }
    if ($service) {
        echo "<tr><td width=\"90\"><b>Service</b></td>\n";
        echo "<td>".htmlspecialchars($service)."</td></tr>\n";
    }
    if ($tel) {
        echo "<tr><td width=\"90\"><b>Tél.</b></td>\n";
        echo "<td>" . htmlspecialchars($tel) . "</td></tr>\n";
    }
    if ($adresse) {
        echo "<tr><td width=\"90\"><b>Adresse</b></td>\n";
        echo "<td>" . htmlspecialchars ($adresse) . " ; " . htmlspecialchars ($postal) . ", " . htmlspecialchars ($localite) ."</td></tr>\n";
    }
    echo "<tr><td width=\"90\"><b>Document</b></td>\n";
    echo "<td>".htmlspecialchars($typedoc)."</td></tr>\n";
    if ($titre) {
        echo "<tr><td width=\"90\"><b>Titre</b></td>\n";
        echo "<td>" . htmlspecialchars ($titre) . "</td></tr>\n";
    }
    if ($auteurs) {
        echo "<tr><td width=\"90\"><b>Auteurs</b></td>\n";
        echo "<td>" . htmlspecialchars ($auteurs) . "</td></tr>\n";
    }
    if ($typedoc=='Article')
        echo "<tr><td width=\"90\"><b>Périodique</b></td>\n";
    else
        echo "<tr><td width=\"90\"><b>Titre du livre</b></td>\n";
    echo "<td>" . htmlspecialchars ($journal) . "</td>\n";
    echo "</tr><tr>\n";
    if ($annee) {
        echo "<td width=\"90\"><b>Année</b></td>\n";
        echo "<td>".htmlspecialchars ($annee)."</td></tr>\n";
    }
    if ($vol) {
        echo "<tr><td  width=\"90\" valign=\"top\"><b>Volume</b></td>\n";
        echo "<td>".htmlspecialchars ($vol)."</td></tr>\n";
    }
    if ($no) {
        echo "<tr><td  width=\"90\" valign=\"top\"><b>Numéro</b></td>\n";
        echo "<td>".htmlspecialchars ($no)."</td></tr>\n";
    }
    if ($suppl) {
        echo "<tr><td  width=\"90\" valign=\"top\"><b>Suppl.</b></td>\n";
        echo "<td>".htmlspecialchars ($suppl)."</td></tr>\n";
    }
    if ($pages) {
        echo "<tr><td  width=\"90\" valign=\"top\"><b>Pages</b></td>\n";
        echo "<td>".htmlspecialchars ($pages)."</td></tr>\n";
    }
    if ($edition) {
        echo "<tr><td  width=\"90\" valign=\"top\"><b>Edition</b></td>\n";
        echo "<td>".htmlspecialchars ($edition)."</td></tr>\n";
    }
    if ($isbn) {
        echo "<tr><td  width=\"90\" valign=\"top\"><b>ISBN</b></td>\n";
        echo "<td>".htmlspecialchars ($isbn)."</td></tr>\n";
    }
    if ($issn) {
        echo "<tr><td  width=\"90\" valign=\"top\"><b>ISSN</b></td>\n";
        echo "<td>".htmlspecialchars ($issn)."</td></tr>\n";
    }
    if ($eissn) {
        echo "<tr><td  width=\"90\" valign=\"top\"><b>eISSN</b></td>\n";
        echo "<td>".htmlspecialchars ($eissn)."</td></tr>\n";
    }
    if ($pmid) {
        echo "<tr><td  width=\"90\" valign=\"top\"><b>PMID</b></td>\n";
        echo "<td>".htmlspecialchars($pmid)."</td></tr>\n";
    }
    if ($doi) {
        echo "<tr><td  width=\"90\" valign=\"top\"><b>DOI</b></td>\n";
        echo "<td>".htmlspecialchars($doi)."</td></tr>\n";
    }
    if ($uid) {
        echo "<tr><td  width=\"90\" valign=\"top\"><b>UID</b></td>\n";
        echo "<td>".htmlspecialchars ($uid)."</td></tr>\n";
    }
	if (in_array($monaut, array('admin', 'sadmin','user'), true)){
		if ($remarques) {
			echo "<tr><td  width=\"90\" valign=\"top\"><b>Commentaire professionnel</b></td>\n";
			echo "<td>". nl2br(htmlspecialchars($remarques))."</td></tr>\n";
		}
	}
    if ($remarquespub) {
        echo "<tr><td  width=\"90\" valign=\"top\"><b>Commentaire public</b></td>\n";
        echo "<td>". nl2br(htmlspecialchars($remarquespub))."</td></tr>\n";
    }
    echo "</table>\n";
    echo "<div class=\"hr\"><hr></div>\n";
    echo "<b><center><a href=\"index.php\">Remplir une nouvelle commande</a></center></b>\n";
    echo "</div></div><div class=\"box-footer\"><div class=\"box-footer-right\"></div></div>\n";
    require ("footer.php");
}
?>
