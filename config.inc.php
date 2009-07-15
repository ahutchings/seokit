<?php

// replace username, password and database-name with your details

$mainconnection =MYSQL_CONNECT("localhost","seokit","seokit") OR DIE("Unable to connect to database");

@mysql_select_db("seokit") or die("Unable to select the database");

// api key available from http://developer.yahoo.com/wsregapp/index.php
// more details here http://developer.yahoo.com/search/

$yahoo_api_key="xxxxxxxxx";
$scriptlocation="http://www.site.com/linkanalysis";

?>
