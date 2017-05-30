<?php

/**
 * AIFS Response 
 * 1.03 Component designed to send json responses.
 * @version 1.03 2017
 * @author Vincent A. Menard
 * Copyright (c) aifs, digitaloversight
 */

namespace Component;

class Response {

    var $data;
    var $node;
    var $tracking;

    function __construct( $node=false ) {

        $this->node = $node;
        $this->data = [];
        $this->tracking = false;
    }

    public function success($code, $msg, $exit = true) {

        $this->data['success'] = [ 'code'=> $code, 'message'=> $msg ];
        return $this->jsonResponse($exit);
    }

    public function error( $code, $msg) {
        
        if ($code > 500000) {
            header('HTTP/1.1 500 Internal Server Error');
        }
        $this->data = ['error' =>[ 'code' => $code, 'message' => $msg]];
        if ($this->node) {
            $this->data['error']['node'] = $this->node;
        }

        if ($this->tracking) {
            $this->data['error']['tracking'] = $this->tracking;
        }

        return $this->jsonResponse();
    }


    public function setData() {

    }

    public function setLogLevel() {

    }

    private function jsonResponse( $exit = true ) {

        echo json_encode( $this->data );
        if ($exit) {
            exit;
        }
    }

    private function curlResponse() {

    }
}
