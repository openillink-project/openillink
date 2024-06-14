<?php
// ***************************************************************************
// ***************************************************************************
// ***************************************************************************
// This file is part of OpenILLink software.
// Copyright (C) 2005, 2006, 2007, 2008, 2009, 2010, 2011, 2012, 2013, 2015, 2016, 2017, 2018, 2019, 2020, 2023, 2024 CHUV.
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
// essential parameters

// MySQL access codes
$configmysqldb = "openillink";
$configmysqlhost = "localhost";
$configmysqllogin = "root";
$configmysqlpwd = "root";

// Google Analytics code (leave blank if not applicable)
$configanalytics = "";

// Should user IP address be anonymized in Google Analytics?
$configanalytics_ip_anonymization = true;

// Add logging option: used to trace and analyse problems, allowed levels: DEV, TEST, PROD
// any other value will be ignored
$configdebuglogging = '';

// CSS files
// Customize the look of OpenILLink by defining custom CSS files in OpenILLink
// 'css' directory:
//   * config_css_framework: file containing main styles defined by the CSS
//                           framework (currently Bulma). Update this variable
//                           to your own customized Bulma CSS file.
//   * config_css_main: file containing OpenILLink styles not defined by the
//                      CSS framework. Also contains some workaround for the CSS
//                      framework.
//   * config_css_custom: file containing custom CSS styles that you would like
//                        to add / override. Useful in case you want to keep
//                       'config_css_framework' and 'config_css_main' untouched
//                       but still add some minor tweaks to the UI. There is no
//                       value / file defined by default.
$config_css_framework = 'openillink_bulma.css';
$config_css_main = 'openillink.css';
$config_css_custom = '';

// Site title in the header
$sitetitle["fr"] = "OpenILLink";
$sitetitle["en"] = "OpenILLink";
$sitetitle["de"] = "OpenILLink";
$sitetitle["it"] = "OpenILLink";
$sitetitle["es"] = "OpenILLink";

// Informations about the main library managing the ILL network
$configlibname = "XYZ University Library";
$configlibstreet = "River Street";
$configlibbuilding = "Building XY";
$configlibpostalcode = "1234";
$configlibcity = "Ville QW";
$configlibcowntry = "Pays";
$configlibtel = "+12 34 567 89 01";
$configlibemail = "library@univxyz.com";
$configliburl = "http://www.univxyz.com/library";

// Name of ILL or document delivery manager
$configillmanagername = "John Smith";
$configillmanagerfirstname = "John";
$configillmanagerlastname = "Smith";
$configillmanageremail = "john.smith@univxyz.com";
$configillmanagertel = "+12 34 567 89 01";
$configilllibid = "library ill id";
$configillmaxprice = 10;

// e-mail address used to receive external orders
$configemaildelivery = "docdelivery@univxyz.com";

// Public e-mail address displayed on the description pages and error messages
$configemail = "admin@univxyz.com";

// Administrator e-mail address used to send feedback messages
$configemailto = "admin@univxyz.com";

// IP range of main institution like 123.456.*.*
$configipainst1 = "123";
$configipbinst1 = "456";
$configipcinst1 = "*";
$configipdinst1 = "*";
// IP range of secondary institution like 789.10.*.* (leave blank if not applicable
$configipainst2 = "789";
$configipbinst2 = "10";
$configipcinst2 = "*";
$configipdinst2 = "*";

// Secret string added to secure public password generated from e-mail
$secure_string_guest_login = "HYGWGMII?gsSC9mX0X#&ydfgrZç%&467";

// Secret string added to secure admin level on cookies
$secure_string_cookie = "HYdfhrtznvcw354AETte5üPO!äP236%ç";

// Define a custom secret key used to sign some requests. If not provided (empty string),
// some features are not enabled.
$config_secure_secret_key = "";

// Define minimum duration (unit: seconds) needed by a user to fill in the order form.
// Orders submitted in less than configured seconds will be disregarded, to avoir automated.
// Set to 0 to disable check
$config_min_form_filling_time = 4;

