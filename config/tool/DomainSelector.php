<?php

/**
 * AIFS Config Domain Selector
 * @version 1.02
 * @author Vincent Menard 
 */
 

Class General {
 
}
 
Class Config extends General {
    
    var $tmp_path;
    
    function __construct( $domain = 'osint' ) {

        $path = '/var/aifs';
        require_once $path . '/config/config.php';

        $this->global_path = '/var/aifs';
        $this->node_name = $node_name;
        $this->debug = $debug;
        
        switch ($domain) {
            
            case 'osint':
                // Diff Tmp file
                $this->tmp_path = $this->global_path.'/routine/tmp/osint';
                // Buffer size on diff
                $this->diff_buffer_size = 50;
                // Humain to notify on ns fail
                $this->ns_fail_notify = 'aifs-noreply@digitaloversight.com';
                
            break;
            
            case 'dnint':
                // Diff Tmp file
                $this->tmp_path = $this->global_path.'/routine/tmp/dnint';
                
             break;
           
            case 'finint':
            break; 
        }
    }    
 
}
