<?php

/**
 * AIFS Common Domain Selector
 * @digitaloversight
 */
 
 Class Common {
    
    
    function __construct( $domain = 'osint', $path ) {
        require_once $path.'/common/helper/'.$domain.'.helper.php';
    }
 
 }