// Define the number of results per page 
$max_results = 25;

// Folders: display items count? Installations with large amount of folders and
// complex queries might want to disable display of number of orders next to
// folders menu items (admin interface)
$config_display_folders_count = true;

// Folders: is folders administration enabled on the web for authorized users?
// * 0: not possible to add, edit or remove folders from the web interface (only read)
// * 1: editing is disabled, but adding/removing folders is enabled
// * 2: editing, adding and removing folders are enabled
$config_folders_web_administration = 1;

// CrossRef identifiers
$configcrossrefpid1 = "abc";
$configcrossrefpid2 = "abc123";
// used for nlm ordering form
$nlmFormUsername = 'NLM_SPECIFIC_USERNAME';
$nlmFormPassword = 'NLM_SPECIFIC_PASSWORD';

// 
$configBaselCode = 'BASEL_SPECIFIC_CODE';
$configBaselPassword = 'BASEL_SPECIFIC_PASSWORD';
$configBaselName = 'BASEL_SPECIFIC_NAME';

// NCBI API key
// Used server-side to retrieve metadata from NCBI Entrez APIs when auto-filling
// via PMID from PubMed. If left empty, default limit apply for the maximum
// number of requests per seconds
// See https://ncbiinsights.ncbi.nlm.nih.gov/2017/11/02/new-api-keys-for-the-e-utilities/
$configNCBIAPIKey = '';

// WoS Lite Rest API key
// Used server-side to retrieve metadata from WoS APIs when auto-filling via WosID.
$configWOSLiteRestAPIKey = '';

// authentication levels
$auth_sadmin = "1";
$auth_admin = "2";
$auth_user = "3";
$auth_guest = "9";

// display link for resending credentials
$displayResendLink = true;

// Mode to display library attributed orders in "IN" folder
// - 0: display all orders attributed to library
// - 1: display only orders attriburted to library when no localization or own localization
$displayAttributedOrderMode = 1;

// Custom SQL constraints to apply to "IN" folder for each specified library.
// This configuration is added at the end of the default SQL constraints that
// defines the content of the "IN" folder. The configuration must therefore be
// a valid SQL instruction that starts with "OR" or "AND".
// The variable is an array indexed by the code of your library, such that it
// can be customized per library.
// For example:
//     $configINFolderCustomConstraints = array('BiUM' => "AND mail like '%@chuv.ch'");
// This is an advanced configuration option that must be carefully thought.
$configINFolderCustomConstraints = array();

// Display "Ref. internal to the library" field on order form if true
$displayFormInternalRefField = true;

// Display "Origin of the order" field on order form if true
$displayFormOrderSourceField = true;

// Define the name and search URL of directories used to make the home page links
// to determine your URLs make a search with firstname "XFIRSTNAMEX" (without quotes) and name "XNAMEX", then copy the URL of the results page
// if your directory allows only POST request, you can create a form imitating the search form and place on the forms folder,
// or you can specify an array that is going to be posted if not empty (directoryurl_post_data1 and directoryurl_post_data2).
$directoryname1 = "Univ. directory";
$directoryurl1 = "http://www.univxyz.com/directory?ln=XNAMEX&fn=XFIRSTNAMEX";
$directoryurl_post_data1 = array();
$directoryname2 = "Hosp. directory";
$directoryurl2 = "http://www.univabc.com/ldap?nom=XNAMEX&prenom=XFIRSTNAMEX";
$directoryurl_post_data2 = array();

// Define URL to periodicals database in order to let user search it from
// the order submission form.
$periodical_title_search_url = "../openlist/search.php?search=simple&q=";

// Define the unique identifiers used on the lookup tool
$lookupuid = array(
			  array('name' => "PMID", 'code' => "pmid"),
			  array('name' => "DOI", 'code' => "doi"),
			  array('name' => "WoS ID", 'code' => "wosid"),
			  //array('name' => "MMS", 'code' => "sru_marcxml_mms"),
              //array('name' => "ISBN", 'code' => "sru_marcxml_isbn"),
              array('name' => "MMS (Renouvaud)", 'code' => "sru_marcxml_mms"),
              array('name' => "ISBN (Renouvaud+SLSP)", 'code' => "sru_marcxml_isbn"),
			);


