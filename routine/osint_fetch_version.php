<?php

/**
 * AIFS OSINT Get new remote version
 * @digitaloversight
 */

include_once '../common/tool/DomainSelector.php';
require_once '../config/tool/DomainSelector.php';

include_once '../common/sql/Sql.php';
include_once '../common/sql/SqlStatement.php';
include_once '../common/tool/FetchFopen.php';

$conf = new Config('osint');
$helper = new Common('osint', $conf->global_path);
$fetch = new FetchFopen();
$dbh = new SQL_Class("aifs");

$sql = $dbh->execute("SELECT id, url FROM osint_url WHERE dead_link = 0 ORDER BY rand() LIMIT 1");
list($uid, $url) = $sql->fetch_array();

// fetch lastest version
$sql = $dbh->execute("SELECT size, date
                        FROM osint_version
                        WHERE fk_osint_url_id=".$uid." ORDER BY date desc LIMIT 1");
list($size, $date) = $sql->fetch_array();

if (strpos($date, date("Y").'-'.date("m").'-'.date("d") ) !== false ) {
    die();

}

// fetch online copy
$content = $fetch->getContent($url);


// compare and save
if ( $size != strlen(addslashes($content)))      {

    $dbh->execute("INSERT INTO osint_version SET  fk_osint_url_id = '".$uid."',
                                                                        content ='".addslashes($content)."',
                                                                        size ='".strlen(addslashes($content))."',
                                                                        date = NOW()");

    $sql = $dbh->execute("SELECT tag FROM osint_tags WHERE fk_osint_url_id='".$uid."' LIMIT 1");
    list($tag) = $sql->fetch_array();
    if (strlen($tag) > 1) {
        $sql = $dbh->execute("SELECT COUNT(*) FROM osint_tags_changelog WHERE tag='".addslashes($tag)."'");
        if ($sql->sql_result() == 0) {
            $dbh->execute("INSERT INTO osint_tags_changelog SET tag='".addslashes($tag)."', date=NOW()");
        } else {
            $dbh->execute("UPDATE osint_tags_changelog SET date=NOW() WHERE tag='".addslashes($tag)."'");

        }
    }


    $sql = $dbh->execute("SELECT date FROM osint_version ORDER BY id DESC LIMIT 1");
    list($date) = $sql->fetch_array();
    $sql = $dbh->execute("SELECT fk_aifs_member_id FROM osint_tags_subscribed WHERE fk_osint_url_id = '".$uid."'");

    while ($row = $sql->fetch_assoc()) {

        $mid = $row['fk_aifs_member_id'];
        $s = $dbh->execute("SELECT aifs_members.email FROM aifs_members
                    WHERE aifs_members.id = ".$mid." LIMIT 1");
        list($email) = $s->fetch_array();
        
        include '../common/helper/osint.helper.php';
        
        mailChangeAlert($email, $url, 
              $mydomain .= 'aifs/page.php?id='.$uid.'&date='.$date);

    }
}
