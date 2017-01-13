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
// The following customer values can be coded in the link URL :
// - my_customer_username
// - my_customer_password
//
// If the above values are not specified, then default values are read from config:
// - nlmFormUsername
// - nlmFormPassword
// 
error_reporting(E_ALL); ini_set("display_errors", 1);

$username = $nlmFormUsername;
$password = $nlmFormPassword;
// overwrite default config with values set in URL, if they exist
if (isset($_GET["my_customer_username"])) {
	$username = $_GET['my_customer_username'];
}
if (isset($_GET["my_customer_password"])) {
	$password = $_GET['my_customer_password'];
}

$atitleO = $enreg['titre_article'];
$issnO = $enreg['issn'];
$authorO = $enreg['auteurs'];
$rtfIdO['pmid'] = 'pmid/'.$enreg['PMID'];
$rtfIdO['doi'] = 'doi/'.$enreg['doi'];
$titleO = $enreg['titre_periodique'];
$volumeO = $enreg['volume'];
$issuenoO = $enreg['numero'];
$pagesO = $enreg['pages'];
$commentO = 'réf.:'.$enreg['illinkid'];
$maxPrix = $enreg['prix'];
$anneeO = $enreg['annee'];
// See API at https://relais.atlassian.net/wiki/display/ILL/OpenURL
$url = 'https://relais.nlm.nih.gov/user/login.html?group=library&UL='.urlencode($username).'&UP='.urlencode($password).'&genre=Article&atitle='.urlencode($atitleO).'&aau='.urlencode($authorO).'&issn='.urlencode($issnO).'&rft_id=info:'.urlencode($rtfIdO['pmid']).'&rft_id=info:'.urlencode($rtfIdO['doi']).'&title='.urlencode($titleO).'&VS='.urlencode($volumeO).'&issue='.urlencode($issuenoO).'&PG='.urlencode($pagesO).'&NO='.urlencode($commentO).'&maxcst='.urlencode($maxPrix).'&PD='.urlencode($anneeO);

// hugly but prevent limits timeout effect on form loading
set_time_limit(120);
header('Location: '.$url);
die();

?>