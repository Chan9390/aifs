<?php

class SQL_Class {

    var $host;
    var $user;
    var $passwd;
    var $db_name;
    var $dbh;
    var $db;

    function SQL_Class($db = 'aifs') {
        $this->host = "127.0.0.1";

        switch($db) {
            
            case 'main': case 'aifs': default:
                $this->user = "aifs";
                $this->passwd = "";
                $this->db_name = "aifs";
            
            break;

        }

        if (!$this->dbh = mysqli_connect($this->host, $this->user, $this->passwd))
            die("Can't open sql connection on host<br />");
        if (!mysqli_select_db($this->dbh, $this->db_name))
            die("Can't select database on host<br />");

    }

    function execute($query) {
        if (!$this->dbh)
            die("Can't execute query without connection<br>");

        $ret = mysqli_query($this->dbh, $query);
        if (!$ret) {
            die("We are unable to execute your request.");
        }
        else if (!($ret instanceof mysqli_result)) {
            return TRUE;
        }
        else {
            $stmt = new Statement($this->dbh, $query);
            $stmt->result = $ret;
            return $stmt;
        }
    }
}
