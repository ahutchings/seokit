<?php

include 'config.inc.php';

$linking_page = mysql_escape_string($_GET["linking_page"]);
$url          = mysql_escape_string($_GET["url"]);
$domain       = mysql_escape_string($_GET["domain"]);

$today = date("Y-m-d");

if (!empty($url)) {

    $incoming_links = Yahoo::get_inlink_count(array('query' => $url));

    if (ctype_digit($incoming_links)) {

        $db->exec("UPDATE urls SET checkdate='$today',links='$incoming_links' WHERE url='$url' LIMIT 1");
        echo "$incoming_links links to $url";
    }
}

if (!empty($linking_page)) {

    $incoming_links = Yahoo::get_inlink_count(array('query' => $linking_page));

    if (ctype_digit($incoming_links)) {

        $db->exec("UPDATE linkdata SET linking_page_inlinks='$incoming_links' WHERE linking_page='$linking_page' LIMIT 1");
        echo "$incoming_links links to $url";
    }
}
