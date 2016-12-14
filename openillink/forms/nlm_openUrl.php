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
// 
error_reporting(E_ALL); ini_set("display_errors", 1);

$username = $nlmFormUsername;
$password = $nlmFormPassword;
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
$url = 'https://relais.nlm.nih.gov/user/login.html?group=library&UL='.$username.'&UP='.$password.'&genre=Article&atitle='.$atitleO.'&aau='.$authorO.'&issn='.$issnO.'&rft_id=info:'.$rtfIdO['pmid'].'&rft_id=info:'.$rtfIdO['doi'].'&title='.$titleO.'&VS='.$volumeO.'&issue='.$issuenoO.'&PG='.$pagesO.'&NO='.$commentO.'&maxcst='.$maxPrix.'&PD='.$anneeO;

// hugly but prevent limits timeout effect on form loading
set_time_limit(120);
header('Location: '.$url);
die();

?>