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