// URL to sru server used to autofill orders from an ISBN.
// Server must return MARCXML metadata. 
// Keyword '_OPENILLINK_ISBN_' specified in the url below is replaced with the actual ISBN.
$sru_marcxml_isbn_url = "https://eu01.alma.exlibrisgroup.com/view/sru/41BCULAUSA_NETWORK?version=1.2&operation=searchRetrieve&recordSchema=marcxml&query=alma.isbn=_OPENILLINK_ISBN_";

// URL to a second sru server used to autofill orders from an ISBN.
// Server must return MARCXML metadata. 
// Keyword '_OPENILLINK_ISBN_' specified in the url below is replaced with the actual ISBN.
$sru_marcxml_isbn_url2 = "https://swisscovery.slsp.ch/view/sru/41SLSP_NETWORK?version=1.2&operation=searchRetrieve&recordSchema=marcxml&query=alma.isbn=_OPENILLINK_ISBN_";


// URL to sru server used to autofill orders from an MMS.
// Server must return MARCXML metadata. 
// Keyword '_OPENILLINK_MMS_' specified in the url below is replaced with the actual MMS.
$sru_marcxml_mms_url = "https://eu01.alma.exlibrisgroup.com/view/sru/41BCULAUSA_NETWORK?version=1.2&operation=searchRetrieve&recordSchema=marcxml&query=alma.mms_id=_OPENILLINK_MMS_"; 

// Enable the upload of orders files (in EndNote XML, RIS or MEDLINE/PubMed format).
// Define the minimum privilege required to upload file:
// "", "guest", "user", "admin" "sadmin"
// (Leave empty string to prevent anyone from uploading order files
$enableOrdersUploadForUser = "guest";

// Maximum number of simultaneous orders that can be submitted at once.
// Consider variable "max_input_vars" in your php.ini which limits how many
// form elements can be submitted at once. Each OpenILLink order lines needs 
// 17 variables, in addition to 39 default elements. To compute the maximum value 
// you can set for $maxSimultaneousOrders = (max_input_vars - 39 ) / 17
// See also file upload limitations defined in the `upload_max_filesize` and 
// `post_max_size ` variables in the php.ini file on your server.
// In order to be compatible with the NLM/PubMed Document Delivery Service
// (https://www.ncbi.nlm.nih.gov/books/NBK3803/#related.Document_Delivery_Service_DDS)
// this value must be set at least 100.
$maxSimultaneousOrders = 50;

// OpenURL parameters
$openurlsid = "OpenILLink:DemoDB";

// Autodetect language from browser settings (0 inactive, 1 active)
$langautodetect = 0;

// Default language when it cannot be determined or does not exist among OpenILLink translations
$configdefaultlang = "en";

// Languages available for the user interface.
// If not defined, use all existing translations.
// If only one language left, the language switcher is hidden.
$config_available_langs = array("fr", "en", "de", "it", "es");

// See the others values on tranlations.php
require_once("translations.php");

// Define the document types (based on OpenURL spec)
$doctypes = array(
			  array('name' => __("Article"), 'code' => "article"),
			  array('name' => __("Preprint"), 'code' => "preprint"),
			  array('name' => __("Book"), 'code' => "book"),
			  array('name' => __("Book chapter"), 'code' => "bookitem"),
			  array('name' => __("Thesis"), 'code' => "thesis"),
			  array('name' => __("Journal issue"), 'code' => "journal"),
			  array('name' => __("Conference proceedings"), 'code' => "proceeding"),
			  array('name' => __("Conference paper"), 'code' => "conference"),
			  array('name' => __("Other"), 'code' => "other"),
		  );

// Is search in list of units enabled? Note that enabling this option will 
// add dependency on jQuery.
$config_units_search_enabled = 1;

// Is choice for delivery option (pickup / delivery) displayed on order form?
$config_display_delivery_choice = true;

