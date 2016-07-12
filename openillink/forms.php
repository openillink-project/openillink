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
// 29.03.2016 MDV add input validation using checkInput defined into toolkit.php
//
require_once ("includes/config.php");
require_once ("includes/authcookie.php");
require_once ("includes/connexion.php");
require_once ("includes/toolkit.php");

if (!empty($_COOKIE[illinkid])){
    if (($monaut == "admin")||($monaut == "sadmin")||($monaut == "user")){
        $illinkid = (isset($_GET['intId']))?safeSetInput($_GET['intId'],8,'i',NULL,false):NULL;
        $myform = (isset($_GET['form']))?safeSetInput($_GET['form'],8,'s',NULL,false):NULL;
        if (isset($illinkid) && isset($myform)){
            $myform = "forms/" . $myform . ".php";
            $req = "select * from orders where illinkid = $illinkid";
            $result = dbquery($req);
            $nb = iimysqli_num_rows($result);
            //require ("includes/headeradmin.php");
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
                require ($myform);
            }
            echo "</body></html>";
            //require ("includes/footer.php");
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
