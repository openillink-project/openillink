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
		setcookie('openillink_lang', $lang);
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

// Document types (based on OpenURL spec)
/* MR mis dans config
$doctypes[0]["code"] = "article";
$doctypes[1]["code"] = "preprint";
$doctypes[2]["code"] = "book";
$doctypes[3]["code"] = "bookitem";
$doctypes[4]["code"] = "thesis";
$doctypes[5]["code"] = "journal";
$doctypes[6]["code"] = "proceeding";
$doctypes[7]["code"] = "conference";
$doctypes[8]["code"] = "other";

$doctypes[0]["fr"] = "Article";
$doctypes[0]["en"] = "Article";
$doctypes[0]["de"] = "Article";
$doctypes[0]["it"] = "Article";
$doctypes[0]["es"] = "Article";

$doctypes[1]["fr"] = "Preprint";
$doctypes[1]["en"] = "Preprint";
$doctypes[1]["de"] = "Preprint";
$doctypes[1]["it"] = "Preprint";
$doctypes[1]["es"] = "Preprint";

$doctypes[2]["fr"] = "Livre";
$doctypes[2]["en"] = "Book";
$doctypes[2]["de"] = "Buch";
$doctypes[2]["it"] = "Libro";
$doctypes[2]["es"] = "Libro";

$doctypes[3]["fr"] = "Chapitre de livre";
$doctypes[3]["en"] = "Book chapter";
$doctypes[3]["de"] = "Book chapter";
$doctypes[3]["it"] = "Capitolo del libro";
$doctypes[3]["es"] = "Book chapter";

$doctypes[4]["fr"] = "Thèse";
$doctypes[4]["en"] = "Thesis";
$doctypes[4]["de"] = "Thesis";
$doctypes[4]["it"] = "Tesi";
$doctypes[4]["es"] = "Thesis";

$doctypes[5]["fr"] = "No de revue";
$doctypes[5]["en"] = "Journal issue";
$doctypes[5]["de"] = "Journal issue";
$doctypes[5]["it"] = "Journal issue";
$doctypes[5]["es"] = "Journal issue";

$doctypes[6]["fr"] = "Actes d'un congrès";
$doctypes[6]["en"] = "Conference proceedings";
$doctypes[6]["de"] = "Conference proceedings";
$doctypes[6]["it"] = "Conference proceedings";
$doctypes[6]["es"] = "Conference proceedings";

$doctypes[7]["fr"] = "Article d'une conference";
$doctypes[7]["en"] = "Conference paper";
$doctypes[7]["de"] = "Conference paper";
$doctypes[7]["it"] = "Conference paper";
$doctypes[7]["es"] = "Conference paper";

$doctypes[8]["fr"] = "Autre";
$doctypes[8]["en"] = "Other";
$doctypes[8]["de"] = "Other";
$doctypes[8]["it"] = "Other";
$doctypes[8]["es"] = "Other";
*/

// Commons terms

