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
// links table : creation and update of records
// 
require_once ("config.php");
require_once ("authcookie.php");
require_once ("connexion.php");
require_once ("includes/toolkit.php");

if (!empty($_COOKIE['illinkid'])){
    $id=$_POST['id'];
    $ip = $_SERVER['REMOTE_ADDR'];
    
    $validActionSet = array('new', 'update', 'delete', 'deleteok');
    $action = ((!empty($_GET['action'])) && isValidInput($_GET['action'],10,'s',false, $validActionSet)) ? $_GET['action'] : NULL;

    if (empty($action))
        $action = ((!empty($_POST['action'])) && isValidInput($_POST['action'],10,'s',false, $validActionSet)) ? $_POST['action'] : '';
    if (($monaut == "admin")||($monaut == "sadmin")){
        $mes="";
        $date=date("Y-m-d H:i:s");
        $linktitle = ((!empty($_POST['title'])) && isValidInput($_POST['title'],50,'s',false))?trim($_POST['title']):'';
        $linkurl = ((!empty($_POST['url'])) && isValidInput($_POST['url'],1000,'s',false))?trim($_POST['url']):'';
        $linksearch_issn = ((!empty($_POST['search_issn'])) && isValidInput($_POST['search_issn'],1,'i',false))?trim($_POST['search_issn']):0;
        $linksearch_isbn = ((!empty($_POST['search_isbn'])) && isValidInput($_POST['search_isbn'],1,'i',false))?trim($_POST['search_isbn']):0;
        $linksearch_ptitle = ((!empty($_POST['search_ptitle'])) && isValidInput($_POST['search_ptitle'],1,'i',false))?trim($_POST['search_ptitle']):0;
        $linksearch_btitle = ((!empty($_POST['search_btitle'])) && isValidInput($_POST['search_btitle'],1,'i',false))?trim($_POST['search_btitle']):0;
        $linksearch_atitle = ((!empty($_POST['search_atitle'])) && isValidInput($_POST['search_atitle'],1,'i',false))?trim($_POST['search_atitle']):0;
        $linkorder_ext = ((!empty($_POST['order_ext'])) && isValidInput($_POST['order_ext'],1,'i',false))?trim($_POST['order_ext']):0;
        $linkorder_form = ((!empty($_POST['order_form'])) && isValidInput($_POST['order_form'],1,'i',false))?trim($_POST['order_form']):0;
        $linkopenurl = ((!empty($_POST['openurl'])) && isValidInput($_POST['openurl'],1,'i',false))?trim($_POST['openurl']):0;
        $linklibrary = ((!empty($_POST['library'])) && isValidInput($_POST['library'],50,'s',false))?trim($_POST['library']):'';
        $linkactive = ((!empty($_POST['active'])) && isValidInput($_POST['active'],1,'i',false))?trim($_POST['active']):0;
        $linkordonnancement = ((!empty($_POST['active'])) && isValidInput($_POST['active'],3,'i',false))?$_POST['ordonnancement']:NULL;
        $linkurl_encode = ((!empty($_POST['url_encoded'])) && isValidInput($_POST['url_encoded'],1,'i',false))?trim($_POST['url_encoded']):0;
        $linkskip_words = ((!empty($_POST['skip_words'])) && isValidInput($_POST['skip_words'],1,'i',false))?trim($_POST['skip_words']):0;
        $linkskip_txt_after_mark = ((!empty($_POST['skip_txt_after_mark'])) && isValidInput($_POST['skip_txt_after_mark'],1,'i',false))?trim($_POST['skip_txt_after_mark']):0;
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
            }
            else{
                // Début de l'édition
                if ($action == "update"){
                    if ($id != ""){
                        require ("headeradmin.php");
                        $myhtmltitle = $configname[$lang] . " : édition de la fiche du lien " . htmlspecialchars($id);
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
                            echo "La modification de la fiche ".htmlspecialchars($id)." a été enregistrée avec succès</b></font>\n";
                            echo "<br/><br/><br/><a href=\"list.php?table=links\">Retour à la liste de liens</a></center>\n";
                            require ("footer.php");
                        }
                        else{
                            echo "<center><br/><b><font color=\"red\">\n";
                            echo "La modification n'a pas été enregistrée car l'identifiant de la fiche " . htmlspecialchars($id) . " n'a pas été trouvé dans la base.</b></font>\n";
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
				// Fin de l'édition
				// Début de la création
				if ($action == "new"){
					require ("headeradmin.php");
					$myhtmltitle = $configname[$lang] . " : nouveau lien ";
					$query = "INSERT INTO `links` (`id`, `title`, `url`, `search_issn`, `search_isbn`, `search_ptitle`, `search_btitle`, `search_atitle`, `order_ext`, `order_form`, `openurl`, `library`, `active`, `ordonnancement`, `url_encoded`, `skip_words`, `skip_txt_after_mark`) ".
					"VALUES ('', ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
					$params = array($linktitle, $linkurl, $linksearch_issn, $linksearch_isbn, $linksearch_ptitle, $linksearch_btitle, $linksearch_atitle, $linkorder_ext, $linkorder_form, $linkopenurl,$linklibrary,$linkactive, $linkordonnancement, $linkurl_encode, $linkskip_words, $linkskip_txt_after_mark);
					$paramstypes = 'ssiiiiiiiissiiii';
					$id = dbquery($query, $params, $paramstypes) or die("Error : ".mysqli_error());
					echo "<center><br/><b><font color=\"green\">\n";
					echo "La nouvelle fiche ".htmlspecialchars($id)." a été enregistrée avec succès</b></font>\n";
					echo "<br/><br/><br/><a href=\"list.php?table=links\">Retour à la liste de liens</a></center>\n";
					echo "</center>\n";
					echo "\n";
					require ("footer.php");
				}
			}
        }
        // Fin de la création
        // Début de la suppresion
        if ($action == "delete"){
            $id= ((!empty($_GET['id'])) && isValidInput($_GET['id'],11,'s',false)) ? $_GET['id'] : "";
            $myhtmltitle = $configname[$lang] . " : confirmation pour la suppresion d'une lien ";
            require ("headeradmin.php");
            echo "<center><br/><br/><br/><b><font color=\"red\">\n";
            echo "Voulez-vous vraiement supprimer la fiche " . htmlspecialchars($id) . "?</b></font>\n";
            echo "<form action=\"update.php\" method=\"POST\" enctype=\"x-www-form-encoded\" name=\"fiche\" id=\"fiche\">\n";
            echo "<input name=\"table\" type=\"hidden\" value=\"links\">\n";
            echo "<input name=\"id\" type=\"hidden\" value=\"".htmlspecialchars($id)."\">\n";
            echo "<input name=\"action\" type=\"hidden\" value=\"deleteok\">\n";
            echo "<br /><br />\n";
            echo "<input type=\"submit\" value=\"Confirmer la suppression de la fiche " . htmlspecialchars($id) . " en cliquant ici\">\n";
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
            echo "La fiche " . htmlspecialchars($id) . " a été supprimée avec succès</b></font>\n";
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
