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
// links table : creation and update of records
// 11.03.2016, MDV Replaced connector to db from mysql_ to mysqli_
// 21.03.2016, MDV Input reading verification
// 01.04.2016, MDV suppressed reference to undefined local file menurech.php; added input verification
// 
require_once ("config.php");
require_once ("authcookie.php");
require_once ("connexion.php");
require_once ("includes/toolkit.php");

if (!empty($_COOKIE[illinkid])){
    $id=addslashes($_POST['id']);
    $ip = $_SERVER['REMOTE_ADDR'];
    
    $validActionSet = array('new', 'update', 'delete', 'deleteok');
    $action = (isset($_GET['action']) && isValidInput($_GET['action'],10,'s',false, $validActionSet)) ? $_GET['action'] : NULL;

    if (!isset($action))
        $action = (isset($_POST['action']) && isValidInput($_POST['action'],10,'s',false, $validActionSet)) ? addslashes($_POST['action']) : '';
    if (($monaut == "admin")||($monaut == "sadmin")){
        $mes="";
        $date=date("Y-m-d H:i:s");
        $linktitle = (isset($_POST['title']) && isValidInput($_POST['title'],50,'s',false))?addslashes(trim($_POST['title'])):'';
        $linkurl = (isset($_POST['url']) && isValidInput($_POST['url'],1000,'s',false))?addslashes(trim($_POST['url'])):'';
        $linksearch_issn = (isset($_POST['search_issn']) && isValidInput($_POST['search_issn'],1,'i',false))?addslashes(trim($_POST['search_issn'])):0;
        $linksearch_isbn = (isset($_POST['search_isbn']) && isValidInput($_POST['search_isbn'],1,'i',false))?addslashes(trim($_POST['search_isbn'])):0;
        $linksearch_ptitle = (isset($_POST['search_ptitle']) && isValidInput($_POST['search_ptitle'],1,'i',false))?addslashes(trim($_POST['search_ptitle'])):0;
        $linksearch_btitle = (isset($_POST['search_btitle']) && isValidInput($_POST['search_btitle'],1,'i',false))?addslashes(trim($_POST['search_btitle'])):0;
        $linksearch_atitle = (isset($_POST['search_atitle']) && isValidInput($_POST['search_atitle'],1,'i',false))?addslashes(trim($_POST['search_atitle'])):0;
        $linkorder_ext = (isset($_POST['order_ext']) && isValidInput($_POST['order_ext'],1,'i',false))?addslashes(trim($_POST['order_ext'])):0;
        $linkorder_form = (isset($_POST['order_form']) && isValidInput($_POST['order_form'],1,'i',false))?addslashes(trim($_POST['order_form'])):0;
        $linkopenurl = (isset($_POST['openurl']) && isValidInput($_POST['openurl'],1,'i',false))?addslashes(trim($_POST['openurl'])):0;
        $linklibrary = (isset($_POST['library']) && isValidInput($_POST['library'],50,'s',false))?addslashes(trim($_POST['library'])):'';
        $linkactive = (isset($_POST['active']) && isValidInput($_POST['active'],1,'i',false))?addslashes(trim($_POST['active'])):0;
        $linkordonnancement = (isset($_POST['active']) && isValidInput($_POST['active'],3,'i',false))?trim($_POST['ordonnancement']):NULL;
        $linkurl_encode = (isset($_POST['url_encoded']) && isValidInput($_POST['url_encoded'],1,'i',false))?addslashes(trim($_POST['url_encoded'])):0;
        $linkskip_words = (isset($_POST['skip_words']) && isValidInput($_POST['skip_words'],1,'i',false))?addslashes(trim($_POST['skip_words'])):0;
        $linkskip_txt_after_mark = (isset($_POST['skip_txt_after_mark']) && isValidInput($_POST['skip_txt_after_mark'],1,'i',false))?addslashes(trim($_POST['skip_txt_after_mark'])):0;
        if ($linksearch_issn != "1")
            $linksearch_issn = 0;
        if ($linksearch_isbn != "1")
            $linksearch_isbn = 0;
        if ($linksearch_ptitle != "1")
            $linksearch_ptitle = 0;
        if ($linksearch_btitle != "1")
            $linksearch_btitle = 0;
        if ($linksearch_atitle != "1")
            $linksearch_atitle = 0;
        if ($linkorder_ext != "1")
            $linkorder_ext = 0;
        if ($linkorder_form != "1")
            $linkorder_form = 0;
        if ($linkopenurl != "1")
            $linkopenurl = 0;
        if ($linkactive != "1")
            $linkactive = 0;
        if ($linkurl_encode != "1")
            $linkurl_encode = 0;
        if ($linkskip_words != "1")
            $linkskip_words = 0;
        if ($linkskip_txt_after_mark != "1")
            $linkskip_txt_after_mark = 0;
        if ($linkordonnancement < 1 || $linkordonnancement > 999)
            $linkordonnancement = 0;
        if (($action == "update")||($action == "new")){
            // Tester les champs obligatoires
            if ($linktitle == "")
                $mes = $mes . "<br/>le Nom du lien est obligatoire";
            if ($linkurl == "")
                $mes = $mes . "<br/>l'URL est obligatoire";
            if ($mes != ""){
                require ("headeradmin.php");
                echo "<center><br/><b><font color=\"red\">\n";
                echo $mes."</b></font>\n";
                echo "<br /><br /><a href=\"javascript:history.back();\"><b>retour au formulaire</a></b></center><br /><br /><br /><br />\n";
                require ("footer.php");
                break;
            }
            else{
                // Début de l'édition
                if ($action == "update"){
                    if ($id != ""){
                        require ("headeradmin.php");
                        $myhtmltitle = $configname[$lang] . " : édition de la fiche du lien " . $id;
                        $reqid = "SELECT * FROM links WHERE id = ?";
                        $resultid = dbquery($reqid, array($id), 'i');
                        $nb = iimysqli_num_rows($resultid);
                        if ($nb == 1){
                            $enregid = iimysqli_result_fetch_array($resultid);
                            $query = 'UPDATE links SET links.title=?, '.
                            'links.url=?, links.search_issn=?, '.
                            'links.search_isbn=?, links.search_ptitle=?, '.
                            'links.search_btitle=?, links.search_atitle=?, '.
                            'links.order_ext=?, links.order_form=?, '.
                            'links.openurl=?, links.library=?, '.
                            'links.active=?, links.ordonnancement=?, '.
                            'links.url_encoded=?, links.skip_words=?, links.skip_txt_after_mark=? '.
                            'WHERE links.id=?';
                            $params = array($linktitle, $linkurl, $linksearch_issn, $linksearch_isbn, $linksearch_ptitle, $linksearch_btitle, $linksearch_atitle, $linkorder_ext, $linkorder_form, $linkopenurl, $linklibrary, $linkactive, $linkordonnancement, $linkurl_encode, $linkskip_words, $linkskip_txt_after_mark, $id);
                            $typeParam = 'ssiiiiiiiisiiiiii';
                            $resultupdate = dbquery($query, $params, $typeParam) or die("Error : ".mysqli_error());
                            echo "<center><br/><b><font color=\"green\">\n";
                            echo "La modification de la fiche $id a été enregistrée avec succès</b></font>\n";
                            echo "<br/><br/><br/><a href=\"list.php?table=links\">Retour à la liste de liens</a></center>\n";
                            require ("footer.php");
                        }
                        else{
                            echo "<center><br/><b><font color=\"red\">\n";
                            echo "La modification n'a pas été enregistrée car l'identifiant de la fiche " . $id . " n'a pas été trouvé dans la base.</b></font>\n";
                            echo "<br /><br /><b>Veuillez relancer de nouveau votre recherche ou contactez l'administrateur de la base : " . $configemail . "</b></center><br /><br /><br /><br />\n";
                            require ("footer.php");
                        }
                    }
                    else{
                        require ("headeradmin.php");
                        //require ("menurech.php");
                        echo "<center><br/><b><font color=\"red\">\n";
                        echo "La modification n'a pas été enregistrée car il manque l'identifiant de la fiche</b></font>\n";
                        echo "<br /><br /><b>Veuillez relancer de nouveau votre recherche</b></center><br /><br /><br /><br />\n";
                        require ("footer.php");
                    }
                }
            }
            // Fin de l'édition
            // Début de la création
            if ($action == "new"){
                require ("headeradmin.php");
                $myhtmltitle = $configname[$lang] . " : nouveau lien ";
                $query = "INSERT INTO `links` (`id`, `title`, `url`, `search_issn`, `search_isbn`, `search_ptitle`, `search_btitle`, `search_atitle`, `order_ext`, `order_form`, `openurl`, `library`, `active`, `ordonnancement`, `url_encoded`, `skip_words`, `skip_txt_after_mark`) ".
                "VALUES ('', ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $params = array($linktitle, $linkurl, $linksearch_issn, $linksearch_isbn, $linksearch_ptitle, $linksearch_btitle, $linksearch_atitle, $linkorder_ext, $linkorder_form, $linkopenurl,$linklibrary,$linkactive, $linkordonnancement, $linkurl_encode, $linkskip_words, $linkskip_txt_after_mark);
                $paramstypes = 'ssiiiiiiiissiiii';
                $id = dbquery($query, $params, $paramstypes) or die("Error : ".mysqli_error());
                echo "<center><br/><b><font color=\"green\">\n";
                echo "La nouvelle fiche $id a été enregistrée avec succès</b></font>\n";
                echo "<br/><br/><br/><a href=\"list.php?table=links\">Retour à la liste de liens</a></center>\n";
                echo "</center>\n";
                echo "\n";
                require ("footer.php");
            }
        }
        // Fin de la création
        // Début de la suppresion
        if ($action == "delete"){
            $id= addslashes((isset($_GET['id']) && isValidInput($_GET['id'],11,'s',false)) ? $_GET['id'] : "");
            $myhtmltitle = $configname[$lang] . " : confirmation pour la suppresion d'une lien ";
            require ("headeradmin.php");
            echo "<center><br/><br/><br/><b><font color=\"red\">\n";
            echo "Voulez-vous vraiement supprimer la fiche " . $id . "?</b></font>\n";
            echo "<form action=\"update.php\" method=\"POST\" enctype=\"x-www-form-encoded\" name=\"fiche\" id=\"fiche\">\n";
            echo "<input name=\"table\" type=\"hidden\" value=\"links\">\n";
            echo "<input name=\"id\" type=\"hidden\" value=\"".$id."\">\n";
            echo "<input name=\"action\" type=\"hidden\" value=\"deleteok\">\n";
            echo "<br /><br />\n";
            echo "<input type=\"submit\" value=\"Confirmer la suppression de la fiche " . $id . " en cliquant ici\">\n";
            echo "</form>\n";
            echo "<br/><br/><br/><a href=\"list.php?table=links\">Retour à la liste des liens</a></center>\n";
            echo "</center>\n";
            echo "\n";
            require ("footer.php");
        }
        if ($action == "deleteok"){
            $myhtmltitle = $configname[$lang] . " : supprimer une lien ";
            require ("headeradmin.php");
            $query = "DELETE FROM links WHERE links.id = ?";
            $result = dbquery($query, array($id), 'i') or die("Error : ".mysqli_error());
            echo "<center><br/><b><font color=\"green\">\n";
            echo "La fiche " . $id . " a été supprimée avec succès</b></font>\n";
            echo "<br/><br/><br/><a href=\"list.php?table=links\">Retour à la liste des liens</a></center>\n";
            echo "</center>\n";
            echo "\n";
            require ("footer.php");
        }
        // Fin de la suppresion
    }
    else{
        require ("header.php");
        echo "<center><br/><b><font color=\"red\">\n";
        echo "Vos droits sont insuffisants pour consulter cette page</b></font></center><br /><br /><br /><br />\n";
        require ("footer.php");
    }
}
else{
    require ("header.php");
    require ("loginfail.php");
    require ("footer.php");
}
?>
