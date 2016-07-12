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
// Order update or delete
//
// 15.03.2016, MDV Replaced connector to db from mysql_ to mysqli_
// 21.03.2016, MDV Input reading verification

require_once ('connexion.php');
require_once ("includes/toolkit.php");

$id = (isset($_GET['id']) && isValidInput($_GET['id'],8,'s',false)) ? $_GET['id'] : NULL;
$validActionSet = array('update', 'delete', 'deleteok');
$action= (isset($_GET['action']) && isValidInput($_GET['action'],10,'s',false, $validActionSet)) ? $_GET['action'] : NULL;
if (!isset($action)){
    $action = (isset($_POST['action']) && isValidInput($_POST['action'],10,'s',false, $validActionSet)) ? $_POST['action'] : NULL;
}
if ( in_array ($monaut, array('admin', 'sadmin','user'), true)){
    // Début de l'édition
    if ($action == "update"){
        $id = (isset($_POST['id']) && isValidInput($_POST['id'],8,'i',false)) ? $_POST['id'] : NULL;
        if (isset($id)){
            $mes="";
            $doi="";
            $pmid="";
            $isbn="";
            $issn="";
            $eissn="";
            $userid = (isset($_POST['userid']) && isValidInput($_POST['userid'],50,'s',false)) ? $_POST['userid'] : NULL;
            $stade = (isset($_POST['stade']) && isValidInput($_POST['stade'],3,'i',false)) ? $_POST['stade'] : NULL;
            $date=(isset($_POST['datesaisie']) && validateDate($_POST['datesaisie']))?$_POST['datesaisie']:NULL;
            if (!isset($date))
                $date=date("Y-m-d");
            $date2=date("d/m/Y H:i:s");
            $envoye=(isset($_POST['envoye']) && validateDate($_POST['envoye']))?$_POST['envoye']:NULL;
            $facture=(isset($_POST['facture']) && validateDate($_POST['facture']))?$_POST['facture']:NULL;
            $renouveler=(isset($_POST['renouveler']) && validateDate($_POST['renouveler']))?$_POST['renouveler']:NULL;
            // Ajouter un delai de renouvellement d'un mois si status.special = renew et pas de date de renouvellement
            $req = "SELECT status.* FROM status WHERE status.special = ?";
            $result = dbquery($req, array('renew'), 's');
            $nb = iimysqli_num_rows($result);
            if ($nb > 0){
                for ($i=0 ; $i<$nb ; $i++){
                    $enreg = iimysqli_result_fetch_array($result);
                    $statuscoderenew = $enreg['code'];
                    if (($stade==$statuscoderenew) && ($renouveler=='0000-00-00'))
                        $renouveler = date("Y-m-d", mktime(0, 0, 0, date("m")+1, date("d"), date("Y")));
                }
            }
            // Ajouter la date du jour si status.special = sent
            $req = "SELECT status.* FROM status WHERE status.special = ?";
            $result = dbquery($req, array('sent'),'s');
            $nb = iimysqli_num_rows($result);
            if ($nb > 0){
                for ($i=0 ; $i<$nb ; $i++){
                    $enreg = iimysqli_result_fetch_array($result);
                    $statuscodesent = $enreg['code'];
                    if (($stade==$statuscodesent) && ($envoye=='0000-00-00')){
                        $envoye=date("Y-m-d");
                    }
                }
            }
            // Ajouter la date du jour si status.special = paid
            $req = "SELECT status.* FROM status WHERE status.special = ?";
            $result = dbquery($req,array('paid'),'s');
            $nb = iimysqli_num_rows($result);
            if ($nb > 0){
                for ($i=0 ; $i<$nb ; $i++){
                    $enreg = iimysqli_result_fetch_array($result);
                    $statuscodepaid = $enreg['code'];
                    if (($stade==$statuscodepaid) && ($facture=='0000-00-00')){
                        $facture=date("Y-m-d");
                    }
                }
            }
            $localisation = (isset($_POST['localisation']) && isValidInput($_POST['localisation'],20,'s',false)) ? $_POST['localisation'] : NULL;
            $sid = (isset($_POST['sid']) && isValidInput($_POST['sid'],50,'s',false)) ? $_POST['sid'] : NULL;
            $pid= (isset($_POST['pid']) && isValidInput($_POST['pid'],50,'s',false)) ? $_POST['pid'] : NULL;
            $bibliotheque = (isset($_POST['bibliotheque']) && isValidInput($_POST['bibliotheque'],50,'s',false)) ? $_POST['bibliotheque'] : NULL;
            $source=(isset($_POST['source']) && isValidInput($_POST['source'],20,'s',false)) ? $_POST['source']:NULL;
            $nom=(isset($_POST['nom']) && isValidInput($_POST['nom'],100,'s',false)) ? addslashes($_POST['nom']): NULL;
            $prenom=(isset($_POST['prenom']) && isValidInput($_POST['prenom'],100,'s',false)) ? addslashes($_POST['prenom']):NULL;
            $service=(isset($_POST['service']) && isValidInput($_POST['service'],20,'s',false)) ? $_POST['service']:NULL;
            $prix=(isset($_POST['prix']) && isValidInput($_POST['prix'],4,'s',false)) ? $_POST['prix']:NULL;
            $prepaye=(isset($_POST['avance']) && isValidInput($_POST['avance'],3,'s',false)) ? $_POST['avance']:NULL;
            $urgent=(isset($_POST['urgent']) && isValidInput($_POST['urgent'],3,'s',false)) ?$_POST['urgent']:NULL;
            $ref=(isset($_POST['ref']) && isValidInput($_POST['ref'],50,'s',false)) ?$_POST['ref']:NULL;
            $refinterbib=(isset($_POST['refinterbib']) && isValidInput($_POST['refinterbib'],50,'s',false)) ?$_POST['refinterbib']:NULL;
            $servautre = (isset($_POST['servautre']) && isValidInput($_POST['servautre'],50,'s',false)) ? $_POST['servautre']:NULL;
            if (($servautre!='') && ($servautre!=$service))
                $service=$servautre;
            $cgra=(isset($_POST['cgra']) && isValidInput($_POST['cgra'],10,'s',false)) ? addslashes($_POST['cgra']):NULL;
            $cgrb=(isset($_POST['cgrb']) && isValidInput($_POST['cgrb'],10,'s',false)) ? addslashes($_POST['cgrb']):NULL;
            $mail=(isset($_POST['mail']) && isValidInput($_POST['mail'],100,'s',false)) ? trim($_POST['mail']):NULL;
            $tel=(isset($_POST['tel']) && isValidInput($_POST['tel'],20,'s',false)) ? addslashes($_POST['tel']):NULL;
            $adresse=(isset($_POST['adresse']) && isValidInput($_POST['adresse'],255,'s',false)) ? addslashes($_POST['adresse']):NULL;
            $postal=(isset($_POST['postal']) && isValidInput($_POST['postal'],10,'s',false)) ?addslashes($_POST['postal']):NULL;
            $localite=(isset($_POST['localite']) && isValidInput($_POST['localite'],50,'s',false)) ? addslashes($_POST['localite']):NULL;
            $envoi=(isset($_POST['envoi']) && isValidInput($_POST['envoi'],50,'s',false)) ?$_POST['envoi']:NULL;
            $tid = (isset($_POST['tid']) && isValidInput($_POST['tid'],4,'s',false, array('pmid','doi'))) ?$_POST['tid']:NULL;
            $uids = trim($_POST['uids']);
            $uids = (isset($uids) && isValidInput($uids,80,'s',false))?$uids:NULL;
            if ($tid=='pmid'){
                $uids = (isset($uids) && isValidInput($uids,20,'s',false)) ?$uids:NULL;
                $pmid=$uids;
            }
            if ($tid=='doi'){
                $doi=$uids;
            }
            $typedoc=(isset($_POST['genre']) && isValidInput($_POST['genre'],50,'s',false)) ?$_POST['genre']:NULL;
            $journal=(isset($_POST['title']) && isValidInput($_POST['title'],1000,'s',false)) ?addslashes(trim($_POST['title'])):NULL;
            $annee=(isset($_POST['date']) && isValidInput($_POST['date'],10,'s',false)) ?addslashes(trim($_POST['date'])):NULL;
            $vol=(isset($_POST['volume']) && isValidInput($_POST['volume'],50,'s',false)) ?$_POST['volume']:NULL;
            $no=(isset($_POST['issue']) && isValidInput($_POST['issue'],100,'s',false)) ?$_POST['issue']:NULL;
            $suppl=(isset($_POST['suppl']) && isValidInput($_POST['suppl'],100,'s',false)) ?$_POST['suppl']:NULL;
            $pages=(isset($_POST['pages']) && isValidInput($_POST['pages'],50,'s',false)) ?$_POST['pages']:NULL;
            $titre=(isset($_POST['atitle']) && isValidInput($_POST['atitle'],1000,'s',false)) ?addslashes(trim($_POST['atitle'])):NULL;
            $auteurs=(isset($_POST['auteurs']) && isValidInput($_POST['auteurs'],50,'s',false)) ?addslashes($_POST['auteurs']):NULL;
            $edition=(isset($_POST['edition']) && isValidInput($_POST['edition'],100,'s',false)) ?addslashes($_POST['edition']):NULL;
            $issn = (isset($_POST['issn']) && isValidInput($_POST['issn'],50, 's', false))?$_POST['issn']:NULL;
            if (isset($issn)){
                if ( in_array($typedoc, array('book', 'bookitem', 'proceeding', 'conference'), true)){
                    $isbn = $issn;
                    $issn = '';
                }
                else{
                    $pos = strpos($issn,',');
                    if ($pos === false){
                        $issn=$issn;
                    }
                    else{
                        $eissn=substr($issn,$pos+1);
                        $issn=substr($issn,0,$pos);
                    }
                }
            }
            $uid=(isset($_POST['uid']) && isValidInput($_POST['uid'],50, 's', false))?$_POST['uid']:NULL;
            if($pmid==''){
                if(ereg("pmid:",$uid))
                    $pmid=str_replace("pmid:","",$uid);
            }
            $remarques=(isset($_POST['remarques']) && isValidInput($_POST['remarques'],4000, 's', false))?addslashes($_POST['remarques']):NULL;
            $remarquespub=(isset($_POST['remarquespub']) && isValidInput($_POST['remarquespub'],4000, 's', false))?addslashes($_POST['remarquespub']):NULL;
            $modifs=(isset($_POST['modifs']) && isValidInput($_POST['modifs'],4000, 's', false))?addslashes($_POST['modifs']):NULL;
            $ip=$_SERVER['REMOTE_ADDR'];
            $historique=((isset($_POST['historique']) && isValidInput($_POST['historique'],4000, 's', false))?$_POST['historique']:'').'<br /> Commande modifiée par ' . $monnom . ' le ' . $date2;
            if ($modifs)
                $historique=$historique.' ['.$modifs.']';
            if (!isset($nom))
                $mes="le nom est obligatoire";
            if (!isset($journal))
                $mes=$mes."<br>le titre du périodique ou du livre est obligatoire";
            if ($mes){
                require ("headeradmin.php");
                echo "\n";
                echo "<div class=\"box\"><div class=\"box-content\">\n";
                echo "<center><b><font color=\"red\">\n";
                echo $mes."</b></font>\n";
                echo "<br /><br /><a href=\"javascript:history.back();\"><b>retour au formulaire de saisie</a></b></center><br />\n";
                echo "</div></div><div class=\"box-footer\"><div class=\"box-footer-right\"></div></div>\n";
                require ("footer.php");
            }
            else{
                $query ="UPDATE orders ".
                "SET stade=?, localisation=?, date=?, envoye=?, facture=?, renouveler=?, prix=?, prepaye=?, ref=?, arrivee=?, nom=?, prenom=?, service=?, cgra=?, cgrb=?, mail=?, tel=?, adresse=?, code_postal=?, localite=?, type_doc=?, urgent=?, envoi_par=?, titre_periodique=?, annee=?, volume=?, numero=?, supplement=?, pages=?, titre_article=?, auteurs=?, edition=?, isbn=?, issn=?, eissn=?, doi=?, uid=?, remarques=?, remarquespub=?, historique=?, saisie_par=?, bibliotheque=?, refinterbib=?, PMID=?, ip=? WHERE illinkid=?";
                $params = array($stade, $localisation, $date, $envoye, $facture, $renouveler, $prix, $prepaye, $ref, $source, $nom, $prenom, $service, $cgra, $cgrb, $mail, $tel, $adresse, $postal, $localite, $typedoc, $urgent, $envoi, $journal, $annee, $vol, $no, $suppl, $pages, $titre, $auteurs, $edition, $isbn, $issn, $eissn, $doi, $uid, $remarques, $remarquespub,$historique, $userid, $bibliotheque, $refinterbib, $pmid, $ip, $id);
                $typeparams = 'sssssssssssssssssssssssssssssssssssssssssssssi';
                $result = dbquery($query,$params,$typeparams) or die("Error : ".mysqli_error());
                require ("headeradmin.php");
                echo "\n";
                echo "<div class=\"box\"><div class=\"box-content\">\n";
                echo "\n";
                echo "<center><b><font color=\"green\">Votre commande a été modifiée avec succès</b></center></font>\n";
                echo "<div class=\"hr\"><hr></div>\n";
                echo "<table align=\"center\">\n";
                echo "</td></tr>\n";
                echo "<tr><td width=\"90\"><b>Commande</b></td>\n";
                echo "<td><b>".$id."</b></td></tr>\n";
                echo "<tr><td width=\"90\"><b>Nom</b></td>\n";
                echo "<td>".$nom.", ".$prenom."</td></tr>\n";
                if ($mail) {
                    echo "<tr><td width=\"90\"><b>Courriel</b></td>\n";
                    echo "<td>".$mail."</td></tr>\n";
                }
                if ($service) {
                    echo "<tr><td width=\"90\"><b>Service</b></td>\n";
                    echo "<td>".$service."</td></tr>\n";
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
                /*echo "<tr><td>localisation:</td><td>$localisation;</td></tr>\n";*/
                echo "</table>\n";
                echo "<div class=\"hr\"><hr></div>\n";
                echo "<b><center><a href=\"detail.php?table=orders&id=".$id."\">Retourner à la fiche de commande</a></center></b>\n";
                echo "</div></div><div class=\"box-footer\"><div class=\"box-footer-right\"></div></div>\n";
            }
        }
        else{
            require ("headeradmin.php");
            require ("loginfail.php");
        }
        require ("footer.php");
    }
    // Fin de la modification
    // Début de la suppresion
    if ($action == "delete"){
        if (($monaut == "admin")||($monaut == "sadmin")){
            $myhtmltitle = $configname[$lang] . " : confirmation pour la suppresion de la commande " . $id;
            require ("headeradmin.php");
            echo "<center><br/><br/><br/><b><font color=\"red\">\n";
            echo "Voulez-vous vraiement supprimer la fiche " . $id . "?</b></font>\n";
            echo "<form action=\"update.php\" method=\"POST\" enctype=\"x-www-form-encoded\" name=\"fiche\" id=\"fiche\">\n";
            echo "<input name=\"table\" type=\"hidden\" value=\"orders\">\n";
            echo "<input name=\"id\" type=\"hidden\" value=\"".$id."\">\n";
            echo "<input name=\"action\" type=\"hidden\" value=\"deleteok\">\n";
            echo "<br /><br />\n";
            echo "<input type=\"submit\" value=\"Confirmer la suppression de la fiche " . $id . " en cliquant ici\">\n";
            echo "</form>\n";
            echo "<br/><br/><br/><a href=\"list.php?table=orders\">Retour à la liste des commandes</a></center>\n";
            echo "</center>\n";
            echo "\n";
        }
        else{
            require ("headeradmin.php");
            require ("loginfail.php");
        }
        require ("footer.php");
    }

    // Confimation de la suppresion
    if ($action == "deleteok"){
        if (($monaut == "admin")||($monaut == "sadmin")){
            $id = (isset($_POST['id']) && isValidInput($_POST['id'],8,'i',false)) ? $_POST['id'] : NULL;
            $myhtmltitle = $configname[$lang] . " : suppresion de la commande " . $id;
            require ("headeradmin.php");
            $query = "DELETE FROM orders WHERE orders.illinkid = ?";
            $result = dbquery($query, array($id), 's') or die("Error : ".mysqli_error());
            echo "<center><br/><b><font color=\"green\">\n";
            echo "La fiche " . $id . " a été supprimée avec succès</b></font>\n";
            echo "<br/><br/><br/><a href=\"list.php?table=orders\">Retour à la liste des commandes</a></center>\n";
            echo "</center>\n";
            echo "\n";
        }
        else{
            require ("headeradmin.php");
            require ("loginfail.php");
        }
        require ("footer.php");
    }
}
else{
    require ("header.php");
    require ("loginfail.php");
    require ("footer.php");
}
?>
