<?php

/**
 * AIFS OSINT Get outbound routine
 * Copyright (c) digitaloversight
 */
 
error_reporting(1); 
ini_set('error_reporting', 1);

require_once '../config/tool/DomainSelector.php';
require_once '../common/tool/DomainSelector.php';

require_once '../common/sql/Sql.php';
require_once '../common/sql/SqlStatement.php';

$conf = new Config('osint');
$helper = new Common('osint', $conf->global_path);

require_once '../common/sql/dnint_requests.php';

$dnint = new dnint_requests();
$dnint->get_outbound();
