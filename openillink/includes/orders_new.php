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
// Page to save the order, display errors or confirm if the order is normally saved
//


//
// START Common Vars
// 15.03.2016, MDV Replaced connector to db from mysql_ to mysqli_
// 01.04.2016, MDV input verification
//
require_once ("connexion.php");
require_once ("toolkit.php");

$mes="";
$doi="";
$pmid="";
$isbn="";
$issn="";
$eissn="";
$userid= (isset($_POST['userid']) && isValidInput($_POST['userid'],50,'s',false))?$_POST['userid']:NULL;
if (! isset($userid)){
    $userid = (isset($_SERVER['REMOTE_ADDR']) && isValidInput($_SERVER['REMOTE_ADDR'],50,'s',false))?$_SERVER['REMOTE_ADDR']:NULL;
}

$referer=isset($_POST['referer'])? $_POST['referer'] :'';
//$action=$_POST['action']; // TODO : is that code usefull or useless?
$stade="";
$stade="";


// extended set of common vars
$uid = (isset($_POST['uid']) && isValidInput($_POST['uid'],50, 's', false))?$_POST['uid']:NULL;
$uid = convertLtGtToTxtValue($uid);
$validTidSet = array('pmid','doi');
$tid = (isset($_POST['tid']) && isValidInput($_POST['tid'],4, 's', false,$validTidSet))?addslashes($_POST['tid']):'';
if ($tid=='pmid'){
    $uids = trim($_POST['uids']);
    $uids = (isset($uids) && isValidInput($uids,20, 's', false))?addslashes($uids):'';
    $pmid = $uids;
}
elseif ($tid=='doi'){
    $uids = trim($_POST['uids']);
    $uids = (isset($uids) && isValidInput($uids,80, 's', false))?addslashes($uids):'';
    $doi = $uids;
}
$uids = convertLtGtToTxtValue($uids);
$sid=(isset($_POST['sid']) && isValidInput($_POST['sid'],50, 's', false))?$_POST['sid']:'';
$pid=(isset($_POST['pid']) && isValidInput($_POST['pid'],50, 's', false))?$_POST['pid']:'';
$source=(isset($_POST['source']) && isValidInput($_POST['source'],20, 's', false))?$_POST['source']:'';
$nom=(isset($_POST['nom']) && isValidInput($_POST['nom'],100, 's', false))?trim(addslashes($_POST['nom'])):'';
$nom=convertLtGtToTxtValue($nom);
$prenom=(isset($_POST['prenom']) && isValidInput($_POST['prenom'],100, 's', false))?trim(addslashes($_POST['prenom'])):'';
$prenom = convertLtGtToTxtValue($prenom);
$service=(isset($_POST['service']) && isValidInput($_POST['service'],20, 's', false))?$_POST['service']:'';
$servautre=(isset($_POST['servautre']) && isValidInput($_POST['servautre'],20, 's', false))?$_POST['servautre']:'';
if($servautre)
    $service=$servautre;
$service = convertLtGtToTxtValue($service);

$cgra=(isset($_POST['cgra']) && isValidInput($_POST['cgra'],10, 's', false))?addslashes($_POST['cgra']):'';
$cgra = convertLtGtToTxtValue($cgra);
$cgrb=(isset($_POST['cgrb']) && isValidInput($_POST['cgrb'],10, 's', false))?addslashes($_POST['cgrb']):'';
$cgrb = convertLtGtToTxtValue($cgrb);

$mail=(isset($_POST['mail']) && isValidInput($_POST['mail'],100, 's', false))?addslashes(trim($_POST['mail'])):'';
$mail = convertLtGtToTxtValue($mail);
$tel =(isset($_POST['tel']) && isValidInput($_POST['tel'],20, 's', false))?addslashes($_POST['tel']):'';$tel = convertLtGtToTxtValue($tel);
$adresse=(isset($_POST['adresse']) && isValidInput($_POST['adresse'],255 ,'s' ,false))?addslashes($_POST['adresse']):'';
$adresse = convertLtGtToTxtValue($adresse);
$postal=(isset($_POST['postal']) && isValidInput($_POST['postal'],10, 's', false))?addslashes($_POST['postal']):'';
$localite=(isset($_POST['localite']) && isValidInput($_POST['localite'],50, 's', false))?addslashes($_POST['localite']):'';
$localite = convertLtGtToTxtValue($localite);

$envoi=(isset($_POST['envoi']) && isValidInput($_POST['envoi'],50, 's', false))?addslashes($_POST['envoi']):'';

