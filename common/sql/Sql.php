<?php

namespace Sql;

class Sql {

    var $host;
    var $user;
    var $passwd;
    var $db_name;
    var $dbh;
    var $db;

    function __construct($db = 'aifs') {
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
            die("We are unable to execute your request.".$query);
        }
        // Plz perform instance type here.
        else {
            $stmt = new Statement($this->dbh, $query);
            $stmt->result = $ret;
            return $stmt;
        }
    }
}
