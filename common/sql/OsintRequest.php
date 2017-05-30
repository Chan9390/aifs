<?php

/**
 * AIFS OSINT SQL Requests file
 * @verison 1.03
 * @digitaloversight
 */

namespace Sql;
 
class OsintRequest extends Sql {

    function __construct() {
        parent::__construct("aifs");
    }

    function getContentByDate($date) {

        $date = addslashes($date);
        return $this->execute("SELECT content FROM osint_version
                               WHERE osint_version.date='".$date."' LIMIT 1");

    } //getContentByDate

    function nbUrls() {

        $stmt = $this->execute("SELECT COUNT(url) AS nbUrls FROM osint_url");
        return $stmt;

    }

	function fetchUrlId($from, $to) {

		$from = addslashes($from);
		$to = addslashes($to);

		$stmt = $this->execute("SELECT id, url FROM osint_url WHERE id>=".$from." AND id<=".$from);

		return $stmt;

	}

	function verifyTagUrl($tag, $url) {

		$tag = addslashes($tag);

	    $check = substr($url,0,7);

	    if($check != 'http://')
	      $url='http://'.$url;

		$url = addslashes($url);

		$stmt = $this->execute("SELECT osint_tags.tag,
					       osint_url.url
					       FROM osint_tags
				           LEFT JOIN osint_url ON (osint_url.id = osint_tags.FK_urls_id)
				           WHERE osint_tags.tag = '".$tag."'
					       AND osint_url.url = '".$url."'");


	 if ( mysql_num_rows($stmt->result) > 0 ) {
			return true;
		}
		else {
			return false;
		}

	} // verifyTagUrl

	function getUrlById($id) {

		$id = addslashes($id);

		$stmt = $this->execute("SELECT osint_url.url FROM osint_url WHERE osint_url.id = '".$id."'");

		$row = $stmt->fetch_assoc();

		return $row['url'];

	}
        function verifyUrlId($id) {

                $id = addslashes($id);

                $stmt = $this->execute("SELECT count(*) FROM osint_url WHERE osint_url.id = '".$id."'");

		if ($stmt->sql_result() == 0) {
			return false;

		}
                return true; 

        }

	function getTitleById($id) {

	        $id = addslashes($id);

                $stmt = $this->execute("SELECT osint_titles.title FROM osint_titles
					WHERE osint_titles.FK_urls_id = '".$id."'
					ORDER BY id DESC LIMIT 1");

                $row = $stmt->fetch_assoc();

                return $row['title'];


	}
	function search( $search ) {

		if ($search != '' && $search != '%') {

			$search = addslashes($search);

		 	$s = $this->execute("SELECT DISTINCT osint_tags.tag,
		 					     osint_url.url,
		 					     osint_tags.date
		 			     FROM osint_tags
					     LEFT JOIN osint_url ON (osint_tags.FK_urls_id = osint_url.id)
					     WHERE osint_tags.tag LIKE '%".$search."%'
						OR osint_url.url LIKE '%".$search."%'
					     LIMIT 50");

		} // if

		return $s;

	} // search

	function getUrlsbyTag($tag) {

		$tag = addslashes($tag);

		$s = $this->execute("SELECT 	osint_url.id,
					    	osint_url.url,
					    	osint_version.FK_urls_id,
						(SELECT thumb FROM osint_thumbs
							WHERE osint_thumbs.FK_urls_id = osint_url.id
							order by date_yyyy_mm DESC limit 1) as thumbs,
		 		     		(SELECT added FROM osint_changes
							WHERE osint_changes.FK_urls_id = osint_url.id
							ORDER BY id DESC LIMIT 1) as added,
						(SELECT title FROM osint_titles
							WHERE osint_titles.FK_urls_id = osint_url.id
							ORDER BY id DESC LIMIT 1) as title
					FROM osint_tags
		 		     LEFT JOIN osint_url ON (osint_url.id = osint_tags.FK_urls_id )
				     LEFT JOIN osint_version ON (osint_tags.FK_urls_id = osint_version.FK_urls_id )
				     WHERE osint_tags.tag = '".$tag."'
				     GROUP BY osint_version.FK_urls_id
					ORDER BY osint_url.id DESC");

		return $s;

	} // getUrlsbyTag

	function verifyIfSubscribedToUrl($id) {

		$id = addslashes($id);

		$s = $this->execute("SELECT *
		 		     FROM osint_tags_subscribed
				     WHERE osint_tags_subscribed.fk_aifs_member_id = '".$_SESSION['uid']."'
				     AND osint_tags_subscribed.FK_urls_id= '".$id."'");

		if ( mysql_num_rows($s->result) > 0 ) {
			$result = true;
		}
		else {
			$result = false;
		}

		return $result;

	} //verifyIfSubscribedToUrl


	function verifyTagExist($tag) {

		$tag = addslashes($tag);

		$s = $this->execute("SELECT osint_tags.tag
		 		     FROM osint_tags
				     WHERE osint_tags.tag = '".$tag."'");

		if ( $row = $s->fetch_assoc() ) {
			$result = true;
		}
		else {
			$result = false;
		}

		return $result;

	}

	function getGroupId($tag) {

		$tag = addslashes($tag);

		$s = $this->execute("SELECT osint_tags.FK_group_id
		 						FROM osint_tags
								WHERE osint_tags.tag = '".$tag."'");
		$row = $s->fetch_assoc();

		return $row['FK_group_id'];

	} //getGroupId

	function getVersionsById($id) {

		$id = addslashes($id);

		$s = $this->execute("SELECT osint_version.date
		 		     FROM osint_version
				     WHERE osint_version.FK_urls_id = '".$id."'
				     ORDER BY osint_version.date desc LIMIT 12");

		return $s;

	}

	function getContentbyVersion($id, $date) {

		$id = addslashes($id);
		$date = addslashes($date);

		$s = $this->execute("SELECT osint_version.content,
					    osint_url.url
		 		     FROM osint_version
			             LEFT JOIN osint_url ON (osint_version.FK_urls_id = osint_url.id)
				     WHERE osint_version.FK_urls_id = '".$id."'
				     AND osint_version.date ='".$date."'");

		return $s;

	}

	function getVersionsByIdLimited($id) {

		$id = addslashes($id);

		$s = $this->execute("SELECT osint_version.date
		 		     FROM osint_version
				     WHERE osint_version.FK_urls_id = '".$id."'
				     ORDER BY osint_version.date desc
				     LIMIT 5");

		return $s;

	}

	function ifMemberOfTheTag($tag) {

		$tag = addslashes($tag);

		$groupId = $this->getGroupId($tag);

		$s = $this->execute("SELECT aifs_members_groups.id
		 		     FROM aifs_members_groups
				     WHERE aifs_members_groups.FK_groups_id = '".$groupId."'
				     AND aifs_members_groups.fk_aifs_member_id='".$_SESSION['uid']."'");

		if ( $row = $s->fetch_assoc() ) {

			$res = true;

		}
		else {

			$res = false;

		}

		return $res;

	} //ifOwnerOfTheTag

	function getMyTags() {

		$s = $this->execute("SELECT osint_tags.tag,
					    osint_tags.id,
					    osint_tags.date
		 		     FROM osint_tags
				     LEFT JOIN aifs_members_groups ON (aifs_members_groups.FK_groups_id = osint_tags.FK_group_id)
				     WHERE aifs_members_groups.fk_aifs_member_id = '".$_SESSION['uid']."'
				     GROUP BY osint_tags.tag
				     ORDER BY date desc");

		return $s;

	}

	function getMyUrls() {

		$s = $this->execute("SELECT osint_tags.FK_urls_id
		 		     FROM osint_tags
				     LEFT JOIN aifs_members_groups ON (aifs_members_groups.FK_groups_id = osint_tags.FK_group_id)
				     WHERE aifs_members_groups.fk_aifs_member_id = '".$_SESSION['uid']."'
				     GROUP BY osint_tags.FK_urls_id");

		return $s;

	}

	function deleteSubscribedUrls() {

		$this->execute("DELETE FROM osint_tags_subscribed WHERE fk_aifs_member_id='".$_SESSION['uid']."'");

	}

	function deleteUrlFromTag($id, $tag) {

		$id = addslashes($id);
		$tag = addslashes($tag);

		$s = $this->execute("SELECT osint_tags.id
		 		     FROM osint_tags
				     WHERE osint_tags.tag = '".$tag."'
				     AND osint_tags.FK_urls_id='".$id."'");

		$row = $s->fetch_assoc();
		$tagId = $row['id'];

		//TODO : delete group si vide et delete tout les members_groups si vide

		$this->execute("DELETE FROM osint_tags WHERE FK_urls_id='".$id."' AND tag='".$tag."'");

	}

	function saveSubscribedUrls($array) {

		$query = "INSERT INTO osint_tags_subscribed (FK_urls_id, fk_aifs_member_id) VALUES ";

		foreach ($array as $key => $value) {

			$query .= "(".$key.",".$_SESSION['uid']."),";

		}

		$query = substr($query,0,strlen($query)-1);

		$this->execute($query);

	}

	function ifOwnerOfTheTag($tag) {

		$tag = addslashes($tag);

		$groupId = $this->getGroupId($tag);

		$s = $this->execute("SELECT *
		 		     FROM osint_groups
				     WHERE osint_groups.id = '".$groupId."'
				     AND osint_groups.owner_id='".$_SESSION['uid']."'");

		if ( $row = $s->fetch_assoc() ) {

			$res = true;

		}
		else {

			$res = false;

		}

		return $res;

	} //ifOwnerOfTheTag


	//function added by Rabih
	function getemailowner($groupid){

	$s = $this->execute("SELECT aifs_member.email, aifs_member.username
			             FROM osint_members
			             LEFT JOIN osint_groups ON (osint_groups.owner_id = aifs_member.id)
			             WHERE osint_groups.id = '".$groupid."'");
	return $s;

	}


	function getusernamefromID($userid){

	$s = $this->execute("SELECT aifs_member.username
			             FROM osint_members
			             WHERE aifs_member.id = '".$userid."'");
	return $s;

	} //end of function added by Rabih



	function getMembersOfTag($tag) {

		$tag = addslashes($tag);

		$s = $this->execute("SELECT aifs_member.username
		 		     FROM osint_members
				     LEFT JOIN aifs_members_groups ON (aifs_members_groups.fk_aifs_member_id = aifs_member.id)
				     LEFT JOIN osint_tags ON (osint_tags.FK_group_id = aifs_members_groups.FK_groups_id)
				     WHERE osint_tags.tag = '".$tag."'
				     GROUP BY aifs_member.username");

		return $s;

	}

    function getNumberofURLs($tag){

    	$tag = addslashes($tag);

    	$s = $this->execute("SELECT COUNT(*) FROM osint_tags WHERE tag ='".$tag."'");

        list($countnumber) = $s->fetch_array();

    	return $countnumber;
    }

	function getUsers($word) {

		$word = addslashes($word);

		$s = $this->execute("SELECT aifs_member.username
		 		     FROM osint_members
				     WHERE aifs_member.username LIKE '".$word."%'");

		return $s;

	} //getUsers

	/*Functions added by Rabih */
	
	function getTags($id_tobe_searched) {

		$id_tobe_searched = addslashes($id_tobe_searched);

		$s = $this->execute("SELECT osint_tags.tag
		 		     FROM osint_tags
				     WHERE osint_tags.FK_urls_id LIKE '".$id_tobe_searched."%'");

		return $s;

	} //getTags

	function  get_URL_list($url_tobe_searched){

		$url_tobe_searched= addslashes($url_tobe_searched);

        $s = $this->execute("SELECT osint_url.url
		 		     FROM osint_url
				     WHERE osint_url.url LIKE '%".$url_tobe_searched."%'");

	    return $s;
	}//get_URL_list

	function  get_URL_id($url_fetched_database){

		$url_tobe_searched= addslashes($url_fetched_database);

        $s = $this->execute("SELECT osint_url.id
		 		     FROM osint_url
				     WHERE osint_url.url LIKE '%".$url_tobe_searched."%'");

	    return $s;
	}//get_URL_id

	/* End of functions added by Rabih */

	function getPreviousPage($word) {

		$word = addslashes($word);

		$query = "SELECT osint_tags.tag, MAX(osint_tags.date), COUNT(osint_tags.FK_urls_id) AS nbUrls
			  FROM osint_tags
			  WHERE osint_tags.tag < '".$word."'
			  GROUP BY osint_tags.tag
			  ORDER BY osint_tags.tag desc
	 		  LIMIT 15";

		$s = $this->execute($query);

		while ($row = $s->fetch_assoc()) {

			$lastTag = $row['tag'];

		}

		return $lastTag;

	}

	function getLastTag() {

		$query = "SELECT osint_tags.tag
			  FROM osint_tags
			  GROUP BY osint_tags.tag
			  ORDER BY osint_tags.tag desc
			  LIMIT 1";

		$s = $this->execute($query);

		$row = $s->fetch_assoc();

		return $row['tag'];

	}

	function getFirstTag() {

		$query = "SELECT osint_tags.tag
			  FROM osint_tags
			  GROUP BY osint_tags.tag
			  ORDER BY osint_tags.tag
			  LIMIT 1";

		$s = $this->execute($query);

		$row = $s->fetch_assoc();

		return $row['tag'];

	}

	function getPreviousPageUrl($word) {

		$word = addslashes($word);

		$query = "SELECT osint_url.url
			  FROM osint_url
			  WHERE osint_url.url < '".$word."'
			  ORDER BY osint_url.url desc
	 		  LIMIT 15";

		$s = $this->execute($query);

		while ($row = $s->fetch_assoc()) {

			$lastUrl = $row['url'];

		}

		return $lastUrl;

	}

	function getFirstUrl() {

		$query = "SELECT osint_url.url
			  FROM osint_url
			  ORDER BY osint_url.url
			  LIMIT 1";

		$s = $this->execute($query);

		$row = $s->fetch_assoc();

		return $row['url'];

	}

	function getLastUrl() {

		$query = "SELECT osint_url.url
			  FROM osint_url
			  ORDER BY osint_url.url desc
			  LIMIT 1";

		$s = $this->execute($query);

		$row = $s->fetch_assoc();

		return $row['url'];

	}

	function userExist($user) {

		$user = addslashes($user);

		$s = $this->execute("SELECT *
		 		     FROM osint_members
				     WHERE aifs_member.username = '".$user."'");

		if ( $row = $s->fetch_assoc() ) {

			$res = true;

		}
		else {
			$res = false;
		}
		return $res;

	}

	function userHaveAccess($tag, $user) {

		$tag = addslashes($tag);
		$user = addslashes($user);

		$s = $this->execute("SELECT osint_tags.FK_group_id
		 		     FROM osint_tags
				     WHERE osint_tags.tag = '".$tag."'");

		$row = $s->fetch_assoc();
		$groupId = $row['FK_group_id'];

		$s = $this->execute("SELECT aifs_member.id
				    FROM osint_members
				    WHERE aifs_member.username = '".$user."'");

		$row = $s->fetch_assoc();
		$userId = $row['id'];

		$s = $this->execute("SELECT *
		 		     FROM aifs_members_groups
				     WHERE aifs_members_groups.FK_groups_id = '".$groupId."'
				     AND aifs_members_groups.fk_aifs_member_id ='".$userId."'");

		if ( $row = $s->fetch_assoc() ) {
			$res = true;
		}
		else {
			$res = false;
		}
		return $res;

	}

	function addUser($tag, $user) {


		$tag = addslashes($tag);
		$user = addslashes($user);

		$s = $this->execute("SELECT osint_tags.FK_group_id
		 		     FROM osint_tags
				     WHERE osint_tags.tag = '".$tag."'");

		$row = $s->fetch_assoc();
		$groupId = $row['FK_group_id'];

		$s = $this->execute("SELECT aifs_member.id
				    FROM osint_members
				    WHERE aifs_member.username = '".$user."'");

		$row = $s->fetch_assoc();
		$userId = $row['id'];

		$s = $this->execute("INSERT INTO aifs_members_groups SET
				     aifs_members_groups.FK_groups_id = '".$groupId."',
				     aifs_members_groups.fk_aifs_member_id ='".$userId."'");



	}

	function deleteMemberFromGroup($user, $tag) {

		$user = addslashes($user);
		$tag = addslashes($tag);

		$s = $this->execute("SELECT osint_tags.FK_group_id
		 		     FROM osint_tags
				     WHERE osint_tags.tag = '".$tag."'");

		$row = $s->fetch_assoc();
		$groupId = $row['FK_group_id'];

		$s = $this->execute("SELECT aifs_member.id
				    FROM osint_members
				    WHERE aifs_member.username = '".$user."'");

		$row = $s->fetch_assoc();
		$userId = $row['id'];

		$s = $this->execute("INSERT INTO aifs_members_groups SET
				     aifs_members_groups.FK_groups_id = '".$groupId."',
				     aifs_members_groups.fk_aifs_member_id ='".$userId."'");

		//TODO : delete group si vide et delete tout les members_groups si vide

		$this->execute("DELETE FROM aifs_members_groups WHERE fk_aifs_member_id='".$userId."' AND FK_groups_id='".$groupId."'");

	}

	function getEmail( $user ) {

		$user = addslashes($user);

		$s = $this->execute("SELECT email FROM aifs_members WHERE username='".$user."'");
		list($email) = $s->fetch_array();
		return $email;

	}

	function logging() {

		if ($_SERVER['QUERY_STRING'] != '') {
			$str = $_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'];
		} else {
			$str = $_SERVER['PHP_SELF'];
		}

		$this->execute("INSERT INTO osint_logs SET  ip='".getenv("REMOTE_ADDR")."',
														url='".$str."',
														date_yyyy = '".date("Y")."',
														date_mm = '".date("m")."',
														date_dd = '".date("d")."'");

	} //log

	function freqChange($uid) {

 	       $s = $this->execute("SELECT COUNT(*) FROM osint_version WHERE FK_urls_id=".$uid);
        	$count = $s->sql_result();

 	       $s = $this->execute("SELECT date FROM osint_version WHERE FK_urls_id =".$uid." ORDER BY date desc");
        	list($d1) = $s->fetch_array();

 	       $s = $this->execute("SELECT date FROM osint_version WHERE FK_urls_id =".$uid." ORDER BY date asc");
        	list($d2) = $s->fetch_array();

		$arr = explode(" ", $d1);
		$arr = explode("-", $arr[0]);
		$unix1 = mktime(0, 0, 0, $arr[1], $arr[2], $arr[0]);


		$arr = explode(" ", $d2);
        	$arr = explode("-", $arr[0]);
        	$unix2 = mktime(0, 0, 0, $arr[1], $arr[2], $arr[0]);

		$timecrawled =  round(($unix1 - $unix2 )/86400);
	return array(0=>$count, 1=>$timecrawled);
	}


	function getFriendsByUser( $user ) {

		$stmt = $this->execute("SELECT DISTINCT aifs_member.id, aifs_member.username
				FROM osint_members
				LEFT JOIN aifs_members_groups ON (aifs_member.id = aifs_members_groups.fk_aifs_member_id)
				WHERE aifs_members_groups.FK_groups_id IN
					(
					select aifs_members_groups.FK_groups_id
					FROM aifs_members_groups
					LEFT JOIN aifs_members ON (aifs_members_groups.fk_aifs_member_id = aifs_member.id)
					WHERE aifs_member.username='".addslashes($user)."'
					)
				AND aifs_member.username != '".addslashes($user)."'
				ORDER BY aifs_members_groups.id LIMIT 400");
		return $stmt;
	}

	function getGooglePageRank($id) {
		$stmt = $this->execute('SELECT rank FROM osint_pagerank where fk_url_id='.addslashes($id).' ORDER BY id DESC LIMIT 1');
		list($gpr) = $stmt->fetch_array();
		if(!$gpr) $gpr = 'N-A';
		return $gpr;
	}
}
