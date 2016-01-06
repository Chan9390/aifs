<?php

/**
 * AIFS OSINT NS Check
 * @digitaloversight
 */

require_once '../config/tool/DomainSelector.php';

$conf = new Config('dnint');

require_once '../common/sql/Sql.php';
require_once '../common/sql/SqlStatement.php';

$dbh = new SQL_Class("aifs");

$sql = $dbh->execute("SELECT id, url FROM osint_url WHERE dead_link = 0 ORDER BY rand()");

list($id, $url) = $sql->fetch_array();
$url = str_replace('http://', '', $url);
$url = str_replace('https://', '', $url);

$arr = explode('/', $url);
$out = shell_exec("nslookup $arr[0]");
if (strpos($out, '** server') !== false) {
	$dbh->execute("UPDATE osint_url SET dead_link = 1 WHERE id=".$id);
	if (strpos($conf->ns_fail_notify, '@') !=== false) {
		mail($conf->ns_fail_notify, "AIFS NS expired", 
		"The following url had it's domain name expired recently : \r\t\r\t" . $url .
		"\r\t\r\tDelivered you by AIFS node " $conf->node_name );
	}
}
