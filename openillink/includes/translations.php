<?php
// ***************************************************************************
// ***************************************************************************
// ***************************************************************************
// This file is part of OpenILLink software.
// Copyright (C) 2005, 2006, 2007, 2008, 2009, 2010, 2011, 2012, 2013, 2015, 2016, 2017, 2019 CHUV.
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
//
// Translations of terms used on front-end

require_once('vendor/php-gettext/gettext.inc');

if (!isset($config_available_langs)) {
	$config_available_langs = array("fr", "en", "de", "it", "es");
}

function format_string($string, array $args = array()) {
    /*
    String formatting with named placeholders.

    For example:
    format_string("this is %foo and %bar", array('foo' => "min", '%bar' => "max"));
    */
    $updated_array_args = array();
    foreach($args as $key => $value){
        $updated_array_args["%".$key] = $value;
    }
    return strtr($string, $updated_array_args);
}

function parse_browser_preferred_languages() {
    /*
     Returns the preferred language requested by the browser in $_SERVER["HTTP_ACCEPT_LANGUAGE".

     Adapted from https://stackoverflow.com/a/11161193
    */
    preg_match_all('/([a-z]{1,8}(-[a-z]{1,8})*)\s*(;\s*q\s*=\s*(1|0\.[0-9]+))?/i', $_SERVER["HTTP_ACCEPT_LANGUAGE"], $parsed_accepted_langs);
    $langs = $parsed_accepted_langs[1];
    $ranks = $parsed_accepted_langs[4];
    $rank_for_lang = array();
    for($i=0; $i<count($langs); $i++) {
        $rank_for_lang[$langs[$i]] = (float) (!empty($ranks[$i]) ? $ranks[$i] : 1);
    }
    // Define a comparison function to order based on rank and most specific region
    $compare_langs = function ($a, $b) use ($rank_for_lang) {
        if ($rank_for_lang[$a] > $rank_for_lang[$b])
            return -1;
        elseif ($rank_for_lang[$a] < $rank_for_lang[$b])
            return 1;
        elseif (strlen($a) > strlen($b))
            return -1;
        elseif (strlen($a) < strlen($b))
            return 1;
        else
            return 0;
    };

    uksort($rank_for_lang, $compare_langs);
    return $rank_for_lang;
}

function get_user_language() {
    /*
       Returns the preferred user language. Only return values among the existing
       OpenILLink translations. If langauge is not set or does not belong to the existing
       translations, return the default language configured in configdefaultlang
    */
    global $config_available_langs, $langautodetect, $configdefaultlang;
    if (!isset($configdefaultlang)) {
        // If default language is not defined, use English
        $configdefaultlang = "en";
    }
    // When no information is provided, use default
    $lang = $configdefaultlang;

	// Retrieve language from cookies if available.
	if (array_key_exists('openillink_lang', $_COOKIE) && in_array($_COOKIE['openillink_lang'], $config_available_langs)) {
		$lang = $_COOKIE['openillink_lang'];
	}
    // When browser sends preferred language
	else if ($langautodetect == 1 &&
        !array_key_exists('lang', $_REQUEST) &&
        array_key_exists('HTTP_ACCEPT_LANGUAGE', $_SERVER)) {
        $browser_preferred_langs = parse_browser_preferred_languages();
        foreach ($browser_preferred_langs as $browser_preferred_lang => $score ) {
            if (in_array(substr($browser_preferred_lang, 0, 2), $config_available_langs)) {
                $lang = substr($browser_preferred_lang, 0, 2);
                break;
            }
        }
    }

    // When language is set in URL, use it (if possible)
    if (array_key_exists('lang', $_REQUEST) && in_array($_REQUEST["lang"], $config_available_langs)) {
        $lang = $_REQUEST["lang"];
    }

	// Set cookie with language
	if (!array_key_exists('openillink_lang', $_COOKIE) || $_COOKIE['openillink_lang'] != $lang) {
		$current_time = time();
		$expiration_time = $current_time + 365 * 24 * 60 * 60; // 1 year
		setcookie('openillink_lang', $lang, $expiration_time);
	}

    return $lang;
}

$lang = get_user_language();
// We use the language as domain to simplify the requirement on installed locale with gettext.
// https://stackoverflow.com/questions/15541747/use-php-gettext-without-having-to-install-locales
$domain = $lang;
T_setlocale(LC_MESSAGES, 'default');
T_bindtextdomain($domain, dirname(__FILE__) . "/locale" );
T_bind_textdomain_codeset($domain, 'UTF-8');
T_textdomain($domain);

?>
