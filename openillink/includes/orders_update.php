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

$id = ((!empty($_GET['id'])) && isValidInput($_GET['id'],8,'s',false)) ? $_GET['id'] : NULL;
$validActionSet = array('update', 'delete', 'deleteok');
$action= ((!empty($_GET['action'])) && isValidInput($_GET['action'],10,'s',false, $validActionSet)) ? $_GET['action'] : NULL;
if (empty($action)){
    $action = ((!empty($_POST['action'])) && isValidInput($_POST['action'],10,'s',false, $validActionSet)) ? $_POST['action'] : NULL;
}
if ( in_array ($monaut, array('admin', 'sadmin','user'), true)){
    // Début de l'édition
    if ($action == "update"){
        $id = ((!empty($_POST['id'])) && isValidInput($_POST['id'],8,'i',false)) ? $_POST['id'] : NULL;
        if (!empty($id)){
            $mes="";
            $doi="";
            $pmid="";
            $isbn="";
            $issn="";
            $eissn="";
            $userid = ((!empty($_POST['userid'])) && isValidInput($_POST['userid'],50,'s',false)) ? $_POST['userid'] : NULL;
            $stade = ((!empty($_POST['stade'])) && isValidInput($_POST['stade'],3,'i',false)) ? $_POST['stade'] : NULL;
            $date=((!empty($_POST['datesaisie'])) && validateDate($_POST['datesaisie']))?$_POST['datesaisie']:NULL;
            if (empty($date))
                $date=date("Y-m-d");
            $date2=date("d/m/Y H:i:s");
            $envoye=((!empty($_POST['envoye'])) && validateDate($_POST['envoye']))?$_POST['envoye']:NULL;
            $facture=((!empty($_POST['facture'])) && validateDate($_POST['facture']))?$_POST['facture']:NULL;
            $renouveler=((!empty($_POST['renouveler'])) && validateDate($_POST['renouveler']))?$_POST['renouveler']:NULL;
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
            $localisation = ((!empty($_POST['localisation'])) && isValidInput($_POST['localisation'],20,'s',false)) ? $_POST['localisation'] : NULL;
            $sid = ((!empty($_POST['sid'])) && isValidInput($_POST['sid'],50,'s',false)) ? $_POST['sid'] : NULL;
            $pid= ((!empty($_POST['pid'])) && isValidInput($_POST['pid'],50,'s',false)) ? $_POST['pid'] : NULL;
            $bibliotheque = ((!empty($_POST['bibliotheque'])) && isValidInput($_POST['bibliotheque'],50,'s',false)) ? $_POST['bibliotheque'] : NULL;
            $source=((!empty($_POST['source'])) && isValidInput($_POST['source'],20,'s',false)) ? $_POST['source']:NULL;
            $nom=((!empty($_POST['nom'])) && isValidInput($_POST['nom'],100,'s',false)) ? $_POST['nom']: NULL;
            $prenom=((!empty($_POST['prenom'])) && isValidInput($_POST['prenom'],100,'s',false)) ? $_POST['prenom']:NULL;
            $service=((!empty($_POST['service'])) && isValidInput($_POST['service'],20,'s',false)) ? $_POST['service']:NULL;
            $prix=((!empty($_POST['prix'])) && isValidInput($_POST['prix'],4,'s',false)) ? $_POST['prix']:NULL;
            $prepaye=((!empty($_POST['avance'])) && isValidInput($_POST['avance'],3,'s',false)) ? $_POST['avance']:NULL;
            $urgent=((!empty($_POST['urgent'])) && isValidInput($_POST['urgent'],3,'s',false)) ?$_POST['urgent']:NULL;
            $ref=((!empty($_POST['ref'])) && isValidInput($_POST['ref'],50,'s',false)) ?$_POST['ref']:NULL;
            $refinterbib=((!empty($_POST['refinterbib'])) && isValidInput($_POST['refinterbib'],50,'s',false)) ?$_POST['refinterbib']:NULL;
            $servautre = ((!empty($_POST['servautre'])) && isValidInput($_POST['servautre'],50,'s',false)) ? $_POST['servautre']:NULL;
            if (($servautre!='') && ($servautre!=$service))
                $service=$servautre;
            $cgra=((!empty($_POST['cgra'])) && isValidInput($_POST['cgra'],10,'s',false)) ? $_POST['cgra']:NULL;
            $cgrb=((!empty($_POST['cgrb'])) && isValidInput($_POST['cgrb'],10,'s',false)) ? $_POST['cgrb']:NULL;
            $mail=((!empty($_POST['mail'])) && isValidInput($_POST['mail'],100,'s',false)) ? trim($_POST['mail']):NULL;
            $tel=((!empty($_POST['tel'])) && isValidInput($_POST['tel'],20,'s',false)) ? $_POST['tel']:NULL;
            $adresse=((!empty($_POST['adresse'])) && isValidInput($_POST['adresse'],255,'s',false)) ? $_POST['adresse']:NULL;
            $postal=((!empty($_POST['postal'])) && isValidInput($_POST['postal'],10,'s',false)) ?$_POST['postal']:NULL;
            $localite=((!empty($_POST['localite'])) && isValidInput($_POST['localite'],50,'s',false)) ? $_POST['localite']:NULL;
            $envoi=((!empty($_POST['envoi'])) && isValidInput($_POST['envoi'],50,'s',false)) ?$_POST['envoi']:NULL;
            $tid = ((!empty($_POST['tid'])) && isValidInput($_POST['tid'],4,'s',false, array('pmid','doi'))) ?$_POST['tid']:NULL;
            $uids = trim($_POST['uids']);
            $uids = ((!empty($uids)) && isValidInput($uids,80,'s',false))?$uids:NULL;
            if ($tid=='pmid'){
                $uids = ((!empty($uids)) && isValidInput($uids,20,'s',false)) ?$uids:NULL;
                $pmid=$uids;
            }
            if ($tid=='doi'){
                $doi=$uids;
            }
            $typedoc=((!empty($_POST['genre'])) && isValidInput($_POST['genre'],50,'s',false)) ?$_POST['genre']:NULL;
            $journal=((!empty($_POST['title'])) && isValidInput($_POST['title'],1000,'s',false)) ?trim($_POST['title']):NULL;
            $annee=((!empty($_POST['date'])) && isValidInput($_POST['date'],10,'s',false)) ?trim($_POST['date']):NULL;
            $vol=((!empty($_POST['volume'])) && isValidInput($_POST['volume'],50,'s',false)) ?$_POST['volume']:NULL;
            $no=((!empty($_POST['issue'])) && isValidInput($_POST['issue'],100,'s',false)) ?$_POST['issue']:NULL;
            $suppl=((!empty($_POST['suppl'])) && isValidInput($_POST['suppl'],100,'s',false)) ?$_POST['suppl']:NULL;
            $pages=((!empty($_POST['pages'])) && isValidInput($_POST['pages'],50,'s',false)) ?$_POST['pages']:NULL;
            $titre=((!empty($_POST['atitle'])) && isValidInput($_POST['atitle'],1000,'s',false)) ? trim($_POST['atitle']):NULL;
            $auteurs=((!empty($_POST['auteurs'])) && isValidInput($_POST['auteurs'],255,'s',false)) ? $_POST['auteurs']:NULL;
            $edition=((!empty($_POST['edition'])) && isValidInput($_POST['edition'],100,'s',false)) ? $_POST['edition']:NULL;
            $issn = ((!empty($_POST['issn'])) && isValidInput($_POST['issn'],50, 's', false))?$_POST['issn']:NULL;
            if (!empty($issn)){
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
            $uid=((!empty($_POST['uid'])) && isValidInput($_POST['uid'],50, 's', false))?$_POST['uid']:NULL;
            if($pmid==''){
                if(strpos($uid, 'pmid:') !== false) {
					$pmid=str_replace("pmid:","",$uid);
				}
            }
            $remarques=((!empty($_POST['remarques'])) && isValidInput($_POST['remarques'],4000, 's', false))? $_POST['remarques']:NULL;
            $remarquespub=((!empty($_POST['remarquespub'])) && isValidInput($_POST['remarquespub'],4000, 's', false))? $_POST['remarquespub']:NULL;
            $modifs=((!empty($_POST['modifs'])) && isValidInput($_POST['modifs'],4000, 's', false))? $_POST['modifs']:NULL;
            $ip=$_SERVER['REMOTE_ADDR'];
            $historique=(((!empty($_POST['historique'])) && isValidInput($_POST['historique'],4000, 's', false))?$_POST['historique']:'').'<br /> Commande modifiée par ' . $monnom . ' le ' . $date2;
            if ($modifs)
                $historique=$historique.' ['.$modifs.']';
            if (empty($nom))
                $mes="le nom est obligatoire";
            if (empty($journal))
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
                echo "<td><b>".htmlspecialchars($id)."</b></td></tr>\n";
                echo "<tr><td width=\"90\"><b>Nom</b></td>\n";
                echo "<td>".htmlspecialchars($nom).", ".htmlspecialchars($prenom)."</td></tr>\n";
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
                    echo "<td>" . htmlspecialchars($adresse) . " ; " . htmlspecialchars($postal) . ", " . htmlspecialchars($localite) ."</td></tr>\n";
                }
                echo "<tr><td width=\"90\"><b>Document</b></td>\n";
                echo "<td>".htmlspecialchars($typedoc)."</td></tr>\n";
                if ($titre) {
                    echo "<tr><td width=\"90\"><b>Titre</b></td>\n";
                    echo "<td>" . htmlspecialchars($titre) . "</td></tr>\n";
                }
                if ($auteurs) {
                    echo "<tr><td width=\"90\"><b>Auteurs</b></td>\n";
                    echo "<td>" . htmlspecialchars($auteurs) . "</td></tr>\n";
                }
                if ($typedoc=='Article')
                    echo "<tr><td width=\"90\"><b>Périodique</b></td>\n";
                else
                    echo "<tr><td width=\"90\"><b>Titre du livre</b></td>\n";
                echo "<td>" . htmlspecialchars($journal) . "</td>\n";
                echo "</tr><tr>\n";
                if ($annee) {
                    echo "<td width=\"90\"><b>Année</b></td>\n";
                    echo "<td>".htmlspecialchars($annee)."</td></tr>\n";
                }
                if ($vol) {
                    echo "<tr><td  width=\"90\" valign=\"top\"><b>Volume</b></td>\n";
                    echo "<td>".htmlspecialchars($vol)."</td></tr>\n";
                }
                if ($no) {
                    echo "<tr><td  width=\"90\" valign=\"top\"><b>Numéro</b></td>\n";
                    echo "<td>".htmlspecialchars($no)."</td></tr>\n";
                }
                if ($suppl) {
                    echo "<tr><td  width=\"90\" valign=\"top\"><b>Suppl.</b></td>\n";
                    echo "<td>".htmlspecialchars($suppl)."</td></tr>\n";
                }
                if ($pages) {
                    echo "<tr><td  width=\"90\" valign=\"top\"><b>Pages</b></td>\n";
                    echo "<td>".htmlspecialchars($pages)."</td></tr>\n";
                }
                if ($edition) {
                    echo "<tr><td  width=\"90\" valign=\"top\"><b>Edition</b></td>\n";
                    echo "<td>".htmlspecialchars($edition)."</td></tr>\n";
                }
                if ($isbn) {
                    echo "<tr><td  width=\"90\" valign=\"top\"><b>ISBN</b></td>\n";
                    echo "<td>".htmlspecialchars($isbn)."</td></tr>\n";
                }
                if ($issn) {
                    echo "<tr><td  width=\"90\" valign=\"top\"><b>ISSN</b></td>\n";
                    echo "<td>".htmlspecialchars($issn)."</td></tr>\n";
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
                    echo "<td>".htmlspecialchars($uid)."</td></tr>\n";
                }
                if ($remarques) {
                    echo "<tr><td  width=\"90\" valign=\"top\"><b>Commentaire professionnel</b></td>\n";
                    echo "<td>".nl2br(htmlspecialchars($remarques))."</td></tr>\n";
                }
                if ($remarquespub) {
                    echo "<tr><td  width=\"90\" valign=\"top\"><b>Commentaire public</b></td>\n";
                    echo "<td>".nl2br(htmlspecialchars($remarquespub))."</td></tr>\n";
                }
                /*echo "<tr><td>localisation:</td><td>$localisation;</td></tr>\n";*/
                echo "</table>\n";
                echo "<div class=\"hr\"><hr></div>\n";
                echo "<b><center><a href=\"detail.php?table=orders&amp;id=".htmlspecialchars($id)."\">Retourner à la fiche de commande</a></center></b>\n";
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
            $myhtmltitle = $configname[$lang] . " : confirmation pour la suppresion de la commande " . htmlspecialchars($id);
            require ("headeradmin.php");
            echo "<center><br/><br/><br/><b><font color=\"red\">\n";
            echo "Voulez-vous vraiement supprimer la fiche " . htmlspecialchars($id) . "?</b></font>\n";
            echo "<form action=\"update.php\" method=\"POST\" enctype=\"x-www-form-encoded\" name=\"fiche\" id=\"fiche\">\n";
            echo "<input name=\"table\" type=\"hidden\" value=\"orders\">\n";
            echo "<input name=\"id\" type=\"hidden\" value=\"".htmlspecialchars($id)."\">\n";
            echo "<input name=\"action\" type=\"hidden\" value=\"deleteok\">\n";
            echo "<br /><br />\n";
            echo "<input type=\"submit\" value=\"Confirmer la suppression de la fiche " . htmlspecialchars($id) . " en cliquant ici\">\n";
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
            $id = ((!empty($_POST['id'])) && isValidInput($_POST['id'],8,'i',false)) ? $_POST['id'] : NULL;
            $myhtmltitle = $configname[$lang] . " : suppresion de la commande " . htmlspecialchars($id);
            require ("headeradmin.php");
            $query = "DELETE FROM orders WHERE orders.illinkid = ?";
            $result = dbquery($query, array($id), 's') or die("Error : ".mysqli_error());
            echo "<center><br/><b><font color=\"green\">\n";
            echo "La fiche " . htmlspecialchars($id) . " a été supprimée avec succès</b></font>\n";
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
