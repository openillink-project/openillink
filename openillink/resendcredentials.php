<?php
// ***************************************************************************
// ***************************************************************************
// ***************************************************************************
// This file is part of OpenILLink software.
// Copyright (C) 2005, 2006, 2007, 2008, 2009, 2010, 2011, 2012, 2013, 2015, 2016, 2017, 2018 CHUV.
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


require_once ('includes/config.php');
require_once ('includes/toolkit.php');

    $formSubmitted = (array_key_exists( 'commande', $_POST) || array_key_exists( 'id', $_POST));
    $destination = $formSubmitted ?( isValidInput($_POST['mail'],100,'s')?$_POST['mail'] : "" ) :"";
    $commande = $formSubmitted ? ( isValidInput($_POST['commande'],8,'i')?$_POST['commande']:NULL):NULL;

    $resendOk = false;

    $warningNokTxt = '<div class="notification is-warning"><strong> &#9888;  </strong>'.__("Information is missing or incorrect, please check.")."</div>";
    $infoEmptyTxt = '<div class="notification">'.__("Please fill in all fields on the form.")."</div>";
	$infoEmptyTxt = '';

    $infoSendOk = "<p><h1>".__("Thank you !")."</h1></p>".
    "<p>".__("The password was returned to you at address %s; Please check your mailbox.")."</p>".
    "<p>".format_string(__("In case of concern please %x_mailto_startcontact us%x_mailto_end."), array('x_mailto_start' => '<a href="mailto:%s">', 'x_mailto_end' => '</a>')) . "</p>";

    $resend_form_html = '
<div class="container">
	<div class="columns is-centered">
		<article class="card is-rounded">
			<div class="card-content">
				<h1 class="title">'. __("OpenILLink password recovery") .'</h1>'.
        '<form id="resend_OIL_credentials" name="resend_OIL_credentials" action="resendcredentials.php" method="post">'.
        '%s'.
        '<div class="field">
				<label class="label required" for="mail">'. __("Mail address") .'</label>
				<p class="control has-icon">
				<input class="input" type="text" id="mail" name="mail" value="" placeholder="'.__("Email").'" required>
				<span class="icon is-small is-left"><i class="fas fa-envelope"></i></span>
				</p>
		</div>'.
        '<div class="field">
				<label class="label required" for="commande">'. __("Number of one of your orders linked to this email") .'</label>
				<p class="control has-icon">
				<input class="input" type="text" id="commande" name="commande" value="" required>
				<span class="icon is-small is-left"><i class="fas fa-tag"></i></span>
				</p>
		</div>'.
        '<p class="control"><button type="submit" value="Valider" class="button is-primary is-fullwidth">'. __("Submit") .'</button></p>'.
        '
		</form>
			</div>
		</article>
	</div>
</div>';

    if (empty($destination) || empty($commande)){
        require_once ("includes/config.php");
        require_once ("includes/header.php");
        echo sprintf($resend_form_html, $infoEmptyTxt.($formSubmitted ? $warningNokTxt:""));
        require_once ("includes/footer.php");
    }
    else{
        require_once ("includes/config.php");
        require_once ("includes/connexion.php");
        
        $reqOrderExists = "SELECT illinkid as nbCommandes FROM orders WHERE orders.illinkid = ? AND orders.mail=?";
        $resOrderExists = dbquery($reqOrderExists, array($commande, $destination), "is");
        $nbOrderExists = iimysqli_num_rows($resOrderExists);
        if ($nbOrderExists > 0){
            if ($destination){
                $pos1 = strpos($destination,';');
                $pos2 = strpos($destination,',');
                $pos3 = strpos($destination,' ');
                if (($pos1 === false)&&($pos2 === false)&&($pos3 === false)){
                    $maillog = strtolower($destination);
                }
                else{
                    if (($pos1 != false)&&($pos2 != false)&&($pos3 != false))
                        $pos = min($pos1, $pos2, $pos3);
                    else if (($pos1 != false)&&($pos2 != false))
                        $pos = min($pos1, $pos2);
                    else if (($pos1 != false)&&($pos3 != false))
                        $pos = min($pos1, $pos3);
                    else if (($pos2 != false)&&($pos3 != false))
                        $pos = min($pos2, $pos3);
                    else if ($pos1 != false)
                        $pos = $pos1;
                    else if ($pos2 != false)
                        $pos = $pos2;
                    else if ($pos3 != false)
                        $pos = $pos3;
                    $maillog = substr($destination,0,$pos);
                    $maillog = strtolower($maillog);
                }
                $mailg = $maillog . $secure_string_guest_login;
                $passwordg = substr(md5($mailg), 0, 8);
                require_once('includes/resend_credential.php');
                resendPwd($destination, $passwordg, $lang, $configemail);
                $resendOk = true;
            }
        }
        if (!$resendOk) {
            require_once ("includes/config.php");
            require_once ("includes/header.php");
            echo sprintf($resend_form_html, $infoEmptyTxt.($formSubmitted ?$warningNokTxt:""));
            require_once ("includes/footer.php");
        }
        else {
                require_once ("includes/config.php");
                require_once ("includes/header.php");
                echo sprintf($infoSendOk, htmlspecialchars($destination), $configillmanageremail);
                require_once ("includes/footer.php");
        }
    }
?>