# openLinker
OpenLinker is a web based library system designed to manage lists of electronic resources (journals, e-books, databases, etc.), Inter library loans (ILL), document delivery and OpenURL links

This fork of the project is currently a test

Thanks to Pablo Iriarte for answering all the question and providing base code for this new version.

Thanks to "Bibliothèque de l'Université de Médecine (CMU - Genève 8)"  (http://www.unige.ch/biblio) for providing detailed istructions and code which has been integrated and modified to comply with current project and the library of the faculty of medicine (http://www.bium.ch/) needs.

Project main changes:
*****************************************************************************
New features:
*****************************************************************************
* added reporting and statistics module
* generic translations
* new style defined for tables
* added input validation
* added order flag to allow sorting of link for document search;
* allow condition argument encoding for documents' search links.

*****************************************************************************
Fixes and improvements:
*****************************************************************************

* suppress limit of 200 entries in administrator lists to allow edition of all existing data;
* reported fix of order information retrieval with wos id;
* refactoring of queries into search.php and order_list.php to improve performances;
* improved order selection to include orders which are bound to services related to a library;
* added empty localization to avoid wrong assignation of localization to new orders.

*****************************************************************************
Code quality
*****************************************************************************

* refactoring of several files (detail.hp, edit.php, forms.php, ...); this include avoiding require whenever possible and favor require_once; 
* refactoring of file email.php: texts are now isolated at the beginning and more separate from page generation; mail is created by calling a function and no longer by simply including the file;
* added toolkit.php to deal with placeholder with a specific function;
* added interface to use mysqli connector instead of the default one (to conform with quality guidelines), connection is performed using an object oriented style;
* improvement of forms (basel, nlm) to submit request of document to external library;
* added $debugOn flag reading from configuration to headeradmin.php file; debug display is performed into div into footer.php;
* added function to retrieve states from status table.

*****************************************************************************
