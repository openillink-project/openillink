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
// 
// Authentication by cookie
// 05.04.2016, MDV add input validation on cookies, whenever possible
//
require_once ('toolkit.php');

$monnom = "";
$monlog = "";
$monbib = "";
$monaut = "";
$monlog = "";
if (!empty($_COOKIE['illinkid'])){
    $monnom = $_COOKIE['illinkid']['nom']; // varies according to context cannot be validated without prior knowledge
    $monbib = ((!empty($_COOKIE['illinkid']['bib'])) && isValidInput($_COOKIE['illinkid']['bib'],50,'s',false) )?$_COOKIE['illinkid']['bib']:'';
    $monautcripted = $_COOKIE['illinkid']['aut'];
    $monlog = ((!empty($_COOKIE['illinkid']['log'])) && isValidInput($_COOKIE['illinkid']['log'],255,'s',false) )?$_COOKIE['illinkid']['log']:'';

    $monautchecksadmin = $auth_sadmin . $secure_string_cookie;
    $monautchecksadmin = md5 ($monautchecksadmin);
    $monautcheckadmin = $auth_admin . $secure_string_cookie;
    $monautcheckadmin = md5 ($monautcheckadmin);
    $monautcheckuser = $auth_user . $secure_string_cookie;
    $monautcheckuser = md5 ($monautcheckuser);
    $monautcheckguest = $auth_guest . $secure_string_cookie;
    $monautcheckguest = md5 ($monautcheckguest);
    // if you want more levels of authorization you must add the code here (4, 5, 6, 7 or 8)
    if ($monautcripted == $monautchecksadmin)
        $monaut = "sadmin";
    if ($monautcripted == $monautcheckadmin)
        $monaut = "admin";
    if ($monautcripted == $monautcheckuser)
        $monaut = "user";
    if ($monautcripted == $monautcheckguest)
        $monaut = "guest";
}
?>
