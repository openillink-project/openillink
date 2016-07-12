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
// Order form for the NLM
// 31.03.2016 MDV add input validation using checkInput defined into toolkit.php
//
require_once ("includes/config.php");
require_once ("includes/authcookie.php");
require_once ("includes/connexion.php");
require_once ("includes/toolkit.php");

$nom = (isset($_GET['nom']) && isValidInput($_GET['nom'], 200, 's', false))?$_GET['nom']:"";
echo "<html>\n";
echo "<head>\n";
echo "<meta http-equiv=\"content-type\" content=\"text/html;charset=iso-8859-1\">\n";
echo "<title>Recherche dans le bottin du CHUV</title>\n";
echo "</head>\n";
echo "<body onload=\"form1.submit()\">\n";
echo "<FORM ACTION=\"http://www2.unil.ch/ci/annuaire/search.php\" METHOD=\"post\" NAME=\"form1\">\n";
echo "<INPUT TYPE=\"hidden\" NAME=\"searchdomain\" VALUE=\"directory\">\n";
echo "<INPUT TYPE=\"hidden\" NAME=\"q\" VALUE=\"$nom\">\n";
echo "<INPUT TYPE=\"hidden\" NAME=\"lang\" VALUE=\"fr\">\n";
echo "<INPUT TYPE=\"hidden\" NAME=\"category\" VALUE=\"everyone\">\n";
echo "<INPUT TYPE=\"hidden\" NAME=\"basedn\" VALUE=\"o=Universite de Lausanne, c=ch\">\n";
echo "<INPUT TYPE=\"hidden\" NAME=\"cn\" VALUE=\"\">\n";
// echo "<INPUT TYPE=\"hidden\" NAME=\"givenName\" VALUE=\"".$_GET['prenom']."\">\n";
echo "</FORM>\n";
echo "</body>\n";
echo "</html>";
?>