// Are CGRA/B fields displayed on order form?
$config_display_cgr_fields = false;

// List order form names that are enabled (from "forms" subdirectory) for outgoing links.
// Eg: array ("basel", "rpvz");
$config_enabled_internal_order_forms = array ();

// Shibboleth authentication (0 inactive, 1 active)
$config_shibboleth_enabled = 0;

// Shibboleth login url including entityID, return URL and target (redirection to the login.php)
$config_shibboleth_login_url = "https://wayf.www.univxyz.com/shibboleth/WAYF?entityID=https%3A%2F%2Fwww.univxyz.com%2Fshibboleth&return=http%3A%2F%2Fwww.univxyz.com%2FShibboleth.sso%2FDS%3FSAMLDS%3D1%26target%3Dhttp%3A%2F%2Fwww.univxyz.com%2Flogin.php%26action%3Dshibboleth";

// Shibboleth logout url. You might want to ensure that the logout URL has a way
// to specify a return URL (eg. via 'return' parameter) that you can include in 
// the configuration variable below to redirect to this OpenILLink server. To help
// with this, any keyword '_OPENILLINK_RETURN_URL_' within the configuration below
// will be replaced with an adequate return URL
$config_shibboleth_logout_url = "";

// Shibboleth email attribute provided by the service provider. OpenILLink expects
// to retrieve the email of the logged in user in the corresponding attribute
// of this web server environement. Email is then matched with existing OpenILLink 
// accounts in OpenILLink. When no corresponding account exists, the user is 
// considered as guest.
$config_shibboleth_email_attr = "mail";

// Description displayed after the institutional login link. This gives a chance
// to clarify the meaning of the "institutional login", for eg. by providing
// the advertised name for SSO institution-wide, an icon, etc. The content is
// of this variable is not HTML-escaped.
$config_shibboleth_login_description["fr"] = "";
$config_shibboleth_login_description["en"] = "";
$config_shibboleth_login_description["de"] = "";
$config_shibboleth_login_description["it"] = "";
$config_shibboleth_login_description["es"] = "";

// Currency

$currency = "CHF";

// Name of the system displayed in the main menu bar and on title HTML tag
$configname["fr"] = "OpenILLink : commande de documents à la Bibliothèque XYZ";
$configname["en"] = "OpenILLink : Orders to the Library XYZ";
$configname["de"] = "OpenILLink : Orders to the Library XYZ";
$configname["it"] = "OpenILLink : Orders to the Library XYZ";
$configname["es"] = "OpenILLink : Orders to the Library XYZ";

// Main institution name
$configinstitution["fr"] = "Bibliothèque universitaire de XYZ";
$configinstitution["en"] = "University Library of XYZ";
$configinstitution["de"] = "Universitätsbibliothek XYZ";
$configinstitution["it"] = "Biblioteca universitaria XYZ";
$configinstitution["es"] = "Biblioteca de la Universidad XYZ";

// Secondary institution name
$configinstitution2["fr"] = "Université de XYZ";
$configinstitution2["en"] = "University of XYZ";
$configinstitution2["de"] = "Universität XYZ";
$configinstitution2["it"] = "Università XYZ";
$configinstitution2["es"] = "Universidad XYZ";

// Library name
$configlibrary["fr"] = "Faculté de XYZ"; // MDV - CONFIG : title text displayed for a new order (08.12.2015)
$configlibrary["en"] = "Faculty of XYZ";
$configlibrary["de"] = "Fakultät für XYZ";
$configlibrary["it"] = "Facoltà XYZ";
$configlibrary["es"] = "Facultad XYZ";

// Library address displayed on description pages and messages 
$configadresse["fr"] = "Faculté XYZ\n"; // MDV - CONFIG : title text displayed for a new order (08.12.2015)
$configadresse["fr"] .= "1, Central Park\n"; // MDV - CONFIG : first line of address displayed for a new order (08.12.2015)
$configadresse["fr"] .= "CH-1000 Lausanne\n"; // MDV - CONFIG : zip and town of the address displayed for a new order (08.12.2015)
$configadresse["fr"] .= "Tel. +12 34 567 89 00\n";
$configadresse["fr"] .= "help@library.net\n"; // MDV - CONFIG : valeur affichÃ©e en haut de la page d'une nouvelle commande (08.12.2015)
$configadresse["fr"] .= "http://library.net/\n";

