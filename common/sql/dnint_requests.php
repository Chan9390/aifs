<?php

/**
 * AIFS DNINT SQL Requests file
 * @digitaloversight
 */
 
class dnint_requests extends SQL_Class {

	private $id;
	private $page;
	private $url;
	
	function dnint_requests() {
		parent::SQL_Class("aifs");

		$s = $this->execute("SELECT id, fk_osint_url_id, content FROM osint_version ORDER BY id desc LIMIT 1");
		list($id, $url, $data) = $s->fetch_array();
		$this->id = $id;
		$this->page = $data;
		$this->url = $url;
	}

	function get_outbound( ) {
		if ($this->id == null)
			die();
		
		$s = $this->execute("SELECT count(*) FROM dnint_outbound WHERE fk_osint_version_id=".$this->id);
		
		$count = $s->sql_result();
		if ($count != 0)
			die("");
		$data = $this->page;
		$data = stripslashes( $data );
		$data = ereg_replace("<a href=\"", "++link++", $data);
		$data = ereg_replace("\">", "++link++", $data);
		$data = ereg_replace("\" ", "++link++", $data);
		$arr = explode("++link++", $data);
	
		$trim = ereg_replace("http://", "",  $this->url);
		$url_arr = explode("/", $trim);
	
		for ($i=0; $i<sizeof($arr); $i++) {
			if ((strpos($arr[$i], "http:") === 0) && (strpos($arr[$i], " ") === false) &&
				(strpos($arr[$i], $url_arr[0]) === false)) 
			{	
				$this->execute("INSERT INTO dnint_outbound SET 	url='".addslashes(html_entity_decode ($arr[$i]))."', 
											fk_osint_version_id=".$this->id);
			}

		}


	}

}
