<?php
// ***************************************************************************
// ***************************************************************************
// ***************************************************************************
// This file is part of OpenILLink software.
// Copyright (C) 2005, 2006, 2007, 2008, 2009, 2010, 2011, 2012, 2013, 2015, 2016, 2017, 2018 CHUV.
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
// Table users : modification / création d'un enregistrement
//

require_once ("config.php");
require ("authcookie.php");
require_once ("connexion.php");
require_once ("toolkit.php");

$validActionSet = array('new', 'update', 'delete', 'deleteok', 'updateprofile');
if (!empty($_COOKIE['illinkid'])){
    $id=$_POST['id'];

	// Fetch current user id to compare with input if needed
	$reqlogin = "SELECT user_id FROM users WHERE users.login = ?";
	$resultlogin = dbquery($reqlogin, array($monlog), 's');
    $enreglogin = iimysqli_result_fetch_array($resultlogin);
	$myId = $enreglogin['user_id'];

    $ip = $_SERVER['REMOTE_ADDR'];
    $action = ((!empty($_GET['action'])) && isValidInput($_GET['action'],15,'s',false,$validActionSet))? $_GET['action']:NULL;
    if (empty($action)){
        $action = ((!empty($_POST['action'])) && isValidInput($_POST['action'],15,'s',false,$validActionSet))? $_POST['action']:NULL;
    }
    if (($monaut == "admin")||($monaut == "sadmin")||(($monaut == "user" && $id == $myId)&&($action == "updateprofile"))){
/*
        if (($monaut == "user")&&($action == "updateprofile"))
            $action == "update";
*/
        if (($action == "update")||($action == "new") || ($action == "updateprofile")){
            $mes="";
            $date=date("Y-m-d H:i:s");
            $name = ((!empty($_POST['name'])) && isValidInput($_POST['name'],255,'s',false))? trim($_POST['name']):NULL;
            $email = ((!empty($_POST['email'])) && isValidInput($_POST['email'],255,'s',false))? trim($_POST['email']):NULL;
            $login = ((!empty($_POST['login'])) && isValidInput($_POST['login'],255,'s',false))? trim($_POST['login']):NULL;
            $status = ((!empty($_POST['status'])) && isValidInput($_POST['status'],1,'i',false))? trim($_POST['status']):0;
            $admin = ((!empty($_POST['admin'])) && isValidInput($_POST['admin'],1,'i',false))? trim($_POST['admin']):NULL;
            $library = ((!empty($_POST['library'])) && isValidInput($_POST['library'],50,'s',false))? trim($_POST['library']):NULL;
            $newpassword1 = ((!empty($_POST['newpassword1'])) && isValidInput($_POST['newpassword1'],255,'s',false))? trim($_POST['newpassword1']):NULL;
            $newpassword2 = ((!empty($_POST['newpassword2'])) && isValidInput($_POST['newpassword2'],255,'s',false))? trim($_POST['newpassword2']):NULL;
            if (!empty($newpassword1)) {
                $password = md5($newpassword1);
			}
            // Tester si le login est unique
            $reqlogin = "SELECT * FROM users WHERE users.login = ?";
			$resultlogin = dbquery($reqlogin,array($login), 's');
            $nblogin = iimysqli_num_rows($resultlogin);
            $enreglogin = iimysqli_result_fetch_array($resultlogin);
            $idlogin = $enreglogin['user_id'];
            if (($nblogin == 1)&&($action == "new"))
                $mes = $mes . "<br/>".format_string(__("The login %login already exists in database. Please choose another one."), array('login' => htmlspecialchars($login)));
            if (($nblogin == 1)&&($action != "new")&&($idlogin != $id))
                $mes = $mes . "<br/>".format_string(__("The login %login is already attributed to another user. Please choose another one."), array('login' => htmlspecialchars($login)));
            if (empty($name) && ($action != "updateprofile"))
                $mes = $mes . "<br/>le nom est obligatoire";
            if (empty($login) && ($action != "updateprofile"))
                $mes = $mes . "<br/>le login est obligatoire";
            if ((!isset($status)) && ($action != "updateprofile"))
                $mes = $mes . "<br/>la status est obligatoire";
            if ((empty($admin)) &&($action != "updateprofile"))
                $mes = $mes . "<br/>le type d'utilisateur est obligatoire";
			if (($monaut != "sadmin") && $admin == 1) {
				// Not allowed to change to superadmin. Fallback to lower role
				$admin = 2;
			}
			if ((($monaut != "admin") && ($monaut != "sadmin")) && $admin == 2) {
				// Not allowed to change to admin. Fallback to lower role
				$admin = 3;
			}
			if ((($monaut != "admin") && ($monaut != "sadmin") && ($monaut != "user")) && $admin == 3) {
				// Not allowed to change to collaborator. Fallback to lower role
				$admin = 9;
			}
            if ((empty($newpassword1)) && ($action == "new"))
                $mes = $mes . "<br/>".__("The Password is required");
            if (($newpassword2 !== $newpassword1)||(($newpassword2 == "")&&($action == "new")))
                $mes = $mes . "<br/>".__("The password has not been confirmed correctly");
            if (!empty($mes)){
                require ("headeradmin.php");
                echo "<center><br/><b><font color=\"red\">\n";
                echo $mes."</b></font>\n";
                echo "<br /><br /><a href=\"javascript:history.back();\"><b>".__("Back to the form")."</a></b></center><br /><br /><br /><br />\n";
                require ("footer.php");
            }
            else{
                // Début de l'édition
                if (($action == "update")||($action == "updateprofile")){
                    if ($id != ""){
                        require ("headeradmin.php");
                        $reqid = "SELECT * FROM users WHERE users.user_id = ?";
                        $myhtmltitle = format_string(__("%institution_name : edition of the user %id"), array('institution_name' => $configinstitution[$lang], 'id' => htmlspecialchars($id)));
						$resultid = dbquery($reqid, array($id), 'i');
                        $nb = iimysqli_num_rows($resultid);
                        if ($nb == 1){
                            $enregid = iimysqli_result_fetch_array($resultid);
                            if ($action == "updateprofile"){
                                $login = $enregid['login'];
                                $name = $enregid['name'];
                            }
                            $query = "UPDATE users SET name=?, email=?, login=? ";
							$params = array($name, $email, $login);
							$param_types = "sss";
                            if ($action == "update") {
                                $query = $query . ", status=?, admin=?";
								array_push($params, $status, $admin);
								$param_types .= "ii"; 
							}
                            if ($newpassword1 != "") {
                                $query = $query . ", password=?";
								array_push($params, $password);
								$param_types .= "s"; 
							}
							if (($monaut == "admin") || ($monaut == "sadmin")) {
								$query = $query . ", library=?";
								array_push($params, $library);
								$param_types .= "s";
							}
							$query = $query . " WHERE user_id=?";
							array_push($params, $id);
							$param_types .= "i";
                            $resultupdate = dbquery($query, $params, $param_types) or die("Error : ".mysqli_error());
                            echo "<center><br/><b><font color=\"green\">\n";
                            if ($id != $myId) {
								echo __("The user has been successfully modified")."</b></font>\n";
							} else {
								echo __("Your user data has been successfully modified")."</b></font>\n";
							}                            if ($action == "updateprofile")
                                echo "<br/><br/><br/><a href=\"admin.php\">".__("Back to the administration page")."</a></center>\n";
                            else
                                echo "<br/><br/><br/><a href=\"list.php?table=users\">".__("Back to the users list")."</a></center>\n";
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
                    $myhtmltitle = "commandes de " . $configinstitution[$lang] . " : ".__("new user");
					$query ="INSERT INTO `users` (`user_id`, `name`, `email`, `login`, `status`, `admin`, `password`, `created_ip`, `created_on`, `library`) VALUES ('', ?, ?, ?, ?, ?, ?, ?, ?, ?)";
					$params = array($name, $email, $login, $status, $admin, $password, $ip, $date, $library);
					$id = dbquery($query, $params,'sssiissss') or die("Error : ".mysqli_error());
                    echo "<center><br/><b><font color=\"green\">\n";
                    echo format_string(__("The new record %id_record has been successfully registered"),array('id_record' => htmlspecialchars($id)))."</b></font>\n";
                    echo "<br/><br/><br/><a href=\"list.php?table=users\">".__("Back to the users list")."</a></center>\n";
                    echo "</center>\n";
                    echo "\n";
                    require ("footer.php");
                }
                // Fin de la saisie
            }
        }
        // Début de la suppresion
        if ($action == "delete"){
            $id=isValidInput($_GET['id'],9,'i',false)?$_GET['id']:'';
            $myhtmltitle = $configname[$lang] . " : ".__("Confirmation for deleting a user");
            require ("headeradmin.php");
            echo "<center><br/><br/><br/><b><font color=\"red\">\n";
            echo format_string(__("Do you really want to delete the record %id_record ?"),array('id_record' => htmlspecialchars($id)))."</b></font>\n";
            echo "<form action=\"update.php\" method=\"POST\" enctype=\"x-www-form-encoded\" name=\"fiche\" id=\"fiche\">\n";
            echo "<input name=\"table\" type=\"hidden\" value=\"users\">\n";
            echo "<input name=\"id\" type=\"hidden\" value=\"".htmlspecialchars($id)."\">\n";
            echo "<input name=\"action\" type=\"hidden\" value=\"deleteok\">\n";
            echo "<br /><br />\n";
            echo "<input type=\"submit\" value=\"".format_string(__("Confirm the deletion of the record %id_record by clicking here"),array('id_record' => htmlspecialchars($id)))."\">\n";
            echo "</form>\n";
            echo "<br/><br/><br/><a href=\"list.php?table=users\">".__("Back to the users list")."</a></center>\n";
            echo "</center>\n";
            echo "\n";
            require ("footer.php");
        }
        if ($action == "deleteok"){
            $myhtmltitle = $configname[$lang] . " : ".__("Delete a user");
            require ("headeradmin.php");
            $query = "DELETE FROM users WHERE users.user_id = ?";
			$result = dbquery($query, array($id), 'i') or die("Error : ".mysqli_error());
            echo "<center><br/><b><font color=\"green\">\n";
            echo format_string(__("The record %id has been successfully deleted"),array('id' => htmlspecialchars($id)))."</b></font>\n";
            echo "<br/><br/><br/><a href=\"list.php?table=users\">".__("Back to the users list")."</a></center>\n";
            echo "</center>\n";
            echo "\n";
            require ("footer.php");
        }
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
    require ("loginfail.php");
    require ("footer.php");
}
?>
