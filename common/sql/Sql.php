<?php

namespace Sql;

use Component\Response;

class Sql {

    var $host;
    var $user;
    var $passwd;
    var $db_name;
    var $dbh;
    var $db;

    var $resp;

    function __construct($db = 'aifs') {
        $this->host = "127.0.0.1";

        switch($db) {
            
            case 'main': case 'aifs': default:
                $this->user = "aifs";
                $this->passwd = "";
                $this->db_name = "aifs";
            break;

        }

        $this->resp = new Response();
        $resp = $this->resp;
        if (!$this->dbh = mysqli_connect($this->host, $this->user, $this->passwd))
            $resp->error('500001', 'Can not open sql connection on host.');
            
        if (!mysqli_select_db($this->dbh, $this->db_name))
            $resp->error('500002', 'Impossible to select database on host.');

    }

    function execute($query) {
        $resp = $this->resp;
        if (!$this->dbh) {
            $resp->error('500003', 'Cannot execute query without connection.');
        }
        $ret = mysqli_query($this->dbh, $query);
        if (!$ret) {
            $resp->error('500004', 'We are unable to execute your request.');
            // perform error with log level.
        }
        // perform instance type check here.
        else {
            $stmt = new Statement($this->dbh, $query, $resp);
            $stmt->result = $ret;
            return $stmt;
        }
    }
}
