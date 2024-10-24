<?php
// ***************************************************************************
// ***************************************************************************
// ***************************************************************************
// This file is part of OpenILLink software.
// Copyright (C) 2005, 2006, 2007, 2008, 2009, 2010, 2011, 2012, 2013, 2015, 2016, 2017, 2018, 2020, 2024 CHUV.
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
// Order update or delete
//

require_once ('connexion.php');
require_once ("toolkit.php");
require_once ("authip.php");

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
                    if (($stade==$statuscoderenew) && ($renouveler=='0000-00-00' || empty($renouveler)))
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
                    if (($stade==$statuscodesent) && ($envoye=='0000-00-00' || empty($envoye) )){
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
                    if (($stade==$statuscodepaid) && ($facture=='0000-00-00' || empty($facture) )){
                        $facture=date("Y-m-d");
                    }
                }
            }
            $localisation = ((!empty($_POST['localisation'])) && isValidInput($_POST['localisation'],20,'s',false)) ? $_POST['localisation'] : "";
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
            $servautre = ((!empty($_POST['servautre'])) && isValidInput($_POST['servautre'],255,'s',false)) ? $_POST['servautre']:NULL;
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
            $tid = ((!empty($_POST['tid_0'])) && isValidInput($_POST['tid_0'],4,'s',false, array('pmid','doi'))) ?$_POST['tid_0']:NULL;
            $uids = trim(null_to_empty_string($_POST['uids_0']));
            $uids = ((!empty($uids)) && isValidInput($uids,255,'s',false))?$uids:NULL;
            if ($tid=='pmid'){
                $uids = ((!empty($uids)) && isValidInput($uids,20,'s',false)) ?$uids:NULL;
                $pmid=$uids;
            }
            if ($tid=='doi'){
                $doi=$uids;
            }
            $typedoc=((!empty($_POST['genre_0'])) && isValidInput($_POST['genre_0'],50,'s',false)) ?$_POST['genre_0']:"";
            $journal=((!empty($_POST['title_0'])) && isValidInput($_POST['title_0'], null,'s',false)) ?trim($_POST['title_0']):NULL;
            $annee=((!empty($_POST['date_0'])) && isValidInput(substr($_POST['date_0'], 0, 10),10,'s',false)) ?trim(substr($_POST['date_0'], 0, 10)):NULL;
            $vol=((!empty($_POST['volume_0'])) && isValidInput(substr($_POST['volume_0'], 0, 50),50,'s',false)) ?substr($_POST['volume_0'], 0, 50):NULL;
            $no=((!empty($_POST['issue_0'])) && isValidInput(substr($_POST['issue_0'], 0, 100),100,'s',false)) ?substr($_POST['issue_0'], 0, 100):NULL;
            $suppl=((!empty($_POST['suppl_0'])) && isValidInput(substr($_POST['suppl_0'], 0, 100),100,'s',false)) ?substr($_POST['suppl_0'], 0, 100):NULL;
            $pages=((!empty($_POST['pages_0'])) && isValidInput(substr($_POST['pages_0'], 0, 50),50,'s',false)) ?substr($_POST['pages_0'], 0, 50):NULL;
            $titre=((!empty($_POST['atitle_0'])) && isValidInput($_POST['atitle_0'], null,'s',false)) ? trim($_POST['atitle_0']):NULL;
            $auteurs=((!empty($_POST['auteurs_0'])) && isValidInput(substr($_POST['auteurs_0'], 0, 255),255,'s',false)) ? substr($_POST['auteurs_0'], 0, 255):NULL;
            $edition=((!empty($_POST['edition_0'])) && isValidInput(substr($_POST['edition_0'], 0, 100),100,'s',false)) ? substr($_POST['edition_0'], 0, 100):NULL;
            $issn = ((!empty($_POST['issn_0'])) && isValidInput(substr($_POST['issn_0'], 0, 50),50, 's', false))?substr($_POST['issn_0'], 0, 50):NULL;
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
                    $issn = substr($issn, 0, 20);
                    $eissn = substr($eissn, 0, 20);
                }
            }
            $uid=((!empty($_POST['uid_0'])) && isValidInput(substr($_POST['uid_0'], 0, 255),255, 's', false))?substr($_POST['uid_0'], 0, 255):NULL;
            $parsed_uid = parse_uid_str($uid);
            if($pmid == ''){
                if(array_key_exists('pmid', $parsed_uid)) {
                    $pmid = $parsed_uid['pmid'];
                }
            }
            if($doi == ''){
                if(array_key_exists('doi', $parsed_uid)) {
                    $doi = $parsed_uid['doi'];
                }
            }

            $remarques=((!empty($_POST['remarques'])) && isValidInput($_POST['remarques'], null, 's', false))? $_POST['remarques']:NULL;
            $remarquespub=((!empty($_POST['remarquespub_0'])) && isValidInput($_POST['remarquespub_0'],null, 's', false))? $_POST['remarquespub_0']:NULL;
            $modifs=((!empty($_POST['modifs'])) && isValidInput($_POST['modifs'],4000, 's', false))? $_POST['modifs']:NULL;

            $historique=(((!empty($_POST['historique'])) && isValidInput($_POST['historique'], null, 's', false))?$_POST['historique']:'').'<br /> Commande modifiée par ' . $monnom . ' le ' . $date2;
            if ($modifs)
                $historique=$historique.' ['.$modifs.']';
            if (empty($nom))
                $mes="le nom est obligatoire";
            if (empty($journal))
                $mes=$mes."<br>". __("journal or book title is required");
            if ($mes){
                require ("headeradmin.php");
                echo "\n";
                echo "<div class=\"box\"><div class=\"box-content\">\n";
                echo "<center><b><font color=\"red\">\n";
                echo $mes."</b></font>\n";
                echo "<br /><br /><a href=\"javascript:history.back();\"><b>".__("Back to entry form")."</a></b></center><br />\n";
                echo "</div></div><div class=\"box-footer\"><div class=\"box-footer-right\"></div></div>\n";
                require ("footer.php");
            }
            else{
                $query ="UPDATE orders ".
                "SET stade=?, localisation=?, date=?, envoye=?, facture=?, renouveler=?, prix=?, prepaye=?, ref=?, arrivee=?, nom=?, prenom=?, service=?, cgra=?, cgrb=?, mail=?, tel=?, adresse=?, code_postal=?, localite=?, type_doc=?, urgent=?, envoi_par=?, titre_periodique=?, annee=?, volume=?, numero=?, supplement=?, pages=?, titre_article=?, auteurs=?, edition=?, isbn=?, issn=?, eissn=?, doi=?, uid=?, remarques=?, remarquespub=?, historique=?, saisie_par=?, bibliotheque=?, refinterbib=?, PMID=?, ip=? WHERE illinkid=?";
                $params = array(($stade ? $stade : 0), $localisation, $date, $envoye, $facture, $renouveler, $prix, $prepaye, $ref, $source, $nom, $prenom, $service, $cgra, $cgrb, $mail, $tel, $adresse, $postal, $localite, $typedoc, $urgent, $envoi, $journal, $annee, $vol, $no, $suppl, $pages, $titre, $auteurs, $edition, $isbn, $issn, $eissn, $doi, $uid, $remarques, $remarquespub,$historique, $userid, $bibliotheque, $refinterbib, $pmid, $ip, $id);
                $typeparams = 'sssssssssssssssssssssssssssssssssssssssssssssi';
                $result = dbquery($query,$params,$typeparams) or die("Error : ".mysqli_error(dbconnect()));
				update_folders_item_count();
                require ("headeradmin.php");
                echo "\n";
                echo "<div class=\"box\"><div class=\"box-content\">\n";
                echo "\n";
                echo "<center><b><font color=\"green\">".__("Your order has been successfully modified")."</b></center></font>\n";
                echo "<div class=\"hr\"><hr></div>\n";
                echo "<table align=\"center\">\n";
                echo "</td></tr>\n";
                echo "<tr><td width=\"90\"><b>".__("Order")."</b></td>\n";
                echo "<td><b>".htmlspecialchars_nullable($id)."</b></td></tr>\n";
                echo "<tr><td width=\"90\"><b>".__("Name")."</b></td>\n";
                echo "<td>".htmlspecialchars_nullable($nom).", ".htmlspecialchars_nullable($prenom)."</td></tr>\n";
                if ($mail) {
                    echo "<tr><td width=\"90\"><b>".__("E-Mail")."</b></td>\n";
                    echo "<td>".htmlspecialchars_nullable($mail)."</td></tr>\n";
                }
                if ($service) {
                    echo "<tr><td width=\"90\"><b>".__("Service")."</b></td>\n";
                    echo "<td>".htmlspecialchars_nullable($service)."</td></tr>\n";
                }
                if ($tel) {
                    echo "<tr><td width=\"90\"><b>".__("Tel.")."</b></td>\n";
                    echo "<td>" . htmlspecialchars_nullable($tel) . "</td></tr>\n";
                }
                if ($adresse) {
                    echo "<tr><td width=\"90\"><b>".__("Address")."</b></td>\n";
                    echo "<td>" . htmlspecialchars_nullable($adresse) . " ; " . htmlspecialchars_nullable($postal) . ", " . htmlspecialchars_nullable($localite) ."</td></tr>\n";
                }
                echo "<tr><td width=\"90\"><b>".__("Document")."</b></td>\n";
                echo "<td>".htmlspecialchars_nullable($typedoc)."</td></tr>\n";
                if ($titre) {
                    echo "<tr><td width=\"90\"><b>".__("Title")."</b></td>\n";
                    echo "<td>" . htmlspecialchars_nullable($titre) . "</td></tr>\n";
                }
                if ($auteurs) {
                    echo "<tr><td width=\"90\"><b>".__("Authors")."</b></td>\n";
                    echo "<td>" . htmlspecialchars_nullable($auteurs) . "</td></tr>\n";
                }
                if ($typedoc=='Article')
                    echo "<tr><td width=\"90\"><b>".__("Journal")."</b></td>\n";
                else
                    echo "<tr><td width=\"90\"><b>".__("Book title")."</b></td>\n";
                echo "<td>" . htmlspecialchars_nullable($journal) . "</td>\n";
                echo "</tr><tr>\n";
                if ($annee) {
                    echo "<td width=\"90\"><b>".__("Year")."</b></td>\n";
                    echo "<td>".htmlspecialchars_nullable($annee)."</td></tr>\n";
                }
                if ($vol) {
                    echo "<tr><td  width=\"90\" valign=\"top\"><b>".__("Volume")."</b></td>\n";
                    echo "<td>".htmlspecialchars_nullable($vol)."</td></tr>\n";
                }
                if ($no) {
                    echo "<tr><td  width=\"90\" valign=\"top\"><b>".__("Number")."</b></td>\n";
                    echo "<td>".htmlspecialchars_nullable($no)."</td></tr>\n";
                }
                if ($suppl) {
                    echo "<tr><td  width=\"90\" valign=\"top\"><b>".__("Suppl.")."</b></td>\n";
                    echo "<td>".htmlspecialchars_nullable($suppl)."</td></tr>\n";
                }
                if ($pages) {
                    echo "<tr><td  width=\"90\" valign=\"top\"><b>".__("Pages")."</b></td>\n";
                    echo "<td>".htmlspecialchars_nullable($pages)."</td></tr>\n";
                }
                if ($edition) {
                    echo "<tr><td  width=\"90\" valign=\"top\"><b>".__("Edition")."</b></td>\n";
                    echo "<td>".htmlspecialchars_nullable($edition)."</td></tr>\n";
                }
                if ($isbn) {
                    echo "<tr><td  width=\"90\" valign=\"top\"><b>ISBN</b></td>\n";
                    echo "<td>".htmlspecialchars_nullable($isbn)."</td></tr>\n";
                }
                if ($issn) {
                    echo "<tr><td  width=\"90\" valign=\"top\"><b>ISSN</b></td>\n";
                    echo "<td>".htmlspecialchars_nullable($issn)."</td></tr>\n";
                }
                if ($pmid) {
                    echo "<tr><td  width=\"90\" valign=\"top\"><b>PMID</b></td>\n";
                    echo "<td>".htmlspecialchars_nullable($pmid)."</td></tr>\n";
                }
                if ($doi) {
                    echo "<tr><td  width=\"90\" valign=\"top\"><b>DOI</b></td>\n";
                    echo "<td>".htmlspecialchars_nullable($doi)."</td></tr>\n";
                }
                if ($uid) {
                    echo "<tr><td  width=\"90\" valign=\"top\"><b>UID</b></td>\n";
                    echo "<td>".htmlspecialchars_nullable($uid)."</td></tr>\n";
                }
                if ($remarques) {
                    echo "<tr><td  width=\"90\" valign=\"top\"><b>".__("Professional comment")."</b></td>\n";
                    echo "<td>".nl2br(htmlspecialchars_nullable($remarques))."</td></tr>\n";
                }
                if ($remarquespub) {
                    echo "<tr><td  width=\"90\" valign=\"top\"><b>".__("Public comment")."</b></td>\n";
                    echo "<td>".nl2br(htmlspecialchars_nullable($remarquespub))."</td></tr>\n";
                }
                /*echo "<tr><td>localisation:</td><td>$localisation;</td></tr>\n";*/
                echo "</table>\n";
                echo "<div class=\"hr\"><hr></div>\n";
                echo "<b><center><a href=\"detail.php?table=orders&amp;id=".htmlspecialchars_nullable($id)."\">".__("Return to the order form")."</a></center></b>\n";
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
            $myhtmltitle = format_string(__("%institution_name : confirmation for deletion of the order %order_id"), array('institution_name' => $configname[$lang], 'order_id' => $id));
            require ("headeradmin.php");
            echo "<center><br/><br/><br/><b><font color=\"red\">\n";
            echo format_string(__("Are you sure you want to delete record %order_id ?"), array('order_id' => htmlspecialchars_nullable($id))). "</b></font>\n";
            echo "<form action=\"update.php\" method=\"POST\" enctype=\"x-www-form-encoded\" name=\"fiche\" id=\"fiche\">\n";
            echo "<input name=\"table\" type=\"hidden\" value=\"orders\">\n";
            echo "<input name=\"id\" type=\"hidden\" value=\"".htmlspecialchars_nullable($id)."\">\n";
            echo "<input name=\"action\" type=\"hidden\" value=\"deleteok\">\n";
            echo "<br /><br />\n";
            echo "<input type=\"submit\" value=\"".format_string(__("Confirm the deletion of order %order_id by clicking here"), array('order_id' => htmlspecialchars_nullable($id)))."\">\n";
            echo "</form>\n";
            echo "<br/><br/><br/><a href=\"list.php?table=orders\">".__("Return to the orders list")."</a></center>\n";
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
            $myhtmltitle = format_string(__("%institution_name : deletion of the order %order_id"), array('institution_name' => $configname[$lang], 'order_id' => $id));
            $query = "DELETE FROM orders WHERE orders.illinkid = ?";
            $result = dbquery($query, array($id), 's') or die("Error : ".mysqli_error(dbconnect()));
			update_folders_item_count();
			require ("headeradmin.php");
            echo "<center><br/><b><font color=\"green\">\n";
            echo format_string(__("The order %order_id has been successfully deleted"), array('order_id' => htmlspecialchars_nullable($id)))."</b></font>\n";
            echo "<br/><br/><br/><a href=\"list.php?table=orders\">".__("Return to the orders list")."</a></center>\n";
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
