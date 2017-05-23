<?php

namespace Sql;

class Statement {

    var $result;
    var $query;
    var $dbh;

    function __construct($dbh, $query) {
	$this->query = $query;
	$this->dbh = $dbh;
    }
	
    function fetch_row() {
	if (!$this->result)
            die("Query not executed<br>");
	return mysqli_fetch_row($this->result);
    }
	
    function fetch_assoc() {
	if (!$this->result)
		die("Query not executed<br>");
	return mysqli_fetch_assoc($this->result);
    }
  
    function fetchall_assoc() {
    $retval = array();
    while ($row = $this->fetch_assoc()) {
      $retval[] = $row;
    }
    return $retval;
    }
    function fetch_array() {
    if (!$this->result)
        die("Query not executed<br>");
	return mysqli_fetch_array($this->result);
    }
    function sql_result() {
     if (!$this->result)
     die("Query not executed<br>");
        return $this->mysqli_result($this->result, 0);
    }

    function mysqli_result($result, $row, $field = 0) {
        if ($result===false)
            return false;

        if ($row >= mysqli_num_rows($result))
            return false;

        mysqli_data_seek($result, $row);
        $line = mysqli_fetch_array($result);
        return $line[$field];
    }
}
