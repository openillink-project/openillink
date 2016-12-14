<?php 
// ***************************************************************************
// ***************************************************************************
// ***************************************************************************
// This file is part of OpenILLink software.
// Copyright (C) 2015, 2016, 2017 CHUV.
// Original author(s): Mara Dalla Valle <mara.dallavalle@gmail.com>
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
//


function resendPwd($to, $pwd, $textsArray, $language, $configemail){
    require_once("translations.php");
    $message = "
<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
<html>
<head>
<title>".$textsArray["emailResendTitle"][$language]."</title>
</head>
<body>
<p>".$textsArray["openingTxt"][$language]."</p>
<p>".$textsArray["par1Txt"][$language]."</p>
<p>".$textsArray["par2Txt"][$language]."</p>
<p>".sprintf($textsArray["par4Txt"][$language], $pwd)."</p>
<p>".$textsArray["par3Txt"][$language]."</p>
".$textsArray["bibSignature"][$language]."
</body>
</html>
";

    // Always set content-type when sending HTML email
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

    // More headers
    $headers .= 'From: <'.$configemail.'>' . "\r\n";
    //$headers .= 'Cc: '.$configemail . "\r\n";

    mail($to,$textsArray["subject"][$language],$message,$headers);
}
?>