/*
$loginmessage["fr"] = "Se connecter";
$loginmessage["en"] = "Login";
$loginmessage["de"] = "Login";
$loginmessage["it"] = "Login";
$loginmessage["es"] = "Login";

$logout["fr"] = "Déconnexion";
$logout["en"] = "Logout";
$logout["de"] = "Logout";
$logout["it"] = "Logout";
$logout["es"] = "Logout";

$neworder["fr"] = "Nouvelle commande";
$neworder["en"] = "New Order";
$neworder["de"] = "Neue Bestellung";
$neworder["it"] = "Nuovo ordine";
$neworder["es"] = "Nuevo pedido";

$inhelp["fr"] = "Commandes à fournir ou à valider";
$inhelp["en"] = "Inbox";
$inhelp["de"] = "Inbox";
$inhelp["it"] = "Inbox";
$inhelp["es"] = "Inbox";

$inbox["fr"] = "In";
$inbox["en"] = "In";
$inbox["de"] = "In";
$inbox["it"] = "In";
$inbox["es"] = "In";

$outhelp["fr"] = "Commandes envoyées à l'extérieur et pas encore reçues";
$outhelp["en"] = "Orders sent to the outside and not yet received";
$outhelp["de"] = "Orders sent to the outside and not yet received";
$outhelp["it"] = "Ordine inviato in attesa di risposta";
$outhelp["es"] = "Orders sent to the outside and not yet received";

$outbox["fr"] = "Out";
$outbox["en"] = "Out";
$outbox["de"] = "Out";
$outbox["it"] = "Out";
$outbox["es"] = "Out";

$allhelp["fr"] = "Toutes les commandes";
$allhelp["en"] = "All orders";
$allhelp["de"] = "All orders";
$allhelp["it"] = "Tutti gli ordini";
$allhelp["fr"] = "All orders";

$allbox["fr"] = "All";
$allbox["en"] = "All";
$allbox["de"] = "All";
$allbox["it"] = "All";
$allbox["es"] = "All";

$trashhelp["fr"] = "Commandes supprimées";
$trashhelp["en"] = "Orders deleted";
$trashhelp["de"] = "Orders deleted";
$trashhelp["it"] = "Ordini cancellati";
$trashhelp["es"] = "Orders deleted";

$trashbox["fr"] = "Trash";
$trashbox["en"] = "Trash";
$trashbox["de"] = "Trash";
$trashbox["it"] = "Cestino";
$trashbox["es"] = "Basura";

$adminhelp["fr"] = "Administration des utilisateurs et des valeurs";
$adminhelp["en"] = "Administration of users and values";
$adminhelp["de"] = "Administration of users and values";
$adminhelp["it"] = "Gestione utenti e valori";
$adminhelp["es"] = "Administration of users and values";

$admindisp["fr"] = "Administration";
$admindisp["en"] = "Administration";
$admindisp["de"] = "Administration";
$admindisp["it"] = "Amministrazione";
$admindisp["es"] = "Administration";

$reportdisp["fr"] = "Statistiques";
$reportdisp["en"] = "Reports";
$reportdisp["de"] = "Reports";
$reportdisp["it"] = "Statistiche";
$reportdisp["es"] = "Reports";

$helphelp["fr"] = "Menu d'aide";
$helphelp["en"] = "Help topics";
$helphelp["de"] = "Help topics";
$helphelp["it"] = "Help topics";
$helphelp["es"] = "Help topics";

$helpdisp["fr"] = "Aide";
$helpdisp["en"] = "Help";
$helpdisp["de"] = "Hilfe";
$helpdisp["it"] = "Aiuto";
$helpdisp["es"] = "Help";

$myordershelp["fr"] = "Voir toutes mes commandes";
$myordershelp["en"] = "See all my orders";
$myordershelp["de"] = "See all my orders";
$myordershelp["it"] = "Vedere tutti gli ordini effettuati";
$myordershelp["es"] = "See all my orders";

$myorders["fr"] = "Mes commandes";
$myorders["en"] = "My orders";
$myorders["de"] = "My orders";
$myorders["it"] = "Ordini effettuati";
$myorders["es"] = "My orders";

$firstmessage["fr"] = "Formulaire de commande de documents à la ";
$firstmessage["en"] = "Document order form to the ";
$firstmessage["de"] = "Document order form to the ";
$firstmessage["it"] = "Formulario di richiesta di documenti presso ";
$firstmessage["es"] = "Document order form to the ";
*/
// Optional second line message on order page
/*
$secondmessage["fr"] = "";
$secondmessage["en"] = "";
$secondmessage["de"] = "";
$secondmessage["it"] = "";
$secondmessage["es"] = "";

$statusmessage["fr"] = "Statut";
$statusmessage["en"] = "Status";
$statusmessage["de"] = "Status";
$statusmessage["it"] = "Stato";
$statusmessage["es"] = "Status";

$localisationmessage["fr"] = "Localisation";
$localisationmessage["en"] = "Localization";
$localisationmessage["de"] = "Localization";
$localisationmessage["it"] = "Localizzazione";
$localisationmessage["es"] = "Localization";

$localisationintmessage["fr"] = "Localisations propres";
$localisationintmessage["en"] = "Our Localizations";
$localisationintmessage["de"] = "Our Localizations";
$localisationintmessage["it"] = "Le nostre localizzazioni";
$localisationintmessage["es"] = "Our Localizations";

$localisationextmessage["fr"] = "Bibliothèques du réseau";
$localisationextmessage["en"] = "Network libraries";
$localisationextmessage["de"] = "Network libraries";
$localisationextmessage["it"] = "Librerie della rete";
$localisationextmessage["es"] = "Network libraries";

$prioritymessage["fr"] = "Priorité";
$prioritymessage["en"] = "Priority";
$prioritymessage["de"] = "Priority";
$prioritymessage["it"] = "Priorità";
$prioritymessage["es"] = "Priority";

$prioritynormmessage["fr"] = "Normale";
$prioritynormmessage["en"] = "Normal";
$prioritynormmessage["de"] = "Normal";
$prioritynormmessage["it"] = "Normale";
$prioritynormmessage["es"] = "Normal";

$prioritynonemessage["fr"] = "Pas prioritaire";
$prioritynonemessage["en"] = "Not a priority";
$prioritynonemessage["de"] = "Not a priority";
$prioritynonemessage["it"] = "Non prioritario";
$prioritynonemessage["es"] = "Not a priority";

$sourcemessage["fr"] = "Origine de la commande";
$sourcemessage["en"] = "Origin of the order";
$sourcemessage["de"] = "Origin of the order";
$sourcemessage["it"] = "Origin of the order";
$sourcemessage["es"] = "Origin of the order";

$addvaluemessage["fr"] = "Ajouter une valeur...";
$addvaluemessage["en"] = "Add new value...";
$addvaluemessage["de"] = "Add new value...";
$addvaluemessage["it"] = "Aggiunger un nuovo valore...";
$addvaluemessage["es"] = "Add new value...";

$orderdatehelpmessage["fr"] = "à remplir uniquement si différente de la date du jour";
$orderdatehelpmessage["en"] = "to be completed only if different from the current date";
$orderdatehelpmessage["de"] = "to be completed only if different from the current date";
$orderdatehelpmessage["it"] = "da completare solo se diverso dalla data odierna";
$orderdatehelpmessage["es"] = "to be completed only if different from the current date";

$orderdatemessage["fr"] = "Date de commande";
$orderdatemessage["en"] = "Order date";
$orderdatemessage["de"] = "Order date";
$orderdatemessage["it"] = "Data dell'ordine";
$orderdatemessage["es"] = "Order date";

$orderfactdatemessage["fr"] = "Date de facturation";
$orderfactdatemessage["en"] = "Invoice date";
$orderfactdatemessage["de"] = "Invoice date";
$orderfactdatemessage["it"] = "Data di fatturazione";
$orderfactdatemessage["es"] = "Invoice date";

$orderrenewdatemessage["fr"] = "A renouveler le";
$orderrenewdatemessage["en"] = "To be renewed on";
$orderrenewdatemessage["de"] = "To be renewed on";
$orderrenewdatemessage["it"] = "Da rinnovare il";
$orderrenewdatemessage["es"] = "To be renewed on";

$pricemessage["fr"] = "Prix (CHF)";
$pricemessage["en"] = "Price (CHF)";
$pricemessage["de"] = "Price (CHF)";
$pricemessage["it"] = "Prezzo (CHF)";
$pricemessage["es"] = "Price (CHF)";

$paidadvmessage["fr"] = "commande payée à l'avance";
$paidadvmessage["en"] = "order paid in advance";
$paidadvmessage["de"] = "order paid in advance";
$paidadvmessage["it"] = "ordine pagato in anticipo";
$paidadvmessage["es"] = "order paid in advance";

$refextmessage["fr"] = "Réf. fournisseur";
$refextmessage["en"] = "Provider Ref.";
$refextmessage["de"] = "Provider Ref.";
$refextmessage["it"] = "Ref. fornitore";
$refextmessage["es"] = "Provider Ref.";

$refintmessage["fr"] = "Réf. interne à la bibliothèque";
$refintmessage["en"] = "Internal ref. to the library";
$refintmessage["de"] = "Ref. internal to the library";
$refintmessage["it"] = "Ref. interna alla biblioteca";
$refintmessage["es"] = "Ref. internal to the library";

$alertmessage["fr"] = "Attention, toute commande est soumise à une participation financière";
$alertmessage["en"] = "Please note, all orders are subject to a financial contribution";
$alertmessage["de"] = "Please note, all orders are subject to a financial contribution";
$alertmessage["it"] = "Attenzione, tutti gli ordini sono soggetti a un contributo finanziario";
$alertmessage["es"] = "Please note, all orders are subject to a financial contribution";

$informationmessage["fr"] = "Pour plus de renseignements (tarifs, facturation, etc.) contactez nous par courriel";
$informationmessage["en"] = "Contact us by email for more information (pricing, billing, etc.)";
$informationmessage["de"] = "Contact us by email for more information (pricing, billing, etc.)";
$informationmessage["it"] = "Per più informazioni (tariffe, fatturazione, etc.) vogliate contattarci per mail";
$informationmessage["es"] = "Contact us by email for more information (pricing, billing, etc.)";

$directory1message["fr"] = "Chercher le nom dans l'annuaire de l'hôpital";
$directory1message["en"] = "Search the name in the directory of the hospital";
$directory1message["de"] = "Search the name in the directory of the hospital";
$directory1message["it"] = "Cercare il nome nell'annuario dell'ospedale";
$directory1message["es"] = "Search the name in the directory of the hospital";

$directory2message["fr"] = "Chercher le nom dans l'annuaire de l'université";
$directory2message["en"] = "Search the name in the directory of the university";
$directory2message["de"] = "Search the name in the directory of the university";
$directory2message["it"] = "Cercare il nome nell'annuario dell'università";
$directory2message["es"] = "Search the name in the directory of the university";

$unitmessage["fr"] = "Service";
$unitmessage["en"] = "Unit";
$unitmessage["de"] = "Unit";
$unitmessage["it"] = "Servizio";
$unitmessage["es"] = "Unit";

$unitothermessage["fr"] = "Autre service";
$unitothermessage["en"] = "Other unit";
$unitothermessage["de"] = "Other unit";
$unitothermessage["it"] = "Altro servizio";
$unitothermessage["es"] = "Other unit";

$cgramessage["fr"] = "Code budgetaire";
$cgramessage["en"] = "Budget heading";
$cgramessage["de"] = "Budget heading";
$cgramessage["it"] = "Codice budgetario";
$cgramessage["es"] = "Budget heading";

$cgrbmessage["fr"] = "Ligne budgetaire";
$cgrbmessage["en"] = "Budget subheading";
$cgrbmessage["de"] = "Budget subheading";
$cgrbmessage["it"] = "Linea budgetaria";
$cgrbmessage["es"] = "Budget subheading";

$emailmessage["fr"] = "E-Mail";
$emailmessage["en"] = "E-Mail";
$emailmessage["de"] = "E-Mail";
$emailmessage["it"] = "Mail";
$emailmessage["es"] = "E-Mail";

$telmessage["fr"] = "Tél.";
$telmessage["en"] = "Tel.";
$telmessage["de"] = "Tel.";
$telmessage["it"] = "Tel.";
$telmessage["es"] = "Tel.";

$addressmessage["fr"] = "Adresse privée";
$addressmessage["en"] = "Private address";
$addressmessage["de"] = "Private address";
$addressmessage["it"] = "Indirizzo privato";
$addressmessage["es"] = "Private address";

$cpmessage["fr"] = "Code postal";
$cpmessage["en"] = "Zip code";
$cpmessage["de"] = "Zip code";
$cpmessage["it"] = "Codice NPA";
$cpmessage["es"] = "Zip code";

$citymessage["fr"] = "Localité";
$citymessage["en"] = "City";
$citymessage["de"] = "City";
$citymessage["it"] = "Città";
$citymessage["es"] = "Ciudad";

$dispomessage["fr"] = "Si disponible à la bibliothèque";
$dispomessage["en"] = "If available at the library";
$dispomessage["de"] = "If available at the library";
$dispomessage["it"] = "Se disponibile alla biblioteca";
$dispomessage["es"] = "Si es disponible en la biblioteca";

$dispofactmessage["fr"] = "envoi par e-mail (facturé)";
$dispofactmessage["en"] = "send by e-mail (billed)";
$dispofactmessage["de"] = "send by e-mail (billed)";
$dispofactmessage["it"] = "invio per mail(fatturato)";
$dispofactmessage["es"] = "enviado por e-mail (facturado)";

$disponotfactmessage["fr"] = "m'avertir et je passe faire la copie (non facturé)";
$disponotfactmessage["en"] = "let me know and I come to make a copy (not billed)";
$disponotfactmessage["de"] = "let me know and I come to make a copy (not billed)";
$disponotfactmessage["it"] = "let me know and I come to make a copy (not billed)";
$disponotfactmessage["es"] = "let me know and I come to make a copy (not billed)";

$savecookiemessage["fr"] = "Mémoriser ces données pour les prochaines commandes (cookies autorisées)";
$savecookiemessage["en"] = "Remember data for future orders (cookies allowed)";
$savecookiemessage["de"] = "Remember data for future orders (cookies allowed)";
$savecookiemessage["it"] = "Remember data for future orders (cookies allowed)";
$savecookiemessage["es"] = "Remember data for future orders (cookies allowed)";

$deletecookiemessage["fr"] = "supprimer le cookie";
$deletecookiemessage["en"] = "delete the cookie";
$deletecookiemessage["de"] = "delete the cookie";
$deletecookiemessage["it"] = "delete the cookie";
$deletecookiemessage["es"] = "delete the cookie";

$lookupmessage["fr"] = "Remplir la commande à partir du";
$lookupmessage["en"] = "Fulfill the order from";
$lookupmessage["de"] = "Fulfill the order from";
$lookupmessage["it"] = "Fulfill the order from";
$lookupmessage["es"] = "Fulfill the order from";

$stitlemessage["fr"] = "Titre du périodique ou du livre";
$stitlemessage["en"] = "Title of journal or book";
$stitlemessage["de"] = "Title of journal or book";
$stitlemessage["it"] = "Titolo del giornale o del libro";
$stitlemessage["es"] = "Title of journal or book";

$atozlinkmessage["fr"] = "vérifier sur la base des périodiques";
$atozlinkmessage["en"] = "check on journals database";
$atozlinkmessage["de"] = "check on journals database";
$atozlinkmessage["it"] = "verificate nel database dei periodici";
$atozlinkmessage["es"] = "check on journals database";

$yearmessage["fr"] = "Année";
$yearmessage["en"] = "Year";
$yearmessage["de"] = "Year";
$yearmessage["it"] = "Anno";
$yearmessage["es"] = "Año";

$volumemessage["fr"] = "Vol.";
$volumemessage["en"] = "Vol.";
$volumemessage["de"] = "Vol.";
$volumemessage["it"] = "Vol.";
$volumemessage["es"] = "Vol.";

$issuemessage["fr"] = "(No)";
$issuemessage["en"] = "(Issue)";
$issuemessage["de"] = "(Issue)";
$issuemessage["it"] = "(No.)";
$issuemessage["es"] = "(Issue)";

$supplementmessage["fr"] = "Suppl.";
$supplementmessage["en"] = "Suppl.";
$supplementmessage["de"] = "Suppl.";
$supplementmessage["it"] = "Suppl.";
$supplementmessage["es"] = "Suppl.";

$pagesmessage["fr"] = "Pages";
$pagesmessage["en"] = "Pages";
$pagesmessage["de"] = "Pages";
$pagesmessage["it"] = "Pagine";
$pagesmessage["es"] = "Paginas";

$atitlemessage["fr"] = "Titre de l'article ou du chapitre";
$atitlemessage["en"] = "Title of article or book chapter";
$atitlemessage["de"] = "Title of article or book chapter";
$atitlemessage["it"] = "Titolo dell'articolo o del capitolo del libro";
$atitlemessage["es"] = "Title of article or book chapter";

$authorsmessage["fr"] = "Auteur(s)";
$authorsmessage["en"] = "Author(s)";
$authorsmessage["de"] = "Author(s)";
$authorsmessage["it"] = "Autore(i)";
$authorsmessage["es"] = "Author(s)";

$editionmessage["fr"] = "Edition (pour les livres)";
$editionmessage["en"] = "Edition (for books)";
$editionmessage["de"] = "Edition (for books)";
$editionmessage["it"] = "Edizione (per i libri)";
$editionmessage["es"] = "Edition (for books)";

$commentsmessage["fr"] = "Remarques professionnelles";
$commentsmessage["en"] = "Professional Notes";
$commentsmessage["de"] = "Professional Notes";
$commentsmessage["it"] = "Note personali";
$commentsmessage["es"] = "Professional Notes";

$publiccommentsmessage["fr"] = "Remarques";
$publiccommentsmessage["en"] = "Notes";
$publiccommentsmessage["de"] = "Notes";
$publiccommentsmessage["it"] = "Note";
$publiccommentsmessage["es"] = "Notes";

$submitmessage["fr"] = "Enregistrer";
$submitmessage["en"] = "Submit";
$submitmessage["de"] = "Submit";
$submitmessage["it"] = "Salva";
$submitmessage["es"] = "Submit";

$resetmessage["fr"] = "Effacer";
$resetmessage["en"] = "Reset";
$resetmessage["de"] = "Reset";
$resetmessage["it"] = "Annulla";
$resetmessage["es"] = "Reset";

$poweredmessage["fr"] = "Site propulsé par <a href=\"https://github.com/openillink-project\" target=\"_blank\">OpeniLLink-project</a><br />";
$poweredmessage["en"] = "Powered by <a href=\"https://github.com/openillink-project\" target=\"_blank\">OpeniLLink-project</a><br />";
$poweredmessage["de"] = "Powered by <a href=\"https://github.com/openillink-project\" target=\"_blank\">OpeniLLink-project</a><br />";
$poweredmessage["it"] = "Powered by <a href=\"https://github.com/openillink-project\" target=\"_blank\">OpeniLLink-project</a><br />";
$poweredmessage["es"] = "Powered by <a href=\"https://github.com/openillink-project\" target=\"_blank\">OpeniLLink-project</a><br />";

$copyrightmessage["fr"] = "&copy; <a href=\"http://www.pablog.ch\" target=\"_blank\">Pablo Iriarte</a>,  <a href=\"http://jankrause.net\" target=\"_blank\">Jan Krause</a>, <a href=\"http://www.bium.ch\" target=\"_blank\">BiUM</a>/<a href=\"http://www.chuv.ch\">CHUV</a> Lausanne, <a href=\"http://www.unige.ch/medecine/bibliotheque/\" target=\"_blank\">BFM</a>, <a href=\"http://www.unige.ch\" target=\"_blank\">UNIGE</a> Genève";

$openIllinkOfficialTitle["fr"]="Openillink v2";
$openIllinkOfficialTitle["en"]="Openillink v2";
$openIllinkOfficialTitle["de"]="Openillink v2";
$openIllinkOfficialTitle["it"]="Openillink v2";
$openIllinkOfficialTitle["es"]="Openillink v2";

$guiLabelName1["fr"]="Nom en français";
$guiLabelName1["en"]="Name in French";
$guiLabelName1["de"]="Name auf Französisch";
$guiLabelName1["it"]="Nome in francese";
$guiLabelName1["es"]="Name in French";

$guiLabelName2["fr"]="Nom en anglais";
$guiLabelName2["en"]="Name in English";
$guiLabelName2["de"]="Name auf Englisch";
$guiLabelName2["it"]="Nome in inglese";
$guiLabelName2["es"]="Name in English";

$guiLabelName3["fr"]="Nom en allemand";
$guiLabelName3["en"]="Name in German";
$guiLabelName3["de"]="Name auf Deutsch";
$guiLabelName3["it"]="Nome in tedesco";
$guiLabelName3["es"]="Name in German";

$guiLabelName4["fr"]="Nom en italien";
$guiLabelName4["en"]="Name in Italian";
$guiLabelName4["de"]="Name auf Italienisch";
$guiLabelName4["it"]="Nome in italiano";
$guiLabelName4["es"]="Name in Italian";

$guiLabelName5["fr"]="Nom en espagnol";
$guiLabelName5["en"]="Name in Spanish";
$guiLabelName5["de"]="Name auf Spanisch";
$guiLabelName5["it"]="Nome in spagnolo";
$guiLabelName5["es"]="Name in Spanish";

$guiLabelHelp1["fr"]="Aide en français";
$guiLabelHelp1["en"]="Help in French";
$guiLabelHelp1["de"]="Hilfe auf Französisch";
$guiLabelHelp1["it"]="Aiuto in francese";
$guiLabelHelp1["es"]="Help in French";

$guiLabelHelp2["fr"]="Aide en anglais";
$guiLabelHelp2["en"]="Help in English";
$guiLabelHelp2["de"]="Hilfe auf Englisch";
$guiLabelHelp2["it"]="Aiuto in inglese";
$guiLabelHelp2["es"]="Help in English";

$guiLabelHelp3["fr"]="Aide en allemand";
$guiLabelHelp3["en"]="Help in German";
$guiLabelHelp3["de"]="Hilfe auf Deutsch";
$guiLabelHelp3["it"]="Aiuto in tedesco";
$guiLabelHelp3["es"]="Help in German";

$guiLabelHelp4["fr"]="Aide en italien";
$guiLabelHelp4["en"]="Help in Italian";
$guiLabelHelp4["de"]="Hilfe auf Italienisch";
$guiLabelHelp4["it"]="Aiuto in italiano";
$guiLabelHelp4["es"]="Help in Italian";

$guiLabelHelp5["fr"]="Aide en espagnol";
$guiLabelHelp5["en"]="Help in Spanish";
$guiLabelHelp5["de"]="Hilfe auf Spanisch";
$guiLabelHelp5["it"]="Aiuto in spagnolo";
$guiLabelHelp5["es"]="Help in Spanish";

$guiLibrarySignature["fr"]="Signature (pour les emails envoyés à l'utilisateur)";
$guiLibrarySignature["en"]="Signature (for emails sent to users)";
$guiLibrarySignature["de"]="Signature (for emails sent to users)";
$guiLibrarySignature["it"]="Signature (for emails sent to users)";
$guiLibrarySignature["es"]="Signature (for emails sent to users)";

$guiFolderIn["fr"]="Afficher les commandes avec ce statut dans la liste des commandes en entrée (IN)";
$guiFolderIn["en"]="Display orders with this status in the IN listing";
$guiFolderIn["de"]="Display orders with this status in the IN listing";
$guiFolderIn["it"]="Display orders with this status in the IN listing";
$guiFolderIn["es"]="Display orders with this status in the IN listing";

$guiFolderOut["fr"]="Afficher les commandes avec ce statut dans la liste des commandes sortantes (OUT)";
$guiFolderOut["en"]="Display orders with this status in the OUT listing";
$guiFolderOut["de"]="Display orders with this status in the OUT listing";
$guiFolderOut["it"]="Display orders with this status in the OUT listing";
$guiFolderOut["es"]="Display orders with this status in the OUT listing";

$guiFolderTrash["fr"]="Afficher les commandes avec ce statut dans la liste des commandes effacées (TRASH)";
$guiFolderTrash["en"]="Display orders with this status in the TRASH listing";
$guiFolderTrash["de"]="Display orders with this status in the TRASH listing";
$guiFolderTrash["it"]="Display orders with this status in the TRASH listing";
$guiFolderTrash["es"]="Display orders with this status in the TRASH listing";

$guiStatusSpecial["fr"] = "Marquer ce statut avec un label spécifique";
$guiStatusSpecial["en"] = "Add special status flag";
$guiStatusSpecial["de"] = "Add special status flag";
$guiStatusSpecial["it"] = "Add special status flag";
$guiStatusSpecial["es"] = "Add special status flag";

$guiColor["fr"] = "Couleur (indiquer une valeur valide pour une feuille de style .CSS)";
$guiColor["en"] = "Color (.CSS valid value is expected)";
$guiColor["de"] = "Color (.CSS valid value is expected)";
$guiColor["it"] = "Color (.CSS valid value is expected)";
$guiColor["es"] = "Color (.CSS valid value is expected)";

$guiListing["fr"] = "Afficher dans les listes";
$guiListing["en"] = "Display in listing";
$guiListing["de"] = "Display in listing";
$guiListing["it"] = "Display in listing";
$guiListing["es"] = "Display in listing";

$guiDepartment["fr"] = "Département";
$guiDepartment["en"] = "Department";
$guiDepartment["de"] = "Department";
$guiDepartment["it"] = "Department";
$guiDepartment["es"] = "Department";

$guiFaculty["fr"] = "Faculté";
$guiFaculty["en"] = "Faculty";
$guiFaculty["de"] = "Faculty";
$guiFaculty["it"] = "Faculty";
$guiFaculty["es"] = "Faculty";

$guiLibrary["fr"] = "Bibliothèque";
$guiLibrary["en"] = "Library";
$guiLibrary["de"] = "Library";
$guiLibrary["it"] = "Library";
$guiLibrary["es"] = "Library";

$guiNeedValidation["fr"] = "À valider";
$guiNeedValidation["en"] = "Need validation";
$guiNeedValidation["de"] = "Need validation";
$guiNeedValidation["it"] = "Need validation";
$guiNeedValidation["es"] = "Need validation";

$guiEdit["fr"] = "Éditer";
$guiEdit["en"] = "Edit";
$guiEdit["de"] = "Edit";
$guiEdit["it"] = "Edit";
$guiEdit["es"] = "Edit";
*/





