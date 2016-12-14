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
// Search form for RPVZ
// 

$target="http://rpvz.nb.admin.ch/search/query?theme=rpvz";
$target="http://www.rpvz.ch/wicket/page?5-1.IFormSubmitListener-search-quickSearch-form&amp;theme=rpvz";
$user_agent = "Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)";

$params=['query'=>"issn:".$enreg['issn']];
$defaults = array(
CURLOPT_URL => $target
,CURLOPT_RETURNTRANSFER => true
,CURLOPT_HEADER=> false
,CURLOPT_POST => true
,CURLOPT_POSTFIELDS => $params
);
$ch = curl_init();
curl_setopt_array($ch, (/*$options + */ $defaults));
$output = curl_exec($ch);
// close curl resource to free up system resources
curl_close($ch);
 if(curl_error($ch)){
    echo 'error:' . curl_error($ch);
}
elseif($output)
    echo $output;
else 
    echo "output nok and error could not be retrieved";
?>