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
// 
// 29.03.2016 MDV add input validation using checkInput defined into toolkit.php
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
// See API at https://relais.atlassian.net/wiki/display/ILL/OpenURL
$url = 'https://relais.nlm.nih.gov/user/login.html?group=library&UL='.$username.'&UP='.$password.'&genre=Article&atitle='.$atitleO.'&aau='.$authorO.'&issn='.$issnO.'&rft_id=info:'.$rtfIdO['pmid'].'&rft_id=info:'.$rtfIdO['doi'].'&title='.$titleO.'&VS='.$volumeO.'&issue='.$issuenoO.'&PG='.$pagesO.'&NO='.$commentO.'&maxcst='.$maxPrix;

// hugly but prevent limits timeout effect on form loading
set_time_limit(120);
header('Location: '.$url);
die();

?>