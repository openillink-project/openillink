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
// Units table : creation and update of records
// 

require ("config.php");
require ("authcookie.php");
require_once ("connexion.php");
require_once ("toolkit.php");

$validActionSet = array('new', 'update', 'delete', 'deleteok');
if (!empty($_COOKIE['illinkid'])){
    $id=((!empty($_POST['id'])) && isValidInput($_POST['id'],11,'i',false))? $_POST['id']:NULL;
    $ip = $_SERVER['REMOTE_ADDR'];
    $action = (isset($_GET['action']) && isValidInput($_GET['action'],10,'s',false,$validActionSet))? $_GET['action']:NULL;
    if (empty($action))
        $action = ((!empty($_POST['action'])) && isValidInput($_POST['action'],10,'s',false,$validActionSet))? $_POST['action']:NULL;
    if (($monaut == "admin")||($monaut == "sadmin")){
        $mes="";
        $date=date("Y-m-d H:i:s");
        $code = ((!empty($_POST['code'])) && isValidInput($_POST['code'],20,'s',false))? trim($_POST['code']):NULL;
        $name1 = ((!empty($_POST['name1'])) && isValidInput($_POST['name1'],100,'s',false))? trim($_POST['name1']):NULL;
        $name2 = ((!empty($_POST['name2'])) && isValidInput($_POST['name2'],100,'s',false))? trim($_POST['name2']):NULL;
        $name3 = ((!empty($_POST['name3'])) && isValidInput($_POST['name3'],100,'s',false))? trim($_POST['name3']):NULL;
        $name4 = ((!empty($_POST['name4'])) && isValidInput($_POST['name4'],100,'s',false))? trim($_POST['name4']):NULL;
        $name5 = ((!empty($_POST['name5'])) && isValidInput($_POST['name5'],100,'s',false))? trim($_POST['name5']):NULL;
        $library = ((!empty($_POST['library'])) && isValidInput($_POST['library'],50,'s',false))? trim($_POST['library']):NULL;
        $unitdepartment = ((!empty($_POST['department'])) && isValidInput($_POST['department'],100,'s',false))? trim($_POST['department']):NULL;
        $unitdepartmentnew = ((!empty($_POST['departmentnew'])) && isValidInput($_POST['departmentnew'],100,'s',false))? trim($_POST['departmentnew']):NULL;
        if ($unitdepartment == "new")
            $unitdepartment = $unitdepartmentnew;
        $unitfaculty = ((!empty($_POST['faculty'])) && isValidInput($_POST['faculty'],100,'s',false))? trim($_POST['faculty']):NULL;
        $unitfacultynew = ((!empty($_POST['facultynew'])) && isValidInput($_POST['facultynew'],100,'s',false))? trim($_POST['facultynew']):NULL;
        if ($unitfaculty == "new")
            $unitfaculty = $unitfacultynew;
        $unitip1 = ((!empty($_POST['ip1'])) && isValidInput($_POST['ip1'],1,'i',false))? trim($_POST['ip1']):0;
        if ($unitip1 != 1)
            $unitip1 = 0;
        $unitip2 = ((!empty($_POST['ip2'])) && isValidInput($_POST['ip2'],1,'i',false))? trim($_POST['ip2']):0;
        if ($unitip2 != 1)
            $unitip2 = 0;
        $unitipext = ((!empty($_POST['ipext'])) && isValidInput($_POST['ipext'],1,'i',false))? trim($_POST['ipext']):0;
        if ($unitipext != 1)
            $unitipext = 0;
        $validation = ((!empty($_POST['validation'])) && isValidInput($_POST['validation'],1,'i',false))? trim($_POST['validation']):0;
        if ($validation != 1)
            $validation = 0;
        if (($action == "update")||($action == "new")){
            // Tester si le code est unique
            $reqcode = "SELECT * FROM units WHERE units.code = ?";
			$resultcode = dbquery($reqcode,array($code), 's');
            $nbcode = iimysqli_num_rows($resultcode);
            $enregcode = iimysqli_result_fetch_array($resultcode);
            $idcode = $enregcode['id'];
            if (($nbcode == 1)&&($action == "new"))
                $mes = $mes . "<br/>le code '" . htmlspecialchars($code) . "' existe déjà dans la base, veuillez choisir un autre";
            if (($nbcode == 1)&&($action != "new")&&($idcode != $id))
                $mes = $mes . "<br/>le code '" . htmlspecialchars($code) . "' est déjà attribué à une autre unité , veuillez choisir un autre";
            if ($name1 == "")
                $mes = $mes . "<br/>le nom1 est obligatoire";
            if ($code == "")
                $mes = $mes . "<br/>le code est obligatoire";

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
                        $reqid = "SELECT * FROM units WHERE id = ?";
                        $myhtmltitle = $configname[$lang] . " : édition de la fiche unité " . htmlspecialchars($id);
						$resultid = dbquery($reqid, array($id), 'i');
                        $nb = iimysqli_num_rows($resultid);
                        if ($nb == 1){
                            $enregid = iimysqli_result_fetch_array($resultid);
							$query = "UPDATE units SET units.name1=?, units.name2=?, units.name3=?, units.name4=?, units.name5=?, units.library=?, units.code=?, units.department=?, units.faculty=?, units.internalip1display=?, units.internalip2display=?, units.externalipdisplay=?, units.validation=? WHERE units.id=?";
                            $params = array($name1, $name2, $name3, $name4, $name5, $library, $code, $unitdepartment, $unitfaculty, $unitip1, $unitip2, $unitipext, $validation, $id);
                            $resultupdate = dbquery($query, $params,'sssssssssiiiii') or die("Error : ".mysqli_error());
                            echo "<center><br/><b><font color=\"green\">\n";
                            echo "La modification de la fiche " . htmlspecialchars($id) . " a été enregistrée avec succès</b></font>\n";
                            echo "<br/><br/><br/><a href=\"list.php?table=units\">Retour à la liste de unités</a></center>\n";
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
					$myhtmltitle = $configname[$lang] . " : nouvelle unité ";
					$query = "INSERT INTO `units` (`id`, `name1`, `name2`, `name3`, `name4`, `name5`, `code`, `library`, `department`, `faculty`, `internalip1display`, `internalip2display`, `externalipdisplay`, `validation`) ";
					$query .= "VALUES ('', ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
					$params = array($name1, $name2, $name3, $name4, $name5, $code, $library, $unitdepartment, $unitfaculty, $unitip1, $unitip2, $unitipext, $validation);
					$id = dbquery($query, $params,'sssssssssiiii') or die("Error : ".mysqli_error());
					echo "<center><br/><b><font color=\"green\">\n";
					echo "La nouvelle fiche " . htmlspecialchars($id) . " a été enregistrée avec succès</b></font>\n";
					echo "<br/><br/><br/><a href=\"list.php?table=units\">Retour à la liste de unités</a></center>\n";
					echo "</center>\n";
					echo "\n";
					require ("footer.php");
				}
			}
        }
        // Fin de la création
        // Début de la suppresion
        if ($action == "delete"){
            $id=isValidInput($_GET['id'],11,'i',false)?$_GET['id']:'';
            $myhtmltitle = $configname[$lang] . " : confirmation pour la suppresion d'une unité ";
            require ("headeradmin.php");
            echo "<center><br/><br/><br/><b><font color=\"red\">\n";
            echo "Voulez-vous vraiement supprimer la fiche " . htmlspecialchars($id) . "?</b></font>\n";
            echo "<form action=\"update.php\" method=\"POST\" enctype=\"x-www-form-encoded\" name=\"fiche\" id=\"fiche\">\n";
            echo "<input name=\"table\" type=\"hidden\" value=\"units\">\n";
            echo "<input name=\"id\" type=\"hidden\" value=\"".htmlspecialchars($id)."\">\n";
            echo "<input name=\"action\" type=\"hidden\" value=\"deleteok\">\n";
            echo "<br /><br />\n";
            echo "<input type=\"submit\" value=\"Confirmer la suppression de la fiche " . htmlspecialchars($id) . " en cliquant ici\">\n";
            echo "</form>\n";
            echo "<br/><br/><br/><a href=\"list.php?table=units\">Retour à la liste des unités</a></center>\n";
            echo "</center>\n";
            echo "\n";
            require ("footer.php");
        }
        if ($action == "deleteok"){
            $myhtmltitle = $configname[$lang] . " : supprimer une unité ";
            require ("headeradmin.php");
            $query = "DELETE FROM units WHERE units.id = ?";
			$result = dbquery($query, array($id), 'i') or die("Error : ".mysqli_error());
            echo "<center><br/><b><font color=\"green\">\n";
            echo "La fiche " . htmlspecialchars($id) . " a été supprimée avec succès</b></font>\n";
            echo "<br/><br/><br/><a href=\"list.php?table=units\">Retour à la liste des unités</a></center>\n";
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
