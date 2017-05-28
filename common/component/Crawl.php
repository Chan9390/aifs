<?php

/**
 * AIFS OSINT Fetch Class by using Fopen
 * @version 1.03
 * Copyright (c) digitaloversight
 */

namespace Common;

use Sql\Sql;
 
class Crawl extends Sql {

    var $domain;
    
    function __construct( $domain = 'osint' ) {
        parent::__construct("aifs");
        $this->domain = $domain;
    }

    /* getContent
     *
     */

    function getContent( $url = false, $name = false, $newGroup = false, $addToDb = false) {

        $contents = '';
            if ($url != false) {

            $url = addslashes($url);

            $f = 1;
            $c = 2; //1 for header, 2 for body, 3 for both
            $a = NULL;
            $cf = NULL;
            $pd = NULL;
            $contents = $this->open_page($url,$f,$c,$a,$cf,$pd);

        }

        if ($addToDb) {

            $this->addToDb($url, $name, $newGroup, $contents);
        }

        return $contents;
    } // fetch

    function addToDb($url, $name, $newGroup, $contents) {

        $url = addslashes($url);
        $url = str_replace("http://","",$url);
        $url = 'http://'.$url;
        $name = addslashes($name);
        $newGroup = addslashes($newGroup);
        $contents = addslashes($contents);
        $pattern ='utf-8';

        if (!strstr($contents,$pattern)) {
           $contents = utf8_encode ($contents);
        }
        if ($contents != '' && $name != false ) {

            if ($newGroup) {

                $s = $this->execute("INSERT INTO osint_groups SET owner_id='".$_SESSION['uid']."'");

                $groupId = mysql_insert_id();

                $this->execute("INSERT INTO osint_members_groups SET fk_osint_groups_id = '".$_SESSION['uid']."',
                                             fk_osint_groups_id = '".$groupId."'");

            }
            else {

                $s = $this->execute("SELECT osint_tags.fk_osint_groups_id
                             FROM osint_tags
                             WHERE osint_tags.tag = '".$name."'");
                $row = $s->fetch_assoc();
                $groupId = $row['fk_osint_groups_id'];
            }

            $stmt = $this->execute("SELECT osint_url.id
                        FROM osint_url
                        WHERE url='".$url."'");

            if ( $row = $stmt->fetch_assoc() ) {
                $urlId = $row['id'];
            }
            else {

                $this->execute("INSERT INTO osint_url SET url ='".$url."'");

                $s = $this->execute("SELECT osint_url.id
                             FROM osint_url
                             WHERE url='".$url."'");

                        $row = $s->fetch_assoc();
                $urlId = $row['id'];
            }

            $this->execute("INSERT INTO osint_tags SET  tag='".$name."',
                                                            FK_urls_id='".$urlId."',
                                                            fk_osint_groups_id='".$groupId."',
                                                            date= NOW()");

            $this->execute("INSERT INTO osint_version SET  fk_osint_url_id = '".$urlId."',
                                            content ='".$contents."',
                                            size ='".strlen($contents)."',
                                            date = NOW()");

            $this->execute("INSERT INTO osint_members_urls SET FK_urls_id ='".$urlId."',
                                    fk_aifs_member_id='".$_SESSION['uid']."'");
        }

    }

    function fetchVersion($url, $id) {

        $url = addslashes($url);
        $id = addslashes($id);

        $f = 1;
        $c = 2; //1 for header, 2 for body, 3 for both
        $a = NULL;
        $cf = NULL;
        $pd = NULL;
        $contents = $this->open_page($url,$f,$c,$a,$cf,$pd);

        $contents = addslashes($contents);

        $this->execute("INSERT INTO osint_version SET  osint_url_id = '".$id."',
                                                                content ='".$contents."',
                                                                size ='".strlen($contents)."',
                                                                date = NOW()");

    } // fetchVersion

    function open_page($url,$f=1,$c=2,$a=0,$cf=0,$pd="", $urlId=0, $dbh=false){
        global $oldheader;
        $url = str_replace('http://','',str_replace('https://','',$url));
        if (preg_match("#/#","$url")){
            $page = $url;
            $url = @explode("/",$url);
            $url = $url[0];
            $page = str_replace($url,"",$page);
            if (!$page || $page == ""){
                $page = "/";
            }
            $ip = gethostbyname($url);
        }
        else{
            $ip = gethostbyname($url);
            $page = "/";
        }
        $open = fsockopen($ip, 80, $errno, $errstr, 60);
        if ($pd){
            $send = "POST $page HTTP/1.0\r\n";
        }
        else {
            $send = "GET $page HTTP/1.0\r\n";
        }
        $send .= "Host: $url\r\n";
        $send .= "Referer: http://www.digitaloversight.com/developer/aifs\r\n";
        if ($cf){
            if (@file_exists($cf)){
                $cookie = urldecode(@file_get_contents($cf));
                if ($cookie){
                    $send .= "Cookie: $cookie\r\n";
                    $add = @fopen($cf,'w');
                    fwrite($add,"");
                    fclose($add);
                   }
              }
         }
         $send .= "Accept-Language: en-us, en;q=0.50\r\n";
         if ($a){
              $send .= "User-Agent: $a\r\n";
         }
         else{
              $send .= "User-Agent: {$_SERVER['HTTP_USER_AGENT']}\r\n";
         }
         if ($pd){
              $send .= "Content-Type: application/x-www-form-urlencoded\r\n";
              $send .= "Content-Length: " .strlen($pd) ."\r\n\r\n";
              $send .= $pd;
         }
         else{
             $send .= "Connection: Close\r\n\r\n";
         }
         fputs($open, $send);
         $seeding = 0;
         $return = '';
         while (!feof($open)) {
             if ($open === false) {
                die('Invalid network resource.');
             } 
             if ($seeding < 5000) {
                 $return .= fgets($open, 4096);
             }
             else {
                if ($dbh) {
                    $dbh->execute("INSERT INTO osint_deadLinks set 
                            dateFound=NOW(),
                            url_id='".$urlId."',
                            retry1=0,
                            retry2=0,
                            retry3=0,
                            retry4=0,
                            retry5=0");
                    $dbh->execute("UPDATE osint_url SET dead_link=1 WHERE id='".$urlId."'");
                }    
                die();
                }
            $seeding++;
         }
         fclose($open);
         $return = @explode("\r\n\r\n",$return,2);
         $header = $return[0];
         if ($cf){
              if (preg_match("/Set\-Cookie\: /i","$header")){
                   $cookie = @explode("Set-Cookie: ",$header,2);
                   $cookie = $cookie[1];
                   $cookie = explode("\r",$cookie);
                   $cookie = $cookie[0];
                   $cookie = str_replace("path=/","",$cookie[0]);
                   $add = @fopen($cf,'a');
                   fwrite($add,$cookie,strlen($read));
                   fclose($add);
              }
         }
         if ($oldheader){
              $header = "$oldheader<br /><br />\n$header";
         }
         $header = str_replace("\n","<br />",$header);
         if ($return[1]){
              $body = $return[1];
         }
         else{
              $body = "";
         }
         if ($c === 2){
              if ($body){
                   $return = $body;
              }
              else{
                   $return = $header;
              }
         }
         if ($c === 1){
              $return = $header;
         }
         if ($c === 3){
              $return = "$header$body";
         }
         if ($f){
              if (preg_match("/Location\:/","$header")){
                   $url = @explode("Location: ",$header);
                   $url = $url[1];
                   $url = @explode("\r",$url);
                   $url = $url[0];
                   $oldheader = str_replace("\r\n\r\n","",$header);
                   $l = "&#76&#111&#99&#97&#116&#105&#111&#110&#58";
                   $oldheader = str_replace("Location:",$l,$oldheader);
                   return $this->open_page($url,$f,$c,$a,$cf,$pd);
              }
              else{
                   return $return;
             }
         }
         else{
              return $return;
         }
    } // OpenPage
}
