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
// Search form for Ulrichsweb
// 

$target="http://ulrichsweb.serialssolutions.com/";
$user_agent = "Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)";

$params=['query'=>$enreg['issn']];
$defaults = array(
CURLOPT_URL => $target
,CURLOPT_RETURNTRANSFER => true
,CURLOPT_HEADER=> true /*false*/
,CURLOPT_USERAGENT => $user_agent
,CURLOPT_SSL_VERIFYPEER => false
);
$ch = curl_init($target);
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