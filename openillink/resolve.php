<?php
// ***************************************************************************
// ***************************************************************************
// ***************************************************************************
// This file is part of OpenILLink software.
// Copyright (C) 2019 CHUV.
// Original author(s): Jérôme Zbinden <jerome.zbinden@chuv.ch>
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
// Page to retrieve links provided by link resolved (if configured)
//
require_once ("includes/config.php");
require_once ("includes/authip.php");
require_once ("includes/authcookie.php");
require_once ("includes/toolkit.php");
require_once ("includes/connexion.php");

function resolve($ip) {
	global $config_link_resolver_msg_result, $lang;
	
	$tid_code = !empty($_GET['tid'])? $_GET['tid'] : '';
	$uids = !empty($_GET['uids'])? $_GET['uids'] : '';
	$genre = !empty($_GET['genre'])? $_GET['genre'] : '';
	$title = !empty($_GET['title'])? $_GET['title'] : '';
	$date = !empty($_GET['date'])? $_GET['date'] : '';
	$volume = !empty($_GET['volume'])? $_GET['volume'] : '';
	$issue = !empty($_GET['issue'])? $_GET['issue'] : '';
	$suppl = !empty($_GET['suppl'])? $_GET['suppl'] : '';
	$pages = !empty($_GET['pages'])? $_GET['pages'] : '';
	$atitle = !empty($_GET['atitle'])? $_GET['atitle'] : '';
	$author = !empty($_GET['auteurs'])? $_GET['auteurs'] : '';
	$edition = !empty($_GET['edition'])? $_GET['edition'] : '';
	$issn_isbn = !empty($_GET['issn'])? $_GET['issn'] : '';
	$uid = !empty($_GET['uid'])? $_GET['uid'] : '';
	$referer = !empty($_GET['referer'])? $_GET['referer'] : '';
	
	$pmid = null;
	$mms_id = null;
	$doi = null;

	if (startsWith($uid, 'pmid:')) {
		$pmid = trim(substr(strtolower($uid), 5));
	} else if ($tid_code == "pmid" && !empty($uids)) {
		$pmid = trim($uids);
	}
	if (startsWith(trim(strtolower($uid)), 'mms:')) {
		$mms_id = trim(substr($uid, 4));
	} else if (($tid_code == "renouvaudmms_swissbib" || $tid_code == "mms") && !empty($uids)) {
		$mms_id = trim($uids);
	}
	
	if (startsWith(trim(strtolower($uid)), 'doi:')) {
		$doi = trim(substr($uid, 4));
	} else if ($tid_code == "doi"  && !empty($uids)) {
		$doi = trim($uids);
	}
	$search_params = "pmid=" . urlencode($pmid) . "&mms_id=" . urlencode($mms_id) . "&doi=" . urlencode($doi) . "&l=" . urlencode($lang) . "&genre=" . urlencode($genre) . "&title=" . urlencode($title) . "&date=" . urlencode($date) . "&volume=" . urlencode($volume) . "&issue=" . urlencode($issue) . "&suppl=" . urlencode($suppl) . "&pages=" . urlencode($pages) . "&author=" . urlencode($author) . "&issn_isbn=" . urlencode($issn_isbn) . "&edition=" . urlencode($edition) . "&atitle=" . urlencode($atitle);
	
	// purge old cache
	$query = "DELETE FROM `resolver_cache` WHERE date < NOW() - INTERVAL 30 MINUTE";
	$res = dbquery($query);

	// check if exists in cache
	$query = "SELECT cache FROM `resolver_cache` WHERE params=? LIMIT 1";
	$res = dbquery($query, array($search_params), 's');
	if (iimysqli_num_rows($res) > 0) {
		return iimysqli_result_fetch_array($res)['cache'];
	}
	$resolved_services = resolve_link($pmid, $mms_id, $doi, $genre, $atitle, $title, $date, $volume, $issue, $suppl, $pages, $author, $issn_isbn, $edition, $ip);
	$html_output = "";
	if ($resolved_services['has_fulltext']) {
		//if (count($resolved_services['services']) == 1) {
		$msg = $config_link_resolver_msg_result[$lang];
		//} else if (count($resolved_services['services']) > 1) {
		//	$msg = $config_link_resolver_msg_single_result[$lang];
		//}
		$links_list = array();
		foreach ($resolved_services['services'] as $service) {
			$links_list[] = '<a target="_blank" href="resolve.php?go='.htmlspecialchars(urlencode($service['resolution_url'])).'&r='. htmlspecialchars(urlencode($referer)) .'&p='.htmlspecialchars(urlencode($search_params)).'&pkg='.htmlspecialchars(urlencode($service['package_display_name'])).'">'.$service['package_display_name'].'</a>';
		}
		$html_output =  '
<div class="notification is-warning">
  <button class="delete" onclick="this.parentNode.parentNode.style.display=\'none\';this.parentNode.parentNode.previousSibling.previousSibling.value=\'\';this.parentNode.parentNode.innerHTML = \'\';return false;"></button>
  <span class="resolver_msg_header">'. $msg .'</span><ul><li>'. implode('</li><li>', $links_list).'
</li></ul></div>';
	} else {
		$html_output = "<ul><li>". __("No result found via link resolver") . "</li></ul>";
	}
	
	$response = json_encode(array('nb'=> count($resolved_services['services']), 'msg'=>$html_output, 'search_params' => $search_params));
	// cache 
	$query = "INSERT INTO `resolver_cache` (`params`, `cache`) VALUES (?, ?)";
	$params = array($search_params, $response);
	$res = dbquery($query, $params, 'ss');


	return $response;
}


header('Content-type: application/json');
if (isset($config_link_resolver_base_openurl) && $config_link_resolver_base_openurl != ''){
	$go_to_url = !empty($_GET['go'])? $_GET['go'] : '';
	$selected_package = !empty($_GET['pkg'])? $_GET['pkg'] : '';
	$search_params = !empty($_GET['p'])? $_GET['p'] : '';
	$local_referer = !empty($_GET['r'])? $_GET['r'] : '';
	if ($go_to_url != '') {
		$query = "INSERT INTO `resolver_log` (`package`, `params`, `referer`, `auth_level`) VALUES (?, ?, ?, ?)";
		$params = array($selected_package, $search_params, $local_referer, $monaut);
		$res = dbquery($query, $params, 'ssss');
		header('Location: ' . $go_to_url);
		exit();		
	} else {
		echo resolve($ip);
	}
}

?>