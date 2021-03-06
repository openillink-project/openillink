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
// Order form for the NLM
//
require_once ("includes/config.php");
require_once ("includes/authcookie.php");
require_once ("includes/connexion.php");
require_once ("includes/toolkit.php");

$nom = ((!empty($_GET['nom'])) && isValidInput($_GET['nom'], 200, 's', false))?$_GET['nom']:"";
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