$configadresse["en"] = "Faculty XYZ\n"; // MDV - CONFIG : title text displayed for a new order (08.12.2015)
$configadresse["en"] .= "1, Central Park\n"; // MDV - CONFIG : first line of address displayed for a new order (08.12.2015)
$configadresse["en"] .= "CH-1000 Lausanne\n"; // MDV - CONFIG : zip and town of the address displayed for a new order (08.12.2015)
$configadresse["en"] .= "Tel. +12 34 567 89 00\n";
$configadresse["en"] .= "help@library.net\n"; // MDV - CONFIG : valeur affichÃ©e en haut de la page d'une nouvelle commande (08.12.2015)
$configadresse["en"] .= "http://library.net/\n";

$configadresse["de"] = "Facultät XYZ\n"; // MDV - CONFIG : title text displayed for a new order (08.12.2015)
$configadresse["de"] .= "1, Central Park\n"; // MDV - CONFIG : first line of address displayed for a new order (08.12.2015)
$configadresse["de"] .= "CH-1000 Lausanne\n"; // MDV - CONFIG : zip and town of the address displayed for a new order (08.12.2015)
$configadresse["de"] .= "Tel. +12 34 567 89 00\n";
$configadresse["de"] .= "help@library.net\n"; // MDV - CONFIG : valeur affichÃ©e en haut de la page d'une nouvelle commande (08.12.2015)
$configadresse["de"] .= "http://library.net/\n";

$configadresse["it"] = "Facoltà XYZ\n"; // MDV - CONFIG : title text displayed for a new order (08.12.2015)
$configadresse["it"] .= "1, Central Park\n"; // MDV - CONFIG : first line of address displayed for a new order (08.12.2015)
$configadresse["it"] .= "CH-1000 Lausanne\n"; // MDV - CONFIG : zip and town of the address displayed for a new order (08.12.2015)
$configadresse["it"] .= "Tel. +12 34 567 89 00\n";
$configadresse["it"] .= "help@library.net\n"; // MDV - CONFIG : valeur affichÃ©e en haut de la page d'une nouvelle commande (08.12.2015)
$configadresse["it"] .= "http://library.net/\n";

$configadresse["es"] = "Facultad XYZ\n"; // MDV - CONFIG : title text displayed for a new order (08.12.2015)
$configadresse["es"] .= "1, Central Park\n"; // MDV - CONFIG : first line of address displayed for a new order (08.12.2015)
$configadresse["es"] .= "CH-1000 Lausanne\n"; // MDV - CONFIG : zip and town of the address displayed for a new order (08.12.2015)
$configadresse["es"] .= "Tel. +12 34 567 89 00\n";
$configadresse["es"] .= "help@library.net\n"; // MDV - CONFIG : valeur affichÃ©e en haut de la page d'une nouvelle commande (08.12.2015)
$configadresse["es"] .= "http://library.net/\n";

// URL of the IT help desk
$confighelpdeskurl["fr"] = "http://library.net/contact/";
$confighelpdeskurl["en"] = "http://library.net/contact/";
$confighelpdeskurl["de"] = "http://library.net/contact/";
$confighelpdeskurl["it"] = "http://library.net/contact/";
$confighelpdeskurl["es"] = "http://library.net/contact/";

// Informations about document delivery team
$configteam["fr"] = 'Renseignements (<a href="mailto:help@library.net">help@library.net</a>) - <a href="http://library.net/contact/" target="_blank">Library name</a>';
$configteam["en"] = 'Information (<a href="mailto:help@library.net">help@library.net</a>) - <a href="http://library.net/contact/" target="_blank">Library name</a>';
$configteam["de"] = 'Informationen (<a href="mailto:help@library.net">help@library.net</a>) - <a href="http://library.net/contact/" target="_blank">Library name</a>';
$configteam["it"] = 'Informazioni (<a href="mailto:help@library.net">help@library.net</a>) - <a href="http://library.net/contact/" target="_blank">Library name</a>';
$configteam["es"] = 'Informaciòn (<a href="mailto:help@library.net">help@library.net</a>) - <a href="http://library.net/contact/" target="_blank">Library name</a>';

