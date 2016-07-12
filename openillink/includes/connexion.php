<?php
require_once 'constantes.php';
/**
* Original code from Jérôme Zbinden (version 11.03.2016 from perunil)
* 11.03.2016 MDV added debug flag
* 17.03.2016 MDV improvement of error output
*/

function dbconnect() {
    // Keep a single connection
    static $link; 
    // check connection
    if(!isset($link)) {
        $link = new mysqli(DB_HOST, DB_USER, DB_PWD, DB_NAME);
    }

    if ($link->connect_error) {
       trigger_error('Database connection failed: '  . $link->connect_error, E_USER_ERROR);
    }
    $link->set_charset("utf8");
    return $link;
}

function dbauthconnect() {
    // Keep a single connection
    static $link; 

    if(!isset($link)) {
        $link = new mysqli(DB_AUTH_HOST, DB_AUTH_USER, DB_AUTH_PWD, DB_AUTH_NAME);
    }
    if ($link->connect_error) {
       trigger_error('Database connection failed: '  . $link->connect_error, E_USER_ERROR);
    }
    $link->set_charset("utf8");
    return $link;
}

function dbquery($sql_string, $params=NULL, $types=NULL, $dblink=NULL, $debugLog = false) {
    /* Execute the given $sql_string with properly encoded $params, on the given $dblink.

    @param string $sql_string: the SQL statement to execute, with '?' for parameters to replace with $params.
    @param array $params: array of (ordered) parameters to replace in $sql_string.
    @param string $types: string representing the type of each parameters in $params.
    @param mysqli $dblink: link to the database.
    
    When $params and $types are not provided, it is assumed that no parameters are necessary.
    When $dblink is not provided, a link to the default database is created.
    
    Returns the result of the query. 
    In case of INSERT, returns the last inserted ID.
    In case of DELETE or UPDATE, returns a boolean indicating the success of operation.
    In case of SELECT, returns the result of the query, or FALSE if an error occurred. 
    */
    $link = $dblink;
    if (is_null($dblink)) {
        $link = dbconnect();
    }
    if (is_null($params)) {
        $params = array();
    }
    if (is_null($types)) {
        $types = "";
    }
    if ($debugLog)
        echo $sql_string;
    $sql_stmt = $link->prepare($sql_string);
    if ($debugLog)
    if($sql_stmt === false) {
        trigger_error('Wrong SQL: ' . $sql_string . ' Error: ' . $link->errno . ' ' . $link->error, E_USER_ERROR);
    }
    if ($types != "") {
        array_unshift($params, $types);  
        $myCallbackRes = call_user_func_array(array($sql_stmt, 'bind_param'), refValues($params));
    }
    $success = $sql_stmt->execute();
    if (!$success){
        $sql_stmt->close();
        return FALSE;
    }
    // check if we need to run mysqli_stmt_get_result
    $exploded_query = explode(" ", ltrim(strtoupper($sql_string)));
    $mysql_action = $exploded_query[0];
    if ($mysql_action == "INSERT") {
        $res = $link->insert_id;
    } else if ($mysql_action == "DELETE" || $mysql_action == "UPDATE") {
        $res = $success;
    } else {
        $res = iimysqli_stmt_get_result($sql_stmt);
    }
    if($debugLog){ 
        echo "affected_rows:".$res->stmt->affected_rows.";<br/>";
        echo "info:".$link->info.";<br/>";
    }

    return $res;
}
class iimysqli_result
{
    public $stmt, $ncols;
}    

function iimysqli_stmt_get_result($stmt) {
    /*
        Helper function to abstract native mysqli_stmt_get_result which might not be available when mysqlnd is not installed.
        If installed, this function could simply call it instead.
    */
    $metadata = $stmt->result_metadata();
    $ret = new iimysqli_result();
    if (!$ret) return NULL;
    $ret->ncols = $stmt->field_count;
    $stmt->store_result();
    $ret->stmt = $stmt;

    $metadata->free_result();
    return $ret;
}

function iimysqli_result_fetch_array(&$result) {
    /*
        Helper function to replace native mysqli_fetch_array() when iimysqli_stmt_get_result() is not available.
    */
    if (!is_a($result, "iimysqli_result")) {
        return $result->fetch_array();
    }
    $stmt = $result->stmt;
    
    $stmt->store_result();
    $resultkeys = array();
    $thisName = "";
    $meta = $result->stmt->result_metadata();

    $ret = array();

    while( $field = $meta->fetch_field()){
        $variables[$field->name] = &$ret[$field->name]; // pass by reference
    }

    call_user_func_array(array($stmt, 'bind_result'), refValues($variables));
    // This should advance the "$stmt" cursor.
    if (!$stmt->fetch ()) { 
        //mysqli_stmt_close($sql_stmt);   // FIXME?
        //$stmt->close();
        return NULL; 
    }
    // Return the array we built.
    return $variables;
}

function iimysqli_num_rows(&$result)
{
    /*
        Helper function to replace native mysqli_num_rows() when iimysqli_stmt_get_result() is not available.
    */
    if (!is_a($result, "iimysqli_result")) {
        return $result->num_rows;
    }
    return $result->stmt->num_rows;
}

function refValues($arr){ 
    /* Helper function to prepare an array of references */
    if (strnatcmp(phpversion(),'5.3') >= 0) //Reference is required for PHP 5.3+ 
    {
        $refs = array(); 
        foreach($arr as $key => $value) 
            $refs[$key] = &$arr[$key]; 
        return $refs; 
    } 
    return $arr; 
} 


?>
