<?php

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
	return mysql_fetch_array($this->result);
  }
  function sql_result() {
     if (!$this->result)
     die("Query not executed<br>");
        return mysql_result($this->result, 0);
  }

}