// Name and URL of AtoZ system
$atozname["fr"] = "AtoZ system";
$atozname["en"] = "AtoZ system";
$atozname["de"] = "AtoZ system";
$atozname["it"] = "AtoZ system";
$atozname["es"] = "AtoZ system";

$atozlinkurl["fr"] = "http://atozlink.net/magazines/";
$atozlinkurl["en"] = "http://atozlink.net/magazines/";
$atozlinkurl["de"] = "http://atozlink.net/magazines/";
$atozlinkurl["it"] = "http://atozlink.net/magazines/";
$atozlinkurl["es"] = "http://atozlink.net/magazines/";

// Library web site URL
$configlibraryurl["fr"] = "http://faculty.net/";
$configlibraryurl["en"] = "http://faculty.net/";
$configlibraryurl["de"] = "http://faculty.net/";
$configlibraryurl["it"] = "http://faculty.net/";
$configlibraryurl["es"] = "http://faculty.net/";

// Library email
$configlibraryemail["fr"] = "illMail@library.net";
$configlibraryemail["en"] = "illMail@library.net";
$configlibraryemail["de"] = "illMail@library.net";
$configlibraryemail["it"] = "illMail@library.net";
$configlibraryemail["es"] = "illMail@library.net";

// Location of discovery tool for record given by MMS ID.
// "{MMS_ID}" will be replaced by actual MMS ID in these URL
$configMMSdiscoveryurl["fr"] = "";
$configMMSdiscoveryurl["en"] = "";
$configMMSdiscoveryurl["de"] = "";
$configMMSdiscoveryurl["it"] = "";
$configMMSdiscoveryurl["es"] = "";

// Optional. Second header line displayed on public+admin order form
$secondmessage["fr"] = "";
$secondmessage["en"] = "";
$secondmessage["de"] = "";
$secondmessage["it"] = "";
$secondmessage["es"] = "";

// Optional. Third header line displayed on public order form
$thirdmessage["fr"] = "<b><font color=\"red\">" . __("Please note, all orders are subject to a financial contribution") . "</font></b><br />" . __("Contact us by email for more information (pricing, billing, etc.)") . " : <a href=\"mailto:" . $configlibraryemail["fr"] . "\">" . $configlibraryemail["fr"] . "</a>\n";
$thirdmessage["en"] = "<b><font color=\"red\">" . __("Please note, all orders are subject to a financial contribution") . "</font></b><br />" . __("Contact us by email for more information (pricing, billing, etc.)") . " : <a href=\"mailto:" . $configlibraryemail["en"] . "\">" . $configlibraryemail["en"] . "</a>\n";
$thirdmessage["de"] = "<b><font color=\"red\">" . __("Please note, all orders are subject to a financial contribution") . "</font></b><br />" . __("Contact us by email for more information (pricing, billing, etc.)") . " : <a href=\"mailto:" . $configlibraryemail["de"] . "\">" . $configlibraryemail["de"] . "</a>\n";
$thirdmessage["it"] = "<b><font color=\"red\">" . __("Please note, all orders are subject to a financial contribution") . "</font></b><br />" . __("Contact us by email for more information (pricing, billing, etc.)") . " : <a href=\"mailto:" . $configlibraryemail["it"] . "\">" . $configlibraryemail["it"] . "</a>\n";
$thirdmessage["es"] = "<b><font color=\"red\">" . __("Please note, all orders are subject to a financial contribution") . "</font></b><br />" . __("Contact us by email for more information (pricing, billing, etc.)") . " : <a href=\"mailto:" . $configlibraryemail["es"] . "\">" . $configlibraryemail["es"] . "</a>\n";