$typeDocValidSet = array('article','preprint','book','bookitem','thesis','journal','proceeding','conference','other');
$typedoc=(isset($_POST['genre']) && isValidInput($_POST['genre'],50, 's', false, $typeDocValidSet))?addslashes($_POST['genre']):'';
$journal=(isset($_POST['title']) && isValidInput($_POST['title'],1000, 's', false))?addslashes(trim($_POST['title'])):'';
$journal = convertLtGtToTxtValue($journal);
$annee=(isset($_POST['date']) && isValidInput($_POST['date'],10, 's', false))?addslashes($_POST['date']):'';
$annee = convertLtGtToTxtValue($annee);
$vol=(isset($_POST['volume']) && isValidInput($_POST['volume'],50, 's', false))?addslashes($_POST['volume']):'';
$vol = convertLtGtToTxtValue($vol);
$no=(isset($_POST['issue']) && isValidInput($_POST['issue'],100, 's', false))?$_POST['issue']:'';
$no = convertLtGtToTxtValue($no);
$suppl=(isset($_POST['suppl']) && isValidInput($_POST['suppl'],100, 's', false))?$_POST['suppl']:'';
$suppl = convertLtGtToTxtValue($suppl);
$pages=(isset($_POST['pages']) && isValidInput($_POST['pages'],50, 's', false))?$_POST['pages']:'';
$pages = convertLtGtToTxtValue($pages);
$titre=(isset($_POST['atitle']) && isValidInput($_POST['atitle'],1000, 's', false))?addslashes(trim($_POST['atitle'])):'';
$titre = convertLtGtToTxtValue($titre);
$auteurs=(isset($_POST['auteurs']) && isValidInput($_POST['auteurs'],255, 's', false))?addslashes($_POST['auteurs']):'';
$auteurs = convertLtGtToTxtValue($auteurs);
$edition=(isset($_POST['edition']) && isValidInput($_POST['edition'],100, 's', false))?addslashes($_POST['edition']):'';
$edition = convertLtGtToTxtValue($edition);
$issn = (isset($_POST['issn']) && isValidInput($_POST['issn'],50, 's', false))?$_POST['issn']:NULL;
$issn = convertLtGtToTxtValue($issn);

if (isset($issn)){
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
    if(ereg("pmid:",$uid))
        $pmid=str_replace("pmid:","",$uid);
}

$remarques=(isset($_POST['remarques']) && isValidInput($_POST['remarques'],4000, 's', false))?$_POST['remarques']:'';
$remarques = convertLtGtToTxtValue($remarques);
$remarquespub=(isset($_POST['remarquespub']) && isValidInput($_POST['remarquespub'],4000, 's', false))?$_POST['remarquespub']:'';
$remarquespub=str_replace("<script>","",$remarquespub);
$remarquespub=str_replace("</script>","",$remarquespub);
//$remarquespub=str_replace("script","scrpt",$remarquespub);
$remarquespub = convertLtGtToTxtValue($remarquespub);
$remarquespub=addslashes($remarquespub);

