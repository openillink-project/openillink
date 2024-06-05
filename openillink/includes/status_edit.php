<?php
// ***************************************************************************
// ***************************************************************************
// ***************************************************************************
// This file is part of OpenILLink software.
// Copyright (C) 2005, 2006, 2007, 2008, 2009, 2010, 2011, 2012, 2013, 2015, 2016, 2017, 2018, 2019, 2024 CHUV.
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
// Status table : edit form
// 
require_once ("config.php");
require_once ("authcookie.php");
require_once ("connexion.php");
require_once ("toolkit.php");

$id="";
$montitle = __("Status management");
$id =  isValidInput($_GET['id'],11,'i',false)?$_GET['id']:'';
if (!empty($_COOKIE['illinkid'])){
    if (($monaut == "admin")||($monaut == "sadmin")){
        if ($id!=""){
            $req = "SELECT * FROM status WHERE id = ?";
            $myhtmltitle = format_string(__("%institution_name : edition of the order step %order_id"), array('institution_name' => $configinstitution[$lang], 'order_id' => $id));
            $montitle = format_string(__("Status management : edition of the order step %order_id"), array('order_id' => $id));
            require ("headeradmin.php");
			echo '<nav class="breadcrumb" aria-label="breadcrumbs">
  <ul>
    <li><a href="admin.php">'.__("Administration").'</a></li>
    <li><a href="list.php?table=status">'.__("Order steps management").'</a></li>
	<li class="is-active"><a href="edit.php?table=status" aria-current="page">'.format_string(__("Edit step %order_id"), array('order_id' => $id)).'</a></li>
  </ul>
</nav>';
            $result = dbquery($req, array($id), "i");
            $nb = iimysqli_num_rows($result);
            if ($nb == 1){
                echo "<h1 class=\"title\">" . $montitle . "</h1>\n";
                echo "<br /></b>";
                echo "<ul>\n";
                $enreg = iimysqli_result_fetch_array($result);
                $statusid = $enreg['id'];
                $statuscode = $enreg['code'];
                $name["fr"] = $enreg['title1'];
                $name["en"] = $enreg['title2'];
                $name["de"] = $enreg['title3'];
                $name["it"] = $enreg['title4'];
                $name["es"] = $enreg['title5'];
                $help["fr"] = $enreg['help1'];
                $help["en"] = $enreg['help2'];
                $help["de"] = $enreg['help3'];
                $help["it"] = $enreg['help4'];
                $help["es"] = $enreg['help5'];
                $in = $enreg['in'];
                $out = $enreg['out'];
                $trash = $enreg['trash'];
                $special = $enreg['special'];
                $color = $enreg['color'];
                $anonymize = $enreg['anonymize'];
                echo "<form action=\"update.php\" method=\"POST\" enctype=\"x-www-form-encoded\" name=\"fiche\" id=\"fiche\">\n";
                echo "<input name=\"table\" type=\"hidden\" value=\"status\">\n";
                echo "<input name=\"id\" type=\"hidden\" value=\"".htmlspecialchars($statusid)."\">\n";
                echo "<input name=\"action\" type=\"hidden\" value=\"update\">\n";
                echo "<table class=\"table is-striped\" id=\"hor-zebra\">\n";
                echo "<tr><td></td><td><div class=\"field is-grouped\"><input class=\"button is-primary\" type=\"submit\" value=\"".__("Save changes")."\">\n";
                echo "&nbsp;&nbsp;<input class=\"button\" type=\"button\" value=\"".__("Cancel")."\" onClick=\"self.location='list.php?table=status'\">\n";
                echo "&nbsp;&nbsp;<input class=\"button is-danger\" type=\"button\" value=\"".__("Remove")."\" onClick=\"self.location='update.php?action=delete&table=status&id=" . htmlspecialchars($statusid) . "'\"></div></td></tr>\n";
                echo "<tr><td>&nbsp;</td><td>&nbsp;</td></tr>\n";
                echo "<tr><td><b>".__("Code")." *</b></td><td>\n";
                echo "<input name=\"code\" type=\"text\" size=\"60\" value=\"" . htmlspecialchars($statuscode) . "\"></td></tr>\n";
                echo "</td></tr>\n";
                echo "<tr><td class=\"odd\"><b>".__("Name in French")." *</b></td><td class=\"odd\"><input name=\"title1\" type=\"text\" size=\"60\" value=\"" . htmlspecialchars($name["fr"] ? $name["fr"]:"") . "\"></td></tr>\n";
                echo "<tr><td><b>".__("Help in French")."</b></td><td><input name=\"help1\" type=\"text\" size=\"60\" value=\"" . htmlspecialchars($help["fr"]? $help["fr"]: "") . "\"></td></tr>\n";
                echo "<tr><td class=\"odd\"><b>".__("Name in English")."</b></td><td class=\"odd\"><input name=\"title2\" type=\"text\" size=\"60\" value=\"" . htmlspecialchars($name["en"]?$name["en"]:"") . "\"></td></tr>\n";
                echo "<tr><td><b>".__("Help in English")."</b></td><td><input name=\"help2\" type=\"text\" size=\"60\" value=\"" . htmlspecialchars($help["en"]?$help["en"]:"") . "\"></td></tr>\n";
                echo "<tr><td class=\"odd\"><b>".__("Name in German")."</b></td><td class=\"odd\"><input name=\"title3\" type=\"text\" size=\"60\" value=\"" . htmlspecialchars($name["de"]?$name["de"]:"") . "\"></td></tr>\n";
                echo "<tr><td><b>".__("Help in German")."</b></td><td><input name=\"help3\" type=\"text\" size=\"60\" value=\"" . htmlspecialchars($help["de"]?$help["de"]:"") . "\"></td></tr>\n";
                echo "<tr><td class=\"odd\"><b>".__("Name in Italian")."</b></td><td class=\"odd\"><input name=\"title4\" type=\"text\" size=\"60\" value=\"" . htmlspecialchars($name["it"]?$name["it"]:"") . "\"></td></tr>\n";
                echo "<tr><td><b>".__("Help in Italian")."</b></td><td><input name=\"help4\" type=\"text\" size=\"60\" value=\"" . htmlspecialchars($help["it"]?$help["it"]:"") . "\"></td></tr>\n";
                echo "<tr><td class=\"odd\"><b>".__("Name in Spanish")."</b></td><td class=\"odd\"><input name=\"title5\" type=\"text\" size=\"60\" value=\"" . htmlspecialchars($name["es"]?$name["es"]:"") . "\"></td></tr>\n";
                echo "<tr><td><b>".__("Help in Spanish")."</b></td><td><input name=\"help5\" type=\"text\" size=\"60\" value=\"" . htmlspecialchars($help["es"]?$help["es"]:"") . "\"></td></tr>\n";
                echo "<tr><td class=\"odd\"><b>".__("Display orders with this status in the IN listing")."</b></td><td class=\"odd\"><input name=\"in\" value=\"1\" type=\"checkbox\"";
                if ($in==1)
                    echo " checked";
                echo "></td></tr>\n";
                echo "<tr><td><b>".__("Display orders with this status in the OUT listing")."</b></td><td><input name=\"out\" value=\"1\" type=\"checkbox\"";
                if ($out==1)
                    echo " checked";
                echo "></td></tr>\n";
                echo "<tr><td class=\"odd\"><b>".__("Display orders with this status in the TRASH listing")."</b></td><td class=\"odd\"><input name=\"trash\" value=\"1\" type=\"checkbox\"";
                if ($trash==1)
                    echo " checked";
                echo "></td></tr>\n";
                if ($config_dataprotection_retention_policy > -1) {
                    echo "<tr><td><b>";
                    if ($config_dataprotection_retention_policy > 1) {
                        echo sprintf(__("Anonymize orders with this status after %s years"), $config_dataprotection_retention_policy);
                    } else {
                        echo sprintf(__("Anonymize orders with this status after %s year"), $config_dataprotection_retention_policy);
                    }
                    echo "</b></td><td><input name=\"anonymize\" value=\"1\" type=\"checkbox\"";
                    if ($anonymize==1)
                        echo " checked";
                    echo "></td></tr>\n";
                }
                echo "<tr><td><b>".__("Add special status flag")."</b></td><td>";
                echo "<select name=\"special\">\n";
                echo "<option value=\"\"></option>\n";
                echo "<option value=\"new\"";
                if ($special == "new")
                    echo " selected";
                echo ">".__("New order (new)")."</option>\n";
                echo "<option value=\"newmultiple\"";
                if ($special == "newmultiple")
                    echo " selected";
                echo ">".__("New multiple order (newmultiple)")."</option>\n";
                echo "<option value=\"sent\"";
                if ($special == "sent")
                    echo " selected";
                echo ">".__("Order sent (sent)")."</option>\n";
                echo "<option value=\"paid\"";
                if ($special == "paid")
                    echo " selected";
                echo ">".__("Order paid (paid)")."</option>\n";
                echo "<option value=\"renew\"";
                if ($special == "renew")
                    echo " selected";
                echo ">".__("Order to renew (renew)")."</option>\n";
                echo "<option value=\"reject\"";
                if ($special == "reject")
                    echo " selected";
                echo ">".__("Order rejected (reject)")."</option>\n";
                    echo "<option value=\"tobevalidated\"";
                if ($special == "tobevalidated")
                    echo " selected";
                echo ">".__("Order to be validated (tobevalidated)")."</option>\n";
                echo "</select>\n";
                echo "</td></tr>\n";
                echo "<tr><td><b>".__("Color (.CSS valid value is expected)")."</b></td><td><input name=\"color\" type=\"text\" size=\"60\" value=\"" . htmlspecialchars($color ? $color : "") . "\"></td></tr>\n";
                echo "<tr><td>&nbsp;</td><td>&nbsp;</td></tr>\n";
                echo "<tr><td></td><td><div class=\"field is-grouped\"><input class=\"button is-primary\" type=\"submit\" value=\"".__("Save changes")."\">\n";
                echo "&nbsp;&nbsp;<input class=\"button\" type=\"button\" value=\"".__("Cancel")."\" onClick=\"self.location='list.php?table=status'\">\n";
                echo "&nbsp;&nbsp;<input class=\"button is-danger\" type=\"button\" value=\"".__("Remove")."\" onClick=\"self.location='update.php?action=delete&table=status&id=" . htmlspecialchars($statusid) . "'\"></div></td></tr>\n";
                echo "</table>\n";
                echo "</form><br /><br />\n";
                require ("footer.php");
            }
            else{
                echo "<center><br/><b><font color=\"red\">\n";
                echo __("The record was not found in the database.")."</b></font>\n";
                echo "<br /><br /><b>".__("Please restart your search or contact the database administrator")." : " . $configemail . "</b></center><br /><br /><br /><br />\n";
                require ("footer.php");
            }
        }
        else{
            require ("header.php");
            //require ("menurech.php");
            echo "<center><br/><b><font color=\"red\">\n";
            echo __("The record was not found in the database.")."</b></font>\n";
            echo "<br /><br /><b>".__("Please restart your search or contact the database administrator")." : " . $configemail . "</b></center><br /><br /><br /><br />\n";
            echo "<br /><br />\n";
            echo "</ul>\n";
            echo "\n";
            require ("footer.php");
        }
    }
    else{
        require ("header.php");
        echo "<center><br/><b><font color=\"red\">\n";
        echo __("Your rights are insufficient to edit this record")."</b></font></center><br /><br /><br /><br />\n";
        require ("footer.php");
    }
}
else{
    require ("header.php");
    require ("loginfail.php");
    require ("footer.php");
}
?>
