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
// Status table : creation and update of records
// 
require ("config.php");
require ("authcookie.php");
require_once ("connexion.php");
require_once ("toolkit.php");

$validActionSet = array('new', 'update', 'delete', 'deleteok');
if (!empty($_COOKIE['illinkid'])){
    $action2="";
    $action="";
    $id=((!empty($_POST['id'])) && isValidInput($_POST['id'],11,'i',false))? $_POST['id']:NULL;
    $ip = $_SERVER['REMOTE_ADDR'];
    $action=((!empty($_GET['action'])) && isValidInput($_GET['action'],10,'s',false,$validActionSet))? $_GET['action']:NULL;
    if (empty($action))
        $action = $action=((!empty($_POST['action'])) && isValidInput($_POST['action'],10,'s',false,$validActionSet))? $_POST['action']:'';
    if (($monaut == "admin")||($monaut == "sadmin")){
        $mes="";
        $date=date("Y-m-d H:i:s");
        $code = ((!empty($_POST['code'])) && isValidInput($_POST['code'],6,'i',false))? trim($_POST['code']):NULL;
        $title1 = ((!empty($_POST['title1'])) && isValidInput($_POST['title1'],50,'s',false))? trim($_POST['title1']):NULL;
        $title2 = ((!empty($_POST['title2'])) && isValidInput($_POST['title2'],50,'s',false))? trim($_POST['title2']):NULL;
        $title3 = ((!empty($_POST['title3'])) && isValidInput($_POST['title3'],50,'s',false))? trim($_POST['title3']):NULL;
        $title4 = ((!empty($_POST['title4'])) && isValidInput($_POST['title4'],50,'s',false))? trim($_POST['title4']):NULL;
        $title5 = ((!empty($_POST['title5'])) && isValidInput($_POST['title5'],50,'s',false))? trim($_POST['title5']):NULL;
        $help1 = ((!empty($_POST['help1'])) && isValidInput($_POST['help1'],255,'s',false))? trim($_POST['help1']):NULL;
        $help2 = ((!empty($_POST['help2'])) && isValidInput($_POST['help2'],255,'s',false))? trim($_POST['help2']):NULL;
        $help3 = ((!empty($_POST['help3'])) && isValidInput($_POST['help3'],255,'s',false))? trim($_POST['help3']):NULL;
        $help4 = ((!empty($_POST['help4'])) && isValidInput($_POST['help4'],255,'s',false))? trim($_POST['help4']):NULL;
        $help5 = ((!empty($_POST['help5'])) && isValidInput($_POST['help5'],255,'s',false))? trim($_POST['help5']):NULL;
        $in = ((!empty($_POST['in'])) && isValidInput($_POST['in'],1,'i',false))? trim($_POST['in']):0;
        if ($in != 1)
            $in = 0;
        $out = ((!empty($_POST['out'])) && isValidInput($_POST['out'],1,'i',false))? trim($_POST['out']):0;
        if ($out != 1)
            $out = 0;
        $trash = ((!empty($_POST['trash'])) && isValidInput($_POST['trash'],1,'i',false))? trim($_POST['trash']):0;
        if ($trash != 1)
            $trash = 0;
        $special = ((!empty($_POST['special'])) && isValidInput($_POST['special'],20,'s',false))? trim($_POST['special']):NULL;
        $color = ((!empty($_POST['color'])) && isValidInput($_POST['color'],50,'s',false))? trim($_POST['color']):NULL;
        if (($action == "update")||($action == "new")) {
            // Tester si le code est unique
            $reqcode = "SELECT * FROM status WHERE code = ?";
            $resultcode = dbquery($reqcode,array($code), 'i');
            $nbcode = iimysqli_num_rows($resultcode);
            $enregcode = iimysqli_result_fetch_array($resultcode);
            $idcode = $enregcode['id'];
            if (($nbcode == 1)&&($action == "new"))
                $mes = $mes . "<br/>le code '" . htmlspecialchars($code) . "' existe déjà dans la base, veuillez choisir un autre";
            if (($nbcode == 1)&&($action != "new")&&($idcode != $id))
                $mes = $mes . "<br/>le code '" . htmlspecialchars($code) . "' est déjà attribué à une autre étape, veuillez choisir un autre";
            if ($title1 == "")
                $mes = $mes . "<br/>le nom1 est obligatoire";
            if ($code == "")
                $mes = $mes . "<br/>le code est obligatoire (et doit être un nombre)";
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
                        $reqid = "SELECT * FROM status WHERE id = ?";
                        $myhtmltitle = $configname[$lang] . " : édition de l'étape " . htmlspecialchars($id);
                        $resultid = dbquery($reqid, array($id), 'i');
                        $nb = iimysqli_num_rows($resultid);
                        if ($nb == 1){
                            $enregid = iimysqli_result_fetch_array($resultid);
                            $query = "UPDATE status SET status.title1=?, status.title2=?, status.title3=?, status.title4=?, status.title5=?, status.help1=?, status.help2=?, status.help3=?, status.help4=?, status.help5=?, status.in=?, status.out=?, status.trash=?, status.special=?, status.color=?, status.code=? WHERE status.id=?";
                            $params = array($title1, $title2, $title3, $title4, $title5, $help1, $help2, $help3, $help4, $help5, $in, $out, $trash, $special, $color, $code, $id);
                            $resultupdate = dbquery($query, $params,'ssssssssssiiissii') or die("Error : ".mysqli_error());
                            echo "<center><br/><b><font color=\"green\">\n";
                            echo "La modification de la fiche " . htmlspecialchars($id) . " a été enregistrée avec succès</b></font>\n";
                            echo "<br/><br/><br/><a href=\"list.php?table=status\">Retour à la liste des étapes</a></center>\n";
                            require ("footer.php");
                        }
                        else{
                            echo "<center><br/><b><font color=\"red\">\n";
                            echo "La modification n'a pas été enregistrée car l'identifiant de la fiche " . htmlspecialchars($id) . " n'a pas été trouvée dans la base.</b></font>\n";
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
					$myhtmltitle = $configname[$lang] . " : nouvelle status";
					$query ="INSERT INTO `status` (`id`, `title1`, `title2`, `title3`, `title4`, `title5`, `help1`, `help2`, `help3`, `help4`, `help5`, `code`, `in`, `out`, `trash`, `special`, `color`) VALUES ('', ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
					$params = array($title1, $title2, $title3, $title4, $title5, $help1, $help2, $help3, $help4, $help5, $code, $in, $out, $trash, $special, $color);
					$id = dbquery($query, $params,'ssssssssssiiiiss') or die("Error : ".mysqli_error());
					echo "<center><br/><b><font color=\"green\">\n";
					echo "La nouvelle fiche " . htmlspecialchars($id) . " a été enregistrée avec succès</b></font>\n";
					echo "<br/><br/><br/><a href=\"list.php?table=status\">Retour à la liste des étapes</a></center>\n";
					echo "</center>\n";
					echo "\n";
					require ("footer.php");
				}
			}
        }
        // Fin de la création
        // Début de la suppresion
        if ($action == "delete"){
            $id=((isset($_GET['id'])) && isValidInput($_GET['id'],11,'i',false))? $_GET['id']:NULL;
            $myhtmltitle = $configname[$lang] . " : confirmation pour la suppresion d'une étape de la commande";
            require ("headeradmin.php");
            echo "<center><br/><br/><br/><b><font color=\"red\">\n";
            echo "Voulez-vous vraiement supprimer la fiche " . htmlspecialchars($id) . "?</b></font>\n";
            echo "<form action=\"update.php\" method=\"POST\" enctype=\"x-www-form-encoded\" name=\"fiche\" id=\"fiche\">\n";
            echo "<input name=\"table\" type=\"hidden\" value=\"status\">\n";
            echo "<input name=\"id\" type=\"hidden\" value=\"".htmlspecialchars($id)."\">\n";
            echo "<input name=\"action\" type=\"hidden\" value=\"deleteok\">\n";
            echo "<br /><br />\n";
            echo "<input type=\"submit\" value=\"Confirmer la suppression de la fiche " . htmlspecialchars($id) . " en cliquant ici\">\n";
            echo "</form>\n";
            echo "<br/><br/><br/><a href=\"list.php?table=status\">Retour à la liste des étapes</a></center>\n";
            echo "</center>\n";
            echo "\n";
            require ("footer.php");
        }
        if ($action == "deleteok"){
            $myhtmltitle = $configname[$lang] . " : supprimer une étape de la commande";
            require ("headeradmin.php");
            $query = "DELETE FROM status WHERE status.id = ?";
            $result = dbquery($query, array($id), 'i') or die("Error : ".mysqli_error());
            echo "<center><br/><b><font color=\"green\">\n";
            echo "La fiche " . htmlspecialchars($id) . " a été supprimée avec succès</b></font>\n";
            echo "<br/><br/><br/><a href=\"list.php?table=status\">Retour à la liste des étapes</a></center>\n";
            echo "</center>\n";
            echo "\n";
            require ("footer.php");
        }
        // Fin de la saisie
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
    require ("codefail.php");
    require ("footer.php");
}
?>
