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
// Authentication by IP adress
//
$ip1 = 0;
$ip2 = 0;
$ipwww = 0;
$ip = $_SERVER['REMOTE_ADDR'];
if (!empty($_SERVER['HTTP_REFERER'])){
    $referer=$_SERVER['HTTP_REFERER'];
}
$sep = ".";
$ips1 = strtok( $ip, $sep );
$ips2 = strtok( $sep );
$ips3 = strtok( $sep );
$ips4 = strtok( $sep );

if (($ips1 == $configipainst1) && ($ips2 == $configipbinst1)){
    $ip1 = 1;
}
elseif (($ips1 == $configipainst2) && ($ips2 == $configipbinst2)){
    $ip2 = 1;
}
else{
    $ipwww = 1;
}


?>