//
// END common vars
//
// START admin vars
//
if ( in_array ($monaut, array('admin', 'sadmin','user'), true)){
    $localisation= (isset($_POST['localisation']) && isValidInput($_POST['localisation'],20,'s',false))? $_POST['localisation']:NULL;
    $stade=(isset($_POST['stade']) && isValidInput($_POST['stade'],3,'i',false))? $_POST['stade']:NULL;
    $date= (isset($_POST['datesaisie']) && validateDate($_POST['datesaisie']))?$_POST['datesaisie']:NULL;
    if(!isset($date))
        $date=date("Y-m-d");
    $date2=date("d/m/Y H:i:s");
    $envoye=(isset($_POST['envoye']) && validateDate($_POST['envoye']))?$_POST['envoye']:'';
    $facture=(isset($_POST['facture']) && validateDate($_POST['facture']))?$_POST['facture']:'';
    $renouveler=(isset($_POST['renouveler']) && validateDate($_POST['renouveler']))?$_POST['renouveler']:'';
    $reqstatus="SELECT code FROM status WHERE status.special = ?";
    $resultstatus = dbquery($reqstatus,array('renew'), 's');
    while ($rowstatus = iimysqli_result_fetch_array($resultstatus)){
        $codestatus = $rowstatus["code"];
        if (($stade==$codestatus) && ($renouveler=='')){
            $renouveler = date("Y-m-d", mktime(0, 0, 0, date("m")+1, date("d"), date("Y")));
        }
    }
    $bibliotheque=(isset($_POST['bibliotheque']) && isValidInput($_POST['bibliotheque'],50, 's', false))?$_POST['bibliotheque']:'';
    $prix=(isset($_POST['prix']) && isValidInput($_POST['prix'],4, 's', false))?$_POST['prix']:'';
    $prepaye=(isset($_POST['avance']) && isValidInput($_POST['avance'],3, 's', false))?$_POST['avance']:'';
    $urgent=(isset($_POST['urgent']) && isValidInput($_POST['urgent'],3, 's', false))?$_POST['urgent']:'';
    $ref=(isset($_POST['ref']) && isValidInput($_POST['ref'],50, 's', false))?$_POST['ref']:'';
    $refinterbib=(isset($_POST['refinterbib']) && isValidInput($_POST['refinterbib'],50, 's', false))?$_POST['refinterbib']:'';
    // END admin vars
}
else{
    // START public vars
    $date=date("Y-m-d");
    $date2=date("d/m/Y H:i:s");
    $bibliotheque="";
    $localisation="";
    $validation = 0;
    $reqstatus="SELECT code FROM status WHERE status.special = ?";
    $resultstatus = dbquery($reqstatus,array('new'), 's');
    while ($rowstatus = iimysqli_result_fetch_array($resultstatus))
        $stade = $rowstatus["code"];
    if (isset($service)){
        $reqlibfromunits="SELECT library, validation FROM units WHERE units.code = ?";
        $resultunits = dbquery($reqlibfromunits,array($service), 's');
        while ($rowunits = iimysqli_result_fetch_array($resultunits)){
            $bibliotheque = $rowunits["library"];
            $localisation =  $rowunits["library"];
            $validation =  $rowunits["validation"];
        }
    }
    if ($bibliotheque == ""){
        $reqlibdefault="SELECT code FROM libraries WHERE libraries.default = ?";
        $resultlibdefault = dbquery($reqlibdefault,array(1),'s');
        while ($rowlibdefault = iimysqli_result_fetch_array($resultlibdefault)){
            $bibliotheque = $rowlibdefault["code"];
            $localisation =  $rowlibdefault["code"];
        }
    }
    if ($validation == 1){
        $reqstatus="SELECT code FROM status WHERE status.special = ?";
        $resultstatus = dbquery($reqstatus,array('tobevalidated'), 's');
        while ($rowstatus = iimysqli_result_fetch_array($resultstatus))
            $stade = $rowstatus["code"];
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
        $query ="INSERT INTO `orders` (`illinkid`, `stade`, `localisation`, `date`, `envoye`, `facture`, `renouveler`, `prix`, `prepaye`, `ref`, `arrivee`, `nom`, `prenom`, `service`, `cgra`, `cgrb`, `mail`, `tel`, `adresse`, `code_postal`, `localite`, `type_doc`, `urgent`, `envoi_par`, `titre_periodique`, `annee`, `volume`, `numero`, `supplement`, `pages`, `titre_article`, `auteurs`, `edition`, `isbn`, `issn`, `eissn`, `doi`, `uid`, `remarques`, `remarquespub`, `historique`, `saisie_par`, `bibliotheque`, `refinterbib`, `PMID`, `ip`, `referer`) VALUES ('', '$stade', '$localisation', '$date', '$envoye', '$facture', '$renouveler', '$prix', '$prepaye', '$ref', '$source', '$nom', '$prenom', '$service', '$cgra', '$cgrb', '$mail', '$tel', '$adresse', '$postal', '$localite', '$typedoc', '$urgent', '$envoi', '$journal', '$annee', '$vol', '$no', '$suppl', '$pages', '$titre', '$auteurs', '$edition', '$isbn', '$issn', '$eissn', '$doi', '$uid', '$remarques','$remarquespub', '$historique', '$userid', '$bibliotheque', '$refinterbib', '$pmid', '$ip', '$referer')";
        $monno = dbquery($query) or die("Error : ".mysqli_error());
        require ("headeradmin.php");
    }
    else{
        $query ="INSERT INTO `orders` (`illinkid`, `stade`, `localisation`, `date`, `envoye`, `facture`, `renouveler`, `prix`, `prepaye`, `ref`, `arrivee`, `nom`, `prenom`, `service`, `cgra`, `cgrb`, `mail`, `tel`, `adresse`, `code_postal`, `localite`, `type_doc`, `urgent`, `envoi_par`, `titre_periodique`, `annee`, `volume`, `numero`, `supplement`, `pages`, `titre_article`, `auteurs`, `edition`, `isbn`, `issn`, `eissn`, `doi`, `uid`, `remarques`, `remarquespub`, `historique`, `saisie_par`, `bibliotheque`, `refinterbib`, `PMID`, `ip`, `referer`) VALUES ('', '$stade', '$localisation', '$date', '', '', '', '', '', '', '$source', '$nom', '$prenom', '$service', '$cgra', '$cgrb', '$mail', '$tel', '$adresse', '$postal', '$localite', '$typedoc', '2', '$envoi', '$journal', '$annee', '$vol', '$no', '$suppl', '$pages', '$titre', '$auteurs', '$edition', '$isbn', '$issn', '$eissn', '$doi', '$uid', '$remarques', '$remarquespub', '$historique', '$userid', '$bibliotheque', '', '$pmid', '$ip', '$referer')";
        $monno = dbquery($query) or die("Error : ".mysqli_error());
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
    echo "<td>$nom, $prenom</td></tr>\n";
    if ($mail) {
        echo "<tr><td width=\"90\"><b>Courriel</b></td>\n";
        echo "<td>$mail</td></tr>\n";
    }
    if ($service) {
        echo "<tr><td width=\"90\"><b>Service</b></td>\n";
        echo "<td>$service</td></tr>\n";
    }
    if ($tel) {
        echo "<tr><td width=\"90\"><b>Tél.</b></td>\n";
        echo "<td>" . stripslashes($tel) . "</td></tr>\n";
    }
    if ($adresse) {
        echo "<tr><td width=\"90\"><b>Adresse</b></td>\n";
        echo "<td>" . stripslashes($adresse) . " ; " . stripslashes($postal) . ", " . stripslashes($localite) ."</td></tr>\n";
    }
    echo "<tr><td width=\"90\"><b>Document</b></td>\n";
    echo "<td>$typedoc</td></tr>\n";
    if ($titre) {
        echo "<tr><td width=\"90\"><b>Titre</b></td>\n";
        echo "<td>" . stripslashes($titre) . "</td></tr>\n";
    }
    if ($auteurs) {
        echo "<tr><td width=\"90\"><b>Auteurs</b></td>\n";
        echo "<td>" . stripslashes($auteurs) . "</td></tr>\n";
    }
    if ($typedoc=='Article')
        echo "<tr><td width=\"90\"><b>Périodique</b></td>\n";
    else
        echo "<tr><td width=\"90\"><b>Titre du livre</b></td>\n";
    echo "<td>" . stripslashes($journal) . "</td>\n";
    echo "</tr><tr>\n";
    if ($annee) {
        echo "<td width=\"90\"><b>Année</b></td>\n";
        echo "<td>$annee</td></tr>\n";
    }
    if ($vol) {
        echo "<tr><td  width=\"90\" valign=\"top\"><b>Volume</b></td>\n";
        echo "<td>$vol</td></tr>\n";
    }
    if ($no) {
        echo "<tr><td  width=\"90\" valign=\"top\"><b>Numéro</b></td>\n";
        echo "<td>$no</td></tr>\n";
    }
    if ($suppl) {
        echo "<tr><td  width=\"90\" valign=\"top\"><b>Suppl.</b></td>\n";
        echo "<td>$suppl</td></tr>\n";
    }
    if ($pages) {
        echo "<tr><td  width=\"90\" valign=\"top\"><b>Pages</b></td>\n";
        echo "<td>$pages</td></tr>\n";
    }
    if ($edition) {
        echo "<tr><td  width=\"90\" valign=\"top\"><b>Edition</b></td>\n";
        echo "<td>".stripslashes($edition)."</td></tr>\n";
    }
    if ($isbn) {
        echo "<tr><td  width=\"90\" valign=\"top\"><b>ISBN</b></td>\n";
        echo "<td>$isbn</td></tr>\n";
    }
    if ($issn) {
        echo "<tr><td  width=\"90\" valign=\"top\"><b>ISSN</b></td>\n";
        echo "<td>$issn</td></tr>\n";
    }
    if ($eissn) {
        echo "<tr><td  width=\"90\" valign=\"top\"><b>eISSN</b></td>\n";
        echo "<td>$eissn</td></tr>\n";
    }
    if ($pmid) {
        echo "<tr><td  width=\"90\" valign=\"top\"><b>PMID</b></td>\n";
        echo "<td>$pmid</td></tr>\n";
    }
    if ($doi) {
        echo "<tr><td  width=\"90\" valign=\"top\"><b>DOI</b></td>\n";
        echo "<td>$doi</td></tr>\n";
    }
    if ($uid) {
        echo "<tr><td  width=\"90\" valign=\"top\"><b>UID</b></td>\n";
        echo "<td>$uid</td></tr>\n";
    }
    if ($remarques) {
        echo "<tr><td  width=\"90\" valign=\"top\"><b>Commentaire professionnel</b></td>\n";
        echo "<td>".stripslashes(nl2br($remarques))."</td></tr>\n";
    }
    if ($remarquespub) {
        echo "<tr><td  width=\"90\" valign=\"top\"><b>Commentaire public</b></td>\n";
        echo "<td>".stripslashes(nl2br($remarquespub))."</td></tr>\n";
    }
    echo "</table>\n";
    echo "<div class=\"hr\"><hr></div>\n";
    echo "<b><center><a href=\"index.php\">Remplir une nouvelle commande</a></center></b>\n";
    echo "</div></div><div class=\"box-footer\"><div class=\"box-footer-right\"></div></div>\n";
    require ("footer.php");
}
?>
