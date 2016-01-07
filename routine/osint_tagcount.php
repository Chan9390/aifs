<?php

/**
 * AIFS OSINT Async Tag Count
 * Copyright (c) digitaloversight
 */
 
error_reporting(1); 
ini_set('error_reporting', 1);

include_once '../common/sql/Sql.php';
include_once '../common/sql/SqlStatement.php';

$dbh = new SQL_Class('aifs');

$sql = $dbh->execute("SELECT DISTINCT   tag as mytag, 
                                        (select count(*) from osint_tags WHERE tag = mytag) as mycount
                     FROM osint_tags ORDER BY rand() LIMIT 1");

$results = $sql->fetch_assoc();

$sql = $dbh->execute("SELECT count(*) FROM osint_tags_count WHERE tags_name = '".addslashes($results['mytag'])."'");
$count = $sql->sql_result();

if($count==0){
    $dbh->execute("INSERT INTO osint_tags_count SET tags_name = '".addslashes($results['mytag'])."',
                                                        tags_count = '".$results['mycount']."'");
}else{
    $dbh->execute("UPDATE osint_tags_count SET tags_count = '".$results['mycount']."' WHERE
                                                    tags_name ='".addslashes($results['mytag'])."'");
}
