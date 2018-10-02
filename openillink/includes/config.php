<?php
// ***************************************************************************
// ***************************************************************************
// ***************************************************************************
// This file is part of OpenILLink software.
// Copyright (C) 2005, 2006, 2007, 2008, 2009, 2010, 2011, 2012, 2013, 2015, 2016, 2017, 2018 CHUV.
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

// Define the number of results per page 
$max_results = 25;

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
// if your directory allows only POST request, you can create a form imitating the search form and place on the forms folder
$directoryname1 = "Univ. directory";
$directoryurl1 = "http://www.univxyz.com/directory?ln=XNAMEX&fn=XFIRSTNAMEX";
$directoryname2 = "Hosp. directory";
$directoryurl2 = "http://www.univabc.com/ldap?nom=XNAMEX&prenom=XFIRSTNAMEX";

// Define URL to periodicals database in order to let user search it from
// the order submission form.
$periodical_title_search_url = "../openlist/search.php?search=simple&q=";

// Define the unique identifiers used on the lookup tool
$lookupuid = array(
			  array('name' => "PMID", 'code' => "pmid"),
			  array('name' => "DOI", 'code' => "doi"),
			  array('name' => "ISBN (RERO)", 'code' => "isbn"),
			  array('name' => "ISBN (swissbib)", 'code' => "isbn_swissbib"),
			  array('name' => "RERO ID", 'code' => "reroid"),
			  array('name' => "WoS ID", 'code' => "wosid"),
			  //array('name' => "Renouvaud MMS", 'code' => "renouvaudmms_swissbib"),
			);

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

// shibboleth authentication (0 inactive, 1 active)
$shibboleth = 0;

// shibboleth url including entityID, return URL and target (redirection to the login.php)
$shibbolethurl = "https://wayf.www.univxyz.com/shibboleth/WAYF?entityID=https%3A%2F%2Fwww.univxyz.com%2Fshibboleth&return=http%3A%2F%2Fwww.univxyz.com%2FShibboleth.sso%2FDS%3FSAMLDS%3D1%26target%3Dhttp%3A%2F%2Fwww.univxyz.com%2Flogin.php%26action%3Dshibboleth";

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

?>