// Enable a banner informing users about the use of cookies?
$config_dataprotection_banner_enable = false;

// URL used (for each language) in the banner (enabled with $config_dataprotection_banner_enable) 
// to link to more legal information (cookies, data privacy, etc.)
$config_dataprotection_banner_legal_information_url["fr"] = "";
$config_dataprotection_banner_legal_information_url["en"] = "";
$config_dataprotection_banner_legal_information_url["de"] = "";
$config_dataprotection_banner_legal_information_url["it"] = "";
$config_dataprotection_banner_legal_information_url["es"] = "";

// Name of the link used (for each language) in the banner (enabled with $config_dataprotection_banner_enable) 
// to provide more legal information (cookies, data privacy, etc.)
$config_dataprotection_banner_legal_information_link_name["fr"] = "Lire les informations l&eacute;gales";
$config_dataprotection_banner_legal_information_link_name["en"] = "Read the legal information";
$config_dataprotection_banner_legal_information_link_name["de"] = "Lesen Sie die rechtlichen Informationen";
$config_dataprotection_banner_legal_information_link_name["it"] = "Leggi le informazioni legali";
$config_dataprotection_banner_legal_information_link_name["es"] = "Leer la información legal";

// Text used (for each language) in the banner (enabled with $config_dataprotection_banner_enable) 
// to provide more legal information (cookies, data privacy, etc.)
$config_dataprotection_banner_message["fr"] = "En poursuivant votre navigation sur ce site, vous acceptez l'utilisation de cookies nous permettant d&rsquo;optimiser votre exp&eacute;rience utilisateur.";
$config_dataprotection_banner_message["en"] = "By continuing your navigation on this site, you accept the use of cookies allowing us to optimize your user experience.";
$config_dataprotection_banner_message["de"] = "Indem Sie Ihre Navigation auf dieser site fortsetzen, akzeptieren Sie die Verwendung von Cookies, um Ihre Benutzererfahrung zu optimieren.";
$config_dataprotection_banner_message["it"] = "Continuando la navigazione sui questo sito, accetti l\'utilizzo di cookie che ci consentono di ottimizzare la tua esperienza utente.";
$config_dataprotection_banner_message["es"] = "Al continuar su navegación en este sitio, acepta el uso de cookies que nos permiten optimizar su experiencia de usuario.";

// Checkbox displayed on order form to collect user consent to process the data
// - value 0: the consent checkbox is not displayed
// - value 1: the consent checkbox is displayed on public (not logged in) / "guest" interface
// - value 2: the consent checkbox is displayed for public (not logged in) / "guest" interface as well as for other logged in users
$config_dataprotection_consent_mode = 1;
// URL to the legal information provided in consent checkbox label
$config_dataprotection_consent_legal_information_url["fr"] = "";
$config_dataprotection_consent_legal_information_url["en"] = "";
$config_dataprotection_consent_legal_information_url["de"] = "";
$config_dataprotection_consent_legal_information_url["it"] = "";
$config_dataprotection_consent_legal_information_url["es"] = "";
// URL to the service conditions of use provided in consent checkbox label, if any
$config_dataprotection_consent_conditionsofuse_url["fr"] = "";
$config_dataprotection_consent_conditionsofuse_url["en"] = "";
$config_dataprotection_consent_conditionsofuse_url["de"] = "";
$config_dataprotection_consent_conditionsofuse_url["it"] = "";
$config_dataprotection_consent_conditionsofuse_url["es"] = "";
// Current version of the consent. This value can be anything that allows to
// unambiguously store in the database what was consented by a user when
// ordering a document. This could for eg. be the last modification date of the
// current privacy policy document shown to the users. Max 255 chars
$config_dataprotection_consent_version = "v1";
// How many accounting years data should be kept before being anonymized?
// Set to -1 to prevent anonymization
$config_dataprotection_retention_policy = 10;

