<?php
// ***************************************************************************
// ***************************************************************************
// ***************************************************************************
// This file is part of OpenILLink software.
// Copyright (C) 2019 CHUV.
// Original author(s): Jerome Zbinden <jerome.zbinden@chuv.ch>
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
// Admin interface to anonymize old orders.
//
// Ideally this would be called via a web cron to ensure that orders are always
// anonymized in due time.
//
require_once ("includes/config.php");
require_once ("includes/authcookie.php");
require_once ("includes/connexion.php");
require_once ("includes/toolkit.php");

if (!empty($_COOKIE['illinkid'])){
	if (($monaut == "admin")||($monaut == "sadmin")){
		$action = "";
		$action= (!empty($_POST['action'])) && isValidInput($_POST['action'],20,'s',false,array('anonymize_confirmed'))?$_POST['action']:'';
		$pagetitle = format_string(__("%institution_name orders: empty trash"), array('institution_name' => $configinstitution[$lang]));
		require ("includes/headeradmin.php");
		echo "\n";
		echo '<nav class="breadcrumb" aria-label="breadcrumbs">
  <ul>
    <li><a href="admin.php">'.__("Administration").'</a></li>
    <li class="is-active"><a href="anonymizeorders.php" aria-current="page">'.__("Anonymize old orders").'</a></li>
  </ul>
</nav>';
		echo "<h1 class=\"title\">". __("Anonymize old orders") ."</h1>\n";
		echo "<br/>\n";
		if ($config_dataprotection_retention_policy > 1) {
			$retention_period_string = sprintf(__("%s accounting years"), $config_dataprotection_retention_policy);
		} else {
			$retention_period_string = sprintf(__("%s accounting year"), $config_dataprotection_retention_policy);
		}
		if ($action == "anonymize_confirmed"){
			echo '<div class="has-text-info has-text-centered" style="display:none" id="anonymizationprogressinfo">';
			echo '<span class="icon is-large">
					<i class="fas fa-spinner fa-pulse fa-3x"></i>
				</span><br/><br/>';
			echo __("Anonymization is on-going.");
			echo '<br/><span class="has-text-weight-bold">';
			echo __("Please do not refresh page or close browser.");
			echo "</span>";
			echo '</div>';
			$result  = "";
			$query = "";
			// For MySQL >= 5.6.3, we could use INET6_ATON(saisie_par) IS NOT NULL to match IPs
			$date_limit = date("Y", strtotime('-'.$config_dataprotection_retention_policy.' years')) . "-01-01";
			$query_1 = "UPDATE orders SET historique = REPLACE(REPLACE(historique, ip, '***'), saisie_par, '***'), saisie_par = '***', ip = '***' WHERE anonymized = 0 AND date < '" . $date_limit . "' AND (saisie_par REGEXP '^[A-Z0-9._-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$' OR saisie_par REGEXP '^((25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?).(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?).(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?).(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?))|((([0-9A-Fa-f]{1,4}:){7}[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){6}:[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){5}:([0-9A-Fa-f]{1,4}:)?[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){4}:([0-9A-Fa-f]{1,4}:){0,2}[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){3}:([0-9A-Fa-f]{1,4}:){0,3}[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){2}:([0-9A-Fa-f]{1,4}:){0,4}[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){6}((b((25[0-5])|(1d{2})|(2[0-4]d)|(d{1,2}))b).){3}(b((25[0-5])|(1d{2})|(2[0-4]d)|(d{1,2}))b))|(([0-9A-Fa-f]{1,4}:){0,5}:((b((25[0-5])|(1d{2})|(2[0-4]d)|(d{1,2}))b).){3}(b((25[0-5])|(1d{2})|(2[0-4]d)|(d{1,2}))b))|(::([0-9A-Fa-f]{1,4}:){0,5}((b((25[0-5])|(1d{2})|(2[0-4]d)|(d{1,2}))b).){3}(b((25[0-5])|(1d{2})|(2[0-4]d)|(d{1,2}))b))|([0-9A-Fa-f]{1,4}::([0-9A-Fa-f]{1,4}:){0,5}[0-9A-Fa-f]{1,4})|(::([0-9A-Fa-f]{1,4}:){0,6}[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){1,7}:))$')";
			$result_1 = dbquery($query_1);
			$query_2 = "UPDATE orders SET nom = '***', prenom = '***', mail = '', tel = '***', adresse = '***', code_postal = '***', localite = '***', historique = concat(historique, '<br />', '" . sprintf(__("Order anonymized after exceeding the maximum personal data retention period (%s)"), $retention_period_string) . "',  ' (', CURRENT_TIMESTAMP(), ')'), anonymized=1 WHERE date < '" . $date_limit . "' AND anonymized = 0;";
			$result_2 = dbquery($query_2);
			echo '<script>document.getElementById("anonymizationprogressinfo").style.display="none";</script>';
			if ($query_1 && $result_2){
				echo "<center><br/><b><font color=\"green\">\n";
				echo __("The old orders have been anonymized successfully") ."</b></font>\n";
			}
			else{
				echo "<center><br/><b><font color=\"red\">\n";
				echo __("The orders could not be anonymized") ."</b></font>\n";
			}
			echo "<br/><br/><br/><a href=\"list.php?table=orders\">". __("Return to the orders list") ."</a></center>\n";
			echo "</center>\n";
			echo "\n";
		} else {
			//echo "<center><br/><br/><br/><b><font color=\"red\">\n";
			echo '<div class="container">
	<div class="columns is-centered">
	 <div class="column is-two-fifths">
	<article class="message is-danger" id="alertanonymize">
  <div class="message-body has-text-centered">';
			echo "<strong><p>";
			echo sprintf(__("Do you really want to anonymize personal data in orders older than the configured retention period (%s)?"), $retention_period_string);
			echo "</p></strong>";
			echo "<br/>";
			echo __("This cannot be undone.");
			echo "<br/>";
			echo __("Anonymization can take a while.");
			//echo "</b></font>\n";
			echo "<form action=\"anonymizeorders.php\" method=\"POST\" enctype=\"x-www-form-encoded\" name=\"fiche\" id=\"fiche\">\n";
			echo "<input name=\"action\" type=\"hidden\" value=\"anonymize_confirmed\">\n";
			echo "<br/>\n";
			echo "<input class=\"button is-danger\" type=\"submit\" onclick=\"document.getElementById('anonymizationprogressinfo').style.display='';document.getElementById('alertanonymize').style.display='none';\" value=\"". __("Confirm anonymization by clicking here") ."\">\n";
			echo "</form>\n";
			echo '</div>
</article>';
			echo '<div class="has-text-info has-text-centered" style="display:none" id="anonymizationprogressinfo">';
			echo '<span class="icon is-large">
					<i class="fas fa-spinner fa-pulse fa-3x"></i>
				</span><br/><br/>';
			echo __("Anonymization is on-going.");
			echo '<br/><span class="has-text-weight-bold">';
			echo __("Please do not refresh page or close browser.");
			echo "</span>";
			echo '</div>';
			echo '</div></div></div>';
			echo "<br/><br/><br/><a href=\"list.php?table=orders\">". __("Return to the orders list") ."</a></center>\n";
			//echo "</center>\n";
			echo "\n";
		}
		require ("includes/footer.php");
	} else {
		require ("includes/header.php");
		echo "<center><br/><b><font color=\"red\">\n";
		echo __("Your rights are insufficient to view this page") ."</b></font></center><br /><br /><br /><br />\n";
		require ("includes/footer.php");
    }
}
else{
    require ("includes/header.php");
    require ("includes/loginfail.php");
    require ("includes/footer.php");
}