/* Texts for mail resend (cfr resend_credential.php)*/
/*
$resendPwdTxt["subject"]["fr"] = "OpenILLink récupération de mot de passe";
$resendPwdTxt["subject"]["en"] = "OpenILLink resend password";
$resendPwdTxt["subject"]["de"] = "OpenILLink resend password";
$resendPwdTxt["subject"]["it"] = "OpenILLink resend password";
$resendPwdTxt["subject"]["es"] = "OpenILLink resend password";

$resendPwdTxt["openingTxt"]["fr"] = "Bonjour";
$resendPwdTxt["openingTxt"]["en"] = "To whom it may concern,";
$resendPwdTxt["openingTxt"]["de"] = "To whom it may concern";
$resendPwdTxt["openingTxt"]["it"] = "To whom it may concern";
$resendPwdTxt["openingTxt"]["es"] = "To whom it may concern";

$resendPwdTxt["par1Txt"]["fr"] = "Quelqu’un, probablement vous même, a fait demande du mot de passe pour le compte associé à cette adresse mail pour le système OpenILLink.";
$resendPwdTxt["par1Txt"]["en"] = "Someone, probably you , has requested the password for the OpenILLink system account associated with this email address.";
$resendPwdTxt["par1Txt"]["de"] = "Someone, probably you , has requested the password for the OpenILLink system account associated with this email address.";
$resendPwdTxt["par1Txt"]["it"] = "Someone, probably you , has requested the password for the OpenILLink system account associated with this email address.";
$resendPwdTxt["par1Txt"]["es"] = "Someone, probably you , has requested the password for the OpenILLink system account associated with this email address.";

$resendPwdTxt["par2Txt"]["fr"] = "Merci de faire appel à ce service. Vous pouvez ainsi accéder à la liste de vos commandes de documents.";
$resendPwdTxt["par2Txt"]["en"] = "This account allows you to review list of your document orders.";
$resendPwdTxt["par2Txt"]["de"] = "This account allows you to review list of your document orders.";
$resendPwdTxt["par2Txt"]["it"] = "This account allows you to review list of your document orders.";
$resendPwdTxt["par2Txt"]["es"] = "This account allows you to review list of your document orders.";

$resendPwdTxt["par3Txt"]["fr"] = "Si vous n'avez pas fait cette demande merci de nous le signaler.";
$resendPwdTxt["par3Txt"]["en"] = "If you have not made ​​that request thank you to let us know.";
$resendPwdTxt["par3Txt"]["de"] = "f you have not made ​​that request thank you to let us know.";
$resendPwdTxt["par3Txt"]["it"] = "f you have not made ​​that request thank you to let us know.";
$resendPwdTxt["par3Txt"]["es"] = "f you have not made ​​that request thank you to let us know.";

$resendPwdTxt["par4Txt"]["fr"] = "Votre mot de passe est: %s";
$resendPwdTxt["par4Txt"]["en"] = "Your password is: %s";
$resendPwdTxt["par4Txt"]["de"] = "Your password is: %s";
$resendPwdTxt["par4Txt"]["it"] = "Your password is: %s";
$resendPwdTxt["par4Txt"]["es"] = "Your password is: %s";

$resendPwdTxt["bibSignature"]["fr"] = "Votre service de Prêt entre bibliothèques.<br/><b>BIBLIOTHÈQUE UNIVERSITAIRE DE MÉDECINE</b> <br/>
CHUV BH 08<br/>
Rue du Bugnon 46<br/>
CH 1011 Lausanne SWITZERLAND <br/>
Courriel : docdelivery@chuv.ch <br/>
Tél. : +41 21 314 52 82 <br/>
Site web : http://www.bium.ch <br/>
";

$resendPwdTxt["bibSignature"]["en"] = "<b>Lib name</b> <br/>
Building name<br/>
Street address<br/>
CAP City Country<br/>
Courriel : mail address for contact <br/>
Tél. : phone number<br/>
Site web : web site url <br/>
";

$resendPwdTxt["bibSignature"]["de"] = "<b>Lib name</b> <br/>
Building name<br/>
Street address<br/>
CAP City Country<br/>
Courriel : mail address for contact <br/>
Tél. : phone number<br/>
Site web : web site url <br/>
";

$resendPwdTxt["bibSignature"]["it"] = "<b>Lib name</b> <br/>
Building name<br/>
Street address<br/>
CAP City Country<br/>
Courriel : mail address for contact <br/>
Tél. : phone number<br/>
Site web : web site url <br/>
";

$resendPwdTxt["bibSignature"]["es"] = "<b>Lib name</b> <br/>
Building name<br/>
Street address<br/>
CAP City Country<br/>
Courriel : mail address for contact <br/>
Tél. : phone number<br/>
Site web : web site url <br/>
";

$resendPwdTxt["emailResendTitle"]["fr"] = "Récupération de mot de passe";
$resendPwdTxt["emailResendTitle"]["en"] = "Password recovery";
$resendPwdTxt["emailResendTitle"]["de"] = "Password recovery";
$resendPwdTxt["emailResendTitle"]["it"] = "Password recovery";
$resendPwdTxt["emailResendTitle"]["es"] = "Password recovery";

$searchForm["ordersSubmittedByMe"]["fr"] = "Commandes saisies par moi uniquement";
$searchForm["ordersSubmittedByMe"]["en"] = "Orders submitted by me only";
$searchForm["ordersSubmittedByMe"]["de"] = "Orders submitted by me only";
$searchForm["ordersSubmittedByMe"]["it"] = "Orders submitted by me only";
$searchForm["ordersSubmittedByMe"]["es"] = "Orders submitted by me only";
*/
?>
