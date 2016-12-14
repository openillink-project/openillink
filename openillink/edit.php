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
// Record edit form (order, library, unit, etc.)
//

require_once ("includes/config.php");
require_once ("includes/authcookie.php");
require_once ("includes/toolkit.php");

if (!empty($_COOKIE['illinkid'])){
    // switch from table parameter
    $table = safeSetInput($_GET['table'],20);
    switch ($table){
        case 'orders':
            require ("includes/orders_edit.php");
            break;
        case 'users':
            require ("includes/users_edit.php");
            break;
        case 'libraries':
            require ("includes/libraries_edit.php");
            break;
        case 'units':
            require ("includes/units_edit.php");
            break;
        case 'status':
            require ("includes/status_edit.php");
            break;
        case 'localizations':
            require ("includes/localizations_edit.php");
            break;
        case 'links':
            require ("includes/links_edit.php");
            break;
        default:
            require ("includes/orders_edit.php");
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
