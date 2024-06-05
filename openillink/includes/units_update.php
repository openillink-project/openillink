<?php
// ***************************************************************************
// ***************************************************************************
// ***************************************************************************
// This file is part of OpenILLink software.
// Copyright (C) 2005, 2006, 2007, 2008, 2009, 2010, 2011, 2012, 2013, 2015, 2016, 2017, 2018, 2019, 2020, 2024 CHUV.
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

require_once ("config.php");
require_once ("authcookie.php");
require_once ("connexion.php");
require_once ("toolkit.php");

$validActionSet = array('new', 'update', 'delete', 'deleteok');
if (!empty($_COOKIE['illinkid'])){
    $id=((!empty($_POST['id'])) && isValidInput($_POST['id'],11,'i',false))? $_POST['id']:NULL;
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
            if ($nbcode > 0) {
                $idcode = $enregcode['id'];
                if (($nbcode == 1)&&($action == "new"))
                    $mes = $mes . "<br/>".format_string(__("The code %code already exists in database. Please choose another one."), array('code' => htmlspecialchars($code)));
                if (($nbcode == 1)&&($action != "new")&&($idcode != $id))
                    $mes = $mes . "<br/>". format_string(__("The code %code is already attributed to a localization. Please choose another one."), array('code' => htmlspecialchars($code)));
                }
            if ($name1 == "")
                $mes = $mes . "<br/>".__("name1 is required");
            if ($code == "")
                $mes = $mes . "<br/>".__("Code is required");

            if ($mes != ""){
                require ("headeradmin.php");
                echo "<center><br/><b><font color=\"red\">\n";
                echo $mes."</b></font>\n";
                echo "<br /><br /><a href=\"javascript:history.back();\"><b>".__("Back to the form")."</a></b></center><br /><br /><br /><br />\n";
                require ("footer.php");
            }
            else{
                // Début de l'édition
                if ($action == "update"){
                    if ($id != ""){
                        require ("headeradmin.php");
                        $reqid = "SELECT * FROM units WHERE id = ?";
                        $myhtmltitle = format_string(__("%institution_name : edition of the unit %id"), array('institution_name' => $configinstitution[$lang], 'id' => htmlspecialchars($id)));
						$resultid = dbquery($reqid, array($id), 'i');
                        $nb = iimysqli_num_rows($resultid);
                        if ($nb == 1){
                            $enregid = iimysqli_result_fetch_array($resultid);
							$query = "UPDATE units SET units.name1=?, units.name2=?, units.name3=?, units.name4=?, units.name5=?, units.library=?, units.code=?, units.department=?, units.faculty=?, units.internalip1display=?, units.internalip2display=?, units.externalipdisplay=?, units.validation=? WHERE units.id=?";
                            $params = array($name1, $name2, $name3, $name4, $name5, $library, $code, $unitdepartment, $unitfaculty, $unitip1, $unitip2, $unitipext, $validation, $id);
                            $resultupdate = dbquery($query, $params,'sssssssssiiiii') or die("Error : ".mysqli_error(dbconnect()));
                            echo "<center><br/><b><font color=\"green\">\n";
                            echo __("Your order has been successfully modified")."</b></font>\n";
                            echo "<br/><br/><br/><a href=\"list.php?table=units\">".__("Back to the units")."</a></center>\n";
                            require ("footer.php");
                        }
                        else{
                            echo "<center><br/><b><font color=\"red\">\n";
                            echo format_string(__("The change was not saved because the identifier of record  %id was not found in the database."), array('id' => htmlspecialchars($id))). "</b></font>\n";
                            echo "<br /><br /><b>".__("Please retry your search")."</b></center><br /><br /><br /><br />\n";
                            require ("footer.php");
                        }
                    }
                    else{
                        require ("headeradmin.php");
                        //require ("menurech.php");
                        echo "<center><br/><b><font color=\"red\">\n";
                        echo __("The modification was not saved because it lacks the identifier of the form")."</b></font>\n";
                        echo "<br /><br /><b>".__("Please retry your search")."</b></center><br /><br /><br /><br />\n";
                        require ("footer.php");
                    }
                }
				// Fin de l'édition
				// Début de la création
				if ($action == "new"){
					require ("headeradmin.php");
					$myhtmltitle = $configname[$lang] . " ".__("new unit");
					$query = "INSERT INTO `units` (`name1`, `name2`, `name3`, `name4`, `name5`, `code`, `library`, `department`, `faculty`, `internalip1display`, `internalip2display`, `externalipdisplay`, `validation`) ";
					$query .= "VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
					$params = array($name1, $name2, $name3, $name4, $name5, $code, $library, $unitdepartment, $unitfaculty, $unitip1, $unitip2, $unitipext, $validation);
					$id = dbquery($query, $params,'sssssssssiiii') or die("Error : ".mysqli_error(dbconnect()));
					echo "<center><br/><b><font color=\"green\">\n";
					echo format_string(__("The new record %id_record has been successfully registered"),array('id_record' => htmlspecialchars($id)))."</b></font>\n";
					echo "<br/><br/><br/><a href=\"list.php?table=units\">".__("Back to the order steps list")."</a></center>\n";
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
            $myhtmltitle = $configname[$lang] . " : ".__("Confirmation for deleting an unit");
            require ("headeradmin.php");
            echo "<center><br/><br/><br/><b><font color=\"red\">\n";
            echo format_string(__("Do you really want to delete the record %id_record ?"),array('id_record' => htmlspecialchars($id)))."</b></font>\n";
            echo "<form action=\"update.php\" method=\"POST\" enctype=\"x-www-form-encoded\" name=\"fiche\" id=\"fiche\">\n";
            echo "<input name=\"table\" type=\"hidden\" value=\"units\">\n";
            echo "<input name=\"id\" type=\"hidden\" value=\"".htmlspecialchars($id)."\">\n";
            echo "<input name=\"action\" type=\"hidden\" value=\"deleteok\">\n";
            echo "<br /><br />\n";
            echo "<input type=\"submit\" value=\"".format_string(__("Confirm the deletion of the record %id_record by clicking here"),array('id_record' => htmlspecialchars($id)))."\">\n";
            echo "</form>\n";
            echo "<br/><br/><br/><a href=\"list.php?table=units\">".__("Back to the units list")."</a></center>\n";
            echo "</center>\n";
            echo "\n";
            require ("footer.php");
        }
        if ($action == "deleteok"){
            $myhtmltitle = $configname[$lang] . " : ".__("Delete an unit");
            require ("headeradmin.php");
            $query = "DELETE FROM units WHERE units.id = ?";
			$result = dbquery($query, array($id), 'i') or die("Error : ".mysqli_error(dbconnect()));
            echo "<center><br/><b><font color=\"green\">\n";
            echo format_string(__("The record %id has been successfully deleted"),array('id' => htmlspecialchars($id)))."</b></font>\n";
            echo "<br/><br/><br/><a href=\"list.php?table=units\">".__("Back to the units list")."</a></center>\n";
            echo "</center>\n";
            echo "\n";
            require ("footer.php");
        }
        // Fin de la saisie
    }
    else{
        require ("header.php");
        echo "<center><br/><b><font color=\"red\">\n";
        echo __("Your rights are insufficient to edit this page")."</b></font></center><br /><br /><br /><br />\n";
        require ("footer.php");
    }
}
else{
    require ("header.php");
    require ("codefail.php");
    require ("footer.php");
}
?>
