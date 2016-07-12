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

    if(!isset($link)) {
        $link = mysqli_connect(DB_HOST, DB_USER, DB_PWD, DB_NAME);
    }
    if($link === false) {
        // Handle error - notify administrator, log to a file, show an error screen, etc.
        return mysqli_connect_error(); 
    }
    mysqli_set_charset($link, 'utf8');

    return $link;
}

function dbauthconnect() {
    // Keep a single connection
    static $link; 

    if(!isset($link)) {
        $link = mysqli_connect(DB_AUTH_HOST, DB_AUTH_USER, DB_AUTH_PWD, DB_AUTH_NAME);
    }
    if($link === false) {
        // Handle error - notify administrator, log to a file, show an error screen, etc.
        return mysqli_connect_error(); 
    }
    mysqli_set_charset($link, 'utf8');

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
    $sql_stmt = mysqli_prepare($link, $sql_string);
    if($sql_stmt === false) {
        trigger_error('Wrong SQL: ' . $sql_string . ' Error: ' . $link->errno . ' ' . $link->error, E_USER_ERROR);
    }
    if ($types != "") {
        call_user_func_array('mysqli_stmt_bind_param', array_merge (array($sql_stmt, $types), refValues($params)));
    }
    $success = mysqli_stmt_execute($sql_stmt);
    if (!$success){
        mysqli_stmt_close($sql_stmt);
        return FALSE;
    }
    // check if we need to run mysqli_stmt_get_result
    $exploded_query = explode(" ", ltrim(strtoupper($sql_string)));
    $mysql_action = $exploded_query[0];
    if ($mysql_action == "INSERT") {
        $res = mysqli_insert_id($link);
    } else if ($mysql_action == "DELETE" || $mysql_action == "UPDATE") {
        $res = $success;
    } else {
        $res = iimysqli_stmt_get_result($sql_stmt);
    }
    if($debugLog){
        echo mysqli_affected_rows ($link);
        echo mysqli_info ($link);
    }
    //mysqli_stmt_close($sql_stmt);   // FIXME?
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
    $metadata = mysqli_stmt_result_metadata($stmt);
    $ret = new iimysqli_result;
    if (!$ret) return NULL;

    $ret->ncols = mysqli_num_fields($metadata);
    $stmt->store_result();
    $ret->stmt = $stmt;

    mysqli_free_result($metadata);
    return $ret;
}

function iimysqli_result_fetch_array(&$result) {
    /*
        Helper function to replace native mysqli_fetch_array() when iimysqli_stmt_get_result() is not available.
    */
      if (!is_a($result, "iimysqli_result")) {
        return mysqli_fetch_array($result);
      }
      $stmt = $result->stmt;
      //$stmt->store_result();
      /*$resultkeys = array();
      $thisName = "";
      for ( $i = 0; $i < $stmt->num_rows; $i++ ) {
            $metadata = $stmt->result_metadata();
            while ( $field = $metadata->fetch_field() ) {
                $thisName = $field->name;
                $resultkeys[] = $thisName;
            }
      }*/
      $resultkeys = array();
      $thisName = "";
      $metadata = $stmt->result_metadata();
      //echo var_dump($metadata);
      while ( $field = $metadata->fetch_field() ) {
        $thisName = $field->name;
        $resultkeys[] = $thisName;
      }

      $ret = array();

      $code = "return mysqli_stmt_bind_result(\$result->stmt ";
      //echo "\nNcols:".$result->ncols;
      //echo "\nnum_rows".$stmt->num_rows;
      //echo "\n".var_dump($resultkeys);
      for ($i=0; $i<$result->ncols; $i++) {
          //$ret[$i] = NULL; // TODO ! check with JZ when this statement is usefull
          $theValue = $resultkeys[$i];
          $code .= ", \$ret['$theValue']";
      }

      $code .= ");";
      //echo "\ncode:".$code;
      if (!eval($code)) { 
        return NULL; 
      }

      // This should advance the "$stmt" cursor.
      if (!mysqli_stmt_fetch($result->stmt)) { 
        return NULL; 
      }

      // Return the array we built.
      return $ret;
    }
    
function iimysqli_num_rows(&$result)
{
    /*
        Helper function to replace native mysqli_num_rows() when iimysqli_stmt_get_result() is not available.
    */
    //echo var_dump($result);
    //echo var_dump($result->stmt);
    if (!is_a($result, "iimysqli_result")) {
        return $result->num_rows;
    }
    //echo var_dump($result->stmt);
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
