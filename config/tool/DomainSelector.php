<?php

/**
 * AIFS Config Domain Selector
 * @digitaloversight
 */
 
$path = '/var/www/aifs';
require_once = $path . '/config/aifs_config.php';

Class General {
 
}
 
Class Config extends General {
    
    var tmp_path;
    
    __construct( $domain = 'osint' ) {
    
        
        $this->global_path = '/var/www/aifs';
        $this->node_name = $node_name;
        
        switch ($domain) {
            
            case 'osint':
                // Diff Tmp file
                $this->tmp_path = $this->global_path.'/routine/tmp_osint';
                // Buffer size on diff
                $this->diff_buffer_size = 50;
                // Humain to notify on ns fail
                $this->ns_fail_notify = 'aifs-noreply@digitaloversight.com';
                
            break;
            
        }
    }    
 
}