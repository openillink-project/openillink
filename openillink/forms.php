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
// Order form (remote sources)
//
require_once ("includes/config.php");
require_once ("includes/authcookie.php");
require_once ("includes/connexion.php");
require_once ("includes/toolkit.php");

if (!empty($_COOKIE['illinkid'])){
    if (($monaut == "admin")||($monaut == "sadmin")||($monaut == "user")){
        $illinkid = ((!empty($_GET['intId'])))?safeSetInput($_GET['intId'],8,'s',NULL,false):NULL;
        $myform = (!empty($_GET['form']))?safeSetInput($_GET['form'],20,'s',NULL,false):NULL;
        $redirect = (!empty($_GET['redirect']))?safeSetInput($_GET['redirect'],1,'s',NULL):0;
        if ((!empty($illinkid)) && (!empty($myform))){
            $myform = "forms/" . $myform . ".php";
            $req = "select * from orders where illinkid = ?";
			$result = dbquery($req, array($illinkid), 'i');
            $nb = iimysqli_num_rows($result);
            //require ("includes/headeradmin.php");
            if ($redirect!=1)
                echo "<html> <head/> ".
                "<body "."onload=\" document.forms['ILL'].submit();\"".">";
            for ($i=0 ; $i<$nb ; $i++){
                $enreg = iimysqli_result_fetch_array($result);
                $illinkid = $enreg['illinkid'];
                // Add suppl. to issue 
                $issue2 = $enreg['numero'];
                if ($enreg['supplement']!=''){
                    if ($enreg['numero']!='')
                        $issue2 = $issue2 . " suppl. " . $enreg['supplement'];
                    else
                        $issue2 = "suppl. " . $enreg['supplement'];
                }
                if ($redirect!=1)
                    require ($myform);
            }
            if ($redirect!=1)
                echo "</body></html>";
            else 
                include ($myform);
        }
        else{
            echo "<br/><br/><center><b>Missing id or form parameters</b></center><br/><br/><br/><br/>\n";
            require ("includes/footer.php");
        }
    }
    else{
        require ("includes/header.php");
        require ("includes/loginfail.php");
        require ("includes/footer.php");
    }
}
else{
    require ("includes/header.php");
    require ("includes/loginfail.php");
    require ("includes/footer.php");
}
?>
