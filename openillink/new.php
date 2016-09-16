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
// Save the new record (order, library, unit, etc.)
// 01.04.2016, MDV, Add input verification
//
require_once ("includes/config.php");
require_once ("includes/authcookie.php");
require_once ("includes/toolkit.php");

$validTableSet = array('orders', 'users', 'libraries', 'units', 'status', 'localizations', 'links');
$table = ((!empty($_GET['table'])) && isValidInput($_GET['table'],13,'s',false,$validTableSet))?addslashes($_GET['table']):NULL;
if (empty($table)){
    $table = ((!empty($_POST['table'])) && isValidInput($_POST['table'],13,'s',false,$validTableSet))?addslashes($_POST['table']):NULL;
}

if (!empty($_COOKIE['illinkid'])){
    // switch from table parameter
    switch ($table){
        case 'orders':
        require ("includes/orders_new.php");
        break;
        case 'users':
        require ("includes/users_new.php");
        break;
        case 'libraries':
        require ("includes/libraries_new.php");
        break;
        case 'units':
        require ("includes/units_new.php");
        break;
        case 'status':
        require ("includes/status_new.php");
        break;
        case 'localizations':
        require ("includes/localizations_new.php");
        break;
        case 'links':
        require ("includes/links_new.php");
        break;
        default:
        require ("includes/orders_new.php");
        break;
    }
    // end of switch
}
elseif ($table == "orders"){
    require ("includes/orders_new.php");
}
else{
    require ("includes/header.php");
    require ("includes/loginfail.php");
    require ("includes/footer.php");
}
?>
