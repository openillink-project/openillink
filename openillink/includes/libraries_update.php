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
// Libraries table : creation and update of records
// 
// 11.03.2016, MDV Replaced connector to db from mysql_ to mysqli_
// 21.03.2016, MDV Input reading verification
// 01.04.2016, MDV suppressed reference to undefined local file menurech.php

require_once ("config.php");
require_once ("authcookie.php");
require_once ("connexion.php");
require_once ("toolkit.php");

if (!empty($_COOKIE[illinkid])){
  $action2="";
  $action="";
  $id = (isset($_POST['id']) && isValidInput($_POST['id'],11,'i',false))?$_POST['id']:NULL;
  $ip = $_SERVER['REMOTE_ADDR'];
  $validActionSet = array('new', 'update', 'delete', 'deleteok');
  $action=(isset($_POST['action']) && isValidInput($_POST['action'],10,'s',false,$validActionSet))?addslashes($_POST['action']):'';
  $action2=(isset($_GET['action']) && isValidInput($_GET['action'],10,'s',false,$validActionSet))?addslashes($_GET['action']):NULL;
  if (isset($action2))
    $action = $action2;
    if (($monaut == "admin")||($monaut == "sadmin")){
      $mes="";
      $date=date("Y-m-d H:i:s");
      $code = (isset($_POST['code']) && isValidInput($_POST['code'],50,'s',false))? addslashes(trim($_POST['code'])):'';
      $name1 = (isset($_POST['name1']) && isValidInput($_POST['name1'],50,'s',false))? addslashes(trim($_POST['name1'])):'';
      $name2 = (isset($_POST['name2']) && isValidInput($_POST['name2'],50,'s',false))? addslashes(trim($_POST['name2'])):'';
      $name3 = (isset($_POST['name3']) && isValidInput($_POST['name3'],50,'s',false))? addslashes(trim($_POST['name3'])):'';
      $name4 = (isset($_POST['name4']) && isValidInput($_POST['name4'],50,'s',false))? addslashes(trim($_POST['name4'])):'';
      $name5 = (isset($_POST['name5']) && isValidInput($_POST['name5'],50,'s',false))? addslashes(trim($_POST['name5'])):'';
      $default = (isset($_POST['default']) && isValidInput($_POST['default'],1,'s',false))? addslashes(trim($_POST['default'])):'';
      if ($default != "1")
        $default = 0;
      else
        $default = 1;
      $hasSharedOrders = (isset($_POST['hasSharedOrders']) && isValidInput($_POST['hasSharedOrders'],1,'s',false))? addslashes(trim($_POST['hasSharedOrders'])):'';
      if ($hasSharedOrders != "1")
        $hasSharedOrders = 0;
      else
        $hasSharedOrders = 1;
      if (($action == "update")||($action == "new")){
        // Tester si le code est unique
        $reqcode = "SELECT * FROM libraries WHERE code = ?";
        $resultcode = dbquery($reqcode, array($code), 's');
        $nbcode = iimysqli_num_rows($resultcode);
        $enregcode = iimysqli_result_fetch_array($resultcode);
        $idcode = $enregcode['id'];
        if (($nbcode == 1)&&($action == "new"))
          $mes = $mes . "<br/>le code '" . $code . "' existe déjà dans la base, veuillez choisir un autre";
        if (($nbcode == 1)&&($action != "new")&&($idcode != $id))
          $mes = $mes . "<br/>le code '" . $code . "' est déjà attribué à une autre bibliothèque, veuillez choisir un autre";
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
        else {
          // Début de l'édition
          if ($action == "update") {
            if ($id != "") {
              require ("headeradmin.php");
              $reqid = "SELECT * FROM libraries WHERE id = ?";
              $myhtmltitle = $configname[$lang] . " : édition de la fiche bibliothèque $id";
              $resultid = dbquery($reqid, array($id), 'i');

              $nb = iimysqli_num_rows($resultid);
              if ($nb == 1) {
                $enregid = iimysqli_result_fetch_array($resultid);
                $query = "UPDATE libraries SET libraries.name1=?, libraries.name2=?, libraries.name3=?, libraries.name4=?, libraries.name5=?, libraries.default=?, libraries.code=?, libraries.has_shared_ordres=? WHERE libraries.id=?";
                $params = array($name1, $name2, $name3, $name4, $name5, $default, $code, $hasSharedOrders, $id);
                $resultupdate = dbquery($query, $params, 'sssssisii') or die("Error : ".mysqli_error());
                echo "<center><br/><b><font color=\"green\">\n";
                echo "La modification de la fiche $id a été enregistrée avec succès</b></font>\n";
                echo "<br/><br/><br/><a href=\"list.php?table=libraries\">Retour à la liste de bibliothèques</a></center>\n";
                require ("footer.php");
              }
              else {
                echo "<center><br/><b><font color=\"red\">\n";
                echo "La modification n'a pas été enregistrée car l'identifiant de la fiche $id n'a pas été trouvée dans la base.</b></font>\n";
                echo "<br /><br /><b>Veuillez relancer de nouveau votre recherche ou contactez l'administrateur de la base : " . $configemail . "</b></center><br /><br /><br /><br />\n";
                require ("footer.php");
              }
            }
          else {
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
      if ($action == "new") {
        require ("headeradmin.php");
        $myhtmltitle = $configname[$lang] . " : nouvelle bibliothèque";
        $query ="INSERT INTO `libraries` (`id`, `name1`, `name2`, `name3`, `name4`, `name5`, `code`, `default`,`has_shared_ordres`) VALUES ('', ?, ?, ?, ?, ?, ?, ?, ?)";
        $params = array($name1, $name2, $name3, $name4, $name5, $code, $default, $hasSharedOrders);
        $id = dbquery($query, $params, 'ssssssii') or die("Error : ".mysqli_error());
        echo "<center><br/><b><font color=\"green\">\n";
        echo "La nouvelle fiche $id a été enregistrée avec succès</b></font>\n";
        echo "<br/><br/><br/><a href=\"list.php?table=libraries\">Retour à la liste de bibliothèques</a></center>\n";
        echo "</center>\n";
        echo "\n";
        require ("footer.php");
      }
    }
    // Fin de la création
    // Début de la suppresion
    if ($action == "delete") {
      $id=addslashes((isset($_GET['id']) && isValidInput($_GET['id'],11,'i',false))?$_GET['id']:"");
      $myhtmltitle = $configname[$lang] . " : confirmation pour la suppresion d'une bibliothèque";
      require ("headeradmin.php");
      echo "<center><br/><br/><br/><b><font color=\"red\">\n";
      echo "Voulez-vous vraiement supprimer la fiche " . $id . "?</b></font>\n";
      echo "<form action=\"update.php\" method=\"POST\" enctype=\"x-www-form-encoded\" name=\"fiche\" id=\"fiche\">\n";
      echo "<input name=\"table\" type=\"hidden\" value=\"libraries\">\n";
      echo "<input name=\"id\" type=\"hidden\" value=\"".$id."\">\n";
      echo "<input name=\"action\" type=\"hidden\" value=\"deleteok\">\n";
      echo "<br /><br />\n";
      echo "<input type=\"submit\" value=\"Confirmer la suppression de la fiche " . $id . " en cliquant ici\">\n";
      echo "</form>\n";
      echo "<br/><br/><br/><a href=\"list.php?table=libraries\">Retour à la liste des bibliothèques</a></center>\n";
      echo "</center>\n";
      echo "\n";
      require ("footer.php");
    }
    if ($action == "deleteok") {
      $myhtmltitle = $configname[$lang] . " : supprimer une bibliothèque";
      require ("headeradmin.php");
      $query = "DELETE FROM libraries WHERE libraries.id = ?";
      $result = dbquery($query, array($id), 'i') or die("Error : ".mysqli_error());
      echo "<center><br/><b><font color=\"green\">\n";
      echo "La fiche " . $id . " a été supprimée avec succès</b></font>\n";
      echo "<br/><br/><br/><a href=\"list.php?table=libraries\">Retour à la liste des bibliothèques</a></center>\n";
      echo "</center>\n";
      echo "\n";
      require ("footer.php");
    }
    // Fin de la saisie
  }
  else {
    require ("header.php");
    echo "<center><br/><b><font color=\"red\">\n";
    echo "Vos droits sont insuffisants pour consulter cette page</b></font></center><br /><br /><br /><br />\n";
     require ("footer.php");
  }
}
else {
  require ("header.php");
  require ("codefail.php");
  require ("footer.php");
}
?>
