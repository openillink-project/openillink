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
// Headers for the administrators or users logged-in
//

$fileStyle1 = (is_readable ( "css/style1.css" ))? "css/style1.css" : "../css/style1.css";
$fileStyle2 = (is_readable ( "css/style1.css" ))? "css/style2.css" : "../css/style2.css";
$fileStyleTable = (is_readable ( "css/tables.css" ))? "css/tables.css" : "../css/tables.css";
$fileStyleCalendar = (is_readable ( "css/calendar.css" ))? "css/calendar.css" : "../css/calendar.css";
$scriptJs = (is_readable ( "js/script.js" ))? "js/script.js" : "../js/script.js";
$calendarJs = (is_readable ( "js/calendar.js" ))? "js/calendar.js" : "../js/calendar.js";

$fileLogin = (is_readable ( "login.php" ))? "login.php" : "../login.php";
$fileIndex = (is_readable ( "index.php" ))? "index.php" : "../index.php";
$fileList = (is_readable ( "list.php" ))? "list.php" : "../list.php";
$fileAdmin = (is_readable ( "admin.php" ))? "admin.php" : "../admin.php";
$fileReports = (is_readable ( "reports.php" ))? "reports.php" : "../reports.php";
$debugOn = (!empty($configdebuglogging)) && in_array($configdebuglogging, array('DEV', 'TEST'));

header ('Content-type: text/html; charset=utf-8');
error_reporting(-1);
ini_set('display_errors', 'On');

// paniers à afficher : in / out / sent / default = in
if(!isset($_GET['folder']))
{
$folder = "in";
}
else
{ 
$folder = $_GET['folder'];
}
if(!isset($_GET['folderid']))
{
$folderid = 0;
}
else
{ 
$folderid = $_GET['folderid'];
}
// echo $folder;

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

echo "<style type=\"text/css\" media=\"all\">\n @import url(\"$fileStyle1\");\n </style>\n";
echo "<style type=\"text/css\" media=\"print\">\n @import url(\"$fileStyle2\");\n </style>\n";
echo "<style type=\"text/css\" media=\"all\">\n @import url(\"$fileStyleTable\");\n </style>\n";
echo "<style type=\"text/css\" media=\"all\">\n @import url(\"$fileStyleCalendar\");\n </style>\n";
echo "<script type=\"text/javascript\" src=\"//ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js\"></script>\n";
echo "<script>window.jQuery || document.write('<script src=\"js/jquery-2.1.4.min.js\" type=\"text/javascript\"><\/script>')</script>\n";
echo "<script type=\"text/javascript\" src=\"$scriptJs\"></script>\n";
echo "<script type=\"text/javascript\" src=\"$calendarJs\"></script>\n";

echo "</head>\n";
if (!empty($mybodyonload))
	echo "<body onload=\"" . $mybodyonload . "\">\n";
else
	echo "<body onload=\"\">\n";
echo "<div class=\"page\">\n";
echo "<div class=\"headBar\">\n";
echo "\n";
echo "<div class=\"headBarRow1b\">\n";
echo "<h1 class=\"siteTitleBar\">".$openIllinkOfficialTitle[$lang]."</h1>";
echo "</div>\n";
echo "<div class=\"headBarRow2\">\n";
echo "<div class=\"topNavArea\">\n";
echo "<ul>\n";

echo "<li><b>".htmlspecialchars($monnom)."</b>&nbsp;<a href=\"$fileLogin?action=logout\" title=\"Logout\">[" . $logout[$lang] . "]</a></li>\n";
echo "| <li><a href=\"$fileIndex\" title=\"" . $neworder[$lang] . "\">" . $neworder[$lang] . "</a></li>\n";
if (($monaut == "admin")||($monaut == "sadmin")||($monaut == "user")){
	echo "| &nbsp;<li><a href=\"$fileList?folder=in\" title=\"" . $inhelp[$lang] . "\"";
	if ($folder == 'in')
		echo " class=\"selected\"";
	echo ">" . $inbox[$lang] . "</a></li>\n";
	echo "<li><a href=\"$fileList?folder=out\" title=\"" . $outhelp[$lang] . "\"";
	if ($folder == 'out')
		echo " class=\"selected\"";
	echo ">" . $outbox[$lang] . "</a></li>\n";
	echo "<li><a href=\"$fileList?folder=all\" title=\"" . $allhelp[$lang] . "\"";
	if ($folder == 'all')
		echo " class=\"selected\"";
	echo ">" . $allbox[$lang] . "</a></li>\n";
	echo "<li><a href=\"$fileList?folder=trash\" title=\"" . $trashhelp[$lang] . "\"";
	if ($folder == 'trash')
		echo " class=\"selected\"";
	echo ">" . $trashbox[$lang] . "</a></li>\n";
	
	// Folders personalized
	require_once ("folders.php");
	

///** begin test
	echo "| &nbsp;&nbsp;<li><a href=\"$fileAdmin\" title=\"" . $adminhelp[$lang] . "\">" . $admindisp[$lang] . "</a></li>\n";
	if (($monaut == "admin")||($monaut == "sadmin")){
///** end test

		echo "| &nbsp;&nbsp;<li><a href=\"$fileReports\" title=\"" . $reporthelp[$lang] . "\">" . $reportdisp[$lang] . "</a></li>\n";
		//echo "| &nbsp;&nbsp;<li><a href=\"help.php\" title=\"" . $helphelp[$lang] . "\">" . $helpdisp[$lang] . "</a></li>\n";
	}
}
if ($monaut == "guest"){
	echo "<li><a href=\"$fileList?folder=guest\" title=\"" . $myordershelp[$lang] . "\">" . $myorders[$lang] . "</a></li>\n";
}
// Link to journals database
echo "| &nbsp;&nbsp;<li><a href=\"" . $atozlinkurl[$lang] . "\" title=\"" . $atozname[$lang] . "\">" . $atozname[$lang] . "</a></li>\n";
// Languages links
// echo "| &nbsp;&nbsp;<li><a href=\"index.php?lang=fr\" title=\"français\">fr</a></li>";
// echo "&nbsp;<li><a href=\"index.php?lang=en\" title=\"english\">en</a></li>";
echo "</ul>\n";
echo "</div>\n";
echo "<div class=\"sysNavArea\"></div>\n";
echo "<div class=\"clb\"></div>\n";
echo "</div>\n";
echo "</div>\n";
echo "<div class=\"contentArea\">\n";
echo "<div class=\"content\">\n";
?>
