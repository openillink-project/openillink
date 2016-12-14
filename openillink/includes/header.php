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
// Headers common to all pages
//
header ('Content-type: text/html; charset=utf-8');
$debugOn = false;
error_reporting(-1);
ini_set('display_errors', 'On');

echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">\n";
echo "<html xmlns=\"http://www.w3.org/1999/xhtml\" lang=\"" . $lang . "\" xml:lang=\"" . $lang . "\" >\n";
echo "<head>\n";
echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\"/>\n";
echo "<title>";
if (!empty($myhtmltitle))
    echo $myhtmltitle;
else
    echo "OpenILLink";
echo "</title>\n";
echo "\n";
// (MDV) header to work for subdirectory pages as well
$fileStyle1 = (is_readable ( "css/style1.css" ))? "css/style1.css" : "../css/style1.css";
$fileStyle2 = (is_readable ( "css/style1.css" ))? "css/style2.css" : "../css/style2.css";
$fileStyleTable = (is_readable ( "css/tables.css" ))? "css/tables.css" : "../css/tables.css";
$scriptJs = (is_readable ( "js/script.js" ))? "js/script.js" : "../js/script.js";
echo "<style type=\"text/css\" media=\"all\">\n @import url(\"$fileStyle1\");\n </style>\n";
echo "<style type=\"text/css\" media=\"print\">\n @import url(\"$fileStyle2\");\n </style>\n";
echo "<style type=\"text/css\" media=\"all\">\n @import url(\"$fileStyleTable\");\n </style>\n";
echo "<script type=\"text/javascript\" src=\"$scriptJs\"></script>\n";
echo "</head>\n";
if (empty($mybodyonload)){
  $mybodyonload = '';
}
echo "<body onload=\"" . $mybodyonload . "\">\n";
echo "<div class=\"page\">\n";
echo "<div class=\"headBar\">\n";
echo "\n";
echo "<div class=\"headBarRow1b\">\n";
echo "<h1 class=\"siteTitleBar\">".$openIllinkOfficialTitle[$lang]."</h1>";
echo "</div>\n";
echo "<div class=\"headBarRow2\">\n";
echo "<div class=\"topNavArea\">\n";
echo "<ul>\n";
echo "<li><a href=\"index.php\" class=\"selected\" title=\"" . $neworder[$lang] . "\">" . $neworder[$lang] . "</a></li>\n";
echo "| &nbsp;&nbsp;&nbsp;<li><a href=\"login.php\" title=\"" . $loginmessage[$lang] . "\">" . $loginmessage[$lang] . "</a></li>\n";
// Link to journals database
echo "| &nbsp;&nbsp;&nbsp;<li><a href=\"" . $atozlinkurl[$lang] . "\" title=\"" . $atozname[$lang] . "\">" . $atozname[$lang] . "</a></li>\n";
// Languages links : uncomment for activation
//echo "| &nbsp;&nbsp;&nbsp;<li><a href=\"index.php?lang=fr\" title=\"français\">fr</a></li>";
//echo "&nbsp;<li><a href=\"index.php?lang=en\" title=\"english\">en</a></li>";
echo "</ul>\n";
echo "</div>\n";
echo "<div class=\"sysNavArea\"></div>\n";
echo "<div class=\"clb\"></div>\n";
echo "</div>\n";
echo "</div>\n";
echo "<div class=\"contentArea\">\n";
echo "<div class=\"content\">\n";
?>