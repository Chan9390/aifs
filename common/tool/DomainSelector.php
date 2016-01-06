<?php

/**
 * AIFS Common Domain Selector
 * @digitaloversight
 */
 
 Class Common {
	
	
    __construct( $domain = 'osint', $path ) {
		require_once $path.'/common/helper/'.$domain.'.helper.php';
    }	
 
 }