<?php

/**
 * AIFS Finint Crypto Currency 
 * 1.03 PoC for currency information gathering routine.
 * @version 1.03 2017
 * @author Vincent A. Menard 
 * Copyright (c) aifs
 */

error_reporting(1);
ini_set('error_reporting', 1);

require_once '../config/tool/DomainSelector.php';

require_once '../common/sql/Sql.php';
require_once '../common/sql/Statement.php';

$conf = new Config('finint');

require_once '../common/helper/finint.helper.php';
require_once '../common/sql/finint_request.php';

$finint = new finint_request();

$stmt = $finint->getOneCurrency();
//while ($row = $stmt->fetch_assoc())
//    print_r($row);

/* todo: place sources in dnint_url  */
$url = ['https://coinmarketcap.com/currencies/ethereum/'];
$content = file_get_contents($url[0]);

//echo planarExtraction($content, '<span class="text-large" id="quote_price">', '</span>', '$');
//sql insert here