// Customize email message sent to users
$emailTxt['fr']['start'] = __("Hello,"). "\r\n\r\n". __("thank you for your order:");
$emailTxt['en']['start'] = __("Hello,"). "\r\n\r\n". __("thank you for your order:");
$emailTxt['de']['start'] = __("Hello,"). "\r\n\r\n". __("thank you for your order:");
$emailTxt['it']['start'] = __("Hello,"). "\r\n\r\n". __("thank you for your order:");
$emailTxt['es']['start'] = __("Hello,"). "\r\n\r\n". __("thank you for your order:");

$emailTxt['fr']['infoservice'] = __("Check the current status of your orders online at:");
$emailTxt['en']['infoservice'] = __("Check the current status of your orders online at:");
$emailTxt['de']['infoservice'] = __("Check the current status of your orders online at:");
$emailTxt['it']['infoservice'] = __("Check the current status of your orders online at:");
$emailTxt['es']['infoservice'] = __("Check the current status of your orders online at:");

$emailTxt['fr']['copyrightWarning'] = __("In accordance with copyright laws, the attached document is for your own use only, for the sole purpose of scientific research. It cannot be copied or shared.");
$emailTxt['en']['copyrightWarning'] = __("In accordance with copyright laws, the attached document is for your own use only, for the sole purpose of scientific research. It cannot be copied or shared.");
$emailTxt['de']['copyrightWarning'] = __("In accordance with copyright laws, the attached document is for your own use only, for the sole purpose of scientific research. It cannot be copied or shared.");
$emailTxt['it']['copyrightWarning'] = __("In accordance with copyright laws, the attached document is for your own use only, for the sole purpose of scientific research. It cannot be copied or shared.");
$emailTxt['es']['copyrightWarning'] = __("In accordance with copyright laws, the attached document is for your own use only, for the sole purpose of scientific research. It cannot be copied or shared.");

$emailTxt['fr']['greetings'] = __("Best regards");
$emailTxt['en']['greetings'] = __("Best regards");
$emailTxt['de']['greetings'] = __("Best regards");
$emailTxt['it']['greetings'] = __("Best regards");
$emailTxt['es']['greetings'] = __("Best regards");

$emailTxt['fr']['signature'] = __("Your Interlibrary loan service.");
$emailTxt['en']['signature'] = __("Your Interlibrary loan service.");
$emailTxt['de']['signature'] = __("Your Interlibrary loan service.");
$emailTxt['it']['signature'] = __("Your Interlibrary loan service.");
$emailTxt['es']['signature'] = __("Your Interlibrary loan service.");

// Base URL to a supported link resolver which will be used to provide immediate
// access to the requested document if available (via subscription, openaccess, etc.)
$config_link_resolver_base_openurl = "";
// Should user IP be forwarded to the link resolver (if available), via "user_ip" URL argument?
// Possible values:
// - null: do not specify any IP
// - "forward": forward end user IP
// - "server": use server IP
// - custom value (IP): forward the provided custom IP (value as string, in single/double quotes)
// - custom value (IP range): this mode forwards the IP of the end user if it starts 
//   with the provided value. Otherwise it forwards a default specified as second value. 
//   For eg. with "123.  123.255.123.255" the IP of the end user is forwarded if it starts with
//   "123.", otherwise the value "123.255.123.255" is forwarded instead
$config_link_resolver_user_ip_forwarding_mode = null;
// Array of custom url parameters to enrich the $config_link_resolver_base_openurl
$config_link_resolver_custom_parameters = array();
// Message displayed when one or several documents have been resolved.
$config_link_resolver_msg_result['fr'] = htmlspecialchars(__("The requested document might be immediately available online here (within the institutional network):"));
$config_link_resolver_msg_result['en'] = htmlspecialchars(__("The requested document might be immediately available online here (within the institutional network):"));
$config_link_resolver_msg_result['de'] = htmlspecialchars(__("The requested document might be immediately available online here (within the institutional network):"));
$config_link_resolver_msg_result['it'] = htmlspecialchars(__("The requested document might be immediately available online here (within the institutional network):"));
$config_link_resolver_msg_result['es'] = htmlspecialchars(__("The requested document might be immediately available online here (within the institutional network):"));

?>
