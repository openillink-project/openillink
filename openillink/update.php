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
// Record update (order, library, unit, etc.)
// 01.04.2016, MDV Add input validation
//
require_once ("includes/config.php");
require_once ("includes/authcookie.php");
require_once ("includes/toolkit.php");

if (!empty($_COOKIE[illinkid])){
    // switch from table parameter
    $validTableSet = array('orders', 'users', 'libraries', 'units', 'status', 'localizations', 'links');
    $table = (isset($_GET['table']) && isValidInput($_GET['table'],13,'s',false,$validTableSet))?addslashes($_GET['table']):NULL;
    if (!isset($table))
        $table = (isset($_POST['table']) && isValidInput($_POST['table'],13,'s',false,$validTableSet))?addslashes($_POST['table']):'';
    switch ($table){
        case 'orders':
        require ("includes/orders_update.php");
        break;
        case 'users':
        require ("includes/users_update.php");
        break;
        case 'libraries':
        require ("includes/libraries_update.php");
        break;
        case 'units':
        require ("includes/units_update.php");
        break;
        case 'status':
        require ("includes/status_update.php");
        break;
        case 'localizations':
        require ("includes/localizations_update.php");
        break;
        case 'links':
        require ("includes/links_update.php");
        break;
        default:
        require ("includes/orders_update.php");
        break;
    }
    // end of switch
}
else{
    require ("includes/header.php");
    require ("includes/loginfail.php");
    require ("includes/footer.php");
}
?>
