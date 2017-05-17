<?php

/**
 * AIFS FININT SQL Requests file
 * @version 1.03 2017
 * @author Vincent Menard
 * @digitaloversight
 */
 
class finint_request extends SQL_Class {

    function __construct() {
	parent::SQL_Class("aifs");
    } //consctructor

    /**
     * Get information and a few the sources for one currency
     */

    function getOneCurrency($crpt = true) {
        return $this->execute("SELECT finint_currency.id, name, ticker FROM finint_currency
                                   INNER JOIN finint_currency_source 
                                       ON (finint_currency_source.fk_finint_currency_id = finint_currency.id)
				       ORDER BY rand() LIMIT 3");
    } // getOneCurrency

    function saveCurrentValue( $c_id, $ref_id, $ticker, $dValue ) {

	$stmt = $this->execute("INSERT INTO finint_currency_value 
                                   SET value='".addslashes($dValue)."', 
                                   fk_finint_currency_ticker_id=".$c_addy.",
                                   fk_finint_currency_ref_id=".$ref_id."
              FROM osint_url");
	return $stmt;
    }
}
