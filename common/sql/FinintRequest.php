<?php

/**
 * AIFS FININT SQL Requests file
 * @version 1.03 2017
 * @author Vincent Menard
 * @digitaloversight
 */

namespace Sql;

class FinintRequest extends Sql {

    function __construct() {
	parent::__construct("aifs");
    }

    /**
     * Get information and a few the sources for one currency
     */

    function getOneCurrency($crpt = true) {
        return $this->execute("SELECT finint_currency.id, name, ticker, dnint_url.url, dnint_url.id as dnint_id FROM finint_currency
                                   INNER JOIN finint_currency_source 
                                       ON (finint_currency_source.fk_finint_currency_id = finint_currency.id)
                                   LEFT JOIN dnint_url
                                       ON (finint_currency_source.fk_dnint_url_id = dnint_url.id)
				       ORDER BY rand() LIMIT 3");
    } // getOneCurrency

    function saveCurrentValue( $c_id, $ref_id, $dnint_id, $dValue ) {

        $stmt = $this->execute("INSERT INTO finint_currency_value 
                                   SET value='".addslashes($dValue)."', 
                                   fk_finint_currency_id=".$c_id.",
                                   fk_finint_currency_ref_id=".$ref_id.",
                                   fk_dnint_url_id=".$dnint_id);
        return $stmt;
    }
}
