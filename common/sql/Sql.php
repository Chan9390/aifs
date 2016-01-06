<?php

class SQL_Class {

    var $host;
    var $user;
    var $passwd;
    var $db_name;
    var $dbh;
    var $db;

    function SQL_Class($db = 'main') {
        $this->host = "localhost";

        switch($db) {
            
            case 'main': case 'aifs':

                $this->user = "aifs";
                $this->passwd = "";
                $this->db_name = "aifs";
            
            break;

        }

        if (!$this->dbh = mysql_connect($this->host, $this->user, $this->passwd))
            die("Can't open sql connection on host<br />");
        if (!mysql_select_db($this->db_name, $this->dbh))
            die("Can't select database on host<br />");
        //mysql_query("SET CHARSET utf8"); 
    }

    function execute($query) {
        if (!$this->dbh)
            die("Can't execute query without connection<br>");

        $ret = mysql_query($query, $this->dbh);

        if (!$ret) {
        
            //mail("monitoring@domain.com", "SQL Error", $query.'\r\n'.$_SERVER['PHP_SELF']);
            die("We are unable to execute your request.");
            //die("Can't send query to db<br>".$query); // Debug case
        }
        else if (!is_resource($ret)) {
            return TRUE;
        }
        else {
            $stmt = new SQL_Statement($this->dbh, $query);
            $stmt->result = $ret;
            return $stmt;
        }
    }
}
