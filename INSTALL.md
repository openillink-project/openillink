# OpenILLink Installation Guide

## Install
Here are the main steps to install and configure OpenILLink:
 
1. Download the OpenILLink files from the GitHub repository 
 
2. Create a MySQL database and import the file `data/openillink_structure.sql`.
   It is recommended to initially import default users, configuration and test data:
    - openillink_data_libraries.sql
    - openillink_data_users.sql
    - openillink_data_units.sql
    - openillink_data_localizations.sql
    - openillink_data_links.sql
    - openillink_data_folders.sql
    - openillink_data_status.sql
    - openillink_data_orders.sql

3. Edit the file `includes/config.php` to change parameters to your MySQL database: 
    - `$configmysqldb` = database name
    - `$configmysqlhost` = hostname
    - `$configmysqllogin` = username (with read/write access to configured `$configmysqldb`)
    - `$configmysqlpwd` = password
 
4. Configure other variables in `includes/config.php` with your own settings (name of the library, address, e-mail, security codes, IP addresses, etc.) If you don't want to use some parameters (second IP range for example) you can leave those values empty (read each config description for details)

5. Optionnally, adapt the (S)CSS to customize the look of the user interface. OpenILLink uses the [Bulma framework](https://bulma.io). See https://github.com/openillink-project/openillink-bulma/wiki for more information

5. Copy all files and directories to your server (excpted the `data` directory, only needed to build the database)
 
## Log In
You can connect to the admin interface with these default credentials, if you have imported them from openillink_data_users.sql :
 
* Superadmin
  * Login : sadmin
  * Pwd : sadmin
 
* Administrator
  * Login : admin
  * Pwd : admin
 
* User (staff collab.)
  * Login : user
  * Pwd : user
 
Those credentials allow you to test the interface. Before deploying to a production site you must change all logins and passwords.
 
Note that only the "superadmin" can create admins. The "user" login allows you to work with orders and modify them but not to delete them. Administrators can add/modify users (other than administrators or superadministratos). The administrators can "see" only the orders of their own library. Superadmins could "see" all the orders of the database.

## Configure

Administrators and superadministrators have access to the following areas:
 
1. **Libraries** : The different libraries of your network. The system can manage a library network where each library "see" only their orders and could "send" orders to others, but you can also use a single library if you want.
2. **Locations** : The different localizations for the documents of each library.
3. **Users** : The users of the back office (library professionals). You don't need to manage end users ("customers").
4. **Units / Groups** : The different units listed on the menu of the order form. They are linked to the existing libraries and to the IP ranges, so you can choose to display some units only for the users of your network and others for the external users. If you check the box "Need validation", then the orders of the customers of this unit are created with an order status set to "tobevalidated" rather than "new order".
5. **Order steps (status)** : The different status of the orders that you want to differentiate. There are no limits but you will find 5 "special status" with the important values that I recommend to not be removed or reused. Those status are important because they are used by other pieces of the system. Each status determines the folder where the orders could be found : IN (new orders addressed to my library by users or the others libraries), OUT (orders pushed to suppliers or to the others libraries) and TRASH (to be deleted). Two exceptions : the "rejected" orders are showed always on the IN folder of the both libraries requester and responder, and the status "to be renewed" disappears from the IN folder and appears only when the renewal date comes.
6. **Outgoing links** : The different links displayed on the details of the order. They are used to search external databases and to submit the order into external document delivery systems. In some case you must modify the default links with your own codes in places marked by "[]" (codes for Subito for example). You can create your own links easily using this codes (near to the OpenURL 0.1) that will be replaced contextually with the values of the order displayed :
    * doi : `XDOIX`
    * pmid (PubMed identifier) : `XPMIDX`
    * genre (Document Type) : `XGENREX`
    * aulast (Authors names) : `XAULASTX`
    * issn : `XISSNX`
    * eissn : `XEISSNX`
    * isbn : `XISBNX`
    * title (Journal name) : `XTITLEX`
    * atitle (Article/chapter title) : `XATITLEX`
    * volume : `XVOLUMEX`
    * issue : `XISSUEX`
    * pages : `XPAGESX`
    * date : `XDATEX`
    * end user name : `XNAMEX`
    
    Sometimes the external order forms requires data to be submitted via POST methods and they don't allow the GET equivalent. For those cases you have to recreate the whole form to imitate the POST request. You can find such sample insiide the `forms` directory. Those forms must be explicitely enabled with config `$config_enabled_internal_order_forms`

7. **Filters** : (*experimental*) manage "folders" available for users in the top menu. For the moment, you might need to set up the queries diretly in the database.
8. **Anonymize old orders** : remove personal information from old orders